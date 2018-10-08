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

use MZ\Util\Mask;
use MZ\Util\Filter;
use MZ\Util\Validator;
use MZ\Database\DB;
use MZ\Database\SyncModel;
use MZ\Exception\ValidationException;

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
     * @return int id of Imposto
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param int $id Set id for Imposto
     * @return self Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Grupo do imposto
     * @return string grupo of Imposto
     */
    public function getGrupo()
    {
        return $this->grupo;
    }

    /**
     * Set Grupo value to new on param
     * @param string $grupo Set grupo for Imposto
     * @return self Self instance
     */
    public function setGrupo($grupo)
    {
        $this->grupo = $grupo;
        return $this;
    }

    /**
     * Informa se o imposto é do simples nacional
     * @return string simples nacional of Imposto
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
     * @param string $simples Set simples nacional for Imposto
     * @return self Self instance
     */
    public function setSimples($simples)
    {
        $this->simples = $simples;
        return $this;
    }

    /**
     * Informa se o imposto é por substituição tributária
     * @return string substituição tributária of Imposto
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
     * @param string $substituicao Set substituição tributária for Imposto
     * @return self Self instance
     */
    public function setSubstituicao($substituicao)
    {
        $this->substituicao = $substituicao;
        return $this;
    }

    /**
     * Informa o código do imposto
     * @return int código of Imposto
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set Codigo value to new on param
     * @param int $codigo Set código for Imposto
     * @return self Self instance
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
        return $this;
    }

    /**
     * Descrição do imposto
     * @return string descrição of Imposto
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Set Descricao value to new on param
     * @param string $descricao Set descrição for Imposto
     * @return self Self instance
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param boolean $recursive Allow rescursive conversion of fields
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
     * @param mixed $imposto Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($imposto = [])
    {
        if ($imposto instanceof self) {
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
            $this->setSimples('N');
        } else {
            $this->setSimples($imposto['simples']);
        }
        if (!isset($imposto['substituicao'])) {
            $this->setSubstituicao('N');
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
     * @param self $original Original instance without modifications
     * @param boolean $localized Informs if fields are localized
     * @return self Self instance
     */
    public function filter($original, $localized = false)
    {
        $this->setID($original->getID());
        $this->setCodigo(Filter::number($this->getCodigo()));
        $this->setDescricao(Filter::string($this->getDescricao()));
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
     * @return array All field of Imposto in array format
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function validate()
    {
        $errors = [];
        if (!Validator::checkInSet($this->getGrupo(), self::getGrupoOptions())) {
            $errors['grupo'] = _t('imposto.grupo_invalid');
        }
        if (!Validator::checkBoolean($this->getSimples())) {
            $errors['simples'] = _t('imposto.simples_invalid');
        }
        if (!Validator::checkBoolean($this->getSubstituicao())) {
            $errors['substituicao'] = _t('imposto.substituicao_invalid');
        }
        if (is_null($this->getCodigo())) {
            $errors['codigo'] = _t('imposto.codigo_cannot_empty');
        }
        if (is_null($this->getDescricao())) {
            $errors['descricao'] = _t('imposto.descricao_cannot_empty');
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
        if (contains(['Grupo', 'Simples', 'Substituicao', 'Codigo', 'UNIQUE'], $e->getMessage())) {
            return new ValidationException([
                'grupo' => _t(
                    'imposto.grupo_used',
                    $this->getGrupo()
                ),
                'simples' => _t(
                    'imposto.simples_used',
                    $this->getSimples()
                ),
                'substituicao' => _t(
                    'imposto.substituicao_used',
                    $this->getSubstituicao()
                ),
                'codigo' => _t(
                    'imposto.codigo_used',
                    $this->getCodigo()
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Insert a new Imposto into the database and fill instance from database
     * @return self Self instance
     * @throws \MZ\Exception\ValidationException for invalid input data
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
     * @param array $only Save these fields only, when empty save all fields except id
     * @return int rows affected
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function update($only = [])
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new ValidationException(
                ['id' => _t('imposto.id_cannot_empty')]
            );
        }
        $values = DB::filterValues($values, $only, false);
        try {
            $affected = DB::update('Impostos')
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
                ['id' => _t('imposto.id_cannot_empty')]
            );
        }
        $result = DB::deleteFrom('Impostos')
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
     * Load into this object from database using, Grupo, Simples, Substituicao, Codigo
     * @return self Self filled instance or empty when not found
     */
    public function loadByGrupoSimplesSubstituicaoCodigo()
    {
        return $this->load([
            'grupo' => strval($this->getGrupo()),
            'simples' => strval($this->getSimples()),
            'substituicao' => strval($this->getSubstituicao()),
            'codigo' => intval($this->getCodigo()),
        ]);
    }

    /**
     * Gets textual and translated Grupo for Imposto
     * @param int $index choose option from index
     * @return string[] A associative key -> translated representative text or text for index
     */
    public static function getGrupoOptions($index = null)
    {
        $options = [
            self::GRUPO_ICMS => _t('imposto.grupo_icms'),
            self::GRUPO_PIS => _t('imposto.grupo_pis'),
            self::GRUPO_COFINS => _t('imposto.grupo_cofins'),
            self::GRUPO_IPI => _t('imposto.grupo_ipi'),
            self::GRUPO_II => _t('imposto.grupo_ii'),
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
        $imposto = new self();
        $allowed = Filter::concatKeys('i.', $imposto->toArray());
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
        return Filter::orderBy($order, $allowed, 'i.');
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
     * @param array $condition condition to filter rows
     * @param array $order order rows
     * @return SelectQuery query object with condition statement
     */
    protected static function query($condition = [], $order = [])
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
     * @param array $condition Condition for searching the row
     * @param array $order order rows
     * @return self A filled Imposto or empty instance
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
     * @return self A filled Imposto or empty instance
     * @throws \Exception when register has not found
     */
    public static function findOrFail($condition, $order = [])
    {
        $result = self::find($condition, $order);
        if (!$result->exists()) {
            throw new \Exception(_t('imposto.not_found'), 404);
        }
        return $result;
    }

    /**
     * Find this object on database using, Grupo, Simples, Substituicao, Codigo
     * @param string $grupo grupo to find Imposto
     * @param string $simples simples nacional to find Imposto
     * @param string $substituicao substituição tributária to find Imposto
     * @param int $codigo código to find Imposto
     * @return self A filled instance or empty when not found
     */
    public static function findByGrupoSimplesSubstituicaoCodigo($grupo, $simples, $substituicao, $codigo)
    {
        $result = new self();
        $result->setGrupo($grupo);
        $result->setSimples($simples);
        $result->setSubstituicao($substituicao);
        $result->setCodigo($codigo);
        return $result->loadByGrupoSimplesSubstituicaoCodigo();
    }

    /**
     * Find all Imposto
     * @param array  $condition Condition to get all Imposto
     * @param array  $order     Order Imposto
     * @param int    $limit     Limit data into row count
     * @param int    $offset    Start offset to get rows
     * @return self[] List of all rows instanced as Imposto
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
