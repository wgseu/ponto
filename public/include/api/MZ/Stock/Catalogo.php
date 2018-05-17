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
namespace MZ\Stock;

use MZ\Database\Model;
use MZ\Database\DB;
use MZ\Util\Filter;
use MZ\Util\Validator;

/**
 * Informa a lista de produtos disponíveis nos fornecedores
 */
class Catalogo extends Model
{

    /**
     * Identificador do catálogo
     */
    private $id;
    /**
     * Produto consultado
     */
    private $produto_id;
    /**
     * Fornecedor que possui o produto à venda
     */
    private $fornecedor_id;
    /**
     * Preço a qual o produto foi comprado da última vez
     */
    private $preco_compra;
    /**
     * Preço de venda do produto pelo fornecedor na última consulta
     */
    private $preco_venda;
    /**
     * Quantidade mínima que o fornecedor vende
     */
    private $quantidade_minima;
    /**
     * Quantidade em estoque do produto no fornecedor
     */
    private $estoque;
    /**
     * Informa se a quantidade de estoque é limitada
     */
    private $limitado;
    /**
     * Última data de consulta do preço do produto
     */
    private $data_consulta;

    /**
     * Constructor for a new empty instance of Catalogo
     * @param array $catalogo All field and values to fill the instance
     */
    public function __construct($catalogo = [])
    {
        parent::__construct($catalogo);
    }

    /**
     * Identificador do catálogo
     * @return mixed ID of Catalogo
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param  mixed $id new value for ID
     * @return Catalogo Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Produto consultado
     * @return mixed Produto of Catalogo
     */
    public function getProdutoID()
    {
        return $this->produto_id;
    }

    /**
     * Set ProdutoID value to new on param
     * @param  mixed $produto_id new value for ProdutoID
     * @return Catalogo Self instance
     */
    public function setProdutoID($produto_id)
    {
        $this->produto_id = $produto_id;
        return $this;
    }

    /**
     * Fornecedor que possui o produto à venda
     * @return mixed Fornecedor of Catalogo
     */
    public function getFornecedorID()
    {
        return $this->fornecedor_id;
    }

    /**
     * Set FornecedorID value to new on param
     * @param  mixed $fornecedor_id new value for FornecedorID
     * @return Catalogo Self instance
     */
    public function setFornecedorID($fornecedor_id)
    {
        $this->fornecedor_id = $fornecedor_id;
        return $this;
    }

    /**
     * Preço a qual o produto foi comprado da última vez
     * @return mixed Preço de compra of Catalogo
     */
    public function getPrecoCompra()
    {
        return $this->preco_compra;
    }

    /**
     * Set PrecoCompra value to new on param
     * @param  mixed $preco_compra new value for PrecoCompra
     * @return Catalogo Self instance
     */
    public function setPrecoCompra($preco_compra)
    {
        $this->preco_compra = $preco_compra;
        return $this;
    }

    /**
     * Preço de venda do produto pelo fornecedor na última consulta
     * @return mixed Preço de venda of Catalogo
     */
    public function getPrecoVenda()
    {
        return $this->preco_venda;
    }

    /**
     * Set PrecoVenda value to new on param
     * @param  mixed $preco_venda new value for PrecoVenda
     * @return Catalogo Self instance
     */
    public function setPrecoVenda($preco_venda)
    {
        $this->preco_venda = $preco_venda;
        return $this;
    }

    /**
     * Quantidade mínima que o fornecedor vende
     * @return mixed Quantidade mínima of Catalogo
     */
    public function getQuantidadeMinima()
    {
        return $this->quantidade_minima;
    }

    /**
     * Set QuantidadeMinima value to new on param
     * @param  mixed $quantidade_minima new value for QuantidadeMinima
     * @return Catalogo Self instance
     */
    public function setQuantidadeMinima($quantidade_minima)
    {
        $this->quantidade_minima = $quantidade_minima;
        return $this;
    }

    /**
     * Quantidade em estoque do produto no fornecedor
     * @return mixed Estoque of Catalogo
     */
    public function getEstoque()
    {
        return $this->estoque;
    }

    /**
     * Set Estoque value to new on param
     * @param  mixed $estoque new value for Estoque
     * @return Catalogo Self instance
     */
    public function setEstoque($estoque)
    {
        $this->estoque = $estoque;
        return $this;
    }

    /**
     * Informa se a quantidade de estoque é limitada
     * @return mixed Limitado of Catalogo
     */
    public function getLimitado()
    {
        return $this->limitado;
    }

    /**
     * Informa se a quantidade de estoque é limitada
     * @return boolean Check if o of Limitado is selected or checked
     */
    public function isLimitado()
    {
        return $this->limitado == 'Y';
    }

    /**
     * Set Limitado value to new on param
     * @param  mixed $limitado new value for Limitado
     * @return Catalogo Self instance
     */
    public function setLimitado($limitado)
    {
        $this->limitado = $limitado;
        return $this;
    }

    /**
     * Última data de consulta do preço do produto
     * @return mixed Data de consulta of Catalogo
     */
    public function getDataConsulta()
    {
        return $this->data_consulta;
    }

    /**
     * Set DataConsulta value to new on param
     * @param  mixed $data_consulta new value for DataConsulta
     * @return Catalogo Self instance
     */
    public function setDataConsulta($data_consulta)
    {
        $this->data_consulta = $data_consulta;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param  boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $catalogo = parent::toArray($recursive);
        $catalogo['id'] = $this->getID();
        $catalogo['produtoid'] = $this->getProdutoID();
        $catalogo['fornecedorid'] = $this->getFornecedorID();
        $catalogo['precocompra'] = $this->getPrecoCompra();
        $catalogo['precovenda'] = $this->getPrecoVenda();
        $catalogo['quantidademinima'] = $this->getQuantidadeMinima();
        $catalogo['estoque'] = $this->getEstoque();
        $catalogo['limitado'] = $this->getLimitado();
        $catalogo['dataconsulta'] = $this->getDataConsulta();
        return $catalogo;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param  mixed $catalogo Associated key -> value to assign into this instance
     * @return Catalogo Self instance
     */
    public function fromArray($catalogo = [])
    {
        if ($catalogo instanceof Catalogo) {
            $catalogo = $catalogo->toArray();
        } elseif (!is_array($catalogo)) {
            $catalogo = [];
        }
        parent::fromArray($catalogo);
        if (!isset($catalogo['id'])) {
            $this->setID(null);
        } else {
            $this->setID($catalogo['id']);
        }
        if (!isset($catalogo['produtoid'])) {
            $this->setProdutoID(null);
        } else {
            $this->setProdutoID($catalogo['produtoid']);
        }
        if (!isset($catalogo['fornecedorid'])) {
            $this->setFornecedorID(null);
        } else {
            $this->setFornecedorID($catalogo['fornecedorid']);
        }
        if (!isset($catalogo['precocompra'])) {
            $this->setPrecoCompra(null);
        } else {
            $this->setPrecoCompra($catalogo['precocompra']);
        }
        if (!isset($catalogo['precovenda'])) {
            $this->setPrecoVenda(null);
        } else {
            $this->setPrecoVenda($catalogo['precovenda']);
        }
        if (!isset($catalogo['quantidademinima'])) {
            $this->setQuantidadeMinima(null);
        } else {
            $this->setQuantidadeMinima($catalogo['quantidademinima']);
        }
        if (!isset($catalogo['estoque'])) {
            $this->setEstoque(null);
        } else {
            $this->setEstoque($catalogo['estoque']);
        }
        if (!isset($catalogo['limitado'])) {
            $this->setLimitado(null);
        } else {
            $this->setLimitado($catalogo['limitado']);
        }
        if (!array_key_exists('dataconsulta', $catalogo)) {
            $this->setDataConsulta(null);
        } else {
            $this->setDataConsulta($catalogo['dataconsulta']);
        }
        return $this;
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $catalogo = parent::publish();
        return $catalogo;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param Catalogo $original Original instance without modifications
     */
    public function filter($original)
    {
        $this->setID($original->getID());
        $this->setProdutoID(Filter::number($this->getProdutoID()));
        $this->setFornecedorID(Filter::number($this->getFornecedorID()));
        $this->setPrecoCompra(Filter::money($this->getPrecoCompra()));
        $this->setPrecoVenda(Filter::money($this->getPrecoVenda()));
        $this->setQuantidadeMinima(Filter::float($this->getQuantidadeMinima()));
        $this->setEstoque(Filter::float($this->getEstoque()));
        $this->setDataConsulta(Filter::datetime($this->getDataConsulta()));
    }

    /**
     * Clean instance resources like images and docs
     * @param  Catalogo $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Catalogo in array format
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getProdutoID())) {
            $errors['produtoid'] = 'O produto não pode ser vazio';
        }
        if (is_null($this->getFornecedorID())) {
            $errors['fornecedorid'] = 'O fornecedor não pode ser vazio';
        }
        if (is_null($this->getPrecoCompra())) {
            $errors['precocompra'] = 'O preço de compra não pode ser vazio';
        }
        if (is_null($this->getPrecoVenda())) {
            $errors['precovenda'] = 'O preço de venda não pode ser vazio';
        }
        if (is_null($this->getQuantidadeMinima())) {
            $errors['quantidademinima'] = 'A quantidade mínima não pode ser vazia';
        }
        if (is_null($this->getEstoque())) {
            $errors['estoque'] = 'O estoque não pode ser vazio';
        }
        if (is_null($this->getLimitado())) {
            $errors['limitado'] = 'O limitado não pode ser vazio';
        }
        if (!Validator::checkBoolean($this->getLimitado(), true)) {
            $errors['limitado'] = 'O limitado é inválido';
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
     * Insert a new Catálogo de produtos into the database and fill instance from database
     * @return Catalogo Self instance
     */
    public function insert()
    {
        $this->setID(null);
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = DB::insertInto('Catalogos')->values($values)->execute();
            $this->loadByID($id);
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Catálogo de produtos with instance values into database for ID
     * @return Catalogo Self instance
     */
    public function update($only = [], $except = false)
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador do catálogo de produtos não foi informado');
        }
        $values = DB::filterValues($values, $only, $except);
        try {
            DB::update('Catalogos')
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
            throw new \Exception('O identificador do catálogo de produtos não foi informado');
        }
        $result = DB::deleteFrom('Catalogos')
            ->where('id', $this->getID())
            ->execute();
        return $result;
    }

    /**
     * Load one register for it self with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order associative field name -> [-1, 1]
     * @return Catalogo Self instance filled or empty
     */
    public function load($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return $this->fromArray($row);
    }

    /**
     * Produto consultado
     * @return \MZ\Product\Produto The object fetched from database
     */
    public function findProdutoID()
    {
        return \MZ\Product\Produto::findByID($this->getProdutoID());
    }

    /**
     * Fornecedor que possui o produto à venda
     * @return \MZ\Stock\Fornecedor The object fetched from database
     */
    public function findFornecedorID()
    {
        return \MZ\Stock\Fornecedor::findByID($this->getFornecedorID());
    }

    /**
     * Get allowed keys array
     * @return array allowed keys array
     */
    private static function getAllowedKeys()
    {
        $catalogo = new Catalogo();
        $allowed = Filter::concatKeys('c.', $catalogo->toArray());
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
        $query = DB::from('Catalogos c');
        $condition = self::filterCondition($condition);
        $query = DB::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('c.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order order rows
     * @return Catalogo A filled Catálogo de produtos or empty instance
     */
    public static function find($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return new Catalogo($row);
    }

    /**
     * Find this object on database using, ID
     * @param  int $id id to find Catálogo de produtos
     * @return Catalogo A filled instance or empty when not found
     */
    public static function findByID($id)
    {
        $result = new self();
        return $result->loadByID($id);
    }

    /**
     * Find all Catálogo de produtos
     * @param  array  $condition Condition to get all Catálogo de produtos
     * @param  array  $order     Order Catálogo de produtos
     * @param  int    $limit     Limit data into row count
     * @param  int    $offset    Start offset to get rows
     * @return array             List of all rows instanced as Catalogo
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
            $result[] = new Catalogo($row);
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
