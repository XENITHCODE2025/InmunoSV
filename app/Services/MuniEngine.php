<?php

namespace App\Services;

use App\Models\Conversacion;
use App\Models\User;
use App\Models\Vacuna;
use App\Models\VacunaRegistrada;
use Illuminate\Support\Collection;

/**
 * MuniEngine v2
 * Consulta datos reales del usuario desde la base de datos.
 * No utiliza IA externa.
 */
class MuniEngine
{
    private User         $user;
    private Conversacion $conversacion;

    private int        $edad            = 0;
    private Collection $vacunasAplicadas;
    private Collection $vacunasEncuesta;
    private Collection $condiciones;
    private Collection $todasLasVacunas;
    private ?string    $municipio       = null;
    private ?string    $otraCondicion   = null;

    private array $intenciones = [
        'saludo'        => ['hola', 'buenos', 'buenas', 'hey', 'saludos', 'hi', 'que tal', 'que hay', 'buen dia'],
        'vacunas'       => ['que vacunas me faltan', 'vacunas pendientes', 'cuales me faltan', 'que me falta'],
        'pendientes'    => ['faltan', 'falta', 'pendiente', 'pendientes', 'me falta', 'no tengo', 'necesito', 'cuales me faltan', 'que me faltan', 'que vacunas me faltan'],
        'aplicadas'     => ['aplique', 'apliqué', 'tengo aplicadas', 'puse', 'recibi', 'ya me puse', 'complete', 'me aplique', 'tengo aplicada', 'aplicadas', 'he aplicado', 'me he puesto', 'ya tengo', 'cuales tengo', 'que tengo', 'tengo registradas', 'historial', 'mis vacunas'],
        'recordatorio'  => ['recordatorio', 'recordar', 'alerta', 'avisos', 'cita', 'citas', 'notificacion', 'avisar'],
        'lugar'         => ['donde', 'lugar', 'centro', 'clinica', 'hospital', 'unidad', 'aplicar', 'puesto', 'me la puedo'],
        'informacion'   => ['informacion', 'que es', 'para que', 'sirve', 'explica', 'cuentame', 'detalle', 'describe'],
        'esquema'       => ['esquema', 'calendario', 'plan', 'cuantas', 'veces', 'refuerzo', 'refuerzos'],
        'recomendacion' => ['recomienda', 'recomendacion', 'sugiere', 'sugerencia', 'consejo', 'que debo'],
        'condiciones'   => ['condicion', 'condiciones', 'enfermedad', 'enfermedades', 'padezco', 'cronico', 'cronica'],
        'catalogo'      => ['catalogo', 'lista de vacunas', 'todas las vacunas', 'vacunas disponibles', 'que vacunas hay'],
        'ayuda'         => ['ayuda', 'soporte', 'opciones', 'funciones', 'que puedes', 'como funciona', 'no se que hacer'],
        'despedida'     => ['adios', 'chao', 'chau', 'hasta luego', 'bye', 'gracias', 'muchas gracias'],
    ];

    private array $sinonimosVacunas = [
        'influenza'   => ['influenza', 'gripe', 'flu', 'gripa'],
        'hepatitis_b' => ['hepatitis b', 'hepatitis', 'hep b'],
        'tetanos'     => ['tetanos', 'tetano', 'td', 'dt', 'toxoide'],
        'vph'         => ['vph', 'papiloma', 'papilomavirus', 'hpv'],
        'neumococo'   => ['neumococo', 'neumonia', 'pneumococo'],
        'covid'       => ['covid', 'coronavirus', 'sars'],
    ];

    // ─── Constructor ──────────────────────────────────────────────────────────

    public function __construct(User $user, Conversacion $conversacion)
    {
        $this->user         = $user;
        $this->conversacion = $conversacion;
        $this->cargarDatosUsuario();
    }

    // ─── API pública ──────────────────────────────────────────────────────────

    public function getMuniResponse(string $mensajeRaw): string
    {
        $texto     = $this->normalizar($mensajeRaw);
        $intencion = $this->detectarIntencion($texto);
        $vacunaRef = $this->detectarVacunaEspecifica($texto);

        if ($vacunaRef) {
            $this->actualizarContexto('ultima_vacuna', $vacunaRef);
        }

        return $this->generarRespuesta($intencion, $texto, $vacunaRef);
    }

    // ─── Carga de datos desde BD ──────────────────────────────────────────────

    private function cargarDatosUsuario(): void
    {
        // fecha_nacimiento ya viene como cast 'date' en User, Carbon lo maneja directo
        $this->edad = $this->user->fecha_nacimiento
            ? $this->user->fecha_nacimiento->age
            : 0;

        // Vacunas registradas manualmente (tabla vacunas_aplicadas via VacunaRegistrada)
        $this->vacunasAplicadas = $this->user->vacunasAplicadas()->get();

        // Encuesta de salud con sus relaciones pivot
        $encuesta = $this->user->encuestaSalud()
            ->with(['vacunas', 'condiciones'])
            ->first();

        $this->vacunasEncuesta = $encuesta ? $encuesta->vacunas  : collect();
        $this->condiciones     = $encuesta ? $encuesta->condiciones : collect();
        $this->municipio       = $encuesta?->municipio;
        $this->otraCondicion   = $encuesta?->otra_condicion;

        // Catálogo completo de vacunas del sistema
        $this->todasLasVacunas = Vacuna::all();
    }

    // ─── Normalización ────────────────────────────────────────────────────────

    private function normalizar(?string $texto): string
    {
        if ($texto === null) return '';
        $texto = mb_strtolower(trim($texto));
        return strtr($texto, [
            'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
            'à' => 'a', 'è' => 'e', 'ì' => 'i', 'ò' => 'o', 'ù' => 'u',
            'ü' => 'u', 'ñ' => 'n',
        ]);
    }

    // ─── Detección de intención ───────────────────────────────────────────────

    private function detectarIntencion(string $texto): string
    {
        $mejor   = 'desconocido';
        $puntaje = 0;

        foreach ($this->intenciones as $intencion => $palabras) {
            $pts = 0;
            foreach ($palabras as $p) {
                if (str_contains($texto, $p)) {
                    // Las frases de más de una palabra tienen doble peso
                    $pts += str_word_count($p) > 1 ? 2 : 1;
                }
            }
            if ($pts > $puntaje) {
                $puntaje = $pts;
                $mejor   = $intencion;
            }
        }

        // ── Reglas explícitas de desambiguación ───────────────────────────────

        // "tengo aplicadas/aplicada/registradas" → aplicadas (no pendientes)
        if (preg_match('/tengo.*(aplicad|registrad|pues)/i', $texto)) {
            $mejor = 'aplicadas';
        }

        // "que vacunas he/ya/me" sin "faltan/falta/pendiente" → aplicadas
        if (str_contains($texto, 'vacuna') &&
            (str_contains($texto, 'ya') || str_contains($texto, 'he') || str_contains($texto, 'historial')) &&
            !str_contains($texto, 'falt') && !str_contains($texto, 'pendiente')) {
            $mejor = 'aplicadas';
        }

        // "donde" siempre es lugar
        if (str_contains($texto, 'donde') || str_contains($texto, 'me la puedo')) {
            $mejor = 'lugar';
        }

        return $mejor;
    }

    // ─── Detección de vacuna en el texto ──────────────────────────────────────

    private function detectarVacunaEspecifica(string $texto): ?string
    {
        foreach ($this->sinonimosVacunas as $clave => $sinonimos) {
            foreach ($sinonimos as $s) {
                if (str_contains($texto, $s)) return $clave;
            }
        }

        foreach ($this->todasLasVacunas as $vacuna) {
            if (!$vacuna->nombre) continue;
            $nombre = $this->normalizar($vacuna->nombre);
            if (str_contains($texto, $nombre)) return $nombre;
        }

        $pronombres = [' la ', 'esa', 'esas', 'ella', 'las mismas', 'esa vacuna'];
        foreach ($pronombres as $p) {
            if (str_contains($texto, $p)) return $this->conversacion->ultima_vacuna;
        }

        return null;
    }

    // ─── Generador de respuestas ──────────────────────────────────────────────

    private function generarRespuesta(string $intencion, string $texto, ?string $vacunaRef): string
    {
        $nombre = $this->user->name ?? 'Usuario';

        return match ($intencion) {
            'saludo'        => $this->respuestaSaludo($nombre),
            'vacunas'       => $this->respuestaVacunasMenu($nombre),
            'pendientes'    => $this->respuestaVacunasPendientes($nombre),
            'aplicadas'     => $this->respuestaVacunasAplicadas($nombre),
            'recordatorio'  => $this->respuestaRecordatorio($nombre),
            'lugar'         => $this->respuestaLugar($nombre, $vacunaRef),
            'informacion'   => $this->respuestaInformacion($nombre, $vacunaRef),
            'esquema'       => $this->respuestaEsquema($nombre),
            'recomendacion' => $this->respuestaRecomendaciones($nombre),
            'condiciones'   => $this->respuestaCondiciones($nombre),
            'catalogo'      => $this->respuestaCatalogo($nombre),
            'ayuda'         => $this->respuestaAyuda($nombre),
            'despedida'     => $this->respuestaDespedida($nombre),
            default         => $this->respuestaContextual($nombre),
        };
    }

    // ─── Respuestas individuales ──────────────────────────────────────────────

    private function respuestaVacunasMenu(string $nombre): string
    {
        return "Claro, **{$nombre}**. ¿Qué información sobre vacunas necesitas?\n\n" .
               "• 💉 **\"¿Qué vacunas tengo aplicadas?\"** — ver tu historial\n" .
               "• ⏳ **\"¿Qué vacunas me faltan?\"** — ver pendientes\n" .
               "• 📋 **\"¿Cuál es mi esquema?\"** — plan completo según tu edad\n" .
               "• 📖 **\"¿Para qué sirve la Influenza?\"** — información de una vacuna específica";
    }

    private function respuestaSaludo(string $nombre): string
    {
        $hora   = (int) now()->format('H');
        $saludo = $hora < 12 ? '¡Buenos días' : ($hora < 18 ? '¡Buenas tardes' : '¡Buenas noches');
        $extra  = $this->condiciones->isNotEmpty()
            ? "\n\nTengo en cuenta tus condiciones de salud registradas para darte recomendaciones más precisas. 💊"
            : '';

        return "{$saludo}, **{$nombre}**! 👋 Soy Muni, tu asistente de salud.\n\n" .
               "Puedo ayudarte con:\n" .
               "• 💉 Vacunas pendientes o aplicadas\n" .
               "• 📋 Tu esquema de vacunación personalizado\n" .
               "• 🩺 Recomendaciones según tus condiciones de salud\n" .
               "• 🏥 Dónde aplicarte vacunas\n" .
               "• 📅 Recordatorios de vacunación" .
               $extra .
               "\n\n¿Con qué te ayudo hoy?";
    }

    private function respuestaVacunasPendientes(string $nombre): string
    {
        $this->actualizarContexto('ultimo_tema', 'vacunas_pendientes');

        // Unir nombres ya vacunados (historial + encuesta)
        $yaVacunado = collect()
            ->merge($this->vacunasAplicadas->pluck('nombre'))
            ->merge($this->vacunasEncuesta->pluck('nombre'))
            ->filter()                                          // elimina null, '', false
            ->map(fn($n) => $this->normalizar((string) $n))
            ->unique()
            ->toArray();

        $recomendadas = $this->vacunasRecomendadasSegunPerfil();
        $pendientes   = array_values(array_filter(
            $recomendadas,
            fn($v) => !in_array($this->normalizar($v['nombre']), $yaVacunado)
        ));

        if (empty($pendientes)) {
            return "¡Excelentes noticias, **{$nombre}**! 🎉 Según tu historial y encuesta, " .
                   "tienes al día las vacunas recomendadas para tu perfil ({$this->edad} años).\n\n" .
                   "Te recomiendo confirmar con tu médico periódicamente. ¿Necesitas algo más?";
        }

        $lista = implode("\n", array_map(
            fn($v) => "• **{$v['nombre']}** — {$v['razon']}",
            $pendientes
        ));

        $this->actualizarContexto('ultima_recomendacion', implode(', ', array_column($pendientes, 'nombre')));

        return "Según tu perfil, **{$nombre}** ({$this->edad} años), estas vacunas podrían estar pendientes:\n\n" .
               $lista .
               "\n\n¿Te gustaría saber dónde aplicártelas o programar un recordatorio?";
    }

    private function respuestaVacunasAplicadas(string $nombre): string
    {
        $this->actualizarContexto('ultimo_tema', 'vacunas_aplicadas');

        if ($this->vacunasAplicadas->isEmpty() && $this->vacunasEncuesta->isEmpty()) {
            return "Hola, **{$nombre}**. Aún no tienes vacunas registradas en tu historial.\n\n" .
                   "Puedes agregarlas desde **Mi Control**. ¿Te ayudo con algo más?";
        }

        $respuesta = "Aquí está tu registro de vacunación, **{$nombre}**:\n\n";

        if ($this->vacunasAplicadas->isNotEmpty()) {
            $respuesta .= "💉 **Vacunas aplicadas (historial):**\n";
            foreach ($this->vacunasAplicadas as $v) {
                $fecha     = $v->fecha_aplicacion ? $v->fecha_aplicacion->format('d/m/Y') : 'sin fecha';
                $lugar     = $v->lugar ? " en {$v->lugar}" : '';
                $respuesta .= "• **{$v->nombre}** — {$v->dosis}{$lugar} ({$fecha})\n";
            }
        }

        if ($this->vacunasEncuesta->isNotEmpty()) {
            $respuesta .= "\n📋 **Vacunas reportadas en tu encuesta inicial:**\n";
            foreach ($this->vacunasEncuesta as $v) {
                $respuesta .= "• {$v->nombre}\n";
            }
        }

        return $respuesta . "\n¿Quieres revisar qué vacunas podrían faltarte?";
    }

    private function respuestaCondiciones(string $nombre): string
    {
        $this->actualizarContexto('ultimo_tema', 'condiciones');

        if ($this->condiciones->isEmpty() && !$this->otraCondicion) {
            return "No tengo condiciones médicas registradas en tu perfil, **{$nombre}**.\n\n" .
                   "Si tienes alguna condición crónica, puedes actualizarla en tu encuesta de salud " .
                   "para recibir recomendaciones más precisas.";
        }

        $respuesta = "Condiciones de salud registradas en tu perfil, **{$nombre}**:\n\n";

        foreach ($this->condiciones as $c) {
            $respuesta .= "• **{$c->nombre}**";
            if ($c->descripcion) $respuesta .= " — {$c->descripcion}";
            $respuesta .= "\n";
        }

        if ($this->otraCondicion) {
            $respuesta .= "• {$this->otraCondicion} *(condición adicional)*\n";
        }

        $recs = $this->recomendacionesPorCondicion();
        if (!empty($recs)) {
            $respuesta .= "\n🛡️ **Recomendaciones especiales para ti:**\n";
            foreach ($recs as $rec) {
                $respuesta .= "• {$rec}\n";
            }
        }

        return $respuesta . "\n¿Quieres más información sobre alguna de estas recomendaciones?";
    }

    private function respuestaCatalogo(string $nombre): string
    {
        if ($this->todasLasVacunas->isEmpty()) {
            return "El catálogo de vacunas aún no tiene registros, **{$nombre}**. " .
                   "Consulta con tu médico o visita el MINSAL.";
        }

        $lista = $this->todasLasVacunas->map(
            fn($v) => "• **{$v->nombre}**" . ($v->descripcion ? " — {$v->descripcion}" : '')
        )->join("\n");

        return "Catálogo de vacunas disponibles en el sistema, **{$nombre}**:\n\n" .
               $lista .
               "\n\n¿Quieres información detallada sobre alguna en particular?";
    }

    private function respuestaRecordatorio(string $nombre): string
    {
        $this->actualizarContexto('ultimo_tema', 'recordatorios');

        $pendientes = array_column($this->vacunasRecomendadasSegunPerfil(), 'nombre');

        if (empty($pendientes)) {
            return "No tienes vacunas pendientes registradas, **{$nombre}**. " .
                   "Aún así puedes crear recordatorios desde la sección **Recordatorios**. ¿Te ayudo con algo más?";
        }

        $lista = implode("\n", array_map(fn($v) => "• {$v}", $pendientes));

        return "Claro, **{$nombre}**. Puedo ayudarte a recordar:\n\n" .
               $lista .
               "\n\nVe a la sección **Recordatorios** en el menú para configurar fechas y alertas. ¿Deseas hacer algo más?";
    }

    private function respuestaLugar(string $nombre, ?string $vacunaRef): string
    {
        $vacuna  = $vacunaRef ?? $this->conversacion->ultima_vacuna;
        $mencion = $vacuna ? "**{$this->nombreAmigable($vacuna)}**" : 'esa vacuna';
        $lugar   = $this->municipio ? " en **{$this->municipio}**" : '';

        return "Para aplicarte {$mencion}{$lugar}, puedes acudir a:\n\n" .
               "• 🏥 **Unidades de Salud del MINSAL** más cercana\n" .
               "• 🏨 **Hospitales Nacionales** de tu departamento\n" .
               "• 💊 **Farmacias y clínicas autorizadas** con servicio de vacunación\n\n" .
               "Te recomiendo llamar antes para confirmar disponibilidad. ¿Necesitas información sobre otra vacuna?";
    }

    private function respuestaInformacion(string $nombre, ?string $vacunaRef): string
    {
        $clave = $vacunaRef ?? $this->conversacion->ultima_vacuna;

        if ($clave) {
            // Buscar primero en el catálogo real de la BD
            $vacunaDb = $this->todasLasVacunas->first(
                fn($v) => $this->normalizar($v->nombre) === $clave
                       || str_contains($this->normalizar($v->nombre), $clave)
            );

            if ($vacunaDb && $vacunaDb->descripcion) {
                return "Información sobre **{$vacunaDb->nombre}**:\n\n" .
                       $vacunaDb->descripcion . "\n\n¿Tienes alguna otra pregunta?";
            }
        }

        if (!$clave) {
            return "Con gusto te explico, **{$nombre}**. ¿Sobre qué vacuna quieres información?\n\n" .
                   "Escribe **\"catálogo\"** para ver todas las vacunas disponibles.";
        }

        return "Información sobre **{$this->nombreAmigable($clave)}**:\n\n" .
               $this->infoGeneralVacuna($clave) . "\n\n¿Tienes alguna otra pregunta?";
    }

    private function respuestaEsquema(string $nombre): string
    {
        $this->actualizarContexto('ultimo_tema', 'esquema');

        $recomendadas = $this->vacunasRecomendadasSegunPerfil();
        $lista = implode("\n", array_map(fn($v) => "• **{$v['nombre']}** — {$v['razon']}", $recomendadas));

        $condText = $this->condiciones->isNotEmpty()
            ? "\n⚠️ Considerando tus condiciones: **" . $this->condiciones->pluck('nombre')->join(', ') . "**\n"
            : '';

        return "Tu esquema de vacunación recomendado, **{$nombre}** ({$this->edad} años):\n{$condText}\n" .
               $lista .
               "\n\n📋 Consulta siempre con tu médico para un plan personalizado.";
    }

    private function respuestaRecomendaciones(string $nombre): string
    {
        $this->actualizarContexto('ultimo_tema', 'recomendaciones');

        $recomendadas = $this->vacunasRecomendadasSegunPerfil();
        $lista = implode("\n", array_map(fn($v) => "• **{$v['nombre']}** — {$v['razon']}", $recomendadas));

        $condText = '';
        if ($this->condiciones->isNotEmpty()) {
            $condText = "\n\n🩺 **Por tus condiciones ({$this->condiciones->pluck('nombre')->join(', ')}):**\n";
            $condText .= implode("\n", array_map(fn($r) => "• {$r}", $this->recomendacionesPorCondicion()));
        }

        $genText = '';
        if ($this->normalizar($this->user->genero ?? '') === 'femenino' && $this->edad < 45) {
            $genText = "\n\n👩 **Para mujeres:** Asegúrate de completar el esquema de **VPH** si aún no lo has hecho.";
        }

        return "Basándome en tu perfil, **{$nombre}** ({$this->edad} años):\n\n" .
               "💉 **Vacunas recomendadas:**\n" . $lista .
               $condText . $genText .
               "\n\n🛡️ **Consejos generales:**\n" .
               "• Mantén tu carnet de vacunación actualizado\n" .
               "• Lávate las manos frecuentemente\n" .
               "• Consulta a tu médico ante cualquier síntoma\n\n" .
               "¿Deseas programar algún recordatorio?";
    }

    private function respuestaAyuda(string $nombre): string
    {
        return "Hola, **{$nombre}**. Esto es lo que puedo hacer por ti:\n\n" .
               "💉 **Vacunas**\n" .
               "  → \"¿Qué vacunas me faltan?\"\n" .
               "  → \"¿Qué vacunas ya me he puesto?\"\n\n" .
               "📋 **Catálogo**\n" .
               "  → \"¿Qué vacunas hay disponibles?\"\n\n" .
               "🩺 **Mis condiciones**\n" .
               "  → \"¿Qué condiciones tengo registradas?\"\n\n" .
               "📅 **Recordatorios**\n" .
               "  → \"Quiero un recordatorio para la influenza\"\n\n" .
               "🏥 **Lugares**\n" .
               "  → \"¿Dónde me aplico la Hepatitis B?\"\n\n" .
               "📊 **Esquema**\n" .
               "  → \"¿Cuál es mi esquema de vacunación?\"\n\n" .
               "Escribe cualquier pregunta y haré lo posible por ayudarte. 😊";
    }

    private function respuestaDespedida(string $nombre): string
    {
        return "¡Hasta pronto, **{$nombre}**! 👋 Fue un gusto ayudarte. " .
               "Recuerda mantener tu esquema de vacunación al día. ¡Cuídate mucho! 💙";
    }

    private function respuestaContextual(string $nombre): string
    {
        return match ($this->conversacion->ultimo_tema) {
            'vacunas_pendientes' => "¿Tienes más preguntas sobre tus vacunas pendientes, **{$nombre}**? " .
                                    "Puedo decirte dónde aplicártelas o programar un recordatorio.",
            'condiciones'        => "Si quieres más información sobre cómo tus condiciones afectan tu vacunación, con gusto te ayudo, **{$nombre}**.",
            default              => "No estoy seguro de entender tu mensaje, **{$nombre}**. 😅\n\n" .
                                    "Escribe **\"ayuda\"** para ver todas mis funciones.",
        };
    }

    // ─── Lógica de negocio ────────────────────────────────────────────────────

    private function vacunasRecomendadasSegunPerfil(): array
    {
        $recomendadas = [];
        $genero       = $this->normalizar($this->user->genero ?? '');

        if ($this->edad < 18) {
            $recomendadas[] = ['nombre' => 'VPH',         'razon' => 'esquema de 2 dosis para menores de 15 años'];
            $recomendadas[] = ['nombre' => 'Tétanos',     'razon' => 'refuerzo cada 10 años'];
            $recomendadas[] = ['nombre' => 'Influenza',   'razon' => 'anualmente'];
            $recomendadas[] = ['nombre' => 'Hepatitis B', 'razon' => 'si no completaste el esquema de 3 dosis'];
        } elseif ($this->edad <= 59) {
            $recomendadas[] = ['nombre' => 'Influenza',   'razon' => 'anualmente, especialmente en temporada'];
            $recomendadas[] = ['nombre' => 'Hepatitis B', 'razon' => 'si no completaste las 3 dosis'];
            $recomendadas[] = ['nombre' => 'Tétanos',     'razon' => 'refuerzo cada 10 años'];
            if ($this->edad < 26) {
                $recomendadas[] = ['nombre' => 'VPH', 'razon' => 'si no completaste el esquema'];
            }
        } else {
            $recomendadas[] = ['nombre' => 'Influenza',  'razon' => 'prioritaria en adultos mayores'];
            $recomendadas[] = ['nombre' => 'Neumococo',  'razon' => '1 o 2 dosis según indicación médica'];
            $recomendadas[] = ['nombre' => 'Tétanos',    'razon' => 'refuerzo cada 10 años'];
        }

        // Ajustes por condiciones médicas
        $nombresCondiciones = $this->condiciones->pluck('nombre')->filter()->map(fn($c) => $this->normalizar((string) $c))->toArray();
        $condicionesRiesgo  = ['diabet', 'asma', 'epoc', 'cardio', 'corazon', 'hipertens', 'renal', 'hepatic', 'inmuno'];

        foreach ($condicionesRiesgo as $cond) {
            foreach ($nombresCondiciones as $nc) {
                if (str_contains($nc, $cond)) {
                    $recomendadas[] = ['nombre' => 'Neumococo', 'razon' => 'recomendada por tu condición de salud registrada'];
                    $recomendadas[] = ['nombre' => 'Influenza',  'razon' => 'prioritaria dado tu historial de salud'];
                    break 2;
                }
            }
        }

        // Ajuste por género
        if (str_contains($genero, 'femenin') && $this->edad < 45) {
            $yaVPH = collect($recomendadas)->pluck('nombre')->filter()->map(fn($n) => $this->normalizar((string) $n))->contains('vph');
            if (!$yaVPH) {
                $recomendadas[] = ['nombre' => 'VPH', 'razon' => 'recomendada para mujeres menores de 45 años'];
            }
        }

        // Eliminar duplicados por nombre
        $vistos = [];
        return array_values(array_filter($recomendadas, function ($v) use (&$vistos) {
            if (empty($v['nombre'])) return false;
            $key = $this->normalizar((string) $v['nombre']);
            if (in_array($key, $vistos)) return false;
            $vistos[] = $key;
            return true;
        }));
    }

    private function recomendacionesPorCondicion(): array
    {
        $recs = [];
        foreach ($this->condiciones as $condicion) {
            $nombre = $this->normalizar($condicion->nombre);
            if (str_contains($nombre, 'diabet')) {
                $recs[] = 'Vacuna contra la **Influenza** anualmente (mayor riesgo de complicaciones)';
                $recs[] = 'Vacuna contra el **Neumococo** (mayor susceptibilidad a neumonía)';
            }
            if (str_contains($nombre, 'asma') || str_contains($nombre, 'epoc') || str_contains($nombre, 'pulmon')) {
                $recs[] = 'Vacuna contra la **Influenza** es prioritaria en enfermedades respiratorias';
                $recs[] = 'Vacuna contra el **Neumococo** reduce riesgo de neumonía grave';
            }
            if (str_contains($nombre, 'cardiac') || str_contains($nombre, 'corazon') || str_contains($nombre, 'hipertens')) {
                $recs[] = 'Vacuna contra la **Influenza** reduce riesgo de eventos cardiovasculares';
            }
            if (str_contains($nombre, 'inmuno') || str_contains($nombre, 'vih') || str_contains($nombre, 'sida')) {
                $recs[] = 'Consulta con tu médico: algunas vacunas de **virus vivos** pueden estar contraindicadas';
            }
        }
        return array_unique($recs);
    }

    // ─── Utilidades ───────────────────────────────────────────────────────────

    private function nombreAmigable(?string $clave): string
    {
        return match ($clave) {
            'influenza'   => 'la Influenza',
            'hepatitis_b' => 'la Hepatitis B',
            'tetanos'     => 'el Tétanos (Td)',
            'vph'         => 'el VPH',
            'neumococo'   => 'el Neumococo',
            'covid'       => 'el COVID-19',
            default       => $clave ? "la vacuna **{$clave}**" : 'esa vacuna',
        };
    }

    private function infoGeneralVacuna(string $clave): string
    {
        return match ($clave) {
            'influenza'   => "Protege contra los virus de la gripe estacional. Se actualiza anualmente. Recomendada en embarazadas, adultos mayores y personas con enfermedades crónicas.",
            'hepatitis_b' => "Protege contra la infección viral del hígado. Esquema de **3 dosis** (0, 1 y 6 meses). Importante completarlo para protección duradera.",
            'tetanos'     => "Protege contra el tétanos y la difteria. **Refuerzo cada 10 años**. En heridas profundas puede aplicarse antes.",
            'vph'         => "Protege contra tipos de VPH que causan cáncer cervicouterino. **2 dosis** en menores de 15 años, **3 dosis** en mayores.",
            'neumococo'   => "Protege frente a neumonía, meningitis y sepsis por Streptococcus pneumoniae. Recomendada en adultos mayores y personas con condiciones crónicas.",
            'covid'       => "Protege contra formas graves de COVID-19. Consulta con tu médico sobre refuerzos según tu historial.",
            default       => "No tengo información detallada sobre esa vacuna. Te recomiendo consultar con tu médico o visitar el sitio del MINSAL.",
        };
    }

    private function actualizarContexto(string $campo, string $valor): void
    {
        $this->conversacion->update([$campo => $valor]);
    }
}