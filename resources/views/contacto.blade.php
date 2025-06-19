@extends('layouts.plantilla')

@section('titulo', 'Contacto')

@section('contenido')
<div class="max-w-xl mx-auto mt-10 p-6 bg-white rounded shadow">
    <h2 class="text-lg font-bold mb-4">Contáctanos</h2>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @auth
    <form method="POST" action="{{ route('contacto.enviar') }}">
        @csrf

        <div class="mb-4">
            <label for="nombre">Tu nombre</label>
            <input type="text" id="nombre" name="nombre" class="form-control"
                value="{{ old('nombre', Auth::user()->name) }}" readonly required autocomplete="name">
        </div>

        <div class="mb-4">
            <label for="email">Tu correo electrónico</label>
            <input type="email" id="email" name="email" class="form-control"
                value="{{ old('email', Auth::user()->email) }}" readonly required autocomplete="email">
        </div>

        <div class="mb-4">
            <label for="contenido">Mensaje</label>
            <textarea id="contenido" name="contenido" class="form-control" rows="5" required autocomplete="off">{{ old('contenido') }}</textarea>
        </div>
        <button type="submit" class="btn btn-accent">Enviar mensaje</button>
    </form>
    @else
    <ul>
        <li><strong>Email:</strong> lferreras01f@educantabria.es</li>
        <li><strong>Teléfono:</strong> +34 123 456 789</li>
        <li><strong>Dirección:</strong> Calle Ejemplo 123, Madrid</li>
    </ul>
    <p class="mt-3 text-sm text-gray-600">
        Para enviar un mensaje, por favor <a href="{{ route('login') }}" class="text-blue-500 underline">inicia sesión</a>.
    </p>
    @endauth

</div>
@endsection