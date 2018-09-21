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

use MZ\Database\SyncModel;
use MZ\Database\DB;
use MZ\Util\Filter;
use MZ\Util\Validator;
use MZ\Exception\ValidationException;
use MZ\Sale\Pedido;
use MZ\Sale\Juncao;

/**
 * Mesas para lançamento de pedidos
 */
class Mesa extends SyncModel
{

    /**
     * Número da mesa
     */
    private $id;
    /**
     * Setor em que a mesa está localizada
     */
    private $setor_id;
    /**
     * Número da mesa
     */
    private $numero;
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
     * @return self Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Setor em que a mesa está localizada
     * @return mixed Setor of Mesa
     */
    public function getSetorID()
    {
        return $this->setor_id;
    }

    /**
     * Set SetorID value to new on param
     * @param  mixed $setor_id new value for SetorID
     * @return self Self instance
     */
    public function setSetorID($setor_id)
    {
        $this->setor_id = $setor_id;
        return $this;
    }

    /**
     * Número da mesa
     * @return mixed Número of Mesa
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set Numero value to new on param
     * @param  mixed $numero new value for Numero
     * @return self Self instance
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;
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
     * @return self Self instance
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
     * @return self Self instance
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
        $mesa['setorid'] = $this->getSetorID();
        $mesa['numero'] = $this->getNumero();
        $mesa['nome'] = $this->getNome();
        $mesa['ativa'] = $this->getAtiva();
        return $mesa;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param  mixed $mesa Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($mesa = [])
    {
        if ($mesa instanceof self) {
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
        if (!array_key_exists('setorid', $mesa)) {
            $this->setSetorID(null);
        } else {
            $this->setSetorID($mesa['setorid']);
        }
        if (!isset($mesa['numero'])) {
            $this->setNumero(null);
        } else {
            $this->setNumero($mesa['numero']);
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
     * @param self $original Original instance without modifications
     */
    public function filter($original)
    {
        $this->setID($original->getID());
        $this->setSetorID(Filter::number($this->getSetorID()));
        $this->setNumero(Filter::number($this->getNumero()));
        $this->setNome(Filter::string($this->getNome()));
    }

    /**
     * Clean instance resources like images and docs
     * @param  self $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return mixed[] All field of Mesa in array format
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getNumero())) {
            $errors['numero'] = 'O número não pode ser vazio';
        }
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
            throw new ValidationException($errors);
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
        if (contains(['Nome', 'UNIQUE'], $e->getMessage())) {
            return new ValidationException([
                'nome' => sprintf(
                    'O nome "%s" já está cadastrado',
                    $this->getNome()
                ),
            ]);
        }
        if (contains(['Numero', 'UNIQUE'], $e->getMessage())) {
            return new ValidationException([
                'numero' => sprintf(
                    'O número "%s" já está cadastrado',
                    $this->getNumero()
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Insert a new Mesa into the database and fill instance from database
     * @return self Self instance
     */
    public function insert()
    {
        $this->setID(null);
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = DB::insertInto('Mesas')->values($values)->execute();
            $this->setID($id);
            $this->loadByID();
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Mesa with instance values into database for Número
     * @param  array $only Save these fields only, when empty save all fields except id
     * @return int rows affected
     */
    public function update($only = [])
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador da mesa não foi informado');
        }
        $values = DB::filterValues($values, $only, false);
        try {
            $affected = DB::update('Mesas')
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
     * Delete this instance from database using Número
     * @return integer Number of rows deleted (Max 1)
     */
    public function delete()
    {
        if (!$this->exists()) {
            throw new \Exception('O identificador da mesa não foi informado');
        }
        $result = DB::deleteFrom('Mesas')
            ->where('id', $this->getID())
            ->execute();
        return $result;
    }

    /**
     * Load one register for it self with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order associative field name -> [-1, 1]
     * @return self Self instance filled or empty
     */
    public function load($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return $this->fromArray($row);
    }

    /**
     * Load into this object from database using, Nome
     * @return self Self filled instance or empty when not found
     */
    public function loadByNome()
    {
        return $this->load([
            'nome' => strval($this->getNome()),
        ]);
    }

    /**
     * Load into this object from database using, Numero
     * @return self Self filled instance or empty when not found
     */
    public function loadByNumero()
    {
        return $this->load([
            'numero' => intval($this->getNumero()),
        ]);
    }

    /**
     * Load next available number from database into this object numero field
     * @return Mesa Self id filled instance with next numero
     */
    public function loadNextNumero()
    {
        $last = self::find([], ['numero' => -1]);
        return $this->setNumero($last->getNumero() + 1);
    }

    /**
     * Setor em que a mesa está localizada
     * @return \MZ\Environment\Setor The object fetched from database
     */
    public function findSetorID()
    {
        if (is_null($this->getSetorID())) {
            return new \MZ\Environment\Setor();
        }
        return \MZ\Environment\Setor::findByID($this->getSetorID());
    }

    /**
     * Get allowed keys array
     * @return array allowed keys array
     */
    private static function getAllowedKeys()
    {
        $mesa = new self();
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
        $query = DB::from('Mesas m');
        if (isset($condition['pedidos'])) {
            $query = $query->select('p.estado')
                ->select('p.id as pedidoid')
                ->select('c.nome as cliente')
                ->select('e.mesaid as juntaid')
                ->select('s.nome as juntanome')
                ->leftJoin(
                    'Pedidos p ON p.mesaid = m.id AND p.tipo = ? AND p.cancelado = ? AND p.estado <> ?',
                    Pedido::TIPO_MESA,
                    'N',
                    Pedido::ESTADO_FINALIZADO
                )
                ->leftJoin('Juncoes j ON j.mesaid = m.id AND j.estado = ?', Juncao::ESTADO_ASSOCIADO)
                ->leftJoin('Clientes c ON c.id = p.clienteid')
                ->leftJoin('Pedidos e ON e.id = j.pedidoid')
                ->leftJoin('Mesas s ON s.id = e.mesaid')
                ->groupBy('m.id');
            if (isset($order['funcionario'])) {
                $funcionario_id = intval($order['funcionario']);
                $query = $query->orderBy('IF(p.prestadorid = ?, 1, 0) DESC', $funcionario_id);
            }
        }
        $condition = self::filterCondition($condition);
        $query = DB::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('m.numero ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order order rows
     * @return self A filled Mesa or empty instance
     */
    public static function find($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return new self($row);
    }

    /**
     * Find this object on database using, Nome
     * @param  string $nome nome to find Mesa
     * @return self A filled instance or empty when not found
     */
    public static function findByNome($nome)
    {
        $result = new self();
        $result->setNome($nome);
        return $result->loadByNome();
    }

    /**
     * Find this object on database using, Numero
     * @param  int $numero número to find Mesa
     * @return self A filled instance or empty when not found
     */
    public static function findByNumero($numero)
    {
        $result = new self();
        $result->setNumero($numero);
        return $result->loadByNumero();
    }

    /**
     * Find all Mesa
     * @param  array  $condition Condition to get all Mesa
     * @param  array  $order     Order Mesa
     * @param  int    $limit     Limit data into row count
     * @param  int    $offset    Start offset to get rows
     * @return self[]             List of all rows instanced as Mesa
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
