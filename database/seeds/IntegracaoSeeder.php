<?php

use App\Models\Integracao;
use Illuminate\Database\Seeder;

class IntegracaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        (new Integracao([
            'nome' => 'iFood',
            'codigo' => 'ifood',
            'descricao' => __('messages.integration_module', ['name' => 'iFood']),
            'tipo' => Integracao::TIPO_PEDIDO
        ]))->save();

        (new Integracao([
            'nome' => 'Delivery Much',
            'codigo' => 'delivery_much',
            'descricao' => __('messages.integration_module', ['name' => 'Delivery Much']),
            'tipo' => Integracao::TIPO_PEDIDO
        ]))->save();

        (new Integracao([
            'nome' => 'Uber Eats',
            'codigo' => 'uber_eats',
            'descricao' => __('messages.integration_module', ['name' => 'Uber Eats']),
            'tipo' => Integracao::TIPO_PEDIDO
        ]))->save();

        (new Integracao([
            'nome' => 'Google',
            'codigo' => 'google',
            'descricao' => __('messages.integration_module', ['name' => 'Google']),
            'tipo' => Integracao::TIPO_LOGIN,
        ]))->save();

        (new Integracao([
            'nome' => 'Facebook',
            'codigo' => 'facebook',
            'descricao' => __('messages.integration_module', ['name' => 'Facebook']),
            'tipo' => Integracao::TIPO_LOGIN,
        ]))->save();
    }
}
