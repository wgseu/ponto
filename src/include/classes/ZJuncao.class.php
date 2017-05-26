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
class JuncaoEstado {
	const ASSOCIADO = 'Associado';
	const LIBERADO = 'Liberado';
	const CANCELADO = 'Cancelado';
}

/**
 * Junções de mesas, informa quais mesas estão juntas ao pedido
 */
class ZJuncao {
	private $id;
	private $mesa_id;
	private $pedido_id;
	private $estado;
	private $data_movimento;

	public function __construct($juncao = array()) {
		if(is_array($juncao)) {
			$this->setID(isset($juncao['id'])?$juncao['id']:null);
			$this->setMesaID(isset($juncao['mesaid'])?$juncao['mesaid']:null);
			$this->setPedidoID(isset($juncao['pedidoid'])?$juncao['pedidoid']:null);
			$this->setEstado(isset($juncao['estado'])?$juncao['estado']:null);
			$this->setDataMovimento(isset($juncao['datamovimento'])?$juncao['datamovimento']:null);
		}
	}

	/**
	 * Identificador da junção
	 */
	public function getID() {
		return $this->id;
	}

	public function setID($id) {
		$this->id = $id;
	}

	/**
	 * Mesa que está junta ao pedido
	 */
	public function getMesaID() {
		return $this->mesa_id;
	}

	public function setMesaID($mesa_id) {
		$this->mesa_id = $mesa_id;
	}

	/**
	 * Pedido a qual a mesa está junta, o pedido deve ser de uma mesa
	 */
	public function getPedidoID() {
		return $this->pedido_id;
	}

	public function setPedidoID($pedido_id) {
		$this->pedido_id = $pedido_id;
	}

	/**
	 * Estado a junção da mesa. Associado: a mesa está junta ao pedido, Liberado: A
	 * mesa está livre, Cancelado: A mesa está liberada
	 */
	public function getEstado() {
		return $this->estado;
	}

	public function setEstado($estado) {
		$this->estado = $estado;
	}

	/**
	 * Data e hora da junção das mesas
	 */
	public function getDataMovimento() {
		return $this->data_movimento;
	}

	public function setDataMovimento($data_movimento) {
		$this->data_movimento = $data_movimento;
	}

	public function toArray() {
		$juncao = array();
		$juncao['id'] = $this->getID();
		$juncao['mesaid'] = $this->getMesaID();
		$juncao['pedidoid'] = $this->getPedidoID();
		$juncao['estado'] = $this->getEstado();
		$juncao['datamovimento'] = $this->getDataMovimento();
		return $juncao;
	}

	public static function getPeloID($id) {
		$query = DB::$pdo->from('Juncoes')
		                 ->where(array('id' => $id));
		return new ZJuncao($query->fetch());
	}

	private static function validarCampos(&$juncao) {
		$erros = array();
		if(!is_numeric($juncao['mesaid']))
			$erros['mesaid'] = 'A mesa não foi informada';
		if(!is_numeric($juncao['pedidoid']))
			$erros['pedidoid'] = 'O pedido não foi informado';
		$juncao['estado'] = strval($juncao['estado']);
		if(!in_array($juncao['estado'], array('Associado', 'Liberado', 'Cancelado')))
			$erros['estado'] = 'O estado informado não é válido';
		$juncao['datamovimento'] = date('Y-m-d H:i:s');
		if(!empty($erros))
			throw new ValidationException($erros);
	}

	private static function handleException(&$e) {
		if(stripos($e->getMessage(), 'PRIMARY') !== false)
			throw new ValidationException(array('id' => 'O ID informado já está cadastrado'));
	}

	public static function cadastrar($juncao) {
		$_juncao = $juncao->toArray();
		self::validarCampos($_juncao);
		try {
			$_juncao['id'] = DB::$pdo->insertInto('Juncoes')->values($_juncao)->execute();
		} catch (Exception $e) {
			self::handleException($e);
			throw $e;
		}
		return self::getPeloID($_juncao['id']);
	}

	public static function atualizar($juncao) {
		$_juncao = $juncao->toArray();
		if(!$_juncao['id'])
			throw new ValidationException(array('id' => 'O id da juncao não foi informado'));
		self::validarCampos($_juncao);
		$campos = array(
			'mesaid',
			'pedidoid',
			'estado',
			'datamovimento',
		);
		try {
			$query = DB::$pdo->update('Juncoes');
			$query = $query->set(array_intersect_key($_juncao, array_flip($campos)));
			$query = $query->where('id', $_juncao['id']);
			$query->execute();
		} catch (Exception $e) {
			self::handleException($e);
			throw $e;
		}
		return self::getPeloID($_juncao['id']);
	}

	private static function initSearch() {
		return   DB::$pdo->from('Juncoes')
		                 ->orderBy('id ASC');
	}

	public static function getTodas($inicio = null, $quantidade = null) {
		$query = self::initSearch();
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_juncaos = $query->fetchAll();
		$juncaos = array();
		foreach($_juncaos as $juncao)
			$juncaos[] = new ZJuncao($juncao);
		return $juncaos;
	}

	public static function getCount() {
		$query = self::initSearch();
		return $query->count();
	}

	private static function initSearchDaMesaID($mesa_id) {
		return   DB::$pdo->from('Juncoes')
		                 ->where(array('mesaid' => $mesa_id))
		                 ->orderBy('id ASC');
	}

	public static function getTodasDaMesaID($mesa_id, $inicio = null, $quantidade = null) {
		$query = self::initSearchDaMesaID($mesa_id);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_juncaos = $query->fetchAll();
		$juncaos = array();
		foreach($_juncaos as $juncao)
			$juncaos[] = new ZJuncao($juncao);
		return $juncaos;
	}

	public static function getCountDaMesaID($mesa_id) {
		$query = self::initSearchDaMesaID($mesa_id);
		return $query->count();
	}

	private static function initSearchDoPedidoID($pedido_id) {
		return   DB::$pdo->from('Juncoes')
		                 ->where(array('pedidoid' => $pedido_id))
		                 ->orderBy('id ASC');
	}

	public static function getTodasDoPedidoID($pedido_id, $inicio = null, $quantidade = null) {
		$query = self::initSearchDoPedidoID($pedido_id);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_juncaos = $query->fetchAll();
		$juncaos = array();
		foreach($_juncaos as $juncao)
			$juncaos[] = new ZJuncao($juncao);
		return $juncaos;
	}

	public static function getCountDoPedidoID($pedido_id) {
		$query = self::initSearchDoPedidoID($pedido_id);
		return $query->count();
	}

}
