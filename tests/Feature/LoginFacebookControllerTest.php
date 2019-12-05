<?php

namespace Tests\Feature;

use App\Models\Cliente;
use Tests\TestCase;

class LoginFacebookControllerTest extends TestCase
{
    public function testLoginFacebookClieteExistente()
    {
        $cliente = factory(Cliente::class)->create(['email' => 'teste@gmail.com', 'status' => Cliente::STATUS_ATIVO]);
        $data = ['email' => $cliente->email, 'name' =>  $cliente->nome];
        $response = $this->call('POST', 'login/facebook', array(
            '_token' => csrf_token(),
            'response' => $data,
        ));
        $response->assertStatus(200);
    }

    public function testLoginFacebookNovoCliente()
    {
        $data = ['email' => 'teste@gmail.com', 'name' => 'teste'];
        $response = $this->call('POST', 'login/facebook', array(
            '_token' => csrf_token(),
            'response' => $data,
        ));
        $response->assertStatus(200);

        $cliente = Cliente::where('nome', $data['name'])->first();
        $this->assertEquals('teste', $cliente->nome);
    }

    public function testLoginFacebookClienteInvalido()
    {
        $cliente = factory(Cliente::class)->create(['email' => 'teste@gmail.com', 'status' => Cliente::STATUS_INATIVO]);
        $data = ['email' => $cliente->email, 'name' =>  $cliente->nome];
        $response = $this->call('POST', 'login/facebook', array(
            '_token' => csrf_token(),
            'response' => $data,
        ));
        $response->assertStatus(500);
    }
}
