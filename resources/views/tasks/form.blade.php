@extends('layouts.app')
@section('title', isset($task) ? 'Editar tarea' : 'Nueva tarea')

@section('content')
<div class="page-header">
    <a href="{{ route('tasks.index') }}" class="back-link-nav">← Volver a tareas</a>
    <div class="page-title">{{ isset($task) ? 'Editar tarea' : 'Nueva tarea' }}</div>
</div>

<form method="POST" action="{{ isset($task) ? route('tasks.update', $task->id) : route('tasks.store') }}" id="task-form">
    @csrf
    @if(isset($task)) @method('PUT') @endif

    <div class="task-form-layout">

        {{-- Panel principal --}}
        <div class="panel task-main-panel">

            @if($errors->any())
                <div class="alert-error">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <div class="form-grid">

                <div class="form-grid-full">
                    <label class="form-label" for="titulo">Título *</label>
                    <input type="text" id="titulo" name="titulo" class="form-input"
                           placeholder="Nombre de la tarea"
                           value="{{ old('titulo', $task->titulo ?? '') }}" required>
                </div>

                <div class="form-grid-full">
                    <label class="form-label" for="descripcion">Descripción</label>
                    <textarea id="descripcion" name="descripcion" class="form-input" rows="3"
                              placeholder="Descripción opcional..."
                              style="resize:vertical; line-height:1.5;">{{ old('descripcion', $task->descripcion ?? '') }}</textarea>
                </div>

                {{-- Categoría + opción "Otros" --}}
                <div class="form-grid-full">
                    <label class="form-label" for="categoria_id">Categoría</label>
                    <select id="categoria_id" name="categoria_id" class="form-input">
                        <option value="">Sin categoría</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}"
                                    data-color="{{ $cat->color_borde }}"
                                    {{ old('categoria_id', $task->categoria_id ?? '') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->nombre }}
                            </option>
                        @endforeach
                        <option value="__otros__" style="border-top:1px solid var(--border-subtle); color:var(--accent-gold);">
                            + Crear nueva categoría
                        </option>
                    </select>

                    {{-- Mini-form inline para crear nueva categoría --}}
                    <div id="otros-panel" style="display:none; margin-top:12px; background:rgba(139,92,246,0.06);
                         border:1px solid rgba(139,92,246,0.2); border-radius:6px; padding:16px;">
                        <div style="font-size:11px; font-weight:500; letter-spacing:0.1em; text-transform:uppercase;
                                    color:var(--text-dim); margin-bottom:12px;">Nueva categoría</div>
                        <div style="display:flex; gap:10px; align-items:flex-end; flex-wrap:wrap;">
                            <div style="flex:1; min-width:140px;">
                                <label class="form-label" for="nueva_cat_nombre">Nombre *</label>
                                <input type="text" id="nueva_cat_nombre" class="form-input"
                                       placeholder="Ej: Salud, Hogar..." maxlength="50">
                            </div>
                            <div style="flex-shrink:0;">
                                <label class="form-label" for="nueva_cat_color">Color</label>
                                <input type="color" id="nueva_cat_color" value="#4dcfcf"
                                       style="width:42px; height:38px; border:1px solid var(--border-subtle);
                                              border-radius:3px; background:transparent; cursor:pointer; padding:2px;">
                            </div>
                            <div style="display:flex; gap:8px;">
                                <button type="button" id="btn-crear-cat" class="btn-primary" style="padding:9px 16px; font-size:13px;">
                                    Crear
                                </button>
                                <button type="button" id="btn-cancelar-cat" class="btn-secondary" style="padding:9px 12px; font-size:13px;">
                                    ✕
                                </button>
                            </div>
                        </div>
                        <div id="otros-feedback" style="margin-top:8px; font-size:12px; display:none;"></div>
                    </div>
                </div>

                <div>
                    <label class="form-label" for="prioridad">Prioridad</label>
                    <select id="prioridad" name="prioridad" class="form-input">
                        <option value="baja"  {{ old('prioridad', $task->prioridad ?? 'baja')  === 'baja'  ? 'selected' : '' }}>Baja</option>
                        <option value="media" {{ old('prioridad', $task->prioridad ?? '')       === 'media' ? 'selected' : '' }}>Media</option>
                        <option value="alta"  {{ old('prioridad', $task->prioridad ?? '')       === 'alta'  ? 'selected' : '' }}>Alta</option>
                    </select>
                </div>

                @if(isset($task))
                <div>
                    <label class="form-label" for="estado">Estado</label>
                    <select id="estado" name="estado" class="form-input">
                        <option value="pendiente"   {{ old('estado', $task->estado) === 'pendiente'   ? 'selected' : '' }}>Pendiente</option>
                        <option value="en_progreso" {{ old('estado', $task->estado) === 'en_progreso' ? 'selected' : '' }}>En progreso</option>
                        <option value="completada"  {{ old('estado', $task->estado) === 'completada'  ? 'selected' : '' }}>Completada</option>
                    </select>
                </div>
                @endif

                <div>
                    <label class="form-label" for="fecha_fin">Fecha de vencimiento</label>
                    <input type="date" id="fecha_fin" name="fecha_fin" class="form-input"
                           value="{{ old('fecha_fin', isset($task) && $task->fecha_fin
                               ? \Carbon\Carbon::parse($task->fecha_fin)->format('Y-m-d')
                               : request('fecha_fin', '')) }}">
                </div>

                <div class="emoji-picker-wrap">
                    <label class="form-label">Emoji (opcional)</label>

                    <button type="button" id="emoji-trigger" class="emoji-trigger">
                        <span id="emoji-display" class="emoji-display-char">🚀</span>
                        <span id="emoji-label-text" class="emoji-label-text">Elegir emoji</span>
                        <i class="fa-solid fa-chevron-down emoji-chevron" id="emoji-chevron"></i>
                    </button>

                    <input type="hidden" id="emoji" name="emoji"
                           value="{{ old('emoji', $task->emoji ?? '') }}">

                    <div id="emoji-picker" class="emoji-picker-panel" style="display:none;">
                        <div class="emoji-picker-cats" id="emoji-cats"></div>
                        <div class="emoji-picker-grid" id="emoji-grid"></div>
                        <div class="emoji-picker-footer">
                            <button type="button" id="emoji-none-btn" class="emoji-none-btn">
                                Sin emoji
                            </button>
                        </div>
                    </div>
                </div>

            </div>

            <div class="form-section">
                <div class="form-section-label">Opciones visuales</div>
                <div class="form-checkbox-row">
                    <label class="form-checkbox-label">
                        <input type="checkbox" name="negrita" value="1"
                               {{ old('negrita', $task->negrita ?? false) ? 'checked' : '' }}>
                        <strong style="color:var(--star-white);">Negrita</strong>
                    </label>
                    <label class="form-checkbox-label">
                        <input type="checkbox" name="cursiva" value="1"
                               {{ old('cursiva', $task->cursiva ?? false) ? 'checked' : '' }}>
                        <em style="color:var(--star-white);">Cursiva</em>
                    </label>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary">
                    {{ isset($task) ? 'Guardar cambios' : 'Crear tarea' }}
                </button>
                <a href="{{ route('tasks.index') }}" class="btn-secondary">Cancelar</a>
            </div>

        </div>{{-- /task-main-panel --}}

        {{-- Panel lateral Pomodoro (siempre visible) --}}
        <div id="pomodoro-panel" class="panel pomodoro-side-panel pom-locked">

            <div class="pom-header">
                <span class="pom-icon">🍅</span>
                <div>
                    <div class="pom-title">Pomodoro</div>
                    <div class="pom-subtitle">Tiempos de esta tarea</div>
                </div>
            </div>

            {{-- Overlay: se muestra cuando la categoría Pomodoro NO está seleccionada --}}
            <div class="pom-overlay">
                <span class="pom-overlay-icon">🍅</span>
                <p class="pom-overlay-text">
                    Selecciona la categoría <strong>Pomodoro</strong> para configurar los tiempos de estudio y descanso
                </p>
                <span class="pom-overlay-arrow">← Categoría</span>
            </div>

            {{-- Controles (activos solo con categoría Pomodoro) --}}
            <div class="pom-body">

                <div class="pom-field">
                    <label class="form-label" for="pomodoro_estudio">Estudio</label>
                    <div class="pom-control">
                        <button type="button" class="pom-btn" data-target="pomodoro_estudio" data-delta="-5">−5</button>
                        <button type="button" class="pom-btn" data-target="pomodoro_estudio" data-delta="-1">−1</button>
                        <input type="number" id="pomodoro_estudio" name="pomodoro_estudio" class="form-input pom-input"
                               min="1" max="120" value="{{ old('pomodoro_estudio', $task->pomodoro_estudio ?? 25) }}">
                        <button type="button" class="pom-btn" data-target="pomodoro_estudio" data-delta="1">+1</button>
                        <button type="button" class="pom-btn" data-target="pomodoro_estudio" data-delta="5">+5</button>
                    </div>
                    <div class="pom-preview" id="preview-estudio">25:00</div>
                    <div class="pom-hint">minutos</div>
                </div>

                <div class="pom-divider"></div>

                <div class="pom-field">
                    <label class="form-label" for="pomodoro_descanso">Descanso</label>
                    <div class="pom-control">
                        <button type="button" class="pom-btn" data-target="pomodoro_descanso" data-delta="-5">−5</button>
                        <button type="button" class="pom-btn" data-target="pomodoro_descanso" data-delta="-1">−1</button>
                        <input type="number" id="pomodoro_descanso" name="pomodoro_descanso" class="form-input pom-input"
                               min="1" max="60" value="{{ old('pomodoro_descanso', $task->pomodoro_descanso ?? 5) }}">
                        <button type="button" class="pom-btn" data-target="pomodoro_descanso" data-delta="1">+1</button>
                        <button type="button" class="pom-btn" data-target="pomodoro_descanso" data-delta="5">+5</button>
                    </div>
                    <div class="pom-preview pom-preview-break" id="preview-descanso">05:00</div>
                    <div class="pom-hint">minutos</div>
                </div>

                <div class="pom-note">
                    Se aplicarán automáticamente al seleccionar esta tarea en el temporizador.
                </div>

            </div>{{-- /pom-body --}}

        </div>{{-- /pomodoro-side-panel --}}

    </div>{{-- /task-form-layout --}}

</form>

<style>
.task-form-layout {
    display: flex;
    gap: 20px;
    align-items: flex-start;
}

.task-main-panel {
    flex: 1;
    max-width: 600px;
    padding: 28px;
}

/* Pomodoro side panel */
.pomodoro-side-panel {
    width: 290px;
    min-width: 290px;
    padding: 24px;
    border: 1px solid rgba(249,115,22,0.3) !important;
    background: rgba(249,115,22,0.035) !important;
    flex-shrink: 0;
    position: relative;
    overflow: hidden;
}

/* Overlay: visible cuando está bloqueado */
.pom-overlay {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 12px;
    text-align: center;
    padding: 24px 8px;
    transition: opacity 0.25s ease;
}

.pom-overlay-icon {
    font-size: 36px;
    opacity: 0.3;
    filter: grayscale(1);
}

.pom-overlay-text {
    font-size: 12px;
    color: rgba(255,255,255,0.35);
    line-height: 1.6;
    margin: 0;
}

.pom-overlay-text strong {
    color: rgba(249,115,22,0.6);
}

.pom-overlay-arrow {
    font-size: 11px;
    color: rgba(249,115,22,0.4);
    letter-spacing: 0.06em;
    display: flex;
    align-items: center;
    gap: 4px;
    animation: pomArrowPulse 1.8s ease-in-out infinite;
}

@keyframes pomArrowPulse {
    0%, 100% { opacity: 0.4; transform: translateX(0); }
    50%       { opacity: 0.8; transform: translateX(-4px); }
}

/* Body: controles reales */
.pom-body {
    transition: opacity 0.25s ease;
}

/* Estado BLOQUEADO: overlay visible, body oculto */
.pom-locked .pom-overlay { display: flex; }
.pom-locked .pom-body    { display: none; }

/* Estado ACTIVO: overlay oculto, body visible */
.pomodoro-side-panel:not(.pom-locked) .pom-overlay { display: none; }
.pomodoro-side-panel:not(.pom-locked) .pom-body    { display: block; }

/* Header */
.pom-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 20px;
    padding-bottom: 16px;
    border-bottom: 1px solid rgba(249,115,22,0.2);
}

.pom-icon { font-size: 26px; line-height: 1; }

.pom-title {
    font-size: 14px;
    font-weight: 600;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: #F97316;
    line-height: 1.2;
}

.pom-subtitle {
    font-size: 11px;
    color: rgba(249,115,22,0.55);
    margin-top: 2px;
}

/* Fields */
.pom-field {
    margin-bottom: 18px;
}

.pom-control {
    display: flex;
    align-items: center;
    gap: 5px;
    margin-top: 7px;
}

.pom-input {
    text-align: center !important;
    width: 56px !important;
    padding: 8px 4px !important;
    flex-shrink: 0;
    font-size: 15px !important;
    font-weight: 600 !important;
}

.pom-preview {
    font-size: 32px;
    font-weight: 700;
    color: #F97316;
    text-align: center;
    margin-top: 10px;
    letter-spacing: 0.06em;
    font-variant-numeric: tabular-nums;
    line-height: 1;
}

.pom-preview-break {
    color: #22d3ee;
}

.pom-hint {
    font-size: 10px;
    text-align: center;
    color: rgba(249,115,22,0.45);
    margin-top: 4px;
    text-transform: uppercase;
    letter-spacing: 0.1em;
}

.pom-divider {
    height: 1px;
    background: rgba(249,115,22,0.15);
    margin: 18px 0;
}

.pom-note {
    font-size: 11px;
    color: rgba(249,115,22,0.55);
    line-height: 1.5;
    margin-top: 18px;
    padding-top: 14px;
    border-top: 1px solid rgba(249,115,22,0.12);
}

.pom-btn {
    background: rgba(249,115,22,0.1);
    border: 1px solid rgba(249,115,22,0.25);
    color: #F97316;
    border-radius: 4px;
    padding: 6px 8px;
    font-size: 12px;
    cursor: pointer;
    white-space: nowrap;
    flex-shrink: 0;
    transition: background 0.15s;
}

.pom-btn:hover { background: rgba(249,115,22,0.22); }

/* ── Emoji picker ── */
.emoji-picker-wrap { position: relative; }

.emoji-trigger {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: 6px;
    background: var(--input-bg, rgba(255,255,255,0.05));
    border: 1px solid var(--border-subtle, rgba(255,255,255,0.12));
    border-radius: 6px;
    padding: 8px 12px;
    cursor: pointer;
    width: 100%;
    text-align: left;
    color: inherit;
    transition: border-color 0.15s, background 0.15s;
}
.emoji-trigger:hover {
    border-color: rgba(139,92,246,0.5);
    background: rgba(139,92,246,0.06);
}
.emoji-trigger.open {
    border-color: rgba(139,92,246,0.6);
    background: rgba(139,92,246,0.08);
}

.emoji-display-char {
    font-size: 22px;
    line-height: 1;
    min-width: 26px;
    text-align: center;
    opacity: 0.35;         /* placeholder state */
    transition: opacity 0.2s;
}
.emoji-trigger.has-emoji .emoji-display-char { opacity: 1; }

.emoji-label-text {
    flex: 1;
    font-size: 13px;
    color: rgba(255,255,255,0.4);
    transition: color 0.2s;
}
.emoji-trigger.has-emoji .emoji-label-text { color: rgba(255,255,255,0.75); }

.emoji-chevron {
    font-size: 11px;
    color: rgba(255,255,255,0.3);
    transition: transform 0.2s;
}
.emoji-trigger.open .emoji-chevron { transform: rotate(180deg); }

/* Picker dropdown */
.emoji-picker-panel {
    position: absolute;
    z-index: 200;
    left: 0;
    top: calc(100% + 6px);
    width: 300px;
    background: var(--panel-bg, #1a1a2e);
    border: 1px solid rgba(139,92,246,0.3);
    border-radius: 10px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.45);
    overflow: hidden;
    animation: emojiDrop 0.18s ease;
}
@keyframes emojiDrop {
    from { opacity: 0; transform: translateY(-6px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* Category tabs */
.emoji-picker-cats {
    display: flex;
    gap: 2px;
    padding: 8px 8px 0;
    border-bottom: 1px solid rgba(255,255,255,0.07);
    overflow-x: auto;
    scrollbar-width: none;
}
.emoji-picker-cats::-webkit-scrollbar { display: none; }

.emoji-cat-btn {
    flex-shrink: 0;
    background: none;
    border: none;
    font-size: 18px;
    padding: 5px 7px;
    border-radius: 6px;
    cursor: pointer;
    opacity: 0.45;
    transition: opacity 0.15s, background 0.15s;
    line-height: 1;
}
.emoji-cat-btn:hover  { opacity: 0.8; background: rgba(255,255,255,0.06); }
.emoji-cat-btn.active { opacity: 1;   background: rgba(139,92,246,0.18); }

/* Emoji grid */
.emoji-picker-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 2px;
    padding: 8px;
    max-height: 210px;
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: rgba(139,92,246,0.3) transparent;
}
.emoji-picker-grid::-webkit-scrollbar { width: 4px; }
.emoji-picker-grid::-webkit-scrollbar-track { background: transparent; }
.emoji-picker-grid::-webkit-scrollbar-thumb { background: rgba(139,92,246,0.3); border-radius: 2px; }

.emoji-item {
    font-size: 22px;
    line-height: 1;
    text-align: center;
    padding: 5px 2px;
    border-radius: 6px;
    cursor: pointer;
    border: none;
    background: none;
    transition: background 0.12s, transform 0.1s;
}
.emoji-item:hover  { background: rgba(255,255,255,0.1); transform: scale(1.2); }
.emoji-item.selected { background: rgba(139,92,246,0.25); }

/* Footer */
.emoji-picker-footer {
    padding: 6px 8px 8px;
    border-top: 1px solid rgba(255,255,255,0.07);
    display: flex;
    justify-content: flex-end;
}
.emoji-none-btn {
    font-size: 11px;
    color: rgba(255,255,255,0.35);
    background: none;
    border: 1px solid rgba(255,255,255,0.12);
    border-radius: 4px;
    padding: 4px 10px;
    cursor: pointer;
    transition: color 0.15s, border-color 0.15s;
}
.emoji-none-btn:hover { color: rgba(255,255,255,0.6); border-color: rgba(255,255,255,0.25); }

/* Responsive: apila vertical en pantallas pequeñas */
@media (max-width: 860px) {
    .task-form-layout {
        flex-direction: column;
    }
    .task-main-panel {
        max-width: 100%;
        width: 100%;
    }
    .pomodoro-side-panel {
        width: 100%;
        min-width: 0;
    }
    .pom-overlay-arrow {
        display: none;
    }
}
</style>

<script>
var POMODORO_CAT_ID = '{{ $pomodoroCateg->id ?? "" }}';
var CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;

var selectCat   = document.getElementById('categoria_id');
var otrosPanel  = document.getElementById('otros-panel');
var pomPanel    = document.getElementById('pomodoro-panel');
var inputNombre = document.getElementById('nueva_cat_nombre');
var inputColor  = document.getElementById('nueva_cat_color');
var btnCrear    = document.getElementById('btn-crear-cat');
var btnCancelar = document.getElementById('btn-cancelar-cat');
var feedback    = document.getElementById('otros-feedback');

function unlockPomodoro() {
    pomPanel.classList.remove('pom-locked');
    updatePreview('pomodoro_estudio', 'preview-estudio');
    updatePreview('pomodoro_descanso', 'preview-descanso');
}

function lockPomodoro() {
    pomPanel.classList.add('pom-locked');
}

// Al cargar: si la categoría Pomodoro ya estaba seleccionada
if (POMODORO_CAT_ID !== '' && selectCat.value == POMODORO_CAT_ID) {
    unlockPomodoro();
}

selectCat.addEventListener('change', function () {
    otrosPanel.style.display = 'none';

    if (this.value === '__otros__') {
        otrosPanel.style.display = '';
        inputNombre.focus();
        lockPomodoro();
    } else if (POMODORO_CAT_ID !== '' && this.value == POMODORO_CAT_ID) {
        unlockPomodoro();
    } else {
        lockPomodoro();
    }
});

btnCancelar.addEventListener('click', function () {
    otrosPanel.style.display = 'none';
    selectCat.value = '';
    inputNombre.value = '';
    feedback.style.display = 'none';
});

btnCrear.addEventListener('click', function () {
    var nombre = inputNombre.value.trim();
    if (nombre === '') {
        feedback.textContent = 'El nombre no puede estar vacío.';
        feedback.style.color = '#f87171';
        feedback.style.display = '';
        return;
    }
    btnCrear.disabled = true;
    btnCrear.textContent = '...';

    fetch('{{ route("categories.store") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
        body: JSON.stringify({ nombre: nombre, color_borde: inputColor.value })
    })
    .then(function (r) { return r.json(); })
    .then(function (cat) {
        if (cat.id) {
            var nueva = document.createElement('option');
            nueva.value = cat.id;
            nueva.textContent = cat.nombre;
            selectCat.insertBefore(nueva, selectCat.querySelector('option[value="__otros__"]'));
            selectCat.value = cat.id;
            otrosPanel.style.display = 'none';
            inputNombre.value = '';
            feedback.textContent = 'Categoría "' + cat.nombre + '" creada y seleccionada.';
            feedback.style.color = '#4ade80';
            feedback.style.display = '';
            setTimeout(function () { feedback.style.display = 'none'; }, 3000);
        } else {
            feedback.textContent = cat.errors && cat.errors.nombre ? cat.errors.nombre[0] : 'Error al crear la categoría.';
            feedback.style.color = '#f87171';
            feedback.style.display = '';
        }
    })
    .catch(function () {
        feedback.textContent = 'Error al crear la categoría.';
        feedback.style.color = '#f87171';
        feedback.style.display = '';
    })
    .finally(function () {
        btnCrear.disabled = false;
        btnCrear.textContent = 'Crear';
    });
});

// Previsualización de tiempos
function updatePreview(inputId, previewId) {
    var val = parseInt(document.getElementById(inputId).value) || 0;
    document.getElementById(previewId).textContent =
        String(val).padStart(2, '0') + ':00';
}

document.querySelectorAll('.pom-btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
        var input = document.getElementById(btn.dataset.target);
        var nuevo = Math.min(parseInt(input.max), Math.max(parseInt(input.min),
                    parseInt(input.value) + parseInt(btn.dataset.delta)));
        input.value = nuevo;

        if (btn.dataset.target === 'pomodoro_estudio')  updatePreview('pomodoro_estudio',  'preview-estudio');
        if (btn.dataset.target === 'pomodoro_descanso') updatePreview('pomodoro_descanso', 'preview-descanso');
    });
});

document.getElementById('pomodoro_estudio').addEventListener('input', function () {
    updatePreview('pomodoro_estudio', 'preview-estudio');
});
document.getElementById('pomodoro_descanso').addEventListener('input', function () {
    updatePreview('pomodoro_descanso', 'preview-descanso');
});

// ── Emoji picker ──
var EMOJI_CATS = [
    { id: 'frecuentes', icon: '⭐', title: 'Frecuentes',
      list: ['🚀','⭐','🔥','💡','✅','📌','🎯','💪','📝','🏆','❤️','😊','🌟','⚡','🔑','🎁','⏰','🔔'] },
    { id: 'trabajo',    icon: '💼', title: 'Trabajo',
      list: ['💼','📋','📝','💻','🖥️','📊','📈','📉','🗂️','📁','📂','📧','☎️','🖊️','✏️','📏','📐','🔧','⚙️','🔨','🗄️','📦','🖨️','🖱️','⌨️'] },
    { id: 'estudio',    icon: '📚', title: 'Estudio',
      list: ['📚','📖','📓','📔','📒','📕','📗','📘','📙','✏️','🖊️','🖋️','📐','📏','🔬','🔭','🎓','🏫','🧠','💡','🔢','🔤','🧮','📜'] },
    { id: 'salud',      icon: '💪', title: 'Salud',
      list: ['💪','🏃','🚴','🧘','⚽','🏋️','🤸','🏊','🎾','🥗','🍎','💊','🩺','❤️','💤','😴','🥦','🥕','🧃','🏅','🥇','🏐','🎽'] },
    { id: 'creatividad',icon: '🎨', title: 'Creatividad',
      list: ['🎨','🖌️','✏️','🎭','🎬','🎤','🎵','🎶','🎸','🎹','🎲','♟️','🎮','🕹️','📷','🎥','✍️','🖼️','🎺','🎻','🎧','🎙️'] },
    { id: 'simbolos',   icon: '🌟', title: 'Símbolos',
      list: ['⭐','🌟','💫','✨','🔥','⚡','❄️','🌈','☀️','🌙','💎','🏆','🥇','🎯','🎁','🔑','🗝️','❤️','💜','💙','💚','💛','🧡','❌','✅','⚠️','🔖','🔔','💬','💭','🌀','♾️'] },
    { id: 'viajes',     icon: '✈️', title: 'Viajes',
      list: ['🚀','✈️','🚂','🚗','🌍','🌏','🌎','🏔️','🏖️','🏕️','🗺️','🧭','🏠','🏢','🏛️','🗽','⛺','🛸','🚁','⛵','🏙️'] },
];

var emojiInput   = document.getElementById('emoji');
var emojiTrigger = document.getElementById('emoji-trigger');
var emojiDisplay = document.getElementById('emoji-display');
var emojiLabelTx = document.getElementById('emoji-label-text');
var emojiPicker  = document.getElementById('emoji-picker');
var emojiCatsCon = document.getElementById('emoji-cats');
var emojiGridCon = document.getElementById('emoji-grid');
var emojiNoneBtn = document.getElementById('emoji-none-btn');
var activeCat    = 'frecuentes';

function setEmoji(val) {
    emojiInput.value = val;
    if (val) {
        emojiDisplay.textContent = val;
        emojiLabelTx.textContent = val + '  — Cambiar emoji';
        emojiTrigger.classList.add('has-emoji');
    } else {
        emojiDisplay.textContent = '🚀';
        emojiLabelTx.textContent = 'Elegir emoji';
        emojiTrigger.classList.remove('has-emoji');
    }
    renderGrid(activeCat);
}

function renderCats() {
    emojiCatsCon.innerHTML = '';
    EMOJI_CATS.forEach(function (cat) {
        var btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'emoji-cat-btn' + (cat.id === activeCat ? ' active' : '');
        btn.textContent = cat.icon;
        btn.title = cat.title;
        btn.addEventListener('click', function () {
            activeCat = cat.id;
            document.querySelectorAll('.emoji-cat-btn').forEach(function (b) { b.classList.remove('active'); });
            btn.classList.add('active');
            renderGrid(cat.id);
        });
        emojiCatsCon.appendChild(btn);
    });
}

function renderGrid(catId) {
    var cat = EMOJI_CATS.find(function (c) { return c.id === catId; });
    if (!cat) return;
    emojiGridCon.innerHTML = '';
    var current = emojiInput.value;
    cat.list.forEach(function (em) {
        var btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'emoji-item' + (em === current ? ' selected' : '');
        btn.textContent = em;
        btn.title = em;
        btn.addEventListener('click', function () {
            setEmoji(em);
            closePicker();
        });
        emojiGridCon.appendChild(btn);
    });
}

function openPicker() {
    renderCats();
    renderGrid(activeCat);
    emojiPicker.style.display = '';
    emojiTrigger.classList.add('open');
}

function closePicker() {
    emojiPicker.style.display = 'none';
    emojiTrigger.classList.remove('open');
}

emojiTrigger.addEventListener('click', function (e) {
    e.stopPropagation();
    emojiPicker.style.display === 'none' ? openPicker() : closePicker();
});

emojiNoneBtn.addEventListener('click', function () {
    setEmoji('');
    closePicker();
});

document.addEventListener('click', function (e) {
    if (!emojiPicker.contains(e.target) && e.target !== emojiTrigger) {
        closePicker();
    }
});

// Inicializar con el valor actual (si lo hay, en modo edición)
setEmoji(emojiInput.value || '');
</script>
@endsection
