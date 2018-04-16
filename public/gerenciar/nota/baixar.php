<?php
require_once(dirname(__DIR__) . '/app.php');

use MZ\Invoice\Nota;
use MZ\System\Permissao;
use MZ\Util\Filter;

need_permission(
    [
        Permissao::NOME_RELATORIOFLUXO,
        Permissao::NOME_EXCLUIRPEDIDO,
    ],
    true
);

set_time_limit(0);

try {
    $condition = Filter::query($_GET);
    $notas = Nota::findAll($condition);
    if (count($notas) == 0) {
        throw new \Exception('Nenhuma nota no resultado da busca', 404);
    }
    $nfe_api = new \NFeAPI();
    $nfe_api->init();
    if (count($notas) == 1) {
        $_nota = current($notas);
        $xmlfile = $_nota->getCaminhoXml();
        if (!is_array($xmlfile)) {
            $xmlname = basename($xmlfile);
            header('Content-Type: application/xml; charset=utf-8');
            header("Content-Disposition: attachment; filename*=UTF-8''" . rawurlencode($xmlname));
            readfile($xmlfile);
            exit;
        }
    }
    $zipfile = Nota::zip($notas);
    $zipname = 'notas.zip';
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: public");
    header("Content-Description: File Transfer");
    header("Content-Type: application/octet-stream");
    header("Content-Disposition: attachment; filename*=UTF-8''" . rawurlencode($zipname));
    header("Content-Transfer-Encoding: binary");
    header("Content-Length: ".filesize($zipfile));
    readfile($zipfile);
    unlink($zipfile);
} catch (\Exception $e) {
    \Log::error($e->getMessage());
    json($e->getMessage());
}
