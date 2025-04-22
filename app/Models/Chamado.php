<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Situacao;

class Chamado extends Model
{
    protected $fillable = ['titulo', 'descricao', 'categoria_id', 'situacao_id', 'prazo_solucao', 'data_solucao'];

    // Relacionamento com Categoria (muitos para um)
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    // Relacionamento com Situação (muitos para um)
    public function situacao()
    {
        return $this->belongsTo(Situacao::class);
    }
}
