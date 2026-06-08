<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EncuestaVacuna extends Model
{
    protected $table = 'encuesta_vacunas';

    protected $fillable = [
        'encuesta_id',
        'vacuna_id'
    ];
}