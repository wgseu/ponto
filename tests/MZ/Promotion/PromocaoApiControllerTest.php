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
namespace MZ\Promotion;

use MZ\System\Permissao;
use MZ\Account\AuthenticationTest;

class PromocaoApiControllerTest extends \MZ\Framework\TestCase
{
    public function testFind()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROPRODUTOS]);
        $promocao = PromocaoTest::create();
        $expected = [
            'status' => 'ok',
            'items' => [
                $promocao->publish(app()->auth->provider),
            ],
        ];
        $result = $this->get('/api/promocoes', ['search' => $promocao->getProdutoID()]);
        $this->assertEquals($expected, \array_intersect_key($result, $expected));

        $result = $this->get('/api/promocoes', ['ate_inicio' => $promocao->getInicio()]);
        $this->assertEquals($expected, \array_intersect_key($result, $expected));

        $result = $this->get('/api/promocoes', ['apartir_fim' => $promocao->getFim()]);
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testAdd()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROPRODUTOS]);
        $promocao = PromocaoTest::build();
        $promocao->setParcial('Y');
        $this->assertTrue($promocao->isParcial());
        $promocao->setProibir('Y');
        $this->assertTrue($promocao->isProibir());
        $promocao->setAgendamento('Y');
        $this->assertTrue($promocao->isAgendamento());
        $this->assertEquals($promocao->toArray(), (new Promocao($promocao))->toArray());
        $this->assertEquals((new Promocao())->toArray(), (new Promocao(1))->toArray());
        $expected = [
            'status' => 'ok',
            'item' => $promocao->publish(app()->auth->provider),
        ];
        $result = $this->post('/api/promocoes', $promocao->toArray());
        $expected['item']['id'] = $result['item']['id'] ?? null;

        $result['item']['inicio'] = intval($result['item']['inicio'] ?? null);
        $result['item']['fim'] = intval($result['item']['fim'] ?? null);
        $result['item']['valor'] = intval($result['item']['valor'] ?? null);
        $result['item']['pontos'] = intval($result['item']['pontos'] ?? null);
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testUpdate()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROPRODUTOS]);
        $promocao = PromocaoTest::create();
        $id = $promocao->getID();
        $result = $this->patch('/api/promocoes/' . $id, $promocao->toArray());
        $promocao->loadByID();
        $expected = [
            'status' => 'ok',
            'item' => $promocao->publish(app()->auth->provider),
        ];
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testDelete()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROPRODUTOS]);
        $promocao = PromocaoTest::create();
        $id = $promocao->getID();
        $result = $this->delete('/api/promocoes/' . $id);
        $promocao->loadByID();
        $expected = [ 'status' => 'ok', ];
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
        $this->assertFalse($promocao->exists());
    }
}
