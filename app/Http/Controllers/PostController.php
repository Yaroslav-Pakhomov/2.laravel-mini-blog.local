<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Post\SearchPostRequest;
use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

use function compact;
use function redirect;

class PostController extends Controller
{
    public function __construct()
    {
        // не аутентифицированные пользователи могут только просматривать
        $this->middleware('auth')->except('index', 'show', 'search');
    }

    /**
     * Отображение списка постов.
     *
     * @return View
     */
    public function index(): View
    {
        $posts = Post::posts();

        return view('posts.index', compact('posts'));
    }

    /**
     * Отображение указанного поста.
     *
     * @param Post $post
     * @return View
     */
    public function show(Post $post): View
    {
        return \view('posts.show', compact('post'));
    }

    /**
     * Показывает форму для создания нового поста.
     *
     * @return View
     */
    public function create(): View
    {
        return view('posts.create');
    }

    /**
     * Сохраняет вновь созданный пост в хранилище.
     *
     * @param StorePostRequest $request
     * @return RedirectResponse
     */
    public function store(StorePostRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        Post::storePost($request, $validated);

        return redirect()->route('post.index')->with('success', 'Новый пост успешно создан');
    }

    /**
     * Показывает форму для редактирования указанного поста.
     *
     * @param Post $post
     * @return View|RedirectResponse
     */
    public function edit(Post $post): View|RedirectResponse
    {
        if (!Post::checkRights($post)) {
            return redirect()->route('index')->withErrors('Вы можете редактировать только свои посты');
        }
        return \view('posts.edit', compact('post'));
    }

    /**
     * Обновляет указанный пост в хранилище.
     *
     * @param UpdatePostRequest $request
     * @param Post              $post
     * @return RedirectResponse
     */
    public function update(UpdatePostRequest $request, Post $post): RedirectResponse
    {
        $validated = $request->validated();
        if (!Post::checkRights($post)) {
            return redirect()->route('index')->withErrors('Вы можете редактировать только свои посты');
        }
        Post::updatePost($request, $post, $validated);

        return redirect()->route('post.show', [ $post->id ])->with('success', 'Пост успешно отредактирован');
    }

    /**
     * Удаляет указанный пост из хранилища.
     *
     * @param Post $post
     * @return RedirectResponse
     */
    public function delete(Post $post): RedirectResponse
    {
        if (Post::checkRights($post)) {
            return redirect()->route('index')->withErrors('Вы можете редактировать только свои посты');
        }
        Post::deletePost($post);

        return redirect()->route('post.index')->with('success', 'Пост успешно удалён');
    }

    /**
     * Поиск по блогу
     *
     * @param SearchPostRequest $request
     * @return View
     */
    public function search(SearchPostRequest $request): View
    {
        $posts = Post::search($request);

        return view('posts.search', compact('posts'));
    }

}
