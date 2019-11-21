<?php

use App\Models\Funcao;
use App\Models\Cliente;
use App\Models\Prestador;
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
        $funcao = Funcao::where('descricao', __('messages.administrator'))->first();
        $cliente = Cliente::where('login', 'admin')->first();
        (new Prestador([
            'codigo' => 1,
            'funcao_id' => $funcao->id,
            'cliente_id' => $cliente->id,
        ]))->save();
    }
}
