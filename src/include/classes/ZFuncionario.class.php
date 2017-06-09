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
 * Funcionário que trabalha na empresa e possui uma determinada função
 */
class ZFuncionario {
	private $id;
	private $funcao_id;
	private $cliente_id;
	private $codigo_barras;
	private $porcentagem;
	private $linguagem_id;
	private $pontuacao;
	private $ativo;
	private $data_saida;
	private $data_cadastro;

	public function __construct($funcionario = array()) {
		if(is_array($funcionario)) {
			$this->setID(isset($funcionario['id'])?$funcionario['id']:null);
			$this->setFuncaoID(isset($funcionario['funcaoid'])?$funcionario['funcaoid']:null);
			$this->setClienteID(isset($funcionario['clienteid'])?$funcionario['clienteid']:null);
			$this->setCodigoBarras(isset($funcionario['codigobarras'])?$funcionario['codigobarras']:null);
			$this->setPorcentagem(isset($funcionario['porcentagem'])?$funcionario['porcentagem']:null);
			$this->setLinguagemID(isset($funcionario['linguagemid'])?$funcionario['linguagemid']:null);
			$this->setPontuacao(isset($funcionario['pontuacao'])?$funcionario['pontuacao']:null);
			$this->setAtivo(isset($funcionario['ativo'])?$funcionario['ativo']:null);
			$this->setDataSaida(isset($funcionario['datasaida'])?$funcionario['datasaida']:null);
			$this->setDataCadastro(isset($funcionario['datacadastro'])?$funcionario['datacadastro']:null);
		}
	}

	/**
	 * Código do funcionário
	 */
	public function getID() {
		return $this->id;
	}

	/**
	 * Código do funcionário
	 */
	public function setID($id) {
		$this->id = $id;
	}

	/**
	 * Função do funcionário na empresa
	 */
	public function getFuncaoID() {
		return $this->funcao_id;
	}

	/**
	 * Função do funcionário na empresa
	 */
	public function setFuncaoID($funcao_id) {
		$this->funcao_id = $funcao_id;
	}

	/**
	 * Cliente que representa esse funcionário, único no cadastro de funcionários
	 */
	public function getClienteID() {
		return $this->cliente_id;
	}

	/**
	 * Cliente que representa esse funcionário, único no cadastro de funcionários
	 */
	public function setClienteID($cliente_id) {
		$this->cliente_id = $cliente_id;
	}

	/**
	 * Código de barras utilizado pelo funcionário para autorizar uma operação no sistema
	 */
	public function getCodigoBarras() {
		return $this->codigo_barras;
	}

	/**
	 * Código de barras utilizado pelo funcionário para autorizar uma operação no sistema
	 */
	public function setCodigoBarras($codigo_barras) {
		$this->codigo_barras = $codigo_barras;
	}

	/**
	 * Porcentagem cobrada pelo funcionário ao cliente, Ex.: Comissão de 10%
	 */
	public function getPorcentagem() {
		return $this->porcentagem;
	}

	/**
	 * Porcentagem cobrada pelo funcionário ao cliente, Ex.: Comissão de 10%
	 */
	public function setPorcentagem($porcentagem) {
		$this->porcentagem = $porcentagem;
	}

	/**
	 * Código da linguagem utilizada pelo funcionário para visualizar o programa e o site
	 */
	public function getLinguagemID() {
		return $this->linguagem_id;
	}

	/**
	 * Código da linguagem utilizada pelo funcionário para visualizar o programa e o site
	 */
	public function setLinguagemID($linguagem_id) {
		$this->linguagem_id = $linguagem_id;
	}

	/**
	 * Define a distribuição da porcentagem pela parcela de pontos
	 */
	public function getPontuacao() {
		return $this->pontuacao;
	}

	/**
	 * Define a distribuição da porcentagem pela parcela de pontos
	 */
	public function setPontuacao($pontuacao) {
		$this->pontuacao = $pontuacao;
	}

	/**
	 * Informa se o funcionário está ativo na empresa
	 */
	public function getAtivo() {
		return $this->ativo;
	}

	/**
	 * Informa se o funcionário está ativo na empresa
	 */
	public function isAtivo() {
		return $this->ativo == 'Y';
	}

	/**
	 * Informa se o funcionário está ativo na empresa
	 */
	public function setAtivo($ativo) {
		$this->ativo = $ativo;
	}

	/**
	 * Data de saída do funcionário, informado apenas quando ativo for não
	 */
	public function getDataSaida() {
		return $this->data_saida;
	}

	/**
	 * Data de saída do funcionário, informado apenas quando ativo for não
	 */
	public function setDataSaida($data_saida) {
		$this->data_saida = $data_saida;
	}

	/**
	 * Data em que o funcionário foi cadastrado no sistema
	 */
	public function getDataCadastro() {
		return $this->data_cadastro;
	}

	/**
	 * Data em que o funcionário foi cadastrado no sistema
	 */
	public function setDataCadastro($data_cadastro) {
		$this->data_cadastro = $data_cadastro;
	}

	public function toArray() {
		$funcionario = array();
		$funcionario['id'] = $this->getID();
		$funcionario['funcaoid'] = $this->getFuncaoID();
		$funcionario['clienteid'] = $this->getClienteID();
		$funcionario['codigobarras'] = $this->getCodigoBarras();
		$funcionario['porcentagem'] = $this->getPorcentagem();
		$funcionario['linguagemid'] = $this->getLinguagemID();
		$funcionario['pontuacao'] = $this->getPontuacao();
		$funcionario['ativo'] = $this->getAtivo();
		$funcionario['datasaida'] = $this->getDataSaida();
		$funcionario['datacadastro'] = $this->getDataCadastro();
		return $funcionario;
	}

	private static function initGet() {
		return   DB::$pdo->from('Funcionarios f');
	}

	public static function getPeloID($id) {
		$query = self::initGet()->where(array('f.id' => $id));
		return new ZFuncionario($query->fetch());
	}

	public static function getPeloLogin($login) {
		$query = self::initGet()->where(array('c.login' => $login));
		return new ZFuncionario($query->fetch());
	}

	public static function getPeloClienteID($cliente_id, $todos = false) {
		$query = self::initGet()->where(array('f.clienteid' => $cliente_id));
		if(!$todos)
			$query = $query->where(array('f.ativo' => 'Y'));
		return new ZFuncionario($query->fetch());
	}

	public static function getPeloCodigoBarras($codigo_barras) {
		$query = self::initGet()->where(array('f.codigobarras' => $codigo_barras));
		return new ZFuncionario($query->fetch());
	}

	private static function validarCampos(&$funcionario) {
		$erros = array();
		if(!is_numeric($funcionario['funcaoid']))
			$erros['funcaoid'] = 'A função não foi informada';
		if(!is_numeric($funcionario['clienteid']))
			$erros['clienteid'] = 'O cliente não foi informado';
		else {
			$cliente = ZCliente::getPeloID($funcionario['clienteid']);
			if(trim($cliente->getLogin()) == '')
				$erros['clienteid'] = 'O cliente não possui nome de login';
			else if($cliente->getTipo() != ClienteTipo::FISICA)
				$erros['clienteid'] = 'O cliente precisa ser uma pessoa física';
			else if(is_null($cliente->getSenha()))
				$erros['clienteid'] = 'O cliente precisa possuir uma senha';
		}
		$funcionario['codigobarras'] = strip_tags(trim($funcionario['codigobarras']));
		if(strlen($funcionario['codigobarras']) == 0)
			$funcionario['codigobarras'] = null;
		else if(!is_number($funcionario['codigobarras']))
			$erros['codigobarras'] = 'O código de barras deve conter apenas números';
		if(!is_numeric($funcionario['porcentagem']))
			$erros['porcentagem'] = 'A porcentagem não foi informada';
		else {
			$funcionario['porcentagem'] = floatval($funcionario['porcentagem']);
			if($funcionario['porcentagem'] < 0)
				$erros['porcentagem'] = 'A porcentagem não pode ser negativa';
		}
		if(!is_numeric($funcionario['linguagemid']))
			$erros['linguagemid'] = 'A linguagem não foi informada';
		else
			$funcionario['linguagemid'] = intval($funcionario['linguagemid']);
		if(!is_numeric($funcionario['pontuacao']))
			$erros['pontuacao'] = 'A pontuação não foi informada';
		else {
			$funcionario['pontuacao'] = intval($funcionario['pontuacao']);
			if($funcionario['pontuacao'] < 0)
				$erros['pontuacao'] = 'A pontuação não pode ser negativa';
		}
		$funcionario['ativo'] = trim($funcionario['ativo']);
		if(strlen($funcionario['ativo']) == 0)
			$funcionario['ativo'] = 'N';
		else if(!in_array($funcionario['ativo'], array('Y', 'N')))
			$erros['ativo'] = 'A informação se o funcionário está ativo é inválida';
		if($funcionario['ativo'] == 'N' && is_null($funcionario['datasaida']))
			$funcionario['datasaida'] = date('Y-m-d H:i:s');
		else if($funcionario['ativo'] == 'Y')
			$funcionario['datasaida'] = null;
		$funcionario['datacadastro'] = date('Y-m-d H:i:s');
		if(!empty($erros))
			throw new ValidationException($erros);
	}

	private static function handleException(&$e) {
		if(stripos($e->getMessage(), 'PRIMARY') !== false)
			throw new ValidationException(array('id' => 'O ID informado já está cadastrado'));
		if(stripos($e->getMessage(), 'UK_ClienteID') !== false)
			throw new ValidationException(array('clienteid' => 'O cliente informado já é um funcionário'));
		if(stripos($e->getMessage(), 'CodigoBarras_UNIQUE') !== false)
			throw new ValidationException(array('codigobarras' => 'O código de barras informado já está cadastrado'));
	}

	public static function cadastrar($funcionario) {
		$_funcionario = $funcionario->toArray();
		self::validarCampos($_funcionario);
		try {
			$_funcionario['id'] = DB::$pdo->insertInto('Funcionarios')->values($_funcionario)->execute();
		} catch (Exception $e) {
			self::handleException($e);
			throw $e;
		}
		return self::getPeloID($_funcionario['id']);
	}

	public static function atualizar($funcionario) {
		$_funcionario = $funcionario->toArray();
		if(!$_funcionario['id'])
			throw new ValidationException(array('id' => 'O id do funcionário não foi informado'));
		self::validarCampos($_funcionario);
		$campos = array(
			'funcaoid',
			'clienteid',
			'codigobarras',
			'porcentagem',
			'linguagemid',
			'pontuacao',
			'ativo',
			'datasaida',
		);
		try {
			$query = DB::$pdo->update('Funcionarios');
			$query = $query->set(array_intersect_key($_funcionario, array_flip($campos)));
			$query = $query->where('id', $_funcionario['id']);
			$query->execute();
		} catch (Exception $e) {
			self::handleException($e);
			throw $e;
		}
		return self::getPeloID($_funcionario['id']);
	}

	public static function excluir($funcionario) {
		if(is_null($funcionario->getID()))
			throw new Exception('Não foi possível excluir o funcionário, o id do funcionário não foi informado');
		if(have_permission(PermissaoNome::CADASTROFUNCIONARIOS, $funcionario) && !is_owner())
			throw new Exception('Você não tem permissão para excluir esse funcionário!');
		if(is_self($funcionario))
			throw new Exception('Você não pode excluir a si mesmo!');
		if(is_owner($funcionario))
			throw new Exception('Esse funcionário não pode ser excluído!');
		$query = DB::$pdo->deleteFrom('Funcionarios')
		                 ->where(array('id' => $funcionario->getID()));
		return $query->execute();
	}

	private static function initSearch($nome, $funcao_id, $genero, $ativo) {
		$query = self::initGet()
					 ->leftJoin('Clientes c ON c.id = f.clienteid');
		$nome = trim($nome);
		if($nome == '') {
			# não faz nada
		} else if(check_email($nome)) {
			$query = $query->where('c.email', $nome);
		} else if(check_cpf($nome)) {
			$query = $query->where('c.cpf', \MZ\Util\Filter::digits($nome));
		} else if(check_fone($nome, true)) {
			$_fone = \MZ\Util\Filter::digits($nome);
			$_ddd = substr($_fone, 0, 2).'%';
			if(strlen($_fone) == 10)
				$_fone = $_ddd . substr($_fone, 2, 8);
			else if(strlen($_fone) <= 9)
				$_fone = '%' . $_fone;
			else
				$_fone = $_ddd . substr($_fone, 3);
			$query = $query->where('(c.fone1 LIKE ? OR c.fone2 LIKE ?)', $_fone, $_fone);
		} else if(is_numeric($nome)) {
			$query = $query->where('f.id', intval($nome));
		} else {
			$keywords = preg_split('/[\s,]+/', $nome);
			$words = '';
	 		foreach ($keywords as $word) {
	 			$words .= '%'.$word.'%';
	 			$query = $query->orderBy('IF(LOCATE(?, CONCAT(" ", c.nome, " ", COALESCE(c.sobrenome, ""))) = 0, '.
					'256, LOCATE(?, CONCAT(" ", c.nome, " ", COALESCE(c.sobrenome, "")))) ASC, IF(LOCATE(?, '.
					'CONCAT(c.nome, " ", COALESCE(c.sobrenome, ""))) = 0, 256, LOCATE(?, CONCAT(c.nome, " ", COALESCE(c.sobrenome, "")))) ASC', 
	 				' '.$word, ' '.$word, $word, $word);
	 		}
			$query = $query->where('CONCAT(c.nome, " ", COALESCE(c.sobrenome, "")) LIKE ?', $words);
		}
		if(is_numeric($funcao_id)) {
			$query = $query->where('f.funcaoid', $funcao_id);
		}
		$genero = trim($genero);
		if($genero != '') {
			$query = $query->where('c.genero', $genero);
		}
		$ativo = trim($ativo);
		if($ativo != '') {
			$query = $query->where('f.ativo', $ativo);
		}
		$query = $query->orderBy('f.id ASC');
		return $query;
	}

	public static function getTodos($nome = null, $funcao_id = null, $genero = null, $ativo = null, $inicio = null, $quantidade = null) {
		$query = self::initSearch($nome, $funcao_id, $genero, $ativo);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_funcionarios = $query->fetchAll();
		$funcionarios = array();
		foreach($_funcionarios as $funcionario)
			$funcionarios[] = new ZFuncionario($funcionario);
		return $funcionarios;
	}

	public static function getCount($nome = null, $funcao_id = null, $genero = null, $ativo = null) {
		$query = self::initSearch($nome, $funcao_id, $genero, $ativo);
		return $query->count();
	}

	private static function initSearchDaFuncaoID($funcao_id) {
		return   self::initGet()->where(array('f.funcaoid' => $funcao_id))
		                 ->orderBy('f.id ASC');
	}

	public static function getTodosDaFuncaoID($funcao_id, $inicio = null, $quantidade = null) {
		$query = self::initSearchDaFuncaoID($funcao_id);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_funcionarios = $query->fetchAll();
		$funcionarios = array();
		foreach($_funcionarios as $funcionario)
			$funcionarios[] = new ZFuncionario($funcionario);
		return $funcionarios;
	}

	public static function getCountDaFuncaoID($funcao_id) {
		$query = self::initSearchDaFuncaoID($funcao_id);
		return $query->count();
	}

	private static function initSearchDoClienteID($cliente_id) {
		return   self::initGet()->where(array('f.clienteid' => $cliente_id))
		                 ->orderBy('f.id ASC');
	}

	public static function getTodosDoClienteID($cliente_id, $inicio = null, $quantidade = null) {
		$query = self::initSearchDoClienteID($cliente_id);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_funcionarios = $query->fetchAll();
		$funcionarios = array();
		foreach($_funcionarios as $funcionario)
			$funcionarios[] = new ZFuncionario($funcionario);
		return $funcionarios;
	}

	public static function getCountDoClienteID($cliente_id) {
		$query = self::initSearchDoClienteID($cliente_id);
		return $query->count();
	}

}
