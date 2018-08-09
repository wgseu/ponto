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
namespace MZ\Stock;

use MZ\Database\Model;
use MZ\Database\DB;
use MZ\Util\Filter;
use MZ\Util\Validator;

/**
 * Lista de compras de produtos
 */
class Lista extends Model
{

    /**
     * Estado da lista de compra. Análise: Ainda estão sendo adicionado
     * produtos na lista, Fechada: Está pronto para compra, Comprada: Todos os
     * itens foram comprados
     */
    const ESTADO_ANALISE = 'Analise';
    const ESTADO_FECHADA = 'Fechada';
    const ESTADO_COMPRADA = 'Comprada';

    /**
     * Identificador da lista de compras
     */
    private $id;
    /**
     * Nome da lista, pode ser uma data
     */
    private $descricao;
    /**
     * Estado da lista de compra. Análise: Ainda estão sendo adicionado
     * produtos na lista, Fechada: Está pronto para compra, Comprada: Todos os
     * itens foram comprados
     */
    private $estado;
    /**
     * Informa o funcionário encarregado de fazer as compras
     */
    private $encarregado_id;
    /**
     * Data e hora para o encarregado ir fazer as compras
     */
    private $data_viagem;
    /**
     * Data de cadastro da lista
     */
    private $data_cadastro;

    /**
     * Constructor for a new empty instance of Lista
     * @param array $lista All field and values to fill the instance
     */
    public function __construct($lista = [])
    {
        parent::__construct($lista);
    }

    /**
     * Identificador da lista de compras
     * @return mixed ID of Lista
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param  mixed $id new value for ID
     * @return Lista Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Nome da lista, pode ser uma data
     * @return mixed Descrição of Lista
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Set Descricao value to new on param
     * @param  mixed $descricao new value for Descricao
     * @return Lista Self instance
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * Estado da lista de compra. Análise: Ainda estão sendo adicionado
     * produtos na lista, Fechada: Está pronto para compra, Comprada: Todos os
     * itens foram comprados
     * @return mixed Estado of Lista
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set Estado value to new on param
     * @param  mixed $estado new value for Estado
     * @return Lista Self instance
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
        return $this;
    }

    /**
     * Informa o funcionário encarregado de fazer as compras
     * @return mixed Encarregado of Lista
     */
    public function getEncarregadoID()
    {
        return $this->encarregado_id;
    }

    /**
     * Set EncarregadoID value to new on param
     * @param  mixed $encarregado_id new value for EncarregadoID
     * @return Lista Self instance
     */
    public function setEncarregadoID($encarregado_id)
    {
        $this->encarregado_id = $encarregado_id;
        return $this;
    }

    /**
     * Data e hora para o encarregado ir fazer as compras
     * @return mixed Data de viagem of Lista
     */
    public function getDataViagem()
    {
        return $this->data_viagem;
    }

    /**
     * Set DataViagem value to new on param
     * @param  mixed $data_viagem new value for DataViagem
     * @return Lista Self instance
     */
    public function setDataViagem($data_viagem)
    {
        $this->data_viagem = $data_viagem;
        return $this;
    }

    /**
     * Data de cadastro da lista
     * @return mixed Data de cadastro of Lista
     */
    public function getDataCadastro()
    {
        return $this->data_cadastro;
    }

    /**
     * Set DataCadastro value to new on param
     * @param  mixed $data_cadastro new value for DataCadastro
     * @return Lista Self instance
     */
    public function setDataCadastro($data_cadastro)
    {
        $this->data_cadastro = $data_cadastro;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param  boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $lista = parent::toArray($recursive);
        $lista['id'] = $this->getID();
        $lista['descricao'] = $this->getDescricao();
        $lista['estado'] = $this->getEstado();
        $lista['encarregadoid'] = $this->getEncarregadoID();
        $lista['dataviagem'] = $this->getDataViagem();
        $lista['datacadastro'] = $this->getDataCadastro();
        return $lista;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param  mixed $lista Associated key -> value to assign into this instance
     * @return Lista Self instance
     */
    public function fromArray($lista = [])
    {
        if ($lista instanceof Lista) {
            $lista = $lista->toArray();
        } elseif (!is_array($lista)) {
            $lista = [];
        }
        parent::fromArray($lista);
        if (!isset($lista['id'])) {
            $this->setID(null);
        } else {
            $this->setID($lista['id']);
        }
        if (!isset($lista['descricao'])) {
            $this->setDescricao(null);
        } else {
            $this->setDescricao($lista['descricao']);
        }
        if (!isset($lista['estado'])) {
            $this->setEstado(null);
        } else {
            $this->setEstado($lista['estado']);
        }
        if (!isset($lista['encarregadoid'])) {
            $this->setEncarregadoID(null);
        } else {
            $this->setEncarregadoID($lista['encarregadoid']);
        }
        if (!isset($lista['dataviagem'])) {
            $this->setDataViagem(null);
        } else {
            $this->setDataViagem($lista['dataviagem']);
        }
        if (!isset($lista['datacadastro'])) {
            $this->setDataCadastro(null);
        } else {
            $this->setDataCadastro($lista['datacadastro']);
        }
        return $this;
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $lista = parent::publish();
        return $lista;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param Lista $original Original instance without modifications
     */
    public function filter($original)
    {
        $this->setID($original->getID());
        $this->setDescricao(Filter::string($this->getDescricao()));
        $this->setEncarregadoID(Filter::number($this->getEncarregadoID()));
        $this->setDataViagem(Filter::datetime($this->getDataViagem()));
        $this->setDataCadastro(Filter::datetime($this->getDataCadastro()));
    }

    /**
     * Clean instance resources like images and docs
     * @param  Lista $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Lista in array format
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getDescricao())) {
            $errors['descricao'] = 'A descrição não pode ser vazia';
        }
        if (is_null($this->getEstado())) {
            $errors['estado'] = 'O estado não pode ser vazio';
        }
        if (!Validator::checkInSet($this->getEstado(), self::getEstadoOptions(), true)) {
            $errors['estado'] = 'O estado é inválido';
        }
        if (is_null($this->getEncarregadoID())) {
            $errors['encarregadoid'] = 'O encarregado não pode ser vazio';
        }
        if (is_null($this->getDataViagem())) {
            $errors['dataviagem'] = 'A data de viagem não pode ser vazia';
        }
        if (is_null($this->getDataCadastro())) {
            $errors['datacadastro'] = 'A data de cadastro não pode ser vazia';
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
     * Insert a new Lista de compra into the database and fill instance from database
     * @return Lista Self instance
     */
    public function insert()
    {
        $this->setID(null);
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = DB::insertInto('Listas')->values($values)->execute();
            $this->loadByID($id);
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Lista de compra with instance values into database for ID
     * @return Lista Self instance
     */
    public function update($only = [], $except = false)
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador da lista de compra não foi informado');
        }
        $values = DB::filterValues($values, $only, $except);
        try {
            DB::update('Listas')
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
            throw new \Exception('O identificador da lista de compra não foi informado');
        }
        $result = DB::deleteFrom('Listas')
            ->where('id', $this->getID())
            ->execute();
        return $result;
    }

    /**
     * Load one register for it self with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order associative field name -> [-1, 1]
     * @return Lista Self instance filled or empty
     */
    public function load($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return $this->fromArray($row);
    }

    /**
     * Informa o funcionário encarregado de fazer as compras
     * @return \MZ\Employee\Funcionario The object fetched from database
     */
    public function findEncarregadoID()
    {
        return \MZ\Employee\Funcionario::findByID($this->getEncarregadoID());
    }

    /**
     * Gets textual and translated Estado for Lista
     * @param  int $index choose option from index
     * @return mixed A associative key -> translated representative text or text for index
     */
    public static function getEstadoOptions($index = null)
    {
        $options = [
            self::ESTADO_ANALISE => 'Análise',
            self::ESTADO_FECHADA => 'Fechada',
            self::ESTADO_COMPRADA => 'Comprada',
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
        $lista = new Lista();
        $allowed = Filter::concatKeys('l.', $lista->toArray());
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
        return Filter::orderBy($order, $allowed, 'l.');
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
            $field = 'l.descricao LIKE ?';
            $condition[$field] = '%'.$search.'%';
            $allowed[$field] = true;
            unset($condition['search']);
        }
        return Filter::keys($condition, $allowed, 'l.');
    }

    /**
     * Fetch data from database with a condition
     * @param  array $condition condition to filter rows
     * @param  array $order order rows
     * @return SelectQuery query object with condition statement
     */
    private static function query($condition = [], $order = [])
    {
        $query = DB::from('Listas l');
        $condition = self::filterCondition($condition);
        $query = DB::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('l.descricao ASC');
        $query = $query->orderBy('l.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order order rows
     * @return Lista A filled Lista de compra or empty instance
     */
    public static function find($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return new Lista($row);
    }

    /**
     * Find all Lista de compra
     * @param  array  $condition Condition to get all Lista de compra
     * @param  array  $order     Order Lista de compra
     * @param  int    $limit     Limit data into row count
     * @param  int    $offset    Start offset to get rows
     * @return array             List of all rows instanced as Lista
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
            $result[] = new Lista($row);
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
