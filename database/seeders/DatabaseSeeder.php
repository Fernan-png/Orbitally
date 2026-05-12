<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ---
        // USUARIOS DE PRUEBA
        // ---
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

        // ---
        // CATEGORÍAS PREDEFINIDAS (globales, usuario_id = NULL)
        // ---
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

        // ---
        // TAREAS - Usuario 1 (Fernando)
        // ---
        $tareasF = [
            [
                'titulo'       => 'Terminar migrations de Orbitally',
                'descripcion'  => 'Hay que repasar todos los ficheros de migración y limpiar las tablas que ya no se usan. Mejor hacerlo antes de que se acumule más.',
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
                'descripcion'  => 'Todo lo del login y el registro. También proteger las rutas para que no entren sin cuenta.',
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
                'descripcion'  => 'Montar la pantalla principal con el menú lateral y los paneles de resumen. Que quede limpio y fácil de entender.',
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
                'descripcion'  => 'Conectar el chat con la IA para que los usuarios puedan pedir ayuda desde la propia app. Ojo con no exponer la clave en el frontend.',
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
                'descripcion'  => 'Enseñarle al cliente cómo va la web y hablar de los cambios de diseño que pidió la semana pasada.',
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
                'descripcion'  => 'Llevar un rato con auriculares haciendo ejercicios de escucha. Se me da fatal y necesito practicarlo más.',
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
                'descripcion'  => 'Por lo menos 30 minutos. Si llueve, la bici estática de casa y ya.',
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
                'descripcion'  => 'Terminar de escribir cómo están organizadas las tablas. Me quedé a medias y hay que entregarlo pronto.',
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

        // ---
        // SESIONES POMODORO
        // ---
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

        // ---
        // CHAT IA
        // ---
        $chats = [
            [
                'mensaje'    => 'Tengo el proyecto del PFG encima y no sé ni por dónde empezar. ¿Cómo lo organizarías tú?',
                'respuesta'  => 'Lo primero es listar todo lo que tienes pendiente sin filtrar nada. Luego separa lo que es bloqueante (si no lo haces, no puedes avanzar en lo demás) de lo que puede esperar. Empieza por las tareas de alta prioridad y trabaja en bloques de 25 minutos, uno a la vez. No intentes abarcar todo en un día.',
                'usuario_id' => 1,
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
            ],
            [
                'mensaje'    => 'Tengo 3 horas libres esta tarde y quiero aprovecharlas bien. ¿Cómo las distribuyo?',
                'respuesta'  => 'Con 3 horas tienes margen para 4 bloques de 25 minutos con sus descansos. Usa los dos primeros para lo que más te cueste o lo más urgente, el tercero para algo que lleves a medias, y el cuarto para repasar o documentar lo que hayas avanzado. Reserva los últimos 10 minutos para anotar qué queda pendiente mañana.',
                'usuario_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'mensaje'    => 'La tarea de integrar la IA me parece enorme y no avanzo. ¿Me ayudas a dividirla en pasos más manejables?',
                'respuesta'  => "Claro. Cuando algo parece enorme es porque estás viendo el resultado final, no los pasos. Divídelo así: primero consigue que la petición llegue al servidor y devuelva algo, aunque sea un texto fijo. Luego conecta eso con la API real. Después pinta la respuesta en pantalla. Y al final pule los errores y los casos raros. Cuatro bloques en lugar de uno gigante.",
                'usuario_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'mensaje'    => 'Llevo semanas intentando preparar el B2 de inglés y siento que no avanzo. ¿Por dónde empiezo?',
                'respuesta'  => 'El problema más común es intentar trabajar todo a la vez. Elige una habilidad por sesión: un día listening, otro writing, otro gramática. 25 minutos de práctica enfocada vale más que una hora dando saltos entre temas. Si el examen está cerca, prioriza las partes donde más puntos puedes ganar, que suele ser reading y listening.',
                'usuario_id' => 2,
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
        ];

        DB::table('chat_ia')->insert($chats);
    }
}