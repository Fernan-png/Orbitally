<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    // --- LOGIN ----------------------------------------------------------
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required'    => 'El correo electrónico es obligatorio.',
            'email.email'       => 'Introduce un correo electrónico válido.',
            'password.required' => 'La contraseña es obligatoria.',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email'    => 'El correo electrónico o la contraseña son incorrectos.',
        ])->onlyInput('email');
    }

    // --- REGISTER -------------------------------------------------------
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'nombre'   => ['required', 'string', 'max:100'],
            'email'    => ['required', 'email', 'max:150', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'nombre.required'       => 'El nombre es obligatorio.',
            'nombre.string'         => 'El nombre debe ser un texto válido.',
            'nombre.max'            => 'El nombre no puede superar los 100 caracteres.',
            'email.required'        => 'El correo electrónico es obligatorio.',
            'email.email'           => 'Introduce un correo electrónico válido.',
            'email.max'             => 'El correo electrónico no puede superar los 150 caracteres.',
            'email.unique'          => 'Ya existe una cuenta registrada con ese correo electrónico.',
            'password.required'     => 'La contraseña es obligatoria.',
            'password.confirmed'    => 'Las contraseñas no coinciden.',
            'password.min'          => 'La contraseña debe tener al menos 8 caracteres.',
        ]);

        $user = User::create([
            'nombre'   => $data['nombre'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard')->with('success', '¡Bienvenido a Orbitally, ' . $user->nombre . '!');
    }

    // --- LOGOUT ---------------------------------------------------------
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('welcome');
    }
}
