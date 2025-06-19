<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prenda;
use Illuminate\Support\Facades\Log;
use App\Models\Categoria;

class FavoritoController extends Controller
{
    /**
     * Requiere que el usuario esté autenticado para acceder a cualquier método del controlador.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Alterna el estado de favorito de una prenda para el usuario autenticado.
     * Si ya es favorito, lo elimina; si no, lo agrega.
     * Devuelve respuesta JSON si se solicita.
     */
    public function toggle(Request $request, Prenda $prenda)
    {
        $user = auth()->user();

        if (!$user) {
            abort(403, 'Usuario no autenticado');
        }

        if (!method_exists($user, 'favoritos')) {
            Log::error("El modelo User no tiene el método favoritos()");
            abort(500, 'El método favoritos no existe en User');
        }

        try {
            $esFavorito = $user->favoritos()->where('prenda_id', $prenda->id)->exists();

            if ($esFavorito) {
                $user->favoritos()->detach($prenda->id);
            } else {
                $user->favoritos()->attach($prenda->id);
            }

            $nuevoConteo = $prenda->favoritos()->count();

            if ($request->wantsJson()) {
                return response()->json([
                    'favorito' => !$esFavorito,
                    'favoritos_count' => $nuevoConteo,
                ]);
            }

            return redirect()->back()->with('status', 'Favorito actualizado');
        } catch (\Exception $e) {
            Log::error('Error al hacer toggle de favorito: ' . $e->getMessage());
            abort(500, 'Error al procesar la solicitud');
        }
    }

    /**
     * Muestra la lista de prendas marcadas como favoritas por el usuario.
     * Incluye filtros de búsqueda por estado, talla, marca y categoría.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        if (!method_exists($user, 'favoritos')) {
            abort(500, 'La relación favoritos no está definida en el modelo User');
        }

        $query = $user->favoritos()->with('user', 'categorias');

        if ($request->filled('search')) {
            $search = strtolower($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(nombre) LIKE ?', ["%{$search}%"])
                    ->orWhereHas('user', fn($q2) => $q2->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"]));
            });
        }

        if ($request->filled('estado')) {
            $query->whereIn('estado', $request->input('estado'));
        }

        if ($request->filled('talla')) {
            $query->whereIn('talla', $request->input('talla'));
        }

        if ($request->filled('marca')) {
            $query->whereIn('marca', $request->input('marca'));
        }

        if ($request->filled('categoria')) {
            $query->whereHas('categorias', function ($q) use ($request) {
                $q->whereIn('nombre', $request->input('categoria'));
            });
        }

        $query->where('vendido', false);

        $prendas = $query->paginate(12)->withQueryString();

        $estados = Prenda::select('estado')->distinct()->pluck('estado')->filter()->values();
        $tallas = Prenda::select('talla')->distinct()->pluck('talla')->filter()->values();
        $marcas = Prenda::select('marca')->distinct()->pluck('marca')->filter()->values();
        $categorias = Categoria::all();

        $breadcrumb = [
            ['label' => 'Inicio', 'url' => route('home')],
            ['label' => 'Mis Favoritos', 'url' => route('prendas.favoritos')],
        ];

        return view('prendas.favoritos', compact(
            'prendas',
            'estados',
            'tallas',
            'marcas',
            'categorias',
            'breadcrumb'
        ));
    }
}
