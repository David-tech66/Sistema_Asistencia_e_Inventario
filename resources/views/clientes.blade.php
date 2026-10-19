@extends('layouts.admin')

@section('title', 'Clientes')

@section('styles')
    @vite(['resources/css/clientes.css'])
@endsection

@section('content')
    <!-- Título de Sección -->
    <div class="section-header">
        <div>
            <h2 class="section-title">Clientes</h2>
            <p class="section-subtitle">Listado de clientes registrados en el sistema.</p>
        </div>
        <button type="button" class="btn btn-primary" onclick="abrirModal('modal-cliente-crear')">
            <i class="fa-solid fa-plus"></i> Nuevo cliente
        </button>
    </div>

    <!-- Alertas -->
    @if (session('exito'))
        <div class="alert alert-success">
            <i class="fa-solid fa-circle-check"></i> {{ session('exito') }}
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
    <input type="hidden" id="errores-validacion-clientes" value="{{ $errors->any() ? '1' : '0' }}">

    <!-- TABLA DE CLIENTES -->
    <div class="table-card">
        <div class="table-toolbar">
            <div class="table-total">
                <i class="fa-solid fa-user-group"></i>
                <span>Total: <strong>{{ $clientes->count() }}</strong> cliente(s)</span>
            </div>
            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass search-icon"></i>
                <input type="text" id="buscar-cliente" oninput="filtrarClientes()" placeholder="Buscar cliente..." class="search-input" autocomplete="off">
            </div>
        </div>

        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Documento</th>
                        <th>Nombre</th>
                        <th>Teléfono</th>
                        <th>Fecha de Registro</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="tabla-clientes">
                    @forelse ($clientes as $cliente)
                        <tr class="cliente-fila" data-buscar="{{ strtolower($cliente->nombre . ' ' . $cliente->documento . ' ' . ($cliente->telefono ?? '')) }}">
                            <td>
                                <span class="badge-doc">{{ $cliente->documento }}</span>
                            </td>
                            <td>
                                <div class="client-name-cell">
                                    <div class="avatar-icon">
                                        {{ mb_substr($cliente->nombre, 0, 1) }}
                                    </div>
                                    <span class="client-name">{{ $cliente->nombre }}</span>
                                </div>
                            </td>
                            <td>
                                @if ($cliente->telefono)
                                    <span><i class="fa-solid fa-phone" style="font-size:11px; color:#9ca3af; margin-right:6px;"></i>{{ $cliente->telefono }}</span>
                                @else
                                    <span style="color:#d1d5db;">—</span>
                                @endif
                            </td>
                            <td>{{ $cliente->created_at ? $cliente->created_at->format('d/m/Y') : '—' }}</td>
                            <td>
                                <div style="display: flex; gap: 8px;">
                                    <button type="button" class="btn-icon btn-icon--edit" onclick="editarCliente({{ $cliente->id }})">
                                        <i class="fa-solid fa-pen"></i> Editar
                                    </button>
                                    <button type="button" class="btn-icon btn-icon--delete" onclick="eliminarCliente({{ $cliente->id }}, '{{ addslashes($cliente->nombre) }}')">
                                        <i class="fa-solid fa-trash"></i> Eliminar
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <i class="fa-solid fa-user-group"></i>
                                    </div>
                                    <p style="font-weight: 500; color: #6b7280;">No hay clientes registrados</p>
                                    <p style="font-size: 12px; color: #9ca3af; margin-top: 4px;">Cuando se registren clientes, aparecerán en esta lista.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <p id="sin-resultados" class="hidden" style="padding: 32px 20px; text-align: center; color: #9ca3af; font-size: 14px;">No se encontraron clientes con esa búsqueda.</p>
        </div>
    </div>

    <!-- MODAL: Registrar cliente -->
    <div id="modal-cliente-crear" class="modal-overlay hidden">
        <div class="modal-backdrop" onclick="cerrarModal('modal-cliente-crear')"></div>
        <div class="modal-dialog">
            <div class="modal-header">
                <div class="modal-heading">
                    <div class="modal-icon modal-icon--blue">
                        <i class="fa-solid fa-user-plus"></i>
                    </div>
                    <h3 class="modal-title">Registrar cliente</h3>
                </div>
                <button type="button" class="modal-close" onclick="cerrarModal('modal-cliente-crear')">&times;</button>
            </div>
            <form method="POST" action="/clientes">
                @csrf
                <div class="form-group">
                    <label class="form-label">Documento (DNI o RUC) *</label>
                    <input type="text" name="documento" required maxlength="20" class="form-input" style="font-family: monospace;" placeholder="Ej: 12345678">
                </div>
                <div class="form-group">
                    <label class="form-label">Nombre completo *</label>
                    <input type="text" name="nombre" required maxlength="200" class="form-input" placeholder="Ej: Juan Pérez">
                </div>
                <div class="form-group">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="telefono" maxlength="20" class="form-input" placeholder="Ej: 999888777 (opcional)">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="cerrarModal('modal-cliente-crear')">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Registrar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: Editar cliente -->
    <div id="modal-cliente-editar" class="modal-overlay hidden">
        <div class="modal-backdrop" onclick="cerrarModal('modal-cliente-editar')"></div>
        <div class="modal-dialog">
            <div class="modal-header">
                <div class="modal-heading">
                    <div class="modal-icon modal-icon--amber">
                        <i class="fa-solid fa-pen"></i>
                    </div>
                    <h3 class="modal-title">Editar cliente</h3>
                </div>
                <button type="button" class="modal-close" onclick="cerrarModal('modal-cliente-editar')">&times;</button>
            </div>
            <form id="form-cliente-editar" method="POST" action="/clientes/0">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label class="form-label">Documento (DNI o RUC) *</label>
                    <input type="text" name="documento" required maxlength="20" class="form-input" style="font-family: monospace;">
                </div>
                <div class="form-group">
                    <label class="form-label">Nombre completo *</label>
                    <input type="text" name="nombre" required maxlength="200" class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="telefono" maxlength="20" class="form-input">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="cerrarModal('modal-cliente-editar')">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: Confirmar eliminación -->
    <div id="modal-eliminar" class="modal-overlay hidden">
        <div class="modal-backdrop" onclick="cerrarModal('modal-eliminar')"></div>
        <div class="modal-dialog" style="text-align: center;">
            <div class="modal-icon modal-icon--red">
                <i class="fa-solid fa-trash"></i>
            </div>
            <h3 class="modal-title">Eliminar cliente</h3>
            <p style="font-size: 14px; color: #6b7280; margin-top: 8px;">¿Seguro que deseas eliminar a <span id="eliminar-nombre" style="font-weight: 600; color: #111827;"></span>? Esta acción no se puede deshacer.</p>
            <form id="form-eliminar" method="POST" action="/clientes/0">
                @csrf
                @method('DELETE')
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="cerrarModal('modal-eliminar')">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Sí, eliminar</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    @vite(['resources/js/clientes.js'])
@endpush