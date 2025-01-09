@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Carrito de Compras</h1>

    @if(session('cart'))
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Precio Unitario</th>
                                <th>Descuento</th>
                                <th>Subtotal</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $total = 0 @endphp
                            @foreach(session('cart') as $id => $details)
                                @php
                                    $product = \App\Models\Product::find($id);
                                    $precio = $product->precio * (1 - ($product->descuento / 100));
                                    $subtotal = $precio * $details['quantity'];
                                    $total += $subtotal;
                                @endphp
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('storage/'.$product->imagen) }}"
                                                 alt="{{ $product->nombre }}"
                                                 class="img-thumbnail me-2"
                                                 style="width: 50px">
                                            {{ $product->nombre }}
                                        </div>
                                    </td>
                                    <td>
                                        <input type="number"
                                               value="{{ $details['quantity'] }}"
                                               class="form-control quantity update-cart"
                                               data-id="{{ $id }}"
                                               min="1"
                                               max="{{ $product->stock }}"
                                               style="width: 70px">
                                    </td>
                                    <td>{{ number_format($product->precio, 0, ',', '.') }} CLP</td>
                                    <td>
                                        @if($product->descuento > 0)
                                            <span class="badge bg-success">{{ $product->descuento }}%</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ number_format($subtotal, 0, ',', '.') }} CLP</td>
                                    <td>
                                        <button class="btn btn-danger btn-sm remove-from-cart"
                                                data-id="{{ $id }}">
                                            <i class="bi bi-trash"></i>
                                            Eliminar
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-0">
                                <strong>Subtotal:</strong>
                                {{ number_format($total, 0, ',', '.') }} CLP
                            </p>
                            @php
                                $shipping = $total >= 20000 ? 0 : 3000;
                                $total += $shipping;
                            @endphp
                            <p class="mb-0">
                                <strong>Envío:</strong>
                                @if($shipping === 0)
                                    <span class="text-success">¡Envío gratis!</span>
                                @else
                                    {{ number_format($shipping, 0, ',', '.') }} CLP
                                @endif
                            </p>
                            <p class="h4 mt-2">
                                <strong>Total:</strong>
                                <span data-cart-total>{{ number_format($total, 0, ',', '.') }} CLP</span>
                            </p>
                        </div>
                        <div class="col-md-6 text-end">
                            <a href="{{ route('checkout') }}" class="btn btn-primary">
                                Proceder al pago
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-info">
            Tu carrito está vacío. <a href="{{ route('home') }}">Continuar comprando</a>
        </div>
    @endif
</div>
@endsection


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Función para actualizar el contador del carrito en el header
    function updateCartCounter(count) {
        const counter = document.getElementById('cartItemCount');
        if (counter) {
            counter.textContent = count;
        }
    }

    // Actualizar cantidad
    document.querySelectorAll('.update-cart').forEach(function(element) {
        element.addEventListener('change', function() {
            const id = this.getAttribute('data-id');
            const quantity = parseInt(this.value);
            const row = this.closest('tr');

            if (quantity < 1) {
                this.value = 1;
                return;
            }

            fetch(`/cart/update/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    quantity: quantity
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Actualizar subtotal del item
                    const subtotalCell = row.querySelector('td:nth-last-child(2)');
                    if (subtotalCell) {
                        subtotalCell.textContent = data.item_total + ' CLP';
                    }

                    // Actualizar totales
                    document.querySelector('[data-cart-total]').textContent = data.cart_total + ' CLP';
                    updateCartCounter(data.cart_count);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al actualizar el carrito');
            });
        });
    });

    // Eliminar del carrito
    document.querySelectorAll('.remove-from-cart').forEach(function(element) {
        element.addEventListener('click', function(e) {
            e.preventDefault();
            const id = this.getAttribute('data-id');
            const row = this.closest('tr');

            if (confirm('¿Estás seguro de que deseas eliminar este producto?')) {
                fetch(`/cart/remove/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        row.remove();
                        document.querySelector('[data-cart-total]').textContent = data.cart_total + ' CLP';
                        updateCartCounter(data.cart_count);

                        // Si no quedan items, recargar la página para mostrar el mensaje de carrito vacío
                        if (data.cart_count === 0) {
                            location.reload();
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al eliminar el producto del carrito');
                });
            }
        });
    });
});
</script>
@endpush
