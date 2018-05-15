<?php
require_once(dirname(__DIR__) . '/app.php');

use MZ\Invoice\Nota;
use MZ\System\Permissao;
use MZ\Util\Filter;

need_permission(
    Permissao::NOME_PAGAMENTO,
    true
);

set_time_limit(0);

try {
    $condition = Filter::query($_GET);
    $notas = Nota::findAll($condition);
    $nota = new Nota($condition);

    $modo = isset($condition['modo']) ? $condition['modo'] : null;

    $emissao_inicio = isset($condition['emissao_inicio']) ? strtotime($condition['emissao_inicio']) : null;
    $emissao_fim = isset($condition['emissao_fim']) ? strtotime($condition['emissao_fim']) : null;
    $lancamento_inicio = isset($condition['lancamento_inicio']) ? strtotime($condition['lancamento_inicio']) : null;
    $lancamento_fim = isset($condition['lancamento_fim']) ? strtotime($condition['lancamento_fim']) : null;

    if (!in_array($modo, ['contador', 'consumidor'])) {
        throw new \Exception('O modo de envio informado é inválido', 500);
    }
    if (count($notas) == 0) {
        throw new \Exception('Nenhuma nota no resultado da busca', 404);
    }
    if (count($notas) > 1 && $modo == 'consumidor') {
        throw new \Exception('Apenas uma nota por vez pode ser enviada para um consumidor', 500);
    }
    $_nota = current($notas);
    $nfe_api = new \NFeAPI();
    $nfe_api->init();
    $destinatario = $nfe_api->getExternalEmitente()->findContadorID();
    if ($modo == 'consumidor') {
        $pedido = $_nota->findPedidoID();
        $destinatario = $pedido->findClienteID();
    }
    if (!$destinatario->exists()) {
        if ($modo == 'contador') {
            throw new \Exception('O contador não foi informado nas configurações do emitente', 500);
        } else {
            throw new \Exception('O consumidor não foi informado no pedido', 500);
        }
    }
    if (!check_email($destinatario->getEmail())) {
        if ($modo == 'contador') {
            throw new \Exception('O E-mail do contador não foi informado no cadastro', 500);
        } else {
            throw new \Exception('O E-mail do consumidor não foi informado no cadastro', 500);
        }
    }
    $sufixo = '';
    $filters = [];
    if ($emissao_inicio !== false || $emissao_fim !== false) {
        $sufixo .= ' emissão '.human_range($emissao_inicio, $emissao_fim, '-');
        $filters['Período de emissão'] = human_range($emissao_inicio, $emissao_fim);
    }
    if ($lancamento_inicio !== false || $lancamento_fim !== false) {
        $sufixo .= ' lançamento '.human_range($emissao_inicio, $emissao_fim, '-');
        $filters['Período de lançamento'] = human_range($lancamento_inicio, $lancamento_fim);
    }
    if (is_numeric($nota->getSerie())) {
        $filters['Série'] = intval($nota->getSerie());
    }
    if (trim($nota->getAmbiente()) != '') {
        $filters['Ambiente'] = Nota::getAmbienteOptions($nota->getAmbiente());
    }
    if ($nota->isContingencia()) {
        $filters['Contingência'] = 'Sim';
    }
    if (trim($nota->getEstado()) != '') {
        $sufixo .= ' ' . Nota::getEstadoOptions($nota->getEstado());
        $filters['Estado da nota'] = Nota::getEstadoOptions($nota->getEstado());
    }
    if (count($notas) == 1) {
        $xmlfile = $_nota->getCaminhoXml();
        if (!is_array($xmlfile)) {
            $xmlname = basename($xmlfile);
            mail_nota($destinatario->getEmail(), $destinatario->getNome(), $modo, $filters, [$xmlname => $xmlfile]);
            json(null, []);
        }
    }
    need_permission(
        [
            Permissao::NOME_RELATORIOFLUXO,
            Permissao::NOME_EXCLUIRPEDIDO,
        ],
        is_output('json')
    );
    $zipfile = Nota::zip($notas);
    $zipname = 'Notas'.$sufixo.'.zip';
    try {
        mail_nota($destinatario->getEmail(), $destinatario->getNome(), $modo, $filters, [$zipname => $zipfile]);
    } catch (\Exception $e) {
        unlink($zipfile);
        throw $e;
    }
    unlink($zipfile);
    json(null, []);
} catch (\Exception $e) {
    json($e->getMessage());
}
