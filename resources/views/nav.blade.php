<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">Мой блог</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="#">Автор</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('post.create') }}">Создать пост</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Контакты</a>
            </li>
        </ul>
        @error('search')
        <div class="text-danger">
            {{ $message }}
        </div>
        @enderror
        <form class="form-inline my-2 my-lg-0" action="{{ route('post.search') }}">
            <label for="">
                <input class="form-control mr-sm-2 mx-3" name="search" type="search" placeholder="Найти пост..." aria-label="Поиск" value="{{ old('search') }}">
            </label>
            <button class="btn btn-outline-success my-2 my-sm-0 mx-3" type="submit">Поиск</button>
        </form>




        <!-- ссылки справа -->
        <ul class="navbar-nav ml-auto">
            @guest
            <li class="nav-item">
                <!-- ссылка для входа -->
                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
            </li>
            @if (Route::has('register'))
            <li class="nav-item">
                <!-- ссылка для регистрации -->
                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
            </li>
            @endif
            @else
            <li class="nav-item">
                <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    {{ __('Logout') }} <!-- ссылка выхода -->
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>
            @endguest
        </ul>
    </div>
</nav>
