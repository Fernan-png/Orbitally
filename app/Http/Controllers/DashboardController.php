<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index() {
        $user = Auth::user();

        $stats = [
            'pendientes'  => $user->tareas()->where('estado', 'pendiente')->count(),
            'en_progreso' => $user->tareas()->where('estado', 'en_progreso')->count(),
            'completadas' => $user->tareas()->where('estado', 'completada')->count(),
        ];

        $recentTasks = $user->tareas()
            ->with('categoria')
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        $upcomingTasks = $user->tareas()
            ->whereNotNull('fecha_fin')
            ->where('estado', '!=', 'completada')
            ->where('fecha_fin', '>=', Carbon::today())
            ->orderBy('fecha_fin', 'asc')
            ->take(5)
            ->get();

        return view('dashboard.index', compact('stats', 'recentTasks', 'upcomingTasks'));
    }

    public function calendar(Request $request) {
        $user = Auth::user();

        // Obtenemos el mes y año de la petición, por defecto el actual
        $month = $request->integer('month', now()->month);
        $year  = $request->integer('year', now()->year);

        // Fecha base del mes seleccionado
        $currentDate     = Carbon::createFromDate($year, $month, 1);
        $firstDayOfWeek  = $currentDate->dayOfWeek; // 0 = Domingo
        $daysInMonth     = $currentDate->daysInMonth;

        // Meses anterior y siguiente para la navegación
        $prevDate = $currentDate->copy()->subMonth();
        $nextDate = $currentDate->copy()->addMonth();

        // Tareas con fecha de entrega en el mes actual
        $monthTasks = $user->tareas()
            ->whereNotNull('fecha_fin')
            ->whereYear('fecha_fin', $year)
            ->whereMonth('fecha_fin', $month)
            ->with('categoria')
            ->orderBy('fecha_fin')
            ->get();

        // Agrupamos las tareas por día para pintarlas en el calendario
        $tasksByDate = $monthTasks->groupBy(
            fn($t) => Carbon::parse($t->fecha_fin)->format('Y-m-d')
        );

        return view('dashboard.calendar', compact(
            'currentDate',
            'firstDayOfWeek',
            'daysInMonth',
            'monthTasks',
            'tasksByDate',
        ) + [
            'prevMonth' => $prevDate->month,
            'prevYear'  => $prevDate->year,
            'nextMonth' => $nextDate->month,
            'nextYear'  => $nextDate->year,
        ]);
    }
}
