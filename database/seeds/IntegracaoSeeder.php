<?php

use App\Models\Integracao;
use App\Util\Upload;
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
            'descricao' => __('messages.integration_module', ['name' => 'iFood']),
        ]))->save();

        (new Integracao([
            'nome' => 'Delivery Much',
            'descricao' => __('messages.integration_module', ['name' => 'Delivery Much']),
        ]))->save();

        (new Integracao([
            'nome' => 'Uber Eats',
            'descricao' => __('messages.integration_module', ['name' => 'Uber Eats']),
        ]))->save();

        (new Integracao([
            'nome' => 'Google',
            'descricao' => __('messages.integration_module', ['name' => 'Google']),
        ]))->save();

        (new Integracao([
            'nome' => 'Facebook',
            'descricao' => __('messages.integration_module', ['name' => 'Facebook']),
        ]))->save();
    }
}
