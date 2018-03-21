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
require_once(dirname(dirname(__DIR__)) . '/app.php');

use MZ\Location\Cidade;

$limite = isset($_GET['limite'])?intval($_GET['limite']):10;
if ($limite > 100 || $limite < 1) {
	$limite = 10;
}
$condition = \MZ\Util\Filter::query($_GET);
unset($condition['ordem']);
$order = \MZ\Util\Filter::order(isset($_GET['ordem'])?$_GET['ordem']:'');
$cidades = Cidade::findAll($condition, $order, $limite);

$items = [];
foreach ($cidades as $cidade) {
    $items[] = $cidade->publish();
}
json(['status' => 'ok', 'items' => $items]);
