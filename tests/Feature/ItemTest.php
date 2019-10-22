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
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ItemTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateItem()
    {
        $headers = PrestadorTest::auth();
        $seed_item =  factory(Item::class)->create();
        $response = $this->graphfl('create_item', [
            'input' => [
                'pedido_id' => $seed_item->pedido_id,
                'preco' => 1.50,
                'quantidade' => 1.0,
                'subtotal' => 1.50,
                'total' => 1.50,
                'preco_venda' => 1.50,
            ]
        ], $headers);

        $found_item = Item::findOrFail($response->json('data.CreateItem.id'));
        $this->assertEquals($seed_item->pedido_id, $found_item->pedido_id);
        $this->assertEquals(1.50, $found_item->preco);
        $this->assertEquals(1.0, $found_item->quantidade);
        $this->assertEquals(1.50, $found_item->subtotal);
        $this->assertEquals(1.50, $found_item->total);
        $this->assertEquals(1.50, $found_item->preco_venda);
    }

    public function testUpdateItem()
    {
        $headers = PrestadorTest::auth();
        $item = factory(Item::class)->create();
        $this->graphfl('update_item', [
            'id' => $item->id,
            'input' => [
                'preco' => 1.50,
                'quantidade' => 1.0,
                'subtotal' => 1.50,
                'total' => 1.50,
                'preco_venda' => 1.50,
            ]
        ], $headers);
        $item->refresh();
        $this->assertEquals(1.50, $item->preco);
        $this->assertEquals(1.0, $item->quantidade);
        $this->assertEquals(1.50, $item->subtotal);
        $this->assertEquals(1.50, $item->total);
        $this->assertEquals(1.50, $item->preco_venda);
    }

    public function testDeleteItem()
    {
        $headers = PrestadorTest::auth();
        $item_to_delete = factory(Item::class)->create();
        $item_to_delete = $this->graphfl('delete_item', ['id' => $item_to_delete->id], $headers);
        $item = Item::find($item_to_delete->id);
        $this->assertNull($item);
    }

    public function testFindItem()
    {
        $headers = PrestadorTest::auth();
        $item = factory(Item::class)->create();
        $response = $this->graphfl('query_item', [ 'id' => $item->id ], $headers);
        $this->assertEquals($item->id, $response->json('data.itens.data.0.id'));
    }
}
