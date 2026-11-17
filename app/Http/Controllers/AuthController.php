<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Manejar el inicio de sesión.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return response()->json([
                'success' => true,
                'message' => 'Inicio de sesión exitoso',
                'redirect' => '/inicio'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Las credenciales no coinciden con nuestros registros.'
        ], 422);
    }

    /**
     * Manejar el registro de nuevos usuarios.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        try {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            Auth::login($user);

            return response()->json([
                'success' => true,
                'message' => '¡Cuenta creada correctamente!',
                'redirect' => '/inicio'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar usuario: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cerrar sesión.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    /**
     * Actualizar datos del perfil de usuario.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        // Lógica para actualizar perfil
        return response()->json([
            'success' => true,
            'message' => 'Perfil actualizado correctamente.'
        ]);
    }
}