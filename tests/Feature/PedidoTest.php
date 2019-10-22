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
use App\Models\Pedido;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PedidoTest extends TestCase
{
    use RefreshDatabase;

    public function testCreatePedido()
    {
        $headers = PrestadorTest::auth();
        $seed_pedido =  factory(Pedido::class)->create();
        $response = $this->graphfl('create_pedido', [
            'input' => [
            ]
        ], $headers);

        $found_pedido = Pedido::findOrFail($response->json('data.CreatePedido.id'));
    }

    public function testUpdatePedido()
    {
        $headers = PrestadorTest::auth();
        $pedido = factory(Pedido::class)->create();
        $this->graphfl('update_pedido', [
            'id' => $pedido->id,
            'input' => [
            ]
        ], $headers);
        $pedido->refresh();
    }

    public function testDeletePedido()
    {
        $headers = PrestadorTest::auth();
        $pedido_to_delete = factory(Pedido::class)->create();
        $pedido_to_delete = $this->graphfl('delete_pedido', ['id' => $pedido_to_delete->id], $headers);
        $pedido = Pedido::find($pedido_to_delete->id);
        $this->assertNull($pedido);
    }

    public function testFindPedido()
    {
        $headers = PrestadorTest::auth();
        $pedido = factory(Pedido::class)->create();
        $response = $this->graphfl('query_pedido', [ 'id' => $pedido->id ], $headers);
        $this->assertEquals($pedido->id, $response->json('data.pedidos.data.0.id'));
    }
}
