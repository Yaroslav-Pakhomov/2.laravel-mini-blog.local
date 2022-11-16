<?php

declare(strict_types=1);

namespace App\Models;

use App\Http\Requests\Post\SearchPostRequest;
use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

/**
 * @property int|mixed    $user_id
 * @property mixed        $title
 * @property mixed        $excerpt
 * @property mixed        $body
 * @property mixed        $id
 * @property mixed|string $thumbnail
 * @property mixed|string $img
 * @property mixed|string $image
 */
class Post extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $table = 'posts';

    protected $guarded = [];

    /**
     * Посты блога с сортировкой по убыванию даты обновления и с пагинацией по 6
     *
     * @return LengthAwarePaginator
     */
    public static function posts(): LengthAwarePaginator
    {
        return self::query()->select('posts.*')
            ->orderBy('posts.updated_at', 'desc')
            ->paginate(6);
    }

    /**
     * Возвращает пользователя из таблицы users
     *
     * @return BelongsTo
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Сохраняет новый пост в таблице
     *
     * @param StorePostRequest $request
     * @param                  $validated
     * @return void
     */
    public static function storePost(StorePostRequest $request, $validated): void
    {
        $post = new Post();
        // try {
        //     $post->user_id = random_int(1, 4);
        // } catch (Exception) {
        // }
        $post->user_id = auth()->id();
        $post->title = $validated['title'];
        $post->excerpt = $validated['excerpt'];
        $post->body = $validated['body'];
        if (!empty($validated['img'])) {
            self::uploadImage($request, $post);
        }
        $post->save();
    }

    /**
     * Обновляет существующий пост в таблице
     *
     * @param UpdatePostRequest $request
     * @param Post              $post
     * @param                   $validated
     * @return void
     */
    public static function updatePost(UpdatePostRequest $request, Post $post, $validated): void
    {
        $post->title = $validated['title'];
        $post->excerpt = $validated['excerpt'];
        $post->body = $validated['body'];
        // если надо удалить старое изображение
        if (!empty($validated['remove'])) {
            self::removeImage($post);
        }
        self::uploadImage($request, $post);
        $post->update();
    }

    /**
     * Обновляет поля 'img' и 'thumbnail' и 'мягко' удаляет существующий пост в таблице
     *
     * @param Post $post
     * @return void
     */
    public static function deletePost(Post $post): void
    {
        self::removeImage($post);
        $post->image = NULL;
        $post->thumbnail = NULL;
        $post->update();
        $post->delete();
    }

    /**
     * Поиск по таблице posts
     *
     * @param SearchPostRequest $request
     * @return Factory|View|LengthAwarePaginator|Application
     */
    public static function search(SearchPostRequest $request): Factory|View|LengthAwarePaginator|Application
    {
        $validated = $request->validated();
        $search = $validated['search'];
        // обрезаем слишком длинный запрос
        $search = iconv_substr($search, 0, 64);
        // удаляем все, кроме букв и цифр
        $search = preg_replace('#[^0-9a-zA-ZА-яёЁ]#u', ' ', $search);
        // сжимаем двойные пробелы
        $search = preg_replace('#\s+#u', ' ', $search);
        if (empty($search)) {
            return view('posts.search');
        }

        return self::query()->select('posts.*', 'users.name as author')
            ->join('users', 'posts.user_id', '=', 'users.id')
            ->where('posts.title', 'like', '%' . $search . '%') // поиск по заголовку поста
            ->orWhere('posts.excerpt', 'like', '%' . $search . '%') // поиск по тексту поста
            ->orWhere('posts.body', 'like', '%' . $search . '%') // поиск по тексту поста
            ->orWhere('users.name', 'like', '%' . $search . '%') // поиск по автору поста
            ->orderBy('posts.updated_at', 'desc')
            ->paginate(4)
            ->appends([ 'search' => $search ]);
    }

    /**
     * Загрузка изображения при создании поста
     *
     * @param      $request
     * @param Post $post
     * @return void
     */
    public static function uploadImage($request, Post $post): void
    {
        // если было загружено новое изображение
        $orig_img = $request->file('image');
        if ($orig_img) {
            // уникальное имя файла
            $name = md5(Carbon::now() . '_' . $orig_img->getClientOriginalName());
            // расширение файла
            $ext = $orig_img->getClientOriginalExtension();

            // сохраним его в storage/images/original
            Storage::putFileAs('public/images/original', $orig_img, $name . '.' . $ext);

            //---------------------------------------------
            // Основное изображение - начало
            // Размер 1200x400
            //---------------------------------------------
            // создаем jpg изображение для списка постов блога размером 1200x400, качество 100%
            $image = self::setResizeImage($orig_img, 1200, 400);
            // сохраняем это изображение под именем $name.jpg в директории public/images/images
            Storage::put('public/images/images/' . $name . '.jpg', $image);
            $image->destroy();
            // записываем путь в БД
            $post->image = Storage::url('public/images/images/' . $name . '.jpg');
            //---------------------------------------------
            // Основное изображение - конец
            //---------------------------------------------

            //---------------------------------------------
            // Анонс изображение - начало
            // Размер 600x200
            //---------------------------------------------
            // создаем jpg изображение для списка постов блога размером 600x200, качество 100%
            $thumbnail = self::setResizeImage($orig_img, 600, 200);
            // сохраняем это изображение под именем $name.jpg в директории public/img/thumb
            Storage::put('public/images/thumbnails/' . $name . '.jpg', $thumbnail);
            $thumbnail->destroy();
            // записываем путь в БД
            $post->thumbnail = Storage::url('public/images/thumbnails/' . $name . '.jpg');
            //---------------------------------------------
            // Анонс изображение - конец
            //---------------------------------------------

            // $post->thumbnail = Storage::url('public/images/original/' . $name . '.' . $ext);
            // $post->img = Storage::url('public/images/original/' . $name . '.' . $ext);
        }
    }

    /**
     * Создаёт jpg изображения заданных размеров
     *
     * @param object $orig_img
     * @param int    $width
     * @param int    $height
     * @return \Intervention\Image\Image
     */
    public static function setResizeImage(object $orig_img, int $width, int $height): \Intervention\Image\Image
    {
        $image = Image::make($orig_img);
        $resizeHeight = $image->height();
        $resizeWidth = $image->width();

        // Размер width x height
        if ($resizeWidth > $width && $resizeHeight > $height) {
            // Изменяет размер изображения так, чтобы самая большая сторона соответствовала ограничению; меньшая сторона будет масштабирована для сохранения исходного соотношения сторон
            $image->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        } else {
            // Изменяет размер границ текущего изображения на заданную ширину и высоту, фоновый цвет чёрный
            $image->resizeCanvas($width, $height, 'center', FALSE, '000000');
        }

        // Кодируем текущее изображение в jpg, качество 100%
        return $image->encode('jpg', 100);
    }

    /**
     * Удаление изображения при обновлении и удалении поста
     *
     * @param Post $post
     * @return void
     */
    public static function removeImage(Post $post): void
    {
        // Основное изображение
        if (!empty($post->image)) {
            $name = basename($post->image);
            if (Storage::exists('public/images/images/' . $name)) {
                Storage::delete('public/images/images/' . $name);
            }
            $post->image = NULL;
        }
        // Анонс-изображение
        if (!empty($post->thumbnail)) {
            $name = basename($post->thumbnail);
            if (Storage::exists('public/images/thumbnails/' . $name)) {
                Storage::delete('public/images/thumbnails/' . $name);
            }
            $post->thumbnail = NULL;
        }

        if (!empty($name)) {
            // Все изображения директорий
            // $images = Storage::files('public/images/original');
            $images = Storage::files('public/images');

            // берём имя файла без расширения
            $base = pathinfo($name, PATHINFO_FILENAME);

            foreach ($images as $img) {
                $temp = pathinfo($img, PATHINFO_FILENAME);
                if ($temp === $base) {
                    Storage::delete($img);
                    break;
                }
            }
        }
    }

    /**
     * Проверяет права пользователя на редактирование и удаление поста либо автор поста, либо админ
     *
     * @param Post $post
     * @return bool
     */
    public static function checkRights(Post $post): bool
    {
        return auth()->id() === $post->user_id || auth()->id() === 1;
    }
}
