@extends('layouts.app')
@section('title', 'Calendario')

@push('styles')
<style>
/* ── Hover en celdas de día ── */
.cal-day {
    cursor: pointer;
    position: relative;
    transition: background 0.18s ease;
}
.cal-day:hover {
    background: rgba(139,92,246,0.055) !important;
}
.cal-day-plus {
    position: absolute;
    top: 5px;
    right: 7px;
    font-size: 17px;
    font-weight: 300;
    color: rgba(139,92,246,0.35);
    opacity: 0;
    transition: opacity 0.18s ease, color 0.18s ease;
    pointer-events: none;
    line-height: 1;
}
.cal-day:hover .cal-day-plus { opacity: 1; color: rgba(139,92,246,0.6); }

/* ── Modal overlay ── */
.day-modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.55);
    z-index: 500;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.22s ease;
    backdrop-filter: blur(3px);
}
.day-modal-overlay.open {
    opacity: 1;
    pointer-events: auto;
}

/* ── Modal panel ── */
.day-modal {
    background: var(--panel-bg, #111827);
    border: 1px solid rgba(139,92,246,0.28);
    border-radius: 12px;
    width: 400px;
    max-width: calc(100vw - 32px);
    max-height: 82vh;
    display: flex;
    flex-direction: column;
    transform: translateY(18px) scale(0.97);
    transition: transform 0.22s cubic-bezier(.4,0,.2,1);
    box-shadow: 0 20px 56px rgba(0,0,0,0.55);
    overflow: hidden;
}
.day-modal-overlay.open .day-modal {
    transform: translateY(0) scale(1);
}

.day-modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 18px 20px 14px;
    border-bottom: 1px solid rgba(255,255,255,0.07);
    flex-shrink: 0;
}
.day-modal-date {
    font-family: 'Cinzel', serif;
    font-size: 14px;
    font-weight: 600;
    color: var(--star-white);
    letter-spacing: 0.05em;
}
.day-modal-close {
    background: none;
    border: none;
    color: rgba(255,255,255,0.3);
    font-size: 15px;
    cursor: pointer;
    padding: 3px 7px;
    border-radius: 5px;
    transition: color 0.15s, background 0.15s;
    line-height: 1;
    font-family: inherit;
}
.day-modal-close:hover {
    color: rgba(255,255,255,0.75);
    background: rgba(255,255,255,0.07);
}

.day-modal-body {
    flex: 1;
    overflow-y: auto;
    padding: 14px 18px 6px;
    min-height: 80px;
    scrollbar-width: thin;
    scrollbar-color: rgba(139,92,246,0.3) transparent;
}
.day-modal-body::-webkit-scrollbar { width: 4px; }
.day-modal-body::-webkit-scrollbar-thumb { background: rgba(139,92,246,0.3); border-radius: 2px; }

.day-modal-empty {
    text-align: center;
    padding: 22px 0 14px;
    color: rgba(255,255,255,0.28);
    font-size: 13px;
    line-height: 1.6;
}
.day-modal-empty-icon {
    font-size: 30px;
    display: block;
    margin-bottom: 10px;
    opacity: 0.45;
}

.day-modal-task {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 9px 10px;
    border-radius: 7px;
    margin-bottom: 6px;
    text-decoration: none;
    border-left: 3px solid;
    transition: transform 0.15s ease, background 0.15s ease;
}
.day-modal-task:hover {
    transform: translateX(4px);
}
.day-modal-task-title {
    flex: 1;
    font-size: 13px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.day-modal-task-badge {
    font-size: 10px;
    padding: 2px 8px;
    border-radius: 3px;
    text-transform: uppercase;
    letter-spacing: 0.07em;
    flex-shrink: 0;
    font-weight: 500;
}

.day-modal-footer {
    padding: 12px 18px 16px;
    border-top: 1px solid rgba(255,255,255,0.07);
    flex-shrink: 0;
}
.day-modal-footer a {
    display: flex;
    width: 100%;
    justify-content: center;
}
</style>
@endpush

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
            <div class="cal-day"
                 onclick="openDay('{{ $dateStr }}')"
                 style="min-height:96px; border-bottom:1px solid var(--border-subtle); padding:6px;
                        {{ ($dayCount % 7 !== 6) ? 'border-right:1px solid var(--border-subtle);' : '' }}
                        background:{{ $isToday ? 'rgba(201,168,76,0.04)' : 'transparent' }};">

                {{-- Número de día --}}
                <div style="display:inline-flex; align-items:center; justify-content:center;
                            width:24px; height:24px; border-radius:50%; font-size:12px; margin-bottom:5px;
                            font-weight:{{ $isToday ? '600' : '400' }};
                            color:{{ $isToday ? '#03060f' : 'var(--text-dim)' }};
                            background:{{ $isToday ? 'var(--accent-gold)' : 'transparent' }};">
                    {{ $day }}
                </div>

                {{-- Tareas (máx. 3 visibles) --}}
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
                <div title="{{ $task->titulo }}"
                     style="display:block; padding:2px 6px; border-radius:2px; font-size:11px; margin-bottom:2px;
                            white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
                            color:{{ $color }}; background:{{ $bg }}; border-left:2px solid {{ $color }};">
                    {{ $task->emoji ? $task->emoji . ' ' : '' }}{{ Str::limit($task->titulo, 18) }}
                </div>
                @endforeach

                @if($dayTasks->count() > 3)
                    <div style="font-size:10px; color:var(--text-dim); padding:2px 6px;">
                        +{{ $dayTasks->count() - 3 }} más
                    </div>
                @endif

                {{-- Indicador "+" en hover --}}
                <div class="cal-day-plus">+</div>
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

{{-- ── Modal de día ── --}}
<div id="day-modal" class="day-modal-overlay" onclick="if(event.target===this) closeDay()">
    <div class="day-modal">
        <div class="day-modal-header">
            <div class="day-modal-date" id="modal-date"></div>
            <button class="day-modal-close" onclick="closeDay()" type="button">✕</button>
        </div>
        <div class="day-modal-body" id="modal-body"></div>
        <div class="day-modal-footer">
            <a id="modal-new-task" href="#" class="btn-primary">
                + Nueva tarea para este día
            </a>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
@php
$tasksForJs = [];
foreach ($tasksByDate as $dateKey => $tasks) {
    foreach ($tasks as $t) {
        $tasksForJs[] = [
            'date'     => $dateKey,
            'id'       => $t->id,
            'titulo'   => $t->titulo,
            'emoji'    => $t->emoji ?? '',
            'estado'   => $t->estado,
            'prioridad'=> $t->prioridad,
            'edit_url' => route('tasks.edit', $t->id),
        ];
    }
}
@endphp
const CALENDAR_TASKS = @json($tasksForJs);
const CREATE_BASE    = "{{ route('tasks.create') }}";

const DAYS_ES   = ['domingo','lunes','martes','miércoles','jueves','viernes','sábado'];
const MONTHS_ES = ['enero','febrero','marzo','abril','mayo','junio','julio','agosto',
                   'septiembre','octubre','noviembre','diciembre'];

function openDay(dateStr) {
    const [y, m, d] = dateStr.split('-').map(Number);
    const date      = new Date(y, m - 1, d);
    const dayName   = DAYS_ES[date.getDay()];
    const title     = dayName.charAt(0).toUpperCase() + dayName.slice(1)
                    + ', ' + d + ' de ' + MONTHS_ES[m - 1] + ' de ' + y;

    document.getElementById('modal-date').textContent = title;

    const tasks = CALENDAR_TASKS.filter(t => t.date === dateStr);
    const body  = document.getElementById('modal-body');

    if (tasks.length === 0) {
        body.innerHTML = `
            <div class="day-modal-empty">
                <span class="day-modal-empty-icon">📅</span>
                No hay tareas programadas para este día
            </div>`;
    } else {
        body.innerHTML = tasks.map(t => {
            const c = t.estado === 'completada'
                ? { border:'#4dcfcf', bg:'rgba(77,207,207,0.08)',   text:'#4dcfcf', badge:'rgba(77,207,207,0.15)' }
                : t.prioridad === 'alta'
                ? { border:'#ff8866', bg:'rgba(255,100,80,0.08)',   text:'#ff8866', badge:'rgba(255,100,80,0.15)' }
                : t.prioridad === 'media'
                ? { border:'#a78bfa', bg:'rgba(139,92,246,0.08)',   text:'#a78bfa', badge:'rgba(139,92,246,0.15)' }
                : { border:'rgba(180,200,240,0.45)', bg:'rgba(255,255,255,0.03)', text:'rgba(180,200,240,0.8)', badge:'rgba(255,255,255,0.07)' };

            const strike  = t.estado === 'completada' ? 'text-decoration:line-through; opacity:0.6;' : '';
            const badge   = t.estado === 'completada' ? 'Hecha'
                          : t.prioridad.charAt(0).toUpperCase() + t.prioridad.slice(1);

            return `<a href="${t.edit_url}" class="day-modal-task"
                       style="border-color:${c.border}; background:${c.bg};">
                <div class="day-modal-task-title" style="color:${c.text}; ${strike}">
                    ${t.emoji ? t.emoji + ' ' : ''}${t.titulo}
                </div>
                <span class="day-modal-task-badge" style="background:${c.badge}; color:${c.text};">
                    ${badge}
                </span>
            </a>`;
        }).join('');
    }

    document.getElementById('modal-new-task').href = CREATE_BASE + '?fecha_fin=' + dateStr;

    document.getElementById('day-modal').classList.add('open');
    document.body.style.overflow = 'hidden';
}

function closeDay() {
    document.getElementById('day-modal').classList.remove('open');
    document.body.style.overflow = '';
}

document.addEventListener('keydown', e => { if (e.key === 'Escape') closeDay(); });
</script>
@endpush
