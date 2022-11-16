<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Веб-разработка Laravel' }}</title>

    <link rel="shortcut icon" href="{{ asset('laravel_logo.ico') }}">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    @vite(['resources/js/app.js'])


</head>

<body>
<div class="container">
    <nav class="navbar navbar-expand-lg navbar-light bg-white bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ route('index') }}">
                {{ config('app.name', 'Laravel') }}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- ссылки слева -->
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Авторы</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('post.create') }}">Создать пост</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Контакты</a>
                    </li>
                </ul>

                <!-- форма поиска -->
                @error('search')
                <div class="text-danger">
                    {{ $message }}
                </div>
                @enderror
                <form class="form-inline my-2 my-lg-0" action="{{ route('post.search') }}">
                    <label for="">
                        <input class="form-control mr-sm-2 mx-3" name="search" type="search" placeholder="Найти пост..."
                               aria-label="Поиск" value="{{ old('search') }}">
                    </label>
                    <button class="btn btn-outline-success my-2 my-sm-0 mx-3" type="submit">Поиск</button>
                </form>

                <!-- ссылки справа -->
                <ul class="navbar-nav ms-auto">
                    <!-- Authentication Links -->
                    @guest
                        @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Войти') }}</a>
                            </li>
                        @endif

                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Регистрация') }}</a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                               data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name ?? 'Пользователь' }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Выйти') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>


    <main class="py-4">
        @if ($message = Session::get('success'))
            <div class='alert alert-success alert-dismissible mt-3' role='alert'>
                <button type="button" class="btn-close" data-dismiss="alert" aria-label="Закрыть">
                    <!-- <span aria-hidden="true">&times;</span> -->
                </button>
                {{ $message }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible mt-4 pb-0" role="alert">
                <button type="button" class="btn-close" data-dismiss="alert" aria-label="Закрыть">
                    {{--                    <span aria-hidden="true">&times;</span>--}}
                </button>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

</div>

</body>
<script>
    let btn_close_success = document.querySelector('.alert-success .btn-close');
    if (btn_close_success) {
        btn_close_success.addEventListener('click', function () {
            let div_success = document.querySelector('.alert.alert-success.alert-dismissible');
            div_success.style.display = "none";
        });
    }
    let btn_close_danger = document.querySelector('.alert-danger .btn-close');
    if (btn_close_danger) {
        btn_close_danger.addEventListener('click', function () {
            let div_danger = document.querySelector('.alert.alert-danger.alert-dismissible');
            div_danger.style.display = "none";
        });
    }
</script>

</html>
