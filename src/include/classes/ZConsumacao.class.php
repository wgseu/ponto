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
class ConsumacaoModulo {
	const MESA = 'Mesa';
	const COMANDA = 'Comanda';
	const AVULSO = 'Avulso';
	const ENTREGA = 'Entrega';
}

/**
 * Permite atribuir uma taxa mínima de compra para cada dia da semana e para cada
 * módulo de vendas
 */
class ZConsumacao {
	private $id;
	private $modulo;
	private $dia;
	private $valor;

	public function __construct($consumacao = array()) {
		if(is_array($consumacao)) {
			$this->setID(isset($consumacao['id'])?$consumacao['id']:null);
			$this->setModulo(isset($consumacao['modulo'])?$consumacao['modulo']:null);
			$this->setDia(isset($consumacao['dia'])?$consumacao['dia']:null);
			$this->setValor(isset($consumacao['valor'])?$consumacao['valor']:null);
		}
	}

	/**
	 * Identificador da consumação
	 */
	public function getID() {
		return $this->id;
	}

	public function setID($id) {
		$this->id = $id;
	}

	/**
	 * Módulo em que a taxa se aplicará
	 */
	public function getModulo() {
		return $this->modulo;
	}

	public function setModulo($modulo) {
		$this->modulo = $modulo;
	}

	/**
	 * Dia que a taxa de consumação será aplicada, 1 para domingo e 7 para sábado
	 */
	public function getDia() {
		return $this->dia;
	}

	public function setDia($dia) {
		$this->dia = $dia;
	}

	/**
	 * Valor da taxa mínima, se o total do pedido for maior, esse valor será ignorado,
	 * caso contrário, esse valor será utilizado como total
	 */
	public function getValor() {
		return $this->valor;
	}

	public function setValor($valor) {
		$this->valor = $valor;
	}

	public function toArray() {
		$consumacao = array();
		$consumacao['id'] = $this->getID();
		$consumacao['modulo'] = $this->getModulo();
		$consumacao['dia'] = $this->getDia();
		$consumacao['valor'] = $this->getValor();
		return $consumacao;
	}

	public static function getDias() {
		return array(
			1 => 'Domingo',
			2 => 'Segunda',
			3 => 'Terça',
			4 => 'Quarta',
			5 => 'Quinta',
			6 => 'Sexta',
			7 => 'Sábado',
		);
	}

	public static function getPeloID($id) {
		$query = DB::$pdo->from('Consumacoes')
		                 ->where(array('id' => $id));
		return new ZConsumacao($query->fetch());
	}

	public static function getPeloModuloDia($modulo, $dia) {
		$query = DB::$pdo->from('Consumacoes')
		                 ->where(array('modulo' => $modulo, 'dia' => $dia));
		return new ZConsumacao($query->fetch());
	}

	private static function validarCampos(&$consumacao) {
		$erros = array();
		$consumacao['modulo'] = strval($consumacao['modulo']);
		if(!in_array($consumacao['modulo'], array('Mesa', 'Comanda', 'Avulso', 'Entrega')))
			$erros['modulo'] = 'O módulo de venda informado não é válido';
		if(!is_numeric($consumacao['dia']))
			$erros['dia'] = 'O dia não foi informado';
		else if($consumacao['dia'] < 1 || $consumacao['dia'] > 7)
			$erros['dia'] = 'O dia informado não é válido';
		if(!is_numeric($consumacao['valor']))
			$erros['valor'] = 'O valor não foi informado';
		else if($consumacao['valor'] < 0)
			$erros['valor'] = 'O valor não pode ser negativo';
		if(!empty($erros))
			throw new ValidationException($erros);
	}

	private static function handleException(&$e) {
		if(stripos($e->getMessage(), 'PRIMARY') !== false)
			throw new ValidationException(array('id' => 'O ID informado já está cadastrado'));
		if(stripos($e->getMessage(), 'UK_Consumacoes_Modulo_Dia') !== false)
			throw new ValidationException(array('dia' => 'O dia informado já está cadastrado'));
	}

	public static function cadastrar($consumacao) {
		$_consumacao = $consumacao->toArray();
		self::validarCampos($_consumacao);
		try {
			$_consumacao['id'] = DB::$pdo->insertInto('Consumacoes')->values($_consumacao)->execute();
		} catch (Exception $e) {
			self::handleException($e);
			throw $e;
		}
		return self::getPeloID($_consumacao['id']);
	}

	public static function atualizar($consumacao) {
		$_consumacao = $consumacao->toArray();
		if(!$_consumacao['id'])
			throw new ValidationException(array('id' => 'O id da consumacao não foi informado'));
		self::validarCampos($_consumacao);
		$campos = array(
			'modulo',
			'dia',
			'valor',
		);
		try {
			$query = DB::$pdo->update('Consumacoes');
			$query = $query->set(array_intersect_key($_consumacao, array_flip($campos)));
			$query = $query->where('id', $_consumacao['id']);
			$query->execute();
		} catch (Exception $e) {
			self::handleException($e);
			throw $e;
		}
		return self::getPeloID($_consumacao['id']);
	}

	public static function excluir($id) {
		if(!$id)
			throw new Exception('Não foi possível excluir a consumacao, o id da consumacao não foi informado');
		$query = DB::$pdo->deleteFrom('Consumacoes')
		                 ->where(array('id' => $id));
		return $query->execute();
	}

	private static function initSearch($modulo, $dia) {
		$query = DB::$pdo->from('Consumacoes')
		                 ->orderBy('modulo ASC, dia ASC');
		$modulo = trim($modulo);
		if($modulo != '')
			$query = $query->where('modulo', $modulo);
		if(is_numeric($dia))
			$query = $query->where('dia', intval($dia));
		return $query;
	}

	public static function getTodas($modulo = null, $dia = null, $inicio = null, $quantidade = null) {
		$query = self::initSearch($modulo, $dia);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_consumacaos = $query->fetchAll();
		$consumacaos = array();
		foreach($_consumacaos as $consumacao)
			$consumacaos[] = new ZConsumacao($consumacao);
		return $consumacaos;
	}

	public static function getCount($modulo = null, $dia = null) {
		$query = self::initSearch($modulo, $dia);
		return $query->count();
	}

}
