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

use MZ\Wallet\Carteira;

need_permission(PermissaoNome::PAGAMENTO);

$data_inicio = date_create_from_format('d/m/Y', $_GET['inicio']);
$data_inicio = $data_inicio===false?null:$data_inicio->getTimestamp();
$data_fim = date_create_from_format('d/m/Y', $_GET['fim']);
$data_fim = $data_fim===false?null:$data_fim->getTimestamp();
$count = ZPagamento::getCount(
    $_GET['query'],
    $_GET['formapagtoid'],
    $_GET['cartaoid'],
    $_GET['funcionarioid'],
    $_GET['carteiraid'],
    $_GET['estado'],
    $data_inicio,
    $data_fim
);
list($pagesize, $offset, $pagestring) = pagestring($count, 10);
$pagamentos = ZPagamento::getTodos(
    $_GET['query'],
    $_GET['formapagtoid'],
    $_GET['cartaoid'],
    $_GET['funcionarioid'],
    $_GET['carteiraid'],
    $_GET['estado'],
    $data_inicio,
    $data_fim,
    $offset,
    $pagesize
);

$_tipo_names = [
    'Mesa' => 'Mesa',
    'Comanda' => 'Comanda',
    'Avulso' => 'Balcão',
    'Entrega' => 'Entrega',
];
$_estado_names = [
    'Valido' => 'Válido',
    'Ativo' => 'Ativo',
    'Espera' => 'Em espera',
    'Cancelado' => 'Cancelado',
];

$_pagamento_icon = [
    'Dinheiro' => 0,
    'Cartao' => 16,
    'Cheque' => 32,
    'Conta' => 48,
    'Credito' => 64,
    'Transferencia' => 80,
];

$formas_de_pagamento = ZFormaPagto::getTodos();
$_forma_names = [];
foreach ($formas_de_pagamento as $forma) {
    $_forma_names[$forma->getID()] = $forma->getDescricao();
}
$cartoes = ZCartao::getTodos();
$_cartao_names = [];
foreach ($cartoes as $cartao) {
    $_cartao_names[$cartao->getID()] = $cartao->getDescricao();
}
$carteiras = Carteira::findAll();
$_carteira_names = [];
foreach ($carteiras as $carteira) {
    $_carteira_names[$carteira->getID()] = $carteira->getDescricao();
}
$_funcionario = ZFuncionario::getPeloID($_GET['funcionarioid']);
include template('gerenciar_pagamento_index');
