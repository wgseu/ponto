<?php

use App\Models\Categoria;
use App\Models\Produto;
use App\Models\Setor;
use App\Models\Unidade;
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
        $soda_category = Categoria::where('descricao', __('messages.soda'))->first();
        $snacks_category = Categoria::where('descricao', __('messages.snacks'))->first();
        $pizza_category = Categoria::where('descricao', __('messages.pizza_pastas'))->first();
        $acai_category = Categoria::where('descricao', __('messages.acai'))->first();
        $oil_category = Categoria::where('descricao', __('messages.oils'))->first();
        $spices_category = Categoria::where('descricao', __('messages.spices'))->first();

        $un_unity = Unidade::where('sigla', 'UN')->first();
        $g_unity = Unidade::where('sigla', 'g')->first();

        $pizza_sector = Setor::where('nome', __('messages.pizza_oven'))->first();
        $pub_sector = Setor::where('nome', __('messages.pub'))->first();
        $kitchen_sector = Setor::where('nome', __('messages.kitchen'))->first();
        $stock_sector = Setor::where('nome', __('messages.stock'))->first();
        (new Produto([
            'codigo' => '1',
            'categoria_id' => $soda_category->id,
            'unidade_id' => $un_unity->id,
            'setor_estoque_id' => $stock_sector->id,
            'setor_preparo_id' => $pub_sector->id,
            'descricao' => __('messages.coca_cola_350ml'),
            'preco_venda' => 3.5,
            'tipo' => Produto::TIPO_PRODUTO,
            'imagem_url' => 'images/produtcs/coca-cola_lata_350ml.png',
        ]))->save();
        (new Produto([
            'codigo' => '2',
            'categoria_id' => $snacks_category->id,
            'unidade_id' => $un_unity->id,
            'setor_preparo_id' => $kitchen_sector->id,
            'descricao' => __('messages.x_burguer'),
            'preco_venda' => 15.0,
            'tipo' => Produto::TIPO_COMPOSICAO,
            'imagem_url' => 'images/produtcs/x-burguer.png',
        ]))->save();
        (new Produto([
            'codigo' => '3',
            'categoria_id' => $soda_category->id,
            'unidade_id' => $un_unity->id,
            'setor_estoque_id' => $stock_sector->id,
            'setor_preparo_id' => $pub_sector->id,
            'descricao' => __('messages.lettuce'),
            'tipo' => Produto::TIPO_PRODUTO,
            'divisivel' => true,
            'disponivel' => false,
            'imagem_url' => 'images/produtcs/alface.png',
        ]))->save();
        (new Produto([
            'codigo' => '4',
            'categoria_id' => $soda_category->id,
            'unidade_id' => $un_unity->id,
            'setor_estoque_id' => $stock_sector->id,
            'setor_preparo_id' => $pub_sector->id,
            'descricao' => __('messages.tomato'),
            'tipo' => Produto::TIPO_PRODUTO,
            'divisivel' => true,
            'disponivel' => false,
            'imagem_url' => 'images/produtcs/tomate.png',
        ]))->save();
        (new Produto([
            'codigo' => '5',
            'categoria_id' => $pizza_category->id,
            'unidade_id' => $un_unity->id,
            'setor_preparo_id' => $pizza_sector->id,
            'descricao' => __('messages.pizza'),
            'tipo' => Produto::TIPO_PACOTE,
            'imagem_url' => 'images/produtcs/pizza.png',
        ]))->save();
        (new Produto([
            'codigo' => '6',
            'categoria_id' => $snacks_category->id,
            'unidade_id' => $un_unity->id,
            'setor_preparo_id' => $kitchen_sector->id,
            'descricao' => __('messages.x_bacon'),
            'preco_venda' => 18.0,
            'tipo' => Produto::TIPO_COMPOSICAO,
            'imagem_url' => 'images/produtcs/x_bacon.png',
        ]))->save();
        (new Produto([
            'codigo' => '7',
            'categoria_id' => $pizza_category->id,
            'unidade_id' => $un_unity->id,
            'setor_preparo_id' => $pizza_sector->id,
            'descricao' => __('messages.mozzarella'),
            'preco_venda' => 3.5,
            'tipo' => Produto::TIPO_COMPOSICAO,
            'divisivel' => true,
            'disponivel' => false,
            'imagem_url' => 'images/produtcs/pizza_mussarela.png',
        ]))->save();
        (new Produto([
            'codigo' => '8',
            'categoria_id' => $pizza_category->id,
            'unidade_id' => $un_unity->id,
            'setor_preparo_id' => $pizza_sector->id,
            'descricao' => __('messages.pepperoni'),
            'preco_venda' => 3.5,
            'tipo' => Produto::TIPO_COMPOSICAO,
            'divisivel' => true,
            'disponivel' => false,
            'imagem_url' => 'images/produtcs/pizza_calabresa.png',
        ]))->save();
        (new Produto([
            'codigo' => '9',
            'categoria_id' => $pizza_category->id,
            'unidade_id' => $g_unity->id,
            'setor_estoque_id' => $stock_sector->id,
            'descricao' => __('messages.catupiry_1kg'),
            'tipo' => Produto::TIPO_PRODUTO,
            'divisivel' => true,
            'disponivel' => false,
            'imagem_url' => 'images/produtcs/catupiry.png',
        ]))->save();
        (new Produto([
            'codigo' => '10',
            'categoria_id' => $pizza_category->id,
            'unidade_id' => $g_unity->id,
            'setor_estoque_id' => $stock_sector->id,
            'descricao' => __('messages.cheddar_1kg'),
            'tipo' => Produto::TIPO_PRODUTO,
            'divisivel' => true,
            'disponivel' => false,
            'imagem_url' => 'images/produtcs/cheddar.png',
        ]))->save();
        (new Produto([
            'codigo' => '11',
            'categoria_id' => $pizza_category->id,
            'unidade_id' => $un_unity->id,
            'setor_estoque_id' => $stock_sector->id,
            'descricao' => __('messages.bread'),
            'tipo' => Produto::TIPO_PRODUTO,
            'disponivel' => false,
            'imagem_url' => 'images/produtcs/pao.png',
        ]))->save();
        (new Produto([
            'codigo' => '12',
            'categoria_id' => $pizza_category->id,
            'unidade_id' => $un_unity->id,
            'setor_estoque_id' => $stock_sector->id,
            'descricao' => __('messages.egg'),
            'tipo' => Produto::TIPO_PRODUTO,
            'disponivel' => false,
            'imagem_url' => 'images/produtcs/ovo.png',
        ]))->save();
        (new Produto([
            'codigo' => '13',
            'categoria_id' => $pizza_category->id,
            'unidade_id' => $un_unity->id,
            'setor_estoque_id' => $stock_sector->id,
            'descricao' => __('messages.onion'),
            'tipo' => Produto::TIPO_PRODUTO,
            'divisivel' => true,
            'disponivel' => false,
            'imagem_url' => 'images/produtcs/cebola.png',
        ]))->save();
        (new Produto([
            'codigo' => '14',
            'categoria_id' => $pizza_category->id,
            'unidade_id' => $g_unity->id,
            'setor_estoque_id' => $stock_sector->id,
            'descricao' => __('messages.bacon_1kg'),
            'abreviacao' => __('bacon'),
            'tipo' => Produto::TIPO_PRODUTO,
            'divisivel' => true,
            'disponivel' => false,
            'imagem_url' => 'images/produtcs/bacon.png',
        ]))->save();
        (new Produto([
            'codigo' => '15',
            'categoria_id' => $pizza_category->id,
            'unidade_id' => $un_unity->id,
            'setor_estoque_id' => $stock_sector->id,
            'descricao' => __('messages.handmade_burger'),
            'abreviacao' => _('messages.burger'),
            'tipo' => Produto::TIPO_COMPOSICAO,
            'disponivel' => false,
            'imagem_url' => 'images/produtcs/hamburguer_artesanal.png',
        ]))->save();
        (new Produto([
            'codigo' => '16',
            'categoria_id' => $acai_category->id,
            'unidade_id' => $un_unity->id,
            'descricao' => __('messages.acai'),
            'preco_venda' => 8.00,
            'tipo' => Produto::TIPO_COMPOSICAO,
            'imagem_url' => 'images/produtcs/acai.png',
        ]))->save();
        (new Produto([
            'codigo' => '17',
            'categoria_id' => $acai_category->id,
            'unidade_id' => $g_unity->id,
            'setor_estoque_id' => $stock_sector->id,
            'descricao' => __('messages.strawberry_1kg'),
            'abreviacao' => _('messages.strawberry'),
            'tipo' => Produto::TIPO_PRODUTO,
            'divisivel' => true,
            'disponivel' => false,
            'imagem_url' => 'images/produtcs/morango.png',
        ]))->save();
        (new Produto([
            'codigo' => '18',
            'categoria_id' => $acai_category->id,
            'unidade_id' => $g_unity->id,
            'setor_estoque_id' => $stock_sector->id,
            'descricao' => __('messages.chocolate_1kg'),
            'abreviacao' => __('messages.chocolate'),
            'tipo' => Produto::TIPO_PRODUTO,
            'divisivel' => true,
            'disponivel' => false,
            'imagem_url' => 'images/produtcs/chocolate.png',
        ]))->save();
        (new Produto([
            'codigo' => '19',
            'categoria_id' => $acai_category->id,
            'unidade_id' => $g_unity->id,
            'setor_estoque_id' => $stock_sector->id,
            'descricao' => __('messages.condensed_milk_1kg'),
            'abreviacao' => __('messages.condensed_milk'),
            'tipo' => Produto::TIPO_PRODUTO,
            'divisivel' => true,
            'disponivel' => false,
            'imagem_url' => 'images/produtcs/leite_condensado.png',
        ]))->save();
        (new Produto([
            'codigo' => '20',
            'categoria_id' => $acai_category->id,
            'unidade_id' => $g_unity->id,
            'setor_estoque_id' => $stock_sector->id,
            'descricao' => __('messages.acai_pulp_1kg'),
            'abreviacao' => __('messages.acai'),
            'tipo' => Produto::TIPO_PRODUTO,
            'divisivel' => true,
            'disponivel' => false,
            'imagem_url' => 'images/produtcs/polpa_de_acai.png',

        ]))->save();
        (new Produto([
            'codigo' => '21',
            'categoria_id' => $pizza_category->id,
            'unidade_id' => $g_unity->id,
            'setor_estoque_id' => $stock_sector->id,
            'descricao' => __('messages.pizza_dough'),
            'tipo' => Produto::TIPO_COMPOSICAO,
            'divisivel' => true,
            'disponivel' => false,
            'imagem_url' => 'images/produtcs/massa_de_pizza.png',
        ]))->save();
        (new Produto([
            'codigo' => '22',
            'categoria_id' => $oil_category->id,
            'unidade_id' => $un_unity->id,
            'setor_estoque_id' => $stock_sector->id,
            'descricao' => __('messages.soybean_oil_1L'),
            'tipo' => Produto::TIPO_PRODUTO,
            'divisivel' => true,
            'disponivel' => false,
            'imagem_url' => 'images/produtcs/oleo_de_soja.png',
        ]))->save();
        (new Produto([
            'codigo' => '23',
            'categoria_id' => $spices_category->id,
            'unidade_id' => $g_unity->id,
            'setor_estoque_id' => $stock_sector->id,
            'descricao' => __('messages.oregano_1kg'),
            'abreviacao' => __('messages.oregano'),
            'tipo' => Produto::TIPO_PRODUTO,
            'divisivel' => true,
            'disponivel' => false,
            'imagem_url' => 'images/produtcs/oregano.png',
        ]))->save();
        (new Produto([
            'codigo' => '24',
            'categoria_id' => $pizza_category->id,
            'unidade_id' => $g_unity->id,
            'setor_estoque_id' => $stock_sector->id,
            'descricao' => __('messages.wheat_flour_1kg'),
            'abreviacao' => __('messages.wheat_flour'),
            'tipo' => Produto::TIPO_PRODUTO,
            'divisivel' => true,
            'disponivel' => false,
            'imagem_url' => 'images/produtcs/farinha_de_trigo.png',
        ]))->save();
        (new Produto([
            'codigo' => '25',
            'categoria_id' => $pizza_category->id,
            'unidade_id' => $un_unity->id,
            'descricao' => __('messages.catupiry_border'),
            'abreviacao' => __('messages.catupiry'),
            'tipo' => Produto::TIPO_COMPOSICAO,
            'disponivel' => false,
            'imagem_url' => 'images/produtcs/borda_de_catupiry.png',
        ]))->save();
        (new Produto([
            'codigo' => '26',
            'categoria_id' => $pizza_category->id,
            'unidade_id' => $un_unity->id,
            'descricao' => __('messages.cheddar_border'),
            'abreviacao' => __('messages.cheddar'),
            'tipo' => Produto::TIPO_COMPOSICAO,
            'disponivel' => false,
            'imagem_url' => 'images/produtcs/borda_de_cheddar.png',
        ]))->save();
    }
}
