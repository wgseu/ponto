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
        $carteira = Carteira::where('descricao', __('messages.cash_drawer_number', ['number' => 1]))->first();
        (new Caixa([
            'carteira_id' => $carteira->id,
            'descricao' => __('messages.cash_number', ['number' => 1]),
        ]))->save();
    }
}
