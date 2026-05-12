<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pomodoro extends Model
{
    protected $table = 'sesiones_pomodoro';

    protected $fillable = [
        'duracion_estudio',
        'duracion_descanso',
        'inicio',
        'fin',
        'duracion_real',
        'estado',
        'tarea_id',
        'usuario_id',
    ];

    protected $casts = [
        'inicio' => 'datetime',
        'fin'    => 'datetime',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function tarea()
    {
        return $this->belongsTo(Tarea::class, 'tarea_id');
    }
}