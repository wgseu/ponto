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

use MZ\Location\Localizacao;

need_permission(\Permissao::NOME_CADASTROCLIENTES, true);
$id = isset($_GET['id'])?$_GET['id']:null;
$localizacao = Localizacao::findByID($id);
if (!$localizacao->exists()) {
	$msg = 'Não existe Localização com o ID informado!';
	json($msg);
}
try {
	$localizacao->delete();
	$localizacao->clean(new Localizacao());
	$msg = sprintf(
		'Localização "%s" excluída com sucesso!',
		$localizacao->getApelido() ?: $localizacao->getLogradouro()
	);
	json('msg', $msg);
} catch (\Exception $e) {
	$msg = sprintf(
		'Não foi possível excluir a Localização "%s"!',
		$localizacao->getApelido() ?: $localizacao->getLogradouro()
	);
	json($msg);
}
