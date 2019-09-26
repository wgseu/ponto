<?php

use App\Models\Comanda;
use Illuminate\Database\Seeder;

class ComandaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($number = 1; $number <= 10; $number++) {
            (new Comanda([
                'numero' => $number,
                'nome' => __('messages.cards_number', ['number' => $number]),
            ]))->save();
        }
    }
}
