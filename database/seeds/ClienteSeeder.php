<?php

use App\Models\Cliente;
use App\Models\Empresa;
use Illuminate\Database\Seeder;

class ClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cliente = new Cliente([
            'tipo' => Cliente::TIPO_FISICA,
            'login' => 'admin',
            'senha' => 'Teste123',
            'nome' => 'Beta',
            'sobrenome' => 'Teste',
            'genero' => Cliente::GENERO_MASCULINO,
            'email' => 'beta@grandchef.com.br',
            'empresa_id' => Empresa::find(1)->empresa_id,
        ]);
        $cliente->forceFill(['status' => Cliente::STATUS_ATIVO]);
        $cliente->save();
    }
}
