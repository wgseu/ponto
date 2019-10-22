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

namespace MZ\Sale;

use MZ\Sale\PedidoTest;
use MZ\Product\ProdutoTest;
use MZ\Provider\PrestadorTest;
use MZ\Session\MovimentacaoTest;

class ItemTest extends \MZ\Framework\TestCase
{
    /**
     * Build a valid item do pedido
     * @return Item
     */
    public static function build($pedido = null)
    {
        $last = Item::find([], ['id' => -1]);
        $id = $last->getID() + 1;
        $pedido = $pedido ?? PedidoTest::create();
        $prestador = PrestadorTest::create();
        $produto = ProdutoTest::create();
        $item = new Item();
        $item->setPedidoID($pedido->getID());
        $item->setPrestadorID($prestador->getID());
        $item->setProdutoID($produto->getID());
        $item->setPreco($produto->getPrecoVenda() - 0.50);
        $item->setPrecoVenda($produto->getPrecoVenda());
        $item->setQuantidade(2);
        $item->totalize();
        $item->setComissao($prestador->getPorcentagem() * $item->getSubtotal() / 100);
        $item->totalize();
        $item->setEstado(Item::ESTADO_ADICIONADO);
        $item->setCancelado('N');
        $item->setDesperdicado('N');
        $item->setReservado('N');
        return $item;
    }

    /**
     * Create a item do pedido on database
     * @return Item
     */
    public static function create($pedido = null)
    {
        $item = self::build($pedido);
        $item->insert();
        return $item;
    }

    public function testFind()
    {
        $movimentacao = MovimentacaoTest::create();
        $item = self::create();
        $condition = ['produtoid' => $item->getProdutoID()];
        $found_item = Item::find($condition);
        $this->assertEquals($item, $found_item);
        list($found_item) = Item::findAll($condition, [], 1);
        $this->assertEquals($item, $found_item);
        $this->assertEquals(1, Item::count($condition));
        $pedido = $item->findPedidoID();
        PedidoTest::close($pedido);
        MovimentacaoTest::close($movimentacao);
    }

    public function testAdd()
    {
        $movimentacao = MovimentacaoTest::create();
        $item = self::build();
        $item->insert();
        $this->assertTrue($item->exists());
        $pedido = $item->findPedidoID();
        PedidoTest::close($pedido);
        MovimentacaoTest::close($movimentacao);
    }

    public function testUpdate()
    {
        $movimentacao = MovimentacaoTest::create();
        $item = self::create();
        $item->update();
        $this->assertTrue($item->exists());
        $pedido = $item->findPedidoID();
        PedidoTest::close($pedido);
        MovimentacaoTest::close($movimentacao);
    }

    public function testDelete()
    {
        $movimentacao = MovimentacaoTest::create();
        $item = self::create();
        $pedido = $item->findPedidoID();
        $item->delete();
        $item->loadByID();
        $this->assertFalse($item->exists());
        $pedido->delete();
        MovimentacaoTest::close($movimentacao);
    }
}
