<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    use HasFactory;

    protected $table = 'reservas';

    protected $fillable = [
        'user_id',
        'espacio_id',
        'fecha',
        'hora_entrada',
        'hora_salida',
        'total',
        'estado',
    ];

    /**
     * Opcional: Asegura que los campos de fecha u otros tipos se casteen correctamente.
     */
    protected $casts = [
        'fecha' => 'date:Y-m-d',
        'total' => 'decimal:2',
    ];

    /**
     * Relación con el modelo Espacio (cada reserva pertenece a un espacio).
     */
    public function espacio()
    {
        return $this->belongsTo(Espacio::class, 'espacio_id', 'id');
    }
}