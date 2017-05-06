<?php
/*
	Copyright 2016 da MZ Software - MZ Desenvolvimento de Sistemas LTDA
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

need_permission(PermissaoNome::ALTERARPAGINAS);
$focusctrl = 'nome';
$errors = array();
$nomes = get_pages_info();
$linguagens = get_languages_info();
if ($_POST) {
	$pagina = new ZPagina($_POST);
	try {
		$pagina->setID(null);
		$pagina->setLinguagemID(numberval($pagina->getLinguagemID()));
		$pagina = ZPagina::cadastrar($pagina);
		Thunder::success('Página "'.$nomes[$pagina->getNome()] . ' - ' . $linguagens[$pagina->getLinguagemID()].'" cadastrada com sucesso!', true);
		redirect('/gerenciar/pagina/');
	} catch (ValidationException $e) {
		$errors = $e->getErrors();
	} catch (Exception $e) {
		$errors['unknow'] = $e->getMessage();
	}
	foreach($errors as $key => $value) {
		$focusctrl = $key;
		Thunder::error($value);
		break;
	}
} else {
	$pagina = new ZPagina();
}
include template('gerenciar_pagina_cadastrar');