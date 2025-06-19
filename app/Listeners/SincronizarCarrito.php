<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Models\Carrito;

class SincronizarCarrito
{

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    

    public function handle(Login $event)
    {
        $user = $event->user;

        $prendas = Carrito::with('prenda')
            ->where('user_id', $user->id)
            ->get()
            ->pluck('prenda')
            ->filter();

        session(['carrito' => $prendas]);
    }
}

