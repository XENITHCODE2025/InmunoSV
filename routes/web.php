<?php
use App\Http\Controllers\Auth\LoginController;

use Illuminate\Support\Facades\Route;

// Página de inicio (Tu index.html)
Route::get('/', function () {
    return view('welcome');
});

// Pantalla de Bienvenida
Route::get('/bienvenida', function () {
    return view('Bienvenida');
});

// Autenticación y Registro
Route::get('/login', function () {
    return view('Login');
});

Route::get('/registro', function () {
    return view('Registro');
});

// Perfil e Historiales del Usuario
Route::get('/mi-perfil', function () {
    return view('Mi-perfil');
});

Route::get('/mi-historial', function () {
    return view('Mi-historial');
});

// Gestión de Vacunas e Historiales
Route::get('/crear-historial', function () {
    return view('Crear-historial-de-vacunacion');
});

Route::get('/registrar-vacuna', function () {
    return view('Registrar-vacuna');
});

// Notificaciones y Recordatorios
Route::get('/notificaciones', function () {
    return view('Notificacion');
});

Route::get('/recordatorios', function () {
    return view('Recordatorios');
});




/* NOTA: Las siguientes rutas son simulaciones para la maqueta frontend. En una implementación real, estas acciones deberían ser manejadas
 por controladores y lógica de backend adecuada. */
// Módulo Municipal / Alcaldía
Route::get('/muni', function () {
    return view('muni');
});

// Intercepta el envío del formulario de registro y redirige temporalmente a Bienvenida
Route::post('/registro', function () {
    return redirect('/bienvenida');
});

// Simula la ruta de logout para la maqueta frontend
Route::post('/logout', function () {
    return redirect('/');
})->name('logout');

// Simula la ruta POST para almacenar el historial médico en la maqueta
Route::post('/crear-historial', function () {
    return redirect('/mi-historial');
})->name('historial.store');

Route::get('/muni', function () {
    // Si no hay un usuario logueado en la sesión de Laragon, simulamos uno rápido
    if (!auth()->check()) {
        $user = new \App\Models\User();
        $user->name = "José Heinar";
        $user->email = "jose.jimenez@gmail.com";
        $user->fecha_nacimiento = "1998-03-15";
        $user->genero = "Masculino";
        $user->telefono = "7123-4567";

        auth()->login($user); 
    }

    return view('muni');
});

// Simula el guardado de la vacuna en la maqueta frontend
Route::post('/registrar-vacuna', function () {
    return redirect('/mi-historial');
})->name('vacunas.store');



//ruta controller registro de users 
use App\Http\Controllers\Auth\RegisterController;

Route::post('/registro', [RegisterController::class, 'store']);

//ruta controller login de users 
Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', [LoginController::class, 'login']);

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth');
