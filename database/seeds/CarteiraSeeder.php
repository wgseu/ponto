<?php

use App\Models\Banco;
use App\Models\Carteira;
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
        $banco_brasil = Banco::where('fantasia', 'Banco do Brasil')->first();
        $itau = Banco::where('fantasia', ' 	Itaú Unibanco')->first();
        $caixa = Banco::where('fantasia', 'Caixa Econômica Federal')->first();
        $bradesco = Banco::where('fantasia', 'Bradesco')->first();
        $santander = Banco::where('fantasia', 'Santander')->first();

        (new Carteira([
            'tipo' => Carteira::TIPO_LOCAL,
            'descricao' => __('messages.cash_drawer_number', ['number' => 1]),
            'ativa' => true,
        ]))->save();

        (new Carteira([
            'tipo' => Carteira::TIPO_BANCARIA,
            'descricao' => 'Banco do brasil',
            'ativa' => false,
            'logo' => Upload::getResource('images/banks/banco_do_brasil.png'),
            'banco_id' => $banco_brasil->id,
            'agencia' => '0001',
            'conta' => '000000',
        ]))->save();

        (new Carteira([
            'tipo' => Carteira::TIPO_BANCARIA,
            'descricao' => 'Itaú',
            'ativa' => false,
            'logo' => Upload::getResource('images/banks/itau.png'),
            'banco_id' => $itau->id,
            'agencia' => '0001',
            'conta' => '000000',
        ]))->save();

        (new Carteira([
            'tipo' => Carteira::TIPO_BANCARIA,
            'descricao' => 'Caixa',
            'ativa' => false,
            'logo' => Upload::getResource('images/banks/caixa.png'),
            'banco_id' => $caixa->id,
            'agencia' => '0001',
            'conta' => '000000',
        ]))->save();

        (new Carteira([
            'tipo' => Carteira::TIPO_BANCARIA,
            'descricao' => 'Bradesco',
            'ativa' => false,
            'logo' => Upload::getResource('images/banks/bradesco.png'),
            'banco_id' => $bradesco->id,
            'agencia' => '0001',
            'conta' => '000000',
        ]))->save();

        (new Carteira([
            'tipo' => Carteira::TIPO_BANCARIA,
            'descricao' => 'Santander',
            'ativa' => false,
            'logo' => Upload::getResource('images/banks/santander.png'),
            'banco_id' => $santander->id,
            'agencia' => '0001',
            'conta' => '000000',
        ]))->save();
    }
}
