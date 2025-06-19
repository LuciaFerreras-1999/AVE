<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PrendaController;
use App\Http\Controllers\FavoritoController;
use App\Http\Controllers\ContactoController;
use App\Http\Controllers\MensajeController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\StrikeController;
use App\Http\Controllers\UserAJAXController;
use App\Http\Controllers\PrendaAJAXController;
use App\Http\Controllers\ValoracionController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [PrendaController::class, 'bienvenida'])->name('home');

Route::get('/contacto', [ContactoController::class, 'index'])->name('contacto');
Route::post('/contacto', [ContactoController::class, 'enviar'])->name('contacto.enviar');

Route::get('/mercado', [PrendaController::class, 'mercado'])->name('prendas.mercado.index');
Route::get('/mercado/{usuario}/{prenda}', [PrendaController::class, 'showMercado'])->name('prendas.mercado.show');

Route::get('/auth/user', function () {
    if (auth()->check()) {
        return response()->json([
            'authUser' => auth()->user()
        ]);
    }
    return null;
});

Route::middleware('auth')->group(function () {
    Route::get('/prendas', [PrendaController::class, 'index'])->name('prendas.index');
    Route::get('/prendas/create', [PrendaController::class, 'create'])->name('prendas.create');
    Route::post('/prendas', [PrendaController::class, 'store'])->name('prendas.store');
    Route::get('/prendas/{prenda}', [PrendaController::class, 'show'])->name('prendas.show');
    Route::get('/prendas/{prenda}/edit', [PrendaController::class, 'edit'])->name('prendas.edit');
    Route::put('/prendas/{prenda}', [PrendaController::class, 'update'])->name('prendas.update');
    Route::delete('/prendas/{prenda}', [PrendaController::class, 'destroy'])->name('prendas.destroy');
    Route::post('/prendas/{prenda}/comentar', [PrendaController::class, 'comentar'])->name('prendas.comentar');
    Route::post('/usuarios/{user}/valorar', [ValoracionController::class, 'store'])->name('usuarios.valorar')->middleware('auth');

    Route::post('/mercado', [PrendaController::class, 'storeMercado'])->name('prendas.mercado.store');
    Route::post('/mercado/{prenda}/comprar', [PrendaController::class, 'comprar'])->name('prendas.comprar');

    Route::post('/favoritos/{prenda}/toggle', [FavoritoController::class, 'toggle'])->name('favoritos.toggle');
    Route::get('/favoritos', [FavoritoController::class, 'index'])->name('prendas.favoritos');

    Route::get('/perfil', [PerfilController::class, 'show'])->name('perfil.propio');
    Route::get('/perfil/edit', [PerfilController::class, 'edit'])->name('perfil.edit');
    Route::post('/perfil', [PerfilController::class, 'update'])->name('perfil.update');
    Route::get('/perfil/{nombre}', [PerfilController::class, 'mostrarPorNombre'])->name('perfil.publico');

    Route::get('/chats', [ChatController::class, 'index'])->name('chats.index');
    Route::get('/chats/{user}', [ChatController::class, 'conversacion'])->name('chats.conversacion');
    Route::post('/chats/{user}/enviar', [ChatController::class, 'enviarMensaje'])->name('chats.enviar');

    Route::post('/carrito', [CarritoController::class, 'agregar'])->name('carrito.agregar');
    Route::delete('/carrito/{prenda}', [CarritoController::class, 'quitar'])->name('carrito.quitar');
    Route::get('/carrito', [CarritoController::class, 'index'])->name('carrito.index');
    Route::post('/carrito/finalizar', [CarritoController::class, 'finalizar'])->name('carrito.finalizar');
    Route::get('/compras/historial', [CarritoController::class, 'historial'])->name('compras.historial');

    Route::post('/prendas/{prenda}/reportar', [ReporteController::class, 'store'])->name('prendas.reportar');
});

Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::post('/usuario/{user}/strike', [StrikeController::class, 'aplicarStrike'])->name('usuario.aplicarStrike');
    Route::post('/usuario/{user}/desbloquear', [StrikeController::class, 'desbloquear'])->name('usuario.desbloquear');

    Route::post('/usuarios/{user}/dar-rol-admin', [PerfilController::class, 'darRolAdmin'])->name('usuario.darRolAdmin');
    Route::delete('/usuario/{user}/quitar-rol-admin', [PerfilController::class, 'quitarRolAdmin'])->name('usuario.quitarRolAdmin');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('mensajes', [MensajeController::class, 'index'])->name('mensajes.index');
        Route::get('mensajes/{mensaje}', [MensajeController::class, 'show'])->name('mensajes.show');
        Route::post('mensajes/{mensaje}/responder', [MensajeController::class, 'responder'])->name('mensajes.responder');
        Route::patch('mensajes/{mensaje}/toggle-leido', [MensajeController::class, 'toggleLeido'])->name('mensajes.toggleLeido');

        Route::delete('reportes/{reporte}', [ReporteController::class, 'destroy'])->name('reportes.destroy');
    });
});

Route::resource('usuarios-ajax-crud', UserAJAXController::class)->middleware('auth', 'role:admin');
Route::resource('prendas-ajax-crud', PrendaAJAXController::class)->middleware('auth', 'role:admin');
Route::middleware(['auth', 'blocked'])->group(function () {
    Route::post('/chats/enviar', [ChatController::class, 'enviar']);
    Route::post('/prendas/store', [PrendaController::class, 'store']);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
