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
use MZ\Environment\Mesa;
use MZ\Sale\Comanda;
use MZ\System\Permissao;
use MZ\Sale\Pedido;

if (!is_login()) {
    json('Usuário não autenticado!');
}
if (!logged_employee()->has(Permissao::NOME_PEDIDOMESA)) {
    json('Você não tem permissão para acessar mesas');
}
$order = [
    'funcionario' => logged_employee()->getID()
];
/* verifica se deve ordenar pelo número da mesa */
if (isset($_GET['ordenar']) && $_GET['ordenar'] == 'mesa') {
    unset($order['funcionario']);
}
$condition = [
    'ativa' => 'Y',
    'pedidos' => true
];
$mesas = Mesa::rawFindAll($condition, $order);
$condition['mesas'] = null;
$comandas = Comanda::rawFindAll($condition, $order);
$grupos = [];
foreach ($comandas as $comanda) {
    if (!isset($grupos[$comanda['juntaid']])) {
        $grupos[$comanda['juntaid']] = [];
    }
    $grupos[$comanda['juntaid']][] = $comanda;
}
$items = [];
$pedido = new Pedido();
$pedido->setCancelado('N');
foreach ($mesas as $item) {
    $pedido->setID($item['pedidoid']);
    $pedido->setMesaID($item['id']);
    $pedido->setEstado($item['estado']);
    $item['estado'] = $pedido->getEstadoSimples();
    if ($pedido->exists() || isset($grupos[$item['id']])) {
        $total = $pedido->findTotal();
        if (!$pedido->exists()) {
            $item['estado'] = 'ocupado';
            $item['comandas'] = count($grupos[$item['id']]);
        }
        $item['produtos'] = $total['produtos'];
        $item['comissao'] = $total['comissao'];
        $item['servicos'] = ['total' => $total['servicos']];
        $item['descontos'] = $total['descontos'];
        $item['pago'] = $pedido->findPagamentoTotal();
    }
    if (is_null($item['pedidoid'])) {
        unset($item['pedidoid']);
    }
    if (is_null($item['cliente'])) {
        unset($item['cliente']);
    }
    if (is_null($item['juntaid'])) {
        unset($item['juntaid']);
    }
    if (is_null($item['juntanome'])) {
        unset($item['juntanome']);
    }
    $items[] = $item;
}
json('mesas', $items);
