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
class ImpressoraModo {
	const TERMINAL = 'Terminal';
	const CAIXA = 'Caixa';
	const SERVICO = 'Servico';
	const ESTOQUE = 'Estoque';
}

/**
 * Impressora para impressão de serviços e contas
 */
class ZImpressora {
	private $id;
	private $setor_id;
	private $dispositivo_id;
	private $nome;
	private $driver;
	private $descricao;
	private $modo;
	private $opcoes;
	private $colunas;
	private $avanco;
	private $comandos;

	public function __construct($impressora = array()) {
		if(is_array($impressora)) {
			$this->setID(isset($impressora['id'])?$impressora['id']:null);
			$this->setSetorID(isset($impressora['setorid'])?$impressora['setorid']:null);
			$this->setDispositivoID(isset($impressora['dispositivoid'])?$impressora['dispositivoid']:null);
			$this->setNome(isset($impressora['nome'])?$impressora['nome']:null);
			$this->setDriver(isset($impressora['driver'])?$impressora['driver']:null);
			$this->setDescricao(isset($impressora['descricao'])?$impressora['descricao']:null);
			$this->setModo(isset($impressora['modo'])?$impressora['modo']:null);
			$this->setOpcoes(isset($impressora['opcoes'])?$impressora['opcoes']:null);
			$this->setColunas(isset($impressora['colunas'])?$impressora['colunas']:null);
			$this->setAvanco(isset($impressora['avanco'])?$impressora['avanco']:null);
			$this->setComandos(isset($impressora['comandos'])?$impressora['comandos']:null);
		}
	}

	/**
	 * Identificador da impressora
	 */
	public function getID() {
		return $this->id;
	}

	public function setID($id) {
		$this->id = $id;
	}

	/**
	 * Setor de impressão
	 */
	public function getSetorID() {
		return $this->setor_id;
	}

	public function setSetorID($setor_id) {
		$this->setor_id = $setor_id;
	}

	/**
	 * Dispositivo que contém a impressora
	 */
	public function getDispositivoID() {
		return $this->dispositivo_id;
	}

	public function setDispositivoID($dispositivo_id) {
		$this->dispositivo_id = $dispositivo_id;
	}

	/**
	 * Nome da impressora instalada no windows
	 */
	public function getNome() {
		return $this->nome;
	}

	public function setNome($nome) {
		$this->nome = $nome;
	}

	/**
	 * Informa qual conjunto de comandos deve ser utilizado
	 */
	public function getDriver() {
		return $this->driver;
	}

	public function setDriver($driver) {
		$this->driver = $driver;
	}

	/**
	 * Descrição da impressora
	 */
	public function getDescricao() {
		return $this->descricao;
	}

	public function setDescricao($descricao) {
		$this->descricao = $descricao;
	}

	/**
	 * Modo de impressão
	 */
	public function getModo() {
		return $this->modo;
	}

	public function setModo($modo) {
		$this->modo = $modo;
	}

	/**
	 * Opções da impressora, Ex.: Cortar papel, Acionar gaveta e outros
	 */
	public function getOpcoes() {
		return $this->opcoes;
	}

	public function setOpcoes($opcoes) {
		$this->opcoes = $opcoes;
	}

	/**
	 * Quantidade de colunas do cupom
	 */
	public function getColunas() {
		return $this->colunas;
	}

	public function setColunas($colunas) {
		$this->colunas = $colunas;
	}

	/**
	 * Quantidade de linhas para avanço do papel
	 */
	public function getAvanco() {
		return $this->avanco;
	}

	public function setAvanco($avanco) {
		$this->avanco = $avanco;
	}

	/**
	 * Comandos para impressão, quando o driver é customizado
	 */
	public function getComandos() {
		return $this->comandos;
	}

	public function setComandos($comandos) {
		$this->comandos = $comandos;
	}

	public function toArray() {
		$impressora = array();
		$impressora['id'] = $this->getID();
		$impressora['setorid'] = $this->getSetorID();
		$impressora['dispositivoid'] = $this->getDispositivoID();
		$impressora['nome'] = $this->getNome();
		$impressora['driver'] = $this->getDriver();
		$impressora['descricao'] = $this->getDescricao();
		$impressora['modo'] = $this->getModo();
		$impressora['opcoes'] = $this->getOpcoes();
		$impressora['colunas'] = $this->getColunas();
		$impressora['avanco'] = $this->getAvanco();
		$impressora['comandos'] = $this->getComandos();
		return $impressora;
	}

	public static function getPeloID($id) {
		$query = DB::$pdo->from('Impressoras')
		                 ->where(array('id' => $id));
		return new ZImpressora($query->fetch());
	}

	public static function getPeloSetorIDDispositivoIDModo($setor_id, $dispositivo_id, $modo) {
		$query = DB::$pdo->from('Impressoras')
		                 ->where(array('setorid' => $setor_id, 'dispositivoid' => $dispositivo_id, 'modo' => $modo));
		return new ZImpressora($query->fetch());
	}

	public static function getPeloDispositivoIDDescricao($dispositivo_id, $descricao) {
		$query = DB::$pdo->from('Impressoras')
		                 ->where(array('dispositivoid' => $dispositivo_id, 'descricao' => $descricao));
		return new ZImpressora($query->fetch());
	}

	private static function validarCampos(&$impressora) {
		$erros = array();
		if(!is_numeric($impressora['setorid']))
			$erros['setorid'] = 'O setor de impressão não foi informado';
		$impressora['dispositivoid'] = trim($impressora['dispositivoid']);
		if(strlen($impressora['dispositivoid']) == 0)
			$impressora['dispositivoid'] = null;
		else if(!is_numeric($impressora['dispositivoid']))
			$erros['dispositivoid'] = 'O dispositivo não foi informado';
		$impressora['nome'] = strip_tags(trim($impressora['nome']));
		if(strlen($impressora['nome']) == 0)
			$erros['nome'] = 'O nome não pode ser vazio';
		$impressora['driver'] = strip_tags(trim($impressora['driver']));
		if(strlen($impressora['driver']) == 0)
			$impressora['driver'] = null;
		$impressora['descricao'] = trim($impressora['descricao']);
		if(strlen($impressora['descricao']) == 0)
			$impressora['descricao'] = null;
		else if(!in_array($impressora['descricao'], array(null)))
			$erros['descricao'] = 'A descrição informada não é válida';
		$impressora['modo'] = trim($impressora['modo']);
		if(strlen($impressora['modo']) == 0)
			$impressora['modo'] = null;
		else if(!in_array($impressora['modo'], array('Terminal', 'Caixa', 'Servico', 'Estoque')))
			$erros['modo'] = 'O modo informado não é válido';
		if(!is_numeric($impressora['opcoes']))
			$erros['opcoes'] = 'A opções não foi informada';
		else
			$impressora['opcoes'] = intval($impressora['opcoes']);
		if(!is_numeric($impressora['colunas']))
			$erros['colunas'] = 'A quantidade de colunas não foi informada';
		else
			$impressora['colunas'] = intval($impressora['colunas']);
		if(!is_numeric($impressora['avanco']))
			$erros['avanco'] = 'O avanço de papel não foi informado';
		else
			$impressora['avanco'] = intval($impressora['avanco']);
		$impressora['comandos'] = strval($impressora['comandos']);
		if(strlen($impressora['comandos']) == 0)
			$impressora['comandos'] = null;
		if(!empty($erros))
			throw new ValidationException($erros);
	}

	private static function handleException(&$e) {
		if(stripos($e->getMessage(), 'PRIMARY') !== false)
			throw new ValidationException(array('id' => 'O ID informado já está cadastrado'));
		if(stripos($e->getMessage(), 'UK_Impresoras_Setor_Dispositivo_Modo') !== false)
			throw new ValidationException(array('modo' => 'O modo informado já está cadastrado'));
		if(stripos($e->getMessage(), 'UK_Impressoras_Dispositivo_Descricao') !== false)
			throw new ValidationException(array('descricao' => 'A descrição informada já está cadastrada'));
	}

	public static function cadastrar($impressora) {
		$_impressora = $impressora->toArray();
		self::validarCampos($_impressora);
		try {
			$_impressora['id'] = DB::$pdo->insertInto('Impressoras')->values($_impressora)->execute();
		} catch (Exception $e) {
			self::handleException($e);
			throw $e;
		}
		return self::getPeloID($_impressora['id']);
	}

	public static function atualizar($impressora) {
		$_impressora = $impressora->toArray();
		if(!$_impressora['id'])
			throw new ValidationException(array('id' => 'O id da impressora não foi informado'));
		self::validarCampos($_impressora);
		$campos = array(
			'setorid',
			'dispositivoid',
			'nome',
			'driver',
			'descricao',
			'modo',
			'opcoes',
			'colunas',
			'avanco',
			'comandos',
		);
		try {
			$query = DB::$pdo->update('Impressoras');
			$query = $query->set(array_intersect_key($_impressora, array_flip($campos)));
			$query = $query->where('id', $_impressora['id']);
			$query->execute();
		} catch (Exception $e) {
			self::handleException($e);
			throw $e;
		}
		return self::getPeloID($_impressora['id']);
	}

	public static function excluir($id) {
		if(!$id)
			throw new Exception('Não foi possível excluir a impressora, o id da impressora não foi informado');
		$query = DB::$pdo->deleteFrom('Impressoras')
		                 ->where(array('id' => $id));
		return $query->execute();
	}

	private static function initSearch() {
		return   DB::$pdo->from('Impressoras')
		                 ->orderBy('id ASC');
	}

	public static function getTodas($inicio = null, $quantidade = null) {
		$query = self::initSearch();
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_impressoras = $query->fetchAll();
		$impressoras = array();
		foreach($_impressoras as $impressora)
			$impressoras[] = new ZImpressora($impressora);
		return $impressoras;
	}

	public static function getCount() {
		$query = self::initSearch();
		return $query->count();
	}

	private static function initSearchDoDispositivoID($dispositivo_id) {
		return   DB::$pdo->from('Impressoras')
		                 ->where(array('dispositivoid' => $dispositivo_id))
		                 ->orderBy('id ASC');
	}

	public static function getTodasDoDispositivoID($dispositivo_id, $inicio = null, $quantidade = null) {
		$query = self::initSearchDoDispositivoID($dispositivo_id);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_impressoras = $query->fetchAll();
		$impressoras = array();
		foreach($_impressoras as $impressora)
			$impressoras[] = new ZImpressora($impressora);
		return $impressoras;
	}

	public static function getCountDoDispositivoID($dispositivo_id) {
		$query = self::initSearchDoDispositivoID($dispositivo_id);
		return $query->count();
	}

	private static function initSearchDoSetorID($setor_id) {
		return   DB::$pdo->from('Impressoras')
		                 ->where(array('setorid' => $setor_id))
		                 ->orderBy('id ASC');
	}

	public static function getTodasDoSetorID($setor_id, $inicio = null, $quantidade = null) {
		$query = self::initSearchDoSetorID($setor_id);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_impressoras = $query->fetchAll();
		$impressoras = array();
		foreach($_impressoras as $impressora)
			$impressoras[] = new ZImpressora($impressora);
		return $impressoras;
	}

	public static function getCountDoSetorID($setor_id) {
		$query = self::initSearchDoSetorID($setor_id);
		return $query->count();
	}

}
