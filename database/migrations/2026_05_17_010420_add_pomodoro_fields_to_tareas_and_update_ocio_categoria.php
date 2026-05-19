<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tareas', function (Blueprint $table) {
            $table->unsignedSmallInteger('pomodoro_estudio')->nullable()->after('categoria_id');
            $table->unsignedSmallInteger('pomodoro_descanso')->nullable()->after('pomodoro_estudio');
        });

        // Cambiar "Ocio" por "Pomodoro" en las categorías predefinidas
        DB::table('categorias')
            ->whereNull('usuario_id')
            ->where('nombre', 'Ocio')
            ->update([
                'nombre'      => 'Pomodoro',
                'color_borde' => '#F97316',
                'updated_at'  => now(),
            ]);
    }

    public function down(): void
    {
        Schema::table('tareas', function (Blueprint $table) {
            $table->dropColumn(['pomodoro_estudio', 'pomodoro_descanso']);
        });

        DB::table('categorias')
            ->whereNull('usuario_id')
            ->where('nombre', 'Pomodoro')
            ->update([
                'nombre'      => 'Ocio',
                'color_borde' => '#FBBF24',
                'updated_at'  => now(),
            ]);
    }
};
