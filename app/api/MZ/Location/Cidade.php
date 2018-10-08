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
use MZ\System\Permissao;

/**
 * Cidade de um estado, contém bairros
 */
class Cidade extends SyncModel
{

    /**
     * Código que identifica a cidade
     */
    private $id;
    /**
     * Informa a qual estado a cidade pertence
     */
    private $estado_id;
    /**
     * Nome da cidade, é único para cada estado
     */
    private $nome;
    /**
     * Código dos correios para identificação da cidade
     */
    private $cep;

    /**
     * Constructor for a new empty instance of Cidade
     * @param array $cidade All field and values to fill the instance
     */
    public function __construct($cidade = [])
    {
        parent::__construct($cidade);
    }

    /**
     * Código que identifica a cidade
     * @return int id of Cidade
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param int $id Set id for Cidade
     * @return self Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Informa a qual estado a cidade pertence
     * @return int estado of Cidade
     */
    public function getEstadoID()
    {
        return $this->estado_id;
    }

    /**
     * Set EstadoID value to new on param
     * @param int $estado_id Set estado for Cidade
     * @return self Self instance
     */
    public function setEstadoID($estado_id)
    {
        $this->estado_id = $estado_id;
        return $this;
    }

    /**
     * Nome da cidade, é único para cada estado
     * @return string nome of Cidade
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set Nome value to new on param
     * @param string $nome Set nome for Cidade
     * @return self Self instance
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * Código dos correios para identificação da cidade
     * @return string cep of Cidade
     */
    public function getCEP()
    {
        return $this->cep;
    }

    /**
     * Set CEP value to new on param
     * @param string $cep Set cep for Cidade
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
        $cidade = parent::toArray($recursive);
        $cidade['id'] = $this->getID();
        $cidade['estadoid'] = $this->getEstadoID();
        $cidade['nome'] = $this->getNome();
        $cidade['cep'] = $this->getCEP();
        return $cidade;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param mixed $cidade Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($cidade = [])
    {
        if ($cidade instanceof self) {
            $cidade = $cidade->toArray();
        } elseif (!is_array($cidade)) {
            $cidade = [];
        }
        parent::fromArray($cidade);
        if (!isset($cidade['id'])) {
            $this->setID(null);
        } else {
            $this->setID($cidade['id']);
        }
        if (!isset($cidade['estadoid'])) {
            $this->setEstadoID(null);
        } else {
            $this->setEstadoID($cidade['estadoid']);
        }
        if (!isset($cidade['nome'])) {
            $this->setNome(null);
        } else {
            $this->setNome($cidade['nome']);
        }
        if (!array_key_exists('cep', $cidade)) {
            $this->setCEP(null);
        } else {
            $this->setCEP($cidade['cep']);
        }
        return $this;
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $cidade = parent::publish();
        $cidade['cep'] = Mask::cep($cidade['cep']);
        return $cidade;
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
        $this->setEstadoID(Filter::number($this->getEstadoID()));
        $this->setNome(Filter::string($this->getNome()));
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
     * @return array All field of Cidade in array format
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getEstadoID())) {
            $errors['estadoid'] = _t('cidade.estado_id_cannot_empty');
        }
        if (is_null($this->getNome())) {
            $errors['nome'] = _t('cidade.nome_cannot_empty');
        }
        if (!Validator::checkCEP($this->getCEP(), true)) {
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
        if (contains(['EstadoID', 'Nome', 'UNIQUE'], $e->getMessage())) {
            return new ValidationException([
                'estadoid' => _t(
                    'cidade.estado_id_used',
                    $this->getEstadoID()
                ),
                'nome' => _t(
                    'cidade.nome_used',
                    $this->getNome()
                ),
            ]);
        }
        if (contains(['CEP', 'UNIQUE'], $e->getMessage())) {
            return new ValidationException([
                'cep' => _t(
                    'cidade.cep_used',
                    _p('Titulo', 'CEP'),
                    $this->getCEP()
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Insert a new Cidade into the database and fill instance from database
     * @return self Self instance
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function insert()
    {
        $this->setID(null);
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = DB::insertInto('Cidades')->values($values)->execute();
            $this->setID($id);
            $this->loadByID();
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Cidade with instance values into database for ID
     * @param array $only Save these fields only, when empty save all fields except id
     * @return int rows affected
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function update($only = [])
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new ValidationException(
                ['id' => _t('cidade.id_cannot_empty')]
            );
        }
        $values = DB::filterValues($values, $only, false);
        try {
            $affected = DB::update('Cidades')
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
                ['id' => _t('cidade.id_cannot_empty')]
            );
        }
        $result = DB::deleteFrom('Cidades')
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
     * Load into this object from database using, ID
     * @return self Self filled instance or empty when not found
     */
    public function loadByID()
    {
        return $this->load([
            'id' => intval($this->getID()),
        ]);
    }

    /**
     * Load into this object from database using, EstadoID, Nome
     * @return self Self filled instance or empty when not found
     */
    public function loadByEstadoIDNome()
    {
        return $this->load([
            'estadoid' => intval($this->getEstadoID()),
            'nome' => strval($this->getNome()),
        ]);
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
     * Informa a qual estado a cidade pertence
     * @return \MZ\Location\Estado The object fetched from database
     */
    public function findEstadoID()
    {
        return \MZ\Location\Estado::findByID($this->getEstadoID());
    }

    /**
     * Get allowed keys array
     * @return array allowed keys array
     */
    private static function getAllowedKeys()
    {
        $cidade = new self();
        $allowed = Filter::concatKeys('c.', $cidade->toArray());
        $allowed['e.paisid'] = true;
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
        return Filter::orderBy($order, $allowed, ['c.', 'e.']);
    }

    /**
     * Filter condition array with allowed fields
     * @param array $condition condition to filter rows
     * @return array allowed condition
     */
    protected static function filterCondition($condition)
    {
        $allowed = self::getAllowedKeys();
        if (isset($condition['search'])) {
            $search = trim($condition['search']);
            $field = 'c.nome LIKE ?';
            $condition[$field] = '%'.$search.'%';
            $allowed[$field] = true;
            unset($condition['search']);
        }
        return Filter::keys($condition, $allowed, ['c.', 'e.']);
    }

    /**
     * Fetch data from database with a condition
     * @param array $condition condition to filter rows
     * @param array $order order rows
     * @return SelectQuery query object with condition statement
     */
    protected static function query($condition = [], $order = [])
    {
        $query = DB::from('Cidades c');
        $query = $query->leftJoin('Estados e ON e.id = c.estadoid');
        $condition = self::filterCondition($condition);
        $query = DB::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('c.nome ASC');
        $query = $query->orderBy('c.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param array $condition Condition for searching the row
     * @param array $order order rows
     * @return self A filled Cidade or empty instance
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
     * @return self A filled Cidade or empty instance
     * @throws \Exception when register has not found
     */
    public static function findOrFail($condition, $order = [])
    {
        $result = self::find($condition, $order);
        if (!$result->exists()) {
            throw new \Exception(_t('cidade.not_found'), 404);
        }
        return $result;
    }

    /**
     * Find city with that name or register a new one
     * @param  int $estado_id estado to find Cidade
     * @param  string $nome nome to find Cidade
     * @return Cidade A filled instance
     */
    public static function findOrInsert($estado_id, $nome)
    {
        $cidade = self::findByEstadoIDNome(
            $estado_id,
            Filter::string($nome)
        );
        if ($cidade->exists()) {
            return $cidade;
        }
        $msg = 'A cidade não está cadastrada e você não tem permissão para cadastrar uma';
        app()->needPermission([Permissao::NOME_CADASTROCIDADES], $msg);
        $cidade->setEstadoID($estado_id);
        $cidade->setNome($nome);
        $cidade->filter(new Cidade());
        try {
            $cidade->insert();
        } catch (ValidationException $e) {
            $errors = $e->getErrors();
            if (isset($errors['nome'])) {
                $errors['cidade'] = $errors['nome'];
                unset($errors['nome']);
            }
            throw new ValidationException($errors);
        }
        return $cidade;
    }

    /**
     * Find this object on database using, EstadoID, Nome
     * @param int $estado_id estado to find Cidade
     * @param string $nome nome to find Cidade
     * @return self A filled instance or empty when not found
     */
    public static function findByEstadoIDNome($estado_id, $nome)
    {
        $result = new self();
        $result->setEstadoID($estado_id);
        $result->setNome($nome);
        return $result->loadByEstadoIDNome();
    }

    /**
     * Find this object on database using, CEP
     * @param string $cep cep to find Cidade
     * @return self A filled instance or empty when not found
     */
    public static function findByCEP($cep)
    {
        $result = new self();
        $result->setCEP($cep);
        return $result->loadByCEP();
    }

    /**
     * Find all Cidade
     * @param array  $condition Condition to get all Cidade
     * @param array  $order     Order Cidade
     * @param int    $limit     Limit data into row count
     * @param int    $offset    Start offset to get rows
     * @return self[] List of all rows instanced as Cidade
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
