<?php
require_once(dirname(dirname(__FILE__)) . '/app.php');

need_permission(
	array(
		PermissaoNome::RELATORIOFLUXO, 
		PermissaoNome::EXCLUIRPEDIDO,
	),
	$_GET['saida'] == 'json'
);

try {
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

	$notas = ZNota::getTodas($busca, $estado, $acao, $ambiente, $serie, $pedido_id, 
		$tipo, $contingencia, $emissao_inicio, $emissao_fim, $lancamento_inicio, $lancamento_fim);
	if(count($notas) == 0)
		throw new Exception('Nenhuma nota no resultado da busca', 404);
	$nfe_api = new NFeAPI();
	$nfe_api->init();
	if (count($notas) == 1) {
		$_nota = current($notas);
		$xmlfile = NFeDB::getCaminhoXmlAtual($_nota);
		if(!file_exists($xmlfile))
			throw new Exception('Não existe XML para a nota de número "' . $_nota->getNumeroInicial() . '"', 404);
		$xmlname = basename($xmlfile);
		header('Content-Type: application/xml; charset=utf-8');
		header('Content-Disposition: attachment; filename="' . $xmlname . '"');
		readfile($xmlfile);
		exit;
	}
	$zipfile = ZNota::zip($notas);
	$zipname = 'notas.zip';
	header("Pragma: public");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: public");
	header("Content-Description: File Transfer");
	header("Content-Type: application/octet-stream");
	header('Content-Disposition: attachment; filename="' . $zipname . '"');
	header("Content-Transfer-Encoding: binary");
	header("Content-Length: ".filesize($zipfile));
	readfile($zipfile);
	unlink($zipfile);
} catch (Exception $e) {
	Log::error($e->getMessage());
	json($e->getMessage());
}