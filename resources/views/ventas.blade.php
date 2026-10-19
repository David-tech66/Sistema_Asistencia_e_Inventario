@extends('layouts.admin')

@section('title', 'Punto de Venta')

@section('styles')
    @vite(['resources/css/ventas.css'])
@endsection

@section('content')
    <!-- Título de Sección -->
    <div class="section-header">
        <h2 class="section-title">Punto de Venta</h2>
        <p class="section-subtitle">Escanea los productos y cobra la venta.</p>
    </div>

    <!-- Toast de escaneo -->
    <div id="toast" class="toast-pos hidden"></div>

    <div class="flex gap-6 items-start">

        <!-- COLUMNA IZQUIERDA: Escáner y Últimas Ventas -->
        <div class="flex-1 space-y-6 min-w-0">

            <!-- ESCÁNER -->
            <div class="scanner-card">
                <div class="scanner-head">
                    <div class="scanner-icon">
                        <i class="fa-solid fa-barcode"></i>
                    </div>
                    <div>
                        <h3 class="scanner-title">Escanear producto</h3>
                        <p class="scanner-sub">Apunta el lector al QR y presiona Enter para agregar al voucher.</p>
                    </div>
                </div>
                <div class="scanner-row">
                    <div class="scanner-input-wrap">
                        <i class="fa-solid fa-qrcode scanner-input-icon"></i>
                        <input type="text" id="scanner-input" autofocus autocomplete="off"
                               placeholder="Escanea o escribe el código aquí..."
                               class="scanner-input">
                    </div>
                    <button type="button" class="scanner-btn" onclick="abrirCamara()">
                        <i class="fa-solid fa-camera"></i> Cámara
                    </button>
                </div>
                <p class="scanner-hint"><i class="fa-solid fa-circle-info mr-1"></i> Cada escaneo agrega 1 unidad al voucher. Escanea varias veces para sumar cantidad.</p>
            </div>

            <!-- ÚLTIMAS VENTAS -->
            <div class="table-card">
                <div class="table-toolbar">
                    <i class="fa-solid fa-receipt"></i>
                    <span>Últimas ventas — Total: <strong style="color: #111827;">{{ $ventas->count() }}</strong></span>
                </div>
                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Comprobante</th>
                                <th>Cliente</th>
                                <th>Tipo</th>
                                <th style="text-align: right;">Total</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($ventas->take(10) as $venta)
                                <tr>
                                    <td>
                                        <span class="badge-comprobante">{{ $venta->numero_comprobante }}</span>
                                    </td>
                                    <td style="color: #374151; font-weight: 500;">{{ $venta->cliente?->nombre }}</td>
                                    <td>
                                        <span class="badge-tipo {{ $venta->tipo_comprobante === 'Boleta' ? 'badge-tipo--boleta' : 'badge-tipo--factura' }}">{{ $venta->tipo_comprobante }}</span>
                                    </td>
                                    <td style="text-align: right; font-weight: 600; color: #111827;">S/ {{ number_format($venta->total, 2) }}</td>
                                    <td style="color: #6b7280;">{{ $venta->created_at?->format('d/m/Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" style="text-align: center; padding: 40px 0; color: #9ca3af;">No hay ventas registradas todavía.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- COLUMNA DERECHA: Voucher -->
        <div style="width: 400px; flex-shrink: 0;">
            <div class="voucher-card">

                <!-- Encabezado del voucher -->
                <div class="voucher-header">
                    <div class="voucher-icon">
                        <i class="fa-solid fa-file-invoice-dollar"></i>
                    </div>
                    <div>
                        <h3 style="font-size: 16px; font-weight: 600; color: #111827;">Boleta de venta</h3>
                        <p style="font-size: 11px; color: #9ca3af;">Grupo MDL E.I.R.L.</p>
                    </div>
                </div>

                <!-- Cliente + Tipo -->
                <div class="voucher-form-section" style="space-y: 12px;">
                    <div style="margin-bottom: 12px;">
                        <label class="voucher-label">Cliente</label>
                        <select id="cliente-select" class="voucher-select">
                            @foreach ($clientes as $cliente)
                                <option value="{{ $cliente->id }}" {{ $cliente->nombre === 'Cliente General' ? 'selected' : '' }}>
                                    {{ $cliente->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="voucher-label">Comprobante</label>
                        <select id="comprobante-select" class="voucher-select">
                            <option value="Boleta">Boleta</option>
                            <option value="Factura">Factura</option>
                        </select>
                    </div>
                </div>

                <!-- Items del voucher -->
                <div id="voucher-items" class="voucher-items-container">
                    <p id="voucher-vacio" style="text-align: center; font-size: 14px; color: #9ca3af; padding: 32px 0;">
                        <i class="fa-solid fa-cart-shopping" style="font-size: 30px; color: #e5e7eb; display: block; margin-bottom: 12px;"></i>
                        Escanea un producto para comenzar
                    </p>
                </div>

                <!-- Total + Cobrar -->
                <div class="voucher-footer">
                    <div style="display: flex; justify-content: space-between; align-items: center; font-size: 18px; font-weight: 700; color: #111827; margin-bottom: 16px;">
                        <span>Total</span>
                        <span id="voucher-total">S/ 0.00</span>
                    </div>
                    <button type="button" id="btn-cobrar" onclick="cobrarVenta()" disabled class="btn-cobrar">
                        <i class="fa-solid fa-check-double"></i> Cobrar venta
                    </button>
                    <button type="button" onclick="cancelarVenta()" class="btn-cancelar-venta">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL: Confirmación de venta -->
    <div id="modal-exito" class="modal-overlay hidden">
        <div class="modal-backdrop" onclick="cerrarModalExito()"></div>
        <div class="modal-dialog" style="text-align: center;">
            <div style="height: 64px; width: 64px; border-radius: 9999px; background-color: #ecfdf5; color: #059669; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                <i class="fa-solid fa-check" style="font-size: 28px;"></i>
            </div>
            <h3 style="font-size: 20px; font-weight: 700; color: #111827; margin-bottom: 4px;">Venta registrada</h3>
            <p style="font-size: 14px; color: #6b7280; margin-bottom: 16px;">Comprobante generado correctamente.</p>
            <div style="background-color: #f9fafb; border-radius: 12px; padding: 16px; text-align: left; font-size: 14px; space-y: 8px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 6px;"><span style="color: #6b7280;">Comprobante:</span> <span id="exito-comprobante" style="font-family: monospace; font-weight: 600;"></span></div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 6px;"><span style="color: #6b7280;">Cliente:</span> <span id="exito-cliente" style="font-weight: 600;"></span></div>
                <div style="display: flex; justify-content: space-between;"><span style="color: #6b7280;">Total:</span> <span id="exito-total" style="font-weight: 700; color: #059669;"></span></div>
            </div>
            <button type="button" onclick="cerrarModalExito()" class="scanner-btn" style="width: 100%; justify-content: center; margin-top: 20px;">Aceptar</button>
        </div>
    </div>

    <!-- MODAL: Cámara -->
    <div id="modal-camara" class="modal-overlay hidden">
        <div class="modal-backdrop" onclick="cerrarCamara()"></div>
        <div class="modal-dialog">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
                <h3 style="font-size: 16px; font-weight: 600; color: #111827;">Escanear con cámara</h3>
                <button type="button" onclick="cerrarCamara()" style="height: 32px; width: 32px; border-radius: 8px; background-color: #f3f4f6; border: none; color: #4b5563; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div id="lector-camara" class="camera-viewport"></div>
            <p id="camara-estado" style="font-size: 12px; color: #6b7280; margin-top: 12px; text-align: center;">Iniciando cámara…</p>
            <button type="button" onclick="voltearCamara()" style="margin-top: 12px; width: 100%; padding: 8px; font-size: 12px; font-weight: 500; color: #4b5563; background-color: #f3f4f6; border: none; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;">
                <i class="fa-solid fa-camera-rotate"></i> Voltear cámara
            </button>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    @vite(['resources/js/ventas.js'])
@endpush