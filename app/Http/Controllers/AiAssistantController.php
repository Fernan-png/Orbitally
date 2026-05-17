<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class AiAssistantController extends Controller
{
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'history' => 'array|max:40',
            'history.*.role'    => 'required|in:user,assistant',
            'history.*.content' => 'required|string|max:4000',
        ]);

        $user  = Auth::user();
        $tareas = $user->tareas()->with('categoria')->latest()->take(15)->get()
            ->map(fn($t) => $t->titulo . ' [' . $t->estado . ', prioridad ' . $t->prioridad . ', categoría: ' . ($t->categoria->nombre ?? 'ninguna') . ']')
            ->implode(' | ');

        $stats = $user->tareas()
            ->selectRaw("estado, count(*) as total")
            ->groupBy('estado')
            ->pluck('total', 'estado');

        $system = "Eres Orbi, asistente de productividad de Orbitally. Responde siempre en español, de forma breve y amigable.
Ayuda al usuario a organizar tareas, planificarlas, clasificarlas y con dudas de la app (secciones: Dashboard, Tareas, Calendario, Categorías, Pomodoro).
Usuario: {$user->nombre} | Pendientes: {$stats->get('pendiente',0)} | En progreso: {$stats->get('en_progreso',0)} | Completadas: {$stats->get('completada',0)}
Últimas tareas: {$tareas}";

        $response = Http::withoutVerifying()
            ->withToken(config('services.groq.api_key'))
            ->post(config('services.groq.url'), [
                'model'    => config('services.groq.model'),
                'messages' => array_merge(
                    [['role' => 'system', 'content' => $system]],
                    $request->input('history', []),
                    [['role' => 'user', 'content' => $request->message]]
                ),
                'max_tokens'  => 500,
                'temperature' => 0.7,
            ]);

        if ($response->failed()) {
            return response()->json(['error' => 'No se pudo conectar con el asistente.'], 500);
        }

        return response()->json([
            'reply' => $response->json('choices.0.message.content', 'Sin respuesta.'),
        ]);
    }
}
