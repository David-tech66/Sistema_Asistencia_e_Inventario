@extends('layouts.app')

@section('title', 'Iniciar Sesión - Sistema de Inventario')

@section('content')
<div class="flex min-h-screen">

    {{-- Lado izquierdo: Formulario --}}
    <div class="w-full lg:w-1/2 flex flex-col justify-center px-8 py-10 lg:px-24">

        {{-- Logo y botón de tema --}}
        <div class="flex justify-between items-center mb-10">
            <div class="flex items-center font-bold text-lg">
                <span class="w-8 h-8 mr-2 rounded-lg bg-[#8e3a80] text-white flex items-center justify-center">
                    <i class="fa-solid fa-user"></i>
                </span> Logo
            </div>
            <button type="button" data-theme-toggle class="text-xl" aria-label="Cambiar tema">
                <i data-theme-icon class="fa-solid fa-moon"></i>
            </button>
        </div>

        <h1 class="text-3xl font-bold mb-2">Bienvenido de nuevo</h1>
        <p class="text-gray-500 dark:text-gray-400 mb-8">Ingresa tus credenciales para dar inicio</p>

        <form action="#" method="POST">
            <div class="mb-4">
                <label class="block text-gray-500 dark:text-gray-400 text-sm mb-2">Ingresa tu nombre de usuario</label>
                <input type="text" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-3 bg-transparent dark:text-white focus:outline-none focus:border-[#8e3a80]" placeholder="Nombre de usuario">
            </div>

            <div class="mb-6">
                <label class="block text-gray-500 dark:text-gray-400 text-sm mb-2">Contraseña</label>
                <div class="relative">
                    <input type="password" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-3 bg-transparent dark:text-white focus:outline-none focus:border-[#8e3a80]" placeholder="***************">
                    <span data-toggle-password class="absolute right-4 top-3 cursor-pointer opacity-60"><i class="fa-solid fa-eye-slash"></i></span>
                </div>
            </div>

            <p class="-mt-2 mb-6 text-right text-sm text-gray-500 dark:text-gray-400">
                ¿Haz olvidado la <a href="/recuperar-contrasena" data-transition class="font-bold text-[#a04d97]">contraseña</a>?
            </p>

            <button type="button" class="w-full bg-[#8e3a80] text-white rounded-full py-3 font-semibold hover:bg-[#a04d97] transition">
                Iniciar Sesión
            </button>
        </form>
    </div>

    {{-- Lado derecho: panel morado (oculto en móvil) --}}
    <div class="hidden lg:flex w-1/2 bg-gradient-to-br from-[#9c27b0] via-[#7b1fa2] to-[#4a148c] relative items-center justify-center rounded-tl-[3rem] overflow-hidden">
        <div class="absolute -top-20 -right-20 w-80 h-80 bg-purple-400/30 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-fuchsia-500/20 rounded-full blur-3xl"></div>

        <div class="text-center z-10 px-12">
            <div class="bg-white/10 backdrop-blur-md rounded-lg p-10">
                <h2 class="text-white text-4xl font-bold leading-tight">Haz que tu día sea<br>aún mejor</h2>
            </div>
        </div>

        <div class="absolute bottom-12 text-center">
            <div class="text-white text-sm mb-4">Organiza, controla y mantén al día tu inventario</div>
            <div class="flex justify-center space-x-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                <span class="w-1.5 h-1.5 rounded-full bg-white/40"></span>
                <span class="w-1.5 h-1.5 rounded-full bg-white/40"></span>
            </div>
        </div>
    </div>

</div>
@endsection
