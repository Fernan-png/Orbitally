<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'categorias';

    protected $fillable = [
        'nombre',
        'prioridad',
        'color_borde',
        'es_predefinida',
        'usuario_id',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function tareas()
    {
        return $this->hasMany(Tarea::class, 'categoria_id');
    }

    protected $casts = [
        'es_predefinida' => 'boolean',
    ];
}
