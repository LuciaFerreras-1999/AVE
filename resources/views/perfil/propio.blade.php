@extends('layouts.plantilla')

@section('titulo', 'Mi Perfil')

@section('contenido')
<div class="max-w-4xl mx-auto mt-8 bg-white p-6 rounded shadow">

    <div class="text-center mb-6">
        <img src="{{ $user->avatar ? asset('assets/imagenes/' . $user->avatar) : asset('assets/logo/default-avatar.png') }}"
            alt="Avatar" class="w-24 h-24 rounded-full mx-auto mb-4 object-cover">
        <h2 class="text-2xl font-semibold">{{ $user->name }}</h2>

        @php
        $promedio = round($user->promedioValoracion(), 1);
        @endphp

        <div class="flex justify-center items-center space-x-1 mb-2">

            @for ($i = 1; $i <= 5; $i++)

                @if($promedio>= $i)
                <svg class="w-6 h-6 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.37 2.448a1 1 0 00-.364 1.118l1.286 3.957c.3.921-.755 1.688-1.54 1.118l-3.37-2.448a1 1 0 00-1.176 0l-3.37 2.448c-.784.57-1.838-.197-1.539-1.118l1.286-3.957a1 1 0 00-.364-1.118L2.075 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.286-3.957z" />
                </svg>
                @elseif($promedio >= $i - 0.5)
                <svg class="w-6 h-6 text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                    <defs>
                        <linearGradient id="half">
                            <stop offset="50%" stop-color="currentColor" />
                            <stop offset="50%" stop-color="transparent" />
                        </linearGradient>
                    </defs>
                    <path fill="url(#half)" d="M12 .587l3.668 7.431L24 9.748l-6 5.847 1.416 8.268L12 18.896l-7.416 4.967L6 15.595 0 9.748l8.332-1.73z" />
                </svg>
                @else
                <svg class="w-6 h-6 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.37 2.448a1 1 0 00-.364 1.118l1.286 3.957c.3.921-.755 1.688-1.54 1.118l-3.37-2.448a1 1 0 00-1.176 0l-3.37 2.448c-.784.57-1.838-.197-1.539-1.118l1.286-3.957a1 1 0 00-.364-1.118L2.075 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.286-3.957z" />
                </svg>
                @endif

                @endfor

                <span class="ml-2 text-sm text-gray-600">({{ $promedio }}/5) ({{ $totalValoraciones }} valoraciones)</span>

        </div>

        <p class="text-gray-600 mb-2">{{ $user->email }}</p>
        <p class="text-gray-600 mb-2">{{ $user->phone }}</p>
        <p class="text-gray-600 mb-2">{{ $user->address }}</p>
        <p class="text-gray-700"><strong>Bio:</strong> {{ $user->bio ?? 'Sin bio' }}</p>

    </div>

    <div class="text-center mb-6">
        <a href="{{ route('perfil.edit') }}" class="btn btn-accent">Editar perfil</a>
    </div>

    <h3 class="text-xl font-semibold mb-4">Productos publicados</h3>

    @if($prendas->isEmpty())
    <p class="text-gray-600">No has publicado productos aún.</p>
    @else
    <div class="row">

        @foreach($prendas as $prenda)
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm">
                <img src="{{ $prenda->imagen ? asset('assets/imagenes/' . $prenda->imagen) : asset('images/default.jpg') }}"
                    class="card-img-top"
                    alt="{{ $prenda->nombre }}">
                <div class="card-body">
                    <h5 class="card-title">{{ $prenda->nombre }}</h5>
                    <p class="card-text"><strong>Precio:</strong> €{{ number_format($prenda->precio, 2) }}</p>
                    <a href="{{ route('prendas.mercado.show', ['usuario' => $prenda->user->name, 'prenda' => $prenda->slug, 'from' => 'perfil']) }}"
                        class="btn btn-accent btn-sm mt-2">Ver detalles</a>

                </div>
            </div>
        </div>
        @endforeach

    </div>

    <div class="mt-4">
        {{ $prendas->links() }}
    </div>
    @endif

    @if($valoraciones->isNotEmpty())
    <div class="mt-8">
        <h3 class="text-xl font-semibold mb-4">Comentarios recibidos</h3>

        @foreach($valoraciones as $valoracion)
        <div class="border rounded p-4 mb-4 bg-gray-50">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center space-x-2">
                    <strong>{{ $valoracion->usuario->name }}</strong>
                    <div class="flex">

                        @for ($i = 1; $i <= 5; $i++)
                            <svg class="w-4 h-4 {{ $i <= $valoracion->puntuacion ? 'text-yellow-400' : 'text-gray-300' }}"
                            fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.37 2.448a1 1 0 00-.364 1.118l1.286 3.957c.3.921-.755 1.688-1.54 1.118l-3.37-2.448a1 1 0 00-1.176 0l-3.37 2.448c-.784.57-1.838-.197-1.539-1.118l1.286-3.957a1 1 0 00-.364-1.118L2.075 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.286-3.957z" />
                            </svg>
                            @endfor

                    </div>
                </div>
                <small class="text-gray-500">{{ $valoracion->created_at->format('d/m/Y') }}</small>
            </div>
            <p class="text-gray-700">{{ $valoracion->comentario }}</p>
        </div>
        @endforeach

    </div>
    @else
    <p class="mt-8 text-gray-600">Aún no tienes comentarios recibidos.</p>
    @endif

</div>
@endsection