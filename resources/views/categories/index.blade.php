@extends('layouts.app')
@section('title', 'Categorías')

@section('content')
<div class="page-header" style="display:flex; align-items:flex-end; justify-content:space-between; flex-wrap:wrap; gap:16px;">
    <div>
        <div class="page-title">Categorías</div>
        <div class="page-subtitle">Organiza tus tareas con categorías personalizadas</div>
    </div>
</div>

<div style="display:grid; grid-template-columns:1fr 320px; gap:20px; align-items:start;">

    {{-- Lista --}}
    <div class="panel">

        @if($categories->isEmpty())
            <div style="padding:48px; text-align:center; color:var(--text-dim);">
                <div style="font-size:28px; margin-bottom:12px;">🏷️</div>
                <div style="font-size:14px; color:var(--star-white); margin-bottom:6px;">Sin categorías todavía</div>
                <div style="font-size:13px;">Crea tu primera categoría desde el formulario.</div>
            </div>
        @else
            @foreach($categories as $cat)
            <div style="padding:14px 20px; border-bottom:1px solid var(--border-subtle);
                        display:flex; align-items:center; gap:14px; transition:background 0.2s;"
                 class="hover:bg-black/5 dark:hover:bg-white/[0.03]">

                {{-- Color strip --}}
                <div style="width:4px; height:40px; border-radius:2px; flex-shrink:0;
                            background:{{ $cat->color_borde }};
                            box-shadow:0 0 8px {{ $cat->color_borde }}44;"></div>

                {{-- Info --}}
                <div style="flex:1;">
                    <div style="font-size:14px; color:var(--star-white); font-weight:400;">
                        {{ $cat->nombre }}
                    </div>
                    <div style="font-size:12px; color:var(--text-dim); margin-top:3px; display:flex; gap:12px;">
                        <span>{{ $cat->tareas()->count() }} {{ $cat->tareas()->count() === 1 ? 'tarea' : 'tareas' }}</span>
                        @if($cat->es_predefinida ?? false)
                            <span style="color:rgba(201,168,76,0.6);">Predefinida</span>
                        @endif
                    </div>
                </div>

                {{-- Eliminar (solo las personalizadas) --}}
                @if(!($cat->es_predefinida ?? false))
                    <form method="POST" action="{{ route('categories.destroy', $cat->id) }}"
                          data-confirm="¿Eliminar esta categoría? Las tareas asociadas quedarán sin categoría.">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                style="padding:5px 12px; font-family:'Jost',sans-serif; font-size:12px;
                                       color:rgba(255,100,80,0.6); background:rgba(255,100,80,0.05);
                                       border:1px solid rgba(255,100,80,0.14); border-radius:3px; cursor:pointer;
                                       transition:all 0.2s;"
                                onmouseover="this.style.color='#ff6644'; this.style.background='rgba(255,100,80,0.1)';"
                                onmouseout="this.style.color='rgba(255,100,80,0.6)'; this.style.background='rgba(255,100,80,0.05)';">
                            Eliminar
                        </button>
                    </form>
                @else
                    <span style="font-size:11px; color:var(--text-muted); padding:5px 12px;">—</span>
                @endif
            </div>
            @endforeach
        @endif
    </div>

    {{-- Formulario nueva categoría --}}
    <div class="panel" style="padding:20px;">
        <div style="font-size:11px; font-weight:500; letter-spacing:0.1em; text-transform:uppercase;
                    margin-bottom:18px; padding-bottom:12px; border-bottom:1px solid var(--border-subtle);
                    color:var(--text-dim);">
            Nueva categoría
        </div>

        @if($errors->any())
            <div class="alert-error" style="margin-bottom:14px;">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('categories.store') }}">
            @csrf

            <div style="margin-bottom:14px;">
                <label class="form-label" for="nombre">Nombre *</label>
                <input type="text" id="nombre" name="nombre" class="form-input"
                       placeholder="Ej: Trabajo, Salud..."
                       value="{{ old('nombre') }}" required>
            </div>

            <div style="margin-bottom:20px;">
                <label class="form-label" for="color_borde">Color</label>
                <div style="display:flex; align-items:center; gap:10px;">
                    <input type="color" id="color_borde" name="color_borde"
                           value="{{ old('color_borde', '#4dcfcf') }}"
                           style="width:40px; height:36px; border:1px solid var(--border-subtle);
                                  border-radius:3px; background:transparent; cursor:pointer; padding:2px;">
                    <span style="font-size:12px; color:var(--text-dim);">Color del borde lateral</span>
                </div>
            </div>

            <button type="submit" class="btn-primary" style="width:100%; justify-content:center;">
                Crear categoría
            </button>
        </form>
    </div>
</div>
@endsection