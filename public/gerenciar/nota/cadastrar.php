<?php
require_once(dirname(__DIR__) . '/app.php');

need_permission([Permissao::NOME_PAGAMENTO, ['||'], Permissao::NOME_SELECIONARCAIXA], is_output('json'));

if (!is_post()) {
    json('Nenhum dado foi enviado');
}

try {
    $caixa = Caixa::findByID($_POST['caixa_id']);
    if (is_null($caixa->getID())) {
        throw new \Exception('O caixa de código "'.$_POST['caixa_id'].'" não existe', 404);
    }
    if (!$caixa->isAtivo()) {
        throw new \Exception('O caixa "'.$caixa->getDescricao().'" não está ativo', 500);
    }
    $pedido = Pedido::findByID($_POST['pedido_id']);
    if (is_null($pedido->getID())) {
        throw new \Exception('O pedido de código "'.$_POST['pedido_id'].'" não existe', 404);
    }
    $emitente = Emitente::findByID('1');
    if (is_null($emitente->getID())) {
        throw new \Exception('As configurações fiscais do emitente não foram ajustadas', 500);
    }
    $nota = Nota::findByPedidoID($pedido->getID(), true);
    $added = 0;
    if (is_null($nota->getID())) {
        $nota->setPedidoID($pedido->getID());
        $nota->setSerie($caixa->getSerie());
        $nota->setAmbiente($emitente->getAmbiente());
        $nota = Nota::criarProxima($nota);
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
    } catch (Exception $e) {
        Log::error($e->getMessage());
    }
    json('nota', [
        'id' => $nota->getID(),
        'pedido_id' => $nota->getPedidoID(),
        'notificado' => $notified,
        'adicionado' => $added
    ]);
} catch (Exception $e) {
    Log::error($e->getMessage());
    json($e->getMessage());
}
