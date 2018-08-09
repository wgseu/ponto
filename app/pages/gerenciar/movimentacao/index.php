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
use MZ\Session\Caixa;
use MZ\Session\Movimentacao;
use MZ\System\Permissao;
use MZ\Util\Filter;

need_permission(Permissao::NOME_ABRIRCAIXA, is_output('json'));

$limite = isset($_GET['limite']) ? intval($_GET['limite']) : 10;
if ($limite > 100 || $limite < 1) {
    $limite = 10;
}
$condition = Filter::query($_GET);
unset($condition['ordem']);
$movimentacao = new Movimentacao($condition);
$order = Filter::order(isset($_GET['ordem']) ? $_GET['ordem'] : '');
$count = Movimentacao::count($condition);
list($pagesize, $offset, $pagination) = pagestring($count, $limite);
$movimentacoes = Movimentacao::findAll($condition, $order, $pagesize, $offset);

if (is_output('json')) {
    $items = [];
    foreach ($movimentacoes as $_movimentacao) {
        $items[] = $_movimentacao->publish();
    }
    json(['status' => 'ok', 'items' => $items]);
}

$_movimentacao_icon = [
    'Y' => 0,
    'N' => 16,
];

$estados = [
    'Y' => 'Aberto',
    'N' => 'Fechado',
];
$caixas = Caixa::findAll();
$_caixa_names = [];
foreach ($caixas as $caixa) {
    $_caixa_names[$caixa->getID()] = $caixa->getDescricao();
}
$_funcionario = $movimentacao->findFuncionarioAberturaID();
return $app->getResponse()->output('gerenciar_movimentacao_index');
