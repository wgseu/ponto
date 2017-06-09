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
class LocalizacaoTipo {
	const CASA = 'Casa';
	const APARTAMENTO = 'Apartamento';
}

/**
 * Endereço detalhado de um cliente
 */
class ZLocalizacao {
	private $id;
	private $cliente_id;
	private $bairro_id;
	private $cep;
	private $logradouro;
	private $numero;
	private $tipo;
	private $complemento;
	private $condominio;
	private $bloco;
	private $apartamento;
	private $referencia;
	private $latitude;
	private $longitude;
	private $apelido;
	private $mostrar;

	public function __construct($localizacao = array()) {
		if(is_array($localizacao)) {
			$this->setID(isset($localizacao['id'])?$localizacao['id']:null);
			$this->setClienteID(isset($localizacao['clienteid'])?$localizacao['clienteid']:null);
			$this->setBairroID(isset($localizacao['bairroid'])?$localizacao['bairroid']:null);
			$this->setCEP(isset($localizacao['cep'])?$localizacao['cep']:null);
			$this->setLogradouro(isset($localizacao['logradouro'])?$localizacao['logradouro']:null);
			$this->setNumero(isset($localizacao['numero'])?$localizacao['numero']:null);
			$this->setTipo(isset($localizacao['tipo'])?$localizacao['tipo']:null);
			$this->setComplemento(isset($localizacao['complemento'])?$localizacao['complemento']:null);
			$this->setCondominio(isset($localizacao['condominio'])?$localizacao['condominio']:null);
			$this->setBloco(isset($localizacao['bloco'])?$localizacao['bloco']:null);
			$this->setApartamento(isset($localizacao['apartamento'])?$localizacao['apartamento']:null);
			$this->setReferencia(isset($localizacao['referencia'])?$localizacao['referencia']:null);
			$this->setLatitude(isset($localizacao['latitude'])?$localizacao['latitude']:null);
			$this->setLongitude(isset($localizacao['longitude'])?$localizacao['longitude']:null);
			$this->setApelido(isset($localizacao['apelido'])?$localizacao['apelido']:null);
			$this->setMostrar(isset($localizacao['mostrar'])?$localizacao['mostrar']:null);
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
	 * Cliente a qual esse endereço pertence
	 */
	public function getClienteID() {
		return $this->cliente_id;
	}

	public function setClienteID($cliente_id) {
		$this->cliente_id = $cliente_id;
	}

	/**
	 * Bairro do endereço
	 */
	public function getBairroID() {
		return $this->bairro_id;
	}

	public function setBairroID($bairro_id) {
		$this->bairro_id = $bairro_id;
	}

	/**
	 * Código dos correios para identificar um logradouro
	 */
	public function getCEP() {
		return $this->cep;
	}

	public function setCEP($cep) {
		$this->cep = $cep;
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
	 * Número da casa ou do condomínio
	 */
	public function getNumero() {
		return $this->numero;
	}

	public function setNumero($numero) {
		$this->numero = $numero;
	}

	/**
	 * Tipo de endereço Casa ou Apartamento
	 */
	public function getTipo() {
		return $this->tipo;
	}

	public function setTipo($tipo) {
		$this->tipo = $tipo;
	}

	/**
	 * Complemento do endereço, Ex.: Loteamento Sul
	 */
	public function getComplemento() {
		return $this->complemento;
	}

	public function setComplemento($complemento) {
		$this->complemento = $complemento;
	}

	/**
	 * Nome do condomínio
	 */
	public function getCondominio() {
		return $this->condominio;
	}

	public function setCondominio($condominio) {
		$this->condominio = $condominio;
	}

	/**
	 * Número do bloco quando for apartamento
	 */
	public function getBloco() {
		return $this->bloco;
	}

	public function setBloco($bloco) {
		$this->bloco = $bloco;
	}

	/**
	 * Número do apartamento
	 */
	public function getApartamento() {
		return $this->apartamento;
	}

	public function setApartamento($apartamento) {
		$this->apartamento = $apartamento;
	}

	/**
	 * Ponto de referência para chegar ao local
	 */
	public function getReferencia() {
		return $this->referencia;
	}

	public function setReferencia($referencia) {
		$this->referencia = $referencia;
	}

	/**
	 * Ponto latitudinal para localização em um mapa
	 */
	public function getLatitude() {
		return $this->latitude;
	}

	public function setLatitude($latitude) {
		$this->latitude = $latitude;
	}

	/**
	 * Ponto longitudinal para localização em um mapa
	 */
	public function getLongitude() {
		return $this->longitude;
	}

	public function setLongitude($longitude) {
		$this->longitude = $longitude;
	}

	/**
	 * Ex.: Minha Casa, Casa da Amiga
	 */
	public function getApelido() {
		return $this->apelido;
	}

	public function setApelido($apelido) {
		$this->apelido = $apelido;
	}

	/**
	 * Permite esconder ou exibir um endereço do cliente
	 */
	public function getMostrar() {
		return $this->mostrar;
	}

	/**
	 * Permite esconder ou exibir um endereço do cliente
	 */
	public function isMostrar() {
		return $this->mostrar == 'Y';
	}

	public function setMostrar($mostrar) {
		$this->mostrar = $mostrar;
	}

	public function toArray() {
		$localizacao = array();
		$localizacao['id'] = $this->getID();
		$localizacao['clienteid'] = $this->getClienteID();
		$localizacao['bairroid'] = $this->getBairroID();
		$localizacao['cep'] = $this->getCEP();
		$localizacao['logradouro'] = $this->getLogradouro();
		$localizacao['numero'] = $this->getNumero();
		$localizacao['tipo'] = $this->getTipo();
		$localizacao['complemento'] = $this->getComplemento();
		$localizacao['condominio'] = $this->getCondominio();
		$localizacao['bloco'] = $this->getBloco();
		$localizacao['apartamento'] = $this->getApartamento();
		$localizacao['referencia'] = $this->getReferencia();
		$localizacao['latitude'] = $this->getLatitude();
		$localizacao['longitude'] = $this->getLongitude();
		$localizacao['apelido'] = $this->getApelido();
		$localizacao['mostrar'] = $this->getMostrar();
		return $localizacao;
	}

	public static function getPeloID($id) {
		$query = DB::$pdo->from('Localizacoes')
						 ->where(array('id' => $id));
		return new ZLocalizacao($query->fetch());
	}

	public static function getPeloClienteIDApelido($cliente_id, $apelido) {
		$query = DB::$pdo->from('Localizacoes')
						 ->where(array('clienteid' => $cliente_id, 'apelido' => $apelido));
		return new ZLocalizacao($query->fetch());
	}

	public static function getPeloClienteID($cliente_id) {
		$query = DB::$pdo->from('Localizacoes')
						 ->where('clienteid', $cliente_id)
						 ->orderBy('mostrar ASC')
						 ->limit(1);
		return new ZLocalizacao($query->fetch());
	}

	private static function validarCampos(&$localizacao) {
		$erros = array();
		if(!is_numeric($localizacao['clienteid']))
			$erros['clienteid'] = 'O cliente não foi informado';
		if(!is_numeric($localizacao['bairroid']))
			$erros['bairroid'] = 'O bairro não foi informado';
		$localizacao['cep'] = unmask($localizacao['cep'], '99999-999');
		if(strlen($localizacao['cep']) == 0)
			$localizacao['cep'] = null;
		else if(!check_cep($localizacao['cep']))
			$erros['cep'] = 'CEP inválido';
		$localizacao['logradouro'] = strip_tags(trim($localizacao['logradouro']));
		if(strlen($localizacao['logradouro']) == 0)
			$erros['logradouro'] = 'O logradouro não pode ser vazio';
		$localizacao['numero'] = strip_tags(trim($localizacao['numero']));
		if(strlen($localizacao['numero']) == 0)
			$erros['numero'] = 'O número não pode ser vazio';
		$localizacao['tipo'] = trim($localizacao['tipo']);
		if(strlen($localizacao['tipo']) == 0)
			$localizacao['tipo'] = null;
		else if(!in_array($localizacao['tipo'], array('Casa', 'Apartamento')))
			$erros['tipo'] = 'O tipo informado não é válido';
		$localizacao['complemento'] = strip_tags(trim($localizacao['complemento']));
		if(strlen($localizacao['complemento']) == 0)
			$localizacao['complemento'] = null;
		$localizacao['condominio'] = strip_tags(trim($localizacao['condominio']));
		if(strlen($localizacao['condominio']) == 0)
			$localizacao['condominio'] = null;
		$localizacao['bloco'] = strip_tags(trim($localizacao['bloco']));
		if(strlen($localizacao['bloco']) == 0)
			$localizacao['bloco'] = null;
		$localizacao['apartamento'] = strip_tags(trim($localizacao['apartamento']));
		if(strlen($localizacao['apartamento']) == 0)
			$localizacao['apartamento'] = null;
		$localizacao['referencia'] = strip_tags(trim($localizacao['referencia']));
		if(strlen($localizacao['referencia']) == 0)
			$localizacao['referencia'] = null;
		$localizacao['latitude'] = trim($localizacao['latitude']);
		if(strlen($localizacao['latitude']) == 0)
			$localizacao['latitude'] = null;
		else if(!is_numeric($localizacao['latitude']))
			$erros['latitude'] = 'A latitude não foi informada';
		$localizacao['longitude'] = trim($localizacao['longitude']);
		if(strlen($localizacao['longitude']) == 0)
			$localizacao['longitude'] = null;
		else if(!is_numeric($localizacao['longitude']))
			$erros['longitude'] = 'A longitude não foi informada';
		$localizacao['apelido'] = strip_tags(trim($localizacao['apelido']));
		if(strlen($localizacao['apelido']) == 0)
			$localizacao['apelido'] = null;
		$localizacao['mostrar'] = trim($localizacao['mostrar']);
		if(strlen($localizacao['mostrar']) == 0)
			$localizacao['mostrar'] = 'N';
		else if(!in_array($localizacao['mostrar'], array('Y', 'N')))
			$erros['mostrar'] = 'O mostrar informado não é válido';
		if(!empty($erros))
			throw new ValidationException($erros);
	}

	private static function handleException(&$e) {
		if(stripos($e->getMessage(), 'PRIMARY') !== false)
			throw new ValidationException(array('id' => 'O ID informado já está cadastrado'));
		if(stripos($e->getMessage(), 'UK_Localizacoes_ClienteID_Apelido') !== false)
			throw new ValidationException(array('apelido' => 'O apelido informado já está cadastrado'));
	}

	public static function cadastrar($localizacao) {
		$_localizacao = $localizacao->toArray();
		self::validarCampos($_localizacao);
		try {
			$_localizacao['id'] = DB::$pdo->insertInto('Localizacoes')->values($_localizacao)->execute();
		} catch (Exception $e) {
			self::handleException($e);
			throw $e;
		}
		return self::getPeloID($_localizacao['id']);
	}

	public static function atualizar($localizacao) {
		$_localizacao = $localizacao->toArray();
		if(!$_localizacao['id'])
			throw new ValidationException(array('id' => 'O id da localizacao não foi informado'));
		self::validarCampos($_localizacao);
		$campos = array(
			'clienteid',
			'bairroid',
			'cep',
			'logradouro',
			'numero',
			'tipo',
			'complemento',
			'condominio',
			'bloco',
			'apartamento',
			'referencia',
			'latitude',
			'longitude',
			'apelido',
			'mostrar',
		);
		try {
			$query = DB::$pdo->update('Localizacoes');
			$query = $query->set(array_intersect_key($_localizacao, array_flip($campos)));
			$query = $query->where('id', $_localizacao['id']);
			$query->execute();
		} catch (Exception $e) {
			self::handleException($e);
			throw $e;
		}
		return self::getPeloID($_localizacao['id']);
	}

	public static function excluir($id) {
		if(!$id)
			throw new Exception('Não foi possível excluir a localizacao, o id da localizacao não foi informado');
		$query = DB::$pdo->deleteFrom('Localizacoes')
						 ->where(array('id' => $id));
		return $query->execute();
	}

    /**
     * Gets textual and translated Tipo for Localizacao
     * @param  int $index choose option from index
     * @return mixed A associative key -> translated representative text or text for index
     */
    public static function getTipoOptions($index = null)
    {
        $options = array(
            LocalizacaoTipo::CASA => 'Casa',
            LocalizacaoTipo::APARTAMENTO => 'Apartamento',
        );
        if (!is_null($index)) {
            return $options[$index];
        }
        return $options;
    }

	private static function initSearch() {
		return   DB::$pdo->from('Localizacoes')
						 ->orderBy('id ASC');
	}

	public static function getTodas($inicio = null, $quantidade = null) {
		$query = self::initSearch();
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_localizacaos = $query->fetchAll();
		$localizacaos = array();
		foreach($_localizacaos as $localizacao)
			$localizacaos[] = new ZLocalizacao($localizacao);
		return $localizacaos;
	}

	public static function getCount() {
		$query = self::initSearch();
		return $query->count();
	}

	private static function initSearchDoClienteID($cliente_id) {
		return   DB::$pdo->from('Localizacoes')
						 ->where(array('clienteid' => $cliente_id))
						 ->orderBy('id ASC');
	}

	public static function getTodasDoClienteID($cliente_id, $inicio = null, $quantidade = null) {
		$query = self::initSearchDoClienteID($cliente_id);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_localizacaos = $query->fetchAll();
		$localizacaos = array();
		foreach($_localizacaos as $localizacao)
			$localizacaos[] = new ZLocalizacao($localizacao);
		return $localizacaos;
	}

	public static function getCountDoClienteID($cliente_id) {
		$query = self::initSearchDoClienteID($cliente_id);
		return $query->count();
	}

	private static function initSearchDoBairroID($bairro_id) {
		return   DB::$pdo->from('Localizacoes')
						 ->where(array('bairroid' => $bairro_id))
						 ->orderBy('id ASC');
	}

	public static function getTodasDoBairroID($bairro_id, $inicio = null, $quantidade = null) {
		$query = self::initSearchDoBairroID($bairro_id);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_localizacaos = $query->fetchAll();
		$localizacaos = array();
		foreach($_localizacaos as $localizacao)
			$localizacaos[] = new ZLocalizacao($localizacao);
		return $localizacaos;
	}

	public static function getCountDoBairroID($bairro_id) {
		$query = self::initSearchDoBairroID($bairro_id);
		return $query->count();
	}

	private static function initSearchDaCidadeID($cidade_id, $tipo, $busca) {
		$query = DB::$pdo->from('Localizacoes l')
						 ->leftJoin('Bairros b ON b.id = l.bairroid')
						 ->where('b.cidadeid', $cidade_id);
		$busca = trim($busca);
		if($tipo == 'condominio') {
			$query = $query->where('l.tipo', LocalizacaoTipo::APARTAMENTO)
						   ->orderBy('l.condominio ASC')
						   ->groupBy('l.condominio');
			if($busca != '') {
				$query = $query->where('l.condominio LIKE ?', '%'.$busca.'%');
			}
		} else {
			$query = $query->orderBy('l.logradouro ASC')
						   ->groupBy('l.logradouro');			   
			if($busca != '') {
				$query = $query->where('l.logradouro LIKE ?', '%'.$busca.'%');
			}
		}
		return $query;
	}

	public static function getTodasDaCidadeID($cidade_id, $tipo, $busca = null, $inicio = null, $quantidade = null) {
		$query = self::initSearchDaCidadeID($cidade_id, $tipo, $busca);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_localizacaos = $query->fetchAll();
		$localizacaos = array();
		foreach($_localizacaos as $localizacao)
			$localizacaos[] = new ZLocalizacao($localizacao);
		return $localizacaos;
	}

	public static function getCountDoCidadeID($cidade_id, $tipo, $busca = null) {
		$query = self::initSearchDaCidadeID($cidade_id, $tipo, $busca);
		if($tipo == 'condominio')
			$query = $query->select(null)->groupBy(null)->select('COUNT(DISTINCT l.condominio)');
		else
			$query = $query->select(null)->groupBy(null)->select('COUNT(DISTINCT l.logradouro)');
		return (int) $query->fetchColumn();
	}

}
