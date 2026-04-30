<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CategoryController;

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

Route::get('/categorias',              [CategoryController::class, 'index'])->name('categorias.index');
Route::post('/categorias',             [CategoryController::class, 'store'])->name('categorias.store');
Route::delete('/categorias/{categoria}', [CategoryController::class, 'destroy'])->name('categorias.destroy');

// --- Protected -----------------------------------
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/calendario', [DashboardController::class, 'calendar'])->name('calendar');

    // Tasks (resourceful)
    Route::resource('tasks', TaskController::class)->except(['show']);
    Route::patch('/tasks/{task}/toggle', [TaskController::class, 'toggle'])->name('tasks.toggle');

    Route::resource('categories', CategoryController::class)->only(['index', 'store', 'destroy']);
});
