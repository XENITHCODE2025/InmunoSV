<?php

namespace App\Http\Controllers;

use App\Models\Conversacion;
use App\Models\Mensaje;
use App\Services\MuniEngine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    // ─── Vistas ───────────────────────────────────────────────────────────────

    /**
     * Muestra la interfaz del chatbot.
     * Crea una conversación activa si no existe en sesión.
     */
    public function index()
    {
        $user = Auth::user();

        // Recuperar o crear conversación activa
        $conversacionId = session('conversacion_activa_id');
        $conversacion   = null;

        if ($conversacionId) {
            $conversacion = Conversacion::where('id', $conversacionId)
                ->where('user_id', $user->id)
                ->first();
        }

        if (!$conversacion) {
            $conversacion = Conversacion::create([
                'user_id' => $user->id,
                'titulo'  => 'Conversación ' . now()->format('d/m/Y H:i'),
            ]);
            session(['conversacion_activa_id' => $conversacion->id]);
        }

        // Cargar historial de mensajes existente
        $mensajes = $conversacion->mensajes()->get();

        return view('muni', compact('conversacion', 'mensajes'));
    }

    // ─── API: enviar mensaje ───────────────────────────────────────────────────

    /**
     * Recibe un mensaje del usuario, lo guarda, genera la respuesta del bot y la devuelve.
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'mensaje'         => 'required|string|max:1000',
            'conversacion_id' => 'nullable|integer|exists:conversaciones,id',
            'tipo'            => 'nullable|in:texto,audio',
        ]);

        $user = Auth::user();
        $tipo = $request->input('tipo', 'texto');

        // Obtener o crear conversación
        $conversacion = $this->obtenerOCrearConversacion($user, $request->input('conversacion_id'));

        // Guardar mensaje del usuario
        Mensaje::create([
            'conversacion_id' => $conversacion->id,
            'emisor'          => 'usuario',
            'mensaje'         => $request->input('mensaje'),
            'tipo'            => $tipo,
        ]);

        // Generar respuesta con el motor
        $engine    = new MuniEngine($user, $conversacion);
        $respuesta = $engine->getMuniResponse($request->input('mensaje'));

        // Guardar respuesta del bot
        $mensajeBot = Mensaje::create([
            'conversacion_id' => $conversacion->id,
            'emisor'          => 'bot',
            'mensaje'         => $respuesta,
            'tipo'            => 'texto',
        ]);

        return response()->json([
            'success'         => true,
            'respuesta'       => $respuesta,
            'conversacion_id' => $conversacion->id,
            'timestamp'       => $mensajeBot->created_at->format('h:i a'),
        ]);
    }

    // ─── API: historial ────────────────────────────────────────────────────────

    /**
     * Retorna todas las conversaciones del usuario autenticado.
     */
    public function history()
    {
        $conversaciones = Conversacion::where('user_id', Auth::id())
            ->with('ultimoMensaje')
            ->orderByDesc('updated_at')
            ->get()
            ->map(function ($conv) {
                return [
                    'id'              => $conv->id,
                    'titulo'          => $conv->titulo,
                    'ultimo_mensaje'  => $conv->ultimoMensaje?->mensaje ?? 'Sin mensajes',
                    'fecha'           => $conv->updated_at->diffForHumans(),
                ];
            });

        return response()->json(['conversaciones' => $conversaciones]);
    }

    // ─── API: conversación específica ─────────────────────────────────────────

    /**
     * Carga una conversación por ID y la activa en sesión.
     */
    public function getConversacion(Request $request)
    {
        $request->validate(['conversacion_id' => 'required|integer|exists:conversaciones,id']);

        $conversacion = Conversacion::where('id', $request->input('conversacion_id'))
            ->where('user_id', Auth::id())
            ->with('mensajes')
            ->firstOrFail();

        // Activar en sesión
        session(['conversacion_activa_id' => $conversacion->id]);

        $mensajes = $conversacion->mensajes->map(function ($m) {
            return [
                'emisor'    => $m->emisor,
                'mensaje'   => $m->mensaje,
                'tipo'      => $m->tipo,
                'timestamp' => $m->created_at->format('h:i a'),
            ];
        });

        return response()->json([
            'conversacion_id' => $conversacion->id,
            'titulo'          => $conversacion->titulo,
            'mensajes'        => $mensajes,
        ]);
    }

    // ─── API: nueva conversación ───────────────────────────────────────────────

    /**
     * Crea una nueva conversación vacía y la activa.
     */
    public function nuevaConversacion()
    {
        $conversacion = Conversacion::create([
            'user_id' => Auth::id(),
            'titulo'  => 'Conversación ' . now()->format('d/m/Y H:i'),
        ]);

        session(['conversacion_activa_id' => $conversacion->id]);

        return response()->json([
            'success'         => true,
            'conversacion_id' => $conversacion->id,
        ]);
    }

    // ─── Privados ──────────────────────────────────────────────────────────────

    private function obtenerOCrearConversacion($user, ?int $conversacionId): Conversacion
    {
        if ($conversacionId) {
            $conv = Conversacion::where('id', $conversacionId)
                ->where('user_id', $user->id)
                ->first();
            if ($conv) {
                return $conv;
            }
        }

        // Usar la de sesión
        $sesionId = session('conversacion_activa_id');
        if ($sesionId) {
            $conv = Conversacion::where('id', $sesionId)->where('user_id', $user->id)->first();
            if ($conv) {
                return $conv;
            }
        }

        // Crear nueva
        $conv = Conversacion::create([
            'user_id' => $user->id,
            'titulo'  => 'Conversación ' . now()->format('d/m/Y H:i'),
        ]);
        session(['conversacion_activa_id' => $conv->id]);

        return $conv;
    }
}