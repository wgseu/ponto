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
$focusctrl = '$[table.desc]';
$errors = array();
if ($_POST) {
	$$[table.unix] = new $[class]($_POST);
	try {
		$$[table.unix]->$[table.pk.set]null);
$[field.each]
$[field.if(date)]
		$_$[field.unix] = date_create_from_format('d/m/Y', $$[table.unix]->$[field.get]);
		$$[table.unix]->$[field.set]$_$[field.unix] === false?null:date_format($_$[field.unix], 'Y-m-d'));
$[field.else.if(time)]
		$_$[field.unix] = strtotime($$[table.unix]->$[field.get]);
		$$[table.unix]->$[field.set]$_$[field.unix] === false?null:date('H:i:s', $_$[field.unix]));
$[field.else.if(datetime)]
		$_$[field.unix] = strtotime($$[table.unix]->$[field.get]);
		$$[table.unix]->$[field.set]$_$[field.unix] === false?null:date('Y-m-d H:i:s', $_$[field.unix]));
$[field.else.if(currency)]
		$$[table.unix]->$[field.set]valmoney($$[table.unix]->$[field.get]));
$[field.else.if(float)]
		$$[table.unix]->$[field.set]valmoney($$[table.unix]->$[field.get]));
$[field.else.if(integer)]
		$$[table.unix]->$[field.set]number_only($$[table.unix]->$[field.get]));
$[field.else.if(masked)]
		$$[table.unix]->$[field.set]unmask($$[table.unix]->$[field.get], '$[field.mask]'));
$[field.else.if(image)]
		$$[field.unix] = upload_image('raw_$[field]', '$[field.image.folder]');
		$$[table.unix]->$[field.set]$$[field.unix]);
$[field.else.if(blob)]
		$$[field.unix] = upload_image('raw_$[field]', '$[field.image.folder]');
		if(!is_null($$[field.unix])) {
			$$[table.unix]->$[field.set]file_get_contents(WWW_ROOT . get_image_url($$[field.unix], '$[field.image.folder]')));
			unlink(WWW_ROOT . get_image_url($$[field.unix], '$[field.image.folder]'));
		} else
			$$[table.unix]->$[field.set]null);
$[field.end]
$[field.end]
		$$[table.unix] = $[class]::cadastrar($$[table.unix]);
		$msg = '$[Table.name] "'.$$[table.unix]->$[table.desc.get].'" cadastrad$[table.gender] com sucesso!';
		if($_GET['saida'] == 'json')
			json(null, array('item' => $$[table.unix]->toArray(), 'msg' => $msg));
		Thunder::success($msg, true);
		redirect('/gerenciar/$[table.unix]/');
	} catch (ValidationException $e) {
		$errors = $e->getErrors();
	} catch (Exception $e) {
		$errors['unknow'] = $e->getMessage();
	}
$[field.each]
$[field.if(image)]
	// remove $[field.gender] $[field.name] enviad$[field.gender]
	if(!is_null($$[table.unix]->$[field.get]))
		unlink(WWW_ROOT . get_image_url($$[table.unix]->$[field.get], '$[field.image.folder]'));
	$$[table.unix]->$[field.set]null);
$[field.else.if(blob)]
	// remove $[field.gender] $[field.name] enviad$[field.gender]
	$$[table.unix]->$[field.set]null);
$[field.end]
$[field.end]
	foreach($errors as $key => $value) {
		$focusctrl = $key;
		if($_GET['saida'] == 'json')
			json($value, null, array('field' => $focusctrl));
		Thunder::error($value);
		break;
	}
} else {
	$$[table.unix] = new $[class]();
$[field.each]
$[field.if(date)]
	$$[table.unix]->$[field.set]date('Y-m-d', time()));
$[field.end]
$[field.end]
$[field.each]
$[field.if(datetime)]
	$$[table.unix]->$[field.set]date('Y-m-d H:i:s', time()));
$[field.end]
$[field.end]
}
if($_GET['saida'] == 'json')
	json('Nenhum dado foi enviado');
$[field.each(reference)]
$_$[reference.unix.plural] = $[reference.class]::$[reference.class.get.all]();
$[field.end]
include template('gerenciar_$[table.unix]_cadastrar');