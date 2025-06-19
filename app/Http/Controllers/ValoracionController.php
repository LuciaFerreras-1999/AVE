<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Valoracion;
use App\Models\User;

class ValoracionController extends Controller
{
    /**
     * Guarda o actualiza la valoración que el usuario autenticado realiza sobre otro usuario.
     *
     * @param Request $request Datos enviados con la puntuación y comentario.
     * @param User $user Usuario que será valorado.
     * @return \Illuminate\Http\RedirectResponse Redirecciona con mensaje de éxito.
     */
    public function store(Request $request, User $user)
    {
        $request->validate([
            'puntuacion' => 'required|integer|min:1|max:5',
            'comentario' => 'nullable|string|max:500',
        ]);

        $valoracion = Valoracion::updateOrCreate(
            ['valorador_id' => auth()->id(), 'valorado_id' => $user->id],
            ['puntuacion' => $request->puntuacion, 'comentario' => $request->comentario]
        );

        return back()->with('success', 'Valoración enviada.');
    }
}
