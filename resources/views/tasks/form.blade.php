@extends('layouts.app')
@section('title', isset($task) ? 'Editar tarea' : 'Nueva tarea')

@section('content')
<div class="page-header">
    <a href="{{ route('tasks.index') }}" style="font-size:0.75rem; color:var(--text-dim); text-decoration:none; display:inline-flex; align-items:center; gap:0.3rem; margin-bottom:0.75rem;">← Volver a tareas</a>
    <div class="page-title">{{ isset($task) ? 'Editar tarea' : 'Nueva tarea' }}</div>
</div>

<div class="panel" style="max-width:600px; padding:2rem;">
    @if($errors->any())
        <div class="alert-error">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ isset($task) ? route('tasks.update', $task->id) : route('tasks.store') }}">
        @csrf
        @if(isset($task)) @method('PUT') @endif

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.25rem;">

            <div style="grid-column:span 2;">
                <label class="form-label" for="titulo">Título *</label>
                <input type="text" id="titulo" name="titulo" class="form-input"
                       placeholder="Nombre de la tarea" value="{{ old('titulo', $task->titulo ?? '') }}" required>
            </div>

            <div style="grid-column:span 2;">
                <label class="form-label" for="descripcion">Descripción</label>
                <textarea id="descripcion" name="descripcion" class="form-input" rows="3"
                          placeholder="Descripción opcional..." style="resize:vertical;">{{ old('descripcion', $task->descripcion ?? '') }}</textarea>
            </div>

            <div>
                <label class="form-label" for="categoria_id">Categoría</label>
                <select id="categoria_id" name="categoria_id" class="form-input">
                    <option value="">Sin categoría</option>
                    @foreach($categorias as $cat)
                        <option value="{{ $cat->id }}" {{ old('categoria_id', $task->categoria_id ?? '') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="form-label" for="prioridad">Prioridad</label>
                <select id="prioridad" name="prioridad" class="form-input">
                    <option value="baja" {{ old('prioridad', $task->prioridad ?? 'baja') === 'baja' ? 'selected' : '' }}>Baja</option>
                    <option value="media" {{ old('prioridad', $task->prioridad ?? '') === 'media' ? 'selected' : '' }}>Media</option>
                    <option value="alta" {{ old('prioridad', $task->prioridad ?? '') === 'alta' ? 'selected' : '' }}>Alta</option>
                </select>
            </div>

            @if(isset($task))
            <div>
                <label class="form-label" for="estado">Estado</label>
                <select id="estado" name="estado" class="form-input">
                    <option value="pendiente" {{ old('estado', $task->estado) === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="en_progreso" {{ old('estado', $task->estado) === 'en_progreso' ? 'selected' : '' }}>En progreso</option>
                    <option value="completada" {{ old('estado', $task->estado) === 'completada' ? 'selected' : '' }}>Completada</option>
                </select>
            </div>
            @endif

            <div>
                <label class="form-label" for="fecha_fin">Fecha de vencimiento</label>
                <input type="date" id="fecha_fin" name="fecha_fin" class="form-input"
                       value="{{ old('fecha_fin', isset($task) && $task->fecha_fin ? \Carbon\Carbon::parse($task->fecha_fin)->format('Y-m-d') : '') }}">
            </div>

            <div>
                <label class="form-label" for="emoji">Emoji (opcional)</label>
                <input type="text" id="emoji" name="emoji" class="form-input"
                       placeholder="📌" maxlength="4" value="{{ old('emoji', $task->emoji ?? '') }}"
                       style="font-size:1.2rem;">
            </div>

        </div>

        <!-- Visual options -->
        <div style="margin-top:1.5rem; padding-top:1.5rem; border-top:1px solid var(--border-subtle);">
            <div style="font-size:0.72rem; letter-spacing:0.1em; text-transform:uppercase; color:var(--text-dim); margin-bottom:1rem;">Opciones visuales</div>
            <div style="display:flex; gap:2rem; align-items:center;">
                <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer; font-size:0.82rem; color:rgba(180,200,240,0.7);">
                    <input type="checkbox" name="negrita" value="1" {{ old('negrita', $task->negrita ?? false) ? 'checked' : '' }}
                           style="accent-color:var(--accent-gold);">
                    <strong>Negrita</strong>
                </label>
                <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer; font-size:0.82rem; color:rgba(180,200,240,0.7);">
                    <input type="checkbox" name="cursiva" value="1" {{ old('cursiva', $task->cursiva ?? false) ? 'checked' : '' }}
                           style="accent-color:var(--accent-gold);">
                    <em>Cursiva</em>
                </label>
            </div>
        </div>

        <!-- Actions -->
        <div style="margin-top:2rem; display:flex; gap:0.75rem;">
            <button type="submit" class="btn-primary">
                {{ isset($task) ? 'Guardar cambios' : 'Crear tarea' }}
            </button>
            <a href="{{ route('tasks.index') }}" class="btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection
