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

use MZ\Database\SyncModel;
use MZ\Database\DB;
use MZ\Util\Filter;
use MZ\Util\Validator;

/**
 * Informa todos os valores nutricionais da tabela nutricional
 */
class ValorNutricional extends SyncModel
{

    /**
     * Identificador do valor nutricional
     */
    private $id;
    /**
     * Informe a que tabela nutricional este valor pertence
     */
    private $informacao_id;
    /**
     * Unidade de medida do valor nutricional, geralmente grama, exceto para
     * valor energético
     */
    private $unidade_id;
    /**
     * Nome do valor nutricional
     */
    private $nome;
    /**
     * Quantidade do valor nutricional com base na porção
     */
    private $quantidade;
    /**
     * Valor diário em %
     */
    private $valor_diario;

    /**
     * Constructor for a new empty instance of ValorNutricional
     * @param array $valor_nutricional All field and values to fill the instance
     */
    public function __construct($valor_nutricional = [])
    {
        parent::__construct($valor_nutricional);
    }

    /**
     * Identificador do valor nutricional
     * @return mixed ID of ValorNutricional
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param  mixed $id new value for ID
     * @return ValorNutricional Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Informe a que tabela nutricional este valor pertence
     * @return mixed Informação of ValorNutricional
     */
    public function getInformacaoID()
    {
        return $this->informacao_id;
    }

    /**
     * Set InformacaoID value to new on param
     * @param  mixed $informacao_id new value for InformacaoID
     * @return ValorNutricional Self instance
     */
    public function setInformacaoID($informacao_id)
    {
        $this->informacao_id = $informacao_id;
        return $this;
    }

    /**
     * Unidade de medida do valor nutricional, geralmente grama, exceto para
     * valor energético
     * @return mixed Unidade of ValorNutricional
     */
    public function getUnidadeID()
    {
        return $this->unidade_id;
    }

    /**
     * Set UnidadeID value to new on param
     * @param  mixed $unidade_id new value for UnidadeID
     * @return ValorNutricional Self instance
     */
    public function setUnidadeID($unidade_id)
    {
        $this->unidade_id = $unidade_id;
        return $this;
    }

    /**
     * Nome do valor nutricional
     * @return mixed Nome of ValorNutricional
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set Nome value to new on param
     * @param  mixed $nome new value for Nome
     * @return ValorNutricional Self instance
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * Quantidade do valor nutricional com base na porção
     * @return mixed Quantidade of ValorNutricional
     */
    public function getQuantidade()
    {
        return $this->quantidade;
    }

    /**
     * Set Quantidade value to new on param
     * @param  mixed $quantidade new value for Quantidade
     * @return ValorNutricional Self instance
     */
    public function setQuantidade($quantidade)
    {
        $this->quantidade = $quantidade;
        return $this;
    }

    /**
     * Valor diário em %
     * @return mixed Valor diário of ValorNutricional
     */
    public function getValorDiario()
    {
        return $this->valor_diario;
    }

    /**
     * Set ValorDiario value to new on param
     * @param  mixed $valor_diario new value for ValorDiario
     * @return ValorNutricional Self instance
     */
    public function setValorDiario($valor_diario)
    {
        $this->valor_diario = $valor_diario;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param  boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $valor_nutricional = parent::toArray($recursive);
        $valor_nutricional['id'] = $this->getID();
        $valor_nutricional['informacaoid'] = $this->getInformacaoID();
        $valor_nutricional['unidadeid'] = $this->getUnidadeID();
        $valor_nutricional['nome'] = $this->getNome();
        $valor_nutricional['quantidade'] = $this->getQuantidade();
        $valor_nutricional['valordiario'] = $this->getValorDiario();
        return $valor_nutricional;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param  mixed $valor_nutricional Associated key -> value to assign into this instance
     * @return ValorNutricional Self instance
     */
    public function fromArray($valor_nutricional = [])
    {
        if ($valor_nutricional instanceof ValorNutricional) {
            $valor_nutricional = $valor_nutricional->toArray();
        } elseif (!is_array($valor_nutricional)) {
            $valor_nutricional = [];
        }
        parent::fromArray($valor_nutricional);
        if (!isset($valor_nutricional['id'])) {
            $this->setID(null);
        } else {
            $this->setID($valor_nutricional['id']);
        }
        if (!isset($valor_nutricional['informacaoid'])) {
            $this->setInformacaoID(null);
        } else {
            $this->setInformacaoID($valor_nutricional['informacaoid']);
        }
        if (!isset($valor_nutricional['unidadeid'])) {
            $this->setUnidadeID(null);
        } else {
            $this->setUnidadeID($valor_nutricional['unidadeid']);
        }
        if (!isset($valor_nutricional['nome'])) {
            $this->setNome(null);
        } else {
            $this->setNome($valor_nutricional['nome']);
        }
        if (!isset($valor_nutricional['quantidade'])) {
            $this->setQuantidade(null);
        } else {
            $this->setQuantidade($valor_nutricional['quantidade']);
        }
        if (!array_key_exists('valordiario', $valor_nutricional)) {
            $this->setValorDiario(null);
        } else {
            $this->setValorDiario($valor_nutricional['valordiario']);
        }
        return $this;
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $valor_nutricional = parent::publish();
        return $valor_nutricional;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param ValorNutricional $original Original instance without modifications
     */
    public function filter($original)
    {
        $this->setID($original->getID());
        $this->setInformacaoID(Filter::number($this->getInformacaoID()));
        $this->setUnidadeID(Filter::number($this->getUnidadeID()));
        $this->setNome(Filter::string($this->getNome()));
        $this->setQuantidade(Filter::float($this->getQuantidade()));
        $this->setValorDiario(Filter::float($this->getValorDiario()));
    }

    /**
     * Clean instance resources like images and docs
     * @param  ValorNutricional $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of ValorNutricional in array format
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getInformacaoID())) {
            $errors['informacaoid'] = 'A informação não pode ser vazia';
        }
        if (is_null($this->getUnidadeID())) {
            $errors['unidadeid'] = 'A unidade não pode ser vazia';
        }
        if (is_null($this->getNome())) {
            $errors['nome'] = 'O nome não pode ser vazio';
        }
        if (is_null($this->getQuantidade())) {
            $errors['quantidade'] = 'A quantidade não pode ser vazia';
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
        if (stripos($e->getMessage(), 'UK_Informacao_Nome') !== false) {
            return new \MZ\Exception\ValidationException([
                'informacaoid' => sprintf(
                    'A informação "%s" já está cadastrada',
                    $this->getInformacaoID()
                ),
                'nome' => sprintf(
                    'O nome "%s" já está cadastrado',
                    $this->getNome()
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Insert a new Valor nutricional into the database and fill instance from database
     * @return ValorNutricional Self instance
     */
    public function insert()
    {
        $this->setID(null);
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = DB::insertInto('Valores_Nutricionais')->values($values)->execute();
            $this->setID($id);
            $this->loadByID();
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Valor nutricional with instance values into database for ID
     * @return ValorNutricional Self instance
     */
    public function update($only = [])
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador do valor nutricional não foi informado');
        }
        $values = DB::filterValues($values, $only, false);
        try {
            DB::update('Valores_Nutricionais')
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
            throw new \Exception('O identificador do valor nutricional não foi informado');
        }
        $result = DB::deleteFrom('Valores_Nutricionais')
            ->where('id', $this->getID())
            ->execute();
        return $result;
    }

    /**
     * Load one register for it self with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order associative field name -> [-1, 1]
     * @return ValorNutricional Self instance filled or empty
     */
    public function load($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return $this->fromArray($row);
    }

    /**
     * Load into this object from database using, InformacaoID, Nome
     * @param  int $informacao_id informação to find Valor nutricional
     * @param  string $nome nome to find Valor nutricional
     * @return ValorNutricional Self filled instance or empty when not found
     */
    public function loadByInformacaoIDNome($informacao_id, $nome)
    {
        return $this->load([
            'informacaoid' => intval($informacao_id),
            'nome' => strval($nome),
        ]);
    }

    /**
     * Informe a que tabela nutricional este valor pertence
     * @return \MZ\Product\Informacao The object fetched from database
     */
    public function findInformacaoID()
    {
        return \MZ\Product\Informacao::findByID($this->getInformacaoID());
    }

    /**
     * Unidade de medida do valor nutricional, geralmente grama, exceto para
     * valor energético
     * @return \MZ\Product\Unidade The object fetched from database
     */
    public function findUnidadeID()
    {
        return \MZ\Product\Unidade::findByID($this->getUnidadeID());
    }

    /**
     * Get allowed keys array
     * @return array allowed keys array
     */
    private static function getAllowedKeys()
    {
        $valor_nutricional = new ValorNutricional();
        $allowed = Filter::concatKeys('v.', $valor_nutricional->toArray());
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
        return Filter::orderBy($order, $allowed, 'v.');
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
            $field = 'v.nome LIKE ?';
            $condition[$field] = '%'.$search.'%';
            $allowed[$field] = true;
            unset($condition['search']);
        }
        return Filter::keys($condition, $allowed, 'v.');
    }

    /**
     * Fetch data from database with a condition
     * @param  array $condition condition to filter rows
     * @param  array $order order rows
     * @return SelectQuery query object with condition statement
     */
    private static function query($condition = [], $order = [])
    {
        $query = DB::from('Valores_Nutricionais v');
        $condition = self::filterCondition($condition);
        $query = DB::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('v.nome ASC');
        $query = $query->orderBy('v.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order order rows
     * @return ValorNutricional A filled Valor nutricional or empty instance
     */
    public static function find($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return new ValorNutricional($row);
    }

    /**
     * Find this object on database using, InformacaoID, Nome
     * @param  int $informacao_id informação to find Valor nutricional
     * @param  string $nome nome to find Valor nutricional
     * @return ValorNutricional A filled instance or empty when not found
     */
    public static function findByInformacaoIDNome($informacao_id, $nome)
    {
        $result = new self();
        return $result->loadByInformacaoIDNome($informacao_id, $nome);
    }

    /**
     * Find all Valor nutricional
     * @param  array  $condition Condition to get all Valor nutricional
     * @param  array  $order     Order Valor nutricional
     * @param  int    $limit     Limit data into row count
     * @param  int    $offset    Start offset to get rows
     * @return array             List of all rows instanced as ValorNutricional
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
            $result[] = new ValorNutricional($row);
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
