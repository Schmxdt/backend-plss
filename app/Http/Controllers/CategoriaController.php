<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CategoriaController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $search = $request->input('search', '');
        $categorias = Categoria::where('nome', 'LIKE', "%$search%")->get();
        return response()->json($categorias, 200);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255|unique:categorias',
        ]);

        $categoria = Categoria::create($validated);

        return response()->json($categoria, 201);
    }

    public function show($id): JsonResponse
    {
        $categoria = Categoria::find($id);

        if (!$categoria) {
            return response()->json(['error' => 'Categoria não encontrada.'], 404);
        }

        return response()->json($categoria, 200);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $categoria = Categoria::find($id);

        if (!$categoria) {
            return response()->json(['error' => 'Categoria não encontrada.'], 404);
        }

        $validated = $request->validate([
            'nome' => 'required|string|max:255|unique:categorias,nome,' . $id,
        ]);

        $categoria->update($validated);

        return response()->json($categoria, 200);
    }

    public function destroy($id): JsonResponse
    {
        $categoria = Categoria::find($id);

        if (!$categoria) {
            return response()->json(['error' => 'Categoria não encontrada.'], 404);
        }

        $categoria->delete();

        return response()->json(['message' => 'Categoria excluída com sucesso.'], 200);
    }
}
