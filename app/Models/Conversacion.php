<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversacion extends Model
{
    use HasFactory;

    protected $table = 'conversaciones';

    protected $fillable = [
        'user_id',
        'titulo',
        'ultimo_tema',
        'ultima_vacuna',
        'ultima_recomendacion',
    ];

    // ─── Relaciones ───────────────────────────────────────────────────────────

    /**
     * Usuario dueño de la conversación.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Todos los mensajes de la conversación.
     */
    public function mensajes()
    {
        return $this->hasMany(Mensaje::class, 'conversacion_id')->orderBy('created_at');
    }

    /**
     * Último mensaje enviado en la conversación.
     */
    public function ultimoMensaje()
    {
        return $this->hasOne(Mensaje::class, 'conversacion_id')->latestOfMany();
    }
}