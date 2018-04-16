<?php
require_once(dirname(__DIR__) . '/app.php');
need_owner();

use MZ\Payment\Pagamento;
use MZ\Account\Cliente;
use MZ\Sale\Pedido;
use MZ\Session\Sessao;
use MZ\Account\Conta;
use MZ\Sale\ProdutoPedido;

$data_inicio = strtotime(date('Y-m').' -1 month');
$data_fim = strtotime(date('Y-m').' 0 month');
$data_fim = strtotime('last day of this month 23:59:59', $data_fim);
$faturamentos = Pagamento::getTodosFaturamentos(-1, 0);
$top_clientes = Cliente::getTodosCompradores(-6, 0, 0, 5);
$sessao = Sessao::findAbertaOuUltima();
$pessoas = Pedido::getTotalPessoas($sessao->getID());
$stats = Pedido::getTicketMedio($sessao->getID());
$permanencia = $stats['permanencia'];
$ticket_medio = $stats['total'];
$receitas = Pagamento::getReceitas($sessao->getID());
$vendas = Pedido::getTotal($sessao->getID());
$faturamento = [];
$faturamento['atual'] = Pagamento::getFaturamento(null, date('Y-m').'-01', date('Y-m-d'));
$prev_month = strtotime(date('Y-m').' -1 month');
$start_prev = date('Y-m', $prev_month).'-01';
$end_prev = date('Y-m', $prev_month).'-'.relative_day(-1);
$faturamento['anterior'] = Pagamento::getFaturamento(null, $start_prev, $end_prev);
$faturamento['base'] = Pagamento::getFaturamento(null, -1, -1);
$clientes = [];
$clientes['total'] = Cliente::count();
$clientes['hoje'] = Cliente::count([
    'data_inicio' => Helper::date(),
    'data_fim' => Helper::date()
]);
$start_curr = strtotime(date('Y-m').' 0 month');
$despesas = [];
$despesas['pagas'] = Pagamento::getDespesas(null, $start_curr, $data_fim);
$conta_info = Conta::getTotalAbertas(null, null, -1, null, date('Y-m-d', $data_fim));
$despesas['apagar'] = $conta_info['despesas'] - $conta_info['pago'];
$pagamentos = Pagamento::getPagamentos($sessao->getID());
$categorias = ProdutoPedido::getTodosPorCategoria($sessao->getID(), 0, 6);
$app->getResponse('html')->output('gerenciar_diversos_index');
