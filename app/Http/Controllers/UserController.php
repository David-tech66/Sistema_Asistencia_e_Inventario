<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Mostrar listado de usuarios.
     */
    public function index(): View
    {
        $users = User::orderBy('nombre_usuario')->get();

        return view('users', compact('users'));
    }

    /**
     * Registrar un nuevo usuario.
     */
    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'nombre_usuario' => ['required', 'string', 'max:150', 'unique:users,nombre_usuario'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'rol' => ['required', 'in:admin,empleado'],
        ]);

        User::create([
            'nombre_usuario' => $datos['nombre_usuario'],
            'email' => $datos['email'],
            'password' => Hash::make($datos['password']),
            'rol' => $datos['rol'],
            'estado' => true,
        ]);

        return redirect('/users')->with('success', 'Usuario registrado correctamente.');
    }

    /**
     * Actualizar un usuario.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $datos = $request->validate([
            'nombre_usuario' => [
                'required',
                'string',
                'max:150',
                'unique:users,nombre_usuario,' . $user->id,
            ],
            'email' => [
                'required',
                'email',
                'max:150',
                'unique:users,email,' . $user->id,
            ],
            'password' => ['nullable', 'string', 'min:6'],
            'rol' => ['required', 'in:admin,empleado'],
        ]);

        $user->nombre_usuario = $datos['nombre_usuario'];
        $user->email = $datos['email'];
        $user->rol = $datos['rol'];

        if (!empty($datos['password'])) {
            $user->password = Hash::make($datos['password']);
        }

        $user->save();

        return redirect('/users')->with('success', 'Usuario actualizado correctamente.');
    }

    /**
     * Activar o desactivar un usuario.
     */
    public function cambiarEstado(User $user): RedirectResponse
    {
        $user->estado = !$user->estado;
        $user->save();

        return redirect('/users')->with(
            'success',
            $user->estado
                ? 'Usuario activado correctamente.'
                : 'Usuario desactivado correctamente.'
        );
    }
}