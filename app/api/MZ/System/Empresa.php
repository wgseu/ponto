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
namespace MZ\System;

use MZ\Util\Mask;
use MZ\Util\Filter;
use MZ\Util\Validator;
use MZ\Database\DB;
use MZ\Database\SyncModel;
use MZ\Exception\ValidationException;

/**
 * Informações da empresa
 */
class Empresa extends SyncModel
{

    /**
     * Identificador único da empresa, valor 1
     */
    private $id;
    /**
     * País em que a empresa está situada
     */
    private $pais_id;
    /**
     * Informa a empresa do cadastro de clientes, a empresa deve ser um cliente
     * do tipo pessoa jurídica
     */
    private $empresa_id;
    /**
     * Informa quem realiza o suporte do sistema, deve ser um cliente do tipo
     * empresa que possua um acionista como representante
     */
    private $parceiro_id;
    /**
     * Opções gerais do sistema como opções de impressão e comportamento
     */
    private $opcoes;

    /** Company fields */

    /**
     * Company options
     * @var \MZ\System\Settings
     */
    private $options;

    /** End company fields */

    /**
     * Constructor for a new empty instance of Empresa
     * @param array $empresa All field and values to fill the instance
     */
    public function __construct($empresa = [])
    {
        parent::__construct($empresa);
        $this->options = new \MZ\Core\Settings();
    }

    /**
     * Identificador único da empresa, valor 1
     * @return string id of Empresa
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param string $id Set id for Empresa
     * @return self Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * País em que a empresa está situada
     * @return int país of Empresa
     */
    public function getPaisID()
    {
        return $this->pais_id;
    }

    /**
     * Set PaisID value to new on param
     * @param int $pais_id Set país for Empresa
     * @return self Self instance
     */
    public function setPaisID($pais_id)
    {
        $this->pais_id = $pais_id;
        return $this;
    }

    /**
     * Informa a empresa do cadastro de clientes, a empresa deve ser um cliente
     * do tipo pessoa jurídica
     * @return int empresa of Empresa
     */
    public function getEmpresaID()
    {
        return $this->empresa_id;
    }

    /**
     * Set EmpresaID value to new on param
     * @param int $empresa_id Set empresa for Empresa
     * @return self Self instance
     */
    public function setEmpresaID($empresa_id)
    {
        $this->empresa_id = $empresa_id;
        return $this;
    }

    /**
     * Informa quem realiza o suporte do sistema, deve ser um cliente do tipo
     * empresa que possua um acionista como representante
     * @return int parceiro of Empresa
     */
    public function getParceiroID()
    {
        return $this->parceiro_id;
    }

    /**
     * Set ParceiroID value to new on param
     * @param int $parceiro_id Set parceiro for Empresa
     * @return self Self instance
     */
    public function setParceiroID($parceiro_id)
    {
        $this->parceiro_id = $parceiro_id;
        return $this;
    }

    /**
     * Opções gerais do sistema como opções de impressão e comportamento
     * @return string opções do sistema of Empresa
     */
    public function getOpcoes()
    {
        return $this->opcoes;
    }

    /**
     * Set Opcoes value to new on param
     * @param string $opcoes Set opções do sistema for Empresa
     * @return self Self instance
     */
    public function setOpcoes($opcoes)
    {
        $this->opcoes = $opcoes;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $empresa = parent::toArray($recursive);
        $empresa['id'] = $this->getID();
        $empresa['paisid'] = $this->getPaisID();
        $empresa['empresaid'] = $this->getEmpresaID();
        $empresa['parceiroid'] = $this->getParceiroID();
        $empresa['opcoes'] = $this->getOpcoes();
        return $empresa;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param mixed $empresa Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($empresa = [])
    {
        if ($empresa instanceof self) {
            $empresa = $empresa->toArray();
        } elseif (!is_array($empresa)) {
            $empresa = [];
        }
        parent::fromArray($empresa);
        if (!isset($empresa['id'])) {
            $this->setID(null);
        } else {
            $this->setID($empresa['id']);
        }
        if (!array_key_exists('paisid', $empresa)) {
            $this->setPaisID(null);
        } else {
            $this->setPaisID($empresa['paisid']);
        }
        if (!array_key_exists('empresaid', $empresa)) {
            $this->setEmpresaID(null);
        } else {
            $this->setEmpresaID($empresa['empresaid']);
        }
        if (!array_key_exists('parceiroid', $empresa)) {
            $this->setParceiroID(null);
        } else {
            $this->setParceiroID($empresa['parceiroid']);
        }
        if (!array_key_exists('opcoes', $empresa)) {
            $this->setOpcoes(null);
        } else {
            $this->setOpcoes($empresa['opcoes']);
        }
        return $this;
    }

    /**
     * Opções gerais do sistema como opções de impressão e comportamento
     * @return \MZ\Core\Settings Manipulador das opções da empresa
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $empresa = parent::publish();
        unset($empresa['opcoes']);
        return $empresa;
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
        $this->setEmpresaID(Filter::number($this->getEmpresaID()));
        $this->setParceiroID(Filter::number($this->getParceiroID()));

        $opcoes = to_ini($this->getOptions()->getValues());
        $opcoes = base64_encode($opcoes);
        $this->setOpcoes($opcoes);
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
     * @return array All field of Empresa in array format
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function validate()
    {
        $errors = [];
        if ($this->getID() != '1') {
            $errors['id'] = 'O id da empresa não foi informado';
        }
        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
        return $this->toArray();
    }

    /**
     * Insert a new Empresa into the database and fill instance from database
     * @return self Self instance
     */
    public function insert()
    {
        throw new \Exception(
            'Nenhuma informação sobre a empresa, contacte seu fornecedor para resolve o problema',
            500
        );
    }

    /**
     * Update Empresa with instance values into database for ID
     * @param array $only Save these fields only, when empty save all fields except id
     * @return int rows affected
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function update($only = [])
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new ValidationException(
                ['id' => _t('empresa.id_cannot_empty')]
            );
        }
        $values = DB::filterValues($values, $only, false);
        try {
            $affected = DB::update('Empresas')
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
        throw new \Exception(
            'Não é possível excluir as informações da empresa',
            500
        );
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

    public function loadAll()
    {
        $this->setID('1');
        $this->loadByID();
        $values = parse_ini_string(base64_decode($this->getOpcoes()), true, INI_SCANNER_RAW);
        settype($values, 'array');
        $this->getOptions()->addValues($values);
    }

    /**
     * País em que a empresa está situada
     * @return \MZ\Location\Pais The object fetched from database
     */
    public function findPaisID()
    {
        if (is_null($this->getPaisID())) {
            return new \MZ\Location\Pais();
        }
        return \MZ\Location\Pais::findByID($this->getPaisID());
    }

    /**
     * Informa a empresa do cadastro de clientes, a empresa deve ser um cliente
     * do tipo pessoa jurídica
     * @return \MZ\Account\Cliente The object fetched from database
     */
    public function findEmpresaID()
    {
        if (is_null($this->getEmpresaID())) {
            return new \MZ\Account\Cliente();
        }
        return \MZ\Account\Cliente::findByID($this->getEmpresaID());
    }

    /**
     * Informa quem realiza o suporte do sistema, deve ser um cliente do tipo
     * empresa que possua um acionista como representante
     * @return \MZ\Account\Cliente The object fetched from database
     */
    public function findParceiroID()
    {
        if (is_null($this->getParceiroID())) {
            return new \MZ\Account\Cliente();
        }
        return \MZ\Account\Cliente::findByID($this->getParceiroID());
    }

    /**
     * Get allowed keys array
     * @return array allowed keys array
     */
    private static function getAllowedKeys()
    {
        $empresa = new self();
        $allowed = Filter::concatKeys('e.', $empresa->toArray());
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
        $query = DB::from('Empresas e');
        $condition = self::filterCondition($condition);
        $query = DB::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('e.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param array $condition Condition for searching the row
     * @param array $order order rows
     * @return self A filled Empresa or empty instance
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
     * @return self A filled Empresa or empty instance
     * @throws \Exception when register has not found
     */
    public static function findOrFail($condition, $order = [])
    {
        $result = self::find($condition, $order);
        if (!$result->exists()) {
            throw new \Exception(_t('empresa.not_found'), 404);
        }
        return $result;
    }

    /**
     * Find all Empresa
     * @param array  $condition Condition to get all Empresa
     * @param array  $order     Order Empresa
     * @param int    $limit     Limit data into row count
     * @param int    $offset    Start offset to get rows
     * @return self[] List of all rows instanced as Empresa
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
