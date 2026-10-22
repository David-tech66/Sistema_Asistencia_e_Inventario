<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('configuraciones', function (Blueprint $table) {
            $table->id();
            $table->string('clave')->unique();
            $table->string('valor')->nullable();
            $table->timestamps();
        });

        $ahora = now();
        DB::table('configuraciones')->insert([
            ['clave' => 'hora_entrada', 'valor' => '08:10', 'created_at' => $ahora, 'updated_at' => $ahora],
            ['clave' => 'hora_salida', 'valor' => '18:00', 'created_at' => $ahora, 'updated_at' => $ahora],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('configuraciones');
    }
};
