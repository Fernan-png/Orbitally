@extends('layouts.app')
@section('title', 'Tareas')

@section('content')
<div class="page-header" style="display:flex; align-items:flex-end; justify-content:space-between; flex-wrap:wrap; gap:16px;">
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
                  color:{{ !request('categoria_id') ? '#03060f' : 'var(--text-dim)' }};
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
            $prioColors = ['alta' => '#ff8866', 'media' => '#ffcc55', 'baja' => '#4dcfcf'];
            $statusColors = ['completada' => '#4dcfcf', 'en_progreso' => '#88aaff', 'pendiente' => '#ffcc55'];
            $borderColor = $task->categoria->color_borde ?? ($prioColors[$task->prioridad] ?? '#4dcfcf');
        @endphp
        <div style="padding:14px 20px; border-bottom:1px solid var(--border-subtle); display:flex; align-items:center; gap:14px;
                    opacity:{{ $task->estado === 'completada' ? '0.5' : '1' }};
                    transition:opacity 0.2s, background 0.2s;"
             class="hover:bg-black/5 dark:hover:bg-white/5">

            {{-- Borde de prioridad/categoría --}}
            <div style="width:3px; height:40px; border-radius:2px; flex-shrink:0; background:{{ $borderColor }};
                        box-shadow:0 0 8px {{ $borderColor }}44;"></div>

            {{-- Toggle completar --}}
            <form method="POST" action="{{ route('tasks.toggle', $task->id) }}" style="flex-shrink:0;">
                @csrf
                @method('PATCH')
                <button type="submit" title="Marcar como {{ $task->estado === 'completada' ? 'pendiente' : 'completada' }}"
                        style="width:18px; height:18px; border-radius:50%; cursor:pointer; flex-shrink:0;
                               border:1.5px solid {{ $task->estado === 'completada' ? '#4dcfcf' : 'rgba(255,255,255,0.18)' }};
                               background:{{ $task->estado === 'completada' ? 'rgba(77,207,207,0.18)' : 'transparent' }};
                               display:flex; align-items:center; justify-content:center;
                               font-size:10px; color:#4dcfcf; transition:all 0.2s;">
                    @if($task->estado === 'completada') ✓ @endif
                </button>
            </form>

            {{-- Info de la tarea --}}
            <div style="flex:1; min-width:0;">
                <div style="font-size:14px;
                            font-weight:{{ $task->negrita ? '700' : '400' }};
                            font-style:{{ $task->cursiva ? 'italic' : 'normal' }};
                            color:var(--star-white);
                            text-decoration:{{ $task->estado === 'completada' ? 'line-through' : 'none' }};
                            white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                    {{ $task->emoji ? $task->emoji . ' ' : '' }}{{ $task->titulo }}
                </div>
                <div style="font-size:12px; color:var(--text-dim); margin-top:3px; display:flex; gap:14px; align-items:center; flex-wrap:wrap;">
                    {{-- Badge estado --}}
                    <span style="display:inline-flex; align-items:center; gap:4px;">
                        <span style="width:6px; height:6px; border-radius:50%;
                                     background:{{ $statusColors[$task->estado] ?? '#ffcc55' }};
                                     box-shadow:0 0 5px {{ $statusColors[$task->estado] ?? '#ffcc55' }}66;"></span>
                        {{ ['pendiente' => 'Pendiente', 'en_progreso' => 'En progreso', 'completada' => 'Completada'][$task->estado] ?? $task->estado }}
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
            <div style="display:flex; gap:6px; flex-shrink:0;">
                <a href="{{ route('tasks.edit', $task->id) }}"
                   class="btn-secondary" style="padding:5px 12px; font-size:12px;">
                    Editar
                </a>
                <form method="POST" action="{{ route('tasks.destroy', $task->id) }}"
                      onsubmit="return confirm('¿Eliminar esta tarea?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            style="padding:5px 12px; font-family:'Jost',sans-serif; font-size:12px;
                                   color:rgba(255,100,80,0.65); background:rgba(255,100,80,0.05);
                                   border:1px solid rgba(255,100,80,0.15); border-radius:3px; cursor:pointer;
                                   transition:all 0.2s;"
                            onmouseover="this.style.color='#ff6644'; this.style.background='rgba(255,100,80,0.1)';"
                            onmouseout="this.style.color='rgba(255,100,80,0.65)'; this.style.background='rgba(255,100,80,0.05)';">
                        Eliminar
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    @endif
</div>
@endsection