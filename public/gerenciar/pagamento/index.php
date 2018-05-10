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

use MZ\Sale\Pedido;
use MZ\Payment\Pagamento;
use MZ\System\Permissao;
use MZ\Util\Filter;
use MZ\Wallet\Carteira;
use MZ\Payment\Cartao;
use MZ\Payment\FormaPagto;

need_permission(Permissao::NOME_PAGAMENTO, is_output('json'));

$limite = isset($_GET['limite']) ? intval($_GET['limite']) : 10;
if ($limite > 100 || $limite < 1) {
    $limite = 10;
}
$condition = Filter::query($_GET);
unset($condition['ordem']);
$pagamento = new Pagamento($condition);
$order = Filter::order(isset($_GET['ordem']) ? $_GET['ordem'] : '');
$count = Pagamento::count($condition);
list($pagesize, $offset, $pagination) = pagestring($count, $limite);
$pagamentos = Pagamento::findAll($condition, $order, $pagesize, $offset);

if (is_output('json')) {
    $items = [];
    foreach ($pagamentos as $_pagamento) {
        $items[] = $_pagamento->publish();
    }
    json(['status' => 'ok', 'items' => $items]);
}

$_tipo_names = Pedido::getTipoOptions();
$_estado_names = [
    'Valido' => 'Válido',
    'Ativo' => 'Ativo',
    'Espera' => 'Em espera',
    'Cancelado' => 'Cancelado',
];

$_pagamento_icon = [
    'Dinheiro' => 0,
    'Cartao' => 16,
    'Cheque' => 32,
    'Conta' => 48,
    'Credito' => 64,
    'Transferencia' => 80,
];

$formas_de_pagamento = FormaPagto::findAll();
$_forma_names = [];
foreach ($formas_de_pagamento as $forma) {
    $_forma_names[$forma->getID()] = $forma->getDescricao();
}
$cartoes = Cartao::findAll();
$_cartao_names = [];
foreach ($cartoes as $cartao) {
    $_cartao_names[$cartao->getID()] = $cartao->getDescricao();
}
$carteiras = Carteira::findAll();
$_carteira_names = [];
foreach ($carteiras as $carteira) {
    $_carteira_names[$carteira->getID()] = $carteira->getDescricao();
}
$_funcionario = $pagamento->findFuncionarioID();
$app->getResponse('html')->output('gerenciar_pagamento_index');
