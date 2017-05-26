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
 * Informação tributária dos produtos
 */
class ZTributacao {
	private $id;
	private $ncm;
	private $cest;
	private $origem_id;
	private $operacao_id;
	private $imposto_id;

	public function __construct($tributacao = array()) {
		$this->fromArray($tributacao);
	}

	/**
	 * Identificador da tributação
	 */
	public function getID() {
		return $this->id;
	}

	public function setID($id) {
		$this->id = $id;
	}

	/**
	 * Código NCM (Nomenclatura Comum do Mercosul) do produto
	 */
	public function getNCM() {
		return $this->ncm;
	}

	public function setNCM($ncm) {
		$this->ncm = $ncm;
	}

	/**
	 * Código CEST do produto (Opcional)
	 */
	public function getCEST() {
		return $this->cest;
	}

	public function setCEST($cest) {
		$this->cest = $cest;
	}

	/**
	 * Origem do produto
	 */
	public function getOrigemID() {
		return $this->origem_id;
	}

	public function setOrigemID($origem_id) {
		$this->origem_id = $origem_id;
	}

	/**
	 * CFOP do produto
	 */
	public function getOperacaoID() {
		return $this->operacao_id;
	}

	public function setOperacaoID($operacao_id) {
		$this->operacao_id = $operacao_id;
	}

	/**
	 * Imposto do produto
	 */
	public function getImpostoID() {
		return $this->imposto_id;
	}

	public function setImpostoID($imposto_id) {
		$this->imposto_id = $imposto_id;
	}

	public function toArray() {
		$tributacao = array();
		$tributacao['id'] = $this->getID();
		$tributacao['ncm'] = $this->getNCM();
		$tributacao['cest'] = $this->getCEST();
		$tributacao['origemid'] = $this->getOrigemID();
		$tributacao['operacaoid'] = $this->getOperacaoID();
		$tributacao['impostoid'] = $this->getImpostoID();
		return $tributacao;
	}

	public function fromArray($tributacao = array()) {
		if(!is_array($tributacao))
			return $this;
		$this->setID(isset($tributacao['id'])?$tributacao['id']:null);
		$this->setNCM(isset($tributacao['ncm'])?$tributacao['ncm']:null);
		$this->setCEST(isset($tributacao['cest'])?$tributacao['cest']:null);
		$this->setOrigemID(isset($tributacao['origemid'])?$tributacao['origemid']:null);
		$this->setOperacaoID(isset($tributacao['operacaoid'])?$tributacao['operacaoid']:null);
		$this->setImpostoID(isset($tributacao['impostoid'])?$tributacao['impostoid']:null);
	}

	public static function getPeloID($id) {
		$query = DB::$pdo->from('Tributacoes')
		                 ->where(array('id' => $id));
		return new ZTributacao($query->fetch());
	}

	private static function validarCampos(&$tributacao) {
		$erros = array();
		$tributacao['ncm'] = strip_tags(trim($tributacao['ncm']));
		if(strlen($tributacao['ncm']) == 0)
			$erros['ncm'] = 'O NCM não pode ser vazio';
		$tributacao['cest'] = strip_tags(trim($tributacao['cest']));
		if(strlen($tributacao['cest']) == 0)
			$tributacao['cest'] = null;
		if(!is_numeric($tributacao['origemid']))
			$erros['origemid'] = 'A origem não foi informada';
		if(!is_numeric($tributacao['operacaoid']))
			$erros['operacaoid'] = 'O CFOP não foi informado';
		if(!is_numeric($tributacao['impostoid']))
			$erros['impostoid'] = 'O imposto não foi informado';
		if(!empty($erros))
			throw new ValidationException($erros);
	}

	private static function handleException(&$e) {
		if(stripos($e->getMessage(), 'PRIMARY') !== false)
			throw new ValidationException(array('id' => 'O id informado já está cadastrado'));
	}

	public static function cadastrar($tributacao) {
		$_tributacao = $tributacao->toArray();
		self::validarCampos($_tributacao);
		try {
			$_tributacao['id'] = DB::$pdo->insertInto('Tributacoes')->values($_tributacao)->execute();
		} catch (Exception $e) {
			self::handleException($e);
			throw $e;
		}
		return self::getPeloID($_tributacao['id']);
	}

	public static function atualizar($tributacao) {
		$_tributacao = $tributacao->toArray();
		if(!$_tributacao['id'])
			throw new ValidationException(array('id' => 'O id da tributacao não foi informado'));
		self::validarCampos($_tributacao);
		$campos = array(
			'ncm',
			'cest',
			'origemid',
			'operacaoid',
			'impostoid',
		);
		try {
			$query = DB::$pdo->update('Tributacoes');
			$query = $query->set(array_intersect_key($_tributacao, array_flip($campos)));
			$query = $query->where('id', $_tributacao['id']);
			$query->execute();
		} catch (Exception $e) {
			self::handleException($e);
			throw $e;
		}
		return self::getPeloID($_tributacao['id']);
	}

	public static function excluir($id) {
		if(!$id)
			throw new Exception('Não foi possível excluir a tributacao, o id da tributacao não foi informado');
		$query = DB::$pdo->deleteFrom('Tributacoes')
		                 ->where(array('id' => $id));
		return $query->execute();
	}

	private static function initSearch() {
		return   DB::$pdo->from('Tributacoes')
		                 ->orderBy('id ASC');
	}

	public static function getTodas($inicio = null, $quantidade = null) {
		$query = self::initSearch();
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_tributacaos = $query->fetchAll();
		$tributacaos = array();
		foreach($_tributacaos as $tributacao)
			$tributacaos[] = new ZTributacao($tributacao);
		return $tributacaos;
	}

	public static function getCount() {
		$query = self::initSearch();
		return $query->count();
	}

	private static function initSearchDaOrigemID($origem_id) {
		return   DB::$pdo->from('Tributacoes')
		                 ->where(array('origemid' => $origem_id))
		                 ->orderBy('id ASC');
	}

	public static function getTodasDaOrigemID($origem_id, $inicio = null, $quantidade = null) {
		$query = self::initSearchDaOrigemID($origem_id);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_tributacaos = $query->fetchAll();
		$tributacaos = array();
		foreach($_tributacaos as $tributacao)
			$tributacaos[] = new ZTributacao($tributacao);
		return $tributacaos;
	}

	public static function getCountDaOrigemID($origem_id) {
		$query = self::initSearchDaOrigemID($origem_id);
		return $query->count();
	}

	private static function initSearchDaOperacaoID($operacao_id) {
		return   DB::$pdo->from('Tributacoes')
		                 ->where(array('operacaoid' => $operacao_id))
		                 ->orderBy('id ASC');
	}

	public static function getTodasDaOperacaoID($operacao_id, $inicio = null, $quantidade = null) {
		$query = self::initSearchDaOperacaoID($operacao_id);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_tributacaos = $query->fetchAll();
		$tributacaos = array();
		foreach($_tributacaos as $tributacao)
			$tributacaos[] = new ZTributacao($tributacao);
		return $tributacaos;
	}

	public static function getCountDaOperacaoID($operacao_id) {
		$query = self::initSearchDaOperacaoID($operacao_id);
		return $query->count();
	}

	private static function initSearchDoImpostoID($imposto_id) {
		return   DB::$pdo->from('Tributacoes')
		                 ->where(array('impostoid' => $imposto_id))
		                 ->orderBy('id ASC');
	}

	public static function getTodasDoImpostoID($imposto_id, $inicio = null, $quantidade = null) {
		$query = self::initSearchDoImpostoID($imposto_id);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_tributacaos = $query->fetchAll();
		$tributacaos = array();
		foreach($_tributacaos as $tributacao)
			$tributacaos[] = new ZTributacao($tributacao);
		return $tributacaos;
	}

	public static function getCountDoImpostoID($imposto_id) {
		$query = self::initSearchDoImpostoID($imposto_id);
		return $query->count();
	}

}
