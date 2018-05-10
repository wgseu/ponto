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
require_once(dirname(__DIR__) . '/app.php');

use MZ\Sale\Pedido;
use MZ\System\Permissao;
use MZ\Util\Filter;

need_permission(Permissao::NOME_PAGAMENTO, is_output('json'));

$limite = isset($_GET['limite']) ? intval($_GET['limite']) : 10;
if ($limite > 100 || $limite < 1) {
    $limite = 10;
}
$condition = Filter::query($_GET);
unset($condition['ordem']);

$estado = isset($_GET['estado']) ? trim($_GET['estado']) : null;
if ($estado == 'Cancelado') {
    $condition['cancelado'] = 'Y';
    unset($condition['estado']);
} elseif ($estado == 'Valido') {
    $condition['cancelado'] = 'N';
    unset($condition['estado']);
} elseif ($estado != '') {
    $condition['cancelado'] = 'N';
}

$pedido = new Pedido($condition);
$order = Filter::order(isset($_GET['ordem']) ? $_GET['ordem'] : '');
$count = Pedido::count($condition);
list($pagesize, $offset, $pagination) = pagestring($count, $limite);
$pedidos = Pedido::findAll($condition, $order, $pagesize, $offset);

if (is_output('json')) {
    $items = [];
    foreach ($pedidos as $_pedido) {
        $items[] = $_pedido->publish();
    }
    json(['status' => 'ok', 'items' => $items]);
}

$_tipo_names = Pedido::getTipoOptions();
$_estado_names = ['Valido' => 'Válido'] + Pedido::getEstadoOptions() + ['Cancelado' => 'Cancelado'];

$_pedido_icon = [
    'Mesa' => 0,
    'Comanda' => 16,
    'Avulso' => 32,
    'Entrega' => 48,
];
$_funcionario = $pedido->findFuncionarioID();
$_cliente = $pedido->findClienteID();
$app->getResponse('html')->output('gerenciar_pedido_index');
