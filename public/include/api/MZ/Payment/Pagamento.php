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
 * @author  Francimar Alves <mazinsw@gmail.com>
 */
namespace MZ\Payment;

use MZ\Util\Filter;
use MZ\Util\Validator;

/**
 * Pagamentos de contas e pedidos
 */
class Pagamento extends \MZ\Database\Helper
{

    /**
     * Identificador do pagamento
     */
    private $id;
    /**
     * Carteira de destino do valor
     */
    private $carteira_id;
    /**
     * Movimentação do caixa quando for pagamento de pedido ou quando a conta
     * for paga do caixa
     */
    private $movimentacao_id;
    /**
     * Funcionário que lançou o pagamento no sistema
     */
    private $funcionario_id;
    /**
     * Forma da pagamento do pedido ou conta
     */
    private $forma_pagto_id;
    /**
     * Pedido que foi pago
     */
    private $pedido_id;
    /**
     * Conta que foi paga/recebida
     */
    private $pagto_conta_id;
    /**
     * Cartão em que foi pago, para forma de pagamento em cartão
     */
    private $cartao_id;
    /**
     * Cheque em que foi pago
     */
    private $cheque_id;
    /**
     * Conta que foi utilizada como pagamento do pedido
     */
    private $conta_id;
    /**
     * Crédito que foi utilizado para pagar o pedido
     */
    private $credito_id;
    /**
     * Total do pagamento, não inclui juros, negativo para trocos e pagamento
     * de contas
     */
    private $total;
    /**
     * Quantidade de parcelas quando pagamento parcelado
     */
    private $parcelas;
    /**
     * Valor da parcela em caso de parcelamento
     */
    private $valor_parcela;
    /**
     * Total de taxas cobrada por financeiras e outros (Não negativo)
     */
    private $taxas;
    /**
     * Detalhes do pagamento
     */
    private $detalhes;
    /**
     * Informa se o pagamento foi cancelado
     */
    private $cancelado;
    /**
     * Informa se o pagamento está efetivado(Sim) ou apenas foi lançado(Não)
     */
    private $ativo;
    /**
     * Data de compensação do pagamento
     */
    private $data_compensacao;
    /**
     * Data de hora do lançamento do pagamento
     */
    private $data_hora;

    /**
     * Constructor for a new empty instance of Pagamento
     * @param array $pagamento All field and values to fill the instance
     */
    public function __construct($pagamento = [])
    {
        parent::__construct($pagamento);
    }

    /**
     * Identificador do pagamento
     * @return mixed ID of Pagamento
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param  mixed $id new value for ID
     * @return Pagamento Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Carteira de destino do valor
     * @return mixed Carteira of Pagamento
     */
    public function getCarteiraID()
    {
        return $this->carteira_id;
    }

    /**
     * Set CarteiraID value to new on param
     * @param  mixed $carteira_id new value for CarteiraID
     * @return Pagamento Self instance
     */
    public function setCarteiraID($carteira_id)
    {
        $this->carteira_id = $carteira_id;
        return $this;
    }

    /**
     * Movimentação do caixa quando for pagamento de pedido ou quando a conta
     * for paga do caixa
     * @return mixed Movimentação of Pagamento
     */
    public function getMovimentacaoID()
    {
        return $this->movimentacao_id;
    }

    /**
     * Set MovimentacaoID value to new on param
     * @param  mixed $movimentacao_id new value for MovimentacaoID
     * @return Pagamento Self instance
     */
    public function setMovimentacaoID($movimentacao_id)
    {
        $this->movimentacao_id = $movimentacao_id;
        return $this;
    }

    /**
     * Funcionário que lançou o pagamento no sistema
     * @return mixed Funcionário of Pagamento
     */
    public function getFuncionarioID()
    {
        return $this->funcionario_id;
    }

    /**
     * Set FuncionarioID value to new on param
     * @param  mixed $funcionario_id new value for FuncionarioID
     * @return Pagamento Self instance
     */
    public function setFuncionarioID($funcionario_id)
    {
        $this->funcionario_id = $funcionario_id;
        return $this;
    }

    /**
     * Forma da pagamento do pedido ou conta
     * @return mixed Forma de pagamento of Pagamento
     */
    public function getFormaPagtoID()
    {
        return $this->forma_pagto_id;
    }

    /**
     * Set FormaPagtoID value to new on param
     * @param  mixed $forma_pagto_id new value for FormaPagtoID
     * @return Pagamento Self instance
     */
    public function setFormaPagtoID($forma_pagto_id)
    {
        $this->forma_pagto_id = $forma_pagto_id;
        return $this;
    }

    /**
     * Pedido que foi pago
     * @return mixed Pedido of Pagamento
     */
    public function getPedidoID()
    {
        return $this->pedido_id;
    }

    /**
     * Set PedidoID value to new on param
     * @param  mixed $pedido_id new value for PedidoID
     * @return Pagamento Self instance
     */
    public function setPedidoID($pedido_id)
    {
        $this->pedido_id = $pedido_id;
        return $this;
    }

    /**
     * Conta que foi paga/recebida
     * @return mixed Conta of Pagamento
     */
    public function getPagtoContaID()
    {
        return $this->pagto_conta_id;
    }

    /**
     * Set PagtoContaID value to new on param
     * @param  mixed $pagto_conta_id new value for PagtoContaID
     * @return Pagamento Self instance
     */
    public function setPagtoContaID($pagto_conta_id)
    {
        $this->pagto_conta_id = $pagto_conta_id;
        return $this;
    }

    /**
     * Cartão em que foi pago, para forma de pagamento em cartão
     * @return mixed Cartão of Pagamento
     */
    public function getCartaoID()
    {
        return $this->cartao_id;
    }

    /**
     * Set CartaoID value to new on param
     * @param  mixed $cartao_id new value for CartaoID
     * @return Pagamento Self instance
     */
    public function setCartaoID($cartao_id)
    {
        $this->cartao_id = $cartao_id;
        return $this;
    }

    /**
     * Cheque em que foi pago
     * @return mixed Cheque of Pagamento
     */
    public function getChequeID()
    {
        return $this->cheque_id;
    }

    /**
     * Set ChequeID value to new on param
     * @param  mixed $cheque_id new value for ChequeID
     * @return Pagamento Self instance
     */
    public function setChequeID($cheque_id)
    {
        $this->cheque_id = $cheque_id;
        return $this;
    }

    /**
     * Conta que foi utilizada como pagamento do pedido
     * @return mixed Conta pedido of Pagamento
     */
    public function getContaID()
    {
        return $this->conta_id;
    }

    /**
     * Set ContaID value to new on param
     * @param  mixed $conta_id new value for ContaID
     * @return Pagamento Self instance
     */
    public function setContaID($conta_id)
    {
        $this->conta_id = $conta_id;
        return $this;
    }

    /**
     * Crédito que foi utilizado para pagar o pedido
     * @return mixed Crédito of Pagamento
     */
    public function getCreditoID()
    {
        return $this->credito_id;
    }

    /**
     * Set CreditoID value to new on param
     * @param  mixed $credito_id new value for CreditoID
     * @return Pagamento Self instance
     */
    public function setCreditoID($credito_id)
    {
        $this->credito_id = $credito_id;
        return $this;
    }

    /**
     * Total do pagamento, não inclui juros, negativo para trocos e pagamento
     * de contas
     * @return mixed Total of Pagamento
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set Total value to new on param
     * @param  mixed $total new value for Total
     * @return Pagamento Self instance
     */
    public function setTotal($total)
    {
        $this->total = $total;
        return $this;
    }

    /**
     * Quantidade de parcelas quando pagamento parcelado
     * @return mixed Parcelas of Pagamento
     */
    public function getParcelas()
    {
        return $this->parcelas;
    }

    /**
     * Set Parcelas value to new on param
     * @param  mixed $parcelas new value for Parcelas
     * @return Pagamento Self instance
     */
    public function setParcelas($parcelas)
    {
        $this->parcelas = $parcelas;
        return $this;
    }

    /**
     * Valor da parcela em caso de parcelamento
     * @return mixed Valor da parcela of Pagamento
     */
    public function getValorParcela()
    {
        return $this->valor_parcela;
    }

    /**
     * Set ValorParcela value to new on param
     * @param  mixed $valor_parcela new value for ValorParcela
     * @return Pagamento Self instance
     */
    public function setValorParcela($valor_parcela)
    {
        $this->valor_parcela = $valor_parcela;
        return $this;
    }

    /**
     * Total de taxas cobrada por financeiras e outros (Não negativo)
     * @return mixed Taxas of Pagamento
     */
    public function getTaxas()
    {
        return $this->taxas;
    }

    /**
     * Set Taxas value to new on param
     * @param  mixed $taxas new value for Taxas
     * @return Pagamento Self instance
     */
    public function setTaxas($taxas)
    {
        $this->taxas = $taxas;
        return $this;
    }

    /**
     * Detalhes do pagamento
     * @return mixed Detalhes of Pagamento
     */
    public function getDetalhes()
    {
        return $this->detalhes;
    }

    /**
     * Set Detalhes value to new on param
     * @param  mixed $detalhes new value for Detalhes
     * @return Pagamento Self instance
     */
    public function setDetalhes($detalhes)
    {
        $this->detalhes = $detalhes;
        return $this;
    }

    /**
     * Informa se o pagamento foi cancelado
     * @return mixed Cancelado of Pagamento
     */
    public function getCancelado()
    {
        return $this->cancelado;
    }

    /**
     * Informa se o pagamento foi cancelado
     * @return boolean Check if o of Cancelado is selected or checked
     */
    public function isCancelado()
    {
        return $this->cancelado == 'Y';
    }

    /**
     * Set Cancelado value to new on param
     * @param  mixed $cancelado new value for Cancelado
     * @return Pagamento Self instance
     */
    public function setCancelado($cancelado)
    {
        $this->cancelado = $cancelado;
        return $this;
    }

    /**
     * Informa se o pagamento está efetivado(Sim) ou apenas foi lançado(Não)
     * @return mixed Ativo of Pagamento
     */
    public function getAtivo()
    {
        return $this->ativo;
    }

    /**
     * Informa se o pagamento está efetivado(Sim) ou apenas foi lançado(Não)
     * @return boolean Check if o of Ativo is selected or checked
     */
    public function isAtivo()
    {
        return $this->ativo == 'Y';
    }

    /**
     * Set Ativo value to new on param
     * @param  mixed $ativo new value for Ativo
     * @return Pagamento Self instance
     */
    public function setAtivo($ativo)
    {
        $this->ativo = $ativo;
        return $this;
    }

    /**
     * Data de compensação do pagamento
     * @return mixed Data de compensação of Pagamento
     */
    public function getDataCompensacao()
    {
        return $this->data_compensacao;
    }

    /**
     * Set DataCompensacao value to new on param
     * @param  mixed $data_compensacao new value for DataCompensacao
     * @return Pagamento Self instance
     */
    public function setDataCompensacao($data_compensacao)
    {
        $this->data_compensacao = $data_compensacao;
        return $this;
    }

    /**
     * Data de hora do lançamento do pagamento
     * @return mixed Data de hora of Pagamento
     */
    public function getDataHora()
    {
        return $this->data_hora;
    }

    /**
     * Set DataHora value to new on param
     * @param  mixed $data_hora new value for DataHora
     * @return Pagamento Self instance
     */
    public function setDataHora($data_hora)
    {
        $this->data_hora = $data_hora;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param  boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $pagamento = parent::toArray($recursive);
        $pagamento['id'] = $this->getID();
        $pagamento['carteiraid'] = $this->getCarteiraID();
        $pagamento['movimentacaoid'] = $this->getMovimentacaoID();
        $pagamento['funcionarioid'] = $this->getFuncionarioID();
        $pagamento['formapagtoid'] = $this->getFormaPagtoID();
        $pagamento['pedidoid'] = $this->getPedidoID();
        $pagamento['pagtocontaid'] = $this->getPagtoContaID();
        $pagamento['cartaoid'] = $this->getCartaoID();
        $pagamento['chequeid'] = $this->getChequeID();
        $pagamento['contaid'] = $this->getContaID();
        $pagamento['creditoid'] = $this->getCreditoID();
        $pagamento['total'] = $this->getTotal();
        $pagamento['parcelas'] = $this->getParcelas();
        $pagamento['valorparcela'] = $this->getValorParcela();
        $pagamento['taxas'] = $this->getTaxas();
        $pagamento['detalhes'] = $this->getDetalhes();
        $pagamento['cancelado'] = $this->getCancelado();
        $pagamento['ativo'] = $this->getAtivo();
        $pagamento['datacompensacao'] = $this->getDataCompensacao();
        $pagamento['datahora'] = $this->getDataHora();
        return $pagamento;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param  mixed $pagamento Associated key -> value to assign into this instance
     * @return Pagamento Self instance
     */
    public function fromArray($pagamento = [])
    {
        if ($pagamento instanceof Pagamento) {
            $pagamento = $pagamento->toArray();
        } elseif (!is_array($pagamento)) {
            $pagamento = [];
        }
        parent::fromArray($pagamento);
        if (!isset($pagamento['id'])) {
            $this->setID(null);
        } else {
            $this->setID($pagamento['id']);
        }
        if (!isset($pagamento['carteiraid'])) {
            $this->setCarteiraID(null);
        } else {
            $this->setCarteiraID($pagamento['carteiraid']);
        }
        if (!array_key_exists('movimentacaoid', $pagamento)) {
            $this->setMovimentacaoID(null);
        } else {
            $this->setMovimentacaoID($pagamento['movimentacaoid']);
        }
        if (!isset($pagamento['funcionarioid'])) {
            $this->setFuncionarioID(null);
        } else {
            $this->setFuncionarioID($pagamento['funcionarioid']);
        }
        if (!isset($pagamento['formapagtoid'])) {
            $this->setFormaPagtoID(null);
        } else {
            $this->setFormaPagtoID($pagamento['formapagtoid']);
        }
        if (!array_key_exists('pedidoid', $pagamento)) {
            $this->setPedidoID(null);
        } else {
            $this->setPedidoID($pagamento['pedidoid']);
        }
        if (!array_key_exists('pagtocontaid', $pagamento)) {
            $this->setPagtoContaID(null);
        } else {
            $this->setPagtoContaID($pagamento['pagtocontaid']);
        }
        if (!array_key_exists('cartaoid', $pagamento)) {
            $this->setCartaoID(null);
        } else {
            $this->setCartaoID($pagamento['cartaoid']);
        }
        if (!array_key_exists('chequeid', $pagamento)) {
            $this->setChequeID(null);
        } else {
            $this->setChequeID($pagamento['chequeid']);
        }
        if (!array_key_exists('contaid', $pagamento)) {
            $this->setContaID(null);
        } else {
            $this->setContaID($pagamento['contaid']);
        }
        if (!array_key_exists('creditoid', $pagamento)) {
            $this->setCreditoID(null);
        } else {
            $this->setCreditoID($pagamento['creditoid']);
        }
        if (!isset($pagamento['total'])) {
            $this->setTotal(null);
        } else {
            $this->setTotal($pagamento['total']);
        }
        if (!isset($pagamento['parcelas'])) {
            $this->setParcelas(null);
        } else {
            $this->setParcelas($pagamento['parcelas']);
        }
        if (!isset($pagamento['valorparcela'])) {
            $this->setValorParcela(null);
        } else {
            $this->setValorParcela($pagamento['valorparcela']);
        }
        if (!isset($pagamento['taxas'])) {
            $this->setTaxas(null);
        } else {
            $this->setTaxas($pagamento['taxas']);
        }
        if (!array_key_exists('detalhes', $pagamento)) {
            $this->setDetalhes(null);
        } else {
            $this->setDetalhes($pagamento['detalhes']);
        }
        if (!isset($pagamento['cancelado'])) {
            $this->setCancelado(null);
        } else {
            $this->setCancelado($pagamento['cancelado']);
        }
        if (!isset($pagamento['ativo'])) {
            $this->setAtivo(null);
        } else {
            $this->setAtivo($pagamento['ativo']);
        }
        if (!isset($pagamento['datacompensacao'])) {
            $this->setDataCompensacao(null);
        } else {
            $this->setDataCompensacao($pagamento['datacompensacao']);
        }
        if (!isset($pagamento['datahora'])) {
            $this->setDataHora(null);
        } else {
            $this->setDataHora($pagamento['datahora']);
        }
        return $this;
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $pagamento = parent::publish();
        return $pagamento;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param Pagamento $original Original instance without modifications
     */
    public function filter($original)
    {
        $this->setID($original->getID());
        $this->setCarteiraID(Filter::number($this->getCarteiraID()));
        $this->setMovimentacaoID(Filter::number($this->getMovimentacaoID()));
        $this->setFuncionarioID(Filter::number($this->getFuncionarioID()));
        $this->setFormaPagtoID(Filter::number($this->getFormaPagtoID()));
        $this->setPedidoID(Filter::number($this->getPedidoID()));
        $this->setPagtoContaID(Filter::number($this->getPagtoContaID()));
        $this->setCartaoID(Filter::number($this->getCartaoID()));
        $this->setChequeID(Filter::number($this->getChequeID()));
        $this->setContaID(Filter::number($this->getContaID()));
        $this->setCreditoID(Filter::number($this->getCreditoID()));
        $this->setTotal(Filter::money($this->getTotal()));
        $this->setParcelas(Filter::number($this->getParcelas()));
        $this->setValorParcela(Filter::money($this->getValorParcela()));
        $this->setTaxas(Filter::money($this->getTaxas()));
        $this->setDetalhes(Filter::string($this->getDetalhes()));
        $this->setDataCompensacao(Filter::datetime($this->getDataCompensacao()));
        $this->setDataHora(Filter::datetime($this->getDataHora()));
    }

    /**
     * Clean instance resources like images and docs
     * @param  Pagamento $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Pagamento in array format
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getCarteiraID())) {
            $errors['carteiraid'] = 'A carteira não pode ser vazia';
        }
        if (is_null($this->getFuncionarioID())) {
            $errors['funcionarioid'] = 'O funcionário não pode ser vazio';
        }
        if (is_null($this->getFormaPagtoID())) {
            $errors['formapagtoid'] = 'A forma de pagamento não pode ser vazia';
        }
        if (is_null($this->getTotal())) {
            $errors['total'] = 'O total não pode ser vazio';
        }
        if (is_null($this->getParcelas())) {
            $errors['parcelas'] = 'A parcelas não pode ser vazia';
        }
        if (is_null($this->getValorParcela())) {
            $errors['valorparcela'] = 'O valor da parcela não pode ser vazio';
        }
        if (is_null($this->getTaxas())) {
            $errors['taxas'] = 'A taxas não pode ser vazia';
        }
        if (is_null($this->getCancelado())) {
            $errors['cancelado'] = 'O cancelado não pode ser vazio';
        }
        if (!Validator::checkBoolean($this->getCancelado(), true)) {
            $errors['cancelado'] = 'O cancelado é inválido';
        }
        if (is_null($this->getAtivo())) {
            $errors['ativo'] = 'O ativo não pode ser vazio';
        }
        if (!Validator::checkBoolean($this->getAtivo(), true)) {
            $errors['ativo'] = 'O ativo é inválido';
        }
        if (is_null($this->getDataCompensacao())) {
            $errors['datacompensacao'] = 'A data de compensação não pode ser vazia';
        }
        if (is_null($this->getDataHora())) {
            $errors['datahora'] = 'A data de hora não pode ser vazia';
        }
        if (!empty($errors)) {
            throw new \MZ\Exception\ValidationException($errors);
        }
        return $this->toArray();
    }

    /**
     * Translate SQL exception into application exception
     * @param  \Exception $e exception to translate into a readable error
     * @return \MZ\Exception\ValidationException new exception translated
     */
    protected function translate($e)
    {
        if (stripos($e->getMessage(), 'PRIMARY') !== false) {
            return new \MZ\Exception\ValidationException([
                'id' => sprintf(
                    'O id "%s" já está cadastrado',
                    $this->getID()
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Insert a new Pagamento into the database and fill instance from database
     * @return Pagamento Self instance
     */
    public function insert()
    {
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = self::getDB()->insertInto('Pagamentos')->values($values)->execute();
            $pagamento = self::findByID($id);
            $this->fromArray($pagamento->toArray());
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Pagamento with instance values into database for ID
     * @return Pagamento Self instance
     */
    public function update()
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador do pagamento não foi informado');
        }
        unset($values['id']);
        try {
            self::getDB()
                ->update('Pagamentos')
                ->set($values)
                ->where('id', $this->getID())
                ->execute();
            $pagamento = self::findByID($this->getID());
            $this->fromArray($pagamento->toArray());
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Delete this instance from database using ID
     * @return integer Number of rows deleted (Max 1)
     */
    public function delete()
    {
        if (!$this->exists()) {
            throw new \Exception('O identificador do pagamento não foi informado');
        }
        $result = self::getDB()
            ->deleteFrom('Pagamentos')
            ->where('id', $this->getID())
            ->execute();
        return $result;
    }

    /**
     * Load one register for it self with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order associative field name -> [-1, 1]
     * @return Pagamento Self instance filled or empty
     */
    public function load($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return $this->fromArray($row);
    }

    /**
     * Load into this object from database using, ID
     * @param  int $id id to find Pagamento
     * @return Pagamento Self filled instance or empty when not found
     */
    public function loadByID($id)
    {
        return $this->load([
            'id' => intval($id),
        ]);
    }

    /**
     * Carteira de destino do valor
     * @return \MZ\Wallet\Carteira The object fetched from database
     */
    public function findCarteiraID()
    {
        return \MZ\Wallet\Carteira::findByID($this->getCarteiraID());
    }

    /**
     * Movimentação do caixa quando for pagamento de pedido ou quando a conta
     * for paga do caixa
     * @return \MZ\Session\Movimentacao The object fetched from database
     */
    public function findMovimentacaoID()
    {
        if (is_null($this->getMovimentacaoID())) {
            return new \MZ\Session\Movimentacao();
        }
        return \MZ\Session\Movimentacao::findByID($this->getMovimentacaoID());
    }

    /**
     * Funcionário que lançou o pagamento no sistema
     * @return \MZ\Employee\Funcionario The object fetched from database
     */
    public function findFuncionarioID()
    {
        return \MZ\Employee\Funcionario::findByID($this->getFuncionarioID());
    }

    /**
     * Forma da pagamento do pedido ou conta
     * @return \MZ\Payment\FormaPagto The object fetched from database
     */
    public function findFormaPagtoID()
    {
        return \MZ\Payment\FormaPagto::findByID($this->getFormaPagtoID());
    }

    /**
     * Pedido que foi pago
     * @return \MZ\Sale\Pedido The object fetched from database
     */
    public function findPedidoID()
    {
        if (is_null($this->getPedidoID())) {
            return new \MZ\Sale\Pedido();
        }
        return \MZ\Sale\Pedido::findByID($this->getPedidoID());
    }

    /**
     * Conta que foi paga/recebida
     * @return \MZ\Account\Conta The object fetched from database
     */
    public function findPagtoContaID()
    {
        if (is_null($this->getPagtoContaID())) {
            return new \MZ\Account\Conta();
        }
        return \MZ\Account\Conta::findByID($this->getPagtoContaID());
    }

    /**
     * Cartão em que foi pago, para forma de pagamento em cartão
     * @return \MZ\Payment\Cartao The object fetched from database
     */
    public function findCartaoID()
    {
        if (is_null($this->getCartaoID())) {
            return new \MZ\Payment\Cartao();
        }
        return \MZ\Payment\Cartao::findByID($this->getCartaoID());
    }

    /**
     * Cheque em que foi pago
     * @return \MZ\Payment\Cheque The object fetched from database
     */
    public function findChequeID()
    {
        if (is_null($this->getChequeID())) {
            return new \MZ\Payment\Cheque();
        }
        return \MZ\Payment\Cheque::findByID($this->getChequeID());
    }

    /**
     * Conta que foi utilizada como pagamento do pedido
     * @return \MZ\Account\Conta The object fetched from database
     */
    public function findContaID()
    {
        if (is_null($this->getContaID())) {
            return new \MZ\Account\Conta();
        }
        return \MZ\Account\Conta::findByID($this->getContaID());
    }

    /**
     * Crédito que foi utilizado para pagar o pedido
     * @return \MZ\Account\Credito The object fetched from database
     */
    public function findCreditoID()
    {
        if (is_null($this->getCreditoID())) {
            return new \MZ\Account\Credito();
        }
        return \MZ\Account\Credito::findByID($this->getCreditoID());
    }

    /**
     * Get allowed keys array
     * @return array allowed keys array
     */
    private static function getAllowedKeys()
    {
        $pagamento = new Pagamento();
        $allowed = Filter::concatKeys('p.', $pagamento->toArray());
        return $allowed;
    }

    /**
     * Filter order array
     * @param  mixed $order order string or array to parse and filter allowed
     * @return array allowed associative order
     */
    private static function filterOrder($order)
    {
        $allowed = self::getAllowedKeys();
        return Filter::orderBy($order, $allowed, 'p.');
    }

    /**
     * Filter condition array with allowed fields
     * @param  array $condition condition to filter rows
     * @return array allowed condition
     */
    private static function filterCondition($condition)
    {
        $allowed = self::getAllowedKeys();
        return Filter::keys($condition, $allowed, 'p.');
    }

    /**
     * Fetch data from database with a condition
     * @param  array $condition condition to filter rows
     * @param  array $order order rows
     * @return SelectQuery query object with condition statement
     */
    private static function query($condition = [], $order = [])
    {
        $query = self::getDB()->from('Pagamentos p');
        $condition = self::filterCondition($condition);
        $query = self::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('p.id ASC');
        return self::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order order rows
     * @return Pagamento A filled Pagamento or empty instance
     */
    public static function find($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return new Pagamento($row);
    }

    /**
     * Find this object on database using, ID
     * @param  int $id id to find Pagamento
     * @return Pagamento A filled instance or empty when not found
     */
    public static function findByID($id)
    {
        return self::find([
            'id' => intval($id),
        ]);
    }

    /**
     * Find all Pagamento
     * @param  array  $condition Condition to get all Pagamento
     * @param  array  $order     Order Pagamento
     * @param  int    $limit     Limit data into row count
     * @param  int    $offset    Start offset to get rows
     * @return array             List of all rows instanced as Pagamento
     */
    public static function findAll($condition = [], $order = [], $limit = null, $offset = null)
    {
        $query = self::query($condition, $order);
        if (!is_null($limit)) {
            $query = $query->limit($limit);
        }
        if (!is_null($offset)) {
            $query = $query->offset($offset);
        }
        $rows = $query->fetchAll();
        $result = [];
        foreach ($rows as $row) {
            $result[] = new Pagamento($row);
        }
        return $result;
    }

    /**
     * Count all rows from database with matched condition critery
     * @param  array $condition condition to filter rows
     * @return integer Quantity of rows
     */
    public static function count($condition = [])
    {
        $query = self::query($condition);
        return $query->count();
    }
}
