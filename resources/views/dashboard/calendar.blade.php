@extends('layouts.app')
@section('title', 'Calendario')

@section('content')

{{-- Encabezado --}}
<div class="page-header-row">
    <div>
        <div class="page-title">Calendario</div>
        <div class="page-subtitle">Vista mensual de tus tareas</div>
    </div>
    <div style="display:flex; gap:6px; align-items:center;">
        <a href="{{ route('calendar', ['month' => $prevMonth, 'year' => $prevYear]) }}"
           class="btn-secondary" style="padding:7px 14px; font-size:13px;">← Anterior</a>
        <span style="font-size:14px; font-family:'Cinzel',serif; letter-spacing:0.08em;
                     padding:0 10px; color:var(--star-white);">
            {{ $currentDate->isoFormat('MMMM YYYY') }}
        </span>
        <a href="{{ route('calendar', ['month' => $nextMonth, 'year' => $nextYear]) }}"
           class="btn-secondary" style="padding:7px 14px; font-size:13px;">Siguiente →</a>
    </div>
</div>

<div style="display:grid; grid-template-columns:1fr 260px; gap:20px; align-items:start;">

    {{-- Cuadrícula --}}
    <div class="panel" style="overflow:hidden;">

        {{-- Cabecera días --}}
        <div style="display:grid; grid-template-columns:repeat(7,1fr); border-bottom:1px solid var(--border-subtle);">
            @foreach(['Lun','Mar','Mié','Jue','Vie','Sáb','Dom'] as $dayName)
                <div class="cal-day-header">{{ $dayName }}</div>
            @endforeach
        </div>

        {{-- Celdas --}}
        <div style="display:grid; grid-template-columns:repeat(7,1fr);">
            @php $dayCount = 0; @endphp

            @for($i = 0; $i < $firstDayOfWeek; $i++, $dayCount++)
                <div style="min-height:96px; border-bottom:1px solid var(--border-subtle);
                            border-right:1px solid var(--border-subtle); padding:6px;
                            background:rgba(0,0,0,0.06);"></div>
            @endfor

            @for($day = 1; $day <= $daysInMonth; $day++, $dayCount++)
            @php
                $date    = $currentDate->copy()->day($day);
                $dateStr = $date->format('Y-m-d');
                $isToday = $date->isToday();
                $dayTasks = $tasksByDate[$dateStr] ?? collect();
            @endphp
            <div style="min-height:96px; border-bottom:1px solid var(--border-subtle); padding:6px;
                        {{ ($dayCount % 7 !== 6) ? 'border-right:1px solid var(--border-subtle);' : '' }}
                        background:{{ $isToday ? 'rgba(201,168,76,0.04)' : 'transparent' }};
                        transition:background 0.2s;">

                <div style="display:inline-flex; align-items:center; justify-content:center;
                            width:24px; height:24px; border-radius:50%; font-size:12px; margin-bottom:5px;
                            font-weight:{{ $isToday ? '600' : '400' }};
                            color:{{ $isToday ? '#03060f' : 'var(--text-dim)' }};
                            background:{{ $isToday ? 'var(--accent-gold)' : 'transparent' }};">
                    {{ $day }}
                </div>

                @foreach($dayTasks->take(3) as $task)
                @php
                    $isComp = $task->estado === 'completada';
                    $color  = $isComp ? '#4dcfcf'
                        : ($task->prioridad === 'alta' ? '#ff8866'
                        : ($task->prioridad === 'media' ? '#a78bfa' : 'rgba(180,200,240,0.7)'));
                    $bg = $isComp ? 'rgba(77,207,207,0.07)'
                        : ($task->prioridad === 'alta' ? 'rgba(255,100,80,0.08)'
                        : ($task->prioridad === 'media' ? 'rgba(139,92,246,0.08)' : 'rgba(255,255,255,0.03)'));
                @endphp
                <a href="{{ route('tasks.edit', $task->id) }}" title="{{ $task->titulo }}"
                   style="display:block; padding:2px 6px; border-radius:2px; font-size:11px; margin-bottom:2px;
                          white-space:nowrap; overflow:hidden; text-overflow:ellipsis; text-decoration:none;
                          color:{{ $color }}; background:{{ $bg }}; border-left:2px solid {{ $color }};
                          transition:opacity 0.2s;"
                   onmouseover="this.style.opacity='0.75'" onmouseout="this.style.opacity='1'">
                    {{ $task->emoji ? $task->emoji . ' ' : '' }}{{ Str::limit($task->titulo, 18) }}
                </a>
                @endforeach

                @if($dayTasks->count() > 3)
                    <div style="font-size:10px; color:var(--text-dim); padding:2px 6px;">
                        +{{ $dayTasks->count() - 3 }} más
                    </div>
                @endif
            </div>
            @endfor

            @for($i = 0; $i < (7 - ($dayCount % 7)) % 7; $i++)
                <div style="min-height:96px; border-bottom:1px solid var(--border-subtle);
                            border-right:1px solid var(--border-subtle); padding:6px;
                            background:rgba(0,0,0,0.06);"></div>
            @endfor
        </div>
    </div>

    {{-- Sidebar --}}
    <aside style="display:flex; flex-direction:column; gap:16px;">

        {{-- Tareas del mes --}}
        <div class="panel" style="padding:18px 20px;">
            <div style="font-size:11px; font-weight:500; letter-spacing:0.1em; text-transform:uppercase;
                        margin-bottom:16px; padding-bottom:10px; border-bottom:1px solid var(--border-subtle);
                        color:var(--text-dim);">
                Tareas del mes
            </div>

            @forelse($monthTasks as $task)
            <a href="{{ route('tasks.edit', $task->id) }}"
               style="display:flex; align-items:center; gap:10px; padding:8px 0;
                      border-bottom:1px solid var(--border-subtle); text-decoration:none; transition:opacity 0.2s;"
               onmouseover="this.style.opacity='0.75'" onmouseout="this.style.opacity='1'">
                <div style="width:3px; height:32px; border-radius:2px; flex-shrink:0;
                            opacity:{{ $task->estado === 'completada' ? '0.35' : '1' }};
                            background:{{ $task->prioridad === 'alta' ? '#ff8866' : ($task->prioridad === 'media' ? '#a78bfa' : '#4dcfcf') }};"></div>
                <div style="flex:1; min-width:0;">
                    <div style="font-size:13px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
                                color:{{ $task->estado === 'completada' ? 'var(--text-muted)' : 'var(--star-white)' }};
                                text-decoration:{{ $task->estado === 'completada' ? 'line-through' : 'none' }};">
                        {{ $task->titulo }}
                    </div>
                    <div style="font-size:11px; color:var(--text-dim); margin-top:2px;">
                        {{ \Carbon\Carbon::parse($task->fecha_fin)->isoFormat('D MMM') }}
                    </div>
                </div>
            </a>
            @empty
            <div style="font-size:13px; color:var(--text-dim); text-align:center; padding:16px 0;">
                Sin tareas este mes
            </div>
            @endforelse

            <div style="margin-top:16px;">
                <a href="{{ route('tasks.create') }}" class="btn-primary" style="width:100%; justify-content:center;">
                    + Nueva tarea
                </a>
            </div>
        </div>

        {{-- Leyenda --}}
        <div class="panel" style="padding:16px 20px;">
            <div style="font-size:10px; letter-spacing:0.12em; text-transform:uppercase;
                        color:var(--text-dim); margin-bottom:12px;">Leyenda</div>
            <div style="display:flex; flex-direction:column; gap:8px;">
                @foreach([
                    ['#ff8866', 'rgba(255,100,80,0.08)', 'Prioridad alta'],
                    ['#a78bfa', 'rgba(139,92,246,0.08)', 'Prioridad media'],
                    ['#4dcfcf', 'rgba(77,207,207,0.08)', 'Completada / baja'],
                ] as [$border, $bg, $label])
                <div style="display:flex; align-items:center; gap:8px; font-size:12px; color:var(--text-dim);">
                    <div style="width:12px; height:12px; border-radius:1px; flex-shrink:0;
                                background:{{ $bg }}; border-left:3px solid {{ $border }};"></div>
                    {{ $label }}
                </div>
                @endforeach
            </div>
        </div>
    </aside>
</div>
@endsection