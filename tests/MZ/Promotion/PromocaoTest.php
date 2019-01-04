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
namespace MZ\Promotion;

use MZ\Product\ProdutoTest;
use MZ\Product\CategoriaTest;
use MZ\Util\Date;

class PromocaoTest extends \MZ\Framework\TestCase
{
    /**
     * Build a valid promoção
     * @return Promocao
     */
    public static function build()
    {
        $last = Promocao::find([], ['id' => -1]);
        $id = $last->getID() + 1;
        $produto = ProdutoTest::create();
        $promocao = new Promocao();
        $promocao->setProdutoID($produto->getID());
        $promocao->setInicio(Date::MINUTES_PER_DAY + $id * 20);
        $promocao->setFim($promocao->getInicio() + 10);
        $promocao->setValor(12.3);
        $promocao->setPontos(123);
        $promocao->setParcial('Y');
        $promocao->setProibir('N');
        $promocao->setEvento('N');
        $promocao->setAgendamento('N');
        return $promocao;
    }

    /**
     * Create a promoção on database
     * @return Promocao
     */
    public static function create()
    {
        $promocao = self::build();
        $promocao->insert();
        return $promocao;
    }

    public function testFind()
    {
        $promocao = self::create();
        $condition = ['produtoid' => $promocao->getProdutoID()];
        $found_promocao = Promocao::find($condition);
        $this->assertEquals($promocao, $found_promocao);
        list($found_promocao) = Promocao::findAll($condition, [], 1);
        $this->assertEquals($promocao, $found_promocao);
        $this->assertEquals(1, Promocao::count($condition));
    }

    public function testAdd()
    {
        $promocao = self::build();
        $promocao->insert();
        $this->assertTrue($promocao->exists());
    }

    public function testUpdate()
    {
        $promocao = self::create();
        $promocao->update();
        $this->assertTrue($promocao->exists());
    }

    public function testDelete()
    {
        $promocao = self::create();
        $promocao->delete();
        $promocao->loadByID();
        $this->assertFalse($promocao->exists());
    }

    public function testMultipleSelections()
    {
        $promocao = self::build();
        $promocao->setCategoriaID(CategoriaTest::create([])->getID());
        $this->expectException('\MZ\Exception\ValidationException');
        $promocao->insert();
    }

    public function testInvalidInterval()
    {
        $promocao = self::build();
        $inicio = $promocao->getInicio();
        $promocao->setInicio($promocao->getFim());
        $promocao->setFim($inicio);
        $this->expectException('\MZ\Exception\ValidationException');
        $promocao->insert();
    }

    public function testInvalidStart()
    {
        $promocao = self::build();
        $promocao->setInicio(1439);
        $this->expectException('\MZ\Exception\ValidationException');
        $promocao->insert();
    }

    public function testInvalidEnd()
    {
        $promocao = self::build();
        $promocao->setFim(Date::MINUTES_PER_DAY * 8);
        $this->expectException('\MZ\Exception\ValidationException');
        $promocao->insert();
    }

    public function testExistingInto()
    {
        $promocao = self::create();
        $promocao->setInicio($promocao->getInicio() - 1);
        $promocao->setFim($promocao->getFim() + 1);
        $this->expectException('\MZ\Exception\ValidationException');
        $promocao->insert();
    }

    public function testExistingBefore()
    {
        $promocao = self::create();
        $promocao->setInicio($promocao->getInicio() - 1);
        $promocao->setFim($promocao->getFim() - 1);
        $this->expectException('\MZ\Exception\ValidationException');
        $promocao->insert();
    }

    public function testExistingAfter()
    {
        $promocao = self::create();
        $promocao->setInicio($promocao->getInicio() + 1);
        $promocao->setFim($promocao->getFim() + 1);
        $this->expectException('\MZ\Exception\ValidationException');
        $promocao->insert();
    }

    public function testExistingExternal()
    {
        $promocao = self::create();
        $promocao->setInicio($promocao->getInicio() + 1);
        $promocao->setFim($promocao->getFim() - 1);
        $this->expectException('\MZ\Exception\ValidationException');
        $promocao->insert();
    }
}
