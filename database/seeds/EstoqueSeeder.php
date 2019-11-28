<?php

use App\Models\Estoque;
use App\Models\Produto;
use App\Models\Setor;
use Illuminate\Database\Seeder;

class EstoqueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $coca_cola = Produto::where('codigo', '1')->first();
        $alface = Produto::where('codigo', '3')->first();
        $tomate = Produto::where('codigo', '4')->first();
        $catupity = Produto::where('codigo', '9')->first();
        $cheddar = Produto::where('codigo', '10')->first();
        $pao = Produto::where('codigo', '11')->first();
        $ovo = Produto::where('codigo', '12')->first();
        $cebola = Produto::where('codigo', '13')->first();
        $bacon = Produto::where('codigo', '14')->first();
        $morango = Produto::where('codigo', '17')->first();
        $chocolate = Produto::where('codigo', '18')->first();
        $leite_condensado = Produto::where('codigo', '19')->first();
        $acai = Produto::where('codigo', '20')->first();
        $oleo = Produto::where('codigo', '22')->first();
        $oregano = Produto::where('codigo', '23')->first();
        $farinha = Produto::where('codigo', '24')->first();

        $setor = Setor::where('nome', __('messages.covered_hall'))->first();

        (new Estoque([
            'produto_id' => $coca_cola->id,
            'setor_id' =>  $setor->id,
            'quantidade' => 24,
            'preco_compra' => 2,
        ]))->calculate()->save();

        (new Estoque([
            'produto_id' => $alface->id,
            'setor_id' =>  $setor->id,
            'quantidade' => 4,
            'preco_compra' => 2,
        ]))->calculate()->save();

        (new Estoque([
            'produto_id' => $tomate->id,
            'setor_id' =>  $setor->id,
            'quantidade' => 30,
            'preco_compra' => 0.5,
        ]))->calculate()->save();

        (new Estoque([
            'produto_id' => $catupity->id,
            'setor_id' =>  $setor->id,
            'quantidade' => 3,
            'preco_compra' => 50,
        ]))->calculate()->save();

        (new Estoque([
            'produto_id' => $cheddar->id,
            'setor_id' =>  $setor->id,
            'quantidade' => 3,
            'preco_compra' => 56,
        ]))->calculate()->save();

        (new Estoque([
            'produto_id' => $pao->id,
            'setor_id' =>  $setor->id,
            'quantidade' => 40,
            'preco_compra' => 0.3,
        ]))->calculate()->save();

        (new Estoque([
            'produto_id' => $ovo->id,
            'setor_id' =>  $setor->id,
            'quantidade' => 30,
            'preco_compra' => 0.33,
        ]))->calculate()->save();

        (new Estoque([
            'produto_id' => $cebola->id,
            'setor_id' =>  $setor->id,
            'quantidade' => 30,
            'preco_compra' => 0.3,
        ]))->calculate()->save();

        (new Estoque([
            'produto_id' => $bacon->id,
            'setor_id' =>  $setor->id,
            'quantidade' => 4,
            'preco_compra' => 30,
        ]))->calculate()->save();

        (new Estoque([
            'produto_id' => $morango->id,
            'setor_id' =>  $setor->id,
            'quantidade' => 1.5,
            'preco_compra' => 40,
        ]))->calculate()->save();

        (new Estoque([
            'produto_id' => $chocolate->id,
            'setor_id' =>  $setor->id,
            'quantidade' => 2,
            'preco_compra' => 20,
        ]))->calculate()->save();

        (new Estoque([
            'produto_id' => $leite_condensado->id,
            'setor_id' =>  $setor->id,
            'quantidade' => 1.05,
            'preco_compra' => 11.43,
        ]))->calculate()->save();

        (new Estoque([
            'produto_id' => $acai->id,
            'setor_id' =>  $setor->id,
            'quantidade' => 3,
            'preco_compra' => 12,
        ]))->calculate()->save();

        (new Estoque([
            'produto_id' => $oleo->id,
            'setor_id' =>  $setor->id,
            'quantidade' => 4.5,
            'preco_compra' => 4.22,
        ]))->calculate()->save();

        (new Estoque([
            'produto_id' => $oregano->id,
            'setor_id' =>  $setor->id,
            'quantidade' => 0.6,
            'preco_compra' => 20,
        ]))->calculate()->save();

        (new Estoque([
            'produto_id' => $farinha->id,
            'setor_id' =>  $setor->id,
            'quantidade' => 15,
            'preco_compra' => 6,
        ]))->calculate()->save();
    }
}
