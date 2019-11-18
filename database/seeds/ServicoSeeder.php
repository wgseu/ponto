<?php

use App\Models\Servico;
use Illuminate\Database\Seeder;

class ServicoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        (new Servico([
            'nome' => __('messages.discount'),
            'descricao' => __('messages.discount_description'),
            'tipo' => __('messages.rate'),
            'obrigatorio' => 'N',
            'valor' => 0,
            'individual' => 'N',
            'ativo' => 'Y',
        ]))->save();
        (new Servico([
            'nome' => __('messages.delivery'),
            'descricao' => __('messages.delivery_description'),
            'tipo' => __('messages.rate'),
            'obrigatorio' => 'N',
            'valor' => 0,
            'individual' => 'N',
            'ativo' => 'Y',
        ]))->save();
    }
}
