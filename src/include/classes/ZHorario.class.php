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
 * Informa o horário de funcionamento do estabelecimento
 */
class ZHorario {
	private $id;
	private $inicio;
	private $fim;
	private $tempo_entrega;

	public function __construct($horario = array()) {
		if(is_array($horario)) {
			$this->setID($horario['id']);
			$this->setInicio($horario['inicio']);
			$this->setFim($horario['fim']);
			$this->setTempoEntrega($horario['tempoentrega']);
		}
	}

	/**
	 * Identificador do horário
	 */
	public function getID() {
		return $this->id;
	}

	public function setID($id) {
		$this->id = $id;
	}

	/**
	 * Início do horário de funcionamento em minutos contando a partir de domingo até
	 * sábado
	 */
	public function getInicio() {
		return $this->inicio;
	}

	public function setInicio($inicio) {
		$this->inicio = $inicio;
	}

	/**
	 * Duração em minutos em que o restaurante ficará aberto contando a partir de
	 * domingo
	 */
	public function getFim() {
		return $this->fim;
	}

	public function setFim($fim) {
		$this->fim = $fim;
	}

	/**
	 * Tempo médio de entrega em minutos dos pedidos para entrega no dia informado
	 */
	public function getTempoEntrega() {
		return $this->tempo_entrega;
	}

	public function setTempoEntrega($tempo_entrega) {
		$this->tempo_entrega = $tempo_entrega;
	}

	public function toArray() {
		$horario = array();
		$horario['id'] = $this->getID();
		$horario['inicio'] = $this->getInicio();
		$horario['fim'] = $this->getFim();
		$horario['tempoentrega'] = $this->getTempoEntrega();
		return $horario;
	}

	public static function getPeloID($id) {
		$query = DB::$pdo->from('Horarios')
		                 ->where(array('id' => $id));
		return new ZHorario($query->fetch());
	}

	private static function validarCampos(&$horario) {
		$erros = array();
		if(!is_numeric($horario['inicio']))
			$erros['inicio'] = 'O início não foi informado';
		if(!is_numeric($horario['fim']))
			$erros['fim'] = 'O fim não foi informado';
		$horario['tempoentrega'] = trim($horario['tempoentrega']);
		if(strlen($horario['tempoentrega']) == 0)
			$horario['tempoentrega'] = null;
		else if(!is_numeric($horario['tempoentrega']))
			$erros['tempoentrega'] = 'O tempo de entrega não foi informado';
		if(!empty($erros))
			throw new ValidationException($erros);
	}

	private static function handleException(&$e) {
		if(stripos($e->getMessage(), 'PRIMARY') !== false)
			throw new ValidationException(array('id' => 'O ID informado já está cadastrado'));
	}

	public static function cadastrar($horario) {
		$_horario = $horario->toArray();
		self::validarCampos($_horario);
		try {
			$_horario['id'] = DB::$pdo->insertInto('Horarios')->values($_horario)->execute();
		} catch (Exception $e) {
			self::handleException($e);
			throw $e;
		}
		return self::getPeloID($_horario['id']);
	}

	public static function atualizar($horario) {
		$_horario = $horario->toArray();
		if(!$_horario['id'])
			throw new ValidationException(array('id' => 'O id do horario não foi informado'));
		self::validarCampos($_horario);
		$campos = array(
			'inicio',
			'fim',
			'tempoentrega',
		);
		try {
			$query = DB::$pdo->update('Horarios');
			$query = $query->set(array_intersect_key($_horario, array_flip($campos)));
			$query = $query->where('id', $_horario['id']);
			$query->execute();
		} catch (Exception $e) {
			self::handleException($e);
			throw $e;
		}
		return self::getPeloID($_horario['id']);
	}

	public static function excluir($id) {
		if(!$id)
			throw new Exception('Não foi possível excluir o horario, o id do horario não foi informado');
		$query = DB::$pdo->deleteFrom('Horarios')
		                 ->where(array('id' => $id));
		return $query->execute();
	}

	private static function initSearch() {
		return   DB::$pdo->from('Horarios')
		                 ->orderBy('id ASC');
	}

	public static function getTodos($inicio = null, $quantidade = null) {
		$query = self::initSearch();
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_horarios = $query->fetchAll();
		$horarios = array();
		foreach($_horarios as $horario)
			$horarios[] = new ZHorario($horario);
		return $horarios;
	}

	public static function getCount() {
		$query = self::initSearch();
		return $query->count();
	}

}
