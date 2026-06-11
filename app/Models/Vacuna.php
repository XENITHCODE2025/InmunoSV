<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vacuna extends Model
{
    protected $table = 'vacunas';

    protected $primaryKey = 'id_vacuna';

    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion'
    ];

    public function vacunasAplicadas()
    {
        return $this->hasMany(VacunaRegistrada::class, 'vacuna_id', 'id_vacuna');
    }

    public function recomendacion()
    {
        return $this->hasOne(
            Recomendacion::class,
            'vacuna_id',
            'id_vacuna'
        );
    }
}
