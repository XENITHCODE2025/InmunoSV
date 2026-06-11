<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VacunaRegistrada extends Model
{
    protected $table = 'vacunas_aplicadas';

    protected $fillable = [
        'user_id',
        'vacuna_id',
        'tipo',
        'fecha_aplicacion',
        'dosis',
        'lugar'
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
