@extends('layouts.app')

@section('title', 'Productos - ' . ucfirst($category))

@section('content')
<div class="container py-4">
    <h1 class="mb-4">{{ ucfirst($category) }}</h1>

    @if($products->isEmpty())
        <div class="alert alert-info">
            No hay productos disponibles en esta categoría.
        </div>
    @else
        <div class="row">
            @foreach($products as $product)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <img src="{{ asset('storage/' . $product->imagen) }}" 
                             class="card-img-top" 
                             alt="{{ $product->nombre }}"
                             style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->nombre }}</h5>
                            <p class="card-text">{{ Str::limit($product->descripcion, 100) }}</p>
                            <p class="card-text"><strong>Precio:</strong> {!! App\Helpers\PriceHelper::formatPrice($product->precio) !!}</p>
                            <p class="card-text"><strong>Stock:</strong> {{ $product->stock }}</p>
                        </div>
                        <div class="card-footer bg-white border-top-0">
                            <div class="d-grid gap-2">
                                <a href="{{ route('products.show', $product->id) }}" 
                                   class="btn btn-primary">
                                    Ver Detalles
                                </a>
                                @if($product->stock > 0)
                                    <button class="btn btn-outline-primary add-to-cart" 
                                            data-product-id="{{ $product->id }}">
                                        Agregar al Carrito
                                    </button>
                                @else
                                    <button class="btn btn-outline-secondary" disabled>
                                        Sin Stock
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $products->links() }}
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.add-to-cart').forEach(button => {
    button.addEventListener('click', function() {
        const productId = this.dataset.productId;
        fetch('{{ route('cart.add') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: 1
            })
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
});
</script>
@endpush