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

use MZ\Util\Mask;
use MZ\Util\Filter;
use MZ\Util\Validator;
use MZ\Database\DB;
use MZ\Database\SyncModel;
use MZ\Exception\ValidationException;

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
     * @return int id of Produtos da lista
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param int $id Set id for Produtos da lista
     * @return self Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Lista de compra desse produto
     * @return int lista de compra of Produtos da lista
     */
    public function getListaID()
    {
        return $this->lista_id;
    }

    /**
     * Set ListaID value to new on param
     * @param int $lista_id Set lista de compra for Produtos da lista
     * @return self Self instance
     */
    public function setListaID($lista_id)
    {
        $this->lista_id = $lista_id;
        return $this;
    }

    /**
     * Produto que deve ser comprado
     * @return int produto of Produtos da lista
     */
    public function getProdutoID()
    {
        return $this->produto_id;
    }

    /**
     * Set ProdutoID value to new on param
     * @param int $produto_id Set produto for Produtos da lista
     * @return self Self instance
     */
    public function setProdutoID($produto_id)
    {
        $this->produto_id = $produto_id;
        return $this;
    }

    /**
     * Informa em qual fornecedor foi realizado a compra desse produto
     * @return int compra of Produtos da lista
     */
    public function getCompraID()
    {
        return $this->compra_id;
    }

    /**
     * Set CompraID value to new on param
     * @param int $compra_id Set compra for Produtos da lista
     * @return self Self instance
     */
    public function setCompraID($compra_id)
    {
        $this->compra_id = $compra_id;
        return $this;
    }

    /**
     * Fornecedor em que deve ser consultado ou realizado as compras dos
     * produtos, pode ser alterado no momento da compra
     * @return int fornecedor of Produtos da lista
     */
    public function getFornecedorID()
    {
        return $this->fornecedor_id;
    }

    /**
     * Set FornecedorID value to new on param
     * @param int $fornecedor_id Set fornecedor for Produtos da lista
     * @return self Self instance
     */
    public function setFornecedorID($fornecedor_id)
    {
        $this->fornecedor_id = $fornecedor_id;
        return $this;
    }

    /**
     * Quantidade de produtos que deve ser comprado
     * @return float quantidade of Produtos da lista
     */
    public function getQuantidade()
    {
        return $this->quantidade;
    }

    /**
     * Set Quantidade value to new on param
     * @param float $quantidade Set quantidade for Produtos da lista
     * @return self Self instance
     */
    public function setQuantidade($quantidade)
    {
        $this->quantidade = $quantidade;
        return $this;
    }

    /**
     * Informa quantos produtos já foram comprados
     * @return float comprado of Produtos da lista
     */
    public function getComprado()
    {
        return $this->comprado;
    }

    /**
     * Set Comprado value to new on param
     * @param float $comprado Set comprado for Produtos da lista
     * @return self Self instance
     */
    public function setComprado($comprado)
    {
        $this->comprado = $comprado;
        return $this;
    }

    /**
     * Preço máximo que deve ser pago na compra desse produto
     * @return string preço máximo of Produtos da lista
     */
    public function getPrecoMaximo()
    {
        return $this->preco_maximo;
    }

    /**
     * Set PrecoMaximo value to new on param
     * @param string $preco_maximo Set preço máximo for Produtos da lista
     * @return self Self instance
     */
    public function setPrecoMaximo($preco_maximo)
    {
        $this->preco_maximo = $preco_maximo;
        return $this;
    }

    /**
     * Preço em que o produto foi comprado da última vez ou o novo preço
     * @return string preço of Produtos da lista
     */
    public function getPreco()
    {
        return $this->preco;
    }

    /**
     * Set Preco value to new on param
     * @param string $preco Set preço for Produtos da lista
     * @return self Self instance
     */
    public function setPreco($preco)
    {
        $this->preco = $preco;
        return $this;
    }

    /**
     * Detalhes na compra desse produto
     * @return string observações of Produtos da lista
     */
    public function getObservacoes()
    {
        return $this->observacoes;
    }

    /**
     * Set Observacoes value to new on param
     * @param string $observacoes Set observações for Produtos da lista
     * @return self Self instance
     */
    public function setObservacoes($observacoes)
    {
        $this->observacoes = $observacoes;
        return $this;
    }

    /**
     * Informa o momento do recolhimento da mercadoria na pratileira
     * @return string data de recolhimento of Produtos da lista
     */
    public function getDataRecolhimento()
    {
        return $this->data_recolhimento;
    }

    /**
     * Set DataRecolhimento value to new on param
     * @param string $data_recolhimento Set data de recolhimento for Produtos da lista
     * @return self Self instance
     */
    public function setDataRecolhimento($data_recolhimento)
    {
        $this->data_recolhimento = $data_recolhimento;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param boolean $recursive Allow rescursive conversion of fields
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
     * @param mixed $requisito Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($requisito = [])
    {
        if ($requisito instanceof self) {
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
     * @param \MZ\Provider\Prestador $requester user that request to view this fields
     * @return array All public field and values into array format
     */
    public function publish($requester)
    {
        $requisito = parent::publish($requester);
        return $requisito;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param self $original Original instance without modifications
     * @param \MZ\Provider\Prestador $updater user that want to update this object
     * @param boolean $localized Informs if fields are localized
     * @return self Self instance
     */
    public function filter($original, $updater, $localized = false)
    {
        $this->setID($original->getID());
        $this->setListaID(Filter::number($this->getListaID()));
        $this->setProdutoID(Filter::number($this->getProdutoID()));
        $this->setCompraID(Filter::number($this->getCompraID()));
        $this->setFornecedorID(Filter::number($this->getFornecedorID()));
        $this->setQuantidade(Filter::float($this->getQuantidade(), $localized));
        $this->setComprado(Filter::float($this->getComprado(), $localized));
        $this->setPrecoMaximo(Filter::money($this->getPrecoMaximo(), $localized));
        $this->setPreco(Filter::money($this->getPreco(), $localized));
        $this->setObservacoes(Filter::string($this->getObservacoes()));
        $this->setDataRecolhimento(Filter::datetime($this->getDataRecolhimento()));
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
     * @return array All field of Requisito in array format
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getListaID())) {
            $errors['listaid'] = _t('requisito.lista_id_cannot_empty');
        }
        if (is_null($this->getProdutoID())) {
            $errors['produtoid'] = _t('requisito.produto_id_cannot_empty');
        }
        if (is_null($this->getQuantidade())) {
            $errors['quantidade'] = _t('requisito.quantidade_cannot_empty');
        }
        if (is_null($this->getComprado())) {
            $errors['comprado'] = _t('requisito.comprado_cannot_empty');
        }
        if (is_null($this->getPrecoMaximo())) {
            $errors['precomaximo'] = _t('requisito.preco_maximo_cannot_empty');
        }
        if (is_null($this->getPreco())) {
            $errors['preco'] = _t('requisito.preco_cannot_empty');
        }
        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
        return $this->toArray();
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
     * Filter condition array with allowed fields
     * @param array $condition condition to filter rows
     * @return array allowed condition
     */
    protected function filterCondition($condition)
    {
        $allowed = $this->getAllowedKeys();
        return Filter::keys($condition, $allowed, 'r.');
    }

    /**
     * Fetch data from database with a condition
     * @param array $condition condition to filter rows
     * @param array $order order rows
     * @return SelectQuery query object with condition statement
     */
    protected function query($condition = [], $order = [])
    {
        $query = DB::from('Requisitos r');
        $condition = $this->filterCondition($condition);
        $query = DB::buildOrderBy($query, $this->filterOrder($order));
        $query = $query->orderBy('r.id ASC');
        return DB::buildCondition($query, $condition);
    }
}
