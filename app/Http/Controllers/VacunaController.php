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
            'fecha' => 'required|date',
            'dosis' => 'required',
            'lugar' => 'required|string|max:255',
        ]);

        $estado = $request->fecha > now()->toDateString()
            ? 'pendiente'
            : 'completado';

        VacunaRegistrada::create([
            'user_id' => Auth::id(),
            'vacuna_id' => $request->vacuna_id,
            'tipo' => $request->tipo,
            'fecha_aplicacion' => $request->fecha,
            'dosis' => $request->dosis,
            'lugar' => $request->lugar,
            'estado' => $estado,
        ]);

        return redirect('/mi-historial')
            ->with('success', 'Vacuna registrada correctamente');
    }

    public function completar($id)
    {
        $vacuna = VacunaRegistrada::findOrFail($id);

        $vacuna->update([
            'estado' => 'completado'
        ]);

        return redirect()
            ->back()
            ->with(
                'success',
                'Vacuna marcada como completada'
            );
    }
}
