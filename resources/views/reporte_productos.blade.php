@extends('layouts.admin')

@section('title', 'Reporte de Productos')

@section('styles')
    @vite(['resources/css/reporte_productos.css'])
@endsection

@section('content')
    <!-- Título de Sección -->
    <div class="section-header">
        <div>
            <h2 class="section-title">Reporte de Productos</h2>
            <p class="section-subtitle">Gestión de productos: registra, edita o elimina con su imagen.</p>
        </div>
        <div class="section-actions">
            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass search-icon"></i>
                <input type="text" id="buscar-producto" placeholder="Buscar por nombre, código o categoría..." class="search-input" autocomplete="off">
            </div>
            <button type="button" class="btn btn-primary" onclick="abrirModal('modal-producto-crear')">
                <i class="fa-solid fa-plus"></i> Nuevo producto
            </button>
        </div>
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

    @if ($productos->isEmpty())
        <!-- Estado vacío -->
        <div class="table-card">
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fa-solid fa-box-open"></i>
                </div>
                <p class="empty-title">No hay productos registrados</p>
                <p class="empty-text">Registra el primer producto con el botón "Nuevo producto".</p>
            </div>
        </div>
    @else
        <!-- Cuadrícula de productos -->
        <div class="product-grid">
            @foreach ($productos as $producto)
                <div class="producto-card product-card"
                     data-buscar="{{ e(strtolower($producto->nombre . ' ' . $producto->codigo_qr . ' ' . ($producto->categoria?->nombre_categoria ?? '') . ' ' . ($producto->marca?->nombre ?? ''))) }}">
                    <!-- Imagen -->
                    <div class="product-media">
                        @if ($producto->imagen)
                            <img src="{{ asset('storage/' . $producto->imagen) }}" alt="{{ $producto->nombre }}">
                        @else
                            <div class="product-media-empty">
                                <i class="fa-solid fa-image"></i>
                                <span>Sin imagen</span>
                            </div>
                        @endif
                    </div>

                    <div class="product-body">
                        <div class="product-title-row">
                            <h3 class="product-name">{{ $producto->nombre }}</h3>
                            @if ($producto->stock_actual <= $producto->stock_minimo)
                                <span class="stock-badge stock-badge--low">Stock bajo</span>
                            @endif
                        </div>
                        <p class="product-meta">{{ $producto->categoria?->nombre_categoria }} · {{ $producto->marca?->nombre }}</p>
                        <p class="product-code">{{ $producto->codigo_qr }}</p>

                        <div class="product-footer">
                            <p class="product-price">S/ {{ number_format($producto->precio_venta, 2) }}</p>
                            <p class="product-stock">Stock: {{ $producto->stock_actual }} unidades</p>
                        </div>

                        <div class="card-actions">
                            <button type="button" class="btn-edit" onclick="editarProducto({{ $producto->id }})">
                                <i class="fa-solid fa-pen"></i> Editar
                            </button>
                            <button type="button" class="btn-delete" onclick="eliminarProducto({{ $producto->id }}, '{{ addslashes($producto->nombre) }}')">
                                <i class="fa-solid fa-trash"></i> Eliminar
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <p id="sin-resultados" class="empty-filter hidden">No se encontraron productos con esa búsqueda.</p>
    @endif

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
@endsection

@push('scripts')
    @vite(['resources/js/reporte_productos.js'])
@endpush
