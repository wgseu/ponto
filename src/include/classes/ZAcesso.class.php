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
 * Permite acesso à uma determinada funcionalidade da lista de permissões
 */
class ZAcesso {
	private $id;
	private $funcao_id;
	private $permissao_id;

	public function __construct($acesso = array()) {
		if(is_array($acesso)) {
			$this->setID(isset($acesso['id'])?$acesso['id']:null);
			$this->setFuncaoID(isset($acesso['funcaoid'])?$acesso['funcaoid']:null);
			$this->setPermissaoID(isset($acesso['permissaoid'])?$acesso['permissaoid']:null);
		}
	}

	/**
	 * Identificador do acesso
	 */
	public function getID() {
		return $this->id;
	}

	/**
	 * Identificador do acesso
	 */
	public function setID($id) {
		$this->id = $id;
	}

	/**
	 * Função a que a permissão se aplica
	 */
	public function getFuncaoID() {
		return $this->funcao_id;
	}

	/**
	 * Função a que a permissão se aplica
	 */
	public function setFuncaoID($funcao_id) {
		$this->funcao_id = $funcao_id;
	}

	/**
	 * Permissão liberada para a função
	 */
	public function getPermissaoID() {
		return $this->permissao_id;
	}

	/**
	 * Permissão liberada para a função
	 */
	public function setPermissaoID($permissao_id) {
		$this->permissao_id = $permissao_id;
	}

	public function toArray() {
		$acesso = array();
		$acesso['id'] = $this->getID();
		$acesso['funcaoid'] = $this->getFuncaoID();
		$acesso['permissaoid'] = $this->getPermissaoID();
		return $acesso;
	}

	public static function getPeloID($id) {
		$query = DB::$pdo->from('Acessos')
		                 ->where(array('id' => $id));
		return new ZAcesso($query->fetch());
	}

	public static function getPelaFuncaoIDPermissaoID($funcao_id, $permissao_id) {
		$query = DB::$pdo->from('Acessos')
		                 ->where(array('funcaoid' => $funcao_id, 'permissaoid' => $permissao_id));
		return new ZAcesso($query->fetch());
	}

	public static function temPermissao($funcao_id, $permissao_nome) {
		$query = DB::$pdo->from('Acessos a')
						 ->leftJoin('Permissoes p ON p.id = a.permissaoid')
		                 ->where(array('a.funcaoid' => $funcao_id, 'p.nome' => $permissao_nome));
		$acesso = new ZAcesso($query->fetch());
		return !is_null($acesso->getID());
	}

	public static function getPermissoes($functionario_id) {
		$query = DB::$pdo->from('Funcionarios f')
						 ->select(null)
						 ->select('pr.nome')
						 ->leftJoin('Acessos ac ON ac.funcaoid = f.funcaoid')
						 ->leftJoin('Permissoes pr ON pr.id = ac.permissaoid')
		                 ->where(array('f.id' => $functionario_id))
						 ->groupBy('pr.id');
		$_permissoes = $query->fetchAll();
		$permissoes = array();
		foreach($_permissoes as $permissao)
			$permissoes[] = $permissao['nome'];
		return $permissoes;
	}

	private static function validarCampos(&$acesso) {
		$erros = array();
		if(!is_numeric($acesso['funcaoid']))
			$erros['funcaoid'] = 'A função não foi informada';
		if(!is_numeric($acesso['permissaoid']))
			$erros['permissaoid'] = 'A permissão não foi informada';
		if(!empty($erros))
			throw new ValidationException($erros);
	}

	private static function handleException(&$e) {
		if(stripos($e->getMessage(), 'PRIMARY') !== false)
			throw new ValidationException(array('id' => 'O ID informado já está cadastrado'));
		if(stripos($e->getMessage(), 'UK_Acessos_FuncaoID_PermissaoID') !== false)
			throw new ValidationException(array('permissaoid' => 'A permissão informada já está cadastrada'));
	}

	public static function cadastrar($acesso) {
		$_acesso = $acesso->toArray();
		self::validarCampos($_acesso);
		try {
			$_acesso['id'] = DB::$pdo->insertInto('Acessos')->values($_acesso)->execute();
		} catch (Exception $e) {
			self::handleException($e);
			throw $e;
		}
		return self::getPeloID($_acesso['id']);
	}

	public static function atualizar($acesso) {
		$_acesso = $acesso->toArray();
		if(!$_acesso['id'])
			throw new ValidationException(array('id' => 'O id do acesso não foi informado'));
		self::validarCampos($_acesso);
		$campos = array(
			'funcaoid',
			'permissaoid',
		);
		try {
			$query = DB::$pdo->update('Acessos');
			$query = $query->set(array_intersect_key($_acesso, array_flip($campos)));
			$query = $query->where('id', $_acesso['id']);
			$query->execute();
		} catch (Exception $e) {
			self::handleException($e);
			throw $e;
		}
		return self::getPeloID($_acesso['id']);
	}

	public static function excluir($id) {
		if(!$id)
			throw new Exception('Não foi possível excluir o acesso, o id do acesso não foi informado');
		$query = DB::$pdo->deleteFrom('Acessos')
		                 ->where(array('id' => $id));
		return $query->execute();
	}

	private static function initSearch() {
		return   DB::$pdo->from('Acessos')
		                 ->orderBy('id ASC');
	}

	public static function getTodos($inicio = null, $quantidade = null) {
		$query = self::initSearch();
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_acessos = $query->fetchAll();
		$acessos = array();
		foreach($_acessos as $acesso)
			$acessos[] = new ZAcesso($acesso);
		return $acessos;
	}

	public static function getCount() {
		$query = self::initSearch();
		return $query->count();
	}

	private static function initSearchDaFuncaoID($funcao_id) {
		return   DB::$pdo->from('Acessos')
		                 ->where(array('funcaoid' => $funcao_id))
		                 ->orderBy('id ASC');
	}

	public static function getTodosDaFuncaoID($funcao_id, $inicio = null, $quantidade = null) {
		$query = self::initSearchDaFuncaoID($funcao_id);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_acessos = $query->fetchAll();
		$acessos = array();
		foreach($_acessos as $acesso)
			$acessos[] = new ZAcesso($acesso);
		return $acessos;
	}

	public static function getCountDaFuncaoID($funcao_id) {
		$query = self::initSearchDaFuncaoID($funcao_id);
		return $query->count();
	}

	private static function initSearchDaPermissaoID($permissao_id) {
		return   DB::$pdo->from('Acessos')
		                 ->where(array('permissaoid' => $permissao_id))
		                 ->orderBy('id ASC');
	}

	public static function getTodosDaPermissaoID($permissao_id, $inicio = null, $quantidade = null) {
		$query = self::initSearchDaPermissaoID($permissao_id);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_acessos = $query->fetchAll();
		$acessos = array();
		foreach($_acessos as $acesso)
			$acessos[] = new ZAcesso($acesso);
		return $acessos;
	}

	public static function getCountDaPermissaoID($permissao_id) {
		$query = self::initSearchDaPermissaoID($permissao_id);
		return $query->count();
	}

}
