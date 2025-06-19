@extends('layouts.plantilla')

@section('titulo', 'Mis Favoritos')

@section('contenido')
<div class="max-w-4xl mx-auto my-8 px-4">
    <form action="{{ route('prendas.favoritos') }}" method="GET" id="buscador" class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex flex-col gap-6">

            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                <input
                    type="text"
                    name="search"
                    id="search"
                    value="{{ request('search') }}"
                    placeholder="Buscar por usuario o prenda"
                    class="w-full rounded-md border border-gray-300 shadow-sm focus:ring-2 focus:ring-accent focus:border-accent px-3 py-2">
            </div>

            <div class="mb-4">
                <button
                    type="button"
                    id="toggleFiltros"
                    class="text-accent text-sm font-medium hover:underline flex items-center gap-1 focus:outline-none">
                    <span>Filtros</span>
                    <span id="flecha">▼</span>
                </button>
            </div>

            <div id="filtrosOpciones" class="space-y-6">

                <div class="flex items-center">
                    <span class="w-32 text-sm font-medium text-gray-700">Estado</span>
                    <div class="grid-checks">

                        @foreach ($estados as $estado)
                        <label class="inline-flex items-center filtro-item p-2 rounded-md mb-1">
                            <input
                                type="checkbox"
                                name="estado[]"
                                value="{{ $estado }}"
                                {{ collect(request('estado'))->contains($estado) ? 'checked' : '' }}
                                class="text-black">
                            <span class="ml-2 text-black capitalize font-medium">{{ $estado }}</span>
                        </label>
                        @endforeach

                    </div>
                </div>
                <hr class="my-4 border-gray-300" />
                <div class="flex items-center">
                    <span class="w-32 text-sm font-medium text-gray-700">Talla</span>
                    <div class="grid-checks">

                        @foreach ($tallas as $talla)
                        <label class="inline-flex items-center filtro-item p-2 rounded-md mb-1">
                            <input
                                type="checkbox"
                                name="talla[]"
                                value="{{ $talla }}"
                                {{ collect(request('talla'))->contains($talla) ? 'checked' : '' }}
                                class="text-black">
                            <span class="ml-2 text-black capitalize font-medium">{{ $talla }}</span>
                        </label>
                        @endforeach

                    </div>
                </div>
                <hr class="my-4 border-gray-300" />
                <div class="flex items-center">
                    <span class="w-32 text-sm font-medium text-gray-700 mb-2">Marca</span>
                    <div class="grid-checks">

                        @foreach ($marcas as $marca)
                        <label class="inline-flex items-center filtro-item p-2 rounded-md mb-1">
                            <input
                                type="checkbox"
                                name="marca[]"
                                value="{{ $marca }}"
                                {{ collect(request('marca'))->contains($marca) ? 'checked' : '' }}
                                class="text-black">
                            <span class="ml-2 text-black capitalize font-medium">{{ $marca }}</span>
                        </label>
                        @endforeach

                    </div>
                </div>
                <hr class="my-4 border-gray-300" />
                <div class="flex items-center">
                    <span class="w-32 text-sm font-medium text-gray-700">Categoría</span>
                    <div class="grid-checks">

                        @foreach ($categorias as $categoria)
                        <label class="inline-flex items-center filtro-item p-2 rounded-md mb-1">
                            <input
                                type="checkbox"
                                name="categoria[]"
                                value="{{ $categoria->nombre }}"
                                {{ collect(request('categoria'))->contains($categoria->nombre) ? 'checked' : '' }}
                                class="text-black">
                            <span class="ml-2 text-black font-medium">{{ $categoria->nombre }}</span>
                        </label>
                        @endforeach

                    </div>
                </div>
                <button type="button" id="verMasTotal" class="text-accent text-sm mt-2 hidden" style="text-decoration: underline;">Ver todos</button>
            </div>
            <div>
                <button type="submit" class="btn btn-accent btn-sm mt-4 w-full md:w-auto">
                    Buscar
                </button>
            </div>
        </div>
    </form>
</div>

<div class="row" id="contenedorPrendas">

    @forelse($prendas as $prenda)
    <div class="col-md-3 mb-2">
        <div class="card">
            <img src="{{ $prenda->imagen ? asset('assets/imagenes/' . $prenda->imagen) : asset('images/default.jpg') }}"
                class="card-img-top"
                alt="{{ $prenda->nombre }}">
            <div class="card-body">
                <h5 class="card-title">{{ $prenda->nombre }}</h5>
                <p class="card-text">
                    <strong>Precio:</strong> €{{ number_format($prenda->precio, 2) }}
                </p>
                <p class="card-text text-muted">
                    <small>Publicado por: {{ $prenda->user->name }}</small>
                </p>
                <a href="{{ route('prendas.mercado.show', ['usuario' => $prenda->user->name, 'prenda' => $prenda->slug]) }}"
                    class="btn btn-accent btn-sm mt-2">Ver detalles</a>

                @auth
                <button
                    type="button"
                    aria-label="Favorito"
                    style="background:none; border:none; cursor:pointer;"
                    class="heart-toggle"
                    data-url="{{ route('favoritos.toggle', $prenda->slug) }}"
                    data-favorito="{{ $prenda->favoritoPorUsuario ? '1' : '0' }}">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        fill="{{ $prenda->favoritoPorUsuario ? 'red' : 'none' }}"
                        stroke="red"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        width="24"
                        height="24"
                        class="heart-icon">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                    </svg>
                </button>
                @endauth

            </div>
        </div>
    </div>
    @empty
    <p class="text-center w-full">No tienes prendas favoritas aún.</p>
    @endforelse

</div>

<div id="mensajeSinFavoritos" class="text-center w-full" style="display: none;">
    No tienes prendas favoritas aún.
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {

        const toggleButton = document.getElementById('toggleFiltros');
        const filtros = document.getElementById('filtrosOpciones');
        const flecha = document.getElementById('flecha');

        filtros.style.display = 'none';

        toggleButton.addEventListener('click', function() {
            const visible = filtros.style.display === 'block';
            filtros.style.display = visible ? 'none' : 'block';
            flecha.textContent = visible ? '▼' : '▲';
        });

        const LIMITE = 8;
        const btnVerMas = document.getElementById('verMasTotal');
        const filtros2 = document.querySelectorAll('#filtrosOpciones .grid-checks');
        let hayFiltroGrande = false;

        filtros2.forEach(filtro => {
            const items = filtro.querySelectorAll('.filtro-item');
            if (items.length > LIMITE) {
                hayFiltroGrande = true;
                items.forEach((item, i) => {
                    if (i >= LIMITE) item.style.display = 'none';
                });
            }
        });

        if (hayFiltroGrande) {
            btnVerMas.classList.remove('hidden');
            btnVerMas.textContent = 'Ver todos';
        } else {
            btnVerMas.classList.add('hidden');
        }

        btnVerMas.addEventListener('click', () => {
            const ocultos = Array.from(document.querySelectorAll('#filtrosOpciones .filtro-item')).filter(item => item.style.display === 'none');
            if (ocultos.length > 0) {
                filtros2.forEach(filtro => {
                    filtro.querySelectorAll('.filtro-item').forEach(item => item.style.display = '');
                });
                btnVerMas.textContent = 'Ver menos';
            } else {
                filtros2.forEach(filtro => {
                    filtro.querySelectorAll('.filtro-item').forEach((item, i) => {
                        if (i >= LIMITE) item.style.display = 'none';
                    });
                });
                btnVerMas.textContent = 'Ver todos';
            }
        });

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        const contenedorPrendas = document.getElementById('contenedorPrendas');
        const mensajeSinFavoritos = document.getElementById('mensajeSinFavoritos');

        document.querySelectorAll('.heart-toggle').forEach(button => {
            button.addEventListener('click', async () => {
                try {
                    const response = await fetch(button.dataset.url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({})
                    });

                    if (!response.ok) throw new Error('Error en la petición');

                    const data = await response.json();
                    const svg = button.querySelector('svg');

                    if (data.favorito) {
                        svg.setAttribute('fill', 'red');
                        button.dataset.favorito = '1';
                    } else {
                        svg.setAttribute('fill', 'none');
                        button.dataset.favorito = '0';

                        const card = button.closest('.col-md-3');
                        if (card) card.remove();

                        if (contenedorPrendas.children.length === 0) {
                            mensajeSinFavoritos.style.display = 'block';
                        }
                    }
                } catch (error) {
                    alert('No se pudo actualizar el favorito. Intenta más tarde.');
                    console.error(error);
                }
            });
        });
    });
</script>
@endsection