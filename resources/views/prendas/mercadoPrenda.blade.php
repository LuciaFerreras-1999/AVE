@extends('layouts.plantilla')

@section('titulo', $prenda->nombre)

@section('contenido')

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="container mt-5">
    <div class="row ml-5">
        <div class="col-md-4">
            <img id="prendaImage" src="{{ $prenda->imagen ? asset('assets/imagenes/' . $prenda->imagen) : asset('images/default.jpg') }}"
                class="img-fluid" alt="{{ $prenda->imagen }}" style="cursor: crosshair;">
            <canvas id="colorCanvas" style="display: none;"></canvas>
        </div>

        <div class="col-md-8">
            <p><strong>Vendedor:</strong>
                <a href="{{ route('perfil.publico', [
        'nombre' => $prenda->user->name,
        'from' => 'prenda',
        'prenda_id' => $prenda->id,
        'usuario' => $prenda->user->name
    ]) }}" class="text-blue-600 hover:underline">
                    {{ $prenda->user->name }}
                </a>
            </p>

            <p><strong>Categoría:</strong>

                @if($prenda->categorias->isNotEmpty())

                @foreach($prenda->categorias as $categoria)
                <span class="badge bg-secondary">{{ $categoria->nombre }}</span>
                @endforeach

                @else
                <span class="text-muted">Sin categoría</span>
                @endif

            </p>

            <p><strong>Descripción:</strong> {{ $prenda->descripcion }}</p>
            <p><strong>Talla:</strong> {{ $prenda->talla }}</p>
            <p><strong>Marca:</strong> {{ $prenda->marca }}</p>
            <p><strong>Estado:</strong> {{ ucfirst($prenda->estado) }}</p>

            <p><strong>Precio:</strong>
                @if($prenda->publicada)
                €{{ number_format($prenda->precio, 2) }}
                @else
                <span class="text-muted">No publicado</span>
                @endif
            </p>

            <div class="mt-3">
                <label><strong>Color seleccionado:</strong></label>
                <p id="colorCode">#000000</p>
                <div id="colorPreview" style="width: 50px; height: 50px; background-color: #000000; border: 1px solid #ccc;"></div>
            </div>

            @auth

            @if(auth()->id() === $prenda->user_id)
            {{-- Botón publicar/quitar --}}
            <button id="publicarBtn" class="btn mt-3 {{ $prenda->publicada ? 'btn-danger' : 'btn-accent' }}">
                {{ $prenda->publicada ? 'Quitar del mercado' : 'Poner en venta' }}
            </button>

            <form id="publicarForm" action="{{ route('prendas.mercado.store', ['usuario' => $prenda->user->id, 'prenda' => $prenda->id]) }}" method="POST" style="display:none;">
                @csrf
                <input type="hidden" name="prenda_id" value="{{ $prenda->id }}">
                <input type="hidden" name="precio" id="precioInput" value="{{ $prenda->publicada ? '' : old('precio') }}">
                <input type="hidden" name="accion" id="accionInput" value="{{ $prenda->publicada ? 'despublicar' : 'publicar' }}">
            </form>

            @elseif($prenda->publicada)

            @if($prenda->vendido)
            <p class="text-danger fw-bold mt-4">Este producto ya ha sido vendido.</p>
            @else

            @php
            $enCarrito = session('carrito', collect())->contains('id', $prenda->id);
            $esAdmin = auth()->user()->es_admin ?? false;
            @endphp

            @if(!$esAdmin || empty($reportada))
            <div id="reporteContainer" class="mt-4">

                @if(!empty($reportada))
                <p class="text-danger fw-bold">Esta publicación ha sido reportada.</p>
                @else
                
                @if(!$enCarrito)
                <form action="{{ route('carrito.agregar') }}" method="POST" class="mt-3">
                    @csrf
                    <input type="hidden" name="prenda_id" value="{{ $prenda->id }}">
                    <button id="btnAddCarrito" type="submit" class="btn btn-accent">Añadir al carrito</button>
                </form>
                @else
                <form action="{{ route('carrito.quitar', $prenda) }}" method="POST" class="mt-3">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Quitar del carrito</button>
                </form>
                @endif

                <button id="btnReportar" class="btn btn-reportar mt-2">Reportar publicación</button>

                <form id="formReportar" action="{{ route('prendas.reportar', $prenda) }}" method="POST" style="display:none;" class="mt-3">
                    @csrf
                    <label for="motivo">Motivo del reporte:</label>
                    <textarea name="motivo" id="motivo" rows="3" class="form-control" required></textarea>
                    <button id="btnEnviarReporte" type="submit" class="btn btn-danger mt-2">Enviar reporte</button>
                </form>
                @endif

            </div>
            @endif

            @endif

            @else
            <p class="text-muted mt-3">Esta prenda no está a la venta.</p>
            @endif

            @else
            <p class="mt-3"><a href="{{ route('login') }}">Inicia sesión</a> para comprar o publicar prendas.</p>
            @endauth

        </div>
    </div>

    <a href="{{ route('prendas.mercado.index') }}" class="btn btn-secondary mt-3">Volver</a>

    @auth

    @if(auth()->id() === $prenda->user_id)
    <a href="{{ route('prendas.edit', $prenda->slug) }}" class="btn btn-edit mt-3">Editar Prenda</a>
    @endif

    @endauth

    @auth
    <div class="mt-5">
        <h4>Deja un comentario</h4>

        <form action="{{ route('prendas.comentar', $prenda) }}" method="POST">
            @csrf
            <div class="mb-3">
                <textarea name="contenido" class="form-control @error('contenido') is-invalid @enderror" rows="3" placeholder="Escribe tu comentario aquí..." required>{{ old('contenido') }}</textarea>
                @error('contenido')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-accent">Publicar comentario</button>
        </form>
    </div>
    @else
    <p class="mt-4"><a href="{{ route('login') }}">Inicia sesión</a> para dejar un comentario.</p>
    @endauth

    @if($prenda->comentarios->isNotEmpty())
    <div class="mt-5">
        <h4>Comentarios ({{ $prenda->comentarios->count() }})</h4>
        <ul class="list-group">

            @foreach($prenda->comentarios as $comentario)
            <li class="list-group-item">
                <strong>{{ $comentario->user->name }}</strong>
                <span class="text-muted float-end">{{ $comentario->created_at->diffForHumans() }}</span>
                <p class="mb-0 mt-2">{{ $comentario->contenido }}</p>
            </li>
            @endforeach
            
        </ul>
    </div>
    @else
    <p class="mt-4 text-muted">No hay comentarios todavía. ¡Sé el primero en comentar!</p>
    @endif

</div>
@endsection

@section('scripts')
<script src="{{ asset('js/ntc.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const img = document.getElementById('prendaImage');
        const canvas = document.getElementById('colorCanvas');
        const ctx = canvas.getContext('2d');
        const colorCode = document.getElementById('colorCode');
        const colorPreview = document.getElementById('colorPreview');

        function actualizarCanvas() {
            canvas.width = img.naturalWidth;
            canvas.height = img.naturalHeight;
            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
            console.log(`Canvas actualizado a ${canvas.width}x${canvas.height}`);
        }

        if (img.complete && img.naturalWidth !== 0) {
            actualizarCanvas();
        } else {
            img.addEventListener('load', actualizarCanvas);
        }

        img.addEventListener('click', (e) => {
            const rect = img.getBoundingClientRect();

            const scaleX = img.naturalWidth / rect.width;
            const scaleY = img.naturalHeight / rect.height;

            const x = Math.floor((e.clientX - rect.left) * scaleX);
            const y = Math.floor((e.clientY - rect.top) * scaleY);

            console.log(`Clic detectado en coordenadas: X=${x}, Y=${y}`);

            if (x >= 0 && x < canvas.width && y >= 0 && y < canvas.height) {
                const pixelData = ctx.getImageData(x, y, 1, 1).data;
                const alpha = pixelData[3];

                console.log(`Pixel data:`, pixelData);
                console.log(`Alpha: ${alpha}`);

                if (alpha !== 0) {
                    const hexColor = `#${((1 << 24) + (pixelData[0] << 16) + (pixelData[1] << 8) + pixelData[2]).toString(16).slice(1).toUpperCase()}`;
                    colorCode.textContent = hexColor;
                    colorPreview.style.backgroundColor = hexColor;

                    const colorName = ntc.name(hexColor)[1];
                    console.log("Nombre del color:", colorName);

                    let colorNameElement = document.getElementById('colorName');
                    if (!colorNameElement) {
                        colorNameElement = document.createElement('p');
                        colorNameElement.id = 'colorName';
                        colorCode.parentElement.appendChild(colorNameElement);
                    }
                    colorNameElement.innerHTML = `<strong>Nombre del color:</strong> ${colorName}`;

                } else {
                    alert("El área seleccionada es transparente o fuera de la imagen.");
                }
            } else {
                alert("Coordenadas fuera de los límites de la imagen.");
            }
        });

        const publicarBtn = document.getElementById('publicarBtn');
        if (publicarBtn) {
            publicarBtn.addEventListener('click', function() {
                const accionActual = document.getElementById('accionInput').value;

                if (accionActual === 'publicar') {
                    const precio = prompt("Introduce el precio del producto:");
                    if (precio !== null && precio.trim() !== "" && !isNaN(precio)) {
                        document.getElementById('precioInput').value = parseFloat(precio).toFixed(2);
                        document.getElementById('accionInput').value = 'publicar';
                        document.getElementById('publicarForm').submit();
                    } else {
                        alert("Debes introducir un precio válido.");
                    }
                } else if (accionActual === 'despublicar') {
                    if (confirm("¿Quieres quitar esta prenda del mercado?")) {
                        document.getElementById('precioInput').value = '';
                        document.getElementById('accionInput').value = 'despublicar';
                        document.getElementById('publicarForm').submit();
                    }
                }
            });
        }

        const btnReportar = document.getElementById('btnReportar');
        const formReportar = document.getElementById('formReportar');
        const btnEnviarReporte = document.getElementById('btnEnviarReporte');
        const btnAddCarrito = document.getElementById('btnAddCarrito');

        if (btnReportar && formReportar && btnEnviarReporte) {
            let reporteVisible = false;

            btnReportar.addEventListener('click', () => {
                reporteVisible = !reporteVisible;

                if (reporteVisible) {
                    formReportar.style.display = 'block';
                    btnReportar.textContent = 'Anular reporte';
                } else {
                    formReportar.style.display = 'none';
                    btnReportar.textContent = 'Reportar publicación';
                }
            });

            btnEnviarReporte.addEventListener('click', () => {
                btnReportar.style.display = 'none';
                if (btnAddCarrito) btnAddCarrito.style.display = 'none';
            });
        }
    });
</script>
@endsection