@extends('layouts.app')

@section('titulo', 'Chat con ' . $usuario->name)

@section('contenido')
<div class="max-w-2xl mx-auto bg-white shadow p-4 rounded">
    <h2 class="text-xl font-semibold mb-4">Conversación con {{ $usuario->name }}</h2>

    <div class="space-y-2 max-h-96 overflow-y-auto border p-3 rounded mb-4">
        @foreach($mensajes as $mensaje)
        <div class="text-{{ $mensaje->remitente_id == auth()->id() ? 'right' : 'left' }}">
            <div class="inline-block px-3 py-2 rounded-lg bg-{{ $mensaje->remitente_id == auth()->id() ? 'blue-200' : 'gray-200' }}">
                <strong>{{ $mensaje->remitente->name }}:</strong> {{ $mensaje->mensaje }}
                <div class="text-xs text-gray-600">{{ $mensaje->created_at->diffForHumans() }}</div>
            </div>
        </div>
        @endforeach
    </div>

    <form action="{{ route('mensajes.enviar', $usuario) }}" method="POST">
        @csrf
        <textarea name="mensaje" rows="3" class="form-control mb-2" placeholder="Escribe tu mensaje..."></textarea>
        <button class="btn btn-primary w-100">Enviar</button>
    </form>
</div>
@endsection