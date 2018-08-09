<?php
use MZ\System\Permissao;

need_permission(Permissao::NOME_ALTERARCONFIGURACOES, true);

set_time_limit(0);
$nfe_api = new \NFeAPI();
$nfe_api->setOffline(isset($_POST['offline_start']) ? $_POST['offline_start'] : null);
try {
    $nfe_api->init();
    $result = $nfe_api->processa();
    json('result', ['processed' => $result, 'offline_start' => $nfe_api->getOffline()]);
} catch (\Exception $e) {
    \Log::error($e->getMessage());
    json($e->getMessage());
}
