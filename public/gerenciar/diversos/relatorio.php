<?php
require_once(dirname(__DIR__) . '/app.php');

use MZ\Database\DB;
use MZ\Payment\Pagamento;

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
    $faturamentos = Pagamento::rawFindAllTotal(
        [
            'apartir_datahora' => DB::date($start),
            'ate_datahora' => DB::now(strtotime('-1 sec tomorrow', $end)),
            '!pedidoid' => null
        ],
        ['dia' => true]
    );
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
            $atual_de = DB::date('first day of jan');
            $atual_ate = null;
            $base_de = DB::date('first day of jan last year');
            $base_ate = DB::now('-1 sec first day of jan');
            break;
        case 'semanal':
            $atual_de = DB::date('monday this week');
            $atual_ate = null;
            $base_de = DB::date('monday last week');
            $base_ate = DB::now('-1 sec monday this week');
            break;
        case 'diaria':
            $atual_de = DB::date('today');
            $atual_ate = null;
            $base_de = DB::date('-1 week');
            $base_ate = DB::now('-1 sec tomorrow -1 week');
            break;
        default: // mensal
            $atual_de = DB::date('first day of this month');
            $atual_ate = null;
            $base_de = DB::date('first day of last month');
            $base_ate = DB::now('-1 sec today first day of this month');
            break;
    }
    $atual = Pagamento::getFaturamento(['apartir_datahora' => $atual_de, 'ate_datahora' => $atual_ate]);
    $base  = Pagamento::getFaturamento(['apartir_datahora' => $base_de, 'ate_datahora' => $base_ate]);
    json([
        'status' => 'ok',
        'atual' => $atual,
        'base' => $base,
    ]);
}
