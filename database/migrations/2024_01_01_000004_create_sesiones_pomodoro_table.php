<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sesiones_pomodoro', function (Blueprint $table) {
            $table->id();
            $table->integer('duracion_estudio')->default(25);
            $table->integer('duracion_descanso')->default(5);
            $table->dateTime('inicio')->nullable();
            $table->dateTime('fin')->nullable();
            $table->integer('duracion_real')->nullable();
            $table->enum('estado', ['activa', 'completada', 'cancelada'])->default('activa');
            $table->foreignId('tarea_id')->nullable()->constrained('tareas')->onDelete('set null');
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sesiones_pomodoro');
    }
};