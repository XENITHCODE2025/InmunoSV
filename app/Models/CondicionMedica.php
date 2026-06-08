<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CondicionMedica extends Model
{
    protected $table = 'condiciones_medicas';

    protected $fillable = [
        'nombre'
    ];
}