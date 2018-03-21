<?php
require_once(dirname(__DIR__) . '/app.php');
need_owner(true);
$action = $_GET['action'];
if ($action == 'faturamento') {
    $start = strtotime($_GET['start']);
    if ($start === false) {
        $start = time();
    }
    $end = strtotime($_GET['end']);
    if ($end === false) {
        $end = time();
    }
    if (abs($end - $start) > 60 * 60 * 24 * 90) {
        $end = strtotime('+3 month', $start);
    }
    $faturamentos = ZPagamento::getTodosFaturamentos(date('Y-m-d', $start), date('Y-m-d', $end));
    $data = [];
    foreach ($faturamentos as $faturamento) {
        $data[] = ['data' => strtotime($faturamento['data']), 'total' => $faturamento['total']];
    }
    json([
        'status' => 'ok',
        'faturamento' => $data,
    ]);
} elseif ($action == 'meta') {
    $intervalo = strtolower($_GET['intervalo']);
    switch ($intervalo) {
        case 'anual':
            $year = date('Y') - 1;
            $atual_de = date('Y-01-01');
            $atual_ate = date('Y-m-d');
            $base_de = date('Y-m-d', mktime(0, 0, 0, 1, 1, $year));
            $base_ate = date('Y-m-d', mktime(0, 0, 0, 12, 31, $year));
            break;
        case 'semanal':
            $atual_de = date('Y-m-d', strtotime('monday this week'));
            $atual_ate = date('Y-m-d');
            $base_de = date('Y-m-d', strtotime('monday last week'));
            $base_ate = date('Y-m-d', strtotime('sunday last week'));
            break;
        case 'diaria':
            $atual_de = date('Y-m-d');
            $atual_ate = $atual_de;
            $base_de = date('Y-m-d', strtotime('-1 week'));
            $base_ate = $base_de;
            break;
        default: // mensal
            $atual_de = date('Y-m-01');
            $atual_ate = null;
            $base_de = -1;
            $base_ate = -1;
            break;
    }
    $atual = ZPagamento::getFaturamento(null, $atual_de, $atual_ate);
    $base = ZPagamento::getFaturamento(null, $base_de, $base_ate);
    json([
        'status' => 'ok',
        'atual' => $atual,
        'base' => $base,
    ]);
}
