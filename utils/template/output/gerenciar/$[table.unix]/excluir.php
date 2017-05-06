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

need_permission(PermissaoNome::$[TABLE.style], $_GET['saida'] == 'json');
$$[table.pk.unix] = $_GET['$[table.pk]'];
$$[table.unix] = $[class]::$[class.get]($$[table.pk.unix]);
if(is_null($$[table.unix]->$[table.pk.get])) {
	$msg = '$[Table.gender] $[table.name] de $[table.pk] "'.$$[table.pk.unix].'" não existe!';
	if($_GET['saida'] == 'json')
		json($msg);
	Thunder::warning($msg);
	redirect('/gerenciar/$[table.unix]/');
}
try {
	$[class]::excluir($$[table.pk.unix]);
$[field.each]
$[field.if(image)]
	// exclui $[field.gender] $[field.name] enviad$[field.gender]
	if(!is_null($$[table.unix]->$[field.get])) {
		unlink(WWW_ROOT . get_image_url($$[table.unix]->$[field.get], '$[field.image.folder]'));
	}
$[field.end]
$[field.end]
	$msg = '$[Table.name] "' . $$[table.unix]->$[table.desc.get] . '" excluíd$[table.gender] com sucesso!';
	if($_GET['saida'] == 'json')
		json('msg', $msg);
	Thunder::success($msg, true);
} catch (Exception $e) {
	$msg = 'Não foi possível excluir $[table.gender] $[table.name] "' . $$[table.unix]->$[table.desc.get] . '"!';
	if($_GET['saida'] == 'json')
		json($msg);
	Thunder::error($msg);
}
redirect('/gerenciar/$[table.unix]/');