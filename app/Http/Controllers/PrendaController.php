<?php

namespace App\Http\Controllers;

use App\Models\Prenda;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\User;
use App\Http\Requests\PrendaRequest;

class PrendaController extends Controller
{
    // Muestra la página de bienvenida con las últimas 5 prendas publicadas
    public function bienvenida()
    {
        $prendas = Prenda::where('publicada', true)
            ->with('imagenes')
            ->latest()
            ->take(5)
            ->get();

        return view('bienvenida', compact('prendas'));
    }

    // Muestra las prendas del usuario con filtros de búsqueda, paginación y breadcrumb
    public function index(Request $request)
    {
        $estados = ['nuevo', 'usado'];
        $tallas = Prenda::TALLAS;
        $marcas = Prenda::distinct()->pluck('marca')->toArray();
        $categorias = Categoria::orderBy('nombre')->get();

        $query = Prenda::where('user_id', Auth::id())
            ->where('vendido', false);

        if ($request->filled('search')) {
            $search = strtolower($request->input('search'));
            $query->whereRaw('LOWER(nombre) LIKE ?', ["%{$search}%"]);
        }

        if ($request->filled('estado')) {
            $estadosFiltro = array_filter($request->input('estado'));
            if (!empty($estadosFiltro)) {
                $query->whereIn('estado', $estadosFiltro);
            }
        }

        if ($request->filled('talla')) {
            $tallasFiltro = array_filter($request->input('talla'));
            if (!empty($tallasFiltro)) {
                $query->whereIn('talla', $tallasFiltro);
            }
        }

        if ($request->filled('marca')) {
            $marcasFiltro = array_filter($request->input('marca'));
            if (!empty($marcasFiltro)) {
                $query->whereIn('marca', $marcasFiltro);
            }
        }

        if ($request->filled('categoria')) {
            $categoriasSeleccionadas = array_filter($request->input('categoria'));
            if (!empty($categoriasSeleccionadas)) {
                $query->whereHas('categorias', function ($q) use ($categoriasSeleccionadas) {
                    $q->whereIn('nombre', $categoriasSeleccionadas);
                });
            }
        }

        $prendas = $query->with('categorias')->paginate(12)->withQueryString();
        $breadcrumb = [
            ['label' => 'Inicio', 'url' => route('home')],
            ['label' => 'Mi Armario', 'url' => route('prendas.index')],
        ];

        return view('prendas.index', compact('prendas', 'estados', 'tallas', 'marcas', 'categorias', 'breadcrumb'));
    }

    // Muestra el detalle de una prenda específica
    public function show(Prenda $prenda)
    {
        $imagenes = $prenda->imagenes;

        $breadcrumb = [
            ['label' => 'Inicio', 'url' => route('home')],
            ['label' => 'Mi Armario', 'url' => route('prendas.index')],
            ['label' => $prenda->nombre, 'url' => route('prendas.show', $prenda->id)],
        ];

        return view('prendas.show', compact('prenda', 'imagenes', 'breadcrumb'));
    }

    // Muestra el formulario para crear una nueva prenda
    public function create()
    {
        $categorias = Categoria::orderBy('nombre')->get();

        $breadcrumb = [
            ['label' => 'Inicio', 'url' => route('home')],
            ['label' => 'Mi Armario', 'url' => route('prendas.index')],
            ['label' => 'Crear prenda', 'url' => route('prendas.create')],
        ];

        return view('prendas.create', compact('categorias', 'breadcrumb'));
    }

    // Guarda una imagen en el sistema de archivos y elimina la anterior si existe
    private function guardarImagen(Request $request, ?Prenda $prenda = null): ?string
    {
        if (!$request->hasFile('imagen')) {
            return null;
        }

        if ($prenda && $prenda->imagen) {
            $oldPath = public_path('assets/imagenes/' . $prenda->imagen);
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }

        $image = $request->file('imagen');
        $nombreImagen = time() . '_' . $image->getClientOriginalName();
        $image->move(public_path('assets/imagenes'), $nombreImagen);

        return $nombreImagen;
    }

    // Genera un slug único para la prenda basándose en su nombre y el usuario
    private function generarSlugUnico($nombre, $userId)
    {
        $slugBase = Str::slug($nombre);
        $slug = $slugBase;
        $contador = 1;

        while (Prenda::where('slug', $slug)->where('user_id', $userId)->exists()) {
            $slug = $slugBase . '-' . $contador;
            $contador++;
        }

        return $slug;
    }

    // Guarda una nueva prenda en la base de datos
    public function store(PrendaRequest $request)
    {
        $prenda = new Prenda([
            'user_id' => Auth::id(),
            'nombre' => $request->nombre,
            'slug' => $this->generarSlugUnico($request->nombre, Auth::id()),
            'descripcion' => $request->descripcion,
            'talla' => $request->talla,
            'marca' => $request->marca,
            'estado' => $request->estado,
        ]);

        $imagenNombre = $this->guardarImagen($request, null);
        if ($imagenNombre) {
            $prenda->imagen = $imagenNombre;
        }

        $prenda->save();

        if ($request->has('categorias')) {
            $prenda->categorias()->sync($request->categorias);
        }

        return redirect()->route('prendas.index')->with('success', 'Prenda creada con éxito.');
    }

    // Muestra el formulario para editar una prenda
    public function edit(Prenda $prenda)
    {
        if ($prenda->user_id !== Auth::id()) {
            abort(403);
        }

        $categorias = Categoria::orderBy('nombre')->get();
        $tallas = Prenda::TALLAS;
        $breadcrumb = [
            ['label' => 'Inicio', 'url' => route('home')],
            ['label' => 'Mi Armario', 'url' => route('prendas.index')],
            ['label' => $prenda->nombre, 'url' => route('prendas.show', $prenda->slug)],
            ['label' => "Editar", 'url' => route('prendas.edit', $prenda)],
        ];

        return view('prendas.edit', compact('prenda', 'categorias', 'tallas', 'breadcrumb'));
    }

    // Actualiza los datos de una prenda existente
    public function update(PrendaRequest $request, $id)
    {
        $prenda = Prenda::where('user_id', Auth::id())->findOrFail($id);

        $imagenNombre = $this->guardarImagen($request, $prenda);
        if ($imagenNombre) {
            $prenda->imagen = $imagenNombre;
        }

        $data = [
            'descripcion' => $request->descripcion,
            'talla' => $request->talla,
            'marca' => $request->marca,
            'estado' => $request->estado,
        ];

        if ($request->nombre !== $prenda->nombre) {
            $data['nombre'] = $request->nombre;
            $data['slug'] = $this->generarSlugUnico($request->nombre, Auth::id());
        }

        $prenda->update($data);

        if ($request->has('categorias')) {
            $prenda->categorias()->sync($request->categorias);
        }

        return redirect()->route('prendas.index')->with('success', 'Prenda actualizada con éxito.');
    }

    // Elimina una prenda de la base de datos y borra su imagen del sistema
    public function destroy($id)
    {
        $prenda = Prenda::where('user_id', Auth::id())->findOrFail($id);

        if ($prenda->imagen) {
            $imagePath = public_path('assets/imagenes/' . $prenda->imagen);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $prenda->delete();

        return redirect()->route('prendas.index')->with('success', 'Prenda eliminada con éxito.');
    }

    // Publica o despublica una prenda en el mercado, y gestiona su precio
    public function storeMercado(Request $request)
    {
        $request->validate([
            'prenda_id' => 'required|exists:prendas,id',
            'accion' => 'required|in:publicar,despublicar',
            'precio' => 'nullable|numeric|min:0.01'
        ]);

        $prenda = Prenda::where('id', $request->prenda_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($request->accion === 'publicar') {
            if ($prenda->publicada) {
                return back()->with('error', 'La prenda ya está publicada.');
            }

            $prenda->precio = $request->precio;
            $prenda->publicada = true;
            $prenda->save();

            return redirect()->route('prendas.mercado.index')->with('success', 'Prenda publicada en el mercado.');
        } else {
            $prenda->publicada = false;
            $prenda->precio = null;
            $prenda->save();

            return back()->with('success', 'Prenda retirada del mercado.');
        }
    }

    // Muestra el listado de prendas disponibles en el mercado con filtros
    public function mercado(Request $request)
    {
        $query = Prenda::where('publicada', true)->where('vendido', false);

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

        $prendas = $query->with('categorias')
            ->withCount('favoritos')
            ->paginate(12)
            ->withQueryString();

        $breadcrumb = [
            ['label' => 'Inicio', 'url' => route('home')],
            ['label' => 'Mercado', 'url' => route('prendas.mercado.index')],
        ];

        return view('prendas.mercado', [
            'prendas' => $prendas,
            'estados' => Prenda::select('estado')->distinct()->pluck('estado'),
            'tallas' => Prenda::select('talla')->distinct()->pluck('talla'),
            'marcas' => Prenda::select('marca')->distinct()->pluck('marca'),
            'categorias' => Categoria::all(),
            'breadcrumb' => $breadcrumb,
        ]);
    }

    // Muestra los detalles de una prenda publicada en el mercado
    public function showMercado(Request $request, User $usuario, Prenda $prenda)
    {
        if ($prenda->user_id !== $usuario->id) {
            abort(403, 'Esta prenda no pertenece al usuario.');
        }

        $prenda->load('categorias', 'imagenes');
        $reportada = $prenda->reportes()->exists();

        $from = $request->query('from');

        $breadcrumb = [
            ['label' => 'Inicio', 'url' => route('home')],
        ];

        if ($from === 'perfil') {
            $breadcrumb[] = ['label' => 'Perfil de ' . $usuario->name, 'url' => route('perfil.publico', ['nombre' => $usuario->name, 'from' => 'perfil'])];
        } elseif ($from === 'mi-perfil') {
            $breadcrumb[] = ['label' => 'Mi Perfil', 'url' => route('perfil.propio')];
        } else {
            $breadcrumb[] = ['label' => 'Mercado', 'url' => route('prendas.mercado.index')];
        }

        $breadcrumb[] = [
            'label' => $prenda->nombre,
            'url' => route('prendas.mercado.show', [
                'usuario' => $usuario->name,
                'prenda' => $prenda->slug,
                'from' => $from
            ])
        ];

        return view('prendas.mercadoPrenda', compact('prenda', 'usuario', 'reportada', 'breadcrumb'));
    }

    // Guarda un nuevo comentario en una prenda
    public function comentar(Request $request, Prenda $prenda)
    {
        $request->validate([
            'contenido' => 'required|string|max:1000',
        ]);

        $prenda->comentarios()->create([
            'user_id' => auth()->id(),
            'contenido' => $request->contenido,
        ]);

        return back()->with('success', 'Comentario añadido correctamente.');
    }
}
