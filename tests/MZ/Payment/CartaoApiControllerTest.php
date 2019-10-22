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

namespace MZ\Payment;

use MZ\System\Permissao;
use MZ\Account\AuthenticationTest;

class CartaoApiControllerTest extends \MZ\Framework\TestCase
{
    public function testFind()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROCARTOES]);
        $cartao = CartaoTest::create();
        $expected = [
            'status' => 'ok',
            'items' => [
                $cartao->publish(app()->auth->provider),
            ],
        ];
        $result = $this->get('/api/cartoes', ['search' => $cartao->getBandeira()]);
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    // public function testFetchAssociation()
    // {
    //     AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROCARTOES]);
    //     $cartao = CartaoTest::create();
    //     $name = 'ifood';
    //     $expected = [
    //         'status' => 'ok',
    //         'items' => [
    //             $cartao->publish(app()->auth->provider),
    //         ],
    //     ];
    //     $result = $this->get('/api/cartoes/association/', ['name' => $name]);
    //     $this->assertEquals($expected, \array_intersect_key($result, $expected));
    // }

    // public function testAssociate()
    // {
    //     AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROCARTOES]);
    //     $cartao = CartaoTest::create();
    //     $name = 'ifood';
    //     $expected = [
    //         'status' => 'ok',
    //         'items' => [
    //             $cartao->publish(app()->auth->provider),
    //         ],
    //     ];
    //     $result = $this->patch('/api/cartoes/associate/', ['name' => $name]);
    //     $this->assertEquals($expected, \array_intersect_key($result, $expected));
    // }

    public function testAdd()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROCARTOES]);
        $cartao = CartaoTest::build();
        $cartao->setAtivo('N');
        $this->assertFalse($cartao->isAtivo());
        $this->assertEquals($cartao->toArray(), (new Cartao($cartao))->toArray());
        $this->assertEquals((new Cartao())->toArray(), (new Cartao(1))->toArray());
        $expected = [
            'status' => 'ok',
            'item' => $cartao->publish(app()->auth->provider),
        ];
        $result = $this->post('/api/cartoes', $cartao->toArray());
        $expected['item']['id'] = $result['item']['id'] ?? null;
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }


    public function testUpdate()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROCARTOES]);
        $cartao = CartaoTest::create();
        $id = $cartao->getID();
        $result = $this->patch('/api/cartoes/' . $id, $cartao->toArray());
        $cartao->loadByID();
        $expected = [
            'status' => 'ok',
            'item' => $cartao->publish(app()->auth->provider),
        ];
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testDelete()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROCARTOES]);
        $cartao = CartaoTest::create();
        $id = $cartao->getID();
        $result = $this->delete('/api/cartoes/' . $id);
        $cartao->loadByID();
        $expected = [ 'status' => 'ok', ];
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
        $this->assertFalse($cartao->exists());
    }
}
