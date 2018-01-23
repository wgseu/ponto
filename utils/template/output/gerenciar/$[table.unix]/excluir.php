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
require_once(dirname(__DIR__) . '/app.php');

use $[tAble.package]\$[tAble.norm];

need_permission(\PermissaoNome::$[TABLE.style], is_output('json'));
$$[primary.unix] = isset($_GET['$[primary.unix]'])?$_GET['$[primary.unix]']:null;
$$[table.unix] = $[tAble.norm]::findBy$[pRimary.norm]($$[primary.unix]);
if (!$$[table.unix]->exists()) {
	$msg = 'Não existe $[tAble.name] com $[primary.gender] $[pRimary.name] informado!';
	if (is_output('json')) {
		json($msg);
	}
	\Thunder::warning($msg);
	redirect('/gerenciar/$[table.unix]/');
}
try {
	$$[table.unix]->delete();
	$$[table.unix]->clean(new $[tAble.norm]());
	$msg = '$[tAble.name] "' . $$[table.unix]->get$[dEscriptor.norm]() . '" excluíd$[table.gender] com sucesso!';
	if (is_output('json')) {
		json('msg', $msg);
	}
	\Thunder::success($msg, true);
} catch (\Exception $e) {
	$msg = sprintf(
		'Não foi possível excluir $[table.gender] $[tAble.name] "%s"!',
		$$[table.unix]->get$[dEscriptor.norm]()
	);
	if (is_output('json')) {
		json($msg);
	}
	\Thunder::error($msg);
}
redirect('/gerenciar/$[table.unix]/');
