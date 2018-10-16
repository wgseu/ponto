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
namespace MZ\Coupon\Service;

use MZ\Coupon\Model;
use MZ\Util\Mask;
use \MZ\Sale\Pedido;

/**
 * Coupon model
 */
class Order extends Model
{
    /**
     * Order to print
     * @var \MZ\Sale\Pedido
     */
    private $order;

    /**
     * Customer that made the order
     * @var \MZ\Account\Cliente
     */
    private $customer;

    /**
     * Order items
     * @var \MZ\Sale\Item[]
     */
    private $items;
    private $item_index;

    /**
     * Printing sector
     * @var \MZ\Environment\Setor
     */
    private $sector;

    /**
     * Informs if this coupom is a ticket
     * @var boolean
     */
    private $ticket;

    /**
     * Ticket number
     * @var int
     */
    private $number;

    /**
     * Token for ticket validation
     * @var string
     */
    private $token;

    /**
     * Cache database fetches
     * @var mixed[]
     */
    private $cache;

    /**
     * Constructor for Receipt
     * @param \Thermal\Printer $printer printer to print coupon
     */
    public function __construct($printer)
    {
        parent::__construct($printer);
        $this->cache = [];
        $this->loadTemplate('service_order');
    }

    /**
     * Simple cache for database fetches
     * @param mixed[] $cache
     * @return self
     */
    public function setCache($cache)
    {
        $this->cache = $cache;
        return $this;
    }

    /**
     * Informs order
     * @param Pedido $order order to print
     * @return self
     */
    public function setOrder($order)
    {
        $this->order = $order;
        $this->customer = $order->findClienteID();
        return $this;
    }

    /**
     * Informs all products, services and discounts of order
     * @param \MZ\Sale\Item[] $items all products, services and discounts
     * @return self
     */
    public function setItems($items)
    {
        $this->item_index = 0;
        $this->items = $items;
        return $this;
    }

    /**
     * Get current product item
     * @return \MZ\Sale\Item current product item
     */
    protected function getItem()
    {
        return $this->items[$this->item_index];
    }

    /**
     * @param \MZ\Environment\Setor $sector
     * @return self
     */
    public function setSector($sector)
    {
        $this->sector = $sector;
        return $this;
    }

    /**
     * Informs if this coupom is a ticket
     * @param boolean $ticket
     * @return self
     */
    public function setTicket($ticket)
    {
        $this->ticket = $ticket;
        return $this;
    }

    /**
     * Ticket number
     * @param int $number
     * @return self
     */
    public function setNumber($number)
    {
        $this->number = $number;
        return $this;
    }

    /**
     * @param string $token for ticket validation
     * @return self
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * Find current product instance
     * @return \MZ\Product\Produto
     */
    protected function findProduct()
    {
        $item = $this->getItem();
        if (isset($this->cache['products'][$item->getProdutoID()])) {
            return $this->cache['products'][$item->getProdutoID()];
        }
        $product = $item->findProdutoID();
        $this->cache['products'][$item->getProdutoID()] = $product;
        return $product;
    }

    /**
     * Find current product unit
     * @return \MZ\Product\Unidade
     */
    protected function findUnit()
    {
        $product = $this->findProduct();
        if (isset($this->cache['units'][$product->getUnidadeID()])) {
            return $this->cache['units'][$product->getUnidadeID()];
        }
        $unit = $product->findUnidadeID();
        $this->cache['units'][$product->getUnidadeID()] = $unit;
        return $unit;
    }

    /**
     * Check if resource is available for printing
     * @param string $resource resource name to check
     * @return bool true for available, false otherwise
     */
    protected function isAvailable($resource)
    {
        if ($resource == 'service.product.code') {
            return is_boolean_config('Imprimir', 'Cozinha.Produto.Codigo');
        }
        if ($resource == 'service.balance') {
            return is_boolean_config('Imprimir', 'Cozinha.Saldo');
        }
        if ($resource == 'service.ticket') {
            return $this->ticket;
        }
        return parent::isAvailable($resource);
    }

    protected function resolve($entry)
    {
        if ($entry == 'printing.sector') {
            return $this->sector->getNome();
        }
        if ($entry == 'order.id') {
            return $this->order->getID();
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
        if ($entry == 'order.last_attendant') {
            $last_item = $this->items[count($this->items) - 1];
            $prestador = $last_item->findPrestadorID();
            $cliente = $prestador->findClienteID();
            return $cliente->getNome();
        }
        if ($entry == 'order.product.code') {
            $product = $this->findProduct();
            return $product->getCodigo();
        }
        if ($entry == 'order.product.description') {
            $product = $this->findProduct();
            return $this->getItem()->getDescricaoAtual($product);
        }
        if ($entry == 'order.product.quantity') {
            $product = $this->findProduct();
            $unit = $this->findUnit();
            return $this->getItem()->getQuantidadeFormatada($product, $unit);
        }
        if ($entry == 'order.payments.balance') {
            return Mask::money($this->order->getSaldo());
        }
        if ($entry == 'ticket.number') {
            return $this->number;
        }
        if ($entry == 'ticket.token') {
            return $this->token;
        }
        return parent::resolve($entry);
    }

    /**
     * @param string $list list name
     * @param int $position set index of current item
     */
    protected function setCursor($list, $position)
    {
        $keys = [
            'order.products' => 1,
        ];
        if (!isset($keys[$list])) {
            return parent::setCursor($list, $position);
        }
        $this->item_index = max(0, min($position, count($this->items) - 1));
        return count($this->items);
    }
}
