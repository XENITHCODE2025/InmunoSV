<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VacunaRegistrada extends Model
{
    protected $table = 'vacunas_aplicadas';
    
    protected $fillable = [
        'user_id',
        'vacuna_id',
        'nombre',
        'tipo',
        'fecha_aplicacion',
        'dosis',
        'lugar',
        'estado',
    ];

    protected $casts = [
        'fecha_aplicacion' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function vacuna()
    {
        return $this->belongsTo(
            Vacuna::class,
            'vacuna_id',
            'id_vacuna'
        );
    }
}
