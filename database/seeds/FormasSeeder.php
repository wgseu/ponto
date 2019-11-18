<?php

use App\Models\Carteira;
use App\Models\Forma;
use Illuminate\Database\Seeder;

class FormasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $carteira = Carteira::all()->first();
        (new Forma([
            'descricao' => __('messages.money'),
            'tipo' => __('messages.money'),
            'carteira_id' => $carteira->id,
            'min_parcelas' => null,
            'max_parcelas' => null,
            'parcelas_sem_juros' => null,
            'juros' => null,
            'ativa' => 'Y',
        ]))->save();
        (new Forma([
            'descricao' => __('messages.credit'),
            'tipo' => __('messages.credit'),
            'carteira_id' => $carteira->id,
            'min_parcelas' => 1,
            'max_parcelas' => 1,
            'parcelas_sem_juros' => 1,
            'juros' => 2.5,
            'ativa' => 'Y',
        ]))->save();
        (new Forma([
            'descricao' => __('messages.debit'),
            'tipo' => __('messages.debit'),
            'carteira_id' => $carteira->id,
            'min_parcelas' => 1,
            'max_parcelas' => 1,
            'parcelas_sem_juros' => 1,
            'juros' => 2.5,
            'ativa' => 'Y',
        ]))->save();
        (new Forma([
            'descricao' => __('messages.vale'),
            'tipo' => __('messages.vale'),
            'carteira_id' => $carteira->id,
            'min_parcelas' => null,
            'max_parcelas' => null,
            'parcelas_sem_juros' => null,
            'juros' => null,
            'ativa' => 'N',
        ]))->save();
        (new Forma([
            'descricao' => __('messages.bank_check'),
            'tipo' => __('messages.bank_check'),
            'carteira_id' => $carteira->id,
            'min_parcelas' => null,
            'max_parcelas' => null,
            'parcelas_sem_juros' => null,
            'juros' => null,
            'ativa' => 'N',
        ]))->save();
        (new Forma([
            'descricao' => __('messages.account'),
            'tipo' => __('messages.crediario'),
            'carteira_id' => $carteira->id,
            'min_parcelas' => null,
            'max_parcelas' => null,
            'parcelas_sem_juros' => null,
            'juros' => null,
            'ativa' => 'N',
        ]))->save();
        (new Forma([
            'descricao' => __('messages.saldo'),
            'tipo' => __('messages.saldo'),
            'carteira_id' => $carteira->id,
            'min_parcelas' => null,
            'max_parcelas' => null,
            'parcelas_sem_juros' => null,
            'juros' => null,
            'ativa' => 'N',
        ]))->save();
    }
}
