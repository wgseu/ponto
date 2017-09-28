<?php
require_once(dirname(dirname(__FILE__)) . '/app.php');

need_permission(
    PermissaoNome::PAGAMENTO,
    is_output('json')
);

set_time_limit(0);

try {
    $modo = $_GET['modo'];

    $busca = $_GET['query'];
    $estado = $_GET['estado'];
    $acao = $_GET['acao'];
    $ambiente = $_GET['ambiente'];
    $serie = $_GET['serie'];
    $pedido_id = $_GET['pedido_id'];
    $tipo = $_GET['tipo'];
    $contingencia = $_GET['contingencia'];
    $emissao_inicio = strtotime($_GET['emissao_inicio']);
    $emissao_fim = strtotime($_GET['emissao_fim']);
    $lancamento_inicio = strtotime($_GET['lancamento_inicio']);
    $lancamento_fim = strtotime($_GET['lancamento_fim']);

    if (!in_array($modo, array('contador', 'consumidor'))) {
        throw new Exception('O modo de envio "'.$modo.'" é inválido', 500);
    }
    $notas = ZNota::getTodas($busca, $estado, $acao, $ambiente, $serie, $pedido_id,
        $tipo, $contingencia, $emissao_inicio, $emissao_fim, $lancamento_inicio, $lancamento_fim);
    if (count($notas) == 0) {
        throw new Exception('Nenhuma nota no resultado da busca', 404);
    }
    if (count($notas) > 1 && $modo == 'consumidor') {
        throw new Exception('Apenas um E-mail por vez pode ser enviado para um consumidor', 500);
    }
    $_nota = current($notas);
    $nfe_api = new NFeAPI();
    $nfe_api->init();
    $destinatario_id = $nfe_api->getExternalEmitente()->getContadorID();
    if ($modo == 'consumidor') {
        $pedido = ZPedido::getPeloID($_nota->getPedidoID());
        $destinatario_id = $pedido->getClienteID();
    }
    $destinatario = ZCliente::getPeloID($destinatario_id);
    if (is_null($destinatario->getID())) {
        if ($modo == 'contador') {
            throw new Exception('O contador não foi informado nas configurações do emitente', 500);
        } else {
            throw new Exception('O consumidor não foi informado no pedido', 500);
        }
    }
    if (!check_email($destinatario->getEmail())) {
        if ($modo == 'contador') {
            throw new Exception('O E-mail do contador não foi informado no cadastro', 500);
        } else {
            throw new Exception('O E-mail do consumidor não foi informado no cadastro', 500);
        }
    }
    $sufixo = '';
    $filters = array();
    if ($emissao_inicio !== false || $emissao_fim !== false) {
        $sufixo .= ' emissão '.human_range($emissao_inicio, $emissao_fim, '-');
        $filters['Período de emissão'] = human_range($emissao_inicio, $emissao_fim);
    }
    if ($lancamento_inicio !== false || $lancamento_fim !== false) {
        $sufixo .= ' lançamento '.human_range($emissao_inicio, $emissao_fim, '-');
        $filters['Período de lançamento'] = human_range($lancamento_inicio, $lancamento_fim);
    }
    if (is_numeric($serie)) {
        $filters['Série'] = intval($serie);
    }
    if (trim($ambiente) != '') {
        $filters['Ambiente'] = $ambiente;
    }
    if (trim($contingencia) == 'Y') {
        $filters['Contingência'] = 'Sim';
    }
    if (trim($estado) != '') {
        $sufixo .= ' '.$estado;
        $filters['Estado da nota'] = $estado;
    }
    if (count($notas) == 1) {
        $xmlfile = $_nota->getCaminhoXml();
        if (!is_array($xmlfile)) {
            $xmlname = basename($xmlfile);
            mail_nota($destinatario->getEmail(), $destinatario->getNome(), $modo, $filters, array($xmlname => $xmlfile));
            json(null, array());
        }
    }
    need_permission(
        array(
            PermissaoNome::RELATORIOFLUXO,
            PermissaoNome::EXCLUIRPEDIDO,
        ),
        is_output('json')
    );
    $zipfile = ZNota::zip($notas);
    $zipname = 'Notas'.$sufixo.'.zip';
    try {
        mail_nota($destinatario->getEmail(), $destinatario->getNome(), $modo, $filters, array($zipname => $zipfile));
    } catch (Exception $e) {
        unlink($zipfile);
        throw $e;
    }
    unlink($zipfile);
    json(null, array());
} catch (Exception $e) {
    Log::error($e->getMessage());
    json($e->getMessage());
}
