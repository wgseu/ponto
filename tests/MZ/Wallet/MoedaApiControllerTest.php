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
namespace MZ\Wallet;

use MZ\System\Permissao;
use MZ\Account\AuthenticationTest;

class MoedaApiControllerTest extends \MZ\Framework\TestCase
{
    public function testFind()
    {
        AuthenticationTest::authUser();
        $moeda = MoedaTest::create();
        $expected = [
            'status' => 'ok',
            'items' => [
                $moeda->publish(app()->auth->provider),
            ],
        ];
        $result = $this->get('/api/moedas', ['search' => $moeda->getNome()]);
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testAdd()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROMOEDAS]);
        $moeda = MoedaTest::build();
        $moeda->setDivisao(strval($moeda->getDivisao()));
        $moeda->setConversao(floatval($moeda->getConversao()));
        $moeda->setAtiva('N');
        $this->assertFalse($moeda->isAtiva());
        $moeda->setAtiva('Y');
        $this->assertTrue($moeda->isAtiva());
        $this->assertEquals($moeda->toArray(), (new Moeda($moeda))->toArray());
        $this->assertEquals((new Moeda())->toArray(), (new Moeda(1))->toArray());
        $expected = [
            'status' => 'ok',
            'item' => $moeda->publish(app()->auth->provider),
        ];
        $result = $this->post('/api/moedas', $moeda->toArray());
        $expected['item']['id'] = $result['item']['id'] ?? null;
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testAddInvalid()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROMOEDAS]);
        $expected = [ 'status' => 'error', ];
        $result = $this->post('/api/moedas', ['ativa' => 'S']);
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testUpdate()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROMOEDAS]);
        $moeda = MoedaTest::create();
        $id = $moeda->getID();
        $result = $this->patch('/api/moedas/' . $id, $moeda->toArray());
        $moeda->loadByID();
        $expected = [
            'status' => 'ok',
            'item' => $moeda->publish(app()->auth->provider),
        ];
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testUpdateCurrentInvalid()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROMOEDAS]);
        $moeda = app()->system->currency;
        $moeda->setConversao(2);
        $expected = [ 'status' => 'error', ];
        $result = $this->patch('/api/moedas/' . $moeda->getID(), $moeda->toArray());
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testDelete()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROMOEDAS]);
        $moeda = MoedaTest::create();
        $id = $moeda->getID();
        $result = $this->delete('/api/moedas/' . $id);
        $moeda->loadByID();
        $expected = [ 'status' => 'ok', ];
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
        $this->assertFalse($moeda->exists());
    }
}
