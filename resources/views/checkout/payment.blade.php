@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form id="payment-form">
                        <div id="payment-element">
                            <!-- Stripe Elements se insertará aquí -->
                        </div>
                        <button id="submit" class="btn btn-primary mt-4">
                            <span id="button-text">Pagar ahora</span>
                        </button>
                        <div id="payment-message" class="hidden"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
const stripe = Stripe('{{ env('STRIPE_KEY') }}');
const elements = stripe.elements({
    clientSecret: '{{ $clientSecret }}'
});
const paymentElement = elements.create('payment');
paymentElement.mount('#payment-element');

const form = document.getElementById('payment-form');
form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const {error} = await stripe.confirmPayment({
        elements,
        confirmParams: {
            return_url: '{{ route('checkout.success') }}',
        },
    });

    if (error) {
        const messageDiv = document.getElementById('payment-message');
        messageDiv.textContent = error.message;
    }
});
</script>
@endsection
