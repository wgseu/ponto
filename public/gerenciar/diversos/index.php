<?php
require_once(dirname(__DIR__) . '/app.php');
need_owner();

$data_inicio = strtotime(date('Y-m').' -1 month');
$data_fim = strtotime(date('Y-m').' 0 month');
$data_fim = strtotime('last day of this month 23:59:59', $data_fim);
$faturamentos = ZPagamento::getTodosFaturamentos(-1, 0);
$top_clientes = ZCliente::getTodosCompradores(-6, 0, 0, 5);
$sessao = ZSessao::getAbertaOuUltima();
$pessoas = ZPedido::getTotalPessoas($sessao->getID());
$stats = ZPedido::getTicketMedio($sessao->getID());
$permanencia = $stats['permanencia'];
$ticket_medio = $stats['total'];
$receitas = ZPagamento::getReceitas($sessao->getID());
$vendas = ZPedido::getTotal($sessao->getID());
$faturamento = [];
$faturamento['atual'] = ZPagamento::getFaturamento(null, date('Y-m').'-01', date('Y-m-d'));
$prev_month = strtotime(date('Y-m').' -1 month');
$start_prev = date('Y-m', $prev_month).'-01';
$end_prev = date('Y-m', $prev_month).'-'.relative_day(-1);
$faturamento['anterior'] = ZPagamento::getFaturamento(null, $start_prev, $end_prev);
$faturamento['base'] = ZPagamento::getFaturamento(null, -1, -1);
$clientes = [];
$clientes['total'] = ZCliente::getCount();
$clientes['hoje'] = ZCliente::getCount(
    null, // busca
    null, // tipo
    null, // genero
    date('Y-m-d'), // mes_inicio
    date('Y-m-d') // mes_fim
);
$start_curr = strtotime(date('Y-m').' 0 month');
$despesas = [];
$despesas['pagas'] = ZPagamento::getDespesas(null, $start_curr, $data_fim);
$conta_info = ZConta::getTotalAbertas(null, null, -1, null, date('Y-m-d', $data_fim));
$despesas['apagar'] = $conta_info['despesas'] - $conta_info['pago'];
$pagamentos = ZPagamento::getPagamentos($sessao->getID());
$categorias = ZProdutoPedido::getTodosPorCategoria($sessao->getID(), 0, 6);
include template('gerenciar_diversos_index');
