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

namespace MZ\Stock;

use MZ\Stock\ListaTest;
use MZ\Product\ProdutoTest;
use MZ\Exception\ValidationException;

class RequisitoTest extends \MZ\Framework\TestCase
{
    /**
     * Build a valid produtos da lista
     * @return Requisito
     */
    public static function build()
    {
        $last = Requisito::find([], ['id' => -1]);
        $id = $last->getID() + 1;
        $lista = ListaTest::create();
        $produto = ProdutoTest::create();
        $requisito = new Requisito();
        $requisito->setListaID($lista->getID());
        $requisito->setProdutoID($produto->getID());
        $requisito->setQuantidade(12.3);
        $requisito->setComprado(12.3);
        $requisito->setPrecoMaximo(12.3);
        $requisito->setPreco(12.3);
        return $requisito;
    }

    /**
     * Create a produtos da lista on database
     * @return Requisito
     */
    public static function create()
    {
        $requisito = self::build();
        $requisito->insert();
        return $requisito;
    }

    public function testFind()
    {
        $requisito = self::create();
        $condition = ['produtoid' => $requisito->getProdutoID()];
        $found_requisito = Requisito::find($condition);
        $this->assertEquals($requisito, $found_requisito);
        list($found_requisito) = Requisito::findAll($condition, [], 1);
        $this->assertEquals($requisito, $found_requisito);
        $this->assertEquals(1, Requisito::count($condition));
    }

    public function testFinds()
    {
        $requisito = self::create();

        $lista = $requisito->findListaID();
        $this->assertEquals($requisito->getListaID(), $lista->getID());

        $produto = $requisito->findProdutoID();
        $this->assertEquals($requisito->getProdutoID(), $produto->getID());

        $compra = $requisito->findCompraID();
        $this->assertEquals($requisito->getCompraID(), $compra->getID());

        $fornecedor = $requisito->findFornecedorID();
        $this->assertEquals($requisito->getFornecedorID(), $fornecedor->getID());
    }

    public function testAdd()
    {
        $requisito = self::build();
        $requisito->insert();
        $this->assertTrue($requisito->exists());
    }

    public function testAddInvalid()
    {
        $requisito = self::build();
        $requisito->setListaID(null);
        $requisito->setProdutoID(null);
        $requisito->setQuantidade(null);
        $requisito->setComprado(null);
        $requisito->setPrecoMaximo(null);
        $requisito->setPreco(null);
        try {
            $requisito->insert();
            $this->fail('Nao cadastrar valores nulos');
        } catch (ValidationException $e) {
            $this->assertEquals(['listaid', 'produtoid', 'quantidade', 'comprado', 'precomaximo', 'preco'],
            array_keys($e->getErrors()));
        }
    }

    public function testUpdate()
    {
        $requisito = self::create();
        $requisito->update();
        $this->assertTrue($requisito->exists());
    }

    public function testDelete()
    {
        $requisito = self::create();
        $requisito->delete();
        $requisito->loadByID();
        $this->assertFalse($requisito->exists());
    }
}
