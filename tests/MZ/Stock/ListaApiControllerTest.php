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

class ListaApiControllerTest extends \MZ\Framework\TestCase
{
    public function testFind()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_LISTACOMPRAS]);
        $lista = ListaTest::create();
        $expected = [
            'status' => 'ok',
            'items' => [
                $lista->publish(app()->auth->provider),
            ],
        ];
        $result = $this->get('/api/listas_de_compras', ['search' => $lista->getDescricao()]);
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testAdd()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_LISTACOMPRAS]);
        $lista = ListaTest::build();
        $this->assertEquals($lista->toArray(), (new Lista($lista))->toArray());
        $this->assertEquals((new Lista())->toArray(), (new Lista(1))->toArray());
        $expected = [
            'status' => 'ok',
            'item' => $lista->publish(app()->auth->provider),
        ];
        $result = $this->post('/api/listas_de_compras', $lista->toArray());
        $expected['item']['id'] = $result['item']['id'] ?? null;
        $expected['item']['datacadastro'] = $result['item']['datacadastro'] ?? null;
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testUpdate()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_LISTACOMPRAS]);
        $lista = ListaTest::create();
        $id = $lista->getID();
        $result = $this->patch('/api/listas_de_compras/' . $id, $lista->toArray());
        $lista->loadByID();
        $expected = [
            'status' => 'ok',
            'item' => $lista->publish(app()->auth->provider),
        ];
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testDelete()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_LISTACOMPRAS]);
        $lista = ListaTest::create();
        $id = $lista->getID();
        $result = $this->delete('/api/listas_de_compras/' . $id);
        $lista->loadByID();
        $expected = [ 'status' => 'ok', ];
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
        $this->assertFalse($lista->exists());
    }
}
