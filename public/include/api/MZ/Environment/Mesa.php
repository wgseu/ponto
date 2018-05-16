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
namespace MZ\Environment;

use MZ\Util\Filter;
use MZ\Util\Validator;
use MZ\Sale\Pedido;
use MZ\Sale\Juncao;

/**
 * Mesas para lançamento de pedidos
 */
class Mesa extends \MZ\Database\Helper
{

    /**
     * Número da mesa
     */
    private $id;
    /**
     * Nome da mesa
     */
    private $nome;
    /**
     * Informa se a mesa está disponível para lançamento de pedidos
     */
    private $ativa;

    /**
     * Constructor for a new empty instance of Mesa
     * @param array $mesa All field and values to fill the instance
     */
    public function __construct($mesa = [])
    {
        parent::__construct($mesa);
    }

    /**
     * Número da mesa
     * @return mixed Número of Mesa
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param  mixed $id new value for ID
     * @return Mesa Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Nome da mesa
     * @return mixed Nome of Mesa
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set Nome value to new on param
     * @param  mixed $nome new value for Nome
     * @return Mesa Self instance
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * Informa se a mesa está disponível para lançamento de pedidos
     * @return mixed Ativa of Mesa
     */
    public function getAtiva()
    {
        return $this->ativa;
    }

    /**
     * Informa se a mesa está disponível para lançamento de pedidos
     * @return boolean Check if a of Ativa is selected or checked
     */
    public function isAtiva()
    {
        return $this->ativa == 'Y';
    }

    /**
     * Set Ativa value to new on param
     * @param  mixed $ativa new value for Ativa
     * @return Mesa Self instance
     */
    public function setAtiva($ativa)
    {
        $this->ativa = $ativa;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param  boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $mesa = parent::toArray($recursive);
        $mesa['id'] = $this->getID();
        $mesa['nome'] = $this->getNome();
        $mesa['ativa'] = $this->getAtiva();
        return $mesa;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param  mixed $mesa Associated key -> value to assign into this instance
     * @return Mesa Self instance
     */
    public function fromArray($mesa = [])
    {
        if ($mesa instanceof Mesa) {
            $mesa = $mesa->toArray();
        } elseif (!is_array($mesa)) {
            $mesa = [];
        }
        parent::fromArray($mesa);
        if (!isset($mesa['id'])) {
            $this->setID(null);
        } else {
            $this->setID($mesa['id']);
        }
        if (!isset($mesa['nome'])) {
            $this->setNome(null);
        } else {
            $this->setNome($mesa['nome']);
        }
        if (!isset($mesa['ativa'])) {
            $this->setAtiva('N');
        } else {
            $this->setAtiva($mesa['ativa']);
        }
        return $this;
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $mesa = parent::publish();
        return $mesa;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param Mesa $original Original instance without modifications
     */
    public function filter($original)
    {
        $this->setID(Filter::number($this->getID()));
        $this->setNome(Filter::string($this->getNome()));
    }

    /**
     * Clean instance resources like images and docs
     * @param  Mesa $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Mesa in array format
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getNome())) {
            $errors['nome'] = 'O nome não pode ser vazio';
        }
        if (!Validator::checkBoolean($this->getAtiva())) {
            $errors['ativa'] = 'A disponibilidade não foi informada';
        }
        $old_mesa = self::findByID($this->getID());
        if ($old_mesa->exists() && $old_mesa->isAtiva() && !$this->isAtiva()) {
            $pedido = Pedido::findByMesaID($old_mesa->getID());
            if ($pedido->exists()) {
                $errors['ativa'] = 'A mesa não pode ser desativada porque possui um pedido em aberto';
            }
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
        if (stripos($e->getMessage(), 'PRIMARY') !== false) {
            return new \MZ\Exception\ValidationException([
                'id' => sprintf(
                    'O número "%s" já está cadastrado',
                    $this->getID()
                ),
            ]);
        }
        if (contains(['Nome', 'UNIQUE'], $e->getMessage())) {
            return new \MZ\Exception\ValidationException([
                'nome' => sprintf(
                    'O nome "%s" já está cadastrado',
                    $this->getNome()
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Insert a new Mesa into the database and fill instance from database
     * @return Mesa Self instance
     */
    public function insert()
    {
        $values = $this->validate();
        try {
            $id = self::getDB()->insertInto('Mesas')->values($values)->execute();
            $this->loadByID($id);
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Mesa with instance values into database for Número
     * @return Mesa Self instance
     */
    public function update($only = [], $except = false)
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador da mesa não foi informado');
        }
        $values = self::filterValues($values, $only, $except);
        try {
            self::getDB()
                ->update('Mesas')
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
     * Delete this instance from database using Número
     * @return integer Number of rows deleted (Max 1)
     */
    public function delete()
    {
        if (!$this->exists()) {
            throw new \Exception('O identificador da mesa não foi informado');
        }
        $result = self::getDB()
            ->deleteFrom('Mesas')
            ->where('id', $this->getID())
            ->execute();
        return $result;
    }

    /**
     * Load one register for it self with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order associative field name -> [-1, 1]
     * @return Mesa Self instance filled or empty
     */
    public function load($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return $this->fromArray($row);
    }

    /**
     * Load into this object from database using, Nome
     * @param  string $nome nome to find Mesa
     * @return Mesa Self filled instance or empty when not found
     */
    public function loadByNome($nome)
    {
        return $this->load([
            'nome' => strval($nome),
        ]);
    }

    /**
     * Load next available id from database into this object id field
     * @return Mesa Self id filled instance with next id
     */
    public function loadNextID()
    {
        $query = self::query()
            ->select(null)
            ->select('MAX(id) as id');
        return $this->setID($query->fetchColumn() + 1);
    }

    /**
     * Get allowed keys array
     * @return array allowed keys array
     */
    private static function getAllowedKeys()
    {
        $mesa = new Mesa();
        $allowed = Filter::concatKeys('m.', $mesa->toArray());
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
        return Filter::orderBy($order, $allowed, 'm.');
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
            if (Validator::checkDigits($search)) {
                $condition['id'] = Filter::number($search);
            } else {
                $field = 'm.nome LIKE ?';
                $condition[$field] = '%'.$search.'%';
                $allowed[$field] = true;
            }
        }
        return Filter::keys($condition, $allowed, 'm.');
    }

    /**
     * Fetch data from database with a condition
     * @param  array $condition condition to filter rows
     * @param  array $order order rows
     * @return SelectQuery query object with condition statement
     */
    private static function query($condition = [], $order = [])
    {
        $order = Filter::order($order);
        $query = self::getDB()->from('Mesas m');
        if (isset($condition['pedidos'])) {
            $query = $query->select('p.estado')
                ->select('e.mesaid as juntaid')
                ->select('s.nome as juntanome')
                ->leftJoin(
                    'Pedidos p ON p.mesaid = m.id AND p.tipo = ? AND p.cancelado = ? AND p.estado <> ?',
                    Pedido::TIPO_MESA,
                    'N',
                    Pedido::ESTADO_FINALIZADO
                )
                ->leftJoin('Juncoes j ON j.mesaid = m.id AND j.estado = ?', Juncao::ESTADO_ASSOCIADO)
                ->leftJoin('Pedidos e ON e.id = j.pedidoid')
                ->leftJoin('Mesas s ON s.id = e.mesaid')
                ->groupBy('m.id');
            if (isset($order['funcionario'])) {
                $funcionario_id = intval($order['funcionario']);
                $query = $query->orderBy('IF(p.funcionarioid = ?, 1, 0) DESC', $funcionario_id);
            }
        }
        $condition = self::filterCondition($condition);
        $query = self::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('m.id ASC');
        return self::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order order rows
     * @return Mesa A filled Mesa or empty instance
     */
    public static function find($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return new Mesa($row);
    }

    /**
     * Find this object on database using, ID
     * @param  int $id número to find Mesa
     * @return Mesa A filled instance or empty when not found
     */
    public static function findByID($id)
    {
        return self::find([
            'id' => intval($id),
        ]);
    }

    /**
     * Find this object on database using, Nome
     * @param  string $nome nome to find Mesa
     * @return Mesa A filled instance or empty when not found
     */
    public static function findByNome($nome)
    {
        return self::find([
            'nome' => strval($nome),
        ]);
    }

    /**
     * Find all Mesa
     * @param  array  $condition Condition to get all Mesa
     * @param  array  $order     Order Mesa
     * @param  int    $limit     Limit data into row count
     * @param  int    $offset    Start offset to get rows
     * @return array             List of all rows instanced as Mesa
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
            $result[] = new Mesa($row);
        }
        return $result;
    }

    /**
     * Find all Mesa
     * @param  array  $condition Condition to get all Mesa
     * @param  array  $order     Order Mesa
     * @param  int    $limit     Limit data into row count
     * @param  int    $offset    Start offset to get rows
     * @return array  List of all rows
     */
    public static function rawFindAll($condition = [], $order = [], $limit = null, $offset = null)
    {
        $query = self::query($condition, $order);
        if (!is_null($limit)) {
            $query = $query->limit($limit);
        }
        if (!is_null($offset)) {
            $query = $query->offset($offset);
        }
        return $query->fetchAll();
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
