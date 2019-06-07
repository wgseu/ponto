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
use MZ\Account\TelefoneTest;
use MZ\Exception\ValidationException;

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

    public function testQueryEmail()
    {
        $fornecedor = self::create();
        $cliente = $fornecedor->findEmpresaID();
        $condition = ['search' => $cliente->getEmail()];
        $found_fornecedor = Fornecedor::find($condition);
        $this->assertEquals($fornecedor, $found_fornecedor);
    }

    public function testQueryCPF()
    {
        $fornecedor = self::create();
        $cliente = $fornecedor->findEmpresaID();
        $cliente->setCPF('52238376000132');
        $cliente->update();
        $condition = ['search' => $cliente->getCPF()];
        $found_fornecedor = Fornecedor::find($condition);
        $this->assertEquals($fornecedor, $found_fornecedor);
    }

    public function testQueryPhone()
    {
        $fornecedor = self::create();
        $cliente = $fornecedor->findEmpresaID();
        $telefone = TelefoneTest::build();
        $telefone->setClienteID($cliente->getID());
        $telefone->setNumero('44999719966');
        $telefone->insert();
        $condition = ['search' => $telefone->getNumero()];
        $found_fornecedor = Fornecedor::find($condition);
        $this->assertEquals($fornecedor, $found_fornecedor);
    }

    public function testValidate()
    {
        //testa empresaid nula
        $fornecedor = self::build();
        $fornecedor->setEmpresaID(null);
        try {
            $fornecedor->insert();
            $this->fail('EmpresaID não pode ser nulo');
        } catch (ValidationException $e) {
            $this->assertEquals(['empresaid'], array_keys($e->getErrors()));
        }
        //teste prazo pagamento nulo
        $fornecedor = self::build();
        $fornecedor->setPrazoPagamento(null);
        try {
            $fornecedor->insert();
            $this->fail('Prazo pagamento não pode ser nulo');
        } catch (ValidationException $e) {
            $this->assertEquals(['prazopagamento'], array_keys($e->getErrors()));
        }
        //teste de cliente juridico
        $fornecedor = self::build();
        $cliente = $fornecedor->findEmpresaID();
        $cliente->setTipo(Cliente::TIPO_FISICA);
        $cliente->update();
        try {
            $fornecedor->insert();
            $this->fail('Empresa diferente de juridica');
        } catch (ValidationException $e) {
            $this->assertEquals(['empresaid'], array_keys($e->getErrors()));
        }
    }

    public function testTranslate()
    {
        $fornecedorInit = self::create();
        $fornecedor = self::build();
        $fornecedor->setEmpresaID($fornecedorInit->getEmpresaID());
        try {
            $fornecedor->insert();
            $this->fail('Empresa duplicado para fornecedor');
        } catch (ValidationException $e) {
            $this->assertEquals(['empresaid'], array_keys($e->getErrors()));
        }

    }

    public function testFindEmpresaID()
    {
        $fornecedor = self::create();
        $cliente = $fornecedor->findEmpresaID();
        $this->assertEquals($fornecedor->getEmpresaID(), $cliente->getID());
    }

    public function testFindByEmpresaID()
    {
        $cliente = ClienteTest::create();
        $fornecedor = Fornecedor::findByEmpresaID($cliente->getID());
        $fornecedor->setEmpresaID($cliente->getID());
        $this->assertEquals($fornecedor->getEmpresaID(), $cliente->getID());
    }

    public function testAdd()
    {
        $fornecedor = self::build();
        $fornecedor->insert();
        $this->assertTrue($fornecedor->exists());
    }

}
