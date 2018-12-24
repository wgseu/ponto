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
namespace MZ\Database;

use MZ\Util\Filter;
use MZ\Exception\ValidationException;

/**
 * Implement common model operations on database CRUD and searches
 */
abstract class Model
{
    /**
     * Database table name
     * @var string
     */
    protected $table;
    /**
     * Database table alias
     * @var string
     */
    protected $alias;

    /**
     * Constructor for a new empty instance of Model
     * @param array $model All field and values to fill the instance
     */
    public function __construct($model = [])
    {
        $this->fromArray($model);
        $this->prepare();
    }

    /**
     * Get the primary key for this entry
     * @return integer key of register
     */
    abstract public function getID();

    /**
     * Set the primary key for this entry
     * @param int $id database id
     * @return self key of register
     */
    abstract public function setID($id);

    /**
     * Convert this instance to array associated key -> value
     * @param boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        return [];
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param mixed $model Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($model = [])
    {
        return $this;
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @param \MZ\Provider\Prestador $requester user that request to view this fields
     * @return array All public field and values into array format
     */
    public function publish($requester)
    {
        return $this->toArray(true);
    }

    /**
     * Check if this instance have a valid primary key
     * @return boolean true if have a valid primary key, false otherwise
     */
    public function exists()
    {
        return !is_null($this->getID()) && is_numeric($this->getID());
    }

    /**
     * Database table name
     * @return string
     */
    public function getTable()
    {
        if (!isset($this->table)) {
            return \pluralize(underscore_case(class_basename($this)));
        }
        return $this->table;
    }

    /**
     * Set the table name
     * @return self Self instance
     */
    public function setTable($table)
    {
        $this->table = $table;
        return $this;
    }

    /**
     * Database table alias
     * @return string
     */
    public function getAlias()
    {
        if (!isset($this->alias)) {
            return \strtolower(\substr($this->getTable(), 0, 1));
        }
        return $this->alias;
    }

    /**
     * Set the table alias
     * @return self Self instance
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
        return $this;
    }

    /**
     * Prepare model for database operations
     * @return self Self instance
     */
    protected function prepare()
    {
        if (!isset($this->table)) {
            $this->setTable($this->getTable());
        }
        if (!isset($this->alias)) {
            $this->setAlias($this->getAlias());
        }
        return $this;
    }

    /**
     * Translate SQL exception into application exception
     * @param \Exception $e exception to translate into a readable error
     * @return \Exception new exception translated
     */
    protected function translate($e)
    {
        if ($e instanceof \PDOException) {
            return new \Exception($e->errorInfo[2], 500);
        }
        return $e;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param mixed $original Original instance without modifications
     * @param \MZ\Provider\Prestador $updater user that want to update this object
     * @param boolean $localized Informs if fields are localized
     * @return self Self instance
     */
    abstract public function filter($original, $updater, $localized = false);

    /**
     * Clean instance resources like images and docs
     * @param mixed $dependency Don't clean when dependency use same resources
     * @return mixed Self instance
     */
    abstract public function clean($dependency);

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Model in array format
     */
    abstract public function validate();

    /**
     * Load one register for it self with a condition
     * @param array $condition Condition for searching the row
     * @param array $order associative field name -> [-1, 1]
     * @return self Self instance filled or empty
     */
    public function load($condition, $order = [])
    {
        $query = $this->query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return $this->fromArray($row);
    }

    /**
     * Insert a new register into the database and fill instance from database
     * @return self Self instance
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function insert()
    {
        $this->setID(null);
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = DB::insertInto($this->getTable())->values($values)->execute();
            $this->setID($id);
            $this->loadByID();
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Informação nutricional with instance values into database for ID
     * @param array $only Save these fields only, when empty save all fields except id
     * @return int rows affected
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function update($only = [])
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new ValidationException(
                ['id' => _t(snake_case(class_basename($this)) . '.id_cannot_empty')]
            );
        }
        $values = DB::filterValues($values, $only, false);
        try {
            $affected = DB::update($this->getTable())
                ->set($values)
                ->where(['id' => $this->getID()])
                ->execute();
            $this->loadByID();
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $affected;
    }

    /**
     * Delete this instance from database using ID
     * @return integer Number of rows deleted (Max 1)
     * @throws \MZ\Exception\ValidationException for invalid id
     */
    public function delete()
    {
        if (!$this->exists()) {
            throw new ValidationException(
                ['id' => _t(snake_case(class_basename($this)) . '.id_cannot_empty')]
            );
        }
        $result = DB::deleteFrom($this->getTable())
            ->where('id', $this->getID())
            ->execute();
        return $result;
    }

    /**
     * Save a new or a existing instance into the database and fill instance from database
     * @param array $only Save these fields only, when empty save all fields except id
     * @return Model Self instance
     */
    public function save($only = [])
    {
        if ($this->exists()) {
            return $this->update($only);
        }
        return $this->insert();
    }

    /**
     * Load into this object from database using id
     * @return self Self filled instance or empty when not found
     */
    public function loadByID()
    {
        return $this->load([
            'id' => intval($this->getID()),
        ]);
    }

    /**
     * Get allowed keys array
     * @return array allowed keys array
     */
    protected function getAllowedKeys()
    {
        $allowed = Filter::concatKeys($this->getAlias() . '.', $this->toArray());
        return $allowed;
    }

    /**
     * Filter order array
     * @param mixed $order order string or array to parse and filter allowed
     * @return array allowed associative order
     */
    protected function filterOrder($order)
    {
        $allowed = $this->getAllowedKeys();
        return Filter::orderBy($order, $allowed, $this->getAlias() . '.');
    }

    /**
     * Search one register with a condition
     * @param array $condition Condition for searching the row
     * @param array $order order rows
     * @return self A filled or empty instance
     * @throws \Exception when register has not found
     */
    public static function findOrFail($condition, $order = [])
    {
        $result = self::find($condition, $order);
        if (!$result->exists()) {
            throw new \Exception(_t(snake_case(class_basename($result)) . '.not_found'), 404);
        }
        return $result;
    }

    /**
     * Find this object on database using, ID
     * @param int $id id to find object on database
     * @return self A filled instance or empty when not found
     */
    public static function findByID($id)
    {
        $result = new static();
        $result->setID($id);
        return $result->loadByID();
    }

    /**
     * Search one register with a condition
     * @param array $condition Condition for searching the row
     * @param array $order order rows
     * @return self A filled or empty instance of called class
     */
    public static function find($condition, $order = [])
    {
        $result = new static();
        return $result->load($condition, $order);
    }

    /**
     * Find all register
     * @param array  $condition Condition to get all register
     * @param array  $order     Order rows
     * @param int    $limit     Limit data into row count
     * @param int    $offset    Start offset to get rows
     * @return self[] List of all rows instanced
     */
    public static function findAll($condition = [], $order = [], $limit = null, $offset = null)
    {
        $rows = static::rawFindAll($condition, $order, $limit, $offset);
        $result = [];
        foreach ($rows as $row) {
            $result[] = new static($row);
        }
        return $result;
    }

    /**
     * Find all rows
     * @param  array  $condition Condition to get all rows
     * @param  array  $order     Order rows
     * @param  int    $limit     Limit data into row count
     * @param  int    $offset    Start offset to get rows
     * @return array  List of all rows
     */
    public static function rawFindAll($condition = [], $order = [], $limit = null, $offset = null)
    {
        $instance = new static();
        $query = $instance->query($condition, $order);
        if (!is_null($limit)) {
            $query = $query->limit($limit);
        }
        if (!is_null($offset)) {
            $query = $query->offset($offset);
        }
        return $query->fetchAll();
    }

    /**
     * Count all rows from database with matched condition critery
     * @param array $condition condition to filter rows
     * @return integer Quantity of rows
     */
    public static function count($condition = [])
    {
        $instance = new static();
        $query = $instance->query($condition);
        return $query->count();
    }

    /**
     * Search one register with a condition
     * @param  array $fields fields to sum
     * @param  array $condition Condition for searching the row
     * @return mixed A filled Estoque or empty instance
     */
    public static function sum($fields, $condition)
    {
        $instance = new static();
        $query = $instance->query($condition)->select(null);
        $aliases = $fields;
        $fields = array_flip($instance->filterCondition(array_flip($fields)));
        if (count($fields) != count($aliases)) {
            throw new \Exception('Invalid field to sum', 500);
        }
        foreach ($fields as $index => $field) {
            $alias = $aliases[$index];
            $query = $query->select("SUM($field) as $alias");
        }
        if (count($fields) == 1) {
            return $query->orderBy(null)->fetchColumn();
        }
        return $query->orderBy(null)->fetch();
    }
}
