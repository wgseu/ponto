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

use MZ\Exception\ValidationException;

class InformacaoTest extends \MZ\Framework\TestCase
{
    // public function testPublish()
    // {
    //     $informacao = new Informacao();
    //     $values = $informacao->publish(app()->auth->provider);
    //     $allowed = [
    //         'id',
    //         'produtoid',
    //         'unidadeid',
    //         'porcao',
    //         'dieta',
    //         'ingredientes',
    //     ];
    //     $this->assertEquals($allowed, array_keys($values));
    // }

    /**
     * Build a valid informação nutricional
     * @return Informacao
     */
    public static function build()
    {
        $last = Informacao::find([], ['id' => -1]);
        $id = $last->getID() + 1;
        $produto = ProdutoTest::create();
        $unidade = UnidadeTest::create();
        $informacao = new Informacao();
        $informacao->setProdutoID($produto->getID());
        $informacao->setUnidadeID($unidade->getID());
        $informacao->setPorcao(12.3);
        $informacao->setDieta(12.3);
        return $informacao;
    }

    /**
     * Create a informação nutricional on database
     * @return Informacao
     */
    public static function create()
    {
        $informacao = self::build();
        $informacao->insert();
        return $informacao;
    }

    public function testFind()
    {
        $informacao = self::create();
        $condition = ['produtoid' => $informacao->getProdutoID()];
        $found_informacao = Informacao::find($condition);
        $this->assertEquals($informacao, $found_informacao);
        list($found_informacao) = Informacao::findAll($condition, [], 1);
        $this->assertEquals($informacao, $found_informacao);
        $this->assertEquals(1, Informacao::count($condition));
    }

    public function testAdd()
    {
        $informacao = self::build();
        $informacao->insert();
        $this->assertTrue($informacao->exists());
    }

    public function testAddInvalid()
    {
        $informacao = self::build();
        $informacao->setProdutoID(null);
        $informacao->setUnidadeID(null);
        $informacao->setPorcao(null);
        $informacao->setDieta(null);
        try {
            $informacao->insert();
            $this->fail('Não cadastrar valores nulos');
        } catch (ValidationException $e) {
            $this->assertEquals(['produtoid', 'unidadeid', 'porcao', 'dieta'], array_keys($e->getErrors()));
        }
    }

    public function testTranslate()
    {
        $informacao = self::create();
        try {
            $informacao->insert();
            $this->fail('Não cadastrar produto repetido');
        } catch (ValidationException $e) {
            $this->assertEquals(['produtoid'], array_keys($e->getErrors()));
        }
    }

    public function testFinds()
    {
        $informacao = self::create();

        $produto = $informacao->findProdutoID();
        $this->assertEquals($informacao->getProdutoID(), $produto->getID());

        $unidade = $informacao->findUnidadeID();
        $this->assertEquals($informacao->getUnidadeID(), $unidade->getID());

        $info = $informacao->findByProdutoID($produto->getID());
        $this->assertInstanceOf(get_class($informacao), $info);
    }

    public function testUpdate()
    {
        $informacao = self::create();
        $informacao->update();
        $this->assertTrue($informacao->exists());
    }

    public function testDelete()
    {
        $informacao = self::create();
        $informacao->delete();
        $informacao->loadByID();
        $this->assertFalse($informacao->exists());
    }
}
