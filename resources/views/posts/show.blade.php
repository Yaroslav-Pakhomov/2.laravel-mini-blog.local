@extends('layouts.site', ['title' => $post->title ?? 'Веб-разработка Laravel'])

@section('content')
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h1>{{ $post->title ?? 'Веб-разработка Laravel' }}</h1>
                </div>
                <div class="card-body">
                    <img src="{{ $post->image ?? Vite::asset('resources/images/default.jpg') }}"
                         alt="{{ $post->title ?? '' }}" class="img-fluid mx-auto d-block">
                    <p class="mt-3 mb-0">{{ $post->excerpt ?? '' }}</p>
                    <p class="mt-3 mb-0">{{ $post->body ?? '' }}</p>
                </div>
                <div class="card-footer">
                    <!-- clearfix -->
                    <div class="d-flex justify-content-between">
                        <div class="float-left">
                            Автор: {{ $post->author->name ?? '' }}
                            <br>
                            Дата: {{ date_format($post->updated_at ?? '', 'd.m.Y H:i') }}
                        </div>
                        @auth {{-- Только аутентифицированные пользователи могут редактировать и удалять --}}
                        @if (auth()->id() === ( $post->author->id ?? 0) || auth()->id() === 1) {{-- причем, только свои посты блога --}}
                        <div class="float-right">
                            <a href="{{ route('post.edit', [$post->id ?? '']) }}" class="btn btn-dark mr-2">Редактировать
                                пост</a>
                            {{-- Форма для удаления поста --}}
                            <form action="{{ route('post.delete', [$post->id ?? '']) }}" method="POST"
                                  onsubmit="return confirm('Удалить этот пост?')" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <input type="submit" class="btn btn-danger" value='Удалить пост'>
                            </form>
                        </div>
                        @endif
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
