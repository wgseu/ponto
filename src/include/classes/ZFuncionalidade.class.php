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
 * Grupo de funcionalidades do sistema
 */
class ZFuncionalidade {
	private $id;
	private $nome;
	private $descricao;

	public function __construct($funcionalidade = array()) {
		if(is_array($funcionalidade)) {
			$this->setID(isset($funcionalidade['id'])?$funcionalidade['id']:null);
			$this->setNome(isset($funcionalidade['nome'])?$funcionalidade['nome']:null);
			$this->setDescricao(isset($funcionalidade['descricao'])?$funcionalidade['descricao']:null);
		}
	}

	/**
	 * Identificador da funcionalidade
	 */
	public function getID() {
		return $this->id;
	}

	public function setID($id) {
		$this->id = $id;
	}

	/**
	 * Nome da funcionalidade, único em todo o sistema
	 */
	public function getNome() {
		return $this->nome;
	}

	public function setNome($nome) {
		$this->nome = $nome;
	}

	/**
	 * Descrição da funcionalidade
	 */
	public function getDescricao() {
		return $this->descricao;
	}

	public function setDescricao($descricao) {
		$this->descricao = $descricao;
	}

	public function toArray() {
		$funcionalidade = array();
		$funcionalidade['id'] = $this->getID();
		$funcionalidade['nome'] = $this->getNome();
		$funcionalidade['descricao'] = $this->getDescricao();
		return $funcionalidade;
	}

	public static function getPeloID($id) {
		$query = DB::$pdo->from('Funcionalidades')
		                 ->where(array('id' => $id));
		return new ZFuncionalidade($query->fetch());
	}

	public static function getPeloNome($nome) {
		$query = DB::$pdo->from('Funcionalidades')
		                 ->where(array('nome' => $nome));
		return new ZFuncionalidade($query->fetch());
	}

	private static function validarCampos(&$funcionalidade) {
		$erros = array();
		$funcionalidade['nome'] = strip_tags(trim($funcionalidade['nome']));
		if(strlen($funcionalidade['nome']) == 0)
			$erros['nome'] = 'O nome não pode ser vazio';
		$funcionalidade['descricao'] = strip_tags(trim($funcionalidade['descricao']));
		if(strlen($funcionalidade['descricao']) == 0)
			$erros['descricao'] = 'A descrição não pode ser vazia';
		if(!empty($erros))
			throw new ValidationException($erros);
	}

	private static function handleException(&$e) {
		if(stripos($e->getMessage(), 'PRIMARY') !== false)
			throw new ValidationException(array('id' => 'O ID informado já está cadastrado'));
		if(stripos($e->getMessage(), 'Nome_UNIQUE') !== false)
			throw new ValidationException(array('nome' => 'O nome informado já está cadastrado'));
	}

	private static function initSearch() {
		return   DB::$pdo->from('Funcionalidades')
		                 ->orderBy('id ASC');
	}

	public static function getTodas($inicio = null, $quantidade = null) {
		$query = self::initSearch();
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_funcionalidades = $query->fetchAll();
		$funcionalidades = array();
		foreach($_funcionalidades as $funcionalidade)
			$funcionalidades[] = new ZFuncionalidade($funcionalidade);
		return $funcionalidades;
	}

	public static function getCount() {
		$query = self::initSearch();
		return $query->count();
	}

}
