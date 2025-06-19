<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class UserAJAXController extends Controller
{
    /**
     * Mostrar la vista con la lista de usuarios o devolver datos JSON para DataTables en peticiones AJAX.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::with('roles', 'strikes')->select('users.*');

            return DataTables::of($data)
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-id="' . $row->id . '" class="edit btn btn-warning btn-sm editUser">Editar</a>';
                    $btn .= ' <a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-danger btn-sm deleteUser">Eliminar</a>';
                    return $btn;
                })
                ->addColumn('rol', function ($row) {
                    return $row->getRoleNames()->implode(', ');
                })
                ->addColumn('bloqueado', function ($row) {
                    return $row->bloqueado;
                })
                ->addColumn('strikes_count', function ($row) {
                    return $row->strikes->count();
                })
                ->addColumn('tipo_usuario', function ($row) {
                    return $row->getRoleNames()->contains('admin') ? 'admin' : 'user';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $breadcrumb = [
            ['label' => 'Inicio', 'url' => route('home')],
            ['label' => "Gestión de Usuarios", 'url' => route('usuarios-ajax-crud.index')],
        ];

        return view('admin.crud', compact('breadcrumb'));
    }

    /**
     * Crear un nuevo usuario con validación y contraseña por defecto.
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'bloqueado' => 'required|boolean',
        ];

        $validatedData = $request->validate($rules);

        try {
            $validatedData['password'] = bcrypt('defaultPassword123');

            $usuario = User::create($validatedData);

            return response()->json(['success' => 'Usuario creado con éxito.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al crear usuario: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Actualizar un usuario existente y eliminar strikes si se desbloquea.
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'bloqueado' => 'required|boolean',
        ];

        $validatedData = $request->validate($rules);

        try {
            $usuario = User::findOrFail($id);

            $wasBlocked = $usuario->bloqueado;

            $usuario->update($validatedData);

            if ($wasBlocked && !$usuario->bloqueado) {
                $usuario->strikes()->delete();
            }

            return response()->json(['success' => 'Usuario actualizado con éxito.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al actualizar usuario: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Obtener datos de un usuario para edición.
     */
    public function edit($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        return response()->json($user);
    }

    /**
     * Eliminar un usuario específico.
     */
    public function destroy($id)
    {
        try {
            $usuario = User::findOrFail($id);

            $usuario->delete();

            return response()->json(['success' => 'Usuario eliminado con éxito.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar usuario: ' . $e->getMessage()], 500);
        }
    }
}
