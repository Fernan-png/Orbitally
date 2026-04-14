@extends('layouts.app')
@section('title', 'Calendario')

@section('content')
<div class="page-header" style="display:flex; align-items:flex-end; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
    <div>
        <div class="page-title">Calendario</div>
        <div class="page-subtitle">Vista mensual de tus tareas</div>
    </div>
    <div style="display:flex; gap:0.5rem; align-items:center;">
        <a href="{{ route('calendar', ['month' => $prevMonth, 'year' => $prevYear]) }}" class="btn-secondary" style="padding:0.5rem 1rem;">← Anterior</a>
        <span style="font-size:0.85rem; font-family:'Cinzel',serif; letter-spacing:0.08em; padding:0 0.5rem; color:var(--star-white);">
            {{ $currentDate->isoFormat('MMMM YYYY') }}
        </span>
        <a href="{{ route('calendar', ['month' => $nextMonth, 'year' => $nextYear]) }}" class="btn-secondary" style="padding:0.5rem 1rem;">Siguiente →</a>
    </div>
</div>

<div style="display:grid; grid-template-columns:1fr 280px; gap:1.5rem; align-items:start;">

    <!-- Calendar grid -->
    <div class="panel" style="overflow:hidden;">
        <!-- Day names header -->
        <div style="display:grid; grid-template-columns:repeat(7,1fr); border-bottom:1px solid var(--border-subtle);">
            @foreach(['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'] as $dayName)
                <div style="padding:0.75rem 0.5rem; text-align:center; font-size:0.7rem; letter-spacing:0.08em; text-transform:uppercase; color:var(--text-dim);">
                    {{ substr($dayName, 0, 3) }}
                </div>
            @endforeach
        </div>

        <!-- Days grid -->
        <div style="display:grid; grid-template-columns:repeat(7,1fr);">
            @php
                $today = \Carbon\Carbon::today();
                $dayCount = 0;
            @endphp

            {{-- Empty cells --}}
            @for($i = 0; $i < $firstDayOfWeek; $i++)
                <div style="min-height:100px; border-bottom:1px solid var(--border-subtle); border-right:1px solid var(--border-subtle); padding:0.5rem; background:rgba(0,0,0,0.1);"></div>
                @php $dayCount++ @endphp
            @endfor

            @for($day = 1; $day <= $daysInMonth; $day++)
                @php
                    $dateStr = $currentDate->copy()->day($day)->format('Y-m-d');
                    $isToday = $currentDate->copy()->day($day)->isSameDay($today);
                    $dayTasks = $tasksByDate[$dateStr] ?? collect();
                    $isLastCol = ($dayCount % 7 === 6);
                    $dayCount++;
                @endphp
                <div style="
                    min-height:100px;
                    border-bottom:1px solid var(--border-subtle);
                    {{ !$isLastCol ? 'border-right:1px solid var(--border-subtle);' : '' }}
                    padding:0.5rem;
                    background:{{ $isToday ? 'rgba(201,168,76,0.05)' : 'transparent' }};
                    vertical-align:top;
                ">
                    <div style="
                        display:inline-flex; align-items:center; justify-content:center;
                        width:24px; height:24px;
                        border-radius:50%;
                        font-size:0.78rem;
                        font-weight:{{ $isToday ? '600' : '400' }};
                        color:{{ $isToday ? '#03060f' : 'rgba(180,200,240,0.6)' }};
                        background:{{ $isToday ? 'var(--accent-gold)' : 'transparent' }};
                        margin-bottom:0.35rem;
                    ">{{ $day }}</div>

                    @foreach($dayTasks->take(3) as $task)
                        <a href="{{ route('tasks.edit', $task->id) }}" style="
                            display:block;
                            padding:2px 6px;
                            border-radius:2px;
                            font-size:0.68rem;
                            margin-bottom:2px;
                            white-space:nowrap;
                            overflow:hidden;
                            text-overflow:ellipsis;
                            text-decoration:none;
                            color:{{ $task->estado === 'completada' ? 'rgba(77,207,207,0.7)' : ($task->prioridad === 'alta' ? '#ff8866' : ($task->prioridad === 'media' ? '#ffcc55' : 'rgba(180,200,240,0.7)')) }};
                            background:{{ $task->estado === 'completada' ? 'rgba(77,207,207,0.08)' : ($task->prioridad === 'alta' ? 'rgba(255,100,80,0.1)' : ($task->prioridad === 'media' ? 'rgba(255,180,50,0.1)' : 'rgba(255,255,255,0.04)')) }};
                            border-left:2px solid {{ $task->estado === 'completada' ? '#4dcfcf' : ($task->prioridad === 'alta' ? '#ff8866' : ($task->prioridad === 'media' ? '#ffcc55' : 'rgba(180,200,240,0.3)')) }};
                            transition:opacity 0.15s;
                        " title="{{ $task->titulo }}">
                            {{ $task->emoji ? $task->emoji . ' ' : '' }}{{ Str::limit($task->titulo, 18) }}
                        </a>
                    @endforeach

                    @if($dayTasks->count() > 3)
                        <div style="font-size:0.65rem; color:var(--text-dim); padding:2px 6px;">
                            +{{ $dayTasks->count() - 3 }} más
                        </div>
                    @endif
                </div>
            @endfor

            {{-- Fill remaining cells to complete the row --}}
            @php $remaining = (7 - ($dayCount % 7)) % 7; @endphp
            @for($i = 0; $i < $remaining; $i++)
                <div style="min-height:100px; border-bottom:1px solid var(--border-subtle); border-right:1px solid var(--border-subtle); padding:0.5rem; background:rgba(0,0,0,0.1);"></div>
            @endfor
        </div>
    </div>

    <!-- Side: tasks with due date this month -->
    <div>
        <div class="panel" style="padding:1.25rem 1.5rem;">
            <div style="font-size:0.8rem; font-weight:500; letter-spacing:0.06em; margin-bottom:1.25rem; padding-bottom:0.75rem; border-bottom:1px solid var(--border-subtle);">
                Tareas del mes
            </div>

            @if($monthTasks->isEmpty())
                <div style="font-size:0.8rem; color:var(--text-dim); text-align:center; padding:1rem 0;">
                    Sin tareas este mes
                </div>
            @else
                @foreach($monthTasks as $task)
                <a href="{{ route('tasks.edit', $task->id) }}" style="
                    display:flex; align-items:center; gap:0.75rem;
                    padding:0.6rem 0;
                    border-bottom:1px solid var(--border-subtle);
                    text-decoration:none;
                ">
                    <div style="width:3px; height:32px; border-radius:2px; flex-shrink:0;
                        background:{{ $task->prioridad === 'alta' ? '#ff8866' : ($task->prioridad === 'media' ? '#ffcc55' : '#4dcfcf') }};
                        opacity:{{ $task->estado === 'completada' ? '0.4' : '1' }};"></div>
                    <div style="flex:1; min-width:0;">
                        <div style="font-size:0.78rem; color:{{ $task->estado === 'completada' ? 'rgba(180,200,240,0.4)' : 'var(--star-white)' }}; text-decoration:{{ $task->estado === 'completada' ? 'line-through' : 'none' }}; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                            {{ $task->titulo }}
                        </div>
                        <div style="font-size:0.68rem; color:var(--text-dim); margin-top:2px;">
                            {{ \Carbon\Carbon::parse($task->fecha_fin)->isoFormat('D MMM') }}
                        </div>
                    </div>
                    <span class="badge badge-{{ $task->estado }}" style="font-size:0.6rem;">{{ str_replace('_',' ',$task->estado) }}</span>
                </a>
                @endforeach
            @endif

            <div style="margin-top:1.25rem;">
                <a href="{{ route('tasks.create') }}" class="btn-primary" style="width:100%; justify-content:center;">+ Nueva tarea</a>
            </div>
        </div>

        <!-- Legend -->
        <div class="panel" style="padding:1.25rem 1.5rem; margin-top:1rem;">
            <div style="font-size:0.7rem; letter-spacing:0.1em; text-transform:uppercase; color:var(--text-dim); margin-bottom:0.75rem;">Leyenda</div>
            <div style="display:flex; flex-direction:column; gap:0.5rem;">
                <div style="display:flex; align-items:center; gap:0.6rem; font-size:0.75rem; color:rgba(180,200,240,0.7);">
                    <div style="width:10px; height:10px; border-radius:1px; background:rgba(255,100,80,0.3); border-left:3px solid #ff8866;"></div>
                    Prioridad alta
                </div>
                <div style="display:flex; align-items:center; gap:0.6rem; font-size:0.75rem; color:rgba(180,200,240,0.7);">
                    <div style="width:10px; height:10px; border-radius:1px; background:rgba(255,180,50,0.2); border-left:3px solid #ffcc55;"></div>
                    Prioridad media
                </div>
                <div style="display:flex; align-items:center; gap:0.6rem; font-size:0.75rem; color:rgba(180,200,240,0.7);">
                    <div style="width:10px; height:10px; border-radius:1px; background:rgba(77,207,207,0.1); border-left:3px solid #4dcfcf;"></div>
                    Completada / baja prioridad
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
