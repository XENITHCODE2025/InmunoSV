<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CondicionMedica extends Model
{
    use HasFactory;

    protected $table      = 'condiciones_medicas';
    protected $primaryKey = 'id_condicion';

    protected $fillable = ['nombre', 'descripcion'];

    // ─── Relaciones ───────────────────────────────────────────────────────────

    public function encuestas()
    {
        return $this->belongsToMany(
            EncuestaSalud::class,
            'encuesta_condiciones',
            'condicion_id',
            'encuesta_id'
        );
    }
}