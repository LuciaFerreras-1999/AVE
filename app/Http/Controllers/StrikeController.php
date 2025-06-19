<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\MensajePrivado;
use Illuminate\Support\Facades\Auth;

class StrikeController extends Controller
{
    /**
     * Aplica un strike al usuario especificado.
     * Si el usuario alcanza 3 strikes, se bloquea su cuenta.
     * Se envía un mensaje privado al usuario informándole de la acción.
     */
    public function aplicarStrike(User $user)
    {
        $user->strikes()->create([
            'motivo' => 'Strike por incumplimiento de normas',
        ]);

        $cantidadStrikes = $user->strikes()->count();
        $strikesRestantes = 3 - $cantidadStrikes;

        if ($cantidadStrikes >= 3 && !$user->bloqueado) {
            $user->update(['bloqueado' => true]);

            $mensajeTexto = 'Has alcanzado el límite de 3 strikes. Tu cuenta ha sido bloqueada temporalmente.';
        } else {
            $mensajeTexto = "Has recibido un strike por incumplimiento de normas. Te quedan {$strikesRestantes} strike(s) antes del bloqueo de tu cuenta.";
        }

        MensajePrivado::create([
            'emisor_id' => Auth::id(),
            'receptor_id' => $user->id,
            'mensaje' => $mensajeTexto,
            'es_strike' => true
        ]);

        return back()->with('success', "Strike aplicado. Usuario tiene ahora $cantidadStrikes strike(s).");
    }

    /**
     * Desbloquea al usuario especificado y elimina todos sus strikes.
     * También se envía un mensaje privado informando del desbloqueo.
     */
    public function desbloquear(User $user)
    {
        if ($user->bloqueado) {
            $user->strikes()->delete();

            $user->update(['bloqueado' => false]);

            MensajePrivado::create([
                'emisor_id' => Auth::id(),
                'receptor_id' => $user->id,
                'mensaje' => 'Tu cuenta ha sido desbloqueada y el conteo de strikes se ha reiniciado.',
                'es_strike' => true
            ]);
        }

        return back()->with('success', 'Usuario desbloqueado y strikes reiniciados.');
    }
}
