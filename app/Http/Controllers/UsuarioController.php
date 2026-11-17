<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UsuarioController extends Controller
{
    // READ: Obtener todos los usuarios de la base de datos
    public function index(): JsonResponse
    {
        $usuarios = User::all();
        return response()->json($usuarios, 200);
    }

    // DELETE: Eliminar un usuario por su ID
    public function destroy($id): JsonResponse
    {
        $usuario = User::find($id);

        if (!$usuario) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        $usuario->delete();

        return response()->json(['mensaje' => 'Usuario eliminado correctamente'], 200);
    }
}