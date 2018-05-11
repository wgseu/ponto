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

use MZ\Account\Classificacao;
use MZ\System\Permissao;
use MZ\Util\Filter;

need_permission(Permissao::NOME_CADASTROCONTAS, is_output('json'));

$limite = isset($_GET['limite']) ? intval($_GET['limite']) : 10;
if ($limite > 100 || $limite < 1) {
    $limite = 10;
}
$condition = Filter::query($_GET);
unset($condition['ordem']);
if (isset($condition['classificacaoid']) && intval($condition['classificacaoid']) < 0) {
    unset($condition['classificacaoid']);
} elseif (array_key_exists('classificacaoid', $_GET)) {
    $condition['classificacaoid'] = isset($condition['classificacaoid']) ? $condition['classificacaoid'] : null;
}
$classificacao = new Classificacao($condition);
$order = Filter::order(isset($_GET['ordem']) ? $_GET['ordem'] : '');
$count = Classificacao::count($condition);
list($pagesize, $offset, $pagination) = pagestring($count, $limite);
$classificacoes = Classificacao::findAll($condition, $order, $pagesize, $offset);

if (is_output('json')) {
    $items = [];
    foreach ($classificacoes as $_classificacao) {
        $items[] = $_classificacao->publish();
    }
    json(['status' => 'ok', 'items' => $items]);
}

$classificacoes_sup = Classificacao::findAll(['classificacaoid' => null]);
$_classificacao_names = [];
foreach ($classificacoes_sup as $classificacao) {
    $_classificacao_names[$classificacao->getID()] = $classificacao->getDescricao();
}
$app->getResponse('html')->output('gerenciar_classificacao_index');
