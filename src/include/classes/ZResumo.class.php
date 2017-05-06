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
class ResumoTipo {
	const DINHEIRO = 'Dinheiro';
	const CARTAO = 'Cartao';
	const CHEQUE = 'Cheque';
	const CONTA = 'Conta';
	const CREDITO = 'Credito';
	const TRANSFERENCIA = 'Transferencia';
}

/**
 * Resumo de fechamento de caixa, informa o valor contado no fechamento do caixa
 * para cada forma de pagamento
 */
class ZResumo {
	private $id;
	private $movimentacao_id;
	private $tipo;
	private $cartao_id;
	private $valor;

	public function __construct($resumo = array()) {
		if(is_array($resumo)) {
			$this->setID($resumo['id']);
			$this->setMovimentacaoID($resumo['movimentacaoid']);
			$this->setTipo($resumo['tipo']);
			$this->setCartaoID($resumo['cartaoid']);
			$this->setValor($resumo['valor']);
		}
	}

	/**
	 * Identificador do resumo
	 */
	public function getID() {
		return $this->id;
	}

	public function setID($id) {
		$this->id = $id;
	}

	/**
	 * Movimentação do caixa referente ao resumo
	 */
	public function getMovimentacaoID() {
		return $this->movimentacao_id;
	}

	public function setMovimentacaoID($movimentacao_id) {
		$this->movimentacao_id = $movimentacao_id;
	}

	/**
	 * Tipo de pagamento do resumo
	 */
	public function getTipo() {
		return $this->tipo;
	}

	public function setTipo($tipo) {
		$this->tipo = $tipo;
	}

	/**
	 * Cartão da forma de pagamento
	 */
	public function getCartaoID() {
		return $this->cartao_id;
	}

	public function setCartaoID($cartao_id) {
		$this->cartao_id = $cartao_id;
	}

	/**
	 * Valor que foi contado ao fechar o caixa
	 */
	public function getValor() {
		return $this->valor;
	}

	public function setValor($valor) {
		$this->valor = $valor;
	}

	public function toArray() {
		$resumo = array();
		$resumo['id'] = $this->getID();
		$resumo['movimentacaoid'] = $this->getMovimentacaoID();
		$resumo['tipo'] = $this->getTipo();
		$resumo['cartaoid'] = $this->getCartaoID();
		$resumo['valor'] = $this->getValor();
		return $resumo;
	}

	public static function getPeloID($id) {
		$query = DB::$pdo->from('Resumos')
		                 ->where(array('id' => $id));
		return new ZResumo($query->fetch());
	}

	public static function getPelaMovimentacaoIDTipoCartaoID($movimentacao_id, $tipo, $cartao_id) {
		$query = DB::$pdo->from('Resumos')
		                 ->where(array('movimentacaoid' => $movimentacao_id, 'tipo' => $tipo, 'cartaoid' => $cartao_id));
		return new ZResumo($query->fetch());
	}

	private static function validarCampos(&$resumo) {
		$erros = array();
		if(!is_numeric($resumo['movimentacaoid']))
			$erros['movimentacaoid'] = 'A movimentação não foi informada';
		$resumo['tipo'] = strval($resumo['tipo']);
		if(!in_array($resumo['tipo'], array('Dinheiro', 'Cartao', 'Cheque', 'Conta', 'Credito', 'Transferencia')))
			$erros['tipo'] = 'O tipo informado não é válido';
		$resumo['cartaoid'] = trim($resumo['cartaoid']);
		if(strlen($resumo['cartaoid']) == 0)
			$resumo['cartaoid'] = null;
		else if(!is_numeric($resumo['cartaoid']))
			$erros['cartaoid'] = 'O cartão não foi informado';
		if(!is_numeric($resumo['valor']))
			$erros['valor'] = 'O valor não foi informado';
		if(!empty($erros))
			throw new ValidationException($erros);
	}

	private static function handleException(&$e) {
		if(stripos($e->getMessage(), 'PRIMARY') !== false)
			throw new ValidationException(array('id' => 'O ID informado já está cadastrado'));
		if(stripos($e->getMessage(), 'UK_Resumos_MovimentacaoID_Tipo_CartaoID') !== false)
			throw new ValidationException(array('cartaoid' => 'O cartão informado já está cadastrado'));
	}

	public static function cadastrar($resumo) {
		$_resumo = $resumo->toArray();
		self::validarCampos($_resumo);
		try {
			$_resumo['id'] = DB::$pdo->insertInto('Resumos')->values($_resumo)->execute();
		} catch (Exception $e) {
			self::handleException($e);
			throw $e;
		}
		return self::getPeloID($_resumo['id']);
	}

	public static function atualizar($resumo) {
		$_resumo = $resumo->toArray();
		if(!$_resumo['id'])
			throw new ValidationException(array('id' => 'O id do resumo não foi informado'));
		self::validarCampos($_resumo);
		$campos = array(
			'movimentacaoid',
			'tipo',
			'cartaoid',
			'valor',
		);
		try {
			$query = DB::$pdo->update('Resumos');
			$query = $query->set(array_intersect_key($_resumo, array_flip($campos)));
			$query = $query->where('id', $_resumo['id']);
			$query->execute();
		} catch (Exception $e) {
			self::handleException($e);
			throw $e;
		}
		return self::getPeloID($_resumo['id']);
	}

	private static function initSearch() {
		return   DB::$pdo->from('Resumos')
		                 ->orderBy('id ASC');
	}

	public static function getTodos($inicio = null, $quantidade = null) {
		$query = self::initSearch();
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_resumos = $query->fetchAll();
		$resumos = array();
		foreach($_resumos as $resumo)
			$resumos[] = new ZResumo($resumo);
		return $resumos;
	}

	public static function getCount() {
		$query = self::initSearch();
		return $query->count();
	}

	private static function initSearchDaMovimentacaoID($movimentacao_id) {
		return   DB::$pdo->from('Resumos')
		                 ->where(array('movimentacaoid' => $movimentacao_id))
		                 ->orderBy('id ASC');
	}

	public static function getTodosDaMovimentacaoID($movimentacao_id, $inicio = null, $quantidade = null) {
		$query = self::initSearchDaMovimentacaoID($movimentacao_id);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_resumos = $query->fetchAll();
		$resumos = array();
		foreach($_resumos as $resumo)
			$resumos[] = new ZResumo($resumo);
		return $resumos;
	}

	public static function getCountDaMovimentacaoID($movimentacao_id) {
		$query = self::initSearchDaMovimentacaoID($movimentacao_id);
		return $query->count();
	}

	private static function initSearchDoCartaoID($cartao_id) {
		return   DB::$pdo->from('Resumos')
		                 ->where(array('cartaoid' => $cartao_id))
		                 ->orderBy('id ASC');
	}

	public static function getTodosDoCartaoID($cartao_id, $inicio = null, $quantidade = null) {
		$query = self::initSearchDoCartaoID($cartao_id);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_resumos = $query->fetchAll();
		$resumos = array();
		foreach($_resumos as $resumo)
			$resumos[] = new ZResumo($resumo);
		return $resumos;
	}

	public static function getCountDoCartaoID($cartao_id) {
		$query = self::initSearchDoCartaoID($cartao_id);
		return $query->count();
	}

}
