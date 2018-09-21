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

use MZ\Database\SyncModel;
use MZ\Database\DB;
use MZ\Util\Filter;
use MZ\Util\Validator;

/**
 * Informa os produtos da lista de compras
 */
class Requisito extends SyncModel
{

    /**
     * Identificador do produto da lista
     */
    private $id;
    /**
     * Lista de compra desse produto
     */
    private $lista_id;
    /**
     * Produto que deve ser comprado
     */
    private $produto_id;
    /**
     * Informa em qual fornecedor foi realizado a compra desse produto
     */
    private $compra_id;
    /**
     * Fornecedor em que deve ser consultado ou realizado as compras dos
     * produtos, pode ser alterado no momento da compra
     */
    private $fornecedor_id;
    /**
     * Quantidade de produtos que deve ser comprado
     */
    private $quantidade;
    /**
     * Informa quantos produtos já foram comprados
     */
    private $comprado;
    /**
     * Preço máximo que deve ser pago na compra desse produto
     */
    private $preco_maximo;
    /**
     * Preço em que o produto foi comprado da última vez ou o novo preço
     */
    private $preco;
    /**
     * Detalhes na compra desse produto
     */
    private $observacoes;
    /**
     * Informa o momento do recolhimento da mercadoria na pratileira
     */
    private $data_recolhimento;

    /**
     * Constructor for a new empty instance of Requisito
     * @param array $requisito All field and values to fill the instance
     */
    public function __construct($requisito = [])
    {
        parent::__construct($requisito);
    }

    /**
     * Identificador do produto da lista
     * @return mixed ID of Requisito
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param  mixed $id new value for ID
     * @return Requisito Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Lista de compra desse produto
     * @return mixed Lista de compra of Requisito
     */
    public function getListaID()
    {
        return $this->lista_id;
    }

    /**
     * Set ListaID value to new on param
     * @param  mixed $lista_id new value for ListaID
     * @return Requisito Self instance
     */
    public function setListaID($lista_id)
    {
        $this->lista_id = $lista_id;
        return $this;
    }

    /**
     * Produto que deve ser comprado
     * @return mixed Produto of Requisito
     */
    public function getProdutoID()
    {
        return $this->produto_id;
    }

    /**
     * Set ProdutoID value to new on param
     * @param  mixed $produto_id new value for ProdutoID
     * @return Requisito Self instance
     */
    public function setProdutoID($produto_id)
    {
        $this->produto_id = $produto_id;
        return $this;
    }

    /**
     * Informa em qual fornecedor foi realizado a compra desse produto
     * @return mixed Compra of Requisito
     */
    public function getCompraID()
    {
        return $this->compra_id;
    }

    /**
     * Set CompraID value to new on param
     * @param  mixed $compra_id new value for CompraID
     * @return Requisito Self instance
     */
    public function setCompraID($compra_id)
    {
        $this->compra_id = $compra_id;
        return $this;
    }

    /**
     * Fornecedor em que deve ser consultado ou realizado as compras dos
     * produtos, pode ser alterado no momento da compra
     * @return mixed Fornecedor of Requisito
     */
    public function getFornecedorID()
    {
        return $this->fornecedor_id;
    }

    /**
     * Set FornecedorID value to new on param
     * @param  mixed $fornecedor_id new value for FornecedorID
     * @return Requisito Self instance
     */
    public function setFornecedorID($fornecedor_id)
    {
        $this->fornecedor_id = $fornecedor_id;
        return $this;
    }

    /**
     * Quantidade de produtos que deve ser comprado
     * @return mixed Quantidade of Requisito
     */
    public function getQuantidade()
    {
        return $this->quantidade;
    }

    /**
     * Set Quantidade value to new on param
     * @param  mixed $quantidade new value for Quantidade
     * @return Requisito Self instance
     */
    public function setQuantidade($quantidade)
    {
        $this->quantidade = $quantidade;
        return $this;
    }

    /**
     * Informa quantos produtos já foram comprados
     * @return mixed Comprado of Requisito
     */
    public function getComprado()
    {
        return $this->comprado;
    }

    /**
     * Set Comprado value to new on param
     * @param  mixed $comprado new value for Comprado
     * @return Requisito Self instance
     */
    public function setComprado($comprado)
    {
        $this->comprado = $comprado;
        return $this;
    }

    /**
     * Preço máximo que deve ser pago na compra desse produto
     * @return mixed Preço máximo of Requisito
     */
    public function getPrecoMaximo()
    {
        return $this->preco_maximo;
    }

    /**
     * Set PrecoMaximo value to new on param
     * @param  mixed $preco_maximo new value for PrecoMaximo
     * @return Requisito Self instance
     */
    public function setPrecoMaximo($preco_maximo)
    {
        $this->preco_maximo = $preco_maximo;
        return $this;
    }

    /**
     * Preço em que o produto foi comprado da última vez ou o novo preço
     * @return mixed Preço of Requisito
     */
    public function getPreco()
    {
        return $this->preco;
    }

    /**
     * Set Preco value to new on param
     * @param  mixed $preco new value for Preco
     * @return Requisito Self instance
     */
    public function setPreco($preco)
    {
        $this->preco = $preco;
        return $this;
    }

    /**
     * Detalhes na compra desse produto
     * @return mixed Observações of Requisito
     */
    public function getObservacoes()
    {
        return $this->observacoes;
    }

    /**
     * Set Observacoes value to new on param
     * @param  mixed $observacoes new value for Observacoes
     * @return Requisito Self instance
     */
    public function setObservacoes($observacoes)
    {
        $this->observacoes = $observacoes;
        return $this;
    }

    /**
     * Informa o momento do recolhimento da mercadoria na pratileira
     * @return mixed Data de recolhimento of Requisito
     */
    public function getDataRecolhimento()
    {
        return $this->data_recolhimento;
    }

    /**
     * Set DataRecolhimento value to new on param
     * @param  mixed $data_recolhimento new value for DataRecolhimento
     * @return Requisito Self instance
     */
    public function setDataRecolhimento($data_recolhimento)
    {
        $this->data_recolhimento = $data_recolhimento;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param  boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $requisito = parent::toArray($recursive);
        $requisito['id'] = $this->getID();
        $requisito['listaid'] = $this->getListaID();
        $requisito['produtoid'] = $this->getProdutoID();
        $requisito['compraid'] = $this->getCompraID();
        $requisito['fornecedorid'] = $this->getFornecedorID();
        $requisito['quantidade'] = $this->getQuantidade();
        $requisito['comprado'] = $this->getComprado();
        $requisito['precomaximo'] = $this->getPrecoMaximo();
        $requisito['preco'] = $this->getPreco();
        $requisito['observacoes'] = $this->getObservacoes();
        $requisito['datarecolhimento'] = $this->getDataRecolhimento();
        return $requisito;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param  mixed $requisito Associated key -> value to assign into this instance
     * @return Requisito Self instance
     */
    public function fromArray($requisito = [])
    {
        if ($requisito instanceof Requisito) {
            $requisito = $requisito->toArray();
        } elseif (!is_array($requisito)) {
            $requisito = [];
        }
        parent::fromArray($requisito);
        if (!isset($requisito['id'])) {
            $this->setID(null);
        } else {
            $this->setID($requisito['id']);
        }
        if (!isset($requisito['listaid'])) {
            $this->setListaID(null);
        } else {
            $this->setListaID($requisito['listaid']);
        }
        if (!isset($requisito['produtoid'])) {
            $this->setProdutoID(null);
        } else {
            $this->setProdutoID($requisito['produtoid']);
        }
        if (!array_key_exists('compraid', $requisito)) {
            $this->setCompraID(null);
        } else {
            $this->setCompraID($requisito['compraid']);
        }
        if (!array_key_exists('fornecedorid', $requisito)) {
            $this->setFornecedorID(null);
        } else {
            $this->setFornecedorID($requisito['fornecedorid']);
        }
        if (!isset($requisito['quantidade'])) {
            $this->setQuantidade(null);
        } else {
            $this->setQuantidade($requisito['quantidade']);
        }
        if (!isset($requisito['comprado'])) {
            $this->setComprado(null);
        } else {
            $this->setComprado($requisito['comprado']);
        }
        if (!isset($requisito['precomaximo'])) {
            $this->setPrecoMaximo(null);
        } else {
            $this->setPrecoMaximo($requisito['precomaximo']);
        }
        if (!isset($requisito['preco'])) {
            $this->setPreco(null);
        } else {
            $this->setPreco($requisito['preco']);
        }
        if (!array_key_exists('observacoes', $requisito)) {
            $this->setObservacoes(null);
        } else {
            $this->setObservacoes($requisito['observacoes']);
        }
        if (!array_key_exists('datarecolhimento', $requisito)) {
            $this->setDataRecolhimento(null);
        } else {
            $this->setDataRecolhimento($requisito['datarecolhimento']);
        }
        return $this;
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $requisito = parent::publish();
        return $requisito;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param Requisito $original Original instance without modifications
     */
    public function filter($original)
    {
        $this->setID($original->getID());
        $this->setListaID(Filter::number($this->getListaID()));
        $this->setProdutoID(Filter::number($this->getProdutoID()));
        $this->setCompraID(Filter::number($this->getCompraID()));
        $this->setFornecedorID(Filter::number($this->getFornecedorID()));
        $this->setQuantidade(Filter::float($this->getQuantidade()));
        $this->setComprado(Filter::float($this->getComprado()));
        $this->setPrecoMaximo(Filter::money($this->getPrecoMaximo()));
        $this->setPreco(Filter::money($this->getPreco()));
        $this->setObservacoes(Filter::string($this->getObservacoes()));
        $this->setDataRecolhimento(Filter::datetime($this->getDataRecolhimento()));
    }

    /**
     * Clean instance resources like images and docs
     * @param  Requisito $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Requisito in array format
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getListaID())) {
            $errors['listaid'] = 'A lista de compra não pode ser vazia';
        }
        if (is_null($this->getProdutoID())) {
            $errors['produtoid'] = 'O produto não pode ser vazio';
        }
        if (is_null($this->getQuantidade())) {
            $errors['quantidade'] = 'A quantidade não pode ser vazia';
        }
        if (is_null($this->getComprado())) {
            $errors['comprado'] = 'O comprado não pode ser vazio';
        }
        if (is_null($this->getPrecoMaximo())) {
            $errors['precomaximo'] = 'O preço máximo não pode ser vazio';
        }
        if (is_null($this->getPreco())) {
            $errors['preco'] = 'O preço não pode ser vazio';
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
     * Insert a new Produtos da lista into the database and fill instance from database
     * @return Requisito Self instance
     */
    public function insert()
    {
        $this->setID(null);
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = DB::insertInto('Requisitos')->values($values)->execute();
            $this->setID($id);
            $this->loadByID();
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Produtos da lista with instance values into database for ID
     * @return Requisito Self instance
     */
    public function update($only = [])
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador do produtos da lista não foi informado');
        }
        $values = DB::filterValues($values, $only, false);
        try {
            DB::update('Requisitos')
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
            throw new \Exception('O identificador do produtos da lista não foi informado');
        }
        $result = DB::deleteFrom('Requisitos')
            ->where('id', $this->getID())
            ->execute();
        return $result;
    }

    /**
     * Load one register for it self with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order associative field name -> [-1, 1]
     * @return Requisito Self instance filled or empty
     */
    public function load($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return $this->fromArray($row);
    }

    /**
     * Lista de compra desse produto
     * @return \MZ\Stock\Lista The object fetched from database
     */
    public function findListaID()
    {
        return \MZ\Stock\Lista::findByID($this->getListaID());
    }

    /**
     * Produto que deve ser comprado
     * @return \MZ\Product\Produto The object fetched from database
     */
    public function findProdutoID()
    {
        return \MZ\Product\Produto::findByID($this->getProdutoID());
    }

    /**
     * Informa em qual fornecedor foi realizado a compra desse produto
     * @return \MZ\Stock\Compra The object fetched from database
     */
    public function findCompraID()
    {
        if (is_null($this->getCompraID())) {
            return new \MZ\Stock\Compra();
        }
        return \MZ\Stock\Compra::findByID($this->getCompraID());
    }

    /**
     * Fornecedor em que deve ser consultado ou realizado as compras dos
     * produtos, pode ser alterado no momento da compra
     * @return \MZ\Stock\Fornecedor The object fetched from database
     */
    public function findFornecedorID()
    {
        if (is_null($this->getFornecedorID())) {
            return new \MZ\Stock\Fornecedor();
        }
        return \MZ\Stock\Fornecedor::findByID($this->getFornecedorID());
    }

    /**
     * Get allowed keys array
     * @return array allowed keys array
     */
    private static function getAllowedKeys()
    {
        $requisito = new Requisito();
        $allowed = Filter::concatKeys('r.', $requisito->toArray());
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
        return Filter::orderBy($order, $allowed, 'r.');
    }

    /**
     * Filter condition array with allowed fields
     * @param  array $condition condition to filter rows
     * @return array allowed condition
     */
    private static function filterCondition($condition)
    {
        $allowed = self::getAllowedKeys();
        return Filter::keys($condition, $allowed, 'r.');
    }

    /**
     * Fetch data from database with a condition
     * @param  array $condition condition to filter rows
     * @param  array $order order rows
     * @return SelectQuery query object with condition statement
     */
    private static function query($condition = [], $order = [])
    {
        $query = DB::from('Requisitos r');
        $condition = self::filterCondition($condition);
        $query = DB::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('r.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order order rows
     * @return Requisito A filled Produtos da lista or empty instance
     */
    public static function find($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return new Requisito($row);
    }

    /**
     * Find all Produtos da lista
     * @param  array  $condition Condition to get all Produtos da lista
     * @param  array  $order     Order Produtos da lista
     * @param  int    $limit     Limit data into row count
     * @param  int    $offset    Start offset to get rows
     * @return array             List of all rows instanced as Requisito
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
            $result[] = new Requisito($row);
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
