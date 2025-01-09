@extends('layouts.app')

@section('title', $product->nombre)

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.category', $product->categoria_id) }}">{{ ucfirst($product->category->nombre) }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $product->nombre }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div id="productImages" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="{{ asset('storage/'.$product->imagen) }}" class="d-block w-100" alt="{{ $product->nombre }}">
                    </div>
                    <!-- Aquí puedes agregar más imágenes si las tienes -->
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#productImages" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Anterior</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#productImages" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Siguiente</span>
                </button>
            </div>
        </div>

        <div class="col-md-6">
            <h1 class="mb-3">{{ $product->nombre }}</h1>

            <div class="mb-4">
                <h2 class="h1 text-primary mb-3">{!! App\Helpers\PriceHelper::formatPrice($product->precio) !!}</h2>
                <div class="d-flex align-items-center mb-3">
                    <span class="badge bg-{{ $product->stock > 0 ? 'success' : 'danger' }} me-2">
                        {{ $product->stock > 0 ? 'En Stock' : 'Sin Stock' }}
                    </span>
                    @if($product->stock > 0)
                        <span class="text-muted">
                            {{ $product->stock }} unidades disponibles
                        </span>
                    @endif
                </div>
            </div>

            <div class="mb-4">
                <h3 class="h5">Descripción</h3>
                <p class="text-muted">{{ $product->descripcion }}</p>
            </div>

            @if($product->stock > 0)
                <form action="{{ route('cart.add') }}" method="POST" class="mb-4">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <div class="row g-3 align-items-center mb-3">
                        <div class="col-auto">
                            <label for="quantity" class="form-label">Cantidad:</label>
                        </div>
                        <div class="col-auto">
                            <select class="form-select" id="quantity" name="quantity">
                                @for($i = 1; $i <= min($product->stock, 10); $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg w-100 mb-2 add-to-cart">
                        Agregar al Carrito
                    </button>
                </form>
            @endif

            <div class="card">
                <div class="card-body">
                    <h3 class="h5 mb-3">Detalles del producto</h3>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <strong>Categoría:</strong>
                            {{ ucfirst($product->category->nombre) }}
                        </li>
                        <li class="mb-2">
                            <strong>ID del Producto:</strong>
                            {{ $product->id }}
                        </li>
                        <li>
                            <strong>Fecha de Alta:</strong>
                            {{ $product->fecha_creacion ? $product->fecha_creacion->format('d/m/Y') : 'N/A' }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-5">
        <h3 class="mb-4">Productos Relacionados</h3>
        <div class="row">
            @foreach($relatedProducts as $relatedProduct)
                <div class="col-md-3 mb-4">
                    <div class="card h-100">
                        <img src="{{ asset('storage/' . $relatedProduct->imagen) }}"
                             class="card-img-top"
                             alt="{{ $relatedProduct->nombre }}"
                             style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title">{{ $relatedProduct->nombre }}</h5>
                            <p class="card-text">{!! App\Helpers\PriceHelper::formatPrice($relatedProduct->precio) !!}</p>
                            <a href="{{ route('products.show', $relatedProduct->id) }}"
                               class="btn btn-outline-primary">
                                Ver Detalles
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const addToCartForm = document.querySelector('form');
    if (addToCartForm) {
        addToCartForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Producto agregado al carrito');
                } else {
                    alert('Error al agregar el producto al carrito');
                }
            });
        });
    }
});
</script>
@endpush
