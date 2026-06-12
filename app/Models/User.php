<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\VacunaRegistrada;
use App\Models\EncuestaSalud;
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'apellido',
        'fecha_nacimiento',
        'genero',
        'telefono',
        'departamento',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'fecha_nacimiento' => 'date',
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function encuestaSalud()
    {
        return $this->hasOne(
            EncuestaSalud::class,
            'user_id',
            'id'
        );
    }

    public function encuestas()
    {
        return $this->hasMany(
            EncuestaSalud::class,
            'user_id'
        );
    }

    
public function vacunasAplicadas()
{
    return $this->hasMany(VacunaRegistrada::class, 'user_id');
}
 
/**
 * Encuesta de salud del usuario (relación 1:1).
 */

}

