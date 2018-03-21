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
 * @author  Francimar Alves <mazinsw@gmail.com>
 */
namespace MZ\Invoice;

use MZ\Util\Filter;
use MZ\Util\Validator;

/**
 * Regimes tributários
 */
class Regime extends \MZ\Database\Helper
{

    /**
     * Identificador do regime tributário
     */
    private $id;
    /**
     * Código do regime tributário
     */
    private $codigo;
    /**
     * Descrição do regime tributário
     */
    private $descricao;

    /**
     * Constructor for a new empty instance of Regime
     * @param array $regime All field and values to fill the instance
     */
    public function __construct($regime = [])
    {
        parent::__construct($regime);
    }

    /**
     * Identificador do regime tributário
     * @return mixed ID of Regime
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param  mixed $id new value for ID
     * @return Regime Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Código do regime tributário
     * @return mixed Código of Regime
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set Codigo value to new on param
     * @param  mixed $codigo new value for Codigo
     * @return Regime Self instance
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
        return $this;
    }

    /**
     * Descrição do regime tributário
     * @return mixed Descrição of Regime
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Set Descricao value to new on param
     * @param  mixed $descricao new value for Descricao
     * @return Regime Self instance
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param  boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $regime = parent::toArray($recursive);
        $regime['id'] = $this->getID();
        $regime['codigo'] = $this->getCodigo();
        $regime['descricao'] = $this->getDescricao();
        return $regime;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param  mixed $regime Associated key -> value to assign into this instance
     * @return Regime Self instance
     */
    public function fromArray($regime = [])
    {
        if ($regime instanceof Regime) {
            $regime = $regime->toArray();
        } elseif (!is_array($regime)) {
            $regime = [];
        }
        parent::fromArray($regime);
        if (!isset($regime['id'])) {
            $this->setID(null);
        } else {
            $this->setID($regime['id']);
        }
        if (!isset($regime['codigo'])) {
            $this->setCodigo(null);
        } else {
            $this->setCodigo($regime['codigo']);
        }
        if (!isset($regime['descricao'])) {
            $this->setDescricao(null);
        } else {
            $this->setDescricao($regime['descricao']);
        }
        return $this;
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $regime = parent::publish();
        return $regime;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param Regime $original Original instance without modifications
     */
    public function filter($original)
    {
        $this->setID($original->getID());
        $this->setCodigo(Filter::number($this->getCodigo()));
        $this->setDescricao(Filter::string($this->getDescricao()));
    }

    /**
     * Clean instance resources like images and docs
     * @param  Regime $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Regime in array format
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getCodigo())) {
            $errors['codigo'] = 'O código não pode ser vazio';
        }
        if (is_null($this->getDescricao())) {
            $errors['descricao'] = 'A descrição não pode ser vazia';
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
        if (stripos($e->getMessage(), 'PRIMARY') !== false) {
            return new \MZ\Exception\ValidationException([
                'id' => sprintf(
                    'O id "%s" já está cadastrado',
                    $this->getID()
                ),
            ]);
        }
        if (stripos($e->getMessage(), 'Codigo_UNIQUE') !== false) {
            return new \MZ\Exception\ValidationException([
                'codigo' => sprintf(
                    'O código "%s" já está cadastrado',
                    $this->getCodigo()
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Find this object on database using, ID
     * @param  int $id id to find Regime
     * @return Regime A filled instance or empty when not found
     */
    public static function findByID($id)
    {
        return self::find([
            'id' => intval($id),
        ]);
    }

    /**
     * Find this object on database using, Codigo
     * @param  int $codigo código to find Regime
     * @return Regime A filled instance or empty when not found
     */
    public static function findByCodigo($codigo)
    {
        return self::find([
            'codigo' => intval($codigo),
        ]);
    }

    /**
     * Get allowed keys array
     * @return array allowed keys array
     */
    private static function getAllowedKeys()
    {
        $regime = new Regime();
        $allowed = Filter::concatKeys('r.', $regime->toArray());
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
        return Filter::orderBy($order, $allowed, 'r.');
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
            $field = 'r.descricao LIKE ?';
            $condition[$field] = '%'.$search.'%';
            $allowed[$field] = true;
            unset($condition['search']);
        }
        return Filter::keys($condition, $allowed, 'r.');
    }

    /**
     * Fetch data from database with a condition
     * @param  array $condition condition to filter rows
     * @param  array $order order rows
     * @return SelectQuery query object with condition statement
     */
    private static function query($condition = [], $order = [])
    {
        $query = self::getDB()->from('Regimes r');
        $condition = self::filterCondition($condition);
        $query = self::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('r.descricao ASC');
        $query = $query->orderBy('r.id ASC');
        return self::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order order rows
     * @return Regime A filled Regime or empty instance
     */
    public static function find($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch();
        if ($row === false) {
            $row = [];
        }
        return new Regime($row);
    }

    /**
     * Fetch all rows from database with matched condition critery
     * @param  array $condition condition to filter rows
     * @param  integer $limit number of rows to get, null for all
     * @param  integer $offset start index to get rows, null for begining
     * @return array All rows instanced and filled
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
            $result[] = new Regime($row);
        }
        return $result;
    }

    /**
     * Insert a new Regime into the database and fill instance from database
     * @return Regime Self instance
     */
    public function insert()
    {
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = self::getDB()->insertInto('Regimes')->values($values)->execute();
            $regime = self::findByID($id);
            $this->fromArray($regime->toArray());
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Regime with instance values into database for ID
     * @return Regime Self instance
     */
    public function update()
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador do regime não foi informado');
        }
        unset($values['id']);
        try {
            self::getDB()
                ->update('Regimes')
                ->set($values)
                ->where('id', $this->getID())
                ->execute();
            $regime = self::findByID($this->getID());
            $this->fromArray($regime->toArray());
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Save the Regime into the database
     * @return Regime Self instance
     */
    public function save()
    {
        if ($this->exists()) {
            return $this->update();
        }
        return $this->insert();
    }

    /**
     * Delete this instance from database using ID
     * @return integer Number of rows deleted (Max 1)
     */
    public function delete()
    {
        if (!$this->exists()) {
            throw new \Exception('O identificador do regime não foi informado');
        }
        $result = self::getDB()
            ->deleteFrom('Regimes')
            ->where('id', $this->getID())
            ->execute();
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
