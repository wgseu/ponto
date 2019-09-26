<?php

use App\Models\Mesa;
use App\Models\Setor;
use Illuminate\Database\Seeder;

class MesaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $setor = Setor::where('nome', __('messages.covered_hall'))->first();
        for ($number = 1; $number <= 10; $number++) {
            (new Mesa([
                'setor_id' => $setor->id,
                'numero' => $number,
                'nome' => __('messages.table_number', ['number' => $number]),
            ]))->save();
        }
    }
}
