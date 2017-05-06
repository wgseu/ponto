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
 * Módulos do sistema que podem ser desativados/ativados
 */
class ZModulo {
	private $id;
	private $nome;
	private $descricao;
	private $image_index;
	private $habilitado;

	public function __construct($modulo = array()) {
		if(is_array($modulo)) {
			$this->setID($modulo['id']);
			$this->setNome($modulo['nome']);
			$this->setDescricao($modulo['descricao']);
			$this->setImageIndex($modulo['imageindex']);
			$this->setHabilitado($modulo['habilitado']);
		}
	}

	/**
	 * Identificador do módulo
	 */
	public function getID() {
		return $this->id;
	}

	public function setID($id) {
		$this->id = $id;
	}

	/**
	 * Nome do módulo, unico em todo o sistema
	 */
	public function getNome() {
		return $this->nome;
	}

	public function setNome($nome) {
		$this->nome = $nome;
	}

	/**
	 * Descrição do módulo, informa detalhes sobre a funcionalidade do módulo no
	 * sistema
	 */
	public function getDescricao() {
		return $this->descricao;
	}

	public function setDescricao($descricao) {
		$this->descricao = $descricao;
	}

	/**
	 * Índice da imagem que representa o módulo, tamanho 64x64
	 */
	public function getImageIndex() {
		return $this->image_index;
	}

	public function setImageIndex($image_index) {
		$this->image_index = $image_index;
	}

	/**
	 * Informa se o módulo do sistema está habilitado
	 */
	public function getHabilitado() {
		return $this->habilitado;
	}

	/**
	 * Informa se o módulo do sistema está habilitado
	 */
	public function isHabilitado() {
		return $this->habilitado == 'Y';
	}

	public function setHabilitado($habilitado) {
		$this->habilitado = $habilitado;
	}

	public function toArray() {
		$modulo = array();
		$modulo['id'] = $this->getID();
		$modulo['nome'] = $this->getNome();
		$modulo['descricao'] = $this->getDescricao();
		$modulo['imageindex'] = $this->getImageIndex();
		$modulo['habilitado'] = $this->getHabilitado();
		return $modulo;
	}

	public static function getPeloID($id) {
		$query = DB::$pdo->from('Modulos')
		                 ->where(array('id' => $id));
		return new ZModulo($query->fetch());
	}

	public static function getPeloNome($nome) {
		$query = DB::$pdo->from('Modulos')
		                 ->where(array('nome' => $nome));
		return new ZModulo($query->fetch());
	}

	private static function validarCampos(&$modulo) {
		$erros = array();
		$modulo['nome'] = strip_tags(trim($modulo['nome']));
		if(strlen($modulo['nome']) == 0)
			$erros['nome'] = 'O nome não pode ser vazio';
		$modulo['descricao'] = strip_tags(trim($modulo['descricao']));
		if(strlen($modulo['descricao']) == 0)
			$erros['descricao'] = 'A descrição não pode ser vazia';
		if(!is_numeric($modulo['imageindex']))
			$erros['imageindex'] = 'A imagem não foi informada';
		$modulo['habilitado'] = trim($modulo['habilitado']);
		if(strlen($modulo['habilitado']) == 0)
			$modulo['habilitado'] = 'N';
		else if(!in_array($modulo['habilitado'], array('Y', 'N')))
			$erros['habilitado'] = 'O habilitado informado não é válido';
		if(!empty($erros))
			throw new ValidationException($erros);
	}

	private static function handleException(&$e) {
		if(stripos($e->getMessage(), 'PRIMARY') !== false)
			throw new ValidationException(array('id' => 'O ID informado já está cadastrado'));
		if(stripos($e->getMessage(), 'Nome_UNIQUE') !== false)
			throw new ValidationException(array('nome' => 'O nome informado já está cadastrado'));
	}

	public static function cadastrar($modulo) {
		$_modulo = $modulo->toArray();
		self::validarCampos($_modulo);
		try {
			$_modulo['id'] = DB::$pdo->insertInto('Modulos')->values($_modulo)->execute();
		} catch (Exception $e) {
			self::handleException($e);
			throw $e;
		}
		return self::getPeloID($_modulo['id']);
	}

	public static function atualizar($modulo) {
		$_modulo = $modulo->toArray();
		if(!$_modulo['id'])
			throw new ValidationException(array('id' => 'O id do modulo não foi informado'));
		self::validarCampos($_modulo);
		$campos = array(
			'nome',
			'descricao',
			'imageindex',
			'habilitado',
		);
		try {
			$query = DB::$pdo->update('Modulos');
			$query = $query->set(array_intersect_key($_modulo, array_flip($campos)));
			$query = $query->where('id', $_modulo['id']);
			$query->execute();
		} catch (Exception $e) {
			self::handleException($e);
			throw $e;
		}
		return self::getPeloID($_modulo['id']);
	}

	public static function excluir($id) {
		if(!$id)
			throw new Exception('Não foi possível excluir o modulo, o id do modulo não foi informado');
		$query = DB::$pdo->deleteFrom('Modulos')
		                 ->where(array('id' => $id));
		return $query->execute();
	}

	private static function initSearch($busca) {
		$query = DB::$pdo->from('Modulos')
		                 ->orderBy('id ASC');
		$busca = trim($busca);
		if($busca != '') {
			$query = $query->where('CONCAT(nome, " ", descricao) LIKE ?', '%'.$busca.'%');
		}
		return $query;
	}

	public static function getTodos($busca = null, $inicio = null, $quantidade = null) {
		$query = self::initSearch($busca);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_modulos = $query->fetchAll();
		$modulos = array();
		foreach($_modulos as $modulo)
			$modulos[] = new ZModulo($modulo);
		return $modulos;
	}

	public static function getCount($busca = null) {
		$query = self::initSearch($busca);
		return $query->count();
	}

}
