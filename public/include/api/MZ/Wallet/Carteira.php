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
namespace MZ\Wallet;

use MZ\Database\Model;
use MZ\Database\DB;
use MZ\Util\Filter;
use MZ\Util\Validator;

/**
 * Informa uma conta bancária ou uma carteira financeira
 */
class Carteira extends Model
{

    /**
     * Tipo de carteira, 'Bancaria' para conta bancária e 'Financeira' para
     * carteira financeira da empresa ou de sites de pagamentos
     */
    const TIPO_BANCARIA = 'Bancaria';
    const TIPO_FINANCEIRA = 'Financeira';

    /**
     * Código local da carteira
     */
    private $id;
    /**
     * Tipo de carteira, 'Bancaria' para conta bancária e 'Financeira' para
     * carteira financeira da empresa ou de sites de pagamentos
     */
    private $tipo;
    /**
     * Código local do banco quando a carteira for bancária
     */
    private $banco_id;
    /**
     * Descrição da carteira, nome dado a carteira cadastrada
     */
    private $descricao;
    /**
     * Número da conta bancária ou usuário da conta de acesso da carteira
     */
    private $conta;
    /**
     * Número da agência da conta bancária ou site da carteira financeira
     */
    private $agencia;
    /**
     * Informa se a carteira ou conta bancária está ativa
     */
    private $ativa;

    /**
     * Constructor for a new empty instance of Carteira
     * @param array $carteira All field and values to fill the instance
     */
    public function __construct($carteira = [])
    {
        parent::__construct($carteira);
    }

    /**
     * Código local da carteira
     * @return mixed ID of Carteira
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param  mixed $id new value for ID
     * @return Carteira Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Tipo de carteira, 'Bancaria' para conta bancária e 'Financeira' para
     * carteira financeira da empresa ou de sites de pagamentos
     * @return mixed Tipo of Carteira
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set Tipo value to new on param
     * @param  mixed $tipo new value for Tipo
     * @return Carteira Self instance
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
        return $this;
    }

    /**
     * Código local do banco quando a carteira for bancária
     * @return mixed Banco of Carteira
     */
    public function getBancoID()
    {
        return $this->banco_id;
    }

    /**
     * Set BancoID value to new on param
     * @param  mixed $banco_id new value for BancoID
     * @return Carteira Self instance
     */
    public function setBancoID($banco_id)
    {
        $this->banco_id = $banco_id;
        return $this;
    }

    /**
     * Descrição da carteira, nome dado a carteira cadastrada
     * @return mixed Descrição of Carteira
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Set Descricao value to new on param
     * @param  mixed $descricao new value for Descricao
     * @return Carteira Self instance
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * Número da conta bancária ou usuário da conta de acesso da carteira
     * @return mixed Conta of Carteira
     */
    public function getConta()
    {
        return $this->conta;
    }

    /**
     * Set Conta value to new on param
     * @param  mixed $conta new value for Conta
     * @return Carteira Self instance
     */
    public function setConta($conta)
    {
        $this->conta = $conta;
        return $this;
    }

    /**
     * Número da agência da conta bancária ou site da carteira financeira
     * @return mixed Agência of Carteira
     */
    public function getAgencia()
    {
        return $this->agencia;
    }

    /**
     * Set Agencia value to new on param
     * @param  mixed $agencia new value for Agencia
     * @return Carteira Self instance
     */
    public function setAgencia($agencia)
    {
        $this->agencia = $agencia;
        return $this;
    }

    /**
     * Informa se a carteira ou conta bancária está ativa
     * @return mixed Ativa of Carteira
     */
    public function getAtiva()
    {
        return $this->ativa;
    }

    /**
     * Informa se a carteira ou conta bancária está ativa
     * @return boolean Check if a of Ativa is selected or checked
     */
    public function isAtiva()
    {
        return $this->ativa == 'Y';
    }

    /**
     * Set Ativa value to new on param
     * @param  mixed $ativa new value for Ativa
     * @return Carteira Self instance
     */
    public function setAtiva($ativa)
    {
        $this->ativa = $ativa;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param  boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $carteira = parent::toArray($recursive);
        $carteira['id'] = $this->getID();
        $carteira['tipo'] = $this->getTipo();
        $carteira['bancoid'] = $this->getBancoID();
        $carteira['descricao'] = $this->getDescricao();
        $carteira['conta'] = $this->getConta();
        $carteira['agencia'] = $this->getAgencia();
        $carteira['ativa'] = $this->getAtiva();
        return $carteira;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param  mixed $carteira Associated key -> value to assign into this instance
     * @return Carteira Self instance
     */
    public function fromArray($carteira = [])
    {
        if ($carteira instanceof Carteira) {
            $carteira = $carteira->toArray();
        } elseif (!is_array($carteira)) {
            $carteira = [];
        }
        parent::fromArray($carteira);
        if (!isset($carteira['id'])) {
            $this->setID(null);
        } else {
            $this->setID($carteira['id']);
        }
        if (!isset($carteira['tipo'])) {
            $this->setTipo(null);
        } else {
            $this->setTipo($carteira['tipo']);
        }
        if (!array_key_exists('bancoid', $carteira)) {
            $this->setBancoID(null);
        } else {
            $this->setBancoID($carteira['bancoid']);
        }
        if (!isset($carteira['descricao'])) {
            $this->setDescricao(null);
        } else {
            $this->setDescricao($carteira['descricao']);
        }
        if (!array_key_exists('conta', $carteira)) {
            $this->setConta(null);
        } else {
            $this->setConta($carteira['conta']);
        }
        if (!array_key_exists('agencia', $carteira)) {
            $this->setAgencia(null);
        } else {
            $this->setAgencia($carteira['agencia']);
        }
        if (!isset($carteira['ativa'])) {
            $this->setAtiva('N');
        } else {
            $this->setAtiva($carteira['ativa']);
        }
        return $this;
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $carteira = parent::publish();
        return $carteira;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param Carteira $original Original instance without modifications
     */
    public function filter($original)
    {
        $this->setID($original->getID());
        $this->setBancoID(Filter::number($this->getBancoID()));
        $this->setDescricao(Filter::string($this->getDescricao()));
        $this->setConta(Filter::string($this->getConta()));
        $this->setAgencia(Filter::string($this->getAgencia()));
    }

    /**
     * Clean instance resources like images and docs
     * @param  Carteira $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Carteira in array format
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getTipo())) {
            $errors['tipo'] = 'O Tipo não pode ser vazio';
        }
        if (!is_null($this->getTipo()) &&
            !array_key_exists($this->getTipo(), self::getTipoOptions())
        ) {
            $errors['tipo'] = 'O Tipo é invalido';
        }
        if ($this->getTipo() == self::TIPO_BANCARIA && is_null($this->getBancoID())) {
            $errors['bancoid'] = 'O banco não foi informado';
        }
        if ($this->getTipo() == self::TIPO_FINANCEIRA && !is_null($this->getBancoID())) {
            $errors['bancoid'] = 'O banco não pode ser informado';
        }
        if (is_null($this->getDescricao())) {
            $errors['descricao'] = 'A Descrição não pode ser vazia';
        }
        if ($this->getTipo() == self::TIPO_BANCARIA && is_null($this->getAgencia())) {
            $errors['agencia'] = 'A agência não pode ser vazia';
        }
        if ($this->getTipo() == self::TIPO_BANCARIA && is_null($this->getConta())) {
            $errors['conta'] = 'A conta não pode ser vazia';
        }
        if (is_null($this->getAtiva())) {
            $errors['ativa'] = 'A Ativação não pode ser vazia';
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
     * Insert a new Carteira into the database and fill instance from database
     * @return Carteira Self instance
     */
    public function insert()
    {
        $this->setID(null);
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = DB::insertInto('Carteiras')->values($values)->execute();
            $this->loadByID($id);
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Carteira with instance values into database for ID
     * @param  array $only Save these fields only, when empty save all fields except id
     * @param  boolean $except When true, saves all fields except $only
     * @return Carteira Self instance
     */
    public function update($only = [], $except = false)
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador da carteira não foi informado');
        }
        $values = DB::filterValues($values, $only, $except);
        try {
            DB::update('Carteiras')
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
            throw new \Exception('O identificador da carteira não foi informado');
        }
        $result = DB::deleteFrom('Carteiras')
            ->where('id', $this->getID())
            ->execute();
        return $result;
    }

    /**
     * Load one register for it self with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order associative field name -> [-1, 1]
     * @return Carteira Self instance filled or empty
     */
    public function load($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return $this->fromArray($row);
    }

    /**
     * Código local do banco quando a carteira for bancária
     * @return \MZ\Wallet\Banco The object fetched from database
     */
    public function findBancoID()
    {
        if (is_null($this->getBancoID())) {
            return new \MZ\Wallet\Banco();
        }
        return \MZ\Wallet\Banco::findByID($this->getBancoID());
    }

    /**
     * Gets textual and translated Tipo for Carteira
     * @param  int $index choose option from index
     * @return mixed A associative key -> translated representative text or text for index
     */
    public static function getTipoOptions($index = null)
    {
        $options = [
            self::TIPO_BANCARIA => 'Bancária',
            self::TIPO_FINANCEIRA => 'Financeira',
        ];
        if (!is_null($index)) {
            return $options[$index];
        }
        return $options;
    }

    /**
     * Get allowed keys array
     * @return array allowed keys array
     */
    private static function getAllowedKeys()
    {
        $carteira = new Carteira();
        $allowed = Filter::concatKeys('c.', $carteira->toArray());
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
        return Filter::orderBy($order, $allowed, 'c.');
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
            $search = trim($condition['search']);
            $field = 'c.descricao LIKE ?';
            $condition[$field] = '%'.$search.'%';
            $allowed[$field] = true;
            unset($condition['search']);
        }
        return Filter::keys($condition, $allowed, 'c.');
    }

    /**
     * Fetch data from database with a condition
     * @param  array $condition condition to filter rows
     * @param  array $order order rows
     * @return SelectQuery query object with condition statement
     */
    private static function query($condition = [], $order = [])
    {
        $query = DB::from('Carteiras c');
        $condition = self::filterCondition($condition);
        $query = DB::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('c.descricao ASC');
        $query = $query->orderBy('c.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order order rows
     * @return Carteira A filled Carteira or empty instance
     */
    public static function find($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return new Carteira($row);
    }

    /**
     * Find this object on database using, ID
     * @param  int $id id to find Carteira
     * @return Carteira A filled instance or empty when not found
     */
    public static function findByID($id)
    {
        $result = new self();
        return $result->loadByID($id);
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
            $result[] = new Carteira($row);
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
