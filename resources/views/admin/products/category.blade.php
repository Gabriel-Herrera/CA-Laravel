@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Categoría: {{ $category }}</h1>
    
    <div class="row">
        @foreach($products as $product)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    @if($product->imagen)
                        <img src="{{ asset('storage/' . $product->imagen) }}" class="card-img-top" alt="{{ $product->nombre }}">
                    @else
                        <img src="{{ asset('images/placeholder.jpg') }}" class="card-img-top" alt="Placeholder">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->nombre }}</h5>
                        <p class="card-text">{{ Str::limit($product->descripcion, 100) }}</p>
                        <p class="card-text"><strong>Precio: </strong>${{ number_format($product->precio, 2) }}</p>
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