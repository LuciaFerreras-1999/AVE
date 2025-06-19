<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\User;

class PerfilController extends Controller
{
    protected $defaultAvatar = 'assets/logo/default-avatar.png';

    /**
     * Muestra el formulario de edición del perfil del usuario autenticado.
     */
    public function edit()
    {
        $user = Auth::user();

        $breadcrumb = [
            ['label' => 'Inicio', 'url' => route('home')],
            ['label' => "Mi Perfil", 'url' => route('perfil.propio')],
            ['label' => "Editar perfil", 'url' => route('perfil.edit')],
        ];

        return view('perfil.edit', compact('user', 'breadcrumb'));
    }

    /**
     * Actualiza la información del perfil del usuario autenticado.
     * Puede actualizar campos básicos y cambiar o eliminar el avatar.
     */
    public function update(UpdateProfileRequest $request)
    {
        $user = Auth::user();
        $data = $request->validated();
        $storagePath = 'assets/imagenes';

        if (!empty($data['remove_avatar']) && $data['remove_avatar'] == true) {
            $this->deleteAvatarIfExists($user->avatar);
            $data['avatar'] = null;
        }

        if ($request->hasFile('avatar')) {
            $this->deleteAvatarIfExists($user->avatar);

            $file = $request->file('avatar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path($storagePath), $filename);

            $data['avatar'] = $filename;
        }

        $user->update([
            'name' => $data['name'],
            'bio' => $data['bio'] ?? $user->bio,
            'phone' => $data['phone'] ?? $user->phone,
            'avatar' => $data['avatar'] ?? $user->avatar,
            'address' => $data['address'] ?? $user->address,
        ]);

        return redirect()->route('perfil.edit')->with('success', 'Perfil actualizado correctamente.');
    }

    /**
     * Genera la estructura de breadcrumb dinámica para diferentes orígenes de navegación.
     */
    private function breadcrumbPerfil(User $user, Request $request, $extra = [])
    {
        $from = $request->query('from');

        $breadcrumb = [
            ['label' => 'Inicio', 'url' => route('home')],
        ];

        if ($from === 'mercado') {
            $breadcrumb[] = ['label' => 'Mercado', 'url' => route('prendas.mercado.index')];
        } elseif ($from === 'prenda' && $request->has('prenda_id') && $request->has('usuario')) {
            $prenda = \App\Models\Prenda::find($request->query('prenda_id'));
            $usuarioPrenda = User::where('name', $request->query('usuario'))->first();

            if ($prenda && $usuarioPrenda) {
                $breadcrumb[] = ['label' => 'Mercado', 'url' => route('prendas.mercado.index')];
                $breadcrumb[] = [
                    'label' => $prenda->nombre,
                    'url' => route('prendas.mercado.show', [
                        'usuario' => $usuarioPrenda->name,
                        'prenda' => $prenda->slug
                    ])
                ];
            }
        } elseif ($from === 'mensaje') {
            $mensajeId = $request->query('mensaje_id');

            $breadcrumb[] = ['label' => 'Mensajes y reportes', 'url' => route('admin.mensajes.index')];

            if ($mensajeId) {
                $breadcrumb[] = [
                    'label' => 'Mensaje de ' . ($user->name),
                    'url' => route('admin.mensajes.show', ['mensaje' => $mensajeId])
                ];
            } else {
                $breadcrumb[] = [
                    'label' => 'Mensaje de ' . ($user->name)
                ];
            }
        }

        $breadcrumb[] = [
            'label' => 'Perfil de ' . $user->name,
            'url' => route('perfil.publico', ['nombre' => $user->name])
        ];

        return array_merge($breadcrumb, $extra);
    }

    /**
     * Muestra el perfil público de un usuario específico por su nombre.
     */
    public function mostrarPorNombre($nombre, Request $request)
    {
        $user = User::where('name', $nombre)->firstOrFail();

        $prendas = $user->prendas()
            ->where('publicada', true)
            ->where('vendido', false)
            ->paginate(12);

        $valoraciones = $user->valoracionesRecibidas()
            ->whereNotNull('comentario')
            ->where('comentario', '!=', '')
            ->with('usuario:id,name,avatar')
            ->get();

        $totalValoraciones = $user->totalValoraciones();

        $breadcrumb = $this->breadcrumbPerfil($user, $request);

        return view('perfil.publico', compact('user', 'prendas', 'valoraciones', 'totalValoraciones', 'breadcrumb'));
    }

    /**
     * Muestra el perfil propio del usuario autenticado.
     */
    public function show()
    {
        $user = auth()->user();

        $prendas = $user->prendas()
            ->where('publicada', true)
            ->where('vendido', false)
            ->paginate(12);

        $valoraciones = $user->valoracionesRecibidas()
            ->whereNotNull('comentario')
            ->where('comentario', '!=', '')
            ->with('usuario:id,name,avatar')
            ->get();

        $totalValoraciones = $user->totalValoraciones();

        $breadcrumb = [
            ['label' => 'Inicio', 'url' => route('home')],
            ['label' => "Mi Perfil", 'url' => route('perfil.propio')],
        ];

        return view('perfil.propio', compact('user', 'prendas', 'valoraciones', 'totalValoraciones', 'breadcrumb'));
    }

    /**
     * Elimina el avatar anterior del usuario si existe en el sistema de archivos.
     */
    private function deleteAvatarIfExists($avatar)
    {
        $storagePath = 'assets/imagenes';
        $fullPath = public_path($storagePath . '/' . $avatar);

        if ($avatar && $avatar !== $this->defaultAvatar && file_exists($fullPath)) {
            unlink($fullPath);
        }
    }

    /**
     * Asigna el rol de administrador a un usuario.
     */
    public function darRolAdmin(User $user)
    {
        if ($user->hasRole('admin')) {
            return back()->with('info', 'Este usuario ya es administrador.');
        }

        $user->assignRole('admin');

        return back()->with('success', 'El rol de administrador fue asignado correctamente.');
    }

    /**
     * Elimina el rol de administrador de un usuario.
     */
    public function quitarRolAdmin(User $user)
    {
        $user->removeRole('admin');
        return back()->with('success', 'Rol de administrador quitado correctamente.');
    }
}
