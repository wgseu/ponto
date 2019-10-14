<?php

use App\Models\Credito;
use Illuminate\Database\Seeder;

class CreditoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        (new Credito([
            'cliente_id' => 1,
            'valor' => 20,
            'detalhes' => 'devolução de mercadoria',
            'cancelado' => 0,
        ]))->save();
        (new Credito([
            'cliente_id' => 1,
            'valor' => 10,
            'detalhes' => 'devolução de mercadoria',
            'cancelado' => 0,
        ]))->save();
        (new Credito([
            'cliente_id' => 1,
            'valor' => 5,
            'detalhes' => 'abatimento de credito',
            'cancelado' => 1,
        ]))->save();
    }
}
