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
namespace MZ\Stock;

use MZ\Util\Filter;
use MZ\Util\Validator;

/**
 * Fornecedores de produtos
 */
class Fornecedor extends \MZ\Database\Helper
{

    /**
     * Identificador do fornecedor
     */
    private $id;
    /**
     * Empresa do fornecedor
     */
    private $empresa_id;
    /**
     * Prazo em dias para pagamento do fornecedor
     */
    private $prazo_pagamento;
    /**
     * Data de cadastro do fornecedor
     */
    private $data_cadastro;

    /**
     * Constructor for a new empty instance of Fornecedor
     * @param array $fornecedor All field and values to fill the instance
     */
    public function __construct($fornecedor = [])
    {
        parent::__construct($fornecedor);
    }

    /**
     * Identificador do fornecedor
     * @return mixed ID of Fornecedor
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param  mixed $id new value for ID
     * @return Fornecedor Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Empresa do fornecedor
     * @return mixed Empresa of Fornecedor
     */
    public function getEmpresaID()
    {
        return $this->empresa_id;
    }

    /**
     * Set EmpresaID value to new on param
     * @param  mixed $empresa_id new value for EmpresaID
     * @return Fornecedor Self instance
     */
    public function setEmpresaID($empresa_id)
    {
        $this->empresa_id = $empresa_id;
        return $this;
    }

    /**
     * Prazo em dias para pagamento do fornecedor
     * @return mixed Prazo de pagamento of Fornecedor
     */
    public function getPrazoPagamento()
    {
        return $this->prazo_pagamento;
    }

    /**
     * Set PrazoPagamento value to new on param
     * @param  mixed $prazo_pagamento new value for PrazoPagamento
     * @return Fornecedor Self instance
     */
    public function setPrazoPagamento($prazo_pagamento)
    {
        $this->prazo_pagamento = $prazo_pagamento;
        return $this;
    }

    /**
     * Data de cadastro do fornecedor
     * @return mixed Data de cadastro of Fornecedor
     */
    public function getDataCadastro()
    {
        return $this->data_cadastro;
    }

    /**
     * Set DataCadastro value to new on param
     * @param  mixed $data_cadastro new value for DataCadastro
     * @return Fornecedor Self instance
     */
    public function setDataCadastro($data_cadastro)
    {
        $this->data_cadastro = $data_cadastro;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param  boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $fornecedor = parent::toArray($recursive);
        $fornecedor['id'] = $this->getID();
        $fornecedor['empresaid'] = $this->getEmpresaID();
        $fornecedor['prazopagamento'] = $this->getPrazoPagamento();
        $fornecedor['datacadastro'] = $this->getDataCadastro();
        return $fornecedor;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param  mixed $fornecedor Associated key -> value to assign into this instance
     * @return Fornecedor Self instance
     */
    public function fromArray($fornecedor = [])
    {
        if ($fornecedor instanceof Fornecedor) {
            $fornecedor = $fornecedor->toArray();
        } elseif (!is_array($fornecedor)) {
            $fornecedor = [];
        }
        parent::fromArray($fornecedor);
        if (!isset($fornecedor['id'])) {
            $this->setID(null);
        } else {
            $this->setID($fornecedor['id']);
        }
        if (!isset($fornecedor['empresaid'])) {
            $this->setEmpresaID(null);
        } else {
            $this->setEmpresaID($fornecedor['empresaid']);
        }
        if (!isset($fornecedor['prazopagamento'])) {
            $this->setPrazoPagamento(null);
        } else {
            $this->setPrazoPagamento($fornecedor['prazopagamento']);
        }
        if (!isset($fornecedor['datacadastro'])) {
            $this->setDataCadastro(null);
        } else {
            $this->setDataCadastro($fornecedor['datacadastro']);
        }
        return $this;
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $fornecedor = parent::publish();
        return $fornecedor;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param Fornecedor $original Original instance without modifications
     */
    public function filter($original)
    {
        $this->setID($original->getID());
        $this->setEmpresaID(Filter::number($this->getEmpresaID()));
        $this->setPrazoPagamento(Filter::number($this->getPrazoPagamento()));
        $this->setDataCadastro(Filter::datetime($this->getDataCadastro()));
    }

    /**
     * Clean instance resources like images and docs
     * @param  Fornecedor $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Fornecedor in array format
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getEmpresaID())) {
            $errors['empresaid'] = 'A empresa não pode ser vazia';
        }
        if (is_null($this->getPrazoPagamento())) {
            $errors['prazopagamento'] = 'O prazo de pagamento não pode ser vazio';
        }
        if (is_null($this->getDataCadastro())) {
            $errors['datacadastro'] = 'A data de cadastro não pode ser vazia';
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
        if (stripos($e->getMessage(), 'EmpresaID_UNIQUE') !== false) {
            return new \MZ\Exception\ValidationException([
                'empresaid' => sprintf(
                    'A empresa "%s" já está cadastrada',
                    $this->getEmpresaID()
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Find this object on database using, ID
     * @param  int $id id to find Fornecedor
     * @return Fornecedor A filled instance or empty when not found
     */
    public static function findByID($id)
    {
        return self::find([
            'id' => intval($id),
        ]);
    }

    /**
     * Find this object on database using, EmpresaID
     * @param  int $empresa_id empresa to find Fornecedor
     * @return Fornecedor A filled instance or empty when not found
     */
    public static function findByEmpresaID($empresa_id)
    {
        return self::find([
            'empresaid' => intval($empresa_id),
        ]);
    }

    /**
     * Get allowed keys array
     * @return array allowed keys array
     */
    private static function getAllowedKeys()
    {
        $fornecedor = new Fornecedor();
        $allowed = Filter::concatKeys('f.', $fornecedor->toArray());
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
        $query = self::getDB()->from('Fornecedores f');
        $condition = self::filterCondition($condition);
        $query = self::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('f.id ASC');
        return self::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order order rows
     * @return Fornecedor A filled Fornecedor or empty instance
     */
    public static function find($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch();
        if ($row === false) {
            $row = [];
        }
        return new Fornecedor($row);
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
            $result[] = new Fornecedor($row);
        }
        return $result;
    }

    /**
     * Insert a new Fornecedor into the database and fill instance from database
     * @return Fornecedor Self instance
     */
    public function insert()
    {
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = self::getDB()->insertInto('Fornecedores')->values($values)->execute();
            $fornecedor = self::findByID($id);
            $this->fromArray($fornecedor->toArray());
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Fornecedor with instance values into database for ID
     * @return Fornecedor Self instance
     */
    public function update()
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador do fornecedor não foi informado');
        }
        unset($values['id']);
        try {
            self::getDB()
                ->update('Fornecedores')
                ->set($values)
                ->where('id', $this->getID())
                ->execute();
            $fornecedor = self::findByID($this->getID());
            $this->fromArray($fornecedor->toArray());
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Save the Fornecedor into the database
     * @return Fornecedor Self instance
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
            throw new \Exception('O identificador do fornecedor não foi informado');
        }
        $result = self::getDB()
            ->deleteFrom('Fornecedores')
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
     * Empresa do fornecedor
     * @return \MZ\Account\Cliente The object fetched from database
     */
    public function findEmpresaID()
    {
        return \MZ\Account\Cliente::findByID($this->getEmpresaID());
    }
}
