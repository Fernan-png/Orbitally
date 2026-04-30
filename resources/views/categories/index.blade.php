@extends('layouts.app')
@section('title', 'Mis categorías')

@section('content')
<div class="page-header" style="display:flex; align-items:flex-end; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
    <div>
        <div class="page-title">Categorías</div>
        <div class="page-subtitle">Organiza tus tareas con categorías personalizadas</div>
    </div>
</div>

<div style="display:grid; grid-template-columns:1fr 340px; gap:1.5rem; align-items:start;">

    {{-- Lista de categorías --}}
    <div class="panel">
        @forelse($categorias as $cat)
            <div style="padding:1rem 1.5rem; border-bottom:1px solid var(--border-subtle); display:flex; align-items:center; gap:1rem;">
                <div style="width:4px; height:36px; border-radius:2px; flex-shrink:0; background:{{ $cat->color_borde }};"></div>
                <div style="flex:1;">
                    <div style="font-size:0.90rem; color:var(--star-white);">{{ $cat->nombre }}</div>
                    <div style="font-size:0.75rem; color:var(--text-dim); margin-top:2px;">
                        {{ $cat->tareas()->count() }} tareas
                    </div>
                </div>
                <form method="POST" action="{{ route('categorias.destroy', $cat->id) }}"
                      onsubmit="return confirm('¿Eliminar esta categoría? Las tareas asociadas quedarán sin categoría.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="
                        padding:0.35rem 0.75rem;
                        font-family:'Jost',sans-serif;
                        font-size:0.72rem;
                        color:rgba(255,100,80,0.6);
                        background:rgba(255,100,80,0.06);
                        border:1px solid rgba(255,100,80,0.15);
                        border-radius:2px;
                        cursor:pointer;
                    ">Eliminar</button>
                </form>
            </div>
        @empty
            <div style="padding:3rem; text-align:center; color:var(--text-dim); font-size:0.85rem;">
                No tienes categorías todavía. Crea la primera desde el formulario.
            </div>
        @endforelse
    </div>

    {{-- Formulario nueva categoría --}}
    <div class="panel" style="padding:1.5rem;">
        <div style="font-size:0.8rem; font-weight:500; letter-spacing:0.06em; margin-bottom:1.25rem; padding-bottom:0.75rem; border-bottom:1px solid var(--border-subtle);">
            Nueva categoría
        </div>

        @if($errors->any())
            <div class="alert-error" style="margin-bottom:1rem;">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('categorias.store') }}">
            @csrf
            <div style="margin-bottom:1rem;">
                <label class="form-label" for="nombre">Nombre *</label>
                <input type="text" id="nombre" name="nombre" class="form-input"
                       placeholder="Ej: Trabajo, Salud..." value="{{ old('nombre') }}" required>
            </div>
            <div style="margin-bottom:1.5rem;">
                <label class="form-label" for="color_borde">Color</label>
                <div style="display:flex; align-items:center; gap:0.75rem;">
                    <input type="color" id="color_borde" name="color_borde"
                           value="{{ old('color_borde', '#4dcfcf') }}"
                           style="width:40px; height:36px; border:1px solid var(--border-subtle); border-radius:2px; background:transparent; cursor:pointer; padding:2px;">
                    <span style="font-size:0.78rem; color:var(--text-dim);">Color del borde lateral</span>
                </div>
            </div>
            <div style="margin-bottom:1.5rem;">
                <label class="form-label" for="prioridad">Prioridad</label>
                <select id="prioridad" name="prioridad" class="form-input">
                    <option value="1" {{ old('prioridad') == '1' ? 'selected' : '' }}>1 — Más importante</option>
                    <option value="2" {{ old('prioridad') == '2' ? 'selected' : '' }}>2</option>
                    <option value="3" {{ old('prioridad') == '3' ? 'selected' : '' }}>3</option>
                    <option value="4" {{ old('prioridad') == '4' ? 'selected' : '' }}>4 — Menos importante</option>
                </select>
            </div>
            <button type="submit" class="btn-primary" style="width:100%; justify-content:center;">
                Crear categoría
            </button>
        </form>
    </div>
</div>
@endsection