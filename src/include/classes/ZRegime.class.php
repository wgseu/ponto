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
 * Regimes tributários
 */
class ZRegime {
	private $id;
	private $codigo;
	private $descricao;

	public function __construct($regime = array()) {
		$this->fromArray($regime);
	}

	/**
	 * Identificador do regime tributário
	 */
	public function getID() {
		return $this->id;
	}

	public function setID($id) {
		$this->id = $id;
	}

	/**
	 * Código do regime tributário
	 */
	public function getCodigo() {
		return $this->codigo;
	}

	public function setCodigo($codigo) {
		$this->codigo = $codigo;
	}

	/**
	 * Descrição do regime tributário
	 */
	public function getDescricao() {
		return $this->descricao;
	}

	public function setDescricao($descricao) {
		$this->descricao = $descricao;
	}

	public function toArray() {
		$regime = array();
		$regime['id'] = $this->getID();
		$regime['codigo'] = $this->getCodigo();
		$regime['descricao'] = $this->getDescricao();
		return $regime;
	}

	public function fromArray($regime = array()) {
		if(!is_array($regime))
			return $this;
		$this->setID(isset($regime['id'])?$regime['id']:null);
		$this->setCodigo(isset($regime['codigo'])?$regime['codigo']:null);
		$this->setDescricao(isset($regime['descricao'])?$regime['descricao']:null);
	}

	public static function getPeloID($id) {
		$query = DB::$pdo->from('Regimes')
		                 ->where(array('id' => $id));
		return new ZRegime($query->fetch());
	}

	public static function getPeloCodigo($codigo) {
		$query = DB::$pdo->from('Regimes')
		                 ->where(array('codigo' => $codigo));
		return new ZRegime($query->fetch());
	}

	private static function validarCampos(&$regime) {
		$erros = array();
		if(!is_numeric($regime['codigo']))
			$erros['codigo'] = 'O código não foi informado';
		$regime['descricao'] = strip_tags(trim($regime['descricao']));
		if(strlen($regime['descricao']) == 0)
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

	public static function cadastrar($regime) {
		$_regime = $regime->toArray();
		self::validarCampos($_regime);
		try {
			$_regime['id'] = DB::$pdo->insertInto('Regimes')->values($_regime)->execute();
		} catch (Exception $e) {
			self::handleException($e);
			throw $e;
		}
		return self::getPeloID($_regime['id']);
	}

	public static function atualizar($regime) {
		$_regime = $regime->toArray();
		if(!$_regime['id'])
			throw new ValidationException(array('id' => 'O id do regime não foi informado'));
		self::validarCampos($_regime);
		$campos = array(
			'codigo',
			'descricao',
		);
		try {
			$query = DB::$pdo->update('Regimes');
			$query = $query->set(array_intersect_key($_regime, array_flip($campos)));
			$query = $query->where('id', $_regime['id']);
			$query->execute();
		} catch (Exception $e) {
			self::handleException($e);
			throw $e;
		}
		return self::getPeloID($_regime['id']);
	}

	public static function excluir($id) {
		if(!$id)
			throw new Exception('Não foi possível excluir o regime, o id do regime não foi informado');
		$query = DB::$pdo->deleteFrom('Regimes')
		                 ->where(array('id' => $id));
		return $query->execute();
	}

	private static function initSearch() {
		return   DB::$pdo->from('Regimes')
		                 ->orderBy('id ASC');
	}

	public static function getTodos($inicio = null, $quantidade = null) {
		$query = self::initSearch();
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_regimes = $query->fetchAll();
		$regimes = array();
		foreach($_regimes as $regime)
			$regimes[] = new ZRegime($regime);
		return $regimes;
	}

	public static function getCount() {
		$query = self::initSearch();
		return $query->count();
	}

}
