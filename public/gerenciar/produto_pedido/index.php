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

use MZ\Sale\ProdutoPedido;
use MZ\Sale\Pedido;
use MZ\Product\Produto;
use MZ\Product\Servico;
use MZ\System\Permissao;
use MZ\Util\Filter;

need_permission(Permissao::NOME_PAGAMENTO, is_output('json'));

$limite = isset($_GET['limite']) ? intval($_GET['limite']) : 10;
if ($limite > 100 || $limite < 1) {
    $limite = 10;
}
$condition = Filter::query($_GET);
unset($condition['ordem']);
$produto_pedido = new ProdutoPedido($condition);
$order = Filter::order(isset($_GET['ordem']) ? $_GET['ordem'] : '');
$count = ProdutoPedido::count($condition);
list($pagesize, $offset, $pagestring) = pagestring($count, $limite);
$itens_do_pedido = ProdutoPedido::findAll($condition, $order, $pagesize, $offset);

if (is_output('json')) {
    $items = [];
    foreach ($itens_do_pedido as $_produto_pedido) {
        $items[] = $_produto_pedido->publish();
    }
    json(['status' => 'ok', 'items' => $items]);
}

$_estado_names = ['Valido' => 'Válido'] +
    ProdutoPedido::getEstadoOptions() +
    ['Cancelado' => 'Cancelado'];

$_tipo_names = ['Produtos' => 'Todos os produtos'] +
    Produto::getTipoOptions() +
    ['Servico' => 'Todos os serviços'] +
    Servico::getTipoOptions() +
    ['Desconto' => 'Desconto'];

$_pedido_icon = [
    'Mesa' => 0,
    'Comanda' => 16,
    'Avulso' => 32,
    'Entrega' => 48,
];

$_funcionario = $produto_pedido->findFuncionarioID();
$_produto = $produto_pedido->findProdutoID();

$app->getResponse('html')->output('gerenciar_produto_pedido_index');
