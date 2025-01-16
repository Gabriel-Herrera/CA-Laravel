@extends('layouts.app')

@section('title', 'Inicio - Compra ágil')

@section('content')
<div class="container py-4">
    <!-- Barra de búsqueda -->
    <div class="row mb-4">
        <div class="col-md-8 mx-auto">
            <form action="{{ route('home') }}" method="GET" class="d-flex gap-2">
                <input type="text" name="search" class="form-control" placeholder="Buscar productos..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i>
                    Buscar
                </button>
            </form>
        </div>
    </div>

    <!-- Lista de productos -->
    <div class="row product-grid">
        @foreach ($products as $product)
        <div class="col-6 col-md-4 col-lg-3 mb-4">
            <div class="card product-card h-100">
                @if($product->created_at->gt(now()->subDays(7)))
                    <span class="product-tag tag-new">Nuevo</span>
                @endif

                @if($product->descuento > 0)
                    <span class="discount-badge">-{{ $product->descuento }}%</span>
                @endif

                <img src="{{ asset('storage/' . $product->imagen) }}"
                     class="card-img-top"
                     alt="{{ $product->nombre }}"
                     loading="lazy">

                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $product->nombre }}</h5>
                    <p class="card-text flex-grow-1">{{ Str::limit($product->descripcion, 100) }}</p>

                    <div class="text-center mb-3">
                        @if($product->descuento > 0)
                            <p class="mb-0 original-price">
                                {{ number_format($product->precio, 0, ',', '.') }} CLP
                            </p>
                            <p class="final-price">
                                {{ number_format($product->precio_final, 0, ',', '.') }} CLP
                            </p>
                        @else
                            <p class="final-price mb-0">
                                {{ number_format($product->precio, 0, ',', '.') }} CLP
                            </p>
                        @endif
                    </div>

                    <div class="d-flex flex-column gap-2 mt-auto">
                        <a href="{{ route('products.show', $product->id) }}"
                           class="btn btn-outline-primary">
                            Ver Detalles
                        </a>
                        <button class="btn btn-primary add-to-cart"
                                onclick="addToCart({{ $product->id }})"
                                {{ $product->stock <= 0 ? 'disabled' : '' }}>
                            <i class="bi bi-cart-plus"></i>
                            {{ $product->stock <= 0 ? 'Sin Stock' : 'Añadir al Carrito' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $products->links() }}
    </div>
</div>

@push('scripts')
<script>
function addToCart(productId) {
    const button = event.target.closest('.add-to-cart');
    button.disabled = true;

    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: 1
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            button.classList.add('added');
            button.innerHTML = '<i class="bi bi-check2"></i> Agregado';

            // Actualizar contador del carrito
            const cartCount = document.getElementById('cartItemCount');
            if (cartCount) {
                cartCount.textContent = data.cartCount;
            }

            // Mostrar mensaje de éxito
            showMessage('Producto agregado al carrito', 'success');
        } else {
            throw new Error(data.message || 'Error al agregar al carrito');
        }
    })
    .catch(error => {
        showMessage(error.message, 'error');
        button.disabled = false;
    });
}

function showMessage(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show`;
    alertDiv.role = 'alert';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;

    document.querySelector('.container').insertBefore(alertDiv, document.querySelector('.product-grid'));

    setTimeout(() => {
        alertDiv.remove();
    }, 3000);
}
</script>
@endpush
@endsection
