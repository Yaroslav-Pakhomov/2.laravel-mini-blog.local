@extends('layouts.site')
@section('content')

    <h1 class="mt-2 mb-3">Результаты поиска</h1>

    @if (isset($posts) && count($posts))
        <div class="row">
            @foreach ($posts as $post)
                <div class="col-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <h3>{{ $post->title }}</h3>
                        </div>
                        <div class="card-body text-center">
                            <img src="{{ $post->thumbnail ?? Vite::asset('resources/images/default.jpg') }}"
                                 alt="{{ $post->title }}" class="img-fluid">
                        </div>
                        <div class="card-body">
                            <p class="mt-3 mb-0">{{ $post->excerpt }}</p>
                        </div>
                        <div class="card-footer">
                            <div class="d-flex justify-content-between">
                                <div class="p-1 mb-1">
                                    Автор: {{ $post->author }}
                                    <br>
                                    Дата: {{ date_format($post->updated_at, 'd.m.Y H:i') }}
                                </div>
                                <div class="p-1 mb-1">
                                    <a href="#" class="btn btn-dark float-right">Читать дальше</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p>По Вашему запросу ничего не найдено.</p>
    @endif

    {{ $posts->links() }}

@endsection
