<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\EncuestaSaludController;
use App\Http\Controllers\VacunaController;
use App\Http\Controllers\HistorialController;
use App\Http\Controllers\RegistrarVacunaController;
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
    Route::get('/mi-historial', [HistorialController::class, 'index'])
        ->middleware('auth');

    Route::get('/crear-historial', function () {
        return view('Crear-historial-de-vacunacion');
    });

    Route::post('/crear-historial', function () {
        return redirect('/mi-historial');
    })->name('historial.store');

    // Vacunas
    Route::get('/Registrar-vacuna', [RegistrarVacunaController::class, 'create'])
        ->middleware('auth');

    Route::post('/Registrar-vacuna', [VacunaController::class, 'store'])
        ->name('Registrar-vacuna')
        ->middleware('auth');

    Route::delete(
        '/vacunas/{id}',
        [RegistrarVacunaController::class, 'destroy']
    );

    // Notificaciones
    Route::get('/notificaciones', function () {
        return view('Notificacion');
    });


    Route::get(
        '/recordatorios',
        [HistorialController::class, 'recordatorios']
    );

    Route::put(
        '/vacuna/completar/{id}',
        [VacunaController::class, 'completar']
    )->name('vacuna.completar');

    Route::put(
        '/vacuna/{id}/completar',
        [HistorialController::class, 'completarVacuna']
    )->name('vacuna.completar');

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

use App\Http\Controllers\PerfilController;

Route::post('/perfil/foto', [PerfilController::class, 'subirFoto'])
    ->middleware('auth')
    ->name('perfil.foto');

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
