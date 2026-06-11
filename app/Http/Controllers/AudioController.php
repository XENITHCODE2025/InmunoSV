<?php

namespace App\Http\Controllers;

use App\Models\Conversacion;
use App\Models\Mensaje;
use App\Models\Transcripcion;
use App\Services\MuniEngine;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AudioController extends Controller
{
    /**
     * Recibe el archivo de audio, lo almacena y llama al servicio de transcripción.
     */
    public function uploadAudio(Request $request)
    {
        $request->validate([
            'audio'           => 'required|file|mimes:mp3,mp4,mpeg,mpga,m4a,wav,webm,ogg|max:25600',
            'conversacion_id' => 'nullable|integer|exists:conversaciones,id',
        ]);

        $user = Auth::user();

        // Guardar el archivo
        $path = $request->file('audio')->store("audios/{$user->id}", 'public');

        // Registrar transcripción pendiente
        $transcripcion = Transcripcion::create([
            'user_id'        => $user->id,
            'audio_path'     => $path,
            'texto_generado' => null,
            'servicio'       => config('muni.speech_service', 'google'),
        ]);

        // Transcribir
        $texto = $this->transcribeAudio($transcripcion);

        if (!$texto) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo transcribir el audio. Intenta nuevamente.',
            ], 422);
        }

        // Procesar la transcripción como mensaje normal
        $conversacion = $this->obtenerOCrearConversacion($user, $request->input('conversacion_id'));

        // Guardar mensaje de audio del usuario (con ruta del archivo para reproducirlo)
        Mensaje::create([
            'conversacion_id' => $conversacion->id,
            'emisor'          => 'usuario',
            'mensaje'         => $texto,
            'tipo'            => 'audio',
            'audio_path'      => $path,
        ]);

        // Generar respuesta del bot
        $engine    = new MuniEngine($user, $conversacion);
        $respuesta = $engine->getMuniResponse($texto);

        $mensajeBot = Mensaje::create([
            'conversacion_id' => $conversacion->id,
            'emisor'          => 'bot',
            'mensaje'         => $respuesta,
            'tipo'            => 'texto',
        ]);

        return response()->json([
            'success'          => true,
            'transcripcion'    => $texto,
            'respuesta'        => $respuesta,
            'conversacion_id'  => $conversacion->id,
            'timestamp'        => $mensajeBot->created_at->format('h:i a'),
            'audio_url'        => Storage::url($path),
        ]);
    }

    // ─── Transcripción ────────────────────────────────────────────────────────

    /**
     * Enruta al servicio de transcripción configurado.
     * Arquitectura preparada para Whisper, Google Speech o Azure.
     */
    private function transcribeAudio(Transcripcion $transcripcion): ?string
    {
        $servicio = $transcripcion->servicio;

        $texto = match ($servicio) {
            'google' => $this->transcribeWithGoogle($transcripcion->audio_path),
            'azure'  => $this->transcribeWithAzure($transcripcion->audio_path),
            default  => $this->transcribeWithWhisper($transcripcion->audio_path),
        };

        if ($texto) {
            $transcripcion->update(['texto_generado' => $texto]);
        }

        return $texto;
    }

    // ─── Integraciones de Speech-to-Text ──────────────────────────────────────

    /**
     * Whisper API (OpenAI).
     * Configura OPENAI_API_KEY en .env para activar.
     */
    private function transcribeWithWhisper(string $path): ?string
    {
        $apiKey = config('services.openai.key');

        if (!$apiKey) {
            // Fallback: transcripción simulada para desarrollo
            return $this->fallbackTranscripcion();
        }

        try {
            $filePath = Storage::disk('public')->path($path);

            $response = Http::withToken($apiKey)
                ->attach('file', file_get_contents($filePath), basename($filePath))
                ->post('https://api.openai.com/v1/audio/transcriptions', [
                    'model'    => 'whisper-1',
                    'language' => 'es',
                ]);

            return $response->successful()
                ? $response->json('text')
                : null;

        } catch (\Exception $e) {
            Log::error('Whisper error: ' . $e->getMessage());
            return $this->fallbackTranscripcion();
        }
    }

    /**
     * Google Speech-to-Text.
     * Configura GOOGLE_SPEECH_KEY en .env para activar.
     */
    private function transcribeWithGoogle(string $path): ?string
    {
        $apiKey = config('services.google.speech_key');

        if (!$apiKey) {
            Log::warning('Google Speech-to-Text: GOOGLE_SPEECH_KEY no configurado en .env');
            return $this->fallbackTranscripcion();
        }

        try {
            $filePath  = Storage::disk('public')->path($path);

            if (!file_exists($filePath)) {
                Log::error('Google Speech: archivo de audio no encontrado en ' . $filePath);
                return null;
            }

            $audioData = base64_encode(file_get_contents($filePath));

            // Detectar encoding según extensión del archivo
            $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
            $encoding  = match ($extension) {
                'wav'  => 'LINEAR16',
                'ogg'  => 'OGG_OPUS',
                'mp3'  => 'MP3',
                default => 'WEBM_OPUS', // webm grabado por el navegador
            };

            $response = Http::timeout(30)->post(
                "https://speech.googleapis.com/v1/speech:recognize?key={$apiKey}",
                [
                    'config' => [
                        'encoding'                        => $encoding,
                        'sampleRateHertz'                 => 48000,
                        'languageCode'                    => 'es-SV',
                        'alternativeLanguageCodes'        => ['es-GT', 'es-MX', 'es-ES'],
                        'enableAutomaticPunctuation'      => true,
                        'model'                           => 'default',
                    ],
                    'audio' => ['content' => $audioData],
                ]
            );

            if ($response->successful()) {
                $results = $response->json('results');

                if (empty($results)) {
                    Log::info('Google Speech: transcripción vacía (sin voz detectada)');
                    return null;
                }

                return $results[0]['alternatives'][0]['transcript'] ?? null;
            }

            // Loguear error detallado de la API
            Log::error('Google Speech API error: ' . $response->status() . ' — ' . $response->body());
            return null;

        } catch (\Exception $e) {
            Log::error('Google Speech excepción: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Azure Cognitive Services Speech.
     * Configura AZURE_SPEECH_KEY y AZURE_SPEECH_REGION en .env para activar.
     */
    private function transcribeWithAzure(string $path): ?string
    {
        $key    = config('services.azure.speech_key');
        $region = config('services.azure.speech_region', 'eastus');

        if (!$key) {
            return $this->fallbackTranscripcion();
        }

        try {
            $filePath = Storage::disk('public')->path($path);

            $response = Http::withHeaders([
                'Ocp-Apim-Subscription-Key' => $key,
                'Content-Type'              => 'audio/wav',
            ])->withBody(file_get_contents($filePath), 'audio/wav')
              ->post("https://{$region}.stt.speech.microsoft.com/speech/recognition/conversation/cognitiveservices/v1?language=es-SV");

            return $response->successful()
                ? $response->json('DisplayText')
                : null;

        } catch (\Exception $e) {
            Log::error('Azure Speech error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Transcripción de fallback para entornos sin API configurada.
     * En producción, siempre debe existir una API real.
     */
    private function fallbackTranscripcion(): string
    {
        return '[Transcripción no disponible — configura un servicio de Speech-to-Text en .env]';
    }

    // ─── Privados ─────────────────────────────────────────────────────────────

    private function obtenerOCrearConversacion(User $user, ?int $conversacionId): Conversacion
    {
        if ($conversacionId) {
            $conv = Conversacion::where('id', $conversacionId)
                ->where('user_id', $user->id)
                ->first();
            if ($conv) {
                return $conv;
            }
        }

        $sesionId = session('conversacion_activa_id');
        if ($sesionId) {
            $conv = Conversacion::where('id', $sesionId)->where('user_id', $user->id)->first();
            if ($conv) {
                return $conv;
            }
        }

        $conv = Conversacion::create([
            'user_id' => $user->id,
            'titulo'  => 'Conversación ' . now()->format('d/m/Y H:i'),
        ]);
        session(['conversacion_activa_id' => $conv->id]);

        return $conv;
    }
}