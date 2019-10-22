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
use MZ\Exception\ValidationException;

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
        $composicao->setQuantidade(0.0);
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

    public function testFinds()
    {
        $composicao = self::create();

        $composicaoFind = $composicao->findComposicaoID();
        $this->assertEquals($composicao->getComposicaoID(), $composicaoFind->getID());

        $produto = $composicao->findProdutoID();
        $this->assertEquals($composicao->getProdutoID(), $produto->getID());

        $comp = $composicao->findByComposicaoIDProdutoIDTipo($composicaoFind->getID(), $produto->getID(), $composicao->getTipo());
        $this->assertInstanceOf(get_class($composicao), $comp);
    }

    public function testAdd()
    {
        $composicao = self::build();
        $composicao->insert();
        $this->assertTrue($composicao->exists());
    }

    public function testAddInvalid()
    {
        $composicao = self::build();
        $composicao->setComposicaoID(null);
        $composicao->setProdutoID(null);
        $composicao->setTipo(null);
        $composicao->setQuantidade(null);
        $composicao->setValor(null);
        $composicao->setQuantidadeMaxima(null);
        $composicao->setAtiva(null);
        try {
            $composicao->insert();
            $this->fail('Não inserir valores nulos');
        } catch (ValidationException $e) {
            $this->assertEquals(['composicaoid', 'produtoid', 'tipo', 'quantidade', 'valor', 'quantidademaxima', 'ativa'], array_keys($e->getErrors()));
        }
    }

    public function testTranslate()
    {
        $composicao = self::build();
        $composicao->insert();
        try {
            $composicao->insert();
            $this->fail('Não cadastrar com fk duplicada');
        } catch (ValidationException $e) {
            $this->assertEquals(['composicaoid', 'produtoid', 'tipo'], array_keys($e->getErrors()));
        }
    }

    public function testOptions()
    {
        $composicao = self::create();
        $options = Composicao::getTipoOptions($composicao->getTipo());

        $this->assertEquals('Composição', $options);
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
