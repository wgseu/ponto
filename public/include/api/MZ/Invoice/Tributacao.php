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
namespace MZ\Invoice;

use MZ\Util\Filter;
use MZ\Util\Validator;

/**
 * Informação tributária dos produtos
 */
class Tributacao extends \MZ\Database\Helper
{

    /**
     * Identificador da tributação
     */
    private $id;
    /**
     * Código NCM (Nomenclatura Comum do Mercosul) do produto
     */
    private $ncm;
    /**
     * Código CEST do produto (Opcional)
     */
    private $cest;
    /**
     * Origem do produto
     */
    private $origem_id;
    /**
     * CFOP do produto
     */
    private $operacao_id;
    /**
     * Imposto do produto
     */
    private $imposto_id;

    /**
     * Constructor for a new empty instance of Tributacao
     * @param array $tributacao All field and values to fill the instance
     */
    public function __construct($tributacao = [])
    {
        parent::__construct($tributacao);
    }

    /**
     * Identificador da tributação
     * @return mixed ID of Tributacao
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param  mixed $id new value for ID
     * @return Tributacao Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Código NCM (Nomenclatura Comum do Mercosul) do produto
     * @return mixed NCM of Tributacao
     */
    public function getNCM()
    {
        return $this->ncm;
    }

    /**
     * Set NCM value to new on param
     * @param  mixed $ncm new value for NCM
     * @return Tributacao Self instance
     */
    public function setNCM($ncm)
    {
        $this->ncm = $ncm;
        return $this;
    }

    /**
     * Código CEST do produto (Opcional)
     * @return mixed CEST of Tributacao
     */
    public function getCEST()
    {
        return $this->cest;
    }

    /**
     * Set CEST value to new on param
     * @param  mixed $cest new value for CEST
     * @return Tributacao Self instance
     */
    public function setCEST($cest)
    {
        $this->cest = $cest;
        return $this;
    }

    /**
     * Origem do produto
     * @return mixed Origem of Tributacao
     */
    public function getOrigemID()
    {
        return $this->origem_id;
    }

    /**
     * Set OrigemID value to new on param
     * @param  mixed $origem_id new value for OrigemID
     * @return Tributacao Self instance
     */
    public function setOrigemID($origem_id)
    {
        $this->origem_id = $origem_id;
        return $this;
    }

    /**
     * CFOP do produto
     * @return mixed CFOP of Tributacao
     */
    public function getOperacaoID()
    {
        return $this->operacao_id;
    }

    /**
     * Set OperacaoID value to new on param
     * @param  mixed $operacao_id new value for OperacaoID
     * @return Tributacao Self instance
     */
    public function setOperacaoID($operacao_id)
    {
        $this->operacao_id = $operacao_id;
        return $this;
    }

    /**
     * Imposto do produto
     * @return mixed Imposto of Tributacao
     */
    public function getImpostoID()
    {
        return $this->imposto_id;
    }

    /**
     * Set ImpostoID value to new on param
     * @param  mixed $imposto_id new value for ImpostoID
     * @return Tributacao Self instance
     */
    public function setImpostoID($imposto_id)
    {
        $this->imposto_id = $imposto_id;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param  boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $tributacao = parent::toArray($recursive);
        $tributacao['id'] = $this->getID();
        $tributacao['ncm'] = $this->getNCM();
        $tributacao['cest'] = $this->getCEST();
        $tributacao['origemid'] = $this->getOrigemID();
        $tributacao['operacaoid'] = $this->getOperacaoID();
        $tributacao['impostoid'] = $this->getImpostoID();
        return $tributacao;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param  mixed $tributacao Associated key -> value to assign into this instance
     * @return Tributacao Self instance
     */
    public function fromArray($tributacao = [])
    {
        if ($tributacao instanceof Tributacao) {
            $tributacao = $tributacao->toArray();
        } elseif (!is_array($tributacao)) {
            $tributacao = [];
        }
        parent::fromArray($tributacao);
        if (!isset($tributacao['id'])) {
            $this->setID(null);
        } else {
            $this->setID($tributacao['id']);
        }
        if (!isset($tributacao['ncm'])) {
            $this->setNCM(null);
        } else {
            $this->setNCM($tributacao['ncm']);
        }
        if (!array_key_exists('cest', $tributacao)) {
            $this->setCEST(null);
        } else {
            $this->setCEST($tributacao['cest']);
        }
        if (!isset($tributacao['origemid'])) {
            $this->setOrigemID(null);
        } else {
            $this->setOrigemID($tributacao['origemid']);
        }
        if (!isset($tributacao['operacaoid'])) {
            $this->setOperacaoID(null);
        } else {
            $this->setOperacaoID($tributacao['operacaoid']);
        }
        if (!isset($tributacao['impostoid'])) {
            $this->setImpostoID(null);
        } else {
            $this->setImpostoID($tributacao['impostoid']);
        }
        return $this;
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $tributacao = parent::publish();
        return $tributacao;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param Tributacao $original Original instance without modifications
     */
    public function filter($original)
    {
        $this->setID($original->getID());
        $this->setNCM(Filter::string($this->getNCM()));
        $this->setCEST(Filter::string($this->getCEST()));
        $this->setOrigemID(Filter::number($this->getOrigemID()));
        $this->setOperacaoID(Filter::number($this->getOperacaoID()));
        $this->setImpostoID(Filter::number($this->getImpostoID()));
    }

    /**
     * Clean instance resources like images and docs
     * @param  Tributacao $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Tributacao in array format
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getNCM())) {
            $errors['ncm'] = 'O NCM não pode ser vazio';
        }
        if (is_null($this->getOrigemID())) {
            $errors['origemid'] = 'A origem não pode ser vazia';
        }
        if (is_null($this->getOperacaoID())) {
            $errors['operacaoid'] = 'O CFOP não pode ser vazio';
        }
        if (is_null($this->getImpostoID())) {
            $errors['impostoid'] = 'O imposto não pode ser vazio';
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
        return parent::translate($e);
    }

    /**
     * Insert a new Tributação into the database and fill instance from database
     * @return Tributacao Self instance
     */
    public function insert()
    {
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = self::getDB()->insertInto('Tributacoes')->values($values)->execute();
            $this->loadByID($id);
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Tributação with instance values into database for ID
     * @param  array $only Save these fields only, when empty save all fields except id
     * @param  boolean $except When true, saves all fields except $only
     * @return Tributacao Self instance
     */
    public function update($only = [], $except = false)
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador da tributação não foi informado');
        }
        $values = self::filterValues($values, $only, $except);
        try {
            self::getDB()
                ->update('Tributacoes')
                ->set($values)
                ->where('id', $this->getID())
                ->execute();
            $this->loadByID($this->getID());
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
            throw new \Exception('O identificador da tributação não foi informado');
        }
        $result = self::getDB()
            ->deleteFrom('Tributacoes')
            ->where('id', $this->getID())
            ->execute();
        return $result;
    }

    /**
     * Load one register for it self with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order associative field name -> [-1, 1]
     * @return Tributacao Self instance filled or empty
     */
    public function load($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return $this->fromArray($row);
    }

    /**
     * Origem do produto
     * @return \MZ\Invoice\Origem The object fetched from database
     */
    public function findOrigemID()
    {
        return \MZ\Invoice\Origem::findByID($this->getOrigemID());
    }

    /**
     * CFOP do produto
     * @return \MZ\Invoice\Operacao The object fetched from database
     */
    public function findOperacaoID()
    {
        return \MZ\Invoice\Operacao::findByID($this->getOperacaoID());
    }

    /**
     * Imposto do produto
     * @return \MZ\Invoice\Imposto The object fetched from database
     */
    public function findImpostoID()
    {
        return \MZ\Invoice\Imposto::findByID($this->getImpostoID());
    }

    /**
     * Get allowed keys array
     * @return array allowed keys array
     */
    private static function getAllowedKeys()
    {
        $tributacao = new Tributacao();
        $allowed = Filter::concatKeys('t.', $tributacao->toArray());
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
        return Filter::orderBy($order, $allowed, 't.');
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
            $field = 't.ncm LIKE ?';
            $condition[$field] = '%'.$search.'%';
            $allowed[$field] = true;
            unset($condition['search']);
        }
        return Filter::keys($condition, $allowed, 't.');
    }

    /**
     * Fetch data from database with a condition
     * @param  array $condition condition to filter rows
     * @param  array $order order rows
     * @return SelectQuery query object with condition statement
     */
    private static function query($condition = [], $order = [])
    {
        $query = self::getDB()->from('Tributacoes t');
        $condition = self::filterCondition($condition);
        $query = self::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('t.ncm ASC');
        $query = $query->orderBy('t.id ASC');
        return self::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order order rows
     * @return Tributacao A filled Tributação or empty instance
     */
    public static function find($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return new Tributacao($row);
    }

    /**
     * Find this object on database using, ID
     * @param  int $id id to find Tributação
     * @return Tributacao A filled instance or empty when not found
     */
    public static function findByID($id)
    {
        return self::find([
            'id' => intval($id),
        ]);
    }

    /**
     * Find all Tributação
     * @param  array  $condition Condition to get all Tributação
     * @param  array  $order     Order Tributação
     * @param  int    $limit     Limit data into row count
     * @param  int    $offset    Start offset to get rows
     * @return array             List of all rows instanced as Tributacao
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
            $result[] = new Tributacao($row);
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
