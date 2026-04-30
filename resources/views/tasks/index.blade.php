@extends('layouts.app')
@section('title', 'Tareas')

@section('content')
<div class="page-header" style="display:flex; align-items:flex-end; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
    <div>
        <div class="page-title">Tareas</div>
        <div class="page-subtitle">Gestiona y organiza todas tus tareas</div>
    </div>
    <a href="{{ route('tasks.create') }}" class="btn-primary">+ Nueva tarea</a>
</div>

<!-- Filter tabs -->
<div class="tabs-bar">
    <a href="{{ route('tasks.index') }}" class="tab-btn {{ !request('estado') ? 'active' : '' }}">Todas</a>
    <a href="{{ route('tasks.index', ['estado' => 'pendiente']) }}" class="tab-btn {{ request('estado') === 'pendiente' ? 'active' : '' }}">Pendientes</a>
    <a href="{{ route('tasks.index', ['estado' => 'en_progreso']) }}" class="tab-btn {{ request('estado') === 'en_progreso' ? 'active' : '' }}">En progreso</a>
    <a href="{{ route('tasks.index', ['estado' => 'completada']) }}" class="tab-btn {{ request('estado') === 'completada' ? 'active' : '' }}">Completadas</a>
</div>

@if($categorias->isNotEmpty())
    <div style="display:flex; gap:0.5rem; flex-wrap:wrap; margin-bottom:1.5rem; align-items:center;">
        <span style="font-size:0.7rem; letter-spacing:0.1em; text-transform:uppercase; color:var(--text-dim); margin-right:0.25rem;">Categoría:</span>
        <a href="{{ route('tasks.index', array_merge(request()->except('categoria_id'), [])) }}"
           style="padding:0.3rem 0.8rem; border-radius:2px; font-size:0.75rem; text-decoration:none; border:1px solid var(--border-subtle);
                  {{ !request('categoria_id') ? 'color:#03060f; background:var(--accent-gold); border-color:var(--accent-gold);' : 'color:var(--text-dim); background:transparent;' }}">
            Todas
        </a>
        @foreach($categorias as $cat)
            <a href="{{ route('tasks.index', array_merge(request()->query(), ['categoria_id' => $cat->id])) }}"
               style="padding:0.3rem 0.8rem; border-radius:2px; font-size:0.75rem; text-decoration:none;
                      border:1px solid {{ request('categoria_id') == $cat->id ? $cat->color_borde : 'var(--border-subtle)' }};
                      color:{{ request('categoria_id') == $cat->id ? $cat->color_borde : 'var(--text-dim)' }};
                      background:{{ request('categoria_id') == $cat->id ? $cat->color_borde . '18' : 'transparent' }};">
                {{ $cat->nombre }}
            </a>
        @endforeach
    </div>
@endif

<!-- Tasks list -->
<div class="panel">
    @if($tasks->isEmpty())
        <div style="padding:3rem; text-align:center; color:var(--text-dim);">
            <div style="font-size:2rem; margin-bottom:0.75rem;">🌌</div>
            <div style="font-size:0.9rem; margin-bottom:0.5rem;">No hay tareas aquí</div>
            <a href="{{ route('tasks.create') }}" style="font-size:0.8rem; color:var(--accent-gold); text-decoration:none;">Crear nueva tarea →</a>
        </div>
    @else
        @foreach($tasks as $task)
        <div style="padding:1rem 1.5rem; border-bottom:1px solid var(--border-subtle); display:flex; align-items:center; gap:1rem;
            {{ $task->estado === 'completada' ? 'opacity:0.55;' : '' }}">

            <!-- Complete toggle -->
            <form method="POST" action="{{ route('tasks.toggle', $task->id) }}" style="flex-shrink:0;">
                @csrf
                @method('PATCH')
                <button type="submit" style="
                    width:18px; height:18px; border-radius:50%; cursor:pointer;
                    border:1.5px solid {{ $task->estado === 'completada' ? '#4dcfcf' : 'rgba(255,255,255,0.2)' }};
                    background:{{ $task->estado === 'completada' ? 'rgba(77,207,207,0.2)' : 'transparent' }};
                    display:flex; align-items:center; justify-content:center; font-size:0.6rem;
                    color:#4dcfcf; transition:all 0.2s;
                " title="Marcar como {{ $task->estado === 'completada' ? 'pendiente' : 'completada' }}">
                    @if($task->estado === 'completada') ✓ @endif
                </button>
            </form>

            <!-- Task info -->
            <div style="flex:1; min-width:0;">
                <div style="
                    font-size:0.88rem;
                    font-weight:{{ $task->negrita ? '700' : '400' }};
                    font-style:{{ $task->cursiva ? 'italic' : 'normal' }};
                    color:var(--star-white);
                    text-decoration:{{ $task->estado === 'completada' ? 'line-through' : 'none' }};
                    white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
                ">
                    {{ $task->emoji ? $task->emoji . ' ' : '' }}{{ $task->titulo }}
                </div>
                <div style="font-size:0.72rem; color:var(--text-dim); margin-top:3px; display:flex; gap:1rem; align-items:center; flex-wrap:wrap;">
                    @if($task->categoria)
                        <span>{{ $task->categoria->nombre }}</span>
                    @endif
                    @if($task->fecha_fin)
                        <span>Vence {{ \Carbon\Carbon::parse($task->fecha_fin)->isoFormat('D MMM YYYY') }}</span>
                    @endif
                    @if($task->descripcion)
                        <span style="color:rgba(180,200,240,0.35);">{{ Str::limit($task->descripcion, 40) }}</span>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div style="display:flex; gap:0.5rem; flex-shrink:0;">
                <a href="{{ route('tasks.edit', $task->id) }}" class="btn-secondary" style="padding:0.35rem 0.75rem; font-size:0.72rem;">Editar</a>
                <form method="POST" action="{{ route('tasks.destroy', $task->id) }}" onsubmit="return confirm('¿Eliminar esta tarea?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="
                        padding:0.35rem 0.75rem;
                        font-family:'Jost',sans-serif;
                        font-size:0.72rem;
                        color:rgba(255,100,80,0.6);
                        background:rgba(255,100,80,0.06);
                        border:1px solid rgba(255,100,80,0.15);
                        border-radius:2px;
                        cursor:pointer;
                        transition:all 0.2s;
                    ">Eliminar</button>
                </form>
            </div>
        </div>
        @endforeach
    @endif
</div>
@endsection
