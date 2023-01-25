<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user" content="{{ Auth::user() }}">

    @vite('resources/sass/app.scss')

    <title>Social app</title>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light navbar-laravel">
        <div class="container">
          <a class="navbar-brand" href="{{ route('home') }}">SocialApp</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
              {{-- <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="#">Inicio</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">Link</a>
              </li> --}}
            </ul>
            <ul class="navbar-nav ml-auto mb-2 mb-lg-0">
                @guest
                    <li class="nav-item">
                        <a href="{{ route('login')}} " class="nav-link">Login</a>
                    </li>
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Action</a></li>
                        <li><a class="dropdown-item" href="#">Another action</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a onclick="document.getElementById('logout').submit()" class="dropdown-item" href="#">Cerrar sesión</a></li>
                        </ul>
                    </li>
                    <form id="logout" action="{{ route('logout') }}" method="POST">@csrf</form>
                @endguest
            </ul>
          </div>
        </div>
    </nav>

    <main id="app" class="py-4">
        @yield('content')
    </main>


    @vite('resources/js/app.js')
</body>
</html>
