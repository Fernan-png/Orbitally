@extends('layouts.auth')
@section('title', 'Iniciar Sesión')

@section('content')
    <div class="auth-logo">orbitally</div>
    <div class="auth-subtitle">Iniciar sesión</div>
    <div class="auth-separator"></div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
            <label class="form-label" for="email">Correo electrónico</label>
            <input type="email" id="email" name="email" class="form-input @error('email') input-error @enderror"
                   placeholder="tu@email.com" value="{{ old('email') }}" required autofocus>
            @error('email')
                <span class="field-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="password">Contraseña</label>
            <input type="password" id="password" name="password" class="form-input @error('password') input-error @enderror"
                   placeholder="••••••••" required>
            @error('password')
                <span class="field-error">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn-submit">Entrar</button>
    </form>

    <div class="auth-footer">
        ¿No tienes cuenta? <a href="{{ route('register') }}">Regístrate</a>
    </div>
@endsection
