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
 * Créditos de clientes
 */
class ZCredito {
	private $id;
	private $cliente_id;
	private $valor;
	private $detalhes;
	private $funcionario_id;
	private $cancelado;
	private $data_cadastro;

	public function __construct($credito = array()) {
		if(is_array($credito)) {
			$this->setID(isset($credito['id'])?$credito['id']:null);
			$this->setClienteID(isset($credito['clienteid'])?$credito['clienteid']:null);
			$this->setValor(isset($credito['valor'])?$credito['valor']:null);
			$this->setDetalhes(isset($credito['detalhes'])?$credito['detalhes']:null);
			$this->setFuncionarioID(isset($credito['funcionarioid'])?$credito['funcionarioid']:null);
			$this->setCancelado(isset($credito['cancelado'])?$credito['cancelado']:null);
			$this->setDataCadastro(isset($credito['datacadastro'])?$credito['datacadastro']:null);
		}
	}

	/**
	 * Identificador do crédito
	 */
	public function getID() {
		return $this->id;
	}

	public function setID($id) {
		$this->id = $id;
	}

	/**
	 * Cliente a qual o crédito pertence
	 */
	public function getClienteID() {
		return $this->cliente_id;
	}

	public function setClienteID($cliente_id) {
		$this->cliente_id = $cliente_id;
	}

	/**
	 * Valor do crédito
	 */
	public function getValor() {
		return $this->valor;
	}

	public function setValor($valor) {
		$this->valor = $valor;
	}

	/**
	 * Detalhes do crédito, justificativa do crédito
	 */
	public function getDetalhes() {
		return $this->detalhes;
	}

	public function setDetalhes($detalhes) {
		$this->detalhes = $detalhes;
	}

	/**
	 * Funcionário que cadastrou o crédito
	 */
	public function getFuncionarioID() {
		return $this->funcionario_id;
	}

	public function setFuncionarioID($funcionario_id) {
		$this->funcionario_id = $funcionario_id;
	}

	/**
	 * Informa se o crédito foi cancelado
	 */
	public function getCancelado() {
		return $this->cancelado;
	}

	/**
	 * Informa se o crédito foi cancelado
	 */
	public function isCancelado() {
		return $this->cancelado == 'Y';
	}

	public function setCancelado($cancelado) {
		$this->cancelado = $cancelado;
	}

	/**
	 * Data de cadastro do crédito
	 */
	public function getDataCadastro() {
		return $this->data_cadastro;
	}

	public function setDataCadastro($data_cadastro) {
		$this->data_cadastro = $data_cadastro;
	}

	public function toArray() {
		$credito = array();
		$credito['id'] = $this->getID();
		$credito['clienteid'] = $this->getClienteID();
		$credito['valor'] = $this->getValor();
		$credito['detalhes'] = $this->getDetalhes();
		$credito['funcionarioid'] = $this->getFuncionarioID();
		$credito['cancelado'] = $this->getCancelado();
		$credito['datacadastro'] = $this->getDataCadastro();
		return $credito;
	}

	public static function getPeloID($id) {
		$query = DB::$pdo->from('Creditos')
		                 ->where(array('id' => $id));
		return new ZCredito($query->fetch());
	}

	private static function validarCampos(&$credito) {
		$erros = array();
		if(!is_numeric($credito['clienteid']))
			$erros['clienteid'] = 'O cliente não foi informado';
		if(!is_numeric($credito['valor']))
			$erros['valor'] = 'O valor não foi informado';
		else if(is_equal($credito['valor'], 0))
			$erros['valor'] = 'O valor não pode ser nulo';
		$credito['detalhes'] = strip_tags(trim($credito['detalhes']));
		if(strlen($credito['detalhes']) == 0)
			$credito['detalhes'] = null;
		if(!is_numeric($credito['funcionarioid']))
			$erros['funcionarioid'] = 'O funcionário não foi informado';
		$credito['cancelado'] = trim($credito['cancelado']);
		if(strlen($credito['cancelado']) == 0)
			$credito['cancelado'] = 'N';
		else if(!in_array($credito['cancelado'], array('Y', 'N')))
			$erros['cancelado'] = 'O cancelado informado não é válido';
		$credito['datacadastro'] = date('Y-m-d H:i:s');
		if(!empty($erros))
			throw new ValidationException($erros);
	}

	private static function handleException(&$e) {
		if(stripos($e->getMessage(), 'PRIMARY') !== false)
			throw new ValidationException(array('id' => 'O ID informado já está cadastrado'));
	}

	public static function cadastrar($credito) {
		$_credito = $credito->toArray();
		self::validarCampos($_credito);
		try {
			$_credito['id'] = DB::$pdo->insertInto('Creditos')->values($_credito)->execute();
		} catch (Exception $e) {
			self::handleException($e);
			throw $e;
		}
		return self::getPeloID($_credito['id']);
	}

	public static function atualizar($credito) {
		if($credito->isCancelado())
			throw new ValidationException(array('cancelado' => 'O credito está cancelado e não pode mais ser alterado'));
		$_credito = $credito->toArray();
		if(!$_credito['id'])
			throw new ValidationException(array('id' => 'O id do credito não foi informado'));
		self::validarCampos($_credito);
		$campos = array(
			'clienteid',
			'valor',
			'detalhes',
			'funcionarioid',
			'cancelado',
		);
		try {
			$query = DB::$pdo->update('Creditos');
			$query = $query->set(array_intersect_key($_credito, array_flip($campos)));
			$query = $query->where('id', $_credito['id']);
			$query->execute();
		} catch (Exception $e) {
			self::handleException($e);
			throw $e;
		}
		return self::getPeloID($_credito['id']);
	}

	public function cancelar() {
		if($this->isCancelado())
			throw new Exception('O crédito informado já está cancelado');
		$query = DB::$pdo->update('Creditos')
						 ->set('cancelado', 'Y')
						 ->where('id', $this->getID());
		$query->execute();
		$this->setCancelado('Y');
	}

	public static function excluir($id) {
		if(!$id)
			throw new Exception('Não foi possível excluir o credito, o id do credito não foi informado');
		$query = DB::$pdo->deleteFrom('Creditos')
		                 ->where(array('id' => $id));
		return $query->execute();
	}

	private static function initSearch($busca, $cliente_id, $cancelado) {
		$query = DB::$pdo->from('Creditos')
		                 ->orderBy('id DESC');
		$busca = trim($busca);
		if($busca != '')
			$query = $query->where('detalhes LIKE ?', '%'.$busca.'%');
		if(is_numeric($cliente_id))
			$query = $query->where('clienteid', intval($cliente_id));
		$cancelado = trim($cancelado);
		if($cancelado != '')
			$query = $query->where('cancelado', $cancelado);
		return $query;
	}

	public static function getTodos($busca = null, $cliente_id = null, $cancelado = null, $inicio = null, $quantidade = null) {
		$query = self::initSearch($busca, $cliente_id, $cancelado);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_creditos = $query->fetchAll();
		$creditos = array();
		foreach($_creditos as $credito)
			$creditos[] = new ZCredito($credito);
		return $creditos;
	}

	public static function getCount($busca, $cliente_id, $cancelado) {
		$query = self::initSearch($busca, $cliente_id, $cancelado);
		return $query->count();
	}

	private static function initSearchDoClienteID($cliente_id) {
		return   DB::$pdo->from('Creditos')
		                 ->where(array('clienteid' => $cliente_id))
		                 ->orderBy('id ASC');
	}

	public static function getTodosDoClienteID($cliente_id, $inicio = null, $quantidade = null) {
		$query = self::initSearchDoClienteID($cliente_id);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_creditos = $query->fetchAll();
		$creditos = array();
		foreach($_creditos as $credito)
			$creditos[] = new ZCredito($credito);
		return $creditos;
	}

	public static function getCountDoClienteID($cliente_id) {
		$query = self::initSearchDoClienteID($cliente_id);
		return $query->count();
	}

	private static function initSearchDoFuncionarioID($funcionario_id) {
		return   DB::$pdo->from('Creditos')
		                 ->where(array('funcionarioid' => $funcionario_id))
		                 ->orderBy('id ASC');
	}

	public static function getTodosDoFuncionarioID($funcionario_id, $inicio = null, $quantidade = null) {
		$query = self::initSearchDoFuncionarioID($funcionario_id);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_creditos = $query->fetchAll();
		$creditos = array();
		foreach($_creditos as $credito)
			$creditos[] = new ZCredito($credito);
		return $creditos;
	}

	public static function getCountDoFuncionarioID($funcionario_id) {
		$query = self::initSearchDoFuncionarioID($funcionario_id);
		return $query->count();
	}

}
