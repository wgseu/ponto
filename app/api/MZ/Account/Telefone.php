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
namespace MZ\Account;

use MZ\Util\Mask;
use MZ\Util\Filter;
use MZ\Util\Validator;
use MZ\Database\DB;
use MZ\Database\SyncModel;
use MZ\Exception\ValidationException;

/**
 * Telefones dos clientes, apenas o telefone principal deve ser único por
 * cliente
 */
class Telefone extends SyncModel
{

    /**
     * Identificador do telefone
     */
    private $id;
    /**
     * Informa o cliente que possui esse número de telefone
     */
    private $cliente_id;
    /**
     * Informa o país desse número de telefone
     */
    private $pais_id;
    /**
     * Número de telefone com DDD
     */
    private $numero;
    /**
     * Informa qual a operadora desse telefone
     */
    private $operadora;
    /**
     * Informa qual serviço está associado à esse número, Ex: WhatsApp
     */
    private $servico;
    /**
     * Informa se o telefone é principal e exclusivo do cliente
     */
    private $principal;

    /**
     * Constructor for a new empty instance of Telefone
     * @param array $telefone All field and values to fill the instance
     */
    public function __construct($telefone = [])
    {
        parent::__construct($telefone);
    }

    /**
     * Identificador do telefone
     * @return int id of Telefone
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param int $id Set id for Telefone
     * @return self Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Informa o cliente que possui esse número de telefone
     * @return int cliente of Telefone
     */
    public function getClienteID()
    {
        return $this->cliente_id;
    }

    /**
     * Set ClienteID value to new on param
     * @param int $cliente_id Set cliente for Telefone
     * @return self Self instance
     */
    public function setClienteID($cliente_id)
    {
        $this->cliente_id = $cliente_id;
        return $this;
    }

    /**
     * Informa o país desse número de telefone
     * @return int país of Telefone
     */
    public function getPaisID()
    {
        return $this->pais_id;
    }

    /**
     * Set PaisID value to new on param
     * @param int $pais_id Set país for Telefone
     * @return self Self instance
     */
    public function setPaisID($pais_id)
    {
        $this->pais_id = $pais_id;
        return $this;
    }

    /**
     * Número de telefone com DDD
     * @return string número of Telefone
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set Numero value to new on param
     * @param string $numero Set número for Telefone
     * @return self Self instance
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;
        return $this;
    }

    /**
     * Informa qual a operadora desse telefone
     * @return string operadora of Telefone
     */
    public function getOperadora()
    {
        return $this->operadora;
    }

    /**
     * Set Operadora value to new on param
     * @param string $operadora Set operadora for Telefone
     * @return self Self instance
     */
    public function setOperadora($operadora)
    {
        $this->operadora = $operadora;
        return $this;
    }

    /**
     * Informa qual serviço está associado à esse número, Ex: WhatsApp
     * @return string serviço of Telefone
     */
    public function getServico()
    {
        return $this->servico;
    }

    /**
     * Set Servico value to new on param
     * @param string $servico Set serviço for Telefone
     * @return self Self instance
     */
    public function setServico($servico)
    {
        $this->servico = $servico;
        return $this;
    }

    /**
     * Informa se o telefone é principal e exclusivo do cliente
     * @return string principal of Telefone
     */
    public function getPrincipal()
    {
        return $this->principal;
    }

    /**
     * Informa se o telefone é principal e exclusivo do cliente
     * @return boolean Check if o of Principal is selected or checked
     */
    public function isPrincipal()
    {
        return $this->principal == 'Y';
    }

    /**
     * Set Principal value to new on param
     * @param string $principal Set principal for Telefone
     * @return self Self instance
     */
    public function setPrincipal($principal)
    {
        $this->principal = $principal;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $telefone = parent::toArray($recursive);
        $telefone['id'] = $this->getID();
        $telefone['clienteid'] = $this->getClienteID();
        $telefone['paisid'] = $this->getPaisID();
        $telefone['numero'] = $this->getNumero();
        $telefone['operadora'] = $this->getOperadora();
        $telefone['servico'] = $this->getServico();
        $telefone['principal'] = $this->getPrincipal();
        return $telefone;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param mixed $telefone Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($telefone = [])
    {
        if ($telefone instanceof self) {
            $telefone = $telefone->toArray();
        } elseif (!is_array($telefone)) {
            $telefone = [];
        }
        parent::fromArray($telefone);
        if (!isset($telefone['id'])) {
            $this->setID(null);
        } else {
            $this->setID($telefone['id']);
        }
        if (!isset($telefone['clienteid'])) {
            $this->setClienteID(null);
        } else {
            $this->setClienteID($telefone['clienteid']);
        }
        if (!isset($telefone['paisid'])) {
            $this->setPaisID(null);
        } else {
            $this->setPaisID($telefone['paisid']);
        }
        if (!isset($telefone['numero'])) {
            $this->setNumero(null);
        } else {
            $this->setNumero($telefone['numero']);
        }
        if (!array_key_exists('operadora', $telefone)) {
            $this->setOperadora(null);
        } else {
            $this->setOperadora($telefone['operadora']);
        }
        if (!array_key_exists('servico', $telefone)) {
            $this->setServico(null);
        } else {
            $this->setServico($telefone['servico']);
        }
        if (!isset($telefone['principal'])) {
            $this->setPrincipal('N');
        } else {
            $this->setPrincipal($telefone['principal']);
        }
        return $this;
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $telefone = parent::publish();
        $telefone['numero'] = Mask::mask($telefone['numero'], _p('numero.mask'));
        return $telefone;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param self $original Original instance without modifications
     * @param boolean $localized Informs if fields are localized
     * @return self Self instance
     */
    public function filter($original, $localized = false)
    {
        $this->setID($original->getID());
        $this->setClienteID(Filter::number($original->getClienteID()));
        $this->setPaisID(Filter::number($this->getPaisID()));
        $this->setNumero(Filter::unmask($this->getNumero(), _p('Mascara', 'Telefone')));
        $this->setOperadora(Filter::string($this->getOperadora()));
        $this->setServico(Filter::string($this->getServico()));
        return $this;
    }

    /**
     * Clean instance resources like images and docs
     * @param self $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Telefone in array format
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getClienteID())) {
            $errors['clienteid'] = _t('telefone.cliente_id_cannot_empty');
        }
        if (is_null($this->getPaisID())) {
            $errors['paisid'] = _t('telefone.pais_id_cannot_empty');
        }
        if (is_null($this->getNumero())) {
            $errors['numero'] = _t('telefone.numero_cannot_empty');
        }
        if (!Validator::checkBoolean($this->getPrincipal())) {
            $errors['principal'] = _t('telefone.principal_invalid');
        }
        if ($this->isPrincipal() &&
            self::count([
                'numero' => $this->getNumero(),
                '!clienteid' => $this->getClienteID(),
                'principal' => 'Y',
            ]) > 0
        ) {
            $errors['numero'] = _t('telefone.numero_used');
        }
        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
        return $this->toArray();
    }

    /**
     * Insert a new Telefone into the database and fill instance from database
     * @return self Self instance
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function insert()
    {
        $this->setID(null);
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = DB::insertInto('Telefones')->values($values)->execute();
            $this->setID($id);
            $this->loadByID();
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Telefone with instance values into database for ID
     * @param array $only Save these fields only, when empty save all fields except id
     * @return int rows affected
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function update($only = [])
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new ValidationException(
                ['id' => _t('telefone.id_cannot_empty')]
            );
        }
        $values = DB::filterValues($values, $only, false);
        try {
            $affected = DB::update('Telefones')
                ->set($values)
                ->where(['id' => $this->getID()])
                ->execute();
            $this->loadByID();
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $affected;
    }

    /**
     * Delete this instance from database using ID
     * @return integer Number of rows deleted (Max 1)
     * @throws \MZ\Exception\ValidationException for invalid id
     */
    public function delete()
    {
        if (!$this->exists()) {
            throw new ValidationException(
                ['id' => _t('telefone.id_cannot_empty')]
            );
        }
        $result = DB::deleteFrom('Telefones')
            ->where('id', $this->getID())
            ->execute();
        return $result;
    }

    /**
     * Load one register for it self with a condition
     * @param array $condition Condition for searching the row
     * @param array $order associative field name -> [-1, 1]
     * @return self Self instance filled or empty
     */
    public function load($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return $this->fromArray($row);
    }

    /**
     * Informa o cliente que possui esse número de telefone
     * @return \MZ\Account\Cliente The object fetched from database
     */
    public function findClienteID()
    {
        return \MZ\Account\Cliente::findByID($this->getClienteID());
    }

    /**
     * Get allowed keys array
     * @return array allowed keys array
     */
    private static function getAllowedKeys()
    {
        $telefone = new self();
        $allowed = Filter::concatKeys('t.', $telefone->toArray());
        return $allowed;
    }

    /**
     * Filter order array
     * @param mixed $order order string or array to parse and filter allowed
     * @return array allowed associative order
     */
    private static function filterOrder($order)
    {
        $allowed = self::getAllowedKeys();
        return Filter::orderBy($order, $allowed, 't.');
    }

    /**
     * Filter condition array with allowed fields
     * @param array $condition condition to filter rows
     * @return array allowed condition
     */
    private static function filterCondition($condition)
    {
        $allowed = self::getAllowedKeys();
        if (isset($condition['search'])) {
            $search = $condition['search'];
            $field = 't.numero LIKE ?';
            $condition[$field] = '%'.$search.'%';
            $allowed[$field] = true;
            unset($condition['search']);
        }
        if (isset($condition['!clienteid'])) {
            $field = 'NOT t.clienteid';
            $condition[$field] = $condition['!clienteid'];
            $allowed[$field] = true;
        }
        return Filter::keys($condition, $allowed, 't.');
    }

    /**
     * Fetch data from database with a condition
     * @param array $condition condition to filter rows
     * @param array $order order rows
     * @return SelectQuery query object with condition statement
     */
    private static function query($condition = [], $order = [])
    {
        $query = DB::from('Telefones t');
        $condition = self::filterCondition($condition);
        $query = DB::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('t.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param array $condition Condition for searching the row
     * @param array $order order rows
     * @return self A filled Telefone or empty instance
     */
    public static function find($condition, $order = [])
    {
        $result = new self();
        return $result->load($condition, $order);
    }

    /**
     * Search one register with a condition
     * @param array $condition Condition for searching the row
     * @param array $order order rows
     * @return self A filled Telefone or empty instance
     * @throws \Exception when register has not found
     */
    public static function findOrFail($condition, $order = [])
    {
        $result = self::find($condition, $order);
        if (!$result->exists()) {
            throw new \Exception(_t('telefone.not_found'), 404);
        }
        return $result;
    }

    /**
     * Find all Telefone
     * @param array  $condition Condition to get all Telefone
     * @param array  $order     Order Telefone
     * @param int    $limit     Limit data into row count
     * @param int    $offset    Start offset to get rows
     * @return self[] List of all rows instanced as Telefone
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
            $result[] = new self($row);
        }
        return $result;
    }

    /**
     * Count all rows from database with matched condition critery
     * @param array $condition condition to filter rows
     * @return integer Quantity of rows
     */
    public static function count($condition = [])
    {
        $query = self::query($condition);
        return $query->count();
    }
}
