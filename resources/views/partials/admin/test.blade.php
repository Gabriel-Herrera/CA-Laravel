<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container none">

            <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
                <img class="logo" src="{{ asset('storage/compra-agil.jpeg') }}" alt="laf logo";>
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
                            <i class="bi bi-shop"></i>
                            Tienda
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Dashboard
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">Inicio</a>
                            </li>
                            <li><a class="dropdown-item" href="{{ route('admin.products.index') }}">Productos</a>
                            </li>
                            <li><a class="dropdown-item" href="{{ route('admin.categories.index') }}">Categorías</a>
                            </li>


                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="btn btn-primary btn-sm nav-link">
                            <i class="fas fa-shopping-cart fa-sm"></i> Gestionar Pedidos
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.discounts.index') }}" class="btn btn-primary btn-sm nav-link">
                            <i class="fas fa-percent fa-sm"></i> Gestionar Descuentos
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav">

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
