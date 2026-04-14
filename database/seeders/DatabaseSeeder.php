<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Categoria;
use App\Models\Tarea;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Usuario de prueba
        $user = User::create([
            'nombre'   => 'Usuario Demo',
            'email'    => 'demo@orbitally.app',
            'password' => Hash::make('password'),
        ]);

        // Categorías por defecto
        $cats = [
            Categoria::create(['nombre' => 'Laboral',  'prioridad' => 1, 'color_borde' => '#4dcfcf', 'usuario_id' => $user->id]),
            Categoria::create(['nombre' => 'Personal', 'prioridad' => 2, 'color_borde' => '#c9a84c', 'usuario_id' => $user->id]),
            Categoria::create(['nombre' => 'Estudios', 'prioridad' => 3, 'color_borde' => '#88aaff', 'usuario_id' => $user->id]),
            Categoria::create(['nombre' => 'Ocio',     'prioridad' => 4, 'color_borde' => '#aaffaa', 'usuario_id' => $user->id]),
        ];

        // Tareas de ejemplo
        $tareas = [
            ['titulo' => 'Preparar informe mensual',    'emoji' => '📊', 'prioridad' => 'alta',  'estado' => 'en_progreso', 'categoria_id' => $cats[0]->id, 'fecha_fin' => Carbon::now()->addDays(2)],
            ['titulo' => 'Revisar correos pendientes',  'emoji' => '📧', 'prioridad' => 'media', 'estado' => 'pendiente',   'categoria_id' => $cats[0]->id, 'fecha_fin' => Carbon::now()->addDays(1)],
            ['titulo' => 'Estudiar Laravel avanzado',   'emoji' => '📚', 'prioridad' => 'alta',  'estado' => 'pendiente',   'categoria_id' => $cats[2]->id, 'fecha_fin' => Carbon::now()->addDays(7)],
            ['titulo' => 'Repasar migraciones',         'emoji' => '🗄️', 'prioridad' => 'media', 'estado' => 'completada',  'categoria_id' => $cats[2]->id, 'fecha_fin' => Carbon::now()->subDays(1)],
            ['titulo' => 'Llamar al médico',            'emoji' => '🏥', 'prioridad' => 'alta',  'estado' => 'pendiente',   'categoria_id' => $cats[1]->id, 'fecha_fin' => Carbon::now()->addDays(3)],
            ['titulo' => 'Comprar material de oficina', 'emoji' => '🛒', 'prioridad' => 'baja',  'estado' => 'pendiente',   'categoria_id' => $cats[1]->id, 'fecha_fin' => Carbon::now()->addDays(10)],
            ['titulo' => 'Terminar serie favorita',     'emoji' => '🎬', 'prioridad' => 'baja',  'estado' => 'pendiente',   'categoria_id' => $cats[3]->id, 'fecha_fin' => Carbon::now()->addDays(14)],
            ['titulo' => 'Ejercicio semanal',           'emoji' => '🏃', 'prioridad' => 'media', 'estado' => 'en_progreso', 'categoria_id' => $cats[1]->id, 'fecha_fin' => Carbon::now()->addDays(5)],
        ];

        foreach ($tareas as $tarea) {
            Tarea::create(array_merge($tarea, ['usuario_id' => $user->id]));
        }

        $this->command->info('✓ Usuario demo creado: demo@orbitally.app / password');
    }
}
