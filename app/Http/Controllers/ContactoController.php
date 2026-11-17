<?php

namespace App\Http\Controllers;

use App\Models\Contacto;
use Illuminate\Http\Request;

class ContactoController extends Controller
{
    // Listar todos los mensajes (para el panel de administración si lo requieres)
    public function index()
    {
        return response()->json(Contacto::orderBy('created_at', 'desc')->get());
    }

    // Guardar un nuevo mensaje enviado desde el formulario de contacto
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'correo' => 'required|email|max:255',
            'asunto' => 'required|string|max:255',
            'mensaje' => 'required|string',
        ]);

        $contacto = Contacto::create($validated);

        return response()->json([
            'mensaje' => '¡Mensaje enviado con éxito! Nos pondremos en contacto pronto.',
            'data' => $contacto
        ], 201);
    }

    // Eliminar un mensaje
    public function destroy($id)
    {
        $contacto = Contacto::find($id);
        if (!$contacto) {
            return response()->json(['error' => 'Mensaje no encontrado'], 404);
        }
        $contacto->delete();
        return response()->json(['mensaje' => 'Mensaje eliminado correctamente']);
    }
}