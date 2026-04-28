<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ══════════════════════════════════════════
        // USUARIOS DE PRUEBA
        // ══════════════════════════════════════════
        $usuarios = [
            [
                'id'             => 1,
                'nombre'         => 'Fernando Berigüete',
                'email'          => 'fernando@orbitally.test',
                'password'       => Hash::make('password123'),
                'notificaciones' => true,
                'tema'           => 'oscuro',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'id'             => 2,
                'nombre'         => 'Diego Eugenio',
                'email'          => 'diego@orbitally.test',
                'password'       => Hash::make('password123'),
                'notificaciones' => true,
                'tema'           => 'claro',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
        ];
        DB::table('users')->insert($usuarios);

        // ══════════════════════════════════════════
        // CATEGORÍAS PREDEFINIDAS (globales, usuario_id = NULL)
        // ══════════════════════════════════════════
        $categoriasPredefinidas = [
            ['id' => 1, 'nombre' => 'Laboral',  'es_predefinida' => true, 'prioridad' => 1, 'color_borde' => '#4F9CF9', 'usuario_id' => null],
            ['id' => 2, 'nombre' => 'Estudios', 'es_predefinida' => true, 'prioridad' => 2, 'color_borde' => '#A78BFA', 'usuario_id' => null],
            ['id' => 3, 'nombre' => 'Personal', 'es_predefinida' => true, 'prioridad' => 3, 'color_borde' => '#34D399', 'usuario_id' => null],
            ['id' => 4, 'nombre' => 'Ocio',     'es_predefinida' => true, 'prioridad' => 4, 'color_borde' => '#FBBF24', 'usuario_id' => null],
        ];

        // CATEGORÍAS PERSONALIZADAS (creadas por los usuarios)
        $categoriasPersonalizadas = [
            ['id' => 5, 'nombre' => 'DAW - PFG',      'es_predefinida' => false, 'prioridad' => 1, 'color_borde' => '#F87171', 'usuario_id' => 1],
            ['id' => 6, 'nombre' => 'Freelance',       'es_predefinida' => false, 'prioridad' => 2, 'color_borde' => '#FB923C', 'usuario_id' => 1],
            ['id' => 7, 'nombre' => 'Idiomas',         'es_predefinida' => false, 'prioridad' => 1, 'color_borde' => '#38BDF8', 'usuario_id' => 2],
            ['id' => 8, 'nombre' => 'Ejercicio',       'es_predefinida' => false, 'prioridad' => 2, 'color_borde' => '#4ADE80', 'usuario_id' => 2],
        ];

        DB::table('categorias')->insert(array_map(fn($c) => array_merge($c, [
            'created_at' => now(), 'updated_at' => now(),
        ]), array_merge($categoriasPredefinidas, $categoriasPersonalizadas)));

        // ══════════════════════════════════════════
        // TAREAS - Usuario 1 (Fernando)
        // ══════════════════════════════════════════
        $tareasF = [
            [
                'titulo'       => 'Terminar migrations de Orbitally',
                'descripcion'  => 'Rehacer los ficheros de migración según la tercera entrega del PFG eliminando etiquetas y tarea_etiqueta.',
                'emoji'        => '🛠️',
                'color'        => '#1E293B',
                'negrita'      => true,
                'cursiva'      => false,
                'fecha_inicio' => now()->subDays(2),
                'fecha_fin'    => now()->addDays(1),
                'estado'       => 'en_progreso',
                'prioridad'    => 'alta',
                'categoria_id' => 5, // DAW - PFG
                'usuario_id'   => 1,
            ],
            [
                'titulo'       => 'Implementar sistema de autenticación',
                'descripcion'  => 'Configurar Laravel Breeze con registro, login y middleware de rutas protegidas.',
                'emoji'        => '🔐',
                'color'        => '#0F172A',
                'negrita'      => true,
                'cursiva'      => false,
                'fecha_inicio' => now()->subDays(5),
                'fecha_fin'    => now()->subDays(1),
                'estado'       => 'completada',
                'prioridad'    => 'alta',
                'categoria_id' => 5,
                'usuario_id'   => 1,
            ],
            [
                'titulo'       => 'Diseñar dashboard principal',
                'descripcion'  => 'Maquetar con Blade y Tailwind CSS el panel principal con sidebar y secciones.',
                'emoji'        => '🎨',
                'color'        => '#1E293B',
                'negrita'      => false,
                'cursiva'      => false,
                'fecha_inicio' => now(),
                'fecha_fin'    => now()->addDays(4),
                'estado'       => 'pendiente',
                'prioridad'    => 'media',
                'categoria_id' => 5,
                'usuario_id'   => 1,
            ],
            [
                'titulo'       => 'Integrar API de Groq',
                'descripcion'  => 'Conectar el asistente de IA usando Guzzle desde el backend. La clave API nunca se expone al cliente.',
                'emoji'        => '🤖',
                'color'        => '#312E81',
                'negrita'      => false,
                'cursiva'      => true,
                'fecha_inicio' => now()->addDays(3),
                'fecha_fin'    => now()->addDays(8),
                'estado'       => 'pendiente',
                'prioridad'    => 'alta',
                'categoria_id' => 5,
                'usuario_id'   => 1,
            ],
            [
                'titulo'       => 'Reunión con cliente web',
                'descripcion'  => 'Presentar avance del proyecto freelance y revisar cambios en el diseño.',
                'emoji'        => '💼',
                'color'        => null,
                'negrita'      => false,
                'cursiva'      => false,
                'fecha_inicio' => now()->addDays(1),
                'fecha_fin'    => now()->addDays(1),
                'estado'       => 'pendiente',
                'prioridad'    => 'media',
                'categoria_id' => 6, // Freelance
                'usuario_id'   => 1,
            ],
            [
                'titulo'       => 'Hacer la compra semanal',
                'descripcion'  => null,
                'emoji'        => '🛒',
                'color'        => null,
                'negrita'      => false,
                'cursiva'      => false,
                'fecha_inicio' => now(),
                'fecha_fin'    => now()->addDays(2),
                'estado'       => 'pendiente',
                'prioridad'    => 'baja',
                'categoria_id' => 3, // Personal
                'usuario_id'   => 1,
            ],
        ];

        // TAREAS - Usuario 2 (Diego)
        $tareasD = [
            [
                'titulo'       => 'Estudiar inglés B2 - Listening',
                'descripcion'  => 'Practicar comprensión auditiva con ejercicios de Cambridge.',
                'emoji'        => '🎧',
                'color'        => '#0C4A6E',
                'negrita'      => false,
                'cursiva'      => true,
                'fecha_inicio' => now()->subDays(1),
                'fecha_fin'    => now()->addDays(5),
                'estado'       => 'en_progreso',
                'prioridad'    => 'alta',
                'categoria_id' => 7, // Idiomas
                'usuario_id'   => 2,
            ],
            [
                'titulo'       => 'Rutina de cardio 30 min',
                'descripcion'  => 'Salir a correr por el parque o usar la bicicleta estática.',
                'emoji'        => '🏃',
                'color'        => '#14532D',
                'negrita'      => false,
                'cursiva'      => false,
                'fecha_inicio' => now(),
                'fecha_fin'    => now(),
                'estado'       => 'completada',
                'prioridad'    => 'media',
                'categoria_id' => 8, // Ejercicio
                'usuario_id'   => 2,
            ],
            [
                'titulo'       => 'Documentar entidades de la BD',
                'descripcion'  => 'Completar la sección 4.2.2 del documento de entrega con las descripciones finales.',
                'emoji'        => '📄',
                'color'        => '#1E293B',
                'negrita'      => true,
                'cursiva'      => false,
                'fecha_inicio' => now()->subDays(3),
                'fecha_fin'    => now()->subDays(1),
                'estado'       => 'completada',
                'prioridad'    => 'alta',
                'categoria_id' => 2, // Estudios
                'usuario_id'   => 2,
            ],
            [
                'titulo'       => 'Ver serie de estreno',
                'descripcion'  => null,
                'emoji'        => '🎬',
                'color'        => null,
                'negrita'      => false,
                'cursiva'      => false,
                'fecha_inicio' => now()->addDays(2),
                'fecha_fin'    => now()->addDays(2),
                'estado'       => 'pendiente',
                'prioridad'    => 'baja',
                'categoria_id' => 4, // Ocio
                'usuario_id'   => 2,
            ],
        ];

        $todasTareas = array_map(fn($t) => array_merge($t, [
            'created_at' => now(), 'updated_at' => now(),
        ]), array_merge($tareasF, $tareasD));

        DB::table('tareas')->insert($todasTareas);

        // ══════════════════════════════════════════
        // SESIONES POMODORO
        // ══════════════════════════════════════════
        $sesiones = [
            // Fernando - completadas
            [
                'duracion_estudio'  => 25,
                'duracion_descanso' => 5,
                'inicio'            => now()->subDays(2)->setTime(10, 0),
                'fin'               => now()->subDays(2)->setTime(10, 25),
                'duracion_real'     => 25,
                'estado'            => 'completada',
                'tarea_id'          => 2, // autenticación (completada)
                'usuario_id'        => 1,
            ],
            [
                'duracion_estudio'  => 25,
                'duracion_descanso' => 5,
                'inicio'            => now()->subDays(2)->setTime(11, 0),
                'fin'               => now()->subDays(2)->setTime(11, 25),
                'duracion_real'     => 25,
                'estado'            => 'completada',
                'tarea_id'          => 2,
                'usuario_id'        => 1,
            ],
            [
                'duracion_estudio'  => 50,
                'duracion_descanso' => 10,
                'inicio'            => now()->subDays(1)->setTime(9, 0),
                'fin'               => now()->subDays(1)->setTime(9, 38),
                'duracion_real'     => 38,
                'estado'            => 'cancelada',
                'tarea_id'          => 1, // migrations (en progreso)
                'usuario_id'        => 1,
            ],
            [
                'duracion_estudio'  => 25,
                'duracion_descanso' => 5,
                'inicio'            => now()->setTime(10, 0),
                'fin'               => null,
                'duracion_real'     => null,
                'estado'            => 'activa',
                'tarea_id'          => 1,
                'usuario_id'        => 1,
            ],
            // Diego - completadas
            [
                'duracion_estudio'  => 25,
                'duracion_descanso' => 5,
                'inicio'            => now()->subDays(1)->setTime(16, 0),
                'fin'               => now()->subDays(1)->setTime(16, 25),
                'duracion_real'     => 25,
                'estado'            => 'completada',
                'tarea_id'          => 7, // documentar BD (completada)
                'usuario_id'        => 2,
            ],
            [
                'duracion_estudio'  => 25,
                'duracion_descanso' => 5,
                'inicio'            => now()->subDays(1)->setTime(17, 0),
                'fin'               => now()->subDays(1)->setTime(17, 25),
                'duracion_real'     => 25,
                'estado'            => 'completada',
                'tarea_id'          => null, // sin tarea asociada
                'usuario_id'        => 2,
            ],
        ];

        DB::table('sesiones_pomodoro')->insert(array_map(fn($s) => array_merge($s, [
            'created_at' => now(), 'updated_at' => now(),
        ]), $sesiones));

        // ══════════════════════════════════════════
        // CHAT IA
        // ══════════════════════════════════════════
        $chats = [
            [
                'mensaje'    => '¿Cómo puedo organizar mis tareas del PFG para terminar a tiempo?',
                'respuesta'  => 'Te recomiendo dividir el proyecto en bloques semanales. Empieza por las tareas marcadas como alta prioridad y asigna sesiones Pomodoro de 25 minutos a cada una. Para esta semana, prioriza "Terminar migrations" y "Diseñar dashboard" antes de avanzar con la integración de Groq.',
                'usuario_id' => 1,
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
            ],
            [
                'mensaje'    => 'Tengo 3 horas libres esta tarde, ¿cómo las distribuyo?',
                'respuesta'  => 'Con 3 horas puedes hacer 4 bloques Pomodoro de 25 min + descanso. Propongo: bloque 1-2 para "Implementar autenticación", bloque 3 para revisar el diseño del dashboard y bloque 4 para documentar lo avanzado. Deja los últimos 15 min para anotar pendientes del día siguiente.',
                'usuario_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'mensaje'    => 'Ayúdame a dividir la tarea "Integrar API de Groq" en pasos más pequeños.',
                'respuesta'  => "Claro, aquí tienes los pasos: 1) Instalar Guzzle con composer. 2) Crear el GroqService en app/Services. 3) Añadir GROQ_API_KEY al .env. 4) Crear el controlador ChatIaController. 5) Definir la ruta POST /chat. 6) Conectar la vista Blade con fetch() al endpoint. 7) Probar con preguntas de prueba y controlar errores de la API.",
                'usuario_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'mensaje'    => '¿Cuántas sesiones Pomodoro necesito para estudiar el temario de inglés B2?',
                'respuesta'  => 'Depende del volumen de temario, pero para un B2 completo te recomiendo entre 3 y 4 sesiones diarias de 25 minutos. Distribuye: 1 sesión de listening, 1 de reading, 1 de gramática y 1 de vocabulario. En 4 semanas a este ritmo cubres un temario estándar con repaso.',
                'usuario_id' => 2,
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
        ];

        DB::table('chat_ia')->insert($chats);
    }
}