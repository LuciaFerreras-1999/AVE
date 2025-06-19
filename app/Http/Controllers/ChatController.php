<?php

namespace App\Http\Controllers;

use App\Models\MensajePrivado;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Muestra la lista de usuarios con los que el usuario autenticado ha tenido chats,
     * junto con el último mensaje y el contador de mensajes no leídos.
     */
    public function index()
    {
        $userId = Auth::id();

        $usuariosConChats = MensajePrivado::where('emisor_id', $userId)
            ->orWhere('receptor_id', $userId)
            ->get()
            ->flatMap(function ($mensaje) use ($userId) {
                return [$mensaje->emisor_id, $mensaje->receptor_id];
            })
            ->unique()
            ->reject(fn($id) => $id == $userId);

        $usuarios = User::whereIn('id', $usuariosConChats)->get();

        $ultimosMensajes = [];
        $contadorNoLeidos = [];

        foreach ($usuarios as $usuario) {

            $ultimoMensaje = MensajePrivado::where(function ($query) use ($userId, $usuario) {
                $query->where('emisor_id', $userId)->where('receptor_id', $usuario->id);
            })
                ->orWhere(function ($query) use ($userId, $usuario) {
                    $query->where('emisor_id', $usuario->id)->where('receptor_id', $userId);
                })
                ->orderByDesc('created_at')
                ->first();

            if ($ultimoMensaje) {

                $prefijo = $ultimoMensaje->emisor_id === $userId ? 'Tú' : $usuario->name;
                $mensajeTexto = "{$prefijo}: {$ultimoMensaje->mensaje}";
            } else {
                $mensajeTexto = '';
            }
            $ultimosMensajes[$usuario->id] = $mensajeTexto;

            $contadorNoLeidos[$usuario->id] = MensajePrivado::where('emisor_id', $usuario->id)
                ->where('receptor_id', $userId)
                ->where('leido', false)
                ->count();
        }

        $breadcrumb = [
            ['label' => 'Inicio', 'url' => route('home')],
            ['label' => 'Mis Chats', 'url' => route('chats.index')],
        ];
        return view('chats.index', compact('usuarios', 'ultimosMensajes', 'contadorNoLeidos', 'breadcrumb'));
    }

    /**
     * Muestra la conversación completa entre el usuario autenticado y otro usuario especificado.
     */
    public function show(User $user)
    {
        $userId = Auth::id();

        $mensajes = MensajePrivado::where(function ($query) use ($userId, $user) {
            $query->where('emisor_id', $userId)->where('receptor_id', $user->id);
        })->orWhere(function ($query) use ($userId, $user) {
            $query->where('emisor_id', $user->id)->where('receptor_id', $userId);
        })
            ->orderBy('created_at')
            ->get();

        return view('chats.show', compact('user', 'mensajes'));
    }

    /**
     * Valida y guarda un nuevo mensaje enviado por el usuario autenticado a otro usuario.
     */
    public function store(Request $request, User $usuario)
    {
        $request->validate([
            'mensaje' => 'required|string|max:1000',
        ]);

        MensajePrivado::create([
            'emisor_id' => Auth::id(),
            'receptor_id' => $usuario->id,
            'mensaje' => $request->mensaje,
        ]);

        return redirect()->route('chats.show', $usuario->id);
    }

    /**
     * Genera el breadcrumb para la vista de perfil, agregando rutas condicionales
     * basadas en parámetros de consulta en la URL.
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
                    'url' => route('prendas.mercado.show', ['usuario' => $usuarioPrenda->name, 'prenda' => $prenda->slug])
                ];
            }
        }

        $breadcrumb[] = ['label' => 'Perfil de ' . $user->name, 'url' => route('perfil.publico', ['nombre' => $user->name])];

        return array_merge($breadcrumb, $extra);
    }

    /**
     * Muestra la conversación entre el usuario autenticado y otro usuario, 
     * marcando como leídos los mensajes recibidos.
     */
    public function conversacion(User $user, Request $request)
    {
        $authUser = auth()->user();

        $mensajes = MensajePrivado::where(function ($q) use ($authUser, $user) {
            $q->where('emisor_id', $authUser->id)->where('receptor_id', $user->id);
        })->orWhere(function ($q) use ($authUser, $user) {
            $q->where('emisor_id', $user->id)->where('receptor_id', $authUser->id);
        })->orderBy('created_at')->get();

        MensajePrivado::where('emisor_id', $user->id)
            ->where('receptor_id', $authUser->id)
            ->where('leido', false)
            ->update(['leido' => true]);

        $from = $request->query('from');

        $params = ['user' => $user->id];
        $from = $request->query('from');

        if ($from) $params['from'] = $from;
        if ($request->has('prenda_id')) $params['prenda_id'] = $request->query('prenda_id');
        if ($request->has('usuario')) $params['usuario'] = $request->query('usuario');

        $extra = [
            ['label' => $user->name, 'url' => route('chats.conversacion', $params)]
        ];

        $breadcrumb = $this->breadcrumbPerfil($user, $request, $extra);

        return view('chats.conversacion', compact('user', 'mensajes', 'breadcrumb'));
    }

    /**
     * Valida y envía un mensaje desde el usuario autenticado a otro usuario,
     * bloqueando el envío si el último mensaje recibido tiene una marca de strike.
     */
    public function enviarMensaje(Request $request, User $user)
    {
        $request->validate([
            'mensaje' => 'required|string|max:1000',
        ]);

        $authUser = auth()->user();

        $ultimoMensajeRecibido = MensajePrivado::where('emisor_id', $user->id)
            ->where('receptor_id', $authUser->id)
            ->orderByDesc('created_at')
            ->first();

        if ($ultimoMensajeRecibido && $ultimoMensajeRecibido->es_strike) {
            return redirect()->route('chats.conversacion', $user)
                ->with('error', 'No puedes responder hasta que el administrador levante el bloqueo.');
        }

        MensajePrivado::create([
            'emisor_id' => $authUser->id,
            'receptor_id' => $user->id,
            'mensaje' => $request->mensaje,
        ]);

        return redirect()->route('chats.conversacion', $user);
    }
}
