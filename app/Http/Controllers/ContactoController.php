<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mensaje;
use App\Models\User;
use App\Mail\ContactoAdminMail;
use Illuminate\Support\Facades\Mail;

class ContactoController extends Controller
{
    public function __construct() {}

    /**
     * Muestra la vista del formulario de contacto con el breadcrumb correspondiente.
     */
    public function index()
    {
        $breadcrumb = [
            ['label' => 'Inicio', 'url' => route('home')],
            ['label' => 'Ayuda', 'url' => route('contacto')],
        ];
        return view('contacto', compact('breadcrumb'));
    }

    /**
     * Valida y procesa el envío del mensaje de contacto,
     * guarda el mensaje en base de datos y envía un email a los administradores.
     */
    public function enviar(Request $request)
    {
        $adminUsers = \App\Models\User::role('admin')->get();

        if ($adminUsers->isEmpty()) {
            return back()->withErrors('No se encontró un administrador para contactar.');
        }

        $request->validate([
            'nombre' => 'required|string|max:100',
            'email' => 'required|email',
            'contenido' => 'required|string|max:1000',
        ]);

        Mensaje::create([
            'emisor_id' => auth()->id(),
            'receptor_id' => null,
            'nombre_emisor' => $request->nombre,
            'email_emisor' => $request->email,
            'contenido' => $request->contenido,
        ]);

        $emails = $adminUsers->pluck('email')->toArray();

        Mail::to($emails)->send(new ContactoAdminMail(
            $request->nombre,
            $request->email,
            $request->contenido
        ));

        return back()->with('success', 'Tu mensaje ha sido enviado al administrador. ¡Gracias por contactarnos!');
    }
}
