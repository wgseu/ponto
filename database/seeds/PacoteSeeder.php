<?php

use App\Models\Pacote;
use App\Models\Grupo;
use App\Models\Propriedade;
use App\Models\Produto;
use Illuminate\Database\Seeder;

class PacoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $product = Produto::where('codigo', '5')->first();

        $size_group = Grupo::where('produto_id', $product->id)
            ->where('nome', __('messages.size'))
            ->first();

        $small_property = Propriedade::where('grupo_id', $size_group->id)
            ->where('nome', __('messages.small'))
            ->first();
        $medium_property = Propriedade::where('grupo_id', $size_group->id)
            ->where('nome', __('messages.medium'))
            ->first();
        $large_property = Propriedade::where('grupo_id', $size_group->id)
            ->where('nome', __('messages.large'))
            ->first();

        $pizza_small = new Pacote([
            'pacote_id' => $product->id,
            'grupo_id' => $size_group->id,
            'propriedade_id' => $small_property->id,
            'quantidade_minima' => 0,
            'quantidade_maxima' => 1,
            'acrescimo' => 0,
            'selecionado' => false,
            'disponivel' => true,
        ]);
        $pizza_small->save();
        $pizza_medium = new Pacote([
            'pacote_id' => $product->id,
            'grupo_id' => $size_group->id,
            'propriedade_id' => $medium_property->id,
            'quantidade_minima' => 0,
            'quantidade_maxima' => 1,
            'acrescimo' => 0,
            'selecionado' => false,
            'disponivel' => true,
        ]);
        $pizza_medium->save();
        $pizza_large = new Pacote([
            'pacote_id' => $product->id,
            'grupo_id' => $size_group->id,
            'propriedade_id' => $large_property->id,
            'quantidade_minima' => 0,
            'quantidade_maxima' => 1,
            'acrescimo' => 0,
            'selecionado' => true,
            'disponivel' => true,
        ]);
        $pizza_large->save();

        $flavors_group = Grupo::where('produto_id', $product->id)
            ->where('nome', __('messages.flavors'))
            ->first();

        $mozzarella_product = Produto::where('codigo', '7')->first();
        $pepperoni_product = Produto::where('codigo', '8')->first();

        $pizza_mozzarella_small = new Pacote([
            'pacote_id' => $product->id,
            'grupo_id' => $flavors_group->id,
            'produto_id' => $mozzarella_product->id,
            'associacao_id' => $pizza_small->id,
            'quantidade_minima' => 0,
            'quantidade_maxima' => 1,
            'acrescimo' => 32.9,
            'selecionado' => false,
            'disponivel' => true,
        ]);
        $pizza_mozzarella_small->save();
        $pizza_pepperoni_small = new Pacote([
            'pacote_id' => $product->id,
            'grupo_id' => $flavors_group->id,
            'produto_id' => $pepperoni_product->id,
            'associacao_id' => $pizza_small->id,
            'quantidade_minima' => 0,
            'quantidade_maxima' => 1,
            'acrescimo' => 34.9,
            'selecionado' => false,
            'disponivel' => true,
        ]);
        $pizza_pepperoni_small->save();
        $pizza_mozzarella_medium = new Pacote([
            'pacote_id' => $product->id,
            'grupo_id' => $flavors_group->id,
            'produto_id' => $mozzarella_product->id,
            'associacao_id' => $pizza_medium->id,
            'quantidade_minima' => 0,
            'quantidade_maxima' => 2,
            'acrescimo' => 36.9,
            'selecionado' => false,
            'disponivel' => true,
        ]);
        $pizza_mozzarella_medium->save();
        $pizza_pepperoni_medium = new Pacote([
            'pacote_id' => $product->id,
            'grupo_id' => $flavors_group->id,
            'produto_id' => $pepperoni_product->id,
            'associacao_id' => $pizza_medium->id,
            'quantidade_minima' => 0,
            'quantidade_maxima' => 2,
            'acrescimo' => 38.9,
            'selecionado' => false,
            'disponivel' => true,
        ]);
        $pizza_pepperoni_medium->save();
        $pizza_mozzarella_large = new Pacote([
            'pacote_id' => $product->id,
            'grupo_id' => $flavors_group->id,
            'produto_id' => $mozzarella_product->id,
            'associacao_id' => $pizza_large->id,
            'quantidade_minima' => 0,
            'quantidade_maxima' => 3,
            'acrescimo' => 42.9,
            'selecionado' => false,
            'disponivel' => true,
        ]);
        $pizza_mozzarella_large->save();
        $pizza_pepperoni_large = new Pacote([
            'pacote_id' => $product->id,
            'grupo_id' => $flavors_group->id,
            'produto_id' => $pepperoni_product->id,
            'associacao_id' => $pizza_large->id,
            'quantidade_minima' => 0,
            'quantidade_maxima' => 3,
            'acrescimo' => 44.9,
            'selecionado' => false,
            'disponivel' => true,
        ]);
        $pizza_pepperoni_large->save();

        $border_group = Grupo::where('produto_id', $product->id)
            ->where('nome', __('messages.border'))
            ->first();

        $catupiry_border_product = Produto::where('codigo', '25')->first();

        $pizza_catupiry_border_small = new Pacote([
            'pacote_id' => $product->id,
            'grupo_id' => $border_group->id,
            'produto_id' => $catupiry_border_product->id,
            'associacao_id' => $pizza_small->id,
            'quantidade_minima' => 0,
            'quantidade_maxima' => 1,
            'acrescimo' => 0.5,
            'selecionado' => false,
            'disponivel' => true,
        ]);
        $pizza_catupiry_border_small->save();
        $pizza_catupiry_border_medium = new Pacote([
            'pacote_id' => $product->id,
            'grupo_id' => $border_group->id,
            'produto_id' => $catupiry_border_product->id,
            'associacao_id' => $pizza_medium->id,
            'quantidade_minima' => 0,
            'quantidade_maxima' => 1,
            'acrescimo' => 0.8,
            'selecionado' => false,
            'disponivel' => true,
        ]);
        $pizza_catupiry_border_medium->save();
        $pizza_catupiry_border_large = new Pacote([
            'pacote_id' => $product->id,
            'grupo_id' => $border_group->id,
            'produto_id' => $catupiry_border_product->id,
            'associacao_id' => $pizza_large->id,
            'quantidade_minima' => 0,
            'quantidade_maxima' => 1,
            'acrescimo' => 1.2,
            'selecionado' => false,
            'disponivel' => true,
        ]);
        $pizza_catupiry_border_large->save();

        $cheddar_border_product = Produto::where('codigo', '26')->first();

        $pizza_cheddar_border_large = new Pacote([
            'pacote_id' => $product->id,
            'grupo_id' => $border_group->id,
            'produto_id' => $cheddar_border_product->id,
            'associacao_id' => $pizza_large->id,
            'quantidade_minima' => 0,
            'quantidade_maxima' => 1,
            'acrescimo' => 1.2,
            'selecionado' => false,
            'disponivel' => true,
        ]);
        $pizza_cheddar_border_large->save();
        $pizza_cheddar_border_medium = new Pacote([
            'pacote_id' => $product->id,
            'grupo_id' => $border_group->id,
            'produto_id' => $cheddar_border_product->id,
            'associacao_id' => $pizza_medium->id,
            'quantidade_minima' => 0,
            'quantidade_maxima' => 1,
            'acrescimo' => 0.8,
            'selecionado' => false,
            'disponivel' => true,
        ]);
        $pizza_cheddar_border_medium->save();
        $pizza_cheddar_border_small = new Pacote([
            'pacote_id' => $product->id,
            'grupo_id' => $border_group->id,
            'produto_id' => $cheddar_border_product->id,
            'associacao_id' => $pizza_small->id,
            'quantidade_minima' => 0,
            'quantidade_maxima' => 1,
            'acrescimo' => 0.5,
            'selecionado' => false,
            'disponivel' => true,
        ]);
        $pizza_cheddar_border_small->save();
    }
}
