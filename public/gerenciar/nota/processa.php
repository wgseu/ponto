<?php
require_once(dirname(__DIR__) . '/app.php');

need_permission(Permissao::NOME_ALTERARCONFIGURACOES, is_output('json'));

set_time_limit(0);
$nfe_api = new NFeAPI();
$nfe_api->setOffline($_POST['offline_start']);
try {
    $nfe_api->init();
    $result = $nfe_api->processa();
    json('result', ['processed' => $result, 'offline_start' => $nfe_api->getOffline()]);
} catch (Exception $e) {
    Log::error($e->getMessage());
    json($e->getMessage());
}
