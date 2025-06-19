<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mensaje;
use Illuminate\Support\Facades\Mail;
use App\Models\Reporte;

class MensajeController extends Controller
{
    /**
     * Muestra la lista de mensajes recibidos, con filtro por nombre del emisor,
     * y también muestra los reportes de usuarios.
     */
    public function index(Request $request)
    {
        $query = Mensaje::with('emisor')->latest();

        if ($request->filled('filtro')) {
            $filtro = $request->input('filtro');

            $query->where(function ($q) use ($filtro) {
                $q->whereHas('emisor', function ($subQ) use ($filtro) {
                    $subQ->where('name', 'like', "%{$filtro}%");
                })->orWhere(function ($subQ) use ($filtro) {
                    $subQ->whereNull('emisor_id')
                        ->where('nombre_emisor', 'like', "%{$filtro}%");
                });
            });
        }

        $mensajes = $query->paginate(10)->appends(['filtro' => $request->filtro]);

        $reportes = Reporte::with(['usuarioReporta', 'prenda.user'])
            ->latest()
            ->paginate(10);

        $breadcrumb = [
            ['label' => 'Inicio', 'url' => route('home')],
            ['label' => 'Mensajes y reportes', 'url' => route('admin.mensajes.index')],
        ];

        return view('admin.mensajes.index', compact('mensajes', 'reportes', 'breadcrumb'));
    }

    /**
     * Muestra un mensaje individual y lo marca como leído.
     */
    public function show(Mensaje $mensaje)
    {
        $mensaje->update(['leido' => true]);

        $emisor = $mensaje->emisor;

        $breadcrumb = [
            ['label' => 'Inicio', 'url' => route('home')],
            ['label' => 'Mensajes y reportes', 'url' => route('admin.mensajes.index')],
            ['label' => 'Mensaje de ' . ($emisor->name)],
        ];

        return view('admin.mensajes.show', compact('mensaje', 'breadcrumb'));
    }

    /**
     * Envía una respuesta por correo al emisor del mensaje, ya sea registrado o anónimo.
     */
    public function responder(Request $request, Mensaje $mensaje)
    {
        $request->validate(['respuesta' => 'required|string']);

        if ($mensaje->emisor_id) {
            $email = $mensaje->remitente->email;
        } else {
            $email = $mensaje->email_emisor;
        }

        if ($email) {
            Mail::raw($request->respuesta, function ($mail) use ($email) {
                $mail->to($email)
                    ->subject('Respuesta a tu mensaje');
            });
        }

        return redirect()->route('admin.mensajes.index')->with('success', 'Respuesta enviada correctamente.');
    }
}
