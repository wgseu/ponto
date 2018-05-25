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

use MZ\Database\Model;
use MZ\Database\DB;
use MZ\Util\Filter;
use MZ\Util\Validator;

/**
 * Créditos de clientes
 */
class Credito extends Model
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
     * Funcionário que cadastrou o crédito
     */
    private $funcionario_id;
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
     * @return mixed ID of Credito
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param  mixed $id new value for ID
     * @return Credito Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Cliente a qual o crédito pertence
     * @return mixed Cliente of Credito
     */
    public function getClienteID()
    {
        return $this->cliente_id;
    }

    /**
     * Set ClienteID value to new on param
     * @param  mixed $cliente_id new value for ClienteID
     * @return Credito Self instance
     */
    public function setClienteID($cliente_id)
    {
        $this->cliente_id = $cliente_id;
        return $this;
    }

    /**
     * Valor do crédito
     * @return mixed Valor of Credito
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Set Valor value to new on param
     * @param  mixed $valor new value for Valor
     * @return Credito Self instance
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
        return $this;
    }

    /**
     * Detalhes do crédito, justificativa do crédito
     * @return mixed Detalhes of Credito
     */
    public function getDetalhes()
    {
        return $this->detalhes;
    }

    /**
     * Set Detalhes value to new on param
     * @param  mixed $detalhes new value for Detalhes
     * @return Credito Self instance
     */
    public function setDetalhes($detalhes)
    {
        $this->detalhes = $detalhes;
        return $this;
    }

    /**
     * Funcionário que cadastrou o crédito
     * @return mixed Funcionário of Credito
     */
    public function getFuncionarioID()
    {
        return $this->funcionario_id;
    }

    /**
     * Set FuncionarioID value to new on param
     * @param  mixed $funcionario_id new value for FuncionarioID
     * @return Credito Self instance
     */
    public function setFuncionarioID($funcionario_id)
    {
        $this->funcionario_id = $funcionario_id;
        return $this;
    }

    /**
     * Informa se o crédito foi cancelado
     * @return mixed Cancelado of Credito
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
     * @param  mixed $cancelado new value for Cancelado
     * @return Credito Self instance
     */
    public function setCancelado($cancelado)
    {
        $this->cancelado = $cancelado;
        return $this;
    }

    /**
     * Data de cadastro do crédito
     * @return mixed Data de cadastro of Credito
     */
    public function getDataCadastro()
    {
        return $this->data_cadastro;
    }

    /**
     * Set DataCadastro value to new on param
     * @param  mixed $data_cadastro new value for DataCadastro
     * @return Credito Self instance
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
        $credito = parent::toArray($recursive);
        $credito['id'] = $this->getID();
        $credito['clienteid'] = $this->getClienteID();
        $credito['valor'] = $this->getValor();
        $credito['detalhes'] = $this->getDetalhes();
        $credito['funcionarioid'] = $this->getFuncionarioID();
        $credito['cancelado'] = $this->getCancelado();
        $credito['datacadastro'] = $this->getDataCadastro();
        return $credito;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param  mixed $credito Associated key -> value to assign into this instance
     * @return Credito Self instance
     */
    public function fromArray($credito = [])
    {
        if ($credito instanceof Credito) {
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
        if (!array_key_exists('detalhes', $credito)) {
            $this->setDetalhes(null);
        } else {
            $this->setDetalhes($credito['detalhes']);
        }
        if (!isset($credito['funcionarioid'])) {
            $this->setFuncionarioID(null);
        } else {
            $this->setFuncionarioID($credito['funcionarioid']);
        }
        if (!isset($credito['cancelado'])) {
            $this->setCancelado('N');
        } else {
            $this->setCancelado($credito['cancelado']);
        }
        if (!isset($credito['datacadastro'])) {
            $this->setDataCadastro(DB::now());
        } else {
            $this->setDataCadastro($credito['datacadastro']);
        }
        return $this;
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $credito = parent::publish();
        return $credito;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param Credito $original Original instance without modifications
     */
    public function filter($original)
    {
        $this->setID($original->getID());
        $this->setFuncionarioID($original->getFuncionarioID());
        $this->setCancelado($original->getCancelado());
        $this->setDataCadastro($original->getDataCadastro());
        $this->setClienteID(Filter::number($original->getClienteID()));
        $this->setValor(Filter::money($this->getValor()));
        $this->setDetalhes(Filter::string($this->getDetalhes()));
    }

    /**
     * Clean instance resources like images and docs
     * @param  Credito $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Credito in array format
     */
    public function validate()
    {
        $errors = [];
        $old_credito = self::findByID($this->getID());
        if (is_null($this->getClienteID())) {
            $errors['clienteid'] = 'O cliente não pode ser vazio';
        }
        if (is_null($this->getValor())) {
            $errors['valor'] = 'O valor não pode ser vazio';
        }
        if (is_null($this->getFuncionarioID())) {
            $errors['funcionarioid'] = 'O funcionário não pode ser vazio';
        }
        if (is_null($this->getDetalhes())) {
            $errors['detalhes'] = 'Os detalhes não foram informados';
        }
        if (is_null($this->getCancelado())) {
            $errors['cancelado'] = 'O cancelado não pode ser vazio';
        } elseif (!Validator::checkBoolean($this->getCancelado())) {
            $errors['cancelado'] = 'A informação se cancelado é inválida';
        } elseif ($this->isCancelado() && $old_credito->exists() && $old_credito->isCancelado()) {
            $errors['cancelado'] = 'O crédito informado já está cancelado';
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
     * Insert a new Crédito into the database and fill instance from database
     * @return Credito Self instance
     */
    public function insert()
    {
        $this->setID(null);
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = DB::insertInto('Creditos')->values($values)->execute();
            $this->loadByID($id);
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Crédito with instance values into database for ID
     * @param  array $only Save these fields only, when empty save all fields except id
     * @param  boolean $except When true, saves all fields except $only
     * @return Credito Self instance
     */
    public function update($only = [], $except = false)
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador do crédito não foi informado');
        }
        $values = DB::filterValues($values, $only, $except);
        try {
            DB::update('Creditos')
                ->set($values)
                ->where('id', $this->getID())
                ->execute();
            $this->loadByID($this->getID());
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    public function cancel()
    {
        $this->setCancelado('Y');
        return $this->update(['cancelado']);
    }

    /**
     * Delete this instance from database using ID
     * @return integer Number of rows deleted (Max 1)
     */
    public function delete()
    {
        if (!$this->exists()) {
            throw new \Exception('O identificador do crédito não foi informado');
        }
        $result = DB::deleteFrom('Creditos')
            ->where('id', $this->getID())
            ->execute();
        return $result;
    }

    /**
     * Load one register for it self with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order associative field name -> [-1, 1]
     * @return Credito Self instance filled or empty
     */
    public function load($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return $this->fromArray($row);
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
     * Funcionário que cadastrou o crédito
     * @return \MZ\Employee\Funcionario The object fetched from database
     */
    public function findFuncionarioID()
    {
        return \MZ\Employee\Funcionario::findByID($this->getFuncionarioID());
    }

    /**
     * Get allowed keys array
     * @return array allowed keys array
     */
    private static function getAllowedKeys()
    {
        $credito = new Credito();
        $allowed = Filter::concatKeys('c.', $credito->toArray());
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
     * @param  array $condition condition to filter rows
     * @param  array $order order rows
     * @return SelectQuery query object with condition statement
     */
    private static function query($condition = [], $order = [])
    {
        $query = DB::from('Creditos c');
        $condition = self::filterCondition($condition);
        $query = DB::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('c.cancelado DESC');
        $query = $query->orderBy('c.id DESC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order order rows
     * @return Credito A filled Crédito or empty instance
     */
    public static function find($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return new Credito($row);
    }

    /**
     * Find this object on database using, ID
     * @param  int $id id to find Crédito
     * @return Credito A filled instance or empty when not found
     */
    public static function findByID($id)
    {
        $result = new self();
        return $result->loadByID($id);
    }

    /**
     * Find all Crédito
     * @param  array  $condition Condition to get all Crédito
     * @param  array  $order     Order Crédito
     * @param  int    $limit     Limit data into row count
     * @param  int    $offset    Start offset to get rows
     * @return array             List of all rows instanced as Credito
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
            $result[] = new Credito($row);
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
