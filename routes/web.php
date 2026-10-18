<?php

use App\Http\Controllers\ProveedorController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;

// Vistas públicas
Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials, $request->boolean('remember'))) {
        $request->session()->regenerate();

        return redirect()->intended('/dashboard');
    }

    return back()->withErrors([
        'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
    ])->onlyInput('email');
});

Route::get('/recuperar-contrasena', function () {
    return view('auth.recuperar');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');

// Cierre de sesión real
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/login');
})->name('logout');

// Rutas de Recursos Humanos - Empleados
Route::get('/users', [App\Http\Controllers\UserController::class, 'index']);
Route::post('/users', [App\Http\Controllers\UserController::class, 'store']);
Route::put('/users/{user}', [App\Http\Controllers\UserController::class, 'update']);
Route::patch('/users/{user}/estado', [App\Http\Controllers\UserController::class, 'cambiarEstado']);

// Rutas de Recursos Humanos - Asistencias
Route::get('/asistencias', [App\Http\Controllers\AsistenciaController::class, 'index']);

// Rutas de productos
Route::get('/productos', [App\Http\Controllers\ProductoController::class, 'index']);
Route::post('/productos', [App\Http\Controllers\ProductoController::class, 'store']);
Route::get('/productos/{producto}/edit', [App\Http\Controllers\ProductoController::class, 'edit']);
Route::put('/productos/{producto}', [App\Http\Controllers\ProductoController::class, 'update']);
Route::delete('/productos/{producto}', [App\Http\Controllers\ProductoController::class, 'destroy']);

// Escaneo de stock: código existente = +1 stock, código desconocido = registrar
Route::post('/productos/escanear', [App\Http\Controllers\ProductoController::class, 'escanear']);

Route::get('/marcas', [App\Http\Controllers\MarcaController::class, 'index']);
Route::post('/marcas', [App\Http\Controllers\MarcaController::class, 'store']);
Route::get('/marcas/{marca}/edit', [App\Http\Controllers\MarcaController::class, 'edit']);
Route::put('/marcas/{marca}', [App\Http\Controllers\MarcaController::class, 'update']);
Route::delete('/marcas/{marca}', [App\Http\Controllers\MarcaController::class, 'destroy']);

Route::get('/categorias', [App\Http\Controllers\CategoriaController::class, 'index']);
Route::post('/categorias', [App\Http\Controllers\CategoriaController::class, 'store']);
Route::get('/categorias/{categoria}/edit', [App\Http\Controllers\CategoriaController::class, 'edit']);
Route::put('/categorias/{categoria}', [App\Http\Controllers\CategoriaController::class, 'update']);
Route::delete('/categorias/{categoria}', [App\Http\Controllers\CategoriaController::class, 'destroy']);

Route::get('/proveedores', [App\Http\Controllers\ProveedorController::class, 'index']);
Route::post('/proveedores', [App\Http\Controllers\ProveedorController::class, 'store']);
Route::get('/proveedores/{proveedor}/edit', [App\Http\Controllers\ProveedorController::class, 'edit']);
Route::put('/proveedores/{proveedor}', [App\Http\Controllers\ProveedorController::class, 'update']);
Route::delete('/proveedores/{proveedor}', [App\Http\Controllers\ProveedorController::class, 'destroy']);

Route::get('/ventas', [App\Http\Controllers\VentaController::class, 'index']);
Route::post('/ventas/escanear', [App\Http\Controllers\VentaController::class, 'escanear']);
Route::post('/ventas', [App\Http\Controllers\VentaController::class, 'store']);
Route::get('/clientes', [App\Http\Controllers\ClienteController::class, 'index']);
Route::post('/clientes', [App\Http\Controllers\ClienteController::class, 'store']);
Route::get('/clientes/{cliente}/edit', [App\Http\Controllers\ClienteController::class, 'edit']);
Route::put('/clientes/{cliente}', [App\Http\Controllers\ClienteController::class, 'update']);
Route::delete('/clientes/{cliente}', [App\Http\Controllers\ClienteController::class, 'destroy']);
Route::get('/reportes/productos', [App\Http\Controllers\ReporteController::class, 'productos']);
Route::get('/reportes/asistencias', [App\Http\Controllers\ReporteController::class, 'asistencias']);
Route::get('/reportes/ventas', [App\Http\Controllers\ReporteController::class, 'ventas']);
