@extends('layouts.admin')

@section('title', 'Proveedores')

@section('styles')
    @vite(['resources/css/crud-components.css'])
@endsection

@section('content')
    <!-- Título de Sección -->
    <div class="section-header">
        <div>
            <h2 class="section-title">Proveedores</h2>
            <p class="section-subtitle">Administra las empresas proveedoras registradas en el sistema.</p>
        </div>
        <button type="button" class="btn btn-primary" onclick="abrirModal('modal-proveedor-crear')">
            <i class="fa-solid fa-plus"></i> Nuevo proveedor
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
    <input type="hidden" id="errores-validacion" value="{{ $errors->any() ? '1' : '0' }}">

    <!-- TABLA PROVEEDORES -->
    <div class="table-card">
        <div class="table-card-header">
            <div class="table-total">
                <i class="fa-solid fa-truck-field table-total-icon"></i>
                <span>Total: <strong>{{ $proveedores->count() }}</strong> proveedor(es)</span>
            </div>
            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass search-icon"></i>
                <input type="text" id="buscar-proveedor" placeholder="Buscar proveedor..." class="search-input" autocomplete="off">
            </div>
        </div>

        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Razón Social</th>
                        <th>RUC</th>
                        <th>Teléfono</th>
                        <th>Correo Electronico</th>
                        <th>Dirección</th>
                        <th>Fecha de Registro</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($proveedores as $proveedor)
                        <tr class="proveedor-fila"
                            data-buscar="{{ e(strtolower($proveedor->razon_social . ' ' . $proveedor->ruc . ' ' . ($proveedor->telefono ?? ''))) }}">
                            <td>
                                <div class="cell-entity">
                                    <div class="entity-icon">
                                        <i class="fa-solid fa-building"></i>
                                    </div>
                                    <span class="entity-name">{{ $proveedor->razon_social }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="cell-code">{{ $proveedor->ruc }}</span>
                            </td>
                            <td>
                                @if ($proveedor->telefono)
                                    <span class="cell-detail"><i class="fa-solid fa-phone"></i>{{ $proveedor->telefono }}</span>
                                @else
                                    <span class="cell-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @if ($proveedor->correo)
                                    <span class="cell-detail"><i class="fa-solid fa-envelope"></i>{{ $proveedor->correo }}</span>
                                @else
                                    <span class="cell-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @if ($proveedor->direccion)
                                    <span class="cell-detail"><i class="fa-solid fa-location-dot"></i>{{ $proveedor->direccion }}</span>
                                @else
                                    <span class="cell-muted">—</span>
                                @endif
                            </td>
                            <td class="cell-date">{{ $proveedor->created_at ? $proveedor->created_at->format('d/m/Y') : '—' }}</td>
                            <td class="cell-actions">
                                <div class="actions-group">
                                    <button type="button" class="btn-edit" onclick="editarProveedor({{ $proveedor->id }})">
                                        <i class="fa-solid fa-pen"></i> Editar
                                    </button>
                                    <button type="button" class="btn-delete" onclick="eliminarProveedor({{ $proveedor->id }}, '{{ addslashes($proveedor->razon_social) }}')">
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
                                        <i class="fa-solid fa-truck-field"></i>
                                    </div>
                                    <p class="empty-title">No hay proveedores registrados</p>
                                    <p class="empty-text">Cuando se registren proveedores, aparecerán en esta lista.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <p id="sin-resultados" class="empty-filter hidden">No se encontraron proveedores con esa búsqueda.</p>
        </div>
    </div>

    <!-- MODAL: Registrar proveedor -->
    <div id="modal-proveedor-crear" class="modal-overlay hidden">
        <div class="modal-backdrop" onclick="cerrarModal('modal-proveedor-crear')"></div>
        <div class="modal-dialog">
            <div class="modal-header">
                <div class="modal-heading">
                    <div class="modal-icon modal-icon--blue">
                        <i class="fa-solid fa-truck-field"></i>
                    </div>
                    <div>
                        <h3 class="modal-title">Registrar nuevo proveedor</h3>
                        <p class="modal-subtitle">Completa los datos de la empresa.</p>
                    </div>
                </div>
                <button type="button" class="modal-close" onclick="cerrarModal('modal-proveedor-crear')">&times;</button>
            </div>
            <form method="POST" action="/proveedores">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Razón social *</label>
                        <input type="text" name="razon_social" required maxlength="150"
                                value="{{ old('razon_social') }}" class="form-input"
                                placeholder="Ej: Distribuidora SAC">
                    </div>
                    <div class="form-group">
                        <label class="form-label">RUC *</label>
                        <input type="text" name="ruc" required maxlength="20"
                                value="{{ old('ruc') }}" class="form-input form-input--mono"
                                placeholder="Ej: 20555555555">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="telefono" maxlength="20"
                                value="{{ old('telefono') }}" class="form-input"
                                placeholder="Ej: 999888777 (opcional)">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Correo electrónico</label>
                        <input type="email" name="correo" maxlength="100"
                                value="{{ old('correo') }}" class="form-input"
                                placeholder="Ej: contacto@empresa.com (opcional)">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Dirección</label>
                        <input type="text" name="direccion" maxlength="200"
                                value="{{ old('direccion') }}" class="form-input"
                                placeholder="Ej: Av. Principal 123 (opcional)">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-muted" onclick="cerrarModal('modal-proveedor-crear')">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Registrar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: Editar proveedor -->
    <div id="modal-proveedor-editar" class="modal-overlay hidden">
        <div class="modal-backdrop" onclick="cerrarModal('modal-proveedor-editar')"></div>
        <div class="modal-dialog">
            <div class="modal-header">
                <div class="modal-heading">
                    <div class="modal-icon modal-icon--amber">
                        <i class="fa-solid fa-pen"></i>
                    </div>
                    <div>
                        <h3 class="modal-title">Editar proveedor</h3>
                        <p class="modal-subtitle">Actualiza los datos de la empresa.</p>
                    </div>
                </div>
                <button type="button" class="modal-close" onclick="cerrarModal('modal-proveedor-editar')">&times;</button>
            </div>
            <form id="form-proveedor-editar" method="POST" action="/proveedores/0">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Razón social *</label>
                        <input type="text" name="razon_social" required maxlength="150" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">RUC *</label>
                        <input type="text" name="ruc" required maxlength="20" class="form-input form-input--mono">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="telefono" maxlength="20" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Correo electrónico</label>
                        <input type="email" name="correo" maxlength="100" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Dirección</label>
                        <input type="text" name="direccion" maxlength="200" class="form-input">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-muted" onclick="cerrarModal('modal-proveedor-editar')">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: Confirmar eliminación -->
    <div id="modal-eliminar" class="modal-overlay hidden">
        <div class="modal-backdrop" onclick="cerrarModal('modal-eliminar')"></div>
        <div class="modal-dialog modal-dialog--confirm">
            <div class="modal-header">
                <div>
                    <div class="confirm-icon">
                        <i class="fa-solid fa-trash"></i>
                    </div>
                    <h3 class="confirm-title">Eliminar proveedor</h3>
                    <p class="confirm-text">¿Seguro que deseas eliminar <strong id="eliminar-nombre"></strong>? Esta acción no se puede deshacer.</p>
                </div>
            </div>
            <form id="form-eliminar" method="POST" action="/proveedores/0">
                @csrf
                @method('DELETE')
                <div class="modal-footer">
                    <button type="button" class="btn btn-muted" onclick="cerrarModal('modal-eliminar')">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Sí, eliminar</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    @vite(['resources/js/proveedores.js'])
@endpush
