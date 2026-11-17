<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Espacio extends Model
{
    use HasFactory;

    protected $table = 'espacios';

    protected $fillable = [
        'user_id',
        'nombre',
        'ubicacion',
        'precio_hora',
        'espacios_disponibles',
        'disponibilidad',
        'hora_apertura',
        'hora_cierre',
    ];

    /**
     * Relación: Un espacio pertenece a un usuario (propietario)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación: Un espacio puede tener muchas reservas
     */
    public function reservas()
    {
        return $this->hasMany(Reserva::class);
    }
}