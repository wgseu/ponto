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
 * Informa se há descontos nos produtos em determinados dias da semana, o preço
 * pode subir ou descer
 */
class ZPromocao {
	private $id;
	private $produto_id;
	private $inicio;
	private $fim;
	private $valor;
	private $proibir;

	public function __construct($promocao = array()) {
		if(is_array($promocao)) {
			$this->setID($promocao['id']);
			$this->setProdutoID($promocao['produtoid']);
			$this->setInicio($promocao['inicio']);
			$this->setFim($promocao['fim']);
			$this->setValor($promocao['valor']);
			$this->setProibir($promocao['proibir']);
		}
	}

	/**
	 * Identificador da promoção
	 */
	public function getID() {
		return $this->id;
	}

	public function setID($id) {
		$this->id = $id;
	}

	/**
	 * Informa qual o produto que possui desconto ou acréscimo
	 */
	public function getProdutoID() {
		return $this->produto_id;
	}

	public function setProdutoID($produto_id) {
		$this->produto_id = $produto_id;
	}

	/**
	 * Dia inicial em que o produto começa a sofrer alteração de preço
	 */
	public function getInicio() {
		return $this->inicio;
	}

	public function setInicio($inicio) {
		$this->inicio = $inicio;
	}

	/**
	 * Dia final em que o produto deixará de estar na promoção
	 */
	public function getFim() {
		return $this->fim;
	}

	public function setFim($fim) {
		$this->fim = $fim;
	}

	/**
	 * Acréscimo ou desconto aplicado ao produto produto
	 */
	public function getValor() {
		return $this->valor;
	}

	public function setValor($valor) {
		$this->valor = $valor;
	}

	/**
	 * Informa se deve proibir a venda desse produto no período informado
	 */
	public function getProibir() {
		return $this->proibir;
	}

	/**
	 * Informa se deve proibir a venda desse produto no período informado
	 */
	public function isProibir() {
		return $this->proibir == 'Y';
	}

	public function setProibir($proibir) {
		$this->proibir = $proibir;
	}

	public function toArray() {
		$promocao = array();
		$promocao['id'] = $this->getID();
		$promocao['produtoid'] = $this->getProdutoID();
		$promocao['inicio'] = $this->getInicio();
		$promocao['fim'] = $this->getFim();
		$promocao['valor'] = $this->getValor();
		$promocao['proibir'] = $this->getProibir();
		return $promocao;
	}

	public static function getPeloID($id) {
		$query = DB::$pdo->from('Promocoes')
		                 ->where(array('id' => $id));
		return new ZPromocao($query->fetch());
	}

	private static function validarCampos(&$promocao) {
		$erros = array();
		if(!is_numeric($promocao['produtoid']))
			$erros['produtoid'] = 'O produto não foi informado';
		if(!is_numeric($promocao['inicio']))
			$erros['inicio'] = 'O dia inicial não foi informado';
		if(!is_numeric($promocao['fim']))
			$erros['fim'] = 'O dia final não foi informado';
		if(!is_numeric($promocao['valor']))
			$erros['valor'] = 'O valor não foi informado';
		$promocao['proibir'] = trim($promocao['proibir']);
		if(strlen($promocao['proibir']) == 0)
			$promocao['proibir'] = 'N';
		else if(!in_array($promocao['proibir'], array('Y', 'N')))
			$erros['proibir'] = 'A proibir a venda informada não é válida';
		if(!empty($erros))
			throw new ValidationException($erros);
	}

	private static function handleException(&$e) {
		if(stripos($e->getMessage(), 'PRIMARY') !== false)
			throw new ValidationException(array('id' => 'O ID informado já está cadastrado'));
	}

	public static function cadastrar($promocao) {
		$_promocao = $promocao->toArray();
		self::validarCampos($_promocao);
		try {
			$_promocao['id'] = DB::$pdo->insertInto('Promocoes')->values($_promocao)->execute();
		} catch (Exception $e) {
			self::handleException($e);
			throw $e;
		}
		return self::getPeloID($_promocao['id']);
	}

	public static function atualizar($promocao) {
		$_promocao = $promocao->toArray();
		if(!$_promocao['id'])
			throw new ValidationException(array('id' => 'O id da promocao não foi informado'));
		self::validarCampos($_promocao);
		$campos = array(
			'produtoid',
			'inicio',
			'fim',
			'valor',
			'proibir',
		);
		try {
			$query = DB::$pdo->update('Promocoes');
			$query = $query->set(array_intersect_key($_promocao, array_flip($campos)));
			$query = $query->where('id', $_promocao['id']);
			$query->execute();
		} catch (Exception $e) {
			self::handleException($e);
			throw $e;
		}
		return self::getPeloID($_promocao['id']);
	}

	public static function excluir($id) {
		if(!$id)
			throw new Exception('Não foi possível excluir a promocao, o id da promocao não foi informado');
		$query = DB::$pdo->deleteFrom('Promocoes')
		                 ->where(array('id' => $id));
		return $query->execute();
	}

	private static function initSearch() {
		return   DB::$pdo->from('Promocoes')
		                 ->orderBy('id ASC');
	}

	public static function getTodas($inicio = null, $quantidade = null) {
		$query = self::initSearch();
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_promocaos = $query->fetchAll();
		$promocaos = array();
		foreach($_promocaos as $promocao)
			$promocaos[] = new ZPromocao($promocao);
		return $promocaos;
	}

	public static function getCount() {
		$query = self::initSearch();
		return $query->count();
	}

	private static function initSearchDoProdutoID($produto_id) {
		return   DB::$pdo->from('Promocoes')
		                 ->where(array('produtoid' => $produto_id))
		                 ->orderBy('id ASC');
	}

	public static function getTodasDoProdutoID($produto_id, $inicio = null, $quantidade = null) {
		$query = self::initSearchDoProdutoID($produto_id);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_promocaos = $query->fetchAll();
		$promocaos = array();
		foreach($_promocaos as $promocao)
			$promocaos[] = new ZPromocao($promocao);
		return $promocaos;
	}

	public static function getCountDoProdutoID($produto_id) {
		$query = self::initSearchDoProdutoID($produto_id);
		return $query->count();
	}

}
