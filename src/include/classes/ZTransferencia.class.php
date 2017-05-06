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
class TransferenciaTipo {
	const PEDIDO = 'Pedido';
	const PRODUTO = 'Produto';
}
class TransferenciaModulo {
	const MESA = 'Mesa';
	const COMANDA = 'Comanda';
}

/**
 * Informa a transferência de uma mesa / comanda para outra, ou de um produto para
 * outra mesa / comanda
 */
class ZTransferencia {
	private $id;
	private $pedido_id;
	private $destino_pedido_id;
	private $tipo;
	private $modulo;
	private $mesa_id;
	private $destino_mesa_id;
	private $comanda_id;
	private $destino_comanda_id;
	private $produto_pedido_id;
	private $funcionario_id;
	private $data_hora;

	public function __construct($transferencia = array()) {
		if(is_array($transferencia)) {
			$this->setID($transferencia['id']);
			$this->setPedidoID($transferencia['pedidoid']);
			$this->setDestinoPedidoID($transferencia['destinopedidoid']);
			$this->setTipo($transferencia['tipo']);
			$this->setModulo($transferencia['modulo']);
			$this->setMesaID($transferencia['mesaid']);
			$this->setDestinoMesaID($transferencia['destinomesaid']);
			$this->setComandaID($transferencia['comandaid']);
			$this->setDestinoComandaID($transferencia['destinocomandaid']);
			$this->setProdutoPedidoID($transferencia['produtopedidoid']);
			$this->setFuncionarioID($transferencia['funcionarioid']);
			$this->setDataHora($transferencia['datahora']);
		}
	}

	/**
	 * Identificador da transferência
	 */
	public function getID() {
		return $this->id;
	}

	public function setID($id) {
		$this->id = $id;
	}

	/**
	 * Identificador do pedido de origem
	 */
	public function getPedidoID() {
		return $this->pedido_id;
	}

	public function setPedidoID($pedido_id) {
		$this->pedido_id = $pedido_id;
	}

	/**
	 * Identificador do pedido de destino
	 */
	public function getDestinoPedidoID() {
		return $this->destino_pedido_id;
	}

	public function setDestinoPedidoID($destino_pedido_id) {
		$this->destino_pedido_id = $destino_pedido_id;
	}

	/**
	 * Tipo de transferência, se de mesa/comanda ou de produto
	 */
	public function getTipo() {
		return $this->tipo;
	}

	public function setTipo($tipo) {
		$this->tipo = $tipo;
	}

	/**
	 * Módulo de venda, se mesa ou comanda
	 */
	public function getModulo() {
		return $this->modulo;
	}

	public function setModulo($modulo) {
		$this->modulo = $modulo;
	}

	/**
	 * Identificador da mesa de origem
	 */
	public function getMesaID() {
		return $this->mesa_id;
	}

	public function setMesaID($mesa_id) {
		$this->mesa_id = $mesa_id;
	}

	/**
	 * Mesa de destino da transferência
	 */
	public function getDestinoMesaID() {
		return $this->destino_mesa_id;
	}

	public function setDestinoMesaID($destino_mesa_id) {
		$this->destino_mesa_id = $destino_mesa_id;
	}

	/**
	 * Comanda de origem da transferência
	 */
	public function getComandaID() {
		return $this->comanda_id;
	}

	public function setComandaID($comanda_id) {
		$this->comanda_id = $comanda_id;
	}

	/**
	 * Comanda de destino
	 */
	public function getDestinoComandaID() {
		return $this->destino_comanda_id;
	}

	public function setDestinoComandaID($destino_comanda_id) {
		$this->destino_comanda_id = $destino_comanda_id;
	}

	/**
	 * Item que foi transferido
	 */
	public function getProdutoPedidoID() {
		return $this->produto_pedido_id;
	}

	public function setProdutoPedidoID($produto_pedido_id) {
		$this->produto_pedido_id = $produto_pedido_id;
	}

	/**
	 * Funcionário que transferiu esse pedido/produto
	 */
	public function getFuncionarioID() {
		return $this->funcionario_id;
	}

	public function setFuncionarioID($funcionario_id) {
		$this->funcionario_id = $funcionario_id;
	}

	/**
	 * Data e hora da transferência
	 */
	public function getDataHora() {
		return $this->data_hora;
	}

	public function setDataHora($data_hora) {
		$this->data_hora = $data_hora;
	}

	public function toArray() {
		$transferencia = array();
		$transferencia['id'] = $this->getID();
		$transferencia['pedidoid'] = $this->getPedidoID();
		$transferencia['destinopedidoid'] = $this->getDestinoPedidoID();
		$transferencia['tipo'] = $this->getTipo();
		$transferencia['modulo'] = $this->getModulo();
		$transferencia['mesaid'] = $this->getMesaID();
		$transferencia['destinomesaid'] = $this->getDestinoMesaID();
		$transferencia['comandaid'] = $this->getComandaID();
		$transferencia['destinocomandaid'] = $this->getDestinoComandaID();
		$transferencia['produtopedidoid'] = $this->getProdutoPedidoID();
		$transferencia['funcionarioid'] = $this->getFuncionarioID();
		$transferencia['datahora'] = $this->getDataHora();
		return $transferencia;
	}

	public static function getPeloID($id) {
		$query = DB::$pdo->from('Transferencias')
		                 ->where(array('id' => $id));
		return new ZTransferencia($query->fetch());
	}

	private static function validarCampos(&$transferencia) {
		$erros = array();
		if(!is_numeric($transferencia['pedidoid']))
			$erros['pedidoid'] = 'O pedido de origem não foi informado';
		if(!is_numeric($transferencia['destinopedidoid']))
			$erros['destinopedidoid'] = 'O pedido de destino não foi informado';
		$transferencia['tipo'] = strval($transferencia['tipo']);
		if(!in_array($transferencia['tipo'], array('Pedido', 'Produto')))
			$erros['tipo'] = 'O tipo informado não é válido';
		$transferencia['modulo'] = strval($transferencia['modulo']);
		if(!in_array($transferencia['modulo'], array('Mesa', 'Comanda')))
			$erros['modulo'] = 'O módulo informado não é válido';
		$transferencia['mesaid'] = trim($transferencia['mesaid']);
		if(strlen($transferencia['mesaid']) == 0)
			$transferencia['mesaid'] = null;
		else if(!is_numeric($transferencia['mesaid']))
			$erros['mesaid'] = 'A mesa de origem não foi informada';
		$transferencia['destinomesaid'] = trim($transferencia['destinomesaid']);
		if(strlen($transferencia['destinomesaid']) == 0)
			$transferencia['destinomesaid'] = null;
		else if(!is_numeric($transferencia['destinomesaid']))
			$erros['destinomesaid'] = 'A mesa de destino não foi informada';
		$transferencia['comandaid'] = trim($transferencia['comandaid']);
		if(strlen($transferencia['comandaid']) == 0)
			$transferencia['comandaid'] = null;
		else if(!is_numeric($transferencia['comandaid']))
			$erros['comandaid'] = 'A comanda de origem não foi informada';
		$transferencia['destinocomandaid'] = trim($transferencia['destinocomandaid']);
		if(strlen($transferencia['destinocomandaid']) == 0)
			$transferencia['destinocomandaid'] = null;
		else if(!is_numeric($transferencia['destinocomandaid']))
			$erros['destinocomandaid'] = 'A comanda de destino não foi informada';
		$transferencia['produtopedidoid'] = trim($transferencia['produtopedidoid']);
		if(strlen($transferencia['produtopedidoid']) == 0)
			$transferencia['produtopedidoid'] = null;
		else if(!is_numeric($transferencia['produtopedidoid']))
			$erros['produtopedidoid'] = 'O item transferido não foi informado';
		if(!is_numeric($transferencia['funcionarioid']))
			$erros['funcionarioid'] = 'O funcionário não foi informado';
		$transferencia['datahora'] = date('Y-m-d H:i:s');
		if(!empty($erros))
			throw new ValidationException($erros);
	}

	private static function handleException(&$e) {
		if(stripos($e->getMessage(), 'PRIMARY') !== false)
			throw new ValidationException(array('id' => 'O ID informado já está cadastrado'));
	}

	public static function cadastrar($transferencia) {
		$_transferencia = $transferencia->toArray();
		self::validarCampos($_transferencia);
		try {
			$_transferencia['id'] = DB::$pdo->insertInto('Transferencias')->values($_transferencia)->execute();
		} catch (Exception $e) {
			self::handleException($e);
			throw $e;
		}
		return self::getPeloID($_transferencia['id']);
	}

	private static function initSearch() {
		return   DB::$pdo->from('Transferencias')
		                 ->orderBy('id ASC');
	}

	public static function getTodas($inicio = null, $quantidade = null) {
		$query = self::initSearch();
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_transferencias = $query->fetchAll();
		$transferencias = array();
		foreach($_transferencias as $transferencia)
			$transferencias[] = new ZTransferencia($transferencia);
		return $transferencias;
	}

	public static function getCount() {
		$query = self::initSearch();
		return $query->count();
	}

	private static function initSearchDoPedidoID($pedido_id) {
		return   DB::$pdo->from('Transferencias')
		                 ->where(array('pedidoid' => $pedido_id))
		                 ->orderBy('id ASC');
	}

	public static function getTodasDoPedidoID($pedido_id, $inicio = null, $quantidade = null) {
		$query = self::initSearchDoPedidoID($pedido_id);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_transferencias = $query->fetchAll();
		$transferencias = array();
		foreach($_transferencias as $transferencia)
			$transferencias[] = new ZTransferencia($transferencia);
		return $transferencias;
	}

	public static function getCountDoPedidoID($pedido_id) {
		$query = self::initSearchDoPedidoID($pedido_id);
		return $query->count();
	}

	private static function initSearchDoDestinoPedidoID($destino_pedido_id) {
		return   DB::$pdo->from('Transferencias')
		                 ->where(array('destinopedidoid' => $destino_pedido_id))
		                 ->orderBy('id ASC');
	}

	public static function getTodasDoDestinoPedidoID($destino_pedido_id, $inicio = null, $quantidade = null) {
		$query = self::initSearchDoDestinoPedidoID($destino_pedido_id);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_transferencias = $query->fetchAll();
		$transferencias = array();
		foreach($_transferencias as $transferencia)
			$transferencias[] = new ZTransferencia($transferencia);
		return $transferencias;
	}

	public static function getCountDoDestinoPedidoID($destino_pedido_id) {
		$query = self::initSearchDoDestinoPedidoID($destino_pedido_id);
		return $query->count();
	}

	private static function initSearchDaMesaID($mesa_id) {
		return   DB::$pdo->from('Transferencias')
		                 ->where(array('mesaid' => $mesa_id))
		                 ->orderBy('id ASC');
	}

	public static function getTodasDaMesaID($mesa_id, $inicio = null, $quantidade = null) {
		$query = self::initSearchDaMesaID($mesa_id);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_transferencias = $query->fetchAll();
		$transferencias = array();
		foreach($_transferencias as $transferencia)
			$transferencias[] = new ZTransferencia($transferencia);
		return $transferencias;
	}

	public static function getCountDaMesaID($mesa_id) {
		$query = self::initSearchDaMesaID($mesa_id);
		return $query->count();
	}

	private static function initSearchDaDestinoMesaID($destino_mesa_id) {
		return   DB::$pdo->from('Transferencias')
		                 ->where(array('destinomesaid' => $destino_mesa_id))
		                 ->orderBy('id ASC');
	}

	public static function getTodasDaDestinoMesaID($destino_mesa_id, $inicio = null, $quantidade = null) {
		$query = self::initSearchDaDestinoMesaID($destino_mesa_id);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_transferencias = $query->fetchAll();
		$transferencias = array();
		foreach($_transferencias as $transferencia)
			$transferencias[] = new ZTransferencia($transferencia);
		return $transferencias;
	}

	public static function getCountDaDestinoMesaID($destino_mesa_id) {
		$query = self::initSearchDaDestinoMesaID($destino_mesa_id);
		return $query->count();
	}

	private static function initSearchDoFuncionarioID($funcionario_id) {
		return   DB::$pdo->from('Transferencias')
		                 ->where(array('funcionarioid' => $funcionario_id))
		                 ->orderBy('id ASC');
	}

	public static function getTodasDoFuncionarioID($funcionario_id, $inicio = null, $quantidade = null) {
		$query = self::initSearchDoFuncionarioID($funcionario_id);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_transferencias = $query->fetchAll();
		$transferencias = array();
		foreach($_transferencias as $transferencia)
			$transferencias[] = new ZTransferencia($transferencia);
		return $transferencias;
	}

	public static function getCountDoFuncionarioID($funcionario_id) {
		$query = self::initSearchDoFuncionarioID($funcionario_id);
		return $query->count();
	}

	private static function initSearchDaComandaID($comanda_id) {
		return   DB::$pdo->from('Transferencias')
		                 ->where(array('comandaid' => $comanda_id))
		                 ->orderBy('id ASC');
	}

	public static function getTodasDaComandaID($comanda_id, $inicio = null, $quantidade = null) {
		$query = self::initSearchDaComandaID($comanda_id);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_transferencias = $query->fetchAll();
		$transferencias = array();
		foreach($_transferencias as $transferencia)
			$transferencias[] = new ZTransferencia($transferencia);
		return $transferencias;
	}

	public static function getCountDaComandaID($comanda_id) {
		$query = self::initSearchDaComandaID($comanda_id);
		return $query->count();
	}

	private static function initSearchDaDestinoComandaID($destino_comanda_id) {
		return   DB::$pdo->from('Transferencias')
		                 ->where(array('destinocomandaid' => $destino_comanda_id))
		                 ->orderBy('id ASC');
	}

	public static function getTodasDaDestinoComandaID($destino_comanda_id, $inicio = null, $quantidade = null) {
		$query = self::initSearchDaDestinoComandaID($destino_comanda_id);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_transferencias = $query->fetchAll();
		$transferencias = array();
		foreach($_transferencias as $transferencia)
			$transferencias[] = new ZTransferencia($transferencia);
		return $transferencias;
	}

	public static function getCountDaDestinoComandaID($destino_comanda_id) {
		$query = self::initSearchDaDestinoComandaID($destino_comanda_id);
		return $query->count();
	}

	private static function initSearchDoProdutoPedidoID($produto_pedido_id) {
		return   DB::$pdo->from('Transferencias')
		                 ->where(array('produtopedidoid' => $produto_pedido_id))
		                 ->orderBy('id ASC');
	}

	public static function getTodasDoProdutoPedidoID($produto_pedido_id, $inicio = null, $quantidade = null) {
		$query = self::initSearchDoProdutoPedidoID($produto_pedido_id);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_transferencias = $query->fetchAll();
		$transferencias = array();
		foreach($_transferencias as $transferencia)
			$transferencias[] = new ZTransferencia($transferencia);
		return $transferencias;
	}

	public static function getCountDoProdutoPedidoID($produto_pedido_id) {
		$query = self::initSearchDoProdutoPedidoID($produto_pedido_id);
		return $query->count();
	}

}
