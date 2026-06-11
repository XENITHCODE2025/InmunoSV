<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transcripcion extends Model
{
    use HasFactory;

    protected $table = 'transcripciones';

    protected $fillable = [
        'user_id',
        'audio_path',
        'texto_generado',
        'servicio',
    ];

    // ─── Relaciones ───────────────────────────────────────────────────────────

    /**
     * Usuario que generó la transcripción.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}