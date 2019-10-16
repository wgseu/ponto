<?php

use App\Models\Banco;
use App\Models\Cheque;
use App\Models\Cliente;
use App\Models\Mesa;
use Illuminate\Database\Seeder;

class ChequeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cliente_id = Cliente::all()->first();
        $banco = Banco::all()->first();
        for ($number = 1; $number <= 10; $number++) {
            (new Cheque([
                'cliente_id' => $cliente_id->id,
                'banco_id' => $banco->id,
                'agencia' => 5521,
                'conta' => 123456,
                'numero' => $number,
                'valor' => 100,
                'vencimento' => '2521-10-10',
            ]))->save();
        }
    }
}
