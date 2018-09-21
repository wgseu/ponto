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
namespace MZ\Sale;

use MZ\Database\SyncModel;
use MZ\Database\DB;
use MZ\Util\Filter;
use MZ\Util\Validator;

/**
 * Junções de mesas, informa quais mesas estão juntas ao pedido
 */
class Juncao extends SyncModel
{

    /**
     * Estado a junção da mesa. Associado: a mesa está junta ao pedido,
     * Liberado: A mesa está livre, Cancelado: A mesa está liberada
     */
    const ESTADO_ASSOCIADO = 'Associado';
    const ESTADO_LIBERADO = 'Liberado';
    const ESTADO_CANCELADO = 'Cancelado';

    /**
     * Identificador da junção
     */
    private $id;
    /**
     * Mesa que está junta ao pedido
     */
    private $mesa_id;
    /**
     * Pedido a qual a mesa está junta, o pedido deve ser de uma mesa
     */
    private $pedido_id;
    /**
     * Estado a junção da mesa. Associado: a mesa está junta ao pedido,
     * Liberado: A mesa está livre, Cancelado: A mesa está liberada
     */
    private $estado;
    /**
     * Data e hora da junção das mesas
     */
    private $data_movimento;

    /**
     * Constructor for a new empty instance of Juncao
     * @param array $juncao All field and values to fill the instance
     */
    public function __construct($juncao = [])
    {
        parent::__construct($juncao);
    }

    /**
     * Identificador da junção
     * @return mixed ID of Juncao
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param  mixed $id new value for ID
     * @return Juncao Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Mesa que está junta ao pedido
     * @return mixed Mesa of Juncao
     */
    public function getMesaID()
    {
        return $this->mesa_id;
    }

    /**
     * Set MesaID value to new on param
     * @param  mixed $mesa_id new value for MesaID
     * @return Juncao Self instance
     */
    public function setMesaID($mesa_id)
    {
        $this->mesa_id = $mesa_id;
        return $this;
    }

    /**
     * Pedido a qual a mesa está junta, o pedido deve ser de uma mesa
     * @return mixed Pedido of Juncao
     */
    public function getPedidoID()
    {
        return $this->pedido_id;
    }

    /**
     * Set PedidoID value to new on param
     * @param  mixed $pedido_id new value for PedidoID
     * @return Juncao Self instance
     */
    public function setPedidoID($pedido_id)
    {
        $this->pedido_id = $pedido_id;
        return $this;
    }

    /**
     * Estado a junção da mesa. Associado: a mesa está junta ao pedido,
     * Liberado: A mesa está livre, Cancelado: A mesa está liberada
     * @return mixed Estado of Juncao
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set Estado value to new on param
     * @param  mixed $estado new value for Estado
     * @return Juncao Self instance
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
        return $this;
    }

    /**
     * Data e hora da junção das mesas
     * @return mixed Data do movimento of Juncao
     */
    public function getDataMovimento()
    {
        return $this->data_movimento;
    }

    /**
     * Set DataMovimento value to new on param
     * @param  mixed $data_movimento new value for DataMovimento
     * @return Juncao Self instance
     */
    public function setDataMovimento($data_movimento)
    {
        $this->data_movimento = $data_movimento;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param  boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $juncao = parent::toArray($recursive);
        $juncao['id'] = $this->getID();
        $juncao['mesaid'] = $this->getMesaID();
        $juncao['pedidoid'] = $this->getPedidoID();
        $juncao['estado'] = $this->getEstado();
        $juncao['datamovimento'] = $this->getDataMovimento();
        return $juncao;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param  mixed $juncao Associated key -> value to assign into this instance
     * @return Juncao Self instance
     */
    public function fromArray($juncao = [])
    {
        if ($juncao instanceof Juncao) {
            $juncao = $juncao->toArray();
        } elseif (!is_array($juncao)) {
            $juncao = [];
        }
        parent::fromArray($juncao);
        if (!isset($juncao['id'])) {
            $this->setID(null);
        } else {
            $this->setID($juncao['id']);
        }
        if (!isset($juncao['mesaid'])) {
            $this->setMesaID(null);
        } else {
            $this->setMesaID($juncao['mesaid']);
        }
        if (!isset($juncao['pedidoid'])) {
            $this->setPedidoID(null);
        } else {
            $this->setPedidoID($juncao['pedidoid']);
        }
        if (!isset($juncao['estado'])) {
            $this->setEstado(null);
        } else {
            $this->setEstado($juncao['estado']);
        }
        if (!isset($juncao['datamovimento'])) {
            $this->setDataMovimento(null);
        } else {
            $this->setDataMovimento($juncao['datamovimento']);
        }
        return $this;
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $juncao = parent::publish();
        return $juncao;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param Juncao $original Original instance without modifications
     */
    public function filter($original, $localized = false)
    {
        $this->setID($original->getID());
        $this->setMesaID(Filter::number($this->getMesaID()));
        $this->setPedidoID(Filter::number($this->getPedidoID()));
        $this->setDataMovimento(Filter::datetime($this->getDataMovimento()));
    }

    /**
     * Clean instance resources like images and docs
     * @param  Juncao $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Juncao in array format
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getMesaID())) {
            $errors['mesaid'] = 'A mesa não pode ser vazia';
        }
        if (is_null($this->getPedidoID())) {
            $errors['pedidoid'] = 'O pedido não pode ser vazio';
        }
        if (is_null($this->getEstado())) {
            $errors['estado'] = 'O estado não pode ser vazio';
        }
        if (!Validator::checkInSet($this->getEstado(), self::getEstadoOptions(), true)) {
            $errors['estado'] = 'O estado é inválido';
        }
        if (is_null($this->getDataMovimento())) {
            $errors['datamovimento'] = 'A data do movimento não pode ser vazia';
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
     * Insert a new Junção into the database and fill instance from database
     * @return Juncao Self instance
     */
    public function insert()
    {
        $this->setID(null);
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = DB::insertInto('Juncoes')->values($values)->execute();
            $this->setID($id);
            $this->loadByID();
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Junção with instance values into database for ID
     * @param  array $only Save these fields only, when empty save all fields except id
     * @return Juncao Self instance
     */
    public function update($only = [])
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador da junção não foi informado');
        }
        $values = DB::filterValues($values, $only, false);
        try {
            DB::update('Juncoes')
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
            throw new \Exception('O identificador da junção não foi informado');
        }
        $result = DB::deleteFrom('Juncoes')
            ->where('id', $this->getID())
            ->execute();
        return $result;
    }

    /**
     * Load one register for it self with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order associative field name -> [-1, 1]
     * @return Juncao Self instance filled or empty
     */
    public function load($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return $this->fromArray($row);
    }

    /**
     * Mesa que está junta ao pedido
     * @return \MZ\Environment\Mesa The object fetched from database
     */
    public function findMesaID()
    {
        return \MZ\Environment\Mesa::findByID($this->getMesaID());
    }

    /**
     * Pedido a qual a mesa está junta, o pedido deve ser de uma mesa
     * @return \MZ\Sale\Pedido The object fetched from database
     */
    public function findPedidoID()
    {
        return \MZ\Sale\Pedido::findByID($this->getPedidoID());
    }

    /**
     * Gets textual and translated Estado for Juncao
     * @param  int $index choose option from index
     * @return mixed A associative key -> translated representative text or text for index
     */
    public static function getEstadoOptions($index = null)
    {
        $options = [
            self::ESTADO_ASSOCIADO => 'Associado',
            self::ESTADO_LIBERADO => 'Liberado',
            self::ESTADO_CANCELADO => 'Cancelado',
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
        $juncao = new Juncao();
        $allowed = Filter::concatKeys('j.', $juncao->toArray());
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
        return Filter::orderBy($order, $allowed, 'j.');
    }

    /**
     * Filter condition array with allowed fields
     * @param  array $condition condition to filter rows
     * @return array allowed condition
     */
    private static function filterCondition($condition)
    {
        $allowed = self::getAllowedKeys();
        return Filter::keys($condition, $allowed, 'j.');
    }

    /**
     * Fetch data from database with a condition
     * @param  array $condition condition to filter rows
     * @param  array $order order rows
     * @return SelectQuery query object with condition statement
     */
    private static function query($condition = [], $order = [])
    {
        $query = DB::from('Juncoes j');
        $condition = self::filterCondition($condition);
        $query = DB::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('j.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order order rows
     * @return Juncao A filled Junção or empty instance
     */
    public static function find($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return new Juncao($row);
    }

    /**
     * Find all Junção
     * @param  array  $condition Condition to get all Junção
     * @param  array  $order     Order Junção
     * @param  int    $limit     Limit data into row count
     * @param  int    $offset    Start offset to get rows
     * @return array             List of all rows instanced as Juncao
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
            $result[] = new Juncao($row);
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
