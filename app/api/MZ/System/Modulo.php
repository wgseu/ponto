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

use MZ\Database\SyncModel;
use MZ\Database\DB;
use MZ\Util\Filter;
use MZ\Util\Validator;

/**
 * Módulos do sistema que podem ser desativados/ativados
 */
class Modulo extends SyncModel
{

    /**
     * Identificador do módulo
     */
    private $id;
    /**
     * Nome do módulo, unico em todo o sistema
     */
    private $nome;
    /**
     * Descrição do módulo, informa detalhes sobre a funcionalidade do módulo
     * no sistema
     */
    private $descricao;
    /**
     * Índice da imagem que representa o módulo, tamanho 64x64
     */
    private $image_index;
    /**
     * Informa se o módulo do sistema está habilitado
     */
    private $habilitado;

    /**
     * Constructor for a new empty instance of Modulo
     * @param array $modulo All field and values to fill the instance
     */
    public function __construct($modulo = [])
    {
        parent::__construct($modulo);
    }

    /**
     * Identificador do módulo
     * @return mixed ID of Modulo
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param  mixed $id new value for ID
     * @return Modulo Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Nome do módulo, unico em todo o sistema
     * @return mixed Nome of Modulo
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set Nome value to new on param
     * @param  mixed $nome new value for Nome
     * @return Modulo Self instance
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * Descrição do módulo, informa detalhes sobre a funcionalidade do módulo
     * no sistema
     * @return mixed Descrição of Modulo
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Set Descricao value to new on param
     * @param  mixed $descricao new value for Descricao
     * @return Modulo Self instance
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * Índice da imagem que representa o módulo, tamanho 64x64
     * @return mixed Imagem of Modulo
     */
    public function getImageIndex()
    {
        return $this->image_index;
    }

    /**
     * Set ImageIndex value to new on param
     * @param  mixed $image_index new value for ImageIndex
     * @return Modulo Self instance
     */
    public function setImageIndex($image_index)
    {
        $this->image_index = $image_index;
        return $this;
    }

    /**
     * Informa se o módulo do sistema está habilitado
     * @return mixed Habilitado of Modulo
     */
    public function getHabilitado()
    {
        return $this->habilitado;
    }

    /**
     * Informa se o módulo do sistema está habilitado
     * @return boolean Check if o of Habilitado is selected or checked
     */
    public function isHabilitado()
    {
        return $this->habilitado == 'Y';
    }

    /**
     * Set Habilitado value to new on param
     * @param  mixed $habilitado new value for Habilitado
     * @return Modulo Self instance
     */
    public function setHabilitado($habilitado)
    {
        $this->habilitado = $habilitado;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param  boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $modulo = parent::toArray($recursive);
        $modulo['id'] = $this->getID();
        $modulo['nome'] = $this->getNome();
        $modulo['descricao'] = $this->getDescricao();
        $modulo['imageindex'] = $this->getImageIndex();
        $modulo['habilitado'] = $this->getHabilitado();
        return $modulo;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param  mixed $modulo Associated key -> value to assign into this instance
     * @return Modulo Self instance
     */
    public function fromArray($modulo = [])
    {
        if ($modulo instanceof Modulo) {
            $modulo = $modulo->toArray();
        } elseif (!is_array($modulo)) {
            $modulo = [];
        }
        parent::fromArray($modulo);
        if (!isset($modulo['id'])) {
            $this->setID(null);
        } else {
            $this->setID($modulo['id']);
        }
        if (!isset($modulo['nome'])) {
            $this->setNome(null);
        } else {
            $this->setNome($modulo['nome']);
        }
        if (!isset($modulo['descricao'])) {
            $this->setDescricao(null);
        } else {
            $this->setDescricao($modulo['descricao']);
        }
        if (!isset($modulo['imageindex'])) {
            $this->setImageIndex(null);
        } else {
            $this->setImageIndex($modulo['imageindex']);
        }
        if (!isset($modulo['habilitado'])) {
            $this->setHabilitado('N');
        } else {
            $this->setHabilitado($modulo['habilitado']);
        }
        return $this;
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $modulo = parent::publish();
        return $modulo;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param Modulo $original Original instance without modifications
     */
    public function filter($original, $localized = false)
    {
        $this->setID($original->getID());
        $this->setNome($original->getNome());
        $this->setDescricao($original->getDescricao());
        $this->setImageIndex($original->getImageIndex());
    }

    /**
     * Clean instance resources like images and docs
     * @param  Modulo $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Modulo in array format
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getNome())) {
            $errors['nome'] = 'O nome não pode ser vazio';
        }
        if (is_null($this->getDescricao())) {
            $errors['descricao'] = 'A descrição não pode ser vazia';
        }
        if (is_null($this->getImageIndex())) {
            $errors['imageindex'] = 'A imagem não pode ser vazia';
        }
        if (!Validator::checkBoolean($this->getHabilitado())) {
            $errors['habilitado'] = 'A informação de habilitado é inválida';
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
     * Insert a new Módulo into the database and fill instance from database
     * @return Modulo Self instance
     */
    public function insert()
    {
        $this->setID(null);
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = DB::insertInto('Modulos')->values($values)->execute();
            $this->setID($id);
            $this->loadByID();
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Módulo with instance values into database for ID
     * @param  array $only Save these fields only, when empty save all fields except id
     * @return Modulo Self instance
     */
    public function update($only = [])
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador do módulo não foi informado');
        }
        $values = DB::filterValues($values, $only, false);
        try {
            DB::update('Modulos')
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
            throw new \Exception('O identificador do módulo não foi informado');
        }
        $result = DB::deleteFrom('Modulos')
            ->where('id', $this->getID())
            ->execute();
        return $result;
    }

    /**
     * Load one register for it self with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order associative field name -> [-1, 1]
     * @return Modulo Self instance filled or empty
     */
    public function load($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return $this->fromArray($row);
    }

    /**
     * Load into this object from database using, Nome
     * @param  string $nome nome to find Módulo
     * @return Modulo Self filled instance or empty when not found
     */
    public function loadByNome($nome)
    {
        return $this->load([
            'nome' => strval($nome),
        ]);
    }

    /**
     * Get allowed keys array
     * @return array allowed keys array
     */
    private static function getAllowedKeys()
    {
        $modulo = new Modulo();
        $allowed = Filter::concatKeys('m.', $modulo->toArray());
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
            $field = '(m.nome LIKE ? OR m.descricao LIKE ?)';
            $condition[$field] = ['%'.$search.'%', '%'.$search.'%'];
            $allowed[$field] = true;
            unset($condition['search']);
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
        $query = DB::from('Modulos m');
        $condition = self::filterCondition($condition);
        $query = DB::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('m.nome ASC');
        $query = $query->orderBy('m.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order order rows
     * @return Modulo A filled Módulo or empty instance
     */
    public static function find($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return new Modulo($row);
    }

    /**
     * Find this object on database using, Nome
     * @param  string $nome nome to find Módulo
     * @return Modulo A filled instance or empty when not found
     */
    public static function findByNome($nome)
    {
        $result = new self();
        return $result->loadByNome($nome);
    }

    /**
     * Find all Módulo
     * @param  array  $condition Condition to get all Módulo
     * @param  array  $order     Order Módulo
     * @param  int    $limit     Limit data into row count
     * @param  int    $offset    Start offset to get rows
     * @return array             List of all rows instanced as Modulo
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
            $result[] = new Modulo($row);
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
