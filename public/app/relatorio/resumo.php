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

use MZ\Database\Helper;

if (!is_login()) {
    json('Usuário não autenticado!');
}
if (!logged_employee()->has(Permissao::NOME_RELATORIOCAIXA)) {
    json('Você não tem permissão para acessar o resumo de valores');
}
$sessao = Sessao::findLastAberta();
$data_inicio = !$sessao->exists() ? Helper::date('today') : null;
$response = ['status' => 'ok'];
$response['vendas'] = Pedido::getTotal($sessao->getID(), $data_inicio);
$response['receitas'] = Pagamento::getReceitas(
    ['sessaoid' => $sessao->getID(), 'apartir_datahora' => $data_inicio]
);
$response['despesas'] = Pagamento::getDespesas(
    ['sessaoid' => $sessao->getID(), 'apartir_datahora' => $data_inicio]
);
$response['faturamento']['atual'] = Pagamento::getFaturamento(
    ['apartir_datahora' => Helper::date('first day of this month')]
);
$response['faturamento']['base'] = Pagamento::getFaturamento([
    'apartir_datahora' => Helper::date('first day of last month'),
    'ate_datahora' => Helper::now('-1 month')
]);
$response['faturamento']['estimado'] = round(($response['faturamento']['atual'] / date('j')) * date('t'), 4);
$response['faturamento']['anterior'] = Pagamento::getFaturamento([
    'apartir_datahora' => Helper::date('first day of last month'),
    'ate_datahora' => Helper::now('-1 sec today first day of this month')
]);
$response['faturamento']['restante'] = $response['faturamento']['anterior'] - $response['faturamento']['atual'];
if ($response['faturamento']['anterior'] < 0.01) {
    $response['faturamento']['alcancado'] = 100;
    $response['faturamento']['metrica'] = 100;
} else {
    $response['faturamento']['alcancado'] = round(
        ($response['faturamento']['atual'] / $response['faturamento']['anterior']) * 100,
        2
    );
    $response['faturamento']['metrica'] = round(
        ($response['faturamento']['base'] / $response['faturamento']['anterior']) * 100,
        2
    );
}
$response['faturamento']['pagamentos'] = Pagamento::rawFindAllTotal(
    [
        'apartir_datahora' => Helper::date('first day of this month'),
        '!pedidoid' => null
    ],
    ['forma_tipo' => true]
);
$mes = abs(intval(isset($_GET['mes']) ? $_GET['mes'] : null));
$meses = [];
for ($i = $mes; $i < $mes + 4; $i++) {
    $data = Helper::date("first day of -$i month");
    $meses[] = [
        'mes' => human_date($data, true),
        'total' => Pagamento::getFaturamento([
            'apartir_datahora' => $data,
            'ate_datahora' => Helper::now("last day of -$i month 23:59:59")
        ])
    ];
}
$response['faturamento']['mensal'] = $meses;
json($response);
