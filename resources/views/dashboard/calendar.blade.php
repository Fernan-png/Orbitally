@extends('layouts.app')
@section('title', 'Calendario')

@section('content')
{{-- Encabezado y Navegación --}}
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

    {{-- Cuadrícula del Calendario --}}
    <div class="panel" style="overflow:hidden;">
        <div style="display:grid; grid-template-columns:repeat(7,1fr); border-bottom:1px solid var(--border-subtle);">
            @foreach(['Dom','Lun','Mar','Mié','Jue','Vie','Sáb'] as $dayName)
                <div style="padding:0.75rem 0.5rem; text-align:center; font-size:0.7rem; letter-spacing:0.08em; text-transform:uppercase; color:var(--text-dim);">
                    {{ $dayName }}
                </div>
            @endforeach
        </div>

        <div style="display:grid; grid-template-columns:repeat(7,1fr);">
            @php
                $today = now()->startOfDay();
                $dayCount = 0;
            @endphp

            {{-- Celdas vacías iniciales --}}
            @for($i = 0; $i < $firstDayOfWeek; $i++, $dayCount++)
                <div style="min-height:100px; border-bottom:1px solid var(--border-subtle); border-right:1px solid var(--border-subtle); padding:0.5rem; background:rgba(0,0,0,0.1);"></div>
            @endfor

            {{-- Días del mes --}}
            @for($day = 1; $day <= $daysInMonth; $day++, $dayCount++)
                @php
                    $date = $currentDate->copy()->day($day);
                    $dateStr = $date->format('Y-m-d');
                    $isToday = $date->isToday();
                    $dayTasks = $tasksByDate[$dateStr] ?? collect();
                @endphp
                
                <div style="min-height:100px; border-bottom:1px solid var(--border-subtle); padding:0.5rem; 
                            {{ ($dayCount % 7 !== 6) ? 'border-right:1px solid var(--border-subtle);' : '' }}
                            background: {{ $isToday ? 'rgba(201,168,76,0.05)' : 'transparent' }};">
                    
                    <div style="display:inline-flex; align-items:center; justify-content:center; width:24px; height:24px; border-radius:50%; font-size:0.78rem; margin-bottom:0.35rem;
                                font-weight:{{ $isToday ? '600' : '400' }}; 
                                color:{{ $isToday ? '#03060f' : 'rgba(180,200,240,0.6)' }};
                                background:{{ $isToday ? 'var(--accent-gold)' : 'transparent' }};">
                        {{ $day }}
                    </div>

                    @foreach($dayTasks->take(3) as $task)
                        @php
                            $isComp = $task->estado === 'completada';
                            $color = $isComp ? '#4dcfcf' : ($task->prioridad === 'alta' ? '#ff8866' : ($task->prioridad === 'media' ? '#ffcc55' : 'rgba(180,200,240,0.7)'));
                            $bg = $isComp ? 'rgba(77,207,207,0.08)' : ($task->prioridad === 'alta' ? 'rgba(255,100,80,0.1)' : ($task->prioridad === 'media' ? 'rgba(255,180,50,0.1)' : 'rgba(255,255,255,0.04)'));
                        @endphp
                        <a href="{{ route('tasks.edit', $task->id) }}" title="{{ $task->titulo }}"
                           style="display:block; padding:2px 6px; border-radius:2px; font-size:0.68rem; margin-bottom:2px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; text-decoration:none;
                                  color:{{ $color }}; background:{{ $bg }}; border-left:2px solid {{ $color }};">
                            {{ $task->emoji ? $task->emoji . ' ' : '' }}{{ Str::limit($task->titulo, 18) }}
                        </a>
                    @endforeach

                    @if($dayTasks->count() > 3)
                        <div style="font-size:0.65rem; color:var(--text-dim); padding:2px 6px;">+{{ $dayTasks->count() - 3 }} más</div>
                    @endif
                </div>
            @endfor

            {{-- Celdas vacías finales --}}
            @for($i = 0; $i < (7 - ($dayCount % 7)) % 7; $i++)
                <div style="min-height:100px; border-bottom:1px solid var(--border-subtle); border-right:1px solid var(--border-subtle); padding:0.5rem; background:rgba(0,0,0,0.1);"></div>
            @endfor
        </div>
    </div>

    {{-- Barra Lateral --}}
    <aside>
        <div class="panel" style="padding:1.25rem 1.5rem;">
            <div style="font-size:0.8rem; font-weight:500; letter-spacing:0.06em; margin-bottom:1.25rem; padding-bottom:0.75rem; border-bottom:1px solid var(--border-subtle);">
                Tareas del mes
            </div>

            @forelse($monthTasks as $task)
                <a href="{{ route('tasks.edit', $task->id) }}" style="display:flex; align-items:center; gap:0.75rem; padding:0.6rem 0; border-bottom:1px solid var(--border-subtle); text-decoration:none;">
                    <div style="width:3px; height:32px; border-radius:2px; flex-shrink:0; opacity:{{ $task->estado === 'completada' ? '0.4' : '1' }};
                                background:{{ $task->prioridad === 'alta' ? '#ff8866' : ($task->prioridad === 'media' ? '#ffcc55' : '#4dcfcf') }};"></div>
                    <div style="flex:1; min-width:0;">
                        <div style="font-size:0.78rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
                                    color:{{ $task->estado === 'completada' ? 'rgba(180,200,240,0.4)' : 'var(--star-white)' }}; 
                                    text-decoration:{{ $task->estado === 'completada' ? 'line-through' : 'none' }};">
                            {{ $task->titulo }}
                        </div>
                        <div style="font-size:0.68rem; color:var(--text-dim); margin-top:2px;">
                            {{ \Carbon\Carbon::parse($task->fecha_fin)->isoFormat('D MMM') }}
                        </div>
                    </div>
                    <span class="badge badge-{{ $task->estado }}" style="font-size:0.6rem;">{{ str_replace('_',' ',$task->estado) }}</span>
                </a>
            @empty
                <div style="font-size:0.8rem; color:var(--text-dim); text-align:center; padding:1rem 0;">Sin tareas este mes</div>
            @endforelse

            <div style="margin-top:1.25rem;">
                <a href="{{ route('tasks.create') }}" class="btn-primary" style="width:100%; justify-content:center;">+ Nueva tarea</a>
            </div>
        </div>

        {{-- Leyenda --}}
        <div class="panel" style="padding:1.25rem 1.5rem; margin-top:1rem;">
            <div style="font-size:0.7rem; letter-spacing:0.1em; text-transform:uppercase; color:var(--text-dim); margin-bottom:0.75rem;">Leyenda</div>
            <div style="display:flex; flex-direction:column; gap:0.5rem;">
                @foreach([
                    ['#ff8866', 'rgba(255,100,80,0.3)', 'Prioridad alta'],
                    ['#ffcc55', 'rgba(255,180,50,0.2)', 'Prioridad media'],
                    ['#4dcfcf', 'rgba(77,207,207,0.1)', 'Completada / baja']
                ] as [$border, $bg, $label])
                    <div style="display:flex; align-items:center; gap:0.6rem; font-size:0.75rem; color:rgba(180,200,240,0.7);">
                        <div style="width:10px; height:10px; border-radius:1px; background:{{ $bg }}; border-left:3px solid {{ $border }};"></div>
                        {{ $label }}
                    </div>
                @endforeach
            </div>
        </div>
    </aside>
</div>
@endsection