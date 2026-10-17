@php
    $modo = $modo ?? 'crear';
    $producto = $producto ?? null;
@endphp

<form action="{{ $modo === 'editar' ? '/productos/' . ($producto?->id ?? 0) : '/productos' }}" method="POST" enctype="multipart/form-data" class="space-y-4" id="form-producto-{{ $modo }}">
    @csrf
    @if ($modo === 'editar')
        @method('PUT')
    @endif

    <!-- Código QR + Nombre -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Código QR / Barras *</label>
            <input type="text" name="codigo_qr" id="codigo_qr-{{ $modo }}" value="{{ old('codigo_qr', $producto?->codigo_qr) }}" required
                   placeholder="Escanea o escribe el código"
                   class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400">
            @error('codigo_qr') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre del producto *</label>
            <input type="text" name="nombre" value="{{ old('nombre', $producto?->nombre) }}" required maxlength="200"
                   placeholder="Ej: Laptop Lenovo IdeaPad"
                   class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400">
            @error('nombre') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
    </div>

    <!-- Categoría + Marca -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Categoría *</label>
            <select name="categoria_id" required
                    class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400">
                <option value="">Selecciona una categoría</option>
                @foreach ($categorias as $categoria)
                    <option value="{{ $categoria->id }}" @selected(old('categoria_id', $producto?->categoria_id) == $categoria->id)>
                        {{ $categoria->nombre_categoria }}
                    </option>
                @endforeach
            </select>
            @error('categoria_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Marca *</label>
            <select name="marca_id" required
                    class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400">
                <option value="">Selecciona una marca</option>
                @foreach ($marcas as $marca)
                    <option value="{{ $marca->id }}" @selected(old('marca_id', $producto?->marca_id) == $marca->id)>
                        {{ $marca->nombre }}
                    </option>
                @endforeach
            </select>
            @error('marca_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
    </div>

    <!-- Proveedor + Garantía -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Proveedor *</label>
            <select name="proveedor_id" required
                    class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400">
                <option value="">Selecciona un proveedor</option>
                @foreach ($proveedores as $proveedor)
                    <option value="{{ $proveedor->id }}" @selected(old('proveedor_id', $producto?->proveedor_id) == $proveedor->id)>
                        {{ $proveedor->razon_social }}
                    </option>
                @endforeach
            </select>
            @error('proveedor_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Garantía (meses) *</label>
            <input type="number" name="meses_garantia" value="{{ old('meses_garantia', $producto?->meses_garantia ?? 12) }}" required min="0" max="120"
                   class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400">
            @error('meses_garantia') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
    </div>

    <!-- Stock -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Stock actual *</label>
            <input type="number" name="stock_actual" value="{{ old('stock_actual', $producto?->stock_actual ?? 0) }}" required min="0"
                   class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400">
            @error('stock_actual') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Stock mínimo *</label>
            <input type="number" name="stock_minimo" value="{{ old('stock_minimo', $producto?->stock_minimo ?? 5) }}" required min="0"
                   class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400">
            @error('stock_minimo') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
    </div>

    <!-- Precios -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Precio de compra (S/) *</label>
            <input type="number" name="precio_compra" step="0.01" value="{{ old('precio_compra', $producto?->precio_compra ?? '0.00') }}" required min="0"
                   class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400">
            @error('precio_compra') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Precio de venta (S/) *</label>
            <input type="number" name="precio_venta" step="0.01" value="{{ old('precio_venta', $producto?->precio_venta ?? '0.00') }}" required min="0"
                   class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400">
            @error('precio_venta') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
    </div>

    <!-- Imagen -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Imagen del producto</label>
        <div class="flex items-center gap-4">
            <div class="h-20 w-20 rounded-xl border-2 border-dashed border-gray-200 bg-gray-50 flex items-center justify-center overflow-hidden shrink-0">
                @if ($modo === 'editar' && $producto?->imagen)
                    <img src="{{ asset('storage/' . $producto->imagen) }}" alt="Imagen actual" class="h-full w-full object-cover" id="preview-img-{{ $modo }}">
                @else
                    <i class="fa-solid fa-image text-gray-300 text-xl" id="preview-icon-{{ $modo }}"></i>
                    <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" alt="Vista previa" class="h-full w-full object-cover hidden" id="preview-img-{{ $modo }}">
                @endif
            </div>
            <div class="flex-1">
                <input type="file" name="imagen" id="imagen-{{ $modo }}" accept="image/jpeg,image/png,image/webp"
                       class="w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                <p class="text-[11px] text-gray-400 mt-1">JPG, PNG o WebP. Máximo 2 MB.</p>
            </div>
        </div>
        @error('imagen') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
    </div>

    <!-- Especificaciones -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Especificaciones</label>
        <textarea name="especificaciones" rows="3" maxlength="2000"
                  placeholder="Ej: RAM 16GB, CPU Ryzen 5, SSD 512GB"
                  class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400">{{ old('especificaciones', $producto?->especificaciones) }}</textarea>
        @error('especificaciones') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
    </div>

    <!-- Botones -->
    <div class="flex justify-end gap-3 pt-2">
        <button type="button" onclick="cerrarModal('modal-producto-{{ $modo }}')"
                class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">
            Cancelar
        </button>
        <button type="submit"
                class="px-5 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition-colors shadow-sm">
            {{ $modo === 'editar' ? 'Guardar cambios' : 'Registrar producto' }}
        </button>
    </div>
</form>

<script>
    (function () {
        const input = document.getElementById('imagen-{{ $modo }}');
        const preview = document.getElementById('preview-img-{{ $modo }}');
        const icon = document.getElementById('preview-icon-{{ $modo }}');
        if (input && preview) {
            input.addEventListener('change', function () {
                const file = this.files[0];
                if (file) {
                    preview.src = URL.createObjectURL(file);
                    preview.classList.remove('hidden');
                    if (icon) icon.classList.add('hidden');
                }
            });
        }
    })();
</script>
