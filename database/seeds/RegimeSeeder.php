<?php

use App\Models\Regime;
use Illuminate\Database\Seeder;

class RegimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        (new Regime([
            'codigo' => 1,
            'descricao' => 'Simples Nacional',
        ]))->save();

        (new Regime([
            'codigo' => 2,
            'descricao' => 'Simples Nacional - excesso de sublimite de receita bruta',
        ]))->save();

        (new Regime([
            'codigo' => 3,
            'descricao' => 'Regime Normal',
        ]))->save();
    }
}
