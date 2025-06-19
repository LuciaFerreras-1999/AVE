@extends('layouts.plantilla')

@section('titulo', 'Perfil de ' . $user->name)

@section('contenido')
<div class="max-w-4xl mx-auto mt-8 bg-white p-6 rounded shadow">

    @auth
    @role('admin')
    <div class="flex flex-wrap items-center justify-start gap-4 mb-6">

        @if(!$user->bloqueado)
        <form action="{{ route('usuario.aplicarStrike', $user) }}" method="POST" class="inline-block">
            @csrf
            <button type="submit" class="btn btn-danger">Aplicar Strike</button>
        </form>
        @else
        <form action="{{ route('usuario.desbloquear', $user) }}" method="POST" class="inline-block">
            @csrf
            <button type="submit" class="btn btn-edit">Desbloquear</button>
        </form>
        @endif

        <p class="text-sm text-muted mb-0 ml-2">
            Strikes actuales: <strong>{{ $user->strikes->count() }}/3</strong>
        </p>

        @if(!$user->hasRole('admin'))
        <form action="{{ route('usuario.darRolAdmin', $user) }}" method="POST" class="inline-block ml-auto">
            @csrf
            <button type="submit" class="btn btn-edit">Dar rol de Admin</button>
        </form>
        @else
        <form action="{{ route('usuario.quitarRolAdmin', $user) }}" method="POST" class="inline-block ml-auto">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Quitar rol de Admin</button>
        </form>
        @endif

    </div>
    @endrole
    @endauth

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

    @auth

    @if(auth()->id() !== $user->id)

    <div class="mb-4 d-flex justify-content-center">
        <a href="{{ route('chats.conversacion', ['user' => $user]) }}" class="btn btn-accent btn-sm mt-4">
            Chatear con {{ $user->name }}
        </a>
    </div>
    <div class="mt-6 mb-4">
        <h4 class="text-lg font-semibold mb-2">Valorar a {{ $user->name }}</h4>
        <form action="{{ route('usuarios.valorar', $user) }}" method="POST" class="space-y-2">
            @csrf
            <div class="flex items-center space-x-2">

                @for($i = 1; $i <= 5; $i++)
                    <label>
                    <input type="radio" name="puntuacion" value="{{ $i }}" class="hidden" required>
                    <svg class="w-6 h-6 cursor-pointer text-gray-300 hover:text-yellow-500 star"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor"
                        viewBox="0 0 24 24">
                        <path d="M12 .587l3.668 7.431L24 9.748l-6 5.847 1.416 8.268L12 18.896l-7.416 4.967L6 15.595 0 9.748l8.332-1.73z" />
                    </svg>
                    </label>
                    @endfor

            </div>

            <textarea name="comentario" rows="2" placeholder="Comentario (opcional)" class="form-control"></textarea>

            <button type="submit" class="btn btn-accent btn-sm">Enviar valoración</button>
        </form>
    </div>

    @endif

    @endauth

    <h3 class="text-xl font-semibold mb-4">Productos publicados</h3>

    @if($prendas->isEmpty())
    <p class="text-gray-600">Este usuario no ha publicado productos.</p>
    @else
    <div class="row">

        @forelse($prendas as $prenda)
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm">
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
                    <a href="{{ route('prendas.mercado.show', ['usuario' => $prenda->user->name, 'prenda' => $prenda->slug, 'from' => 'perfil']) }}"
                        class="btn btn-accent btn-sm mt-2">Ver detalles</a>

                    @auth

                    @if(Auth::id() !== $prenda->user_id)
                    <button
                        type="button"
                        aria-label="Favorito"
                        style="background:none; border:none; cursor:pointer;"
                        class="heart-toggle"
                        data-url="{{ route('favoritos.toggle', $prenda->slug) }}"
                        data-id="{{ $prenda->id }}"
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
                    @endif

                    @endauth

                </div>
            </div>
        </div>
        @empty
        <p class="text-center">No hay prendas publicadas en el mercado.</p>
        @endforelse

    </div>

    <div class="mt-4">
        {{ $prendas->links() }}
    </div>

    @if($valoraciones->isNotEmpty())
    <div class="mt-8">
        <h3 class="text-xl font-semibold mb-4">Comentarios recibidos</h3>

        @foreach($valoraciones as $valoracion)
        <div class="border rounded p-4 mb-4 bg-gray-50">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center space-x-2">
                    @if($valoracion->usuario)
                    <a href="{{ route('perfil.publico', $valoracion->usuario->name) }}" class="font-semibold hover:underline">
                        {{ $valoracion->usuario->name }}
                    </a>
                    @else
                    <span>Usuario eliminado</span>
                    @endif

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
    <p class="mt-8 text-gray-600">Aún no tiene comentarios.</p>
    @endif

    @endif

</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        document.querySelectorAll('.heart-toggle').forEach(button => {
            button.addEventListener('click', function() {
                const url = this.dataset.url;
                const svg = this.querySelector('svg');

                fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({})
                    })
                    .then(response => response.json())
                    .then(data => {
                        svg.setAttribute('fill', data.favorito ? 'red' : 'none');
                    })
                    .catch(error => {
                        alert('Error al actualizar favorito');
                        console.error(error);
                    });
            });
        });
    });
</script>

<script>
    document.querySelectorAll('.star').forEach((star, index, stars) => {
        star.addEventListener('click', () => {
            document.querySelectorAll('input[name="puntuacion"]')[index].checked = true;
            stars.forEach((s, i) => {
                s.classList.toggle('text-yellow-400', i <= index);
                s.classList.toggle('text-gray-300', i > index);
            });
        });
    });
</script>
@endsection