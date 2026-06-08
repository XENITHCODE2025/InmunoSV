<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EncuestaCondicion extends Model
{
    protected $table = 'encuesta_condiciones';

    protected $fillable = [
        'encuesta_id',
        'condicion_id'
    ];
}