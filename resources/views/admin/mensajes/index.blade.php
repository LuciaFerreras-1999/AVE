@extends('layouts.plantilla')

@section('titulo', 'Mensajes recibidos y Reportes')

@section('contenido')

<h2 class="text-xl font-bold mb-2">Mensajes</h2>
<table class="table mb-8">
    <thead>
        <tr>
            <th>Remitente</th>
            <th>Contenido</th>
            <th>Leído</th>
            <th>Fecha</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse($mensajes as $mensaje)
        <tr>
            <td>{{ $mensaje->emisor->name ?? $mensaje->nombre_emisor }}</td>
            <td>{{ Str::limit($mensaje->contenido, 50) }}</td>
            <td>{{ $mensaje->leido ? 'Sí' : 'No' }}</td>
            <td>{{ $mensaje->created_at->diffForHumans() }}</td>
            <td><a href="{{ route('admin.mensajes.show', $mensaje->id) }}" class="btn btn-sm btn-primary">Ver</a></td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="text-center">No se encontraron mensajes.</td>
        </tr>
        @endforelse
    </tbody>
</table>

{{ $mensajes->links() }}

<h2 class="text-xl font-bold mb-2">Reportes de Publicaciones</h2>
<table class="table">
    <thead>
        <tr>
            <th>Usuario que reporta</th>
            <th>Publicación</th>
            <th>Motivo</th>
            <th>Fecha</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse($reportes as $reporte)
        <tr>
            <td>{{ $reporte->usuarioReporta->name ?? 'Usuario eliminado' }}</td>
            <td>
                <a href="{{ route('prendas.mercado.show', ['usuario' => $reporte->prenda->user->name, 'prenda' => $reporte->prenda->slug]) }}" target="_blank">
                    {{ $reporte->prenda->nombre }}
                </a>
            </td>
            <td>{{ Str::limit($reporte->motivo, 80) }}</td>
            <td>{{ $reporte->created_at->diffForHumans() }}</td>
            <td>
                <form action="{{ route('admin.reportes.destroy', $reporte->id) }}" method="POST" onsubmit="return confirm('¿Eliminar reporte?');">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger">Eliminar</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="text-center">No hay reportes de publicaciones.</td>
        </tr>
        @endforelse
    </tbody>
</table>

{{ $reportes->links() }}

@endsection