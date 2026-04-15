@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
{{-- Encabezado --}}
<div class="page-header" style="display:flex; align-items:flex-end; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
    <div>
        <div class="page-title">Panel de Control</div>
        <div class="page-subtitle">Bienvenido, {{ Auth::user()->nombre }} — {{ now()->isoFormat('dddd, D [de] MMMM YYYY') }}</div>
    </div>
    <a href="{{ route('tasks.create') }}" class="btn-primary">+ Nueva tarea</a>
</div>

{{-- Fila de Estadísticas --}}
<div style="display:grid; grid-template-columns:repeat(3,1fr); gap:1rem; margin-bottom:2rem;">
    @foreach([
        ['Pendientes', $stats['pendientes'], '#ffcc55'],
        ['En progreso', $stats['en_progreso'], '#88aaff'],
        ['Completadas', $stats['completadas'], '#4dcfcf']
    ] as [$label, $count, $color])
        <div class="panel" style="padding:1.25rem 1.5rem;">
            <div style="font-size:0.7rem; letter-spacing:0.1em; text-transform:uppercase; color:var(--text-dim); margin-bottom:0.5rem;">{{ $label }}</div>
            <div style="font-size:2rem; font-family:'Cinzel',serif; font-weight:600; color:{{ $color }};">{{ $count }}</div>
        </div>
    @endforeach
</div>

<div style="display:grid; grid-template-columns:1fr 340px; gap:1.5rem;">

    {{-- Tareas Recientes --}}
    <div class="panel">
        <div style="padding:1.25rem 1.5rem; border-bottom:1px solid var(--border-subtle); display:flex; justify-content:space-between; align-items:center;">
            <span style="font-size:0.8rem; font-weight:500; letter-spacing:0.06em;">Tareas recientes</span>
            <a href="{{ route('tasks.index') }}" style="font-size:0.72rem; color:var(--accent-gold); text-decoration:none;">Ver todas →</a>
        </div>

        @forelse($recentTasks as $task)
            @php
                $statusColors = ['completada' => '#4dcfcf', 'en_progreso' => '#88aaff', 'pendiente' => '#ffcc55'];
                $dotColor = $statusColors[$task->estado] ?? '#ffcc55';
            @endphp
            <div style="padding:0.9rem 1.5rem; border-bottom:1px solid var(--border-subtle); display:flex; align-items:center; gap:1rem;">
                <div style="width:8px; height:8px; border-radius:50%; flex-shrink:0; background:{{ $dotColor }}; box-shadow:0 0 6px 2px {{ $dotColor }}30;"></div>
                
                <div style="flex:1; min-width:0;">
                    <div style="font-size:0.85rem; color:var(--star-white); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
                                font-weight:{{ $task->negrita ? '700' : '400' }}; font-style:{{ $task->cursiva ? 'italic' : 'normal' }};">
                        {{ $task->emoji ? $task->emoji . ' ' : '' }}{{ $task->titulo }}
                    </div>
                    <div style="font-size:0.72rem; color:var(--text-dim); margin-top:2px;">
                        {{ $task->categoria->nombre ?? '—' }}
                        @if($task->fecha_fin) · vence {{ \Carbon\Carbon::parse($task->fecha_fin)->isoFormat('D MMM') }} @endif
                    </div>
                </div>
                
                <span class="badge badge-{{ $task->prioridad }}">{{ $task->prioridad }}</span>
                <a href="{{ route('tasks.edit', $task->id) }}" style="font-size:0.72rem; color:var(--text-dim); text-decoration:none; flex-shrink:0;">editar</a>
            </div>
        @empty
            <div style="padding:2rem 1.5rem; text-align:center; color:var(--text-dim); font-size:0.82rem;">
                No hay tareas todavía.<br>
                <a href="{{ route('tasks.create') }}" style="color:var(--accent-gold); text-decoration:none;">Crea tu primera tarea →</a>
            </div>
        @endforelse
    </div>

    {{-- Mini Calendario y Próximas Tareas --}}
    <aside class="panel" style="padding:1.25rem 1.5rem;">
        <div style="font-size:0.8rem; font-weight:500; letter-spacing:0.06em; margin-bottom:1.25rem; display:flex; justify-content:space-between; align-items:center;">
            <span>Calendario</span>
            <a href="{{ route('calendar') }}" style="font-size:0.72rem; color:var(--accent-gold); text-decoration:none;">Ver mes →</a>
        </div>

        @php
            $today = now();
            $taskDates = $upcomingTasks->pluck('fecha_fin')->filter()->map(fn($d) => \Carbon\Carbon::parse($d)->format('Y-m-d'))->toArray();
            $firstDay = $today->copy()->startOfMonth()->dayOfWeek;
        @endphp

        <div style="font-size:0.75rem; color:var(--text-dim); text-align:center; margin-bottom:0.75rem; letter-spacing:0.05em;">
            {{ $today->isoFormat('MMMM YYYY') }}
        </div>

        <div style="display:grid; grid-template-columns:repeat(7,1fr); text-align:center; margin-bottom:0.5rem;">
            @foreach(['D','L','M','X','J','V','S'] as $d)
                <div style="font-size:0.65rem; color:var(--text-dim); padding:2px 0;">{{ $d }}</div>
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
                <div style="padding:4px 2px; font-size:0.75rem; border-radius:2px; position:relative;
                            font-weight:{{ $isToday ? '600' : '400' }};
                            background:{{ $isToday ? 'var(--accent-gold)' : 'transparent' }};
                            color:{{ $isToday ? '#03060f' : ($hasTask ? 'var(--star-white)' : 'rgba(180,200,240,0.5)') }};">
                    {{ $day }}
                    @if($hasTask && !$isToday)
                        <span style="position:absolute; bottom:1px; left:50%; transform:translateX(-50%); width:4px; height:4px; border-radius:50%; background:#4dcfcf;"></span>
                    @endif
                </div>
            @endfor
        </div>

        {{-- Próximas Tareas (Debajo del calendario) --}}
        @if($upcomingTasks->isNotEmpty())
            <div style="margin-top:1.25rem; padding-top:1.25rem; border-top:1px solid var(--border-subtle);">
                <div style="font-size:0.7rem; letter-spacing:0.1em; text-transform:uppercase; color:var(--text-dim); margin-bottom:0.75rem;">Próximas</div>
                @foreach($upcomingTasks->take(3) as $task)
                    <div style="display:flex; align-items:center; gap:0.6rem; margin-bottom:0.6rem;">
                        <div style="width:3px; height:28px; border-radius:2px; flex-shrink:0;
                                    background:{{ $task->prioridad === 'alta' ? '#ff8866' : ($task->prioridad === 'media' ? '#ffcc55' : '#4dcfcf') }};"></div>
                        <div>
                            <div style="font-size:0.78rem; color:var(--star-white); line-height:1.3;">{{ Str::limit($task->titulo, 25) }}</div>
                            <div style="font-size:0.68rem; color:var(--text-dim);">{{ \Carbon\Carbon::parse($task->fecha_fin)->isoFormat('D MMM') }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </aside>
</div>
@endsection