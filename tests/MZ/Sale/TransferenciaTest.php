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
use MZ\Provider\PrestadorTest;
use MZ\Session\MovimentacaoTest;

class TransferenciaTest extends \MZ\Framework\TestCase
{
    /**
     * Build a valid transferência
     * @return Transferencia
     */
    public static function build()
    {
        $last = Transferencia::find([], ['id' => -1]);
        $id = $last->getID() + 1;
        $pedido = PedidoTest::create();
        $pedido_destino = PedidoTest::create();
        $prestador = PrestadorTest::create();
        $transferencia = new Transferencia();
        $transferencia->setPedidoID($pedido->getID());
        $transferencia->setDestinoPedidoID($pedido_destino->getID());
        $transferencia->setTipo(Transferencia::TIPO_PEDIDO);
        $transferencia->setModulo(Transferencia::MODULO_MESA);
        $transferencia->setMesaID($pedido->getMesaID());
        $transferencia->setDestinoMesaID($pedido_destino->getMesaID());
        $transferencia->setPrestadorID($prestador->getID());
        return $transferencia;
    }

    /**
     * Create a transferência on database
     * @return Transferencia
     */
    public static function create()
    {
        $transferencia = self::build();
        $transferencia->insert();
        return $transferencia;
    }

    public function testFind()
    {
        $movimentacao = MovimentacaoTest::create();
        $transferencia = self::create();
        $condition = ['pedidoid' => $transferencia->getPedidoID()];
        $found_transferencia = Transferencia::find($condition);
        $this->assertEquals($transferencia, $found_transferencia);
        list($found_transferencia) = Transferencia::findAll($condition, [], 1);
        $this->assertEquals($transferencia, $found_transferencia);
        $this->assertEquals(1, Transferencia::count($condition));
        $pedido = $transferencia->findPedidoID();
        $pedido_destino = $transferencia->findDestinoPedidoID();
        PedidoTest::close($pedido);
        PedidoTest::close($pedido_destino);
        MovimentacaoTest::close($movimentacao);
    }

    public function testAdd()
    {
        $movimentacao = MovimentacaoTest::create();
        $transferencia = self::build();
        $transferencia->insert();
        $this->assertTrue($transferencia->exists());
        $pedido = $transferencia->findPedidoID();
        $pedido_destino = $transferencia->findDestinoPedidoID();
        PedidoTest::close($pedido);
        PedidoTest::close($pedido_destino);
        MovimentacaoTest::close($movimentacao);
    }

    public function testUpdate()
    {
        $movimentacao = MovimentacaoTest::create();
        $transferencia = self::create();
        $this->expectException('\MZ\Exception\ValidationException');
        try {
            $transferencia->update();
        } finally {
            $pedido = $transferencia->findPedidoID();
            $pedido_destino = $transferencia->findDestinoPedidoID();
            PedidoTest::close($pedido);
            PedidoTest::close($pedido_destino);
            MovimentacaoTest::close($movimentacao);
        }
    }

    public function testDelete()
    {
        $movimentacao = MovimentacaoTest::create();
        $transferencia = self::create();
        $pedido = $transferencia->findPedidoID();
        $pedido_destino = $transferencia->findDestinoPedidoID();
        $transferencia->delete();
        $transferencia->loadByID();
        $this->assertFalse($transferencia->exists());
        PedidoTest::close($pedido);
        PedidoTest::close($pedido_destino);
        MovimentacaoTest::close($movimentacao);
    }
}
