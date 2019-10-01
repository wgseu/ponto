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
namespace MZ\Association;

use MZ\Sale\Pedido;

abstract class Integrator
{
    const ESTADO_CANCELADO = 'Cancelado';

    /**
     * Integration
     */
    public $integracao;

    /**
     * @var \MZ\Sale\Pedido
     */
    public $order;

    /**
     * Integration order code
     */
    protected $code;

    /**
     * Load order information and return data array
     * @return array
     */
    abstract public function load();

    /**
     * Store integrated order for post sync status
     * @return array changes to submit to the web API
     */
    public function store()
    {
        $change = ['id' => $this->order->getID(), 'code' => $this->code, 'estado' => $this->order->getEstado()];
        $dados = $this->integracao->read() ?: [];
        $pedidos = isset($dados['pedidos']) ? $dados['pedidos']: [];
        $pedidos[$this->order->getID()] = $change;
        $dados['pedidos'] = $pedidos;
        $this->integracao->write($dados);
        return [$change];
    }

    /**
     * Retrive orders changes to submit to web API
     * @param int $limit limit to get first changes
     * @return array changes to submit to the web API
     */
    public function changes($limit = null)
    {
        $dados = $this->integracao->read() ?: [];
        $pedidos = isset($dados['pedidos']) ? $dados['pedidos']: [];
        $changes = [];
        foreach ($pedidos as $pedido_id => $change) {
            $pedido = Pedido::findByID($pedido_id);
            if (!$pedido->exists()) {
                continue;
            }
            if ($pedido->isCancelado()) {
                $change['estado'] = self::ESTADO_CANCELADO;
                $changes[] = $change;
            } elseif ($pedido->getEstado() != $change['estado']) {
                $change['estado'] = $pedido->getEstado();
                $changes[] = $change;
            }
            if (count($changes) >= $limit) {
                break;
            }
        }
        return $changes;
    }

    /**
     * Apply submited changes to web API to local storage
     * @param array $updates list of changes to apply
     * @return boolean true when any changes was applied
     */
    public function apply($updates)
    {
        $changes = 0;
        $dados = $this->integracao->read() ?: [];
        $pedidos = isset($dados['pedidos']) ? $dados['pedidos']: [];
        foreach ($updates as $update) {
            if (!isset($pedidos[$update['id']])) {
                continue;
            }
            $change = $pedidos[$update['id']];
            if ($update['estado'] == Pedido::ESTADO_FINALIZADO ||
                $update['estado'] == self::ESTADO_CANCELADO
            ) {
                unset($pedidos[$update['id']]);
                $changes++;
                continue;
            }
            $change['estado'] = $update['estado'];
            $pedidos[$update['id']] = $change;
            $changes++;
        }
        if ($changes > 0) {
            $dados['pedidos'] = $pedidos;
            $this->integracao->write($dados);
        }
        return $changes;
    }
}
