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
namespace MZ\Location;

use MZ\Location\BairroTest;
use MZ\Exception\ValidationException;

class ZonaTest extends \MZ\Framework\TestCase
{
    /**
     * Build a valid zona
     * @param string $nome Zona nome
     * @return Zona
     */
    public static function build($nome = null)
    {
        $last = Zona::find([], ['id' => -1]);
        $id = $last->getID() + 1;
        $bairro = BairroTest::create();
        $zona = new Zona();
        $zona->setBairroID($bairro->getID());
        $zona->setNome('Nome da zona');
        $zona->setAdicionalEntrega(12.3);
        $zona->setDisponivel('Y');
        return $zona;
    }

    /**
     * Create a zona on database
     * @param string $nome Zona nome
     * @return Zona
     */
    public static function create($nome = null)
    {
        $zona = self::build($nome);
        $zona->insert();
        return $zona;
    }

    public function testFind()
    {
        $zona = self::create();
        $condition = ['nome' => $zona->getNome()];
        $found_zona = Zona::find($condition);
        $this->assertEquals($zona, $found_zona);
        list($found_zona) = Zona::findAll($condition, [], 1);
        $this->assertEquals($zona, $found_zona);
        $this->assertEquals(1, Zona::count($condition));
    }

    public function testFinds()
    {
        $zona = self::create();
        $bairro = $zona->findBairroID();
        $this->assertEquals($zona->getBairroID(), $bairro->getID());

        $zonaByBairroIDNome = $zona->findByBairroIDNome($bairro->getID(), $zona->getNome());
        $this->assertInstanceOf(get_class($zona), $zonaByBairroIDNome);
    }

    public function testAdd()
    {
        $zona = self::build();
        $zona->insert();
        $this->assertTrue($zona->exists());
    }

    public function testAddInvalid()
    {
        $zona = self::build();
        $zona->setBairroID(null);
        $zona->setNome(null);
        $zona->setAdicionalEntrega(null);
        $zona->setDisponivel('E');
        try {
            $zona->insert();
            $this->fail('Não cadastrar valores nulos');
        } catch (ValidationException $e) {
            $this->assertEquals(['bairroid', 'nome', 'adicionalentrega', 'disponivel'], array_keys($e->getErrors()));
        }
    }

    public function testTranslate()
    {
        $zona = self::build();
        $zona->setID(12);
        $zona->insert();
        try {
            $zona->insert();
            $this->fail('Fk duplicada');
        } catch (ValidationException $e) {
            $this->assertEquals(['bairroid', 'nome'], array_keys($e->getErrors()));
        }
    }

    public function testUpdate()
    {
        $zona = self::create();
        $zona->update();
        $this->assertTrue($zona->exists());
    }

    public function testDelete()
    {
        $zona = self::create();
        $zona->delete();
        $zona->loadByID();
        $this->assertFalse($zona->exists());
    }
}
