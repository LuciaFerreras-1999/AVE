@extends('layouts.plantilla')

@section('titulo', 'Detalle del Mensaje')

@section('contenido')
<h2 class="text-xl font-bold mb-4">Mensaje de

    <a href="{{ route('perfil.publico', ['nombre' => $mensaje->emisor->name,'from' => 'mensaje','mensaje_id' => $mensaje->id]) }}">
        {{ $mensaje->emisor->name }}
    </a>

</h2>

<p><strong>Mensaje:</strong></p>
<p class="border p-4 rounded bg-gray-100">{{ $mensaje->contenido }}</p>
<p class="text-sm text-gray-500 mt-2">Enviado: {{ $mensaje->created_at->format('d/m/Y H:i') }}</p>
<a href="{{ route('admin.mensajes.index') }}" class="btn btn-secondary mt-4">Volver</a>
@endsection