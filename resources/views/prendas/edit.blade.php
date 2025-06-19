@extends('layouts.plantilla')

@section('titulo', 'Editar Prenda')

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

    <form action="{{ route('prendas.update', $prenda->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" name="nombre" id="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre', $prenda->nombre) }}" required>
            @error('nombre')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea name="descripcion" id="descripcion" class="form-control @error('descripcion') is-invalid @enderror" rows="4">{{ old('descripcion', $prenda->descripcion) }}</textarea>
            @error('descripcion')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="talla" class="form-label">Talla</label>
            <select name="talla" id="talla" class="form-select @error('talla') is-invalid @enderror">
                <optgroup label="Tallas con letras">
                    @foreach($tallas as $talla)
                    @if(!is_numeric($talla))
                    <option value="{{ $talla }}" {{ old('talla', $prenda->talla) == $talla ? 'selected' : '' }}>{{ $talla }}</option>
                    @endif
                    @endforeach
                </optgroup>
                <optgroup label="Tallas numéricas">
                    @foreach($tallas as $talla)
                    @if(is_numeric($talla))
                    <option value="{{ $talla }}" {{ old('talla', $prenda->talla) == $talla ? 'selected' : '' }}>{{ $talla }}</option>
                    @endif
                    @endforeach
                </optgroup>                
            </select>

            @error('talla')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="marca" class="form-label">Marca</label>
            <input type="text" name="marca" id="marca" class="form-control @error('marca') is-invalid @enderror" value="{{ old('marca', $prenda->marca) }}">
            @error('marca')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="estado" class="form-label">Estado</label>
            <select name="estado" id="estado" class="form-select @error('estado') is-invalid @enderror">
                <option value="nuevo" {{ old('estado', $prenda->estado) == 'nuevo' ? 'selected' : '' }}>Nuevo</option>
                <option value="usado" {{ old('estado', $prenda->estado) == 'usado' ? 'selected' : '' }}>Usado</option>
            </select>
            @error('estado')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
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
                            {{ in_array($categoria->id, old('categorias', $prenda->categorias->pluck('id')->toArray())) ? 'checked' : '' }}>
                        <label class="form-check-label" for="categoria-{{ $categoria->id }}">
                            {{ $categoria->nombre }}
                        </label>
                    </div>
                </div>
                @endforeach

            </div>
            @error('categorias')
            <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="imagen" class="form-label">Imagen</label>
            <input type="file" name="imagen" id="imagen" class="form-control @error('imagen') is-invalid @enderror">

            @if($prenda->imagen)
            <div class="mt-2">
                <p><strong>Imagen actual:</strong></p>
                <img src="{{ $prenda->imagen ? asset('assets/imagenes/' . $prenda->imagen) : asset('images/default.jpg') }}" class="card-img-top" alt="{{ $prenda->imagen }}">
                <small class="text-muted">Deja en blanco para mantener la imagen actual.</small>
            </div>
            @endif

            @error('imagen')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-accent">Actualizar</button>
        <a href="{{ route('prendas.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection