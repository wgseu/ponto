<?php

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
        (new Carteira([
            'id' => 1,
            'tipo' => Carteira::TIPO_LOCAL,
            'descricao' => __('messages.wallet_1'),
            'ativa' => true,
        ]))->save();
    }
}
