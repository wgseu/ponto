<?php

use App\Models\Produto;
use Illuminate\Database\Seeder;

class ProdutoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        (new Produto([
            'codigo' => '1',
            'categoria_id' => 4,
            'unidade_id' => 1,
            'setor_estoque_id' => null,
            'setor_preparo_id' => null,
            'tributacao_id' => null,
            'descricao' => 'Coca Cola 350ml',
            'abreviacao' => null,
            'detalhes' => null,
            'quantidade_minima' => 1,
            'quantidade_maxima' => 100,
            'preco_venda' => 3.5,
            'custo_producao' => 0,
            'tipo' => 'produto',
            'cobrar_servico' => 0,
            'divisivel' => 0,
            'pesavel' => 0,
            'tempo_preparo' => 0,
            'disponivel' => 1,
            'insumo' => 0,
            'avaliacao' => null,
            'estoque' => 3,
            'imagem_url' => null,
            'data_atualizacao' => null,
            'data_arquivado' => null
        ]))->save();
        (new Produto([
            'codigo' => '2',
            'categoria_id' => 3,
            'unidade_id' => 2,
            'setor_estoque_id' => null,
            'setor_preparo_id' => null,
            'tributacao_id' => null,
            'descricao' => 'X-Burguer',
            'abreviacao' => null,
            'detalhes' => null,
            'quantidade_minima' => 1,
            'quantidade_maxima' => 100,
            'preco_venda' => 7.0,
            'custo_producao' => 0,
            'tipo' => 'produto',
            'cobrar_servico' => 0,
            'divisivel' => 0,
            'pesavel' => 0,
            'tempo_preparo' => 0,
            'disponivel' => 1,
            'insumo' => 0,
            'avaliacao' => null,
            'estoque' => 0,
            'imagem_url' => null,
            'data_atualizacao' => null,
            'data_arquivado' => null
        ]))->save();
        (new Produto([
            'codigo' => '3',
            'categoria_id' => 1,
            'unidade_id' => 3,
            'setor_estoque_id' => null,
            'setor_preparo_id' => null,
            'tributacao_id' => null,
            'descricao' => 'Pizza',
            'abreviacao' => null,
            'detalhes' => null,
            'quantidade_minima' => 1,
            'quantidade_maxima' => 100,
            'preco_venda' => 35,
            'custo_producao' => 0,
            'tipo' => 'produto',
            'cobrar_servico' => 0,
            'divisivel' => 1,
            'pesavel' => 0,
            'tempo_preparo' => 0,
            'disponivel' => 1,
            'insumo' => 0,
            'avaliacao' => null,
            'estoque' => 3,
            'imagem_url' => null,
            'data_atualizacao' => null,
            'data_arquivado' => null
        ]))->save();
        (new Produto([
            'codigo' => '4',
            'categoria_id' => 4,
            'unidade_id' => 1,
            'setor_estoque_id' => null,
            'setor_preparo_id' => null,
            'tributacao_id' => null,
            'descricao' => 'X-Bacon',
            'abreviacao' => null,
            'detalhes' => null,
            'quantidade_minima' => 1,
            'quantidade_maxima' => 100,
            'preco_venda' => 10.9,
            'custo_producao' => 0,
            'tipo' => 'produto',
            'cobrar_servico' => 0,
            'divisivel' => 0,
            'pesavel' => 0,
            'tempo_preparo' => 0,
            'disponivel' => 1,
            'insumo' => 0,
            'avaliacao' => null,
            'estoque' => 3,
            'imagem_url' => null,
            'data_atualizacao' => null,
            'data_arquivado' => null
        ]))->save();
        (new Produto([
            'codigo' => '5',
            'categoria_id' => 4,
            'unidade_id' => 1,
            'setor_estoque_id' => null,
            'setor_preparo_id' => null,
            'tributacao_id' => null,
            'descricao' => 'Mussarela',
            'abreviacao' => null,
            'detalhes' => null,
            'quantidade_minima' => 1,
            'quantidade_maxima' => 100,
            'preco_venda' => 3.5,
            'custo_producao' => 0,
            'tipo' => 'produto',
            'cobrar_servico' => 0,
            'divisivel' => 0,
            'pesavel' => 0,
            'tempo_preparo' => 0,
            'disponivel' => 1,
            'insumo' => 0,
            'avaliacao' => null,
            'estoque' => 3,
            'imagem_url' => null,
            'data_atualizacao' => null,
            'data_arquivado' => null
        ]))->save();
    }
}
