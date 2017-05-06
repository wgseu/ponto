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
class ZComanda {
	private $id;
	private $nome;
	private $ativa;

	public function __construct($comanda = array()) {
		if(is_array($comanda)) {
			$this->setID($comanda['id']);
			$this->setNome($comanda['nome']);
			$this->setAtiva($comanda['ativa']);
		}
	}

	public function getID() {
		return $this->id;
	}

	public function setID($id) {
		$this->id = $id;
	}

	public function getNome() {
		return $this->nome;
	}

	public function setNome($nome) {
		$this->nome = $nome;
	}

	public function getAtiva() {
		return $this->ativa;
	}

	public function isAtiva() {
		return $this->ativa == 'Y';
	}

	public function setAtiva($ativa) {
		$this->ativa = $ativa;
	}

	public function toArray() {
		$comanda = array();
		$comanda['id'] = $this->getID();
		$comanda['nome'] = $this->getNome();
		$comanda['ativa'] = $this->getAtiva();
		return $comanda;
	}

	public static function getPeloID($id) {
		$query = DB::$pdo->from('Comandas')
		                 ->where(array('id' => $id));
		return new ZComanda($query->fetch());
	}

	public static function getPeloNome($nome) {
		$query = DB::$pdo->from('Comandas')
		                 ->where(array('nome' => $nome));
		return new ZComanda($query->fetch());
	}

	public static function getProximoID() {
		$query = DB::$pdo->from('Comandas')
						 ->select(null)
						 ->select('MAX(id) as id');
		return $query->fetch('id') + 1;
	}

	private static function validarCampos(&$comanda) {
		$erros = array();
		$comanda['nome'] = strip_tags(trim($comanda['nome']));
		if(strlen($comanda['nome']) == 0)
			$erros['nome'] = 'O Nome não pode ser vazio';
		$comanda['ativa'] = trim($comanda['ativa']);
		if(strlen($comanda['ativa']) == 0)
			$comanda['ativa'] = 'N';
		else if(!in_array($comanda['ativa'], array('Y', 'N')))
			$erros['ativa'] = 'O estado de ativação da comanda não é válido';
		if(!empty($erros))
			throw new ValidationException($erros);
	}

	private static function handleException(&$e) {
		if(stripos($e->getMessage(), 'PRIMARY') !== false)
			throw new ValidationException(array('id' => 'O ID informado já está cadastrado'));
		if(stripos($e->getMessage(), 'Nome_UNIQUE') !== false)
			throw new ValidationException(array('nome' => 'O Nome informado já está cadastrado'));
	}

	public static function cadastrar($comanda) {
		$_comanda = $comanda->toArray();
		self::validarCampos($_comanda);
		try {
			$_comanda['id'] = DB::$pdo->insertInto('Comandas')->values($_comanda)->execute();
		} catch (Exception $e) {
			self::handleException($e);
			throw $e;
		}
		return self::getPeloID($_comanda['id']);
	}

	public static function atualizar($comanda) {
		$_comanda = $comanda->toArray();
		if(!$_comanda['id'])
			throw new ValidationException(array('id' => 'O id da comanda não foi informado'));
		self::validarCampos($_comanda);
		$campos = array(
			'nome',
			'ativa',
		);
		try {
			$query = DB::$pdo->update('Comandas');
			$query = $query->set(array_intersect_key($_comanda, array_flip($campos)));
			$query = $query->where('id', $_comanda['id']);
			$query->execute();
		} catch (Exception $e) {
			self::handleException($e);
			throw $e;
		}
		return self::getPeloID($_comanda['id']);
	}

	public static function excluir($id) {
		if(!$id)
			throw new Exception('Não foi possível excluir a comanda, o id da comanda não foi informado');
		$query = DB::$pdo->deleteFrom('Comandas')
		                 ->where(array('id' => $id));
		return $query->execute();
	}

	private static function initSearch($ativa, $busca) {
		$query = DB::$pdo->from('Comandas');
		$busca = trim($busca);
		if(is_numeric($busca)) {
			$query = $query->where('id', $busca);
		} else if($busca != '') {
			$query = $query->where('nome LIKE ?', '%'.$busca.'%');
		}
		if(in_array($ativa, array('Y', 'N'))) {
			$query = $query->where('ativa', $ativa);
		}
		$query = $query->orderBy('id ASC');
		return $query;
	}

	public static function getTodas($ativa = null, $busca = null, $inicio = null, $quantidade = null) {
		$query = self::initSearch($ativa, $busca);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_comandas = $query->fetchAll();
		$comandas = array();
		foreach($_comandas as $comanda)
			$comandas[] = new ZComanda($comanda);
		return $comandas;
	}

	public static function getCount($ativa = null, $busca = null) {
		$query = self::initSearch($ativa, $busca);
		return $query->count();
	}

}
