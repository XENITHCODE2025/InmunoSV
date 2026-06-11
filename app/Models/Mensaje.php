<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mensaje extends Model
{
    use HasFactory;

    protected $table = 'mensajes';

    protected $fillable = [
        'conversacion_id',
        'emisor',      // 'usuario' | 'bot'
        'mensaje',
        'tipo',        // 'texto' | 'audio'
        'audio_path',  // ruta en storage/app/public (solo cuando tipo = 'audio')
    ];

    // ─── Accessor ─────────────────────────────────────────────────────────────

    /**
     * URL pública del audio para reproducirlo en el navegador.
     * Usa asset() que respeta APP_URL — funciona con artisan serve en cualquier puerto.
     * Retorna null si el mensaje no es de tipo audio.
     */
    public function getAudioUrlAttribute(): ?string
    {
        if ($this->tipo !== 'audio' || !$this->audio_path) {
            return null;
        }

        // asset('storage/audios/1/archivo.webm')
        // → http://127.0.0.1:8000/storage/audios/1/archivo.webm
        return asset('storage/' . ltrim($this->audio_path, '/'));
    }

    // ─── Relaciones ───────────────────────────────────────────────────────────

    /**
     * Conversación a la que pertenece este mensaje.
     */
    public function conversacion()
    {
        return $this->belongsTo(Conversacion::class, 'conversacion_id');
    }
}