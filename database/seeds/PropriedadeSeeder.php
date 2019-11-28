<?php

use App\Models\Propriedade;
use App\Models\Produto;
use App\Models\Grupo;
use Illuminate\Database\Seeder;

class PropriedadeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $product = Produto::where('codigo', '5')->first();
        $group = Grupo::where('produto_id', $product->id)
            ->where('nome', 'Tamanho')
            ->first();

        (new Propriedade([
            'grupo_id' => $group->id,
            'nome' => __('messages.small'),
            'abreviacao' => __('messages.abbreviation_small'),
        ]))->save();
        (new Propriedade([
            'grupo_id' => $group->id,
            'nome' => __('messages.medium'),
            'abreviacao' => __('messages.abbreviation_medium'),
        ]))->save();
        (new Propriedade([
            'grupo_id' => $group->id,
            'nome' => __('messages.large'),
            'abreviacao' => __('messages.abbreviation_large'),
        ]))->save();
    }
}
