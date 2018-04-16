<?php
require_once(dirname(__DIR__) . '/app.php');

use MZ\Invoice\Nota;
use MZ\Sale\Pedido;
use MZ\Invoice\Emitente;
use MZ\Session\Caixa;
use MZ\System\Permissao;

need_permission([Permissao::NOME_PAGAMENTO, ['||'], Permissao::NOME_SELECIONARCAIXA], true);

if (!is_post()) {
    json('Nenhum dado foi enviado');
}

try {
    $caixa = Caixa::findByID(isset($_POST['caixa_id']) ? $_POST['caixa_id'] : null);
    if (!$caixa->exists()) {
        throw new \Exception('O caixa informado não existe', 404);
    }
    if (!$caixa->isAtivo()) {
        throw new \Exception(sprintf('O caixa "%s" não está ativo', $caixa->getDescricao()), 500);
    }
    $pedido = Pedido::findByID(isset($_POST['pedido_id']) ? $_POST['pedido_id'] : null);
    if (!$pedido->exists()) {
        throw new \Exception('O pedido informado não existe', 404);
    }
    $emitente = Emitente::findByID('1');
    if (!$emitente->exists()) {
        throw new \Exception('As configurações fiscais do emitente não foram ajustadas', 500);
    }
    $nota = Nota::findByPedidoID($pedido->getID(), true);
    $added = 0;
    if (!$nota->exists()) {
        $nota->setPedidoID($pedido->getID());
        $nota->setSerie($caixa->getSerie());
        $nota->setAmbiente($emitente->getAmbiente());
        $nota = $nota->criarProxima();
        $added = 1;
    }
    if (!$nota->isCorrigido()) {
        $nota->setCorrigido('Y');
        $nota->update();
    }
    $notified = 0;
    try {
        $appsync = new \MZ\System\Synchronizer();
        if ($added) {
            $appsync->invoiceAdded($nota->getID(), $nota->getPedidoID());
        } else {
            $appsync->invoiceRun($nota->getID(), $nota->getPedidoID());
        }
        $notified = 1;
    } catch (\Exception $e) {
        Log::error($e->getMessage());
    }
    json('nota', [
        'id' => $nota->getID(),
        'pedido_id' => $nota->getPedidoID(),
        'notificado' => $notified,
        'adicionado' => $added
    ]);
} catch (\Exception $e) {
    Log::error($e->getMessage());
    json($e->getMessage());
}
