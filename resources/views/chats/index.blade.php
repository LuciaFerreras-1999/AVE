@extends('layouts.plantilla')

@section('titulo', 'Mis Chats')

@section('contenido')
<h2 class="text-xl font-semibold mb-4">Conversaciones</h2>

<ul class="list-group">

    @forelse($usuarios as $user)
    <li class="list-group-item d-flex justify-content-between align-items-center">

        <div class="d-flex align-items-center flex-grow-1">
            <img src="{{ $user->avatar ? asset('assets/imagenes/' . $user->avatar) : asset('assets/logo/default-avatar.png') }}"
                alt="{{ $user->name }}"
                class="rounded-circle mr-3"
                style="width: 40px; height: 40px; object-fit: cover;">

            <a href="{{ route('chats.conversacion', $user) }}" class="font-weight-bold text-dark">
                {{ $user->name }}
            </a>
        </div>

        <div class="text-right ml-3" style="max-width: 50%; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
            <small class="text-muted">{{ $ultimosMensajes[$user->id] ?? '' }}</small>
        </div>

        @if(isset($contadorNoLeidos[$user->id]) && $contadorNoLeidos[$user->id] > 0)
        <span class="badge badge-pill badge-danger ml-2">{{ $contadorNoLeidos[$user->id] }}</span>
        @endif

    </li>
    @empty
    <li class="list-group-item">No tienes conversaciones activas.</li>
    @endforelse

</ul>
@endsection