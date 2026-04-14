@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="page-header" style="display:flex; align-items:flex-end; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
    <div>
        <div class="page-title">Panel de Control</div>
        <div class="page-subtitle">Bienvenido, {{ Auth::user()->nombre }} — {{ now()->isoFormat('dddd, D [de] MMMM YYYY') }}</div>
    </div>
    <a href="{{ route('tasks.create') }}" class="btn-primary">
        + Nueva tarea
    </a>
</div>

<!-- Stats row -->
<div style="display:grid; grid-template-columns:repeat(3,1fr); gap:1rem; margin-bottom:2rem;">
    <div class="panel" style="padding:1.25rem 1.5rem;">
        <div style="font-size:0.7rem; letter-spacing:0.1em; text-transform:uppercase; color:var(--text-dim); margin-bottom:0.5rem;">Pendientes</div>
        <div style="font-size:2rem; font-family:'Cinzel',serif; font-weight:600; color:#ffcc55;">{{ $stats['pendientes'] }}</div>
    </div>
    <div class="panel" style="padding:1.25rem 1.5rem;">
        <div style="font-size:0.7rem; letter-spacing:0.1em; text-transform:uppercase; color:var(--text-dim); margin-bottom:0.5rem;">En progreso</div>
        <div style="font-size:2rem; font-family:'Cinzel',serif; font-weight:600; color:#88aaff;">{{ $stats['en_progreso'] }}</div>
    </div>
    <div class="panel" style="padding:1.25rem 1.5rem;">
        <div style="font-size:0.7rem; letter-spacing:0.1em; text-transform:uppercase; color:var(--text-dim); margin-bottom:0.5rem;">Completadas</div>
        <div style="font-size:2rem; font-family:'Cinzel',serif; font-weight:600; color:#4dcfcf;">{{ $stats['completadas'] }}</div>
    </div>
</div>

<!-- Two-column: tasks + mini calendar -->
<div style="display:grid; grid-template-columns:1fr 340px; gap:1.5rem;">

    <!-- Recent tasks -->
    <div class="panel">
        <div style="padding:1.25rem 1.5rem; border-bottom:1px solid var(--border-subtle); display:flex; justify-content:space-between; align-items:center;">
            <span style="font-size:0.8rem; font-weight:500; letter-spacing:0.06em;">Tareas recientes</span>
            <a href="{{ route('tasks.index') }}" style="font-size:0.72rem; color:var(--accent-gold); text-decoration:none;">Ver todas →</a>
        </div>
        @if($recentTasks->isEmpty())
            <div style="padding:2rem 1.5rem; text-align:center; color:var(--text-dim); font-size:0.82rem;">
                No hay tareas todavía.<br>
                <a href="{{ route('tasks.create') }}" style="color:var(--accent-gold); text-decoration:none;">Crea tu primera tarea →</a>
            </div>
        @else
            @foreach($recentTasks as $task)
            <div style="padding:0.9rem 1.5rem; border-bottom:1px solid var(--border-subtle); display:flex; align-items:center; gap:1rem;">
                <!-- Status indicator -->
                <div style="width:8px; height:8px; border-radius:50%; flex-shrink:0;
                    background:{{ $task->estado === 'completada' ? '#4dcfcf' : ($task->estado === 'en_progreso' ? '#88aaff' : '#ffcc55') }};
                    box-shadow:0 0 6px 2px {{ $task->estado === 'completada' ? '#4dcfcf30' : ($task->estado === 'en_progreso' ? '#88aaff30' : '#ffcc5530') }};"></div>
                <div style="flex:1; min-width:0;">
                    <div style="font-size:0.85rem; font-weight:{{ $task->negrita ? '700' : '400' }}; font-style:{{ $task->cursiva ? 'italic' : 'normal' }}; color:var(--star-white); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                        {{ $task->emoji ? $task->emoji . ' ' : '' }}{{ $task->titulo }}
                    </div>
                    <div style="font-size:0.72rem; color:var(--text-dim); margin-top:2px;">
                        {{ $task->categoria ? $task->categoria->nombre : '—' }}
                        @if($task->fecha_fin)
                            · vence {{ \Carbon\Carbon::parse($task->fecha_fin)->isoFormat('D MMM') }}
                        @endif
                    </div>
                </div>
                <span class="badge badge-{{ $task->prioridad }}">{{ $task->prioridad }}</span>
                <a href="{{ route('tasks.edit', $task->id) }}" style="font-size:0.72rem; color:var(--text-dim); text-decoration:none; flex-shrink:0;">editar</a>
            </div>
            @endforeach
        @endif
    </div>

    <!-- Mini calendar -->
    <div class="panel" style="padding:1.25rem 1.5rem;">
        <div style="font-size:0.8rem; font-weight:500; letter-spacing:0.06em; margin-bottom:1.25rem; display:flex; justify-content:space-between; align-items:center;">
            <span>Calendario</span>
            <a href="{{ route('calendar') }}" style="font-size:0.72rem; color:var(--accent-gold); text-decoration:none;">Ver mes →</a>
        </div>

        @php
            $today = \Carbon\Carbon::today();
            $startOfMonth = $today->copy()->startOfMonth();
            $endOfMonth = $today->copy()->endOfMonth();
            $daysInMonth = $today->daysInMonth;
            $firstDayOfWeek = $startOfMonth->dayOfWeek; // 0=Sun
            $taskDates = $upcomingTasks->pluck('fecha_fin')->filter()->map(fn($d) => \Carbon\Carbon::parse($d)->format('Y-m-d'))->toArray();
        @endphp

        <div style="font-size:0.75rem; color:var(--text-dim); text-align:center; margin-bottom:0.75rem; letter-spacing:0.05em;">
            {{ $today->isoFormat('MMMM YYYY') }}
        </div>

        <!-- Day headers -->
        <div style="display:grid; grid-template-columns:repeat(7,1fr); text-align:center; margin-bottom:0.5rem;">
            @foreach(['D','L','M','X','J','V','S'] as $day)
                <div style="font-size:0.65rem; color:var(--text-dim); padding:2px 0; letter-spacing:0.05em;">{{ $day }}</div>
            @endforeach
        </div>

        <!-- Days grid -->
        <div style="display:grid; grid-template-columns:repeat(7,1fr); text-align:center; gap:2px;">
            {{-- Empty cells before first day --}}
            @for($i = 0; $i < $firstDayOfWeek; $i++)
                <div></div>
            @endfor

            @for($day = 1; $day <= $daysInMonth; $day++)
                @php
                    $dateStr = $today->copy()->day($day)->format('Y-m-d');
                    $isToday = $day === $today->day;
                    $hasTask = in_array($dateStr, $taskDates);
                @endphp
                <div style="
                    padding:4px 2px;
                    font-size:0.75rem;
                    border-radius:2px;
                    position:relative;
                    color:{{ $isToday ? '#03060f' : ($hasTask ? 'var(--star-white)' : 'rgba(180,200,240,0.5)') }};
                    background:{{ $isToday ? 'var(--accent-gold)' : 'transparent' }};
                    font-weight:{{ $isToday ? '600' : '400' }};
                ">
                    {{ $day }}
                    @if($hasTask && !$isToday)
                        <span style="position:absolute;bottom:1px;left:50%;transform:translateX(-50%);width:4px;height:4px;border-radius:50%;background:#4dcfcf;display:block;"></span>
                    @endif
                </div>
            @endfor
        </div>

        <!-- Upcoming tasks -->
        @if($upcomingTasks->isNotEmpty())
        <div style="margin-top:1.25rem; padding-top:1.25rem; border-top:1px solid var(--border-subtle);">
            <div style="font-size:0.7rem; letter-spacing:0.1em; text-transform:uppercase; color:var(--text-dim); margin-bottom:0.75rem;">Próximas</div>
            @foreach($upcomingTasks->take(3) as $task)
            <div style="display:flex; align-items:center; gap:0.6rem; margin-bottom:0.6rem;">
                <div style="width:3px; height:28px; border-radius:2px; flex-shrink:0;
                    background:{{ $task->prioridad === 'alta' ? '#ff8866' : ($task->prioridad === 'media' ? '#ffcc55' : '#4dcfcf') }};"></div>
                <div>
                    <div style="font-size:0.78rem; color:var(--star-white); line-height:1.3;">{{ Str::limit($task->titulo, 25) }}</div>
                    <div style="font-size:0.68rem; color:var(--text-dim);">
                        {{ \Carbon\Carbon::parse($task->fecha_fin)->isoFormat('D MMM') }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endsection
