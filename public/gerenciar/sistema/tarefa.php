<?php
require_once(dirname(dirname(__DIR__)) . '/app.php');

use MZ\Task\Runner;

define('TASK_TOKEN', 'WWHxIdzDakrea921zGveQkKccrf80mDp');

set_time_limit(0);

if (!isset($_GET['token']) || $_GET['token'] != TASK_TOKEN) {
    need_permission(PermissaoNome::ENTREGAPEDIDOS, true);
}
try {
    $runner = new Runner();
    $runner->execute();
    json(
        'result',
        array(
            'processed' => $runner->getProcessed(),
            'pending' => $runner->getPending(),
            'failed' => $runner->getFailed()
        )
    );
} catch (\Exception $e) {
    Log::error($e->getMessage());
    json($e->getMessage());
}
