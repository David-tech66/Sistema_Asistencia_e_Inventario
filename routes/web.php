<?php

use App\Http\Controllers\ProveedorController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;

// Vistas públicas
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

// Rutas de tu equipo...
Route::get('/proveedores', [App\Http\Controllers\ProveedorController::class, 'index']);
Route::get('/productos', [App\Http\Controllers\ProductoController::class, 'index']);
Route::get('/marcas', [App\Http\Controllers\MarcaController::class, 'index']);
Route::get('/categorias', [App\Http\Controllers\CategoriaController::class, 'index']);
Route::get('/ventas', [App\Http\Controllers\VentaController::class, 'index']);
Route::get('/clientes', [App\Http\Controllers\ClienteController::class, 'index']);
