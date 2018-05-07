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
require_once(dirname(dirname(__DIR__)) . '/app.php');

use MZ\Sale\Pedido;
use MZ\Sale\ProdutoPedido;

if (!is_login()) {
    json('Usuário não autenticado!');
}
$pedido = new Pedido();
$tipo = Pedido::TIPO_MESA;
if (isset($_GET['comanda'])) {
    $tipo = Pedido::TIPO_COMANDA;
}
$pedido->setTipo($tipo);
$pedido->setMesaID(isset($_GET['mesa']) ? $_GET['mesa'] : null);
$pedido->setComandaID(isset($_GET['comanda']) ? $_GET['comanda'] : null);
$pedido->checkAccess(logged_employee());
$pedido->loadByLocal();
$pedidos = ProdutoPedido::rawFindAll(
    [
        'detalhado' => true,
        'pedidoid' => $pedido->getID(),
        '!produtoid' => null,
        'cancelamento' => 'N',
        'cancelado' => 'N',
        '!status' => Pedido::ESTADO_FINALIZADO
    ],
    [],
    [],
    [
        'produtoid',
        'preco',
        'detalhes'
    ]
);
$response = ['status' => 'ok'];
$campos = [
    'id',
    'produtopedidoid',
    'tipo',
    'mesaid',
    'comandaid',
    'produtoid',
    'produtotipo',
    'produtodescricao',
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
foreach ($pedidos as $_pedido) {
    $item = array_intersect_key($_pedido, array_flip($campos));
    $item['imagemurl'] = get_image_url($item['imagemurl'], 'produto', null);
    $items[] = $item;
}
$response['total'] = $pedido->findTotal(true);
$response['pedidos'] = $items;
json($response);
