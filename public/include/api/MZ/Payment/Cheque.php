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

use MZ\Util\Filter;
use MZ\Util\Validator;

/**
 * Cheques lançados como pagamentos
 */
class Cheque extends \MZ\Database\Helper
{

    /**
     * Identificador do cheque
     */
    private $id;
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
     * Cliente que emitiu o cheque
     */
    private $cliente_id;
    /**
     * Quantidade de parcelas/folhas de cheque
     */
    private $parcelas;
    /**
     * Total pago em cheque
     */
    private $total;
    /**
     * Informa se o cheque e todas as suas folhas estão cancelados
     */
    private $cancelado;
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
     * Identificador do cheque
     * @return mixed ID of Cheque
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param  mixed $id new value for ID
     * @return Cheque Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Banco do cheque
     * @return mixed Banco of Cheque
     */
    public function getBancoID()
    {
        return $this->banco_id;
    }

    /**
     * Set BancoID value to new on param
     * @param  mixed $banco_id new value for BancoID
     * @return Cheque Self instance
     */
    public function setBancoID($banco_id)
    {
        $this->banco_id = $banco_id;
        return $this;
    }

    /**
     * Número da agência
     * @return mixed Agência of Cheque
     */
    public function getAgencia()
    {
        return $this->agencia;
    }

    /**
     * Set Agencia value to new on param
     * @param  mixed $agencia new value for Agencia
     * @return Cheque Self instance
     */
    public function setAgencia($agencia)
    {
        $this->agencia = $agencia;
        return $this;
    }

    /**
     * Número da conta do banco descrito no cheque
     * @return mixed Conta of Cheque
     */
    public function getConta()
    {
        return $this->conta;
    }

    /**
     * Set Conta value to new on param
     * @param  mixed $conta new value for Conta
     * @return Cheque Self instance
     */
    public function setConta($conta)
    {
        $this->conta = $conta;
        return $this;
    }

    /**
     * Cliente que emitiu o cheque
     * @return mixed Cliente of Cheque
     */
    public function getClienteID()
    {
        return $this->cliente_id;
    }

    /**
     * Set ClienteID value to new on param
     * @param  mixed $cliente_id new value for ClienteID
     * @return Cheque Self instance
     */
    public function setClienteID($cliente_id)
    {
        $this->cliente_id = $cliente_id;
        return $this;
    }

    /**
     * Quantidade de parcelas/folhas de cheque
     * @return mixed Parcelas of Cheque
     */
    public function getParcelas()
    {
        return $this->parcelas;
    }

    /**
     * Set Parcelas value to new on param
     * @param  mixed $parcelas new value for Parcelas
     * @return Cheque Self instance
     */
    public function setParcelas($parcelas)
    {
        $this->parcelas = $parcelas;
        return $this;
    }

    /**
     * Total pago em cheque
     * @return mixed Total of Cheque
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set Total value to new on param
     * @param  mixed $total new value for Total
     * @return Cheque Self instance
     */
    public function setTotal($total)
    {
        $this->total = $total;
        return $this;
    }

    /**
     * Informa se o cheque e todas as suas folhas estão cancelados
     * @return mixed Cancelado of Cheque
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
     * @param  mixed $cancelado new value for Cancelado
     * @return Cheque Self instance
     */
    public function setCancelado($cancelado)
    {
        $this->cancelado = $cancelado;
        return $this;
    }

    /**
     * Data de cadastro do cheque
     * @return mixed Data de cadastro of Cheque
     */
    public function getDataCadastro()
    {
        return $this->data_cadastro;
    }

    /**
     * Set DataCadastro value to new on param
     * @param  mixed $data_cadastro new value for DataCadastro
     * @return Cheque Self instance
     */
    public function setDataCadastro($data_cadastro)
    {
        $this->data_cadastro = $data_cadastro;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param  boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $cheque = parent::toArray($recursive);
        $cheque['id'] = $this->getID();
        $cheque['bancoid'] = $this->getBancoID();
        $cheque['agencia'] = $this->getAgencia();
        $cheque['conta'] = $this->getConta();
        $cheque['clienteid'] = $this->getClienteID();
        $cheque['parcelas'] = $this->getParcelas();
        $cheque['total'] = $this->getTotal();
        $cheque['cancelado'] = $this->getCancelado();
        $cheque['datacadastro'] = $this->getDataCadastro();
        return $cheque;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param  mixed $cheque Associated key -> value to assign into this instance
     * @return Cheque Self instance
     */
    public function fromArray($cheque = [])
    {
        if ($cheque instanceof Cheque) {
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
        if (!isset($cheque['clienteid'])) {
            $this->setClienteID(null);
        } else {
            $this->setClienteID($cheque['clienteid']);
        }
        if (!isset($cheque['parcelas'])) {
            $this->setParcelas(null);
        } else {
            $this->setParcelas($cheque['parcelas']);
        }
        if (!isset($cheque['total'])) {
            $this->setTotal(null);
        } else {
            $this->setTotal($cheque['total']);
        }
        if (!isset($cheque['cancelado'])) {
            $this->setCancelado(null);
        } else {
            $this->setCancelado($cheque['cancelado']);
        }
        if (!isset($cheque['datacadastro'])) {
            $this->setDataCadastro(null);
        } else {
            $this->setDataCadastro($cheque['datacadastro']);
        }
        return $this;
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $cheque = parent::publish();
        return $cheque;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param Cheque $original Original instance without modifications
     */
    public function filter($original)
    {
        $this->setID($original->getID());
        $this->setBancoID(Filter::number($this->getBancoID()));
        $this->setAgencia(Filter::string($this->getAgencia()));
        $this->setConta(Filter::string($this->getConta()));
        $this->setClienteID(Filter::number($this->getClienteID()));
        $this->setParcelas(Filter::number($this->getParcelas()));
        $this->setTotal(Filter::money($this->getTotal()));
        $this->setDataCadastro(Filter::datetime($this->getDataCadastro()));
    }

    /**
     * Clean instance resources like images and docs
     * @param  Cheque $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Cheque in array format
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getBancoID())) {
            $errors['bancoid'] = 'O banco não pode ser vazio';
        }
        if (is_null($this->getAgencia())) {
            $errors['agencia'] = 'A agência não pode ser vazia';
        }
        if (is_null($this->getConta())) {
            $errors['conta'] = 'A conta não pode ser vazia';
        }
        if (is_null($this->getClienteID())) {
            $errors['clienteid'] = 'O cliente não pode ser vazio';
        }
        if (is_null($this->getParcelas())) {
            $errors['parcelas'] = 'A parcelas não pode ser vazia';
        }
        if (is_null($this->getTotal())) {
            $errors['total'] = 'O total não pode ser vazio';
        }
        if (is_null($this->getCancelado())) {
            $errors['cancelado'] = 'O cancelado não pode ser vazio';
        }
        if (!Validator::checkBoolean($this->getCancelado(), true)) {
            $errors['cancelado'] = 'O cancelado é inválido';
        }
        if (is_null($this->getDataCadastro())) {
            $errors['datacadastro'] = 'A data de cadastro não pode ser vazia';
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
        return parent::translate($e);
    }

    /**
     * Insert a new Cheque into the database and fill instance from database
     * @return Cheque Self instance
     */
    public function insert()
    {
        $this->setID(null);
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = self::getDB()->insertInto('Cheques')->values($values)->execute();
            $this->loadByID($id);
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Cheque with instance values into database for ID
     * @param  array $only Save these fields only, when empty save all fields except id
     * @param  boolean $except When true, saves all fields except $only
     * @return Cheque Self instance
     */
    public function update($only = [], $except = false)
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador do cheque não foi informado');
        }
        $values = self::filterValues($values, $only, $except);
        try {
            self::getDB()
                ->update('Cheques')
                ->set($values)
                ->where('id', $this->getID())
                ->execute();
            $this->loadByID($this->getID());
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
            throw new \Exception('O identificador do cheque não foi informado');
        }
        $result = self::getDB()
            ->deleteFrom('Cheques')
            ->where('id', $this->getID())
            ->execute();
        return $result;
    }

    /**
     * Load one register for it self with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order associative field name -> [-1, 1]
     * @return Cheque Self instance filled or empty
     */
    public function load($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return $this->fromArray($row);
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
     * Cliente que emitiu o cheque
     * @return \MZ\Account\Cliente The object fetched from database
     */
    public function findClienteID()
    {
        return \MZ\Account\Cliente::findByID($this->getClienteID());
    }

    /**
     * Get allowed keys array
     * @return array allowed keys array
     */
    private static function getAllowedKeys()
    {
        $cheque = new Cheque();
        $allowed = Filter::concatKeys('c.', $cheque->toArray());
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
        return Filter::orderBy($order, $allowed, 'c.');
    }

    /**
     * Filter condition array with allowed fields
     * @param  array $condition condition to filter rows
     * @return array allowed condition
     */
    private static function filterCondition($condition)
    {
        $allowed = self::getAllowedKeys();
        return Filter::keys($condition, $allowed, 'c.');
    }

    /**
     * Fetch data from database with a condition
     * @param  array $condition condition to filter rows
     * @param  array $order order rows
     * @return SelectQuery query object with condition statement
     */
    private static function query($condition = [], $order = [])
    {
        $query = self::getDB()->from('Cheques c');
        $condition = self::filterCondition($condition);
        $query = self::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('c.id ASC');
        return self::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order order rows
     * @return Cheque A filled Cheque or empty instance
     */
    public static function find($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return new Cheque($row);
    }

    /**
     * Find this object on database using, ID
     * @param  int $id id to find Cheque
     * @return Cheque A filled instance or empty when not found
     */
    public static function findByID($id)
    {
        return self::find([
            'id' => intval($id),
        ]);
    }

    /**
     * Find all Cheque
     * @param  array  $condition Condition to get all Cheque
     * @param  array  $order     Order Cheque
     * @param  int    $limit     Limit data into row count
     * @param  int    $offset    Start offset to get rows
     * @return array             List of all rows instanced as Cheque
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
            $result[] = new Cheque($row);
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
