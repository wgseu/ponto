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
        $juncao = self::create();
        $condition = ['mesaid' => $juncao->getMesaID()];
        $found_juncao = Juncao::find($condition);
        $this->assertEquals($juncao, $found_juncao);
        list($found_juncao) = Juncao::findAll($condition, [], 1);
        $this->assertEquals($juncao, $found_juncao);
        $this->assertEquals(1, Juncao::count($condition));
    }

    public function testAdd()
    {
        $juncao = self::build();
        $juncao->insert();
        $this->assertTrue($juncao->exists());
    }

    public function testUpdate()
    {
        $juncao = self::create();
        $juncao->update();
        $this->assertTrue($juncao->exists());
    }

    public function testDelete()
    {
        $juncao = self::create();
        $juncao->delete();
        $juncao->loadByID();
        $this->assertFalse($juncao->exists());
    }

    public function testJuntarLiberado()
    {
        $juncao = self::build();
        $juncao->setEstado(Juncao::ESTADO_LIBERADO);
        $this->expectException('\MZ\Exception\ValidationException');
        $juncao->insert();
    }

    public function testJuntarNovamente()
    {
        $juncao = self::create();
        $this->expectException('\MZ\Exception\ValidationException');
        $juncao->insert();
    }

    public function testJuntarMesmaMesa()
    {
        $juncao = self::build();
        $pedido = $juncao->findPedidoID();
        $juncao->setMesaID($pedido->getMesaID());
        $this->expectException('\MZ\Exception\ValidationException');
        $juncao->insert();
    }

    public function testJuntarPedidoFechado()
    {
        $juncao = self::build();
        $pedido = $juncao->findPedidoID();
        $pedido->setEstado(Pedido::ESTADO_FINALIZADO);
        $pedido->update();
        $this->expectException('\MZ\Exception\ValidationException');
        $juncao->insert();
    }

    public function testJuntarPedidoDiferente()
    {
        $juncao = self::build();
        $pedido = PedidoTest::create(Pedido::TIPO_COMANDA);
        $juncao->setPedidoID($pedido->getID());
        $this->expectException('\MZ\Exception\ValidationException');
        $juncao->insert();
    }
}
