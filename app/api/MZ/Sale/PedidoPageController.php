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

use MZ\System\Permissao;
use MZ\Util\Filter;
use MZ\Core\PageController;

/**
 * Allow application to serve system resources
 */
class PedidoPageController extends PageController
{
    public function find()
    {
        $this->needPermission([Permissao::NOME_PAGAMENTO]);

        $limite = max(1, min(100, $this->getRequest()->query->getInt('limite', 10)));
        $condition = Filter::query($this->getRequest()->query->all());
        unset($condition['ordem']);

        $estado = $this->getRequest()->query->get('estado');
        if ($estado == 'Cancelado') {
            $condition['cancelado'] = 'Y';
            unset($condition['estado']);
        } elseif ($estado == 'Valido') {
            $condition['cancelado'] = 'N';
            unset($condition['estado']);
        } elseif ($estado != '') {
            $condition['cancelado'] = 'N';
        }

        $pedido = new Pedido($condition);
        $order = Filter::order($this->getRequest()->query->get('ordem', ''));
        $count = Pedido::count($condition);
        $page = max(1, $this->getRequest()->query->getInt('pagina', 1));
        $pager = new \Pager($count, $limite, $page, 'pagina');
        $pagination = $pager->genPages();
        $pedidos = Pedido::findAll($condition, $order, $limite, $pager->offset);

        if ($this->isJson()) {
            $items = [];
            foreach ($pedidos as $_pedido) {
                $items[] = $_pedido->publish(app()->auth->provider);
            }
            return $this->json()->success(['items' => $items]);
        }

        $_tipo_names = Pedido::getTipoOptions();
        $_estado_names = ['Valido' => 'Válido'] + Pedido::getEstadoOptions() + ['Cancelado' => 'Cancelado'];

        $_pedido_icon = [
            'Mesa' => 0,
            'Comanda' => 16,
            'Avulso' => 32,
            'Entrega' => 48,
        ];
        $_funcionario = $pedido->findPrestadorID();
        $_cliente = $pedido->findClienteID();
        return $this->view('gerenciar_pedido_index', get_defined_vars());
    }

    /**
     * Get URL patterns associated with callback for use into router
     * @return array List of routes
     */
    public static function getRoutes()
    {
        return [
            [
                'name' => 'pedido_find',
                'path' => '/gerenciar/pedido/',
                'method' => 'GET',
                'controller' => 'find',
            ],
        ];
    }
}
