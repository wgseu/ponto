<?php
/**
 * Copyright 2014 da MZ Software - MZ Desenvolvimento de Sistemas LTDA
 *
 * Este arquivo é parte do programa GrandChef - Sistema para Gerenciamento de Churrascarias, Bares e Restaurantes.
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
 * @author Equipe GrandChef <desenvolvimento@mzsw.com.br>
 */
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Cliente;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClienteTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateCliente()
    {
        $headers = PrestadorTest::auth();
        $seed_cliente =  factory(Cliente::class)->create();
        $response = $this->graphfl('create_cliente', [
            'input' => [
                'nome' => 'Teste',
            ]
        ], $headers);

        $found_cliente = Cliente::findOrFail($response->json('data.CreateCliente.id'));
        $this->assertEquals('Teste', $found_cliente->nome);
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
        $cliente_to_delete = $this->graphfl('delete_cliente', ['id' => $cliente_to_delete->id], $headers);
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
