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

use MZ\Account\ClienteTest;
use MZ\Location\PaisTest;


class EmpresaTest extends \MZ\Framework\TestCase
{
    /**
     * Build a valid empresa
     * @return Empresa
     */
    public static function build()
    {
        $last = Empresa::find([], ['id' => -1]);
        $id = $last->getID() + 1;
        $empresa = new Empresa();
        return $empresa;
    }

    /**
     * Create a empresa on database
     * @return Empresa
     */
    public static function create()
    {
        $empresa = self::build();
        $this->expectException('\Exception');
        $empresa->insert();
        return $empresa;
    }

    public static function loadCompany()
    {
        $empresa = Empresa::find([], ['id' => -1]);
        return $empresa;
    }

    public function testFind()
    {
        $empresa = self::loadCompany();
        $condition = ['empresaid' => $empresa->getEmpresaID()];
        $found_empresa = Empresa::find($condition);
        $this->assertEquals($empresa, $found_empresa);
        list($found_empresa) = Empresa::findAll($condition, [], 1);
        $this->assertEquals($empresa, $found_empresa);
        $this->assertEquals(1, Empresa::count($condition));
    }

    public function testAdd()
    {
        $empresa = self::build();
        $this->expectException('\Exception');
        $empresa->insert();
    }

    public function testUpdate()
    {
        $empresa = self::loadCompany();
        $empresa->update();
        $this->assertTrue($empresa->exists());
    }

    public function testDelete()
    {
        $empresa = self::loadCompany();
        $this->expectException('\Exception');
        $empresa->delete();
        $empresa->loadByID();
    }

    public function testFindParceiroID()
    {
        $empresa = self::loadCompany();
        $empresa->setParceiroID(1);
        $paceiro = $empresa->findParceiroID();
        $this->assertEquals($empresa->getParceiroID(), $paceiro->getID());
    }

    public function testfromArray()
    {
        $empresa = self::loadCompany();
        $result = $empresa->fromArray($empresa);
        $this->assertEquals($empresa->getID(), $result->getID());
    }

    public function testfromArrayVoid()
    {
        $empresa = self::loadCompany();
        $result = $empresa->fromArray('teste');
        $this->assertTrue(!$result->exists());
    }

    public function testPublish()
    {
        $empresa = self::loadCompany();
        $result = $empresa->publish(app()->auth->provider);
        $res = array_key_exists('opcoes', $result);
        $this->assertFalse($res);
    }

    public function testValidate()
    {
        $empresa = self::loadCompany();
        $empresa->setID(2);
        $this->expectException('\MZ\Exception\ValidationException');
        $empresa->update();
        $search = $empresa->validate();
    }
}
