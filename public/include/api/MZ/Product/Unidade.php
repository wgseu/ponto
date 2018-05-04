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
namespace MZ\Product;

use MZ\Util\Filter;
use MZ\Util\Validator;

/**
 * Unidades de medidas aplicadas aos produtos
 */
class Unidade extends \MZ\Database\Helper
{
    const SIGLA_UNITARIA = 'UN';

    /**
     * Identificador da unidade
     */
    private $id;
    /**
     * Nome da unidade de medida, Ex.: Grama, Quilo
     */
    private $nome;
    /**
     * Detalhes sobre a unidade de medida
     */
    private $descricao;
    /**
     * Sigla da unidade de medida, Ex.: UN, L, g
     */
    private $sigla;

    /**
     * Constructor for a new empty instance of Unidade
     * @param array $unidade All field and values to fill the instance
     */
    public function __construct($unidade = [])
    {
        parent::__construct($unidade);
    }

    /**
     * Identificador da unidade
     * @return mixed ID of Unidade
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param  mixed $id new value for ID
     * @return Unidade Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Nome da unidade de medida, Ex.: Grama, Quilo
     * @return mixed Nome of Unidade
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set Nome value to new on param
     * @param  mixed $nome new value for Nome
     * @return Unidade Self instance
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * Detalhes sobre a unidade de medida
     * @return mixed Descrição of Unidade
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Set Descricao value to new on param
     * @param  mixed $descricao new value for Descricao
     * @return Unidade Self instance
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * Sigla da unidade de medida, Ex.: UN, L, g
     * @return mixed Sigla of Unidade
     */
    public function getSigla()
    {
        return $this->sigla;
    }

    /**
     * Set Sigla value to new on param
     * @param  mixed $sigla new value for Sigla
     * @return Unidade Self instance
     */
    public function setSigla($sigla)
    {
        $this->sigla = $sigla;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param  boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $unidade = parent::toArray($recursive);
        $unidade['id'] = $this->getID();
        $unidade['nome'] = $this->getNome();
        $unidade['descricao'] = $this->getDescricao();
        $unidade['sigla'] = $this->getSigla();
        return $unidade;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param  mixed $unidade Associated key -> value to assign into this instance
     * @return Unidade Self instance
     */
    public function fromArray($unidade = [])
    {
        if ($unidade instanceof Unidade) {
            $unidade = $unidade->toArray();
        } elseif (!is_array($unidade)) {
            $unidade = [];
        }
        parent::fromArray($unidade);
        if (!isset($unidade['id'])) {
            $this->setID(null);
        } else {
            $this->setID($unidade['id']);
        }
        if (!isset($unidade['nome'])) {
            $this->setNome(null);
        } else {
            $this->setNome($unidade['nome']);
        }
        if (!array_key_exists('descricao', $unidade)) {
            $this->setDescricao(null);
        } else {
            $this->setDescricao($unidade['descricao']);
        }
        if (!isset($unidade['sigla'])) {
            $this->setSigla(null);
        } else {
            $this->setSigla($unidade['sigla']);
        }
        return $this;
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $unidade = parent::publish();
        return $unidade;
    }

    private function processaUnidade($quantidade, $conteudo)
    {
        $unidade = $this->getSigla();
        $grandezas = [
            -24 => 'y',
            -21 => 'z',
            -18 => 'a',
            -15 => 'f',
            -12 => 'p',
            -9  => 'n',
            -6  => 'µ',
            -3  => 'm',
            -2  => 'c',
            -1  => 'd',
             0  => '',
             1  => 'da',
             2  => 'h',
             3  => 'k',
             6  => 'M',
             9  => 'G',
             12 => 'T',
             15 => 'P',
             18 => 'E',
             21 => 'Z',
             24 => 'Y'
        ];
        $index = intval(log10($conteudo));
        $remain = $conteudo / pow(10, $index);
        if (!array_key_exists($index, $grandezas)) {
            throw new \Exception('Não existe grandeza para o conteudo '.$conteudo.' da unidade '.$unidade, 404);
        }
        $unidade = $grandezas[$index].$unidade;
        return [
            'unidade' => $unidade,
            'quantidade' => $quantidade * $remain
        ];
    }

    public function processaSigla($quantidade, $conteudo)
    {
        $data = $this->processaUnidade($quantidade, $conteudo);
        return $data['unidade'];
    }

    public function processaQuantidade($quantidade, $conteudo)
    {
        $data = $this->processaUnidade($quantidade, $conteudo);
        return $data['quantidade'];
    }

    public function formatar($quantidade, $conteudo)
    {
        $data = $this->processaUnidade($quantidade, $conteudo);
        return strval($data['quantidade']) . ' ' . $data['unidade'];
    }

    /**
     * Filter fields, upload data and keep key data
     * @param Unidade $original Original instance without modifications
     */
    public function filter($original)
    {
        $this->setID($original->getID());
        $this->setNome(Filter::string($this->getNome()));
        $this->setDescricao(Filter::string($this->getDescricao()));
        $this->setSigla(Filter::string($this->getSigla()));
    }

    /**
     * Clean instance resources like images and docs
     * @param  Unidade $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Unidade in array format
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getNome())) {
            $errors['nome'] = 'O nome não pode ser vazio';
        }
        if (is_null($this->getSigla())) {
            $errors['sigla'] = 'A sigla não pode ser vazia';
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
        if (contains(['Sigla', 'UNIQUE'], $e->getMessage())) {
            return new \MZ\Exception\ValidationException([
                'sigla' => sprintf(
                    'A sigla "%s" já está cadastrada',
                    $this->getSigla()
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Insert a new Unidade into the database and fill instance from database
     * @return Unidade Self instance
     */
    public function insert()
    {
        $this->setID(null);
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = self::getDB()->insertInto('Unidades')->values($values)->execute();
            $this->loadByID($id);
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Unidade with instance values into database for ID
     * @param  array $only Save these fields only, when empty save all fields except id
     * @param  boolean $except When true, saves all fields except $only
     * @return Unidade Self instance
     */
    public function update($only = [], $except = false)
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador da unidade não foi informado');
        }
        $values = self::filterValues($values, $only, $except);
        try {
            self::getDB()
                ->update('Unidades')
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
            throw new \Exception('O identificador da unidade não foi informado');
        }
        $result = self::getDB()
            ->deleteFrom('Unidades')
            ->where('id', $this->getID())
            ->execute();
        return $result;
    }

    /**
     * Load one register for it self with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order associative field name -> [-1, 1]
     * @return Unidade Self instance filled or empty
     */
    public function load($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return $this->fromArray($row);
    }

    /**
     * Load into this object from database using, Sigla
     * @param  string $sigla sigla to find Unidade
     * @return Unidade Self filled instance or empty when not found
     */
    public function loadBySigla($sigla)
    {
        return $this->load([
            'sigla' => strval($sigla),
        ]);
    }

    /**
     * Get allowed keys array
     * @return array allowed keys array
     */
    private static function getAllowedKeys()
    {
        $unidade = new Unidade();
        $allowed = Filter::concatKeys('u.', $unidade->toArray());
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
        return Filter::orderBy($order, $allowed, 'u.');
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
            $field = '(u.nome LIKE ? OR u.descricao LIKE ?)';
            $condition[$field] = ['%'.$search.'%', '%'.$search.'%'];
            $allowed[$field] = true;
            unset($condition['search']);
        }
        return Filter::keys($condition, $allowed, 'u.');
    }

    /**
     * Fetch data from database with a condition
     * @param  array $condition condition to filter rows
     * @param  array $order order rows
     * @return SelectQuery query object with condition statement
     */
    private static function query($condition = [], $order = [])
    {
        $query = self::getDB()->from('Unidades u');
        $condition = self::filterCondition($condition);
        $query = self::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('u.nome ASC');
        $query = $query->orderBy('u.id ASC');
        return self::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order order rows
     * @return Unidade A filled Unidade or empty instance
     */
    public static function find($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return new Unidade($row);
    }

    /**
     * Find this object on database using, ID
     * @param  int $id id to find Unidade
     * @return Unidade A filled instance or empty when not found
     */
    public static function findByID($id)
    {
        return self::find([
            'id' => intval($id),
        ]);
    }

    /**
     * Find this object on database using, Sigla
     * @param  string $sigla sigla to find Unidade
     * @return Unidade A filled instance or empty when not found
     */
    public static function findBySigla($sigla)
    {
        return self::find([
            'sigla' => strval($sigla),
        ]);
    }

    /**
     * Find all Unidade
     * @param  array  $condition Condition to get all Unidade
     * @param  array  $order     Order Unidade
     * @param  int    $limit     Limit data into row count
     * @param  int    $offset    Start offset to get rows
     * @return array             List of all rows instanced as Unidade
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
            $result[] = new Unidade($row);
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
