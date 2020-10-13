<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap">
    <!-- Bootstrap core CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Material Design Bootstrap -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.19.1/css/mdb.min.css" rel="stylesheet">

</head>
<body class="blue lighten-4">
    <div id="app">
        <nav class="navbar navbar-expand-lg navbar-dark aqua-gradient shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item pr-3">
                                <a class="nav-link" href="{{ route('login') }}">
                                  ログイン
                                  <i class="fas fa-sign-in-alt"></i>
                                </a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item pr-3">
                                    <a class="nav-link" href="{{ route('register') }}">
                                      ユーザー登録
                                      <i class="fas fa-user-plus"></i>
                                    </a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item avatar pr-3">
                              <a class="nav-link p-1" href="{{ route('users.show', ['name' => Auth::user()->name]) }}">
                                @if(isset(Auth::user()->avatar))
                                  <img src="{{ config('filesystems.disks.s3.url'). Auth::user()->avatar }}" class="rounded-circle z-depth-0"
                                    alt="avatar image" height="35">
                                @endif
                                {{ Auth::user()->name }}
                                <i class="fas fa-user-cog"></i>
                              </a>
                            </li>
                            <li class="nav-item pr-3">
                              <a class="nav-link" href="{{ route('articles.create') }}">
                                投稿する
                                <i class="fas fa-plus"></i>
                              </a>
                            </li>
                            <li class="nav-item pr-3">
                              <a class="nav-link" href="{{ route('logout') }}"
                                  onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                  ログアウト
                                  <i class="fas fa-sign-out-alt"></i>
                              </a>
                            </li>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>


    <script src="{{ mix('js/app.js') }}"></script>
    <!-- JQuery -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Bootstrap tooltips -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.4/umd/popper.min.js"></script>
    <!-- Bootstrap core JavaScript -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <!-- MDB core JavaScript -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.19.1/js/mdb.min.js"></script>
</body>
</html>
