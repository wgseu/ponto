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
 * Origem da mercadoria
 */
class ZOrigem {
	private $id;
	private $codigo;
	private $descricao;

	public function __construct($origem = array()) {
		$this->fromArray($origem);
	}

	/**
	 * Identificador da origem
	 */
	public function getID() {
		return $this->id;
	}

	public function setID($id) {
		$this->id = $id;
	}

	/**
	 * Código da origem da mercadoria
	 */
	public function getCodigo() {
		return $this->codigo;
	}

	public function setCodigo($codigo) {
		$this->codigo = $codigo;
	}

	/**
	 * Descrição da origem da mercadoria
	 */
	public function getDescricao() {
		return $this->descricao;
	}

	public function setDescricao($descricao) {
		$this->descricao = $descricao;
	}

	public function toArray() {
		$origem = array();
		$origem['id'] = $this->getID();
		$origem['codigo'] = $this->getCodigo();
		$origem['descricao'] = $this->getDescricao();
		return $origem;
	}

	public function fromArray($origem = array()) {
		if(!is_array($origem))
			return $this;
		$this->setID(isset($origem['id'])?$origem['id']:null);
		$this->setCodigo(isset($origem['codigo'])?$origem['codigo']:null);
		$this->setDescricao(isset($origem['descricao'])?$origem['descricao']:null);
	}

	public static function getPeloID($id) {
		$query = DB::$pdo->from('Origens')
		                 ->where(array('id' => $id));
		return new ZOrigem($query->fetch());
	}

	public static function getPeloCodigo($codigo) {
		$query = DB::$pdo->from('Origens')
		                 ->where(array('codigo' => $codigo));
		return new ZOrigem($query->fetch());
	}

	private static function validarCampos(&$origem) {
		$erros = array();
		if(!is_numeric($origem['codigo']))
			$erros['codigo'] = 'O código não foi informado';
		$origem['descricao'] = strip_tags(trim($origem['descricao']));
		if(strlen($origem['descricao']) == 0)
			$erros['descricao'] = 'A descrição não pode ser vazia';
		if(!empty($erros))
			throw new ValidationException($erros);
	}

	private static function handleException(&$e) {
		if(stripos($e->getMessage(), 'PRIMARY') !== false)
			throw new ValidationException(array('id' => 'O id informado já está cadastrado'));
		if(stripos($e->getMessage(), 'Codigo_UNIQUE') !== false)
			throw new ValidationException(array('codigo' => 'O código informado já está cadastrado'));
	}

	public static function cadastrar($origem) {
		$_origem = $origem->toArray();
		self::validarCampos($_origem);
		try {
			$_origem['id'] = DB::$pdo->insertInto('Origens')->values($_origem)->execute();
		} catch (Exception $e) {
			self::handleException($e);
			throw $e;
		}
		return self::getPeloID($_origem['id']);
	}

	public static function atualizar($origem) {
		$_origem = $origem->toArray();
		if(!$_origem['id'])
			throw new ValidationException(array('id' => 'O id da origem não foi informado'));
		self::validarCampos($_origem);
		$campos = array(
			'codigo',
			'descricao',
		);
		try {
			$query = DB::$pdo->update('Origens');
			$query = $query->set(array_intersect_key($_origem, array_flip($campos)));
			$query = $query->where('id', $_origem['id']);
			$query->execute();
		} catch (Exception $e) {
			self::handleException($e);
			throw $e;
		}
		return self::getPeloID($_origem['id']);
	}

	public static function excluir($id) {
		if(!$id)
			throw new Exception('Não foi possível excluir a origem, o id da origem não foi informado');
		$query = DB::$pdo->deleteFrom('Origens')
		                 ->where(array('id' => $id));
		return $query->execute();
	}

	private static function initSearch() {
		return   DB::$pdo->from('Origens')
		                 ->orderBy('id ASC');
	}

	public static function getTodas($inicio = null, $quantidade = null) {
		$query = self::initSearch();
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_origems = $query->fetchAll();
		$origems = array();
		foreach($_origems as $origem)
			$origems[] = new ZOrigem($origem);
		return $origems;
	}

	public static function getCount() {
		$query = self::initSearch();
		return $query->count();
	}

}
