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

use MZ\Util\Mask;
use MZ\Util\Filter;
use MZ\Util\Validator;
use MZ\Database\DB;
use MZ\Database\SyncModel;
use MZ\Exception\ValidationException;

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
     * @return int id of Endereço
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param int $id Set id for Endereço
     * @return self Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Cidade a qual o endereço pertence
     * @return int cidade of Endereço
     */
    public function getCidadeID()
    {
        return $this->cidade_id;
    }

    /**
     * Set CidadeID value to new on param
     * @param int $cidade_id Set cidade for Endereço
     * @return self Self instance
     */
    public function setCidadeID($cidade_id)
    {
        $this->cidade_id = $cidade_id;
        return $this;
    }

    /**
     * Bairro a qual o endereço está localizado
     * @return int bairro of Endereço
     */
    public function getBairroID()
    {
        return $this->bairro_id;
    }

    /**
     * Set BairroID value to new on param
     * @param int $bairro_id Set bairro for Endereço
     * @return self Self instance
     */
    public function setBairroID($bairro_id)
    {
        $this->bairro_id = $bairro_id;
        return $this;
    }

    /**
     * Nome da rua ou avenida
     * @return string logradouro of Endereço
     */
    public function getLogradouro()
    {
        return $this->logradouro;
    }

    /**
     * Set Logradouro value to new on param
     * @param string $logradouro Set logradouro for Endereço
     * @return self Self instance
     */
    public function setLogradouro($logradouro)
    {
        $this->logradouro = $logradouro;
        return $this;
    }

    /**
     * Código dos correios para identificar a rua ou avenida
     * @return string cep of Endereço
     */
    public function getCEP()
    {
        return $this->cep;
    }

    /**
     * Set CEP value to new on param
     * @param string $cep Set cep for Endereço
     * @return self Self instance
     */
    public function setCEP($cep)
    {
        $this->cep = $cep;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param boolean $recursive Allow rescursive conversion of fields
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
     * @param mixed $endereco Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($endereco = [])
    {
        if ($endereco instanceof self) {
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
        $endereco['cep'] = Mask::cep($endereco['cep']);
        return $endereco;
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
        $this->setCidadeID(Filter::number($this->getCidadeID()));
        $this->setBairroID(Filter::number($this->getBairroID()));
        $this->setLogradouro(Filter::string($this->getLogradouro()));
        $this->setCEP(Filter::unmask($this->getCEP(), _p('Mascara', 'CEP')));
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
     * @return array All field of Endereco in array format
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getCidadeID())) {
            $errors['cidadeid'] = _t('endereco.cidade_id_cannot_empty');
        }
        if (is_null($this->getBairroID())) {
            $errors['bairroid'] = _t('endereco.bairro_id_cannot_empty');
        }
        if (is_null($this->getLogradouro())) {
            $errors['logradouro'] = _t('endereco.logradouro_cannot_empty');
        }
        if (!Validator::checkCEP($this->getCEP())) {
            $errors['cep'] = _t('cep_invalid', _p('Titulo', 'CEP'));
        }
        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
        return $this->toArray();
    }

    /**
     * Translate SQL exception into application exception
     * @param \Exception $e exception to translate into a readable error
     * @return \MZ\Exception\ValidationException new exception translated
     */
    protected function translate($e)
    {
        if (contains(['CEP', 'UNIQUE'], $e->getMessage())) {
            return new ValidationException([
                'cep' => _t(
                    'endereco.cep_used',
                    _p('Titulo', 'CEP'),
                    $this->getCEP()
                ),
            ]);
        }
        if (contains(['BairroID', 'Logradouro', 'UNIQUE'], $e->getMessage())) {
            return new ValidationException([
                'bairroid' => _t(
                    'endereco.bairro_id_used',
                    $this->getBairroID()
                ),
                'logradouro' => _t(
                    'endereco.logradouro_used',
                    $this->getLogradouro()
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Insert a new Endereço into the database and fill instance from database
     * @return self Self instance
     * @throws \MZ\Exception\ValidationException for invalid input data
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
     * @param array $only Save these fields only, when empty save all fields except id
     * @return int rows affected
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function update($only = [])
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new ValidationException(
                ['id' => _t('endereco.id_cannot_empty')]
            );
        }
        $values = DB::filterValues($values, $only, false);
        try {
            $affected = DB::update('Enderecos')
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
                ['id' => _t('endereco.id_cannot_empty')]
            );
        }
        $result = DB::deleteFrom('Enderecos')
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
     * Load into this object from database using, CEP
     * @return self Self filled instance or empty when not found
     */
    public function loadByCEP()
    {
        return $this->load([
            'cep' => strval($this->getCEP()),
        ]);
    }

    /**
     * Load into this object from database using, BairroID, Logradouro
     * @return self Self filled instance or empty when not found
     */
    public function loadByBairroIDLogradouro()
    {
        return $this->load([
            'bairroid' => intval($this->getBairroID()),
            'logradouro' => strval($this->getLogradouro()),
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
        $endereco = new self();
        $allowed = Filter::concatKeys('e.', $endereco->toArray());
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
        return Filter::orderBy($order, $allowed, 'e.');
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
            $field = 'e.logradouro LIKE ?';
            $condition[$field] = '%'.$search.'%';
            $allowed[$field] = true;
            unset($condition['search']);
        }
        return Filter::keys($condition, $allowed, 'e.');
    }

    /**
     * Fetch data from database with a condition
     * @param array $condition condition to filter rows
     * @param array $order order rows
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
     * @param array $condition Condition for searching the row
     * @param array $order order rows
     * @return self A filled Endereço or empty instance
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
     * @return self A filled Endereço or empty instance
     * @throws \Exception when register has not found
     */
    public static function findOrFail($condition, $order = [])
    {
        $result = self::find($condition, $order);
        if (!$result->exists()) {
            throw new \Exception(_t('endereco.not_found'), 404);
        }
        return $result;
    }

    /**
     * Find this object on database using, CEP
     * @param string $cep cep to find Endereço
     * @return self A filled instance or empty when not found
     */
    public static function findByCEP($cep)
    {
        $result = new self();
        $result->setCEP($cep);
        return $result->loadByCEP();
    }

    /**
     * Find this object on database using, BairroID, Logradouro
     * @param int $bairro_id bairro to find Endereço
     * @param string $logradouro logradouro to find Endereço
     * @return self A filled instance or empty when not found
     */
    public static function findByBairroIDLogradouro($bairro_id, $logradouro)
    {
        $result = new self();
        $result->setBairroID($bairro_id);
        $result->setLogradouro($logradouro);
        return $result->loadByBairroIDLogradouro();
    }

    /**
     * Find all Endereço
     * @param array  $condition Condition to get all Endereço
     * @param array  $order     Order Endereço
     * @param int    $limit     Limit data into row count
     * @param int    $offset    Start offset to get rows
     * @return self[] List of all rows instanced as Endereco
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
