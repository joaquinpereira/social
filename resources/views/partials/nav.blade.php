<nav class="navbar navbar-expand-lg navbar-light navbar-laravel">
    <div class="container">
      <a class="navbar-brand" href="{{ route('home') }}">
        <i class="fa fa-users text-primary mr-1"></i>
        SocialApp
      </a>
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
                    <a href="{{ route('register')}} " class="nav-link">Register</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('login')}} " class="nav-link">Login</a>
                </li>
            @else
                <li class="nav-item">
                    <a href="{{ route('friends.index')}} " class="nav-link">Amigos</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('accept-friendships.index')}} " class="nav-link">Solicitudes</a>
                </li>
                <notification-list><i class="fa fa-bell"></i></notification-list>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('users.show', Auth::user())}}">Perfil</a></li>
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
