<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();

            // Llaves Foraneas:
            $table->foreignId('categoria_id')->constrained('categorias');
            $table->foreignId('marca_id')->constrained('marcas');
            $table->foreignId('proveedor_id')->constrained('proveedores');

            $table->string('codigo_qr', 255)->unique()->comment('Código general del modelo');
            $table->string('nombre', 200);
            $table->json('especificaciones')->nullable()->comment('Guarda RAM, CPU, etc.');
            $table->integer('meses_garantia')->default(12);
            $table->integer('stock_actual')->default(0);
            $table->integer('stock_minimo')->default(5);
            
            $table->decimal('precio_compra', 10, 2)->default(0.00);
            $table->decimal('precio_venta', 10, 2)->default(0.00);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
