<?php

namespace App\Http\Controllers;

use App\Models\Chamado;
use App\Models\Situacao;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class ChamadoController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $search = $request->input('search', '');

        $chamados = Chamado::join('situacoes', 'situacoes.id', '=', 'chamados.situacao_id')
            ->join('categorias', 'categorias.id', '=', 'chamados.categoria_id')
            ->select('chamados.*', 'situacoes.nome as situacao_nome', 'categorias.nome as categoria_nome')
            ->where(function ($query) use ($search) {
                if (!empty($search)) {
                    $query->where('chamados.titulo', 'LIKE', "%$search%")
                        ->orWhere('chamados.descricao', 'LIKE', "%$search%")
                        ->orWhere('situacoes.nome', 'LIKE', "%$search%")
                        ->orWhere('categorias.nome', 'LIKE', "%$search%");
                }
            })
            ->distinct()
            ->get();

        return response()->json($chamados, 200);
    }


    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'categoria_id' => 'required|exists:categorias,id',
            'descricao' => 'required|string',
        ]);

        $situacaoNovo = Situacao::where('nome', 'Novo')->first();

        if (!$situacaoNovo) {
            return response()->json(['error' => 'Situação "Novo" não encontrada.'], 422);
        }

        $validated['situacao_id'] = $situacaoNovo->id;
        $validated['prazo_solucao'] = Carbon::now()->addDays(3)->toDateString();
        $validated['created_at'] = Carbon::now();

        $chamado = Chamado::create($validated);

        return response()->json($chamado, 201);
    }

    public function show($id): JsonResponse
    {
        $chamado = Chamado::join('situacoes', 'situacoes.id', '=', 'chamados.situacao_id')
            ->join('categorias', 'categorias.id', '=', 'chamados.categoria_id')
            ->select('chamados.*', 'situacoes.nome as situacao_nome', 'categorias.nome as categoria_nome')
            ->where('chamados.id', $id)
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$chamado) {
            return response()->json(['error' => 'Chamado não encontrado.'], 404);
        }

        return response()->json($chamado, 200);
    }


    public function update(Request $request, $id): JsonResponse
    {
        $chamado = Chamado::find($id);

        if (!$chamado) {
            return response()->json(['error' => 'Chamado não encontrado.'], 404);
        }

        if ($request->has('situacao_id') && !is_numeric($request->situacao_id)) {
            $situacao = Situacao::where('nome', $request->situacao_id)->first();
            if ($situacao) {
                $request->merge(['situacao_id' => $situacao->id]);
            } else {
                return response()->json(['error' => 'Situação inválida.'], 422);
            }
        }

        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'categoria_id' => 'required|exists:categorias,id',
            'descricao' => 'required|string',
            'situacao_id' => 'required|exists:situacoes,id',
        ]);

        $situacaoResolvido = Situacao::where('nome', 'Resolvido')->first();
        if ($situacaoResolvido && $validated['situacao_id'] == $situacaoResolvido->id) {
            $validated['data_solucao'] = Carbon::now();
        }

        $chamado->update($validated);

        return response()->json($chamado, 200);
    }

    public function destroy($id): JsonResponse
    {
        $chamado = Chamado::find($id);

        if (!$chamado) {
            return response()->json(['error' => 'Chamado não encontrado.'], 404);
        }

        $chamado->delete();

        return response()->json(['message' => 'Chamado excluído com sucesso.'], 200);
    }

    public function percentualDentroPrazo(): JsonResponse
    {
        $situacaoConcluido = Situacao::where('nome', 'Resolvido')->first();

        if (!$situacaoConcluido) {
            return response()->json(['error' => 'Situação "Resolvido" não encontrada.'], 422);
        }

        $total = Chamado::whereMonth('created_at', now()->month)->count();
        $resolvidos = Chamado::whereMonth('created_at', now()->month)
            ->where('situacao_id', $situacaoConcluido->id)
            ->whereColumn('data_solucao', '<=', 'prazo_solucao')
            ->count();

        $percentual = $total > 0 ? round(($resolvidos / $total) * 100, 2) : 0;

        return response()->json(['percentual' => $percentual], 200);
    }

    public function percentualPendente(): JsonResponse
    {
        $situacaoPendente = Situacao::where('nome', 'Pendente')->first();

        if (!$situacaoPendente) {
            return response()->json(['error' => 'Situação "Pendente" não encontrada.'], 422);
        }

        $total = Chamado::whereMonth('created_at', now()->month)->count();
        $pendentes = Chamado::whereMonth('created_at', now()->month)
            ->where('situacao_id', $situacaoPendente->id)
            ->count();

        $percentual = $total > 0 ? round(($pendentes / $total) * 100, 2) : 0;

        return response()->json(['percentual' => $percentual], 200);
    }

    public function percentualAtrasado(): JsonResponse
    {
        $situacaoConcluido = Situacao::where('nome', 'Resolvido')->first();

        if (!$situacaoConcluido) {
            return response()->json(['error' => 'Situação "Resolvido" não encontrada.'], 422);
        }

        $total = Chamado::whereMonth('created_at', now()->month)->count();
        $atrasados = Chamado::whereMonth('created_at', now()->month)
            ->where('situacao_id', $situacaoConcluido->id)
            ->whereColumn('data_solucao', '>', 'prazo_solucao')
            ->count();

        $percentual = $total > 0 ? round(($atrasados / $total) * 100, 2) : 0;

        return response()->json(['percentual' => $percentual], 200);
    }

    public function mediaTempoResolucao(): JsonResponse
    {
        $mediaTempo = Chamado::whereNotNull('data_solucao')
            ->whereMonth('created_at', now()->month)
            // Utilizando a subtração direta de datas para calcular a diferença em dias
            ->selectRaw('avg(EXTRACT(DAY FROM (data_solucao - created_at))) as media_dias_resolucao')
            ->first();

        return response()->json(['media_dias_resolucao' => round($mediaTempo->media_dias_resolucao, 2)], 200);
    }
}
