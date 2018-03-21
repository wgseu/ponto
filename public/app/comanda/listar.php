<?php
/*
	Copyright 2014 da MZ Software - MZ Desenvolvimento de Sistemas LTDA
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
require_once(dirname(dirname(__DIR__)) . '/app.php');

if (!is_login()) {
    json('Usuário não autenticado!');
}
if (!have_permission(PermissaoNome::PEDIDOCOMANDA)) {
    json('Você não tem permissão para acessar comandas');
}
/* verifica se deve ordenar pelo número da comanda ou pelo funcionário */
$funcionario_id = null;
if (!isset($_GET['ordenar']) || $_GET['ordenar'] != 'comanda') {
    $funcionario_id = $login_funcionario_id;
}
$comandas = ZPedido::getTodasComandas($funcionario_id);
$items = [];
$obs_name = is_boolean_config('Vendas', 'Comanda.Observacao');
foreach ($comandas as $_comanda) {
    switch ($_comanda['estado']) {
        case PedidoEstado::ATIVO:
            $_comanda['estado'] = 'ocupado';
            break;
        case PedidoEstado::AGENDADO:
            $_comanda['estado'] = 'reservado';
            break;
        default:
            if (is_null($_comanda['estado'])) {
                $_comanda['estado'] = 'livre';
            } else {
                $_comanda['estado'] = strtolower($_comanda['estado']);
            }
    }
    if ($obs_name && trim($_comanda['observacao']) != '') {
        $_comanda['nome'] = $_comanda['observacao'];
    }
    $items[] = $_comanda;
}
json('comandas', $items);
