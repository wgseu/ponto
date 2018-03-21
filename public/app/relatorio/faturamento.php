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
if (!have_permission(PermissaoNome::RELATORIOCAIXA)) {
    json('Você não tem permissão para acessar o faturamento da empresa');
}
$response = ['status' => 'ok'];
$mes = abs(intval($_GET['mes']));
$meses = [];
for ($i = $mes; $i < $mes + 4; $i++) {
    $data = strtotime(date('Y-m').' -'.$i.' month');
    $meses[] = [
            'mes' => human_date(date('Y-m-d', $data), true),
            'total' => ZPagamento::getFaturamento(null, -$i, -$i)
        ];
}
$response['mensal'] = $meses;
json($response);
