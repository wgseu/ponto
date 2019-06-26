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
namespace MZ\Sale;

use MZ\System\Permissao;
use MZ\Account\AuthenticationTest;
use MZ\Session\MovimentacaoTest;
use MZ\Sale\PedidoTest;
use MZ\Session\Movimentacao;

class FormacaoApiControllerTest extends \MZ\Framework\TestCase
{
    public function testFind()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_PAGAMENTO]);
        $movimentacao = MovimentacaoTest::create();
        $formacao = FormacaoTest::create();

        $expected = [
            'status' => 'ok',
            'items' => [
                $formacao->publish(app()->auth->provider),
            ],
        ];
        $result = $this->get('/api/formacoes', ['search' => $formacao->getPacoteID()]);
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
        $item = $formacao->findItemID();
        $pedido = $item->findPedidoID();
        PedidoTest::close($pedido);
        MovimentacaoTest::close($movimentacao);
    }

    public function testAdd()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_PAGAMENTO]);
        $movimentacao = MovimentacaoTest::create();
        $formacao = FormacaoTest::build();
        $this->assertEquals($formacao->toArray(), (new Formacao($formacao))->toArray());
        $this->assertEquals((new Formacao())->toArray(), (new Formacao(1))->toArray());
        $expected = [
            'status' => 'ok',
            'item' => $formacao->publish(app()->auth->provider),
        ];
        $result = $this->post('/api/formacoes', $formacao->toArray());
        $expected['item']['id'] = $result['item']['id'] ?? null;
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
        $item = $formacao->findItemID();
        $pedido = $item->findPedidoID();
        PedidoTest::close($pedido);
        MovimentacaoTest::close($movimentacao);
    }

    public function testUpdate()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_PAGAMENTO]);
        $movimentacao = MovimentacaoTest::create();
        $formacao = FormacaoTest::create();
        $id = $formacao->getID();
        $result = $this->patch('/api/formacoes/' . $id, $formacao->toArray());
        $formacao->loadByID();
        $expected = [
            'status' => 'ok',
            'item' => $formacao->publish(app()->auth->provider),
        ];
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
        $item = $formacao->findItemID();
        $pedido = $item->findPedidoID();
        PedidoTest::close($pedido);
        MovimentacaoTest::close($movimentacao);
    }

    public function testDelete()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_PAGAMENTO]);
        $movimentacao = MovimentacaoTest::create();
        $formacao = FormacaoTest::create();
        $item = $formacao->findItemID();
        $pedido = $item->findPedidoID();
        $id = $formacao->getID();
        $result = $this->delete('/api/formacoes/' . $id);
        $formacao->loadByID();
        $expected = [ 'status' => 'ok', ];
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
        $this->assertFalse($formacao->exists());
        PedidoTest::close($pedido);
        MovimentacaoTest::close($movimentacao);
    }
}
