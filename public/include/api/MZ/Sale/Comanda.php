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

use MZ\Util\Filter;
use MZ\Util\Validator;

/**
 * Comanda individual, permite lançar pedidos em cartões de consumo
 */
class Comanda extends \MZ\Database\Helper
{

    /**
     * Número da comanda
     */
    private $id;
    /**
     * Nome da comanda
     */
    private $nome;
    /**
     * Informa se a comanda está diponível para ser usada nas vendas
     */
    private $ativa;

    /**
     * Constructor for a new empty instance of Comanda
     * @param array $comanda All field and values to fill the instance
     */
    public function __construct($comanda = [])
    {
        parent::__construct($comanda);
    }

    /**
     * Número da comanda
     * @return mixed Número of Comanda
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param  mixed $id new value for ID
     * @return Comanda Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Nome da comanda
     * @return mixed Nome of Comanda
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set Nome value to new on param
     * @param  mixed $nome new value for Nome
     * @return Comanda Self instance
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * Informa se a comanda está diponível para ser usada nas vendas
     * @return mixed Ativa of Comanda
     */
    public function getAtiva()
    {
        return $this->ativa;
    }

    /**
     * Informa se a comanda está diponível para ser usada nas vendas
     * @return boolean Check if a of Ativa is selected or checked
     */
    public function isAtiva()
    {
        return $this->ativa == 'Y';
    }

    /**
     * Set Ativa value to new on param
     * @param  mixed $ativa new value for Ativa
     * @return Comanda Self instance
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
        $comanda = parent::toArray($recursive);
        $comanda['id'] = $this->getID();
        $comanda['nome'] = $this->getNome();
        $comanda['ativa'] = $this->getAtiva();
        return $comanda;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param  mixed $comanda Associated key -> value to assign into this instance
     * @return Comanda Self instance
     */
    public function fromArray($comanda = [])
    {
        if ($comanda instanceof Comanda) {
            $comanda = $comanda->toArray();
        } elseif (!is_array($comanda)) {
            $comanda = [];
        }
        parent::fromArray($comanda);
        if (!isset($comanda['id'])) {
            $this->setID(null);
        } else {
            $this->setID($comanda['id']);
        }
        if (!isset($comanda['nome'])) {
            $this->setNome(null);
        } else {
            $this->setNome($comanda['nome']);
        }
        if (!isset($comanda['ativa'])) {
            $this->setAtiva('N');
        } else {
            $this->setAtiva($comanda['ativa']);
        }
        return $this;
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $comanda = parent::publish();
        return $comanda;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param Comanda $original Original instance without modifications
     */
    public function filter($original)
    {
        $this->setID(Filter::number($this->getID()));
        $this->setNome(Filter::string($this->getNome()));
    }

    /**
     * Clean instance resources like images and docs
     * @param  Comanda $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Comanda in array format
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getNome())) {
            $errors['nome'] = 'O nome não pode ser vazio';
        }
        if (is_null($this->getAtiva())) {
            $errors['ativa'] = 'A disponibilidade da comanda não foi informada';
        }
        $old_comanda = self::findByID($this->getID());
        if ($old_comanda->exists() && $old_comanda->isAtiva() && !$this->isAtiva()) {
            $pedido = \Pedido::getPelaComandaID($old_comanda->getID());
            if ($pedido->exists()) {
                $errors['ativa'] = 'A comanda não pode ser desativada porque possui um pedido em aberto';
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
                'id' => vsprintf(
                    'O Número "%s" já está cadastrado',
                    [$this->getID()]
                ),
            ]);
        }
        if (contains(['Nome', 'UNIQUE'], $e->getMessage())) {
            return new \MZ\Exception\ValidationException([
                'nome' => vsprintf(
                    'O Nome "%s" já está cadastrado',
                    [$this->getNome()]
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Insert a new Comanda into the database and fill instance from database
     * @return Comanda Self instance
     */
    public function insert()
    {
        $values = $this->validate();
        try {
            $id = self::getDB()->insertInto('Comandas')->values($values)->execute();
            $this->loadByID($id);
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Comanda with instance values into database for Número
     * @return Comanda Self instance
     */
    public function update($only = [], $except = false)
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador da comanda não foi informado');
        }
        $values = self::filterValues($values, $only, $except);
        try {
            self::getDB()
                ->update('Comandas')
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
            throw new \Exception('O identificador da comanda não foi informado');
        }
        $result = self::getDB()
            ->deleteFrom('Comandas')
            ->where('id', $this->getID())
            ->execute();
        return $result;
    }

    /**
     * Load one register for it self with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order associative field name -> [-1, 1]
     * @return Comanda Self instance filled or empty
     */
    public function load($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return $this->fromArray($row);
    }

    /**
     * Load into this object from database using, Nome
     * @param  string $nome nome to find Comanda
     * @return Comanda Self filled instance or empty when not found
     */
    public function loadByNome($nome)
    {
        return $this->load([
            'nome' => strval($nome),
        ]);
    }

    /**
     * Find this object on database using, ID
     * @param  int $id número to find Comanda
     * @return Comanda A filled instance or empty when not found
     */
    public static function findByID($id)
    {
        return self::find([
            'id' => intval($id),
        ]);
    }

    /**
     * Find this object on database using, Nome
     * @param  string $nome nome to find Comanda
     * @return Comanda A filled instance or empty when not found
     */
    public static function findByNome($nome)
    {
        return self::find([
            'nome' => strval($nome),
        ]);
    }

    /**
     * Get allowed keys array
     * @return array allowed keys array
     */
    private static function getAllowedKeys()
    {
        $comanda = new Comanda();
        $allowed = Filter::concatKeys('c.', $comanda->toArray());
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
        return Filter::orderBy($order, $allowed, 'c.');
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
                $condition['id'] = intval($search);
            } else {
                $field = 'c.nome LIKE ?';
                $condition[$field] = '%'.$search.'%';
                $allowed[$field] = true;
            }
        }
        return Filter::keys($condition, $allowed, 'c.');
    }

    /**
     * Fetch data from database with a condition
     * @param  array $condition condition to filter rows
     * @return SelectQuery query object with condition statement
     */
    private static function query($condition = [], $order = [])
    {
        $order = Filter::order($order);
        $query = self::getDB()->from('Comandas c');
        if (isset($condition['pedidos'])) {
            $query = $query->select('p.estado')
                ->select('l.nome as cliente')
                ->select('p.descricao as observacao')
                ->leftJoin(
                    'Pedidos p ON p.comandaid = c.id AND p.tipo = ? AND p.cancelado = ? AND p.estado <> ?',
                    Pedido::TIPO_COMANDA,
                    'N',
                    Pedido::ESTADO_FINALIZADO
                )
                ->leftJoin('Clientes l ON l.id = p.clienteid');
            if (isset($order['funcionario'])) {
                $funcionario_id = intval($order['funcionario']);
                $query = $query->orderBy('IF(p.funcionarioid = ?, 1, 0) DESC', $funcionario_id);
            }
        }
        $condition = self::filterCondition($condition);
        $order = Filter::order($order);
        $query = self::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('c.id ASC');
        return self::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @return Comanda A filled Comanda or empty instance
     */
    public static function find($condition)
    {
        $query = self::query($condition)->limit(1);
        $row = $query->fetch();
        if ($row === false) {
            $row = [];
        }
        return new Comanda($row);
    }

    /**
     * Get next available Comanda id
     * @return int available Comanda id
     */
    public static function getNextID()
    {
        $query = self::query()
            ->select(null)
            ->select('MAX(id) as id');
        return $query->fetch('id') + 1;
    }

    /**
     * Fetch all rows from database with matched condition critery
     * @param  array $condition condition to filter rows
     * @param  integer $limit number of rows to get, null for all
     * @param  integer $offset start index to get rows, null for begining
     * @return array All rows instanced and filled
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
            $result[] = new Comanda($row);
        }
        return $result;
    }

    /**
     * Fetch all rows from database with matched condition critery
     * @param  array $condition condition to filter rows
     * @param  integer $limit number of rows to get, null for all
     * @param  integer $offset start index to get rows, null for begining
     * @return array All rows
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
