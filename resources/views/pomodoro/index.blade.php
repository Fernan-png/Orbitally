@extends('layouts.app')
@section('title', 'Pomodoro')

@push('styles')
<style>
    /* ── Ring SVG ─────────────────────────────────────────────── */
    .pomodoro-ring {
        transform: rotate(-90deg);
        transform-origin: center;
    }
    .ring-track {
        fill: none;
        stroke: var(--border-subtle);
        stroke-width: 5;
    }
    .ring-progress {
        fill: none;
        stroke-width: 5;
        stroke-linecap: round;
        transition: stroke-dashoffset 1s linear, stroke 0.5s ease;
    }

    /* ── Timer display ────────────────────────────────────────── */
    .timer-display {
        font-family: 'Cinzel', serif;
        font-size: 50px;
        font-weight: 600;
        letter-spacing: 3px;
        line-height: 1;
        transition: color 0.4s;
        /* Sin text-shadow para evitar el glow que tapaba el contenedor */
    }

    /* ── Phase badge ──────────────────────────────────────────── */
    .phase-badge {
        font-size: 10px;
        font-weight: 500;
        letter-spacing: 3px;
        text-transform: uppercase;
        padding: 4px 14px;
        border-radius: 2px;
        transition: all 0.4s;
    }

    /* ── Control buttons ──────────────────────────────────────── */
    .pomodoro-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 10px 22px;
        font-family: 'Jost', sans-serif;
        font-size: 13px;
        font-weight: 500;
        letter-spacing: 0.5px;
        border-radius: 3px;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
    }
    .pomodoro-btn i {
        font-size: 13px;
        flex-shrink: 0;
    }
    .pomodoro-btn:disabled {
        opacity: 0.30;
        cursor: not-allowed;
        transform: none !important;
        box-shadow: none !important;
    }
    .pomodoro-btn:not(:disabled):hover {
        transform: translateY(-1px);
    }

    /* ── Duration inputs ──────────────────────────────────────── */
    .duration-input {
        width: 64px;
        background: rgba(128,128,128,0.06);
        border: 1px solid var(--border-subtle);
        border-radius: 3px;
        padding: 7px 10px;
        font-family: 'Cinzel', serif;
        font-size: 16px;
        font-weight: 600;
        color: var(--star-white);
        text-align: center;
        outline: none;
        transition: border-color 0.2s;
        -moz-appearance: textfield;
    }
    .duration-input::-webkit-inner-spin-button,
    .duration-input::-webkit-outer-spin-button { -webkit-appearance: none; }
    .duration-input:focus { border-color: rgba(201,168,76,0.5); }
    .duration-input:disabled { opacity: 0.4; cursor: not-allowed; }

    /* ── Historial row ────────────────────────────────────────── */
    .historial-row {
        padding: 10px 18px;
        border-bottom: 1px solid var(--border-subtle);
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 13px;
        transition: background 0.15s;
    }
    .historial-row:hover { background: rgba(128,128,128,0.03); }
    .historial-row:last-child { border-bottom: none; }

    /* ── Glow animación suavizada (menos brillo, no afecta al contenedor) ── */
    @keyframes ring-glow {
        0%, 100% { filter: drop-shadow(0 0 3px rgba(77,207,207,0.4)); }
        50%       { filter: drop-shadow(0 0 7px rgba(77,207,207,0.6)); }
    }
    @keyframes ring-glow-break {
        0%, 100% { filter: drop-shadow(0 0 3px rgba(136,170,255,0.4)); }
        50%       { filter: drop-shadow(0 0 7px rgba(136,170,255,0.6)); }
    }
    .ring-glow-study { animation: ring-glow       2.5s ease-in-out infinite; }
    .ring-glow-break { animation: ring-glow-break 2.5s ease-in-out infinite; }

    /* ── Flash overlay cambio de fase ─────────────────────────── */
    #phase-flash {
        position: fixed; inset: 0;
        pointer-events: none;
        z-index: 200;
        opacity: 0;
        transition: opacity 0.3s;
    }

    /* ── Preset buttons ───────────────────────────────────────── */
    .preset-btn {
        padding: 5px 14px;
        font-family: 'Jost', sans-serif;
        font-size: 11px;
        letter-spacing: 0.1em;
        border: 1px solid var(--border-subtle);
        border-radius: 2px;
        background: transparent;
        color: var(--text-dim);
        cursor: pointer;
        transition: all 0.2s;
        text-transform: uppercase;
    }
    .preset-btn:hover:not(:disabled) {
        color: var(--star-white);
        border-color: rgba(201,168,76,0.35);
    }
    .preset-btn:disabled { opacity: 0.3; cursor: not-allowed; }
</style>
@endpush

@section('content')

{{-- Flash overlay para cambio de fase --}}
<div id="phase-flash"></div>

{{-- ── Encabezado ── --}}
<div class="page-header" style="display:flex; align-items:flex-end; justify-content:space-between; flex-wrap:wrap; gap:16px;">
    <div>
        <div class="page-title">Temporizador Pomodoro</div>
        <div class="page-subtitle">Organiza tu tiempo de estudio y descanso</div>
    </div>
    {{-- Stats rápidas --}}
    <div style="display:flex; gap:20px; align-items:center;">
        <div style="text-align:center;">
            <div style="font-family:'Cinzel',serif; font-size:22px; font-weight:600; color:var(--accent-teal); line-height:1;">
                {{ $stats['hoy'] }}
            </div>
            <div style="font-size:10px; letter-spacing:0.1em; text-transform:uppercase; color:var(--text-dim); margin-top:3px;">Hoy</div>
        </div>
        <div style="width:1px; height:32px; background:var(--border-subtle);"></div>
        <div style="text-align:center;">
            <div style="font-family:'Cinzel',serif; font-size:22px; font-weight:600; color:var(--accent-gold); line-height:1;">
                {{ $stats['completadas'] }}
            </div>
            <div style="font-size:10px; letter-spacing:0.1em; text-transform:uppercase; color:var(--text-dim); margin-top:3px;">Total</div>
        </div>
        <div style="width:1px; height:32px; background:var(--border-subtle);"></div>
        <div style="text-align:center;">
            <div style="font-family:'Cinzel',serif; font-size:22px; font-weight:600; color:#88aaff; line-height:1;">
                {{ $stats['minutos_totales'] }}
            </div>
            <div style="font-size:10px; letter-spacing:0.1em; text-transform:uppercase; color:var(--text-dim); margin-top:3px;">Min. totales</div>
        </div>
    </div>
</div>

<div style="display:grid; grid-template-columns:1fr 320px; gap:20px; align-items:start;">

    {{-- ── Panel principal del temporizador ── --}}
    <div class="panel" style="padding:40px 32px; display:flex; flex-direction:column; align-items:center; gap:28px;">

        {{-- Ring SVG — aislado en su propio div para que el filter no afecte al panel --}}
        <div style="position:relative; display:inline-flex; align-items:center; justify-content:center; isolation:isolate;">
            <div id="ring-wrapper" style="line-height:0;">
                <svg width="240" height="240" viewBox="0 0 240 240" style="display:block;">
                    <circle class="ring-track pomodoro-ring"    cx="120" cy="120" r="104"/>
                    <circle class="ring-progress pomodoro-ring" cx="120" cy="120" r="104"
                            id="ring-arc"
                            stroke="#4dcfcf"
                            stroke-dasharray="653.45"
                            stroke-dashoffset="0"/>
                </svg>
            </div>

            {{-- Contenido central: posicionado encima del SVG --}}
            <div style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%);
                        text-align:center; display:flex; flex-direction:column; align-items:center; gap:10px;
                        pointer-events:none; width:160px;">
                <div id="phase-badge" class="phase-badge"
                     style="background:rgba(77,207,207,0.1); border:1px solid rgba(77,207,207,0.22); color:#4dcfcf;">
                    Estudio
                </div>
                <div id="timer-display" class="timer-display" style="color:#4dcfcf;">25:00</div>
                <div id="cycle-info" style="font-size:11px; letter-spacing:0.1em; text-transform:uppercase; color:var(--text-dim);">
                    Ciclo 1
                </div>
            </div>
        </div>

        {{-- ── Controles principales ── --}}
        <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap; justify-content:center;">

            {{-- Iniciar --}}
            <button id="btn-start" class="pomodoro-btn"
                    style="background:linear-gradient(135deg,#e8d5a0,#c9a84c); color:#03060f;
                           box-shadow:0 3px 12px rgba(201,168,76,0.18);">
                <i class="fa-solid fa-play"></i>
                Iniciar
            </button>

            {{-- Pausar --}}
            <button id="btn-pause" class="pomodoro-btn" disabled
                    style="background:rgba(136,170,255,0.07); color:rgba(136,170,255,0.7);
                           border:1px solid rgba(136,170,255,0.18);">
                <i class="fa-solid fa-pause"></i>
                Pausar
            </button>

            {{-- Cancelar --}}
            <button id="btn-cancel" class="pomodoro-btn" disabled
                    style="background:rgba(255,100,80,0.07); color:rgba(255,100,80,0.65);
                           border:1px solid rgba(255,100,80,0.15);">
                <i class="fa-solid fa-stop"></i>
                Cancelar
            </button>

        </div>

        {{-- Separador --}}
        <div style="width:100%; height:1px; background:var(--border-subtle);"></div>

        {{-- ── Configuración de tiempos ── --}}
        <div style="display:flex; gap:40px; align-items:flex-end; flex-wrap:wrap; justify-content:center;">

            <div style="text-align:center;">
                <div style="font-size:10px; letter-spacing:0.15em; text-transform:uppercase;
                            color:var(--text-dim); margin-bottom:10px;">
                    Estudio (min)
                </div>
                <div style="display:flex; align-items:center; gap:8px; justify-content:center;">
                    <button type="button" class="adj-btn" onclick="adjustTime('study', -5)"
                            style="width:26px; height:26px; border-radius:50%; border:1px solid var(--border-subtle);
                                   background:transparent; color:var(--text-dim); cursor:pointer; font-size:15px;
                                   display:flex; align-items:center; justify-content:center; transition:all 0.2s;"
                            onmouseover="this.style.color='var(--star-white)'"
                            onmouseout="this.style.color='var(--text-dim)'">−</button>
                    <input type="number" id="study-time" class="duration-input" value="25" min="1" max="120">
                    <button type="button" class="adj-btn" onclick="adjustTime('study', 5)"
                            style="width:26px; height:26px; border-radius:50%; border:1px solid var(--border-subtle);
                                   background:transparent; color:var(--text-dim); cursor:pointer; font-size:15px;
                                   display:flex; align-items:center; justify-content:center; transition:all 0.2s;"
                            onmouseover="this.style.color='var(--star-white)'"
                            onmouseout="this.style.color='var(--text-dim)'">+</button>
                </div>
            </div>

            <div style="text-align:center;">
                <div style="font-size:10px; letter-spacing:0.15em; text-transform:uppercase;
                            color:var(--text-dim); margin-bottom:10px;">
                    Descanso (min)
                </div>
                <div style="display:flex; align-items:center; gap:8px; justify-content:center;">
                    <button type="button" class="adj-btn" onclick="adjustTime('break', -1)"
                            style="width:26px; height:26px; border-radius:50%; border:1px solid var(--border-subtle);
                                   background:transparent; color:var(--text-dim); cursor:pointer; font-size:15px;
                                   display:flex; align-items:center; justify-content:center; transition:all 0.2s;"
                            onmouseover="this.style.color='var(--star-white)'"
                            onmouseout="this.style.color='var(--text-dim)'">−</button>
                    <input type="number" id="break-time" class="duration-input" value="5" min="1" max="60">
                    <button type="button" class="adj-btn" onclick="adjustTime('break', 1)"
                            style="width:26px; height:26px; border-radius:50%; border:1px solid var(--border-subtle);
                                   background:transparent; color:var(--text-dim); cursor:pointer; font-size:15px;
                                   display:flex; align-items:center; justify-content:center; transition:all 0.2s;"
                            onmouseover="this.style.color='var(--star-white)'"
                            onmouseout="this.style.color='var(--text-dim)'">+</button>
                </div>
            </div>

            <div style="text-align:center;">
                <div style="font-size:10px; letter-spacing:0.15em; text-transform:uppercase;
                            color:var(--text-dim); margin-bottom:10px;">
                    Tarea asociada
                    <span style="color:rgba(201,168,76,0.6); font-size:9px; display:block; margin-top:2px; letter-spacing:0.05em; text-transform:none;">
                        Solo Estudios / Laboral
                    </span>
                </div>
                <select id="task-select" class="form-input" style="width:190px; font-size:13px; padding:8px 12px;">
                    <option value="">Sin tarea</option>
                    @forelse($tareas as $tarea)
                        <option value="{{ $tarea->id }}">
                            {{ $tarea->emoji ? $tarea->emoji . ' ' : '' }}{{ Str::limit($tarea->titulo, 28) }}
                            ({{ $tarea->categoria->nombre ?? '' }})
                        </option>
                    @empty
                        <option value="" disabled>Sin tareas disponibles</option>
                    @endforelse
                </select>
            </div>
        </div>

        {{-- ── Presets de tiempo rápidos ── --}}
        <div style="display:flex; gap:8px; flex-wrap:wrap; justify-content:center;">
            @foreach([
                [25, 5,  'Clásico'],
                [50, 10, 'Deep work'],
                [15, 3,  'Sprint'],
            ] as [$s, $b, $label])
            <button type="button" class="preset-btn" onclick="applyPreset({{ $s }}, {{ $b }})">
                {{ $label }} <span style="opacity:0.5;">({{ $s }}/{{ $b }})</span>
            </button>
            @endforeach
        </div>

    </div>

    {{-- ── Sidebar: historial ── --}}
    <aside>
        <div class="panel">
            <div style="padding:16px 18px; border-bottom:1px solid var(--border-subtle);
                        font-size:11px; font-weight:500; letter-spacing:0.12em;
                        text-transform:uppercase; color:var(--text-dim);">
                Últimas sesiones
            </div>

            @forelse($historial as $sesion)
            @php
                $isComp = $sesion->estado === 'completada';
                $color  = $isComp
                    ? '#4dcfcf'
                    : ($sesion->estado === 'activa' ? '#88aaff' : 'rgba(255,100,80,0.55)');
                $icon   = $isComp
                    ? 'fa-circle-check'
                    : ($sesion->estado === 'activa' ? 'fa-rotate' : 'fa-circle-xmark');
            @endphp
            <div class="historial-row">
                <i class="fa-regular {{ $icon }}" style="color:{{ $color }}; font-size:14px; flex-shrink:0;"></i>
                <div style="flex:1; min-width:0;">
                    <div style="font-size:13px; color:var(--star-white);">
                        {{ $sesion->duracion_estudio }}m / {{ $sesion->duracion_descanso }}m
                        @if($sesion->duracion_real !== null && $isComp)
                            <span style="color:var(--text-dim); font-size:11px;">— {{ $sesion->duracion_real }} min</span>
                        @endif
                    </div>
                    <div style="font-size:11px; color:var(--text-dim); margin-top:2px;">
                        @if($sesion->tarea)
                            <span>{{ Str::limit($sesion->tarea->titulo, 22) }}</span> ·
                        @endif
                        {{ $sesion->created_at->isoFormat('D MMM, H:mm') }}
                    </div>
                </div>
            </div>
            @empty
            <div style="padding:32px; text-align:center; color:var(--text-dim); font-size:13px;">
                <div style="font-size:24px; margin-bottom:8px;">⏱️</div>
                Todavía no hay sesiones registradas.
            </div>
            @endforelse
        </div>
    </aside>

</div>
@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    const CSRF        = document.querySelector('meta[name="csrf-token"]').content;
    const CIRCUMFERENCE = 2 * Math.PI * 104; // ≈ 653.45

    /* ── DOM ─────────────────────────────────────────── */
    const timerDisplay = document.getElementById('timer-display');
    const phaseBadge   = document.getElementById('phase-badge');
    const cycleInfo    = document.getElementById('cycle-info');
    const ringArc      = document.getElementById('ring-arc');
    const ringWrapper  = document.getElementById('ring-wrapper');
    const btnStart     = document.getElementById('btn-start');
    const btnPause     = document.getElementById('btn-pause');
    const btnCancel    = document.getElementById('btn-cancel');
    const studyInput   = document.getElementById('study-time');
    const breakInput   = document.getElementById('break-time');
    const taskSelect   = document.getElementById('task-select');
    const phaseFlash   = document.getElementById('phase-flash');

    /* ── State ───────────────────────────────────────── */
    let intervalId   = null;
    let sesionId     = null;
    let secondsLeft  = 0;
    let totalSeconds = 0;
    let isStudy      = true;
    let isPaused     = false;
    let cycleCount   = 1;
    let sessionStart = null;

    /* ── Helpers ─────────────────────────────────────── */
    const pad  = n => String(n).padStart(2, '0');
    const fmt  = s => `${pad(Math.floor(s / 60))}:${pad(s % 60)}`;

    function setRing(remaining, total, color) {
        const offset = CIRCUMFERENCE * (1 - remaining / total);
        ringArc.style.strokeDashoffset = offset;
        ringArc.style.stroke = color;
        timerDisplay.style.color = color;
    }

    function setPhaseUI(study) {
        const color = study ? '#4dcfcf' : '#88aaff';
        phaseBadge.textContent          = study ? 'Estudio' : 'Descanso';
        phaseBadge.style.background     = study ? 'rgba(77,207,207,0.1)'  : 'rgba(136,170,255,0.1)';
        phaseBadge.style.border         = `1px solid ${study ? 'rgba(77,207,207,0.22)' : 'rgba(136,170,255,0.22)'}`;
        phaseBadge.style.color          = color;
        // Cambiar clase de glow en el SVG wrapper (no en el panel)
        ringWrapper.classList.remove('ring-glow-study', 'ring-glow-break');
        ringWrapper.classList.add(study ? 'ring-glow-study' : 'ring-glow-break');
    }

    function flashPhase(study) {
        phaseFlash.style.background = study ? 'rgba(77,207,207,0.10)' : 'rgba(136,170,255,0.10)';
        phaseFlash.style.opacity    = '1';
        setTimeout(() => { phaseFlash.style.opacity = '0'; }, 500);
    }

    function updateTitle(secs) {
        document.title = `${isStudy ? '🔴' : '🟢'} ${fmt(secs)} — Orbitally`;
    }

    /* ── Lock / unlock config ────────────────────────── */
    function lockConfig(lock) {
        studyInput.disabled = lock;
        breakInput.disabled = lock;
        taskSelect.disabled = lock;
        document.querySelectorAll('.adj-btn, .preset-btn').forEach(b => b.disabled = lock);
    }

    /* ── API ─────────────────────────────────────────── */
    async function apiPost(url, body) {
        const r = await fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify(body),
        });
        return r.json();
    }
    async function apiPatch(url, body) {
        const r = await fetch(url, {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify(body),
        });
        return r.json();
    }

    /* ── Timer ───────────────────────────────────────── */
    function tick() {
        if (isPaused) return;
        secondsLeft--;
        timerDisplay.textContent = fmt(secondsLeft);
        setRing(secondsLeft, totalSeconds, isStudy ? '#4dcfcf' : '#88aaff');
        updateTitle(secondsLeft);
        if (secondsLeft <= 0) phaseEnd();
    }

    function phaseEnd() {
        clearInterval(intervalId);
        intervalId = null;

        if (isStudy) {
            // Cerrar sesión de estudio
            finishSession('completada', parseInt(studyInput.value, 10));
            // Pasar a descanso
            isStudy      = false;
            totalSeconds = parseInt(breakInput.value, 10) * 60;
            secondsLeft  = totalSeconds;
            flashPhase(false);
            setPhaseUI(false);
            timerDisplay.textContent = fmt(secondsLeft);
            setRing(secondsLeft, totalSeconds, '#88aaff');
            intervalId = setInterval(tick, 1000);
        } else {
            // Fin de descanso → nuevo ciclo de estudio
            cycleCount++;
            cycleInfo.textContent = `Ciclo ${cycleCount}`;
            isStudy      = true;
            totalSeconds = parseInt(studyInput.value, 10) * 60;
            secondsLeft  = totalSeconds;
            flashPhase(true);
            setPhaseUI(true);
            timerDisplay.textContent = fmt(secondsLeft);
            setRing(secondsLeft, totalSeconds, '#4dcfcf');
            startNewStudy();
        }
    }

    async function startNewStudy() {
        const data = await apiPost('{{ route("pomodoro.store") }}', {
            duracion_estudio:  parseInt(studyInput.value, 10),
            duracion_descanso: parseInt(breakInput.value, 10),
            tarea_id:          taskSelect.value || null,
        });
        sesionId     = data.id;
        sessionStart = Date.now();
        intervalId   = setInterval(tick, 1000);
    }

    async function finishSession(estado, duracion_real) {
        if (!sesionId) return;
        await apiPatch(`/pomodoro/${sesionId}/finish`, { estado, duracion_real });
        sesionId = null;
    }

    /* ── Botones ─────────────────────────────────────── */
    btnStart.addEventListener('click', async () => {
        // Resume desde pausa
        if (isPaused) {
            isPaused = false;
            btnStart.innerHTML  = '<i class="fa-solid fa-play"></i> Iniciar';
            btnStart.disabled   = true;
            btnPause.disabled   = false;
            btnPause.innerHTML  = '<i class="fa-solid fa-pause"></i> Pausar';
            intervalId = setInterval(tick, 1000);
            return;
        }

        // Arranque fresco
        isStudy      = true;
        cycleCount   = 1;
        cycleInfo.textContent = 'Ciclo 1';
        totalSeconds = parseInt(studyInput.value, 10) * 60;
        secondsLeft  = totalSeconds;

        setPhaseUI(true);
        timerDisplay.textContent = fmt(secondsLeft);
        setRing(secondsLeft, totalSeconds, '#4dcfcf');

        lockConfig(true);

        btnStart.disabled  = true;
        btnPause.disabled  = false;
        btnCancel.disabled = false;

        const data = await apiPost('{{ route("pomodoro.store") }}', {
            duracion_estudio:  parseInt(studyInput.value, 10),
            duracion_descanso: parseInt(breakInput.value, 10),
            tarea_id:          taskSelect.value || null,
        });
        sesionId     = data.id;
        sessionStart = Date.now();
        intervalId   = setInterval(tick, 1000);
    });

    btnPause.addEventListener('click', () => {
        isPaused = true;
        clearInterval(intervalId);
        intervalId = null;

        btnPause.disabled  = true;
        btnStart.disabled  = false;
        btnStart.innerHTML = '<i class="fa-solid fa-play"></i> Reanudar';
    });

    btnCancel.addEventListener('click', async () => {
        if (!confirm('¿Cancelar la sesión actual?')) return;
        clearInterval(intervalId);
        intervalId = null;

        if (sesionId && isStudy) {
            const elapsed = Math.round((Date.now() - sessionStart) / 60000);
            await finishSession('cancelada', elapsed);
        } else if (sesionId) {
            await finishSession('cancelada', 0);
        }

        document.title = 'Orbitally — Pomodoro';
        resetUI();
        location.reload();
    });

    function resetUI() {
        clearInterval(intervalId);
        intervalId  = null;
        isPaused    = false;
        sesionId    = null;
        isStudy     = true;
        cycleCount  = 1;
        cycleInfo.textContent = 'Ciclo 1';

        const studyMins  = parseInt(studyInput.value, 10);
        totalSeconds     = studyMins * 60;
        secondsLeft      = totalSeconds;

        timerDisplay.textContent = fmt(secondsLeft);
        setRing(secondsLeft, totalSeconds, '#4dcfcf');
        setPhaseUI(true);
        ringWrapper.classList.remove('ring-glow-study', 'ring-glow-break');

        btnStart.disabled  = false;
        btnStart.innerHTML = '<i class="fa-solid fa-play"></i> Iniciar';
        btnPause.disabled  = true;
        btnPause.innerHTML = '<i class="fa-solid fa-pause"></i> Pausar';
        btnCancel.disabled = true;
        lockConfig(false);
    }

    /* ── Ajustes de tiempo ───────────────────────────── */
    window.adjustTime = function (type, delta) {
        if (studyInput.disabled) return;
        if (type === 'study') {
            const val = Math.min(120, Math.max(1, parseInt(studyInput.value || 25) + delta));
            studyInput.value = val;
            if (!intervalId && isStudy) {
                totalSeconds = val * 60;
                secondsLeft  = totalSeconds;
                timerDisplay.textContent = fmt(secondsLeft);
                setRing(secondsLeft, totalSeconds, '#4dcfcf');
            }
        } else {
            breakInput.value = Math.min(60, Math.max(1, parseInt(breakInput.value || 5) + delta));
        }
    };

    window.applyPreset = function (study, brk) {
        if (studyInput.disabled) return;
        studyInput.value = study;
        breakInput.value = brk;
        totalSeconds = study * 60;
        secondsLeft  = totalSeconds;
        timerDisplay.textContent = fmt(secondsLeft);
        setRing(secondsLeft, totalSeconds, '#4dcfcf');
    };

    /* ── Init ────────────────────────────────────────── */
    ringArc.style.strokeDasharray  = CIRCUMFERENCE;
    ringArc.style.strokeDashoffset = 0;
    timerDisplay.textContent = fmt(parseInt(studyInput.value, 10) * 60);

    studyInput.addEventListener('input', () => {
        if (intervalId || isPaused) return;
        const val    = Math.max(1, Math.min(120, parseInt(studyInput.value) || 25));
        totalSeconds = val * 60;
        secondsLeft  = totalSeconds;
        timerDisplay.textContent = fmt(secondsLeft);
        setRing(secondsLeft, totalSeconds, '#4dcfcf');
    });

})();
</script>
@endpush