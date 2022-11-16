<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    /**
     * Определяет, есть ли права у пользователя на этот запрос
     *
     * @return bool
     */
    public function authorize()
    {
        return TRUE;
    }

    /**
     * Возвращает массив правил для проверки полей формы
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        // Можно объединить с UpdatePostRequest
        // 1. $rules = [...]
        // 2. if ($this->isMethod('PATCH')) {
        //     $rules['title'] = 'required|min:3|max:100';
        // }
        // 3. return $rules

        return [
            'title' => 'required|unique:posts,title|min:3|max:100',
            'excerpt' => 'required|min:100|max:200',
            'body' => 'required',
            'img' => 'nullable|img|mimes:jpeg,bmp,png|max:5000',
        ];

    }

    /**
     * Возвращает массив сообщений об ошибках для заданных правил
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'required' => 'Поле «:attribute» обязательно для заполнения',
            'unique' => 'Такое значение поля «:attribute» уже используется',
            'min' => [
                'string' => 'Поле «:attribute» должно быть не меньше :min символов',
                'file' => 'Файл «:attribute» должен быть не меньше :min Кбайт'
            ],
            'max' => [
                'string' => 'Поле «:attribute» должно быть не больше :max символов',
                'file' => 'Файл «:attribute» должен быть не больше :max Кбайт'
            ],
            'mimes' => 'Файл «:attribute» должен иметь формат :values',
            'img' => 'Недопустимый формат картинки',
        ];
    }

    /**
     * Возвращает массив дружественных пользователю названий полей
     *
     * @return array
     */
    public function attributes() {
        return [
            'title' => 'Заголовок',
            'excerpt' => 'Анонс поста',
            'body' => 'Текст поста',
            'img' => 'Изображение',
        ];
    }
}
