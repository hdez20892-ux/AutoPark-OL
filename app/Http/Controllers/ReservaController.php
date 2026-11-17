<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reserva;
use App\Models\Espacio;
use Illuminate\Support\Facades\Auth;

class ReservaController extends Controller
{
    /**
     * Listar las reservas (para la vista de reservas o historial).
     */
    public function index()
    {
        // Consultar las reservas desde la base de datos incluyendo los datos del espacio
        $reservas = Reserva::with('espacio')->orderBy('id', 'desc')->get();

        return response()->json([
            'success' => true,
            'reservas' => $reservas
        ]);
    }

    /**
     * Crear una nueva reserva.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'espacio_id' => 'required',
            'fecha' => 'required|date',
            'hora_entrada' => 'required',
            'hora_salida' => 'required',
            'total' => 'required|numeric|min:0',
        ]);

        try {
            $reserva = Reserva::create([
                'user_id' => Auth::id() ?? 1,
                'espacio_id' => $validated['espacio_id'],
                'fecha' => $validated['fecha'],
                'hora_entrada' => $validated['hora_entrada'],
                'hora_salida' => $validated['hora_salida'],
                'total' => $validated['total'],
                'estado' => 'activa',
            ]);

            if (method_exists($reserva, 'espacio') && $reserva->espacio) {
                $reserva->load('espacio');
            }

            return response()->json([
                'success' => true,
                'message' => '¡Reserva registrada con éxito!',
                'reserva' => $reserva
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar la reserva: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar una reserva existente.
     */
    public function update(Request $request, $id)
    {
        $reserva = Reserva::findOrFail($id);

        $validated = $request->validate([
            'espacio_id' => 'sometimes|required', // Permite actualizar o conservar el espacio
            'fecha' => 'required|date',
            'hora_entrada' => 'required',
            'hora_salida' => 'required',
            'total' => 'required|numeric|min:0',
        ]);

        try {
            $reserva->update([
                'espacio_id' => $validated['espacio_id'] ?? $reserva->espacio_id,
                'fecha' => $validated['fecha'],
                'hora_entrada' => $validated['hora_entrada'],
                'hora_salida' => $validated['hora_salida'],
                'total' => $validated['total'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Reserva actualizada correctamente.',
                'reserva' => $reserva
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la reserva: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancelar una reserva existente.
     */
    public function cancel($id)
    {
        $reserva = Reserva::findOrFail($id);
        
        $reserva->update([
            'estado' => 'cancelada'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Reserva cancelada correctamente.'
        ]);
    }

    /**
     * Eliminar una reserva.
     */
    public function destroy($id)
    {
        $reserva = Reserva::findOrFail($id);
        $reserva->delete();

        return response()->json([
            'success' => true,
            'message' => 'Reserva eliminada con éxito.'
        ]);
    }
}