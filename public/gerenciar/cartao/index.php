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

need_permission(PermissaoNome::CADASTROCARTOES, is_output('json'));

$limite = isset($_GET['limite'])?intval($_GET['limite']):10;
if ($limite > 100 || $limite < 1) {
	$limite = 10;
}

$count = ZCartao::getCount($_GET['query'], $_GET['estado']);
list($pagesize, $offset, $pagestring) = pagestring($count, $limite);
$cartoes = ZCartao::getTodos($_GET['query'], $_GET['estado'], $offset, $pagesize);

if (is_output('json')) {
	$items = array();
	foreach ($cartoes as $cartao) {
		$items[] = $cartao->toArray();
	}
	json(array('status' => 'ok', 'cartoes' => $items));
}

$estados = array(
    'Y' => 'Ativos',
    'N' => 'Inativos',
);
$_imagens = ZCartao::getImages();
include template('gerenciar_cartao_index');
