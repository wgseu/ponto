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

/**
 * Implement common operations on database CRUD and searches
 */
abstract class Helper
{

    /**
     * Constructor for a new empty instance of Helper
     * @param array $helper All field and values to fill the instance
     */
    public function __construct($helper = [])
    {
        $this->fromArray($helper);
    }

    /**
     * Get the primary key for this entry
     * @return integer key of register
     */
    abstract public function getID();

    /**
     * Convert this instance to array associated key -> value
     * @param  boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        return [];
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param  mixed $helper Associated key -> value to assign into this instance
     * @return Helper Self instance
     */
    public function fromArray($helper = [])
    {
        return $this;
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
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
     * Translate SQL exception into application exception
     * @param  \Exception $e exception to translate into a readable error
     * @return \Exception new exception translated
     */
    protected function translate($e)
    {
        return $e;
    }

    /**
     * Get FluentPDO instance
     * @return FluentPDO connected PDO instance
     */
    public static function getDB()
    {
        return \DB::$pdo;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param mixed $original Original instance without modifications
     * @return mixed Self instance
     */
    abstract public function filter($original);

    /**
     * Clean instance resources like images and docs
     * @param  mixed $dependency Don't clean when dependency use same resources
     * @return mixed Self instance
     */
    abstract public function clean($dependency);

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Helper in array format
     */
    abstract public function validate();

    /**
     * Load one register for it self with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order associative field name -> [-1, 1]
     * @return Setor Self instance filled or empty
     */
    abstract public function load($condition, $order = []);

    /**
     * Insert a new registry into the database and fill instance from database
     * @return mixed Self instance
     */
    abstract public function insert();

    /**
     * Update registry with instance values into database for ID
     * @param  array $only Save these fields only, when empty save all fields except id
     * @param  boolean $except When true, saves all fields except $only
     * @return mixed Self instance
     */
    abstract public function update($only = [], $except = false);

    /**
     * Delete this instance from database using ID
     * @return integer Number of rows deleted (Max 1)
     */
    abstract public function delete();

    /**
     * Save a new or a existing instance into the database and fill instance from database
     * @param  array $only Save these fields only, when empty save all fields except id
     * @param  boolean $except When true, saves all fields except $only
     * @return Helper Self instance
     */
    public function save($only = [], $except = false)
    {
        if ($this->exists()) {
            return $this->update($only, $except);
        }
        return $this->insert();
    }

    /**
     * Load into this object from database using id
     * @param  int $id id to find this object on database
     * @return Helper Self filled instance or empty when not found
     */
    public function loadByID($id)
    {
        return $this->load([
            'id' => intval($id),
        ]);
    }

    /**
     * Retorn current date and time on database format
     * @return string current date and time database formatted
     */
    public static function now($timestamp = null)
    {
        if (is_string($timestamp)) {
            $timestamp = strtotime($timestamp);
        }
        return date('Y-m-d H:i:s', $timestamp?:time());
    }

    /**
     * Retorn current date on database format
     * @return string current date database formatted
     */
    public static function date($timestamp = null)
    {
        if (is_string($timestamp)) {
            $timestamp = strtotime($timestamp);
        }
        return date('Y-m-d', $timestamp?:time());
    }

    /**
     * Gets boolean array or boolean value for index
     * @param  string $index Y or N, null to return array
     * @return mixed A array or boolean value
     */
    public static function getBooleanOptions($index = null)
    {
        $options = ['Y' => true, 'N' => false];
        if (!is_null($index)) {
            return $options[$index];
        }
        return $options;
    }

    /**
     * Add order statement into query object
     * @param  SelectQuery $query FluentPDO query
     * @param  array $order associative field name -> [-1, 1]
     * @return SelectQuery query object with order statement
     */
    public static function buildOrderBy($query, $order)
    {
        foreach ($order as $field => $direction) {
            if (is_array($direction)) {
                $param = current($direction);
                $direction = key($direction);
                if ($direction < 0) {
                    $query = $query->orderBy($field . ' DESC', $param);
                } else {
                    $query = $query->orderBy($field . ' ASC', $param);
                }
            } elseif ($direction < 0) {
                $query = $query->orderBy($field . ' DESC');
            } else {
                $query = $query->orderBy($field . ' ASC');
            }
        }
        return $query;
    }

    /**
     * Add where statement into query object
     * @param  SelectQuery $query FluentPDO query
     * @param  array $condition associative field -> value, accepts multiple fields and values
     * @return SelectQuery query object with where statement
     */
    public static function buildCondition($query, $condition)
    {
        foreach ($condition as $field => $value) {
            if (is_array($value)) {
                if (count($value) == 0 || substr_count($field, '?') != count($value)) {
                    $query = $query->where($field, $value);
                } else {
                    $params = $value;
                    array_unshift($params, $field);
                    $query = call_user_func_array([$query, 'where'], $params);
                }
            } else {
                $query = $query->where($field, $value);
            }
        }
        return $query;
    }

    /**
     * Construct search statement from string
     * @param string $search String to search
     * @param string $field  field name to match
     * @param SelectQuery $query query object
     * @return SelectQuery query object with where and order statement
     */
    public static function buildSearch($search, $field, $query)
    {
        $keywords = preg_split('/[\s,]+/', $search);
        foreach ($keywords as $word) {
            $query = $query->where($field . ' LIKE ?', '%'.$word.'%');
            $query = $query->orderBy(
                'COALESCE(NULLIF(LOCATE(?, '.
                self::concat(['" "', $field]).
                '), 0), 65535) ASC',
                ' '.$word
            );
            $query = $query->orderBy('COALESCE(NULLIF(LOCATE(?, ' . $field . '), 0), 65535) ASC', $word);
        }
        return $query;
    }

    /**
     * Filter values array
     * @param  array $only Save these fields only, when empty save all fields except id
     * @param  boolean $except When true, saves all fields except $only
     * @return aarray filtered array
     */
    public static function filterValues($values, $only, $except)
    {
        $keep = array_keys($values);
        $ignore = [];
        if (is_array($only) && !empty($only)) {
            $ignore = $except ? $only : $ignore;
            $keep = $except ? $keep : $only;
        }
        $ignore[] = 'id';
        $values = array_intersect_key($values, array_flip($keep));
        return array_diff_key($values, array_flip($ignore));
    }

    public static function concat($values)
    {
        if (getenv('DB_DRIVER') == 'sqlite') {
            return '(' . implode(' || ', $values) . ')';
        }
        return 'CONCAT(' . implode(', ', $values) . ')';
    }
}
