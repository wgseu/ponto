<?php
/*
	Copyright 2014 da MZ Software - MZ Desenvolvimento de Sistemas LTDA
	Este arquivo é parte do programa GrandChef - Sistema para Gerenciamento de Churrascarias, Bares e Restaurantes.
	O GrandChef é um software proprietário; você não pode redistribuí-lo e/ou modificá-lo.
	DISPOSIÇÕES GERAIS
	O cliente não deverá remover qualquer identificação do produto, avisos de direitos autorais,
	ou outros avisos ou restrições de propriedade do GrandChef.

	O cliente não deverá causar ou permitir a engenharia reversa, desmontagem,
	ou descompilação do GrandChef.

	PROPRIEDADE DOS DIREITOS AUTORAIS DO PROGRAMA

	GrandChef é a especialidade do desenvolvedor e seus
	licenciadores e é protegido por direitos autorais, segredos comerciais e outros direitos
	de leis de propriedade.

	O Cliente adquire apenas o direito de usar o software e não adquire qualquer outros
	direitos, expressos ou implícitos no GrandChef diferentes dos especificados nesta Licença.
*/
require_once(dirname(dirname(__FILE__)) . '/app.php');

need_manager($_GET['saida'] == 'json');
need_permission(PermissaoNome::CADASTROPRODUTOS);

$pagetitle = 'Converter cardápio iFood';

if ($_POST) {
	try {
		$cardapio_file = upload_document('raw_cardapio', 'xml', 'cardapio.html');
		if(is_null($cardapio_file))
			throw new Exception('O cardápio não foi enviado', 404);
		$cardapio_path = WWW_ROOT . get_document_url($cardapio_file, 'xml');
		$cardapio_data = file_get_contents($cardapio_path);
		unlink($cardapio_path);
		$dom = new \DOMDocument();
		$dom->loadHTML($cardapio_data);
		$nodes = $dom->getElementsByTagName('strong');
		$ifood = array();
		$codigos = array();
		$i = 0;
		foreach ($nodes as $strong) {
			$finder = new DOMXPath($dom);
			$list = $strong->getElementsByTagName('input');
			// $list = $finder->query("//input[contains(@class, 'codigo')]", $strong);
			if ($list->length > 0) {
				$codigo = $list->item(0)->getAttribute('value');
			} else {
				$list = $strong->getElementsByTagName('span');
				if ($list->length > 0) {
					$codigo = $list->item(0)->getAttribute('id');
				} else {
					$codigo = 'unknow_'.++$i;
				}
			}
			$descricao = trim($strong->nodeValue);
			$codigos[$codigo] = $descricao;
		}
		$codigos_sistema = array();
		$produtos = ZProduto::getTodos();
		foreach ($produtos as $produto) {
			$codigos_sistema[$produto->getID()] = $produto->getDescricao();
			if ($produto->getTipo() != ProdutoTipo::PACOTE) {
				continue;
			}
			$grupos = ZGrupo::getTodosDoProdutoID($produto->getID());
			foreach ($grupos as $grupo) {
				$pacotes = ZPacote::getTodosDoGrupoIDEx($grupo->getID());
				foreach ($pacotes as $pacote) {
					if (!is_null($pacote['associacaoid'])) {
						$associado = ZPacote::getPeloID($pacote['associacaoid']);
						$produto_associado = ZProduto::getPeloID($associado->getProdutoID());
						$propriedade_associado = ZPropriedade::getPeloID($associado->getPropriedadeID());
						$assoc_desc = is_null($associado->getProdutoID())?$propriedade_associado->getNome().' - '.$propriedade_associado->getAbreviacao():$produto_associado->getDescricao().' - '.$produto_associado->getAbreviacao();
						$assoc_desc = ': '.$assoc_desc;
					} else {
						$assoc_desc = '';
					}
					if (!is_null($pacote['abreviacao'])) {
						$abrev = ' - '.$pacote['abreviacao'].$assoc_desc;
					} else {
						$abrev = '';
					}
					$codigos_sistema['pacote['.$pacote['id'].']'] = $pacote['descricao'].$abrev;
				}
			}
		}
		$ifood['Codigos'] = $codigos;
		$ifood['Sistema'] = $codigos_sistema;

		header('Content-Type: text/plain');
		header('Content-Disposition: attachment; filename="ifood.ini"');
		echo to_ini($ifood);
		exit;
	} catch (Exception $e) {
		Thunder::error($e->getMessage());
	}
}

include template('produto_ifood');