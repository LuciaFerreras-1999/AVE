<?php

namespace App\Http\Controllers;

use App\Models\Prenda;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use Exception;

class PrendaAJAXController extends Controller
{
    /**
     * Muestra la vista del CRUD de prendas con soporte para DataTables vía AJAX.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Prenda::with(['categorias', 'user'])->latest()->get();

            return DataTables::of($data)
                ->addColumn('categorias', function ($row) {
                    return $row->categorias->pluck('nombre')->join(', ');
                })
                ->addColumn('imagen', function ($row) {
                    if (!empty($row->imagen)) {
                        return asset('assets/imagenes/' . rawurlencode($row->imagen));
                    }
                    return null;
                })
                ->addColumn('user_nombre', function ($row) {
                    return $row->user->name ?? 'Sin usuario';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-id="' . $row->id . '" class="edit btn btn-primary btn-sm">Editar</a> ';
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="delete btn btn-danger btn-sm">Eliminar</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $categorias = Categoria::all();
        $breadcrumb = [
            ['label' => 'Inicio', 'url' => route('home')],
            ['label' => "Gestión de Prendas", 'url' => route('prendas-ajax-crud.index')],
        ];

        return view('admin.crudPrendas', compact('categorias', 'breadcrumb'));
    }

    /**
     * Guarda o actualiza una prenda. Si viene con prenda_id se actualiza, si no, se crea una nueva.
     * También maneja el almacenamiento de la imagen y la asociación con categorías.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'talla' => 'required|in:' . implode(',', Prenda::TALLAS),
            'marca' => 'nullable|string|max:255',
            'estado' => 'nullable|string|max:255',
            'categorias' => 'required|array',
            'categorias.*' => 'exists:categorias,id',
            'imagen' => 'nullable|image|max:2048',
        ]);

        $slug = Str::slug($request->nombre, '-');

        try {
            // Actualización
            if ($request->filled('prenda_id')) {
                $prenda = Prenda::find($request->prenda_id);

                if (!$prenda) {
                    return response()->json(['error' => 'Prenda no encontrada.'], 404);
                }

                $prenda->update([
                    'nombre' => $request->nombre,
                    'slug' => $slug,
                    'descripcion' => $request->descripcion,
                    'talla' => $request->talla,
                    'marca' => $request->marca,
                    'estado' => $request->estado,
                ]);
                $prenda->categorias()->sync($request->categorias);
            } else {
                // Creación
                $prenda = Prenda::create([
                    'nombre' => $request->nombre,
                    'slug' => $slug,
                    'descripcion' => $request->descripcion,
                    'talla' => $request->talla,
                    'marca' => $request->marca,
                    'estado' => $request->estado,
                    'user_id' => auth()->id(),
                ]);
                $prenda->categorias()->attach($request->categorias);
            }

            // Procesar imagen si se subió
            if ($request->hasFile('imagen')) {
                if (!empty($prenda->imagen)) {
                    $path = public_path('assets/imagenes/' . $prenda->imagen);
                    if (file_exists($path)) {
                        unlink($path);
                    }
                }

                $imagen = $request->file('imagen');
                $nombreImagen = time() . '_' . $imagen->getClientOriginalName();
                $imagen->move(public_path('assets/imagenes'), $nombreImagen);

                $prenda->imagen = $nombreImagen;
                $prenda->save();
            }

            return response()->json(['success' => 'Prenda guardada con éxito.']);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al guardar prenda: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Devuelve los datos de una prenda para su edición (incluyendo las categorías asociadas).
     */
    public function edit($id)
    {
        $prenda = Prenda::with('categorias')->find($id);

        if (!$prenda) {
            return response()->json(['error' => 'Prenda no encontrada.'], 404);
        }

        $prenda->categorias_ids = $prenda->categorias->pluck('id');

        return response()->json($prenda);
    }

    /**
     * Elimina una prenda por su ID, incluyendo su imagen del sistema y relaciones con categorías.
     */
    public function destroy($id)
    {
        try {
            $prenda = Prenda::find($id);

            if (!$prenda) {
                return response()->json(['error' => 'Prenda no encontrada.'], 404);
            }

            // Borrar imagen del servidor
            if (!empty($prenda->imagen)) {
                $path = public_path('assets/imagenes/' . $prenda->imagen);
                if (file_exists($path)) {
                    unlink($path);
                }
            }

            // Desvincular categorías y eliminar prenda
            $prenda->categorias()->detach();
            $prenda->delete();

            return response()->json(['success' => 'Prenda eliminada con éxito.']);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}
