@extends('layouts.plantilla')

@section('titulo', 'Inicio')

@section('sinHeader', true)

@section('contenido')
<div class="relative h-[500px] bg-cover bg-center rounded-xl overflow-hidden shadow-lg mb-10"
    style="background-image: url('{{ asset('assets/logo/banner_ave.png') }}')">

    <div class="absolute inset-0 bg-gradient-to-r from-[#264653]/80 via-[#264653]/60 to-[#264653]/40 flex flex-col justify-center items-start px-10 text-white">
        <h1 class="text-5xl font-extrabold mb-4 max-w-2xl leading-tight drop-shadow-lg">
            Bienvenida a <span class="text-[#f4a261]">AVE</span>
        </h1>
        <p class="text-xl mb-6 max-w-xl">
            Organiza, intercambia y reutiliza tu ropa con estilo y conciencia ecológica. Únete al cambio.
        </p>

        @auth
        <a href="{{ route('prendas.mercado.index') }}"
            class="bg-[#f4a261] hover:bg-[#e76f51] text-white font-semibold py-3 px-6 rounded-full shadow-md transition duration-300 no-underline hover:no-underline">
            Explora ahora
        </a>
        @endauth

        @guest
        <a href="{{ route('login') }}"
            class="bg-[#f4a261] hover:bg-[#e76f51] text-white font-semibold py-3 px-6 rounded-full shadow-md transition duration-300 no-underline hover:no-underline">
            Explora ahora
        </a>
        @endguest

    </div>
</div>

<h2 class="text-2xl font-semibold mb-4">Prendas destacadas del mercado</h2>
<div id="carouselExampleIndicators" class="carousel slide mb-8" data-bs-ride="carousel" data-bs-interval="3000">
    <ol class="carousel-indicators">
        @foreach ($prendas->chunk(3) as $index => $grupo)
        <li data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}"></li>
        @endforeach
    </ol>
    <div class="carousel-inner">

        @foreach ($prendas->chunk(3) as $index => $grupo)
        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
            <div class="container py-4">
                <div class="row justify-content-center">

                    @foreach ($grupo as $prenda)
                    <div class="col-12 col-sm-6 col-md-4 d-flex justify-content-center mb-4">
                        <div class="card shadow-md rounded-lg overflow-hidden" style="width: 18rem;">
                            <img class="card-img-top" src="{{ $prenda->imagenes->first()->url ?? asset('assets/imagenes/' . $prenda->imagen) }}" alt="Imagen de {{ $prenda->nombre }}">
                            <div class="card-body text-center">
                                <h5 class="card-title font-weight-bold">{{ $prenda->nombre }}</h5>
                                <p class="card-text text-muted mb-3">
                                    <strong>Precio:</strong> €{{ number_format($prenda->precio, 2) }}
                                </p>
                                <a href="{{ route('prendas.mercado.show', [$prenda->user, $prenda]) }}" class="btn btn-accent">
                                    Ver más
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach

                </div>
            </div>
        </div>
        @endforeach

    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Anterior</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Siguiente</span>
    </button>
</div>


<h2 class="text-2xl font-semibold mb-4">¿Por qué usar AVE?</h2>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white rounded-lg shadow-md p-6 text-center">
        <i class="fas fa-recycle text-4xl text-green-500 mb-4"></i>
        <h3 class="text-xl font-bold mb-2">Sostenibilidad</h3>
        <p>Contribuye al medio ambiente reutilizando y reciclando tus prendas.</p>
    </div>
    <div class="bg-white rounded-lg shadow-md p-6 text-center">
        <i class="fas fa-users text-4xl text-blue-500 mb-4"></i>
        <h3 class="text-xl font-bold mb-2">Comunidad</h3>
        <p>Conecta con personas que comparten tu pasión por la moda consciente.</p>
    </div>
    <div class="bg-white rounded-lg shadow-md p-6 text-center">
        <i class="fas fa-star text-4xl text-yellow-400 mb-4"></i>
        <h3 class="text-xl font-bold mb-2">Confianza</h3>
        <p>Opiniones verificadas y usuarios comprometidos para una experiencia segura.</p>
    </div>
</div>
@endsection