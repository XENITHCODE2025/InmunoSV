<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VacunaRegistrada;
use Illuminate\Support\Facades\Auth;

class VacunaController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'vacuna_id' => 'required',
            'fecha' => 'required|date|before_or_equal:today',
            'dosis' => 'required',
            'lugar' => 'nullable|string|max:255',
        ]);

        VacunaRegistrada::create([
            'user_id' => Auth::id(),
            'vacuna_id' => $request->vacuna_id,
            'tipo' => $request->tipo,
            'fecha_aplicacion' => $request->fecha,
            'dosis' => $request->dosis,
            'lugar' => $request->lugar,
        ]);

        return redirect('/mi-historial')
            ->with('success', 'Vacuna registrada correctamente');
    }
}