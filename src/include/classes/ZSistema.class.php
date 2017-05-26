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
 * Classe que informa detalhes da empresa, parceiro e opções do sistema como a versão do banco de dados e a licença de uso
 */
class ZSistema {
	private $id;
	private $pais_id;
	private $empresa_id;
	private $parceiro_id;
	private $access_key;
	private $registry_key;
	private $license_key;
	private $computadores;
	private $guid;
	private $opcoes;
	private $ultimo_backup;
	private $versao_db;

	const VERSAO = '1805';

	public function __construct($sistema = array()) {
		if(is_array($sistema)) {
			$this->setID(isset($sistema['id'])?$sistema['id']:null);
			$this->setPaisID(isset($sistema['paisid'])?$sistema['paisid']:null);
			$this->setEmpresaID(isset($sistema['empresaid'])?$sistema['empresaid']:null);
			$this->setParceiroID(isset($sistema['parceiroid'])?$sistema['parceiroid']:null);
			$this->setAccessKey(isset($sistema['accesskey'])?$sistema['accesskey']:null);
			$this->setRegistryKey(isset($sistema['registrykey'])?$sistema['registrykey']:null);
			$this->setLicenseKey(isset($sistema['licensekey'])?$sistema['licensekey']:null);
			$this->setComputadores(isset($sistema['computadores'])?$sistema['computadores']:null);
			$this->setGUID(isset($sistema['guid'])?$sistema['guid']:null);
			$this->setOpcoes(isset($sistema['opcoes'])?$sistema['opcoes']:null);
			$this->setUltimoBackup(isset($sistema['ultimobackup'])?$sistema['ultimobackup']:null);
			$this->setVersaoDB(isset($sistema['versaodb'])?$sistema['versaodb']:null);
		}
	}

	/**
	 * Identificador único do sistema, valor 1
	 */
	public function getID() {
		return $this->id;
	}

	/**
	 * Identificador único do sistema, valor 1
	 */
	public function setID($id) {
		$this->id = $id;
	}

	/**
	 * País em que o sistema está sendo utilizado
	 */
	public function getPaisID() {
		return $this->pais_id;
	}

	/**
	 * País em que o sistema está sendo utilizado
	 */
	public function setPaisID($pais_id) {
		$this->pais_id = $pais_id;
	}

	/**
	 * Informa qual a empresa que gerencia o sistema, a empresa deve ser um cliente do tipo pessoa jurídica
	 */
	public function getEmpresaID() {
		return $this->empresa_id;
	}

	/**
	 * Informa qual a empresa que gerencia o sistema, a empresa deve ser um cliente do tipo pessoa jurídica
	 */
	public function setEmpresaID($empresa_id) {
		$this->empresa_id = $empresa_id;
	}

	/**
	 * Informa quem realiza o suporte do sistema, deve ser um cliente do tipo empresa que possua um acionista como representante
	 */
	public function getParceiroID() {
		return $this->parceiro_id;
	}

	/**
	 * Informa quem realiza o suporte do sistema, deve ser um cliente do tipo empresa que possua um acionista como representante
	 */
	public function setParceiroID($parceiro_id) {
		$this->parceiro_id = $parceiro_id;
	}

	/**
	 * Chave de acesso ao sistema, a chave é atualizada sempre ao utilizar o programa
	 */
	public function getAccessKey() {
		return $this->access_key;
	}

	/**
	 * Chave de acesso ao sistema, a chave é atualizada sempre ao utilizar o programa
	 */
	public function setAccessKey($access_key) {
		$this->access_key = $access_key;
	}

	/**
	 * Chave de registro, permite licenças do tipo aluguel
	 */
	public function getRegistryKey() {
		return $this->registry_key;
	}

	/**
	 * Chave de registro, permite licenças do tipo aluguel
	 */
	public function setRegistryKey($registry_key) {
		$this->registry_key = $registry_key;
	}

	/**
	 * Chave da Licença, permite licença do tipo vitalícia
	 */
	public function getLicenseKey() {
		return $this->license_key;
	}

	/**
	 * Chave da Licença, permite licença do tipo vitalícia
	 */
	public function setLicenseKey($license_key) {
		$this->license_key = $license_key;
	}

	/**
	 * Quantidade de computadores permitido para uso em rede
	 */
	public function getComputadores() {
		return $this->computadores;
	}

	/**
	 * Quantidade de computadores permitido para uso em rede
	 */
	public function setComputadores($computadores) {
		$this->computadores = $computadores;
	}

	/**
	 * Código único da empresa, permite baixar novas licenças automaticamente
	 */
	public function getGUID() {
		return $this->guid;
	}

	/**
	 * Código único da empresa, permite baixar novas licenças automaticamente
	 */
	public function setGUID($guid) {
		$this->guid = $guid;
	}

	/**
	 * Opções gerais do sistema como opções de impressão
	 */
	public function getOpcoes() {
		return $this->opcoes;
	}

	/**
	 * Opções gerais do sistema como opções de impressão
	 */
	public function setOpcoes($opcoes) {
		$this->opcoes = $opcoes;
	}

	/**
	 * Informa qual foi a data da última realização de backup do banco de dados do sistema
	 */
	public function getUltimoBackup() {
		return $this->ultimo_backup;
	}

	/**
	 * Informa qual foi a data da última realização de backup do banco de dados do sistema
	 */
	public function setUltimoBackup($ultimo_backup) {
		$this->ultimo_backup = $ultimo_backup;
	}

	/**
	 * Informa qual a versão do banco de dados
	 */
	public function getVersaoDB() {
		return $this->versao_db;
	}

	/**
	 * Informa qual a versão do banco de dados
	 */
	public function setVersaoDB($versao_db) {
		$this->versao_db = $versao_db;
	}

	/**
	 * Quantidade de tablets permitido para uso em rede
	 */
	public function getTablets() {
		return $this->computadores * 2;
	}

	public function toArray() {
		$sistema = array();
		$sistema['id'] = $this->getID();
		$sistema['paisid'] = $this->getPaisID();
		$sistema['empresaid'] = $this->getEmpresaID();
		$sistema['parceiroid'] = $this->getParceiroID();
		$sistema['accesskey'] = $this->getAccessKey();
		$sistema['registrykey'] = $this->getRegistryKey();
		$sistema['licensekey'] = $this->getLicenseKey();
		$sistema['computadores'] = $this->getComputadores();
		$sistema['guid'] = $this->getGUID();
		$sistema['opcoes'] = $this->getOpcoes();
		$sistema['ultimobackup'] = $this->getUltimoBackup();
		$sistema['versaodb'] = $this->getVersaoDB();
		return $sistema;
	}

	public static function getPeloID($id) {
		$query = DB::$pdo->from('Sistema')
		                 ->where(array('id' => $id));
		return new ZSistema($query->fetch());
	}

	private static function validarCampos(&$sistema) {
		$erros = array();
		$sistema['id'] = strval($sistema['id']);
		if(!in_array($sistema['id'], array('1')))
			$erros['id'] = 'O ID informado não é válido';
		$sistema['paisid'] = trim($sistema['paisid']);
		if(strlen($sistema['paisid']) == 0)
			$sistema['paisid'] = null;
		else if(!is_numeric($sistema['paisid']))
			$erros['paisid'] = 'O país não foi informado';
		$sistema['empresaid'] = trim($sistema['empresaid']);
		if(strlen($sistema['empresaid']) == 0)
			$sistema['empresaid'] = null;
		else if(!is_numeric($sistema['empresaid']))
			$erros['empresaid'] = 'A empresa não foi informada';
		$sistema['parceiroid'] = trim($sistema['parceiroid']);
		if(strlen($sistema['parceiroid']) == 0)
			$sistema['parceiroid'] = null;
		else if(!is_numeric($sistema['parceiroid']))
			$erros['parceiroid'] = 'O parceiro não foi informado';
		$sistema['accesskey'] = strip_tags(trim($sistema['accesskey']));
		if(strlen($sistema['accesskey']) == 0)
			$sistema['accesskey'] = null;
		$sistema['registrykey'] = strval($sistema['registrykey']);
		if(strlen($sistema['registrykey']) == 0)
			$sistema['registrykey'] = null;
		$sistema['licensekey'] = strval($sistema['licensekey']);
		if(strlen($sistema['licensekey']) == 0)
			$sistema['licensekey'] = null;
		$sistema['computadores'] = trim($sistema['computadores']);
		if(strlen($sistema['computadores']) == 0)
			$sistema['computadores'] = null;
		else if(!is_numeric($sistema['computadores']))
			$erros['computadores'] = 'A quantidade de computadores não foi informada';
		$sistema['guid'] = strip_tags(trim($sistema['guid']));
		if(strlen($sistema['guid']) == 0)
			$sistema['guid'] = null;
		$sistema['opcoes'] = strval($sistema['opcoes']);
		if(strlen($sistema['opcoes']) == 0)
			$sistema['opcoes'] = null;
		$sistema['ultimobackup'] = date('Y-m-d H:i:s');
		$sistema['versaodb'] = strip_tags(trim($sistema['versaodb']));
		if(strlen($sistema['versaodb']) == 0)
			$erros['versaodb'] = 'A versão do banco de dados não pode ser vazia';
		if(!empty($erros))
			throw new ValidationException($erros);
	}

	private static function handleException(&$e) {
		if(stripos($e->getMessage(), 'PRIMARY') !== false)
			throw new ValidationException(array('id' => 'O ID informado já está cadastrado'));
	}

	public static function atualizar($sistema, $campos = array()) {
		$_sistema = $sistema->toArray();
		if(!$_sistema['id'])
			throw new ValidationException(array('id' => 'O id do sistema não foi informado'));
		self::validarCampos($_sistema);
		$_campos = $campos;
		if(count($_campos) == 0) {
			$_campos = array(
				// 'paisid',
				// 'empresaid',
				// 'parceiroid',
				// 'accesskey',
				// 'registrykey',
				// 'licensekey',
				// 'computadores',
				// 'guid',
				'opcoes',
				// 'ultimobackup',
				// 'versaodb',
			);
		}
		try {
			$query = DB::$pdo->update('Sistema');
			$query = $query->set(array_intersect_key($_sistema, array_flip($_campos)));
			$query = $query->where('id', $_sistema['id']);
			$query->execute();
		} catch (Exception $e) {
			self::handleException($e);
			throw $e;
		}
		return self::getPeloID($_sistema['id']);
	}

	private static function initSearchDaEmpresaID($empresa_id) {
		return   DB::$pdo->from('Sistema')
		                 ->where(array('empresaid' => $empresa_id))
		                 ->orderBy('id ASC');
	}

	public static function getTodosDaEmpresaID($empresa_id, $inicio = null, $quantidade = null) {
		$query = self::initSearchDaEmpresaID($empresa_id);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_sistemas = $query->fetchAll();
		$sistemas = array();
		foreach($_sistemas as $sistema)
			$sistemas[] = new ZSistema($sistema);
		return $sistemas;
	}

	public static function getCountDaEmpresaID($empresa_id) {
		$query = self::initSearchDaEmpresaID($empresa_id);
		return $query->count();
	}

	private static function initSearchDoParceiroID($parceiro_id) {
		return   DB::$pdo->from('Sistema')
		                 ->where(array('parceiroid' => $parceiro_id))
		                 ->orderBy('id ASC');
	}

	public static function getTodosDoParceiroID($parceiro_id, $inicio = null, $quantidade = null) {
		$query = self::initSearchDoParceiroID($parceiro_id);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_sistemas = $query->fetchAll();
		$sistemas = array();
		foreach($_sistemas as $sistema)
			$sistemas[] = new ZSistema($sistema);
		return $sistemas;
	}

	public static function getCountDoParceiroID($parceiro_id) {
		$query = self::initSearchDoParceiroID($parceiro_id);
		return $query->count();
	}

	private static function initSearchDaPaisID($pais_id) {
		return   DB::$pdo->from('Sistema')
		                 ->where(array('paisid' => $pais_id))
		                 ->orderBy('id ASC');
	}

	public static function getTodosDaPaisID($pais_id, $inicio = null, $quantidade = null) {
		$query = self::initSearchDaPaisID($pais_id);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_sistemas = $query->fetchAll();
		$sistemas = array();
		foreach($_sistemas as $sistema)
			$sistemas[] = new ZSistema($sistema);
		return $sistemas;
	}

	public static function getCountDaPaisID($pais_id) {
		$query = self::initSearchDaPaisID($pais_id);
		return $query->count();
	}

	public function salvarOpcoes($options) {
		$opcoes = to_ini($options);
		$opcoes = base64_encode($opcoes);
		$this->setOpcoes($opcoes);
		return ZSistema::atualizar($this);
	}

	static public function getINI() {
		global $INI;

		/* load from php files */
		configure_load();
		self::WebRoot();
		return self::BuildINI($INI);
	}

	static private function WebRoot() {
		if (defined('WEB_ROOT')) 
			return WEB_ROOT;
		/* validator */
		$script_name = $_SERVER['SCRIPT_NAME'];
		if ( preg_match('#^(.*)/app.php$#', $script_name, $m) ) {
			$webroot = $m[1];
			define('WEB_ROOT', $webroot);
		} else {
			$document_root = $_SERVER['DOCUMENT_ROOT'];
			$docroot = rtrim(str_replace('\\','/',$document_root),'/');
			if(!$docroot) {
				$script_filename = $_SERVER['SCRIPT_FILENAME'];
				$script_filename = str_replace('\\','/',$script_filename);
				$script_name = $_SERVER['SCRIPT_NAME'];
				$script_name = str_replace('\\','/',$script_name);
				$lengthf = strlen($script_filename);
				$lengthn = strlen($script_name);
				$length = $lengthf - $lengthn;
				$docroot = rtrim(substr($script_filename,0,$length),'/');
			}
			$webroot = trim(substr(WWW_ROOT, strlen($docroot)), '\\/');
			define('WEB_ROOT', $webroot ? "/{$webroot}" : '');
		}
		return $webroot;
	}

	static private function BuildINI($ini) {
		$host = $_SERVER['HTTP_HOST'];
		$ini['system']['wwwprefix'] = "http://{$host}" . WEB_ROOT;
		return $ini;
	}

}
