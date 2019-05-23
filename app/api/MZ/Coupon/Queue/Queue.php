<?php

namespace Mz\Coupon\Queue;

use MZ\Coupon\Model;
use \MZ\Sale\Pedido;

/**
 * Cupon model
 */
class Queue extends Model
{
    /**
     * Queue to print
     * @var \MZ\Sale\Pedido
     */
    private $order;

    /**
     * Informs order
     * @param Pedido $order order to print
     * @return self
     */
    public function setOrder($order)
    {
        $this->order = $order;
        return $this;
    }

    protected function resolve($entry)
    {
        if ($entry == 'order.id') {
            return $this->order->getID();
        }
        if ($entry == 'queue.number') {
            return $this->order->getID() % 1000;
        }
        if ($entry == 'order.local') {
            if ($this->order->getTipo() == Pedido::TIPO_ENTREGA && is_null($this->order->getLocalizacaoID())) {
                return _t('pedido.tipo_viagem');
            }
            if ($this->order->getTipo() == Pedido::TIPO_MESA) {
                $mesa = $this->order->findMesaID();
                return $mesa->getNome();
            }
            if ($this->order->getTipo() == Pedido::TIPO_COMANDA) {
                $comanda = $this->order->findComandaID();
                return $comanda->getNome();
            }
            $tipos = Pedido::getTipoOptions();
            return $tipos[$this->order->getTipo()];
        }
        if ($entry == 'order.attendant') {
            $last_item = $this->items[count($this->items) - 1];
            $prestador = $last_item->findPrestadorID();
            $cliente = $prestador->findClienteID();
            return $cliente->getNome();
        }
        return parent::resolve($entry);
    }
}
