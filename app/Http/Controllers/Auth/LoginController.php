<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\EncuestaSalud;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {

            $request->session()->regenerate();

            $encuestaExiste = EncuestaSalud::where(
                'user_id',
                Auth::id()
            )->exists();

            if ($encuestaExiste) {
                return redirect('/mi-historial');
            }

            return redirect('/bienvenida');
        }

        return back()->withErrors([
            'email' => 'Correo o contraseña incorrectos.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        // Invalida la sesión actual en el servidor (la marca como inválida en BD/storage)
        $request->session()->invalidate();

        // Genera un nuevo token CSRF para la siguiente sesión
        $request->session()->regenerateToken();

        // Redirige con headers no-cache para que el navegador no muestre
        // la vista anterior al presionar "atrás" (el middleware
        // PreventBackHistory hace esto en todas las rutas autenticadas,
        // pero lo forzamos también aquí explícitamente en la respuesta
        // de logout por si el navegador cachea el redirect en sí).
        return redirect('/')
            ->withHeaders([
                'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma'        => 'no-cache',
                'Expires'       => 'Sat, 01 Jan 2000 00:00:00 GMT',
            ]);
    }
}