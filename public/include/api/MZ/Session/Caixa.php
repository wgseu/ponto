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
 * Caixas de movimentação financeira
 */
class Caixa extends Model
{

    /**
     * Identificador do caixa
     */
    private $id;
    /**
     * Descrição do caixa
     */
    private $descricao;
    /**
     * Série do caixa
     */
    private $serie;
    /**
     * Número inicial na geração da nota, será usado quando maior que o último
     * número utilizado
     */
    private $numero_inicial;
    /**
     * Informa se o caixa está ativo
     */
    private $ativo;

    /**
     * Constructor for a new empty instance of Caixa
     * @param array $caixa All field and values to fill the instance
     */
    public function __construct($caixa = [])
    {
        parent::__construct($caixa);
    }

    /**
     * Identificador do caixa
     * @return mixed ID of Caixa
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param  mixed $id new value for ID
     * @return Caixa Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Descrição do caixa
     * @return mixed Descrição of Caixa
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Set Descricao value to new on param
     * @param  mixed $descricao new value for Descricao
     * @return Caixa Self instance
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * Série do caixa
     * @return mixed Série of Caixa
     */
    public function getSerie()
    {
        return $this->serie;
    }

    /**
     * Set Serie value to new on param
     * @param  mixed $serie new value for Serie
     * @return Caixa Self instance
     */
    public function setSerie($serie)
    {
        $this->serie = $serie;
        return $this;
    }

    /**
     * Número inicial na geração da nota, será usado quando maior que o último
     * número utilizado
     * @return mixed Número inicial of Caixa
     */
    public function getNumeroInicial()
    {
        return $this->numero_inicial;
    }

    /**
     * Set NumeroInicial value to new on param
     * @param  mixed $numero_inicial new value for NumeroInicial
     * @return Caixa Self instance
     */
    public function setNumeroInicial($numero_inicial)
    {
        $this->numero_inicial = $numero_inicial;
        return $this;
    }

    /**
     * Informa se o caixa está ativo
     * @return mixed Ativo of Caixa
     */
    public function getAtivo()
    {
        return $this->ativo;
    }

    /**
     * Informa se o caixa está ativo
     * @return boolean Check if o of Ativo is selected or checked
     */
    public function isAtivo()
    {
        return $this->ativo == 'Y';
    }

    /**
     * Set Ativo value to new on param
     * @param  mixed $ativo new value for Ativo
     * @return Caixa Self instance
     */
    public function setAtivo($ativo)
    {
        $this->ativo = $ativo;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param  boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $caixa = parent::toArray($recursive);
        $caixa['id'] = $this->getID();
        $caixa['descricao'] = $this->getDescricao();
        $caixa['serie'] = $this->getSerie();
        $caixa['numeroinicial'] = $this->getNumeroInicial();
        $caixa['ativo'] = $this->getAtivo();
        return $caixa;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param  mixed $caixa Associated key -> value to assign into this instance
     * @return Caixa Self instance
     */
    public function fromArray($caixa = [])
    {
        if ($caixa instanceof Caixa) {
            $caixa = $caixa->toArray();
        } elseif (!is_array($caixa)) {
            $caixa = [];
        }
        parent::fromArray($caixa);
        if (!isset($caixa['id'])) {
            $this->setID(null);
        } else {
            $this->setID($caixa['id']);
        }
        if (!isset($caixa['descricao'])) {
            $this->setDescricao(null);
        } else {
            $this->setDescricao($caixa['descricao']);
        }
        if (!isset($caixa['serie'])) {
            $this->setSerie(null);
        } else {
            $this->setSerie($caixa['serie']);
        }
        if (!isset($caixa['numeroinicial'])) {
            $this->setNumeroInicial(null);
        } else {
            $this->setNumeroInicial($caixa['numeroinicial']);
        }
        if (!isset($caixa['ativo'])) {
            $this->setAtivo('N');
        } else {
            $this->setAtivo($caixa['ativo']);
        }
        return $this;
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $caixa = parent::publish();
        return $caixa;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param Caixa $original Original instance without modifications
     */
    public function filter($original)
    {
        $this->setID($original->getID());
        $this->setDescricao(Filter::string($this->getDescricao()));
        $this->setSerie(Filter::number($this->getSerie()));
        $this->setNumeroInicial(Filter::number($this->getNumeroInicial()));
    }

    /**
     * Clean instance resources like images and docs
     * @param  Caixa $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Caixa in array format
     */
    public function validate()
    {
        global $app;

        $errors = [];
        if (is_null($this->getDescricao())) {
            $errors['descricao'] = 'A descrição não pode ser vazia';
        }
        $old_caixa = self::findByID($this->getID());
        if (!$app->getSystem()->isFiscalVisible()) {
            $this->setSerie($old_caixa->exists() ? $old_caixa->getSerie() : 1);
        } elseif (!Validator::checkDigits($this->getSerie())) {
            $errors['serie'] = 'A série não pode ser vazia';
        }
        if (!$app->getSystem()->isFiscalVisible()) {
            $this->setNumeroInicial($old_caixa->exists() ? $old_caixa->getNumeroInicial() : 1);
        } elseif (!Validator::checkDigits($this->getNumeroInicial())) {
            $errors['numeroinicial'] = 'O número inicial não pode ser vazio';
        }
        if (!Validator::checkBoolean($this->getAtivo())) {
            $errors['ativo'] = 'O estado de ativação é inválido';
        } elseif (!$this->isAtivo() && Movimentacao::isCaixaOpen($this->getID())) {
            $errors['ativo'] = 'O caixa está aberto e não pode ser desativado';
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
        if (contains(['Descricao', 'UNIQUE'], $e->getMessage())) {
            return new \MZ\Exception\ValidationException([
                'descricao' => sprintf(
                    'A descrição "%s" já está cadastrada',
                    $this->getDescricao()
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Insert a new Caixa into the database and fill instance from database
     * @return Caixa Self instance
     */
    public function insert()
    {
        $this->setID(null);
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = DB::insertInto('Caixas')->values($values)->execute();
            $this->loadByID($id);
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Caixa with instance values into database for ID
     * @param  array $only Save these fields only, when empty save all fields except id
     * @param  boolean $except When true, saves all fields except $only
     * @return Caixa Self instance
     */
    public function update($only = [], $except = false)
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador do caixa não foi informado');
        }
        $values = DB::filterValues($values, $only, $except);
        try {
            DB::update('Caixas')
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
     * Reset invoice initial number to 1 because reached the maximum
     * @param  int $serie affect only this serie
     * @return Caixa Self instance
     */
    public static function resetBySerie($serie)
    {
        // TODO: convert to incremental version
        try {
            DB::update('Caixas')
                ->set('numeroinicial', '1')
                ->where('serie', $serie)
                ->where('ativo', 'Y')
                ->execute();
        } catch (\Exception $e) {
            $caixa = new Caixa();
            throw $caixa->translate($e);
        }
    }

    /**
     * Delete this instance from database using ID
     * @return integer Number of rows deleted (Max 1)
     */
    public function delete()
    {
        if (!$this->exists()) {
            throw new \Exception('O identificador do caixa não foi informado');
        }
        $result = DB::deleteFrom('Caixas')
            ->where('id', $this->getID())
            ->execute();
        return $result;
    }

    /**
     * Load one register for it self with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order associative field name -> [-1, 1]
     * @return Caixa Self instance filled or empty
     */
    public function load($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return $this->fromArray($row);
    }

    /**
     * Load into this object from database using, Descricao
     * @param  string $descricao descrição to find Caixa
     * @return Caixa Self filled instance or empty when not found
     */
    public function loadByDescricao($descricao)
    {
        return $this->load([
            'descricao' => strval($descricao),
        ]);
    }

    /**
     * Get allowed keys array
     * @return array allowed keys array
     */
    private static function getAllowedKeys()
    {
        $caixa = new Caixa();
        $allowed = Filter::concatKeys('c.', $caixa->toArray());
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
            $field = 'c.descricao LIKE ?';
            $condition[$field] = '%'.$search.'%';
            $allowed[$field] = true;
            unset($condition['search']);
        }
        return Filter::keys($condition, $allowed, 'c.');
    }

    /**
     * Fetch data from database with a condition
     * @param  array $condition condition to filter rows
     * @param  array $order order rows
     * @return SelectQuery query object with condition statement
     */
    private static function query($condition = [], $order = [])
    {
        $query = DB::from('Caixas c');
        $condition = self::filterCondition($condition);
        $query = DB::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('c.descricao ASC');
        $query = $query->orderBy('c.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order order rows
     * @return Caixa A filled Caixa or empty instance
     */
    public static function find($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return new Caixa($row);
    }

    /**
     * Find this object on database using, ID
     * @param  int $id id to find Caixa
     * @return Caixa A filled instance or empty when not found
     */
    public static function findByID($id)
    {
        $result = new self();
        return $result->loadByID($id);
    }

    /**
     * Find this object on database using, Descricao
     * @param  string $descricao descrição to find Caixa
     * @return Caixa A filled instance or empty when not found
     */
    public static function findByDescricao($descricao)
    {
        $result = new self();
        return $result->loadByDescricao($descricao);
    }

    /**
     * Find this object as active on database using, Serie
     * @param  string $serie série to find Caixa
     * @return Caixa An active filled instance or empty when not found
     */
    public static function findBySerie($serie)
    {
        return self::find(
            ['serie' => intval($serie), 'ativo' => 'Y'],
            ['numeroinicial' => -1]
        );
    }

    /**
     * Find all Caixa
     * @param  array  $condition Condition to get all Caixa
     * @param  array  $order     Order Caixa
     * @param  int    $limit     Limit data into row count
     * @param  int    $offset    Start offset to get rows
     * @return array             List of all rows instanced as Caixa
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
            $result[] = new Caixa($row);
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
