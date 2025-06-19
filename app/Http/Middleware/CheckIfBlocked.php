<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckIfBlocked
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        if (auth()->check() && auth()->user()->bloqueado) {
            $allowedRoutes = [
                'prendas.index',
                'contacto',
                'logout',
            ];

            $currentRouteName = $request->route()->getName();

            if (!in_array($currentRouteName, $allowedRoutes)) {
                return redirect()->route('prendas.index')
                    ->with('error', 'Tu cuenta está bloqueada. Solo puedes acceder a tu armario y contacto.');
            }
        }

        return $next($request);
    }
}
