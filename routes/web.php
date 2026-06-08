<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\EncuestaSaludController;


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