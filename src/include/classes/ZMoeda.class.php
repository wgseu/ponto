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
 * Moedas financeiras de um país
 */
class ZMoeda {
	private $id;
	private $nome;
	private $simbolo;
	private $codigo;
	private $divisao;
	private $fracao;
	private $formato;

	public function __construct($moeda = array()) {
		if(is_array($moeda)) {
			$this->setID($moeda['id']);
			$this->setNome($moeda['nome']);
			$this->setSimbolo($moeda['simbolo']);
			$this->setCodigo($moeda['codigo']);
			$this->setDivisao($moeda['divisao']);
			$this->setFracao($moeda['fracao']);
			$this->setFormato($moeda['formato']);
		}
	}

	/**
	 * Identificador da moeda
	 */
	public function getID() {
		return $this->id;
	}

	public function setID($id) {
		$this->id = $id;
	}

	/**
	 * Nome da moeda
	 */
	public function getNome() {
		return $this->nome;
	}

	public function setNome($nome) {
		$this->nome = $nome;
	}

	/**
	 * Símbolo da moeda, Ex.: R$, $
	 */
	public function getSimbolo() {
		return $this->simbolo;
	}

	public function setSimbolo($simbolo) {
		$this->simbolo = $simbolo;
	}

	/**
	 * Código internacional da moeda, Ex.: USD, BRL
	 */
	public function getCodigo() {
		return $this->codigo;
	}

	public function setCodigo($codigo) {
		$this->codigo = $codigo;
	}

	/**
	 * Informa o número fracionário para determinar a quantidade de casas decimais, Ex:
	 * 100 para 0,00. 10 para 0,0
	 */
	public function getDivisao() {
		return $this->divisao;
	}

	public function setDivisao($divisao) {
		$this->divisao = $divisao;
	}

	/**
	 * Informa o nome da fração, Ex.: Centavo
	 */
	public function getFracao() {
		return $this->fracao;
	}

	public function setFracao($fracao) {
		$this->fracao = $fracao;
	}

	/**
	 * Formado de exibição do valor, Ex: $ %s, para $ 3,00
	 */
	public function getFormato() {
		return $this->formato;
	}

	public function setFormato($formato) {
		$this->formato = $formato;
	}

	public function toArray() {
		$moeda = array();
		$moeda['id'] = $this->getID();
		$moeda['nome'] = $this->getNome();
		$moeda['simbolo'] = $this->getSimbolo();
		$moeda['codigo'] = $this->getCodigo();
		$moeda['divisao'] = $this->getDivisao();
		$moeda['fracao'] = $this->getFracao();
		$moeda['formato'] = $this->getFormato();
		return $moeda;
	}

	public static function getPeloID($id) {
		$query = DB::$pdo->from('Moedas')
		                 ->where(array('id' => $id));
		return new ZMoeda($query->fetch());
	}

	private static function validarCampos(&$moeda) {
		$erros = array();
		$moeda['nome'] = strip_tags(trim($moeda['nome']));
		if(strlen($moeda['nome']) == 0)
			$erros['nome'] = 'O nome não pode ser vazio';
		$moeda['simbolo'] = strip_tags(trim($moeda['simbolo']));
		if(strlen($moeda['simbolo']) == 0)
			$erros['simbolo'] = 'O símbolo não pode ser vazio';
		$moeda['codigo'] = strip_tags(trim($moeda['codigo']));
		if(strlen($moeda['codigo']) == 0)
			$moeda['codigo'] = null;
		if(!is_numeric($moeda['divisao']))
			$erros['divisao'] = 'A divisão não foi informada';
		else if($moeda['divisao'] < 0)
			$erros['divisao'] = 'A divisão não pode ser negativa';
		else if($moeda['divisao'] < 1)
			$erros['divisao'] = 'A divisão não pode ser nula';
		$moeda['fracao'] = strip_tags(trim($moeda['fracao']));
		if(strlen($moeda['fracao']) == 0)
			$moeda['fracao'] = null;
		$moeda['formato'] = strip_tags(trim($moeda['formato']));
		if(strlen($moeda['formato']) == 0)
			$erros['formato'] = 'O formato não pode ser vazio';
		if(!empty($erros))
			throw new ValidationException($erros);
	}

	private static function handleException(&$e) {
		if(stripos($e->getMessage(), 'PRIMARY') !== false)
			throw new ValidationException(array('id' => 'O ID informado já está cadastrado'));
	}

	public static function cadastrar($moeda) {
		$_moeda = $moeda->toArray();
		self::validarCampos($_moeda);
		try {
			$_moeda['id'] = DB::$pdo->insertInto('Moedas')->values($_moeda)->execute();
		} catch (Exception $e) {
			self::handleException($e);
			throw $e;
		}
		return self::getPeloID($_moeda['id']);
	}

	public static function atualizar($moeda) {
		$_moeda = $moeda->toArray();
		if(!$_moeda['id'])
			throw new ValidationException(array('id' => 'O id da moeda não foi informado'));
		self::validarCampos($_moeda);
		$campos = array(
			'nome',
			'simbolo',
			'codigo',
			'divisao',
			'fracao',
			'formato',
		);
		try {
			$query = DB::$pdo->update('Moedas');
			$query = $query->set(array_intersect_key($_moeda, array_flip($campos)));
			$query = $query->where('id', $_moeda['id']);
			$query->execute();
		} catch (Exception $e) {
			self::handleException($e);
			throw $e;
		}
		return self::getPeloID($_moeda['id']);
	}

	public static function excluir($id) {
		if(!$id)
			throw new Exception('Não foi possível excluir a moeda, o id da moeda não foi informado');
		$query = DB::$pdo->deleteFrom('Moedas')
		                 ->where(array('id' => $id));
		return $query->execute();
	}

	private static function initSearch($busca) {
		$query = DB::$pdo->from('Moedas')
		                 ->orderBy('nome ASC');
		$busca = trim($busca);
		if($busca != '') {
			$query = $query->where('CONCAT(nome, " ", codigo) LIKE ?', '%'.$busca.'%');
		}
		return $query;
	}

	public static function getTodas($busca = null, $inicio = null, $quantidade = null) {
		$query = self::initSearch($busca);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_moedas = $query->fetchAll();
		$moedas = array();
		foreach($_moedas as $moeda)
			$moedas[] = new ZMoeda($moeda);
		return $moedas;
	}

	public static function getCount($busca = null) {
		$query = self::initSearch($busca);
		return $query->count();
	}

}
