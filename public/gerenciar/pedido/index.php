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
require_once(dirname(dirname(__FILE__)) . '/app.php');

need_permission(PermissaoNome::PAGAMENTO);

$estado = trim($_GET['estado']);
$cancelado = null;
if ($estado == 'Cancelado') {
    $cancelado = 'Y';
    $estado = null;
} elseif ($estado == 'Valido') {
    $cancelado = 'N';
    $estado = null;
} elseif ($estado != '') {
    $cancelado = 'N';
}
$data_inicio = date_create_from_format('d/m/Y', $_GET['inicio']);
$data_inicio = $data_inicio===false?null:$data_inicio->getTimestamp();
$data_fim = date_create_from_format('d/m/Y', $_GET['fim']);
$data_fim = $data_fim===false?null:$data_fim->getTimestamp();

$count = ZPedido::getCount(
    $_GET['query'],
    $_GET['clienteid'],
    $_GET['funcionarioid'],
    $_GET['tipo'],
    $estado,
    $cancelado,
    $data_inicio,
    $data_fim,
    null,
    null,
    null
);
list($pagesize, $offset, $pagestring) = pagestring($count, 10);
$pedidos = ZPedido::getTodos(
    $_GET['query'],
    $_GET['clienteid'],
    $_GET['funcionarioid'],
    $_GET['tipo'],
    $estado,
    $cancelado,
    $data_inicio,
    $data_fim,
    null,
    null,
    null,
    $offset,
    $pagesize
);

$_tipo_names = array(
    'Mesa' => 'Mesa',
    'Comanda' => 'Comanda',
    'Avulso' => 'Balcão',
    'Entrega' => 'Entrega',
);

$_estado_names = array(
    'Valido' => 'Válido',
    'Finalizado' => 'Finalizado',
    'Ativo' => 'Ativo',
    'Agendado' => 'Agendado',
    'Entrega' => 'Entrega',
    'Fechado' => 'Fechado',
    'Cancelado' => 'Cancelado',
);

$_pedido_icon = array(
    'Mesa' => 0,
    'Comanda' => 16,
    'Avulso' => 32,
    'Entrega' => 48,
);

$_cliente = ZCliente::getPeloID($_GET['clienteid']);
$_funcionario = ZFuncionario::getPeloID($_GET['funcionarioid']);
include template('gerenciar_pedido_index');
