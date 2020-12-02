<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>WorkUa</title>
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    {{--    <link rel="shortcut icon" href="{{ asset('img/workUa.jpg') }}">--}}
</head>
<body>
<div class="container">
    <nav id="navbar-example2" class="navbar navbar-light bg-light mb-3">
        <a class="navbar-brand" href="/">WorkUa</a>
        <ul class="nav nav-pills">
            <li class="nav-item">
                <a class="nav-link" href="/">Головна</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('organization.createWeb')  }}">Створити організацію</a>
            </li>
{{--            <li class="nav-item">--}}
{{--                <a class="nav-link" href="{{ route('vacancy.createWeb')  }}">Відкрити вакансію</a>--}}
{{--            </li>--}}
        </ul>
        <form class="form-inline my-2 my-lg-0" action="{{ route('organization.indexWeb')  }}">
            <input class="form-control mr-sm-2" name="search" type="search" placeholder="Знайти вакансію..."
                   aria-label="Search">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Пошук</button>
        </form>
        <!-- Right Side Of Navbar -->
        <ul class="navbar-nav ml-auto">
            <!-- Authentication Links -->
            {{--            @guest--}}
            <li class="nav-item">
                {{--                    <a class="nav-link" href="{{ route('login') }}">{{ __('Войти') }}</a>--}}
            </li>
            {{--                @if (Route::has('register'))--}}
            {{--                    <li class="nav-item">--}}
            {{--                        <a class="nav-link" href="{{ route('register') }}">{{ __('Зарегистрироваться') }}</a>--}}
            {{--                    </li>--}}
            {{--                @endif--}}
            {{--            @else--}}
            <li class="nav-item dropdown">
                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"
                   aria-haspopup="true" aria-expanded="false" v-pre>
                    {{--                        {{ Auth::user()->name }} <span class="caret"></span>--}}
                </a>

                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                    {{--                        <a class="dropdown-item" href="{{ route('logout') }}"--}}
                    {{--                           onclick="event.preventDefault();--}}
                    {{--                            document.getElementById('logout-form').submit();">--}}
                    {{--                            {{ __('Logout') }}--}}
                    {{--                        </a>--}}

                    {{--                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">--}}
                    {{--                            @csrf--}}
                    {{--                        </form>--}}
                </div>
            </li>
            {{--            @endguest--}}
        </ul>
    </nav>
</div>
<div class="container">
    @if($errors->any())
        @foreach($errors->all() as $error)
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ $error }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endforeach
    @endif
    @if ( session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    @yield('content')

</div>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
