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
namespace MZ\Location;

use MZ\Database\SyncModel;
use MZ\Database\DB;
use MZ\Util\Filter;
use MZ\Util\Validator;

/**
 * Endereços de ruas e avenidas com informação de CEP
 */
class Endereco extends SyncModel
{

    /**
     * Identificador do endereço
     */
    private $id;
    /**
     * Cidade a qual o endereço pertence
     */
    private $cidade_id;
    /**
     * Bairro a qual o endereço está localizado
     */
    private $bairro_id;
    /**
     * Nome da rua ou avenida
     */
    private $logradouro;
    /**
     * Código dos correios para identificar a rua ou avenida
     */
    private $cep;

    /**
     * Constructor for a new empty instance of Endereco
     * @param array $endereco All field and values to fill the instance
     */
    public function __construct($endereco = [])
    {
        parent::__construct($endereco);
    }

    /**
     * Identificador do endereço
     * @return mixed ID of Endereco
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param  mixed $id new value for ID
     * @return Endereco Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Cidade a qual o endereço pertence
     * @return mixed Cidade of Endereco
     */
    public function getCidadeID()
    {
        return $this->cidade_id;
    }

    /**
     * Set CidadeID value to new on param
     * @param  mixed $cidade_id new value for CidadeID
     * @return Endereco Self instance
     */
    public function setCidadeID($cidade_id)
    {
        $this->cidade_id = $cidade_id;
        return $this;
    }

    /**
     * Bairro a qual o endereço está localizado
     * @return mixed Bairro of Endereco
     */
    public function getBairroID()
    {
        return $this->bairro_id;
    }

    /**
     * Set BairroID value to new on param
     * @param  mixed $bairro_id new value for BairroID
     * @return Endereco Self instance
     */
    public function setBairroID($bairro_id)
    {
        $this->bairro_id = $bairro_id;
        return $this;
    }

    /**
     * Nome da rua ou avenida
     * @return mixed Logradouro of Endereco
     */
    public function getLogradouro()
    {
        return $this->logradouro;
    }

    /**
     * Set Logradouro value to new on param
     * @param  mixed $logradouro new value for Logradouro
     * @return Endereco Self instance
     */
    public function setLogradouro($logradouro)
    {
        $this->logradouro = $logradouro;
        return $this;
    }

    /**
     * Código dos correios para identificar a rua ou avenida
     * @return mixed CEP of Endereco
     */
    public function getCEP()
    {
        return $this->cep;
    }

    /**
     * Set CEP value to new on param
     * @param  mixed $cep new value for CEP
     * @return Endereco Self instance
     */
    public function setCEP($cep)
    {
        $this->cep = $cep;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param  boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $endereco = parent::toArray($recursive);
        $endereco['id'] = $this->getID();
        $endereco['cidadeid'] = $this->getCidadeID();
        $endereco['bairroid'] = $this->getBairroID();
        $endereco['logradouro'] = $this->getLogradouro();
        $endereco['cep'] = $this->getCEP();
        return $endereco;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param  mixed $endereco Associated key -> value to assign into this instance
     * @return Endereco Self instance
     */
    public function fromArray($endereco = [])
    {
        if ($endereco instanceof Endereco) {
            $endereco = $endereco->toArray();
        } elseif (!is_array($endereco)) {
            $endereco = [];
        }
        parent::fromArray($endereco);
        if (!isset($endereco['id'])) {
            $this->setID(null);
        } else {
            $this->setID($endereco['id']);
        }
        if (!isset($endereco['cidadeid'])) {
            $this->setCidadeID(null);
        } else {
            $this->setCidadeID($endereco['cidadeid']);
        }
        if (!isset($endereco['bairroid'])) {
            $this->setBairroID(null);
        } else {
            $this->setBairroID($endereco['bairroid']);
        }
        if (!isset($endereco['logradouro'])) {
            $this->setLogradouro(null);
        } else {
            $this->setLogradouro($endereco['logradouro']);
        }
        if (!isset($endereco['cep'])) {
            $this->setCEP(null);
        } else {
            $this->setCEP($endereco['cep']);
        }
        return $this;
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $endereco = parent::publish();
        $endereco['cep'] = \MZ\Util\Mask::cep($endereco['cep']);
        return $endereco;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param Endereco $original Original instance without modifications
     */
    public function filter($original)
    {
        $this->setID($original->getID());
        $this->setCidadeID(Filter::number($this->getCidadeID()));
        $this->setBairroID(Filter::number($this->getBairroID()));
        $this->setLogradouro(Filter::string($this->getLogradouro()));
        $this->setCEP(Filter::unmask($this->getCEP(), _p('Mascara', 'CEP')));
    }

    /**
     * Clean instance resources like images and docs
     * @param  Endereco $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Endereco in array format
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getCidadeID())) {
            $errors['cidadeid'] = 'A cidade não pode ser vazia';
        }
        if (is_null($this->getBairroID())) {
            $errors['bairroid'] = 'O bairro não pode ser vazio';
        }
        if (is_null($this->getLogradouro())) {
            $errors['logradouro'] = 'O logradouro não pode ser vazio';
        }
        if (is_null($this->getCEP())) {
            $errors['cep'] = 'O cep não pode ser vazio';
        }
        if (!Validator::checkCEP($this->getCEP())) {
            $errors['cep'] = sprintf('O %s é inválido', _p('Titulo', 'CEP'));
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
        if (contains(['CEP', 'UNIQUE'], $e->getMessage())) {
            return new \MZ\Exception\ValidationException([
                'cep' => sprintf(
                    'O cep "%s" já está cadastrado',
                    $this->getCEP()
                ),
            ]);
        }
        if (contains(['BairroID', 'Logradouro', 'UNIQUE'], $e->getMessage())) {
            return new \MZ\Exception\ValidationException([
                'bairroid' => sprintf(
                    'O bairro "%s" já está cadastrado',
                    $this->getBairroID()
                ),
                'logradouro' => sprintf(
                    'O logradouro "%s" já está cadastrado',
                    $this->getLogradouro()
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Insert a new Endereço into the database and fill instance from database
     * @return Endereco Self instance
     */
    public function insert()
    {
        $this->setID(null);
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = DB::insertInto('Enderecos')->values($values)->execute();
            $this->setID($id);
            $this->loadByID();
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Endereço with instance values into database for ID
     * @param  array $only Save these fields only, when empty save all fields except id
     * @return Endereco Self instance
     */
    public function update($only = [])
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador do endereço não foi informado');
        }
        $values = DB::filterValues($values, $only, false);
        try {
            DB::update('Enderecos')
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
            throw new \Exception('O identificador do endereço não foi informado');
        }
        $result = DB::deleteFrom('Enderecos')
            ->where('id', $this->getID())
            ->execute();
        return $result;
    }

    /**
     * Load one register for it self with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order associative field name -> [-1, 1]
     * @return Endereco Self instance filled or empty
     */
    public function load($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return $this->fromArray($row);
    }

    /**
     * Load into this object from database using, CEP
     * @param  string $cep cep to find Endereço
     * @return Endereco Self filled instance or empty when not found
     */
    public function loadByCEP($cep)
    {
        return $this->load([
            'cep' => strval($cep),
        ]);
    }

    /**
     * Load into this object from database using, BairroID, Logradouro
     * @param  int $bairro_id bairro to find Endereço
     * @param  string $logradouro logradouro to find Endereço
     * @return Endereco Self filled instance or empty when not found
     */
    public function loadByBairroIDLogradouro($bairro_id, $logradouro)
    {
        return $this->load([
            'bairroid' => intval($bairro_id),
            'logradouro' => strval($logradouro),
        ]);
    }

    /**
     * Cidade a qual o endereço pertence
     * @return \MZ\Location\Cidade The object fetched from database
     */
    public function findCidadeID()
    {
        return \MZ\Location\Cidade::findByID($this->getCidadeID());
    }

    /**
     * Bairro a qual o endereço está localizado
     * @return \MZ\Location\Bairro The object fetched from database
     */
    public function findBairroID()
    {
        return \MZ\Location\Bairro::findByID($this->getBairroID());
    }

    /**
     * Get allowed keys array
     * @return array allowed keys array
     */
    private static function getAllowedKeys()
    {
        $endereco = new Endereco();
        $allowed = Filter::concatKeys('e.', $endereco->toArray());
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
        return Filter::orderBy($order, $allowed, 'e.');
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
            $field = 'e.logradouro LIKE ?';
            $condition[$field] = '%'.$search.'%';
            $allowed[$field] = true;
            unset($condition['search']);
        }
        return Filter::keys($condition, $allowed, 'e.');
    }

    /**
     * Fetch data from database with a condition
     * @param  array $condition condition to filter rows
     * @param  array $order order rows
     * @return SelectQuery query object with condition statement
     */
    private static function query($condition = [], $order = [])
    {
        $query = DB::from('Enderecos e');
        $condition = self::filterCondition($condition);
        $query = DB::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('e.logradouro ASC');
        $query = $query->orderBy('e.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order order rows
     * @return Endereco A filled Endereço or empty instance
     */
    public static function find($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return new Endereco($row);
    }

    /**
     * Find this object on database using, CEP
     * @param  string $cep cep to find Endereço
     * @return Endereco A filled instance or empty when not found
     */
    public static function findByCEP($cep)
    {
        $result = new self();
        return $result->loadByCEP($cep);
    }

    /**
     * Find this object on database using, BairroID, Logradouro
     * @param  int $bairro_id bairro to find Endereço
     * @param  string $logradouro logradouro to find Endereço
     * @return Endereco A filled instance or empty when not found
     */
    public static function findByBairroIDLogradouro($bairro_id, $logradouro)
    {
        $result = new self();
        return $result->loadByBairroIDLogradouro($bairro_id, $logradouro);
    }

    /**
     * Find all Endereço
     * @param  array  $condition Condition to get all Endereço
     * @param  array  $order     Order Endereço
     * @param  int    $limit     Limit data into row count
     * @param  int    $offset    Start offset to get rows
     * @return array             List of all rows instanced as Endereco
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
            $result[] = new Endereco($row);
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
