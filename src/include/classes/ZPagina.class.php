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
 * Página WEB que contém informações de contato, termos e outras informações da
 * empresa
 */
class ZPagina {
	private $id;
	private $nome;
	private $linguagem_id;
	private $conteudo;

	public function __construct($pagina = array()) {
		if(is_array($pagina)) {
			$this->setID($pagina['id']);
			$this->setNome($pagina['nome']);
			$this->setLinguagemID($pagina['linguagemid']);
			$this->setConteudo($pagina['conteudo']);
		}
	}

	/**
	 * Identificador da página
	 */
	public function getID() {
		return $this->id;
	}

	public function setID($id) {
		$this->id = $id;
	}

	/**
	 * Nome da página, único no sistema com o código da linguagem
	 */
	public function getNome() {
		return $this->nome;
	}

	public function setNome($nome) {
		$this->nome = $nome;
	}

	/**
	 * Código da linguagem para exibição no idioma correto, único com o nome
	 */
	public function getLinguagemID() {
		return $this->linguagem_id;
	}

	public function setLinguagemID($linguagem_id) {
		$this->linguagem_id = $linguagem_id;
	}

	/**
	 * Conteúdo da página, geralmente texto formatado em HTML
	 */
	public function getConteudo() {
		return $this->conteudo;
	}

	public function setConteudo($conteudo) {
		$this->conteudo = $conteudo;
	}

	public function toArray() {
		$pagina = array();
		$pagina['id'] = $this->getID();
		$pagina['nome'] = $this->getNome();
		$pagina['linguagemid'] = $this->getLinguagemID();
		$pagina['conteudo'] = $this->getConteudo();
		return $pagina;
	}

	public static function getPeloID($id) {
		$query = DB::$pdo->from('Paginas')
		                 ->where(array('id' => $id));
		return new ZPagina($query->fetch());
	}

	public static function getPeloNomeLinguagemID($nome, $linguagem_id) {
		$query = DB::$pdo->from('Paginas')
		                 ->where(array('nome' => $nome, 'linguagemid' => $linguagem_id));
		return new ZPagina($query->fetch());
	}

	private static function validarCampos(&$pagina) {
		$erros = array();
		$nomes = array_keys(get_pages_info());
		$linguagens = array_keys(get_languages_info());
		$pagina['nome'] = strip_tags(trim($pagina['nome']));
		if(strlen($pagina['nome']) == 0)
			$erros['nome'] = 'O nome não pode ser vazio';
		if(!in_array($pagina['nome'], $nomes))
			$erros['nome'] = 'A página informada não existe no site';
		if(!is_numeric($pagina['linguagemid']))
			$erros['linguagemid'] = 'A linguagem não foi informada';
		else
			$pagina['linguagemid'] = intval($pagina['linguagemid']);
		if(!in_array($pagina['linguagemid'], $linguagens))
			$erros['linguagemid'] = 'A linguagem informada ainda não é suportada';
		$pagina['conteudo'] = strval($pagina['conteudo']);
		if(strlen($pagina['conteudo']) == 0)
			$pagina['conteudo'] = null;
		if(!empty($erros))
			throw new ValidationException($erros);
	}

	private static function handleException(&$e) {
		if(stripos($e->getMessage(), 'PRIMARY') !== false)
			throw new ValidationException(array('id' => 'O ID informado já está cadastrado'));
		if(stripos($e->getMessage(), 'Nome_LinguagemID_UNIQUE') !== false)
			throw new ValidationException(array('linguagemid' => 'A linguagem informada já está cadastrada'));
	}

	public static function cadastrar($pagina) {
		$_pagina = $pagina->toArray();
		self::validarCampos($_pagina);
		try {
			$_pagina['id'] = DB::$pdo->insertInto('Paginas')->values($_pagina)->execute();
		} catch (Exception $e) {
			self::handleException($e);
			throw $e;
		}
		return self::getPeloID($_pagina['id']);
	}

	public static function atualizar($pagina) {
		$_pagina = $pagina->toArray();
		if(!$_pagina['id'])
			throw new ValidationException(array('id' => 'O id da pagina não foi informado'));
		self::validarCampos($_pagina);
		$campos = array(
			'nome',
			'linguagemid',
			'conteudo',
		);
		try {
			$query = DB::$pdo->update('Paginas');
			$query = $query->set(array_intersect_key($_pagina, array_flip($campos)));
			$query = $query->where('id', $_pagina['id']);
			$query->execute();
		} catch (Exception $e) {
			self::handleException($e);
			throw $e;
		}
		return self::getPeloID($_pagina['id']);
	}

	public static function excluir($id) {
		if(!$id)
			throw new Exception('Não foi possível excluir a pagina, o id da pagina não foi informado');
		$query = DB::$pdo->deleteFrom('Paginas')
		                 ->where(array('id' => $id));
		return $query->execute();
	}

	private static function initSearch($nome, $linguagemid) {
		$query = DB::$pdo->from('Paginas')
		                 ->orderBy('id ASC');
		$nome = trim($nome);
		if($nome != '') {
			$query = $query->where('nome', $nome);
		}
		$linguagemid = trim($linguagemid);
		if($linguagemid != '') {
			$query = $query->where('linguagemid', intval($linguagemid));
		}
		return $query;
	}

	public static function getTodas($nome = null, $linguagemid = null, $inicio = null, $quantidade = null) {
		$query = self::initSearch($nome, $linguagemid);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_paginas = $query->fetchAll();
		$paginas = array();
		foreach($_paginas as $pagina)
			$paginas[] = new ZPagina($pagina);
		return $paginas;
	}

	public static function getCount($nome = null, $linguagemid = null) {
		$query = self::initSearch($nome, $linguagemid);
		return $query->count();
	}

}
