<?php

use App\Models\Carteira;
use App\Models\Forma;
use Illuminate\Database\Seeder;

class FormaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bancaria = Carteira::where('descricao', __('messages.banking'))->first();
        $tesouraria = Carteira::where('descricao', __('messages.treasury'))->first();
        (new Forma([
            'descricao' => __('messages.money'),
            'tipo' => Forma::TIPO_DINHEIRO,
            'carteira_id' => $tesouraria->id,
        ]))->save();
        (new Forma([
            'descricao' => __('messages.credit'),
            'tipo' => Forma::TIPO_CREDITO,
            'carteira_id' => $bancaria->id,
            'min_parcelas' => 1,
            'max_parcelas' => 1,
            'parcelas_sem_juros' => 1,
            'juros' => 2.5,
            'ativa' => true,
        ]))->save();
        (new Forma([
            'descricao' => __('messages.debit'),
            'tipo' => Forma::TIPO_DEBITO,
            'carteira_id' => $bancaria->id,
            'min_parcelas' => 1,
            'max_parcelas' => 1,
            'parcelas_sem_juros' => 1,
            'juros' => 2.5,
            'ativa' => true,
        ]))->save();
        (new Forma([
            'descricao' => __('messages.vale'),
            'tipo' => Forma::TIPO_VALE,
            'carteira_id' => $bancaria->id,
        ]))->save();
        (new Forma([
            'descricao' => __('messages.bank_check'),
            'tipo' => Forma::TIPO_CHEQUE,
            'carteira_id' => $tesouraria->id,
        ]))->save();
        (new Forma([
            'descricao' => __('messages.account'),
            'tipo' => Forma::TIPO_CREDIARIO,
            'carteira_id' => $tesouraria->id,
        ]))->save();
        (new Forma([
            'descricao' => __('messages.saldo'),
            'tipo' => Forma::TIPO_SALDO,
            'carteira_id' => $tesouraria->id,
        ]))->save();
    }
}
