<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProveedorController;

Route::get('/login', function () {
    return view('auth.login');
});

Route::get('/recuperar-contrasena', function () {
    return view('auth.recuperar');
});

Route::get('/dashboard', function () {
    return view('auth.dashboard');
});

Route::get('/proveedores', [
    App\Http\Controllers\ProveedorController::class, 'index'
]);

Route::get('/productos', [
    App\Http\Controllers\ProductoController::class, 'index'
]);

Route::get('/categorias', [
    App\Http\Controllers\CategoriaController::class, 'index'
]);

Route::post('/logout', function () {
    return redirect('/login');
})->name('logout');

