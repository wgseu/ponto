<?php

use Illuminate\Database\Seeder;

use App\Models\Banco;

class BancoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        (new Banco([
            'numero' => '123',
            'fantasia' => 'Banco do Brasil',
            'razao_social' => 'Banco de Brasil S/A',
        ]))->save();
    }
}
