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
require_once(dirname(dirname(dirname(__FILE__))) . '/app.php');

$estado_id = $_GET['estadoid'];
$estado = ZEstado::getPeloID($estado_id);
if (is_null($estado->getID())) {
    json('O estado não foi informado ou não existe!');
}
$cidade = ZCidade::getPeloEstadoIDNome($estado_id, trim($_GET['cidade']));
if (is_null($cidade->getID())) {
    json('A cidade "' . $_GET['cidade'] . '" não existe!');
}
$bairros = ZBairro::getTodosDaCidadeID($cidade->getID(), $_GET['nome'], 0, 10);
$_bairros = array();
foreach ($bairros as $bairro) {
    $_bairros[] = $bairro->toArray();
}
json(array('status' => 'ok', 'items' => $_bairros));