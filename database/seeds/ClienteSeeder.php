<?php

use App\Models\Cliente;
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
        (new Cliente([
            'tipo' => Cliente::TIPO_FISICA,
            'login' => 'admin',
            'senha' => 'Teste123',
            'nome' => 'Beta',
            'sobrenome' => 'Teste',
            'genero' => Cliente::GENERO_MASCULINO,
            'email' => 'beta@grandchef.com.br',
            'status' => Cliente::STATUS_ATIVO,
        ]))->save();
    }
}
