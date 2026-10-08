<?php

use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
    return view('auth.login');
});

Route::get('/recuperar-contrasena', function () {
    return view('auth.recuperar');
});
