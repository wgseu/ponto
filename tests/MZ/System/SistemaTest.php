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
use MZ\Exception\ValidationException;
use MZ\Account\AuthenticationTest;

class SistemaTest extends \MZ\Framework\TestCase
{
    /**
     * Build a valid sistema
     * @param string $versao_db Sistema versão do banco de dados
     * @return Sistema
     */
    public static function build($versao_db = null)
    {
        $last = Sistema::find([], ['id' => -1]);
        $id = $last->getID() + 1;
        $servidor = ServidorTest::create();
        $sistema = new Sistema();
        $sistema->setServidorID($servidor->getID());
        $sistema->setVersaoDB($versao_db ?: "Sistema {$id}");
        return $sistema;
    }

    /**
     * Create a sistema on database
     * @param string $versao_db Sistema versão do banco de dados
     * @return Sistema
     */
    public static function create($versao_db = null)
    {
        $sistema = self::build($versao_db);
        $sistema->insert();
        return $sistema;
    }

    public function testLoadAll()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_ALTERARCONFIGURACOES]);
        $sistema = Sistema::find([]);
        $path =  dirname(dirname(dirname(__DIR__)));
        $sistema->initialize($path);
        $sistema->loadAll();
        $this->assertTrue($sistema->exists());
        $moeda = $sistema->getCurrency();
        $this->assertTrue($moeda->exists());
    }

    public function testInitialize()
    {
        $sistema = Sistema::find([]);
        $path =  dirname(dirname(dirname(__DIR__)));
        $sistema->initialize($path);
        $this->assertTrue($sistema->exists());
    }

    public function testFindServidorID()
    {
        $sistema = Sistema::find([]);
        $servidor = $sistema->findServidorID();
        $this->assertEquals($servidor->getID(), $sistema->getServidorID());
    }

    public function testValidate()
    {
        //teste id diferente de 1
        $sistema = Sistema::find([]);
        $sistema->setID('2');
        try {
            $sistema->update();
            $this->fail('O id do sistema não foi informado');
        } catch (ValidationException $e) {
            $this->assertEquals(['id'], array_keys($e->getErrors()));
        }
        //testa servidor nulo
        $sistema = Sistema::find([]);
        $sistema->setServidorID(null);
        try {
            $sistema->update();
            $this->fail('ServidorID não pode ser nulo');
        } catch (ValidationException $e) {
            $this->assertEquals(['servidorid'], array_keys($e->getErrors()));
        }
        //testa versãoDB nulo
        $sistema = Sistema::find([]);
        $sistema->setVersaoDB(null);
        try {
            $sistema->update();
            $this->fail('VersãoDB não pode ser nulo');
        } catch (ValidationException $e) {
            $this->assertEquals(['versaodb'], array_keys($e->getErrors()));
        }
    }

    public function testFind()
    {
        $condition = ['id' => '1'];
        $sistema = Sistema::find($condition);
        $this->assertTrue($sistema->exists());
        list($found_sistema) = Sistema::findAll($condition, [], 1);
        $this->assertEquals($sistema, $found_sistema);
        $this->assertEquals(1, Sistema::count($condition));
    }

    public function testAdd()
    {
        $sistema = Sistema::find(['id' => '1']);
        $this->expectException('\Exception');
        $sistema->insert();
    }

    public function testUpdate()
    {
        $sistema = Sistema::find(['id' => '1']);
        $sistema->update();
        $this->assertTrue($sistema->exists());
    }

    public function testDelete()
    {
        $sistema = Sistema::find(['id' => '1']);
        $this->expectException('\Exception');
        $sistema->delete();
    }
}
