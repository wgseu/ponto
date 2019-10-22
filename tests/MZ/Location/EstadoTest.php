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

use MZ\Location\PaisTest;
use MZ\Exception\ValidationException;

class EstadoTest extends \MZ\Framework\TestCase
{
    /**
     * Build a valid estado
     * @param string $nome Estado nome
     * @return Estado
     */
    public static function build($nome = null)
    {
        $last = Estado::find([], ['id' => -1]);
        $id = $last->getID() + 1;
        $pais = PaisTest::create();
        $estado = new Estado();
        $estado->setPaisID($pais->getID());
        $estado->setNome($nome ?: "Estado {$id}");
        $estado->setUF("S{$id}");
        return $estado;
    }

    /**
     * Create a estado on database
     * @param string $nome Estado nome
     * @return Estado
     */
    public static function create($nome = null)
    {
        $estado = self::build($nome);
        $estado->insert();
        return $estado;
    }

    public function testFind()
    {
        $estado = self::create();
        $condition = ['nome' => $estado->getNome()];
        $found_estado = Estado::find($condition);
        $this->assertEquals($estado, $found_estado);
        list($found_estado) = Estado::findAll($condition, [], 1);
        $this->assertEquals($estado, $found_estado);
        $this->assertEquals(1, Estado::count($condition));
    }

    public function testFinds()
    {
        $estado = self::create();

        $pais = $estado->findPaisID();
        $this->assertEquals($estado->getPaisID(), $pais->getID());

        $estadoByPaisIDNome = $estado->findByPaisIDNome($pais->getID(), $estado->getNome());
        $this->assertInstanceOf(get_class($estado), $estadoByPaisIDNome);

        $estadoByPaisIDUF = $estado->findByPaisIDUF($pais->getID(), $estado->getUF());
        $this->assertInstanceOf(get_class($estado), $estadoByPaisIDUF);
    }

    public function testAdd()
    {
        $estado = self::build();
        $estado->insert();
        $this->assertTrue($estado->exists());
    }

    public function testAddInvalid()
    {
        $estado = self::build();
        $estado->setPaisID(null);
        $estado->setNome(null);
        $estado->setUF(null);
        try {
            $estado->insert();
            $estado->fail('Não cadastrar');
        } catch (ValidationException $e) {
            $this->assertEquals(['paisid', 'nome', 'uf'], array_keys($e->getErrors()));
        }
    }

    public function testTranslate()
    {
        $estado = self::build();
        $estado->setNome('Teste');
        $estado->insert();
        try {
            $estado->setUF('TR');
            $estado->insert();
            $this->fail('fk duplicada');
        } catch (ValidationException $e) {
            $this->assertEquals(['paisid', 'nome'], array_keys($e->getErrors()));
        }
        //-----------------------
        $estado = self::build();
        $estado->insert();
        try {
            $estado->insert();
            $this->fail('fk duplicada');
        } catch (ValidationException $e) {
            $this->assertEquals(['paisid', 'uf'], array_keys($e->getErrors()));
        }
    }

    public function testUpdate()
    {
        $estado = self::create();
        $estado->update();
        $this->assertTrue($estado->exists());
    }

    public function testDelete()
    {
        $estado = self::create();
        $estado->delete();
        $estado->loadByID();
        $this->assertFalse($estado->exists());
    }
}
