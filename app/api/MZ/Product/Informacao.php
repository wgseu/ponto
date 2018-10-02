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

use MZ\Util\Mask;
use MZ\Util\Filter;
use MZ\Util\Validator;
use MZ\Database\DB;
use MZ\Database\SyncModel;
use MZ\Exception\ValidationException;

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
     * @return int id of Informação nutricional
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param int $id Set id for Informação nutricional
     * @return self Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Produto a que essa tabela de informações nutricionais pertence
     * @return int produto of Informação nutricional
     */
    public function getProdutoID()
    {
        return $this->produto_id;
    }

    /**
     * Set ProdutoID value to new on param
     * @param int $produto_id Set produto for Informação nutricional
     * @return self Self instance
     */
    public function setProdutoID($produto_id)
    {
        $this->produto_id = $produto_id;
        return $this;
    }

    /**
     * Unidade de medida da porção
     * @return int unidade of Informação nutricional
     */
    public function getUnidadeID()
    {
        return $this->unidade_id;
    }

    /**
     * Set UnidadeID value to new on param
     * @param int $unidade_id Set unidade for Informação nutricional
     * @return self Self instance
     */
    public function setUnidadeID($unidade_id)
    {
        $this->unidade_id = $unidade_id;
        return $this;
    }

    /**
     * Quantidade da porção para base nos valores nutricionais
     * @return float porção of Informação nutricional
     */
    public function getPorcao()
    {
        return $this->porcao;
    }

    /**
     * Set Porcao value to new on param
     * @param float $porcao Set porção for Informação nutricional
     * @return self Self instance
     */
    public function setPorcao($porcao)
    {
        $this->porcao = $porcao;
        return $this;
    }

    /**
     * Informa a quantidade de referência da dieta geralmente 2000kcal ou
     * 8400kJ
     * @return float dieta of Informação nutricional
     */
    public function getDieta()
    {
        return $this->dieta;
    }

    /**
     * Set Dieta value to new on param
     * @param float $dieta Set dieta for Informação nutricional
     * @return self Self instance
     */
    public function setDieta($dieta)
    {
        $this->dieta = $dieta;
        return $this;
    }

    /**
     * Informa todos os ingredientes que compõe o produto
     * @return string ingredientes of Informação nutricional
     */
    public function getIngredientes()
    {
        return $this->ingredientes;
    }

    /**
     * Set Ingredientes value to new on param
     * @param string $ingredientes Set ingredientes for Informação nutricional
     * @return self Self instance
     */
    public function setIngredientes($ingredientes)
    {
        $this->ingredientes = $ingredientes;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param boolean $recursive Allow rescursive conversion of fields
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
     * @param mixed $informacao Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($informacao = [])
    {
        if ($informacao instanceof self) {
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
     * @param self $original Original instance without modifications
     * @param boolean $localized Informs if fields are localized
     * @return self Self instance
     */
    public function filter($original, $localized = false)
    {
        $this->setID($original->getID());
        $this->setProdutoID(Filter::number($this->getProdutoID()));
        $this->setUnidadeID(Filter::number($this->getUnidadeID()));
        $this->setPorcao(Filter::float($this->getPorcao(), $localized));
        $this->setDieta(Filter::float($this->getDieta(), $localized));
        $this->setIngredientes(Filter::text($this->getIngredientes()));
        return $this;
    }

    /**
     * Clean instance resources like images and docs
     * @param self $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Informacao in array format
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getProdutoID())) {
            $errors['produtoid'] = _t('informacao.produto_id_cannot_empty');
        }
        if (is_null($this->getUnidadeID())) {
            $errors['unidadeid'] = _t('informacao.unidade_id_cannot_empty');
        }
        if (is_null($this->getPorcao())) {
            $errors['porcao'] = _t('informacao.porcao_cannot_empty');
        }
        if (is_null($this->getDieta())) {
            $errors['dieta'] = _t('informacao.dieta_cannot_empty');
        }
        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
        return $this->toArray();
    }

    /**
     * Translate SQL exception into application exception
     * @param \Exception $e exception to translate into a readable error
     * @return \MZ\Exception\ValidationException new exception translated
     */
    protected function translate($e)
    {
        if (contains(['ProdutoID', 'UNIQUE'], $e->getMessage())) {
            return new ValidationException([
                'produtoid' => _t(
                    'informacao.produto_id_used',
                    $this->getProdutoID()
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Insert a new Informação nutricional into the database and fill instance from database
     * @return self Self instance
     * @throws \MZ\Exception\ValidationException for invalid input data
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
     * @param array $only Save these fields only, when empty save all fields except id
     * @return int rows affected
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function update($only = [])
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new ValidationException(
                ['id' => _t('informacao.id_cannot_empty')]
            );
        }
        $values = DB::filterValues($values, $only, false);
        try {
            $affected = DB::update('Informacoes')
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
     * Delete this instance from database using ID
     * @return integer Number of rows deleted (Max 1)
     * @throws \MZ\Exception\ValidationException for invalid id
     */
    public function delete()
    {
        if (!$this->exists()) {
            throw new ValidationException(
                ['id' => _t('informacao.id_cannot_empty')]
            );
        }
        $result = DB::deleteFrom('Informacoes')
            ->where('id', $this->getID())
            ->execute();
        return $result;
    }

    /**
     * Load one register for it self with a condition
     * @param array $condition Condition for searching the row
     * @param array $order associative field name -> [-1, 1]
     * @return self Self instance filled or empty
     */
    public function load($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return $this->fromArray($row);
    }

    /**
     * Load into this object from database using, ProdutoID
     * @return self Self filled instance or empty when not found
     */
    public function loadByProdutoID()
    {
        return $this->load([
            'produtoid' => intval($this->getProdutoID()),
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
        $informacao = new self();
        $allowed = Filter::concatKeys('i.', $informacao->toArray());
        return $allowed;
    }

    /**
     * Filter order array
     * @param mixed $order order string or array to parse and filter allowed
     * @return array allowed associative order
     */
    private static function filterOrder($order)
    {
        $allowed = self::getAllowedKeys();
        return Filter::orderBy($order, $allowed, 'i.');
    }

    /**
     * Filter condition array with allowed fields
     * @param array $condition condition to filter rows
     * @return array allowed condition
     */
    private static function filterCondition($condition)
    {
        $allowed = self::getAllowedKeys();
        return Filter::keys($condition, $allowed, 'i.');
    }

    /**
     * Fetch data from database with a condition
     * @param array $condition condition to filter rows
     * @param array $order order rows
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
     * @param array $condition Condition for searching the row
     * @param array $order order rows
     * @return self A filled Informação nutricional or empty instance
     */
    public static function find($condition, $order = [])
    {
        $result = new self();
        return $result->load($condition, $order);
    }

    /**
     * Search one register with a condition
     * @param array $condition Condition for searching the row
     * @param array $order order rows
     * @return self A filled Informação nutricional or empty instance
     * @throws \Exception when register has not found
     */
    public static function findOrFail($condition, $order = [])
    {
        $result = self::find($condition, $order);
        if (!$result->exists()) {
            throw new \Exception(_t('informacao.not_found'), 404);
        }
        return $result;
    }

    /**
     * Find this object on database using, ProdutoID
     * @param int $produto_id produto to find Informação nutricional
     * @return self A filled instance or empty when not found
     */
    public static function findByProdutoID($produto_id)
    {
        $result = new self();
        $result->setProdutoID($produto_id);
        return $result->loadByProdutoID();
    }

    /**
     * Find all Informação nutricional
     * @param array  $condition Condition to get all Informação nutricional
     * @param array  $order     Order Informação nutricional
     * @param int    $limit     Limit data into row count
     * @param int    $offset    Start offset to get rows
     * @return self[] List of all rows instanced as Informacao
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
     * Count all rows from database with matched condition critery
     * @param array $condition condition to filter rows
     * @return integer Quantity of rows
     */
    public static function count($condition = [])
    {
        $query = self::query($condition);
        return $query->count();
    }
}
