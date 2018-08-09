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
use MZ\Sale\Pedido;
use MZ\Sale\ProdutoPedido;
use MZ\Payment\Pagamento;

if (!is_login()) {
    json('Usuário não autenticado!');
}
try {
    $pedido = new Pedido();
    $tipo = Pedido::TIPO_MESA;
    if (isset($_GET['comanda'])) {
        $tipo = Pedido::TIPO_COMANDA;
    }
    $pedido->setID(isset($_GET['id']) ? $_GET['id'] : null);
    $pedido->setTipo($tipo);
    $pedido->setMesaID(isset($_GET['mesa']) ? $_GET['mesa'] : null);
    $pedido->setComandaID(isset($_GET['comanda']) ? $_GET['comanda'] : null);
    if ($pedido->exists()) {
        $pedido->loadByID();
        $pedido->checkAccess(logged_employee());
    } else {
        $pedido->checkAccess(logged_employee());
        $pedido->loadByLocal();
    }
    $agrupar = isset($_GET['agrupar']) ? boolval($_GET['agrupar']) : true;
    $group = ['p.id'];
    if ($agrupar) {
        $group = [
            'p.servicoid',
            'p.produtoid',
            'p.preco',
            'p.detalhes'
        ];
    }
    $itens = ProdutoPedido::rawFindAll(
        [
            'detalhado' => true,
            'pedidoid' => $pedido->getID(),
            'cancelado' => 'N'
        ],
        ['id' => 1],
        null,
        null,
        [],
        $group
    );
    $response = ['status' => 'ok'];
    $campos = [
        'id',
        'produtopedidoid',
        'tipo',
        'mesaid',
        'comandaid',
        'produtoid',
        'servicoid',
        'produtotipo',
        'produtodescricao',
        'produtoabreviacao',
        'produtoconteudo',
        'unidadesigla',
        'preco',
        'quantidade',
        'precovenda',
        'porcentagem',
        'detalhes',
        'descricao',
        'imagemurl',
        'produtodataatualizacao',
        'datahora',
    ];
    $items = [];
    $servicos = [];
    foreach ($itens as $_pedido) {
        $_pedido['tipo'] = $_pedido['pedidotipo'];
        $item = array_intersect_key($_pedido, array_flip($campos));
        if (is_null($item['servicoid'])) {
            $item['imagemurl'] = get_image_url($item['imagemurl'], 'produto', null);
            $items[] = $item;
        } elseif (is_greater($item['preco'], 0)) {
            $servicos[] = $item;
        }
    }
    $pagamentos = Pagamento::findAll(
        [
            'pedidoid' => $pedido->getID(),
            'cancelado' => 'N'
        ],
        ['id' => 1]
    );
    $response['estado'] = $pedido->getEstadoSimples();
    $response['pedidoid'] = $pedido->getID();
    if ($pedido->getClienteID()) {
        $cliente = $pedido->findClienteID();
        $response['cliente'] = $cliente->publish();
    }
    $total = $pedido->findTotal();
    $response['produtos'] = $total['produtos'];
    $response['comissao'] = $total['comissao'];
    $response['servicos'] = [
        'total' => $total['servicos'],
        'itens' => $servicos
    ];
    $response['descontos'] = $total['descontos'];
    $response['total'] = $total['total'];
    $_pagamentos = [];
    foreach ($pagamentos as $pagamento) {
        $item = $pagamento->publish();
        $_pagamentos[] = $item;
    }
    $response['pagamentos'] = $_pagamentos;
    $response['pago'] = $pedido->findPagamentoTotal();
    $response['pedidos'] = $items;
    json($response);
} catch (\Exception $e) {
    json($e->getMessage());
}
