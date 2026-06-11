<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\EncuestaSaludController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\AudioController;


/*
|--------------------------------------------------------------------------
| Rutas Públicas
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('Login');
})->name('login');

Route::get('/registro', function () {
    return view('Registro');
});

/*
|--------------------------------------------------------------------------
| Autenticación
|--------------------------------------------------------------------------
*/

Route::post('/registro', [RegisterController::class, 'store']);

Route::post('/login', [LoginController::class, 'login'])
    ->name('login');

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Rutas Protegidas
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // Encuesta inicial
    Route::get('/bienvenida', [EncuestaSaludController::class, 'index'])
    ->middleware('auth');

    Route::post('/encuesta-salud', [EncuestaSaludController::class, 'store'])
    ->middleware('auth')
    ->name('encuesta.store');

    // Perfil
    Route::get('/mi-perfil', function () {
        return view('Mi-perfil');
    });

    // Historial
    Route::get('/mi-historial', function () {
        return view('Mi-historial');
    });

    Route::get('/crear-historial', function () {
        return view('Crear-historial-de-vacunacion');
    });

    Route::post('/crear-historial', function () {
        return redirect('/mi-historial');
    })->name('historial.store');

    // Vacunas
    Route::get('/registrar-vacuna', function () {
        return view('Registrar-vacuna');
    });

    // Notificaciones
    Route::get('/notificaciones', function () {
        return view('Notificacion');
    });

    Route::get('/recordatorios', function () {
        return view('Recordatorios');
    });

    // Municipalidad
    Route::get('/muni', function () {
        return view('muni');
    });
});


/*
|--------------------------------------------------------------------------
| Rutas del Asistente Virtual Muni — InmunoSV
|--------------------------------------------------------------------------
| Todas las rutas están protegidas por el middleware 'auth'.
| El token CSRF se valida automáticamente en las solicitudes POST.
*/

Route::middleware(['auth'])->group(function () {

    // ── Vista principal del chatbot ─────────────────────────────────────────
    Route::get('/muni', [ChatController::class, 'index'])->name('muni');

    // ── API de mensajes ─────────────────────────────────────────────────────
    Route::post('/muni/mensaje', [ChatController::class, 'sendMessage'])->name('muni.mensaje');

    // ── Historial de conversaciones ─────────────────────────────────────────
    Route::get('/muni/historial', [ChatController::class, 'history'])->name('muni.historial');

    // ── Cargar conversación específica ──────────────────────────────────────
    Route::post('/muni/conversacion', [ChatController::class, 'getConversacion'])->name('muni.conversacion');

    // ── Nueva conversación ──────────────────────────────────────────────────
    Route::post('/muni/nueva', [ChatController::class, 'nuevaConversacion'])->name('muni.nueva');

    // ── Audio: subir y transcribir ──────────────────────────────────────────
    Route::post('/muni/audio', [AudioController::class, 'uploadAudio'])->name('muni.audio');
});