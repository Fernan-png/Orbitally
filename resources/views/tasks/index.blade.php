@extends('layouts.app')
@section('title', 'Tareas')

@section('content')
<div class="page-header-row">
    <div>
        <div class="page-title">Tareas</div>
        <div class="page-subtitle">Gestiona y organiza todas tus tareas</div>
    </div>
    <a href="{{ route('tasks.create') }}" class="btn-primary">
        <i class="fa-regular fa-plus" style="font-size:12px;"></i>
        Nueva tarea
    </a>
</div>

{{-- Filtros de estado --}}
<div class="tabs-bar">
    <a href="{{ route('tasks.index') }}"
       class="tab-btn {{ !request('estado') ? 'active' : '' }}">Todas</a>
    <a href="{{ route('tasks.index', ['estado' => 'pendiente']) }}"
       class="tab-btn {{ request('estado') === 'pendiente' ? 'active' : '' }}">Pendientes</a>
    <a href="{{ route('tasks.index', ['estado' => 'en_progreso']) }}"
       class="tab-btn {{ request('estado') === 'en_progreso' ? 'active' : '' }}">En progreso</a>
    <a href="{{ route('tasks.index', ['estado' => 'completada']) }}"
       class="tab-btn {{ request('estado') === 'completada' ? 'active' : '' }}">Completadas</a>
</div>

{{-- Filtro de categorías --}}
@if($categories->isNotEmpty())
    <div style="display:flex; gap:6px; flex-wrap:wrap; margin-bottom:20px; align-items:center;">
        <span style="font-size:10px; letter-spacing:0.12em; text-transform:uppercase; color:var(--text-dim); margin-right:4px;">
            Categoría:
        </span>
        <a href="{{ route('tasks.index', array_merge(request()->except('categoria_id'), [])) }}"
           style="padding:4px 12px; border-radius:2px; font-size:12px; text-decoration:none;
                  border:1px solid {{ !request('categoria_id') ? 'var(--accent-gold)' : 'var(--border-subtle)' }};
                  color:{{ !request('categoria_id') ? '#ffffff' : 'var(--text-dim)' }};
                  background:{{ !request('categoria_id') ? 'var(--accent-gold)' : 'transparent' }};
                  transition:all 0.2s;">
            Todas
        </a>
        @foreach($categories as $cat)
            <a href="{{ route('tasks.index', array_merge(request()->query(), ['categoria_id' => $cat->id])) }}"
               style="padding:4px 12px; border-radius:2px; font-size:12px; text-decoration:none; transition:all 0.2s;
                      border:1px solid {{ request('categoria_id') == $cat->id ? $cat->color_borde : 'var(--border-subtle)' }};
                      color:{{ request('categoria_id') == $cat->id ? $cat->color_borde : 'var(--text-dim)' }};
                      background:{{ request('categoria_id') == $cat->id ? $cat->color_borde . '18' : 'transparent' }};">
                {{ $cat->nombre }}
            </a>
        @endforeach
    </div>
@endif

{{-- Lista de tareas --}}
<div class="panel">
    @if($tasks->isEmpty())
        <div style="padding:48px; text-align:center; color:var(--text-dim);">
            <div style="font-size:32px; margin-bottom:12px;">🌌</div>
            <div style="font-size:14px; margin-bottom:6px; color:var(--star-white);">No hay tareas aquí</div>
            <div style="font-size:13px; margin-bottom:16px;">Empieza creando tu primera tarea</div>
            <a href="{{ route('tasks.create') }}" class="btn-primary" style="font-size:13px;">
                + Nueva tarea
            </a>
        </div>
    @else
        @foreach($tasks as $task)
        @php
            $prioColors = ['alta' => '#ff8866', 'media' => '#a78bfa', 'baja' => '#4dcfcf'];
            $statusColors = ['completada' => '#4dcfcf', 'en_progreso' => '#88aaff', 'pendiente' => '#a78bfa'];
            $borderColor = $task->categoria->color_borde ?? ($prioColors[$task->prioridad] ?? '#4dcfcf');
        @endphp
        <div id="task-row-{{ $task->id }}"
             style="padding:14px 20px; border-bottom:1px solid var(--border-subtle); display:flex; align-items:center; gap:14px;
                    opacity:{{ $task->estado === 'completada' ? '0.5' : '1' }};
                    transition:opacity 0.3s, background 0.3s;"
             class="hover:bg-black/5 dark:hover:bg-white/5">

            {{-- Borde de prioridad/categoría --}}
            <div style="width:3px; height:40px; border-radius:2px; flex-shrink:0; background:{{ $borderColor }};
                        box-shadow:0 0 8px {{ $borderColor }}44;"></div>

            {{-- Toggle completar --}}
            <button class="task-toggle {{ $task->estado === 'completada' ? 'is-done' : '' }}"
                    data-task-id="{{ $task->id }}"
                    data-estado="{{ $task->estado }}"
                    title="Marcar como {{ $task->estado === 'completada' ? 'pendiente' : 'completada' }}"
                    style="width:18px; height:18px; border-radius:50%; cursor:pointer; flex-shrink:0;
                           border:1.5px solid {{ $task->estado === 'completada' ? '#4dcfcf' : 'rgba(255,255,255,0.18)' }};
                           background:{{ $task->estado === 'completada' ? 'rgba(77,207,207,0.18)' : 'transparent' }};
                           display:flex; align-items:center; justify-content:center;
                           font-size:10px; color:#4dcfcf;">
                @if($task->estado === 'completada') ✓ @endif
            </button>

            {{-- Info de la tarea --}}
            <div style="flex:1; min-width:0;">
                <div class="task-title"
                     style="font-size:14px;
                            font-weight:{{ $task->negrita ? '700' : '400' }};
                            font-style:{{ $task->cursiva ? 'italic' : 'normal' }};
                            color:var(--star-white);
                            text-decoration:{{ $task->estado === 'completada' ? 'line-through' : 'none' }};
                            white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
                            transition:text-decoration 0.2s, opacity 0.2s;">
                    {{ $task->emoji ? $task->emoji . ' ' : '' }}{{ $task->titulo }}
                </div>
                <div style="font-size:12px; color:var(--text-dim); margin-top:3px; display:flex; gap:14px; align-items:center; flex-wrap:wrap;">
                    {{-- Badge estado (solo visual) --}}
                    <span style="display:inline-flex; align-items:center; gap:4px;">
                        <span id="dot-{{ $task->id }}"
                              style="width:6px; height:6px; border-radius:50%;
                                     background:{{ $statusColors[$task->estado] ?? '#a78bfa' }};
                                     box-shadow:0 0 5px {{ $statusColors[$task->estado] ?? '#a78bfa' }}66;
                                     transition:background 0.25s, box-shadow 0.25s;"></span>
                        <span id="status-label-{{ $task->id }}">
                            {{ ['pendiente' => 'Pendiente', 'en_progreso' => 'En progreso', 'completada' => 'Completada'][$task->estado] ?? $task->estado }}
                        </span>
                    </span>
                    @if($task->categoria)
                        <span style="color:{{ $task->categoria->color_borde }}; opacity:0.85;">
                            {{ $task->categoria->nombre }}
                        </span>
                    @endif
                    @if($task->fecha_fin)
                        <span>Vence {{ \Carbon\Carbon::parse($task->fecha_fin)->isoFormat('D MMM YYYY') }}</span>
                    @endif
                    @if($task->descripcion)
                        <span style="color:var(--text-muted);">{{ Str::limit($task->descripcion, 40) }}</span>
                    @endif
                </div>
            </div>

            {{-- Acciones --}}
            <div style="display:flex; gap:6px; flex-shrink:0; align-items:center;">
                <div style="position:relative; display:inline-flex; align-items:center;">
                    <select class="status-select" data-task-id="{{ $task->id }}"
                            style="font-size:11px; letter-spacing:0.03em;
                                   border:1px solid {{ $statusColors[$task->estado] }}55;
                                   color:{{ $statusColors[$task->estado] }};
                                   padding:5px 26px 5px 10px;">
                        <option value="pendiente"   {{ $task->estado === 'pendiente'   ? 'selected' : '' }}>Pendiente</option>
                        <option value="en_progreso" {{ $task->estado === 'en_progreso' ? 'selected' : '' }}>En progreso</option>
                        <option value="completada"  {{ $task->estado === 'completada'  ? 'selected' : '' }}>Completada</option>
                    </select>
                    <i class="fa-solid fa-chevron-down"
                       style="position:absolute; right:9px; font-size:7px;
                              color:rgba(160,160,200,0.65); pointer-events:none;"></i>
                </div>
                <a href="{{ route('tasks.edit', $task->id) }}"
                   class="btn-secondary" style="padding:5px 12px; font-size:12px;">
                    Editar
                </a>
                <form method="POST" action="{{ route('tasks.destroy', $task->id) }}"
                      data-confirm="¿Eliminar esta tarea? Esta acción no se puede deshacer.">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
        @endforeach
    @endif
</div>
@endsection