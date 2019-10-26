<?php

use App\Models\Grupo;
use App\Models\Produto;
use Illuminate\Database\Seeder;

class GrupoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        (new Grupo([
            'produto_id' => Produto::where('codigo', '5')->first()->id,
            'nome' => __('messages.size'),
            'descricao' => __('messages.size'),
            'tipo' => Grupo::TIPO_INTEIRO,
            'quantidade_minima' => 1,
            'quantidade_maxima' => 1,
            'funcao' => Grupo::FUNCAO_SOMA,
            'ordem' => 1,
        ]))->save();
        (new Grupo([
            'produto_id' => Produto::where('codigo', '5')->first()->id,
            'nome' => __('messages.flavors'),
            'descricao' => __('messages.flavors'),
            'tipo' => Grupo::TIPO_FRACIONADO,
            'quantidade_minima' => 1,
            'quantidade_maxima' => 2,
            'funcao' => Grupo::FUNCAO_MEDIA,
            'ordem' => 2,
        ]))->save();
        (new Grupo([
            'produto_id' => Produto::where('codigo', '5')->first()->id,
            'nome' => __('messages.border'),
            'descricao' => __('messages.border'),
            'tipo' => Grupo::TIPO_INTEIRO,
            'quantidade_minima' => 0,
            'quantidade_maxima' => 1,
            'funcao' => Grupo::FUNCAO_SOMA,
            'ordem' => 3,
        ]))->save();
    }
}
