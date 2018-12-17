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

use MZ\Account\ClienteTest;
use MZ\Account\Cliente;

class FornecedorTest extends \MZ\Framework\TestCase
{
    /**
     * Build a valid fornecedor
     * @param string $nome nome do fornecedor
     * @return Fornecedor
     */
    public static function build($nome = null)
    {
        $last = Fornecedor::find([], ['id' => -1]);
        $id = $last->getID() + 1;
        $cliente = ClienteTest::build();
        $cliente->setTipo(Cliente::TIPO_JURIDICA);
        $cliente->setNome($nome ?? "Fornecedor ${id}");
        $cliente->setSobrenome(($nome ?? "Fornecedor ${id}") . ' LTDA');
        $cliente->insert();
        $fornecedor = new Fornecedor();
        $fornecedor->setEmpresaID($cliente->getID());
        $fornecedor->setPrazoPagamento(30);
        return $fornecedor;
    }

    /**
     * Create a fornecedor on database
     * @param string $nome nome do fornecedor
     * @return Fornecedor
     */
    public static function create($nome = null)
    {
        $fornecedor = self::build($nome);
        $fornecedor->insert();
        return $fornecedor;
    }

    public function testFind()
    {
        $fornecedor = self::create();
        $condition = ['empresaid' => $fornecedor->getEmpresaID()];
        $found_fornecedor = Fornecedor::find($condition);
        $this->assertEquals($fornecedor, $found_fornecedor);
        list($found_fornecedor) = Fornecedor::findAll($condition, [], 1);
        $this->assertEquals($fornecedor, $found_fornecedor);
        $this->assertEquals(1, Fornecedor::count($condition));
    }

    public function testAdd()
    {
        $fornecedor = self::build();
        $fornecedor->insert();
        $this->assertTrue($fornecedor->exists());
    }

    public function testUpdate()
    {
        $fornecedor = self::create();
        $fornecedor->update();
        $this->assertTrue($fornecedor->exists());
    }

    public function testDelete()
    {
        $fornecedor = self::create();
        $fornecedor->delete();
        $fornecedor->loadByID();
        $this->assertFalse($fornecedor->exists());
    }
}
