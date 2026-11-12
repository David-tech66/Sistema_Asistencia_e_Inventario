@php
    $modo = $modo ?? 'crear';
    $producto = $producto ?? null;
@endphp

<form action="{{ $modo === 'editar' ? '/productos/' . ($producto?->id ?? 0) : '/productos' }}" method="POST" enctype="multipart/form-data" id="form-producto-{{ $modo }}">
    @csrf
    @if ($modo === 'editar')
        @method('PUT')
    @endif
    <input type="hidden" name="modal_origen" value="modal-producto-{{ $modo }}">

    <div class="modal-body">
        <div class="form-grid">
            <!-- Código QR + Nombre -->
            <div class="form-group">
                <label for="codigo_qr-{{ $modo }}" class="form-label">Código QR / Barras *</label>
                <input type="text" name="codigo_qr" id="codigo_qr-{{ $modo }}" value="{{ old('codigo_qr', $producto?->codigo_qr) }}" required
                       class="form-input form-input--mono" placeholder="Escanea o escribe el código">
                @error('codigo_qr') <p class="form-error">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
                <label for="nombre-{{ $modo }}" class="form-label">Nombre del producto *</label>
                <input type="text" id="nombre-{{ $modo }}" name="nombre" value="{{ old('nombre', $producto?->nombre) }}" required maxlength="200"
                       class="form-input" placeholder="Ej: Laptop Lenovo IdeaPad">
                @error('nombre') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <!-- Categoría + Marca -->
            <div class="form-group">
                <label for="categoria_id-{{ $modo }}" class="form-label">Categoría *</label>
                <select name="categoria_id" id="categoria_id-{{ $modo }}" required class="form-input">
                    <option value="">Selecciona una categoría</option>
                    @foreach ($categorias as $categoria)
                        <option value="{{ $categoria->id }}" @selected(old('categoria_id', $producto?->categoria_id) == $categoria->id)>
                            {{ $categoria->nombre_categoria }}
                        </option>
                    @endforeach
                </select>
                @error('categoria_id') <p class="form-error">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
                <label for="marca_id-{{ $modo }}" class="form-label">Marca *</label>
                <select name="marca_id" id="marca_id-{{ $modo }}" required class="form-input">
                    <option value="">Selecciona una marca</option>
                    @foreach ($marcas as $marca)
                        <option value="{{ $marca->id }}" @selected(old('marca_id', $producto?->marca_id) == $marca->id)>
                            {{ $marca->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('marca_id') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <!-- Proveedor + Garantía -->
            <div class="form-group">
                <label for="proveedor_id-{{ $modo }}" class="form-label">Proveedor *</label>
                <select name="proveedor_id" id="proveedor_id-{{ $modo }}" required class="form-input">
                    <option value="">Selecciona un proveedor</option>
                    @foreach ($proveedores as $proveedor)
                        <option value="{{ $proveedor->id }}" @selected(old('proveedor_id', $producto?->proveedor_id) == $proveedor->id)>
                            {{ $proveedor->razon_social }}
                        </option>
                    @endforeach
                </select>
                @error('proveedor_id') <p class="form-error">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
                <label for="meses_garantia-{{ $modo }}" class="form-label">Garantía (meses) *</label>
                <input type="number" id="meses_garantia-{{ $modo }}" name="meses_garantia" value="{{ old('meses_garantia', $producto?->meses_garantia ?? 12) }}" required min="0" max="120"
                       class="form-input">
                @error('meses_garantia') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <!-- Stock -->
            <div class="form-group">
                <label for="stock_actual-{{ $modo }}" class="form-label">Stock actual *</label>
                <input type="number" id="stock_actual-{{ $modo }}" name="stock_actual" value="{{ old('stock_actual', $producto?->stock_actual ?? 0) }}" required min="0"
                       class="form-input">
                @error('stock_actual') <p class="form-error">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
                <label for="stock_minimo-{{ $modo }}" class="form-label">Stock mínimo *</label>
                <input type="number" id="stock_minimo-{{ $modo }}" name="stock_minimo" value="{{ old('stock_minimo', $producto?->stock_minimo ?? 5) }}" required min="0"
                       class="form-input">
                @error('stock_minimo') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <!-- Precios -->
            <div class="form-group">
                <label for="precio_compra-{{ $modo }}" class="form-label">Precio de compra (S/) *</label>
                <input type="number" id="precio_compra-{{ $modo }}" name="precio_compra" step="0.01" value="{{ old('precio_compra', $producto?->precio_compra ?? '0.00') }}" required min="0"
                       class="form-input">
                @error('precio_compra') <p class="form-error">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
                <label for="precio_venta-{{ $modo }}" class="form-label">Precio de venta (S/) *</label>
                <input type="number" id="precio_venta-{{ $modo }}" name="precio_venta" step="0.01" value="{{ old('precio_venta', $producto?->precio_venta ?? '0.00') }}" required min="0"
                       class="form-input">
                @error('precio_venta') <p class="form-error">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- Imagen -->
        <div class="form-group">
            <label for="imagen-{{ $modo }}" class="form-label">Imagen del producto</label>
            <div class="img-row">
                <div class="img-drop">
                    @if ($modo === 'editar' && $producto?->imagen)
                        <img src="{{ asset('storage/' . $producto->imagen) }}" alt="Imagen actual" id="preview-img-{{ $modo }}">
                    @else
                        <i class="fa-solid fa-image" id="preview-icon-{{ $modo }}"></i>
                        <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" alt="Vista previa" class="hidden" id="preview-img-{{ $modo }}">
                    @endif
                </div>
                <div class="img-picker">
                    <label class="btn btn-outline" for="imagen-{{ $modo }}">
                        <i class="fa-solid fa-arrow-up-from-bracket"></i> Seleccionar archivo
                    </label>
                    <input type="file" name="imagen" id="imagen-{{ $modo }}" accept="image/jpeg,image/png,image/webp" class="hidden">
                    <p class="img-hint">
                        <span class="img-name" id="archivo-nombre-{{ $modo }}">Ningún archivo seleccionado</span>
                        · JPG, PNG o WebP. Máximo 2 MB.
                    </p>
                </div>
            </div>
            @error('imagen') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <!-- Especificaciones -->
        <div class="form-group">
            <label for="especificaciones-{{ $modo }}" class="form-label">Especificaciones</label>
            <textarea name="especificaciones" id="especificaciones-{{ $modo }}" rows="3" maxlength="2000"
                      class="form-input" placeholder="Ej: RAM 16GB, CPU Ryzen 5, SSD 512GB">{{ old('especificaciones', $producto?->especificaciones) }}</textarea>
            @error('especificaciones') <p class="form-error">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-muted" onclick="cerrarModal('modal-producto-{{ $modo }}')">Cancelar</button>
        <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-check"></i> {{ $modo === 'editar' ? 'Guardar cambios' : 'Registrar producto' }}
        </button>
    </div>
</form>

<script>
    (function () {
        const input = document.getElementById('imagen-{{ $modo }}');
        const preview = document.getElementById('preview-img-{{ $modo }}');
        const icon = document.getElementById('preview-icon-{{ $modo }}');
        const nombre = document.getElementById('archivo-nombre-{{ $modo }}');
        if (input && preview) {
            input.addEventListener('change', function () {
                const file = this.files[0];
                if (file) {
                    preview.src = URL.createObjectURL(file);
                    preview.classList.remove('hidden');
                    if (icon) icon.classList.add('hidden');
                    if (nombre) nombre.textContent = file.name;
                }
            });
        }
    })();
</script>
