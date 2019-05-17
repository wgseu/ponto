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

use MZ\Provider\PrestadorTest;
use MZ\Stock\FornecedorTest;
use MZ\Exception\ValidationException;

class CompraTest extends \MZ\Framework\TestCase
{
    /**
     * Build a valid compra
     * @return Compra
     */
    public static function build()
    {
        $last = Compra::find([], ['id' => -1]);
        $id = $last->getID() + 1;
        $prestador = PrestadorTest::create();
        $fornecedor = FornecedorTest::create();
        $compra = new Compra();
        $compra->setCompradorID($prestador->getID());
        $compra->setFornecedorID($fornecedor->getID());
        $compra->setDataCompra('2016-12-25 12:15:00');
        return $compra;
    }

    /**
     * Create a compra on database
     * @return Compra
     */
    public static function create()
    {
        $compra = self::build();
        $compra->insert();
        return $compra;
    }

    public function testFind()
    {
        $compra = self::create();
        $condition = ['compradorid' => $compra->getCompradorID()];
        $found_compra = Compra::find($condition);
        $this->assertEquals($compra, $found_compra);
        list($found_compra) = Compra::findAll($condition, [], 1);
        $this->assertEquals($compra, $found_compra);
        $this->assertEquals(1, Compra::count($condition));
    }

    public function testFinds()
    {
        $compra = self::create();

        $comprador = $compra->findCompradorID();
        $this->assertEquals($compra->getCompradorID(), $comprador->getID());

        $fornecedor = $compra->findFornecedorID();
        $this->assertEquals($compra->getFornecedorID(), $fornecedor->getID());

        $compraByNum = $compra->findByNumero($compra->getNumero());
        $this->assertInstanceOf(get_class($compra), $compraByNum);
    }

    public function testAdd()
    {
        $compra = self::build();
        $compra->insert();
        $this->assertTrue($compra->exists());
    }

    public function testAddInvalid()
    {
        $compra = self::build();
        $compra->setCompradorID(null);
        $compra->setFornecedorID(null);
        $compra->setDataCompra(null);
        try {
            $compra->insert();
            $this->fail('Valores nulos');
        } catch (ValidationException $e) {
            $this->assertEquals(['compradorid', 'fornecedorid', 'datacompra'], array_keys($e->getErrors()));
        }
    }

    public function testTranslate()
    {
        $compra = self::build();
        $compra->setNumero('2345678');
        $compra->insert();
        try {
            $compra->insert();
            $this->fail("fk duplicada");
        } catch (ValidationException $e) {
            $this->assertEquals(['numero'], array_keys($e->getErrors()));
        }
    }

    public function testMakeDocURL()
    {
        $compra = new Compra();
        $img = $compra->makeDocumentoURL(true);
        $this->assertEquals('/static/img/compra.png', $img);
        $compra->setDocumentoURL('teste.png');
        $this->assertEquals('/static/img/compra/teste.png', $compra->makeDocumentoURL());
    }

    public function testClean()
    {
        $old = new Compra();
        $old->setDocumentoURL('teste.png');
        $compra = new Compra();
        $compra->setDocumentoURL('teste1.png');
        $compra->clean($old);
        $this->assertEquals($old, $compra);
    }

    public function testUpdate()
    {
        $compra = self::create();
        $compra->update();
        $this->assertTrue($compra->exists());
    }

    public function testDelete()
    {
        $compra = self::create();
        $compra->delete();
        $compra->loadByID();
        $this->assertFalse($compra->exists());
    }
}
