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

/**
 * Folha de cheque lançado como pagamento
 */
class Cheque extends SyncModel
{

    /**
     * Identificador da folha de cheque
     */
    private $id;
    /**
     * Cliente que emitiu o cheque
     */
    private $cliente_id;
    /**
     * Banco do cheque
     */
    private $banco_id;
    /**
     * Número da agência
     */
    private $agencia;
    /**
     * Número da conta do banco descrito no cheque
     */
    private $conta;
    /**
     * Número da folha do cheque
     */
    private $numero;
    /**
     * Valor na folha do cheque
     */
    private $valor;
    /**
     * Data de vencimento do cheque
     */
    private $vencimento;
    /**
     * Informa se o cheque e todas as suas folhas estão cancelados
     */
    private $cancelado;
    /**
     * Informa se o cheque foi recolhido no banco
     */
    private $recolhido;
    /**
     * Data de recolhimento do cheque
     */
    private $recolhimento;
    /**
     * Data de cadastro do cheque
     */
    private $data_cadastro;

    /**
     * Constructor for a new empty instance of Cheque
     * @param array $cheque All field and values to fill the instance
     */
    public function __construct($cheque = [])
    {
        parent::__construct($cheque);
    }

    /**
     * Identificador da folha de cheque
     * @return int id of Cheque
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param int $id Set id for Cheque
     * @return self Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Cliente que emitiu o cheque
     * @return int cliente of Cheque
     */
    public function getClienteID()
    {
        return $this->cliente_id;
    }

    /**
     * Set ClienteID value to new on param
     * @param int $cliente_id Set cliente for Cheque
     * @return self Self instance
     */
    public function setClienteID($cliente_id)
    {
        $this->cliente_id = $cliente_id;
        return $this;
    }

    /**
     * Banco do cheque
     * @return int banco of Cheque
     */
    public function getBancoID()
    {
        return $this->banco_id;
    }

    /**
     * Set BancoID value to new on param
     * @param int $banco_id Set banco for Cheque
     * @return self Self instance
     */
    public function setBancoID($banco_id)
    {
        $this->banco_id = $banco_id;
        return $this;
    }

    /**
     * Número da agência
     * @return string agência of Cheque
     */
    public function getAgencia()
    {
        return $this->agencia;
    }

    /**
     * Set Agencia value to new on param
     * @param string $agencia Set agência for Cheque
     * @return self Self instance
     */
    public function setAgencia($agencia)
    {
        $this->agencia = $agencia;
        return $this;
    }

    /**
     * Número da conta do banco descrito no cheque
     * @return string conta of Cheque
     */
    public function getConta()
    {
        return $this->conta;
    }

    /**
     * Set Conta value to new on param
     * @param string $conta Set conta for Cheque
     * @return self Self instance
     */
    public function setConta($conta)
    {
        $this->conta = $conta;
        return $this;
    }

    /**
     * Número da folha do cheque
     * @return string número of Cheque
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set Numero value to new on param
     * @param string $numero Set número for Cheque
     * @return self Self instance
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;
        return $this;
    }

    /**
     * Valor na folha do cheque
     * @return string valor of Cheque
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Set Valor value to new on param
     * @param string $valor Set valor for Cheque
     * @return self Self instance
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
        return $this;
    }

    /**
     * Data de vencimento do cheque
     * @return string vencimento of Cheque
     */
    public function getVencimento()
    {
        return $this->vencimento;
    }

    /**
     * Set Vencimento value to new on param
     * @param string $vencimento Set vencimento for Cheque
     * @return self Self instance
     */
    public function setVencimento($vencimento)
    {
        $this->vencimento = $vencimento;
        return $this;
    }

    /**
     * Informa se o cheque e todas as suas folhas estão cancelados
     * @return string cancelado of Cheque
     */
    public function getCancelado()
    {
        return $this->cancelado;
    }

    /**
     * Informa se o cheque e todas as suas folhas estão cancelados
     * @return boolean Check if o of Cancelado is selected or checked
     */
    public function isCancelado()
    {
        return $this->cancelado == 'Y';
    }

    /**
     * Set Cancelado value to new on param
     * @param string $cancelado Set cancelado for Cheque
     * @return self Self instance
     */
    public function setCancelado($cancelado)
    {
        $this->cancelado = $cancelado;
        return $this;
    }

    /**
     * Informa se o cheque foi recolhido no banco
     * @return string recolhido of Cheque
     */
    public function getRecolhido()
    {
        return $this->recolhido;
    }

    /**
     * Informa se o cheque foi recolhido no banco
     * @return boolean Check if o of Recolhido is selected or checked
     */
    public function isRecolhido()
    {
        return $this->recolhido == 'Y';
    }

    /**
     * Set Recolhido value to new on param
     * @param string $recolhido Set recolhido for Cheque
     * @return self Self instance
     */
    public function setRecolhido($recolhido)
    {
        $this->recolhido = $recolhido;
        return $this;
    }

    /**
     * Data de recolhimento do cheque
     * @return string data de recolhimento of Cheque
     */
    public function getRecolhimento()
    {
        return $this->recolhimento;
    }

    /**
     * Set Recolhimento value to new on param
     * @param string $recolhimento Set data de recolhimento for Cheque
     * @return self Self instance
     */
    public function setRecolhimento($recolhimento)
    {
        $this->recolhimento = $recolhimento;
        return $this;
    }

    /**
     * Data de cadastro do cheque
     * @return string data de cadastro of Cheque
     */
    public function getDataCadastro()
    {
        return $this->data_cadastro;
    }

    /**
     * Set DataCadastro value to new on param
     * @param string $data_cadastro Set data de cadastro for Cheque
     * @return self Self instance
     */
    public function setDataCadastro($data_cadastro)
    {
        $this->data_cadastro = $data_cadastro;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $cheque = parent::toArray($recursive);
        $cheque['id'] = $this->getID();
        $cheque['clienteid'] = $this->getClienteID();
        $cheque['bancoid'] = $this->getBancoID();
        $cheque['agencia'] = $this->getAgencia();
        $cheque['conta'] = $this->getConta();
        $cheque['numero'] = $this->getNumero();
        $cheque['valor'] = $this->getValor();
        $cheque['vencimento'] = $this->getVencimento();
        $cheque['cancelado'] = $this->getCancelado();
        $cheque['recolhido'] = $this->getRecolhido();
        $cheque['recolhimento'] = $this->getRecolhimento();
        $cheque['datacadastro'] = $this->getDataCadastro();
        return $cheque;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param mixed $cheque Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($cheque = [])
    {
        if ($cheque instanceof self) {
            $cheque = $cheque->toArray();
        } elseif (!is_array($cheque)) {
            $cheque = [];
        }
        parent::fromArray($cheque);
        if (!isset($cheque['id'])) {
            $this->setID(null);
        } else {
            $this->setID($cheque['id']);
        }
        if (!isset($cheque['clienteid'])) {
            $this->setClienteID(null);
        } else {
            $this->setClienteID($cheque['clienteid']);
        }
        if (!isset($cheque['bancoid'])) {
            $this->setBancoID(null);
        } else {
            $this->setBancoID($cheque['bancoid']);
        }
        if (!isset($cheque['agencia'])) {
            $this->setAgencia(null);
        } else {
            $this->setAgencia($cheque['agencia']);
        }
        if (!isset($cheque['conta'])) {
            $this->setConta(null);
        } else {
            $this->setConta($cheque['conta']);
        }
        if (!isset($cheque['numero'])) {
            $this->setNumero(null);
        } else {
            $this->setNumero($cheque['numero']);
        }
        if (!isset($cheque['valor'])) {
            $this->setValor(null);
        } else {
            $this->setValor($cheque['valor']);
        }
        if (!isset($cheque['vencimento'])) {
            $this->setVencimento(null);
        } else {
            $this->setVencimento($cheque['vencimento']);
        }
        if (!isset($cheque['cancelado'])) {
            $this->setCancelado('N');
        } else {
            $this->setCancelado($cheque['cancelado']);
        }
        if (!isset($cheque['recolhido'])) {
            $this->setRecolhido('N');
        } else {
            $this->setRecolhido($cheque['recolhido']);
        }
        if (!array_key_exists('recolhimento', $cheque)) {
            $this->setRecolhimento(null);
        } else {
            $this->setRecolhimento($cheque['recolhimento']);
        }
        if (!isset($cheque['datacadastro'])) {
            $this->setDataCadastro(DB::now());
        } else {
            $this->setDataCadastro($cheque['datacadastro']);
        }
        return $this;
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @param \MZ\Provider\Prestador $requester user that request to view this fields
     * @return array All public field and values into array format
     */
    public function publish($requester)
    {
        $cheque = parent::publish($requester);
        return $cheque;
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
        $this->setClienteID(Filter::number($this->getClienteID()));
        $this->setBancoID(Filter::number($this->getBancoID()));
        $this->setAgencia(Filter::string($this->getAgencia()));
        $this->setConta(Filter::string($this->getConta()));
        $this->setNumero(Filter::string($this->getNumero()));
        $this->setValor(Filter::money($this->getValor(), $localized));
        $this->setVencimento(Filter::datetime($this->getVencimento()));
        $this->setRecolhimento(Filter::datetime($this->getRecolhimento()));
        $this->setDataCadastro(Filter::datetime($this->getDataCadastro()));
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
     * @return array All field of Cheque in array format
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function validate()
    {
        $errors = [];
        $old = self::findByID($this->getID());
        if (is_null($this->getClienteID())) {
            $errors['clienteid'] = _t('cheque.cliente_id_cannot_empty');
        }
        if (is_null($this->getBancoID())) {
            $errors['bancoid'] = _t('cheque.banco_id_cannot_empty');
        }
        if (is_null($this->getAgencia())) {
            $errors['agencia'] = _t('cheque.agencia_cannot_empty');
        }
        if (is_null($this->getConta())) {
            $errors['conta'] = _t('cheque.conta_cannot_empty');
        }
        if (is_null($this->getNumero())) {
            $errors['numero'] = _t('cheque.numero_cannot_empty');
        }
        if (is_null($this->getValor())) {
            $errors['valor'] = _t('cheque.valor_cannot_empty');
        }
        if (is_null($this->getVencimento())) {
            $errors['vencimento'] = _t('cheque.vencimento_cannot_empty');
        }
        if (!Validator::checkBoolean($this->getCancelado())) {
            $errors['cancelado'] = _t('cheque.cancelado_invalid');
        } elseif (!$this->exists() && $this->isCancelado()) {
            $errors['cancelado'] = _t('cheque.new_canceled');
        }
        if (!Validator::checkBoolean($this->getRecolhido())) {
            $errors['recolhido'] = _t('cheque.recolhido_invalid');
        } elseif ($this->isRecolhido() && $old->exists() && $old->isRecolhido()) {
            $errors['recolhido'] = 'Essa folha de cheque já foi recolhida';
        }
        $this->setDataCadastro(DB::now());
        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
        $values = $this->toArray();
        if ($this->exists()) {
            unset($values['datacadastro']);
        }
        return $values;
    }

    public function recolher()
    {
        $this->setRecolhido('Y');
        $this->setRecolhimento(DB::now());
        return $this->update();
    }

    /**
     * Cliente que emitiu o cheque
     * @return \MZ\Account\Cliente The object fetched from database
     */
    public function findClienteID()
    {
        return \MZ\Account\Cliente::findByID($this->getClienteID());
    }

    /**
     * Banco do cheque
     * @return \MZ\Wallet\Banco The object fetched from database
     */
    public function findBancoID()
    {
        return \MZ\Wallet\Banco::findByID($this->getBancoID());
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
            $search = $condition['search'];
            $field = 'c.numero LIKE ?';
            $condition[$field] = '%'.$search.'%';
            $allowed[$field] = true;
            unset($condition['search']);
        }
        return Filter::keys($condition, $allowed, 'c.');
    }

    /**
     * Fetch data from database with a condition
     * @param array $condition condition to filter rows
     * @param array $order order rows
     * @return SelectQuery query object with condition statement
     */
    protected function query($condition = [], $order = [])
    {
        $query = DB::from('Cheques c');
        $condition = $this->filterCondition($condition);
        $query = DB::buildOrderBy($query, $this->filterOrder($order));
        $query = $query->orderBy('c.numero ASC');
        $query = $query->orderBy('c.id ASC');
        return DB::buildCondition($query, $condition);
    }
}