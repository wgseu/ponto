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
namespace MZ\Coupon\Order;

use MZ\Coupon\Model;
use MZ\Util\Mask;

/**
 * Coupon model
 */
class Receipt extends Model
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
     * Products of order
     * @var \MZ\Sale\ProdutoPedido[]
     */
    private $products;
    private $product_index;

    /**
     * Services of order
     * @var \MZ\Sale\ProdutoPedido[]
     */
    private $services;
    private $service_index;

    /**
     * Payments of order
     * @var \MZ\Payment\Pagamento[]
     */
    private $payments;
    private $payment_index;

    private $product_total;
    private $commission;
    private $service_total;
    private $discounts;
    private $payment_total;

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
        $this->loadTemplate('order_receipt');
    }

    /**
     * Informs order
     * @param \MZ\Sale\Pedido $order order to print
     */
    public function setOrder($order)
    {
        $this->order = $order;
        $this->customer = $order->findClienteID();
    }

    /**
     * Informs all products, services and discounts of order
     * @param \MZ\Sale\ProdutoPedido[] $items all products, services and discounts
     */
    public function setItems($items)
    {
        $this->product_index = 0;
        $this->products = [];

        $this->service_index = 0;
        $this->services = [];
        
        $this->product_total = 0;
        $this->commission = 0;
        $this->service_total = 0;
        $this->discounts = 0;
        foreach ($items as $item) {
            if ($item->getProdutoID() !== null) {
                $this->product_total += $item->getSubtotal();
                $this->commission += $item->getComissao();
                $this->products[] = $item;
            } elseif ($item->getPreco() < 0) {
                $this->discounts += $item->getSubtotal();
            } else {
                $this->service_total += $item->getSubtotal();
                $this->services[] = $item;
            }
        }
        return $this;
    }

    /**
     * Get current product item
     * @return \MZ\Sale\ProdutoPedido current product item
     */
    protected function getProduct()
    {
        return $this->products[$this->product_index];
    }

    /**
     * Find current product instance
     * @return \MZ\Product\Produto
     */
    protected function findProduct()
    {
        $item = $this->getProduct();
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
     * Get current service item
     * @return \MZ\Sale\ProdutoPedido current service item
     */
    protected function getService()
    {
        return $this->services[$this->service_index];
    }

    /**
     * Find current service instance
     * @return \MZ\Product\Servico
     */
    protected function findService()
    {
        $item = $this->getService();
        if (isset($this->cache['services'][$item->getServicoID()])) {
            return $this->cache['services'][$item->getServicoID()];
        }
        $service = $item->findServicoID();
        $this->cache['services'][$item->getServicoID()] = $service;
        return $service;
    }

    /**
     * Informs Order payments
     * @param \MZ\Payment\Pagamento[] $payments order payment list
     */
    public function setPayments($payments)
    {
        $this->payment_index = 0;
        $this->payments = $payments;

        $this->payment_total = 0;
        foreach ($payments as $payment) {
            $this->payment_total += $payment->getTotal();
        }
        return $this;
    }

    /**
     * Get current payment
     * @return \MZ\Payment\Pagamento current payment
     */
    protected function getPayment()
    {
        return $this->payments[$this->payment_index];
    }

    /**
     * Find current payment method
     * @return \MZ\Payment\FormaPagto
     */
    protected function findPaymentMethod()
    {
        $payment = $this->getPayment();
        if (isset($this->cache['methods'][$payment->getFormaPagtoID()])) {
            return $this->cache['methods'][$payment->getFormaPagtoID()];
        }
        $method = $payment->findFormaPagtoID();
        $this->cache['methods'][$payment->getFormaPagtoID()] = $method;
        return $method;
    }

    /**
     * Find current payment card
     * @return \MZ\Payment\Cartao
     */
    protected function findCard()
    {
        $payment = $this->getPayment();
        if (isset($this->cache['cards'][$payment->getCartaoID()])) {
            return $this->cache['cards'][$payment->getCartaoID()];
        }
        $card = $payment->findCartaoID();
        $this->cache['cards'][$payment->getCartaoID()] = $card;
        return $card;
    }

    protected function getSubtotal()
    {
        return $this->product_total + $this->commission + $this->service_total;
    }

    protected function getTotal()
    {
        return $this->getSubtotal() + $this->discounts;
    }

    protected function getRemaining()
    {
        return $this->getTotal() - $this->payment_total;
    }

    protected function getBalance()
    {
        return $this->payment_total - $this->getTotal();
    }

    /**
     * Check if resource is available for printing
     * @param string $resource resource name to check
     * @return bool true for available, false otherwise
     */
    protected function isAvailable($resource)
    {

        if ($resource == 'order.subtotal.many') {
            return count($this->services) > 0 || is_greater($this->commission, 0);
        }
        if ($resource == 'order.products.commission') {
            return is_greater($this->commission, 0);
        }
        if ($resource == 'order.services.many') {
            return count($this->services) > 1;
        }
        if ($resource == 'order.services.single') {
            return count($this->services) == 1;
        }
        if ($resource == 'order.discount') {
            return is_less($this->discounts, 0);
        }
        if ($resource == 'order.payments') {
            return count($this->payments) > 0;
        }
        if ($resource == 'order.payments.diff') {
            return count($this->payments) != 0 && !is_equal($this->getRemaining(), 0);
        }
        if ($resource == 'order.payments.[empty|paid]') {
            return count($this->payments) == 0 || is_equal($this->getRemaining(), 0);
        }
        if ($resource == 'order.payment.card') {
            return $this->getPayment()->getCartaoID() != 0;
        }
        if ($resource == 'order.payment.note') {
            return $this->getPayment()->getDetalhes() != null;
        }
        if ($resource == 'order.payments.many') {
            return count($this->payments) > 1;
        }
        if ($resource == 'order.payments.remaining') {
            return count($this->payments) > 0 && is_greater($this->getRemaining(), 0);
        }
        if ($resource == 'order.payments.balance') {
            return count($this->payments) > 0 && is_greater($this->getBalance(), 0);
        }
        return parent::isAvailable($resource);
    }

    protected function resolve($entry)
    {
        if ($entry == 'order.id') {
            return $this->order->getID();
        }
        if ($entry == 'order.product.id') {
            return $this->getProduct()->getProdutoID();
        }
        if ($entry == 'order.product.description') {
            $product = $this->findProduct();
            return $this->getProduct()->getDescricaoAtual($product);
        }
        if ($entry == 'order.product.price') {
            return Mask::money($this->getProduct()->getPreco());
        }
        if ($entry == 'order.product.quantity') {
            $product = $this->findProduct();
            $unit = $this->findUnit();
            return $this->getProduct()->getQuantidadeFormatada($product, $unit);
        }
        if ($entry == 'order.product.subtotal') {
            return Mask::money($this->getProduct()->getSubtotal());
        }
        if ($entry == 'order.products.subtotal') {
            return Mask::money($this->product_total);
        }
        if ($entry == 'order.products.commission') {
            return Mask::money($this->commission);
        }
        if ($entry == 'order.service.name') {
            return $this->findService()->getNome();
        }
        if ($entry == 'order.service.total') {
            return Mask::money($this->getProduct()->getSubtotal());
        }
        if ($entry == 'order.services.total') {
            return Mask::money($this->service_total);
        }
        if ($entry == 'order.subtotal') {
            return Mask::money($this->getSubtotal());
        }
        if ($entry == 'order.discount') {
            return Mask::money($this->discounts);
        }
        if ($entry == 'order.total') {
            return Mask::money($this->getTotal());
        }
        if ($entry == 'order.payment.type') {
            return $this->findPaymentMethod()->getDescricao();
        }
        if ($entry == 'order.payment.card') {
            return $this->findCard()->getDescricao();
        }
        if ($entry == 'order.payment.note') {
            return $this->getPayment()->getDetalhes();
        }
        if ($entry == 'order.payment.total') {
            return Mask::money($this->getPayment()->getTotal());
        }
        if ($entry == 'order.payments.total') {
            return Mask::money($this->payment_total);
        }
        if ($entry == 'order.payments.remaining') {
            return Mask::money($this->getRemaining());
        }
        if ($entry == 'order.payments.balance') {
            return Mask::money($this->getBalance());
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
            'order.services' => 2,
            'order.payments' => 3
        ];
        if (!isset($keys[$list])) {
            return parent::setCursor($list, $position);
        }
        $list_id = $keys[$list];
        if ($list_id == 1) {
            $this->product_index = max(0, min($position, count($this->products) - 1));
            return count($this->products);
        }
        if ($list_id == 2) {
            $this->service_index = max(0, min($position, count($this->services) - 1));
            return count($this->services);
        }
        $this->payment_index = max(0, min($position, count($this->payments) - 1));
        return count($this->payments);
    }
}
