@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Finalizar Compra</h1>

    <form action="{{ route('checkout.process') }}" method="POST" id="payment-form">
        @csrf
        <!-- Detalles del pedido -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Resumen del Pedido</h5>
                @foreach($items as $item)
                    <div class="d-flex justify-content-between">
                        <span>{{ $item['product']->nombre }} x {{ $item['quantity'] }}</span>
                        <span>{{ number_format($item['subtotal'], 0, ',', '.') }} CLP</span>
                    </div>
                @endforeach
                <hr>
                <div class="d-flex justify-content-between">
                    <span>Envío:</span>
                    <span>{{ number_format($shipping, 0, ',', '.') }} CLP</span>
                </div>
                <div class="d-flex justify-content-between font-weight-bold">
                    <span>Total:</span>
                    <span>{{ number_format($total, 0, ',', '.') }} CLP</span>
                </div>
            </div>
        </div>

        <!-- Formulario de pago de Stripe -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Información de Pago</h5>
                <div id="card-element">
                    <!-- Un elemento de Stripe será insertado aquí. -->
                </div>
                <div id="card-errors" role="alert"></div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary mt-4">Pagar Ahora</button>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
    var stripe = Stripe('{{ env('STRIPE_KEY') }}');
    var elements = stripe.elements();

    var style = {
        base: {
            color: '#32325d',
            fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
            fontSmoothing: 'antialiased',
            fontSize: '16px',
            '::placeholder': {
                color: '#aab7c4'
            }
        },
        invalid: {
            color: '#fa755a',
            iconColor: '#fa755a'
        }
    };

    var card = elements.create('card', {style: style});
    card.mount('#card-element');

    card.addEventListener('change', function(event) {
        var displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });

    var form = document.getElementById('payment-form');
    form.addEventListener('submit', function(event) {
        event.preventDefault();

        stripe.createToken(card).then(function(result) {
            if (result.error) {
                var errorElement = document.getElementById('card-errors');
                errorElement.textContent = result.error.message;
            } else {
                stripeTokenHandler(result.token);
            }
        });
    });

    function stripeTokenHandler(token) {
        var form = document.getElementById('payment-form');
        var hiddenInput = document.createElement('input');
        hiddenInput.setAttribute('type', 'hidden');
        hiddenInput.setAttribute('name', 'stripeToken');
        hiddenInput.setAttribute('value', token.id);
        form.appendChild(hiddenInput);

        form.submit();
    }
</script>
@endpush
