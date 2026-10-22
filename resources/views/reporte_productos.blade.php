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
            <p class="section-subtitle">Análisis de productos: consulta precios, stock e imágenes en un solo lugar.</p>
        </div>
        <div class="section-actions">
            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass search-icon"></i>
                <input type="text" id="buscar-producto" placeholder="Buscar por nombre, código o categoría..." class="search-input" autocomplete="off">
            </div>
        </div>
    </div>

    @if ($productos->isEmpty())
        <!-- Estado vacío -->
        <div class="table-card">
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fa-solid fa-box-open"></i>
                </div>
                <p class="empty-title">No hay productos registrados</p>
                <p class="empty-text">Aún no hay productos en el catálogo para analizar.</p>
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
                    </div>
                </div>
            @endforeach
        </div>
        <p id="sin-resultados" class="empty-filter hidden">No se encontraron productos con esa búsqueda.</p>
    @endif

@endsection

@push('scripts')
    @vite(['resources/js/reporte_productos.js'])
@endpush
