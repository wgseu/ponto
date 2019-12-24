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
            'nome' => __('messages.delivery'),
            'descricao' => __('messages.delivery_service_permission'),
            'tipo' => Servico::TIPO_TAXA,
            'obrigatorio' => false,
            'valor' => 1,
            'individual' => false,
            'ativo' => true,
        ]))->forceFill(['id' => Servico::ENTREGA_ID])->save();
    }
}
