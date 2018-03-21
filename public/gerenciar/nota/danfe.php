<?php
require_once(dirname(__DIR__) . '/app.php');

need_permission(
    [
        PermissaoNome::PAGAMENTO, ['||'],
        PermissaoNome::SELECIONARCAIXA, ['||'],
        PermissaoNome::RELATORIOPEDIDOS
    ],
    is_output('json')
);

try {
    $pedido = ZPedido::getPeloID($_GET['pedido_id']);
    if (is_null($pedido->getID())) {
        throw new Exception('O pedido de código "'.$_GET['pedido_id'].'" não existe', 404);
    }
    $_nota = ZNota::getValida($pedido->getID());
    if (is_null($_nota->getID())) {
        throw new Exception('Não existe nota para o pedido de código "'.$pedido->getID().'"', 404);
    }
    if (!$_nota->isAutorizada()) {
        throw new Exception('A nota desse pedido ainda não foi autorizada', 500);
    }
    $nfe_api = new NFeAPI();
    $nfe_api->init();
    $xmlfile = NFeDB::getCaminhoXmlAtual($_nota);
    $nota = new \NFe\Core\NFCe();
    $nota->load($xmlfile);
    if (is_output('json')) {
        json('nota', $nota->toArray(true));
    }
} catch (Exception $e) {
    Log::error($e->getMessage());
    json($e->getMessage());
}
