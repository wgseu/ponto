<?php
require_once(dirname(__DIR__) . '/app.php');
need_owner(true);
$action = isset($_GET['action']) ? $_GET['action'] : null;
if ($action == 'faturamento') {
    $start = strtotime(isset($_GET['start']) ? $_GET['start'] : null);
    if ($start === false) {
        $start = time();
    }
    $end = strtotime(isset($_GET['end']) ? $_GET['end'] : null);
    if ($end === false) {
        $end = time();
    }
    if (abs($end - $start) > 60 * 60 * 24 * 90) {
        $end = strtotime('+3 month', $start);
    }
    $faturamentos = Pagamento::getTodosFaturamentos(date('Y-m-d', $start), date('Y-m-d', $end));
    $data = [];
    foreach ($faturamentos as $faturamento) {
        $data[] = ['data' => strtotime($faturamento['data']), 'total' => $faturamento['total']];
    }
    json([
        'status' => 'ok',
        'faturamento' => $data,
    ]);
} elseif ($action == 'meta') {
    $intervalo = strtolower(isset($_GET['intervalo']) ? $_GET['intervalo'] : null);
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
    $atual = Pagamento::getFaturamento(null, $atual_de, $atual_ate);
    $base = Pagamento::getFaturamento(null, $base_de, $base_ate);
    json([
        'status' => 'ok',
        'atual' => $atual,
        'base' => $base,
    ]);
}
