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

use MZ\Provider\PrestadorTest;
use MZ\Environment\MesaTest;

class PedidoTest extends \MZ\Framework\TestCase
{
    /**
     * Build a valid pedido
     * @return Pedido
     */
    public static function build($tipo = null)
    {
        $prestador = PrestadorTest::create();
        $pedido = new Pedido();
        $pedido->setTipo($tipo ?? Pedido::TIPO_MESA);
        if ($pedido->getTipo() == Pedido::TIPO_MESA) {
            $mesa = MesaTest::create();
            $pedido->setMesaID($mesa->getID());
        } elseif ($pedido->getTipo() == Pedido::TIPO_COMANDA) {
            $comanda = ComandaTest::create();
            $pedido->setComandaID($comanda->getID());
        }
        $pedido->setEstado(Pedido::ESTADO_ATIVO);
        $pedido->setPrestadorID($prestador->getID());
        $pedido->setPessoas(3);
        return $pedido;
    }

    /**
     * Create a pedido on database
     * @return Pedido
     */
    public static function create($tipo = null)
    {
        $pedido = self::build($tipo);
        $pedido->insert();
        return $pedido;
    }

    public function testFind()
    {
        $pedido = self::create();
        $condition = ['mesaid' => $pedido->getMesaID()];
        $found_pedido = Pedido::find($condition);
        $this->assertEquals($pedido, $found_pedido);
        list($found_pedido) = Pedido::findAll($condition, [], 1);
        $this->assertEquals($pedido, $found_pedido);
        $this->assertEquals(1, Pedido::count($condition));
    }

    public function testAdd()
    {
        $pedido = self::build();
        $pedido->insert();
        $this->assertTrue($pedido->exists());
    }

    public function testUpdate()
    {
        $pedido = self::create();
        $pedido->update();
        $this->assertTrue($pedido->exists());
    }

    public function testDelete()
    {
        $pedido = self::create();
        $pedido->delete();
        $pedido->loadByID();
        $this->assertFalse($pedido->exists());
    }
}
