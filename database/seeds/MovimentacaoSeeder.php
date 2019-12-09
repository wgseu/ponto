<?php

use App\Models\Caixa;
use App\Models\Cliente;
use App\Models\Sessao;
use App\Models\Movimentacao;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;

class MovimentacaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cliente = Cliente::where('status', Cliente::STATUS_ATIVO)->first();
        $caixa = Caixa::where('ativa', true)->first();
        $sessao = Sessao::where('aberta', true)->first();
        (new Movimentacao([
            'sessao_id' => $sessao->id,
            'iniciador_id' => $cliente->id,
            'data_abertura' => Carbon::now(),
            'caixa_id' => $caixa->id,
            'aberta' => true,
        ]))->save();
    }
}
