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
namespace MZ\Account;

use MZ\Account\ClienteTest;
use MZ\Location\PaisTest;
use MZ\Exception\ValidationException;

class TelefoneTest extends \MZ\Framework\TestCase
{
    /**
     * Build a valid telefone
     * @param string $numero Telefone número
     * @return Telefone
     */
    public static function build($numero = null)
    {
        $last = Telefone::find([], ['id' => -1]);
        $id = $last->getID() + 1;
        $cliente = ClienteTest::create();
        $pais = PaisTest::create();
        $telefone = new Telefone();
        $telefone->setClienteID($cliente->getID());
        $telefone->setPaisID($pais->getID());
        $telefone->setNumero(\str_pad("{$id}", 10, "0"));
        $telefone->setPrincipal('Y');
        return $telefone;
    }

    /**
     * Create a telefone on database
     * @param string $numero Telefone número
     * @return Telefone
     */
    public static function create($numero = null)
    {
        $telefone = self::build($numero);
        $telefone->insert();
        return $telefone;
    }

    public function testFind()
    {
        $telefone = self::create();
        $condition = ['numero' => $telefone->getNumero()];
        $found_telefone = Telefone::find($condition);
        $this->assertEquals($telefone, $found_telefone);
        list($found_telefone) = Telefone::findAll($condition, [], 1);
        $this->assertEquals($telefone, $found_telefone);
        $this->assertEquals(1, Telefone::count($condition));
        //----
        $cliente = $telefone->findClienteID();
        $this->assertEquals($telefone->getClienteID(), $cliente->getID());
    }

    public function testAdd()
    {
        $telefone = self::build();
        $telefone->insert();
        $this->assertTrue($telefone->exists());
    }

    public function testAddInvalid()
    {
        $telefone = self::build();
        $telefone->setClienteID(null);
        $telefone->setPaisID(null);
        $telefone->setNumero(null);
        $telefone->setPrincipal('E');
        try {
            $telefone->insert();
            $this->fail('Não cadastrar valores nulos');
        } catch (ValidationException $e) {
            $this->assertEquals(['clienteid', 'paisid', 'numero', 'principal'], array_keys($e->getErrors()));
        }
    }

    public function testUpdate()
    {
        $telefone = self::create();
        $telefone->update();
        $this->assertTrue($telefone->exists());
    }

    public function testDelete()
    {
        $telefone = self::create();
        $telefone->delete();
        $telefone->loadByID();
        $this->assertFalse($telefone->exists());
    }
}
