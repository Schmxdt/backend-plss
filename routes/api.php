<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\SituacaoController;
use App\Http\Controllers\ChamadoController;

Route::apiResource('categorias', CategoriaController::class);
Route::apiResource('situacoes', SituacaoController::class);
Route::apiResource('chamados', ChamadoController::class);
 
Route::get('chamados-percentual', [ChamadoController::class, 'percentualDentroPrazo']);
Route::get('chamados-percentual-pendente', [ChamadoController::class, 'percentualPendente']);
Route::get('chamados-percentual-atrasado', [ChamadoController::class, 'percentualAtrasado']);
Route::get('chamados-media-tempo-resolucao', [ChamadoController::class, 'mediaTempoResolucao']);
Route::get('chamados-media-tempo-resolucao', [ChamadoController::class, 'mediaTempoResolucao']);