@extends('layouts.plantilla')

@section('titulo', $prenda->nombre )

@section('contenido')
@php
$publicada = $prenda->publicada ?? false;
@endphp

<div class="container mt-5">
    <div class="row ml-5">
        <div class="col-md-4">
            <img id="prendaImage" src="{{ $prenda->imagen ? asset('assets/imagenes/' . $prenda->imagen) : asset('images/default.jpg') }}" class="img-fluid" alt="{{ $prenda->imagen }}" style="cursor: crosshair;">
            <canvas id="colorCanvas" style="display: none;"></canvas>
        </div>

        <div class="col-md-6">

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

            <div class="mt-3">
                <label><strong>Color seleccionado:</strong></label>
                <p id="colorCode">#000000</p>
                <div id="colorPreview" style="width: 50px; height: 50px; background-color: #000000; border: 1px solid #ccc;"></div>

            </div>

            @php $bloqueado = Auth::user()->bloqueado; @endphp

            @if (!$bloqueado)
            <button id="publicarBtn" class="btn btn-accent mt-3 {{ $publicada ? 'btn-danger' : 'btn-success' }}">
                {{ $publicada ? 'Quitar del mercado' : 'Poner en venta' }}
            </button>

            <form id="publicarForm" action="{{ route('prendas.mercado.store') }}" method="POST" style="display:none;">
                @csrf
                <input type="hidden" name="precio" id="precioInput" value="">
                <input type="hidden" name="prenda_id" value="{{ $prenda->id }}">
                <input type="hidden" name="accion" id="accionInput" value="{{ $publicada ? 'despublicar' : 'publicar' }}">
            </form>
            @else
            <div class="alert alert-warning mt-3">
                <strong>Restricción:</strong> Tu cuenta está bloqueada. No puedes poner prendas en venta hasta que se resuelva esta situación.
            </div>
            @endif

        </div>
    </div>

    <a href="{{ route('prendas.index') }}" class="btn btn-secondary mt-3">Volver</a>
    <a href="{{ route('prendas.edit', $prenda->slug) }}" class="btn btn-edit mt-3">Editar Prenda</a>
    <form action="{{ route('prendas.destroy', $prenda->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta prenda? Esta acción no se puede deshacer.');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger mt-3">Eliminar Prenda</button>
    </form>

</div>
@endsection

@section('scripts')
<script src="{{ asset('js/ntc.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        publicarBtn.addEventListener('click', function() {
            const accionInput = document.getElementById('accionInput');
            const precioInput = document.getElementById('precioInput');
            const btn = this;

            if (accionInput.value === 'publicar') {
                Swal.fire({
                    title: 'Introduce el precio del producto',
                    input: 'text',
                    inputLabel: 'Precio en euros',
                    inputPlaceholder: 'Ejemplo: 19.99',
                    showCancelButton: true,
                    confirmButtonText: 'Aceptar',
                    cancelButtonText: 'Cancelar',
                    inputValidator: (value) => {
                        if (!value) {
                            return '¡Debes introducir un precio!';
                        }
                        if (isNaN(value) || parseFloat(value) <= 0) {
                            return 'Introduce un precio válido mayor a 0';
                        }
                        return null;
                    },
                    customClass: {
                        confirmButton: 'btn btn-accent me-2',
                        cancelButton: 'btn btn-secondary'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        const precio = parseFloat(result.value).toFixed(2);
                        precioInput.value = precio;
                        accionInput.value = 'publicar';
                        btn.textContent = 'Quitar del mercado';
                        btn.classList.remove('btn-success');
                        btn.classList.add('btn-danger');
                        document.getElementById('publicarForm').submit();
                    }
                });
            } else if (accionInput.value === 'despublicar') {
                if (confirm("¿Quieres quitar esta prenda del mercado?")) {
                    precioInput.value = '';
                    accionInput.value = 'despublicar';
                    btn.textContent = 'Poner en venta';
                    btn.classList.remove('btn-danger');
                    btn.classList.add('btn-success');
                    document.getElementById('publicarForm').submit();
                }
            }
        });
    });
</script>

@endsection