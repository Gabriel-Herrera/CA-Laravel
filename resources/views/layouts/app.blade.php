<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Compra ágil')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('storage/compra-agil.jpeg') }}" type="image/x-icon">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container none">

                <a class="navbar-brand" href="{{ route('home') }}">
                    <img class="logo" src="{{ asset('storage/compra-agil.jpeg') }}" alt="laf logo";>
                    Compra ágil
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
                                <li><a class="dropdown-item"
                                        href="{{ route('products.category', 'electronica') }}">Electrónica</a></li>
                                <li><a class="dropdown-item" href="{{ route('products.category', 'ropa') }}">Ropa</a>
                                </li>
                                <li><a class="dropdown-item" href="{{ route('products.category', 'hogar') }}">Hogar</a>
                                </li>
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

    <main>
        @yield('content')
    </main>

    <footer class=" text-white mt-4">
        <div class="container none py-4">
            <div class="row">
                <div class="col-md-6">
                    <h5>Compra ágil</h5>
                    <p>Tu destino para compras en línea.</p>
                </div>
                <div class="col-md-3">
                    <h5>Enlaces Rápidos</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('home') }}" class="text-white">Inicio</a></li>
                        <li><a href="#" class="text-white">Sobre Nosotros</a></li>
                        <li><a href="#" class="text-white">Contacto</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>Síguenos</h5>
                    <ul class="list-unstyled">
                        <li>
                            <a href="#" class="text-white">
                                <i class="bi bi-facebook"></i>
                                Compra-ágil-oficial
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-white">
                                <i class="bi bi-twitter"></i>
                                Compra_ágil_oficial
                            </a>
                        </li>
                        <li><a href="#" class="text-white">
                                <i class="bi bi-instagram"></i>
                                Compra_ágil_oficial
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="text-center py-3">
            <p>&copy; {{ date('Y') }} Compra ágil. Todos los derechos reservados.</p>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/custom.js') }}"></script>

    @stack('scripts')
</body>

</html>
