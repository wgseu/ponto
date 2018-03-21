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
namespace MZ\System;

use MZ\Util\Filter;
use MZ\Util\Validator;

/**
 * Informa a listagem de todas as funções do sistema
 */
class Permissao extends \MZ\Database\Helper
{

    /**
     * Identificador da permissão
     */
    private $id;
    /**
     * Categoriza um grupo de permissões
     */
    private $funcionalidade_id;
    /**
     * Nome da permissão, único no sistema
     */
    private $nome;
    /**
     * Descreve a permissão
     */
    private $descricao;

    /**
     * Constructor for a new empty instance of Permissao
     * @param array $permissao All field and values to fill the instance
     */
    public function __construct($permissao = [])
    {
        parent::__construct($permissao);
    }

    /**
     * Identificador da permissão
     * @return mixed ID of Permissao
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param  mixed $id new value for ID
     * @return Permissao Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Categoriza um grupo de permissões
     * @return mixed Funcionalidade of Permissao
     */
    public function getFuncionalidadeID()
    {
        return $this->funcionalidade_id;
    }

    /**
     * Set FuncionalidadeID value to new on param
     * @param  mixed $funcionalidade_id new value for FuncionalidadeID
     * @return Permissao Self instance
     */
    public function setFuncionalidadeID($funcionalidade_id)
    {
        $this->funcionalidade_id = $funcionalidade_id;
        return $this;
    }

    /**
     * Nome da permissão, único no sistema
     * @return mixed Nome of Permissao
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set Nome value to new on param
     * @param  mixed $nome new value for Nome
     * @return Permissao Self instance
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * Descreve a permissão
     * @return mixed Descrição of Permissao
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Set Descricao value to new on param
     * @param  mixed $descricao new value for Descricao
     * @return Permissao Self instance
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
        $permissao = parent::toArray($recursive);
        $permissao['id'] = $this->getID();
        $permissao['funcionalidadeid'] = $this->getFuncionalidadeID();
        $permissao['nome'] = $this->getNome();
        $permissao['descricao'] = $this->getDescricao();
        return $permissao;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param  mixed $permissao Associated key -> value to assign into this instance
     * @return Permissao Self instance
     */
    public function fromArray($permissao = [])
    {
        if ($permissao instanceof Permissao) {
            $permissao = $permissao->toArray();
        } elseif (!is_array($permissao)) {
            $permissao = [];
        }
        parent::fromArray($permissao);
        if (!isset($permissao['id'])) {
            $this->setID(null);
        } else {
            $this->setID($permissao['id']);
        }
        if (!isset($permissao['funcionalidadeid'])) {
            $this->setFuncionalidadeID(null);
        } else {
            $this->setFuncionalidadeID($permissao['funcionalidadeid']);
        }
        if (!isset($permissao['nome'])) {
            $this->setNome(null);
        } else {
            $this->setNome($permissao['nome']);
        }
        if (!isset($permissao['descricao'])) {
            $this->setDescricao(null);
        } else {
            $this->setDescricao($permissao['descricao']);
        }
        return $this;
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $permissao = parent::publish();
        return $permissao;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param Permissao $original Original instance without modifications
     */
    public function filter($original)
    {
        $this->setID($original->getID());
        $this->setFuncionalidadeID(Filter::number($this->getFuncionalidadeID()));
        $this->setNome(Filter::string($this->getNome()));
        $this->setDescricao(Filter::string($this->getDescricao()));
    }

    /**
     * Clean instance resources like images and docs
     * @param  Permissao $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Permissao in array format
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getFuncionalidadeID())) {
            $errors['funcionalidadeid'] = 'A funcionalidade não pode ser vazia';
        }
        if (is_null($this->getNome())) {
            $errors['nome'] = 'A nome não pode ser vazia';
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
        if (stripos($e->getMessage(), 'Nome_UNIQUE') !== false) {
            return new \MZ\Exception\ValidationException([
                'nome' => sprintf(
                    'A nome "%s" já está cadastrada',
                    $this->getNome()
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Find this object on database using, ID
     * @param  int $id id to find Permissão
     * @return Permissao A filled instance or empty when not found
     */
    public static function findByID($id)
    {
        return self::find([
            'id' => intval($id),
        ]);
    }

    /**
     * Find this object on database using, Nome
     * @param  string $nome nome to find Permissão
     * @return Permissao A filled instance or empty when not found
     */
    public static function findByNome($nome)
    {
        return self::find([
            'nome' => strval($nome),
        ]);
    }

    /**
     * Get allowed keys array
     * @return array allowed keys array
     */
    private static function getAllowedKeys()
    {
        $permissao = new Permissao();
        $allowed = Filter::concatKeys('p.', $permissao->toArray());
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
        return Filter::orderBy($order, $allowed, 'p.');
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
            $field = 'p.descricao LIKE ?';
            $condition[$field] = '%'.$search.'%';
            $allowed[$field] = true;
            unset($condition['search']);
        }
        return Filter::keys($condition, $allowed, 'p.');
    }

    /**
     * Fetch data from database with a condition
     * @param  array $condition condition to filter rows
     * @param  array $order order rows
     * @return SelectQuery query object with condition statement
     */
    private static function query($condition = [], $order = [])
    {
        $query = self::getDB()->from('Permissoes p');
        $condition = self::filterCondition($condition);
        $query = self::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('p.descricao ASC');
        $query = $query->orderBy('p.id ASC');
        return self::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order order rows
     * @return Permissao A filled Permissão or empty instance
     */
    public static function find($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch();
        if ($row === false) {
            $row = [];
        }
        return new Permissao($row);
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
            $result[] = new Permissao($row);
        }
        return $result;
    }

    /**
     * Insert a new Permissão into the database and fill instance from database
     * @return Permissao Self instance
     */
    public function insert()
    {
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = self::getDB()->insertInto('Permissoes')->values($values)->execute();
            $permissao = self::findByID($id);
            $this->fromArray($permissao->toArray());
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Permissão with instance values into database for ID
     * @return Permissao Self instance
     */
    public function update()
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador da permissão não foi informado');
        }
        unset($values['id']);
        try {
            self::getDB()
                ->update('Permissoes')
                ->set($values)
                ->where('id', $this->getID())
                ->execute();
            $permissao = self::findByID($this->getID());
            $this->fromArray($permissao->toArray());
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Save the Permissão into the database
     * @return Permissao Self instance
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
            throw new \Exception('O identificador da permissão não foi informado');
        }
        $result = self::getDB()
            ->deleteFrom('Permissoes')
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

    /**
     * Categoriza um grupo de permissões
     * @return \MZ\System\Funcionalidade The object fetched from database
     */
    public function findFuncionalidadeID()
    {
        return \MZ\System\Funcionalidade::findByID($this->getFuncionalidadeID());
    }
}
