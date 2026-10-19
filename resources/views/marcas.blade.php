@extends('layouts.admin')

@section('title', 'Marcas')

@section('styles')
    @vite(['resources/css/crud-components.css'])
@endsection

@section('content')
    <!-- Título de Sección -->
    <div class="section-header">
        <div>
            <h2 class="section-title">Marcas</h2>
            <p class="section-subtitle">Administra las marcas de los productos.</p>
        </div>
        <button type="button" class="btn btn-primary" onclick="abrirModal('modal-marca-crear')">
            <i class="fa-solid fa-plus"></i> Nueva marca
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

    <!-- TABLA MARCAS -->
    <div class="table-card">
        <div class="table-card-header">
            <div class="table-total">
                <i class="fa-solid fa-copyright table-total-icon"></i>
                <span>Total: <strong>{{ $marcas->count() }}</strong> marca(s)</span>
            </div>
            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass search-icon"></i>
                <input type="text" id="buscar-marca" placeholder="Buscar marca..." class="search-input" autocomplete="off">
            </div>
        </div>

        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Fecha de Registro</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($marcas as $marca)
                        <tr class="marca-fila"
                            data-buscar="{{ e(strtolower($marca->nombre . ' ' . ($marca->descripcion ?? ''))) }}">
                            <td>
                                <div class="cell-entity">
                                    <div class="entity-icon">
                                        <i class="fa-solid fa-copyright"></i>
                                    </div>
                                    <span class="entity-name">{{ $marca->nombre }}</span>
                                </div>
                            </td>
                            <td>
                                @if ($marca->descripcion)
                                    {{ $marca->descripcion }}
                                @else
                                    <span class="cell-muted">—</span>
                                @endif
                            </td>
                            <td class="cell-date">{{ $marca->created_at ? $marca->created_at->format('d/m/Y') : '—' }}</td>
                            <td class="cell-actions">
                                <div class="actions-group">
                                    <button type="button" class="btn-edit" onclick="editarMarca({{ $marca->id }})">
                                        <i class="fa-solid fa-pen"></i> Editar
                                    </button>
                                    <button type="button" class="btn-delete" onclick="eliminarMarca({{ $marca->id }}, '{{ addslashes($marca->nombre) }}')">
                                        <i class="fa-solid fa-trash"></i> Eliminar
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <i class="fa-solid fa-copyright"></i>
                                    </div>
                                    <p class="empty-title">No hay marcas registradas</p>
                                    <p class="empty-text">Registra la primera marca con el botón "Nueva marca".</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <p id="sin-resultados" class="empty-filter hidden">No se encontraron marcas con esa búsqueda.</p>
        </div>
    </div>

    <!-- MODAL: Registrar marca -->
    <div id="modal-marca-crear" class="modal-overlay hidden">
        <div class="modal-backdrop" onclick="cerrarModal('modal-marca-crear')"></div>
        <div class="modal-dialog">
            <div class="modal-header">
                <div class="modal-heading">
                    <div class="modal-icon modal-icon--blue">
                        <i class="fa-solid fa-copyright"></i>
                    </div>
                    <div>
                        <h3 class="modal-title">Registrar nueva marca</h3>
                        <p class="modal-subtitle">Completa los datos de la marca.</p>
                    </div>
                </div>
                <button type="button" class="modal-close" onclick="cerrarModal('modal-marca-crear')">&times;</button>
            </div>
            <form method="POST" action="/marcas">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Nombre *</label>
                        <input type="text" name="nombre" required maxlength="100"
                               value="{{ old('nombre') }}" class="form-input"
                               placeholder="Ej: Lenovo, Samsung, Kingston">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" maxlength="255" rows="2" class="form-input"
                                  placeholder="Descripción breve (opcional)">{{ old('descripcion') }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-muted" onclick="cerrarModal('modal-marca-crear')">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Registrar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: Editar marca -->
    <div id="modal-marca-editar" class="modal-overlay hidden">
        <div class="modal-backdrop" onclick="cerrarModal('modal-marca-editar')"></div>
        <div class="modal-dialog">
            <div class="modal-header">
                <div class="modal-heading">
                    <div class="modal-icon modal-icon--amber">
                        <i class="fa-solid fa-pen"></i>
                    </div>
                    <div>
                        <h3 class="modal-title">Editar marca</h3>
                        <p class="modal-subtitle">Actualiza los datos de la marca.</p>
                    </div>
                </div>
                <button type="button" class="modal-close" onclick="cerrarModal('modal-marca-editar')">&times;</button>
            </div>
            <form id="form-marca-editar" method="POST" action="/marcas/0">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Nombre *</label>
                        <input type="text" name="nombre" required maxlength="100" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" maxlength="255" rows="2" class="form-input"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-muted" onclick="cerrarModal('modal-marca-editar')">Cancelar</button>
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
                    <h3 class="confirm-title">Eliminar marca</h3>
                    <p class="confirm-text">¿Seguro que deseas eliminar <strong id="eliminar-nombre"></strong>? Esta acción no se puede deshacer.</p>
                </div>
            </div>
            <form id="form-eliminar" method="POST" action="/marcas/0">
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
    @vite(['resources/js/marcas.js'])
@endpush
