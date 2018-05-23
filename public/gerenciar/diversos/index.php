<?php
require_once(dirname(__DIR__) . '/app.php');
need_owner();

use MZ\Payment\Pagamento;
use MZ\Account\Cliente;
use MZ\Sale\Pedido;
use MZ\Session\Sessao;
use MZ\Account\Conta;
use MZ\Sale\ProdutoPedido;
use MZ\Database\DB;

$data_inicio = strtotime('first day of last month 0:00');
$data_fim = strtotime('-1 sec tomorrow');
$faturamentos = Pagamento::rawFindAllTotal(
    [
        'apartir_datahora' => DB::date('first day of last month'),
        '!pedidoid' => null
    ],
    ['dia' => true]
);
$apartir_compra = DB::date('first day of -6 month');
$top_clientes = Cliente::rawFindAll(
    [
        'comprador' => true,
        'apartir_compra' => $apartir_compra
    ],
    [],
    5
);
$sessao = Sessao::findLastAberta();
$pessoas = Pedido::getTotalPessoas($sessao->getID());
$stats = Pedido::getTicketMedio($sessao->getID());
$permanencia = $stats['permanencia'];
$ticket_medio = $stats['total'];
$receitas = Pagamento::getReceitas(['sessaoid' => $sessao->getID()]);
$vendas = Pedido::fetchTotal($sessao->getID());
$faturamento = [];
$faturamento['atual'] = Pagamento::getFaturamento(
    ['apartir_datahora' => DB::date('first day of this month')]
);
$faturamento['anterior'] = Pagamento::getFaturamento([
    'apartir_datahora' => DB::date('first day of last month'),
    'ate_datahora' => DB::now('-1 month')
]);
$faturamento['base'] = Pagamento::getFaturamento([
    'apartir_datahora' => DB::date('first day of last month'),
    'ate_datahora' => DB::now('-1 sec today first day of this month')
]);
$clientes = [];
$clientes['total'] = Cliente::count();
$clientes['hoje'] = Cliente::count(['apartir_cadastro' => DB::date()]);
$despesas = [];
$despesas['pagas'] = Pagamento::getDespesas(
    ['apartir_datahora' => DB::date('first day of this month')]
);
$conta_info = Conta::getTotalAbertas(null, null, -1, null, date('Y-m-d', $data_fim));
$despesas['apagar'] = $conta_info['despesas'] - $conta_info['pago'];
$pagamentos = Pagamento::rawFindAllTotal(
    [
        'sessaoid' => $sessao->getID(),
        '!pedidoid' => null
    ],
    ['forma_tipo' => true]
);
$condition = [
    'categorizado' => true,
    'cancelado' => 'N',
    'cancelamento' => 'N'
];
if ($sessao->exists()) {
    $condition['sessaoid'] = $sessao->getID();
}
$categorias = ProdutoPedido::rawFindAll(
    $condition,
    [],
    6
);
$app->getResponse('html')->output('gerenciar_diversos_index');
