<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    // ── LOGIN ─────────────────────────────────────────────
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    // ── REGISTER ──────────────────────────────────────────
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'nombre'               => ['required', 'string', 'max:100'],
            'email'                => ['required', 'email', 'max:150', 'unique:users'],
            'password'             => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::create([
            'nombre'   => $data['nombre'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // Create default categories for new user
        $defaultCats = [
            ['nombre' => 'Laboral',  'prioridad' => 1, 'color_borde' => '#4dcfcf'],
            ['nombre' => 'Personal', 'prioridad' => 2, 'color_borde' => '#c9a84c'],
            ['nombre' => 'Estudios', 'prioridad' => 3, 'color_borde' => '#88aaff'],
            ['nombre' => 'Ocio',     'prioridad' => 4, 'color_borde' => '#aaffaa'],
        ];

        foreach ($defaultCats as $cat) {
            Categoria::create(array_merge($cat, ['usuario_id' => $user->id]));
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard')->with('success', '¡Bienvenido a Orbitally, ' . $user->nombre . '!');
    }

    // ── LOGOUT ────────────────────────────────────────────
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('welcome');
    }
}
