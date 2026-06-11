<?php

namespace App\Http\Controllers;

use App\Models\Vacuna;
use App\Models\EncuestaSalud;
use App\Models\EncuestaVacuna;
use App\Models\VacunaRegistrada;
use Illuminate\Support\Facades\Auth;

class RegistrarVacunaController extends Controller
{
    public function create()
    {
        $vacunas = Vacuna::all();

        $encuesta = EncuestaSalud::where('user_id', auth()->id())
            ->latest('id_encuesta')
            ->first();

        $vacunasEncuesta = [];

        if ($encuesta) {
            $vacunasEncuesta = EncuestaVacuna::where(
                'encuesta_id',
                $encuesta->id_encuesta
            )
                ->pluck('vacuna_id')
                ->toArray();
        }

        return view(
            'Registrar-vacuna',
            compact('vacunas', 'vacunasEncuesta')
        );
    }

    public function destroy($id)
    {
        $vacuna = VacunaRegistrada::findOrFail($id);

        if ($vacuna->user_id != Auth::id()) {
            abort(403);
        }

        $vacuna->delete();

        return redirect()
            ->back()
            ->with(
                'success',
                'Vacuna eliminada correctamente.'
            );
    }
}
