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

class PedidoApiControllerTest extends \MZ\Framework\TestCase
{
    public function testAdd()
    {
        AuthenticationTest::authProvider([Permissao::NOME_SISTEMA, Permissao::NOME_PEDIDOMESA]);
        $pedido = PedidoTest::build();
        $movimentacao = MovimentacaoTest::create();
        $expected = [
            'status' => 'ok',
        ];
        $data = $pedido->toArray();
        $result = $this->post('/api/pedidos', $data);
        $this->assertEquals($expected, \array_intersect_key($result, $expected));
        $pedido->setID($result['item']['id']);
        $pedido->loadByID();
        $pedido->setEstado(Pedido::ESTADO_FINALIZADO);
        $pedido->update();
        MovimentacaoTest::close($movimentacao);
    }
}
