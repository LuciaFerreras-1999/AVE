@extends('layouts.plantilla')

@section('titulo', 'Historial de Compras')

@section('contenido')
<div class="container mt-4">

    @if($compras->isEmpty())
    <p>No has realizado ninguna compra aún.</p>
    @else
    <div class="row">
        @forelse($compras as $compra)
        @php
        $prenda = $compra->prenda;
        @endphp

        <div class="col-12 col-sm-6 col-md-3 d-flex justify-content-center mb-4">
            <div class="card shadow-md rounded-lg overflow-hidden border-0" style="width: 18rem;">
                <img src="{{ $prenda && $prenda->imagen ? asset('assets/imagenes/' . $prenda->imagen) : asset('images/default.jpg') }}"
                    class="card-img-top"
                    alt="{{ $prenda->nombre ?? 'Prenda eliminada' }}">
                <div class="card-body text-center">
                    <h5 class="card-title font-weight-bold">{{ $prenda->nombre ?? 'Prenda eliminada' }}</h5>

                    <p class="card-text text-muted mb-1">
                        <strong>Precio:</strong>
                        <span class="fw-semibold text-success">€{{ number_format($compra->precio ?? ($prenda->precio ?? 0), 2) }}</span>
                    </p>

                    <p class="card-text text-sm text-gray-500 mb-2">
                        <small>Publicado por:
                            @if($prenda && $prenda->user)
                            <a href="{{ route('perfil.publico', $prenda->user->name) }}" class="text-blue-600 hover:underline">
                                {{ $prenda->user->name }}
                            </a>
                            @else
                            Desconocido
                            @endif
                        </small>
                    </p>

                    @if($prenda)
                    <a href="{{ route('prendas.mercado.show', ['usuario' => $prenda->user->name, 'prenda' => $prenda->slug]) }}"
                        class="btn btn-accent btn-sm mb-2">
                        Ver detalles
                    </a>
                    @endif

                    <p class="text-sm text-muted">
                        Comprado el {{ \Carbon\Carbon::parse($compra->fecha_compra)->format('d/m/Y H:i') }}
                    </p>
                </div>
            </div>
        </div>

        @empty
        <p class="text-center">No has realizado ninguna compra aún.</p>
        @endforelse

    </div>
    @endif
</div>
@endsection