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

use MZ\Sale\ItemTest;
use MZ\Sale\Item;
use MZ\Sale\PedidoTest;
use MZ\Sale\Pedido;
use MZ\Provider\PrestadorTest;
use MZ\Session\MovimentacaoTest;
use MZ\Exception\ValidationException;
use MZ\Session\Movimentacao;

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

    public function testAddInvalid()
    {
        $movimentacao = MovimentacaoTest::create();
        $transferencia = self::build();
        $pedido_destino = $transferencia->findDestinoPedidoID();
        $pedido = $transferencia->findPedidoID();
        PedidoTest::close($pedido_destino);
        PedidoTest::close($pedido);
        $transferencia->setPedidoID(null);
        $transferencia->setDestinoPedidoID(null);
        $transferencia->setTipo('Teste');
        $transferencia->setModulo('Teste');
        $transferencia->setPrestadorID(null);

        try {
            $transferencia->insert();
            $this->fail('Valores nulos');
        } catch (ValidationException $e) {
            $this->assertEquals(
                ['pedidoid', 'destinopedidoid', 'tipo', 'modulo', 'prestadorid'],
                array_keys($e->getErrors())
            );
        }
        MovimentacaoTest::close($movimentacao);
    }

    public function testAddCancelado()
    {
        $movimentacao = MovimentacaoTest::create();
        $transferencia = self::build();

        $pedido = $transferencia->findPedidoID();
        $pedido->setCancelado('Y');
        $pedido->update();
        $transferencia->setPedidoID($pedido->getID());
        $transferencia->setModulo(Transferencia::MODULO_MESA);
        $transferencia->setMesaID(null);
        $transferencia->setDestinoMesaID(null);
        $transferencia->setComandaID(1);
        $transferencia->setDestinoComandaID(1);
        try {
            $transferencia->insert();
            $this->fail('Pedido cancelado');
        } catch (ValidationException $e) {
            $this->assertEquals(['pedidoid', 'mesaid', 'destinomesaid', 'comandaid', 'destinocomandaid'], array_keys($e->getErrors()));
        }
        $pedido_destino = $transferencia->findDestinoPedidoID();
        PedidoTest::close($pedido_destino);
        MovimentacaoTest::close($movimentacao);
    }

    public function testAddFinalido()
    {
        $movimentacao = MovimentacaoTest::create();
        $transferencia = self::build();
        $pedido = $transferencia->findPedidoID();
        PedidoTest::close($pedido);
        $transferencia->setModulo(Transferencia::MODULO_COMANDA);
        $transferencia->setMesaID(2);
        $transferencia->setDestinoMesaID(2);
        $transferencia->setComandaID(null);
        $transferencia->setDestinoComandaID(null);
        try {
            $transferencia->insert();
            $this->fail('Pedido cancelado');
        } catch (ValidationException $e) {
            $this->assertEquals(['pedidoid', 'mesaid', 'destinomesaid', 'comandaid', 'destinocomandaid'], array_keys($e->getErrors()));
        }
        $pedido_destino = $transferencia->findDestinoPedidoID();
        PedidoTest::close($pedido_destino);
        MovimentacaoTest::close($movimentacao);

    }

    public function testAddSemProduto()
    {
        $movimentacao = MovimentacaoTest::create();
        $transferencia = self::build();
        $transferencia->setTipo(Transferencia::TIPO_PRODUTO);
        try {
            $transferencia->insert();
            $this->fail('nao cadastrar');
        } catch (ValidationException $e) {
            $this->assertEquals(['itemid'], array_keys($e->getErrors()));
        }
        $pedido_destino = $transferencia->findDestinoPedidoID();
        $pedido = $transferencia->findPedidoID();
        PedidoTest::close($pedido_destino);
        PedidoTest::close($pedido);
        MovimentacaoTest::close($movimentacao);
    }

    public function testFinds()
    {
        $movimentacao = MovimentacaoTest::create();
        $transferencia = self::create();

        $mesa = $transferencia->findMesaID();
        $this->assertEquals($transferencia->getMesaID(), $mesa->getID());

        $destinoMesa = $transferencia->findDestinoMesaID();
        $this->assertEquals($transferencia->getDestinoMesaID(), $destinoMesa->getID());

        $comanda = $transferencia->findComandaID();
        $this->assertEquals($transferencia->getComandaID(), $comanda->getID());

        $comandaDestino = $transferencia->findDestinoComandaID();
        $this->assertEquals($transferencia->getDestinoComandaID(), $comandaDestino->getID());

        $item = $transferencia->findItemID();
        $this->assertEquals($transferencia->getItemID(), $item->getID());

        $prestador = $transferencia->findPrestadorID();
        $this->assertEquals($transferencia->getPrestadorID(), $prestador->getID());

        $pedido = $transferencia->findPedidoID();
        $pedido_destino = $transferencia->findDestinoPedidoID();
        PedidoTest::close($pedido);
        PedidoTest::close($pedido_destino);
        MovimentacaoTest::close($movimentacao);
    }

    public function testGetOptions()
    {
        $movimentacao = MovimentacaoTest::create();
        $transferencia = self::create();
        $options = Transferencia::getTipoOptions($transferencia->getTipo());
        $this->assertEquals($transferencia->getTipo(), $options);

        $options = Transferencia::getModuloOptions($transferencia->getModulo());
        $this->assertEquals($transferencia->getModulo(), $options);
        
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
