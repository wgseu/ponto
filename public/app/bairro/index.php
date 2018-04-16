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
use MZ\Location\Estado;
use MZ\Location\Bairro;

$estado_id = isset($_GET['estadoid']) ? $_GET['estadoid'] : null;
$estado = Estado::findByID($estado_id);
if (!$estado->exists()) {
    json('O estado não foi informado ou não existe!');
}
$cidade_id = isset($_GET['cidade']) ? trim($_GET['cidade']) : null;
$cidade = Cidade::findByEstadoIDNome($estado_id, $cidade_id);
if (!$cidade->exists()) {
    json('A cidade informada não existe!');
}
$bairros = Bairro::findAll(
    [
        'cidadeid' => $cidade->getID(),
        'search' => isset($_GET['nome']) ? $_GET['nome'] : null
    ],
    [],
    10
);
$items = [];
foreach ($bairros as $bairro) {
    $items[] = $bairro->publish();
}
json(['status' => 'ok', 'items' => $items]);
