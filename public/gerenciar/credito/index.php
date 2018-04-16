<?php
/*
    Copyright 2016 da MZ Software - MZ Desenvolvimento de Sistemas LTDA
    Este arquivo é parte do programa GrandChef - Sistema para Gerenciamento de Churrascarias, Bares e Restaurantes.
    O GrandChef é um software proprietário; você não pode redistribuí-lo e/ou modificá-lo.
    DISPOSIÇÕES GERAIS
    O cliente não deverá remover qualquer identificação do produto, avisos de direitos autorais,
    ou outros avisos ou restrições de propriedade do GrandChef.

    O cliente não deverá causar ou permitir a engenharia reversa, desmontagem,
    ou descompilação do GrandChef.

    PROPRIEDADE DOS DIREITOS AUTORAIS DO PROGRAMA

    GrandChef é a especialidade do desenvolvedor e seus
    licenciadores e é protegido por direitos autorais, segredos comerciais e outros direitos
    de leis de propriedade.

    O Cliente adquire apenas o direito de usar o software e não adquire qualquer outros
    direitos, expressos ou implícitos no GrandChef diferentes dos especificados nesta Licença.
*/
require_once(dirname(__DIR__) . '/app.php');

use MZ\Account\Credito;
use MZ\System\Permissao;
use MZ\Util\Filter;

need_permission(Permissao::NOME_CADASTRARCREDITOS, is_output('json'));

$limite = isset($_GET['limite']) ? intval($_GET['limite']) : 10;
if ($limite > 100 || $limite < 1) {
    $limite = 10;
}
$condition = Filter::query($_GET);
unset($condition['ordem']);
$credito = new Credito($condition);
$order = Filter::order(isset($_GET['ordem']) ? $_GET['ordem'] : '');
$count = Credito::count($condition);
list($pagesize, $offset, $pagestring) = pagestring($count, $limite);
$creditos = Credito::findAll($condition, $order, $pagesize, $offset);

if (is_output('json')) {
    $items = [];
    foreach ($creditos as $_credito) {
        $items[] = $_credito->publish();
    }
    json(['status' => 'ok', 'items' => $items]);
}

$_cliente = $credito->findClienteID();
$estados = [
    'Y' => 'Cancelados',
    'N' => 'Válidos',
];
$app->getResponse('html')->output('gerenciar_credito_index');
