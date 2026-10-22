@extends('layouts.admin')

@section('title', 'Productos')

@section('styles')
    @vite(['resources/css/productos.css'])
@endsection

@section('content')
    <!-- Título de Sección -->
    <div class="section-header">
        <div>
            <h2 class="section-title">Productos</h2>
            <p class="section-subtitle">Escanea el código QR o código de barras para registrar un nuevo producto.</p>
        </div>
        <button type="button" class="btn btn-primary" onclick="abrirModal('modal-producto-crear')">
            <i class="fa-solid fa-plus"></i> Nuevo producto
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

    <!-- ESCÁNER -->
    <div class="scanner-card">
        <div class="scanner-head">
            <div class="entity-icon">
                <i class="fa-solid fa-barcode"></i>
            </div>
            <div>
                <p class="scanner-title">Escanear código</p>
                <p class="scanner-sub">Apunta el lector al QR o código de barras y presiona Enter.</p>
            </div>
        </div>
        <div class="scanner-row">
            <div class="scanner-input-wrap">
                <i class="fa-solid fa-qrcode scanner-input-icon"></i>
                <input type="text" id="scanner-input" autofocus autocomplete="off"
                       placeholder="Escanea o escribe el código aquí..." class="scanner-input">
            </div>
            <button type="button" class="scanner-btn" onclick="abrirCamara()">
                <i class="fa-solid fa-camera"></i> Usar cámara
            </button>
        </div>
        <p class="scanner-hint"><i class="fa-solid fa-circle-info"></i> Si el código existe se suma +1 al stock; si es nuevo, se abre el formulario de registro.</p>
    </div>

    <!-- TABLA STOCK -->
    <div class="table-card">
        <div class="table-card-header">
            <div class="table-total">
                <i class="fa-solid fa-boxes-stacked table-total-icon"></i>
                <span>Stock de productos — Total: <strong>{{ $productos->count() }}</strong></span>
            </div>
            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass search-icon"></i>
                <input type="text" id="buscar-stock" placeholder="Buscar por nombre, código o categoría..." class="search-input" autocomplete="off">
            </div>
        </div>

        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Código</th>
                        <th>Categoría · Marca</th>
                        <th class="cell-center">Stock</th>
                        <th class="cell-center">Mínimo</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($productos as $producto)
                        <tr data-stock-id="{{ $producto->id }}"
                            data-stock-minimo="{{ $producto->stock_minimo }}"
                            data-buscar="{{ e(strtolower($producto->nombre . ' ' . $producto->codigo_qr . ' ' . ($producto->categoria?->nombre_categoria ?? '') . ' ' . ($producto->marca?->nombre ?? ''))) }}">
                            <td>
                                <div class="cell-entity">
                                    <div class="entity-icon">
                                        <i class="fa-solid fa-box"></i>
                                    </div>
                                    <span class="entity-name">{{ $producto->nombre }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="cell-code">{{ $producto->codigo_qr }}</span>
                            </td>
                            <td class="cell-date">{{ $producto->categoria?->nombre_categoria }} · {{ $producto->marca?->nombre }}</td>
                            <td class="cell-center">
                                <span data-stock-valor class="stock-value">{{ $producto->stock_actual }}</span>
                            </td>
                            <td class="cell-center cell-date">{{ $producto->stock_minimo }}</td>
                            <td>
                                @if ($producto->stock_actual <= $producto->stock_minimo)
                                    <span data-stock-estado class="stock-badge stock-badge--low">Stock bajo</span>
                                @else
                                    <span data-stock-estado class="stock-badge stock-badge--ok">Normal</span>
                                @endif
                            </td>
                            <td class="cell-actions">
                                <div class="actions-group">
                                    <button type="button" class="btn-edit" title="Editar producto"
                                            onclick="editarProducto({{ $producto->id }})">
                                        <i class="fa-solid fa-pen"></i> Editar
                                    </button>
                                    <button type="button" class="btn-delete" title="Eliminar producto"
                                            onclick="eliminarProducto({{ $producto->id }}, '{{ addslashes($producto->nombre) }}')">
                                        <i class="fa-solid fa-trash"></i> Eliminar
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <i class="fa-solid fa-box-open"></i>
                                    </div>
                                    <p class="empty-title">No hay productos registrados</p>
                                    <p class="empty-text">Registra el primero con el escáner o con el botón "Nuevo producto".</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL: Registrar producto -->
    <div id="modal-producto-crear" class="modal-overlay hidden">
        <div class="modal-backdrop" onclick="cerrarModal('modal-producto-crear')"></div>
        <div class="modal-dialog modal-dialog--lg">
            <div class="modal-header">
                <div class="modal-heading">
                    <div class="modal-icon modal-icon--blue">
                        <i class="fa-solid fa-box-open"></i>
                    </div>
                    <div>
                        <h3 class="modal-title">Registrar nuevo producto</h3>
                        <p class="modal-subtitle">Completa los datos del producto.</p>
                    </div>
                </div>
                <button type="button" class="modal-close" onclick="cerrarModal('modal-producto-crear')">&times;</button>
            </div>
            @include('partials.form_producto', ['modo' => 'crear'])
        </div>
    </div>

    <!-- MODAL: Editar producto -->
    <div id="modal-producto-editar" class="modal-overlay hidden">
        <div class="modal-backdrop" onclick="cerrarModal('modal-producto-editar')"></div>
        <div class="modal-dialog modal-dialog--lg">
            <div class="modal-header">
                <div class="modal-heading">
                    <div class="modal-icon modal-icon--amber">
                        <i class="fa-solid fa-pen"></i>
                    </div>
                    <div>
                        <h3 class="modal-title">Editar producto</h3>
                        <p class="modal-subtitle">Actualiza los datos del producto.</p>
                    </div>
                </div>
                <button type="button" class="modal-close" onclick="cerrarModal('modal-producto-editar')">&times;</button>
            </div>
            @include('partials.form_producto', ['modo' => 'editar'])
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
                    <h3 class="confirm-title">Eliminar producto</h3>
                    <p class="confirm-text">¿Seguro que deseas eliminar <strong id="eliminar-nombre"></strong>? Esta acción no se puede deshacer.</p>
                </div>
            </div>
            <form id="form-eliminar" method="POST" action="/productos/0">
                @csrf
                @method('DELETE')
                <div class="modal-footer">
                    <button type="button" class="btn btn-muted" onclick="cerrarModal('modal-eliminar')">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Sí, eliminar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: Cámara -->
    <div id="modal-camara" class="modal-overlay hidden">
        <div class="modal-backdrop" onclick="cerrarCamara()"></div>
        <div class="modal-dialog modal-dialog--camera">
            <div class="modal-header">
                <div class="modal-heading">
                    <div class="modal-icon modal-icon--blue">
                        <i class="fa-solid fa-camera"></i>
                    </div>
                    <div>
                        <h3 class="modal-title">Escáner con cámara</h3>
                    </div>
                </div>
                <div class="modal-header-actions">
                    <button type="button" class="btn-icon" onclick="voltearCamara()" title="Cambiar cámara">
                        <i class="fa-solid fa-rotate"></i>
                    </button>
                    <button type="button" class="modal-close" onclick="cerrarCamara()">&times;</button>
                </div>
            </div>
            <div class="modal-body">
                <div id="lector-camara" class="camera-viewport"></div>
                <p id="camara-estado" class="camera-status">Iniciando cámara…</p>
            </div>
        </div>
    </div>

    <!-- TOAST de escaneo -->
    <div id="toast-escaneo" class="toast-escaneo hidden">
        <p id="toast-escaneo-texto"></p>
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    @vite(['resources/js/productos.js'])
@endpush
