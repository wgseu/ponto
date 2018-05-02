<?php
/**
 * Copyright 2014 da MZ Software - MZ Desenvolvimento de Sistemas LTDA
 *
 * Este arquivo é parte do programa GrandChef - Sistema para Gerenciamento de Churrascarias, Bares e Restaurantes.
 * O GrandChef é um software proprietário; você não pode redistribuí-lo e/ou modificá-lo.
 * DISPOSIÇÕES GERAIS
 * O cliente não deverá remover qualquer identificação do produto, avisos de direitos autorais,
 * ou outros avisos ou restrições de propriedade do GrandChef.
 *
 * O cliente não deverá causar ou permitir a engenharia reversa, desmontagem,
 * ou descompilação do GrandChef.
 *
 * PROPRIEDADE DOS DIREITOS AUTORAIS DO PROGRAMA
 *
 * GrandChef é a especialidade do desenvolvedor e seus
 * licenciadores e é protegido por direitos autorais, segredos comerciais e outros direitos
 * de leis de propriedade.
 *
 * O Cliente adquire apenas o direito de usar o software e não adquire qualquer outros
 * direitos, expressos ou implícitos no GrandChef diferentes dos especificados nesta Licença.
 *
 * @author Equipe GrandChef <desenvolvimento@mzsw.com.br>
 */
require_once(dirname(__DIR__) . '/app.php');

use MZ\Location\Localizacao;
use MZ\System\Permissao;
use MZ\Util\Filter;

need_permission(Permissao::NOME_CADASTROCLIENTES, true);

$limite = isset($_GET['limite']) ? intval($_GET['limite']) : 10;
if ($limite > 100 || $limite < 1) {
    $limite = 10;
}
$condition = Filter::query($_GET);
unset($condition['ordem']);
$localizacao = new Localizacao($condition);
$cliente = $localizacao->findClienteID();
if (!$cliente->exists()) {
    $msg = 'O cliente não foi informado ou não existe!';
    json($msg);
}
$order = Filter::order(isset($_GET['ordem']) ? $_GET['ordem'] : '');
$count = Localizacao::count($condition);
list($pagesize, $offset, $pagestring) = pagestring($count, $limite);
$localizacoes = Localizacao::findAll($condition, $order, $pagesize, $offset);

$items = [];
foreach ($localizacoes as $_localizacao) {
    $items[] = $_localizacao->publish();
}
json(['status' => 'ok', 'items' => $items]);
