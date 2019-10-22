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
 * Informa a lista de produtos disponíveis nos fornecedores
 */
class Catalogo extends SyncModel
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
     * Informa o conteúdo do produto como é comprado, Ex.: 5UN no mesmo pacote
     */
    private $conteudo;
    /**
     * Última data de consulta do preço do produto
     */
    private $data_consulta;
    /**
     * Data em que o produto deixou de ser vendido pelo fornecedor
     */
    private $data_abandono;

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
     * @return int id of Catálogo de produtos
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param int $id Set id for Catálogo de produtos
     * @return self Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Produto consultado
     * @return int produto of Catálogo de produtos
     */
    public function getProdutoID()
    {
        return $this->produto_id;
    }

    /**
     * Set ProdutoID value to new on param
     * @param int $produto_id Set produto for Catálogo de produtos
     * @return self Self instance
     */
    public function setProdutoID($produto_id)
    {
        $this->produto_id = $produto_id;
        return $this;
    }

    /**
     * Fornecedor que possui o produto à venda
     * @return int fornecedor of Catálogo de produtos
     */
    public function getFornecedorID()
    {
        return $this->fornecedor_id;
    }

    /**
     * Set FornecedorID value to new on param
     * @param int $fornecedor_id Set fornecedor for Catálogo de produtos
     * @return self Self instance
     */
    public function setFornecedorID($fornecedor_id)
    {
        $this->fornecedor_id = $fornecedor_id;
        return $this;
    }

    /**
     * Preço a qual o produto foi comprado da última vez
     * @return string preço de compra of Catálogo de produtos
     */
    public function getPrecoCompra()
    {
        return $this->preco_compra;
    }

    /**
     * Set PrecoCompra value to new on param
     * @param string $preco_compra Set preço de compra for Catálogo de produtos
     * @return self Self instance
     */
    public function setPrecoCompra($preco_compra)
    {
        $this->preco_compra = $preco_compra;
        return $this;
    }

    /**
     * Preço de venda do produto pelo fornecedor na última consulta
     * @return string preço de venda of Catálogo de produtos
     */
    public function getPrecoVenda()
    {
        return $this->preco_venda;
    }

    /**
     * Set PrecoVenda value to new on param
     * @param string $preco_venda Set preço de venda for Catálogo de produtos
     * @return self Self instance
     */
    public function setPrecoVenda($preco_venda)
    {
        $this->preco_venda = $preco_venda;
        return $this;
    }

    /**
     * Quantidade mínima que o fornecedor vende
     * @return float quantidade mínima of Catálogo de produtos
     */
    public function getQuantidadeMinima()
    {
        return $this->quantidade_minima;
    }

    /**
     * Set QuantidadeMinima value to new on param
     * @param float $quantidade_minima Set quantidade mínima for Catálogo de produtos
     * @return self Self instance
     */
    public function setQuantidadeMinima($quantidade_minima)
    {
        $this->quantidade_minima = $quantidade_minima;
        return $this;
    }

    /**
     * Quantidade em estoque do produto no fornecedor
     * @return float estoque of Catálogo de produtos
     */
    public function getEstoque()
    {
        return $this->estoque;
    }

    /**
     * Set Estoque value to new on param
     * @param float $estoque Set estoque for Catálogo de produtos
     * @return self Self instance
     */
    public function setEstoque($estoque)
    {
        $this->estoque = $estoque;
        return $this;
    }

    /**
     * Informa se a quantidade de estoque é limitada
     * @return string limitado of Catálogo de produtos
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
     * @param string $limitado Set limitado for Catálogo de produtos
     * @return self Self instance
     */
    public function setLimitado($limitado)
    {
        $this->limitado = $limitado;
        return $this;
    }

    /**
     * Informa o conteúdo do produto como é comprado, Ex.: 5UN no mesmo pacote
     * @return float conteúdo of Catálogo de produtos
     */
    public function getConteudo()
    {
        return $this->conteudo;
    }

    /**
     * Set Conteudo value to new on param
     * @param float $conteudo Set conteúdo for Catálogo de produtos
     * @return self Self instance
     */
    public function setConteudo($conteudo)
    {
        $this->conteudo = $conteudo;
        return $this;
    }

    /**
     * Última data de consulta do preço do produto
     * @return string data de consulta of Catálogo de produtos
     */
    public function getDataConsulta()
    {
        return $this->data_consulta;
    }

    /**
     * Set DataConsulta value to new on param
     * @param string $data_consulta Set data de consulta for Catálogo de produtos
     * @return self Self instance
     */
    public function setDataConsulta($data_consulta)
    {
        $this->data_consulta = $data_consulta;
        return $this;
    }

    /**
     * Data em que o produto deixou de ser vendido pelo fornecedor
     * @return string data de abandono of Catálogo de produtos
     */
    public function getDataAbandono()
    {
        return $this->data_abandono;
    }

    /**
     * Set DataAbandono value to new on param
     * @param string $data_abandono Set data de abandono for Catálogo de produtos
     * @return self Self instance
     */
    public function setDataAbandono($data_abandono)
    {
        $this->data_abandono = $data_abandono;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param boolean $recursive Allow rescursive conversion of fields
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
        $catalogo['conteudo'] = $this->getConteudo();
        $catalogo['dataconsulta'] = $this->getDataConsulta();
        $catalogo['dataabandono'] = $this->getDataAbandono();
        return $catalogo;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param mixed $catalogo Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($catalogo = [])
    {
        if ($catalogo instanceof self) {
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
            $this->setLimitado('N');
        } else {
            $this->setLimitado($catalogo['limitado']);
        }
        if (!isset($catalogo['conteudo'])) {
            $this->setConteudo(null);
        } else {
            $this->setConteudo($catalogo['conteudo']);
        }
        if (!array_key_exists('dataconsulta', $catalogo)) {
            $this->setDataConsulta(null);
        } else {
            $this->setDataConsulta($catalogo['dataconsulta']);
        }
        if (!array_key_exists('dataabandono', $catalogo)) {
            $this->setDataAbandono(null);
        } else {
            $this->setDataAbandono($catalogo['dataabandono']);
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
        $catalogo = parent::publish($requester);
        return $catalogo;
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
        $this->setProdutoID(Filter::number($this->getProdutoID()));
        $this->setFornecedorID(Filter::number($this->getFornecedorID()));
        $this->setPrecoCompra(Filter::money($this->getPrecoCompra(), $localized));
        $this->setPrecoVenda(Filter::money($this->getPrecoVenda(), $localized));
        $this->setQuantidadeMinima(Filter::float($this->getQuantidadeMinima(), $localized));
        $this->setEstoque(Filter::float($this->getEstoque(), $localized));
        $this->setConteudo(Filter::float($this->getConteudo(), $localized));
        $this->setDataConsulta(Filter::datetime($this->getDataConsulta()));
        $this->setDataAbandono(Filter::datetime($this->getDataAbandono()));
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
     * @return array All field of Catalogo in array format
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getProdutoID())) {
            $errors['produtoid'] = _t('catalogo.produto_id_cannot_empty');
        }
        if (is_null($this->getFornecedorID())) {
            $errors['fornecedorid'] = _t('catalogo.fornecedor_id_cannot_empty');
        }
        if (is_null($this->getPrecoCompra())) {
            $errors['precocompra'] = _t('catalogo.preco_compra_cannot_empty');
        }
        if (is_null($this->getPrecoVenda())) {
            $errors['precovenda'] = _t('catalogo.preco_venda_cannot_empty');
        }
        if (is_null($this->getQuantidadeMinima())) {
            $errors['quantidademinima'] = _t('catalogo.quantidade_minima_cannot_empty');
        }
        if (is_null($this->getEstoque())) {
            $errors['estoque'] = _t('catalogo.estoque_cannot_empty');
        }
        if (!Validator::checkBoolean($this->getLimitado())) {
            $errors['limitado'] = _t('catalogo.limitado_invalid');
        }
        if (is_null($this->getConteudo())) {
            $errors['conteudo'] = _t('catalogo.conteudo_cannot_empty');
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
        if (contains(['FornecedorID', 'UNIQUE'], $e->getMessage())) {
            return new ValidationException([
                'fornecedorid' => _t(
                    'catalogo.fornecedor_id_used',
                    $this->getFornecedorID()
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Load into this object from database using, FornecedorID
     * @return self Self filled instance or empty when not found
     */
    public function loadByFornecedorID()
    {
        return $this->load([
            'fornecedorid' => intval($this->getFornecedorID()),
        ]);
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
     * Filter condition array with allowed fields
     * @param array $condition condition to filter rows
     * @return array allowed condition
     */
    protected function filterCondition($condition)
    {
        $allowed = $this->getAllowedKeys();
        return Filter::keys($condition, $allowed, 'c.');
    }

    /**
     * Fetch data from database with a condition
     * @param array $condition condition to filter rows
     * @param array $order order rows
     * @return SelectQuery query object with condition statement
     */
    protected function query($condition = [], $order = [])
    {
        $query = DB::from('Catalogos c');
        $condition = $this->filterCondition($condition);
        $query = DB::buildOrderBy($query, $this->filterOrder($order));
        $query = $query->orderBy('c.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Find this object on database using, FornecedorID
     * @param int $fornecedor_id fornecedor to find Catálogo de produtos
     * @return self A filled instance or empty when not found
     */
    public static function findByFornecedorID($fornecedor_id)
    {
        $result = new self();
        $result->setFornecedorID($fornecedor_id);
        return $result->loadByFornecedorID();
    }
}
