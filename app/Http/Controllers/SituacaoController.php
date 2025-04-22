<?php

namespace App\Http\Controllers;

use App\Models\Situacao;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SituacaoController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $search = $request->input('search', '');
        $situacoes = Situacao::where('nome', 'LIKE', "%$search%")->get();
        return response()->json($situacoes, 200);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255|unique:situacoes',
        ]);

        $situacao = Situacao::create($validated);

        return response()->json($situacao, 201);
    }

    public function show($id): JsonResponse
    {
        $situacao = Situacao::find($id);

        if (!$situacao) {
            return response()->json(['error' => 'Situação não encontrada.'], 404);
        }

        return response()->json($situacao, 200);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $situacao = Situacao::find($id);

        if (!$situacao) {
            return response()->json(['error' => 'Situação não encontrada.'], 404);
        }

        $validated = $request->validate([
            'nome' => 'required|string|max:255|unique:situacoes,nome,' . $id,
        ]);

        $situacao->update($validated);

        return response()->json($situacao, 200);
    }

    public function destroy($id): JsonResponse
    {
        $situacao = Situacao::find($id);

        if (!$situacao) {
            return response()->json(['error' => 'Situação não encontrada.'], 404);
        }

        $situacao->delete();

        return response()->json(['message' => 'Situação excluída com sucesso.'], 200);
    }
}
