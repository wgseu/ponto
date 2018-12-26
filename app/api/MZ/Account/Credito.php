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
namespace MZ\Account;

use MZ\Util\Mask;
use MZ\Util\Filter;
use MZ\Util\Validator;
use MZ\Database\DB;
use MZ\Database\SyncModel;
use MZ\Exception\ValidationException;

/**
 * Créditos de clientes
 */
class Credito extends SyncModel
{

    /**
     * Identificador do crédito
     */
    private $id;
    /**
     * Cliente a qual o crédito pertence
     */
    private $cliente_id;
    /**
     * Valor do crédito
     */
    private $valor;
    /**
     * Detalhes do crédito, justificativa do crédito
     */
    private $detalhes;
    /**
     * Informa se o crédito foi cancelado
     */
    private $cancelado;
    /**
     * Data de cadastro do crédito
     */
    private $data_cadastro;

    /**
     * Constructor for a new empty instance of Credito
     * @param array $credito All field and values to fill the instance
     */
    public function __construct($credito = [])
    {
        parent::__construct($credito);
    }

    /**
     * Identificador do crédito
     * @return int id of Crédito
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param int $id Set id for Crédito
     * @return self Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Cliente a qual o crédito pertence
     * @return int cliente of Crédito
     */
    public function getClienteID()
    {
        return $this->cliente_id;
    }

    /**
     * Set ClienteID value to new on param
     * @param int $cliente_id Set cliente for Crédito
     * @return self Self instance
     */
    public function setClienteID($cliente_id)
    {
        $this->cliente_id = $cliente_id;
        return $this;
    }

    /**
     * Valor do crédito
     * @return string valor of Crédito
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Set Valor value to new on param
     * @param string $valor Set valor for Crédito
     * @return self Self instance
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
        return $this;
    }

    /**
     * Detalhes do crédito, justificativa do crédito
     * @return string detalhes of Crédito
     */
    public function getDetalhes()
    {
        return $this->detalhes;
    }

    /**
     * Set Detalhes value to new on param
     * @param string $detalhes Set detalhes for Crédito
     * @return self Self instance
     */
    public function setDetalhes($detalhes)
    {
        $this->detalhes = $detalhes;
        return $this;
    }

    /**
     * Informa se o crédito foi cancelado
     * @return string cancelado of Crédito
     */
    public function getCancelado()
    {
        return $this->cancelado;
    }

    /**
     * Informa se o crédito foi cancelado
     * @return boolean Check if o of Cancelado is selected or checked
     */
    public function isCancelado()
    {
        return $this->cancelado == 'Y';
    }

    /**
     * Set Cancelado value to new on param
     * @param string $cancelado Set cancelado for Crédito
     * @return self Self instance
     */
    public function setCancelado($cancelado)
    {
        $this->cancelado = $cancelado;
        return $this;
    }

    /**
     * Data de cadastro do crédito
     * @return string data de cadastro of Crédito
     */
    public function getDataCadastro()
    {
        return $this->data_cadastro;
    }

    /**
     * Set DataCadastro value to new on param
     * @param string $data_cadastro Set data de cadastro for Crédito
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
        $credito = parent::toArray($recursive);
        $credito['id'] = $this->getID();
        $credito['clienteid'] = $this->getClienteID();
        $credito['valor'] = $this->getValor();
        $credito['detalhes'] = $this->getDetalhes();
        $credito['cancelado'] = $this->getCancelado();
        $credito['datacadastro'] = $this->getDataCadastro();
        return $credito;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param mixed $credito Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($credito = [])
    {
        if ($credito instanceof self) {
            $credito = $credito->toArray();
        } elseif (!is_array($credito)) {
            $credito = [];
        }
        parent::fromArray($credito);
        if (!isset($credito['id'])) {
            $this->setID(null);
        } else {
            $this->setID($credito['id']);
        }
        if (!isset($credito['clienteid'])) {
            $this->setClienteID(null);
        } else {
            $this->setClienteID($credito['clienteid']);
        }
        if (!isset($credito['valor'])) {
            $this->setValor(null);
        } else {
            $this->setValor($credito['valor']);
        }
        if (!isset($credito['detalhes'])) {
            $this->setDetalhes(null);
        } else {
            $this->setDetalhes($credito['detalhes']);
        }
        if (!isset($credito['cancelado'])) {
            $this->setCancelado('N');
        } else {
            $this->setCancelado($credito['cancelado']);
        }
        if (!isset($credito['datacadastro'])) {
            $this->setDataCadastro(null);
        } else {
            $this->setDataCadastro($credito['datacadastro']);
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
        $credito = parent::publish($requester);
        return $credito;
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
        $this->setCancelado($original->getCancelado());
        $this->setDataCadastro($original->getDataCadastro());
        $this->setClienteID(Filter::number($original->getClienteID()));
        $this->setValor(Filter::money($this->getValor(), $localized));
        $this->setDetalhes(Filter::string($this->getDetalhes()));
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
     * @return array All field of Credito in array format
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function validate()
    {
        $errors = [];
        $old = self::findByID($this->getID());
        $other_total = self::sum(['valor'], ['clienteid' => $old->getClienteID(), 'cancelado' => 'N']);
        if (is_null($this->getClienteID())) {
            $errors['clienteid'] = _t('credito.cliente_id_cannot_empty');
        } elseif ($old->exists() &&
            $this->getClienteID() != $old->getClienteID() &&
            $old->getValor() > 0 &&
            $other_total < $old->getValor()
        ) {
            $errors['clienteid'] = _t('credito.cliente_cannot_transfer');
        }
        $total = self::sum(['valor'], ['clienteid' => $this->getClienteID(), 'cancelado' => 'N']);
        if (is_null($this->getValor())) {
            $errors['valor'] = _t('credito.valor_cannot_empty');
        } elseif ($old->exists() && $old->getValor() != $this->getValor()) {
            $errors['valor'] = _t('credito.valor_changed');
        } elseif (!$this->isCancelado() && $this->getValor() < 0 && $total + $this->getValor() < 0) {
            $errors['valor'] = _t('credito.valor_insufficient');
        } elseif ($this->isCancelado() && $this->getValor() > 0 && $total < $this->getValor()) {
            $errors['valor'] = _t('credito.valor_cancel_negative');
        }
        if (is_null($this->getDetalhes())) {
            $errors['detalhes'] = _t('credito.detalhes_cannot_empty');
        }
        if (!Validator::checkBoolean($this->getCancelado())) {
            $errors['cancelado'] = _t('credito.cancelado_invalid');
        } elseif ($old->exists() && $old->isCancelado()) {
            $errors['cancelado'] = _t('credito.cancelado_already');
        } elseif (!$this->exists() && $this->isCancelado()) {
            $errors['cancelado'] = _t('credito.cancelado_new');
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

    /**
     * Cancel this credit
     * @return self Self instance filled or empty
     */
    public function cancel()
    {
        $this->setCancelado('Y');
        return $this->update();
    }

    /**
     * Cliente a qual o crédito pertence
     * @return \MZ\Account\Cliente The object fetched from database
     */
    public function findClienteID()
    {
        return \MZ\Account\Cliente::findByID($this->getClienteID());
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
            $field = 'c.detalhes LIKE ?';
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
        $query = DB::from('Creditos c');
        $condition = $this->filterCondition($condition);
        $query = DB::buildOrderBy($query, $this->filterOrder($order));
        $query = $query->orderBy('c.cancelado DESC');
        $query = $query->orderBy('c.id DESC');
        return DB::buildCondition($query, $condition);
    }
}
