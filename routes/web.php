<?php

use App\Http\Controllers\EspacioController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactoController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;

// --- VISTAS DEL SISTEMA ---

// Página principal (landing page) en la raíz
Route::get('/', function () {
    return view('index');
})->name('root');

// Ruta explícita para /index
Route::get('/index', function () {
    return view('index');
})->name('index');

// Panel de inicio (cuando el usuario ya inició sesión)
Route::get('/inicio', function () {
    return view('inicio');
})->name('inicio');

Route::get('/busqueda', function () {
    return view('busqueda');
})->name('busqueda');

Route::get('/contacto', function () {
    return view('contacto');
})->name('contacto');

Route::get('/detalle', function () {
    return view('detalle');
})->name('detalle');

Route::get('/espacios', [EspacioController::class, 'index'])->name('espacios');

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/nosotros', function () {
    return view('nosotros');
})->name('nosotros');

Route::get('/perfil', function () {
    return view('perfil');
})->name('perfil');

Route::get('/registro', function () {
    return view('registro');
})->name('registro');

// Vista HTML de Mis Reservas
Route::get('/reservas', function () {
    return view('reservas');
})->name('reservas');

Route::get('/servicios', function () {
    return view('servicios');
})->name('servicios');

// VISTA WEB DE GESTIÓN DE USUARIOS (Mantiene el diseño de la interfaz)
Route::get('/usuarios', function () {
    return view('usuarios'); // Carga tu archivo usuarios.blade.php con el diseño correcto
})->name('usuarios');

// VISTA WEB DE GESTIÓN DE MENSAJES DE CONTACTO
Route::get('/mensajes', function () {
    return view('contactos-admin'); // Carga la vista para administrar los mensajes de contacto
})->name('mensajes');


// --- RUTAS DE CONTROLADORES Y CRUD ---

// Autenticación y perfil
Route::get('/api/usuario-actual', function () {
    return response()->json([
        'authenticated' => Auth::check(),
        'user' => Auth::user()
    ]);
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/registro', [AuthController::class, 'register']);
Route::post('/register', [AuthController::class, 'register']); // Alias para coincidir con registro.js
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::put('/perfil/actualizar', [AuthController::class, 'updateProfile']);

// CRUD de Espacios
Route::get('/api/espacios', [EspacioController::class, 'index']); // Por si lo consultas vía fetch
Route::post('/espacios', [EspacioController::class, 'store']);
Route::put('/espacios/{id}', [EspacioController::class, 'update']);
Route::delete('/espacios/{id}', [EspacioController::class, 'destroy']);

// CRUD y gestión de Reservas (API para JS)
Route::get('/api/reservas', [ReservaController::class, 'index']); // Endpoint para que reservas.js lea de PostgreSQL
Route::post('/reservas', [ReservaController::class, 'store']);
Route::put('/reservas/{id}', [ReservaController::class, 'update']); // Para edición desde detalle
Route::delete('/reservas/{id}', [ReservaController::class, 'destroy']); // Para eliminar si se requiere
Route::match(['put', 'patch'], '/reservas/{id}/cancelar', [ReservaController::class, 'cancel']); // Acepta PUT y PATCH

// --- ALIAS DE API PARA ASEGURAR COMPATIBILIDAD CON FETCH ---
Route::post('/api/reservas', [ReservaController::class, 'store']);
Route::put('/api/reservas/{id}', [ReservaController::class, 'update']);
Route::delete('/api/reservas/{id}', [ReservaController::class, 'destroy']);

// ENDPOINTS DE API PARA GESTIÓN DE USUARIOS (Consumidos por el JS de la tabla)
Route::get('/api/usuarios', [UsuarioController::class, 'index']);
Route::delete('/api/usuarios/{id}', [UsuarioController::class, 'destroy']);

// --- ENDPOINTS DE API PARA CONTACTO ---
Route::post('/api/contacto', [ContactoController::class, 'store']);
Route::get('/api/contactos', [ContactoController::class, 'index']);
Route::delete('/api/contactos/{id}', [ContactoController::class, 'destroy']);