<?php

use App\Models\Empresa;
use App\Models\Pais;
use Illuminate\Database\Seeder;

class EmpresaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        (new Empresa([
            'pais_id' => Pais::where('codigo', env('APP_COUNTRY', 'BR'))->first()->id,
        ]))->save();
    }
}
