@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')

{{-- Encabezado --}}
<div class="page-header-row">
    <div>
        <div class="page-title">Panel de Control</div>
        <div class="page-subtitle">
            Bienvenido, {{ Auth::user()->nombre }} — {{ now()->isoFormat('dddd, D [de] MMMM YYYY') }}
        </div>
    </div>
    <a href="{{ route('tasks.create') }}" class="btn-primary">
        <i class="fa-regular fa-plus" style="font-size:12px;"></i>
        Nueva tarea
    </a>
</div>

{{-- Stats --}}
<div style="display:grid; grid-template-columns:repeat(3,1fr); gap:14px; margin-bottom:24px;">
    @foreach([
        ['Pendientes',  $stats['pendientes'],  '#a78bfa', 'fa-regular fa-hourglass'],
        ['En progreso', $stats['en_progreso'], '#88aaff', 'fa-solid fa-arrows-rotate'],
        ['Completadas', $stats['completadas'], '#4dcfcf', 'fa-regular fa-circle-check'],
    ] as [$label, $count, $color, $icon])
    <div class="panel" style="padding:20px 22px; display:flex; align-items:center; gap:16px;">
        <div style="width:40px; height:40px; border-radius:3px; flex-shrink:0;
                    background:{{ $color }}14; border:1px solid {{ $color }}30;
                    display:flex; align-items:center; justify-content:center;">
            <i class="{{ $icon }}" style="font-size:16px; color:{{ $color }};"></i>
        </div>
        <div>
            <div style="font-size:11px; letter-spacing:0.1em; text-transform:uppercase;
                        color:var(--text-dim); margin-bottom:4px;">{{ $label }}</div>
            <div style="font-size:28px; font-family:'Cinzel',serif; font-weight:600; color:{{ $color }};
                        line-height:1;">{{ $count }}</div>
        </div>
    </div>
    @endforeach
</div>

<div style="display:grid; grid-template-columns:1fr 320px; gap:20px;">

    {{-- Tareas recientes --}}
    <div class="panel">
        <div style="padding:16px 20px; border-bottom:1px solid var(--border-subtle);
                    display:flex; justify-content:space-between; align-items:center;">
            <span style="font-size:12px; font-weight:500; letter-spacing:0.06em; text-transform:uppercase;
                         color:var(--text-dim);">
                Tareas recientes
            </span>
            <a href="{{ route('tasks.index') }}"
               style="font-size:12px; color:var(--accent-gold); text-decoration:none; transition:opacity 0.2s;"
               onmouseover="this.style.opacity='0.7'" onmouseout="this.style.opacity='1'">
                Ver todas →
            </a>
        </div>

        @forelse($recentTasks as $task)
        @php
            $statusColors = ['completada' => '#4dcfcf', 'en_progreso' => '#88aaff', 'pendiente' => '#a78bfa'];
            $dotColor = $statusColors[$task->estado] ?? '#a78bfa';
            $borderColor = $task->categoria->color_borde ?? $dotColor;
        @endphp
        <div style="padding:12px 20px; border-bottom:1px solid var(--border-subtle);
                    display:flex; align-items:center; gap:12px; transition:background 0.2s;"
             class="hover:bg-black/5 dark:hover:bg-white/[0.03]--">

            <div style="width:3px; height:36px; border-radius:2px; flex-shrink:0;
                        background:{{ $borderColor }}; opacity:{{ $task->estado === 'completada' ? '0.35' : '0.9' }};"></div>

            <div style="flex:1; min-width:0;">
                <div style="font-size:14px; color:var(--star-white);
                            font-weight:{{ $task->negrita ? '700' : '400' }};
                            font-style:{{ $task->cursiva ? 'italic' : 'normal' }};
                            text-decoration:{{ $task->estado === 'completada' ? 'line-through' : 'none' }};
                            white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
                            opacity:{{ $task->estado === 'completada' ? '0.5' : '1' }};">
                    {{ $task->emoji ? $task->emoji . ' ' : '' }}{{ $task->titulo }}
                </div>
                <div style="font-size:12px; color:var(--text-dim); margin-top:3px; display:flex; gap:12px;">
                    <span style="display:inline-flex; align-items:center; gap:4px;">
                        <span style="width:5px; height:5px; border-radius:50%; background:{{ $dotColor }};"></span>
                        {{ ['pendiente' => 'Pendiente', 'en_progreso' => 'En progreso', 'completada' => 'Completada'][$task->estado] ?? $task->estado }}
                    </span>
                    <span>{{ $task->categoria->nombre ?? '—' }}</span>
                    @if($task->fecha_fin)
                        <span>vence {{ \Carbon\Carbon::parse($task->fecha_fin)->isoFormat('D MMM') }}</span>
                    @endif
                </div>
            </div>

            <a href="{{ route('tasks.edit', $task->id) }}"
               style="font-size:12px; color:var(--text-dim); text-decoration:none; flex-shrink:0;
                      padding:4px 10px; border:1px solid var(--border-subtle); border-radius:2px; transition:all 0.2s;"
               onmouseover="this.style.color='var(--star-white)'; this.style.borderColor='rgba(128,128,128,0.2)'"
               onmouseout="this.style.color='var(--text-dim)'; this.style.borderColor='var(--border-subtle)'">
                editar
            </a>
        </div>
        @empty
        <div style="padding:40px; text-align:center; color:var(--text-dim);">
            <div style="font-size:28px; margin-bottom:10px;">🌌</div>
            <div style="font-size:14px; margin-bottom:14px;">No hay tareas todavía.</div>
            <a href="{{ route('tasks.create') }}" class="btn-primary">Crea tu primera tarea →</a>
        </div>
        @endforelse
    </div>

    {{-- Sidebar: mini calendario + próximas --}}
    <aside>
        <div class="panel" style="padding:20px;">
            <div style="font-size:12px; font-weight:500; letter-spacing:0.06em; margin-bottom:16px;
                        display:flex; justify-content:space-between; align-items:center;">
                <span style="text-transform:uppercase; font-size:11px; color:var(--text-dim); letter-spacing:0.1em;">
                    Calendario
                </span>
                <a href="{{ route('calendar') }}"
                   style="font-size:12px; color:var(--accent-gold); text-decoration:none; transition:opacity 0.2s;"
                   onmouseover="this.style.opacity='0.7'" onmouseout="this.style.opacity='1'">
                    Ver mes →
                </a>
            </div>

            @php
                $today = now();
                $taskDates = $upcomingTasks->pluck('fecha_fin')->filter()
                    ->map(fn($d) => \Carbon\Carbon::parse($d)->format('Y-m-d'))->toArray();
                $firstDay = ($today->copy()->startOfMonth()->dayOfWeek + 6) % 7;
            @endphp

            <div style="font-size:12px; color:var(--text-dim); text-align:center; margin-bottom:10px; letter-spacing:0.05em;">
                {{ $today->isoFormat('MMMM YYYY') }}
            </div>

            <div style="display:grid; grid-template-columns:repeat(7,1fr); text-align:center; margin-bottom:6px;">
                @foreach(['L','M','X','J','V','S','D'] as $d)
                    <div style="font-size:10px; color:var(--text-muted); padding:2px 0;">{{ $d }}</div>
                @endforeach
            </div>

            <div style="display:grid; grid-template-columns:repeat(7,1fr); text-align:center; gap:2px;">
                @for($i = 0; $i < $firstDay; $i++) <div></div> @endfor
                @for($day = 1; $day <= $today->daysInMonth; $day++)
                @php
                    $dateStr = $today->copy()->day($day)->format('Y-m-d');
                    $isToday = $day === $today->day;
                    $hasTask = in_array($dateStr, $taskDates);
                @endphp
                <div style="padding:4px 2px; font-size:12px; border-radius:2px; position:relative;
                            font-weight:{{ $isToday ? '600' : '400' }};
                            background:{{ $isToday ? 'var(--accent-gold)' : 'transparent' }};
                            color:{{ $isToday ? '#ffffff' : ($hasTask ? 'var(--star-white)' : 'var(--text-muted)') }};">
                    {{ $day }}
                    @if($hasTask && !$isToday)
                        <span style="position:absolute; bottom:1px; left:50%; transform:translateX(-50%);
                                     width:4px; height:4px; border-radius:50%; background:#4dcfcf;"></span>
                    @endif
                </div>
                @endfor
            </div>

            {{-- Próximas tareas --}}
            @if($upcomingTasks->isNotEmpty())
            <div style="margin-top:18px; padding-top:18px; border-top:1px solid var(--border-subtle);">
                <div style="font-size:10px; letter-spacing:0.12em; text-transform:uppercase;
                            color:var(--text-dim); margin-bottom:12px;">Próximas</div>
                @foreach($upcomingTasks->take(3) as $task)
                <div style="display:flex; align-items:center; gap:8px; margin-bottom:10px;">
                    <div style="width:3px; height:28px; border-radius:2px; flex-shrink:0;
                                background:{{ $task->prioridad === 'alta' ? '#ff8866' : ($task->prioridad === 'media' ? '#a78bfa' : '#4dcfcf') }};"></div>
                    <div>
                        <div style="font-size:13px; color:var(--star-white); line-height:1.3;">
                            {{ Str::limit($task->titulo, 24) }}
                        </div>
                        <div style="font-size:11px; color:var(--text-dim);">
                            {{ \Carbon\Carbon::parse($task->fecha_fin)->isoFormat('D MMM') }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </aside>
</div>
@endsection 