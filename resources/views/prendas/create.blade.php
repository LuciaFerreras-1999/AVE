@extends('layouts.plantilla')

@section('titulo', 'Añadir Prenda')

@section('contenido')
<div class="max-w-4xl mx-auto my-8 px-4 bg-white p-6 shadow rounded">

    @if($errors->any())
    <div class="alert alert-danger">
        <ul>

            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach

        </ul>
    </div>
    @endif

    <form action="{{ route('prendas.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre de la Prenda</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion" rows="3">{{ old('descripcion') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="talla" class="form-label">Talla</label>
            <input type="text" class="form-control" id="talla" name="talla" value="{{ old('talla') }}">
        </div>

        <div class="mb-3">
            <label for="marca" class="form-label">Marca</label>
            <input type="text" class="form-control" id="marca" name="marca" value="{{ old('marca') }}">
        </div>

        <div class="mb-3">
            <label for="estado" class="form-label">Estado</label>
            <select class="form-select" id="estado" name="estado" required>
                <option value="" disabled selected>Seleccione el estado</option>
                <option value="nuevo" {{ old('estado') == 'nuevo' ? 'selected' : '' }}>Nuevo</option>
                <option value="usado" {{ old('estado') == 'usado' ? 'selected' : '' }}>Usado</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label d-block mb-2">Categorías</label>
            <div class="row">
                @foreach($categorias as $categoria)
                <div class="col-12 col-sm-6 col-md-3 mb-2">
                    <div class="form-check">
                        <input
                            class="form-check-input"
                            type="checkbox"
                            name="categorias[]"
                            value="{{ $categoria->id }}"
                            id="categoria-{{ $categoria->id }}"
                            {{ (is_array(old('categorias')) && in_array($categoria->id, old('categorias'))) ? 'checked' : '' }}>
                        <label class="form-check-label" for="categoria-{{ $categoria->id }}">
                            {{ $categoria->nombre }}
                        </label>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        
        <div class="mb-3">
            <label for="imagen" class="form-label">Imagen</label>
            <input type="file" class="form-control" id="imagen" name="imagen" required>

        </div>

        <button type="submit" class="btn btn-accent">Guardar</button>
        <a href="{{ route('prendas.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

@endsection