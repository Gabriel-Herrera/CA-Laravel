@extends('layouts.app')

@section('title', 'Inicio - Compra ágil')

@section('content')
    <div class="container py-4">
        <hr class="sidebar-divider">
        <!-- Barra de búsqueda -->
        <div class="row mb-4">
            <form action="{{ route('home') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Buscar productos..."
                    value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i>
                    Buscar
                </button>
            </form>

        </div>


        <!-- Productos destacados -->
        @if (isset($featuredProducts) && $featuredProducts->isNotEmpty())
            <hr class="sidebar-divider">
            <h2 class="text-center mb-3">Productos Destacados</h2>
            @include('products.carousel')
        @endif

        <!-- Lista de productos -->
        <div class="row mt-4">
            @foreach ($products as $product)
                <div class="col-md-3 mb-4">
                    <div class="card h-100 d-flex flex-column">
                        <img src="{{ asset('storage/' . $product->imagen) }}" class="card-img-top"
                            alt="{{ $product->nombre }}">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $product->nombre }}</h5>
                            <p class="card-text flex-grow-1">{{ Str::limit($product->descripcion, 100) }}</p>
                            <p class="card-text text-center badge bg-success">
                                <strong >
                                     {!! App\Helpers\PriceHelper::formatPrice($product->precio) !!}
                                </strong>
                            </p>
                            <a href="{{ route('products.show', $product->id) }}" class="btn btn-secondary">Ver Detalles</a>
                            <a href="#" class="btn btn-primary">Añadir <i class="bi bi-cart"></i></a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $products->links() }}
        </div>
    </div>
@endsection
