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

class PagamentoApiControllerTest extends \MZ\Framework\TestCase
{
    public function testFind()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_PAGAMENTO]);
        
        $pagamento = PagamentoTest::create();
        $expected = [
            'status' => 'ok',
            'items' => [
                $pagamento->publish(app()->auth->provider),
            ],
            'pages' => 1
        ];
        $result = $this->get('/api/pagamentos', ['id' => $pagamento->getID()]);
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testAdd()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_PAGAMENTO]);
        $pagamento = PagamentoTest::build();
        $pagamento->setEstado(Pagamento::ESTADO_PAGO);
        $this->assertTrue($pagamento->isPago());
        $pagamento->setEstado(Pagamento::ESTADO_CANCELADO);
        $this->assertTrue($pagamento->isCancelado());
        $this->assertEquals($pagamento->toArray(), (new Pagamento($pagamento))->toArray());
        $this->assertEquals((new Pagamento())->toArray(), (new Pagamento(1))->toArray());
        $expected = [
            'status' => 'ok',
            'item' => $pagamento->publish(app()->auth->provider),
        ];
        $result = $this->post('/api/pagamentos', $pagamento->toArray());
        $expected['item']['id'] = $result['item']['id'] ?? null;
        $result['item']['valor'] = floatval($result['item']['valor'] ?? null);
        $result['item']['numeroparcela'] = intval($result['item']['numeroparcela'] ?? null);
        $result['item']['parcelas'] = intval($result['item']['parcelas'] ?? null);
        $result['item']['lancado'] = floatval($result['item']['lancado'] ?? null);
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testUpdate()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_PAGAMENTO]);
        $pagamento = PagamentoTest::create();
        $id = $pagamento->getID();
        $result = $this->patch('/api/pagamentos/' . $id, $pagamento->toArray());
        $pagamento->loadByID();
        $expected = [
            'status' => 'ok',
            'item' => $pagamento->publish(app()->auth->provider),
        ];
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testDelete()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_PAGAMENTO]);
        $pagamento = PagamentoTest::create();
        $id = $pagamento->getID();
        $result = $this->delete('/api/pagamentos/' . $id);
        $pagamento->loadByID();
        $expected = [ 'status' => 'ok', ];
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
        $this->assertFalse($pagamento->exists());
    }
}
