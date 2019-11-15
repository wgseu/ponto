<?php

/**
 * Copyright 2014 da GrandChef - GrandChef Desenvolvimento de Sistemas LTDA
 *
 * Este arquivo é parte do programa GrandChef - Sistema para Gerenciamento de Restaurantes e Afins.
 * O GrandChef é um software proprietário; você não pode redistribuí-lo e/ou modificá-lo.
 * DISPOSIÇÕES GERAIS
 * O cliente não deverá remover qualquer identificação do produto, avisos de direitos autorais,
 * ou outros avisos ou restrições de propriedade do GrandChef.
 *
 * O cliente não deverá causar ou permitir a engenharia reversa, desmontagem,
 * ou descompilação do GrandChef.
 *
 * PROPRIEDADE DOS DIREITOS AUTORAIS DO PROGRAMA
 *
 * GrandChef é a especialidade do desenvolvedor e seus
 * licenciadores e é protegido por direitos autorais, segredos comerciais e outros direitos
 * de leis de propriedade.
 *
 * O Cliente adquire apenas o direito de usar o software e não adquire qualquer outros
 * direitos, expressos ou implícitos no GrandChef diferentes dos especificados nesta Licença.
 *
 * @author Equipe GrandChef <desenvolvimento@grandchef.com.br>
 */

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Cliente;
use App\Models\Telefone;

class ClienteTest extends TestCase
{
    /**
     * Retorna os headers de autenticação do usuário
     *
     * @param Cliente $user opcional
     * @return array
     */
    public static function auth($user = null)
    {
        $user = $user ?: factory(Cliente::class)->make();
        $user->status = Cliente::STATUS_ATIVO;
        $user->save();
        $token = auth()->fromUser($user);
        return [
            'Authorization' => "Bearer $token",
        ];
    }

    public function testCreateCliente()
    {
        $headers = PrestadorTest::auth();
        $cliente_data =  factory(Cliente::class)->make(['nome' => 'Teste']);
        $response = $this->graphfl('create_cliente', [
            'input' => $cliente_data,
        ], $headers);

        $found_cliente = Cliente::findOrFail($response->json('data.CreateCliente.id'));
        $this->assertEquals('Teste', $found_cliente->nome);
    }

    public function testCreateAccount()
    {
        $cliente_data = factory(Cliente::class)->make(['email' => 'contato@grandchef.com.br']);
        $response = $this->graphfl('create_cliente', ['input' => $cliente_data]);
        $this->assertNotNull($response->json('data.CreateCliente.refresh_token'));
    }

    public function testCreateAccountWithPhones()
    {
        $cliente_data = factory(Cliente::class)->raw(['nome' => 'Teste']);
        $cliente_data = array_merge($cliente_data, [
            'telefones' => [
                ['numero' => '44987654321'],
                ['numero' => '44987654320'],
            ],
        ]);
        $response = $this->graphfl('create_cliente', ['input' => $cliente_data]);
        $cliente = Cliente::findOrFail($response->json('data.CreateCliente.id'));
        $fone1 = Telefone::where('cliente_id', $cliente->id)
            ->where('numero', '44987654321')->first();
        $fone2 = Telefone::where('cliente_id', $cliente->id)
            ->where('numero', '44987654320')->first();
        $this->assertNotNull($fone1);
        $this->assertNotNull($fone2);
    }

    public function testCreateAccountWithSamePhones()
    {
        $cliente_data = factory(Cliente::class)->raw(['nome' => 'Teste']);
        $cliente_data = array_merge($cliente_data, [
            'telefones' => [
                ['numero' => '44987654321'],
                ['numero' => '4487654321'],
            ],
        ]);
        $this->expectException('\Exception');
        $this->graphfl('create_cliente', ['input' => $cliente_data]);
    }

    public function testUpdateCliente()
    {
        $headers = PrestadorTest::auth();
        $cliente = factory(Cliente::class)->create();
        $this->graphfl('update_cliente', [
            'id' => $cliente->id,
            'input' => [
                'nome' => 'Atualizou',
            ]
        ], $headers);
        $cliente->refresh();
        $this->assertEquals('Atualizou', $cliente->nome);
    }

    public function testDeleteCliente()
    {
        $headers = PrestadorTest::auth();
        $cliente_to_delete = factory(Cliente::class)->create();
        $this->graphfl('delete_cliente', ['id' => $cliente_to_delete->id], $headers);
        $cliente = Cliente::find($cliente_to_delete->id);
        $this->assertNull($cliente);
    }

    public function testFindCliente()
    {
        $headers = PrestadorTest::auth();
        $cliente = factory(Cliente::class)->create();
        $response = $this->graphfl('query_cliente', [ 'id' => $cliente->id ], $headers);
        $this->assertEquals($cliente->id, $response->json('data.clientes.data.0.id'));
    }
}
