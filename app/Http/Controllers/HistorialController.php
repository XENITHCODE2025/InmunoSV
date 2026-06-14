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

    public function recordatorios()
    {
        $vacunas = VacunaRegistrada::with('vacuna')
            ->where('user_id', Auth::id())
            ->orderBy('fecha_aplicacion')
            ->get();

        $recordatorios = [];

        $conteoProximos = 0;
        $conteoPendientes = 0;
        $conteoCompletados = 0;

        $hoy = \Carbon\Carbon::today();

        foreach ($vacunas as $v) {

            $fecha = \Carbon\Carbon::parse($v->fecha_aplicacion);

            // =========================
            // COMPLETADAS
            // =========================

            if ($v->estado === 'completado') {

                $conteoCompletados++;

                $recordatorios[] = [
                    'id' => $v->id,
                    'nombre' => $v->vacuna->nombre ?? 'Vacuna',
                    'subtitulo' => $v->dosis,
                    'fecha' => $fecha->format('d/m/Y'),
                    'dias' => 'Aplicada',
                    'lugar' => $v->lugar,
                    'direccion' => '',
                    'estado' => 'completado',
                    'color' => '#10b981'
                ];

                continue;
            }

            // =========================
            // PRÓXIMAS
            // =========================

            if ($fecha->greaterThanOrEqualTo($hoy)) {

                $conteoProximos++;

                $recordatorios[] = [
                    'id' => $v->id,
                    'nombre' => $v->vacuna->nombre ?? 'Vacuna',
                    'subtitulo' => $v->dosis,
                    'fecha' => $fecha->format('d/m/Y'),
                    'dias' => 'Faltan ' . $hoy->diffInDays($fecha) . ' días',
                    'lugar' => $v->lugar,
                    'direccion' => '',
                    'estado' => 'proximo',
                    'color' => '#f59e0b'
                ];
            }

            // =========================
            // PENDIENTES
            // =========================

            else {

                $conteoPendientes++;

                $recordatorios[] = [
                    'id' => $v->id,
                    'nombre' => $v->vacuna->nombre ?? 'Vacuna',
                    'subtitulo' => $v->dosis,
                    'fecha' => $fecha->format('d/m/Y'),
                    'dias' => 'Fecha vencida',
                    'lugar' => $v->lugar,
                    'direccion' => '',
                    'estado' => 'pendiente',
                    'color' => '#ef4444'
                ];
            }
        }

        $conteoActivos = $conteoProximos + $conteoPendientes;

        return view('Recordatorios', compact(
            'recordatorios',
            'conteoProximos',
            'conteoPendientes',
            'conteoCompletados',
            'conteoActivos'
        ));
    }
    public function completarVacuna($id)
    {
        $vacuna = VacunaRegistrada::findOrFail($id);

        // Seguridad: que solo pueda modificar sus vacunas
        if ($vacuna->user_id != Auth::id()) {
            abort(403);
        }

        $vacuna->estado = 'completado';
        $vacuna->save();

        return redirect()->back()
            ->with('success', 'Vacuna marcada como completada');
    }
}
