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
 * Permite cadastrar informações da tabela nutricional
 */
class Informacao extends SyncModel
{

    /**
     * Identificador da informação nutricional
     */
    private $id;
    /**
     * Produto a que essa tabela de informações nutricionais pertence
     */
    private $produto_id;
    /**
     * Unidade de medida da porção
     */
    private $unidade_id;
    /**
     * Quantidade da porção para base nos valores nutricionais
     */
    private $porcao;
    /**
     * Informa a quantidade de referência da dieta geralmente 2000kcal ou
     * 8400kJ
     */
    private $dieta;
    /**
     * Informa todos os ingredientes que compõe o produto
     */
    private $ingredientes;

    /**
     * Constructor for a new empty instance of Informacao
     * @param array $informacao All field and values to fill the instance
     */
    public function __construct($informacao = [])
    {
        parent::__construct($informacao);
    }

    /**
     * Identificador da informação nutricional
     * @return mixed ID of Informacao
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param  mixed $id new value for ID
     * @return Informacao Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Produto a que essa tabela de informações nutricionais pertence
     * @return mixed Produto of Informacao
     */
    public function getProdutoID()
    {
        return $this->produto_id;
    }

    /**
     * Set ProdutoID value to new on param
     * @param  mixed $produto_id new value for ProdutoID
     * @return Informacao Self instance
     */
    public function setProdutoID($produto_id)
    {
        $this->produto_id = $produto_id;
        return $this;
    }

    /**
     * Unidade de medida da porção
     * @return mixed Unidade of Informacao
     */
    public function getUnidadeID()
    {
        return $this->unidade_id;
    }

    /**
     * Set UnidadeID value to new on param
     * @param  mixed $unidade_id new value for UnidadeID
     * @return Informacao Self instance
     */
    public function setUnidadeID($unidade_id)
    {
        $this->unidade_id = $unidade_id;
        return $this;
    }

    /**
     * Quantidade da porção para base nos valores nutricionais
     * @return mixed Porção of Informacao
     */
    public function getPorcao()
    {
        return $this->porcao;
    }

    /**
     * Set Porcao value to new on param
     * @param  mixed $porcao new value for Porcao
     * @return Informacao Self instance
     */
    public function setPorcao($porcao)
    {
        $this->porcao = $porcao;
        return $this;
    }

    /**
     * Informa a quantidade de referência da dieta geralmente 2000kcal ou
     * 8400kJ
     * @return mixed Dieta of Informacao
     */
    public function getDieta()
    {
        return $this->dieta;
    }

    /**
     * Set Dieta value to new on param
     * @param  mixed $dieta new value for Dieta
     * @return Informacao Self instance
     */
    public function setDieta($dieta)
    {
        $this->dieta = $dieta;
        return $this;
    }

    /**
     * Informa todos os ingredientes que compõe o produto
     * @return mixed Ingredientes of Informacao
     */
    public function getIngredientes()
    {
        return $this->ingredientes;
    }

    /**
     * Set Ingredientes value to new on param
     * @param  mixed $ingredientes new value for Ingredientes
     * @return Informacao Self instance
     */
    public function setIngredientes($ingredientes)
    {
        $this->ingredientes = $ingredientes;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param  boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $informacao = parent::toArray($recursive);
        $informacao['id'] = $this->getID();
        $informacao['produtoid'] = $this->getProdutoID();
        $informacao['unidadeid'] = $this->getUnidadeID();
        $informacao['porcao'] = $this->getPorcao();
        $informacao['dieta'] = $this->getDieta();
        $informacao['ingredientes'] = $this->getIngredientes();
        return $informacao;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param  mixed $informacao Associated key -> value to assign into this instance
     * @return Informacao Self instance
     */
    public function fromArray($informacao = [])
    {
        if ($informacao instanceof Informacao) {
            $informacao = $informacao->toArray();
        } elseif (!is_array($informacao)) {
            $informacao = [];
        }
        parent::fromArray($informacao);
        if (!isset($informacao['id'])) {
            $this->setID(null);
        } else {
            $this->setID($informacao['id']);
        }
        if (!isset($informacao['produtoid'])) {
            $this->setProdutoID(null);
        } else {
            $this->setProdutoID($informacao['produtoid']);
        }
        if (!isset($informacao['unidadeid'])) {
            $this->setUnidadeID(null);
        } else {
            $this->setUnidadeID($informacao['unidadeid']);
        }
        if (!isset($informacao['porcao'])) {
            $this->setPorcao(null);
        } else {
            $this->setPorcao($informacao['porcao']);
        }
        if (!isset($informacao['dieta'])) {
            $this->setDieta(null);
        } else {
            $this->setDieta($informacao['dieta']);
        }
        if (!array_key_exists('ingredientes', $informacao)) {
            $this->setIngredientes(null);
        } else {
            $this->setIngredientes($informacao['ingredientes']);
        }
        return $this;
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $informacao = parent::publish();
        return $informacao;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param Informacao $original Original instance without modifications
     */
    public function filter($original, $localized = false)
    {
        $this->setID($original->getID());
        $this->setProdutoID(Filter::number($this->getProdutoID()));
        $this->setUnidadeID(Filter::number($this->getUnidadeID()));
        $this->setPorcao(Filter::float($this->getPorcao(), $localized));
        $this->setDieta(Filter::float($this->getDieta(), $localized));
        $this->setIngredientes(Filter::text($this->getIngredientes()));
    }

    /**
     * Clean instance resources like images and docs
     * @param  Informacao $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Informacao in array format
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getProdutoID())) {
            $errors['produtoid'] = 'O produto não pode ser vazio';
        }
        if (is_null($this->getUnidadeID())) {
            $errors['unidadeid'] = 'A unidade não pode ser vazia';
        }
        if (is_null($this->getPorcao())) {
            $errors['porcao'] = 'A porção não pode ser vazia';
        }
        if (is_null($this->getDieta())) {
            $errors['dieta'] = 'A dieta não pode ser vazia';
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
        if (contains(['ProdutoID', 'UNIQUE'], $e->getMessage())) {
            return new \MZ\Exception\ValidationException([
                'produtoid' => sprintf(
                    'O produto "%s" já está cadastrado',
                    $this->getProdutoID()
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Insert a new Informação nutricional into the database and fill instance from database
     * @return Informacao Self instance
     */
    public function insert()
    {
        $this->setID(null);
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = DB::insertInto('Informacoes')->values($values)->execute();
            $this->setID($id);
            $this->loadByID();
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Informação nutricional with instance values into database for ID
     * @return Informacao Self instance
     */
    public function update($only = [])
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador da informação nutricional não foi informado');
        }
        $values = DB::filterValues($values, $only, false);
        try {
            DB::update('Informacoes')
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
            throw new \Exception('O identificador da informação nutricional não foi informado');
        }
        $result = DB::deleteFrom('Informacoes')
            ->where('id', $this->getID())
            ->execute();
        return $result;
    }

    /**
     * Load one register for it self with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order associative field name -> [-1, 1]
     * @return Informacao Self instance filled or empty
     */
    public function load($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return $this->fromArray($row);
    }

    /**
     * Load into this object from database using, ProdutoID
     * @param  int $produto_id produto to find Informação nutricional
     * @return Informacao Self filled instance or empty when not found
     */
    public function loadByProdutoID($produto_id)
    {
        return $this->load([
            'produtoid' => intval($produto_id),
        ]);
    }

    /**
     * Produto a que essa tabela de informações nutricionais pertence
     * @return \MZ\Product\Produto The object fetched from database
     */
    public function findProdutoID()
    {
        return \MZ\Product\Produto::findByID($this->getProdutoID());
    }

    /**
     * Unidade de medida da porção
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
        $informacao = new Informacao();
        $allowed = Filter::concatKeys('i.', $informacao->toArray());
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
        return Filter::orderBy($order, $allowed, 'i.');
    }

    /**
     * Filter condition array with allowed fields
     * @param  array $condition condition to filter rows
     * @return array allowed condition
     */
    private static function filterCondition($condition)
    {
        $allowed = self::getAllowedKeys();
        return Filter::keys($condition, $allowed, 'i.');
    }

    /**
     * Fetch data from database with a condition
     * @param  array $condition condition to filter rows
     * @param  array $order order rows
     * @return SelectQuery query object with condition statement
     */
    private static function query($condition = [], $order = [])
    {
        $query = DB::from('Informacoes i');
        $condition = self::filterCondition($condition);
        $query = DB::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('i.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order order rows
     * @return Informacao A filled Informação nutricional or empty instance
     */
    public static function find($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return new Informacao($row);
    }

    /**
     * Find this object on database using, ProdutoID
     * @param  int $produto_id produto to find Informação nutricional
     * @return Informacao A filled instance or empty when not found
     */
    public static function findByProdutoID($produto_id)
    {
        $result = new self();
        return $result->loadByProdutoID($produto_id);
    }

    /**
     * Find all Informação nutricional
     * @param  array  $condition Condition to get all Informação nutricional
     * @param  array  $order     Order Informação nutricional
     * @param  int    $limit     Limit data into row count
     * @param  int    $offset    Start offset to get rows
     * @return array             List of all rows instanced as Informacao
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
            $result[] = new Informacao($row);
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
