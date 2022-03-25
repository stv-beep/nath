<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- <link rel="shortcut icon" href="{{url('favicon.ico')}}"/> --}}
    <link rel="icon" href="{{ asset('favicon-16x16.jpg') }}">
    <meta name="author" content="Aleix J. Algueró">
    
    {{-- saving user's language in js --}}
    <script>
        var locale = '{{ config('app.locale') }}';
    </script>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>App Nath v0.9.1{{-- {{ config('app.name') }} --}}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/Translate.js') }}" defer></script>
    <script src="{{ asset('js/scripts.js') }}" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" integrity="sha512-c42qTSw/wPZ3/5LBzD+Bw5f7bSF2oxou6wEb+I/lqeaKV5FDIfMvvRp772y4jcJLKuGUOpbJMdg/BTl50fJYAw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" integrity="sha512-aOG0c6nPNzGk+5zjwyJaoRUgCdOrfSDhmMID2u4+OIslr0GjpLKo7Xm0Ao3xmpM4T8AmIouRkqwj1nrdVsLKEQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-light shadow-lg">
            <div class="container">
                <a id="nom-app" class="navbar-brand" href="{{ url('/home') }}">
                    App Nath <small id="versionText">v0.9.1</small>
                    {{-- {{ config('app.name') }} --}}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link user-nav btn" href="{{ route('login') }}">{{ __('messages.Login') }}</a>{{-- Iniciar sessió --}}
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link user-nav btn" href="{{ route('register') }}">{{ __('messages.Register') }}</a>{{-- Registrar-s --}}
                                </li>
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="user-nav nav-link dropdown-toggle btn" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        {{ __("messages.Choose a language") }} <i class="fas fa-language"></i>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{route('set_language', ['ca'])}}">{{ __("messages.Catalan") }}</a> 
                                        <a class="dropdown-item" href="{{route('set_language', ['es'])}}">{{ __("messages.Spanish") }}</a>
                                        <a class="dropdown-item" href="{{route('set_language', ['en'])}}">{{ __("messages.English") }}</a> 
                                    </div>
                                </li> 
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="user-nav nav-link dropdown-toggle btn btn-outline-orange" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }} <i class="fas fa-user-tie"></i>
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('home') }}">
                                        {{ __('Home') }}
                                    </a>
                                    @if ($user->administrador == true)
                                    <a class="dropdown-item" href="{{ route('admin.reports') }}">
                                        {{ __('Reports') }}
                                    </a>
                                    @endif
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('messages.Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                            &nbsp;
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="user-nav nav-link dropdown-toggle btn" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ __("messages.Choose a language") }} <i class="fas fa-language"></i>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item idioma" href="{{route('set_language', ['ca'])}}">{{ __("messages.Catalan") }}</a> 
                                    <a class="dropdown-item idioma" href="{{route('set_language', ['es'])}}">{{ __("messages.Spanish") }}</a>
                                    <a class="dropdown-item idioma" href="{{route('set_language', ['en'])}}">{{ __("messages.English") }}</a> 
                                </div>
                            </li>    
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
