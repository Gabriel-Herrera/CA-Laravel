@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <!-- Existing code for cart items -->
            <div class="col-md-12">
                <h2>Carrito de compras</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Existing code for cart items -->
                    </tbody>
                </table>
            </div>
            <div class="col-md-6">
                <h3>Resumen del pedido</h3>
                <p>Subtotal: <span id="subtotal">0</span> CLP</p>
                <p>IVA (19%): <span id="iva">0</span> CLP</p>
                <p>Total: <span id="total">0</span> CLP</p>
            </div>
            <div class="col-md-6">
                <div class="col-md-6">
                    <form action="{{ route('cart.apply-discount') }}" method="POST" class="mb-3">
                        @csrf
                        <div class="input-group">
                            <input type="text" name="discount_code" class="form-control"
                                placeholder="Código de descuento">
                            <button type="submit" class="btn btn-outline-secondary">Aplicar</button>
                        </div>
                    </form>
                </div>

                @if (isset($appliedDiscount))
                    <div class="alert alert-success">
                        Descuento aplicado: {{ $appliedDiscount['code'] }}
                        <br>
                        Ahorro: {{ number_format($appliedDiscount['saved_amount'], 0, ',', '.') }} CLP
                    </div>
                @endif
                <button type="button" class="btn btn-primary btn-lg btn-block" onclick="proceedToPayment()">Proceder al
                    pago</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Actualizar cantidad
            document.querySelectorAll('.update-cart').forEach(function(element) {
                element.addEventListener('change', function() {
                    let id = this.getAttribute('data-id');
                    let quantity = this.value;

                    fetch(`/cart/update/${id}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                quantity: quantity
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                location.reload();
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                });
            });

            // Eliminar del carrito
            document.querySelectorAll('.remove-from-cart').forEach(function(element) {
                element.addEventListener('click', function(e) {
                    e.preventDefault();
                    let id = this.getAttribute('data-id');

                    fetch(`/cart/remove/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                location.reload();
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                });
            });
        });
    </script>
@endpush
