@extends('layouts.app')
@section('title', isset($task) ? 'Editar tarea' : 'Nueva tarea')

@section('content')
<div class="page-header">
    <a href="{{ route('tasks.index') }}" class="back-link-nav">← Volver a tareas</a>
    <div class="page-title">{{ isset($task) ? 'Editar tarea' : 'Nueva tarea' }}</div>
</div>

<div class="panel" style="max-width:600px; padding:28px;">

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

            <div>
                <label class="form-label" for="categoria_id">Categoría</label>
                <select id="categoria_id" name="categoria_id" class="form-input">
                    <option value="">Sin categoría</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}"
                                {{ old('categoria_id', $task->categoria_id ?? '') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->nombre }}
                        </option>
                    @endforeach
                </select>
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
                           ? \Carbon\Carbon::parse($task->fecha_fin)->format('Y-m-d') : '') }}">
            </div>

            <div>
                <label class="form-label" for="emoji">Emoji (opcional)</label>
                <input type="text" id="emoji" name="emoji" class="form-input"
                       placeholder="📌" maxlength="4"
                       value="{{ old('emoji', $task->emoji ?? '') }}"
                       style="font-size:20px;">
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
    </form>
</div>
@endsection
