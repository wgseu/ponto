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
namespace MZ\Provider;

use MZ\Util\Filter;
use MZ\Util\Validator;
use MZ\Database\DB;
use MZ\Database\SyncModel;
use MZ\Exception\ValidationException;

/**
 * Função ou atribuição de tarefas à um prestador
 */
class Funcao extends SyncModel
{

    /**
     * Identificador da função
     */
    private $id;
    /**
     * Descreve o nome da função
     */
    private $descricao;
    /**
     * Remuneracao pelas atividades exercidas, não está incluso comissões
     */
    private $remuneracao;

    /**
     * Constructor for a new empty instance of Funcao
     * @param array $funcao All field and values to fill the instance
     */
    public function __construct($funcao = [])
    {
        parent::__construct($funcao);
    }

    /**
     * Identificador da função
     * @return mixed ID of Funcao
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
     * Descreve o nome da função
     * @return mixed Descrição of Funcao
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Set Descricao value to new on param
     * @param  mixed $descricao new value for Descricao
     * @return self Self instance
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * Remuneracao pelas atividades exercidas, não está incluso comissões
     * @return mixed Remuneração of Funcao
     */
    public function getRemuneracao()
    {
        return $this->remuneracao;
    }

    /**
     * Set Remuneracao value to new on param
     * @param  mixed $remuneracao new value for Remuneracao
     * @return self Self instance
     */
    public function setRemuneracao($remuneracao)
    {
        $this->remuneracao = $remuneracao;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param  boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $funcao = parent::toArray($recursive);
        $funcao['id'] = $this->getID();
        $funcao['descricao'] = $this->getDescricao();
        $funcao['remuneracao'] = $this->getRemuneracao();
        return $funcao;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param  mixed $funcao Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($funcao = [])
    {
        if ($funcao instanceof self) {
            $funcao = $funcao->toArray();
        } elseif (!is_array($funcao)) {
            $funcao = [];
        }
        parent::fromArray($funcao);
        if (!isset($funcao['id'])) {
            $this->setID(null);
        } else {
            $this->setID($funcao['id']);
        }
        if (!isset($funcao['descricao'])) {
            $this->setDescricao(null);
        } else {
            $this->setDescricao($funcao['descricao']);
        }
        if (!isset($funcao['remuneracao'])) {
            $this->setRemuneracao(null);
        } else {
            $this->setRemuneracao($funcao['remuneracao']);
        }
        return $this;
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $funcao = parent::publish();
        return $funcao;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param self $original Original instance without modifications
     */
    public function filter($original)
    {
        $this->setID($original->getID());
        $this->setDescricao(Filter::string($this->getDescricao()));
        $this->setRemuneracao(Filter::money($this->getRemuneracao()));
    }

    /**
     * Clean instance resources like images and docs
     * @param  Funcao $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return mixed[] All field of Funcao in array format
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getDescricao())) {
            $errors['descricao'] = 'A descrição não pode ser vazia';
        }
        if (is_null($this->getRemuneracao())) {
            $errors['salariobase'] = 'A remuneração base não pode ser vazia';
        } elseif ($this->getRemuneracao() < 0) {
            $errors['salariobase'] = 'A remuneração base não pode ser negativa';
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
        if (contains(['Descricao', 'UNIQUE'], $e->getMessage())) {
            return new ValidationException([
                'descricao' => sprintf(
                    'A descrição "%s" já está cadastrada',
                    $this->getDescricao()
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Insert a new Função into the database and fill instance from database
     * @return Funcao Self instance
     */
    public function insert()
    {
        $this->setID(null);
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = DB::insertInto('Funcoes')->values($values)->execute();
            $this->setID($id);
            $this->loadByID();
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Função with instance values into database for ID
     * @param  array $only Save these fields only, when empty save all fields except id
     * @return Funcao Self instance
     */
    public function update($only = [])
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador da função não foi informado');
        }
        $values = DB::filterValues($values, $only, false);
        try {
            DB::update('Funcoes')
                ->set($values)
                ->where('id', $this->getID())
                ->execute();
            $this->loadByID();
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
            throw new \Exception('O identificador da função não foi informado');
        }
        $result = DB::deleteFrom('Funcoes')
            ->where('id', $this->getID())
            ->execute();
        return $result;
    }

    /**
     * Load one register for it self with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order associative field name -> [-1, 1]
     * @return Funcao Self instance filled or empty
     */
    public function load($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return $this->fromArray($row);
    }

    /**
     * Load into this object from database using, Descricao
     * @param  string $descricao descrição to find Função
     * @return Funcao Self filled instance or empty when not found
     */
    public function loadByDescricao($descricao)
    {
        return $this->load([
            'descricao' => strval($descricao),
        ]);
    }

    /**
     * Get allowed keys array
     * @return array allowed keys array
     */
    private static function getAllowedKeys()
    {
        $funcao = new Funcao();
        $allowed = Filter::concatKeys('f.', $funcao->toArray());
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
        return Filter::orderBy($order, $allowed, 'f.');
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
            $field = 'f.descricao LIKE ?';
            $condition[$field] = '%'.$search.'%';
            $allowed[$field] = true;
            unset($condition['search']);
        }
        return Filter::keys($condition, $allowed, 'f.');
    }

    /**
     * Fetch data from database with a condition
     * @param  array $condition condition to filter rows
     * @param  array $order order rows
     * @return SelectQuery query object with condition statement
     */
    private static function query($condition = [], $order = [])
    {
        $query = DB::from('Funcoes f');
        $condition = self::filterCondition($condition);
        $query = DB::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('f.descricao ASC');
        $query = $query->orderBy('f.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order order rows
     * @return Funcao A filled Função or empty instance
     */
    public static function find($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return new Funcao($row);
    }

    /**
     * Find this object on database using, Descricao
     * @param  string $descricao descrição to find Função
     * @return Funcao A filled instance or empty when not found
     */
    public static function findByDescricao($descricao)
    {
        $result = new self();
        return $result->loadByDescricao($descricao);
    }

    /**
     * Find all Função
     * @param  array  $condition Condition to get all Função
     * @param  array  $order     Order Função
     * @param  int    $limit     Limit data into row count
     * @param  int    $offset    Start offset to get rows
     * @return array             List of all rows instanced as Funcao
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
            $result[] = new Funcao($row);
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
