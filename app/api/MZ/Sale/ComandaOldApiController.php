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

/**
 * Allow application to serve system resources
 */
class ComandaOldApiController extends \MZ\Core\ApiController
{
    public function find()
    {
        if (!is_login()) {
            return $this->json()->error('Usuário não autenticado!');
        }
        if (!logged_provider()->has(Permissao::NOME_PEDIDOCOMANDA)) {
            return $this->json()->error('Você não tem permissão para acessar comandas');
        }
        $order = [
            'funcionario' => logged_provider()->getID()
        ];
        /* verifica se deve ordenar pelo número da comanda */
        if (isset($_GET['ordenar']) && $_GET['ordenar'] == 'comanda') {
            unset($order['funcionario']);
        }
        $condition = [
            'ativa' => 'Y',
            'pedidos' => true
        ];
        $comandas = Comanda::rawFindAll($condition, $order);
        $items = [];
        $pedido = new Pedido();
        $pedido->setCancelado('N');
        $obs_name = is_boolean_config('Vendas', 'Comanda.Observacao');
        foreach ($comandas as $item) {
            $pedido->setID($item['pedidoid']);
            $pedido->setEstado($item['estado']);
            $item['estado'] = $pedido->getEstadoSimples();
            if ($pedido->exists()) {
                $total = $pedido->findTotal();
                $item['produtos'] = $total['produtos'];
                $item['comissao'] = $total['comissao'];
                $item['servicos'] = ['total' => $total['servicos']];
                $item['descontos'] = $total['descontos'];
                $item['pago'] = $pedido->findPagamentoTotal();
            }
            if ($obs_name && trim($item['observacao']) != '') {
                $item['nome'] = $item['observacao'];
            }
            if (is_null($item['pedidoid'])) {
                unset($item['pedidoid']);
            }
            if (is_null($item['juntaid'])) {
                unset($item['juntaid']);
            }
            if (is_null($item['juntanome'])) {
                unset($item['juntanome']);
            }
            $items[] = $item;
        }
        return $this->json()->success(['comandas' => $items]);
    }

    /**
     * Get URL patterns associated with callback for use into router
     * @return array List of routes
     */
    public static function getRoutes()
    {
        return [
            [
                'name' => 'app_comanda_find',
                'path' => '/app/comanda/listar',
                'method' => 'GET',
                'controller' => 'find',
            ]
        ];
    }
}
