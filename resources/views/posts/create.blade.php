@extends('layouts.site', ['title' => 'Создать пост'])

@section('content')
    <h1 class="mt-2 mb-3">Создание поста</h1>

    <form action="{{ route('post.store') }}" method="POST" enctype="multipart/form-data">
        @include('posts.form')
    </form>
@endsection
