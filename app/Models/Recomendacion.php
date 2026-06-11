<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recomendacion extends Model
{
    protected $table = 'recomendaciones';

    protected $fillable = [
        'vacuna_id',
        'titulo',
        'descripcion'
    ];

    public function vacuna()
    {
        return $this->belongsTo(
            Vacuna::class,
            'vacuna_id',
            'id_vacuna'
        );
    }
}