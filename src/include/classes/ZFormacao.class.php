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
class FormacaoTipo {
	const PACOTE = 'Pacote';
	const COMPOSICAO = 'Composicao';
}

/**
 * Informa qual foi a formação que gerou esse produto, assim como quais item foram
 * retirados/adicionados da composição
 */
class ZFormacao {
	private $id;
	private $produto_pedido_id;
	private $tipo;
	private $pacote_id;
	private $composicao_id;

	public function __construct($formacao = array()) {
		if(is_array($formacao)) {
			$this->setID($formacao['id']);
			$this->setProdutoPedidoID($formacao['produtopedidoid']);
			$this->setTipo($formacao['tipo']);
			$this->setPacoteID($formacao['pacoteid']);
			$this->setComposicaoID($formacao['composicaoid']);
		}
	}

	/**
	 * Identificador da formação
	 */
	public function getID() {
		return $this->id;
	}

	public function setID($id) {
		$this->id = $id;
	}

	/**
	 * Informa qual foi o produto vendido para essa formação
	 */
	public function getProdutoPedidoID() {
		return $this->produto_pedido_id;
	}

	public function setProdutoPedidoID($produto_pedido_id) {
		$this->produto_pedido_id = $produto_pedido_id;
	}

	/**
	 * Informa qual tipo de formação foi escolhida, Pacote: O produto ou propriedade
	 * faz parte de um pacote, Composição: O produto é uma composição e esse item foi
	 * retirado ou adicionado na venda
	 */
	public function getTipo() {
		return $this->tipo;
	}

	public function setTipo($tipo) {
		$this->tipo = $tipo;
	}

	/**
	 * Informa qual pacote foi selecionado no momento da venda
	 */
	public function getPacoteID() {
		return $this->pacote_id;
	}

	public function setPacoteID($pacote_id) {
		$this->pacote_id = $pacote_id;
	}

	/**
	 * Informa qual composição foi retirada ou adicionada no momento da venda
	 */
	public function getComposicaoID() {
		return $this->composicao_id;
	}

	public function setComposicaoID($composicao_id) {
		$this->composicao_id = $composicao_id;
	}

	public function toArray() {
		$formacao = array();
		$formacao['id'] = $this->getID();
		$formacao['produtopedidoid'] = $this->getProdutoPedidoID();
		$formacao['tipo'] = $this->getTipo();
		$formacao['pacoteid'] = $this->getPacoteID();
		$formacao['composicaoid'] = $this->getComposicaoID();
		return $formacao;
	}

	public static function getPeloID($id) {
		$query = DB::$pdo->from('Formacoes')
		                 ->where(array('id' => $id));
		return new ZFormacao($query->fetch());
	}

	public static function getPeloProdutoPedidoIDPacoteID($produto_pedido_id, $pacote_id) {
		$query = DB::$pdo->from('Formacoes')
		                 ->where(array('produtopedidoid' => $produto_pedido_id, 'pacoteid' => $pacote_id));
		return new ZFormacao($query->fetch());
	}

	private static function validarCampos(&$formacao) {
		$erros = array();
		if(!is_numeric($formacao['produtopedidoid']))
			$erros['produtopedidoid'] = 'O item do pedido não foi informado';
		$formacao['tipo'] = trim($formacao['tipo']);
		if(strlen($formacao['tipo']) == 0)
			$formacao['tipo'] = null;
		else if(!in_array($formacao['tipo'], array('Pacote', 'Composicao')))
			$erros['tipo'] = 'O tipo informado não é válido';
		$formacao['pacoteid'] = trim($formacao['pacoteid']);
		if(strlen($formacao['pacoteid']) == 0)
			$formacao['pacoteid'] = null;
		else if(!is_numeric($formacao['pacoteid']))
			$erros['pacoteid'] = 'O pacote não foi informado';
		$formacao['composicaoid'] = trim($formacao['composicaoid']);
		if(strlen($formacao['composicaoid']) == 0)
			$formacao['composicaoid'] = null;
		else if(!is_numeric($formacao['composicaoid']))
			$erros['composicaoid'] = 'A composição não foi informada';
		if(!empty($erros))
			throw new ValidationException($erros);
	}

	private static function handleException(&$e) {
		if(stripos($e->getMessage(), 'PRIMARY') !== false)
			throw new ValidationException(array('id' => 'O ID informado já está cadastrado'));
		if(stripos($e->getMessage(), 'UK_Formacoes_ProdutoPedidoID_PacoteID') !== false)
			throw new ValidationException(array('pacoteid' => 'O pacote informado já está cadastrado'));
	}

	public static function cadastrar($formacao) {
		$_formacao = $formacao->toArray();
		self::validarCampos($_formacao);
		try {
			$_formacao['id'] = DB::$pdo->insertInto('Formacoes')->values($_formacao)->execute();
		} catch (Exception $e) {
			self::handleException($e);
			throw $e;
		}
		return self::getPeloID($_formacao['id']);
	}

	public static function atualizar($formacao) {
		$_formacao = $formacao->toArray();
		if(!$_formacao['id'])
			throw new ValidationException(array('id' => 'O id da formacao não foi informado'));
		self::validarCampos($_formacao);
		$campos = array(
			'produtopedidoid',
			'tipo',
			'pacoteid',
			'composicaoid',
		);
		try {
			$query = DB::$pdo->update('Formacoes');
			$query = $query->set(array_intersect_key($_formacao, array_flip($campos)));
			$query = $query->where('id', $_formacao['id']);
			$query->execute();
		} catch (Exception $e) {
			self::handleException($e);
			throw $e;
		}
		return self::getPeloID($_formacao['id']);
	}

	private static function initSearch() {
		return   DB::$pdo->from('Formacoes')
		                 ->orderBy('id ASC');
	}

	public static function getTodas($inicio = null, $quantidade = null) {
		$query = self::initSearch();
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_formacaos = $query->fetchAll();
		$formacaos = array();
		foreach($_formacaos as $formacao)
			$formacaos[] = new ZFormacao($formacao);
		return $formacaos;
	}

	public static function getCount() {
		$query = self::initSearch();
		return $query->count();
	}

	private static function initSearchDoProdutoPedidoID($produto_pedido_id) {
		return   DB::$pdo->from('Formacoes')
		                 ->where(array('produtopedidoid' => $produto_pedido_id))
		                 ->orderBy('id ASC');
	}

	public static function getTodasDoProdutoPedidoID($produto_pedido_id, $inicio = null, $quantidade = null) {
		$query = self::initSearchDoProdutoPedidoID($produto_pedido_id);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_formacaos = $query->fetchAll();
		$formacaos = array();
		foreach($_formacaos as $formacao)
			$formacaos[] = new ZFormacao($formacao);
		return $formacaos;
	}

	public static function getCountDoProdutoPedidoID($produto_pedido_id) {
		$query = self::initSearchDoProdutoPedidoID($produto_pedido_id);
		return $query->count();
	}

	private static function initSearchDoPacoteID($pacote_id) {
		return   DB::$pdo->from('Formacoes')
		                 ->where(array('pacoteid' => $pacote_id))
		                 ->orderBy('id ASC');
	}

	public static function getTodasDoPacoteID($pacote_id, $inicio = null, $quantidade = null) {
		$query = self::initSearchDoPacoteID($pacote_id);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_formacaos = $query->fetchAll();
		$formacaos = array();
		foreach($_formacaos as $formacao)
			$formacaos[] = new ZFormacao($formacao);
		return $formacaos;
	}

	public static function getCountDoPacoteID($pacote_id) {
		$query = self::initSearchDoPacoteID($pacote_id);
		return $query->count();
	}

	private static function initSearchDaComposicaoID($composicao_id) {
		return   DB::$pdo->from('Formacoes')
		                 ->where(array('composicaoid' => $composicao_id))
		                 ->orderBy('id ASC');
	}

	public static function getTodasDaComposicaoID($composicao_id, $inicio = null, $quantidade = null) {
		$query = self::initSearchDaComposicaoID($composicao_id);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_formacaos = $query->fetchAll();
		$formacaos = array();
		foreach($_formacaos as $formacao)
			$formacaos[] = new ZFormacao($formacao);
		return $formacaos;
	}

	public static function getCountDaComposicaoID($composicao_id) {
		$query = self::initSearchDaComposicaoID($composicao_id);
		return $query->count();
	}

}
