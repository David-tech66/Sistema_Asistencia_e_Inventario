@extends('layouts.app')

@section('title', 'Recuperar Contraseña - Sistema de Inventario')

@section('content')
<div class="flex min-h-screen">

    {{-- Lado izquierdo: panel morado (oculto en móvil) --}}
    <div class="hidden lg:flex w-1/2 bg-gradient-to-br from-[#9c27b0] via-[#7b1fa2] to-[#4a148c] relative items-center justify-center rounded-tr-[3rem] overflow-hidden">
        <div class="absolute -top-20 -left-20 w-80 h-80 bg-purple-400/30 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-fuchsia-500/20 rounded-full blur-3xl"></div>

        <div class="text-center z-10 px-12">
            <div class="bg-white/10 backdrop-blur-md rounded-lg p-10 text-left">
                <h2 class="text-white text-4xl font-bold leading-tight">Somos una familia<br>de innovadores, se<br>parte de nuestras<br>metas</h2>
            </div>
        </div>

        <div class="absolute bottom-12 text-center">
            <div class="text-white text-sm mb-4">Controla las asistencias de manera facil y sencilla</div>
            <div class="flex justify-center space-x-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                <span class="w-1.5 h-1.5 rounded-full bg-white/40"></span>
                <span class="w-1.5 h-1.5 rounded-full bg-white/40"></span>
            </div>
        </div>
    </div>

    {{-- Lado derecho: formulario --}}
    <div class="w-full lg:w-1/2 flex flex-col justify-center px-8 py-10 lg:px-24 relative">
        <button type="button" data-theme-toggle class="absolute top-8 right-8 text-xl" aria-label="Cambiar tema">
            <i data-theme-icon class="fa-solid fa-moon"></i>
        </button>

        <div class="max-w-md w-full mx-auto text-center">
            <span class="inline-flex w-12 h-12 rounded-xl bg-[#8e3a80] text-white items-center justify-center text-xl mb-5">
                <i class="fa-solid fa-user"></i>
            </span>

            <h1 class="text-2xl font-bold mb-3">Recuperar Contraseña</h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm mb-8">
                Ingresa tu Correo Electronico para enviarte un código de verificación para tu contraseña
            </p>

            <form action="#" method="POST" class="text-left">
                <div class="mb-6">
                    <label class="block text-gray-500 dark:text-gray-400 text-sm mb-2">Correo Electronico</label>
                    <input type="email" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-3 bg-transparent dark:text-white focus:outline-none focus:border-[#8e3a80]" placeholder="Ingresa tu correo">
                </div>

                <button type="button" class="w-full bg-[#8e3a80] text-white rounded-full py-3 font-semibold hover:bg-[#a04d97] transition">
                    Siguiente <i class="fa-solid fa-arrow-right ml-2"></i>
                </button>
            </form>

            <a href="/login" data-transition class="inline-block mt-5 text-sm text-gray-500 dark:text-gray-400 hover:text-[#a04d97] transition">
                Regresar al Login <i class="fa-solid fa-right-to-bracket ml-1"></i>
            </a>
        </div>
    </div>

</div>
@endsection
