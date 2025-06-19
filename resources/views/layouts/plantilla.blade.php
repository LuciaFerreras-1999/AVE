<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield("titulo")</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />

    @vite("resources/css/app.css")
</head>

<body>
    <div
        class="sidebar fixed top-0 left-0 h-screen w-64 p-4 overflow-y-auto transform transition-transform duration-300 md:translate-x-0 -translate-x-full z-40" id="sidebar">

        <div class="flex justify-center mb-4">
            <img src="{{ asset('assets/logo/logo_ave.png') }}" alt="Logo AVE" class="h-16">
        </div>

        @auth

        @php
        $usuario = Auth::user();
        $bloqueado = $usuario->bloqueado;
        $count = \App\Models\MensajePrivado::where('receptor_id', $usuario->id)->where('leido', false)->count();
        @endphp

        <div class="text-white text-center mb-3 perfil-link">
            <div class="flex items-center justify-center space-x-2">
                <img src="{{ $usuario->avatar ? asset('assets/imagenes/' . $usuario->avatar) : asset('assets/logo/default-avatar.png') }}"
                    alt="Avatar"
                    class="w-10 h-10 rounded-full object-cover border border-white shadow">
                <div class="text-left">
                    <a href="{{ route('perfil.propio') }}" class="text-sm text-blue-300 no-sidebar-hover">
                        {{ $usuario->name }}
                    </a>
                </div>
            </div>
        </div>

        <a href="{{ route('prendas.index') }}">Mi armario</a>
        <a href="{{ route('prendas.favoritos') }}">Favoritos</a>

        @if(!$bloqueado)
        <a href="{{ route('chats.index') }}">
            Chats

            @if($count > 0)
            <span class="badge badge-pill badge-danger">{{ $count }}</span>
            @endif

        </a>
        <a href="{{ route('carrito.index') }}">Carrito</a>
        <a href="{{ route('prendas.mercado.index') }}">Mercado</a>

        @role('admin')
        <a href="{{ route('admin.mensajes.index') }}">Mensajes y reportes</a>
        <a href="{{ route('usuarios-ajax-crud.index') }}">Gestión de Usuarios</a>
        <a href="{{ route('prendas-ajax-crud.index') }}">Gestión de Prendas</a>
        @endrole

        @endif

        <a href="{{ route('contacto') }}">Ayuda / Contactar Soporte</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-accent btn-sm mt-4 w-100">Cerrar sesión</button>
        </form>
        @endauth

        @guest
        <a href="{{ route('home') }}">Inicio</a>
        <a href="{{ route('prendas.mercado.index') }}">Mercado</a>
        <a href="{{ route('contacto') }}">Contáctenos</a>
        <a href="{{ route('login') }}" class="btn btn-accent btn-sm mt-4 w-100 hover:bg-[#e76f51] transition-colors duration-300">
            Iniciar sesión
        </a>
        @endguest

    </div>

    <button id="toggleSidebar" class="md:hidden fixed top-4 left-4 z-50 bg-[#264653] text-white p-2 rounded shadow">
        <i class="fas fa-bars"></i>
    </button>

    <div class="main-content">

        @if (isset($breadcrumb) && is_array($breadcrumb))
        <nav class="text-sm text-gray-600 mb-4 mt-2" aria-label="Breadcrumb">
            <ol class="list-reset flex">

                @foreach ($breadcrumb as $index => $item)
                <li>

                    @if (isset($item['url']))
                    <a href="{{ $item['url'] }}" class="text-blue-500 hover:underline">
                        {{ $item['label'] }}
                    </a>
                    @else
                    <span class="text-gray-700">{{ $item['label'] }}</span>
                    @endif

                    @if (!$loop->last)
                    <span class="mx-2">/</span>
                    @endif

                </li>
                @endforeach

            </ol>
        </nav>
        @endif

        @if (!View::getSection('sinHeader'))
        <div class="header">
            <h1 class="h4">@yield('titulo')</h1>
        </div>
        @endif

        <div class="container mx-auto px-4">
            @yield("contenido")

            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
            <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
            <script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.js"></script>

            @yield('scripts')
        </div>
    </div>

    @include('layouts.partials.footer')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const toggleBtn = document.getElementById('toggleSidebar');
            const sidebar = document.getElementById('sidebar');
            const footer = document.getElementById('footer');

            function ajustarFooter() {
                const isSmallScreen = window.innerWidth <= 768;
                if (!isSmallScreen) {
                    footer.style.marginLeft = '220px';
                    footer.style.width = 'auto';
                    return;
                }

                if (sidebar.classList.contains('translate-x-0')) {
                    footer.style.marginLeft = '220px';
                    footer.style.width = 'auto';
                } else {
                    footer.style.marginLeft = '0';
                    footer.style.width = '100%';
                }
            }

            toggleBtn.addEventListener('click', () => {
                sidebar.classList.toggle('-translate-x-full');
                sidebar.classList.toggle('translate-x-0');
                ajustarFooter();
            });

            window.addEventListener('resize', ajustarFooter);

            ajustarFooter();
        });
    </script>
</body>

</html>