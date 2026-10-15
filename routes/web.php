<?php

use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
    return view('auth.login');
});

Route::get('/recuperar-contrasena', function () {
    return view('auth.recuperar');
});

Route::get('/dashboard', function () {
    return view('auth.dashboard');
});

Route::get('/proveedores', function () {
    return view('views.proveedores');
});

Route::post('/logout', function () {
    return redirect('/login');
})->name('logout');

