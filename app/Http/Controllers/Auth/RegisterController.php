<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'fecha_nacimiento' => 'required|date',
            'genero' => 'nullable|in:femenino,masculino,otro',
            'telefono' => 'required|string|max:9',
            'departamento' => 'required|string|max:50',
            'email' => 'required|email|unique:users,email',
            'password' => [
                'required',
                'confirmed',
                'min:8'
            ]
        ]);

        $user = User::create([
            'name' => $request->name,
            'apellido' => $request->apellido,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'genero' => $request->genero,
            'telefono' => $request->telefono,
            'departamento' => $request->departamento,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        Auth::login($user);

        return redirect('/login')
            ->with('success', 'Cuenta creada correctamente');
    }
}