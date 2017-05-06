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
require_once(dirname(dirname(dirname(__FILE__))) . '/app.php');

if(!is_login())
	json('Usuário não autenticado!');
if(!have_permission(PermissaoNome::RELATORIOCAIXA))
	json('Você não tem permissão para acessar o resumo de valores');
$sessao = ZSessao::getAbertaOuUltima();
$data_inicio = strtotime($sessao->getDataInicio())?:strtotime("midnight", time());
$response = array('status' => 'ok');
$response['vendas'] = ZPedido::getTotal($sessao->getID(), $data_inicio);
$response['receitas'] = ZPagamento::getReceitas($sessao->getID(), $data_inicio);
$response['despesas'] = ZPagamento::getDespesas($sessao->getID(), $data_inicio);

$response['faturamento']['atual'] = ZPagamento::getFaturamento(null, 0, null);
$response['faturamento']['base'] = ZPagamento::getFaturamento(null, -1, -1, null, relative_day(-1));
$response['faturamento']['estimado'] = round(($response['faturamento']['atual'] / date('j')) * date('t'), 4);
$response['faturamento']['anterior'] = ZPagamento::getFaturamento(null, -1, -1);
$response['faturamento']['restante'] = $response['faturamento']['anterior'] - $response['faturamento']['atual'];
if($response['faturamento']['anterior'] < 0.01) {
	$response['faturamento']['alcancado'] = 100;
	$response['faturamento']['metrica'] = 100;
} else {
	$response['faturamento']['alcancado'] = round(($response['faturamento']['atual'] / $response['faturamento']['anterior']) * 100, 2);
	$response['faturamento']['metrica'] = round(($response['faturamento']['base'] / $response['faturamento']['anterior']) * 100, 2);
}
$response['faturamento']['pagamentos'] = ZPagamento::getPagamentos(null, 0, null);
$mes = abs(intval($_GET['mes']));
$meses = array();
for ($i = $mes; $i < $mes + 4; $i++) {
	$data = strtotime(date('Y-m').' -'.$i.' month');
	$meses[] = array(
			'mes' => human_date(date('Y-m-d', $data), true),
			'total' => ZPagamento::getFaturamento(null, -$i, -$i)
		);
}
$response['faturamento']['mensal'] = $meses;
json($response);