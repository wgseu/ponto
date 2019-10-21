<?php

use App\Models\Composicao;
use App\Models\Produto;
use Illuminate\Database\Seeder;

class ComposicaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        (new Composicao([
            'composicao_id' => Produto::where('codigo', '2')->first()->id,
            'produto_id' => Produto::where('codigo', '3')->first()->id,
            'tipo' => Composicao::TIPO_OPCIONAL,
            'quantidade' => 0.2,
            'valor' => 0,
        ]))->save();
        (new Composicao([
            'composicao_id' => Produto::where('codigo', '2')->first()->id,
            'produto_id' => Produto::where('codigo', '4')->first()->id,
            'tipo' => Composicao::TIPO_OPCIONAL,
            'quantidade' => 0.5,
            'valor' => 0,
        ]))->save();
        (new Composicao([
            'composicao_id' => Produto::where('codigo', '2')->first()->id,
            'produto_id' => Produto::where('codigo', '11')->first()->id,
            'tipo' => Composicao::TIPO_COMPOSICAO,
            'quantidade' => 1,
            'valor' => 0,
        ]))->save();
        (new Composicao([
            'composicao_id' => Produto::where('codigo', '2')->first()->id,
            'produto_id' => Produto::where('codigo', '12')->first()->id,
            'tipo' => Composicao::TIPO_ADICIONAL,
            'quantidade' => 1,
            'valor' => 1,
        ]))->save();
        (new Composicao([
            'composicao_id' => Produto::where('codigo', '2')->first()->id,
            'produto_id' => Produto::where('codigo', '13')->first()->id,
            'tipo' => Composicao::TIPO_OPCIONAL,
            'quantidade' => 0.5,
            'valor' => 0,
        ]))->save();
        (new Composicao([
            'composicao_id' => Produto::where('codigo', '2')->first()->id,
            'produto_id' => Produto::where('codigo', '14')->first()->id,
            'tipo' => Composicao::TIPO_ADICIONAL,
            'quantidade' => 0.2,
            'valor' => 1,
        ]))->save();
        (new Composicao([
            'composicao_id' => Produto::where('codigo', '2')->first()->id,
            'produto_id' => Produto::where('codigo', '15')->first()->id,
            'tipo' => Composicao::TIPO_ADICIONAL,
            'quantidade' => 1,
            'valor' => 2.5,
        ]))->save();
        (new Composicao([
            'composicao_id' => Produto::where('codigo', '6')->first()->id,
            'produto_id' => Produto::where('codigo', '3')->first()->id,
            'tipo' => Composicao::TIPO_OPCIONAL,
            'quantidade' => 0.5,
            'valor' => 0,
        ]))->save();
        (new Composicao([
            'composicao_id' => Produto::where('codigo', '6')->first()->id,
            'produto_id' => Produto::where('codigo', '4')->first()->id,
            'tipo' => Composicao::TIPO_OPCIONAL,
            'quantidade' => 0.5,
            'valor' => 0,
        ]))->save();
        (new Composicao([
            'composicao_id' => Produto::where('codigo', '6')->first()->id,
            'produto_id' => Produto::where('codigo', '11')->first()->id,
            'tipo' => Composicao::TIPO_COMPOSICAO,
            'quantidade' => 1,
            'valor' => 0,
        ]))->save();
        (new Composicao([
            'composicao_id' => Produto::where('codigo', '6')->first()->id,
            'produto_id' => Produto::where('codigo', '12')->first()->id,
            'tipo' => Composicao::TIPO_ADICIONAL,
            'quantidade' => 1,
            'valor' => 1,
        ]))->save();
        (new Composicao([
            'composicao_id' => Produto::where('codigo', '6')->first()->id,
            'produto_id' => Produto::where('codigo', '13')->first()->id,
            'tipo' => Composicao::TIPO_OPCIONAL,
            'quantidade' => 0.5,
            'valor' => 0,
        ]))->save();
        (new Composicao([
            'composicao_id' => Produto::where('codigo', '6')->first()->id,
            'produto_id' => Produto::where('codigo', '14')->first()->id,
            'tipo' => Composicao::TIPO_ADICIONAL,
            'quantidade' => 0.2,
            'valor' => 1,
        ]))->save();
        (new Composicao([
            'composicao_id' => Produto::where('codigo', '6')->first()->id,
            'produto_id' => Produto::where('codigo', '15')->first()->id,
            'tipo' => Composicao::TIPO_ADICIONAL,
            'quantidade' => 1,
            'valor' => 2.5,
        ]))->save();
        (new Composicao([
            'composicao_id' => Produto::where('codigo', '16')->first()->id,
            'produto_id' => Produto::where('codigo', '17')->first()->id,
            'tipo' => Composicao::TIPO_ADICIONAL,
            'quantidade' => 0.08,
            'valor' => 1,
        ]))->save();
        (new Composicao([
            'composicao_id' => Produto::where('codigo', '16')->first()->id,
            'produto_id' => Produto::where('codigo', '18')->first()->id,
            'tipo' => Composicao::TIPO_ADICIONAL,
            'quantidade' => 0.1,
            'valor' => 2.5,
        ]))->save();
        (new Composicao([
            'composicao_id' => Produto::where('codigo', '16')->first()->id,
            'produto_id' => Produto::where('codigo', '19')->first()->id,
            'tipo' => Composicao::TIPO_ADICIONAL,
            'quantidade' => 0.05,
            'valor' => 3,
        ]))->save();
        (new Composicao([
            'composicao_id' => Produto::where('codigo', '16')->first()->id,
            'produto_id' => Produto::where('codigo', '20')->first()->id,
            'tipo' => Composicao::TIPO_COMPOSICAO,
            'quantidade' => 0.3,
            'valor' => 0,
        ]))->save();
        (new Composicao([
            'composicao_id' => Produto::where('codigo', '7')->first()->id,
            'produto_id' => Produto::where('codigo', '21')->first()->id,
            'tipo' => Composicao::TIPO_COMPOSICAO,
            'quantidade' => 0.2,
            'valor' => 0,
        ]))->save();
        (new Composicao([
            'composicao_id' => Produto::where('codigo', '8')->first()->id,
            'produto_id' => Produto::where('codigo', '21')->first()->id,
            'tipo' => Composicao::TIPO_COMPOSICAO,
            'quantidade' => 0.2,
            'valor' => 0,
        ]))->save();
        (new Composicao([
            'composicao_id' => Produto::where('codigo', '21')->first()->id,
            'produto_id' => Produto::where('codigo', '22')->first()->id,
            'tipo' => Composicao::TIPO_COMPOSICAO,
            'quantidade' => 0.01,
            'valor' => 0,
        ]))->save();
        (new Composicao([
            'composicao_id' => Produto::where('codigo', '8')->first()->id,
            'produto_id' => Produto::where('codigo', '13')->first()->id,
            'tipo' => Composicao::TIPO_OPCIONAL,
            'quantidade' => 0.5,
            'valor' => 0,
        ]))->save();
        (new Composicao([
            'composicao_id' => Produto::where('codigo', '7')->first()->id,
            'produto_id' => Produto::where('codigo', '23')->first()->id,
            'tipo' => Composicao::TIPO_OPCIONAL,
            'quantidade' => 0.005,
            'valor' => 0,
        ]))->save();
        (new Composicao([
            'composicao_id' => Produto::where('codigo', '21')->first()->id,
            'produto_id' => Produto::where('codigo', '24')->first()->id,
            'tipo' => Composicao::TIPO_COMPOSICAO,
            'quantidade' => 1,
            'valor' => 0,
        ]))->save();
        (new Composicao([
            'composicao_id' => Produto::where('codigo', '25')->first()->id,
            'produto_id' => Produto::where('codigo', '9')->first()->id,
            'tipo' => Composicao::TIPO_COMPOSICAO,
            'quantidade' => 0.1,
            'valor' => 0,
        ]))->save();
        (new Composicao([
            'composicao_id' => Produto::where('codigo', '26')->first()->id,
            'produto_id' => Produto::where('codigo', '10')->first()->id,
            'tipo' => Composicao::TIPO_COMPOSICAO,
            'quantidade' => 0.1,
            'valor' => 0,
        ]))->save();
    }
}
