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

namespace MZ\Environment;

use MZ\System\Permissao;
use MZ\Account\AuthenticationTest;

class PatrimonioApiControllerTest extends \MZ\Framework\TestCase
{
    public function testFind()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROPATRIMONIO]);
        $patrimonio = PatrimonioTest::create();
        $expected = [
            'status' => 'ok',
            'items' => [
                $patrimonio->publish(app()->auth->provider),
            ],
        ];
        $result = $this->get('/api/patrimonios', ['search' => $patrimonio->getDescricao()]);
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testAdd()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROPATRIMONIO]);
        $patrimonio = PatrimonioTest::build();
        $patrimonio->setAtivo('Y');
        $this->assertTrue($patrimonio->isAtivo());
        $this->assertEquals($patrimonio->toArray(), (new Patrimonio($patrimonio))->toArray());
        $this->assertEquals((new Patrimonio())->toArray(), (new Patrimonio(1))->toArray());
        $expected = [
            'status' => 'ok',
            'item' => $patrimonio->publish(app()->auth->provider),
        ];
        $result = $this->post('/api/patrimonios', $patrimonio->toArray());
        $expected['item']['id'] = $result['item']['id'] ?? null;
        $result['item']['numero'] = intval($result['item']['numero'] ?? null);
        $result['item']['quantidade'] = intval($result['item']['quantidade'] ?? null);
        $result['item']['altura'] = intval($result['item']['altura'] ?? null);
        $result['item']['largura'] = intval($result['item']['largura'] ?? null);
        $result['item']['comprimento'] = floatval($result['item']['comprimento'] ?? null);
        $result['item']['custo'] = floatval($result['item']['custo'] ?? null);
        $result['item']['valor'] = floatval($result['item']['valor'] ?? null);
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testUpdate()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROPATRIMONIO]);
        $patrimonio = PatrimonioTest::create();
        $id = $patrimonio->getID();
        $result = $this->patch('/api/patrimonios/' . $id, $patrimonio->toArray());
        $patrimonio->loadByID();
        $expected = [
            'status' => 'ok',
            'item' => $patrimonio->publish(app()->auth->provider),
        ];
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testDelete()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROPATRIMONIO]);
        $patrimonio = PatrimonioTest::create();
        $id = $patrimonio->getID();
        $result = $this->delete('/api/patrimonios/' . $id);
        $patrimonio->loadByID();
        $expected = [ 'status' => 'ok', ];
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
        $this->assertFalse($patrimonio->exists());
    }
}
