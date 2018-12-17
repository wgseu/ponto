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
namespace MZ\Product;

use MZ\Product\ProdutoTest;

class ComposicaoTest extends \MZ\Framework\TestCase
{
    /**
     * Build a valid composição
     * @return Composicao
     */
    public static function build()
    {
        $last = Composicao::find([], ['id' => -1]);
        $id = $last->getID() + 1;
        $produto = ProdutoTest::create();
        $produto = ProdutoTest::create();
        $composicao = new Composicao();
        $composicao->setComposicaoID($produto->getID());
        $composicao->setProdutoID($produto->getID());
        $composicao->setTipo(Composicao::TIPO_COMPOSICAO);
        $composicao->setQuantidade('Quantidade da composição');
        $composicao->setValor(12.3);
        $composicao->setAtiva('Y');
        return $composicao;
    }

    /**
     * Create a composição on database
     * @return Composicao
     */
    public static function create()
    {
        $composicao = self::build();
        $composicao->insert();
        return $composicao;
    }

    public function testFind()
    {
        $composicao = self::create();
        $condition = ['produtoid' => $composicao->getProdutoID()];
        $found_composicao = Composicao::find($condition);
        $this->assertEquals($composicao, $found_composicao);
        list($found_composicao) = Composicao::findAll($condition, [], 1);
        $this->assertEquals($composicao, $found_composicao);
        $this->assertEquals(1, Composicao::count($condition));
    }

    public function testAdd()
    {
        $composicao = self::build();
        $composicao->insert();
        $this->assertTrue($composicao->exists());
    }

    public function testUpdate()
    {
        $composicao = self::create();
        $composicao->update();
        $this->assertTrue($composicao->exists());
    }

    public function testDelete()
    {
        $composicao = self::create();
        $composicao->delete();
        $composicao->loadByID();
        $this->assertFalse($composicao->exists());
    }
}
