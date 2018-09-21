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

use MZ\Database\SyncModel;
use MZ\Database\DB;
use MZ\Util\Date;
use MZ\Util\Filter;
use MZ\Util\Validator;

/**
 * Informa se há descontos nos produtos em determinados dias da semana, o
 * preço pode subir ou descer
 */
class Promocao extends SyncModel
{

    /**
     * Identificador da promoção
     */
    private $id;
    /**
     * Informa qual o produto que possui desconto ou acréscimo
     */
    private $produto_id;
    /**
     * Dia inicial em que o produto começa a sofrer alteração de preço
     */
    private $inicio;
    /**
     * Dia final em que o produto deixará de estar na promoção
     */
    private $fim;
    /**
     * Acréscimo ou desconto aplicado ao produto produto
     */
    private $valor;
    /**
     * Informa se deve proibir a venda desse produto no período informado
     */
    private $proibir;

    /**
     * Constructor for a new empty instance of Promocao
     * @param array $promocao All field and values to fill the instance
     */
    public function __construct($promocao = [])
    {
        parent::__construct($promocao);
    }

    /**
     * Identificador da promoção
     * @return mixed ID of Promocao
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param  mixed $id new value for ID
     * @return Promocao Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Informa qual o produto que possui desconto ou acréscimo
     * @return mixed Produto of Promocao
     */
    public function getProdutoID()
    {
        return $this->produto_id;
    }

    /**
     * Set ProdutoID value to new on param
     * @param  mixed $produto_id new value for ProdutoID
     * @return Promocao Self instance
     */
    public function setProdutoID($produto_id)
    {
        $this->produto_id = $produto_id;
        return $this;
    }

    /**
     * Dia inicial em que o produto começa a sofrer alteração de preço
     * @return mixed Dia inicial of Promocao
     */
    public function getInicio()
    {
        return $this->inicio;
    }

    /**
     * Set Inicio value to new on param
     * @param  mixed $inicio new value for Inicio
     * @return Promocao Self instance
     */
    public function setInicio($inicio)
    {
        $this->inicio = $inicio;
        return $this;
    }

    /**
     * Dia final em que o produto deixará de estar na promoção
     * @return mixed Dia final of Promocao
     */
    public function getFim()
    {
        return $this->fim;
    }

    /**
     * Set Fim value to new on param
     * @param  mixed $fim new value for Fim
     * @return Promocao Self instance
     */
    public function setFim($fim)
    {
        $this->fim = $fim;
        return $this;
    }

    /**
     * Acréscimo ou desconto aplicado ao produto produto
     * @return mixed Valor of Promocao
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Set Valor value to new on param
     * @param  mixed $valor new value for Valor
     * @return Promocao Self instance
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
        return $this;
    }

    /**
     * Informa se deve proibir a venda desse produto no período informado
     * @return mixed Proibir a venda of Promocao
     */
    public function getProibir()
    {
        return $this->proibir;
    }

    /**
     * Informa se deve proibir a venda desse produto no período informado
     * @return boolean Check if a of Proibir is selected or checked
     */
    public function isProibir()
    {
        return $this->proibir == 'Y';
    }

    /**
     * Set Proibir value to new on param
     * @param  mixed $proibir new value for Proibir
     * @return Promocao Self instance
     */
    public function setProibir($proibir)
    {
        $this->proibir = $proibir;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param  boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $promocao = parent::toArray($recursive);
        $promocao['id'] = $this->getID();
        $promocao['produtoid'] = $this->getProdutoID();
        $promocao['inicio'] = $this->getInicio();
        $promocao['fim'] = $this->getFim();
        $promocao['valor'] = $this->getValor();
        $promocao['proibir'] = $this->getProibir();
        return $promocao;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param  mixed $promocao Associated key -> value to assign into this instance
     * @return Promocao Self instance
     */
    public function fromArray($promocao = [])
    {
        if ($promocao instanceof Promocao) {
            $promocao = $promocao->toArray();
        } elseif (!is_array($promocao)) {
            $promocao = [];
        }
        parent::fromArray($promocao);
        if (!isset($promocao['id'])) {
            $this->setID(null);
        } else {
            $this->setID($promocao['id']);
        }
        if (!isset($promocao['produtoid'])) {
            $this->setProdutoID(null);
        } else {
            $this->setProdutoID($promocao['produtoid']);
        }
        if (!isset($promocao['inicio'])) {
            $this->setInicio(null);
        } else {
            $this->setInicio($promocao['inicio']);
        }
        if (!isset($promocao['fim'])) {
            $this->setFim(null);
        } else {
            $this->setFim($promocao['fim']);
        }
        if (!isset($promocao['valor'])) {
            $this->setValor(null);
        } else {
            $this->setValor($promocao['valor']);
        }
        if (!isset($promocao['proibir'])) {
            $this->setProibir(null);
        } else {
            $this->setProibir($promocao['proibir']);
        }
        return $this;
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $promocao = parent::publish();
        return $promocao;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param Promocao $original Original instance without modifications
     */
    public function filter($original)
    {
        $this->setID($original->getID());
        $this->setProdutoID(Filter::number($this->getProdutoID()));
        $this->setInicio(Filter::number($this->getInicio()));
        $this->setFim(Filter::number($this->getFim()));
        $this->setValor(Filter::money($this->getValor()));
    }

    /**
     * Clean instance resources like images and docs
     * @param  Promocao $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Promocao in array format
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getProdutoID())) {
            $errors['produtoid'] = 'O produto não pode ser vazio';
        }
        if (is_null($this->getInicio())) {
            $errors['inicio'] = 'O dia inicial não pode ser vazio';
        }
        if (is_null($this->getFim())) {
            $errors['fim'] = 'O dia final não pode ser vazio';
        }
        if (is_null($this->getValor())) {
            $errors['valor'] = 'O valor não pode ser vazio';
        }
        if (is_null($this->getProibir())) {
            $errors['proibir'] = 'A proibir a venda não pode ser vazia';
        }
        if (!Validator::checkBoolean($this->getProibir(), true)) {
            $errors['proibir'] = 'A proibir a venda é inválida';
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
     * Insert a new Promoção into the database and fill instance from database
     * @return Promocao Self instance
     */
    public function insert()
    {
        $this->setID(null);
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = DB::insertInto('Promocoes')->values($values)->execute();
            $this->setID($id);
            $this->loadByID();
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Promoção with instance values into database for ID
     * @param  array $only Save these fields only, when empty save all fields except id
     * @return Promocao Self instance
     */
    public function update($only = [])
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador da promoção não foi informado');
        }
        $values = DB::filterValues($values, $only, false);
        try {
            DB::update('Promocoes')
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
            throw new \Exception('O identificador da promoção não foi informado');
        }
        $result = DB::deleteFrom('Promocoes')
            ->where('id', $this->getID())
            ->execute();
        return $result;
    }

    /**
     * Load one register for it self with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order associative field name -> [-1, 1]
     * @return Promocao Self instance filled or empty
     */
    public function load($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return $this->fromArray($row);
    }

    /**
     * Load current promotion for this product
     * @return Promocao Self instance filled or empty
     */
    public function loadByProdutoID()
    {
        if ($this->getProdutoID() === null) {
            return $this->fromArray([]);
        }
        $week_offset = Date::weekOffset();
        return $this->load([
            'produtoid' => $this->getProdutoID(),
            'ate_inicio' => $week_offset,
            'apartir_fim' => $week_offset
        ]);
    }

    /**
     * Informa qual o produto que possui desconto ou acréscimo
     * @return \MZ\Product\Produto The object fetched from database
     */
    public function findProdutoID()
    {
        return \MZ\Product\Produto::findByID($this->getProdutoID());
    }

    /**
     * Get allowed keys array
     * @return array allowed keys array
     */
    private static function getAllowedKeys()
    {
        $promocao = new Promocao();
        $allowed = Filter::concatKeys('p.', $promocao->toArray());
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
        return Filter::orderBy($order, $allowed, 'p.');
    }

    /**
     * Filter condition array with allowed fields
     * @param  array $condition condition to filter rows
     * @return array allowed condition
     */
    private static function filterCondition($condition)
    {
        $allowed = self::getAllowedKeys();
        if (isset($condition['ate_inicio'])) {
            $field = 'p.inicio <= ?';
            $condition[$field] = $condition['ate_inicio'];
            $allowed[$field] = true;
            unset($condition['ate_inicio']);
        }
        if (isset($condition['apartir_fim'])) {
            $field = 'p.fim >= ?';
            $condition[$field] = $condition['apartir_fim'];
            $allowed[$field] = true;
            unset($condition['apartir_fim']);
        }
        return Filter::keys($condition, $allowed, 'p.');
    }

    /**
     * Fetch data from database with a condition
     * @param  array $condition condition to filter rows
     * @param  array $order order rows
     * @return SelectQuery query object with condition statement
     */
    private static function query($condition = [], $order = [])
    {
        $query = DB::from('Promocoes p');
        $condition = self::filterCondition($condition);
        $query = DB::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('p.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order order rows
     * @return Promocao A filled Promoção or empty instance
     */
    public static function find($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return new Promocao($row);
    }

    /**
     * Find current promotion for this product
     * @param  int $produto_id sigla to find Unidade
     * @return Promocao A filled Promoção or empty instance
     */
    public static function findByProdutoID($produto_id)
    {
        $result = new self();
        $result->setProdutoID($produto_id);
        return $result->loadByProdutoID();
    }

    /**
     * Find all Promoção
     * @param  array  $condition Condition to get all Promoção
     * @param  array  $order     Order Promoção
     * @param  int    $limit     Limit data into row count
     * @param  int    $offset    Start offset to get rows
     * @return array             List of all rows instanced as Promocao
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
            $result[] = new Promocao($row);
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
