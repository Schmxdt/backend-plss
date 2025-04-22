<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $fillable = ['nome'];

    // Relacionamento com Chamado (um para muitos)
    public function chamados()
    {
        return $this->hasMany(Chamado::class);
    }
}
