<?php

namespace MZ\Coupon;

use MZ\Sale\Pedido;

/**
 * Cupon model
 */
class Queue extends Model
{
    
    /**
     * Customer that made the order
     * @var \MZ\Account\Cliente
     */
    private $customer;

    /**
     * Queue to print
     * @var \MZ\Sale\Pedido
     */
    private $order;

    /**
     * @param \Thermal\Printer
     */
    public function __construct($printer)
    {
      parent::__construct($printer);
      $this->loadTemplate('queue_ticket');
    }

    /**
     * Informs order
     * @param \MZ\Sale\Pedido $order order to print
     */
    public function setOrder($order)
    {
        $this->order = $order;
        $this->customer = $order->findClienteID();
        return $this;
    }

    protected function resolve($entry)
    {

        if ($entry == 'company.name') {
            return app()->getSystem()->getCompany()->getNome();
        }
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
            $cliente = $this->order->findClienteID();
            return $cliente->getNome();
        }
        return parent::resolve($entry);
    }
}
