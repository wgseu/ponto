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

namespace MZ\Account;

use MZ\System\Permissao;
use MZ\Account\AuthenticationTest;

class ContaApiControllerTest extends \MZ\Framework\TestCase
{
    public function testFind()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROCONTAS]);
        $conta = ContaTest::create();
        $expected = [
            'status' => 'ok',
            'items' => [
                $conta->publish(app()->auth->provider),
            ],
        ];
        $result = $this->get('/api/contas', ['search' => $conta->getDescricao()]);
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testAdd()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROCONTAS]);
        $conta = ContaTest::build();

        $conta->setAutomatico('Y');
        $this->assertTrue($conta->isAutomatico());

        $this->assertEquals($conta->toArray(), (new Conta($conta))->toArray());
        $this->assertEquals((new Conta())->toArray(), (new Conta(1))->toArray());

        $expected = [
            'status' => 'ok',
            'item' => $conta->publish(app()->auth->provider),
        ];
        $result = $this->post('/api/contas', $conta->toArray());
        //cópia do que vem da resposta de campos que foram auto preenchidos
        $expected['item']['id'] = $result['item']['id'] ?? null;
        //"isset"
        $expected['item']['funcionarioid'] = $result['item']['funcionarioid'] ?? null;
        $expected['item']['consolidado'] = $result['item']['consolidado'] ?? null;
        $expected['item']['datacalculo'] = $result['item']['datacalculo'] ?? null;
        $expected['item']['dataemissao'] = $result['item']['dataemissao'] ?? null;
        $expected['item']['vencimento'] = $result['item']['vencimento'] ?? null;
        //convertendo a resposta de string para o valor necessário
        $valor = floatval($result['item']['valor'] ?? null);
        $result['valor'] = $valor;
        $result['item']['consolidado'] = floatval($result['item']['consolidado'] ?? null);
        $result['item']['numeroparcela'] = intval($result['item']['numeroparcela'] ?? null);
        $result['item']['parcelas'] = intval($result['item']['parcelas'] ?? null);
        $result['item']['frequencia'] = intval($result['item']['frequencia'] ?? null);
        $result['item']['acrescimo'] = floatval($result['item']['acrescimo'] ?? null);
        $result['item']['multa'] = floatval($result['item']['multa'] ?? null);
        $juros = doubleval($result['item']['juros'] ?? null);
        $result['item']['juros'] = $juros * 100;

        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testModify()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROCONTAS]);
        $conta = ContaTest::create();
        $id = $conta->getID();
        $result = $this->patch('/api/contas/' . $id, $conta->toArray());
        $conta->loadByID();
        $expected = [
            'status' => 'ok',
            'item' => $conta->publish(app()->auth->provider),
        ];
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testDelete()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROCONTAS]);
        $conta = ContaTest::create();
        $id = $conta->getID();
        $result = $this->delete('/api/contas/' . $id);
        $conta->loadByID();
        $expected = [ 'status' => 'ok', ];
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
        $this->assertFalse($conta->exists());
    }
}
