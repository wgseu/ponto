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

class RequisitoApiControllerTest extends \MZ\Framework\TestCase
{
    public function testFind()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_LISTACOMPRAS]);
        $requisito = RequisitoTest::create();
        $expected = [
            'status' => 'ok',
            'items' => [
                $requisito->publish(app()->auth->provider),
            ],
        ];
        $result = $this->get('/api/produtos_das_listas', ['search' => $requisito->getProdutoID()]);
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testAdd()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_LISTACOMPRAS]);
        $requisito = RequisitoTest::build();
        $this->assertEquals($requisito->toArray(), (new Requisito($requisito))->toArray());
        $this->assertEquals((new Requisito())->toArray(), (new Requisito(1))->toArray());
        $expected = [
            'status' => 'ok',
            'item' => $requisito->publish(app()->auth->provider),
        ];
        $result = $this->post('/api/produtos_das_listas', $requisito->toArray());
        $expected['item']['id'] = $result['item']['id'] ?? null;
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testUpdate()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_LISTACOMPRAS]);
        $requisito = RequisitoTest::create();
        $id = $requisito->getID();
        $result = $this->patch('/api/produtos_das_listas/' . $id, $requisito->toArray());
        $requisito->loadByID();
        $expected = [
            'status' => 'ok',
            'item' => $requisito->publish(app()->auth->provider),
        ];
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testDelete()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_LISTACOMPRAS]);
        $requisito = RequisitoTest::create();
        $id = $requisito->getID();
        $result = $this->delete('/api/produtos_das_listas/' . $id);
        $requisito->loadByID();
        $expected = [ 'status' => 'ok', ];
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
        $this->assertFalse($requisito->exists());
    }
}
