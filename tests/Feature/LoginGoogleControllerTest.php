<?php

namespace Tests\Feature;

use App\Models\Cliente;
use Tests\TestCase;

class LoginGoogleControllerTest extends TestCase
{
    public function testLoginGoogleClieteExistente()
    {
        $cliente = factory(Cliente::class)->create(['email' => 'teste@gmail.com', 'status' => Cliente::STATUS_ATIVO]);
        $data = ['U3' => $cliente->email, 'ig' =>  $cliente->nome];
        $response = $this->call('POST', 'login/google', array(
            '_token' => csrf_token(),
            'response' => $data,
        ));
        $response->assertStatus(200);
    }

    public function testLoginGoogleNovoCliente()
    {
        $data = ['U3' => 'teste@gmail.com', 'ig' => 'teste'];
        $response = $this->call('POST', 'login/google', array(
            '_token' => csrf_token(),
            'response' => $data,
        ));
        $response->assertStatus(200);

        $cliente = Cliente::where('nome', $data['ig'])->first();
        $this->assertEquals('teste', $cliente->nome);
    }

    public function testLoginGoogleClienteInvalido()
    {
        $cliente = factory(Cliente::class)->create(['email' => 'teste@gmail.com', 'status' => Cliente::STATUS_INATIVO]);
        $data = ['U3' => $cliente->email, 'ig' =>  $cliente->nome];
        $response = $this->call('POST', 'login/google', array(
            '_token' => csrf_token(),
            'response' => $data,
        ));
        $response->assertStatus(500);
    }
}
