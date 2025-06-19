<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prenda;
use Illuminate\Support\Facades\DB;
use App\Models\Compra;

class CarritoController extends Controller
{
    // Verifica que el usuario esté autenticado y añade una prenda al carrito en sesión, evitando agregar prendas propias.
    public function agregar(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Debes iniciar sesión.'], 401);
        }
        try {
            $prenda = Prenda::findOrFail($request->prenda_id);
            $carrito = session()->get('carrito', collect());

            if ($prenda->user_id === auth()->id()) {
                $mensaje = 'No puedes agregar tus propias prendas al carrito.';

                return $request->ajax()
                    ? response()->json(['error' => $mensaje], 403)
                    : redirect()->back()->with('error', $mensaje);
            }

            if (!$carrito->contains('id', $prenda->id)) {
                $carrito->push($prenda);
                session(['carrito' => $carrito]);
            }

            $respuesta = [
                'success' => true,
                'message' => 'Prenda añadida al carrito.',
                'carritoCount' => $carrito->count(),
                'prendaId' => $prenda->id,
            ];

            return $request->ajax()
                ? response()->json($respuesta)
                : redirect()->back()->with('success', $respuesta['message']);

        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'error' => 'Error al agregar la prenda al carrito.',
                    'exception' => $e->getMessage(),
                ], 500);
            }

            return redirect()->back()->with('error', 'Error al agregar la prenda al carrito.');
        }
    }

    // Elimina la prenda indicada del carrito guardado en sesión.
    public function quitar(Request $request, Prenda $prenda)
    {
        if (!$prenda) {
            return response()->json([
                'success' => false,
                'message' => 'Prenda no encontrada.'
            ], 404);
        }

        $carrito = session()->get('carrito', collect());
        $nuevoCarrito = $carrito->reject(fn($item) => $item->id == $prenda->id);
        session(['carrito' => $nuevoCarrito]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Prenda eliminada del carrito.',
                'carritoCount' => $nuevoCarrito->count(),
                'prendaId' => $prenda->id,
            ]);
        }

        return redirect()->back()->with('success', 'Prenda eliminada del carrito.');
    }

    // Recupera las prendas en el carrito (sesión) y muestra solo las que no están vendidas.
    public function index()
    {
        $carrito = session()->get('carrito', collect());

        $carrito = $carrito->filter(function ($prenda) {
            return !$prenda->vendido;
        });

        session()->put('carrito', $carrito);

        $breadcrumb = [
            ['label' => 'Inicio', 'url' => route('home')],
            ['label' => 'Carrito', 'url' => route('carrito.index')],
        ];

        return view('carrito.index', compact('carrito', 'breadcrumb'));
    }

    // Obtiene y muestra las compras realizadas por el usuario autenticado.
    public function historial()
    {
        $userId = auth()->id();

        $compras = Compra::with('prenda')
            ->where('user_id', $userId)
            ->orderBy('fecha_compra', 'desc')
            ->get();

        $breadcrumb = [
            ['label' => 'Inicio', 'url' => route('home')],
            ['label' => 'Carrito', 'url' => route('carrito.index')],
            ['label' => 'Historial', 'url' => route('compras.historial')],
        ];

        return view('carrito.historial', compact('compras', 'breadcrumb'));
    }

    // Procesa la compra: marca las prendas del carrito como vendidas, guarda las compras y limpia el carrito.
    public function finalizar(Request $request)
    {
        $carrito = session('carrito', collect());

        if ($carrito->isEmpty()) {
            return redirect()->back()->with('error', 'El carrito está vacío.');
        }

        $userId = auth()->id();

        foreach ($carrito as $prenda) {
            $prenda->vendido = true;
            $prenda->save();

            Compra::create([
                'user_id' => $userId,
                'prenda_id' => $prenda->id,
                'precio' => $prenda->precio,
                'fecha_compra' => now(),
            ]);
        }

        session()->forget('carrito');

        return redirect()->route('carrito.index')->with('success', 'Compra finalizada con éxito.');
    }
}
