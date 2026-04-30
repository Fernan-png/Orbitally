<?php

namespace App\Http\Controllers;

use App\Models\Tarea;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    private function categorias()
    {
        return Categoria::where('usuario_id', Auth::id())->orderBy('prioridad')->get();
    }

    public function index(Request $request)
    {
        $query = Auth::user()->tareas()->with('categoria')->orderBy('created_at', 'desc');

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }

        $tasks = $query->get();
        $categories = $this->categorias();
        return view('tasks.index', compact('tasks', 'categorias'));
    }

    public function create()
    {
        $categorias = $this->categorias();
        return view('tasks.form', compact('categorias'));
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->taskRules());

        Auth::user()->tareas()->create(array_merge($data, [
            'estado'     => $data['estado'] ?? 'pendiente',
            'negrita'    => $request->boolean('negrita'),
            'cursiva'    => $request->boolean('cursiva'),
        ]));

        return redirect()->route('tasks.index')->with('success', 'Tarea creada correctamente.');
    }

    public function edit(Tarea $task)
    {
        abort_if($task->usuario_id !== Auth::id(), 403);
        $categorias = $this->categorias();
        return view('tasks.form', compact('task', 'categorias'));
    }

    public function update(Request $request, Tarea $task)
    {
        abort_if($task->usuario_id !== Auth::id(), 403);

        $data = $request->validate($this->taskRules(true));

        $task->update(array_merge($data, [
            'negrita' => $request->boolean('negrita'),
            'cursiva' => $request->boolean('cursiva'),
        ]));

        return redirect()->route('tasks.index')->with('success', 'Tarea actualizada.');
    }

    public function toggle(Tarea $task)
    {
        abort_if($task->usuario_id !== Auth::id(), 403);

        $task->update([
            'estado' => $task->estado === 'completada' ? 'pendiente' : 'completada',
        ]);

        return back()->with('success', 'Estado de la tarea actualizado.');
    }

    public function destroy(Tarea $task)
    {
        abort_if($task->usuario_id !== Auth::id(), 403);
        $task->delete();
        return back()->with('success', 'Tarea eliminada.');
    }

    private function taskRules(bool $update = false): array {
        return [
            'titulo'       => 'required|string|max:150',
            'descripcion'  => 'nullable|string',
            'emoji'        => 'nullable|string|max:4',
            'prioridad'    => 'required|in:baja,media,alta',
            'estado'       => ($update ? 'required' : 'nullable') . '|in:pendiente,en_progreso,completada',
            'categoria_id' => 'nullable|exists:categorias,id',
            'fecha_fin'    => 'nullable|date',
            'negrita'      => 'nullable|boolean',
            'cursiva'      => 'nullable|boolean',
        ];
    }
}