<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerfilController extends Controller
{
    public function subirFoto(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $usuario = Auth::user();

        if ($request->hasFile('foto')) {

            $archivo = $request->file('foto');

            $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();

            $archivo->move(public_path('uploads/perfiles'), $nombreArchivo);

            $usuario->foto = 'uploads/perfiles/' . $nombreArchivo;

            $usuario->save();
        }

        return back()->with('success', 'Foto actualizada correctamente');
    }
}