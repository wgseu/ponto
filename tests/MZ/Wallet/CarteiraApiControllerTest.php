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

class CarteiraApiControllerTest extends \MZ\Framework\TestCase
{
    public function testFind()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROCARTEIRAS]);
        $carteira = CarteiraTest::create();
        $expected = [
            'status' => 'ok',
            'items' => [
                $carteira->publish(app()->auth->provider),
            ],
        ];
        $result = $this->get('/api/carteiras', ['search' => $carteira->getDescricao()]);
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testAdd()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROCARTEIRAS]);
        $carteira = CarteiraTest::build();
        $expected = [
            'status' => 'ok',
            'item' => $carteira->publish(app()->auth->provider),
        ];
        $result = $this->post('/api/carteiras', $carteira->toArray());
        $expected['item']['id'] = $result['item']['id'] ?? null;
        $result['item']['limite'] = intval($result['item']['limite']) ?? null;
        $result['item']['transacao'] = intval($result['item']['transacao']) ?? null;
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testUpdate()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROCARTEIRAS]);
        $carteira = CarteiraTest::create();
        $id = $carteira->getID();
        $result = $this->patch('/api/carteiras/' . $id, $carteira->toArray());
        $carteira->loadByID();
        $expected = [
            'status' => 'ok',
            'item' => $carteira->publish(app()->auth->provider),
        ];
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testDelete()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROCARTEIRAS]);
        $carteira = CarteiraTest::create();
        $id = $carteira->getID();
        $result = $this->delete('/api/carteiras/' . $id);
        $carteira->loadByID();
        $expected = [ 'status' => 'ok', ];
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
        $this->assertFalse($carteira->exists());
    }
}
