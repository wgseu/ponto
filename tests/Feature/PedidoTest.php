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

use App\Models\Mesa;
use Tests\TestCase;
use App\Models\Pedido;

class PedidoTest extends TestCase
{
    public function testCreatePedido()
    {
        $headers = PrestadorTest::auth();
        $seed_pedido =  factory(Pedido::class)->create();
        $response = $this->graphfl('create_pedido', [
            'input' => [
                'tipo' => Pedido::TIPO_BALCAO,
                'sessao_id' => $seed_pedido->sessao_id,
            ]
        ], $headers);

        $found_pedido = Pedido::findOrFail($response->json('data.CreatePedido.id'));
        $this->assertEquals(Pedido::TIPO_BALCAO, $found_pedido->tipo);
    }

    public function testUpdatePedido()
    {
        $headers = PrestadorTest::auth();
        $pedido = factory(Pedido::class)->create();
        $mesa = factory(Mesa::class)->create();
        $this->graphfl(
            'update_pedido',
            [
                'id' => $pedido->id,
                'input' => [
                    'mesa_id' => $mesa->id,
                ]
            ],
            $headers
        );
        $pedido->refresh();
        $this->assertEquals($mesa->id, $pedido->mesa_id);
    }

    public function testFindPedido()
    {
        $headers = PrestadorTest::auth();
        $pedido = factory(Pedido::class)->create();
        $response = $this->graphfl('query_pedido', [ 'id' => $pedido->id ], $headers);
        $this->assertEquals($pedido->id, $response->json('data.pedidos.data.0.id'));
    }
}
