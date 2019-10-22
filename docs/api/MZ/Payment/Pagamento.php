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

namespace MZ\Payment;

use MZ\Util\Mask;
use MZ\Util\Filter;
use MZ\Util\Validator;
use MZ\Database\DB;
use MZ\Database\SyncModel;
use MZ\Exception\ValidationException;
use MZ\Account\Conta;

/**
 * Pagamentos de contas e pedidos
 */
class Pagamento extends SyncModel
{

    /**
     * Informa qual o andamento do processo de pagamento
     */
    const ESTADO_ABERTO = 'Aberto';
    const ESTADO_AGUARDANDO = 'Aguardando';
    const ESTADO_ANALISE = 'Analise';
    const ESTADO_PAGO = 'Pago';
    const ESTADO_DISPUTA = 'Disputa';
    const ESTADO_DEVOLVIDO = 'Devolvido';
    const ESTADO_CANCELADO = 'Cancelado';

    /**
     * Identificador do pagamento
     */
    private $id;
    /**
     * Carteira de destino do valor
     */
    private $carteira_id;
    /**
     * Informa em qual moeda está o valor informado
     */
    private $moeda_id;
    /**
     * Informa o pagamento principal ou primeira parcela, o valor lançado é
     * zero para os pagamentos filhos, restante de antecipação e taxas são
     * filhos do valor antecipado
     */
    private $pagamento_id;
    /**
     * Permite antecipar recebimentos de cartões, um pagamento agrupado é
     * internamente tratado como desativado
     */
    private $agrupamento_id;
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
     * Forma da pagamento do pedido
     */
    private $forma_pagto_id;
    /**
     * Pedido que foi pago
     */
    private $pedido_id;
    /**
     * Conta que foi paga/recebida
     */
    private $conta_id;
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
    private $crediario_id;
    /**
     * Crédito que foi utilizado para pagar o pedido
     */
    private $credito_id;
    /**
     * Valor pago ou recebido na moeda informada no momento do recebimento
     */
    private $valor;
    /**
     * Informa qual o número da parcela para este pagamento
     */
    private $numero_parcela;
    /**
     * Quantidade de parcelas desse pagamento
     */
    private $parcelas;
    /**
     * Valor lançado para pagamento do pedido ou conta na moeda local do país
     */
    private $lancado;
    /**
     * Código do pagamento, usado em transações online
     */
    private $codigo;
    /**
     * Detalhes do pagamento
     */
    private $detalhes;
    /**
     * Informa qual o andamento do processo de pagamento
     */
    private $estado;
    /**
     * Data de compensação do pagamento
     */
    private $data_compensacao;
    /**
     * Data e hora do lançamento do pagamento
     */
    private $data_lancamento;

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
     * @return int id of Pagamento
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param int $id Set id for Pagamento
     * @return self Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Carteira de destino do valor
     * @return int carteira of Pagamento
     */
    public function getCarteiraID()
    {
        return $this->carteira_id;
    }

    /**
     * Set CarteiraID value to new on param
     * @param int $carteira_id Set carteira for Pagamento
     * @return self Self instance
     */
    public function setCarteiraID($carteira_id)
    {
        $this->carteira_id = $carteira_id;
        return $this;
    }

    /**
     * Informa em qual moeda está o valor informado
     * @return int moeda of Pagamento
     */
    public function getMoedaID()
    {
        return $this->moeda_id;
    }

    /**
     * Set MoedaID value to new on param
     * @param int $moeda_id Set moeda for Pagamento
     * @return self Self instance
     */
    public function setMoedaID($moeda_id)
    {
        $this->moeda_id = $moeda_id;
        return $this;
    }

    /**
     * Informa o pagamento principal ou primeira parcela, o valor lançado é
     * zero para os pagamentos filhos, restante de antecipação e taxas são
     * filhos do valor antecipado
     * @return int pagamento of Pagamento
     */
    public function getPagamentoID()
    {
        return $this->pagamento_id;
    }

    /**
     * Set PagamentoID value to new on param
     * @param int $pagamento_id Set pagamento for Pagamento
     * @return self Self instance
     */
    public function setPagamentoID($pagamento_id)
    {
        $this->pagamento_id = $pagamento_id;
        return $this;
    }

    /**
     * Permite antecipar recebimentos de cartões, um pagamento agrupado é
     * internamente tratado como desativado
     * @return int agrupamento of Pagamento
     */
    public function getAgrupamentoID()
    {
        return $this->agrupamento_id;
    }

    /**
     * Set AgrupamentoID value to new on param
     * @param int $agrupamento_id Set agrupamento for Pagamento
     * @return self Self instance
     */
    public function setAgrupamentoID($agrupamento_id)
    {
        $this->agrupamento_id = $agrupamento_id;
        return $this;
    }

    /**
     * Movimentação do caixa quando for pagamento de pedido ou quando a conta
     * for paga do caixa
     * @return int movimentação of Pagamento
     */
    public function getMovimentacaoID()
    {
        return $this->movimentacao_id;
    }

    /**
     * Set MovimentacaoID value to new on param
     * @param int $movimentacao_id Set movimentação for Pagamento
     * @return self Self instance
     */
    public function setMovimentacaoID($movimentacao_id)
    {
        $this->movimentacao_id = $movimentacao_id;
        return $this;
    }

    /**
     * Funcionário que lançou o pagamento no sistema
     * @return int funcionário of Pagamento
     */
    public function getFuncionarioID()
    {
        return $this->funcionario_id;
    }

    /**
     * Set FuncionarioID value to new on param
     * @param int $funcionario_id Set funcionário for Pagamento
     * @return self Self instance
     */
    public function setFuncionarioID($funcionario_id)
    {
        $this->funcionario_id = $funcionario_id;
        return $this;
    }

    /**
     * Forma da pagamento do pedido
     * @return int forma de pagamento of Pagamento
     */
    public function getFormaPagtoID()
    {
        return $this->forma_pagto_id;
    }

    /**
     * Set FormaPagtoID value to new on param
     * @param int $forma_pagto_id Set forma de pagamento for Pagamento
     * @return self Self instance
     */
    public function setFormaPagtoID($forma_pagto_id)
    {
        $this->forma_pagto_id = $forma_pagto_id;
        return $this;
    }

    /**
     * Pedido que foi pago
     * @return int pedido of Pagamento
     */
    public function getPedidoID()
    {
        return $this->pedido_id;
    }

    /**
     * Set PedidoID value to new on param
     * @param int $pedido_id Set pedido for Pagamento
     * @return self Self instance
     */
    public function setPedidoID($pedido_id)
    {
        $this->pedido_id = $pedido_id;
        return $this;
    }

    /**
     * Conta que foi paga/recebida
     * @return int conta of Pagamento
     */
    public function getContaID()
    {
        return $this->conta_id;
    }

    /**
     * Set ContaID value to new on param
     * @param int $conta_id Set conta for Pagamento
     * @return self Self instance
     */
    public function setContaID($conta_id)
    {
        $this->conta_id = $conta_id;
        return $this;
    }

    /**
     * Cartão em que foi pago, para forma de pagamento em cartão
     * @return int cartão of Pagamento
     */
    public function getCartaoID()
    {
        return $this->cartao_id;
    }

    /**
     * Set CartaoID value to new on param
     * @param int $cartao_id Set cartão for Pagamento
     * @return self Self instance
     */
    public function setCartaoID($cartao_id)
    {
        $this->cartao_id = $cartao_id;
        return $this;
    }

    /**
     * Cheque em que foi pago
     * @return int cheque of Pagamento
     */
    public function getChequeID()
    {
        return $this->cheque_id;
    }

    /**
     * Set ChequeID value to new on param
     * @param int $cheque_id Set cheque for Pagamento
     * @return self Self instance
     */
    public function setChequeID($cheque_id)
    {
        $this->cheque_id = $cheque_id;
        return $this;
    }

    /**
     * Conta que foi utilizada como pagamento do pedido
     * @return int conta pedido of Pagamento
     */
    public function getCrediarioID()
    {
        return $this->crediario_id;
    }

    /**
     * Set CrediarioID value to new on param
     * @param int $crediario_id Set conta pedido for Pagamento
     * @return self Self instance
     */
    public function setCrediarioID($crediario_id)
    {
        $this->crediario_id = $crediario_id;
        return $this;
    }

    /**
     * Crédito que foi utilizado para pagar o pedido
     * @return int crédito of Pagamento
     */
    public function getCreditoID()
    {
        return $this->credito_id;
    }

    /**
     * Set CreditoID value to new on param
     * @param int $credito_id Set crédito for Pagamento
     * @return self Self instance
     */
    public function setCreditoID($credito_id)
    {
        $this->credito_id = $credito_id;
        return $this;
    }

    /**
     * Valor pago ou recebido na moeda informada no momento do recebimento
     * @return string valor of Pagamento
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Set Valor value to new on param
     * @param string $valor Set valor for Pagamento
     * @return self Self instance
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
        return $this;
    }

    /**
     * Informa qual o número da parcela para este pagamento
     * @return int número da parcela of Pagamento
     */
    public function getNumeroParcela()
    {
        return $this->numero_parcela;
    }

    /**
     * Set NumeroParcela value to new on param
     * @param int $numero_parcela Set número da parcela for Pagamento
     * @return self Self instance
     */
    public function setNumeroParcela($numero_parcela)
    {
        $this->numero_parcela = $numero_parcela;
        return $this;
    }

    /**
     * Quantidade de parcelas desse pagamento
     * @return int parcelas of Pagamento
     */
    public function getParcelas()
    {
        return $this->parcelas;
    }

    /**
     * Set Parcelas value to new on param
     * @param int $parcelas Set parcelas for Pagamento
     * @return self Self instance
     */
    public function setParcelas($parcelas)
    {
        $this->parcelas = $parcelas;
        return $this;
    }

    /**
     * Valor lançado para pagamento do pedido ou conta na moeda local do país
     * @return string lancado of Pagamento
     */
    public function getLancado()
    {
        return $this->lancado;
    }

    /**
     * Set Lancado value to new on param
     * @param string $lancado Set lancado for Pagamento
     * @return self Self instance
     */
    public function setLancado($lancado)
    {
        $this->lancado = $lancado;
        return $this;
    }

    /**
     * Código do pagamento, usado em transações online
     * @return string código of Pagamento
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set Codigo value to new on param
     * @param string $codigo Set código for Pagamento
     * @return self Self instance
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
        return $this;
    }

    /**
     * Detalhes do pagamento
     * @return string detalhes of Pagamento
     */
    public function getDetalhes()
    {
        return $this->detalhes;
    }

    /**
     * Set Detalhes value to new on param
     * @param string $detalhes Set detalhes for Pagamento
     * @return self Self instance
     */
    public function setDetalhes($detalhes)
    {
        $this->detalhes = $detalhes;
        return $this;
    }

    /**
     * Informa qual o andamento do processo de pagamento
     * @return string estado of Pagamento
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set Estado value to new on param
     * @param string $estado Set estado for Pagamento
     * @return self Self instance
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
        return $this;
    }

    /**
     * Data de compensação do pagamento
     * @return string data de compensação of Pagamento
     */
    public function getDataCompensacao()
    {
        return $this->data_compensacao;
    }

    /**
     * Set DataCompensacao value to new on param
     * @param string $data_compensacao Set data de compensação for Pagamento
     * @return self Self instance
     */
    public function setDataCompensacao($data_compensacao)
    {
        $this->data_compensacao = $data_compensacao;
        return $this;
    }

    /**
     * Data e hora do lançamento do pagamento
     * @return string data de lançamento of Pagamento
     */
    public function getDataLancamento()
    {
        return $this->data_lancamento;
    }

    /**
     * Set DataLancamento value to new on param
     * @param string $data_lancamento Set data de lançamento for Pagamento
     * @return self Self instance
     */
    public function setDataLancamento($data_lancamento)
    {
        $this->data_lancamento = $data_lancamento;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $pagamento = parent::toArray($recursive);
        $pagamento['id'] = $this->getID();
        $pagamento['carteiraid'] = $this->getCarteiraID();
        $pagamento['moedaid'] = $this->getMoedaID();
        $pagamento['pagamentoid'] = $this->getPagamentoID();
        $pagamento['agrupamentoid'] = $this->getAgrupamentoID();
        $pagamento['movimentacaoid'] = $this->getMovimentacaoID();
        $pagamento['funcionarioid'] = $this->getFuncionarioID();
        $pagamento['formapagtoid'] = $this->getFormaPagtoID();
        $pagamento['pedidoid'] = $this->getPedidoID();
        $pagamento['contaid'] = $this->getContaID();
        $pagamento['cartaoid'] = $this->getCartaoID();
        $pagamento['chequeid'] = $this->getChequeID();
        $pagamento['crediarioid'] = $this->getCrediarioID();
        $pagamento['creditoid'] = $this->getCreditoID();
        $pagamento['valor'] = $this->getValor();
        $pagamento['numeroparcela'] = $this->getNumeroParcela();
        $pagamento['parcelas'] = $this->getParcelas();
        $pagamento['lancado'] = $this->getLancado();
        $pagamento['codigo'] = $this->getCodigo();
        $pagamento['detalhes'] = $this->getDetalhes();
        $pagamento['estado'] = $this->getEstado();
        $pagamento['datacompensacao'] = $this->getDataCompensacao();
        $pagamento['datalancamento'] = $this->getDataLancamento();
        return $pagamento;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param mixed $pagamento Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($pagamento = [])
    {
        if ($pagamento instanceof self) {
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
        if (!isset($pagamento['moedaid'])) {
            $this->setMoedaID(null);
        } else {
            $this->setMoedaID($pagamento['moedaid']);
        }
        if (!array_key_exists('pagamentoid', $pagamento)) {
            $this->setPagamentoID(null);
        } else {
            $this->setPagamentoID($pagamento['pagamentoid']);
        }
        if (!array_key_exists('agrupamentoid', $pagamento)) {
            $this->setAgrupamentoID(null);
        } else {
            $this->setAgrupamentoID($pagamento['agrupamentoid']);
        }
        if (!array_key_exists('movimentacaoid', $pagamento)) {
            $this->setMovimentacaoID(null);
        } else {
            $this->setMovimentacaoID($pagamento['movimentacaoid']);
        }
        if (!array_key_exists('funcionarioid', $pagamento)) {
            $this->setFuncionarioID(null);
        } else {
            $this->setFuncionarioID($pagamento['funcionarioid']);
        }
        if (!array_key_exists('formapagtoid', $pagamento)) {
            $this->setFormaPagtoID(null);
        } else {
            $this->setFormaPagtoID($pagamento['formapagtoid']);
        }
        if (!array_key_exists('pedidoid', $pagamento)) {
            $this->setPedidoID(null);
        } else {
            $this->setPedidoID($pagamento['pedidoid']);
        }
        if (!array_key_exists('contaid', $pagamento)) {
            $this->setContaID(null);
        } else {
            $this->setContaID($pagamento['contaid']);
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
        if (!array_key_exists('crediarioid', $pagamento)) {
            $this->setCrediarioID(null);
        } else {
            $this->setCrediarioID($pagamento['crediarioid']);
        }
        if (!array_key_exists('creditoid', $pagamento)) {
            $this->setCreditoID(null);
        } else {
            $this->setCreditoID($pagamento['creditoid']);
        }
        if (!isset($pagamento['valor'])) {
            $this->setValor(null);
        } else {
            $this->setValor($pagamento['valor']);
        }
        if (!isset($pagamento['numeroparcela'])) {
            $this->setNumeroParcela(null);
        } else {
            $this->setNumeroParcela($pagamento['numeroparcela']);
        }
        if (!isset($pagamento['parcelas'])) {
            $this->setParcelas(0);
        } else {
            $this->setParcelas($pagamento['parcelas']);
        }
        if (!isset($pagamento['lancado'])) {
            $this->setLancado(null);
        } else {
            $this->setLancado($pagamento['lancado']);
        }
        if (!array_key_exists('codigo', $pagamento)) {
            $this->setCodigo(null);
        } else {
            $this->setCodigo($pagamento['codigo']);
        }
        if (!array_key_exists('detalhes', $pagamento)) {
            $this->setDetalhes(null);
        } else {
            $this->setDetalhes($pagamento['detalhes']);
        }
        if (!isset($pagamento['estado'])) {
            $this->setEstado(self::ESTADO_ABERTO);
        } else {
            $this->setEstado($pagamento['estado']);
        }
        if (!isset($pagamento['datacompensacao'])) {
            $this->setDataCompensacao(DB::now());
        } else {
            $this->setDataCompensacao($pagamento['datacompensacao']);
        }
        if (!isset($pagamento['datalancamento'])) {
            $this->setDataLancamento(DB::now());
        } else {
            $this->setDataLancamento($pagamento['datalancamento']);
        }
        return $this;
    }

    /**
     * Informa se o pagamento foi realizado com sucesso
     * @return bool
     */
    public function isPago()
    {
        return $this->getEstado() == self::ESTADO_PAGO;
    }

    /**
     * Informa se o pagamento está cancelado
     * @return bool
     */
    public function isCancelado()
    {
        return $this->getEstado() == self::ESTADO_CANCELADO || $this->getEstado() == self::ESTADO_DEVOLVIDO;
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @param \MZ\Provider\Prestador $requester user that request to view this fields
     * @return array All public field and values into array format
     */
    public function publish($requester)
    {
        $pagamento = parent::publish($requester);
        return $pagamento;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param self $original Original instance without modifications
     * @param \MZ\Provider\Prestador $updater user that want to update this object
     * @param boolean $localized Informs if fields are localized
     * @return self Self instance
     */
    public function filter($original, $updater, $localized = false)
    {
        $this->setID($original->getID());
        $this->setCarteiraID(Filter::number($this->getCarteiraID()));
        $this->setMoedaID(Filter::number($this->getMoedaID()));
        $this->setPagamentoID(Filter::number($this->getPagamentoID()));
        $this->setAgrupamentoID(Filter::number($this->getAgrupamentoID()));
        $this->setMovimentacaoID(Filter::number($this->getMovimentacaoID()));
        $this->setFuncionarioID(Filter::number($this->getFuncionarioID()));
        $this->setFormaPagtoID(Filter::number($this->getFormaPagtoID()));
        $this->setPedidoID(Filter::number($this->getPedidoID()));
        $this->setContaID(Filter::number($this->getContaID()));
        $this->setCartaoID(Filter::number($this->getCartaoID()));
        $this->setChequeID(Filter::number($this->getChequeID()));
        $this->setCrediarioID(Filter::number($this->getCrediarioID()));
        $this->setCreditoID(Filter::number($this->getCreditoID()));
        $this->setValor(Filter::money($this->getValor(), $localized));
        $this->setNumeroParcela(Filter::number($this->getNumeroParcela()));
        $this->setParcelas(Filter::number($this->getParcelas()));
        $this->setLancado(Filter::money($this->getLancado(), $localized));
        $this->setCodigo(Filter::string($this->getCodigo()));
        $this->setDetalhes(Filter::string($this->getDetalhes()));
        $this->setDataCompensacao(Filter::datetime($this->getDataCompensacao()));
        $this->setDataLancamento(Filter::datetime($this->getDataLancamento()));
        return $this;
    }

    /**
     * Clean instance resources like images and docs
     * @param self $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Pagamento in array format
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getCarteiraID())) {
            $errors['carteiraid'] = _t('pagamento.carteira_id_cannot_empty');
        }
        if (is_null($this->getMoedaID())) {
            $errors['moedaid'] = _t('pagamento.moeda_id_cannot_empty');
        }
        if (is_null($this->getValor())) {
            $errors['valor'] = _t('pagamento.valor_cannot_empty');
        }
        if (is_null($this->getNumeroParcela())) {
            $errors['numeroparcela'] = _t('pagamento.numero_parcela_cannot_empty');
        }
        if (is_null($this->getParcelas())) {
            $errors['parcelas'] = _t('pagamento.parcelas_cannot_empty');
        }
        if (is_null($this->getLancado())) {
            $errors['lancado'] = _t('pagamento.lancado_cannot_empty');
        }
        if (!Validator::checkInSet($this->getEstado(), self::getEstadoOptions())) {
            $errors['estado'] = _t('pagamento.estado_invalid');
        }
        if (is_null($this->getDataCompensacao())) {
            $errors['datacompensacao'] = _t('pagamento.data_compensacao_cannot_empty');
        }
        $this->setDataLancamento(DB::now());
        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
        $values = $this->toArray();
        if ($this->exists()) {
            unset($values['datalancamento']);
        }
        return $values;
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
     * Informa em qual moeda está o valor informado
     * @return \MZ\Wallet\Moeda The object fetched from database
     */
    public function findMoedaID()
    {
        return \MZ\Wallet\Moeda::findByID($this->getMoedaID());
    }

    /**
     * Informa o pagamento principal ou primeira parcela, o valor lançado é
     * zero para os pagamentos filhos, restante de antecipação e taxas são
     * filhos do valor antecipado
     * @return \MZ\Payment\Pagamento The object fetched from database
     */
    public function findPagamentoID()
    {
        if (is_null($this->getPagamentoID())) {
            return new \MZ\Payment\Pagamento();
        }
        return \MZ\Payment\Pagamento::findByID($this->getPagamentoID());
    }

    /**
     * Permite antecipar recebimentos de cartões, um pagamento agrupado é
     * internamente tratado como desativado
     * @return \MZ\Payment\Pagamento The object fetched from database
     */
    public function findAgrupamentoID()
    {
        if (is_null($this->getAgrupamentoID())) {
            return new \MZ\Payment\Pagamento();
        }
        return \MZ\Payment\Pagamento::findByID($this->getAgrupamentoID());
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
     * @return \MZ\Provider\Prestador The object fetched from database
     */
    public function findFuncionarioID()
    {
        if (is_null($this->getFuncionarioID())) {
            return new \MZ\Provider\Prestador();
        }
        return \MZ\Provider\Prestador::findByID($this->getFuncionarioID());
    }

    /**
     * Forma da pagamento do pedido
     * @return \MZ\Payment\FormaPagto The object fetched from database
     */
    public function findFormaPagtoID()
    {
        if (is_null($this->getFormaPagtoID())) {
            return new \MZ\Payment\FormaPagto();
        }
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
    public function findContaID()
    {
        if (is_null($this->getContaID())) {
            return new \MZ\Account\Conta();
        }
        return \MZ\Account\Conta::findByID($this->getContaID());
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
    public function findCrediarioID()
    {
        if (is_null($this->getCrediarioID())) {
            return new \MZ\Account\Conta();
        }
        return \MZ\Account\Conta::findByID($this->getCrediarioID());
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
     * Gets textual and translated Estado for Pagamento
     * @param int $index choose option from index
     * @return string[] A associative key -> translated representative text or text for index
     */
    public static function getEstadoOptions($index = null)
    {
        $options = [
            self::ESTADO_ABERTO => _t('pagamento.estado_aberto'),
            self::ESTADO_AGUARDANDO => _t('pagamento.estado_aguardando'),
            self::ESTADO_ANALISE => _t('pagamento.estado_analise'),
            self::ESTADO_PAGO => _t('pagamento.estado_pago'),
            self::ESTADO_DISPUTA => _t('pagamento.estado_disputa'),
            self::ESTADO_DEVOLVIDO => _t('pagamento.estado_devolvido'),
            self::ESTADO_CANCELADO => _t('pagamento.estado_cancelado'),
        ];
        if (!is_null($index)) {
            return $options[$index];
        }
        return $options;
    }

    /**
     * Filter condition array with allowed fields
     * @param array $condition condition to filter rows
     * @return array allowed condition
     */
    protected function filterCondition($condition)
    {
        $allowed = $this->getAllowedKeys();
        if (isset($condition['search'])) {
            $search = trim($condition['search']);
            if (Validator::checkDigits($search)) {
                $condition['pedidoid'] = Filter::number($search);
            } elseif (substr($search, 0, 1) == '#') {
                $condition['movimentacaoid'] = Filter::number($search);
            } else {
                $field = 'p.detalhes LIKE ?';
                $condition[$field] = '%'.$search.'%';
                $allowed[$field] = true;
            }
        }
        if (isset($condition['apartir_datacompensacao'])) {
            $field = 'p.datacompensacao >= ?';
            $condition[$field] = Filter::datetime($condition['apartir_datacompensacao'], '00:00:00');
            $allowed[$field] = true;
        }
        if (isset($condition['ate_datacompensacao'])) {
            $field = 'p.datacompensacao <= ?';
            $condition[$field] = Filter::datetime($condition['ate_datacompensacao'], '23:59:59');
            $allowed[$field] = true;
        }
        if (isset($condition['apartir_datalancamento'])) {
            $field = 'p.datalancamento >= ?';
            $condition[$field] = Filter::datetime($condition['apartir_datalancamento'], '00:00:00');
            $allowed[$field] = true;
        }
        if (isset($condition['ate_datalancamento'])) {
            $field = 'p.datalancamento <= ?';
            $condition[$field] = Filter::datetime($condition['ate_datalancamento'], '23:59:59');
            $allowed[$field] = true;
        }
        if (array_key_exists('!pedidoid', $condition)) {
            $field = 'NOT p.pedidoid';
            $condition[$field] = $condition['!pedidoid'];
            $allowed[$field] = true;
        }
        if (array_key_exists('!contaid', $condition)) {
            $field = 'NOT p.contaid';
            $condition[$field] = $condition['!contaid'];
            $allowed[$field] = true;
        }
        if (isset($condition['apartir_valor'])) {
            $field = 'p.valor >= ?';
            $condition[$field] = $condition['apartir_valor'];
            $allowed[$field] = true;
        }
        if (isset($condition['ate_valor'])) {
            $field = 'p.valor <= ?';
            $condition[$field] = $condition['ate_valor'];
            $allowed[$field] = true;
        }
        if (isset($condition['receitas'])) {
            $field = '(NOT p.pedidoid IS NULL OR (p.valor > 0 AND NOT p.contaid IS NULL))';
            $allowed[$field] = true;
        }
        $allowed['m.sessaoid'] = true;
        return Filter::keys($condition, $allowed, ['p.', 'm.']);
    }

    /**
     * Fetch data from database with a condition
     * @param array $condition condition to filter rows
     * @param array $order order rows
     * @return SelectQuery query object with condition statement
     */
    protected function query($condition = [], $order = [])
    {
        $condition = $this->filterCondition($condition);
        $query = DB::from('Pagamentos p');
        if (array_key_exists('m.sessaoid', $condition)) {
            $query = $query->leftJoin('Movimentacoes m ON m.id = p.movimentacaoid');
        }
        $query = DB::buildOrderBy($query, $this->filterOrder($order));
        $query = $query->orderBy('p.id DESC');
        return DB::buildCondition($query, $condition);
    }

    private static function queryTotal($condition, $group = [])
    {
        $condition['estado'] = self::ESTADO_PAGO;
        $instance = new self();
        $query = $instance->query($condition, ['datalancamento' => 1])
            ->select(null)
            ->select('SUM(p.valor) as total');
        if (isset($condition['mesaid'])) {
            $query = $query->leftJoin('Pedidos e ON e.id = p.pedidoid')
                ->where('e.mesaid', $condition['mesaid']);
        }
        if (isset($group['dia'])) {
            $query = $query->select(DB::strftime('%Y-%m-%d', 'p.datalancamento').' as data')
                ->groupBy(DB::strftime('%Y-%m-%d', 'p.datalancamento'));
        } elseif (isset($group['forma_tipo'])) {
            $query = $query->leftJoin('Formas_Pagto f ON f.id = p.formapagtoid')
                ->select('LOWER(f.tipo) as tipo')
                ->orderBy('total DESC')
                ->groupBy('f.tipo');
        }
        return $query;
    }

    /**
     * Retorna a soma dos pagamentos válidos com base na condição informada
     * @param  array $condition Condition for sum payments
     * @return float A soma total dos pagamentos
     */
    public static function rawFindTotal($condition)
    {
        $query = self::queryTotal($condition)->limit(1);
        $total = $query->fetchColumn() ?: 0;
        return floatval($total);
    }

    public static function getReceitas($condition)
    {
        $condition['receitas'] = true;
        return self::rawFindTotal($condition);
    }

    public static function getDespesas($condition)
    {
        $condition['ate_valor'] = 0.0;
        $condition['!contaid'] = null;
        return self::rawFindTotal($condition);
    }

    public static function getFaturamento($condition)
    {
        $condition['!pedidoid'] = null;
        return self::rawFindTotal($condition);
    }

    /**
     * Find total grouping by total, payment method type or day
     * @param  array  $condition Condition to get all Pagamento
     * @param  array  $group group results
     * @param  int    $limit     Limit data into row count
     * @param  int    $offset    Start offset to get rows
     * @return array             List of all rows instanced as Pagamento
     */
    public static function rawFindAllTotal($condition = [], $group = [], $limit = null, $offset = null)
    {
        $query = self::queryTotal($condition, $group);
        if (!is_null($limit)) {
            $query = $query->limit($limit);
        }
        if (!is_null($offset)) {
            $query = $query->offset($offset);
        }
        return $query->fetchAll();
    }
}
