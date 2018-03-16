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
namespace MZ\Location;

use MZ\Util\Filter;
use MZ\Util\Validator;

/**
 * Estado federativo de um país
 */
class Estado extends \MZ\Database\Helper
{

    /**
     * Identificador do estado
     */
    private $id;
    /**
     * País a qual esse estado pertence
     */
    private $pais_id;
    /**
     * Nome do estado
     */
    private $nome;
    /**
     * Sigla do estado
     */
    private $uf;

    /**
     * Constructor for a new empty instance of Estado
     * @param array $estado All field and values to fill the instance
     */
    public function __construct($estado = array())
    {
        parent::__construct($estado);
    }

    /**
     * Identificador do estado
     * @return mixed ID of Estado
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param  mixed $id new value for ID
     * @return Estado Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * País a qual esse estado pertence
     * @return mixed País of Estado
     */
    public function getPaisID()
    {
        return $this->pais_id;
    }

    /**
     * Set PaisID value to new on param
     * @param  mixed $pais_id new value for PaisID
     * @return Estado Self instance
     */
    public function setPaisID($pais_id)
    {
        $this->pais_id = $pais_id;
        return $this;
    }

    /**
     * Nome do estado
     * @return mixed Nome of Estado
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set Nome value to new on param
     * @param  mixed $nome new value for Nome
     * @return Estado Self instance
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * Sigla do estado
     * @return mixed UF of Estado
     */
    public function getUF()
    {
        return $this->uf;
    }

    /**
     * Set UF value to new on param
     * @param  mixed $uf new value for UF
     * @return Estado Self instance
     */
    public function setUF($uf)
    {
        $this->uf = $uf;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param  boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $estado = parent::toArray($recursive);
        $estado['id'] = $this->getID();
        $estado['paisid'] = $this->getPaisID();
        $estado['nome'] = $this->getNome();
        $estado['uf'] = $this->getUF();
        return $estado;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param  mixed $estado Associated key -> value to assign into this instance
     * @return Estado Self instance
     */
    public function fromArray($estado = array())
    {
        if ($estado instanceof Estado) {
            $estado = $estado->toArray();
        } elseif (!is_array($estado)) {
            $estado = array();
        }
        parent::fromArray($estado);
        if (!isset($estado['id'])) {
            $this->setID(null);
        } else {
            $this->setID($estado['id']);
        }
        if (!isset($estado['paisid'])) {
            $this->setPaisID(null);
        } else {
            $this->setPaisID($estado['paisid']);
        }
        if (!isset($estado['nome'])) {
            $this->setNome(null);
        } else {
            $this->setNome($estado['nome']);
        }
        if (!isset($estado['uf'])) {
            $this->setUF(null);
        } else {
            $this->setUF($estado['uf']);
        }
        return $this;
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $estado = parent::publish();
        return $estado;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param Estado $original Original instance without modifications
     */
    public function filter($original)
    {
        $this->setID($original->getID());
        $this->setPaisID(Filter::number($this->getPaisID()));
        $this->setNome(Filter::string($this->getNome()));
        $this->setUF(Filter::string($this->getUF()));
    }

    /**
     * Clean instance resources like images and docs
     * @param  Estado $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Estado in array format
     */
    public function validate()
    {
        $errors = array();
        if (is_null($this->getPaisID())) {
            $errors['paisid'] = 'O País não pode ser vazio';
        }
        if (is_null($this->getNome())) {
            $errors['nome'] = 'O Nome não pode ser vazio';
        }
        if (is_null($this->getUF())) {
            $errors['uf'] = 'A UF não pode ser vazia';
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
            return new \MZ\Exception\ValidationException(array(
                'id' => vsprintf(
                    'O ID "%s" já está cadastrado',
                    array($this->getID())
                ),
            ));
        }
        if (stripos($e->getMessage(), 'PaisID_Nome_UNIQUE') !== false) {
            return new \MZ\Exception\ValidationException(array(
                'paisid' => vsprintf(
                    'O País "%s" já está cadastrado',
                    array($this->getPaisID())
                ),
                'nome' => vsprintf(
                    'O Nome "%s" já está cadastrado',
                    array($this->getNome())
                ),
            ));
        }
        if (stripos($e->getMessage(), 'PaisID_UF_UNIQUE') !== false) {
            return new \MZ\Exception\ValidationException(array(
                'paisid' => vsprintf(
                    'O País "%s" já está cadastrado',
                    array($this->getPaisID())
                ),
                'uf' => vsprintf(
                    'A UF "%s" já está cadastrada',
                    array($this->getUF())
                ),
            ));
        }
        return parent::translate($e);
    }

    /**
     * Find this object on database using, ID
     * @param  int $id id to find Estado
     * @return Estado A filled instance or empty when not found
     */
    public static function findByID($id)
    {
        return self::find(array(
            'id' => intval($id),
        ));
    }

    /**
     * Find this object on database using, PaisID, Nome
     * @param  int $pais_id país to find Estado
     * @param  string $nome nome to find Estado
     * @return Estado A filled instance or empty when not found
     */
    public static function findByPaisIDNome($pais_id, $nome)
    {
        return self::find(array(
            'paisid' => intval($pais_id),
            'nome' => strval($nome),
        ));
    }

    /**
     * Find this object on database using, PaisID, UF
     * @param  int $pais_id país to find Estado
     * @param  string $uf uf to find Estado
     * @return Estado A filled instance or empty when not found
     */
    public static function findByPaisIDUF($pais_id, $uf)
    {
        return self::find(array(
            'paisid' => intval($pais_id),
            'uf' => strval($uf),
        ));
    }

    /**
     * Filter order array
     * @param  mixed $order order string or array to parse and filter allowed
     * @return array allowed associative order
     */
    private static function filterOrder($order)
    {
        $estado = new Estado();
        $allowed = $estado->toArray();
        return Filter::orderBy($order, $allowed);
    }

    /**
     * Filter condition array with allowed fields
     * @param  array $condition condition to filter rows
     * @return array allowed condition
     */
    private static function filterCondition($condition)
    {
        $estado = new Estado();
        $allowed = $estado->toArray();
        return Filter::keys($condition, $allowed);
    }

    /**
     * Fetch data from database with a condition
     * @param  array $condition condition to filter rows
     * @param  array $order order rows
     * @return SelectQuery query object with condition statement
     */
    private static function query($condition = array(), $order = array())
    {
        $query = self::getDB()->from('Estados');
        $condition = self::filterCondition($condition);
        $query = self::buildOrderBy($query, self::filterOrder($order));
        return $query->where($condition);
    }

    /**
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order order rows
     * @return Estado A filled Estado or empty instance
     */
    public static function find($condition, $order = array())
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch();
        if ($row === false) {
            $row = array();
        }
        return new Estado($row);
    }

    /**
     * Fetch all rows from database with matched condition critery
     * @param  array $condition condition to filter rows
     * @param  integer $limit number of rows to get, null for all
     * @param  integer $offset start index to get rows, null for begining
     * @return array All rows instanced and filled
     */
    public static function findAll($condition = array(), $order = array(), $limit = null, $offset = null)
    {
        $query = self::query($condition, $order);
        if (!is_null($limit)) {
            $query = $query->limit($limit);
        }
        if (!is_null($offset)) {
            $query = $query->offset($offset);
        }
        $rows = $query->fetchAll();
        $result = array();
        foreach ($rows as $row) {
            $result[] = new Estado($row);
        }
        return $result;
    }

    /**
     * Insert a new Estado into the database and fill instance from database
     * @return Estado Self instance
     */
    public function insert()
    {
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = self::getDB()->insertInto('Estados')->values($values)->execute();
            $estado = self::findByID($id);
            $this->fromArray($estado->toArray());
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Estado with instance values into database for ID
     * @return Estado Self instance
     */
    public function update()
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador do estado não foi informado');
        }
        unset($values['id']);
        try {
            self::getDB()
                ->update('Estados')
                ->set($values)
                ->where('id', $this->getID())
                ->execute();
            $estado = self::findByID($this->getID());
            $this->fromArray($estado->toArray());
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Save the Estado into the database
     * @return Estado Self instance
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
            throw new \Exception('O identificador do estado não foi informado');
        }
        $result = self::getDB()
            ->deleteFrom('Estados')
            ->where('id', $this->getID())
            ->execute();
        return $result;
    }

    /**
     * Count all rows from database with matched condition critery
     * @param  array $condition condition to filter rows
     * @return integer Quantity of rows
     */
    public static function count($condition = array())
    {
        $query = self::query($condition);
        return $query->count();
    }

    /**
     * País a qual esse estado pertence
     * @return \MZ\Location\Pais The object fetched from database
     */
    public function findPaisID()
    {
        return \MZ\Location\Pais::findByID($this->getPaisID());
    }
}
