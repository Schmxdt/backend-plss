<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('situacoes')->insert([
            ['nome' => 'Resolvido'],
            ['nome' => 'Pendente'],
            ['nome' => 'Novo'],
        ]);
        DB::table('categorias')->insert([
            ['nome' => 'Erro'],
            ['nome' => 'Melhoria'],
            ['nome' => 'Novo'],
        ]);
    }
}
