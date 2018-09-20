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
namespace MZ\System;

use MZ\Util\Mask;
use MZ\Util\Filter;
use MZ\Util\Validator;
use MZ\Database\DB;
use MZ\Database\Model;
use MZ\Exception\ValidationException;

/**
 * Lista de servidores que fazem sincronizações
 */
class Servidor extends Model
{

    /**
     * Identificador do servidor no banco de dados
     */
    private $id;
    /**
     * Identificador único do servidor, usando para identificação na
     * sincronização
     */
    private $guid;
    /**
     * Informa até onde foi sincronzado os dados desse servidor, sempre nulo no
     * proprio servidor
     */
    private $sincronizado_ate;
    /**
     * Data da última sincronização com esse servidor
     */
    private $ultima_sincronizacao;

    /**
     * Constructor for a new empty instance of Servidor
     * @param array $servidor All field and values to fill the instance
     */
    public function __construct($servidor = [])
    {
        parent::__construct($servidor);
    }

    /**
     * Identificador do servidor no banco de dados
     * @return mixed ID of Servidor
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param  mixed $id new value for ID
     * @return self Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Identificador único do servidor, usando para identificação na
     * sincronização
     * @return mixed Identificador único of Servidor
     */
    public function getGUID()
    {
        return $this->guid;
    }

    /**
     * Set GUID value to new on param
     * @param  mixed $guid new value for GUID
     * @return self Self instance
     */
    public function setGUID($guid)
    {
        $this->guid = $guid;
        return $this;
    }

    /**
     * Informa até onde foi sincronzado os dados desse servidor, sempre nulo no
     * proprio servidor
     * @return mixed Sincronizado até of Servidor
     */
    public function getSincronizadoAte()
    {
        return $this->sincronizado_ate;
    }

    /**
     * Set SincronizadoAte value to new on param
     * @param  mixed $sincronizado_ate new value for SincronizadoAte
     * @return self Self instance
     */
    public function setSincronizadoAte($sincronizado_ate)
    {
        $this->sincronizado_ate = $sincronizado_ate;
        return $this;
    }

    /**
     * Data da última sincronização com esse servidor
     * @return mixed Data da última sincronização of Servidor
     */
    public function getUltimaSincronizacao()
    {
        return $this->ultima_sincronizacao;
    }

    /**
     * Set UltimaSincronizacao value to new on param
     * @param  mixed $ultima_sincronizacao new value for UltimaSincronizacao
     * @return self Self instance
     */
    public function setUltimaSincronizacao($ultima_sincronizacao)
    {
        $this->ultima_sincronizacao = $ultima_sincronizacao;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param  boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $servidor = parent::toArray($recursive);
        $servidor['id'] = $this->getID();
        $servidor['guid'] = $this->getGUID();
        $servidor['sincronizadoate'] = $this->getSincronizadoAte();
        $servidor['ultimasincronizacao'] = $this->getUltimaSincronizacao();
        return $servidor;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param  mixed $servidor Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($servidor = [])
    {
        if ($servidor instanceof self) {
            $servidor = $servidor->toArray();
        } elseif (!is_array($servidor)) {
            $servidor = [];
        }
        parent::fromArray($servidor);
        if (!isset($servidor['id'])) {
            $this->setID(null);
        } else {
            $this->setID($servidor['id']);
        }
        if (!isset($servidor['guid'])) {
            $this->setGUID(null);
        } else {
            $this->setGUID($servidor['guid']);
        }
        if (!array_key_exists('sincronizadoate', $servidor)) {
            $this->setSincronizadoAte(null);
        } else {
            $this->setSincronizadoAte($servidor['sincronizadoate']);
        }
        if (!array_key_exists('ultimasincronizacao', $servidor)) {
            $this->setUltimaSincronizacao(null);
        } else {
            $this->setUltimaSincronizacao($servidor['ultimasincronizacao']);
        }
        return $this;
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $servidor = parent::publish();
        return $servidor;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param self $original Original instance without modifications
     */
    public function filter($original)
    {
        $this->setID($original->getID());
        $this->setGUID(Filter::string($this->getGUID()));
        $this->setSincronizadoAte(Filter::number($this->getSincronizadoAte()));
        $this->setUltimaSincronizacao(Filter::datetime($this->getUltimaSincronizacao()));
    }

    /**
     * Clean instance resources like images and docs
     * @param  self $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return mixed[] All field of Servidor in array format
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getGUID())) {
            $errors['guid'] = 'O identificador único não pode ser vazio';
        }
        if (!empty($errors)) {
            throw new ValidationException($errors);
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
        if (contains(['ID', 'UNIQUE'], $e->getMessage())) {
            return new ValidationException([
                'id' => sprintf(
                    'O id "%s" já está cadastrado',
                    $this->getID()
                ),
            ]);
        }
        if (contains(['GUID', 'UNIQUE'], $e->getMessage())) {
            return new ValidationException([
                'guid' => sprintf(
                    'O identificador único "%s" já está cadastrado',
                    $this->getGUID()
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Insert a new Servidor into the database and fill instance from database
     * @return self Self instance
     */
    public function insert()
    {
        $this->setID(null);
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = DB::insertInto('Servidores')->values($values)->execute();
            $this->loadByID($id);
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Servidor with instance values into database for ID
     * @param  array $only Save these fields only, when empty save all fields except id
     * @return int rows affected
     */
    public function update($only = [])
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador do servidor não foi informado');
        }
        $values = DB::filterValues($values, $only, false);
        try {
            $affected = DB::update('Servidores')
                ->set($values)
                ->where(['id' => $this->getID()])
                ->execute();
            $this->loadByID($this->getID());
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $affected;
    }

    /**
     * Delete this instance from database using ID
     * @return integer Number of rows deleted (Max 1)
     */
    public function delete()
    {
        if (!$this->exists()) {
            throw new \Exception('O identificador do servidor não foi informado');
        }
        $result = DB::deleteFrom('Servidores')
            ->where('id', $this->getID())
            ->execute();
        return $result;
    }

    /**
     * Load one register for it self with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order associative field name -> [-1, 1]
     * @return self Self instance filled or empty
     */
    public function load($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return $this->fromArray($row);
    }

    /**
     * Load into this object from database using, GUID
     * @return self Self filled instance or empty when not found
     */
    public function loadByGUID()
    {
        return $this->load([
            'guid' => strval($this->getGUID()),
        ]);
    }

    /**
     * Get allowed keys array
     * @return array allowed keys array
     */
    private static function getAllowedKeys()
    {
        $servidor = new self();
        $allowed = Filter::concatKeys('s.', $servidor->toArray());
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
        return Filter::orderBy($order, $allowed, 's.');
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
            $field = 's.guid LIKE ?';
            $condition[$field] = '%'.$search.'%';
            $allowed[$field] = true;
            unset($condition['search']);
        }
        return Filter::keys($condition, $allowed, 's.');
    }

    /**
     * Fetch data from database with a condition
     * @param  array $condition condition to filter rows
     * @param  array $order order rows
     * @return SelectQuery query object with condition statement
     */
    private static function query($condition = [], $order = [])
    {
        $query = DB::from('Servidores s');
        $condition = self::filterCondition($condition);
        $query = DB::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('s.guid ASC');
        $query = $query->orderBy('s.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order order rows
     * @return self A filled Servidor or empty instance
     */
    public static function find($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return new self($row);
    }

    /**
     * Find this object on database using, GUID
     * @param  string $guid identificador único to find Servidor
     * @return self A filled instance or empty when not found
     */
    public static function findByGUID($guid)
    {
        $result = new self();
        $result->setGUID($guid);
        return $result->loadByGUID();
    }

    /**
     * Find all Servidor
     * @param  array  $condition Condition to get all Servidor
     * @param  array  $order     Order Servidor
     * @param  int    $limit     Limit data into row count
     * @param  int    $offset    Start offset to get rows
     * @return self[]             List of all rows instanced as Servidor
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
            $result[] = new self($row);
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
