<?php

use App\Models\Banco;
use App\Models\Carteira;
use App\Util\Upload;
use Illuminate\Database\Seeder;

class CarteiraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $banco_brasil = Banco::where('numero', '1')->first();
        $itau = Banco::where('numero', '341')->first();
        $caixa = Banco::where('numero', '104')->first();
        $bradesco = Banco::where('numero', '237')->first();
        $santander = Banco::where('numero', '33')->first();

        (new Carteira([
            'tipo' => Carteira::TIPO_LOCAL,
            'descricao' => __('messages.cash_drawer_number', ['number' => 1]),
            'ativa' => true,
        ]))->save();

        (new Carteira([
            'tipo' => Carteira::TIPO_BANCARIA,
            'descricao' => 'Banco do brasil',
            'ativa' => false,
            'logo' => Upload::getResource('images/wallets/banco_do_brasil.png'),
            'banco_id' => $banco_brasil->id,
            'agencia' => '0001',
            'conta' => '000000',
        ]))->save();

        (new Carteira([
            'tipo' => Carteira::TIPO_BANCARIA,
            'descricao' => 'Itaú',
            'ativa' => false,
            'logo' => Upload::getResource('images/wallets/itau.png'),
            'banco_id' => $itau->id,
            'agencia' => '0001',
            'conta' => '000000',
        ]))->save();

        (new Carteira([
            'tipo' => Carteira::TIPO_BANCARIA,
            'descricao' => 'Caixa',
            'ativa' => false,
            'logo' => Upload::getResource('images/wallets/caixa.png'),
            'banco_id' => $caixa->id,
            'agencia' => '0001',
            'conta' => '000000',
        ]))->save();

        (new Carteira([
            'tipo' => Carteira::TIPO_BANCARIA,
            'descricao' => 'Bradesco',
            'ativa' => false,
            'logo' => Upload::getResource('images/wallets/bradesco.png'),
            'banco_id' => $bradesco->id,
            'agencia' => '0001',
            'conta' => '000000',
        ]))->save();

        (new Carteira([
            'tipo' => Carteira::TIPO_BANCARIA,
            'descricao' => 'Santander',
            'ativa' => false,
            'logo' => Upload::getResource('images/wallets/santander.png'),
            'banco_id' => $santander->id,
            'agencia' => '0001',
            'conta' => '000000',
        ]))->save();
    }
}
