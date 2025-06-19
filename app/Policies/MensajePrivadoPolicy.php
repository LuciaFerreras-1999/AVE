<?php

namespace App\Policies;

use App\Models\MensajePrivado;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MensajePrivadoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MensajePrivado $mensaje)
    {
        return $user->id === $mensaje->emisor_id || $user->id === $mensaje->receptor_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MensajePrivado $mensajePrivado): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MensajePrivado $mensajePrivado): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, MensajePrivado $mensajePrivado): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, MensajePrivado $mensajePrivado): bool
    {
        //
    }
}
