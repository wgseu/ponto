<?php
/*
	Copyright 2014 da MZ Software - MZ Desenvolvimento de Sistemas LTDA
	Este arquivo é parte do programa GrandChef - Sistema para Gerenciamento de Churrascarias, Bares e Restaurantes.
	O GrandChef é um software proprietário; você não pode redistribuí-lo e/ou modificá-lo.
	DISPOSIÇÕES GERAIS
	O cliente não deverá remover qualquer identificação do produto, avisos de direitos autorais,
	ou outros avisos ou restrições de propriedade do GrandChef.

	O cliente não deverá causar ou permitir a engenharia reversa, desmontagem,
	ou descompilação do GrandChef.

	PROPRIEDADE DOS DIREITOS AUTORAIS DO PROGRAMA

	GrandChef é a especialidade do desenvolvedor e seus
	licenciadores e é protegido por direitos autorais, segredos comerciais e outros direitos
	de leis de propriedade.

	O Cliente adquire apenas o direito de usar o software e não adquire qualquer outros
	direitos, expressos ou implícitos no GrandChef diferentes dos especificados nesta Licença.
*/
/**
 * Informa a lista de produtos disponíveis nos fornecedores
 */
class ZProdutoFornecedor
{
    private $id;
    private $produto_id;
    private $fornecedor_id;
    private $preco_compra;
    private $preco_venda;
    private $quantidade_minima;
    private $estoque;
    private $limitado;
    private $data_consulta;

    public function __construct($produto_fornecedor = array())
    {
        if (is_array($produto_fornecedor)) {
            $this->setID(isset($produto_fornecedor['id'])?$produto_fornecedor['id']:null);
            $this->setProdutoID(isset($produto_fornecedor['produtoid'])?$produto_fornecedor['produtoid']:null);
            $this->setFornecedorID(isset($produto_fornecedor['fornecedorid'])?$produto_fornecedor['fornecedorid']:null);
            $this->setPrecoCompra(isset($produto_fornecedor['precocompra'])?$produto_fornecedor['precocompra']:null);
            $this->setPrecoVenda(isset($produto_fornecedor['precovenda'])?$produto_fornecedor['precovenda']:null);
            $this->setQuantidadeMinima(isset($produto_fornecedor['quantidademinima'])?$produto_fornecedor['quantidademinima']:null);
            $this->setEstoque(isset($produto_fornecedor['estoque'])?$produto_fornecedor['estoque']:null);
            $this->setLimitado(isset($produto_fornecedor['limitado'])?$produto_fornecedor['limitado']:null);
            $this->setDataConsulta(isset($produto_fornecedor['dataconsulta'])?$produto_fornecedor['dataconsulta']:null);
        }
    }

    /**
     * Identificador do produto do fornecedor
     */
    public function getID()
    {
        return $this->id;
    }

    public function setID($id)
    {
        $this->id = $id;
    }

    /**
     * Produto consultado
     */
    public function getProdutoID()
    {
        return $this->produto_id;
    }

    public function setProdutoID($produto_id)
    {
        $this->produto_id = $produto_id;
    }

    /**
     * Fornecedor que possui o produto à venda
     */
    public function getFornecedorID()
    {
        return $this->fornecedor_id;
    }

    public function setFornecedorID($fornecedor_id)
    {
        $this->fornecedor_id = $fornecedor_id;
    }

    /**
     * Preço a qual o produto foi comprado
     */
    public function getPrecoCompra()
    {
        return $this->preco_compra;
    }

    public function setPrecoCompra($preco_compra)
    {
        $this->preco_compra = $preco_compra;
    }

    /**
     * Preço de venda do produto pelo fornecedor
     */
    public function getPrecoVenda()
    {
        return $this->preco_venda;
    }

    public function setPrecoVenda($preco_venda)
    {
        $this->preco_venda = $preco_venda;
    }

    /**
     * Quantidade mínima que o fornecedor vende
     */
    public function getQuantidadeMinima()
    {
        return $this->quantidade_minima;
    }

    public function setQuantidadeMinima($quantidade_minima)
    {
        $this->quantidade_minima = $quantidade_minima;
    }

    /**
     * Quantidade em estoque do produto no fornecedor
     */
    public function getEstoque()
    {
        return $this->estoque;
    }

    public function setEstoque($estoque)
    {
        $this->estoque = $estoque;
    }

    /**
     * Informa se a quantidade de estoque é limitada
     */
    public function getLimitado()
    {
        return $this->limitado;
    }

    /**
     * Informa se a quantidade de estoque é limitada
     */
    public function isLimitado()
    {
        return $this->limitado == 'Y';
    }

    public function setLimitado($limitado)
    {
        $this->limitado = $limitado;
    }

    /**
     * Última data de consulta do preço do produto
     */
    public function getDataConsulta()
    {
        return $this->data_consulta;
    }

    public function setDataConsulta($data_consulta)
    {
        $this->data_consulta = $data_consulta;
    }

    public function toArray()
    {
        $produto_fornecedor = array();
        $produto_fornecedor['id'] = $this->getID();
        $produto_fornecedor['produtoid'] = $this->getProdutoID();
        $produto_fornecedor['fornecedorid'] = $this->getFornecedorID();
        $produto_fornecedor['precocompra'] = $this->getPrecoCompra();
        $produto_fornecedor['precovenda'] = $this->getPrecoVenda();
        $produto_fornecedor['quantidademinima'] = $this->getQuantidadeMinima();
        $produto_fornecedor['estoque'] = $this->getEstoque();
        $produto_fornecedor['limitado'] = $this->getLimitado();
        $produto_fornecedor['dataconsulta'] = $this->getDataConsulta();
        return $produto_fornecedor;
    }

    public static function getPeloID($id)
    {
        $query = DB::$pdo->from('Produtos_Fornecedores')
                         ->where(array('id' => $id));
        return new ZProdutoFornecedor($query->fetch());
    }

    private static function validarCampos(&$produto_fornecedor)
    {
        $erros = array();
        if (!is_numeric($produto_fornecedor['produtoid'])) {
            $erros['produtoid'] = 'O produto não foi informado';
        }
        if (!is_numeric($produto_fornecedor['fornecedorid'])) {
            $erros['fornecedorid'] = 'O fornecedor não foi informado';
        }
        if (!is_numeric($produto_fornecedor['precocompra'])) {
            $erros['precocompra'] = 'O preço de compra não foi informado';
        }
        if (!is_numeric($produto_fornecedor['precovenda'])) {
            $erros['precovenda'] = 'O preço de venda não foi informado';
        } else {
            $produto_fornecedor['precovenda'] = floatval($produto_fornecedor['precovenda']);
        }
        if (!is_numeric($produto_fornecedor['quantidademinima'])) {
            $erros['quantidademinima'] = 'A quantidade mínima não foi informada';
        } else {
            $produto_fornecedor['quantidademinima'] = floatval($produto_fornecedor['quantidademinima']);
        }
        if (!is_numeric($produto_fornecedor['estoque'])) {
            $erros['estoque'] = 'O estoque não foi informado';
        } else {
            $produto_fornecedor['estoque'] = floatval($produto_fornecedor['estoque']);
        }
        $produto_fornecedor['limitado'] = trim($produto_fornecedor['limitado']);
        if (strlen($produto_fornecedor['limitado']) == 0) {
            $produto_fornecedor['limitado'] = 'N';
        } elseif (!in_array($produto_fornecedor['limitado'], array('Y', 'N'))) {
            $erros['limitado'] = 'O limitado informado não é válido';
        }
        $produto_fornecedor['dataconsulta'] = date('Y-m-d H:i:s');
        if (!empty($erros)) {
            throw new ValidationException($erros);
        }
    }

    private static function handleException(&$e)
    {
        if (stripos($e->getMessage(), 'PRIMARY') !== false) {
            throw new ValidationException(array('id' => 'O ID informado já está cadastrado'));
        }
    }

    public static function cadastrar($produto_fornecedor)
    {
        $_produto_fornecedor = $produto_fornecedor->toArray();
        self::validarCampos($_produto_fornecedor);
        try {
            $_produto_fornecedor['id'] = DB::$pdo->insertInto('Produtos_Fornecedores')->values($_produto_fornecedor)->execute();
        } catch (Exception $e) {
            self::handleException($e);
            throw $e;
        }
        return self::getPeloID($_produto_fornecedor['id']);
    }

    public static function atualizar($produto_fornecedor)
    {
        $_produto_fornecedor = $produto_fornecedor->toArray();
        if (!$_produto_fornecedor['id']) {
            throw new ValidationException(array('id' => 'O id do produtofornecedor não foi informado'));
        }
        self::validarCampos($_produto_fornecedor);
        $campos = array(
            'produtoid',
            'fornecedorid',
            'precocompra',
            'precovenda',
            'quantidademinima',
            'estoque',
            'limitado',
            'dataconsulta',
        );
        try {
            $query = DB::$pdo->update('Produtos_Fornecedores');
            $query = $query->set(array_intersect_key($_produto_fornecedor, array_flip($campos)));
            $query = $query->where('id', $_produto_fornecedor['id']);
            $query->execute();
        } catch (Exception $e) {
            self::handleException($e);
            throw $e;
        }
        return self::getPeloID($_produto_fornecedor['id']);
    }

    public static function excluir($id)
    {
        if (!$id) {
            throw new Exception('Não foi possível excluir o produtofornecedor, o id do produtofornecedor não foi informado');
        }
        $query = DB::$pdo->deleteFrom('Produtos_Fornecedores')
                         ->where(array('id' => $id));
        return $query->execute();
    }

    private static function initSearch()
    {
        return   DB::$pdo->from('Produtos_Fornecedores')
                         ->orderBy('id ASC');
    }

    public static function getTodos($inicio = null, $quantidade = null)
    {
        $query = self::initSearch();
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_produto_fornecedors = $query->fetchAll();
        $produto_fornecedors = array();
        foreach ($_produto_fornecedors as $produto_fornecedor) {
            $produto_fornecedors[] = new ZProdutoFornecedor($produto_fornecedor);
        }
        return $produto_fornecedors;
    }

    public static function getCount()
    {
        $query = self::initSearch();
        return $query->count();
    }

    private static function initSearchDoProdutoID($produto_id)
    {
        return   DB::$pdo->from('Produtos_Fornecedores')
                         ->where(array('produtoid' => $produto_id))
                         ->orderBy('id ASC');
    }

    public static function getTodosDoProdutoID($produto_id, $inicio = null, $quantidade = null)
    {
        $query = self::initSearchDoProdutoID($produto_id);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_produto_fornecedors = $query->fetchAll();
        $produto_fornecedors = array();
        foreach ($_produto_fornecedors as $produto_fornecedor) {
            $produto_fornecedors[] = new ZProdutoFornecedor($produto_fornecedor);
        }
        return $produto_fornecedors;
    }

    public static function getCountDoProdutoID($produto_id)
    {
        $query = self::initSearchDoProdutoID($produto_id);
        return $query->count();
    }

    private static function initSearchDoFornecedorID($fornecedor_id)
    {
        return   DB::$pdo->from('Produtos_Fornecedores')
                         ->where(array('fornecedorid' => $fornecedor_id))
                         ->orderBy('id ASC');
    }

    public static function getTodosDoFornecedorID($fornecedor_id, $inicio = null, $quantidade = null)
    {
        $query = self::initSearchDoFornecedorID($fornecedor_id);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_produto_fornecedors = $query->fetchAll();
        $produto_fornecedors = array();
        foreach ($_produto_fornecedors as $produto_fornecedor) {
            $produto_fornecedors[] = new ZProdutoFornecedor($produto_fornecedor);
        }
        return $produto_fornecedors;
    }

    public static function getCountDoFornecedorID($fornecedor_id)
    {
        $query = self::initSearchDoFornecedorID($fornecedor_id);
        return $query->count();
    }
}
