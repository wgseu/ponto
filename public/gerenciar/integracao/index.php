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

use MZ\System\Integracao;
use MZ\Util\Filter;

need_permission(\PermissaoNome::ALTERARCONFIGURACOES, is_output('json'));

$limite = isset($_GET['limite'])?intval($_GET['limite']):10;
if ($limite > 100 || $limite < 1) {
    $limite = 10;
}
$condition = array_intersect_key($_GET, array_flip(array('query')));
$condition = Filter::query($condition);
$order = Filter::orderBy(isset($_GET['ordem'])?$_GET['ordem']:'');
$count = Integracao::count($condition);
list($pagesize, $offset, $pagestring) = pagestring($count, $limite);
$integracoes = Integracao::findAll($condition, $order, $pagesize, $offset);

if (is_output('json')) {
    $items = array();
    foreach ($integracoes as $integracao) {
        $items[] = $integracao->publish();
    }
    json(array('status' => 'ok', 'items' => $items));
}

include template('gerenciar_integracao_index');
