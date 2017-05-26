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
 * Informa todos os valores nutricionais da tabela nutricional
 */
class ZValorNutricional {
	private $id;
	private $informacao_id;
	private $unidade_id;
	private $nome;
	private $quantidade;
	private $valor_diario;

	public function __construct($valor_nutricional = array()) {
		if(is_array($valor_nutricional)) {
			$this->setID(isset($valor_nutricional['id'])?$valor_nutricional['id']:null);
			$this->setInformacaoID(isset($valor_nutricional['informacaoid'])?$valor_nutricional['informacaoid']:null);
			$this->setUnidadeID(isset($valor_nutricional['unidadeid'])?$valor_nutricional['unidadeid']:null);
			$this->setNome(isset($valor_nutricional['nome'])?$valor_nutricional['nome']:null);
			$this->setQuantidade(isset($valor_nutricional['quantidade'])?$valor_nutricional['quantidade']:null);
			$this->setValorDiario(isset($valor_nutricional['valordiario'])?$valor_nutricional['valordiario']:null);
		}
	}

	/**
	 * Identificador do valor nutricional
	 */
	public function getID() {
		return $this->id;
	}

	public function setID($id) {
		$this->id = $id;
	}

	/**
	 * Informe a que tabela nutricional este valor pertence
	 */
	public function getInformacaoID() {
		return $this->informacao_id;
	}

	public function setInformacaoID($informacao_id) {
		$this->informacao_id = $informacao_id;
	}

	/**
	 * Unidade de medida do valor nutricional, geralmente grama, exceto para valor
	 * energético
	 */
	public function getUnidadeID() {
		return $this->unidade_id;
	}

	public function setUnidadeID($unidade_id) {
		$this->unidade_id = $unidade_id;
	}

	/**
	 * Nome do valor nutricional
	 */
	public function getNome() {
		return $this->nome;
	}

	public function setNome($nome) {
		$this->nome = $nome;
	}

	/**
	 * Quantidade do valor nutricional com base na porção
	 */
	public function getQuantidade() {
		return $this->quantidade;
	}

	public function setQuantidade($quantidade) {
		$this->quantidade = $quantidade;
	}

	/**
	 * Valor diário em %
	 */
	public function getValorDiario() {
		return $this->valor_diario;
	}

	public function setValorDiario($valor_diario) {
		$this->valor_diario = $valor_diario;
	}

	public function toArray() {
		$valor_nutricional = array();
		$valor_nutricional['id'] = $this->getID();
		$valor_nutricional['informacaoid'] = $this->getInformacaoID();
		$valor_nutricional['unidadeid'] = $this->getUnidadeID();
		$valor_nutricional['nome'] = $this->getNome();
		$valor_nutricional['quantidade'] = $this->getQuantidade();
		$valor_nutricional['valordiario'] = $this->getValorDiario();
		return $valor_nutricional;
	}

	public static function getPeloID($id) {
		$query = DB::$pdo->from('Valores_Nutricionais')
		                 ->where(array('id' => $id));
		return new ZValorNutricional($query->fetch());
	}

	public static function getPelaInformacaoIDNome($informacao_id, $nome) {
		$query = DB::$pdo->from('Valores_Nutricionais')
		                 ->where(array('informacaoid' => $informacao_id, 'nome' => $nome));
		return new ZValorNutricional($query->fetch());
	}

	private static function validarCampos(&$valor_nutricional) {
		$erros = array();
		if(!is_numeric($valor_nutricional['informacaoid']))
			$erros['informacaoid'] = 'A informação não foi informada';
		if(!is_numeric($valor_nutricional['unidadeid']))
			$erros['unidadeid'] = 'A unidade não foi informada';
		$valor_nutricional['nome'] = strip_tags(trim($valor_nutricional['nome']));
		if(strlen($valor_nutricional['nome']) == 0)
			$erros['nome'] = 'O nome não pode ser vazio';
		if(!is_numeric($valor_nutricional['quantidade']))
			$erros['quantidade'] = 'A quantidade não foi informada';
		$valor_nutricional['valordiario'] = trim($valor_nutricional['valordiario']);
		if(strlen($valor_nutricional['valordiario']) == 0)
			$valor_nutricional['valordiario'] = null;
		else if(!is_numeric($valor_nutricional['valordiario']))
			$erros['valordiario'] = 'O valor diário não foi informado';
		if(!empty($erros))
			throw new ValidationException($erros);
	}

	private static function handleException(&$e) {
		if(stripos($e->getMessage(), 'PRIMARY') !== false)
			throw new ValidationException(array('id' => 'O ID informado já está cadastrado'));
		if(stripos($e->getMessage(), 'UK_Informacao_Nome') !== false)
			throw new ValidationException(array('nome' => 'O nome informado já está cadastrado'));
	}

	public static function cadastrar($valor_nutricional) {
		$_valor_nutricional = $valor_nutricional->toArray();
		self::validarCampos($_valor_nutricional);
		try {
			$_valor_nutricional['id'] = DB::$pdo->insertInto('Valores_Nutricionais')->values($_valor_nutricional)->execute();
		} catch (Exception $e) {
			self::handleException($e);
			throw $e;
		}
		return self::getPeloID($_valor_nutricional['id']);
	}

	public static function atualizar($valor_nutricional) {
		$_valor_nutricional = $valor_nutricional->toArray();
		if(!$_valor_nutricional['id'])
			throw new ValidationException(array('id' => 'O id da valornutricional não foi informado'));
		self::validarCampos($_valor_nutricional);
		$campos = array(
			'informacaoid',
			'unidadeid',
			'nome',
			'quantidade',
			'valordiario',
		);
		try {
			$query = DB::$pdo->update('Valores_Nutricionais');
			$query = $query->set(array_intersect_key($_valor_nutricional, array_flip($campos)));
			$query = $query->where('id', $_valor_nutricional['id']);
			$query->execute();
		} catch (Exception $e) {
			self::handleException($e);
			throw $e;
		}
		return self::getPeloID($_valor_nutricional['id']);
	}

	public static function excluir($id) {
		if(!$id)
			throw new Exception('Não foi possível excluir a valornutricional, o id da valornutricional não foi informado');
		$query = DB::$pdo->deleteFrom('Valores_Nutricionais')
		                 ->where(array('id' => $id));
		return $query->execute();
	}

	private static function initSearch() {
		return   DB::$pdo->from('Valores_Nutricionais')
		                 ->orderBy('id ASC');
	}

	public static function getTodas($inicio = null, $quantidade = null) {
		$query = self::initSearch();
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_valor_nutricionals = $query->fetchAll();
		$valor_nutricionals = array();
		foreach($_valor_nutricionals as $valor_nutricional)
			$valor_nutricionals[] = new ZValorNutricional($valor_nutricional);
		return $valor_nutricionals;
	}

	public static function getCount() {
		$query = self::initSearch();
		return $query->count();
	}

	private static function initSearchDaInformacaoID($informacao_id) {
		return   DB::$pdo->from('Valores_Nutricionais')
		                 ->where(array('informacaoid' => $informacao_id))
		                 ->orderBy('id ASC');
	}

	public static function getTodasDaInformacaoID($informacao_id, $inicio = null, $quantidade = null) {
		$query = self::initSearchDaInformacaoID($informacao_id);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_valor_nutricionals = $query->fetchAll();
		$valor_nutricionals = array();
		foreach($_valor_nutricionals as $valor_nutricional)
			$valor_nutricionals[] = new ZValorNutricional($valor_nutricional);
		return $valor_nutricionals;
	}

	public static function getCountDaInformacaoID($informacao_id) {
		$query = self::initSearchDaInformacaoID($informacao_id);
		return $query->count();
	}

	private static function initSearchDaUnidadeID($unidade_id) {
		return   DB::$pdo->from('Valores_Nutricionais')
		                 ->where(array('unidadeid' => $unidade_id))
		                 ->orderBy('id ASC');
	}

	public static function getTodasDaUnidadeID($unidade_id, $inicio = null, $quantidade = null) {
		$query = self::initSearchDaUnidadeID($unidade_id);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_valor_nutricionals = $query->fetchAll();
		$valor_nutricionals = array();
		foreach($_valor_nutricionals as $valor_nutricional)
			$valor_nutricionals[] = new ZValorNutricional($valor_nutricional);
		return $valor_nutricionals;
	}

	public static function getCountDaUnidadeID($unidade_id) {
		$query = self::initSearchDaUnidadeID($unidade_id);
		return $query->count();
	}

}
