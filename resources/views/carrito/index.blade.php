@extends('layouts.plantilla')

@section('titulo', '🛒 Mi Carrito')

@section('contenido')
<div class="container mt-4">
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    <div class="text-end mb-3">
        <a href="{{ route('compras.historial') }}" class="btn btn-accent">Historial</a>
    </div>
    @if(session('carrito') && session('carrito')->isNotEmpty())
    <div class="row">

        @php $total = 0; @endphp

        @foreach(session('carrito') as $prenda)
        @php $total += $prenda->precio; @endphp
        <div class="col-md-3 mb-4">
            <div class="card h-100 shadow-sm">
                <img
                    src="{{ $prenda->imagen ? asset('assets/imagenes/' . $prenda->imagen) : asset('images/default.jpg') }}"
                    class="card-img-top"
                    alt="{{ $prenda->nombre }}">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $prenda->nombre }}</h5>
                    <p class="card-text mb-1">
                        <small class="text-muted">Vendedor: {{ $prenda->user->name }}</small>
                    </p>
                    <p class="card-text fw-semibold text-success mb-3">
                        €{{ number_format($prenda->precio, 2) }}
                    </p>
                    <div class="mt-auto d-flex justify-content-between">
                        <a
                            href="{{ route('prendas.mercado.show', ['usuario' => $prenda->user->name, 'prenda' => $prenda->slug]) }}"
                            class="btn btn-accent btn-sm">
                            Ver detalles
                        </a>
                        <form action="{{ route('carrito.quitar', $prenda) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">Quitar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="text-end">
        <h4>Total: €{{ number_format($total, 2) }}</h4>
        <form action="{{ route('carrito.finalizar') }}" method="POST" class="text-end mt-2">
            @csrf
            <button type="submit" class="btn btn-success">Finalizar Compra</button>
        </form>
    </div>

    @else
    <p class="text-center">Tu carrito está vacío.</p>
    <a href="{{ route('prendas.mercado.index') }}" class="btn btn-accent mt-3">Explorar prendas</a>
    @endif
</div>

@endsection