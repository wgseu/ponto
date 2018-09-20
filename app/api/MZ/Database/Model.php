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
 * Implement common model operations on database CRUD and searches
 */
abstract class Model
{
    /**
     * Constructor for a new empty instance of Model
     * @param array $model All field and values to fill the instance
     */
    public function __construct($model = [])
    {
        $this->fromArray($model);
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
     * @param  mixed $model Associated key -> value to assign into this instance
     * @return Model Self instance
     */
    public function fromArray($model = [])
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
        if ($e instanceof \PDOException &&
            preg_match(
                '/SQLSTATE\[\w+\]: <<[^>]+>>: \d+ (.*)/',
                $e->getMessage(),
                $matches
            )
        ) {
            return new \Exception($matches[1], 45000);
        }
        return $e;
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
     * @return array All field of Model in array format
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
     * @return mixed Self instance
     */
    abstract public function update($only = []);

    /**
     * Delete this instance from database using ID
     * @return integer Number of rows deleted (Max 1)
     */
    abstract public function delete();

    /**
     * Save a new or a existing instance into the database and fill instance from database
     * @param  array $only Save these fields only, when empty save all fields except id
     * @return Model Self instance
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
     * @return Model Self filled instance or empty when not found
     */
    public function loadByID($id)
    {
        return $this->load([
            'id' => intval($id),
        ]);
    }

    /**
     * Find this object on database using, ID
     * @param  int $id id to find object on database
     * @return Model A filled instance or empty when not found
     */
    public static function findByID($id)
    {
        $result = new static();
        return $result->loadByID($id);
    }
}
