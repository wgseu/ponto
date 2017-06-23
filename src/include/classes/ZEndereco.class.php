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
 * Endereços de ruas e avenidas com informação de CEP
 */
class ZEndereco {
	private $id;
	private $cidade_id;
	private $bairro_id;
	private $logradouro;
	private $cep;

	public function __construct($endereco = array()) {
		if(is_array($endereco)) {
			$this->setID(isset($endereco['id'])?$endereco['id']:null);
			$this->setCidadeID(isset($endereco['cidadeid'])?$endereco['cidadeid']:null);
			$this->setBairroID(isset($endereco['bairroid'])?$endereco['bairroid']:null);
			$this->setLogradouro(isset($endereco['logradouro'])?$endereco['logradouro']:null);
			$this->setCEP(isset($endereco['cep'])?$endereco['cep']:null);
		}
	}

	/**
	 * Identificador do endereço
	 */
	public function getID() {
		return $this->id;
	}

	public function setID($id) {
		$this->id = $id;
	}

	/**
	 * Cidade a qual o endereço pertence
	 */
	public function getCidadeID() {
		return $this->cidade_id;
	}

	public function setCidadeID($cidade_id) {
		$this->cidade_id = $cidade_id;
	}

	/**
	 * Bairro a qual o endereço está localizado
	 */
	public function getBairroID() {
		return $this->bairro_id;
	}

	public function setBairroID($bairro_id) {
		$this->bairro_id = $bairro_id;
	}

	/**
	 * Nome da rua ou avenida
	 */
	public function getLogradouro() {
		return $this->logradouro;
	}

	public function setLogradouro($logradouro) {
		$this->logradouro = $logradouro;
	}

	/**
	 * Código dos correios para identificar a rua ou avenida
	 */
	public function getCEP() {
		return $this->cep;
	}

	public function setCEP($cep) {
		$this->cep = $cep;
	}

	public function toArray() {
		$endereco = array();
		$endereco['id'] = $this->getID();
		$endereco['cidadeid'] = $this->getCidadeID();
		$endereco['bairroid'] = $this->getBairroID();
		$endereco['logradouro'] = $this->getLogradouro();
		$endereco['cep'] = $this->getCEP();
		return $endereco;
	}

	public static function getPeloID($id) {
		$query = DB::$pdo->from('Enderecos')
		                 ->where(array('id' => $id));
		return new ZEndereco($query->fetch());
	}

	public static function getPeloCEP($cep) {
		$query = DB::$pdo->from('Enderecos')
		                 ->where(array('cep' => $cep));
		return new ZEndereco($query->fetch());
	}

	public static function getPeloBairroIDLogradouro($bairro_id, $logradouro) {
		$query = DB::$pdo->from('Enderecos')
		                 ->where(array('bairroid' => $bairro_id, 'logradouro' => $logradouro));
		return new ZEndereco($query->fetch());
	}

	private static function validarCampos(&$endereco) {
		$erros = array();
		if(!is_numeric($endereco['cidadeid']))
			$erros['cidadeid'] = 'A cidade não foi informada';
		if(!is_numeric($endereco['bairroid']))
			$erros['bairroid'] = 'O bairro não foi informado';
		$endereco['logradouro'] = strip_tags(trim($endereco['logradouro']));
		if(strlen($endereco['logradouro']) == 0)
			$erros['logradouro'] = 'O logradouro não pode ser vazio';
		$endereco['cep'] = \MZ\Util\Filter::unmask($endereco['cep'], _p('Mascara', 'CEP'));
		if(!check_cep($endereco['cep']))
			$erros['cep'] = vsprintf('%s inválido', array(_p('Titulo', 'CEP')));
		if(!empty($erros))
			throw new ValidationException($erros);
	}

	private static function handleException(&$e) {
		if(stripos($e->getMessage(), 'PRIMARY') !== false)
			throw new ValidationException(array('id' => 'O ID informado já está cadastrado'));
		if(stripos($e->getMessage(), 'CEP_UNIQUE') !== false)
			throw new ValidationException(array('cep' => vsprintf('O %s informado já está cadastrado', array(_p('Titulo', 'CEP')))));
		if(stripos($e->getMessage(), 'BairroID_Logradouro_UNIQUE') !== false)
			throw new ValidationException(array('logradouro' => 'O logradouro informado já está cadastrado'));
	}

	public static function cadastrar($endereco) {
		$_endereco = $endereco->toArray();
		self::validarCampos($_endereco);
		try {
			$_endereco['id'] = DB::$pdo->insertInto('Enderecos')->values($_endereco)->execute();
		} catch (Exception $e) {
			self::handleException($e);
			throw $e;
		}
		return self::getPeloID($_endereco['id']);
	}

	public static function atualizar($endereco) {
		$_endereco = $endereco->toArray();
		if(!$_endereco['id'])
			throw new ValidationException(array('id' => 'O id do endereco não foi informado'));
		self::validarCampos($_endereco);
		$campos = array(
			'cidadeid',
			'bairroid',
			'logradouro',
			'cep',
		);
		try {
			$query = DB::$pdo->update('Enderecos');
			$query = $query->set(array_intersect_key($_endereco, array_flip($campos)));
			$query = $query->where('id', $_endereco['id']);
			$query->execute();
		} catch (Exception $e) {
			self::handleException($e);
			throw $e;
		}
		return self::getPeloID($_endereco['id']);
	}

	public static function excluir($id) {
		if(!$id)
			throw new Exception('Não foi possível excluir o endereco, o id do endereco não foi informado');
		$query = DB::$pdo->deleteFrom('Enderecos')
		                 ->where(array('id' => $id));
		return $query->execute();
	}

	private static function initSearch() {
		return   DB::$pdo->from('Enderecos')
		                 ->orderBy('id ASC');
	}

	public static function getTodos($inicio = null, $quantidade = null) {
		$query = self::initSearch();
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_enderecos = $query->fetchAll();
		$enderecos = array();
		foreach($_enderecos as $endereco)
			$enderecos[] = new ZEndereco($endereco);
		return $enderecos;
	}

	public static function getCount() {
		$query = self::initSearch();
		return $query->count();
	}

	private static function initSearchDaCidadeID($cidade_id) {
		return   DB::$pdo->from('Enderecos')
		                 ->where(array('cidadeid' => $cidade_id))
		                 ->orderBy('id ASC');
	}

	public static function getTodosDaCidadeID($cidade_id, $inicio = null, $quantidade = null) {
		$query = self::initSearchDaCidadeID($cidade_id);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_enderecos = $query->fetchAll();
		$enderecos = array();
		foreach($_enderecos as $endereco)
			$enderecos[] = new ZEndereco($endereco);
		return $enderecos;
	}

	public static function getCountDaCidadeID($cidade_id) {
		$query = self::initSearchDaCidadeID($cidade_id);
		return $query->count();
	}

	private static function initSearchDoBairroID($bairro_id) {
		return   DB::$pdo->from('Enderecos')
		                 ->where(array('bairroid' => $bairro_id))
		                 ->orderBy('id ASC');
	}

	public static function getTodosDoBairroID($bairro_id, $inicio = null, $quantidade = null) {
		$query = self::initSearchDoBairroID($bairro_id);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_enderecos = $query->fetchAll();
		$enderecos = array();
		foreach($_enderecos as $endereco)
			$enderecos[] = new ZEndereco($endereco);
		return $enderecos;
	}

	public static function getCountDoBairroID($bairro_id) {
		$query = self::initSearchDoBairroID($bairro_id);
		return $query->count();
	}

}
