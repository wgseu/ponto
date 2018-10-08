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
 * Estado federativo de um país
 */
class Estado extends SyncModel
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
    public function __construct($estado = [])
    {
        parent::__construct($estado);
    }

    /**
     * Identificador do estado
     * @return int id of Estado
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param int $id Set id for Estado
     * @return self Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * País a qual esse estado pertence
     * @return int país of Estado
     */
    public function getPaisID()
    {
        return $this->pais_id;
    }

    /**
     * Set PaisID value to new on param
     * @param int $pais_id Set país for Estado
     * @return self Self instance
     */
    public function setPaisID($pais_id)
    {
        $this->pais_id = $pais_id;
        return $this;
    }

    /**
     * Nome do estado
     * @return string nome of Estado
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set Nome value to new on param
     * @param string $nome Set nome for Estado
     * @return self Self instance
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * Sigla do estado
     * @return string uf of Estado
     */
    public function getUF()
    {
        return $this->uf;
    }

    /**
     * Set UF value to new on param
     * @param string $uf Set uf for Estado
     * @return self Self instance
     */
    public function setUF($uf)
    {
        $this->uf = $uf;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param boolean $recursive Allow rescursive conversion of fields
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
     * @param mixed $estado Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($estado = [])
    {
        if ($estado instanceof self) {
            $estado = $estado->toArray();
        } elseif (!is_array($estado)) {
            $estado = [];
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
     * @param self $original Original instance without modifications
     * @param boolean $localized Informs if fields are localized
     * @return self Self instance
     */
    public function filter($original, $localized = false)
    {
        $this->setID($original->getID());
        $this->setPaisID(Filter::number($this->getPaisID()));
        $this->setNome(Filter::string($this->getNome()));
        $this->setUF(Filter::string($this->getUF()));
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
     * @return array All field of Estado in array format
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getPaisID())) {
            $errors['paisid'] = _t('estado.pais_id_cannot_empty');
        }
        if (is_null($this->getNome())) {
            $errors['nome'] = _t('estado.nome_cannot_empty');
        }
        if (is_null($this->getUF())) {
            $errors['uf'] = _t('estado.uf_cannot_empty');
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
        if (contains(['PaisID', 'Nome', 'UNIQUE'], $e->getMessage())) {
            return new ValidationException([
                'paisid' => _t(
                    'estado.pais_id_used',
                    $this->getPaisID()
                ),
                'nome' => _t(
                    'estado.nome_used',
                    $this->getNome()
                ),
            ]);
        }
        if (contains(['PaisID', 'UF', 'UNIQUE'], $e->getMessage())) {
            return new ValidationException([
                'paisid' => _t(
                    'estado.pais_id_used',
                    $this->getPaisID()
                ),
                'uf' => _t(
                    'estado.uf_used',
                    $this->getUF()
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Insert a new Estado into the database and fill instance from database
     * @return self Self instance
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function insert()
    {
        $this->setID(null);
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = DB::insertInto('Estados')->values($values)->execute();
            $this->setID($id);
            $this->loadByID();
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Estado with instance values into database for ID
     * @param array $only Save these fields only, when empty save all fields except id
     * @return int rows affected
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function update($only = [])
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new ValidationException(
                ['id' => _t('estado.id_cannot_empty')]
            );
        }
        $values = DB::filterValues($values, $only, false);
        try {
            $affected = DB::update('Estados')
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
                ['id' => _t('estado.id_cannot_empty')]
            );
        }
        $result = DB::deleteFrom('Estados')
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
     * Load into this object from database using, PaisID, Nome
     * @return self Self filled instance or empty when not found
     */
    public function loadByPaisIDNome()
    {
        return $this->load([
            'paisid' => intval($this->getPaisID()),
            'nome' => strval($this->getNome()),
        ]);
    }

    /**
     * Load into this object from database using, PaisID, UF
     * @return self Self filled instance or empty when not found
     */
    public function loadByPaisIDUF()
    {
        return $this->load([
            'paisid' => intval($this->getPaisID()),
            'uf' => strval($this->getUF()),
        ]);
    }

    /**
     * País a qual esse estado pertence
     * @return \MZ\Location\Pais The object fetched from database
     */
    public function findPaisID()
    {
        return \MZ\Location\Pais::findByID($this->getPaisID());
    }

    /**
     * Get allowed keys array
     * @return array allowed keys array
     */
    private static function getAllowedKeys()
    {
        $estado = new self();
        $allowed = Filter::concatKeys('e.', $estado->toArray());
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
    protected static function filterCondition($condition)
    {
        $allowed = self::getAllowedKeys();
        return Filter::keys($condition, $allowed, 'e.');
    }

    /**
     * Fetch data from database with a condition
     * @param array $condition condition to filter rows
     * @param array $order order rows
     * @return SelectQuery query object with condition statement
     */
    protected static function query($condition = [], $order = [])
    {
        $query = DB::from('Estados e');
        if (isset($condition['search'])) {
            $search = $condition['search'];
            $query = DB::buildSearch($search, DB::concat(['e.nome', '" "', 'e.uf']), $query);
        }
        $condition = self::filterCondition($condition);
        $query = DB::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('e.nome ASC');
        $query = $query->orderBy('e.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param array $condition Condition for searching the row
     * @param array $order order rows
     * @return self A filled Estado or empty instance
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
     * @return self A filled Estado or empty instance
     * @throws \Exception when register has not found
     */
    public static function findOrFail($condition, $order = [])
    {
        $result = self::find($condition, $order);
        if (!$result->exists()) {
            throw new \Exception(_t('estado.not_found'), 404);
        }
        return $result;
    }

    /**
     * Find this object on database using, PaisID, Nome
     * @param int $pais_id país to find Estado
     * @param string $nome nome to find Estado
     * @return self A filled instance or empty when not found
     */
    public static function findByPaisIDNome($pais_id, $nome)
    {
        $result = new self();
        $result->setPaisID($pais_id);
        $result->setNome($nome);
        return $result->loadByPaisIDNome();
    }

    /**
     * Find this object on database using, PaisID, UF
     * @param int $pais_id país to find Estado
     * @param string $uf uf to find Estado
     * @return self A filled instance or empty when not found
     */
    public static function findByPaisIDUF($pais_id, $uf)
    {
        $result = new self();
        $result->setPaisID($pais_id);
        $result->setUF($uf);
        return $result->loadByPaisIDUF();
    }

    /**
     * Find all Estado
     * @param array  $condition Condition to get all Estado
     * @param array  $order     Order Estado
     * @param int    $limit     Limit data into row count
     * @param int    $offset    Start offset to get rows
     * @return self[] List of all rows instanced as Estado
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
