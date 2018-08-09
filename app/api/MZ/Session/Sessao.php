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
namespace MZ\Session;

use MZ\Database\Model;
use MZ\Database\DB;
use MZ\Util\Filter;
use MZ\Util\Validator;

/**
 * Sessão de trabalho do dia, permite que vários caixas sejam abertos
 * utilizando uma mesma sessão
 */
class Sessao extends Model
{
    const DESCONTO_ID = 1;
    const ENTREGA_ID = 2;

    /**
     * Código da sessão
     */
    private $id;
    /**
     * Data de início da sessão
     */
    private $data_inicio;
    /**
     * Data de fechamento da sessão
     */
    private $data_termino;
    /**
     * Informa se a sessão está aberta
     */
    private $aberta;

    /**
     * Constructor for a new empty instance of Sessao
     * @param array $sessao All field and values to fill the instance
     */
    public function __construct($sessao = [])
    {
        parent::__construct($sessao);
    }

    /**
     * Código da sessão
     * @return mixed ID of Sessao
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param  mixed $id new value for ID
     * @return Sessao Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Data de início da sessão
     * @return mixed Data de início of Sessao
     */
    public function getDataInicio()
    {
        return $this->data_inicio;
    }

    /**
     * Set DataInicio value to new on param
     * @param  mixed $data_inicio new value for DataInicio
     * @return Sessao Self instance
     */
    public function setDataInicio($data_inicio)
    {
        $this->data_inicio = $data_inicio;
        return $this;
    }

    /**
     * Data de fechamento da sessão
     * @return mixed Data de termíno of Sessao
     */
    public function getDataTermino()
    {
        return $this->data_termino;
    }

    /**
     * Set DataTermino value to new on param
     * @param  mixed $data_termino new value for DataTermino
     * @return Sessao Self instance
     */
    public function setDataTermino($data_termino)
    {
        $this->data_termino = $data_termino;
        return $this;
    }

    /**
     * Informa se a sessão está aberta
     * @return mixed Aberta of Sessao
     */
    public function getAberta()
    {
        return $this->aberta;
    }

    /**
     * Informa se a sessão está aberta
     * @return boolean Check if a of Aberta is selected or checked
     */
    public function isAberta()
    {
        return $this->aberta == 'Y';
    }

    /**
     * Set Aberta value to new on param
     * @param  mixed $aberta new value for Aberta
     * @return Sessao Self instance
     */
    public function setAberta($aberta)
    {
        $this->aberta = $aberta;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param  boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $sessao = parent::toArray($recursive);
        $sessao['id'] = $this->getID();
        $sessao['datainicio'] = $this->getDataInicio();
        $sessao['datatermino'] = $this->getDataTermino();
        $sessao['aberta'] = $this->getAberta();
        return $sessao;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param  mixed $sessao Associated key -> value to assign into this instance
     * @return Sessao Self instance
     */
    public function fromArray($sessao = [])
    {
        if ($sessao instanceof Sessao) {
            $sessao = $sessao->toArray();
        } elseif (!is_array($sessao)) {
            $sessao = [];
        }
        parent::fromArray($sessao);
        if (!isset($sessao['id'])) {
            $this->setID(null);
        } else {
            $this->setID($sessao['id']);
        }
        if (!isset($sessao['datainicio'])) {
            $this->setDataInicio(DB::now());
        } else {
            $this->setDataInicio($sessao['datainicio']);
        }
        if (!array_key_exists('datatermino', $sessao)) {
            $this->setDataTermino(null);
        } else {
            $this->setDataTermino($sessao['datatermino']);
        }
        if (!isset($sessao['aberta'])) {
            $this->setAberta('N');
        } else {
            $this->setAberta($sessao['aberta']);
        }
        return $this;
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $sessao = parent::publish();
        return $sessao;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param Sessao $original Original instance without modifications
     */
    public function filter($original)
    {
        $this->setID($original->getID());
        $this->setDataInicio(Filter::datetime($this->getDataInicio()));
        $this->setDataTermino(Filter::datetime($this->getDataTermino()));
    }

    /**
     * Clean instance resources like images and docs
     * @param  Sessao $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Sessao in array format
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getDataInicio())) {
            $errors['datainicio'] = 'A data de início não pode ser vazia';
        }
        if (!Validator::checkBoolean($this->getAberta())) {
            $errors['aberta'] = 'A abertura não foi informada ou é inválida';
        } elseif (!$this->exists() && !$this->isAberta()) {
            $errors['aberta'] = 'A sessão não pode iniciar fechada';
        }
        $sessao = self::findByAberta();
        if ($this->isAberta() && $sessao->exists() && $this->getID() != $sessao->getID()) {
            $errors['aberta'] = 'Já existe uma sessão aberta';
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
     * Insert a new Sessão into the database and fill instance from database
     * @return Sessao Self instance
     */
    public function insert()
    {
        $this->setID(null);
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = DB::insertInto('Sessoes')->values($values)->execute();
            $this->loadByID($id);
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Sessão with instance values into database for ID
     * @param  array $only Save these fields only, when empty save all fields except id
     * @param  boolean $except When true, saves all fields except $only
     * @return Sessao Self instance
     */
    public function update($only = [], $except = false)
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador da sessão não foi informado');
        }
        $values = DB::filterValues($values, $only, $except);
        try {
            DB::update('Sessoes')
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
            throw new \Exception('O identificador da sessão não foi informado');
        }
        $result = DB::deleteFrom('Sessoes')
            ->where('id', $this->getID())
            ->execute();
        return $result;
    }

    /**
     * Load one register for it self with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order associative field name -> [-1, 1]
     * @return Sessao Self instance filled or empty
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
        $sessao = new Sessao();
        $allowed = Filter::concatKeys('s.', $sessao->toArray());
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
        return Filter::orderBy($order, $allowed, 's.');
    }

    /**
     * Filter condition array with allowed fields
     * @param  array $condition condition to filter rows
     * @return array allowed condition
     */
    private static function filterCondition($condition)
    {
        $allowed = self::getAllowedKeys();
        return Filter::keys($condition, $allowed, 's.');
    }

    /**
     * Fetch data from database with a condition
     * @param  array $condition condition to filter rows
     * @param  array $order order rows
     * @return SelectQuery query object with condition statement
     */
    private static function query($condition = [], $order = [])
    {
        $query = DB::from('Sessoes s');
        $condition = self::filterCondition($condition);
        $query = DB::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('s.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order order rows
     * @return Sessao A filled Sessão or empty instance
     */
    public static function find($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return new Sessao($row);
    }

    /**
     * Find open session
     * @param  boolean $required when true and none sesstion open found throw an exception
     * @return Sessao A filled instance or empty when not found
     */
    public static function findByAberta($required = false)
    {
        $sessao = self::find([
            'aberta' => 'Y',
        ]);
        if ($required && !$sessao->exists()) {
            throw new \Exception('O caixa ainda não foi aberto');
        }
        return $sessao;
    }

    /**
     * Find open session
     * @param  boolean $required when true and none sesstion open found throw an exception
     * @return Sessao A filled instance or empty when not found
     */
    public static function findLastAberta($required = false)
    {
        $sessao = self::find([], ['aberta' => 1, 'id' => -1]);
        if ($required && !$sessao->exists()) {
            throw new \Exception('Nenhum caixa aberto ou fechado');
        }
        return $sessao;
    }

    /**
     * Find all Sessão
     * @param  array  $condition Condition to get all Sessão
     * @param  array  $order     Order Sessão
     * @param  int    $limit     Limit data into row count
     * @param  int    $offset    Start offset to get rows
     * @return array             List of all rows instanced as Sessao
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
            $result[] = new Sessao($row);
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
