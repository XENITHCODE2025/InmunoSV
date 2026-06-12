<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EncuestaSalud extends Model
{
    protected $table = 'encuestas_salud';

    protected $primaryKey = 'id_encuesta';

    protected $fillable = [
        'user_id',
        'municipio',
        'otra_condicion'
    ];
    // ─── Relaciones ───────────────────────────────────────────────────────────
 
    public function user()
    {
        return $this->belongsTo(User::class);
    }
 
    /**
     * Vacunas que el usuario reportó tener al registrarse.
     */
    public function vacunas()
    {
        return $this->belongsToMany(
            Vacuna::class,
            'encuesta_vacunas',
            'encuesta_id',
            'vacuna_id'
        );
    }
 
    /**
     * Condiciones médicas del usuario.
     */
    public function condiciones()
    {
        return $this->belongsToMany(
            CondicionMedica::class,
            'encuesta_condiciones',
            'encuesta_id',
            'condicion_id'
        );
    }
}