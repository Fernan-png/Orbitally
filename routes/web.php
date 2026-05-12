<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PomodoroController;

// --- Public -------------------------------------
Route::get('/', fn() => view('welcome'))->name('welcome');

// --- Auth ---------------------------------------
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',[AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// --- Protected -----------------------------------
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/calendar',  [DashboardController::class, 'calendar'])->name('calendar');

    // Toggle de tema (requiere auth)
    Route::post('/tema', function () {
        $user = Auth::user();
        $user->tema = $user->tema === 'oscuro' ? 'claro' : 'oscuro';
        $user->save();
        return back();
    })->name('tema.toggle');

    // Tasks (resourceful)
    Route::resource('tasks', TaskController::class)->except(['show']);
    Route::patch('/tasks/{task}/toggle', [TaskController::class, 'toggle'])->name('tasks.toggle');
    Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.status');

    // Categories
    Route::resource('categories', CategoryController::class)->only(['index', 'store', 'destroy']);

    // Pomodoro
    Route::get('/pomodoro',                              [PomodoroController::class, 'index'])->name('pomodoro.index');
    Route::post('/pomodoro/ciclo',                       [PomodoroController::class, 'logCycle'])->name('pomodoro.logCycle');
    Route::post('/pomodoro',                             [PomodoroController::class, 'store'])->name('pomodoro.store');
    Route::patch('/pomodoro/{sesion}/finish',            [PomodoroController::class, 'finish'])->name('pomodoro.finish');
    Route::delete('/pomodoro/historial',                 [PomodoroController::class, 'clearHistory'])->name('pomodoro.clearHistory');
});