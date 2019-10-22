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

namespace MZ\Environment;

use MZ\Sale\Comanda;
use MZ\System\Permissao;
use MZ\Sale\Pedido;

/**
 * Allow application to serve system resources
 */
class MesaOldApiController extends \MZ\Core\ApiController
{
    public function find()
    {
        if (!app()->getAuthentication()->isLogin()) {
            return $this->json()->error('Usuário não autenticado!');
        }
        if (!app()->auth->has([Permissao::NOME_PEDIDOMESA])) {
            return $this->json()->error('Você não tem permissão para acessar mesas');
        }
        $order = [
            'funcionario' => app()->auth->provider->getID()
        ];
        /* verifica se deve ordenar pelo número da mesa */
        if ($this->getRequest()->query->get('ordenar') == 'mesa') {
            unset($order['funcionario']);
        }
        $condition = [
            'ativa' => 'Y',
            'pedidos' => true
        ];
        $mesas = Mesa::rawFindAll($condition, $order);
        $condition['mesas'] = null;
        $comandas = Comanda::rawFindAll($condition, $order);
        $grupos = [];
        foreach ($comandas as $comanda) {
            if (!isset($grupos[$comanda['juntaid']])) {
                $grupos[$comanda['juntaid']] = [];
            }
            $grupos[$comanda['juntaid']][] = $comanda;
        }
        $items = [];
        $pedido = new Pedido();
        $pedido->setCancelado('N');
        foreach ($mesas as $item) {
            $pedido->setID($item['pedidoid']);
            $pedido->setMesaID($item['id']);
            $pedido->setEstado($item['estado']);
            $item['estado'] = $pedido->getEstadoSimples();
            if ($pedido->exists() || isset($grupos[$item['id']])) {
                $total = $pedido->findTotal();
                if (!$pedido->exists()) {
                    $item['estado'] = 'ocupado';
                    $item['comandas'] = count($grupos[$item['id']]);
                }
                $item['produtos'] = $total['produtos'];
                $item['comissao'] = $total['comissao'];
                $item['servicos'] = ['total' => $total['servicos']];
                $item['descontos'] = $total['descontos'];
                $item['pago'] = $pedido->findPagamentoTotal();
            }
            if (is_null($item['pedidoid'])) {
                unset($item['pedidoid']);
            }
            if (is_null($item['cliente'])) {
                unset($item['cliente']);
            }
            $items[] = $item;
        }
        return $this->json()->success(['mesas' => $items]);
    }

    /**
     * Get URL patterns associated with callback for use into router
     * @return array List of routes
     */
    public static function getRoutes()
    {
        return [
            [
                'name' => 'app_mesa_find',
                'path' => '/app/mesa/listar',
                'method' => 'GET',
                'controller' => 'find',
            ]
        ];
    }
}
