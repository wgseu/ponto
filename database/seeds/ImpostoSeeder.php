<?php

use App\Models\Imposto;
use Illuminate\Database\Seeder;

class ImpostoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        (new Imposto([
            'id' => 1,
            'grupo' => Imposto::GRUPO_ICMS,
            'simples' => true,
            'substituicao' => false,
            'codigo' => 102,
            'descricao' => 'Tributada pelo Simples Nacional sem permissão de crédito',
        ]))->save();

        (new Imposto([
            'id' => 2,
            'grupo' => Imposto::GRUPO_ICMS,
            'simples' => true,
            'substituicao' => false,
            'codigo' => 103,
            'descricao' => 'Isenção do ICMS no Simples Nacional para faixa de receita bruta',
        ]))->save();

        (new Imposto([
            'id' => 3,
            'grupo' => Imposto::GRUPO_ICMS,
            'simples' => true,
            'substituicao' => false,
            'codigo' => 300,
            'descricao' => 'Imune',
        ]))->save();

        (new Imposto([
            'id' => 4,
            'grupo' => Imposto::GRUPO_ICMS,
            'simples' => true,
            'substituicao' => false,
            'codigo' => 400,
            'descricao' => 'Não tributada pelo Simples Nacional',
        ]))->save();

        (new Imposto([
            'id' => 5,
            'grupo' => Imposto::GRUPO_ICMS,
            'simples' => true,
            'substituicao' => false,
            'codigo' => 500,
            'descricao' => 'ICMS cobrado anteriormente por substituição tributária (substituído) ou por antecipação',
        ]))->save();
    }
}
