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
 * Informa os produtos da lista de compras
 */
class ZListaProduto {
	private $id;
	private $lista_compra_id;
	private $produto_id;
	private $fornecedor_id;
	private $quantidade;
	private $preco_maximo;
	private $preco;
	private $observacoes;
	private $comprado;

	public function __construct($lista_produto = array()) {
		if(is_array($lista_produto)) {
			$this->setID(isset($lista_produto['id'])?$lista_produto['id']:null);
			$this->setListaCompraID(isset($lista_produto['listacompraid'])?$lista_produto['listacompraid']:null);
			$this->setProdutoID(isset($lista_produto['produtoid'])?$lista_produto['produtoid']:null);
			$this->setFornecedorID(isset($lista_produto['fornecedorid'])?$lista_produto['fornecedorid']:null);
			$this->setQuantidade(isset($lista_produto['quantidade'])?$lista_produto['quantidade']:null);
			$this->setPrecoMaximo(isset($lista_produto['precomaximo'])?$lista_produto['precomaximo']:null);
			$this->setPreco(isset($lista_produto['preco'])?$lista_produto['preco']:null);
			$this->setObservacoes(isset($lista_produto['observacoes'])?$lista_produto['observacoes']:null);
			$this->setComprado(isset($lista_produto['comprado'])?$lista_produto['comprado']:null);
		}
	}

	/**
	 * Identificador do produto da lista
	 */
	public function getID() {
		return $this->id;
	}

	public function setID($id) {
		$this->id = $id;
	}

	/**
	 * Lista de compra desse produto
	 */
	public function getListaCompraID() {
		return $this->lista_compra_id;
	}

	public function setListaCompraID($lista_compra_id) {
		$this->lista_compra_id = $lista_compra_id;
	}

	/**
	 * Produto que deve ser comprado
	 */
	public function getProdutoID() {
		return $this->produto_id;
	}

	public function setProdutoID($produto_id) {
		$this->produto_id = $produto_id;
	}

	/**
	 * Fornecedor que deve ser consulado ou comprado os produtos, pode ser alterado no
	 * momento da compra
	 */
	public function getFornecedorID() {
		return $this->fornecedor_id;
	}

	public function setFornecedorID($fornecedor_id) {
		$this->fornecedor_id = $fornecedor_id;
	}

	/**
	 * Quantidade de produtos que deve ser comprado
	 */
	public function getQuantidade() {
		return $this->quantidade;
	}

	public function setQuantidade($quantidade) {
		$this->quantidade = $quantidade;
	}

	/**
	 * Preço máximo que deve ser pago na compra desse produto
	 */
	public function getPrecoMaximo() {
		return $this->preco_maximo;
	}

	public function setPrecoMaximo($preco_maximo) {
		$this->preco_maximo = $preco_maximo;
	}

	/**
	 * Preço em que o produto foi comprado da última vez ou o novo preço
	 */
	public function getPreco() {
		return $this->preco;
	}

	public function setPreco($preco) {
		$this->preco = $preco;
	}

	/**
	 * Detalhes na compra desse produto
	 */
	public function getObservacoes() {
		return $this->observacoes;
	}

	public function setObservacoes($observacoes) {
		$this->observacoes = $observacoes;
	}

	/**
	 * Informa se esse produto já foi comprado
	 */
	public function getComprado() {
		return $this->comprado;
	}

	/**
	 * Informa se esse produto já foi comprado
	 */
	public function isComprado() {
		return $this->comprado == 'Y';
	}

	public function setComprado($comprado) {
		$this->comprado = $comprado;
	}

	public function toArray() {
		$lista_produto = array();
		$lista_produto['id'] = $this->getID();
		$lista_produto['listacompraid'] = $this->getListaCompraID();
		$lista_produto['produtoid'] = $this->getProdutoID();
		$lista_produto['fornecedorid'] = $this->getFornecedorID();
		$lista_produto['quantidade'] = $this->getQuantidade();
		$lista_produto['precomaximo'] = $this->getPrecoMaximo();
		$lista_produto['preco'] = $this->getPreco();
		$lista_produto['observacoes'] = $this->getObservacoes();
		$lista_produto['comprado'] = $this->getComprado();
		return $lista_produto;
	}

	public static function getPeloID($id) {
		$query = DB::$pdo->from('Listas_Produtos')
		                 ->where(array('id' => $id));
		return new ZListaProduto($query->fetch());
	}

	private static function validarCampos(&$lista_produto) {
		$erros = array();
		if(!is_numeric($lista_produto['listacompraid']))
			$erros['listacompraid'] = 'A lista de compra não foi informada';
		if(!is_numeric($lista_produto['produtoid']))
			$erros['produtoid'] = 'O produto não foi informado';
		$lista_produto['fornecedorid'] = trim($lista_produto['fornecedorid']);
		if(strlen($lista_produto['fornecedorid']) == 0)
			$lista_produto['fornecedorid'] = null;
		else if(!is_numeric($lista_produto['fornecedorid']))
			$erros['fornecedorid'] = 'O fornecedor não foi informado';
		if(!is_numeric($lista_produto['quantidade']))
			$erros['quantidade'] = 'A quantidade não foi informada';
		else
			$lista_produto['quantidade'] = floatval($lista_produto['quantidade']);
		if(!is_numeric($lista_produto['precomaximo']))
			$erros['precomaximo'] = 'O preço máximo não foi informado';
		if(!is_numeric($lista_produto['preco']))
			$erros['preco'] = 'O preço não foi informado';
		else
			$lista_produto['preco'] = floatval($lista_produto['preco']);
		$lista_produto['observacoes'] = strip_tags(trim($lista_produto['observacoes']));
		if(strlen($lista_produto['observacoes']) == 0)
			$lista_produto['observacoes'] = null;
		$lista_produto['comprado'] = strval($lista_produto['comprado']);
		if(strlen($lista_produto['comprado']) == 0)
			$lista_produto['comprado'] = 'N';
		else if(!in_array($lista_produto['comprado'], array('Y', 'N')))
			$erros['comprado'] = 'O comprado informado não é válido';
		if(!empty($erros))
			throw new ValidationException($erros);
	}

	private static function handleException(&$e) {
		if(stripos($e->getMessage(), 'PRIMARY') !== false)
			throw new ValidationException(array('id' => 'O ID informado já está cadastrado'));
	}

	public static function cadastrar($lista_produto) {
		$_lista_produto = $lista_produto->toArray();
		self::validarCampos($_lista_produto);
		try {
			$_lista_produto['id'] = DB::$pdo->insertInto('Listas_Produtos')->values($_lista_produto)->execute();
		} catch (Exception $e) {
			self::handleException($e);
			throw $e;
		}
		return self::getPeloID($_lista_produto['id']);
	}

	public static function atualizar($lista_produto) {
		$_lista_produto = $lista_produto->toArray();
		if(!$_lista_produto['id'])
			throw new ValidationException(array('id' => 'O id do listaproduto não foi informado'));
		self::validarCampos($_lista_produto);
		$campos = array(
			'listacompraid',
			'produtoid',
			'fornecedorid',
			'quantidade',
			'precomaximo',
			'preco',
			'observacoes',
			'comprado',
		);
		try {
			$query = DB::$pdo->update('Listas_Produtos');
			$query = $query->set(array_intersect_key($_lista_produto, array_flip($campos)));
			$query = $query->where('id', $_lista_produto['id']);
			$query->execute();
		} catch (Exception $e) {
			self::handleException($e);
			throw $e;
		}
		return self::getPeloID($_lista_produto['id']);
	}

	public static function excluir($id) {
		if(!$id)
			throw new Exception('Não foi possível excluir o listaproduto, o id do listaproduto não foi informado');
		$query = DB::$pdo->deleteFrom('Listas_Produtos')
		                 ->where(array('id' => $id));
		return $query->execute();
	}

	private static function initSearch() {
		return   DB::$pdo->from('Listas_Produtos')
		                 ->orderBy('id ASC');
	}

	public static function getTodos($inicio = null, $quantidade = null) {
		$query = self::initSearch();
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_lista_produtos = $query->fetchAll();
		$lista_produtos = array();
		foreach($_lista_produtos as $lista_produto)
			$lista_produtos[] = new ZListaProduto($lista_produto);
		return $lista_produtos;
	}

	public static function getCount() {
		$query = self::initSearch();
		return $query->count();
	}

	private static function initSearchDaListaCompraID($lista_compra_id) {
		return   DB::$pdo->from('Listas_Produtos')
		                 ->where(array('listacompraid' => $lista_compra_id))
		                 ->orderBy('id ASC');
	}

	public static function getTodosDaListaCompraID($lista_compra_id, $inicio = null, $quantidade = null) {
		$query = self::initSearchDaListaCompraID($lista_compra_id);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_lista_produtos = $query->fetchAll();
		$lista_produtos = array();
		foreach($_lista_produtos as $lista_produto)
			$lista_produtos[] = new ZListaProduto($lista_produto);
		return $lista_produtos;
	}

	public static function getCountDaListaCompraID($lista_compra_id) {
		$query = self::initSearchDaListaCompraID($lista_compra_id);
		return $query->count();
	}

	private static function initSearchDoProdutoID($produto_id) {
		return   DB::$pdo->from('Listas_Produtos')
		                 ->where(array('produtoid' => $produto_id))
		                 ->orderBy('id ASC');
	}

	public static function getTodosDoProdutoID($produto_id, $inicio = null, $quantidade = null) {
		$query = self::initSearchDoProdutoID($produto_id);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_lista_produtos = $query->fetchAll();
		$lista_produtos = array();
		foreach($_lista_produtos as $lista_produto)
			$lista_produtos[] = new ZListaProduto($lista_produto);
		return $lista_produtos;
	}

	public static function getCountDoProdutoID($produto_id) {
		$query = self::initSearchDoProdutoID($produto_id);
		return $query->count();
	}

	private static function initSearchDoFornecedorID($fornecedor_id) {
		return   DB::$pdo->from('Listas_Produtos')
		                 ->where(array('fornecedorid' => $fornecedor_id))
		                 ->orderBy('id ASC');
	}

	public static function getTodosDoFornecedorID($fornecedor_id, $inicio = null, $quantidade = null) {
		$query = self::initSearchDoFornecedorID($fornecedor_id);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_lista_produtos = $query->fetchAll();
		$lista_produtos = array();
		foreach($_lista_produtos as $lista_produto)
			$lista_produtos[] = new ZListaProduto($lista_produto);
		return $lista_produtos;
	}

	public static function getCountDoFornecedorID($fornecedor_id) {
		$query = self::initSearchDoFornecedorID($fornecedor_id);
		return $query->count();
	}

}
