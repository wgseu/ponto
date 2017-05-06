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
class EventoEstado {
	const ABERTO = 'Aberto';
	const ASSINADO = 'Assinado';
	const VALIDADO = 'Validado';
	const PENDENTE = 'Pendente';
	const PROCESSAMENTO = 'Processamento';
	const DENEGADO = 'Denegado';
	const CANCELADO = 'Cancelado';
	const REJEITADO = 'Rejeitado';
	const CONTINGENCIA = 'Contingencia';
	const INUTILIZADO = 'Inutilizado';
	const AUTORIZADO = 'Autorizado';
}

/**
 * Eventos de envio das notas
 */
class ZEvento {
	private $id;
	private $nota_id;
	private $estado;
	private $mensagem;
	private $codigo;
	private $data_criacao;

	public function __construct($evento = array()) {
		$this->fromArray($evento);
	}

	/**
	 * Identificador do evento
	 */
	public function getID() {
		return $this->id;
	}

	public function setID($id) {
		$this->id = $id;
	}

	/**
	 * Nota a qual o evento foi criado
	 */
	public function getNotaID() {
		return $this->nota_id;
	}

	public function setNotaID($nota_id) {
		$this->nota_id = $nota_id;
	}

	/**
	 * Estado do evento
	 */
	public function getEstado() {
		return $this->estado;
	}

	public function setEstado($estado) {
		$this->estado = $estado;
	}

	/**
	 * Mensagem do evento, descreve que aconteceu
	 */
	public function getMensagem() {
		return $this->mensagem;
	}

	public function setMensagem($mensagem) {
		$this->mensagem = $mensagem;
	}

	/**
	 * Código de status do evento, geralmente código de erro de uma exceção
	 */
	public function getCodigo() {
		return $this->codigo;
	}

	public function setCodigo($codigo) {
		$this->codigo = $codigo;
	}

	/**
	 * Data de criação do evento
	 */
	public function getDataCriacao() {
		return $this->data_criacao;
	}

	public function setDataCriacao($data_criacao) {
		$this->data_criacao = $data_criacao;
	}

	public function toArray() {
		$evento = array();
		$evento['id'] = $this->getID();
		$evento['notaid'] = $this->getNotaID();
		$evento['estado'] = $this->getEstado();
		$evento['mensagem'] = $this->getMensagem();
		$evento['codigo'] = $this->getCodigo();
		$evento['datacriacao'] = $this->getDataCriacao();
		return $evento;
	}

	public function fromArray($evento = array()) {
		if(!is_array($evento))
			return $this;
		$this->setID($evento['id']);
		$this->setNotaID($evento['notaid']);
		$this->setEstado($evento['estado']);
		$this->setMensagem($evento['mensagem']);
		$this->setCodigo($evento['codigo']);
		$this->setDataCriacao($evento['datacriacao']);
	}

	public static function getPeloID($id) {
		$query = DB::$pdo->from('Eventos')
		                 ->where(array('id' => $id));
		return new ZEvento($query->fetch());
	}

	private static function validarCampos(&$evento) {
		$erros = array();
		if(!is_numeric($evento['notaid']))
			$erros['notaid'] = 'A nota não foi informada';
		$evento['estado'] = strval($evento['estado']);
		if(!in_array($evento['estado'], array('Aberto', 'Assinado', 'Validado', 'Pendente', 'Processamento', 'Denegado', 'Cancelado', 'Rejeitado', 'Contingencia', 'Inutilizado', 'Autorizado')))
			$erros['estado'] = 'O estado informado não é válido';
		$evento['mensagem'] = strip_tags(trim($evento['mensagem']));
		if(strlen($evento['mensagem']) == 0)
			$erros['mensagem'] = 'A mensagem não pode ser vazia';
		$evento['codigo'] = strip_tags(trim($evento['codigo']));
		if(strlen($evento['codigo']) == 0)
			$erros['codigo'] = 'O código não pode ser vazio';
		$evento['datacriacao'] = date('Y-m-d H:i:s');
		if(!empty($erros))
			throw new ValidationException($erros);
	}

	private static function handleException(&$e) {
		if(stripos($e->getMessage(), 'PRIMARY') !== false)
			throw new ValidationException(array('id' => 'O id informado já está cadastrado'));
	}

	public static function cadastrar($evento) {
		$_evento = $evento->toArray();
		self::validarCampos($_evento);
		try {
			$_evento['id'] = DB::$pdo->insertInto('Eventos')->values($_evento)->execute();
		} catch (Exception $e) {
			self::handleException($e);
			throw $e;
		}
		return self::getPeloID($_evento['id']);
	}

	public static function atualizar($evento) {
		$_evento = $evento->toArray();
		if(!$_evento['id'])
			throw new ValidationException(array('id' => 'O id do evento não foi informado'));
		self::validarCampos($_evento);
		$campos = array(
			'notaid',
			'estado',
			'mensagem',
			'codigo',
		);
		try {
			$query = DB::$pdo->update('Eventos');
			$query = $query->set(array_intersect_key($_evento, array_flip($campos)));
			$query = $query->where('id', $_evento['id']);
			$query->execute();
		} catch (Exception $e) {
			self::handleException($e);
			throw $e;
		}
		return self::getPeloID($_evento['id']);
	}

	public static function excluir($id) {
		if(!$id)
			throw new Exception('Não foi possível excluir o evento, o id do evento não foi informado');
		$query = DB::$pdo->deleteFrom('Eventos')
		                 ->where(array('id' => $id));
		return $query->execute();
	}

	/**
	 * Chamado quando ocorre uma falha na execução de uma tarefa
	 */
	public static function log($nota_id, $estado, $mensagem, $codigo) {
		$_evento = new ZEvento();
		$_evento->setNotaID($nota_id);
		$_evento->setEstado($estado);
		$_evento->setMensagem($mensagem);
		$_evento->setCodigo($codigo);
		return self::cadastrar($_evento);
	}

	private static function initSearch() {
		return   DB::$pdo->from('Eventos')
		                 ->orderBy('id ASC');
	}

	public static function getTodos($inicio = null, $quantidade = null) {
		$query = self::initSearch();
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_eventos = $query->fetchAll();
		$eventos = array();
		foreach($_eventos as $evento)
			$eventos[] = new ZEvento($evento);
		return $eventos;
	}

	public static function getCount() {
		$query = self::initSearch();
		return $query->count();
	}

	private static function initSearchDaNotaID($nota_id) {
		return   DB::$pdo->from('Eventos')
		                 ->where(array('notaid' => $nota_id))
		                 ->orderBy('id ASC');
	}

	public static function getTodosDaNotaID($nota_id, $inicio = null, $quantidade = null) {
		$query = self::initSearchDaNotaID($nota_id);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_eventos = $query->fetchAll();
		$eventos = array();
		foreach($_eventos as $evento)
			$eventos[] = new ZEvento($evento);
		return $eventos;
	}

	public static function getCountDaNotaID($nota_id) {
		$query = self::initSearchDaNotaID($nota_id);
		return $query->count();
	}

}
