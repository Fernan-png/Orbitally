<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function index()
    {
        $predefinidas   = Categoria::whereNull('usuario_id')->orderBy('prioridad')->get();
        $personalizadas = Auth::user()->categorias()->orderBy('prioridad')->get();

        $categories = $predefinidas->merge($personalizadas);
        return view('categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'      => 'required|string|max:50',
            'prioridad'   => 'nullable|integer|min:1',
            'color_borde' => 'nullable|string|max:7',
        ]);

        $categoria = Auth::user()->categorias()->create([
            'nombre'         => $data['nombre'],
            'color_borde'    => $data['color_borde'] ?? '#4dcfcf',
            'prioridad'      => $request->integer('prioridad', 1),
            'es_predefinida' => false,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'id'          => $categoria->id,
                'nombre'      => $categoria->nombre,
                'color_borde' => $categoria->color_borde,
            ]);
        }

        return back()->with('success', 'Categoría creada correctamente.');
    }

    public function destroy(Categoria $category)
    {
        abort_if($category->usuario_id !== Auth::id(), 403);
        $category->delete();
        return back()->with('success', 'Categoría eliminada.');
    }
}