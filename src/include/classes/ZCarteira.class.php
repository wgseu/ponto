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
class CarteiraTipo {
	const BANCARIA = 'Bancaria';
	const FINANCEIRA = 'Financeira';
}

/**
 * Informa uma conta bancária ou uma carteira financeira
 */
class ZCarteira {
	private $id;
	private $tipo;
	private $banco_id;
	private $descricao;
	private $conta;
	private $agencia;
	private $ativa;

	public function __construct($carteira = array()) {
		if(is_array($carteira)) {
			$this->setID($carteira['id']);
			$this->setTipo($carteira['tipo']);
			$this->setBancoID($carteira['bancoid']);
			$this->setDescricao($carteira['descricao']);
			$this->setConta($carteira['conta']);
			$this->setAgencia($carteira['agencia']);
			$this->setAtiva($carteira['ativa']);
		}
	}

	/**
	 * Código local da carteira
	 */
	public function getID() {
		return $this->id;
	}

	public function setID($id) {
		$this->id = $id;
	}

	/**
	 * Tipo de carteira, 'Bancaria' para conta bancária e 'Financeira' para carteira
	 * financeira da empresa ou de sites de pagamentos
	 */
	public function getTipo() {
		return $this->tipo;
	}

	public function setTipo($tipo) {
		$this->tipo = $tipo;
	}

	/**
	 * Código local do banco quando a carteira for bancária
	 */
	public function getBancoID() {
		return $this->banco_id;
	}

	public function setBancoID($banco_id) {
		$this->banco_id = $banco_id;
	}

	/**
	 * Descrição da carteira, nome dado a carteira cadastrada
	 */
	public function getDescricao() {
		return $this->descricao;
	}

	public function setDescricao($descricao) {
		$this->descricao = $descricao;
	}

	/**
	 * Número da conta bancária ou usuário da conta de acesso da carteira
	 */
	public function getConta() {
		return $this->conta;
	}

	public function setConta($conta) {
		$this->conta = $conta;
	}

	/**
	 * Número da agência da conta bancária ou site da carteira financeira
	 */
	public function getAgencia() {
		return $this->agencia;
	}

	public function setAgencia($agencia) {
		$this->agencia = $agencia;
	}

	/**
	 * Informa se a carteira ou conta bancária está ativa
	 */
	public function getAtiva() {
		return $this->ativa;
	}

	/**
	 * Informa se a carteira ou conta bancária está ativa
	 */
	public function isAtiva() {
		return $this->ativa == 'Y';
	}

	public function setAtiva($ativa) {
		$this->ativa = $ativa;
	}

	public function toArray() {
		$carteira = array();
		$carteira['id'] = $this->getID();
		$carteira['tipo'] = $this->getTipo();
		$carteira['bancoid'] = $this->getBancoID();
		$carteira['descricao'] = $this->getDescricao();
		$carteira['conta'] = $this->getConta();
		$carteira['agencia'] = $this->getAgencia();
		$carteira['ativa'] = $this->getAtiva();
		return $carteira;
	}

	public static function getPeloID($id) {
		$query = DB::$pdo->from('Carteiras')
		                 ->where(array('id' => $id));
		return new ZCarteira($query->fetch());
	}

	private static function validarCampos(&$carteira) {
		$erros = array();
		$carteira['tipo'] = strval($carteira['tipo']);
		if(!in_array($carteira['tipo'], array('Bancaria', 'Financeira')))
			$erros['tipo'] = 'O tipo informado não é válido';
		$carteira['bancoid'] = trim($carteira['bancoid']);
		if(strlen($carteira['bancoid']) == 0)
			$carteira['bancoid'] = null;
		else if(!is_numeric($carteira['bancoid']))
			$erros['bancoid'] = 'O banco não foi informado';
		$carteira['descricao'] = strip_tags(trim($carteira['descricao']));
		if(strlen($carteira['descricao']) == 0)
			$erros['descricao'] = 'A descrição não pode ser vazia';
		$carteira['conta'] = strip_tags(trim($carteira['conta']));
		if(strlen($carteira['conta']) == 0)
			$carteira['conta'] = null;
		$carteira['agencia'] = strip_tags(trim($carteira['agencia']));
		if(strlen($carteira['agencia']) == 0)
			$carteira['agencia'] = null;
		$carteira['ativa'] = trim($carteira['ativa']);
		if(strlen($carteira['ativa']) == 0)
			$carteira['ativa'] = 'N';
		else if(!in_array($carteira['ativa'], array('Y', 'N')))
			$erros['ativa'] = 'A ativa informada não é válida';
		if(!empty($erros))
			throw new ValidationException($erros);
	}

	private static function handleException(&$e) {
		if(stripos($e->getMessage(), 'PRIMARY') !== false)
			throw new ValidationException(array('id' => 'O ID informado já está cadastrado'));
	}

	public static function cadastrar($carteira) {
		$_carteira = $carteira->toArray();
		self::validarCampos($_carteira);
		try {
			$_carteira['id'] = DB::$pdo->insertInto('Carteiras')->values($_carteira)->execute();
		} catch (Exception $e) {
			self::handleException($e);
			throw $e;
		}
		return self::getPeloID($_carteira['id']);
	}

	public static function atualizar($carteira) {
		$_carteira = $carteira->toArray();
		if(!$_carteira['id'])
			throw new ValidationException(array('id' => 'O id da carteira não foi informado'));
		self::validarCampos($_carteira);
		$campos = array(
			'tipo',
			'bancoid',
			'descricao',
			'conta',
			'agencia',
			'ativa',
		);
		try {
			$query = DB::$pdo->update('Carteiras');
			$query = $query->set(array_intersect_key($_carteira, array_flip($campos)));
			$query = $query->where('id', $_carteira['id']);
			$query->execute();
		} catch (Exception $e) {
			self::handleException($e);
			throw $e;
		}
		return self::getPeloID($_carteira['id']);
	}

	public static function excluir($id) {
		if(!$id)
			throw new Exception('Não foi possível excluir a carteira, o id da carteira não foi informado');
		$query = DB::$pdo->deleteFrom('Carteiras')
		                 ->where(array('id' => $id));
		return $query->execute();
	}

	private static function initSearch($busca, $banco_id, $tipo) {
		$query = DB::$pdo->from('Carteiras')
		                 ->orderBy('id ASC');
		$busca = trim($busca);
		if(is_numeric($busca))
			$query = $query->where('numero', $busca);
		else if($busca != '')
			$query = $query->where('descricao LIKE ?', '%'.$busca.'%');
		if(is_numeric($banco_id))
			$query = $query->where('bancoid', intval($banco_id));
		$tipo = trim($tipo);
		if($tipo != '')
			$query = $query->where('tipo', $tipo);
		return $query;
	}

	public static function getTodas($busca = null, $banco_id = null, $tipo = null, $inicio = null, $quantidade = null) {
		$query = self::initSearch($busca, $banco_id, $tipo);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_carteiras = $query->fetchAll();
		$carteiras = array();
		foreach($_carteiras as $carteira)
			$carteiras[] = new ZCarteira($carteira);
		return $carteiras;
	}

	public static function getCount($busca = null, $banco_id = null, $tipo = null) {
		$query = self::initSearch($busca, $banco_id, $tipo);
		return $query->count();
	}

	private static function initSearchDoBancoID($banco_id) {
		return   DB::$pdo->from('Carteiras')
		                 ->where(array('bancoid' => $banco_id))
		                 ->orderBy('id ASC');
	}

	public static function getTodasDoBancoID($banco_id, $inicio = null, $quantidade = null) {
		$query = self::initSearchDoBancoID($banco_id);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_carteiras = $query->fetchAll();
		$carteiras = array();
		foreach($_carteiras as $carteira)
			$carteiras[] = new ZCarteira($carteira);
		return $carteiras;
	}

	public static function getCountDoBancoID($banco_id) {
		$query = self::initSearchDoBancoID($banco_id);
		return $query->count();
	}

}
