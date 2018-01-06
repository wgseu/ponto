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

use MZ\Wallet\Banco;

need_permission(PermissaoNome::CADASTROBANCOS, is_output('json'));

$limite = trim($_GET['limite']);
if (!is_numeric($limite) || $limite > 100 || $limite < 1) {
    $limite = 10;
}
$condition = array('query' => $_GET['query']);
$count = Banco::count($condition);
list($pagesize, $offset, $pagestring) = pagestring($count, $limite);
$bancos = Banco::findAll($condition, array(), $pagesize, $offset);

if (is_output('json')) {
    $_bancos = array();
    foreach ($bancos as $banco) {
        $_bancos[] = $banco->toArray();
    }
    json('items', $_bancos);
}

include template('gerenciar_banco_index');
