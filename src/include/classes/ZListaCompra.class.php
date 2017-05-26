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
class ListaCompraEstado {
	const ANALISE = 'Analise';
	const FECHADA = 'Fechada';
	const COMPRADA = 'Comprada';
}

/**
 * Lista de compras de produtos
 */
class ZListaCompra {
	private $id;
	private $descricao;
	private $estado;
	private $comprador_id;
	private $data_compra;
	private $data_cadastro;

	public function __construct($lista_compra = array()) {
		if(is_array($lista_compra)) {
			$this->setID(isset($lista_compra['id'])?$lista_compra['id']:null);
			$this->setDescricao(isset($lista_compra['descricao'])?$lista_compra['descricao']:null);
			$this->setEstado(isset($lista_compra['estado'])?$lista_compra['estado']:null);
			$this->setCompradorID(isset($lista_compra['compradorid'])?$lista_compra['compradorid']:null);
			$this->setDataCompra(isset($lista_compra['datacompra'])?$lista_compra['datacompra']:null);
			$this->setDataCadastro(isset($lista_compra['datacadastro'])?$lista_compra['datacadastro']:null);
		}
	}

	/**
	 * Identificador da lista
	 */
	public function getID() {
		return $this->id;
	}

	public function setID($id) {
		$this->id = $id;
	}

	/**
	 * Nome da lista, pode ser uma data
	 */
	public function getDescricao() {
		return $this->descricao;
	}

	public function setDescricao($descricao) {
		$this->descricao = $descricao;
	}

	/**
	 * Estado da lista de compra. Análise: Ainda estão sendo adicionado produtos na
	 * lista, Fechada: Está pronto para compra, Comprada: Todos os itens foram
	 * comprados
	 */
	public function getEstado() {
		return $this->estado;
	}

	public function setEstado($estado) {
		$this->estado = $estado;
	}

	/**
	 * Informa o funcionário que comprou os produtos da lista
	 */
	public function getCompradorID() {
		return $this->comprador_id;
	}

	public function setCompradorID($comprador_id) {
		$this->comprador_id = $comprador_id;
	}

	/**
	 * Informa da data de finalização da compra
	 */
	public function getDataCompra() {
		return $this->data_compra;
	}

	public function setDataCompra($data_compra) {
		$this->data_compra = $data_compra;
	}

	/**
	 * Data de cadastro da lista
	 */
	public function getDataCadastro() {
		return $this->data_cadastro;
	}

	public function setDataCadastro($data_cadastro) {
		$this->data_cadastro = $data_cadastro;
	}

	public function toArray() {
		$lista_compra = array();
		$lista_compra['id'] = $this->getID();
		$lista_compra['descricao'] = $this->getDescricao();
		$lista_compra['estado'] = $this->getEstado();
		$lista_compra['compradorid'] = $this->getCompradorID();
		$lista_compra['datacompra'] = $this->getDataCompra();
		$lista_compra['datacadastro'] = $this->getDataCadastro();
		return $lista_compra;
	}

	public static function getPeloID($id) {
		$query = DB::$pdo->from('Listas_Compras')
		                 ->where(array('id' => $id));
		return new ZListaCompra($query->fetch());
	}

	private static function validarCampos(&$lista_compra) {
		$erros = array();
		$lista_compra['descricao'] = strip_tags(trim($lista_compra['descricao']));
		if(strlen($lista_compra['descricao']) == 0)
			$erros['descricao'] = 'A descrição não pode ser vazia';
		$lista_compra['estado'] = trim($lista_compra['estado']);
		if(strlen($lista_compra['estado']) == 0)
			$lista_compra['estado'] = null;
		else if(!in_array($lista_compra['estado'], array('Analise', 'Fechada', 'Comprada')))
			$erros['estado'] = 'O estado informado não é válido';
		$lista_compra['compradorid'] = trim($lista_compra['compradorid']);
		if(strlen($lista_compra['compradorid']) == 0)
			$lista_compra['compradorid'] = null;
		else if(!is_numeric($lista_compra['compradorid']))
			$erros['compradorid'] = 'O comprador não foi informado';
		$lista_compra['datacompra'] = date('Y-m-d H:i:s');
		$lista_compra['datacadastro'] = date('Y-m-d H:i:s');
		if(!empty($erros))
			throw new ValidationException($erros);
	}

	private static function handleException(&$e) {
		if(stripos($e->getMessage(), 'PRIMARY') !== false)
			throw new ValidationException(array('id' => 'O ID informado já está cadastrado'));
	}

	public static function cadastrar($lista_compra) {
		$_lista_compra = $lista_compra->toArray();
		self::validarCampos($_lista_compra);
		try {
			$_lista_compra['id'] = DB::$pdo->insertInto('Listas_Compras')->values($_lista_compra)->execute();
		} catch (Exception $e) {
			self::handleException($e);
			throw $e;
		}
		return self::getPeloID($_lista_compra['id']);
	}

	public static function atualizar($lista_compra) {
		$_lista_compra = $lista_compra->toArray();
		if(!$_lista_compra['id'])
			throw new ValidationException(array('id' => 'O id da listacompra não foi informado'));
		self::validarCampos($_lista_compra);
		$campos = array(
			'descricao',
			'estado',
			'compradorid',
			'datacompra',
		);
		try {
			$query = DB::$pdo->update('Listas_Compras');
			$query = $query->set(array_intersect_key($_lista_compra, array_flip($campos)));
			$query = $query->where('id', $_lista_compra['id']);
			$query->execute();
		} catch (Exception $e) {
			self::handleException($e);
			throw $e;
		}
		return self::getPeloID($_lista_compra['id']);
	}

	public static function excluir($id) {
		if(!$id)
			throw new Exception('Não foi possível excluir a listacompra, o id da listacompra não foi informado');
		$query = DB::$pdo->deleteFrom('Listas_Compras')
		                 ->where(array('id' => $id));
		return $query->execute();
	}

	private static function initSearch() {
		return   DB::$pdo->from('Listas_Compras')
		                 ->orderBy('id ASC');
	}

	public static function getTodas($inicio = null, $quantidade = null) {
		$query = self::initSearch();
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_lista_compras = $query->fetchAll();
		$lista_compras = array();
		foreach($_lista_compras as $lista_compra)
			$lista_compras[] = new ZListaCompra($lista_compra);
		return $lista_compras;
	}

	public static function getCount() {
		$query = self::initSearch();
		return $query->count();
	}

	private static function initSearchDoCompradorID($comprador_id) {
		return   DB::$pdo->from('Listas_Compras')
		                 ->where(array('compradorid' => $comprador_id))
		                 ->orderBy('id ASC');
	}

	public static function getTodasDoCompradorID($comprador_id, $inicio = null, $quantidade = null) {
		$query = self::initSearchDoCompradorID($comprador_id);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_lista_compras = $query->fetchAll();
		$lista_compras = array();
		foreach($_lista_compras as $lista_compra)
			$lista_compras[] = new ZListaCompra($lista_compra);
		return $lista_compras;
	}

	public static function getCountDoCompradorID($comprador_id) {
		$query = self::initSearchDoCompradorID($comprador_id);
		return $query->count();
	}

}
