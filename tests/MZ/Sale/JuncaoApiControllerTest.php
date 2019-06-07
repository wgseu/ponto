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
namespace MZ\Sale;

use MZ\System\Permissao;
use MZ\Account\AuthenticationTest;
use MZ\Session\MovimentacaoTest;

class JuncaoApiControllerTest extends \MZ\Framework\TestCase
{
    public function testFind()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_MUDARDEMESA]);
        $movimentacao = MovimentacaoTest::create();
        $juncao = JuncaoTest::create();
        $expected = [
            'status' => 'ok',
            'items' => [
                $juncao->publish(app()->auth->provider),
            ],
            'pages' => 1
        ];
        $result = $this->get('/api/juncoes', ['search' => $juncao->getMesaID()]);
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testAdd()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_MUDARDEMESA]);
        $movimentacao = MovimentacaoTest::create();
        $juncao = JuncaoTest::build();
        $this->assertEquals($juncao->toArray(), (new Juncao($juncao))->toArray());
        $this->assertEquals((new Juncao())->toArray(), (new Juncao(1))->toArray());
        $expected = [
            'status' => 'ok',
            'item' => $juncao->publish(app()->auth->provider),
        ];
        $result = $this->post('/api/juncoes', $juncao->toArray());
        $expected['item']['id'] = $result['item']['id'] ?? null;
        $expected['item']['datamovimento'] = $result['item']['datamovimento'] ?? null;
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testUpdate()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_MUDARDEMESA]);
        $movimentacao = MovimentacaoTest::create();
        $juncao = JuncaoTest::create();
        $id = $juncao->getID();
        $result = $this->patch('/api/juncoes/' . $id, $juncao->toArray());
        $juncao->loadByID();
        $expected = [
            'status' => 'ok',
            'item' => $juncao->publish(app()->auth->provider),
        ];
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testDelete()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_MUDARDEMESA]);
        $movimentacao = MovimentacaoTest::create();
        $juncao = JuncaoTest::create();
        $id = $juncao->getID();
        $result = $this->delete('/api/juncoes/' . $id);
        $juncao->loadByID();
        $expected = [ 'status' => 'ok', ];
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
        $this->assertFalse($juncao->exists());
    }
}
