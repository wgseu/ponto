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
use MZ\Sale\PedidoTest;
use MZ\Sale\Pedido;
use MZ\Session\MovimentacaoTest;
use MZ\Session\Movimentacao;

class MesaApiControllerTest extends \MZ\Framework\TestCase
{
    public function testFind()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROMESAS]);
        $movimentacao = MovimentacaoTest::create();
        $mesa = MesaTest::create();
        $pedido = PedidoTest::build();
        $pedido->setMesaID($mesa->getID());
        $pedido->insert();
        $pedidos = Pedido::findAll(['mesaid' => $mesa->getID()]);

        $expected = [
            'status' => 'ok',
            'items' => [
                $mesa->publish(app()->auth->provider),
            ],
        ];
        $result = $this->get('/api/mesas', ['search' => $mesa->getNome()]);
        $this->assertEquals($expected, \array_intersect_key($result, $expected));

        $result = $this->get('/api/mesas', ['search' => $mesa->getID()]);
        $this->assertEquals($expected, \array_intersect_key($result, $expected));

        $result = $this->get('/api/mesas', ['pedidos' => $pedidos]);
        // $this->assertEquals($expected, \array_intersect_key($result, $expected));
        PedidoTest::close($pedido);
        MovimentacaoTest::close($movimentacao);
    }

    public function testAdd()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROMESAS]);
        $mesa = MesaTest::build();
        $this->assertEquals($mesa->toArray(), (new Mesa($mesa))->toArray());
        $this->assertEquals((new Mesa())->toArray(), (new Mesa(1))->toArray());
        $expected = [
            'status' => 'ok',
            'item' => $mesa->publish(app()->auth->provider),
        ];
        $result = $this->post('/api/mesas', $mesa->toArray());
        $result['item']['numero'] = intval($result['item']['numero'] ?? null);
        $expected['item']['id'] = $result['item']['id'] ?? null;
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testUpdate()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROMESAS]);
        $mesa = MesaTest::create();
        $id = $mesa->getID();
        $result = $this->patch('/api/mesas/' . $id, $mesa->toArray());
        $mesa->loadByID();
        $expected = [
            'status' => 'ok',
            'item' => $mesa->publish(app()->auth->provider),
        ];
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testDelete()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROMESAS]);
        $mesa = MesaTest::create();
        $id = $mesa->getID();
        $result = $this->delete('/api/mesas/' . $id);
        $mesa->loadByID();
        $expected = [ 'status' => 'ok', ];
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
        $this->assertFalse($mesa->exists());
    }
}
