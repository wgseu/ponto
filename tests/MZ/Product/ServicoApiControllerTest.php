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

namespace MZ\Product;

use MZ\System\Permissao;
use MZ\Account\AuthenticationTest;

class ServicoApiControllerTest extends \MZ\Framework\TestCase
{
    public function testFind()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROSERVICOS]);
        $servico = ServicoTest::create();
        $expected = [
            'status' => 'ok',
            'items' => [
                $servico->publish(app()->auth->provider),
            ],
        ];
        $result = $this->get('/api/servicos', ['search' => $servico->getDescricao()]);
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testAdd()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROSERVICOS]);
        $servico = ServicoTest::build();
        $servico->setObrigatorio('Y');
        $this->assertTrue($servico->isObrigatorio());
        $servico->setIndividual('Y');
        $this->assertTrue($servico->isIndividual());
        $servico->setAtivo('Y');
        $this->assertTrue($servico->isAtivo());
        $this->assertEquals($servico->toArray(), (new Servico($servico))->toArray());
        $this->assertEquals((new Servico())->toArray(), (new Servico(1))->toArray());
        $expected = [
            'status' => 'ok',
            'item' => $servico->publish(app()->auth->provider),
        ];
        $result = $this->post('/api/servicos', $servico->toArray());
        $expected['item']['id'] = $result['item']['id'] ?? null;
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testUpdate()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROSERVICOS]);
        $servico = ServicoTest::create();
        $id = $servico->getID();
        $result = $this->patch('/api/servicos/' . $id, $servico->toArray());
        $servico->loadByID();
        $expected = [
            'status' => 'ok',
            'item' => $servico->publish(app()->auth->provider),
        ];
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testDelete()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROSERVICOS]);
        $servico = ServicoTest::create();
        $id = $servico->getID();
        $result = $this->delete('/api/servicos/' . $id);
        $servico->loadByID();
        $expected = [ 'status' => 'ok', ];
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
        $this->assertFalse($servico->exists());
    }
}
