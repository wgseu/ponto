<?php
require_once(dirname(dirname(__FILE__)) . '/app.php');

need_permission(
	array(
		\PermissaoNome::RELATORIOFLUXO, 
		\PermissaoNome::EXCLUIRPEDIDO,
	),
	isset($_GET['saida']) && $_GET['saida'] == 'json'
);

set_time_limit(0);

try {
	$busca = isset($_GET['query'])?$_GET['query']:null;
	$estado = isset($_GET['estado'])?$_GET['estado']:null;
	$acao = isset($_GET['acao'])?$_GET['acao']:null;
	$ambiente = isset($_GET['ambiente'])?$_GET['ambiente']:null;
	$serie = isset($_GET['serie'])?$_GET['serie']:null;
	$pedido_id = isset($_GET['pedido_id'])?$_GET['pedido_id']:null;
	$tipo = isset($_GET['tipo'])?$_GET['tipo']:null;
	$contingencia = isset($_GET['contingencia'])?$_GET['contingencia']:null;
	$emissao_inicio = isset($_GET['emissao_inicio']?strtotime($_GET['emissao_inicio']):null;
	$emissao_fim = isset($_GET['emissao_fim'])?strtotime($_GET['emissao_fim']):null;
	$lancamento_inicio = isset($_GET['lancamento_inicio'])?strtotime($_GET['lancamento_inicio']):null;
	$lancamento_fim = isset($_GET['lancamento_fim'])?strtotime($_GET['lancamento_fim']):null;

	$notas = \ZNota::getTodas(
		$busca,
		$estado,
		$acao,
		$ambiente,
		$serie,
		$pedido_id,
		$tipo,
		$contingencia,
		$emissao_inicio,
		$emissao_fim,
		$lancamento_inicio,
		$lancamento_fim
	);
	if (count($notas) == 0) {
		throw new \Exception('Nenhuma nota no resultado da busca', 404);
	}
	$nfe_api = new \NFeAPI();
	$nfe_api->init();
	if (count($notas) == 1) {
		$_nota = current($notas);
		$xmlfile = \NFeDB::getCaminhoXmlAtual($_nota);
		if (!file_exists($xmlfile)) {
			throw new \Exception('Não existe XML para a nota de número "' . $_nota->getNumeroInicial() . '"', 404);
		}
		$xmlname = basename($xmlfile);
		header('Content-Type: application/xml; charset=utf-8');
    	header("Content-Disposition: attachment; filename*=UTF-8''" . rawurlencode($xmlname));
		readfile($xmlfile);
		exit;
	}
	$zipfile = \ZNota::zip($notas);
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