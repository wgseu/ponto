<?php
require_once(dirname(dirname(__FILE__)) . '/app.php');

need_permission(PermissaoNome::ALTERARCONFIGURACOES, $_GET['saida'] == 'json');

set_time_limit(0);
$nfe_api = new NFeAPI();
$nfe_api->setOffline($_POST['offline_start']);
try {
	$nfe_api->init();
	$result = $nfe_api->processa();
	json('result', array('processed' => $result, 'offline_start' => $nfe_api->getOffline()));
} catch (Exception $e) {
	Log::error($e->getMessage());
	json($e->getMessage());
}