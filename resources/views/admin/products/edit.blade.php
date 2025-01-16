@extends('layouts.admin')

@section('title', 'Editar Producto')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title m-0">Editar Producto</h2>
                    </div>

                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('admin.products.update', $product->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre del Producto</label>
                                <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                                    id="nombre" name="nombre" value="{{ old('nombre', $product->nombre) }}" required>
                                @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="descripcion" class="form-label">Descripción</label>
                                <textarea class="form-control @error('descripcion') is-invalid @enderror" id="descripcion" name="descripcion"
                                    rows="3" required>{{ old('descripcion', $product->descripcion) }}</textarea>
                                @error('descripcion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="precio" class="form-label">Precio (CLP)</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control" id="precio" name="precio" step="0.01"
                                        value="{{ $product->precio }}" required>
                                </div>
                                @error('precio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Agregar después del campo de precio -->
                            <div class="mb-3">
                                <label for="descuento" class="form-label">Descuento (%)</label>
                                <input type="number" class="form-control @error('descuento') is-invalid @enderror"
                                    id="descuento" name="descuento" value="{{ old('descuento', $product->descuento) }}"
                                    min="0" max="100">
                                @error('descuento')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="etiquetas" class="form-label">Etiquetas</label>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="etiqueta_oferta" name="etiquetas[]"
                                        value="oferta"
                                        {{ in_array('oferta', $product->etiquetas ?? []) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="etiqueta_oferta">Oferta</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="etiqueta_liquidacion"
                                        name="etiquetas[]" value="liquidacion"
                                        {{ in_array('liquidacion', $product->etiquetas ?? []) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="etiqueta_liquidacion">Liquidación</label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="imagen" class="form-label">Imagen del Producto</label>
                                <input type="file" class="form-control-file" id="imagen" name="imagen">
                                <small class="form-text text-muted">
                                    <p>
                                        Deja este campo vacío si no quieres cambiar la imagen actual.
                                    </p>
                                </small>
                                @if ($product->imagen)
                                    <img src="{{ asset('storage/' . $product->imagen) }}" alt="{{ $product->nombre }}"
                                        class="mt-2" style="max-width: 200px;">
                                @endif

                            </div>

                            <div class="mb-3">
                                <label for="categoria_id" class="form-label">Categoría</label>
                                <select class="form-control @error('categoria_id') is-invalid @enderror" id="categoria_id" name="categoria_id" required>
                                    <option value="">Seleccionar Categoría</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('categoria_id', $product->categoria_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('categoria_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="stock" class="form-label">Stock</label>
                                <input type="number" class="form-control @error('stock') is-invalid @enderror"
                                    id="stock" name="stock" value="{{ old('stock', $product->stock) }}"
                                    min="0" required>
                                @error('stock')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                                    Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    Actualizar Producto
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
