<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function index()
    {
        $categorias = Auth::user()->categorias()->orderBy('prioridad')->get();
        return view('categories.index', compact('categorias'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'      => 'required|string|max:50',
            'prioridad' => 'nullable|integer|min:1',
            'color_borde' => 'nullable|string|max:7',
        ]);

        Auth::user()->categorias()->create([
            'nombre'      => $data['nombre'],
            'color_borde' => $data['color_borde'] ?? '#4dcfcf',
            'prioridad'   => $request->integer('prioridad', 1),
        ]);

        return back()->with('success', 'Categoría creada correctamente.');
    }

    public function destroy(Categoria $categoria)
    {
        abort_if($categoria->usuario_id !== Auth::id(), 403);
        $categoria->delete();
        return back()->with('success', 'Categoría eliminada.');
    }
}