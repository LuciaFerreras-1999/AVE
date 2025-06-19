<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reporte;
use App\Models\Prenda;

class ReporteController extends Controller
{
    /**
     * Guarda un nuevo reporte sobre una prenda específica.
     * Valida que el motivo del reporte (opcional) no supere los 1000 caracteres.
     * El reporte se asocia a la prenda y al usuario autenticado.
     */
    public function store(Request $request, Prenda $prenda)
    {
        $request->validate([
            'motivo' => 'nullable|string|max:1000',
        ]);

        Reporte::create([
            'prenda_id' => $prenda->id,
            'user_id' => auth()->id(),
            'motivo' => $request->motivo,
        ]);

        return redirect()->back()->with('success', 'Gracias por reportar esta publicación. El administrador revisará el contenido.');
    }

    /**
     * Elimina un reporte específico de la base de datos.
     * Usado normalmente por un administrador para gestionar reportes ya revisados.
     */
    public function destroy(Reporte $reporte)
    {
        $reporte->delete();

        return redirect()->back()->with('success', 'Reporte eliminado correctamente.');
    }
}
