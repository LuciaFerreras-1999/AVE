<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prenda;
use App\Models\Compra;
use App\Models\Carrito;

class CarritoController extends Controller
{
    /**
     * Sincroniza el carrito desde la base de datos al iniciar sesión.
     */
    public function sincronizarDesdeBD()
    {
        if (auth()->check()) {
            $prendas = Carrito::with('prenda')
                ->where('user_id', auth()->id())
                ->get()
                ->pluck('prenda')
                ->filter();

            session(['carrito' => $prendas]);
        }
    }

    /**
     * Agrega una prenda al carrito, tanto en sesión como en base de datos si el usuario está autenticado.
     */
    public function agregar(Request $request)
    {
        $prenda = Prenda::findOrFail($request->prenda_id);
        $carrito = session()->get('carrito', collect());

        if (auth()->check()) {
            // Impide que el usuario agregue su propia prenda
            if ($prenda->user_id === auth()->id()) {
                return response()->json(['error' => 'No puedes agregar tus propias prendas al carrito.'], 403);
            }

            Carrito::firstOrCreate([
                'user_id' => auth()->id(),
                'prenda_id' => $prenda->id,
            ]);
        }

        // Solo añade la prenda si no está ya en el carrito de sesión
        if (!$carrito->contains('id', $prenda->id)) {
            $carrito->push($prenda);
            session(['carrito' => $carrito]);
        }

        return $request->ajax() || $request->wantsJson()
            ? response()->json([
                'success' => true,
                'message' => 'Prenda añadida al carrito.',
                'carritoCount' => $carrito->count(),
            ])
            : redirect()->back()->with('success', 'Prenda añadida al carrito.');
    }

    /**
     * Quita una prenda del carrito, tanto de la sesión como de la base de datos.
     */
    public function quitar(Request $request, Prenda $prenda)
    {
        $carrito = session()->get('carrito', collect());
        $nuevoCarrito = $carrito->reject(fn($item) => $item->id == $prenda->id);
        session(['carrito' => $nuevoCarrito]);

        if (auth()->check()) {
            Carrito::where('user_id', auth()->id())
                ->where('prenda_id', $prenda->id)
                ->delete();
        }

        return $request->ajax() || $request->wantsJson()
            ? response()->json([
                'success' => true,
                'message' => 'Prenda eliminada del carrito.',
                'carritoCount' => $nuevoCarrito->count(),
                'prendaId' => $prenda->id,
            ])
            : redirect()->back()->with('success', 'Prenda eliminada del carrito.');
    }

    /**
     * Muestra el contenido actual del carrito (no vendido) y su vista correspondiente.
     */
    public function index()
    {
        $carrito = session()->get('carrito', collect());

        // Elimina prendas vendidas
        $carrito = $carrito->filter(fn($prenda) => !$prenda->vendido);

        session()->put('carrito', $carrito);

        $breadcrumb = [
            ['label' => 'Inicio', 'url' => route('home')],
            ['label' => 'Carrito', 'url' => route('carrito.index')],
        ];

        return view('carrito.index', compact('carrito', 'breadcrumb'));
    }

    /**
     * Muestra el historial de compras del usuario autenticado.
     */
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

    /**
     * Finaliza la compra: marca prendas como vendidas, registra la compra y limpia el carrito.
     */
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

            if ($userId) {
                Carrito::where('user_id', $userId)
                    ->where('prenda_id', $prenda->id)
                    ->delete();
            }
        }

        session()->forget('carrito');

        return redirect()->route('carrito.index')->with('success', 'Compra finalizada con éxito.');
    }
}
