<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Espacio;
use Illuminate\Support\Facades\Auth;

class EspacioController extends Controller
{
    /**
     * Muestra la vista de gestión de espacios del usuario.
     */
    public function index(Request $request)
    {
        $espacios = Espacio::orderBy('id', 'desc')->get();

        if ($request->wantsJson()) {
            return response()->json($espacios);
        }

        return view('espacios', compact('espacios'));
    }

    /**
     * Almacena un nuevo espacio publicado en la base de datos.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'ubicacion' => 'required|string|max:255',
            'precio' => 'required|numeric|min:0',
            'cantidad' => 'required|integer|min:0',
            'disponibilidad' => 'required|string',
            'horaInicio' => 'required',
            'horaFin' => 'required',
        ]);

        try {
            $espacio = Espacio::create([
                'user_id' => Auth::id(),
                'nombre' => $validated['nombre'],
                'ubicacion' => $validated['ubicacion'],
                'precio_hora' => $validated['precio'],
                'espacios_disponibles' => $validated['cantidad'],
                'disponibilidad' => $validated['disponibilidad'],
                'hora_apertura' => $validated['horaInicio'],
                'hora_cierre' => $validated['horaFin'],
            ]);

            return response()->json([
                'success' => true, 
                'message' => '¡Espacio publicado correctamente!',
                'id' => $espacio->id
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Error al guardar el espacio: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualiza un espacio existente (o su disponibilidad).
     */
    public function update(Request $request, $id)
    {
        $espacio = Espacio::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'ubicacion' => 'required|string|max:255',
            'precio' => 'required|numeric|min:0',
            'cantidad' => 'required|integer|min:0',
            'disponibilidad' => 'required|string',
            'horaInicio' => 'required',
            'horaFin' => 'required',
        ]);

        $espacio->update([
            'nombre' => $validated['nombre'],
            'ubicacion' => $validated['ubicacion'],
            'precio_hora' => $validated['precio'],
            'espacios_disponibles' => $validated['cantidad'],
            'disponibilidad' => $validated['disponibilidad'],
            'hora_apertura' => $validated['horaInicio'],
            'hora_cierre' => $validated['horaFin'],
        ]);

        return response()->json([
            'success' => true, 
            'message' => 'Espacio actualizado correctamente.'
        ]);
    }

    /**
     * Elimina un espacio publicado.
     */
    public function destroy($id)
    {
        $espacio = Espacio::findOrFail($id);
        $espacio->delete();

        return response()->json([
            'success' => true, 
            'message' => 'Espacio eliminado con éxito.'
        ]);
    }
}