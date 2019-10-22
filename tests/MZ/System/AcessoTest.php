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

use MZ\Provider\FuncaoTest;
use MZ\System\PermissaoTest;
use MZ\Account\AuthenticationTest;
use MZ\Exception\ValidationException;

class AcessoTest extends \MZ\Framework\TestCase
{
    /**
     * Build a valid acesso
     * @param MZ\Provider\Funcao $funcao Função que irá possuir a permissão
     * @param string $permissao_nome nome da permissão que será construída
     * @return Acesso
     */
    public static function build($funcao, $permissao_nome)
    {
        $permissao = Permissao::findByNome($permissao_nome);
        $acesso = new Acesso();
        $acesso->setPermissaoID($permissao->getID());
        $acesso->setFuncaoID($funcao->getID());
        return $acesso;
    }

    /**
     * @return Acesso[]
     */
    public static function create($funcao, $permissions)
    {
        $result = [];
        foreach ($permissions as $name) {
            $acesso = self::build($funcao, $name);
            $acesso->insert();
            $result[] = $acesso;
        }
        return $result;
    }

    public function testFind()
    {
        list($acesso) = self::create(FuncaoTest::create([]), [Permissao::NOME_ALTERARCONFIGURACOES]);
        $condition = ['permissaoid' => $acesso->getPermissaoID(), 'funcaoid' => $acesso->getFuncaoID()];
        $found_acesso = Acesso::find($condition);
        $this->assertEquals($acesso, $found_acesso);
        list($found_acesso) = Acesso::findAll($condition, [], 1);
        $this->assertEquals($acesso, $found_acesso);
        $this->assertEquals(1, Acesso::count($condition));
    }

    public function testAdd()
    {
        $acesso = self::build(FuncaoTest::create([]), Permissao::NOME_ALTERARCONFIGURACOES);
        $acesso->insert();
        $this->assertTrue($acesso->exists());
    }

    public function testValidate()
    {
        // teste funcaoID nulo
        $provider = AuthenticationTest::authOwner();
        $funcao = $provider->findFuncaoID();
        $acesso = self::build($funcao, Permissao::NOME_ALTERARCONFIGURACOES);
        $acesso->setFuncaoID(null);
        try {
            $acesso->insert();
            $this->fail('FuncaoID não pode ser nulo');
        } catch (ValidationException $e) {
            $this->assertEquals(['funcaoid'], array_keys($e->getErrors()));
        }
        // teste funcaoID nulo
        $provider = AuthenticationTest::authOwner();
        $funcao = $provider->findFuncaoID();
        $acesso = self::build($funcao, Permissao::NOME_ALTERARCONFIGURACOES);
        $acesso->setPermissaoID(null);
        try {
            $acesso->insert();
            $this->fail('PermissaoID não pode ser nulo');
        } catch (ValidationException $e) {
            $this->assertEquals(['permissaoid'], array_keys($e->getErrors()));
        }
    }

    public function testTranslate()
    {
        $provider = AuthenticationTest::authOwner();
        $funcao = $provider->findFuncaoID();
        $acessoInit = self::build($funcao, Permissao::NOME_ALTERARCONFIGURACOES);
        $acessoInit->insert();
        try {
            $acessoInit->insert();
            $this->fail('funcaoID e permissaoID devem ser únicos');
        } catch (ValidationException $e) {
            $this->assertEquals(['funcaoid', 'permissaoid'], array_keys($e->getErrors()));
        }
    }

    public function testFindByFuncaoIDPermissaoID()
    {
        $provider = AuthenticationTest::authOwner();
        $funcao = $provider->findFuncaoID();
        $acesso = self::build($funcao, Permissao::NOME_ALTERARCONFIGURACOES);
        $acesso->insert();
        $found_acesso = $acesso::findByFuncaoIDPermissaoID($acesso->getFuncaoID(), $acesso->getPermissaoID());
        $this->assertEquals($found_acesso, $acesso);
    }

    public function testFindFuncaoID()
    {
        $provider = AuthenticationTest::authOwner();
        $funcao = $provider->findFuncaoID();
        $acesso = self::build($funcao, Permissao::NOME_ALTERARCONFIGURACOES);
        $acesso->insert();
        $found_funcao = $acesso->findFuncaoID();
        $this->assertEquals($found_funcao, $funcao);
    }

    public function testFindPermissaoID()
    {
        $provider = AuthenticationTest::authOwner();
        $funcao = $provider->findFuncaoID();
        $acesso = self::build($funcao, Permissao::NOME_ALTERARCONFIGURACOES);
        $acesso->insert();
        $found_permissao = $acesso->findPermissaoID();
        $this->assertEquals($acesso->getPermissaoID(), $found_permissao->getID());
    }

    public function testUpdate()
    {
        list($acesso) = self::create(FuncaoTest::create([]), [Permissao::NOME_ALTERARCONFIGURACOES]);
        $acesso->update();
        $this->assertTrue($acesso->exists());
    }

    public function testDelete()
    {
        list($acesso) = self::create(FuncaoTest::create([]), [Permissao::NOME_ALTERARCONFIGURACOES]);
        $acesso->delete();
        $acesso->loadByID();
        $this->assertFalse($acesso->exists());
    }
}
