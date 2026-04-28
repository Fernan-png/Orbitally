<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50);
            $table->boolean('es_predefinida')->default(false); // true = predefinida global, false = creada por usuario
            $table->integer('prioridad')->default(1);
            $table->string('color_borde', 7)->nullable();
            $table->foreignId('usuario_id')->nullable()->constrained('users')->onDelete('cascade'); // NULL si es categoría predefinida
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categorias');
    }
};