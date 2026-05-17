@extends('layouts.auth')
@section('title', 'Registro')

@section('content')
    <div class="auth-logo">Orbitally</div>
    <div class="auth-subtitle">Crear cuenta</div>
    <div class="auth-separator"></div>

    <form method="POST" action="{{ route('register') }}">
        @csrf
        
        <div class="form-group">
            <label class="form-label" for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" class="form-input @error('nombre') input-error @enderror"
                   placeholder="Tu nombre" value="{{ old('nombre') }}" required autofocus>
            @error('nombre')
                <span class="field-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="email">Correo electrónico</label>
            <input type="email" id="email" name="email" class="form-input @error('email') input-error @enderror"
                   placeholder="tu@email.com" value="{{ old('email') }}" required>
            @error('email')
                <span class="field-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="password">Contraseña</label>
            <input type="password" id="password" name="password" class="form-input @error('password') input-error @enderror"
                   placeholder="Mínimo 8 caracteres" required>
            @error('password')
                <span class="field-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="password_confirmation">Confirmar contraseña</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-input @error('password_confirmation') input-error @enderror"
                   placeholder="Repite la contraseña" required>
            @error('password_confirmation')
                <span class="field-error">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn-submit">Crear cuenta</button>
    </form>

    <div class="auth-footer">
        ¿Ya tienes cuenta? <a href="{{ route('login') }}">Iniciar sesión</a>
    </div>
@endsection
