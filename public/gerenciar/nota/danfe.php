<?php
require_once(dirname(__DIR__) . '/app.php');

use MZ\Invoice\Nota;
use MZ\System\Permissao;
use MZ\Sale\Pedido;

need_permission(
    [
        Permissao::NOME_PAGAMENTO, ['||'],
        Permissao::NOME_SELECIONARCAIXA, ['||'],
        Permissao::NOME_RELATORIOPEDIDOS
    ],
    true
);

try {
    $pedido_id = isset($_GET['pedidoid']) ? $_GET['pedidoid'] : null;
    $pedido = Pedido::findByID($pedido_id);
    if (!$pedido->exists()) {
        throw new \Exception('O pedido não foi informado ou não existe', 404);
    }
    $_nota = Nota::findValida($pedido->getID());
    if (!$_nota->exists()) {
        throw new \Exception('Não existe nota para o pedido informado', 404);
    }
    if (!$_nota->isAutorizada()) {
        throw new \Exception('A nota desse pedido ainda não foi autorizada', 500);
    }
    $nfe_api = new \NFeAPI();
    $nfe_api->init();
    $xmlfile = \NFeDB::getCaminhoXmlAtual($_nota);
    $nota = new \NFe\Core\NFCe();
    $nota->load($xmlfile);
    json('nota', $nota->toArray(true));
} catch (\Exception $e) {
    \Log::error($e->getMessage());
    json($e->getMessage());
}
