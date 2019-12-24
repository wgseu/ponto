<?php

use App\Models\Categoria;
use App\Models\Produto;
use App\Models\Setor;
use App\Models\Unidade;
use App\Util\Upload;
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
        $vegetables_category = Categoria::where('descricao', __('messages.vegetables'))->first();

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
            'imagem' => Upload::getResource('images/products/coca-cola_lata_350ml.png'),
        ]))->save();
        (new Produto([
            'codigo' => '2',
            'categoria_id' => $snacks_category->id,
            'unidade_id' => $un_unity->id,
            'setor_preparo_id' => $kitchen_sector->id,
            'descricao' => __('messages.x_burguer'),
            'preco_venda' => 15.0,
            'tipo' => Produto::TIPO_COMPOSICAO,
            'imagem' => Upload::getResource('images/products/x-burguer.png'),
        ]))->save();
        (new Produto([
            'codigo' => '3',
            'categoria_id' => $vegetables_category->id,
            'unidade_id' => $un_unity->id,
            'setor_estoque_id' => $stock_sector->id,
            'setor_preparo_id' => $pub_sector->id,
            'descricao' => __('messages.lettuce'),
            'tipo' => Produto::TIPO_PRODUTO,
            'divisivel' => true,
            'disponivel' => false,
            'imagem' => Upload::getResource('images/products/alface.png'),
        ]))->save();
        (new Produto([
            'codigo' => '4',
            'categoria_id' => $vegetables_category->id,
            'unidade_id' => $un_unity->id,
            'setor_estoque_id' => $stock_sector->id,
            'setor_preparo_id' => $pub_sector->id,
            'descricao' => __('messages.tomato'),
            'tipo' => Produto::TIPO_PRODUTO,
            'divisivel' => true,
            'disponivel' => false,
            'imagem' => Upload::getResource('images/products/tomate.png'),
        ]))->save();
        (new Produto([
            'codigo' => '5',
            'categoria_id' => $pizza_category->id,
            'unidade_id' => $un_unity->id,
            'setor_preparo_id' => $pizza_sector->id,
            'descricao' => __('messages.pizza'),
            'tipo' => Produto::TIPO_PACOTE,
            'imagem' => Upload::getResource('images/products/pizza.png'),
        ]))->save();
        (new Produto([
            'codigo' => '6',
            'categoria_id' => $snacks_category->id,
            'unidade_id' => $un_unity->id,
            'setor_preparo_id' => $kitchen_sector->id,
            'descricao' => __('messages.x_bacon'),
            'preco_venda' => 18.0,
            'tipo' => Produto::TIPO_COMPOSICAO,
            'imagem' => Upload::getResource('images/products/x-bacon.png'),
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
            'imagem' => Upload::getResource('images/products/pizza_mussarela.png'),
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
            'imagem' => Upload::getResource('images/products/pizza_calabresa.png'),
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
            'imagem' => Upload::getResource('images/products/catupiry.png'),
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
            'imagem' => Upload::getResource('images/products/cheddar.png'),
        ]))->save();
        (new Produto([
            'codigo' => '11',
            'categoria_id' => $snacks_category->id,
            'unidade_id' => $un_unity->id,
            'setor_estoque_id' => $stock_sector->id,
            'descricao' => __('messages.bread'),
            'tipo' => Produto::TIPO_PRODUTO,
            'disponivel' => false,
            'imagem' => Upload::getResource('images/products/pao.png'),
        ]))->save();
        (new Produto([
            'codigo' => '12',
            'categoria_id' => $snacks_category->id,
            'unidade_id' => $un_unity->id,
            'setor_estoque_id' => $stock_sector->id,
            'descricao' => __('messages.egg'),
            'tipo' => Produto::TIPO_PRODUTO,
            'disponivel' => false,
            'imagem' => Upload::getResource('images/products/ovo.png'),
        ]))->save();
        (new Produto([
            'codigo' => '13',
            'categoria_id' => $spices_category->id,
            'unidade_id' => $un_unity->id,
            'setor_estoque_id' => $stock_sector->id,
            'descricao' => __('messages.onion'),
            'tipo' => Produto::TIPO_PRODUTO,
            'divisivel' => true,
            'disponivel' => false,
            'imagem' => Upload::getResource('images/products/cebola.png'),
        ]))->save();
        (new Produto([
            'codigo' => '14',
            'categoria_id' => $pizza_category->id,
            'unidade_id' => $g_unity->id,
            'setor_estoque_id' => $stock_sector->id,
            'descricao' => __('messages.bacon_1kg'),
            'abreviacao' => __('messages.bacon'),
            'tipo' => Produto::TIPO_PRODUTO,
            'divisivel' => true,
            'disponivel' => false,
            'imagem' => Upload::getResource('images/products/bacon.png'),
        ]))->save();
        (new Produto([
            'codigo' => '15',
            'categoria_id' => $snacks_category->id,
            'unidade_id' => $un_unity->id,
            'setor_estoque_id' => $stock_sector->id,
            'descricao' => __('messages.handmade_burger'),
            'abreviacao' => __('messages.burger'),
            'tipo' => Produto::TIPO_COMPOSICAO,
            'disponivel' => false,
            'imagem' => Upload::getResource('images/products/hamburguer_artesanal.png'),
        ]))->save();
        (new Produto([
            'codigo' => '16',
            'categoria_id' => $acai_category->id,
            'unidade_id' => $un_unity->id,
            'descricao' => __('messages.acai'),
            'preco_venda' => 8.00,
            'tipo' => Produto::TIPO_COMPOSICAO,
            'imagem' => Upload::getResource('images/products/acai.png'),
        ]))->save();
        (new Produto([
            'codigo' => '17',
            'categoria_id' => $acai_category->id,
            'unidade_id' => $g_unity->id,
            'setor_estoque_id' => $stock_sector->id,
            'descricao' => __('messages.strawberry_1kg'),
            'abreviacao' => __('messages.strawberry'),
            'tipo' => Produto::TIPO_PRODUTO,
            'divisivel' => true,
            'disponivel' => false,
            'imagem' => Upload::getResource('images/products/morango.png'),
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
            'imagem' => Upload::getResource('images/products/chocolate.png'),
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
            'imagem' => Upload::getResource('images/products/leite_condensado.png'),
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
            'imagem' => Upload::getResource('images/products/polpa_de_acai.png'),

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
            'imagem' => Upload::getResource('images/products/massa_de_pizza.png'),
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
            'imagem' => Upload::getResource('images/products/oleo_de_soja.png'),
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
            'imagem' => Upload::getResource('images/products/oregano.png'),
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
            'imagem' => Upload::getResource('images/products/farinha_de_trigo.png'),
        ]))->save();
        (new Produto([
            'codigo' => '25',
            'categoria_id' => $pizza_category->id,
            'unidade_id' => $un_unity->id,
            'descricao' => __('messages.catupiry_border'),
            'abreviacao' => __('messages.catupiry'),
            'tipo' => Produto::TIPO_COMPOSICAO,
            'disponivel' => false,
            'imagem' => Upload::getResource('images/products/borda_de_catupiry.png'),
        ]))->save();
        (new Produto([
            'codigo' => '26',
            'categoria_id' => $pizza_category->id,
            'unidade_id' => $un_unity->id,
            'descricao' => __('messages.cheddar_border'),
            'abreviacao' => __('messages.cheddar'),
            'tipo' => Produto::TIPO_COMPOSICAO,
            'disponivel' => false,
            'imagem' => Upload::getResource('images/products/borda_de_cheddar.png'),
        ]))->save();
    }
}
