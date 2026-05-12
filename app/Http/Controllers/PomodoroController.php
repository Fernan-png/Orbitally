<?php

namespace App\Http\Controllers;

use App\Models\Pomodoro;
use App\Models\Tarea;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PomodoroController extends Controller
{
    /**
     * Vista principal del temporizador Pomodoro.
     */
    public function index()
    {
        $user = Auth::user();

        // IDs de las categorías Estudios y Laboral (predefinidas, usuario_id = null)
        $categoriasPermitidas = Categoria::whereNull('usuario_id')
            ->whereIn('nombre', ['Estudios', 'Laboral'])
            ->pluck('id');

        // Solo tareas activas de esas categorías
        $tareas = $user->tareas()
            ->whereIn('estado', ['pendiente', 'en_progreso'])
            ->whereIn('categoria_id', $categoriasPermitidas)
            ->with('categoria')
            ->orderBy('prioridad', 'desc')
            ->get();

        // Historial de las últimas 10 sesiones del usuario
        $historial = Pomodoro::where('usuario_id', $user->id)
            ->with('tarea')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Estadísticas del usuario
        $stats = [
            'completadas'     => Pomodoro::where('usuario_id', $user->id)
                ->where('estado', 'completada')
                ->count(),
            'minutos_totales' => Pomodoro::where('usuario_id', $user->id)
                ->where('estado', 'completada')
                ->sum('duracion_real') ?? 0,
            'hoy'             => Pomodoro::where('usuario_id', $user->id)
                ->where('estado', 'completada')
                ->whereDate('created_at', Carbon::today())
                ->count(),
        ];

        return view('pomodoro.index', compact('tareas', 'historial', 'stats'));
    }

    /**
     * Guarda una nueva sesión Pomodoro cuando el usuario la inicia.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'duracion_estudio'  => 'required|integer|min:1|max:120',
            'duracion_descanso' => 'required|integer|min:1|max:60',
            'tarea_id'          => 'nullable|exists:tareas,id',
        ]);

        // Verificar que la tarea pertenece al usuario y es de categoría permitida
        if (!empty($data['tarea_id'])) {
            $tarea = Tarea::with('categoria')->find($data['tarea_id']);
            abort_if($tarea->usuario_id !== Auth::id(), 403);

            $permitidas = Categoria::whereNull('usuario_id')
                ->whereIn('nombre', ['Estudios', 'Laboral'])
                ->pluck('id');
            abort_if(!$permitidas->contains($tarea->categoria_id), 422);
        }

        $sesion = Pomodoro::create([
            'duracion_estudio'  => $data['duracion_estudio'],
            'duracion_descanso' => $data['duracion_descanso'],
            'inicio'            => Carbon::now(),
            'estado'            => 'activa',
            'tarea_id'          => $data['tarea_id'] ?? null,
            'usuario_id'        => Auth::id(),
        ]);

        return response()->json(['id' => $sesion->id, 'ok' => true]);
    }

    /**
     * Finaliza una sesión (completada o cancelada).
     */
    public function finish(Request $request, Pomodoro $sesion)
    {
        abort_if($sesion->usuario_id !== Auth::id(), 403);
        abort_if($sesion->estado !== 'activa', 422);

        $data = $request->validate([
            'estado'        => 'required|in:completada,cancelada',
            'duracion_real' => 'required|integer|min:0',
        ]);

        $sesion->update([
            'estado'        => $data['estado'],
            'duracion_real' => $data['duracion_real'],
            'fin'           => Carbon::now(),
        ]);

        return response()->json(['ok' => true]);
    }
}