@extends('layouts.plantilla')

@section('titulo', 'Chat con ' . $user->name)

@section('contenido')
<div class="container">

    <div id="chatBox"
        style="border: 1px solid #ccc; height: 400px; overflow-y: scroll; padding: 10px; background: #f9f9f9; margin: 30px 0;">
        
        @forelse($mensajes as $mensaje)

        @if($mensaje->emisor_id === auth()->id())
        <div style="text-align: right; margin-bottom: 10px;">
            <span style="background: #d1ffd6; padding: 5px 10px; border-radius: 10px; display: inline-block;">
                {{ $mensaje->mensaje }}
            </span><br>
            <small class="text-muted">{{ $mensaje->created_at->format('H:i, d/m/Y') }}</small>
        </div>
        @else
        <div style="text-align: left; margin-bottom: 10px;">
            <span style="background: #e2e2e2; padding: 5px 10px; border-radius: 10px; display: inline-block;">
                {{ $mensaje->mensaje }}
            </span><br>
            <small class="text-muted">{{ $mensaje->created_at->format('H:i, d/m/Y') }}</small>
        </div>
        @endif

        @empty
        <p>No hay mensajes aún. ¡Empieza la conversación!</p>
        @endforelse

    </div>

    <form action="{{ route('chats.enviar', ['user' => $user]) }}" method="POST" class="mb-5">
        @csrf
        <div class="d-flex align-items-center">
            <input type="text" name="mensaje" class="form-control input-mensaje" placeholder="Escribe tu mensaje..." required>
            <button class="btn btn-accent ml-2" type="submit">Enviar</button>
        </div>
    </form>
</div>
@endsection