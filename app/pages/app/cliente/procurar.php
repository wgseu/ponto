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
use MZ\Account\Cliente;
use MZ\Util\Filter;

need_manager(true);

$limit = intval(isset($_GET['limite'])?$_GET['limite']:5);
$primeiro = isset($_GET['primeiro']) ? $_GET['primeiro']: false;
$busca = isset($_GET['busca']) ? $_GET['busca'] : null;
if ($primeiro || check_fone($busca, true)) {
    $limit = 1;
} elseif ($limit < 1) {
    $limit = 5;
} elseif ($limit > 20) {
    $limit = 20;
}
$order = Filter::order(isset($_GET['ordem']) ? $_GET['ordem']: '');
$condition = Filter::query($_GET);
unset($condition['limite']);
unset($condition['primeiro']);
unset($condition['ordem']);
if (isset($_GET['busca'])) {
    $condition['search'] = $_GET['busca'];
}
$clientes = Cliente::findAll($condition, $order, $limit);
$items = [];
$domask = intval(isset($_GET['formatar']) ? $_GET['formatar'] : 0) != 0;
foreach ($clientes as $cliente) {
    $item = $cliente->publish();
    if (!$domask) {
        $item['fone1'] = $cliente->getFone(1);
        $item['fone2'] = $cliente->getFone(2);
        $item['cpf'] = $cliente->getCPF();
    }
    $item['imagemurl'] = $item['imagem'];
    $items[] = $item;
}
json('clientes', $items);
