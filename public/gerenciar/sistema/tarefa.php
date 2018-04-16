<?php
require_once(dirname(dirname(__DIR__)) . '/app.php'); // main app file

use MZ\Task\Runner;
use MZ\System\Permissao;

define('TASK_TOKEN', 'WWHxIdzDakrea921zGveQkKccrf80mDp');

set_time_limit(0);

if (!isset($_GET['token']) || $_GET['token'] != TASK_TOKEN) {
    need_permission(Permissao::NOME_ENTREGAPEDIDOS, true);
}
try {
    $runner = new Runner();
    $runner->execute();
    json(
        'result',
        [
            'processed' => $runner->getProcessed(),
            'pending' => $runner->getPending(),
            'failed' => $runner->getFailed()
        ]
    );
} catch (\Exception $e) {
    \Log::error($e->getMessage());
    json($e->getMessage());
}
