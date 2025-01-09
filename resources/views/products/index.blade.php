@extends('layouts.app')

@section('title', 'Inicio - Compra ágil')

@section('content')
    <div class="container py-4">
        <hr class="sidebar-divider">
        <!-- Barra de búsqueda y filtro de categorías -->
        <div class="row mb-4">
            <div class="col-md-6">
                <form action="{{ route('home') }}" method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control me-2" placeholder="Buscar productos..."
                        value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i>
                        Buscar
                    </button>
                </form>
            </div>
            <div class="col-md-6">
                <form action="{{ route('home') }}" method="GET" class="d-flex">
                    <select name="category" class="form-select me-2">
                        <option value="">Todas las categorías</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->nombre }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-secondary">
                        <i class="bi bi-funnel"></i>
                        Filtrar
                    </button>
                </form>
            </div>
        </div>

        <!-- Productos destacados -->
        @if (isset($featuredProducts) && $featuredProducts->isNotEmpty())
            <h2 class="text-center mb-3">Productos Destacados</h2>
            @include('products.carousel')
        @endif

        <!-- Lista de productos -->
        <div class="row mt-4">
            @foreach ($products as $product)
                <div class="col-md-3 mb-4">
                    <div class="card h-100">
                        <img src="{{ asset('storage/' . $product->imagen) }}" class="card-img-top"
                            alt="{{ $product->nombre }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->nombre }}</h5>
                            <p class="card-text">{{ Str::limit($product->descripcion, 100) }}</p>
                            <p class="card-text"><strong>Precio:</strong> {!! App\Helpers\PriceHelper::formatPrice($product->precio) !!}</p>
                            <a href="{{ route('products.show', $product->id) }}" class="btn btn-primary">Ver Detalles</a>
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
