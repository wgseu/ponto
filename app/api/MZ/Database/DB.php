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

use PDO;
use FluentPDO;

/**
 * Implement common operations on database CRUD and searches
 */
class DB
{
    private $query = null;
    private $transactionCounter = 0;

    public function connect($config)
    {
        $driver = isset($config['driver']) ? $config['driver'] : 'mysql';
        $values = [];
        $values[] = $driver . ':' . (
            $driver == 'sqlite'?
            $config['name']:
            'dbname=' . $config['name']
        );
        if ($driver != 'sqlite') {
            $values[] =  'host=' . $config['host'];
            $values[] =  'port=' . $config['port'];
            $values[] = 'charset=utf8';
        }
        $dsn = implode(';', $values);
        $pdo = new PDO($dsn, $config['user'], $config['pass']);
        $pdo->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->query = new FluentPDO($pdo);
        $this->query->convertTypes = true;
    }

    /**
     * Get PDO instance
     * @return \PDO connected PDO instance
     */
    protected function __getPdo()
    {
        return $this->query->getPdo();
    }

    /**
     * Get Fluent PDO instance
     * @return \Envms\FluentPDO\Query connected PDO instance
     */
    protected function __getQuery()
    {
        return $this->query;
    }

    /**
     * Return current date and time on database format
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
     * Return current date on database format
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
                reset($direction);
                $param = current($direction);
                $direction = key($direction);
                if (substr_count($field, '?') == 0) {
                    $field = '(' . $field .' = ?)';
                }
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
                'COALESCE(NULLIF(' .
                self::locate(
                    '?',
                    self::concat(['" "', $field])
                ). ', 0), 65535) ASC',
                ' '.$word
            );
            $query = $query->orderBy(
                'COALESCE(NULLIF(' .
                    self::locate('?', $field) . ', 0), 65535) ASC',
                $word
            );
        }
        return $query;
    }

    /**
     * Filter values array
     * @param  array $only Save these fields only, when empty save all fields except id
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

    public static function locate($search, $string)
    {
        if (getenv('DB_DRIVER') == 'sqlite') {
            return 'instr(' . $search . ', ' . $string . ')';
        }
        return 'LOCATE(' . $search . ', ' . $string . ')';
    }

    public static function strftime($fmt, $value)
    {
        if (getenv('DB_DRIVER') == 'sqlite') {
            return "strftime('" . $fmt . "', " . $value .")";
        }
        return "DATE_FORMAT(" . $value . ", '" . $fmt ."')";
    }

    /**
     * In depth begin transaction
     */
    protected function __beginTransaction()
    {
        $this->transactionCounter++;
        if ($this->transactionCounter == 1) {
            return $this->__getPdo()->beginTransaction();
        }
        return $this->transactionCounter == 1;
    }

    /**
     * In depth commit transaction
     */
    protected function __commit()
    {
        if ($this->transactionCounter <= 0) {
            throw new \Exception('No transaction active');
        }
        $this->transactionCounter--;
        if ($this->transactionCounter == 0) {
            return $this->__getPdo()->commit();
        }
        return $this->transactionCounter == 0;
    }

    /**
     * In depth rollback transaction
     */
    protected function __rollBack()
    {
        if ($this->transactionCounter <= 0) {
            throw new \Exception('No transaction active');
        }
        $this->transactionCounter--;
        if ($this->transactionCounter == 0) {
            return $this->__getPdo()->rollBack();
        }
        return $this->transactionCounter == 0;
    }

    public function __call($name, $arguments)
    {
        if (method_exists($this, '__' . $name)) {
            return call_user_func_array(array($this, '__' . $name), $arguments);
        }
        return call_user_func_array(array($this->query, $name), $arguments);
    }

    public static function __callStatic($name, $arguments)
    {
        return call_user_func_array(array(app()->getDatabase(), $name), $arguments);
    }
}
