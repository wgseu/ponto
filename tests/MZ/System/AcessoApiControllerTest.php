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

use MZ\System\Permissao;
use MZ\Account\AuthenticationTest;

class AcessoApiControllerTest extends \MZ\Framework\TestCase
{
    public function testFind()
    {
        $provider = AuthenticationTest::authOwner();
        $funcao = $provider->findFuncaoID();
        $acesso = AcessoTest::build($funcao, Permissao::NOME_ALTERARCONFIGURACOES);
        $acesso->insert();
        $expected = [
            'status' => 'ok',
            'items' => [
                $acesso->publish(app()->auth->provider),
            ],
        ];
        $result = $this->get('/api/acessos', ['id' => $acesso->getID()]);
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testAdd()
    {
        AuthenticationTest::authOwner();
        $provider = app()->auth->provider;
        $funcao = $provider->findFuncaoID();
        $acesso = AcessoTest::build($funcao, Permissao::NOME_ALTERARCONFIGURACOES);
        $this->assertEquals($acesso->toArray(), (new Acesso($acesso))->toArray());
        $this->assertEquals((new Acesso())->toArray(), (new Acesso(1))->toArray());
        $expected = [
            'status' => 'ok',
            'item' => $acesso->publish(app()->auth->provider),
        ];
        $result = $this->post('/api/acessos', $acesso->toArray());
        $expected['item']['id'] = $result['item']['id'] ?? null;
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testUpdate()
    {
        AuthenticationTest::authOwner();
        $provider = app()->auth->provider;
        $funcao = $provider->findFuncaoID();
        $acesso = AcessoTest::build($funcao, Permissao::NOME_ALTERARCONFIGURACOES);
        $acesso->insert();
        $id = $acesso->getID();
        $result = $this->patch('/api/acessos/' . $id, $acesso->toArray());
        $acesso->loadByID();
        $expected = [
            'status' => 'ok',
            'item' => $acesso->publish(app()->auth->provider),
        ];
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testDelete()
    {
        $provider = AuthenticationTest::authOwner();
        $funcao = $provider->findFuncaoID();
        $acesso = AcessoTest::build($funcao, Permissao::NOME_ALTERARCONFIGURACOES);
        $acesso->insert();
        $id = $acesso->getID();
        $result = $this->delete('/api/acessos/' . $id);
        $acesso->loadByID();
        $expected = [ 'status' => 'ok', ];
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
        $this->assertFalse($acesso->exists());
    }
}
