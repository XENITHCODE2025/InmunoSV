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
}