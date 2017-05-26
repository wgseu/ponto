<?php

class ZPagamento {
	private $id;
	private $carteira_id;
	private $movimentacao_id;
	private $funcionario_id;
	private $forma_pagto_id;
	private $pedido_id;
	private $pagto_conta_id;
	private $cartao_id;
	private $cheque_id;
	private $conta_id;
	private $credito_id;
	private $total;
	private $parcelas;
	private $valor_parcela;
	private $taxas;
	private $detalhes;
	private $cancelado;
	private $ativo;
	private $data_compensacao;
	private $data_hora;

	public function __construct($pagamento = array()) {
		if(is_array($pagamento)) {
			$this->setID(isset($pagamento['id'])?$pagamento['id']:null);
			$this->setCarteiraID(isset($pagamento['carteiraid'])?$pagamento['carteiraid']:null);
			$this->setMovimentacaoID(isset($pagamento['movimentacaoid'])?$pagamento['movimentacaoid']:null);
			$this->setFuncionarioID(isset($pagamento['funcionarioid'])?$pagamento['funcionarioid']:null);
			$this->setFormaPagtoID(isset($pagamento['formapagtoid'])?$pagamento['formapagtoid']:null);
			$this->setPedidoID(isset($pagamento['pedidoid'])?$pagamento['pedidoid']:null);
			$this->setPagtoContaID(isset($pagamento['pagtocontaid'])?$pagamento['pagtocontaid']:null);
			$this->setCartaoID(isset($pagamento['cartaoid'])?$pagamento['cartaoid']:null);
			$this->setChequeID(isset($pagamento['chequeid'])?$pagamento['chequeid']:null);
			$this->setContaID(isset($pagamento['contaid'])?$pagamento['contaid']:null);
			$this->setCreditoID(isset($pagamento['creditoid'])?$pagamento['creditoid']:null);
			$this->setTotal(isset($pagamento['total'])?$pagamento['total']:null);
			$this->setParcelas(isset($pagamento['parcelas'])?$pagamento['parcelas']:null);
			$this->setValorParcela(isset($pagamento['valorparcela'])?$pagamento['valorparcela']:null);
			$this->setTaxas(isset($pagamento['taxas'])?$pagamento['taxas']:null);
			$this->setDetalhes(isset($pagamento['detalhes'])?$pagamento['detalhes']:null);
			$this->setCancelado(isset($pagamento['cancelado'])?$pagamento['cancelado']:null);
			$this->setAtivo(isset($pagamento['ativo'])?$pagamento['ativo']:null);
			$this->setDataCompensacao(isset($pagamento['datacompensacao'])?$pagamento['datacompensacao']:null);
			$this->setDataHora(isset($pagamento['datahora'])?$pagamento['datahora']:null);
		}
	}

	public function getID() {
		return $this->id;
	}

	public function setID($id) {
		$this->id = $id;
	}

	public function getCarteiraID() {
		return $this->carteira_id;
	}

	public function setCarteiraID($carteira_id) {
		$this->carteira_id = $carteira_id;
	}

	public function getMovimentacaoID() {
		return $this->movimentacao_id;
	}

	public function setMovimentacaoID($movimentacao_id) {
		$this->movimentacao_id = $movimentacao_id;
	}

	public function getFuncionarioID() {
		return $this->funcionario_id;
	}

	public function setFuncionarioID($funcionario_id) {
		$this->funcionario_id = $funcionario_id;
	}

	public function getFormaPagtoID() {
		return $this->forma_pagto_id;
	}

	public function setFormaPagtoID($forma_pagto_id) {
		$this->forma_pagto_id = $forma_pagto_id;
	}

	public function getPedidoID() {
		return $this->pedido_id;
	}

	public function setPedidoID($pedido_id) {
		$this->pedido_id = $pedido_id;
	}

	public function getPagtoContaID() {
		return $this->pagto_conta_id;
	}

	public function setPagtoContaID($pagto_conta_id) {
		$this->pagto_conta_id = $pagto_conta_id;
	}

	public function getCartaoID() {
		return $this->cartao_id;
	}

	public function setCartaoID($cartao_id) {
		$this->cartao_id = $cartao_id;
	}

	public function getChequeID() {
		return $this->cheque_id;
	}

	public function setChequeID($cheque_id) {
		$this->cheque_id = $cheque_id;
	}

	public function getContaID() {
		return $this->conta_id;
	}

	public function setContaID($conta_id) {
		$this->conta_id = $conta_id;
	}

	public function getCreditoID() {
		return $this->credito_id;
	}

	public function setCreditoID($credito_id) {
		$this->credito_id = $credito_id;
	}

	public function getTotal() {
		return $this->total;
	}

	public function setTotal($total) {
		$this->total = $total;
	}

	public function getParcelas() {
		return $this->parcelas;
	}

	public function setParcelas($parcelas) {
		$this->parcelas = $parcelas;
	}

	public function getValorParcela() {
		return $this->valor_parcela;
	}

	public function setValorParcela($valor_parcela) {
		$this->valor_parcela = $valor_parcela;
	}

	public function getTaxas() {
		return $this->taxas;
	}

	public function setTaxas($taxas) {
		$this->taxas = $taxas;
	}

	public function getDetalhes() {
		return $this->detalhes;
	}

	public function setDetalhes($detalhes) {
		$this->detalhes = $detalhes;
	}

	public function getCancelado() {
		return $this->cancelado;
	}

	public function isCancelado() {
		return $this->cancelado == 'Y';
	}

	public function setCancelado($cancelado) {
		$this->cancelado = $cancelado;
	}

	public function getAtivo() {
		return $this->ativo;
	}

	public function isAtivo() {
		return $this->ativo == 'Y';
	}

	public function setAtivo($ativo) {
		$this->ativo = $ativo;
	}

	public function getDataCompensacao() {
		return $this->data_compensacao;
	}

	public function setDataCompensacao($data_compensacao) {
		$this->data_compensacao = $data_compensacao;
	}

	public function getDataHora() {
		return $this->data_hora;
	}

	public function setDataHora($data_hora) {
		$this->data_hora = $data_hora;
	}

	public function toArray() {
		$pagamento = array();
		$pagamento['id'] = $this->getID();
		$pagamento['carteiraid'] = $this->getCarteiraID();
		$pagamento['movimentacaoid'] = $this->getMovimentacaoID();
		$pagamento['funcionarioid'] = $this->getFuncionarioID();
		$pagamento['formapagtoid'] = $this->getFormaPagtoID();
		$pagamento['pedidoid'] = $this->getPedidoID();
		$pagamento['pagtocontaid'] = $this->getPagtoContaID();
		$pagamento['cartaoid'] = $this->getCartaoID();
		$pagamento['chequeid'] = $this->getChequeID();
		$pagamento['contaid'] = $this->getContaID();
		$pagamento['creditoid'] = $this->getCreditoID();
		$pagamento['total'] = $this->getTotal();
		$pagamento['parcelas'] = $this->getParcelas();
		$pagamento['valorparcela'] = $this->getValorParcela();
		$pagamento['taxas'] = $this->getTaxas();
		$pagamento['detalhes'] = $this->getDetalhes();
		$pagamento['cancelado'] = $this->getCancelado();
		$pagamento['ativo'] = $this->getAtivo();
		$pagamento['datacompensacao'] = $this->getDataCompensacao();
		$pagamento['datahora'] = $this->getDataHora();
		return $pagamento;
	}

	public static function getPeloID($id) {
		$query = DB::$pdo->from('Pagamentos')
		                 ->where(array('id' => $id));
		return new ZPagamento($query->fetch());
	}


	public static function getReceitas($sessao_id, $data_inicio = null, $data_fim = null) {
		$query = DB::$pdo->from('Pagamentos pg')
						 ->select(null)
						 ->select('ROUND(SUM(pg.total), 4) as total')
						 ->where('pg.cancelado', 'N')
						 ->where('pg.ativo', 'Y')
						 ->where('(NOT ISNULL(pg.pedidoid) OR (pg.total >= 0 AND NOT ISNULL(pg.pagtocontaid) AND pg.pagtocontaid <> 1))');
		if(!is_null($sessao_id)) {
			$query = $query->leftJoin('Movimentacoes mv ON mv.id = pg.movimentacaoid');
			$query = $query->where(array('mv.sessaoid' => $sessao_id));
		}
		if(!is_null($data_inicio) && is_null($sessao_id))
			$query = $query->where('pg.datahora >= ?', date('Y-m-d', $data_inicio));
		if(!is_null($data_fim) && is_null($sessao_id))
			$query = $query->where('pg.datahora <= ?', date('Y-m-d H:i:s', $data_fim));
		$row = $query->fetch();
		return $row['total'] + 0;
	}

	public static function getDespesas($sessao_id, $data_inicio = null, $data_fim = null) {
		$query = DB::$pdo->from('Pagamentos pg')
						 ->select(null)
						 ->select('ROUND(SUM(pg.total), 4) as total')
						 ->where('pg.cancelado', 'N')
						 ->where('pg.ativo', 'Y')
						 ->where('pg.total < 0')
						 ->where('NOT ISNULL(pg.pagtocontaid)');
		if(!is_null($sessao_id)) {
			$query = $query->leftJoin('Movimentacoes mv ON mv.id = pg.movimentacaoid');
			$query = $query->where(array('mv.sessaoid' => $sessao_id));
		}
		if(!is_null($data_inicio) && is_null($sessao_id))
			$query = $query->where('pg.datahora >= ?', date('Y-m-d', $data_inicio));
		if(!is_null($data_fim) && is_null($sessao_id))
			$query = $query->where('pg.datahora <= ?', date('Y-m-d H:i:s', $data_fim));
		$row = $query->fetch();
		return $row['total'] + 0;
	}

	private static function initGetFaturamento($sessao_id, $mes_inicio, $mes_fim, 
			$dia_inicio, $dia_fim, $pedido_id = null) {
		$data_inicio = null;
		if(!is_null($mes_inicio) && !is_numeric($mes_inicio))
			$data_inicio = strtotime($mes_inicio);
		else if(!is_null($mes_inicio))
			$data_inicio = strtotime(date('Y-m').' '.$mes_inicio.' month');
		if(!is_null($data_inicio) && !is_null($dia_inicio))
			$data_inicio = strtotime(date('Y-m-'.$dia_inicio, $data_inicio));
		$data_fim = null;
		if(!is_null($mes_fim) && !is_numeric($mes_fim))
			$data_fim = strtotime($mes_fim);
		else if(!is_null($mes_fim)) {
			$data_fim = strtotime(date('Y-m').' '.$mes_fim.' month');
			$data_fim = strtotime('last day of this month', $data_fim);
		}
		if(!is_null($data_fim) && !is_null($dia_fim))
			$data_fim = strtotime(date('Y-m-'.$dia_fim, $data_fim));
		$query = DB::$pdo->from('Pagamentos pg')
						 ->select(null)
						 ->select('ROUND(SUM(pg.total), 4) as total')
						 ->where('pg.cancelado', 'N')
						 ->where('pg.ativo', 'Y')
						 ->where('NOT ISNULL(pg.pedidoid)');
		if(!is_null($sessao_id)) {
			$query = $query->leftJoin('Movimentacoes mv ON mv.id = pg.movimentacaoid');
			$query = $query->where(array('mv.sessaoid' => $sessao_id));
		}
		if(!is_null($data_inicio))
			$query = $query->where('pg.datahora >= ?', date('Y-m-d', $data_inicio));
		if(!is_null($data_fim))
			$query = $query->where('pg.datahora <= ?', date('Y-m-d 23:59:59', $data_fim));
		if(!is_null($pedido_id))
			$query = $query->where('pg.pedidoid', $pedido_id);
		return $query;
	}

	public static function getFaturamento($sessao_id, $mes_inicio = null, $mes_fim = null, 
			$dia_inicio = null, $dia_fim = null) {
		$row = self::initGetFaturamento($sessao_id, $mes_inicio, $mes_fim, $dia_inicio, $dia_fim)->fetch();
		return $row['total'] + 0;
	}

	public static function getTotalPedido($pedido_id) {
		if(is_null($pedido_id))
			return 0;
		$row = self::initGetFaturamento(null, null, null, null, null, $pedido_id)->fetch();
		return $row['total'] + 0;
	}

	public static function getPagamentos($sessao_id, $mes_inicio = null, $mes_fim = null, 
			$dia_inicio = null, $dia_fim = null) {
		$query = self::initGetFaturamento($sessao_id, $mes_inicio, $mes_fim, $dia_inicio, $dia_fim);
		$query = $query->leftJoin('Formas_Pagto fp ON fp.id = pg.formapagtoid')
					   ->select('LOWER(fp.tipo) as tipo')
					   ->orderBy('total DESC')
					   ->groupBy('fp.tipo');
		return $query->fetchAll();
	}

	private static function validarCampos(&$pagamento) {
		$erros = array();
		if(!is_numeric($pagamento['carteiraid']))
			$erros['carteiraid'] = 'A carteiraid não foi informada';
		$pagamento['movimentacaoid'] = trim($pagamento['movimentacaoid']);
		if(strlen($pagamento['movimentacaoid']) == 0)
			$pagamento['movimentacaoid'] = null;
		else if(!is_numeric($pagamento['movimentacaoid']))
			$erros['movimentacaoid'] = 'A movimentacaoid não foi informada';
		if(!is_numeric($pagamento['funcionarioid']))
			$erros['funcionarioid'] = 'O funcionarioid não foi informado';
		if(!is_numeric($pagamento['formapagtoid']))
			$erros['formapagtoid'] = 'O formapagtoid não foi informado';
		$pagamento['pedidoid'] = trim($pagamento['pedidoid']);
		if(strlen($pagamento['pedidoid']) == 0)
			$pagamento['pedidoid'] = null;
		else if(!is_numeric($pagamento['pedidoid']))
			$erros['pedidoid'] = 'O pedidoid não foi informado';
		$pagamento['pagtocontaid'] = trim($pagamento['pagtocontaid']);
		if(strlen($pagamento['pagtocontaid']) == 0)
			$pagamento['pagtocontaid'] = null;
		else if(!is_numeric($pagamento['pagtocontaid']))
			$erros['pagtocontaid'] = 'A pagtocontaid não foi informada';
		$pagamento['cartaoid'] = trim($pagamento['cartaoid']);
		if(strlen($pagamento['cartaoid']) == 0)
			$pagamento['cartaoid'] = null;
		else if(!is_numeric($pagamento['cartaoid']))
			$erros['cartaoid'] = 'O cartaoid não foi informado';
		$pagamento['chequeid'] = trim($pagamento['chequeid']);
		if(strlen($pagamento['chequeid']) == 0)
			$pagamento['chequeid'] = null;
		else if(!is_numeric($pagamento['chequeid']))
			$erros['chequeid'] = 'O chequeid não foi informado';
		$pagamento['contaid'] = trim($pagamento['contaid']);
		if(strlen($pagamento['contaid']) == 0)
			$pagamento['contaid'] = null;
		else if(!is_numeric($pagamento['contaid']))
			$erros['contaid'] = 'A contaid não foi informada';
		$pagamento['creditoid'] = trim($pagamento['creditoid']);
		if(strlen($pagamento['creditoid']) == 0)
			$pagamento['creditoid'] = null;
		else if(!is_numeric($pagamento['creditoid']))
			$erros['creditoid'] = 'O creditoid não foi informado';
		if(!is_numeric($pagamento['total']))
			$erros['total'] = 'O total não foi informado';
		if(!is_numeric($pagamento['parcelas']))
			$erros['parcelas'] = 'A parcelas não foi informada';
		if(!is_numeric($pagamento['valorparcela']))
			$erros['valorparcela'] = 'A valorparcela não foi informada';
		if(!is_numeric($pagamento['taxas']))
			$erros['taxas'] = 'A taxas não foi informada';
		else
			$pagamento['taxas'] = floatval($pagamento['taxas']);
		$pagamento['detalhes'] = strip_tags(trim($pagamento['detalhes']));
		if(strlen($pagamento['detalhes']) == 0)
			$pagamento['detalhes'] = null;
		$pagamento['cancelado'] = trim($pagamento['cancelado']);
		if(strlen($pagamento['cancelado']) == 0)
			$pagamento['cancelado'] = 'N';
		else if(!in_array($pagamento['cancelado'], array('Y', 'N')))
			$erros['cancelado'] = 'O cancelado informado não é válido';
		$pagamento['ativo'] = trim($pagamento['ativo']);
		if(strlen($pagamento['ativo']) == 0)
			$pagamento['ativo'] = 'N';
		else if(!in_array($pagamento['ativo'], array('Y', 'N')))
			$erros['ativo'] = 'O ativo informado não é válido';
		$pagamento['datacompensacao'] = date('Y-m-d H:i:s');
		$pagamento['datahora'] = date('Y-m-d H:i:s');
		if(!empty($erros))
			throw new ValidationException($erros);
	}

	private static function handleException(&$e) {
		if(stripos($e->getMessage(), 'PRIMARY') !== false)
			throw new ValidationException(array('id' => 'O ID informado já está cadastrado'));
	}

	public static function cadastrar($pagamento) {
		$_pagamento = $pagamento->toArray();
		self::validarCampos($_pagamento);
		try {
			$_pagamento['id'] = DB::$pdo->insertInto('Pagamentos')->values($_pagamento)->execute();
		} catch (Exception $e) {
			self::handleException($e);
			throw $e;
		}
		return self::getPeloID($_pagamento['id']);
	}

	public static function atualizar($pagamento) {
		$_pagamento = $pagamento->toArray();
		if(!$_pagamento['id'])
			throw new ValidationException(array('id' => 'O id do pagamento não foi informado'));
		self::validarCampos($_pagamento);
		$campos = array(
			'carteiraid',
			'movimentacaoid',
			'funcionarioid',
			'formapagtoid',
			'pedidoid',
			'pagtocontaid',
			'cartaoid',
			'chequeid',
			'contaid',
			'creditoid',
			'total',
			'parcelas',
			'valorparcela',
			'taxas',
			'detalhes',
			'cancelado',
			'ativo',
			'datacompensacao',
			'datahora',
		);
		try {
			$query = DB::$pdo->update('Pagamentos');
			$query = $query->set(array_intersect_key($_pagamento, array_flip($campos)));
			$query = $query->where('id', $_pagamento['id']);
			$query->execute();
		} catch (Exception $e) {
			self::handleException($e);
			throw $e;
		}
		return self::getPeloID($_pagamento['id']);
	}

	private static function initSearch($busca, $formapagto_id, $cartao_id, $funcionario_id, $carteira_id, 
			$estado, $data_inicio, $data_fim) {
		$query = DB::$pdo->from('Pagamentos')
		                 ->orderBy('id DESC');
		$movimentacao_id = null;
		$busca = trim($busca);
		if(is_numeric($busca)) {
			$query = $query->where('pedidoid', intval($busca));
		} else if(substr($busca, 0, 1) == '#') {
			$movimentacao_id = intval(substr($busca, 1));
			$query = $query->where('movimentacaoid', $movimentacao_id);
		} else if($busca != '') {
			$query = $query->where('detalhes LIKE ?', '%'.$busca.'%');
		}
		if(is_numeric($formapagto_id))
			$query = $query->where('formapagtoid', intval($formapagto_id));
		if(is_numeric($cartao_id))
			$query = $query->where('cartaoid', intval($cartao_id));
		if(is_numeric($funcionario_id))
			$query = $query->where('funcionarioid', intval($funcionario_id));
		if(is_numeric($carteira_id))
			$query = $query->where('carteiraid', intval($carteira_id));
		$estado = trim($estado);
		if($estado == 'Valido') {
			$query = $query->where('cancelado', 'N');
		} else if($estado == 'Ativo') {
			$query = $query->where('cancelado', 'N');
			$query = $query->where('Ativo', 'Y');
		} else if($estado == 'Espera') {
			$query = $query->where('cancelado', 'N');
			$query = $query->where('Ativo', 'N');
		} else if($estado == 'Cancelado') {
			$query = $query->where('cancelado', 'Y');
		}
		if(!is_null($data_inicio) && is_null($movimentacao_id))
			$query = $query->where('datahora >= ?', date('Y-m-d', $data_inicio));
		if(!is_null($data_fim) && is_null($movimentacao_id))
			$query = $query->where('datahora <= ?', date('Y-m-d 23:59:59', $data_fim));
		return $query;
	}

	public static function getTodos($busca = null, $formapagto_id = null, $cartao_id = null, $funcionario_id = null, 
			$carteira_id = null, $estado = null, $data_inicio = null, $data_fim = null, $inicio = null, $quantidade = null) {
		$query = self::initSearch($busca, $formapagto_id, $cartao_id, $funcionario_id, $carteira_id, 
				$estado, $data_inicio, $data_fim);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_pagamentos = $query->fetchAll();
		$pagamentos = array();
		foreach($_pagamentos as $pagamento)
			$pagamentos[] = new ZPagamento($pagamento);
		return $pagamentos;
	}

	public static function getCount($busca = null, $formapagto_id = null, $cartao_id = null, $funcionario_id = null, 
			$carteira_id = null, $estado = null, $data_inicio = null, $data_fim = null) {
		$query = self::initSearch($busca, $formapagto_id, $cartao_id, $funcionario_id, $carteira_id, 
				$estado, $data_inicio, $data_fim);
		return $query->count();
	}

	private static function initSearchDoFuncionarioID($funcionario_id) {
		return   DB::$pdo->from('Pagamentos')
		                 ->where(array('funcionarioid' => $funcionario_id))
		                 ->orderBy('id ASC');
	}

	public static function getTodosDoFuncionarioID($funcionario_id, $inicio = null, $quantidade = null) {
		$query = self::initSearchDoFuncionarioID($funcionario_id);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_pagamentos = $query->fetchAll();
		$pagamentos = array();
		foreach($_pagamentos as $pagamento)
			$pagamentos[] = new ZPagamento($pagamento);
		return $pagamentos;
	}

	public static function getCountDoFuncionarioID($funcionario_id) {
		$query = self::initSearchDoFuncionarioID($funcionario_id);
		return $query->count();
	}

	private static function initSearchDoFormaPagtoID($forma_pagto_id) {
		return   DB::$pdo->from('Pagamentos')
		                 ->where(array('formapagtoid' => $forma_pagto_id))
		                 ->orderBy('id ASC');
	}

	public static function getTodosDoFormaPagtoID($forma_pagto_id, $inicio = null, $quantidade = null) {
		$query = self::initSearchDoFormaPagtoID($forma_pagto_id);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_pagamentos = $query->fetchAll();
		$pagamentos = array();
		foreach($_pagamentos as $pagamento)
			$pagamentos[] = new ZPagamento($pagamento);
		return $pagamentos;
	}

	public static function getCountDoFormaPagtoID($forma_pagto_id) {
		$query = self::initSearchDoFormaPagtoID($forma_pagto_id);
		return $query->count();
	}

	private static function initSearchDoPedidoID($pedido_id) {
		return   DB::$pdo->from('Pagamentos')
						 ->where('cancelado', 'N')
		                 ->where('pedidoid', $pedido_id)
		                 ->orderBy('id ASC');
	}

	public static function getTodosDoPedidoID($pedido_id, $inicio = null, $quantidade = null) {
		$query = self::initSearchDoPedidoID($pedido_id);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_pagamentos = $query->fetchAll();
		$pagamentos = array();
		foreach($_pagamentos as $pagamento)
			$pagamentos[] = new ZPagamento($pagamento);
		return $pagamentos;
	}

	public static function getCountDoPedidoID($pedido_id) {
		$query = self::initSearchDoPedidoID($pedido_id);
		return $query->count();
	}

	private static function initSearchDoCartaoID($cartao_id) {
		return   DB::$pdo->from('Pagamentos')
		                 ->where(array('cartaoid' => $cartao_id))
		                 ->orderBy('id ASC');
	}

	public static function getTodosDoCartaoID($cartao_id, $inicio = null, $quantidade = null) {
		$query = self::initSearchDoCartaoID($cartao_id);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_pagamentos = $query->fetchAll();
		$pagamentos = array();
		foreach($_pagamentos as $pagamento)
			$pagamentos[] = new ZPagamento($pagamento);
		return $pagamentos;
	}

	public static function getCountDoCartaoID($cartao_id) {
		$query = self::initSearchDoCartaoID($cartao_id);
		return $query->count();
	}

	private static function initSearchDoChequeID($cheque_id) {
		return   DB::$pdo->from('Pagamentos')
		                 ->where(array('chequeid' => $cheque_id))
		                 ->orderBy('id ASC');
	}

	public static function getTodosDoChequeID($cheque_id, $inicio = null, $quantidade = null) {
		$query = self::initSearchDoChequeID($cheque_id);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_pagamentos = $query->fetchAll();
		$pagamentos = array();
		foreach($_pagamentos as $pagamento)
			$pagamentos[] = new ZPagamento($pagamento);
		return $pagamentos;
	}

	public static function getCountDoChequeID($cheque_id) {
		$query = self::initSearchDoChequeID($cheque_id);
		return $query->count();
	}

	private static function initSearchDaContaID($conta_id) {
		return   DB::$pdo->from('Pagamentos')
		                 ->where(array('contaid' => $conta_id))
		                 ->orderBy('id ASC');
	}

	public static function getTodosDaContaID($conta_id, $inicio = null, $quantidade = null) {
		$query = self::initSearchDaContaID($conta_id);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_pagamentos = $query->fetchAll();
		$pagamentos = array();
		foreach($_pagamentos as $pagamento)
			$pagamentos[] = new ZPagamento($pagamento);
		return $pagamentos;
	}

	public static function getCountDaContaID($conta_id) {
		$query = self::initSearchDaContaID($conta_id);
		return $query->count();
	}

	private static function initSearchDaPagtoContaID($pagto_conta_id) {
		return   DB::$pdo->from('Pagamentos')
		                 ->where(array('pagtocontaid' => $pagto_conta_id))
		                 ->orderBy('id ASC');
	}

	public static function getTodosDaPagtoContaID($pagto_conta_id, $inicio = null, $quantidade = null) {
		$query = self::initSearchDaPagtoContaID($pagto_conta_id);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_pagamentos = $query->fetchAll();
		$pagamentos = array();
		foreach($_pagamentos as $pagamento)
			$pagamentos[] = new ZPagamento($pagamento);
		return $pagamentos;
	}

	public static function getCountDaPagtoContaID($pagto_conta_id) {
		$query = self::initSearchDaPagtoContaID($pagto_conta_id);
		return $query->count();
	}

	private static function initSearchDaMovimentacaoID($movimentacao_id) {
		return   DB::$pdo->from('Pagamentos')
		                 ->where(array('movimentacaoid' => $movimentacao_id))
		                 ->orderBy('id ASC');
	}

	public static function getTodosDaMovimentacaoID($movimentacao_id, $inicio = null, $quantidade = null) {
		$query = self::initSearchDaMovimentacaoID($movimentacao_id);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_pagamentos = $query->fetchAll();
		$pagamentos = array();
		foreach($_pagamentos as $pagamento)
			$pagamentos[] = new ZPagamento($pagamento);
		return $pagamentos;
	}

	public static function getCountDaMovimentacaoID($movimentacao_id) {
		$query = self::initSearchDaMovimentacaoID($movimentacao_id);
		return $query->count();
	}

	private static function initSearchDaCarteiraID($carteira_id) {
		return   DB::$pdo->from('Pagamentos')
		                 ->where(array('carteiraid' => $carteira_id))
		                 ->orderBy('id ASC');
	}

	public static function getTodosDaCarteiraID($carteira_id, $inicio = null, $quantidade = null) {
		$query = self::initSearchDaCarteiraID($carteira_id);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_pagamentos = $query->fetchAll();
		$pagamentos = array();
		foreach($_pagamentos as $pagamento)
			$pagamentos[] = new ZPagamento($pagamento);
		return $pagamentos;
	}

	public static function getCountDaCarteiraID($carteira_id) {
		$query = self::initSearchDaCarteiraID($carteira_id);
		return $query->count();
	}

	private static function initSearchDoCreditoID($credito_id) {
		return   DB::$pdo->from('Pagamentos')
		                 ->where(array('creditoid' => $credito_id))
		                 ->orderBy('id ASC');
	}

	public static function getTodosDoCreditoID($credito_id, $inicio = null, $quantidade = null) {
		$query = self::initSearchDoCreditoID($credito_id);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_pagamentos = $query->fetchAll();
		$pagamentos = array();
		foreach($_pagamentos as $pagamento)
			$pagamentos[] = new ZPagamento($pagamento);
		return $pagamentos;
	}

	public static function getCountDoCreditoID($credito_id) {
		$query = self::initSearchDoCreditoID($credito_id);
		return $query->count();
	}

	private static function initSearchFaturamentos($mes_inicio, $mes_fim, 
			$dia_inicio, $dia_fim) {
		$query = self::initGetFaturamento(null, $mes_inicio, $mes_fim, 
			$dia_inicio, $dia_fim);
		$query = $query->select('DATE_FORMAT(pg.datahora, "%Y-%m-%d") as data');
		$query = $query->groupBy('DATE_FORMAT(pg.datahora, "%Y-%m-%d")');
		return $query;
	}

	public static function getTodosFaturamentos($mes_inicio = null, $mes_fim = null, 
			$dia_inicio = null, $dia_fim = null, $inicio = null, $quantidade = null) {
		$query = self::initSearchFaturamentos($mes_inicio, $mes_fim, 
			$dia_inicio, $dia_fim);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		return $query->fetchAll();
	}

	public static function getCountFaturamentos($mes_inicio = null, $mes_fim = null, 
			$dia_inicio = null, $dia_fim = null) {
		$query = self::initSearchFaturamentos($mes_inicio, $mes_fim, 
			$dia_inicio, $dia_fim);
		return $query->count();
	}

}
