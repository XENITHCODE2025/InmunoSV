<?php

namespace App\Services;

use App\Models\Conversacion;
use App\Models\User;

/**
 * MuniEngine
 * ──────────────────────────────────────────────────────────────────────────────
 * Motor de respuestas del asistente virtual Muni.
 * No utiliza IA externa. Funciona con heurísticas, reglas de negocio,
 * normalización de texto, palabras clave y contexto conversacional.
 */
class MuniEngine
{
    private User $user;
    private Conversacion $conversacion;
    private array $vacunasAplicadas  = [];
    private array $vacunasPendientes = [];
    private int   $edad              = 0;

    // ─── Intenciones y sus palabras clave ─────────────────────────────────────

    private array $intenciones = [
        'saludo'        => ['hola', 'buenos', 'buenas', 'hey', 'ey', 'saludos', 'hi', 'hello', 'que tal', 'que hay'],
        'vacunas'       => ['vacuna', 'vacunas', 'dosis', 'inyeccion', 'inyecciones', 'piquete', 'piquetes', 'inmunizacion', 'inmunizar', 'biológico'],
        'pendientes'    => ['faltan', 'falta', 'pendiente', 'pendientes', 'me falta', 'no tengo', 'necesito', 'necesito aplicarme'],
        'aplicadas'     => ['aplique', 'apliqué', 'tengo', 'puse', 'recibí', 'recibi', 'ya me', 'completé', 'complete'],
        'recordatorio'  => ['recordatorio', 'recordar', 'recordatorios', 'alerta', 'alertas', 'aviso', 'avisos', 'cita', 'citas', 'notificacion', 'notificacion', 'avisar'],
        'lugar'         => ['donde', 'dónde', 'lugar', 'centro', 'clinica', 'clínica', 'hospital', 'unidad', 'salud', 'aplicar', 'puesto', 'vacunatorio'],
        'informacion'   => ['informacion', 'información', 'que es', 'qué es', 'para que', 'para qué', 'sirve', 'explica', 'cuéntame', 'cuentame', 'detalle'],
        'esquema'       => ['esquema', 'calendario', 'plan', 'cuantas', 'cuántas', 'veces', 'refuerzo', 'refuerzos', 'dosis'],
        'recomendacion' => ['recomienda', 'recomendacion', 'recomendación', 'sugiere', 'sugerencia', 'consejo', 'consejos'],
        'ayuda'         => ['ayuda', 'soporte', 'opciones', 'funciones', 'no se que hacer', 'no sé que hacer', 'que puedes', 'qué puedes', 'como funciona', 'cómo funciona'],
        'despedida'     => ['adios', 'adiós', 'chao', 'chau', 'hasta luego', 'bye', 'gracias', 'muchas gracias'],
    ];

    // ─── Sinónimos de vacunas específicas ─────────────────────────────────────

    private array $sinonimosVacunas = [
        'influenza'   => ['influenza', 'gripe', 'flu', 'gripa'],
        'hepatitis_b' => ['hepatitis b', 'hepatitis', 'hep b', 'hepb'],
        'tetanos'     => ['tetanos', 'tétanos', 'tetano', 'td', 'dt'],
        'vph'         => ['vph', 'papiloma', 'papilomavirus', 'virus del papiloma', 'hpv'],
        'neumococo'   => ['neumococo', 'neumonía', 'neumonia', 'pneumococo'],
        'covid'       => ['covid', 'coronavirus', 'sars', 'pandemia'],
    ];

    // ─── Constructor ──────────────────────────────────────────────────────────

    public function __construct(User $user, Conversacion $conversacion)
    {
        $this->user         = $user;
        $this->conversacion = $conversacion;
        $this->cargarDatosUsuario();
    }

    // ─── API pública ──────────────────────────────────────────────────────────

    /**
     * Punto de entrada principal. Recibe el mensaje crudo del usuario
     * y retorna la respuesta del bot.
     */
    public function getMuniResponse(string $mensajeRaw): string
    {
        $texto     = $this->normalizar($mensajeRaw);
        $intencion = $this->detectarIntencion($texto);
        $vacunaRef = $this->detectarVacunaEspecifica($texto);

        // Guardar contexto si se detectó vacuna
        if ($vacunaRef) {
            $this->actualizarContexto('ultima_vacuna', $vacunaRef);
        }

        return $this->generarRespuesta($intencion, $texto, $vacunaRef);
    }

    // ─── Normalización ────────────────────────────────────────────────────────

    /**
     * Convierte el texto a minúsculas, elimina acentos y caracteres especiales.
     */
    private function normalizar(string $texto): string
    {
        $texto = mb_strtolower(trim($texto));

        $acentos = [
            'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
            'à' => 'a', 'è' => 'e', 'ì' => 'i', 'ò' => 'o', 'ù' => 'u',
            'ü' => 'u', 'ñ' => 'n',
        ];

        return strtr($texto, $acentos);
    }

    // ─── Detección de intención ───────────────────────────────────────────────

    private function detectarIntencion(string $textoNorm): string
    {
        $mejorIntencion = 'desconocido';
        $mejorPuntaje   = 0;

        foreach ($this->intenciones as $intencion => $palabras) {
            $puntaje = 0;
            foreach ($palabras as $palabra) {
                if (str_contains($textoNorm, $palabra)) {
                    $puntaje++;
                }
            }
            if ($puntaje > $mejorPuntaje) {
                $mejorPuntaje   = $puntaje;
                $mejorIntencion = $intencion;
            }
        }

        // Regla: si menciona "donde" junto con contexto de vacuna pendiente → lugar
        if (str_contains($textoNorm, 'donde') || str_contains($textoNorm, 'la puedo')) {
            $mejorIntencion = 'lugar';
        }

        return $mejorIntencion;
    }

    // ─── Detección de vacuna específica ───────────────────────────────────────

    private function detectarVacunaEspecifica(string $textoNorm): ?string
    {
        foreach ($this->sinonimosVacunas as $clave => $sinonimos) {
            foreach ($sinonimos as $sinonimo) {
                if (str_contains($textoNorm, $sinonimo)) {
                    return $clave;
                }
            }
        }

        // Resolución por contexto: pronombres como "ella", "la", "esa"
        $pronombresContexto = ['ella', ' la ', 'esa', 'esas', 'esas vacunas', 'las mismas'];
        foreach ($pronombresContexto as $pron) {
            if (str_contains($textoNorm, $pron)) {
                return $this->conversacion->ultima_vacuna; // recuperar del contexto
            }
        }

        return null;
    }

    // ─── Generador de respuestas ──────────────────────────────────────────────

    private function generarRespuesta(string $intencion, string $texto, ?string $vacunaRef): string
    {
        $nombre = $this->user->nombre ?? 'Usuario';

        switch ($intencion) {

            case 'saludo':
                return $this->respuestaSaludo($nombre);

            case 'vacunas':
            case 'pendientes':
                $this->actualizarContexto('ultimo_tema', 'vacunas_pendientes');
                return $this->respuestaVacunasPendientes($nombre);

            case 'aplicadas':
                $this->actualizarContexto('ultimo_tema', 'vacunas_aplicadas');
                return $this->respuestaVacunasAplicadas($nombre);

            case 'recordatorio':
                $this->actualizarContexto('ultimo_tema', 'recordatorios');
                return $this->respuestaRecordatorio($nombre);

            case 'lugar':
                return $this->respuestaLugar($nombre, $vacunaRef);

            case 'informacion':
                return $this->respuestaInformacion($nombre, $vacunaRef);

            case 'esquema':
                $this->actualizarContexto('ultimo_tema', 'esquema');
                return $this->respuestaEsquema($nombre);

            case 'recomendacion':
                $this->actualizarContexto('ultimo_tema', 'recomendaciones');
                return $this->respuestaRecomendaciones($nombre);

            case 'ayuda':
                return $this->respuestaAyuda($nombre);

            case 'despedida':
                return $this->respuestaDespedida($nombre);

            default:
                // Intentar responder según contexto previo
                return $this->respuestaContextual($nombre);
        }
    }

    // ─── Respuestas individuales ──────────────────────────────────────────────

    private function respuestaSaludo(string $nombre): string
    {
        $hora = (int) now()->format('H');
        if ($hora < 12) {
            $saludo = '¡Buenos días';
        } elseif ($hora < 18) {
            $saludo = '¡Buenas tardes';
        } else {
            $saludo = '¡Buenas noches';
        }

        return "{$saludo}, {$nombre}! 😊 Soy Muni, tu asistente de salud en InmunoSV.\n\n" .
               "Puedo ayudarte con:\n" .
               "• 💉 Consultar tus vacunas pendientes o aplicadas\n" .
               "• 📅 Gestionar recordatorios de vacunación\n" .
               "• 🏥 Encontrar dónde aplicarte vacunas\n" .
               "• 📋 Revisar tu esquema de vacunación\n\n" .
               "¿Con qué te ayudo hoy?";
    }

    private function respuestaVacunasPendientes(string $nombre): string
    {
        if (empty($this->vacunasPendientes)) {
            $recomendadas = $this->vacunasSegunEdad();
            return "¡Hola, {$nombre}! No tienes vacunas registradas como pendientes en el sistema. 🎉\n\n" .
                   "Sin embargo, según tu edad ({$this->edad} años), te recomiendo verificar con tu médico:\n" .
                   $this->formatearLista($recomendadas) .
                   "\n¿Te gustaría que te recuerde cuándo aplicarlas?";
        }

        $lista = $this->formatearLista(
            array_column($this->vacunasPendientes, 'nombre_vacuna')
        );

        $rec = $this->conversacion->ultima_recomendacion ?? '';
        $this->actualizarContexto('ultima_recomendacion', implode(', ', array_column($this->vacunasPendientes, 'nombre_vacuna')));

        return "Según tu historial, {$nombre}, tienes las siguientes vacunas pendientes:\n\n" .
               $lista .
               "\n¿Te gustaría saber dónde puedes aplicártelas o que te programe un recordatorio?";
    }

    private function respuestaVacunasAplicadas(string $nombre): string
    {
        if (empty($this->vacunasAplicadas)) {
            return "Hola, {$nombre}. Aún no tienes vacunas registradas como aplicadas en tu historial. " .
                   "Puedes agregarlas desde la sección **Mi Control**. ¿Te puedo ayudar con algo más?";
        }

        $lista = $this->formatearLista(
            array_map(function ($v) {
                $fecha = isset($v['fecha_aplicacion'])
                    ? ' (aplicada el ' . date('d/m/Y', strtotime($v['fecha_aplicacion'])) . ')'
                    : '';
                return $v['nombre_vacuna'] . $fecha;
            }, $this->vacunasAplicadas)
        );

        return "¡Muy bien, {$nombre}! Estas son las vacunas que tienes registradas como aplicadas:\n\n" .
               $lista .
               "\n¡Sigue cuidando tu salud! ¿Necesitas revisar las que te faltan?";
    }

    private function respuestaRecordatorio(string $nombre): string
    {
        $pendientes = array_column($this->vacunasPendientes, 'nombre_vacuna');

        if (empty($pendientes)) {
            return "No tienes vacunas pendientes registradas, {$nombre}. Puedes crear recordatorios " .
                   "desde la sección **Recordatorios** para vacunas anuales como la Influenza. ¿Te ayudo con algo más?";
        }

        $lista = $this->formatearLista($pendientes);

        return "Claro, {$nombre}. Puedo ayudarte a recordar tus vacunas pendientes:\n\n" .
               $lista .
               "\nPuedes gestionar tus recordatorios desde la sección **Recordatorios** " .
               "en el menú lateral. Ahí podrás elegir la fecha y recibir una notificación. ¿Deseas hacer algo más?";
    }

    private function respuestaLugar(string $nombre, ?string $vacunaRef): string
    {
        // Resolución de contexto: si no se detectó vacuna, usar la del contexto
        $vacuna = $vacunaRef ?? $this->conversacion->ultima_vacuna;
        $mencion = $vacuna ? $this->nombreAmigableVacuna($vacuna) : 'esa vacuna';

        $depto = $this->user->departamento ?? null;
        $deptoTexto = $depto ? " en **{$depto}**" : '';

        return "Para aplicarte {$mencion}{$deptoTexto}, puedes acudir a:\n\n" .
               "• 🏥 Las **Unidades de Salud** del Ministerio de Salud (MINSAL)\n" .
               "• 🏨 **Hospitales Nacionales** más cercanos a tu domicilio\n" .
               "• 💊 Clínicas y farmacias autorizadas con servicios de vacunación\n\n" .
               "Te recomiendo llamar antes para confirmar disponibilidad. " .
               "¿Necesitas información sobre alguna vacuna en específico?";
    }

    private function respuestaInformacion(string $nombre, ?string $vacunaRef): string
    {
        $vacuna = $vacunaRef ?? $this->conversacion->ultima_vacuna;

        if (!$vacuna) {
            return "Con gusto te explico, {$nombre}. ¿Sobre qué vacuna quieres información? " .
                   "Por ejemplo: Influenza, Hepatitis B, VPH, Tétanos, Neumococo...";
        }

        $info = $this->infoVacuna($vacuna);
        return "Aquí tienes información sobre **{$this->nombreAmigableVacuna($vacuna)}**:\n\n" .
               $info . "\n\n¿Tienes alguna otra pregunta?";
    }

    private function respuestaEsquema(string $nombre): string
    {
        $vacunas = $this->vacunasSegunEdad();

        return "Tu esquema de vacunación recomendado, {$nombre} ({$this->edad} años):\n\n" .
               $this->formatearLista($vacunas) .
               "\n📋 Recuerda que este esquema es orientativo. Consulta siempre con tu médico " .
               "para un plan personalizado. ¿Te ayudo con algo más?";
    }

    private function respuestaRecomendaciones(string $nombre): string
    {
        $recomendadas = $this->vacunasSegunEdad();

        $this->actualizarContexto('ultima_recomendacion', implode(', ', $recomendadas));

        return "Basándome en tu perfil, {$nombre} ({$this->edad} años, género: {$this->user->genero}), " .
               "estas son mis recomendaciones preventivas:\n\n" .
               "💉 **Vacunas recomendadas:**\n" .
               $this->formatearLista($recomendadas) .
               "\n🛡️ **Consejos adicionales:**\n" .
               "• Mantén tu carnet de vacunación actualizado\n" .
               "• Lávate las manos frecuentemente\n" .
               "• Consulta a tu médico ante cualquier síntoma\n\n" .
               "¿Deseas que te ayude a programar algún recordatorio?";
    }

    private function respuestaAyuda(string $nombre): string
    {
        return "Hola, {$nombre}. Aquí tienes todo lo que puedo hacer por ti:\n\n" .
               "💉 **Vacunas**\n" .
               "  → \"¿Qué vacunas me faltan?\"\n" .
               "  → \"¿Qué vacunas ya me he puesto?\"\n\n" .
               "📅 **Recordatorios**\n" .
               "  → \"Quiero un recordatorio para la influenza\"\n" .
               "  → \"¿Tengo citas pendientes?\"\n\n" .
               "🏥 **Lugares**\n" .
               "  → \"¿Dónde me puedo aplicar la Hepatitis B?\"\n\n" .
               "📋 **Esquema e información**\n" .
               "  → \"¿Cuál es mi esquema de vacunación?\"\n" .
               "  → \"¿Para qué sirve la vacuna del VPH?\"\n\n" .
               "Escribe cualquier pregunta y haré lo posible por ayudarte. 😊";
    }

    private function respuestaDespedida(string $nombre): string
    {
        return "¡Hasta pronto, {$nombre}! 👋 Fue un gusto ayudarte. " .
               "Recuerda mantener tu esquema de vacunación al día. ¡Cuídate mucho! 💙";
    }

    private function respuestaContextual(string $nombre): string
    {
        $ultimoTema = $this->conversacion->ultimo_tema;

        if ($ultimoTema === 'vacunas_pendientes') {
            return "Si tienes más preguntas sobre tus vacunas pendientes, con gusto te ayudo, {$nombre}. " .
                   "¿Quieres saber dónde aplicártelas o programar un recordatorio?";
        }

        if ($ultimoTema === 'recordatorios') {
            return "Si quieres gestionar tus recordatorios, ve a la sección **Recordatorios** en el menú. " .
                   "¿Hay algo más en lo que pueda ayudarte, {$nombre}?";
        }

        return "No estoy seguro de entender tu mensaje, {$nombre}. 😅 " .
               "Puedes preguntarme sobre:\n" .
               "• Vacunas pendientes o aplicadas\n" .
               "• Recordatorios de vacunación\n" .
               "• Dónde aplicarte vacunas\n" .
               "• Tu esquema de vacunación\n\n" .
               "Escribe **\"ayuda\"** para ver todas mis funciones.";
    }

    // ─── Lógica de negocio ────────────────────────────────────────────────────

    /**
     * Retorna las vacunas recomendadas según la edad del usuario.
     */
    private function vacunasSegunEdad(): array
    {
        if ($this->edad < 18) {
            return [
                'VPH (Virus del Papiloma Humano) — esquema de 2 dosis',
                'Tétanos (Td) — refuerzo cada 10 años',
                'Hepatitis B — si no completaste el esquema',
                'Influenza — anualmente',
            ];
        } elseif ($this->edad <= 59) {
            return [
                'Influenza — anualmente (especialmente en temporada)',
                'Hepatitis B — si no completaste el esquema de 3 dosis',
                'Tétanos (Td) — refuerzo cada 10 años',
                'VPH — si eres menor de 26 años y no has completado el esquema',
            ];
        } else {
            return [
                'Influenza — anualmente (prioritario en adultos mayores)',
                'Neumococo — 1 o 2 dosis según indicación médica',
                'Tétanos (Td) — refuerzo cada 10 años',
                'Herpes Zóster — recomendada a partir de los 60 años',
            ];
        }
    }

    /**
     * Retorna información básica de una vacuna específica.
     */
    private function infoVacuna(string $clave): string
    {
        $info = [
            'influenza'   => "La **Influenza** es una vacuna anual que protege contra los virus de la gripe estacional. " .
                             "Se recomienda especialmente en adultos mayores, embarazadas y personas con enfermedades crónicas. " .
                             "Cada año se actualiza según las cepas circulantes.",
            'hepatitis_b' => "La **Hepatitis B** protege contra una infección viral grave del hígado. " .
                             "El esquema completo es de 3 dosis (0, 1 y 6 meses). " .
                             "Es muy importante completarlo para lograr protección duradera.",
            'tetanos'     => "El **Tétanos (Td)** protege contra el tétanos y la difteria. " .
                             "El refuerzo se aplica cada 10 años. En caso de heridas profundas, " .
                             "puede aplicarse antes de ese plazo.",
            'vph'         => "El **VPH (Virus del Papiloma Humano)** protege contra los tipos de VPH " .
                             "que causan cáncer cervicouterino y otras enfermedades. " .
                             "El esquema es de 2 dosis en menores de 15 años, o 3 dosis en mayores.",
            'neumococo'   => "La vacuna contra el **Neumococo** protege frente a infecciones causadas " .
                             "por Streptococcus pneumoniae, incluyendo neumonía y meningitis. " .
                             "Es especialmente recomendada en adultos mayores de 65 años.",
            'covid'       => "Las vacunas contra el **COVID-19** protegen contra formas graves de la enfermedad. " .
                             "Consulta con tu médico sobre la necesidad de refuerzos según tu historial.",
        ];

        return $info[$clave] ?? "No tengo información detallada sobre esa vacuna en este momento. " .
               "Te recomiendo consultar con tu médico o visitar el sitio del MINSAL.";
    }

    /**
     * Convierte la clave interna de la vacuna a un nombre amigable.
     */
    private function nombreAmigableVacuna(?string $clave): string
    {
        $nombres = [
            'influenza'   => 'la Influenza',
            'hepatitis_b' => 'la Hepatitis B',
            'tetanos'     => 'el Tétanos (Td)',
            'vph'         => 'el VPH',
            'neumococo'   => 'el Neumococo',
            'covid'       => 'el COVID-19',
        ];

        return $nombres[$clave] ?? 'esa vacuna';
    }

    // ─── Utilidades ───────────────────────────────────────────────────────────

    private function formatearLista(array $items): string
    {
        return implode("\n", array_map(fn($i) => "• {$i}", $items));
    }

    private function actualizarContexto(string $campo, string $valor): void
    {
        $this->conversacion->update([$campo => $valor]);
    }

    /**
     * Carga vacunas aplicadas, pendientes y calcula la edad del usuario.
     */
    private function cargarDatosUsuario(): void
    {
        // Edad
        if ($this->user->fecha_nacimiento) {
            $this->edad = \Carbon\Carbon::parse($this->user->fecha_nacimiento)->age;
        }

        // Historial de vacunación (ajusta las relaciones según tu esquema real)
        if (method_exists($this->user, 'vacunasAplicadas')) {
            $this->vacunasAplicadas = $this->user->vacunasAplicadas()
                ->select('nombre_vacuna', 'fecha_aplicacion')
                ->get()
                ->toArray();
        }

        if (method_exists($this->user, 'vacunasPendientes')) {
            $this->vacunasPendientes = $this->user->vacunasPendientes()
                ->select('nombre_vacuna')
                ->get()
                ->toArray();
        }
    }
    
}