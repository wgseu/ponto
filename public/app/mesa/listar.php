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

use MZ\Environment\Mesa;
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
$items = [];
foreach ($mesas as $item) {
    if ($item['estado'] == Pedido::ESTADO_ATIVO) {
        $item['estado'] = 'ocupado';
    } elseif ($item['estado'] == Pedido::ESTADO_AGENDADO) {
        $item['estado'] = 'reservado';
    } elseif (is_null($item['estado'])) {
        $item['estado'] = 'livre';
    } else {
        $item['estado'] = strtolower($item['estado']);
    }
    $items[] = $item;
}
json('mesas', $items);
