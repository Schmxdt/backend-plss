<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Situacao extends Model
{
    // Definir o nome da tabela explicitamente
    protected $table = 'situacoes';  // Use o nome correto da tabela

    // Defina o que for necessário, como a chave primária ou os campos
    protected $fillable = ['nome'];
}
