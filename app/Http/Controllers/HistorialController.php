<?php

namespace App\Http\Controllers;

use App\Models\Vacuna;
use App\Models\EncuestaSalud;
use App\Models\EncuestaVacuna;
use App\Models\VacunaRegistrada;
use Illuminate\Support\Facades\Auth;

class HistorialController extends Controller
{
    public function index()
    {
        // ===================================
        // VACUNAS APLICADAS
        // ===================================

        $vacunasAplicadas = VacunaRegistrada::with('vacuna')
            ->where('user_id', Auth::id())
            ->orderBy('fecha_aplicacion', 'desc')
            ->get();

        // IDs de vacunas aplicadas
        $vacunasAplicadasIds = VacunaRegistrada::where(
            'user_id',
            Auth::id()
        )->pluck('vacuna_id');

        // ===================================
        // VACUNAS PENDIENTES
        // ===================================

        $vacunasPendientes = Vacuna::whereNotIn(
            'id_vacuna',
            $vacunasAplicadasIds
        )
        ->orderBy('nombre')
        ->get();

        // ===================================
        // VACUNAS PARA EL FILTRO
        // ===================================

        $vacunas = Vacuna::orderBy('nombre')->get();

        // ===================================
        // VACUNAS MARCADAS EN ENCUESTA
        // ===================================

        $vacunasEncuestaIds = collect();

        $encuesta = EncuestaSalud::where(
            'user_id',
            Auth::id()
        )->first();

        if ($encuesta) {

            $vacunasEncuestaIds = EncuestaVacuna::where(
                'encuesta_id',
                $encuesta->id_encuesta
            )->pluck('vacuna_id');
        }

        // ===================================
        // RECOMENDACIONES
        // ===================================

        $vacunasExcluidas = $vacunasEncuestaIds
            ->merge($vacunasAplicadasIds)
            ->unique();

        $vacunasRecomendadas = Vacuna::with('recomendacion')
            ->whereNotIn('id_vacuna', $vacunasExcluidas)
            ->inRandomOrder()
            ->take(3)
            ->get();

        // ===================================
        // RETORNAR VISTA
        // ===================================

        return view('Mi-Historial', compact(
            'vacunasAplicadas',
            'vacunasPendientes',
            'vacunasRecomendadas',
            'vacunas'
        ));
    }
}