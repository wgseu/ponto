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
namespace MZ\Sale;

use MZ\Product\Produto;
use MZ\Product\Servico;
use MZ\System\Permissao;
use MZ\Util\Filter;

/**
 * Allow application to serve system resources
 */
class ProdutoPedidoPageController extends \MZ\Core\Controller
{
    public function find()
    {
        need_permission(Permissao::NOME_PAGAMENTO, is_output('json'));

        $limite = isset($_GET['limite']) ? intval($_GET['limite']) : 10;
        if ($limite > 100 || $limite < 1) {
            $limite = 10;
        }
        $condition = Filter::query($_GET);
        unset($condition['ordem']);
        if (isset($condition['estado'])) {
            if ($condition['estado'] == 'Valido') {
                unset($condition['estado']);
                $condition['cancelado'] = 'N';
            } elseif ($condition['estado'] == 'Cancelado') {
                unset($condition['estado']);
                $condition['cancelado'] = 'Y';
            }
        }
        if (isset($condition['tipo'])) {
            if ($condition['tipo'] == 'Produtos') {
                unset($condition['tipo']);
                $condition['!produtoid'] = null;
            } elseif ($condition['tipo'] == 'Servico') {
                unset($condition['tipo']);
                $condition['!servicoid'] = null;
            } elseif ($condition['tipo'] == 'Desconto') {
                unset($condition['tipo']);
                $condition['ate_preco'] = 0;
                $condition['!servicoid'] = null;
            } elseif (array_key_exists($condition['tipo'], Produto::getTipoOptions())) {
                $condition['produto'] = $condition['tipo'];
                unset($condition['tipo']);
            } elseif (array_key_exists($condition['tipo'], Servico::getTipoOptions())) {
                $condition['servico'] = $condition['tipo'];
                unset($condition['tipo']);
            }
        }
        $produto_pedido = new ProdutoPedido($condition);
        $order = Filter::order(isset($_GET['ordem']) ? $_GET['ordem'] : '');
        $count = ProdutoPedido::count($condition);
        list($pagesize, $offset, $pagination) = pagestring($count, $limite);
        $itens_do_pedido = ProdutoPedido::findAll($condition, $order, $pagesize, $offset);

        if (is_output('json')) {
            $items = [];
            foreach ($itens_do_pedido as $_produto_pedido) {
                $items[] = $_produto_pedido->publish();
            }
            return $this->json()->success(['items' => $items]);
        }

        $_modulo_names = Pedido::getTipoOptions();

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

        return $this->view('gerenciar_produto_pedido_index', get_defined_vars());
    }

    /**
     * Get URL patterns associated with callback for use into router
     * @return array List of routes
     */
    public static function getRoutes()
    {
        return [
            [
                'name' => 'produto_pedido_find',
                'path' => '/gerenciar/produto_pedido/',
                'method' => 'GET',
                'controller' => 'find',
            ],
        ];
    }
}