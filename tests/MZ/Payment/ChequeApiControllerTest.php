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

class ChequeApiControllerTest extends \MZ\Framework\TestCase
{
    public function testFind()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_PAGAMENTO]);
        $cheque = ChequeTest::create();
        $expected = [
            'status' => 'ok',
            'items' => [
                $cheque->publish(app()->auth->provider),
            ],
        ];
        $result = $this->get('/api/cheques', ['search' => $cheque->getNumero()]);
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testAdd()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_PAGAMENTO]);
        $cheque = ChequeTest::build();
        $this->assertEquals($cheque->toArray(), (new Cheque($cheque))->toArray());
        $this->assertEquals((new Cheque())->toArray(), (new Cheque(1))->toArray());
        $expected = [
            'status' => 'ok',
            'item' => $cheque->publish(app()->auth->provider),
        ];
        $result = $this->post('/api/cheques', $cheque->toArray());
        $expected['item']['id'] = $result['item']['id'] ?? null;
        $result['item']['numero'] = intval($result['item']['numero'] ?? null);
        $result['item']['valor'] = floatval($result['item']['valor'] ?? null);
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testUpdate()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_PAGAMENTO]);
        $cheque = ChequeTest::create();
        $id = $cheque->getID();
        $result = $this->patch('/api/cheques/' . $id, $cheque->toArray());
        $cheque->loadByID();
        $expected = [
            'status' => 'ok',
            'item' => $cheque->publish(app()->auth->provider),
        ];
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testDelete()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_PAGAMENTO]);
        $cheque = ChequeTest::create();
        $id = $cheque->getID();
        $result = $this->delete('/api/cheques/' . $id);
        $cheque->loadByID();
        $expected = [ 'status' => 'ok', ];
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
        $this->assertFalse($cheque->exists());
    }

    public function testRecall()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_PAGAMENTO]);
        $cheque = ChequeTest::create();
        $id = $cheque->getID();
        $result = $this->get('/api/cheques/recall', ['id' => $id]);
        $expected = [ 'status' => 'ok', ];
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
        $cheque->loadByID();
        $this->assertTrue($cheque->isRecolhido());
    }
}
