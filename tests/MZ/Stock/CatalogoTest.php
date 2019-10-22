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

use MZ\Product\ProdutoTest;
use MZ\Stock\FornecedorTest;
use MZ\Exception\ValidationException;

class CatalogoTest extends \MZ\Framework\TestCase
{
    /**
     * Build a valid catálogo de produtos
     * @return Catalogo
     */
    public static function build()
    {
        $last = Catalogo::find([], ['id' => -1]);
        $id = $last->getID() + 1;
        $produto = ProdutoTest::create();
        $fornecedor = FornecedorTest::create();
        $catalogo = new Catalogo();
        $catalogo->setProdutoID($produto->getID());
        $catalogo->setFornecedorID($fornecedor->getID());
        $catalogo->setPrecoCompra(12.3);
        $catalogo->setPrecoVenda(12.3);
        $catalogo->setQuantidadeMinima(3);
        $catalogo->setEstoque(10);
        $catalogo->setLimitado('Y');
        $catalogo->setConteudo(12);
        return $catalogo;
    }

    /**
     * Create a catálogo de produtos on database
     * @return Catalogo
     */
    public static function create()
    {
        $catalogo = self::build();
        $catalogo->insert();
        return $catalogo;
    }

    public function testFind()
    {
        $catalogo = self::create();
        $condition = ['produtoid' => $catalogo->getProdutoID()];
        $found_catalogo = Catalogo::find($condition);
        $this->assertEquals($catalogo, $found_catalogo);
        list($found_catalogo) = Catalogo::findAll($condition, [], 1);
        $this->assertEquals($catalogo, $found_catalogo);
        $this->assertEquals(1, Catalogo::count($condition));
    }

    public function testFinds()
    {
        $catalogo = self::create();

        $produto = $catalogo->findProdutoID();
        $this->assertEquals($catalogo->getProdutoID(), $produto->getID());

        $fornecedor = $catalogo->findFornecedorID();
        $this->assertEquals($catalogo->getFornecedorID(), $fornecedor->getID());

        $catalogoByFornecedor = $catalogo->findByFornecedorID($fornecedor->getID());
        $this->assertInstanceOf(get_class($catalogo), $catalogoByFornecedor);
    }

    public function testAdd()
    {
        $catalogo = self::build();
        $catalogo->insert();
        $this->assertTrue($catalogo->exists());
    }

    public function testAddInvalid()
    {
        $catalogo = self::build();
        $catalogo->setProdutoID(null);
        $catalogo->setFornecedorID(null);
        $catalogo->setPrecoCompra(null);
        $catalogo->setPrecoVenda(null);
        $catalogo->setQuantidadeMinima(null);
        $catalogo->setEstoque(null);
        $catalogo->setLimitado(null);
        $catalogo->setConteudo(null);
        try {
            $catalogo->insert();
            $this->fail('Valores nulos');
        } catch (ValidationException $e) {
            $this->assertEquals(['produtoid', 'fornecedorid', 'precocompra', 'precovenda', 'quantidademinima',
            'estoque', 'limitado', 'conteudo'], array_keys($e->getErrors()));
        }
    }

    public function testTranslate()
    {
        $catalogo = self::create();
        try {
            $catalogo->insert();
            $this->fail('Fk duplicada');
        } catch (ValidationException $e) {
            $this->assertEquals(['fornecedorid'], array_keys($e->getErrors()));
        }
    }

    public function testUpdate()
    {
        $catalogo = self::create();
        $catalogo->update();
        $this->assertTrue($catalogo->exists());
    }

    public function testDelete()
    {
        $catalogo = self::create();
        $catalogo->delete();
        $catalogo->loadByID();
        $this->assertFalse($catalogo->exists());
    }
}
