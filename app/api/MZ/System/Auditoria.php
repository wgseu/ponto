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
 * Registra todas as atividades importantes do sistema
 */
class Auditoria extends SyncModel
{

    /**
     * Tipo de atividade exercida
     */
    const TIPO_FINANCEIRO = 'Financeiro';
    const TIPO_ADMINISTRATIVO = 'Administrativo';
    const TIPO_OPERACIONAL = 'Operacional';

    /**
     * Prioridade de acesso do recurso
     */
    const PRIORIDADE_BAIXA = 'Baixa';
    const PRIORIDADE_MEDIA = 'Media';
    const PRIORIDADE_ALTA = 'Alta';

    /**
     * Identificador da auditoria
     */
    private $id;
    /**
     * Informa a permissão concedida ou utilizada que permitiu a realização da
     * operação
     */
    private $permissao_id;
    /**
     * Prestador que exerceu a atividade
     */
    private $prestador_id;
    /**
     * Prestador que autorizou o acesso ao recurso descrito
     */
    private $autorizador_id;
    /**
     * Tipo de atividade exercida
     */
    private $tipo;
    /**
     * Prioridade de acesso do recurso
     */
    private $prioridade;
    /**
     * Descrição da atividade exercida
     */
    private $descricao;
    /**
     * Código de autorização necessário para permitir realizar a função
     * descrita
     */
    private $autorizacao;
    /**
     * Data e hora do ocorrido
     */
    private $data_hora;

    /**
     * Constructor for a new empty instance of Auditoria
     * @param array $auditoria All field and values to fill the instance
     */
    public function __construct($auditoria = [])
    {
        parent::__construct($auditoria);
    }

    /**
     * Identificador da auditoria
     * @return int id of Auditoria
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param int $id Set id for Auditoria
     * @return self Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Informa a permissão concedida ou utilizada que permitiu a realização da
     * operação
     * @return int permissão of Auditoria
     */
    public function getPermissaoID()
    {
        return $this->permissao_id;
    }

    /**
     * Set PermissaoID value to new on param
     * @param int $permissao_id Set permissão for Auditoria
     * @return self Self instance
     */
    public function setPermissaoID($permissao_id)
    {
        $this->permissao_id = $permissao_id;
        return $this;
    }

    /**
     * Prestador que exerceu a atividade
     * @return int prestador of Auditoria
     */
    public function getPrestadorID()
    {
        return $this->prestador_id;
    }

    /**
     * Set PrestadorID value to new on param
     * @param int $prestador_id Set prestador for Auditoria
     * @return self Self instance
     */
    public function setPrestadorID($prestador_id)
    {
        $this->prestador_id = $prestador_id;
        return $this;
    }

    /**
     * Prestador que autorizou o acesso ao recurso descrito
     * @return int autorizador of Auditoria
     */
    public function getAutorizadorID()
    {
        return $this->autorizador_id;
    }

    /**
     * Set AutorizadorID value to new on param
     * @param int $autorizador_id Set autorizador for Auditoria
     * @return self Self instance
     */
    public function setAutorizadorID($autorizador_id)
    {
        $this->autorizador_id = $autorizador_id;
        return $this;
    }

    /**
     * Tipo de atividade exercida
     * @return string tipo of Auditoria
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set Tipo value to new on param
     * @param string $tipo Set tipo for Auditoria
     * @return self Self instance
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
        return $this;
    }

    /**
     * Prioridade de acesso do recurso
     * @return string prioridade of Auditoria
     */
    public function getPrioridade()
    {
        return $this->prioridade;
    }

    /**
     * Set Prioridade value to new on param
     * @param string $prioridade Set prioridade for Auditoria
     * @return self Self instance
     */
    public function setPrioridade($prioridade)
    {
        $this->prioridade = $prioridade;
        return $this;
    }

    /**
     * Descrição da atividade exercida
     * @return string descrição of Auditoria
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Set Descricao value to new on param
     * @param string $descricao Set descrição for Auditoria
     * @return self Self instance
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * Código de autorização necessário para permitir realizar a função
     * descrita
     * @return string autorização of Auditoria
     */
    public function getAutorizacao()
    {
        return $this->autorizacao;
    }

    /**
     * Set Autorizacao value to new on param
     * @param string $autorizacao Set autorização for Auditoria
     * @return self Self instance
     */
    public function setAutorizacao($autorizacao)
    {
        $this->autorizacao = $autorizacao;
        return $this;
    }

    /**
     * Data e hora do ocorrido
     * @return string data e hora of Auditoria
     */
    public function getDataHora()
    {
        return $this->data_hora;
    }

    /**
     * Set DataHora value to new on param
     * @param string $data_hora Set data e hora for Auditoria
     * @return self Self instance
     */
    public function setDataHora($data_hora)
    {
        $this->data_hora = $data_hora;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $auditoria = parent::toArray($recursive);
        $auditoria['id'] = $this->getID();
        $auditoria['permissaoid'] = $this->getPermissaoID();
        $auditoria['prestadorid'] = $this->getPrestadorID();
        $auditoria['autorizadorid'] = $this->getAutorizadorID();
        $auditoria['tipo'] = $this->getTipo();
        $auditoria['prioridade'] = $this->getPrioridade();
        $auditoria['descricao'] = $this->getDescricao();
        $auditoria['autorizacao'] = $this->getAutorizacao();
        $auditoria['datahora'] = $this->getDataHora();
        return $auditoria;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param mixed $auditoria Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($auditoria = [])
    {
        if ($auditoria instanceof self) {
            $auditoria = $auditoria->toArray();
        } elseif (!is_array($auditoria)) {
            $auditoria = [];
        }
        parent::fromArray($auditoria);
        if (!isset($auditoria['id'])) {
            $this->setID(null);
        } else {
            $this->setID($auditoria['id']);
        }
        if (!array_key_exists('permissaoid', $auditoria)) {
            $this->setPermissaoID(null);
        } else {
            $this->setPermissaoID($auditoria['permissaoid']);
        }
        if (!isset($auditoria['prestadorid'])) {
            $this->setPrestadorID(null);
        } else {
            $this->setPrestadorID($auditoria['prestadorid']);
        }
        if (!isset($auditoria['autorizadorid'])) {
            $this->setAutorizadorID(null);
        } else {
            $this->setAutorizadorID($auditoria['autorizadorid']);
        }
        if (!isset($auditoria['tipo'])) {
            $this->setTipo(null);
        } else {
            $this->setTipo($auditoria['tipo']);
        }
        if (!isset($auditoria['prioridade'])) {
            $this->setPrioridade(null);
        } else {
            $this->setPrioridade($auditoria['prioridade']);
        }
        if (!isset($auditoria['descricao'])) {
            $this->setDescricao(null);
        } else {
            $this->setDescricao($auditoria['descricao']);
        }
        if (!array_key_exists('autorizacao', $auditoria)) {
            $this->setAutorizacao(null);
        } else {
            $this->setAutorizacao($auditoria['autorizacao']);
        }
        if (!isset($auditoria['datahora'])) {
            $this->setDataHora(DB::now());
        } else {
            $this->setDataHora($auditoria['datahora']);
        }
        return $this;
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $auditoria = parent::publish();
        return $auditoria;
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
        $this->setPermissaoID(Filter::number($this->getPermissaoID()));
        $this->setPrestadorID(Filter::number($this->getPrestadorID()));
        $this->setAutorizadorID(Filter::number($this->getAutorizadorID()));
        $this->setDescricao(Filter::string($this->getDescricao()));
        $this->setAutorizacao(Filter::text($this->getAutorizacao()));
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
     * @return array All field of Auditoria in array format
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getPrestadorID())) {
            $errors['prestadorid'] = _t('auditoria.prestador_id_cannot_empty');
        }
        if (is_null($this->getAutorizadorID())) {
            $errors['autorizadorid'] = _t('auditoria.autorizador_id_cannot_empty');
        }
        if (!Validator::checkInSet($this->getTipo(), self::getTipoOptions())) {
            $errors['tipo'] = _t('auditoria.tipo_invalid');
        }
        if (!Validator::checkInSet($this->getPrioridade(), self::getPrioridadeOptions())) {
            $errors['prioridade'] = _t('auditoria.prioridade_invalid');
        }
        if (is_null($this->getDescricao())) {
            $errors['descricao'] = _t('auditoria.descricao_cannot_empty');
        }
        $this->setDataHora(DB::now());
        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
        return $this->toArray();
    }

    /**
     * Insert a new Auditoria into the database and fill instance from database
     * @return self Self instance
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function insert()
    {
        $this->setID(null);
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = DB::insertInto('Auditoria')->values($values)->execute();
            $this->setID($id);
            $this->loadByID();
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Auditoria with instance values into database for ID
     * @param array $only Save these fields only, when empty save all fields except id
     * @return int rows affected
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function update($only = [])
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new ValidationException(
                ['id' => _t('auditoria.id_cannot_empty')]
            );
        }
        $values = DB::filterValues($values, $only, false);
        unset($values['datahora']);
        try {
            $affected = DB::update('Auditoria')
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
                ['id' => _t('auditoria.id_cannot_empty')]
            );
        }
        $result = DB::deleteFrom('Auditoria')
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
     * Informa a permissão concedida ou utilizada que permitiu a realização da
     * operação
     * @return \MZ\System\Permissao The object fetched from database
     */
    public function findPermissaoID()
    {
        if (is_null($this->getPermissaoID())) {
            return new \MZ\System\Permissao();
        }
        return \MZ\System\Permissao::findByID($this->getPermissaoID());
    }

    /**
     * Prestador que exerceu a atividade
     * @return \MZ\Provider\Prestador The object fetched from database
     */
    public function findPrestadorID()
    {
        return \MZ\Provider\Prestador::findByID($this->getPrestadorID());
    }

    /**
     * Prestador que autorizou o acesso ao recurso descrito
     * @return \MZ\Provider\Prestador The object fetched from database
     */
    public function findAutorizadorID()
    {
        return \MZ\Provider\Prestador::findByID($this->getAutorizadorID());
    }

    /**
     * Gets textual and translated Tipo for Auditoria
     * @param int $index choose option from index
     * @return string[] A associative key -> translated representative text or text for index
     */
    public static function getTipoOptions($index = null)
    {
        $options = [
            self::TIPO_FINANCEIRO => _t('auditoria.tipo_financeiro'),
            self::TIPO_ADMINISTRATIVO => _t('auditoria.tipo_administrativo'),
            self::TIPO_OPERACIONAL => _t('auditoria.tipo_operacional'),
        ];
        if (!is_null($index)) {
            return $options[$index];
        }
        return $options;
    }

    /**
     * Gets textual and translated Prioridade for Auditoria
     * @param int $index choose option from index
     * @return string[] A associative key -> translated representative text or text for index
     */
    public static function getPrioridadeOptions($index = null)
    {
        $options = [
            self::PRIORIDADE_BAIXA => _t('auditoria.prioridade_baixa'),
            self::PRIORIDADE_MEDIA => _t('auditoria.prioridade_media'),
            self::PRIORIDADE_ALTA => _t('auditoria.prioridade_alta'),
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
        $auditoria = new self();
        $allowed = Filter::concatKeys('a.', $auditoria->toArray());
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
        return Filter::orderBy($order, $allowed, 'a.');
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
            $field = 'a.descricao LIKE ?';
            $condition[$field] = '%'.$search.'%';
            $allowed[$field] = true;
            unset($condition['search']);
        }
        return Filter::keys($condition, $allowed, 'a.');
    }

    /**
     * Fetch data from database with a condition
     * @param array $condition condition to filter rows
     * @param array $order order rows
     * @return SelectQuery query object with condition statement
     */
    private static function query($condition = [], $order = [])
    {
        $query = DB::from('Auditoria a');
        $condition = self::filterCondition($condition);
        $query = DB::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('a.descricao ASC');
        $query = $query->orderBy('a.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param array $condition Condition for searching the row
     * @param array $order order rows
     * @return self A filled Auditoria or empty instance
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
     * @return self A filled Auditoria or empty instance
     * @throws \Exception when register has not found
     */
    public static function findOrFail($condition, $order = [])
    {
        $result = self::find($condition, $order);
        if (!$result->exists()) {
            throw new \Exception(_t('auditoria.not_found'), 404);
        }
        return $result;
    }

    /**
     * Find all Auditoria
     * @param array  $condition Condition to get all Auditoria
     * @param array  $order     Order Auditoria
     * @param int    $limit     Limit data into row count
     * @param int    $offset    Start offset to get rows
     * @return self[] List of all rows instanced as Auditoria
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
