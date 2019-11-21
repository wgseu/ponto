<?php

use App\Models\Caixa;
use App\Models\Carteira;
use Illuminate\Database\Seeder;

class CaixaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        (new Caixa([
            'carteira_id' => Carteira::where('descricao', __('messages.cash_drawer_number', ['number' => 1]))->first()->id,
            'descricao' => __('messages.cash'),
        ]))->save();
    }
}
