@extends('layouts.admin')

@section('title', 'Usuarios')

@section('styles')
    @vite(['resources/css/users.css'])
@endsection

@section('content')
    <!-- Título de Sección -->
    <div class="section-header">
        <div>
            <h2 class="section-title">Gestión de usuarios</h2>
            <p class="section-subtitle">Administra las cuentas de acceso al panel.</p>
        </div>
        <button type="button" class="btn btn-primary" onclick="abrirModal('modal-usuario-crear')">
            <i class="fa-solid fa-plus"></i> Nuevo usuario
        </button>
    </div>

    <!-- Alertas -->
    @if (session('success'))
        <div class="alert alert-success">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">
            <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger alert-list">
            <p><i class="fa-solid fa-triangle-exclamation"></i> Revisa los siguientes errores:</p>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <input type="hidden" id="errores-validacion" value="{{ $errors->any() ? '1' : '0' }}">

    <!-- Tarjetas resumen -->
    <div class="stats-grid">
        <div class="stat-card">
            <div>
                <p class="stat-label">Total de cuentas</p>
                <p class="stat-value">{{ $users->count() }}</p>
            </div>
            <i class="fa-solid fa-users stat-icon stat-icon--blue"></i>
        </div>
        <div class="stat-card">
            <div>
                <p class="stat-label">Cuentas activas</p>
                <p class="stat-value">{{ $users->where('estado', true)->count() }}</p>
            </div>
            <i class="fa-solid fa-user-check stat-icon stat-icon--green"></i>
        </div>
        <div class="stat-card">
            <div>
                <p class="stat-label">Cuentas inactivas</p>
                <p class="stat-value">{{ $users->where('estado', false)->count() }}</p>
            </div>
            <i class="fa-solid fa-user-slash stat-icon stat-icon--red"></i>
        </div>
    </div>

    <!-- TABLA EMPLEADOS -->
    <div class="table-card">
        <div class="table-card-header">
            <div class="table-total">
                <i class="fa-solid fa-id-badge table-total-icon"></i>
                <span>Total: <strong>{{ $users->count() }}</strong> usuario(s)</span>
            </div>
            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass search-icon"></i>
                <input type="text" id="buscar-usuario" oninput="buscarUsuario()" placeholder="Buscar usuario..." class="search-input" autocomplete="off">
            </div>
        </div>

        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Correo</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th class="cell-center">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tabla-usuarios">
                    @forelse ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>
                                <div class="cell-entity">
                                    <div class="avatar-circle">
                                        {{ strtoupper(substr($user->nombre_usuario ?? 'U', 0, 1)) }}
                                    </div>
                                    <span class="entity-name">{{ $user->nombre_usuario }}</span>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if ($user->rol === 'admin')
                                    <span class="badge-rol badge-rol--admin">Administrador</span>
                                @else
                                    <span class="badge-rol badge-rol--empleado">{{ ucfirst($user->rol ?? 'empleado') }}</span>
                                @endif
                            </td>
                            <td>
                                @if ($user->estado)
                                    <span class="badge-estado badge-estado--activo">Activo</span>
                                @else
                                    <span class="badge-estado badge-estado--inactivo">Inactivo</span>
                                @endif
                            </td>
                            <td class="cell-center">
                                <div class="actions-group" style="justify-content: center;">
                                    <button type="button" class="btn-edit" onclick="abrirModal('modal-usuario-editar-{{ $user->id }}')">
                                        <i class="fa-solid fa-pen"></i> Editar
                                    </button>
                                    <form action="/users/{{ $user->id }}/estado" method="POST"
                                          onsubmit="return confirm('¿Deseas cambiar el estado de esta cuenta?')">
                                        @csrf
                                        @method('PATCH')
                                        @if ($user->estado)
                                            <button type="submit" class="btn-state btn-state--off">
                                                <i class="fa-solid fa-user-slash"></i> Desactivar
                                            </button>
                                        @else
                                            <button type="submit" class="btn-state btn-state--on">
                                                <i class="fa-solid fa-user-check"></i> Activar
                                            </button>
                                        @endif
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <i class="fa-solid fa-users"></i>
                                    </div>
                                    <p class="empty-title">No hay usuarios registrados</p>
                                    <p class="empty-text">Utiliza el botón «Nuevo usuario» para registrar el primero.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="table-footer">
            Registros encontrados: {{ $users->count() }}
        </div>
    </div>

    <!-- MODAL: Registrar usuario -->
    <div id="modal-usuario-crear" class="modal-overlay hidden">
        <div class="modal-backdrop" onclick="cerrarModal('modal-usuario-crear')"></div>
        <div class="modal-dialog modal-dialog--lg">
            <div class="modal-header">
                <div class="modal-heading">
                    <div class="modal-icon modal-icon--blue">
                        <i class="fa-solid fa-user-plus"></i>
                    </div>
                    <div>
                        <h3 class="modal-title">Registrar usuario</h3>
                        <p class="modal-subtitle">Completa los datos de acceso.</p>
                    </div>
                </div>
                <button type="button" class="modal-close" onclick="cerrarModal('modal-usuario-crear')">&times;</button>
            </div>
            <form method="POST" action="/users">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Nombre de usuario *</label>
                        <input type="text" name="nombre_usuario" required maxlength="150"
                               value="{{ old('nombre_usuario') }}" class="form-input"
                               placeholder="Ej: jperez">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Correo electrónico *</label>
                        <input type="email" name="email" required maxlength="150"
                               value="{{ old('email') }}" class="form-input"
                               placeholder="correo@grupomdl.com">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Contraseña inicial *</label>
                        <input type="password" name="password" required minlength="6" class="form-input"
                               placeholder="Mínimo 6 caracteres">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Rol de acceso *</label>
                        <select name="rol" required class="form-input">
                            <option value="empleado" {{ old('rol') === 'empleado' ? 'selected' : '' }}>Empleado</option>
                            <option value="admin" {{ old('rol') === 'admin' ? 'selected' : '' }}>Administrador</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-muted" onclick="cerrarModal('modal-usuario-crear')">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-floppy-disk"></i> Guardar usuario
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODALES: Editar usuario -->
    @foreach ($users as $user)
        <div id="modal-usuario-editar-{{ $user->id }}" class="modal-overlay hidden">
            <div class="modal-backdrop" onclick="cerrarModal('modal-usuario-editar-{{ $user->id }}')"></div>
            <div class="modal-dialog modal-dialog--lg">
                <div class="modal-header">
                    <div class="modal-heading">
                        <div class="modal-icon modal-icon--amber">
                            <i class="fa-solid fa-user-pen"></i>
                        </div>
                        <div>
                            <h3 class="modal-title">Editar usuario</h3>
                            <p class="modal-subtitle">Actualiza los datos de acceso.</p>
                        </div>
                    </div>
                    <button type="button" class="modal-close" onclick="cerrarModal('modal-usuario-editar-{{ $user->id }}')">&times;</button>
                </div>
                <form method="POST" action="/users/{{ $user->id }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label">Nombre de usuario *</label>
                            <input type="text" name="nombre_usuario" required maxlength="150"
                                   value="{{ old('nombre_usuario', $user->nombre_usuario) }}" class="form-input">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Correo electrónico *</label>
                            <input type="email" name="email" required maxlength="150"
                                   value="{{ old('email', $user->email) }}" class="form-input">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nueva contraseña (opcional)</label>
                            <input type="password" name="password" minlength="6" class="form-input"
                                   placeholder="Déjalo vacío para conservar la actual">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Rol de acceso *</label>
                            <select name="rol" required class="form-input">
                                <option value="empleado" {{ $user->rol === 'empleado' ? 'selected' : '' }}>Empleado</option>
                                <option value="admin" {{ $user->rol === 'admin' ? 'selected' : '' }}>Administrador</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-muted" onclick="cerrarModal('modal-usuario-editar-{{ $user->id }}')">Cancelar</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-floppy-disk"></i> Guardar cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
@endsection

@push('scripts')
    @vite(['resources/js/users.js'])
@endpush
