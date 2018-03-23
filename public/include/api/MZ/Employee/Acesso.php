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
namespace MZ\Employee;

use MZ\Util\Filter;
use MZ\Util\Validator;

/**
 * Permite acesso à uma determinada funcionalidade da lista de permissões
 */
class Acesso extends \MZ\Database\Helper
{

    /**
     * Identificador do acesso
     */
    private $id;
    /**
     * Função a que a permissão se aplica
     */
    private $funcao_id;
    /**
     * Permissão liberada para a função
     */
    private $permissao_id;

    /**
     * Constructor for a new empty instance of Acesso
     * @param array $acesso All field and values to fill the instance
     */
    public function __construct($acesso = [])
    {
        parent::__construct($acesso);
    }

    /**
     * Identificador do acesso
     * @return mixed ID of Acesso
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param  mixed $id new value for ID
     * @return Acesso Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Função a que a permissão se aplica
     * @return mixed Função of Acesso
     */
    public function getFuncaoID()
    {
        return $this->funcao_id;
    }

    /**
     * Set FuncaoID value to new on param
     * @param  mixed $funcao_id new value for FuncaoID
     * @return Acesso Self instance
     */
    public function setFuncaoID($funcao_id)
    {
        $this->funcao_id = $funcao_id;
        return $this;
    }

    /**
     * Permissão liberada para a função
     * @return mixed Permissão of Acesso
     */
    public function getPermissaoID()
    {
        return $this->permissao_id;
    }

    /**
     * Set PermissaoID value to new on param
     * @param  mixed $permissao_id new value for PermissaoID
     * @return Acesso Self instance
     */
    public function setPermissaoID($permissao_id)
    {
        $this->permissao_id = $permissao_id;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param  boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $acesso = parent::toArray($recursive);
        $acesso['id'] = $this->getID();
        $acesso['funcaoid'] = $this->getFuncaoID();
        $acesso['permissaoid'] = $this->getPermissaoID();
        return $acesso;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param  mixed $acesso Associated key -> value to assign into this instance
     * @return Acesso Self instance
     */
    public function fromArray($acesso = [])
    {
        if ($acesso instanceof Acesso) {
            $acesso = $acesso->toArray();
        } elseif (!is_array($acesso)) {
            $acesso = [];
        }
        parent::fromArray($acesso);
        if (!isset($acesso['id'])) {
            $this->setID(null);
        } else {
            $this->setID($acesso['id']);
        }
        if (!isset($acesso['funcaoid'])) {
            $this->setFuncaoID(null);
        } else {
            $this->setFuncaoID($acesso['funcaoid']);
        }
        if (!isset($acesso['permissaoid'])) {
            $this->setPermissaoID(null);
        } else {
            $this->setPermissaoID($acesso['permissaoid']);
        }
        return $this;
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $acesso = parent::publish();
        return $acesso;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param Acesso $original Original instance without modifications
     */
    public function filter($original)
    {
        $this->setID($original->getID());
        $this->setFuncaoID(Filter::number($this->getFuncaoID()));
        $this->setPermissaoID(Filter::number($this->getPermissaoID()));
    }

    /**
     * Clean instance resources like images and docs
     * @param  Acesso $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Acesso in array format
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getFuncaoID())) {
            $errors['funcaoid'] = 'A função não pode ser vazia';
        }
        if (is_null($this->getPermissaoID())) {
            $errors['permissaoid'] = 'A permissão não pode ser vazia';
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
        if (stripos($e->getMessage(), 'UK_Acessos_FuncaoID_PermissaoID') !== false) {
            return new \MZ\Exception\ValidationException([
                'funcaoid' => sprintf(
                    'A função "%s" já está cadastrada',
                    $this->getFuncaoID()
                ),
                'permissaoid' => sprintf(
                    'A permissão "%s" já está cadastrada',
                    $this->getPermissaoID()
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Insert a new Acesso into the database and fill instance from database
     * @return Acesso Self instance
     */
    public function insert()
    {
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = self::getDB()->insertInto('Acessos')->values($values)->execute();
            $acesso = self::findByID($id);
            $this->fromArray($acesso->toArray());
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Acesso with instance values into database for ID
     * @return Acesso Self instance
     */
    public function update()
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador do acesso não foi informado');
        }
        unset($values['id']);
        try {
            self::getDB()
                ->update('Acessos')
                ->set($values)
                ->where('id', $this->getID())
                ->execute();
            $acesso = self::findByID($this->getID());
            $this->fromArray($acesso->toArray());
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
            throw new \Exception('O identificador do acesso não foi informado');
        }
        $result = self::getDB()
            ->deleteFrom('Acessos')
            ->where('id', $this->getID())
            ->execute();
        return $result;
    }

    /**
     * Load one register for it self with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order associative field name -> [-1, 1]
     * @return Acesso Self instance filled or empty
     */
    public function load($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return $this->fromArray($row);
    }

    /**
     * Load into this object from database using, ID
     * @param  int $id id to find Acesso
     * @return Acesso Self filled instance or empty when not found
     */
    public function loadByID($id)
    {
        return $this->load([
            'id' => intval($id),
        ]);
    }

    /**
     * Load into this object from database using, FuncaoID, PermissaoID
     * @param  int $funcao_id função to find Acesso
     * @param  int $permissao_id permissão to find Acesso
     * @return Acesso Self filled instance or empty when not found
     */
    public function loadByFuncaoIDPermissaoID($funcao_id, $permissao_id)
    {
        return $this->load([
            'funcaoid' => intval($funcao_id),
            'permissaoid' => intval($permissao_id),
        ]);
    }

    /**
     * Função a que a permissão se aplica
     * @return \MZ\Employee\Funcao The object fetched from database
     */
    public function findFuncaoID()
    {
        return \MZ\Employee\Funcao::findByID($this->getFuncaoID());
    }

    /**
     * Permissão liberada para a função
     * @return \MZ\System\Permissao The object fetched from database
     */
    public function findPermissaoID()
    {
        return \MZ\System\Permissao::findByID($this->getPermissaoID());
    }

    /**
     * Get allowed keys array
     * @return array allowed keys array
     */
    private static function getAllowedKeys()
    {
        $acesso = new Acesso();
        $allowed = Filter::concatKeys('a.', $acesso->toArray());
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
        return Filter::orderBy($order, $allowed, 'a.');
    }

    /**
     * Filter condition array with allowed fields
     * @param  array $condition condition to filter rows
     * @return array allowed condition
     */
    private static function filterCondition($condition)
    {
        $allowed = self::getAllowedKeys();
        return Filter::keys($condition, $allowed, 'a.');
    }

    /**
     * Fetch data from database with a condition
     * @param  array $condition condition to filter rows
     * @param  array $order order rows
     * @return SelectQuery query object with condition statement
     */
    private static function query($condition = [], $order = [])
    {
        $query = self::getDB()->from('Acessos a');
        $condition = self::filterCondition($condition);
        $query = self::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('a.id ASC');
        return self::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order order rows
     * @return Acesso A filled Acesso or empty instance
     */
    public static function find($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return new Acesso($row);
    }

    /**
     * Find this object on database using, ID
     * @param  int $id id to find Acesso
     * @return Acesso A filled instance or empty when not found
     */
    public static function findByID($id)
    {
        return self::find([
            'id' => intval($id),
        ]);
    }

    /**
     * Find this object on database using, FuncaoID, PermissaoID
     * @param  int $funcao_id função to find Acesso
     * @param  int $permissao_id permissão to find Acesso
     * @return Acesso A filled instance or empty when not found
     */
    public static function findByFuncaoIDPermissaoID($funcao_id, $permissao_id)
    {
        return self::find([
            'funcaoid' => intval($funcao_id),
            'permissaoid' => intval($permissao_id),
        ]);
    }

    /**
     * Find all Acesso
     * @param  array  $condition Condition to get all Acesso
     * @param  array  $order     Order Acesso
     * @param  int    $limit     Limit data into row count
     * @param  int    $offset    Start offset to get rows
     * @return array             List of all rows instanced as Acesso
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
            $result[] = new Acesso($row);
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
