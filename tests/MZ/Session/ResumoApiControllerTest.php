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

namespace MZ\Session;

use MZ\System\Permissao;
use MZ\Account\AuthenticationTest;
use MZ\Session\MovimentacaoTest;

class ResumoApiControllerTest extends \MZ\Framework\TestCase
{
    public function testFind()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CONFERIRCAIXA]);
        $resumo = ResumoTest::create();
        $expected = [
            'status' => 'ok',
            'items' => [
                $resumo->publish(app()->auth->provider),
            ],
        ];
        $result = $this->get('/api/resumos', ['search' => $resumo->getMovimentacaoID()]);
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
        $movimentacao = $resumo->findMovimentacaoID();
        MovimentacaoTest::close($movimentacao);
    }

    public function testAdd()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CONFERIRCAIXA]);
        $resumo = ResumoTest::build();
        $this->assertEquals($resumo->toArray(), (new Resumo($resumo))->toArray());
        $this->assertEquals((new Resumo())->toArray(), (new Resumo(1))->toArray());
        $expected = [
            'status' => 'ok',
            'item' => $resumo->publish(app()->auth->provider),
        ];
        $result = $this->post('/api/resumos', $resumo->toArray());
        $expected['item']['id'] = $result['item']['id'] ?? null;
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
        $movimentacao = $resumo->findMovimentacaoID();
        MovimentacaoTest::close($movimentacao);
    }

    public function testUpdate()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CONFERIRCAIXA]);
        $resumo = ResumoTest::create();
        $id = $resumo->getID();
        $result = $this->patch('/api/resumos/' . $id, $resumo->toArray());
        $resumo->loadByID();
        $expected = [
            'status' => 'ok',
            'item' => $resumo->publish(app()->auth->provider),
        ];
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
        $movimentacao = $resumo->findMovimentacaoID();
        MovimentacaoTest::close($movimentacao);
    }

    public function testDelete()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CONFERIRCAIXA]);
        $resumo = ResumoTest::create();
        $movimentacao = $resumo->findMovimentacaoID();
        $id = $resumo->getID();
        $result = $this->delete('/api/resumos/' . $id);
        $resumo->loadByID();
        $expected = [ 'status' => 'ok', ];
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
        $this->assertFalse($resumo->exists());

        MovimentacaoTest::close($movimentacao);
    }
}
