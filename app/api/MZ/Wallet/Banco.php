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

use MZ\Database\SyncModel;
use MZ\Database\DB;
use MZ\Util\Filter;
use MZ\Util\Validator;

/**
 * Bancos disponíveis no país
 */
class Banco extends SyncModel
{

    /**
     * Identificador do banco
     */
    private $id;
    /**
     * Número do banco
     */
    private $numero;
    /**
     * Razão social do banco
     */
    private $razao_social;
    /**
     * Mascara para formatação do número da agência
     */
    private $agencia_mascara;
    /**
     * Máscara para formatação do número da conta
     */
    private $conta_mascara;

    /**
     * Constructor for a new empty instance of Banco
     * @param array $banco All field and values to fill the instance
     */
    public function __construct($banco = [])
    {
        parent::__construct($banco);
    }

    /**
     * Identificador do banco
     * @return mixed ID of Banco
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param  mixed $id new value for ID
     * @return Banco Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Número do banco
     * @return mixed Número of Banco
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set Numero value to new on param
     * @param  mixed $numero new value for Numero
     * @return Banco Self instance
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;
        return $this;
    }

    /**
     * Razão social do banco
     * @return mixed Razão social of Banco
     */
    public function getRazaoSocial()
    {
        return $this->razao_social;
    }

    /**
     * Set RazaoSocial value to new on param
     * @param  mixed $razao_social new value for RazaoSocial
     * @return Banco Self instance
     */
    public function setRazaoSocial($razao_social)
    {
        $this->razao_social = $razao_social;
        return $this;
    }

    /**
     * Mascara para formatação do número da agência
     * @return mixed Máscara da agência of Banco
     */
    public function getAgenciaMascara()
    {
        return $this->agencia_mascara;
    }

    /**
     * Set AgenciaMascara value to new on param
     * @param  mixed $agencia_mascara new value for AgenciaMascara
     * @return Banco Self instance
     */
    public function setAgenciaMascara($agencia_mascara)
    {
        $this->agencia_mascara = $agencia_mascara;
        return $this;
    }

    /**
     * Máscara para formatação do número da conta
     * @return mixed Máscara da conta of Banco
     */
    public function getContaMascara()
    {
        return $this->conta_mascara;
    }

    /**
     * Set ContaMascara value to new on param
     * @param  mixed $conta_mascara new value for ContaMascara
     * @return Banco Self instance
     */
    public function setContaMascara($conta_mascara)
    {
        $this->conta_mascara = $conta_mascara;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param  boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $banco = parent::toArray($recursive);
        $banco['id'] = $this->getID();
        $banco['numero'] = $this->getNumero();
        $banco['razaosocial'] = $this->getRazaoSocial();
        $banco['agenciamascara'] = $this->getAgenciaMascara();
        $banco['contamascara'] = $this->getContaMascara();
        return $banco;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param  mixed $banco Associated key -> value to assign into this instance
     * @return Banco Self instance
     */
    public function fromArray($banco = [])
    {
        if ($banco instanceof Banco) {
            $banco = $banco->toArray();
        } elseif (!is_array($banco)) {
            $banco = [];
        }
        parent::fromArray($banco);
        if (!isset($banco['id'])) {
            $this->setID(null);
        } else {
            $this->setID($banco['id']);
        }
        if (!isset($banco['numero'])) {
            $this->setNumero(null);
        } else {
            $this->setNumero($banco['numero']);
        }
        if (!isset($banco['razaosocial'])) {
            $this->setRazaoSocial(null);
        } else {
            $this->setRazaoSocial($banco['razaosocial']);
        }
        if (!array_key_exists('agenciamascara', $banco)) {
            $this->setAgenciaMascara(null);
        } else {
            $this->setAgenciaMascara($banco['agenciamascara']);
        }
        if (!array_key_exists('contamascara', $banco)) {
            $this->setContaMascara(null);
        } else {
            $this->setContaMascara($banco['contamascara']);
        }
        return $this;
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $banco = parent::publish();
        return $banco;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param Banco $original Original instance without modifications
     */
    public function filter($original)
    {
        $this->setID($original->getID());
        $this->setNumero(Filter::string($this->getNumero()));
        $this->setRazaoSocial(Filter::string($this->getRazaoSocial()));
        $this->setAgenciaMascara(Filter::string($this->getAgenciaMascara()));
        $this->setContaMascara(Filter::string($this->getContaMascara()));
    }

    /**
     * Clean instance resources like images and docs
     * @param  Banco $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Banco in array format
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getNumero())) {
            $errors['numero'] = 'O Número não pode ser vazio';
        }
        if (is_null($this->getRazaoSocial())) {
            $errors['razaosocial'] = 'A Razão social não pode ser vazia';
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
        if (contains(['RazaoSocial', 'UNIQUE'], $e->getMessage())) {
            return new \MZ\Exception\ValidationException([
                'razaosocial' => vsprintf(
                    'A Razão social "%s" já está cadastrada',
                    [$this->getRazaoSocial()]
                ),
            ]);
        }
        if (contains(['Numero', 'UNIQUE'], $e->getMessage())) {
            return new \MZ\Exception\ValidationException([
                'numero' => vsprintf(
                    'O Número "%s" já está cadastrado',
                    [$this->getNumero()]
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Insert a new Banco into the database and fill instance from database
     * @return Banco Self instance
     */
    public function insert()
    {
        $this->setID(null);
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = DB::insertInto('Bancos')->values($values)->execute();
            $this->setID($id);
            $this->loadByID();
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Banco with instance values into database for ID
     * @param  array $only Save these fields only, when empty save all fields except id
     * @return Banco Self instance
     */
    public function update($only = [])
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador do banco não foi informado');
        }
        $values = DB::filterValues($values, $only, false);
        try {
            DB::update('Bancos')
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
            throw new \Exception('O identificador do banco não foi informado');
        }
        $result = DB::deleteFrom('Bancos')
            ->where('id', $this->getID())
            ->execute();
        return $result;
    }

    /**
     * Load one register for it self with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order associative field name -> [-1, 1]
     * @return Banco Self instance filled or empty
     */
    public function load($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return $this->fromArray($row);
    }

    /**
     * Load into this object from database using, RazaoSocial
     * @param  string $razao_social razão social to find Banco
     * @return Banco Self filled instance or empty when not found
     */
    public function loadByRazaoSocial($razao_social)
    {
        return $this->load([
            'razaosocial' => strval($razao_social),
        ]);
    }

    /**
     * Load into this object from database using, Numero
     * @param  string $numero número to find Banco
     * @return Banco Self filled instance or empty when not found
     */
    public function loadByNumero($numero)
    {
        return $this->load([
            'numero' => strval($numero),
        ]);
    }

    /**
     * Get allowed keys array
     * @return array allowed keys array
     */
    private static function getAllowedKeys()
    {
        $banco = new Banco();
        $allowed = Filter::concatKeys('b.', $banco->toArray());
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
        return Filter::orderBy($order, $allowed, 'b.');
    }

    /**
     * Filter condition array with allowed fields
     * @param  array $condition condition to filter rows
     * @return array allowed condition
     */
    private static function filterCondition($condition)
    {
        $allowed = self::getAllowedKeys();
        return Filter::keys($condition, $allowed, 'b.');
    }

    /**
     * Fetch data from database with a condition
     * @param  array $condition condition to filter rows
     * @param  array $order order rows
     * @return SelectQuery query object with condition statement
     */
    private static function query($condition = [], $order = [])
    {
        $query = DB::from('Bancos b');
        if (isset($condition['search'])) {
            $search = $condition['search'];
            if (is_numeric($search)) {
                $condition['numero'] = Filter::digits($search);
            } else {
                $query = DB::buildSearch($search, 'b.razaosocial', $query);
            }
        }
        $condition = self::filterCondition($condition);
        $query = DB::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('b.razaosocial ASC');
        $query = $query->orderBy('b.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @return Banco A filled Banco or empty instance
     */
    public static function find($condition)
    {
        $query = self::query($condition)->limit(1);
        $row = $query->fetch();
        if ($row === false) {
            $row = [];
        }
        return new Banco($row);
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
            $result[] = new Banco($row);
        }
        return $result;
    }

    /**
     * Find this object on database using, RazaoSocial
     * @param  string $razao_social razão social to find Banco
     * @return Banco A filled instance or empty when not found
     */
    public static function findByRazaoSocial($razao_social)
    {
        $result = new self();
        return $result->loadByRazaoSocial($razao_social);
    }

    /**
     * Find this object on database using, Numero
     * @param  string $numero número to find Banco
     * @return Banco A filled instance or empty when not found
     */
    public static function findByNumero($numero)
    {
        $result = new self();
        return $result->loadByNumero($numero);
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
