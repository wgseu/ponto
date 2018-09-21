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

use MZ\Database\SyncModel;
use MZ\Database\DB;
use MZ\Util\Filter;
use MZ\Util\Validator;

/**
 * Impostos disponíveis para informar no produto
 */
class Imposto extends SyncModel
{

    /**
     * Grupo do imposto
     */
    const GRUPO_ICMS = 'ICMS';
    const GRUPO_PIS = 'PIS';
    const GRUPO_COFINS = 'COFINS';
    const GRUPO_IPI = 'IPI';
    const GRUPO_II = 'II';

    /**
     * Identificador do imposto
     */
    private $id;
    /**
     * Grupo do imposto
     */
    private $grupo;
    /**
     * Informa se o imposto é do simples nacional
     */
    private $simples;
    /**
     * Informa se o imposto é por substituição tributária
     */
    private $substituicao;
    /**
     * Informa o código do imposto
     */
    private $codigo;
    /**
     * Descrição do imposto
     */
    private $descricao;

    /**
     * Constructor for a new empty instance of Imposto
     * @param array $imposto All field and values to fill the instance
     */
    public function __construct($imposto = [])
    {
        parent::__construct($imposto);
    }

    /**
     * Identificador do imposto
     * @return mixed ID of Imposto
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param  mixed $id new value for ID
     * @return Imposto Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Grupo do imposto
     * @return mixed Grupo of Imposto
     */
    public function getGrupo()
    {
        return $this->grupo;
    }

    /**
     * Set Grupo value to new on param
     * @param  mixed $grupo new value for Grupo
     * @return Imposto Self instance
     */
    public function setGrupo($grupo)
    {
        $this->grupo = $grupo;
        return $this;
    }

    /**
     * Informa se o imposto é do simples nacional
     * @return mixed Simples nacional of Imposto
     */
    public function getSimples()
    {
        return $this->simples;
    }

    /**
     * Informa se o imposto é do simples nacional
     * @return boolean Check if o of Simples is selected or checked
     */
    public function isSimples()
    {
        return $this->simples == 'Y';
    }

    /**
     * Set Simples value to new on param
     * @param  mixed $simples new value for Simples
     * @return Imposto Self instance
     */
    public function setSimples($simples)
    {
        $this->simples = $simples;
        return $this;
    }

    /**
     * Informa se o imposto é por substituição tributária
     * @return mixed Substituição tributária of Imposto
     */
    public function getSubstituicao()
    {
        return $this->substituicao;
    }

    /**
     * Informa se o imposto é por substituição tributária
     * @return boolean Check if a of Substituicao is selected or checked
     */
    public function isSubstituicao()
    {
        return $this->substituicao == 'Y';
    }

    /**
     * Set Substituicao value to new on param
     * @param  mixed $substituicao new value for Substituicao
     * @return Imposto Self instance
     */
    public function setSubstituicao($substituicao)
    {
        $this->substituicao = $substituicao;
        return $this;
    }

    /**
     * Informa o código do imposto
     * @return mixed Código of Imposto
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set Codigo value to new on param
     * @param  mixed $codigo new value for Codigo
     * @return Imposto Self instance
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
        return $this;
    }

    /**
     * Descrição do imposto
     * @return mixed Descrição of Imposto
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Set Descricao value to new on param
     * @param  mixed $descricao new value for Descricao
     * @return Imposto Self instance
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param  boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $imposto = parent::toArray($recursive);
        $imposto['id'] = $this->getID();
        $imposto['grupo'] = $this->getGrupo();
        $imposto['simples'] = $this->getSimples();
        $imposto['substituicao'] = $this->getSubstituicao();
        $imposto['codigo'] = $this->getCodigo();
        $imposto['descricao'] = $this->getDescricao();
        return $imposto;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param  mixed $imposto Associated key -> value to assign into this instance
     * @return Imposto Self instance
     */
    public function fromArray($imposto = [])
    {
        if ($imposto instanceof Imposto) {
            $imposto = $imposto->toArray();
        } elseif (!is_array($imposto)) {
            $imposto = [];
        }
        parent::fromArray($imposto);
        if (!isset($imposto['id'])) {
            $this->setID(null);
        } else {
            $this->setID($imposto['id']);
        }
        if (!isset($imposto['grupo'])) {
            $this->setGrupo(null);
        } else {
            $this->setGrupo($imposto['grupo']);
        }
        if (!isset($imposto['simples'])) {
            $this->setSimples(null);
        } else {
            $this->setSimples($imposto['simples']);
        }
        if (!isset($imposto['substituicao'])) {
            $this->setSubstituicao(null);
        } else {
            $this->setSubstituicao($imposto['substituicao']);
        }
        if (!isset($imposto['codigo'])) {
            $this->setCodigo(null);
        } else {
            $this->setCodigo($imposto['codigo']);
        }
        if (!isset($imposto['descricao'])) {
            $this->setDescricao(null);
        } else {
            $this->setDescricao($imposto['descricao']);
        }
        return $this;
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $imposto = parent::publish();
        return $imposto;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param Imposto $original Original instance without modifications
     */
    public function filter($original, $localized = false)
    {
        $this->setID($original->getID());
        $this->setCodigo(Filter::number($this->getCodigo()));
        $this->setDescricao(Filter::string($this->getDescricao()));
    }

    /**
     * Clean instance resources like images and docs
     * @param  Imposto $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Imposto in array format
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getGrupo())) {
            $errors['grupo'] = 'O grupo não pode ser vazio';
        }
        if (!Validator::checkInSet($this->getGrupo(), self::getGrupoOptions(), true)) {
            $errors['grupo'] = 'O grupo é inválido';
        }
        if (is_null($this->getSimples())) {
            $errors['simples'] = 'O simples nacional não pode ser vazio';
        }
        if (!Validator::checkBoolean($this->getSimples(), true)) {
            $errors['simples'] = 'O simples nacional é inválido';
        }
        if (is_null($this->getSubstituicao())) {
            $errors['substituicao'] = 'A substituição tributária não pode ser vazia';
        }
        if (!Validator::checkBoolean($this->getSubstituicao(), true)) {
            $errors['substituicao'] = 'A substituição tributária é inválida';
        }
        if (is_null($this->getCodigo())) {
            $errors['codigo'] = 'O código não pode ser vazio';
        }
        if (is_null($this->getDescricao())) {
            $errors['descricao'] = 'A descrição não pode ser vazia';
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
        if (stripos($e->getMessage(), 'UK_Imposto') !== false) {
            return new \MZ\Exception\ValidationException([
                'grupo' => sprintf(
                    'O grupo "%s" já está cadastrado',
                    $this->getGrupo()
                ),
                'simples' => sprintf(
                    'O simples nacional "%s" já está cadastrado',
                    $this->getSimples()
                ),
                'substituicao' => sprintf(
                    'A substituição tributária "%s" já está cadastrada',
                    $this->getSubstituicao()
                ),
                'codigo' => sprintf(
                    'O código "%s" já está cadastrado',
                    $this->getCodigo()
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Insert a new Imposto into the database and fill instance from database
     * @return Imposto Self instance
     */
    public function insert()
    {
        $this->setID(null);
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = DB::insertInto('Impostos')->values($values)->execute();
            $this->setID($id);
            $this->loadByID();
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Imposto with instance values into database for ID
     * @param  array $only Save these fields only, when empty save all fields except id
     * @return Imposto Self instance
     */
    public function update($only = [])
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador do imposto não foi informado');
        }
        $values = DB::filterValues($values, $only, false);
        try {
            DB::update('Impostos')
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
            throw new \Exception('O identificador do imposto não foi informado');
        }
        $result = DB::deleteFrom('Impostos')
            ->where('id', $this->getID())
            ->execute();
        return $result;
    }

    /**
     * Load one register for it self with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order associative field name -> [-1, 1]
     * @return Imposto Self instance filled or empty
     */
    public function load($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return $this->fromArray($row);
    }

    /**
     * Load into this object from database using, Grupo, Simples, Substituicao, Codigo
     * @param  string $grupo grupo to find Imposto
     * @param  string $simples simples nacional to find Imposto
     * @param  string $substituicao substituição tributária to find Imposto
     * @param  int $codigo código to find Imposto
     * @return Imposto Self filled instance or empty when not found
     */
    public function loadByGrupoSimplesSubstituicaoCodigo($grupo, $simples, $substituicao, $codigo)
    {
        return $this->load([
            'grupo' => strval($grupo),
            'simples' => strval($simples),
            'substituicao' => strval($substituicao),
            'codigo' => intval($codigo),
        ]);
    }

    /**
     * Gets textual and translated Grupo for Imposto
     * @param  int $index choose option from index
     * @return mixed A associative key -> translated representative text or text for index
     */
    public static function getGrupoOptions($index = null)
    {
        $options = [
            self::GRUPO_ICMS => 'ICMS',
            self::GRUPO_PIS => 'PIS',
            self::GRUPO_COFINS => 'COFINS',
            self::GRUPO_IPI => 'IPI',
            self::GRUPO_II => 'II',
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
        $imposto = new Imposto();
        $allowed = Filter::concatKeys('i.', $imposto->toArray());
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
        return Filter::orderBy($order, $allowed, 'i.');
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
            $field = 'i.descricao LIKE ?';
            $condition[$field] = '%'.$search.'%';
            $allowed[$field] = true;
            unset($condition['search']);
        }
        return Filter::keys($condition, $allowed, 'i.');
    }

    /**
     * Fetch data from database with a condition
     * @param  array $condition condition to filter rows
     * @param  array $order order rows
     * @return SelectQuery query object with condition statement
     */
    private static function query($condition = [], $order = [])
    {
        $query = DB::from('Impostos i');
        $condition = self::filterCondition($condition);
        $query = DB::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('i.descricao ASC');
        $query = $query->orderBy('i.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order order rows
     * @return Imposto A filled Imposto or empty instance
     */
    public static function find($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return new Imposto($row);
    }

    /**
     * Find this object on database using, Grupo, Simples, Substituicao, Codigo
     * @param  string $grupo grupo to find Imposto
     * @param  string $simples simples nacional to find Imposto
     * @param  string $substituicao substituição tributária to find Imposto
     * @param  int $codigo código to find Imposto
     * @return Imposto A filled instance or empty when not found
     */
    public static function findByGrupoSimplesSubstituicaoCodigo($grupo, $simples, $substituicao, $codigo)
    {
        $result = new self();
        return $result->loadByGrupoSimplesSubstituicaoCodigo($grupo, $simples, $substituicao, $codigo);
    }

    /**
     * Find all Imposto
     * @param  array  $condition Condition to get all Imposto
     * @param  array  $order     Order Imposto
     * @param  int    $limit     Limit data into row count
     * @param  int    $offset    Start offset to get rows
     * @return array             List of all rows instanced as Imposto
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
            $result[] = new Imposto($row);
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
