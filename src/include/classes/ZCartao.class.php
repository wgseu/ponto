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
 * Cartões utilizados na forma de pagamento em cartão
 */
class ZCartao {
	private $id;
	private $carteira_id;
	private $carteira_pagto_id;
	private $descricao;
	private $image_index;
	private $mensalidade;
	private $transacao;
	private $taxa;
	private $dias_repasse;
	private $ativo;

	public function __construct($cartao = array()) {
		if(is_array($cartao)) {
			$this->setID($cartao['id']);
			$this->setCarteiraID($cartao['carteiraid']);
			$this->setCarteiraPagtoID($cartao['carteirapagtoid']);
			$this->setDescricao($cartao['descricao']);
			$this->setImageIndex($cartao['imageindex']);
			$this->setMensalidade($cartao['mensalidade']);
			$this->setTransacao($cartao['transacao']);
			$this->setTaxa($cartao['taxa']);
			$this->setDiasRepasse($cartao['diasrepasse']);
			$this->setAtivo($cartao['ativo']);
		}
	}

	/**
	 * Identificador do cartão
	 */
	public function getID() {
		return $this->id;
	}

	public function setID($id) {
		$this->id = $id;
	}

	/**
	 * Carteira de entrada de valores no caixa
	 */
	public function getCarteiraID() {
		return $this->carteira_id;
	}

	public function setCarteiraID($carteira_id) {
		$this->carteira_id = $carteira_id;
	}

	/**
	 * Carteira de saída de pagamentos no caixa
	 */
	public function getCarteiraPagtoID() {
		return $this->carteira_pagto_id;
	}

	public function setCarteiraPagtoID($carteira_pagto_id) {
		$this->carteira_pagto_id = $carteira_pagto_id;
	}

	/**
	 * Descrição do cartão
	 */
	public function getDescricao() {
		return $this->descricao;
	}

	public function setDescricao($descricao) {
		$this->descricao = $descricao;
	}

	/**
	 * Índice da imagem do cartão
	 */
	public function getImageIndex() {
		return $this->image_index;
	}

	public function setImageIndex($image_index) {
		$this->image_index = $image_index;
	}

	/**
	 * Valor da mensalidade cobrada pela operadora do cartão
	 */
	public function getMensalidade() {
		return $this->mensalidade;
	}

	public function setMensalidade($mensalidade) {
		$this->mensalidade = $mensalidade;
	}

	/**
	 * Valor cobrado pela operadora para cada transação com o cartão
	 */
	public function getTransacao() {
		return $this->transacao;
	}

	public function setTransacao($transacao) {
		$this->transacao = $transacao;
	}

	/**
	 * Taxa em porcentagem cobrado sobre o total do pagamento, valores de 0 a 100
	 */
	public function getTaxa() {
		return $this->taxa;
	}

	public function setTaxa($taxa) {
		$this->taxa = $taxa;
	}

	/**
	 * Quantidade de dias para repasse do valor
	 */
	public function getDiasRepasse() {
		return $this->dias_repasse;
	}

	public function setDiasRepasse($dias_repasse) {
		$this->dias_repasse = $dias_repasse;
	}

	/**
	 * Informa se o cartão está ativo
	 */
	public function getAtivo() {
		return $this->ativo;
	}

	/**
	 * Informa se o cartão está ativo
	 */
	public function isAtivo() {
		return $this->ativo == 'Y';
	}

	public function setAtivo($ativo) {
		$this->ativo = $ativo;
	}

	public function toArray() {
		$cartao = array();
		$cartao['id'] = $this->getID();
		$cartao['carteiraid'] = $this->getCarteiraID();
		$cartao['carteirapagtoid'] = $this->getCarteiraPagtoID();
		$cartao['descricao'] = $this->getDescricao();
		$cartao['imageindex'] = $this->getImageIndex();
		$cartao['mensalidade'] = $this->getMensalidade();
		$cartao['transacao'] = $this->getTransacao();
		$cartao['taxa'] = $this->getTaxa();
		$cartao['diasrepasse'] = $this->getDiasRepasse();
		$cartao['ativo'] = $this->getAtivo();
		return $cartao;
	}

	public static function getImages() {
		return array(
			1 => array('id' => 1, 'name' => 'Credishop'),
			2 => array('id' => 2, 'name' => 'Hipercard'),
			3 => array('id' => 3, 'name' => 'Visa'),
			4 => array('id' => 4, 'name' => 'MasterCard'),
			5 => array('id' => 5, 'name' => 'American Express'),
			6 => array('id' => 6, 'name' => 'Diners Club'),
			7 => array('id' => 7, 'name' => 'Elo'),
			8 => array('id' => 8, 'name' => 'Sodexo'),
			9 => array('id' => 9, 'name' => 'Maestro'),
			10 => array('id' => 10, 'name' => 'Ticket'),
			11 => array('id' => 11, 'name' => 'Visa Electron'),
		);
	}

	public static function getPeloID($id) {
		$query = DB::$pdo->from('Cartoes')
		                 ->where(array('id' => $id));
		return new ZCartao($query->fetch());
	}

	public static function getPelaDescricao($descricao) {
		$query = DB::$pdo->from('Cartoes')
		                 ->where(array('descricao' => $descricao));
		return new ZCartao($query->fetch());
	}

	private static function validarCampos(&$cartao) {
		$erros = array();
		$cartao['carteiraid'] = trim($cartao['carteiraid']);
		if(strlen($cartao['carteiraid']) == 0)
			$cartao['carteiraid'] = null;
		else if(!is_numeric($cartao['carteiraid']))
			$erros['carteiraid'] = 'A carteira de entrada não foi informada';
		$cartao['carteirapagtoid'] = trim($cartao['carteirapagtoid']);
		if(strlen($cartao['carteirapagtoid']) == 0)
			$cartao['carteirapagtoid'] = null;
		else if(!is_numeric($cartao['carteirapagtoid']))
			$erros['carteirapagtoid'] = 'A carteira de saída não foi informada';
		$cartao['descricao'] = strip_tags(trim($cartao['descricao']));
		if(strlen($cartao['descricao']) == 0)
			$erros['descricao'] = 'A descrição não pode ser vazia';
		$cartao['imageindex'] = trim($cartao['imageindex']);
		if(strlen($cartao['imageindex']) == 0)
			$cartao['imageindex'] = null;
		else if(!is_numeric($cartao['imageindex']))
			$erros['imageindex'] = 'O índice da imagem não foi informada';
		else if($cartao['imageindex'] < 0 || $cartao['imageindex'] > 11)
			$erros['imageindex'] = 'O índice da imagem não é válido';		
		if(!is_numeric($cartao['mensalidade']))
			$erros['mensalidade'] = 'A mensalidade não foi informada';
		else {
			$cartao['mensalidade'] = floatval($cartao['mensalidade']);
			if($cartao['mensalidade'] < 0)
				$erros['mensalidade'] = 'A mensalidade não pode ser negativa';
		}
		if(!is_numeric($cartao['transacao']))
			$erros['transacao'] = 'O valor da transação não foi informado';
		else {
			$cartao['transacao'] = floatval($cartao['transacao']);
			if($cartao['transacao'] < 0)
				$erros['transacao'] = 'O valor da transação não pode ser negativo';
		}
		if(!is_numeric($cartao['taxa']))
			$erros['taxa'] = 'A taxa não foi informada';
		else {
			$cartao['taxa'] = floatval($cartao['taxa']);
			if($cartao['taxa'] < 0)
			$erros['taxa'] = 'A taxa não pode ser negativa';
		}
		if(!is_numeric($cartao['diasrepasse']))
			$erros['diasrepasse'] = 'Os dias para repasse não foi informado';
		else {
			$cartao['diasrepasse'] = intval($cartao['diasrepasse']);
			if($cartao['diasrepasse'] < 0)
				$erros['diasrepasse'] = 'Os dias para repasse não pode ser negativo';
		}
		$cartao['ativo'] = trim($cartao['ativo']);
		if(strlen($cartao['ativo']) == 0)
			$cartao['ativo'] = 'N';
		else if(!in_array($cartao['ativo'], array('Y', 'N')))
			$erros['ativo'] = 'O ativo informado não é válido';
		if(!empty($erros))
			throw new ValidationException($erros);
	}

	private static function handleException(&$e) {
		if(stripos($e->getMessage(), 'PRIMARY') !== false)
			throw new ValidationException(array('id' => 'O ID informado já está cadastrado'));
		if(stripos($e->getMessage(), 'Descricao_UNIQUE') !== false)
			throw new ValidationException(array('descricao' => 'A descrição informada já está cadastrada'));
	}

	public static function cadastrar($cartao) {
		$_cartao = $cartao->toArray();
		self::validarCampos($_cartao);
		try {
			$_cartao['id'] = DB::$pdo->insertInto('Cartoes')->values($_cartao)->execute();
		} catch (Exception $e) {
			self::handleException($e);
			throw $e;
		}
		return self::getPeloID($_cartao['id']);
	}

	public static function atualizar($cartao) {
		$_cartao = $cartao->toArray();
		if(!$_cartao['id'])
			throw new ValidationException(array('id' => 'O id do cartao não foi informado'));
		self::validarCampos($_cartao);
		$campos = array(
			'carteiraid',
			'carteirapagtoid',
			'descricao',
			'imageindex',
			'mensalidade',
			'transacao',
			'taxa',
			'diasrepasse',
			'ativo',
		);
		try {
			$query = DB::$pdo->update('Cartoes');
			$query = $query->set(array_intersect_key($_cartao, array_flip($campos)));
			$query = $query->where('id', $_cartao['id']);
			$query->execute();
		} catch (Exception $e) {
			self::handleException($e);
			throw $e;
		}
		return self::getPeloID($_cartao['id']);
	}

	public static function excluir($id) {
		if(!$id)
			throw new Exception('Não foi possível excluir o cartao, o id do cartao não foi informado');
		$query = DB::$pdo->deleteFrom('Cartoes')
		                 ->where(array('id' => $id));
		return $query->execute();
	}

	private static function initSearch($busca, $ativo) {
		$query = DB::$pdo->from('Cartoes')
		                 ->orderBy('ativo ASC, descricao ASC');
		$busca = trim($busca);
		if($busca != '')
			$query = $query->where('descricao LIKE ?', '%'.$busca.'%');
		$ativo = trim($ativo);
		if($ativo != '')
			$query = $query->where('ativo', $ativo);
		return $query;
	}

	public static function getTodos($busca = null, $ativo = null, $inicio = null, $quantidade = null) {
		$query = self::initSearch($busca, $ativo);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_cartaos = $query->fetchAll();
		$cartaos = array();
		foreach($_cartaos as $cartao)
			$cartaos[] = new ZCartao($cartao);
		return $cartaos;
	}

	public static function getCount($busca = null, $ativo = null) {
		$query = self::initSearch($busca, $ativo);
		return $query->count();
	}

	private static function initSearchDaCarteiraID($carteira_id) {
		return   DB::$pdo->from('Cartoes')
		                 ->where(array('carteiraid' => $carteira_id))
		                 ->orderBy('id ASC');
	}

	public static function getTodosDaCarteiraID($carteira_id, $inicio = null, $quantidade = null) {
		$query = self::initSearchDaCarteiraID($carteira_id);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_cartaos = $query->fetchAll();
		$cartaos = array();
		foreach($_cartaos as $cartao)
			$cartaos[] = new ZCartao($cartao);
		return $cartaos;
	}

	public static function getCountDaCarteiraID($carteira_id) {
		$query = self::initSearchDaCarteiraID($carteira_id);
		return $query->count();
	}

	private static function initSearchDoCarteiraPagtoID($carteira_pagto_id) {
		return   DB::$pdo->from('Cartoes')
		                 ->where(array('carteirapagtoid' => $carteira_pagto_id))
		                 ->orderBy('id ASC');
	}

	public static function getTodosDoCarteiraPagtoID($carteira_pagto_id, $inicio = null, $quantidade = null) {
		$query = self::initSearchDoCarteiraPagtoID($carteira_pagto_id);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_cartaos = $query->fetchAll();
		$cartaos = array();
		foreach($_cartaos as $cartao)
			$cartaos[] = new ZCartao($cartao);
		return $cartaos;
	}

	public static function getCountDoCarteiraPagtoID($carteira_pagto_id) {
		$query = self::initSearchDoCarteiraPagtoID($carteira_pagto_id);
		return $query->count();
	}

}
