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

use MZ\Util\Filter;
use MZ\Util\Validator;

/**
 * Registra todas as atividades importantes do sistema
 */
class Auditoria extends \MZ\Database\Helper
{

    /**
     * Tipo de atividade exercida
     */
    const TIPO_FINANCEIRO = 'Financeiro';
    const TIPO_ADMINISTRATIVO = 'Administrativo';

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
     * Funcionário que exerceu a atividade
     */
    private $funcionario_id;
    /**
     * Funcionário que autorizou o acesso ao recurso descrito
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
     * @return mixed ID of Auditoria
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param  mixed $id new value for ID
     * @return Auditoria Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Funcionário que exerceu a atividade
     * @return mixed Funcionário of Auditoria
     */
    public function getFuncionarioID()
    {
        return $this->funcionario_id;
    }

    /**
     * Set FuncionarioID value to new on param
     * @param  mixed $funcionario_id new value for FuncionarioID
     * @return Auditoria Self instance
     */
    public function setFuncionarioID($funcionario_id)
    {
        $this->funcionario_id = $funcionario_id;
        return $this;
    }

    /**
     * Funcionário que autorizou o acesso ao recurso descrito
     * @return mixed Autorizador of Auditoria
     */
    public function getAutorizadorID()
    {
        return $this->autorizador_id;
    }

    /**
     * Set AutorizadorID value to new on param
     * @param  mixed $autorizador_id new value for AutorizadorID
     * @return Auditoria Self instance
     */
    public function setAutorizadorID($autorizador_id)
    {
        $this->autorizador_id = $autorizador_id;
        return $this;
    }

    /**
     * Tipo de atividade exercida
     * @return mixed Tipo of Auditoria
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set Tipo value to new on param
     * @param  mixed $tipo new value for Tipo
     * @return Auditoria Self instance
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
        return $this;
    }

    /**
     * Prioridade de acesso do recurso
     * @return mixed Prioridade of Auditoria
     */
    public function getPrioridade()
    {
        return $this->prioridade;
    }

    /**
     * Set Prioridade value to new on param
     * @param  mixed $prioridade new value for Prioridade
     * @return Auditoria Self instance
     */
    public function setPrioridade($prioridade)
    {
        $this->prioridade = $prioridade;
        return $this;
    }

    /**
     * Descrição da atividade exercida
     * @return mixed Descrição of Auditoria
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Set Descricao value to new on param
     * @param  mixed $descricao new value for Descricao
     * @return Auditoria Self instance
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * Data e hora do ocorrido
     * @return mixed Data e hora of Auditoria
     */
    public function getDataHora()
    {
        return $this->data_hora;
    }

    /**
     * Set DataHora value to new on param
     * @param  mixed $data_hora new value for DataHora
     * @return Auditoria Self instance
     */
    public function setDataHora($data_hora)
    {
        $this->data_hora = $data_hora;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param  boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $auditoria = parent::toArray($recursive);
        $auditoria['id'] = $this->getID();
        $auditoria['funcionarioid'] = $this->getFuncionarioID();
        $auditoria['autorizadorid'] = $this->getAutorizadorID();
        $auditoria['tipo'] = $this->getTipo();
        $auditoria['prioridade'] = $this->getPrioridade();
        $auditoria['descricao'] = $this->getDescricao();
        $auditoria['datahora'] = $this->getDataHora();
        return $auditoria;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param  mixed $auditoria Associated key -> value to assign into this instance
     * @return Auditoria Self instance
     */
    public function fromArray($auditoria = [])
    {
        if ($auditoria instanceof Auditoria) {
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
        if (!isset($auditoria['funcionarioid'])) {
            $this->setFuncionarioID(null);
        } else {
            $this->setFuncionarioID($auditoria['funcionarioid']);
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
        if (!isset($auditoria['datahora'])) {
            $this->setDataHora(null);
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
     * @param Auditoria $original Original instance without modifications
     */
    public function filter($original)
    {
        $this->setID($original->getID());
        $this->setFuncionarioID(Filter::number($this->getFuncionarioID()));
        $this->setAutorizadorID(Filter::number($this->getAutorizadorID()));
        $this->setDescricao(Filter::string($this->getDescricao()));
        $this->setDataHora(Filter::datetime($this->getDataHora()));
    }

    /**
     * Clean instance resources like images and docs
     * @param  Auditoria $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Auditoria in array format
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getFuncionarioID())) {
            $errors['funcionarioid'] = 'O funcionário não pode ser vazio';
        }
        if (is_null($this->getAutorizadorID())) {
            $errors['autorizadorid'] = 'O autorizador não pode ser vazio';
        }
        if (is_null($this->getTipo())) {
            $errors['tipo'] = 'O tipo não pode ser vazio';
        }
        if (!Validator::checkInSet($this->getTipo(), self::getTipoOptions(), true)) {
            $errors['tipo'] = 'O tipo é inválido';
        }
        if (is_null($this->getPrioridade())) {
            $errors['prioridade'] = 'A prioridade não pode ser vazia';
        }
        if (!Validator::checkInSet($this->getPrioridade(), self::getPrioridadeOptions(), true)) {
            $errors['prioridade'] = 'A prioridade é inválida';
        }
        if (is_null($this->getDescricao())) {
            $errors['descricao'] = 'A descrição não pode ser vazia';
        }
        if (is_null($this->getDataHora())) {
            $errors['datahora'] = 'A data e hora não pode ser vazia';
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
        return parent::translate($e);
    }

    /**
     * Insert a new Auditoria into the database and fill instance from database
     * @return Auditoria Self instance
     */
    public function insert()
    {
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = self::getDB()->insertInto('Auditoria')->values($values)->execute();
            $this->loadByID($id);
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Auditoria with instance values into database for ID
     * @param  array $only Save these fields only, when empty save all fields except id
     * @param  boolean $except When true, saves all fields except $only
     * @return Auditoria Self instance
     */
    public function update($only = [], $except = false)
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador da auditoria não foi informado');
        }
        $values = self::filterValues($values, $only, $except);
        try {
            self::getDB()
                ->update('Auditoria')
                ->set($values)
                ->where('id', $this->getID())
                ->execute();
            $this->loadByID($this->getID());
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
            throw new \Exception('O identificador da auditoria não foi informado');
        }
        $result = self::getDB()
            ->deleteFrom('Auditoria')
            ->where('id', $this->getID())
            ->execute();
        return $result;
    }

    /**
     * Load one register for it self with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order associative field name -> [-1, 1]
     * @return Auditoria Self instance filled or empty
     */
    public function load($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return $this->fromArray($row);
    }

    /**
     * Funcionário que exerceu a atividade
     * @return \MZ\Employee\Funcionario The object fetched from database
     */
    public function findFuncionarioID()
    {
        return \MZ\Employee\Funcionario::findByID($this->getFuncionarioID());
    }

    /**
     * Funcionário que autorizou o acesso ao recurso descrito
     * @return \MZ\Employee\Funcionario The object fetched from database
     */
    public function findAutorizadorID()
    {
        return \MZ\Employee\Funcionario::findByID($this->getAutorizadorID());
    }

    /**
     * Gets textual and translated Tipo for Auditoria
     * @param  int $index choose option from index
     * @return mixed A associative key -> translated representative text or text for index
     */
    public static function getTipoOptions($index = null)
    {
        $options = [
            self::TIPO_FINANCEIRO => 'Financeiro',
            self::TIPO_ADMINISTRATIVO => 'Administrativo',
        ];
        if (!is_null($index)) {
            return $options[$index];
        }
        return $options;
    }

    /**
     * Gets textual and translated Prioridade for Auditoria
     * @param  int $index choose option from index
     * @return mixed A associative key -> translated representative text or text for index
     */
    public static function getPrioridadeOptions($index = null)
    {
        $options = [
            self::PRIORIDADE_BAIXA => 'Baixa',
            self::PRIORIDADE_MEDIA => 'Média',
            self::PRIORIDADE_ALTA => 'Alta',
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
        $auditoria = new Auditoria();
        $allowed = Filter::concatKeys('a.', $auditoria->toArray());
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
        return Filter::orderBy($order, $allowed, 'a.');
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
            $field = 'a.descricao LIKE ?';
            $condition[$field] = '%'.$search.'%';
            $allowed[$field] = true;
            unset($condition['search']);
        }
        return Filter::keys($condition, $allowed, 'a.');
    }

    /**
     * Fetch data from database with a condition
     * @param  array $condition condition to filter rows
     * @param  array $order order rows
     * @return SelectQuery query object with condition statement
     */
    private static function query($condition = [], $order = [])
    {
        $query = self::getDB()->from('Auditoria a');
        $condition = self::filterCondition($condition);
        $query = self::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('a.descricao ASC');
        $query = $query->orderBy('a.id ASC');
        return self::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order order rows
     * @return Auditoria A filled Auditoria or empty instance
     */
    public static function find($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return new Auditoria($row);
    }

    /**
     * Find this object on database using, ID
     * @param  int $id id to find Auditoria
     * @return Auditoria A filled instance or empty when not found
     */
    public static function findByID($id)
    {
        return self::find([
            'id' => intval($id),
        ]);
    }

    /**
     * Find all Auditoria
     * @param  array  $condition Condition to get all Auditoria
     * @param  array  $order     Order Auditoria
     * @param  int    $limit     Limit data into row count
     * @param  int    $offset    Start offset to get rows
     * @return array             List of all rows instanced as Auditoria
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
            $result[] = new Auditoria($row);
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
