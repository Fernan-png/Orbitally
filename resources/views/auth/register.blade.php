@extends('layouts.auth')
@section('title', 'Registro')

@section('content')
    <div class="auth-logo">Orbitally</div>
    <div class="auth-subtitle">Crear cuenta</div>
    <div class="auth-separator"></div>

    @if($errors->any())
        <div class="alert-error">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="form-group">
            <label class="form-label" for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" class="form-input"
                   placeholder="Tu nombre" value="{{ old('nombre') }}" required autofocus>
        </div>

        <div class="form-group">
            <label class="form-label" for="email">Correo electrónico</label>
            <input type="email" id="email" name="email" class="form-input"
                   placeholder="tu@email.com" value="{{ old('email') }}" required>
        </div>

        <div class="form-group">
            <label class="form-label" for="password">Contraseña</label>
            <input type="password" id="password" name="password" class="form-input"
                   placeholder="Mínimo 8 caracteres" required>
        </div>

        <div class="form-group">
            <label class="form-label" for="password_confirmation">Confirmar contraseña</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-input"
                   placeholder="Repite la contraseña" required>
        </div>

        <button type="submit" class="btn-submit">Crear cuenta</button>
    </form>

    <div class="auth-footer">
        ¿Ya tienes cuenta? <a href="{{ route('login') }}">Iniciar sesión</a>
    </div>
@endsection
