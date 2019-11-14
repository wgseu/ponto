<?php

use App\Models\Caixa;
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
            'id' => 1,
            'carteira_id' => 1,
            'descricao' => __('messages.cash'),
        ]))->save();
    }
}
