@extends('layouts.plantilla')

@section('titulo', 'Mi Armario')

@section('contenido')
<div class="max-w-4xl mx-auto my-8 px-4">

    <form action="{{ route('prendas.index') }}" method="GET" id="buscador" class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex flex-col gap-6">

            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                <input
                    type="text"
                    name="search"
                    id="search"
                    value="{{ request('search') }}"
                    placeholder="Buscar por nombre"
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

<div class="mb-4 d-flex justify-content-center">
    <a href="{{ route('prendas.create') }}" class="btn btn-accent btn-sm mt-4">Añadir Artículo</a>
</div>
<div class="row">

    @forelse($prendas as $prenda)
    <div class="col-12 col-sm-6 col-md-3 d-flex justify-content-center mb-4">
        <div class="card shadow-md rounded-lg overflow-hidden border-0" style="width: 18rem;">
            <img src="{{ $prenda->imagen ? asset('assets/imagenes/' . $prenda->imagen) : asset('images/default.jpg') }}"
                class="card-img-top"
                alt="{{ $prenda->nombre }}">
            <div class="card-body text-center">
                <h5 class="card-title font-weight-bold">{{ $prenda->nombre }}</h5>

                <a href="{{ route('prendas.show', $prenda->slug) }}" class="btn btn-accent btn-sm mt-4">
                    Ver detalles
                </a>
            </div>
        </div>
    </div>
    @empty
    <p class="text-center">No se encontraron prendas que coincidan con los filtros.</p>
    @endforelse

</div>
<div class="mt-6">
    {{ $prendas->links() }}
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
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
    });
</script>

@endsection