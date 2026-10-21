@extends('layouts.admin')

@section('title', 'Empleados')

@section('styles')
    @vite(['resources/css/empleados.css'])
@endsection

@section('content')
    <!-- Título de Sección -->
    <div class="section-header">
        <div>
            <h2 class="section-title">Gestión de empleados</h2>
            <p class="section-subtitle">Registra al personal de la empresa: DNI, cargo y contacto.</p>
        </div>
        <button type="button" class="btn btn-primary" onclick="abrirModal('modal-empleado-crear')">
            <i class="fa-solid fa-plus"></i> Nuevo empleado
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
                <p class="stat-label">Total de empleados</p>
                <p class="stat-value">{{ $empleados->count() }}</p>
            </div>
            <i class="fa-solid fa-users stat-icon stat-icon--blue"></i>
        </div>
        <div class="stat-card">
            <div>
                <p class="stat-label">Empleados activos</p>
                <p class="stat-value">{{ $empleados->where('estado', true)->count() }}</p>
            </div>
            <i class="fa-solid fa-user-check stat-icon stat-icon--green"></i>
        </div>
        <div class="stat-card">
            <div>
                <p class="stat-label">Empleados inactivos</p>
                <p class="stat-value">{{ $empleados->where('estado', false)->count() }}</p>
            </div>
            <i class="fa-solid fa-user-slash stat-icon stat-icon--red"></i>
        </div>
    </div>

    <!-- TABLA EMPLEADOS -->
    <div class="table-card">
        <div class="table-card-header">
            <div class="table-total">
                <i class="fa-solid fa-id-card table-total-icon"></i>
                <span>Total: <strong>{{ $empleados->count() }}</strong> empleado(s)</span>
            </div>
            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass search-icon"></i>
                <input type="text" id="buscar-empleado" oninput="buscarEmpleado()" placeholder="Buscar por nombre o DNI..." class="search-input" autocomplete="off">
            </div>
        </div>

        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre completo</th>
                        <th>DNI</th>
                        <th>Cargo</th>
                        <th>Teléfono</th>
                        <th>Estado</th>
                        <th class="cell-center">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tabla-empleados">
                    @forelse ($empleados as $empleado)
                        <tr>
                            <td>{{ $empleado->id }}</td>
                            <td>
                                <div class="cell-entity">
                                    <div class="avatar-circle">
                                        {{ strtoupper(substr($empleado->nombre_completo ?? 'E', 0, 1)) }}
                                    </div>
                                    <span class="entity-name">{{ $empleado->nombre_completo }}</span>
                                </div>
                            </td>
                            <td>{{ $empleado->dni }}</td>
                            <td>{{ $empleado->cargo }}</td>
                            <td>{{ $empleado->telefono ?? '—' }}</td>
                            <td>
                                @if ($empleado->estado)
                                    <span class="badge-estado badge-estado--activo">Activo</span>
                                @else
                                    <span class="badge-estado badge-estado--inactivo">Inactivo</span>
                                @endif
                            </td>
                            <td class="cell-center">
                                <div class="actions-group" style="justify-content: center;">
                                    <button type="button" class="btn-edit" onclick="abrirModal('modal-empleado-editar-{{ $empleado->id }}')">
                                        <i class="fa-solid fa-pen"></i> Editar
                                    </button>
                                    <form action="/empleados/{{ $empleado->id }}/estado" method="POST"
                                          onsubmit="return confirm('¿Deseas cambiar el estado de este empleado?')">
                                        @csrf
                                        @method('PATCH')
                                        @if ($empleado->estado)
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
                            <td colspan="7">
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <i class="fa-solid fa-users"></i>
                                    </div>
                                    <p class="empty-title">No hay empleados registrados</p>
                                    <p class="empty-text">Utiliza el botón «Nuevo empleado» para registrar el primero.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="table-footer">
            Registros encontrados: {{ $empleados->count() }}
        </div>
    </div>

    <!-- MODAL: Registrar empleado -->
    <div id="modal-empleado-crear" class="modal-overlay hidden">
        <div class="modal-backdrop" onclick="cerrarModal('modal-empleado-crear')"></div>
        <div class="modal-dialog modal-dialog--lg">
            <div class="modal-header">
                <div class="modal-heading">
                    <div class="modal-icon modal-icon--blue">
                        <i class="fa-solid fa-user-plus"></i>
                    </div>
                    <div>
                        <h3 class="modal-title">Registrar empleado</h3>
                        <p class="modal-subtitle">Completa los datos personales y laborales.</p>
                    </div>
                </div>
                <button type="button" class="modal-close" onclick="cerrarModal('modal-empleado-crear')">&times;</button>
            </div>
            <form method="POST" action="/empleados">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Nombre completo *</label>
                        <input type="text" name="nombre_completo" required maxlength="150"
                               value="{{ old('nombre_completo') }}" class="form-input"
                               placeholder="Ej: Juan Pérez López">
                    </div>
                    <div class="form-group">
                        <label class="form-label">DNI *</label>
                        <input type="text" name="dni" required maxlength="15" pattern="[0-9]+"
                               value="{{ old('dni') }}" class="form-input"
                               placeholder="Ej: 12345678" inputmode="numeric">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Cargo *</label>
                        <input type="text" name="cargo" required maxlength="100"
                               value="{{ old('cargo') }}" class="form-input"
                               placeholder="Ej: Ayudante de almacén">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="telefono" maxlength="20"
                               value="{{ old('telefono') }}" class="form-input"
                               placeholder="Ej: 987654321" inputmode="tel">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-muted" onclick="cerrarModal('modal-empleado-crear')">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-floppy-disk"></i> Guardar empleado
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODALES: Editar empleado -->
    @foreach ($empleados as $empleado)
        <div id="modal-empleado-editar-{{ $empleado->id }}" class="modal-overlay hidden">
            <div class="modal-backdrop" onclick="cerrarModal('modal-empleado-editar-{{ $empleado->id }}')"></div>
            <div class="modal-dialog modal-dialog--lg">
                <div class="modal-header">
                    <div class="modal-heading">
                        <div class="modal-icon modal-icon--amber">
                            <i class="fa-solid fa-user-pen"></i>
                        </div>
                        <div>
                            <h3 class="modal-title">Editar empleado</h3>
                            <p class="modal-subtitle">Actualiza los datos del empleado.</p>
                        </div>
                    </div>
                    <button type="button" class="modal-close" onclick="cerrarModal('modal-empleado-editar-{{ $empleado->id }}')">&times;</button>
                </div>
                <form method="POST" action="/empleados/{{ $empleado->id }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label">Nombre completo *</label>
                            <input type="text" name="nombre_completo" required maxlength="150"
                                   value="{{ old('nombre_completo', $empleado->nombre_completo) }}" class="form-input">
                        </div>
                        <div class="form-group">
                            <label class="form-label">DNI *</label>
                            <input type="text" name="dni" required maxlength="15" pattern="[0-9]+"
                                   value="{{ old('dni', $empleado->dni) }}" class="form-input" inputmode="numeric">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Cargo *</label>
                            <input type="text" name="cargo" required maxlength="100"
                                   value="{{ old('cargo', $empleado->cargo) }}" class="form-input">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Teléfono</label>
                            <input type="text" name="telefono" maxlength="20"
                                   value="{{ old('telefono', $empleado->telefono) }}" class="form-input" inputmode="tel">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-muted" onclick="cerrarModal('modal-empleado-editar-{{ $empleado->id }}')">Cancelar</button>
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
    @vite(['resources/js/empleados.js'])
@endpush
