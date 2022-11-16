@extends('layouts.site', ['title' => 'Редактирование поста'])

@section('content')
    <h1 class="mt-2 mb-3">Редактирование поста</h1>

    <form action="{{ route('post.update', [$post->id ?? 0]) }}" method="POST" enctype="multipart/form-data">
        @method('PATCH')
        @include('posts.form')
    </form>
@endsection
