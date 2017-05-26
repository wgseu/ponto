<?php

class ZConta {
	private $id;
	private $classificacao_id;
	private $funcionario_id;
	private $sub_classificacao_id;
	private $cliente_id;
	private $pedido_id;
	private $descricao;
	private $valor;
	private $acrescimo;
	private $multa;
	private $juros;
	private $auto_acrescimo;
	private $vencimento;
	private $data_emissao;
	private $numero_doc;
	private $anexo_caminho;
	private $cancelada;
	private $data_pagamento;
	private $data_cadastro;

	public function __construct($conta = array()) {
		if(is_array($conta)) {
			$this->setID(isset($conta['id'])?$conta['id']:null);
			$this->setClassificacaoID(isset($conta['classificacaoid'])?$conta['classificacaoid']:null);
			$this->setFuncionarioID(isset($conta['funcionarioid'])?$conta['funcionarioid']:null);
			$this->setSubClassificacaoID(isset($conta['subclassificacaoid'])?$conta['subclassificacaoid']:null);
			$this->setClienteID(isset($conta['clienteid'])?$conta['clienteid']:null);
			$this->setPedidoID(isset($conta['pedidoid'])?$conta['pedidoid']:null);
			$this->setDescricao(isset($conta['descricao'])?$conta['descricao']:null);
			$this->setValor(isset($conta['valor'])?$conta['valor']:null);
			$this->setAcrescimo(isset($conta['acrescimo'])?$conta['acrescimo']:null);
			$this->setMulta(isset($conta['multa'])?$conta['multa']:null);
			$this->setJuros(isset($conta['juros'])?$conta['juros']:null);
			$this->setAutoAcrescimo(isset($conta['autoacrescimo'])?$conta['autoacrescimo']:null);
			$this->setVencimento(isset($conta['vencimento'])?$conta['vencimento']:null);
			$this->setDataEmissao(isset($conta['dataemissao'])?$conta['dataemissao']:null);
			$this->setNumeroDoc(isset($conta['numerodoc'])?$conta['numerodoc']:null);
			$this->setAnexoCaminho(isset($conta['anexocaminho'])?$conta['anexocaminho']:null);
			$this->setCancelada(isset($conta['cancelada'])?$conta['cancelada']:null);
			$this->setDataPagamento(isset($conta['datapagamento'])?$conta['datapagamento']:null);
			$this->setDataCadastro(isset($conta['datacadastro'])?$conta['datacadastro']:null);
		}
	}

	public function getID() {
		return $this->id;
	}

	public function setID($id) {
		$this->id = $id;
	}

	public function getClassificacaoID() {
		return $this->classificacao_id;
	}

	public function setClassificacaoID($classificacao_id) {
		$this->classificacao_id = $classificacao_id;
	}

	public function getFuncionarioID() {
		return $this->funcionario_id;
	}

	public function setFuncionarioID($funcionario_id) {
		$this->funcionario_id = $funcionario_id;
	}

	public function getSubClassificacaoID() {
		return $this->sub_classificacao_id;
	}

	public function setSubClassificacaoID($sub_classificacao_id) {
		$this->sub_classificacao_id = $sub_classificacao_id;
	}

	public function getClienteID() {
		return $this->cliente_id;
	}

	public function setClienteID($cliente_id) {
		$this->cliente_id = $cliente_id;
	}

	public function getPedidoID() {
		return $this->pedido_id;
	}

	public function setPedidoID($pedido_id) {
		$this->pedido_id = $pedido_id;
	}

	public function getDescricao() {
		return $this->descricao;
	}

	public function setDescricao($descricao) {
		$this->descricao = $descricao;
	}

	public function getValor() {
		return $this->valor;
	}

	public function setValor($valor) {
		$this->valor = $valor;
	}

	public function getAcrescimo() {
		return $this->acrescimo;
	}

	public function setAcrescimo($acrescimo) {
		$this->acrescimo = $acrescimo;
	}

	public function getMulta() {
		return $this->multa;
	}

	public function setMulta($multa) {
		$this->multa = $multa;
	}

	public function getJuros() {
		return $this->juros;
	}

	public function setJuros($juros) {
		$this->juros = $juros;
	}

	public function getAutoAcrescimo() {
		return $this->auto_acrescimo;
	}

	public function isAutoAcrescimo() {
		return $this->auto_acrescimo == 'Y';
	}

	public function setAutoAcrescimo($auto_acrescimo) {
		$this->auto_acrescimo = $auto_acrescimo;
	}

	public function getVencimento() {
		return $this->vencimento;
	}

	public function setVencimento($vencimento) {
		$this->vencimento = $vencimento;
	}

	public function getDataEmissao() {
		return $this->data_emissao;
	}

	public function setDataEmissao($data_emissao) {
		$this->data_emissao = $data_emissao;
	}

	public function getNumeroDoc() {
		return $this->numero_doc;
	}

	public function setNumeroDoc($numero_doc) {
		$this->numero_doc = $numero_doc;
	}

	public function getAnexoCaminho() {
		return $this->anexo_caminho;
	}

	public function setAnexoCaminho($anexo_caminho) {
		$this->anexo_caminho = $anexo_caminho;
	}

	public function getCancelada() {
		return $this->cancelada;
	}

	public function isCancelada() {
		return $this->cancelada == 'Y';
	}

	public function setCancelada($cancelada) {
		$this->cancelada = $cancelada;
	}

	public function getDataPagamento() {
		return $this->data_pagamento;
	}

	public function setDataPagamento($data_pagamento) {
		$this->data_pagamento = $data_pagamento;
	}

	public function getDataCadastro() {
		return $this->data_cadastro;
	}

	public function setDataCadastro($data_cadastro) {
		$this->data_cadastro = $data_cadastro;
	}

	// extra
	public function getAcrescimoAtual() {
		$datapagto = strtotime($this->getDataPagamento());
		if($datapagto === false)
			$datapagto = time();
		$vencimento = strtotime("tomorrow", strtotime($this->getVencimento())) - 1;
		$is_vencida = !is_null($this->getVencimento()) && $vencimento < $datapagto;
		if(!$is_vencida)
			return $this->getAcrescimo();
		if(is_equal($this->getJuros(), 0, 0.000005)) {
			if(abs($this->getAcrescimo()) < abs($this->getMulta())) // ainda não incluiu a multa
				return $this->getAcrescimo() + $this->getMulta();
			return $this->getAcrescimo();
		}
		if(!is_numeric($this->getID())) {
			$info = array(
				'quantidade' => 0,
				'despesas' => 0,
				'receitas' => 0,
				'pago' => 0,
				'recebido' => 0,
				'datapagto' => null,
			);
		} else
			$info = self::getTotalAbertas($this->getID());
		$is_paga = is_equal($this->getValor() + $this->getAcrescimo(), $info['pago']);
		if($is_paga)
			return $this->getAcrescimo();
		$restante = $this->getValor() + $this->getAcrescimo() - $info['pago'];
		$datavenc = strtotime($info['datapagto']);
		if($datavenc === false)
			$datavenc = strtotime($this->getVencimento());
		$dias = floor(($datapagto - $datavenc) / (60 * 60 * 24));
		$juros = $restante * pow(1 + $this->getJuros(), $dias) - $restante;
		if(abs($this->getAcrescimo()) < abs($this->getMulta())) // ainda não incluiu a multa
			return $this->getAcrescimo() + $this->getMulta() + $juros;
		return $this->getAcrescimo() + $juros;
	}

	public function getTotal() {
		if(!$this->isAutoAcrescimo())
			return $this->valor + $this->acrescimo;
		return $this->valor + $this->getAcrescimoAtual();
	}

	public function toArray() {
		$conta = array();
		$conta['id'] = $this->getID();
		$conta['classificacaoid'] = $this->getClassificacaoID();
		$conta['funcionarioid'] = $this->getFuncionarioID();
		$conta['subclassificacaoid'] = $this->getSubClassificacaoID();
		$conta['clienteid'] = $this->getClienteID();
		$conta['pedidoid'] = $this->getPedidoID();
		$conta['descricao'] = $this->getDescricao();
		$conta['valor'] = $this->getValor();
		$conta['acrescimo'] = $this->getAcrescimo();
		$conta['multa'] = $this->getMulta();
		$conta['juros'] = $this->getJuros();
		$conta['autoacrescimo'] = $this->getAutoAcrescimo();
		$conta['vencimento'] = $this->getVencimento();
		$conta['dataemissao'] = $this->getDataEmissao();
		$conta['numerodoc'] = $this->getNumeroDoc();
		$conta['anexocaminho'] = $this->getAnexoCaminho();
		$conta['cancelada'] = $this->getCancelada();
		$conta['datapagamento'] = $this->getDataPagamento();
		$conta['datacadastro'] = $this->getDataCadastro();
		return $conta;
	}

	public static function getPeloID($id) {
		$query = DB::$pdo->from('Contas')
		                 ->where(array('id' => $id));
		return new ZConta($query->fetch());
	}

	public static function getTotalAbertas($descricao = null, $cliente_id = null, $tipo = 0, 
			$mes_inicio = null, $mes_fim = null) {
		$data_inicio = null;
		if(!is_null($mes_inicio) && !is_numeric($mes_inicio))
			$data_inicio = strtotime($mes_inicio);
		else if(!is_null($mes_inicio))
			$data_inicio = strtotime(date('Y-m').' '.$mes_inicio.' month');
		$data_fim = null;
		if(!is_null($mes_fim) && !is_numeric($mes_fim))
			$data_fim = strtotime($mes_fim);
		else if(!is_null($mes_fim)) {
			$data_fim = strtotime(date('Y-m').' '.$mes_fim.' month');
			$data_fim = strtotime('last day of this month', $data_fim);
		}
		$db = DB::$pdo->getPdo();
		$sql = '';
		$data = array();
		$descricao = trim($descricao);
		if(is_numeric($descricao)) {
			$sql .= 'AND c.id = :codigo ';
			$data[':codigo'] = intval($descricao);
		} else if($descricao != '') {
			$sql .= 'AND c.descricao LIKE :descricao ';
			$data[':descricao'] = '%'.$descricao.'%';
		}
		if(!is_null($cliente_id)) {
			$sql .= 'AND c.clienteid = :cliente ';
			$data[':cliente'] = $cliente_id;
		}
		if(is_numeric($tipo) && $tipo != 0) {
			if($tipo < 0)
				$sql .= 'AND c.valor <= 0 ';
			else
				$sql .= 'AND c.valor > 0 ';
		}
		if(!is_null($data_inicio)) {
			$sql .= 'AND c.vencimento >= :inicio ';
			$data[':inicio'] = date('Y-m-d', $data_inicio);
		}
		if(!is_null($data_fim)) {
			$sql .= 'AND c.vencimento <= :fim ';
			$data[':fim'] = date('Y-m-d 23:59:59', $data_fim);
		}
		$stmt = $db->prepare('SELECT COUNT(id) as quantidade, SUM(IF(valor <= 0, valor + acrescimo, 0)) as despesas, '.
							 '  SUM(IF(valor > 0, valor + acrescimo, 0)) as receitas, SUM(pago) as pago, '.
							 '  SUM(recebido) as recebido, MAX(datapagto) as datapagto '.
							 'FROM ('.
								'SELECT c.id, c.valor, c.acrescimo, MAX(pg.datahora) as datapagto, '.
								'  COALESCE(IF(c.valor <= 0, SUM(pg.total), 0), 0) as pago, '.
								'  COALESCE(IF(c.valor > 0, SUM(pg.total), 0), 0) as recebido '.
							 	'FROM Contas c '.
							 	'LEFT JOIN Pagamentos pg ON pg.pagtocontaid = c.id AND pg.cancelado = "N" AND pg.ativo = "Y" '.
							 	'WHERE c.id <> 1 AND c.cancelada = "N" '.$sql.
							 	'GROUP BY c.id '.
							 	'HAVING (ABS(valor + acrescimo) - ABS(pago + recebido)) >= 0.005) a');
		$stmt->execute($data);
		$info = $stmt->fetch();
		return array(
			'quantidade' => $info['quantidade'] + 0,
			'despesas' => $info['despesas'] + 0,
			'receitas' => $info['receitas'] + 0,
			'pago' => $info['pago'] + 0,
			'recebido' => $info['recebido'] + 0,
			'datapagto' => $info['datapagto'],
		);
	}

	private static function validarCampos(&$conta) {
		$erros = array();
		if(!is_numeric($conta['classificacaoid']))
			$erros['classificacaoid'] = 'A classificacaoid não foi informada';
		if(!is_numeric($conta['funcionarioid']))
			$erros['funcionarioid'] = 'O funcionarioid não foi informado';
		$conta['subclassificacaoid'] = trim($conta['subclassificacaoid']);
		if(strlen($conta['subclassificacaoid']) == 0)
			$conta['subclassificacaoid'] = null;
		else if(!is_numeric($conta['subclassificacaoid']))
			$erros['subclassificacaoid'] = 'A subclassificacaoid não foi informada';
		$conta['clienteid'] = trim($conta['clienteid']);
		if(strlen($conta['clienteid']) == 0)
			$conta['clienteid'] = null;
		else if(!is_numeric($conta['clienteid']))
			$erros['clienteid'] = 'O clienteid não foi informado';
		$conta['pedidoid'] = trim($conta['pedidoid']);
		if(strlen($conta['pedidoid']) == 0)
			$conta['pedidoid'] = null;
		else if(!is_numeric($conta['pedidoid']))
			$erros['pedidoid'] = 'O pedidoid não foi informado';
		$conta['descricao'] = strip_tags(trim($conta['descricao']));
		if(strlen($conta['descricao']) == 0)
			$erros['descricao'] = 'A descricao não pode ser vazia';
		if(!is_numeric($conta['valor']))
			$erros['valor'] = 'O valor não foi informado';
		else if(is_equal($conta['valor'], 0))
			$erros['valor'] = 'O valor não pode ser nulo';
		if(!is_numeric($conta['acrescimo']))
			$erros['acrescimo'] = 'O acrescimo não foi informado';
		else {
			$conta['acrescimo'] = floatval($conta['acrescimo']);
			if($conta['valor'] > 0 && $conta['acrescimo'] < 0)
				$erros['acrescimo'] = 'O acrescimo não pode ser negativo';
			else if($conta['valor'] <= 0 && $conta['acrescimo'] > 0)
				$erros['acrescimo'] = 'O acrescimo não pode ser positivo';
		}
		if(!is_numeric($conta['multa']))
			$erros['multa'] = 'A multa não foi informada';
		else {
			$conta['multa'] = floatval($conta['multa']);
			if($conta['valor'] > 0 && $conta['multa'] < 0)
				$erros['multa'] = 'A multa não pode ser negativa';
			else if($conta['valor'] <= 0 && $conta['multa'] > 0)
				$erros['multa'] = 'A multa não pode ser positiva';
		}
		if(!is_numeric($conta['juros']))
			$erros['juros'] = 'O juros não foi informado';
		else {
			$conta['juros'] = floatval($conta['juros']);
			if($conta['juros'] < 0)
				$erros['juros'] = 'O juros não pode ser negativo';
		}
		$conta['autoacrescimo'] = trim($conta['autoacrescimo']);
		if(strlen($conta['autoacrescimo']) == 0)
			$conta['autoacrescimo'] = 'N';
		else if(!in_array($conta['autoacrescimo'], array('Y', 'N')))
			$erros['autoacrescimo'] = 'O acréscimo automático informado não é válido';
		$conta['vencimento'] = strval($conta['vencimento']);
		if(strlen($conta['vencimento']) == 0)
			$conta['vencimento'] = null;
		else {
			$time = strtotime($conta['vencimento']);
			if($time === false)
				$erros['vencimento'] = 'A data de vencimento é inválida';
			else
				$conta['vencimento'] = date('Y-m-d', $time);
		}
		$conta['dataemissao'] = strval($conta['dataemissao']);
		if(strlen($conta['dataemissao']) == 0)
			$conta['dataemissao'] = null;
		else {
			$time = strtotime($conta['dataemissao']);
			if($time === false)
				$erros['dataemissao'] = 'A data de emissão é inválida';
			else
				$conta['dataemissao'] = date('Y-m-d', $time);
		}
		$conta['numerodoc'] = strip_tags(trim($conta['numerodoc']));
		if(strlen($conta['numerodoc']) == 0)
			$conta['numerodoc'] = null;
		$conta['anexocaminho'] = strip_tags(trim($conta['anexocaminho']));
		if(strlen($conta['anexocaminho']) == 0)
			$conta['anexocaminho'] = null;
		$conta['cancelada'] = trim($conta['cancelada']);
		if(strlen($conta['cancelada']) == 0)
			$conta['cancelada'] = 'N';
		else if(!in_array($conta['cancelada'], array('Y', 'N')))
			$erros['cancelada'] = 'O cancelamento informada não é válido';
		$conta['datapagamento'] = strval($conta['datapagamento']);
		if(strlen($conta['datapagamento']) == 0)
			$conta['datapagamento'] = null;
		else {
			$time = strtotime($conta['datapagamento']);
			if($time === false)
				$erros['datapagamento'] = 'A data de pagamento é inválida';
			else
				$conta['datapagamento'] = date('Y-m-d', $time);
		}
		$conta['datacadastro'] = date('Y-m-d H:i:s');
		$receitas = 0;
		if(is_numeric($conta['id'])) {
			$info = self::getTotalAbertas($conta['id']);
			if(is_equal($info['receitas'], 0) && is_equal($info['despesas'], 0)) {
				throw new Exception('A conta informada já foi consolidada e não '.
        			'pode ser alterada, você ainda pode cancelar os pagamentos e alterar essa conta');
			}
			if($conta['valor'] > 0 && is_greater($info['recebido'], $conta['valor']))
				throw new Exception('O total recebido é maior que o valor da conta');
			if($conta['valor'] <= 0 && is_greater(-$info['pago'], -$conta['valor']))
				throw new Exception('O total pago é maior que o valor da conta');
			$_conta = ZConta::getPeloID($conta['id']);
			$receitas = $_conta->getTotal();
		}
		if(is_numeric($conta['clienteid']) && $conta['valor'] > 0) {
			$cliente = ZCliente::getPeloID($conta['clienteid']);
			if($cliente->getLimiteCompra() > 0) {
				$info_total = self::getTotalAbertas(null, $conta['clienteid']);
				$utilizado = ($info_total['receitas'] - $info_total['recebido']) + 
					($info_total['despesas'] - $info_total['pago']) - $receitas;
				if($conta['valor'] + $utilizado > $cliente->getLimiteCompra()) {
					$restante = ($conta['valor'] + $utilizado) - $cliente->getLimiteCompra();
					throw new Exception('O cliente "'.$cliente->getNomeCompleto().'" não possui limite de crédito '.
						'suficiente para concluir a operação, necessário R$ '.moneyit($restante).', limite '.
						'utilizado R$ '.moneyit($utilizado).' de R$ '.moneyit($cliente->getLimiteCompra()));
				}
			}
		}
		if(!empty($erros))
			throw new ValidationException($erros);
	}

	private static function handleException(&$e) {
		if(stripos($e->getMessage(), 'PRIMARY') !== false)
			throw new ValidationException(array('id' => 'O ID informado já está cadastrado'));
	}

	public static function cadastrar($conta) {
		$_conta = $conta->toArray();
		self::validarCampos($_conta);
		try {
			$_conta['id'] = DB::$pdo->insertInto('Contas')->values($_conta)->execute();
		} catch (Exception $e) {
			self::handleException($e);
			throw $e;
		}
		return self::getPeloID($_conta['id']);
	}

	public static function atualizar($conta) {
		$_conta = $conta->toArray();
		if(!$_conta['id'])
			throw new ValidationException(array('id' => 'O id da conta não foi informado'));
		if($_conta['id'] == 1)
			throw new Exception('Não é possível atualizar essa conta, a conta informada é utilizada internamente pelo sistema');
		self::validarCampos($_conta);
		$campos = array(
			'classificacaoid',
			'funcionarioid',
			'subclassificacaoid',
			'clienteid',
			'pedidoid',
			'descricao',
			'valor',
			'acrescimo',
			'multa',
			'juros',
			'autoacrescimo',
			'vencimento',
			'dataemissao',
			'numerodoc',
			'anexocaminho',
			'cancelada',
			'datapagamento',
		);
		try {
			$query = DB::$pdo->update('Contas');
			$query = $query->set(array_intersect_key($_conta, array_flip($campos)));
			$query = $query->where('id', $_conta['id']);
			$query->execute();
		} catch (Exception $e) {
			self::handleException($e);
			throw $e;
		}
		return self::getPeloID($_conta['id']);
	}

	public static function excluir($id) {
		if(!$id)
			throw new Exception('Não foi possível excluir a conta, o id da conta não foi informado');
		if($id == 1)
			throw new Exception('Não é possível excluir essa conta, a conta informada é utilizada internamente pelo sistema');
		$query = DB::$pdo->deleteFrom('Contas')
		                 ->where(array('id' => $id));
		return $query->execute();
	}

	private static function initSearch($busca, $cliente_id, $classificacao_id) {
		$query = DB::$pdo->from('Contas')
		                 ->orderBy('id DESC');
		$busca = trim($busca);
		if(is_numeric($busca)) {
			$query = $query->where('id', intval($busca));
		} else if(substr($busca, 0, 1) == '#') {
			$query = $query->where('numerodoc', substr($busca, 1));
		} else if($busca != '') {
			$query = $query->where('descricao LIKE ?', '%'.$busca.'%');
		}
		if(is_numeric($cliente_id))
			$query = $query->where('clienteid', intval($cliente_id));
		if(is_numeric($classificacao_id))
			$query = $query->where('(classificacaoid = ? OR subclassificacaoid = ?)', intval($classificacao_id), intval($classificacao_id));
		return $query;
	}

	public static function getTodas($busca = null, $cliente_id = null, $classificacao_id = null, $inicio = null, $quantidade = null) {
		$query = self::initSearch($busca, $cliente_id, $classificacao_id);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_contas = $query->fetchAll();
		$contas = array();
		foreach($_contas as $conta)
			$contas[] = new ZConta($conta);
		return $contas;
	}

	public static function getCount($busca = null, $cliente_id = null, $classificacao_id = null) {
		$query = self::initSearch($busca, $cliente_id, $classificacao_id);
		return $query->count();
	}

	private static function initSearchDoClienteID($cliente_id) {
		return   DB::$pdo->from('Contas')
		                 ->where(array('clienteid' => $cliente_id))
		                 ->orderBy('id ASC');
	}

	public static function getTodasDoClienteID($cliente_id, $inicio = null, $quantidade = null) {
		$query = self::initSearchDoClienteID($cliente_id);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_contas = $query->fetchAll();
		$contas = array();
		foreach($_contas as $conta)
			$contas[] = new ZConta($conta);
		return $contas;
	}

	public static function getCountDoClienteID($cliente_id) {
		$query = self::initSearchDoClienteID($cliente_id);
		return $query->count();
	}

	private static function initSearchDoFuncionarioID($funcionario_id) {
		return   DB::$pdo->from('Contas')
		                 ->where(array('funcionarioid' => $funcionario_id))
		                 ->orderBy('id ASC');
	}

	public static function getTodasDoFuncionarioID($funcionario_id, $inicio = null, $quantidade = null) {
		$query = self::initSearchDoFuncionarioID($funcionario_id);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_contas = $query->fetchAll();
		$contas = array();
		foreach($_contas as $conta)
			$contas[] = new ZConta($conta);
		return $contas;
	}

	public static function getCountDoFuncionarioID($funcionario_id) {
		$query = self::initSearchDoFuncionarioID($funcionario_id);
		return $query->count();
	}

	private static function initSearchDoPedidoID($pedido_id) {
		return   DB::$pdo->from('Contas')
		                 ->where(array('pedidoid' => $pedido_id))
		                 ->orderBy('id ASC');
	}

	public static function getTodasDoPedidoID($pedido_id, $inicio = null, $quantidade = null) {
		$query = self::initSearchDoPedidoID($pedido_id);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_contas = $query->fetchAll();
		$contas = array();
		foreach($_contas as $conta)
			$contas[] = new ZConta($conta);
		return $contas;
	}

	public static function getCountDoPedidoID($pedido_id) {
		$query = self::initSearchDoPedidoID($pedido_id);
		return $query->count();
	}

	private static function initSearchDaClassificacaoID($classificacao_id) {
		return   DB::$pdo->from('Contas')
		                 ->where(array('classificacaoid' => $classificacao_id))
		                 ->orderBy('id ASC');
	}

	public static function getTodasDaClassificacaoID($classificacao_id, $inicio = null, $quantidade = null) {
		$query = self::initSearchDaClassificacaoID($classificacao_id);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_contas = $query->fetchAll();
		$contas = array();
		foreach($_contas as $conta)
			$contas[] = new ZConta($conta);
		return $contas;
	}

	public static function getCountDaClassificacaoID($classificacao_id) {
		$query = self::initSearchDaClassificacaoID($classificacao_id);
		return $query->count();
	}

	private static function initSearchDaSubClassificacaoID($sub_classificacao_id) {
		return   DB::$pdo->from('Contas')
		                 ->where(array('subclassificacaoid' => $sub_classificacao_id))
		                 ->orderBy('id ASC');
	}

	public static function getTodasDaSubClassificacaoID($sub_classificacao_id, $inicio = null, $quantidade = null) {
		$query = self::initSearchDaSubClassificacaoID($sub_classificacao_id);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_contas = $query->fetchAll();
		$contas = array();
		foreach($_contas as $conta)
			$contas[] = new ZConta($conta);
		return $contas;
	}

	public static function getCountDaSubClassificacaoID($sub_classificacao_id) {
		$query = self::initSearchDaSubClassificacaoID($sub_classificacao_id);
		return $query->count();
	}

}
