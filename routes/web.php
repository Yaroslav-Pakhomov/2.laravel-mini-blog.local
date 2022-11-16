<?php

declare(strict_types=1);

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/test', static function () {
});


// ----------------------
// Мини Блог - начало
// ----------------------

// Посты
Route::controller(PostController::class)->group(function () {
    // Главная с постами
    Route::get('/', 'index')->name('index');

    Route::name('post.')->group(function () {
        Route::prefix('posts')->group(function () {
            // Главная с разделами
            Route::get('/', 'index')->name('index');

            // Поиск по блогу
            Route::get('/search', 'search')->name('search');

            // Создание поста
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');

            // Просмотр поста
            Route::get('/{post}', 'show')->name('show');

            // Обновление поста
            Route::get('/{post}/edit', 'edit')->name('edit');
            Route::patch('/{post}', 'update')->name('update');

            // Удаление поста
            Route::delete('/{post}', 'delete')->name('delete');
        });
    });
});

// Можно заменить index, show, create, store, edit, update на:
// Route::resource('post', PostController::class)->only(['index', 'show', 'create', 'store', 'edit', 'update',]);
// Route::resource('post', PostController::class)->except(['delete']);

// Маршруты Auth::routes можно в классе Laravel\Ui\AuthRouteMethods
Auth::routes();

// ----------------------
// Мини Блог - конец
// ----------------------
