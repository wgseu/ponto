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

use MZ\Location\Cidade;

need_permission(\PermissaoNome::CADASTROCIDADES, is_output('json'));
$id = isset($_GET['id'])?$_GET['id']:null;
$cidade = Cidade::findByID($id);
if (!$cidade->exists()) {
	$msg = 'Não existe Cidade com o ID informado!';
	if (is_output('json')) {
		json($msg);
	}
	\Thunder::warning($msg);
	redirect('/gerenciar/cidade/');
}
try {
	$cidade->delete();
	$cidade->clean(new Cidade());
	$msg = sprintf('Cidade "%s" excluída com sucesso!', $cidade->getNome());
	if (is_output('json')) {
		json('msg', $msg);
	}
	\Thunder::success($msg, true);
} catch (\Exception $e) {
	$msg = sprintf(
		'Não foi possível excluir a Cidade "%s"!',
		$cidade->getNome()
	);
	if (is_output('json')) {
		json($msg);
	}
	\Thunder::error($msg);
}
redirect('/gerenciar/cidade/');
