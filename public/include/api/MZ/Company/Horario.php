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
namespace MZ\Company;

use MZ\Util\Filter;
use MZ\Util\Validator;

/**
 * Informa o horário de funcionamento do estabelecimento
 */
class Horario extends \MZ\Database\Helper
{

    /**
     * Identificador do horário
     */
    private $id;
    /**
     * Início do horário de funcionamento em minutos contando a partir de
     * domingo até sábado
     */
    private $inicio;
    /**
     * Duração em minutos em que o restaurante ficará aberto contando a partir
     * de domingo
     */
    private $fim;
    /**
     * Tempo médio de entrega em minutos dos pedidos para entrega no dia
     * informado
     */
    private $tempo_entrega;

    /**
     * Constructor for a new empty instance of Horario
     * @param array $horario All field and values to fill the instance
     */
    public function __construct($horario = [])
    {
        parent::__construct($horario);
    }

    /**
     * Identificador do horário
     * @return mixed ID of Horario
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param  mixed $id new value for ID
     * @return Horario Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Início do horário de funcionamento em minutos contando a partir de
     * domingo até sábado
     * @return mixed Início of Horario
     */
    public function getInicio()
    {
        return $this->inicio;
    }

    /**
     * Set Inicio value to new on param
     * @param  mixed $inicio new value for Inicio
     * @return Horario Self instance
     */
    public function setInicio($inicio)
    {
        $this->inicio = $inicio;
        return $this;
    }

    /**
     * Duração em minutos em que o restaurante ficará aberto contando a partir
     * de domingo
     * @return mixed Fim of Horario
     */
    public function getFim()
    {
        return $this->fim;
    }

    /**
     * Set Fim value to new on param
     * @param  mixed $fim new value for Fim
     * @return Horario Self instance
     */
    public function setFim($fim)
    {
        $this->fim = $fim;
        return $this;
    }

    /**
     * Tempo médio de entrega em minutos dos pedidos para entrega no dia
     * informado
     * @return mixed Tempo de entrega of Horario
     */
    public function getTempoEntrega()
    {
        return $this->tempo_entrega;
    }

    /**
     * Set TempoEntrega value to new on param
     * @param  mixed $tempo_entrega new value for TempoEntrega
     * @return Horario Self instance
     */
    public function setTempoEntrega($tempo_entrega)
    {
        $this->tempo_entrega = $tempo_entrega;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param  boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $horario = parent::toArray($recursive);
        $horario['id'] = $this->getID();
        $horario['inicio'] = $this->getInicio();
        $horario['fim'] = $this->getFim();
        $horario['tempoentrega'] = $this->getTempoEntrega();
        return $horario;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param  mixed $horario Associated key -> value to assign into this instance
     * @return Horario Self instance
     */
    public function fromArray($horario = [])
    {
        if ($horario instanceof Horario) {
            $horario = $horario->toArray();
        } elseif (!is_array($horario)) {
            $horario = [];
        }
        parent::fromArray($horario);
        if (!isset($horario['id'])) {
            $this->setID(null);
        } else {
            $this->setID($horario['id']);
        }
        if (!isset($horario['inicio'])) {
            $this->setInicio(null);
        } else {
            $this->setInicio($horario['inicio']);
        }
        if (!isset($horario['fim'])) {
            $this->setFim(null);
        } else {
            $this->setFim($horario['fim']);
        }
        if (!array_key_exists('tempoentrega', $horario)) {
            $this->setTempoEntrega(null);
        } else {
            $this->setTempoEntrega($horario['tempoentrega']);
        }
        return $this;
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $horario = parent::publish();
        return $horario;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param Horario $original Original instance without modifications
     */
    public function filter($original)
    {
        $this->setID($original->getID());
        $this->setInicio(Filter::number($this->getInicio()));
        $this->setFim(Filter::number($this->getFim()));
        $this->setTempoEntrega(Filter::number($this->getTempoEntrega()));
    }

    /**
     * Clean instance resources like images and docs
     * @param  Horario $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Horario in array format
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getInicio())) {
            $errors['inicio'] = 'O início não pode ser vazio';
        }
        if (is_null($this->getFim())) {
            $errors['fim'] = 'O fim não pode ser vazio';
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
     * Insert a new Horário into the database and fill instance from database
     * @return Horario Self instance
     */
    public function insert()
    {
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = self::getDB()->insertInto('Horarios')->values($values)->execute();
            $this->loadByID($id);
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Horário with instance values into database for ID
     * @param  array $only Save these fields only, when empty save all fields except id
     * @param  boolean $except When true, saves all fields except $only
     * @return Horario Self instance
     */
    public function update($only = [], $except = false)
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador do horário não foi informado');
        }
        $values = self::filterValues($values, $only, $except);
        try {
            self::getDB()
                ->update('Horarios')
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
            throw new \Exception('O identificador do horário não foi informado');
        }
        $result = self::getDB()
            ->deleteFrom('Horarios')
            ->where('id', $this->getID())
            ->execute();
        return $result;
    }

    /**
     * Load one register for it self with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order associative field name -> [-1, 1]
     * @return Horario Self instance filled or empty
     */
    public function load($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return $this->fromArray($row);
    }

    /**
     * Get allowed keys array
     * @return array allowed keys array
     */
    private static function getAllowedKeys()
    {
        $horario = new Horario();
        $allowed = Filter::concatKeys('h.', $horario->toArray());
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
        return Filter::orderBy($order, $allowed, 'h.');
    }

    /**
     * Filter condition array with allowed fields
     * @param  array $condition condition to filter rows
     * @return array allowed condition
     */
    private static function filterCondition($condition)
    {
        $allowed = self::getAllowedKeys();
        return Filter::keys($condition, $allowed, 'h.');
    }

    /**
     * Fetch data from database with a condition
     * @param  array $condition condition to filter rows
     * @param  array $order order rows
     * @return SelectQuery query object with condition statement
     */
    private static function query($condition = [], $order = [])
    {
        $query = self::getDB()->from('Horarios h');
        $condition = self::filterCondition($condition);
        $query = self::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('h.id ASC');
        return self::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order order rows
     * @return Horario A filled Horário or empty instance
     */
    public static function find($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return new Horario($row);
    }

    /**
     * Find this object on database using, ID
     * @param  int $id id to find Horário
     * @return Horario A filled instance or empty when not found
     */
    public static function findByID($id)
    {
        return self::find([
            'id' => intval($id),
        ]);
    }

    /**
     * Find all Horário
     * @param  array  $condition Condition to get all Horário
     * @param  array  $order     Order Horário
     * @param  int    $limit     Limit data into row count
     * @param  int    $offset    Start offset to get rows
     * @return array             List of all rows instanced as Horario
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
            $result[] = new Horario($row);
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
