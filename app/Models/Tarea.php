<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tarea extends Model
{
    protected $table = 'tareas';

    protected $fillable = [
        'titulo',
        'descripcion',
        'emoji',
        'color',
        'negrita',
        'cursiva',
        'fecha_inicio',
        'fecha_fin',
        'estado',
        'prioridad',
        'categoria_id',
        'usuario_id',
    ];

    protected $casts = [
        'negrita'     => 'boolean',
        'cursiva'     => 'boolean',
        'fecha_inicio'=> 'datetime',
        'fecha_fin'   => 'datetime',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }
}
