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
            'tipo' => Carteira::TIPO_LOCAL,
            'descricao' => 'Gaveta do Caixa 1',
            'ativa' => true,
        ]))->save();
    }
}
