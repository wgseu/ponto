<?php

use App\Models\Classificacao;
use Illuminate\Database\Seeder;

class ClassificacaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        (new Classificacao([
            'descricao' => 'Cozinha',
        ]))->save();
        (new Classificacao([
            'descricao' => 'Materiais de Limpeza',
            'classificacao_id' => 1,
        ]))->save();
        (new Classificacao([
            'descricao' => 'Mercadorias',
            'classificacao_id' => 1,
        ]))->save();
    }
}
