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

use MZ\Environment\MesaTest;
use MZ\Sale\PedidoTest;
use MZ\Session\MovimentacaoTest;
use MZ\Session\Movimentacao;

class JuncaoTest extends \MZ\Framework\TestCase
{
    /**
     * Build a valid junção
     * @return Juncao
     */
    public static function build()
    {
        $mesa = MesaTest::create();
        $pedido = PedidoTest::create();
        $juncao = new Juncao();
        $juncao->setMesaID($mesa->getID());
        $juncao->setPedidoID($pedido->getID());
        $juncao->setEstado(Juncao::ESTADO_ASSOCIADO);
        return $juncao;
    }

    /**
     * Create a junção on database
     * @return Juncao
     */
    public static function create()
    {
        $juncao = self::build();
        $juncao->insert();
        return $juncao;
    }

    public function testFind()
    {
        $movimentacao = MovimentacaoTest::create();
        $juncao = self::create();
        $condition = ['mesaid' => $juncao->getMesaID()];
        $found_juncao = Juncao::find($condition);
        $this->assertEquals($juncao, $found_juncao);
        list($found_juncao) = Juncao::findAll($condition, [], 1);
        $this->assertEquals($juncao, $found_juncao);
        $this->assertEquals(1, Juncao::count($condition));
        $pedido = $juncao->findPedidoID();
        PedidoTest::close($pedido);
        MovimentacaoTest::close($movimentacao);
    }

    public function testFindMesa()
    {
        $movimentacao = MovimentacaoTest::create();
        $juncao = self::create();
        $mesa = $juncao->findMesaID();
        $this->assertEquals($juncao->getMesaID(), $mesa->getID());
        $pedido = $juncao->findPedidoID();
        PedidoTest::close($pedido);
        MovimentacaoTest::close($movimentacao);
    }

    public function testAdd()
    {
        $movimentacao = MovimentacaoTest::create();
        $juncao = self::build();
        $juncao->insert();
        $this->assertTrue($juncao->exists());
        $pedido = $juncao->findPedidoID();
        PedidoTest::close($pedido);
        MovimentacaoTest::close($movimentacao);
    }

    public function testUpdate()
    {
        $movimentacao = MovimentacaoTest::create();
        $juncao = self::create();
        $juncao->update();
        $this->assertTrue($juncao->exists());
        $pedido = $juncao->findPedidoID();
        PedidoTest::close($pedido);
        MovimentacaoTest::close($movimentacao);
    }

    public function testDelete()
    {
        $movimentacao = MovimentacaoTest::create();
        $juncao = self::create();
        $pedido = $juncao->findPedidoID();
        $juncao->delete();
        $juncao->loadByID();
        $pedido->delete();
        $this->assertFalse($juncao->exists());
        MovimentacaoTest::close($movimentacao);
    }

    public function testJuntarLiberado()
    {
        $movimentacao = MovimentacaoTest::create();
        $juncao = self::build();
        $juncao->setEstado(Juncao::ESTADO_LIBERADO);
        $this->expectException('\MZ\Exception\ValidationException');
        try {
            $juncao->insert();
        } finally {
            $pedido = $juncao->findPedidoID();
            ItemTest::create($pedido);
            PedidoTest::close($pedido);
            MovimentacaoTest::close($movimentacao);
        }
    }

    public function testJuntarNovamente()
    {
        $movimentacao = MovimentacaoTest::create();
        $juncao = self::create();
        $this->expectException('\MZ\Exception\ValidationException');
        try {
            $juncao->insert();
        } finally {
            $pedido = $juncao->findPedidoID();
            ItemTest::create($pedido);
            PedidoTest::close($pedido);
            MovimentacaoTest::close($movimentacao);
        }
    }

    public function testJuntarMesmaMesa()
    {
        $movimentacao = MovimentacaoTest::create();
        $juncao = self::build();
        $pedido = $juncao->findPedidoID();
        $juncao->setMesaID($pedido->getMesaID());
        $this->expectException('\MZ\Exception\ValidationException');
        try {
            $juncao->insert();
        } finally {
            ItemTest::create($pedido);
            PedidoTest::close($pedido);
            MovimentacaoTest::close($movimentacao);
        }
    }

    public function testJuntarPedidoFechado()
    {
        $movimentacao = MovimentacaoTest::create();
        $juncao = self::build();
        $pedido = $juncao->findPedidoID();
        PedidoTest::close($pedido);
        $this->expectException('\MZ\Exception\ValidationException');
        try {
            $juncao->insert();
        } finally {
            MovimentacaoTest::close($movimentacao);
        }
    }

    public function testJuntarPedidoDiferente()
    {
        $movimentacao = MovimentacaoTest::create();
        $juncao = self::build();
        $old_pedido = $juncao->findPedidoID();
        $pedido = PedidoTest::create(Pedido::TIPO_COMANDA);
        $juncao->setPedidoID($pedido->getID());
        $this->expectException('\MZ\Exception\ValidationException');
        try {
            $juncao->insert();
        } finally {
            PedidoTest::close($pedido);
            PedidoTest::close($old_pedido);
            MovimentacaoTest::close($movimentacao);
        }
    }

    public function testAddInvalid()
    {
        $movimentacao = MovimentacaoTest::create();
        $juncao = self::build();
        $pedido = $juncao->findPedidoID();
        $juncao->setMesaID(null);
        $juncao->setPedidoID(null);
        $juncao->setEstado('Teste');
        try {
            $juncao->insert();
            $this->fail('valores invalidos');
        } catch (\MZ\Exception\ValidationException $e) {
            $this->assertEquals(
                ['mesaid', 'pedidoid', 'estado'],
                array_keys($e->getErrors())
            );
        }
        PedidoTest::close($pedido);
        MovimentacaoTest::close($movimentacao);
    }

    public function testGetEstado()
    {
        $movimentacao = MovimentacaoTest::create();
        $juncao = self::create();
        $options = Juncao::getEstadoOptions($juncao->getEstado());
        $this->assertEquals($juncao->getEstado(), $options);
        $pedido = $juncao->findPedidoID();
        PedidoTest::close($pedido);
        MovimentacaoTest::close($movimentacao);
    }
}
