<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePostRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return TRUE;
    }

    /**
     * Возвращает массив правил для проверки полей формы
     *
     * @return array
     */
    public function rules() {
        return [
            'title' => ['required', Rule::unique('posts', 'title')->ignore($this->post->id), 'min:3', 'max:100'],
            'excerpt' => 'required|min:100|max:200',
            'body' => 'required',
            'img' => 'nullable|img|mimes:jpeg,jpg,png|max:5000',
            'remove' => 'nullable|string',
        ];
    }

    /**
     * Возвращает массив сообщений об ошибках для заданных правил
     *
     * @return array
     */
    public function messages(): array {
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
