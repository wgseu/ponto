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
use MZ\Sale\PedidoTest;
use MZ\Sale\Pedido;
use MZ\Session\MovimentacaoTest;
use MZ\Session\Movimentacao;

class ComandaApiControllerTest extends \MZ\Framework\TestCase
{
    public function testFind()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROCOMANDAS]);
        $comandas = Comanda::findAll();
        foreach($comandas as $comanda) {
            $comanda->delete();
        }
        $movimentacao = MovimentacaoTest::create();
        $comanda = ComandaTest::create('Comanda Teste nome');
        $pedido = PedidoTest::build();
        $pedido->setMesaID(null);
        $pedido->setPessoas(1);
        $pedido->setTipo(Pedido::TIPO_COMANDA);
        $pedido->setComandaID($comanda->getID());
        $pedido->insert();
        $pedidos = Pedido::findAll(['comandaid' => $comanda->getID()]);

        $expected = [
            'status' => 'ok',
            'items' => [
                $comanda->publish(app()->auth->provider),
            ],
        ];
        $result = $this->get('/api/comandas', ['search' => $comanda->getNome()]);
        $this->assertEquals($expected, \array_intersect_key($result, $expected));

        $result = $this->get('/api/comandas', ['search' => $comanda->getID()]);
        $this->assertEquals($expected, \array_intersect_key($result, $expected));

        $result = $this->get('/api/comandas', ['pedidos' => $pedidos]);
        PedidoTest::close($pedido);
        MovimentacaoTest::close($movimentacao);
    }

    public function testAdd()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROCOMANDAS]);
        $comanda = ComandaTest::build('Comanda teste nome2');
        $expected = [
            'status' => 'ok',
            'item' => $comanda->publish(app()->auth->provider),
        ];
        $result = $this->post('/api/comandas', $comanda->toArray());
        $expected['item']['id'] = $result['item']['id'] ?? null;
        $result['item']['numero'] = intval($result['item']['numero']);
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testUpdate()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROCOMANDAS]);
        $comanda = ComandaTest::create('Comanda teste nome3');
        $id = $comanda->getID();
        $result = $this->patch('/api/comandas/' . $id, $comanda->toArray());
        $comanda->loadByID();
        $expected = [
            'status' => 'ok',
            'item' => $comanda->publish(app()->auth->provider),
        ];
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
    }

    public function testDelete()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_CADASTROCOMANDAS]);
        $comanda = ComandaTest::create('Comanda teste nome4');
        $id = $comanda->getID();
        $result = $this->delete('/api/comandas/' . $id);
        $comanda->loadByID();
        $expected = [ 'status' => 'ok', ];
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
        $this->assertFalse($comanda->exists());
    }
}
