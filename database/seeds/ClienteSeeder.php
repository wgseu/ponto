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
            'empresa_id' => null,
            'login' => 'admin',
            'senha' => 'Teste123',
            'nome' => 'Beta',
            'sobrenome' => 'teste',
            'genero' => Cliente::GENERO_MASCULINO,
            'cpf' => '70724444084',
            'rg' => null,
            'im' => null,
            'email' => 'beta@grandchef.com.br',
            'data_nascimento' => null,
            'slogan' => null,
            'status' => Cliente::STATUS_INATIVO,
            'secreto' => null,
            'limite_compra' => null,
            'instagram' => null,
            'facebook_url' => null,
            'twitter' => null,
            'linkedin_url' => null,
            'imagem_url' => null,
            'linguagem' => null,
        ]))->save();
    }
}
