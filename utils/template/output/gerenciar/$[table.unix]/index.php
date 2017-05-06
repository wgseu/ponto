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

$limite = trim($_GET['limite']);
if(!is_numeric($limite) || $limite > 100 || $limite < 0)
	$limite = 10;
$count = $[class]::getCount();
list($pagesize, $offset, $pagestring) = pagestring($count, $limite);
$$[table.unix.plural] = $[class]::$[class.get.all]($offset, $pagesize);

if($_GET['saida'] == 'json') {
	$_$[table.unix.plural] = array();
	foreach ($$[table.unix.plural] as $$[table.unix]) {
		$_$[table.unix.plural][] = $$[table.unix]->toArray();
	}
	json(array('status' => 'ok', 'items' => $_$[table.unix.plural]));
}

$[field.each]
$[field.if(enum)]
$$[field.unix]_values = array(
$[field.each(option)]
	'$[field.option]' => '$[Field.option.name]',
$[field.end]
);
$[field.end]
$[field.end]
include template('gerenciar_$[table.unix]_index');