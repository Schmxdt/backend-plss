<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChamadosTable extends Migration
{
    public function up()
    {
        Schema::create('chamados', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');  // Título do chamado
            $table->foreignId('categoria_id')  // Relacionamento com a tabela categorias
                  ->constrained('categorias')  // A tabela relacionada será categorias
                  ->onDelete('cascade');  // Caso a categoria seja excluída, exclui os chamados relacionados
            $table->text('descricao');  // Descrição do chamado
            $table->date('prazo_solucao');  // Prazo para solução
            $table->foreignId('situacao_id')  // Relacionamento com a tabela situacoes
                  ->constrained('situacoes')  // A tabela relacionada será situacoes
                  ->onDelete('cascade');  // Caso a situação seja excluída, exclui os chamados relacionados
            $table->date('data_criacao')->default(now());  // Data de criação (preenchida automaticamente)
            $table->date('data_solucao')->nullable();  // Data de solução (pode ser nula)
            $table->timestamps();  // Criado_at e atualizado_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('chamados');
    }
}
