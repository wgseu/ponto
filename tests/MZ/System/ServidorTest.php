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
namespace MZ\System;

use MZ\Util\Generator;
use MZ\Exception\ValidationException;

class ServidorTest extends \MZ\Framework\TestCase
{
    /**
     * Build a valid servidor
     * @param string $guid Servidor identificador único
     * @return Servidor
     */
    public static function build($guid = null)
    {
        $last = Servidor::find([], ['id' => -1]);
        $id = $last->getID() + 1;
        $servidor = new Servidor();
        $servidor->setGUID(Generator::guidv4());
        return $servidor;
    }

    /**
     * Create a servidor on database
     * @param string $guid Servidor identificador único
     * @return Servidor
     */
    public static function create($guid = null)
    {
        $servidor = self::build($guid);
        $servidor->insert();
        return $servidor;
    }

    public function testFind()
    {
        $servidor = self::create();
        $condition = ['guid' => $servidor->getGUID()];
        $found_servidor = Servidor::find($condition);
        $this->assertEquals($servidor, $found_servidor);
        list($found_servidor) = Servidor::findAll($condition, [], 1);
        $this->assertEquals($servidor, $found_servidor);
        $this->assertEquals(1, Servidor::count($condition));
    }

    public function testFindGUID()
    {
        $servidor = self::create();
        $servidorFound = Servidor::findByGUID($servidor->getGUID());
        $this->assertInstanceOf(get_class($servidor), $servidorFound);
    }

    public function testAdd()
    {
        $servidor = self::build();
        $servidor->insert();
        $this->assertTrue($servidor->exists());
    }

    public function testAddInvalid()
    {
        $servidor = self::build();
        $servidor->setGUID(null);
        try {
            $servidor->insert();
            $this->fail('Valor invalido');
        } catch (ValidationException $e) {
            $this->assertEquals(['guid'], array_keys($e->getErrors()));
        }
    }

    public function testFromArray()
    {
        $old= new Servidor(['guid' => Generator::guidv4()]);
        $servidor = new Servidor();
        $servidor->fromArray($old);
        $this->assertEquals($servidor, $old);
        $servidor->fromArray(null);
        $this->assertEquals($servidor, new Servidor());
        $servidor->clean($old);
    }

    public function testFilter()
    {
        $old = new Servidor([
            'id' => 1,
            'guid' => 54543433,
        ]);
        $servidor = new Servidor([
            'id' => 1,
            'guid' => '54543433',
        ]);
        $servidor->filter($old, app()->auth->provider, true);
        $this->assertEquals($old, $servidor);
    }

    public function testPublish()
    {
        $servidor = new Servidor();
        $values = $servidor->publish(app()->auth->provider);
        $allowed = [
            'id',
            'guid',
            'sincronizadoate',
            'ultimasincronizacao'
        ];
        $this->assertEquals($allowed, array_keys($values));
    }

    public function testTranslate()
    {
        $servidor = self::create();
        try {
            $servidor->insert();
            $this->fail('Fk duplicada');
        } catch (ValidationException $e) {
            $this->assertEquals(['guid'], array_keys($e->getErrors()));
        }
    }

    public function testUpdate()
    {
        $servidor = self::create();
        $servidor->update();
        $this->assertTrue($servidor->exists());
    }

    public function testDelete()
    {
        $servidor = self::create();
        $servidor->delete();
        $servidor->loadByID();
        $this->assertFalse($servidor->exists());
    }
}
