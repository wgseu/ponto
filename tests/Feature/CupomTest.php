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

use App\Exceptions\ValidationException;
use Tests\TestCase;
use App\Models\Cupom;
use App\Models\Item;
use App\Models\Localizacao;
use App\Models\Pagamento;
use App\Models\Pedido;
use Illuminate\Support\Carbon;

class CupomTest extends TestCase
{
    public function testCreate()
    {
        $headers = PrestadorTest::authOwner();
        $validade = Carbon::now()->addDays(1);
        $response = $this->graphfl('create_cupom', [
            'input' => [
                'codigo' => 'CODE123',
                'quantidade' => 1,
                'tipo_desconto' => Cupom::TIPO_DESCONTO_VALOR,
                'valor' => 3.50,
                'incluir_servicos' => true,
                'validade' => $validade->format('c'),
            ]
        ], $headers);

        $found_cupom = Cupom::findOrFail($response->json('data.CreateCupom.id'));
        $this->assertEquals('CODE123', $found_cupom->codigo);
        $this->assertEquals(1, $found_cupom->quantidade);
        $this->assertEquals(Cupom::TIPO_DESCONTO_VALOR, $found_cupom->tipo_desconto);
        $this->assertEquals(true, $found_cupom->incluir_servicos);
        $this->assertEquals(3.50, $found_cupom->valor);
        $this->assertEquals($validade, $found_cupom->validade);
    }

    public function testUpdate()
    {
        $headers = PrestadorTest::authOwner();
        $cupom = factory(Cupom::class)->create();
        $validade = Carbon::now()->addDays(3);
        $quantidade = $cupom->quantidade + 5;
        $this->graphfl('update_cupom', [
            'id' => $cupom->id,
            'input' => [
                'codigo' => 'CODE321',
                'quantidade' => $quantidade,
                'incluir_servicos' => true,
                'validade' => $validade->format('c'),
            ]
        ], $headers);
        $cupom->refresh();
        $this->assertEquals('CODE321', $cupom->codigo);
        $this->assertEquals($quantidade, $cupom->quantidade);
        $this->assertEquals(true, $cupom->incluir_servicos);
        $this->assertEquals($validade, $cupom->validade);
    }

    public function testDelete()
    {
        $headers = PrestadorTest::authOwner();
        $cupom_to_delete = factory(Cupom::class)->create();
        $this->graphfl('delete_cupom', ['id' => $cupom_to_delete->id], $headers);
        $cupom = Cupom::find($cupom_to_delete->id);
        $this->assertNull($cupom);
    }

    public function testFindMultiple()
    {
        $headers = PrestadorTest::authOwner();
        $cupom = factory(Cupom::class)->create();
        $response = $this->graphfl('query_cupom', [ 'id' => $cupom->id ], $headers);
        $this->assertEquals($cupom->id, $response->json('data.cupons.data.0.id'));
    }

    public function testsSearchByCode()
    {
        $headers = PrestadorTest::authOwner();
        $cupom = factory(Cupom::class)->create();
        $response = $this->graphfl('query_cupom_search', [ 'codigo' => $cupom->codigo ], $headers);
        $this->assertEquals($cupom->id, $response->json('data.cupom.id'));
    }

    public function testSearchUnreachable()
    {
        $headers = PrestadorTest::authOwner();
        $cupom = factory(Cupom::class)->create([
            'limitar_pedidos' => true,
            'funcao_pedidos' => Cupom::FUNCAO_PEDIDOS_MAIOR,
            'pedidos_limite' => 0,
        ]);
        $this->expectException(ValidationException::class);
        $this->graphfl('query_cupom_search', [ 'codigo' => $cupom->codigo ], $headers);
    }

    public function testUseCouponDelivery()
    {
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
        $cupom = factory(Cupom::class)->create([
            'valor' => $total,
        ]);
        $cupons = [
            ['id' => $cupom->id],
        ];
        $this->graphfl('create_pedido', [
            'input' => [
                'tipo' => Pedido::TIPO_ENTREGA,
                'estado' => Pedido::ESTADO_AGENDADO,
                'cliente_id' => $cliente->id,
                'localizacao_id' => $localizacao->id,
                'itens' => $itens,
                'cupons' => $cupons,
            ]
        ], $headers);
        $cupom->refresh();
        $this->assertEquals($cupom->quantidade - 1, $cupom->disponivel);
    }
}
