<?php

use App\Models\Funcao;
use App\Models\Cliente;
use App\Models\Prestador;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;

class PrestadorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $funcao = Funcao::where('id', 1)->first();
        $cliente = Cliente::where('id', 1)->first();
        (new Prestador([
            'codigo' => 1,
            'funcao_id' => $funcao->id,
            'cliente_id' => $cliente->id,
            'data_cadastro' => Carbon::now(),
        ]))->save();
    }
}
