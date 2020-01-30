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

use App\Exceptions\Exception;
use Tests\TestCase;
use App\Models\Cliente;
use App\Models\Telefone;
use App\Exceptions\ValidationException;
use App\Models\Prestador;
use Illuminate\Support\Carbon;

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

    public function testCreate()
    {
        $headers = PrestadorTest::authOwner();
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

    public function testTryCreateOwnerAccount()
    {
        $cliente = EmpresaTest::createOwner();
        $cliente_data = factory(Cliente::class)->make([
            'email' => 'contato@grandchef.com.br',
            'empresa_id' => $cliente->empresa_id,
        ]);
        $response = $this->graphfl('create_cliente', ['input' => $cliente_data]);
        $cliente = Cliente::findOrFail($response->json('data.CreateCliente.id'));
        $this->assertNull($cliente->empresa_id);
    }

    public function testOwnerCreateOwnerAccount()
    {
        $owner = EmpresaTest::createOwner();
        $headers = PrestadorTest::auth(['cliente:create'], $owner->prestador);
        $cliente_data = factory(Cliente::class)->make([
            'email' => 'contato@grandchef.com.br',
            'empresa_id' => $owner->empresa_id,
        ]);
        $cliente_data = array_merge($cliente_data->toArray(), ['senha' => 'Teste123']);
        $response = $this->graphfl('create_cliente', ['input' => $cliente_data], $headers);
        $newOwner = Cliente::findOrFail($response->json('data.CreateCliente.id'));
        $this->assertEquals($owner->empresa_id, $newOwner->empresa_id);
        $this->assertFalse($newOwner->isOwner());
        factory(Prestador::class)->create(['cliente_id' => $newOwner->id]);
        $newOwner->refresh();
        $this->assertTrue($newOwner->isOwner());
    }

    public function testFloodCreateAccount()
    {
        $cliente_data = factory(Cliente::class)->make(['email' => 'contato@grandchef.com.br']);
        $response = $this->graphfl('create_cliente', ['input' => $cliente_data]);
        $this->assertNotNull($response->json('data.CreateCliente.refresh_token'));

        $cliente_data = factory(Cliente::class)->make(['email' => 'contato2@grandchef.com.br']);
        $this->expectException(ValidationException::class);
        $this->graphfl('create_cliente', ['input' => $cliente_data]);
    }

    public function testCreateMultiple()
    {
        $headers = PrestadorTest::auth(['cliente:create']);

        $cliente_data = factory(Cliente::class)->make(['email' => 'contato@grandchef.com.br']);
        $response = $this->graphfl('create_cliente', ['input' => $cliente_data], $headers);
        $this->assertNotNull($response->json('data.CreateCliente.refresh_token'));

        $cliente_data = factory(Cliente::class)->make(['email' => 'contato2@grandchef.com.br']);
        $response = $this->graphfl('create_cliente', ['input' => $cliente_data], $headers);
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

    public function testUpdate()
    {
        $headers = PrestadorTest::authOwner();
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

    public function testUpdateEmail()
    {
        $headers = PrestadorTest::authOwner();
        $cliente = factory(Cliente::class)->create();
        $cliente->status = Cliente::STATUS_ATIVO;
        $cliente->save();
        $this->graphfl('update_cliente', [
            'id' => $cliente->id,
            'input' => [
                'email' => 'other@email.com',
            ]
        ], $headers);
        $cliente->refresh();
        $this->assertEquals('other@email.com', $cliente->email);
        $this->assertEquals(Cliente::STATUS_INATIVO, $cliente->status);
    }

    public function testUpdatePhone()
    {
        $cliente_data = factory(Cliente::class)->raw(['nome' => 'Teste']);
        $cliente_data = array_merge($cliente_data, [
            'telefones' => [
                ['numero' => '44987654321'],
            ],
        ]);
        $response = $this->graphfl('create_cliente', ['input' => $cliente_data]);
        $cliente = Cliente::findOrFail($response->json('data.CreateCliente.id'));
        $fone1 = Telefone::where('cliente_id', $cliente->id)
            ->where('numero', '44987654321')->first();
        $fone1->data_validacao = Carbon::now();
        $fone1->save();
        $headers = PrestadorTest::auth(['cliente:update']);
        $this->graphfl('update_cliente', [
            'id' => $cliente->id,
            'input' => [
                'nome' => 'Atualizou',
                'telefones' => [
                    [
                        'id' => $fone1->id,
                        'numero' => '44981234567',
                    ],
                    ['numero' => '44981236541'],
                ],
            ]
        ], $headers);
        $fone1->refresh();
        Telefone::where('cliente_id', $cliente->id)
            ->where('numero', '44981236541')->firstOrFail();
        $this->assertEquals('44981234567', $fone1->numero);
        $this->assertNull($fone1->data_validacao);
    }

    public function testElevateSelfToOwner()
    {
        $owner = EmpresaTest::createOwner();
        $cliente = factory(Cliente::class)->create();
        $headers = self::auth($cliente);
        $this->graphfl('update_cliente', [
            'id' => $cliente->id,
            'input' => [
                'nome' => 'Admin',
                'empresa_id' => $owner->empresa_id,
            ],
        ], $headers);
        $cliente->refresh();
        $this->assertNull($cliente->empresa_id);
        $this->assertEquals('Admin', $cliente->nome);
    }

    public function testCustomerElevateToProvider()
    {
        $cliente = factory(Cliente::class)->create();
        $headers = self::auth($cliente);
        $this->graphfl('update_cliente', [
            'id' => $cliente->id,
            'input' => [
                'nome' => 'Admin',
                'fornecedor' => !$cliente->fornecedor,
            ],
        ], $headers);
        $updated = $cliente->fresh();
        $this->assertFalse($cliente->fornecedor, $updated->fornecedor);
        $this->assertEquals('Admin', $updated->nome);
    }

    public function testEmployeeTryToUpdateOwner()
    {
        $cliente = EmpresaTest::createOwner();
        $headers = PrestadorTest::auth(['cliente:create']);
        $this->expectException(Exception::class);
        $this->graphfl('update_cliente', [
            'id' => $cliente->id,
            'input' => ['nome' => 'Admin']
        ], $headers);
    }

    public function testOwnerUpdateSelf()
    {
        $cliente = EmpresaTest::createOwner();
        $headers = PrestadorTest::auth(['cliente:create'], $cliente->prestador);
        $this->graphfl('update_cliente', [
            'id' => $cliente->id,
            'input' => ['nome' => 'Admin']
        ], $headers);
        $cliente->refresh();
        $this->assertEquals('Admin', $cliente->nome);
    }

    public function testOwnerUpdateOtherOwner()
    {
        $cliente = EmpresaTest::createOwner();
        $headers = PrestadorTest::authOwner();
        $this->expectException(Exception::class);
        $this->graphfl('update_cliente', [
            'id' => $cliente->id,
            'input' => ['nome' => 'Other']
        ], $headers);
    }

    public function testUniqueOwnerLeave()
    {
        $cliente = EmpresaTest::createOwner();
        $headers = PrestadorTest::auth(['cliente:create'], $cliente->prestador);
        $this->expectException(ValidationException::class);
        $this->graphfl('update_cliente', [
            'id' => $cliente->id,
            'input' => ['empresa_id' => null]
        ], $headers);
    }

    public function testOwnerLeave()
    {
        EmpresaTest::createOwner();
        $cliente = EmpresaTest::createOwner();
        $headers = PrestadorTest::auth(['cliente:create'], $cliente->prestador);
        $this->graphfl('update_cliente', [
            'id' => $cliente->id,
            'input' => ['empresa_id' => null]
        ], $headers);
        $cliente->refresh();
        $this->assertNull($cliente->empresa_id);
    }

    public function testDelete()
    {
        $headers = PrestadorTest::authOwner();
        $cliente_to_delete = factory(Cliente::class)->create();
        $this->graphfl('delete_cliente', ['id' => $cliente_to_delete->id], $headers);
        $cliente = Cliente::find($cliente_to_delete->id);
        $this->assertNull($cliente);
    }

    public function testFind()
    {
        $headers = PrestadorTest::authOwner();
        $cliente = factory(Cliente::class)->create();
        $response = $this->graphfl('query_cliente', [ 'id' => $cliente->id ], $headers);
        $this->assertEquals($cliente->id, $response->json('data.clientes.data.0.id'));
    }
}
