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
 * Permite cadastrar informações da tabela nutricional
 */
class ZInformacao {
	private $id;
	private $produto_id;
	private $unidade_id;
	private $porcao;
	private $dieta;
	private $ingredientes;

	public function __construct($informacao = array()) {
		if(is_array($informacao)) {
			$this->setID(isset($informacao['id'])?$informacao['id']:null);
			$this->setProdutoID(isset($informacao['produtoid'])?$informacao['produtoid']:null);
			$this->setUnidadeID(isset($informacao['unidadeid'])?$informacao['unidadeid']:null);
			$this->setPorcao(isset($informacao['porcao'])?$informacao['porcao']:null);
			$this->setDieta(isset($informacao['dieta'])?$informacao['dieta']:null);
			$this->setIngredientes(isset($informacao['ingredientes'])?$informacao['ingredientes']:null);
		}
	}

	/**
	 * Identificador da informação nutricional
	 */
	public function getID() {
		return $this->id;
	}

	public function setID($id) {
		$this->id = $id;
	}

	/**
	 * Produto a que essa tabela de informações nutricionais pertence
	 */
	public function getProdutoID() {
		return $this->produto_id;
	}

	public function setProdutoID($produto_id) {
		$this->produto_id = $produto_id;
	}

	/**
	 * Unidade de medida da porção
	 */
	public function getUnidadeID() {
		return $this->unidade_id;
	}

	public function setUnidadeID($unidade_id) {
		$this->unidade_id = $unidade_id;
	}

	/**
	 * Quantidade da porção para base nos valores nutricionais
	 */
	public function getPorcao() {
		return $this->porcao;
	}

	public function setPorcao($porcao) {
		$this->porcao = $porcao;
	}

	/**
	 * Informa a quantidade de referência da dieta geralmente 2000kcal ou 8400kJ
	 */
	public function getDieta() {
		return $this->dieta;
	}

	public function setDieta($dieta) {
		$this->dieta = $dieta;
	}

	/**
	 * Informa todos os ingredientes que compõe o produto
	 */
	public function getIngredientes() {
		return $this->ingredientes;
	}

	public function setIngredientes($ingredientes) {
		$this->ingredientes = $ingredientes;
	}

	public function toArray() {
		$informacao = array();
		$informacao['id'] = $this->getID();
		$informacao['produtoid'] = $this->getProdutoID();
		$informacao['unidadeid'] = $this->getUnidadeID();
		$informacao['porcao'] = $this->getPorcao();
		$informacao['dieta'] = $this->getDieta();
		$informacao['ingredientes'] = $this->getIngredientes();
		return $informacao;
	}

	public static function getPeloID($id) {
		$query = DB::$pdo->from('Informacoes')
		                 ->where(array('id' => $id));
		return new ZInformacao($query->fetch());
	}

	public static function getPeloProdutoID($produto_id) {
		$query = DB::$pdo->from('Informacoes')
		                 ->where(array('produtoid' => $produto_id));
		return new ZInformacao($query->fetch());
	}

	private static function validarCampos(&$informacao) {
		$erros = array();
		if(!is_numeric($informacao['produtoid']))
			$erros['produtoid'] = 'O produto não foi informado';
		if(!is_numeric($informacao['unidadeid']))
			$erros['unidadeid'] = 'A unidade não foi informada';
		if(!is_numeric($informacao['porcao']))
			$erros['porcao'] = 'A porção não foi informada';
		if(!is_numeric($informacao['dieta']))
			$erros['dieta'] = 'A dieta não foi informada';
		else
			$informacao['dieta'] = floatval($informacao['dieta']);
		$informacao['ingredientes'] = strval($informacao['ingredientes']);
		if(strlen($informacao['ingredientes']) == 0)
			$informacao['ingredientes'] = null;
		if(!empty($erros))
			throw new ValidationException($erros);
	}

	private static function handleException(&$e) {
		if(stripos($e->getMessage(), 'PRIMARY') !== false)
			throw new ValidationException(array('id' => 'O ID informado já está cadastrado'));
		if(stripos($e->getMessage(), 'ProdutoID_UNIQUE') !== false)
			throw new ValidationException(array('produtoid' => 'O produto informado já está cadastrado'));
	}

	public static function cadastrar($informacao) {
		$_informacao = $informacao->toArray();
		self::validarCampos($_informacao);
		try {
			$_informacao['id'] = DB::$pdo->insertInto('Informacoes')->values($_informacao)->execute();
		} catch (Exception $e) {
			self::handleException($e);
			throw $e;
		}
		return self::getPeloID($_informacao['id']);
	}

	public static function atualizar($informacao) {
		$_informacao = $informacao->toArray();
		if(!$_informacao['id'])
			throw new ValidationException(array('id' => 'O id da informacao não foi informado'));
		self::validarCampos($_informacao);
		$campos = array(
			'produtoid',
			'unidadeid',
			'porcao',
			'dieta',
			'ingredientes',
		);
		try {
			$query = DB::$pdo->update('Informacoes');
			$query = $query->set(array_intersect_key($_informacao, array_flip($campos)));
			$query = $query->where('id', $_informacao['id']);
			$query->execute();
		} catch (Exception $e) {
			self::handleException($e);
			throw $e;
		}
		return self::getPeloID($_informacao['id']);
	}

	public static function excluir($id) {
		if(!$id)
			throw new Exception('Não foi possível excluir a informacao, o id da informacao não foi informado');
		$query = DB::$pdo->deleteFrom('Informacoes')
		                 ->where(array('id' => $id));
		return $query->execute();
	}

	private static function initSearch() {
		return   DB::$pdo->from('Informacoes')
		                 ->orderBy('id ASC');
	}

	public static function getTodas($inicio = null, $quantidade = null) {
		$query = self::initSearch();
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_informacaos = $query->fetchAll();
		$informacaos = array();
		foreach($_informacaos as $informacao)
			$informacaos[] = new ZInformacao($informacao);
		return $informacaos;
	}

	public static function getCount() {
		$query = self::initSearch();
		return $query->count();
	}

	private static function initSearchDoProdutoID($produto_id) {
		return   DB::$pdo->from('Informacoes')
		                 ->where(array('produtoid' => $produto_id))
		                 ->orderBy('id ASC');
	}

	public static function getTodasDoProdutoID($produto_id, $inicio = null, $quantidade = null) {
		$query = self::initSearchDoProdutoID($produto_id);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_informacaos = $query->fetchAll();
		$informacaos = array();
		foreach($_informacaos as $informacao)
			$informacaos[] = new ZInformacao($informacao);
		return $informacaos;
	}

	public static function getCountDoProdutoID($produto_id) {
		$query = self::initSearchDoProdutoID($produto_id);
		return $query->count();
	}

	private static function initSearchDaUnidadeID($unidade_id) {
		return   DB::$pdo->from('Informacoes')
		                 ->where(array('unidadeid' => $unidade_id))
		                 ->orderBy('id ASC');
	}

	public static function getTodasDaUnidadeID($unidade_id, $inicio = null, $quantidade = null) {
		$query = self::initSearchDaUnidadeID($unidade_id);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_informacaos = $query->fetchAll();
		$informacaos = array();
		foreach($_informacaos as $informacao)
			$informacaos[] = new ZInformacao($informacao);
		return $informacaos;
	}

	public static function getCountDaUnidadeID($unidade_id) {
		$query = self::initSearchDaUnidadeID($unidade_id);
		return $query->count();
	}

}
