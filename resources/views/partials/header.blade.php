<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container none">

            <a class="navbar-brand" href="{{ route('home') }}">
                <img class="logo d-inline-block align-text-center" src="{{ asset('storage/ca-icon.png') }}"
                    alt="Compra ágil logo" width="60" height="60">
                Compra Ágil
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">
                            <i class="bi bi-house"></i>
                            Inicio
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-bookmark"></i>
                            Categorías
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            @foreach ($categories as $category)
                                <li>
                                    <a class="dropdown-item {{ request('category') == $category->id ? 'active' : '' }}"
                                        href="{{ route('products.index', ['category' => $category->id]) }}">
                                        {{ $category->nombre }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('cart.index') }}">
                            <i class="bi bi-cart"></i> Carrito
                            <span class="badge bg-primary" id="cartItemCount">0</span>
                        </a>
                    </li>
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Iniciar Sesión</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Registrarse</a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person"></i>
                                {{ Auth::user()->username }}
                            </a>

                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                @if (Auth::check() && Auth::user()->isAdmin())
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">Panel de
                                            Admin</a></li>
                                @endif
                                <li><a class="dropdown-item" href="">Mi Perfil</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                    Cerrar Sesión
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                        </li>
                    </ul>
                    </li>
                @endguest
                </ul>
            </div>
        </div>
    </nav>
</header>
