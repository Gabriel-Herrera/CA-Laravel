@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Resultados de Búsqueda</h1>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver a Productos
        </a>
    </div>

    <!-- Formulario de búsqueda -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Buscar Productos</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.products.search') }}" method="GET" class="mb-4">
                <div class="input-group">
                    <input type="text"
                           class="form-control"
                           name="query"
                           value="{{ request('query') }}"
                           placeholder="Buscar por nombre o descripción...">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search fa-sm"></i> Buscar
                        </button>
                    </div>
                </div>
            </form>

            @if(request('query'))
                <div class="alert alert-info">
                    Mostrando resultados para: "{{ request('query') }}"
                </div>
            @endif
        </div>
    </div>

    <!-- Resultados de la búsqueda -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Resultados</h6>
        </div>
        <div class="card-body">
            @if($products->isEmpty())
                <div class="text-center py-4">
                    <i class="fas fa-search fa-3x text-gray-300 mb-3"></i>
                    <p class="text-gray-500">No se encontraron productos que coincidan con tu búsqueda.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Imagen</th>
                                <th>Nombre</th>
                                <th>Categoría</th>
                                <th>Precio</th>
                                <th>Stock</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                                <tr>
                                    <td>{{ $product->id }}</td>
                                    <td class="text-center">
                                        @if($product->imagen)
                                            <img src="{{ asset('storage/' . $product->imagen) }}"
                                                 alt="{{ $product->nombre }}"
                                                 style="max-height: 50px;">
                                        @else
                                            <i class="fas fa-image text-gray-300"></i>
                                        @endif
                                    </td>
                                    <td>{{ $product->nombre }}</td>
                                    <td>{{ $product->category->nombre }}</td>
                                    <td>${{ number_format($product->precio, 2) }}</td>
                                    <td>
                                        @if($product->stock < 10)
                                            <span class="text-danger">{{ $product->stock }}</span>
                                        @else
                                            {{ $product->stock }}
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('products.edit', $product->id) }}"
                                               class="btn btn-primary btn-sm">
                                                <i class="fas fa-edit"></i> Editar
                                            </a>
                                            <form action="{{ route('products.destroy', $product->id) }}"
                                                  method="POST"
                                                  class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="btn btn-danger btn-sm"
                                                        onclick="return confirm('¿Estás seguro de que quieres eliminar este producto?')">
                                                    <i class="fas fa-trash"></i> Eliminar
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $products->appends(['query' => request('query')])->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .table img {
        transition: transform .2s;
    }
    .table img:hover {
        transform: scale(2);
    }
    .btn-group {
        gap: 0.25rem;
    }
</style>
@endpush
