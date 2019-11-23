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

use App\Models\Item;
use App\Models\Localizacao;
use App\Models\Pagamento;
use Tests\TestCase;
use App\Models\Pedido;
use App\Models\Sessao;
use App\Models\Viagem;

class PedidoTest extends TestCase
{
    public function testFindPedido()
    {
        $headers = PrestadorTest::authOwner();
        $pedido = factory(Pedido::class)->create();
        $response = $this->graphfl('query_pedido', [ 'id' => $pedido->id ], $headers);
        $this->assertEquals($pedido->id, $response->json('data.pedidos.data.0.id'));
    }

    public function testCreateDeliveryFromCustomer()
    {
        $prestador_headers = PrestadorTest::authOwner();
        $localizacao = factory(Localizacao::class)->create();
        $cliente = $localizacao->cliente;
        $headers = ClienteTest::auth($cliente);
        $itens = [
            factory(Item::class)->raw(),
            factory(Item::class)->raw(),
        ];
        $total = array_reduce($itens, function ($prev, $item) {
            return $prev + $item['preco'] * $item['quantidade'];
        }, 0);
        $pagamentos = [
            factory(Pagamento::class)->raw(['lancado' => $total + 10]),
            factory(Pagamento::class)->raw(['lancado' => -10]),
        ];
        $response = $this->graphfl('create_pedido', [
            'input' => [
                'tipo' => Pedido::TIPO_ENTREGA,
                'estado' => Pedido::ESTADO_AGENDADO,
                'cliente_id' => $cliente->id,
                'localizacao_id' => $localizacao->id,
                'itens' => $itens,
                'pagamentos' => $pagamentos,
            ]
        ], $headers);
        $pedido = Pedido::findOrFail($response->json('data.CreatePedido.id'));
        $this->assertEquals(Pedido::TIPO_ENTREGA, $pedido->tipo);

        $entrega = factory(Viagem::class)->raw();
        $this->graphfl(
            'update_pedido',
            [
                'id' => $pedido->id,
                'input' => [
                    'estado' => Pedido::ESTADO_ENTREGA,
                    'entrega' => $entrega,
                ]
            ],
            $prestador_headers
        );

        $pagamentos = array_map(
            function ($pagamento) {
                return ['id' => $pagamento->id, 'estado' => Pagamento::ESTADO_PAGO];
            },
            $pedido->lancados()->get()->all()
        );
        factory(Sessao::class)->create();
        $this->graphfl(
            'update_pedido',
            [
                'id' => $pedido->id,
                'input' => [
                    'estado' => Pedido::ESTADO_CONCLUIDO,
                    'pagamentos' => $pagamentos,
                ]
            ],
            $prestador_headers
        );
    }
}
