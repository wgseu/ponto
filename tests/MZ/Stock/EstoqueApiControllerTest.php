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

namespace MZ\Stock;

use MZ\System\Permissao;
use MZ\Account\AuthenticationTest;

class EstoqueApiControllerTest extends \MZ\Framework\TestCase
{
    public function testFind()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_ESTOQUE]);
        $estoque = EstoqueTest::create();
        $expected = [
            'status' => 'ok'
        ];
        $result = $this->get('/api/estoques', ['search' => $estoque->getProdutoID()]);
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testAdd()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_ESTOQUE]);
        $estoque = EstoqueTest::build();
        $this->assertEquals($estoque->toArray(), (new Estoque($estoque))->toArray());
        $this->assertEquals((new Estoque())->toArray(), (new Estoque(1))->toArray());
        $expected = [
            'status' => 'ok',
            'item' => $estoque->publish(app()->auth->provider),
        ];
        $result = $this->post('/api/estoques', $estoque->toArray());
        $expected['item']['id'] = $result['item']['id'] ?? null;
        $result['item']['quantidade'] = floatval($result['item']['quantidade'] ?? null);
        $result['item']['precocompra'] = floatval($result['item']['precocompra'] ?? null);
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testUpdate()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_ESTOQUE]);
        $estoque = EstoqueTest::create();
        $id = $estoque->getID();
        $result = $this->patch('/api/estoques/' . $id, $estoque->toArray());
        $estoque->loadByID();
        $expected = [
            'status' => 'ok',
            'item' => $estoque->publish(app()->auth->provider),
        ];
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testDelete()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_ESTOQUE]);
        $estoque = EstoqueTest::create();
        $id = $estoque->getID();
        $result = $this->delete('/api/estoques/' . $id);
        $estoque->loadByID();
        $expected = [ 'status' => 'ok', ];
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
        $this->assertFalse($estoque->exists());
    }

    public function testCancelar()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_ESTOQUE]);
        $estoque = EstoqueTest::create();
        $result = $this->get('/api/estoques/cancelar', ['id' => $estoque->getID()]);
        $estoque->loadByID();
        $expected = [
            'status' => 'ok',
            'item' => $estoque->publish(app()->auth->provider),
        ];
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
        $this->assertTrue($estoque->isCancelado());

    }
}
