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
class ClienteTipo {
	const FISICA = 'Fisica';
	const JURIDICA = 'Juridica';
}
class ClienteGenero {
	const MASCULINO = 'Masculino';
	const FEMININO = 'Feminino';
}

/**
 * Informações de cliente físico ou jurídico. Clientes, empresas, funcionários, fornecedores e parceiros são cadastrados aqui
 */
class ZCliente {
	const CHAVE_SECRETA = '%#@87GhÃ¨¬';

	private $id;
	private $tipo;
	private $acionista_id;
	private $login;
	private $senha;
	private $nome;
	private $sobrenome;
	private $genero;
	private $cpf;
	private $rg;
	private $im;
	private $email;
	private $data_aniversario;
	private $fone = array();
	private $slogan;
	private $secreto;
	private $limite_compra;
	private $facebook_url;
	private $twitter_url;
	private $linked_in_url;
	private $imagem;
	private $data_atualizacao;
	private $data_cadastro;

	public function __construct($cliente = array()) {
		if(is_array($cliente)) {
			$this->setID(isset($cliente['id'])?$cliente['id']:null);
			$this->setTipo(isset($cliente['tipo'])?$cliente['tipo']:null);
			$this->setAcionistaID(isset($cliente['acionistaid'])?$cliente['acionistaid']:null);
			$this->setLogin(isset($cliente['login'])?$cliente['login']:null);
			$this->setSenha(isset($cliente['senha'])?$cliente['senha']:null);
			$this->setNome(isset($cliente['nome'])?$cliente['nome']:null);
			$this->setSobrenome(isset($cliente['sobrenome'])?$cliente['sobrenome']:null);
			$this->setGenero(isset($cliente['genero'])?$cliente['genero']:null);
			$this->setCPF(isset($cliente['cpf'])?$cliente['cpf']:null);
			$this->setRG(isset($cliente['rg'])?$cliente['rg']:null);
			$this->setIM(isset($cliente['im'])?$cliente['im']:null);
			$this->setEmail(isset($cliente['email'])?$cliente['email']:null);
			$this->setDataAniversario(isset($cliente['dataaniversario'])?$cliente['dataaniversario']:null);
			for($i = 1; $i <= 2; $i++)
				$this->setFone($i, isset($cliente['fone'.$i])?$cliente['fone'.$i]:null);
			$this->setSlogan(isset($cliente['slogan'])?$cliente['slogan']:null);
			$this->setSecreto(isset($cliente['secreto'])?$cliente['secreto']:null);
			$this->setLimiteCompra(isset($cliente['limitecompra'])?$cliente['limitecompra']:null);
			$this->setFacebookURL(isset($cliente['facebookurl'])?$cliente['facebookurl']:null);
			$this->setTwitterURL(isset($cliente['twitterurl'])?$cliente['twitterurl']:null);
			$this->setLinkedInURL(isset($cliente['linkedinurl'])?$cliente['linkedinurl']:null);
			$this->setImagem(isset($cliente['imagem'])?$cliente['imagem']:null);
			$this->setDataAtualizacao(isset($cliente['dataatualizacao'])?$cliente['dataatualizacao']:null);
			$this->setDataCadastro(isset($cliente['datacadastro'])?$cliente['datacadastro']:null);
		}
	}

	/**
	 * Identificador do cliente
	 */
	public function getID() {
		return $this->id;
	}

	/**
	 * Identificador do cliente
	 */
	public function setID($id) {
		$this->id = $id;
	}

	/**
	 * Informa o tipo de pessoa, que pode ser física ou jurídica
	 */
	public function getTipo() {
		return $this->tipo;
	}

	/**
	 * Informa o tipo de pessoa, que pode ser física ou jurídica
	 */
	public function setTipo($tipo) {
		$this->tipo = $tipo;
	}

	/**
	 * Informa quem é o acionista principal da empresa, obrigatoriamente o cliente deve ser uma pessoa jurídica e o acionista uma pessoa física
	 */
	public function getAcionistaID() {
		return $this->acionista_id;
	}

	/**
	 * Informa quem é o acionista principal da empresa, obrigatoriamente o cliente deve ser uma pessoa jurídica e o acionista uma pessoa física
	 */
	public function setAcionistaID($acionista_id) {
		$this->acionista_id = $acionista_id;
	}

	/**
	 * Nome de usuário utilizado para entrar no sistema, aplicativo ou site
	 */
	public function getLogin() {
		return $this->login;
	}

	/**
	 * Nome de usuário utilizado para entrar no sistema, aplicativo ou site
	 */
	public function setLogin($login) {
		$this->login = $login;
	}

	/**
	 * Senha embaralhada do cliente ou funcionário
	 */
	public function getSenha() {
		return $this->senha;
	}

	/**
	 * Senha embaralhada do cliente ou funcionário
	 */
	public function setSenha($senha) {
		$this->senha = $senha;
	}

	/**
	 * Primeiro nome da pessoa física ou nome fantasia da empresa
	 */
	public function getNome() {
		return $this->nome;
	}

	/**
	 * Primeiro nome da pessoa física ou nome fantasia da empresa
	 */
	public function setNome($nome) {
		$this->nome = $nome;
	}

	/**
	 * Restante do nome da pessoa física ou Razão social da empresa
	 */
	public function getSobrenome() {
		return $this->sobrenome;
	}

	/**
	 * Restante do nome da pessoa física ou Razão social da empresa
	 */
	public function setSobrenome($sobrenome) {
		$this->sobrenome = $sobrenome;
	}

	/**
	 * Informa o gênero do cliente do tipo pessoa física
	 */
	public function getGenero() {
		return $this->genero;
	}

	/**
	 * Informa o gênero do cliente do tipo pessoa física
	 */
	public function setGenero($genero) {
		$this->genero = $genero;
	}

	/**
	 * Cadastro de Pessoa Física(CPF) ou Cadastro Nacional de Pessoa Jurídica(CNPJ)
	 */
	public function getCPF() {
		return $this->cpf;
	}

	/**
	 * Cadastro de Pessoa Física(CPF) ou Cadastro Nacional de Pessoa Jurídica(CNPJ)
	 */
	public function setCPF($cpf) {
		$this->cpf = $cpf;
	}

	/**
	 * Registro Geral(RG) ou Inscrição Estadual (IE)
	 */
	public function getRG() {
		return $this->rg;
	}

	/**
	 * Registro Geral(RG) ou Inscrição Estadual (IE)
	 */
	public function setRG($rg) {
		$this->rg = $rg;
	}

	/**
	 * Inscrição municipal da empresa
	 */
	public function getIM() {
		return $this->im;
	}

	/**
	 * Inscrição municipal da empresa
	 */
	public function setIM($im) {
		$this->im = $im;
	}

	/**
	 * E-mail do cliente ou da empresa
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * E-mail do cliente ou da empresa
	 */
	public function setEmail($email) {
		$this->email = $email;
	}

	/**
	 * Data de aniversário sem o ano ou data de fundação
	 */
	public function getDataAniversario() {
		return $this->data_aniversario;
	}

	/**
	 * Data de aniversário sem o ano ou data de fundação
	 */
	public function setDataAniversario($data_aniversario) {
		$this->data_aniversario = $data_aniversario;
	}

	/**
	 * Telefone principal do cliente, deve ser único
	 */
	public function getFone($index) {
		if ($index < 1 || $index > 2)
			throw new Exception('Índice '.$index.' inválido, aceito somente de 1 até 2');
		return $this->fone[$index];
	}

	/**
	 * Telefone principal do cliente, deve ser único
	 */
	public function setFone($index, $value) {
		if ($index < 1 || $index > 2)
			throw new Exception('Índice '.$index.' inválido, aceito somente de 1 até 2');
		$this->fone[$index] = $value;
	}

	/**
	 * Slogan ou detalhes do cliente
	 */
	public function getSlogan() {
		return $this->slogan;
	}

	/**
	 * Slogan ou detalhes do cliente
	 */
	public function setSlogan($slogan) {
		$this->slogan = $slogan;
	}

	/**
	 * Código secreto para recuperar a conta do cliente
	 */
	public function getSecreto() {
		return $this->secreto;
	}

	/**
	 * Código secreto para recuperar a conta do cliente
	 */
	public function setSecreto($secreto) {
		$this->secreto = $secreto;
	}

	/**
	 * Limite de compra utilizando a forma de pagamento Conta
	 */
	public function getLimiteCompra() {
		return $this->limite_compra;
	}

	/**
	 * Limite de compra utilizando a forma de pagamento Conta
	 */
	public function setLimiteCompra($limite_compra) {
		$this->limite_compra = $limite_compra;
	}

	/**
	 * URL para acessar a página do Facebook do cliente
	 */
	public function getFacebookURL() {
		return $this->facebook_url;
	}

	/**
	 * URL para acessar a página do Facebook do cliente
	 */
	public function setFacebookURL($facebook_url) {
		$this->facebook_url = $facebook_url;
	}

	/**
	 * URL para acessar a página do Twitter do cliente
	 */
	public function getTwitterURL() {
		return $this->twitter_url;
	}

	/**
	 * URL para acessar a página do Twitter do cliente
	 */
	public function setTwitterURL($twitter_url) {
		$this->twitter_url = $twitter_url;
	}

	/**
	 * URL para acessar a página do LinkedIn do cliente
	 */
	public function getLinkedInURL() {
		return $this->linked_in_url;
	}

	/**
	 * URL para acessar a página do LinkedIn do cliente
	 */
	public function setLinkedInURL($linked_in_url) {
		$this->linked_in_url = $linked_in_url;
	}

	/**
	 * Foto do cliente ou logo da empresa
	 */
	public function getImagem() {
		return $this->imagem;
	}

	/**
	 * Foto do cliente ou logo da empresa
	 */
	public function setImagem($imagem) {
		$this->imagem = $imagem;
	}

	/**
	 * Data de atualização das informações do cliente
	 */
	public function getDataAtualizacao() {
		return $this->data_atualizacao;
	}

	/**
	 * Data de atualização das informações do cliente
	 */
	public function setDataAtualizacao($data_atualizacao) {
		$this->data_atualizacao = $data_atualizacao;
	}

	/**
	 * Data de cadastro do cliente
	 */
	public function getDataCadastro() {
		return $this->data_cadastro;
	}

	/**
	 * Data de cadastro do cliente
	 */
	public function setDataCadastro($data_cadastro) {
		$this->data_cadastro = $data_cadastro;
	}

	// Extra

	/**
	 * Obtém o nome completo da pessoa física ou o nome fantasia da empresa
	 */
	public function getNomeCompleto() {
		if($this->getTipo() == ClienteTipo::JURIDICA)
			return $this->getNome();
		return trim($this->getNome() . ' ' . $this->getSobrenome());
	}

	/**
	 * Retorna a assinatura do cliente
	 */
	public function getAssinatura() {
		$nomes = preg_split('/[\s,]+/', $this->getSobrenome());
		$sobrenome = $this->getSobrenome();
 		foreach ($nomes as $nome) {
 			if(in_array($nome, array('da', 'de', 'do', 'das', 'dos')))
 				continue;
 			$sobrenome = $nome;
 			break;
 		}
		return trim($this->nome." ".$sobrenome);
	}

	public function toArray($ignore = array()) {
		$cliente = array();
		$cliente['id'] = $this->getID();
		$cliente['tipo'] = $this->getTipo();
		$cliente['acionistaid'] = $this->getAcionistaID();
		$cliente['login'] = $this->getLogin();
		$cliente['senha'] = $this->getSenha();
		$cliente['nome'] = $this->getNome();
		$cliente['sobrenome'] = $this->getSobrenome();
		$cliente['genero'] = $this->getGenero();
		$cliente['cpf'] = $this->getCPF();
		$cliente['rg'] = $this->getRG();
		$cliente['im'] = $this->getIM();
		$cliente['email'] = $this->getEmail();
		$cliente['dataaniversario'] = $this->getDataAniversario();
		$cliente['slogan'] = $this->getSlogan();
		$cliente['secreto'] = $this->getSecreto();
		$cliente['limitecompra'] = $this->getLimiteCompra();
		$cliente['facebookurl'] = $this->getFacebookURL();
		$cliente['twitterurl'] = $this->getTwitterURL();
		$cliente['linkedinurl'] = $this->getLinkedInURL();
		$cliente['imagem'] = $this->getImagem();
		$cliente['dataatualizacao'] = $this->getDataAtualizacao();
		$cliente['datacadastro'] = $this->getDataCadastro();
		for($i = 1; $i <= 2; $i++)
			$cliente['fone'.$i] = $this->getFone($i);
		if(count($ignore) > 0)
			return array_diff_key($cliente, array_flip($ignore));
		return $cliente;
	}

	public static function gerarSenha($p) {
		return sha1(utf8_decode(self::CHAVE_SECRETA . $p));
	}

	private static function initSelectFields($query) {
		return $query->select(null)
					 ->select('c.id')
					 ->select('c.tipo')
					 ->select('c.acionistaid')
					 ->select('c.login')
					 ->select('c.senha')
					 ->select('c.nome')
					 ->select('c.sobrenome')
					 ->select('c.genero')
					 ->select('c.fone1')
					 ->select('c.fone2')
					 ->select('c.cpf')
					 ->select('c.rg')
					 ->select('c.im')
					 ->select('c.email')
					 ->select('c.dataaniversario')
					 ->select('c.slogan')
					 ->select('c.secreto')
					 ->select('c.limitecompra')
					 ->select('c.facebookurl')
					 ->select('c.twitterurl')
					 ->select('c.linkedinurl')
					 ->select('IF(ISNULL(c.imagem), NULL, CONCAT(c.id, ".png")) as imagem')
					 ->select('c.dataatualizacao')
					 ->select('c.datacadastro');
	}

	private static function initSelect() {
		$query = DB::$pdo->from('Clientes c');
		return self::initSelectFields($query);
	}

	public static function getPeloID($id) {
		$query = self::initSelect()
		                 ->where(array('id' => $id));
		return new ZCliente($query->fetch());
	}

	public static function getPeloFone($fone) {
		$_fone = numberonly($fone);
		if(strlen($_fone) == 0)
			return new ZCliente();
		$_ddd = substr($_fone, 0, 2).'%';
		if(strlen($_fone) == 10)
			$_fone = $_ddd . substr($_fone, 2, 8);
		else if(strlen($_fone) <= 9)
			$_fone = '%' . $_fone;
		else
			$_fone = $_ddd . substr($_fone, 3);
		$query = self::initSelect()
	                 ->where('c.fone1 LIKE ? OR c.fone2 LIKE ?', $_fone, $_fone)
					 ->orderBy('IF(c.fone1 LIKE ?, 0, 1)', $_fone)
					 ->limit(1);
		return new ZCliente($query->fetch());
	}

	public static function getPelaEmail($email) {
		$query = self::initSelect()
		                 ->where(array('email' => $email));
		return new ZCliente($query->fetch());
	}

	public static function getPeloCPF($cpf) {
		$query = self::initSelect()
		                 ->where(array('cpf' => $cpf));
		return new ZCliente($query->fetch());
	}

	public static function getPeloLogin($login) {
		$query = self::initSelect()
		                 ->where(array('login' => $login));
		return new ZCliente($query->fetch());
	}

	public static function getPeloSecreto($secreto) {
		$query = self::initSelect()
		                 ->where(array('secreto' => $secreto));
		return new ZCliente($query->fetch());
	}

	public static function getPeloToken($token) {
		$token = base64_decode($token);
		$len = strlen($token);
		if($len <= 62 || $len > 100)
			return new ZCliente();
		$plen = $len - 48;
		$hlen = 40;
		$offset = min(max($plen * 2, 4 + $plen), 8);
		$id = '';
		$hash = '';
		$crc = '';
		for ($i = 0; $i < $len; $i++) {
			if($i % 2 == 1 && $plen > 0) {
				$id .= $token[$i];
				$plen--;
			} else if($i >= $offset && $hlen > 0) {
				$hash .= $token[$i];
				$hlen--;
			} else {
				$crc .= $token[$i];
			}
		}
		$ccrc = dechex(crc32($id.$hash));
		$m = time();
		$sm = strtotime(preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})/", 
			"$1-$2-$3 $4:$5:$6", substr($id, -14)));
		$i = round(abs($tm - $stm) / 60);
		if($i > 5 || $sm === false || strcasecmp($crc, $ccrc) != 0)
			return new ZCliente();
		$id = substr($id, 0, -14);
		$query = self::initSelect()->where(array(
								'c.id' => intval($id),
								'c.senha' => $hash,
							));
		return new ZCliente($query->fetch());
	}

	public static function getPeloCookie($cname='ru') {
		$cv = cookieget($cname);
		if ($cv) {
			$zone = base64_decode($cv);
			$p = explode('@', $zone, 2);
			$query = self::initSelect()->where(array(
								'c.id' => $p[0],
								'c.senha' => $p[1],
							));
			return new ZCliente($query->fetch());
		}
		return new ZCliente();
	}

	public static function getPeloLoginSenha($email, $unpass) {
		$senha = self::gerarSenha($unpass);
		if(strpos($email, '@') !== false)
			$field = 'c.email';
		else
			$field = 'c.login';
		$query = self::initSelect()->where(array(
							$field => $email,
							'c.senha' => $senha,
						));
		return new ZCliente($query->fetch());
	}

	public static function getImagemPeloID($cliente_id, $dataSomente = false) {
		$query = DB::$pdo->from('Clientes c')
						 ->select(null)
						 ->select('c.dataatualizacao')
						 ->where(array('c.id' => $cliente_id));
		if(!$dataSomente)
			$query = $query->select('c.imagem');
		return $query->fetch();
	}

	private static function validarCampos(&$cliente) {
		$erros = array();
		$funcionario = ZFuncionario::getPeloClienteID($cliente['id']);
		$cliente['tipo'] = trim($cliente['tipo']);
		if(strlen($cliente['tipo']) == 0)
			$cliente['tipo'] = ClienteTipo::FISICA;
		else if(!in_array($cliente['tipo'], array('Fisica', 'Juridica')))
			$erros['tipo'] = 'O tipo de cliente informado não é válido';
		if(is_manager($funcionario) && $cliente['tipo'] != 'Fisica')
			$erros['tipo'] = 'O cliente é um funcionário e deve ser uma pessoa física';
		$cliente['acionistaid'] = trim($cliente['acionistaid']);
		if(strlen($cliente['acionistaid']) == 0)
			$cliente['acionistaid'] = null;
		else if(!is_numeric($cliente['acionistaid']))
			$erros['acionistaid'] = 'O código do acionista informado é inválido';
		$cliente['login'] = strip_tags(trim($cliente['login']));
		// não obriga ter nome de usuário
		if(strlen($cliente['login']) == 0)
			$cliente['login'] = null;
		else if(!check_usuario($cliente['login']))
			$erros['login'] = 'Nome de usuário inválido';
		if(is_manager($funcionario) && is_null($cliente['login']))
			$erros['tipo'] = 'O cliente é um funcionário e deve possuir um nome de login';
		$cliente['senha'] = strval($cliente['senha']);
		$cliente['nome'] = strip_tags(trim($cliente['nome']));
		if(strlen($cliente['nome']) == 0)
			$erros['nome'] = 'O nome não pode ser vazio';
		$cliente['sobrenome'] = strip_tags(trim($cliente['sobrenome']));
		if(strlen($cliente['sobrenome']) == 0)
			$cliente['sobrenome'] = null;
		$cliente['genero'] = strval($cliente['genero']);
		if($cliente['tipo'] == ClienteTipo::JURIDICA)
			$cliente['genero'] = ClienteGenero::FEMININO;
		if(!in_array($cliente['genero'], array('Masculino', 'Feminino')))
			$erros['genero'] = 'O gênero informado não é válido';
		$cliente['cpf'] = numberonly($cliente['cpf']);
		if(strlen($cliente['cpf']) == 0)
			$cliente['cpf'] = null;
		else if(!check_cpf($cliente['cpf']) && $cliente['tipo'] == ClienteTipo::FISICA)
			$erros['cpf'] = 'CPF inválido';
		else if(!check_cnpj($cliente['cpf']) && $cliente['tipo'] == ClienteTipo::JURIDICA)
			$erros['cpf'] = 'CNPJ inválido';
		$cliente['rg'] = strip_tags(trim($cliente['rg']));
		if(strlen($cliente['rg']) == 0)
			$cliente['rg'] = null;
		$cliente['im'] = strip_tags(trim($cliente['im']));
		if(strlen($cliente['im']) == 0)
			$cliente['im'] = null;
		$cliente['email'] = strip_tags(trim($cliente['email']));
		if(strlen($cliente['email']) == 0)
			$cliente['email'] = null;
		else if(!check_email($cliente['email']))
			$erros['email'] = 'E-mail inválido';
		$cliente['dataaniversario'] = strval($cliente['dataaniversario']);
		if(strlen($cliente['dataaniversario']) == 0)
			$cliente['dataaniversario'] = null;
		else {
			$time = strtotime($cliente['dataaniversario']);
			if($time === false)
				$erros['dataaniversario'] = 'A data de aniversário é inválida';
			else
				$cliente['dataaniversario'] = date('Y-m-d', $time);
		}
		for($i = 1; $i <= 2; $i++) {
			$cliente['fone'.$i] = numberonly($cliente['fone'.$i]);
			if(strlen($cliente['fone'.$i]) == 0)
				$cliente['fone'.$i] = null;
			else if(!check_fone($cliente['fone'.$i]))
				$erros['fone'.$i] = 'Telefone inválido';
		}
		if(trim($cliente['cpf']) == '' && trim($cliente['fone1']) == '' && trim($cliente['email']) == '')
			$erros['fone1'] = 'Nenhum dado chave foi informado, informe um Telefone, E-mail ou CPF';
		$cliente['slogan'] = strip_tags(trim($cliente['slogan']));
		if(strlen($cliente['slogan']) == 0)
			$cliente['slogan'] = null;
		$cliente['secreto'] = strip_tags(trim($cliente['secreto']));
		if(strlen($cliente['secreto']) == 0)
			$cliente['secreto'] = null;
		$cliente['limitecompra'] = trim($cliente['limitecompra']);
		if(strlen($cliente['limitecompra']) == 0)
			$cliente['limitecompra'] = null;
		else if(!is_numeric($cliente['limitecompra']))
			$erros['limitecompra'] = 'O limite de compra não foi informado';
		else if($cliente['limitecompra'] < 0)
			$erros['limitecompra'] = 'O limite de compra não pode ser negativo';
		$cliente['facebookurl'] = strip_tags(trim($cliente['facebookurl']));
		if(strlen($cliente['facebookurl']) == 0)
			$cliente['facebookurl'] = null;
		$cliente['twitterurl'] = strip_tags(trim($cliente['twitterurl']));
		if(strlen($cliente['twitterurl']) == 0)
			$cliente['twitterurl'] = null;
		$cliente['linkedinurl'] = strip_tags(trim($cliente['linkedinurl']));
		if(strlen($cliente['linkedinurl']) == 0)
			$cliente['linkedinurl'] = null;
		if($cliente['imagem'] === '')
			$cliente['imagem'] = null;
		$cliente['dataatualizacao'] = date('Y-m-d H:i:s');
		$cliente['datacadastro'] = date('Y-m-d H:i:s');
		if(!empty($erros))
			throw new ValidationException($erros);
	}

	private static function handleException(&$e) {
		if(stripos($e->getMessage(), 'PRIMARY') !== false)
			throw new ValidationException(array('id' => 'O ID informado já está cadastrado'));
		if(stripos($e->getMessage(), 'Fone1_UNIQUE') !== false)
			throw new ValidationException(array('fone1' => 'O Telefone informado já está cadastrado'));
		if(stripos($e->getMessage(), 'Email_UNIQUE') !== false)
			throw new ValidationException(array('email' => 'O E-mail informado já está cadastrado'));
		if(stripos($e->getMessage(), 'CPF_UNIQUE') !== false)
			throw new ValidationException(array('cpf' => 'O CPF informado já está cadastrado'));
		if(stripos($e->getMessage(), 'Login_UNIQUE') !== false)
			throw new ValidationException(array('login' => 'O nome de usuário informado já está cadastrado'));
		if(stripos($e->getMessage(), 'Secreto_UNIQUE') !== false)
			throw new ValidationException(array('secreto' => 'O código de recuperação informado já está cadastrado'));
	}

	public static function cadastrar($cliente) {
		$cliente->setID(null);
		$cliente->setAcionistaID(null);
		$cliente->setSecreto(null);
		$_cliente = $cliente->toArray();
		self::validarCampos($_cliente);
		// a senha não pode ser vazia
		if(trim($_cliente['senha']) == '' && !is_login())
			throw new ValidationException(array('senha' => 'A senha não pode ser vazia'));
		$_cliente['senha'] = self::gerarSenha($_cliente['senha']);
		try {
			$_cliente['id'] = DB::$pdo->insertInto('Clientes')->values($_cliente)->execute();
		} catch (Exception $e) {
			self::handleException($e);
			throw $e;
		}
		return self::getPeloID($_cliente['id']);
	}

	public static function atualizar($cliente) {
		$_cliente = $cliente->toArray();
		if(!$_cliente['id'])
			throw new ValidationException(array('id' => 'O id do cliente não foi informado'));
		self::validarCampos($_cliente);
		$campos = array(
			'tipo',
			'acionistaid',
			'login',
			'nome',
			'sobrenome',
			'genero',
			'cpf',
			'rg',
			'im',
			'email',
			'dataaniversario',
			'slogan',
			'secreto',
			'limitecompra',
			'facebookurl',
			'twitterurl',
			'linkedinurl',
			'dataatualizacao',
		);
		if($_cliente['imagem'] !== true)
			$campos[] = 'imagem';
		if(trim($_cliente['senha']) != '') {
			$_cliente['senha'] = self::gerarSenha($_cliente['senha']);
			$campos[] = 'senha';
		}
		for($i = 1; $i <= 2; $i++)
			$campos[] = 'fone'.$i;
		try {
			$query = DB::$pdo->update('Clientes');
			$query = $query->set(array_intersect_key($_cliente, array_flip($campos)));
			$query = $query->where('id', $_cliente['id']);
			$query->execute();
		} catch (Exception $e) {
			self::handleException($e);
			throw $e;
		}
		return self::getPeloID($_cliente['id']);
	}

	public static function excluir($id) {
		if(!$id)
			throw new Exception('Não foi possível excluir o cliente, o id do cliente não foi informado');
		$query = DB::$pdo->deleteFrom('Clientes')
		                 ->where(array('id' => $id));
		return $query->execute();
	}

	private static function initSearch($nome, $tipo, $genero, $mes_inicio, $mes_fim) {
		$query = self::initSelect();
		$data_inicio = null;
		if(!is_null($mes_inicio) && !is_numeric($mes_inicio))
			$data_inicio = strtotime($mes_inicio);
		else if(!is_null($mes_inicio))
			$data_inicio = strtotime(date('Y-m').' '.$mes_inicio.' month');
		$data_fim = null;
		if(!is_null($mes_fim) && !is_numeric($mes_fim))
			$data_fim = strtotime($mes_fim);
		else if(!is_null($mes_fim)) {
			$data_fim = strtotime(date('Y-m').' '.$mes_fim.' month');
			$data_fim = strtotime('last day of this month', $data_fim);
		}
		$tipo = strtolower($tipo);
		if($tipo == strtolower(ClienteTipo::JURIDICA))
			$tipo = ClienteTipo::JURIDICA;
		else if($tipo == strtolower(ClienteTipo::FISICA))
			$tipo = ClienteTipo::FISICA;
		else
			$tipo = null;

		$genero = strtolower($genero);
		if($tipo == ClienteTipo::JURIDICA) {
			# não faz nada
		} else if($genero == strtolower(ClienteGenero::MASCULINO)) {
			$query = $query->where('c.genero', ClienteGenero::MASCULINO);
			$tipo = ClienteTipo::FISICA;
		} else if($genero == strtolower(ClienteGenero::FEMININO)) {
			$query = $query->where('c.genero', ClienteGenero::FEMININO);
			$tipo = ClienteTipo::FISICA;
		}

		if(!is_null($tipo))
			$query = $query->where('c.tipo', $tipo);

		$nome = trim($nome);
		if($nome == '') {
			# não faz nada
		} else if(check_email($nome)) {
			$query = $query->where('c.email', $nome);
		} else if($tipo != strtolower(ClienteTipo::JURIDICA) && check_cpf($nome)) {
			$query = $query->where('c.cpf', numberonly($nome));
		} else if($tipo != strtolower(ClienteTipo::FISICA) && check_cnpj($nome)) {
			$query = $query->where('c.cpf', numberonly($nome));
		} else if(check_fone($nome, true)) {
			$_fone = numberonly($nome);
			$_ddd = substr($_fone, 0, 2).'%';
			if(strlen($_fone) == 10)
				$_fone = $_ddd . substr($_fone, 2, 8);
			else if(strlen($_fone) <= 9)
				$_fone = '%' . $_fone;
			else
				$_fone = $_ddd . substr($_fone, 3);
			$query = $query->where('(c.fone1 LIKE ? OR c.fone2 LIKE ?)', $_fone, $_fone);
		} else {
			$keywords = preg_split('/[\s,]+/', $nome);
			$words = '';
	 		foreach ($keywords as $word) {
	 			$words .= '%'.$word.'%';
	 			$query = $query->orderBy('IF(LOCATE(?, CONCAT(" ", c.nome, " ", COALESCE(c.sobrenome, ""))) = 0, '.
					'256, LOCATE(?, CONCAT(" ", c.nome, " ", COALESCE(c.sobrenome, "")))) ASC, IF(LOCATE(?, '.
					'CONCAT(c.nome, " ", COALESCE(c.sobrenome, ""))) = 0, 256, LOCATE(?, CONCAT(c.nome, " ", COALESCE(c.sobrenome, "")))) ASC', 
	 				' '.$word, ' '.$word, $word, $word);
	 		}
			$query = $query->where('CONCAT(c.nome, " ", COALESCE(c.sobrenome, "")) LIKE ?', $words);
		}
		if(!is_null($data_inicio))
			$query = $query->where('c.datacadastro >= ?', date('Y-m-d', $data_inicio));
		if(!is_null($data_fim))
			$query = $query->where('c.datacadastro <= ?', date('Y-m-d 23:59:59', $data_fim));
		$query = $query->orderBy('id ASC');
		return $query;
	}

	public static function getTodos($nome = null, $tipo = null, $genero = null, $mes_inicio = null, $mes_fim = null, $inicio = null, $quantidade = null) {
		$query = self::initSearch($nome, $tipo, $genero, $mes_inicio, $mes_fim);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_clientes = $query->fetchAll();
		$clientes = array();
		foreach($_clientes as $cliente)
			$clientes[] = new ZCliente($cliente);
		return $clientes;
	}

	public static function getCount($nome = null, $tipo = null, $genero = null, $mes_inicio = null, $mes_fim = null) {
		$query = self::initSearch($nome, $tipo, $genero, $mes_inicio, $mes_fim);
		return $query->count();
	}

	private static function initSearchCompradores($mes_inicio, $mes_fim) {
		$query = DB::$pdo->from('Pedidos p');
		$query = self::initSelectFields($query)
						 ->select('COUNT(DISTINCT p.id) as pedidos')
						 ->select('SUM(pp.quantidade * pp.preco * (1 + pp.porcentagem/100)) as total')
						 ->leftJoin('Produtos_Pedidos pp ON pp.pedidoid = p.id AND pp.cancelado = ?', 'N')
						 ->leftJoin('Clientes c ON c.id = p.clienteid')
						 ->where('p.cancelado', 'N')
						 ->where('p.estado', PedidoEstado::FINALIZADO)
						 ->where('NOT ISNULL(p.clienteid)')
		                 ->orderBy('total DESC')
		                 ->groupBy('p.clienteid');
		$data_inicio = null;
		if(!is_null($mes_inicio))
			$data_inicio = strtotime(date('Y-m').' '.$mes_inicio.' month');
		$data_fim = null;
		if(!is_null($mes_fim)) {
			$data_fim = strtotime(date('Y-m').' '.$mes_fim.' month');
			$data_fim = strtotime('last day of this month 23:59:59', $data_fim);
		}
		if(!is_null($data_inicio))
			$query = $query->where('p.datacriacao >= ?', date('Y-m-d', $data_inicio));
		if(!is_null($data_fim))
			$query = $query->where('p.datacriacao <= ?', date('Y-m-d H:i:s', $data_fim));
		return $query;
	}

	public static function getTodosCompradores($mes_inicio, $mes_fim, $inicio = null, $quantidade = null) {
		$query = self::initSearchCompradores($mes_inicio, $mes_fim);
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		return $query->fetchAll();
	}

	public static function getCountCompradores($mes_inicio, $mes_fim) {
		$query = self::initSearchCompradores($mes_inicio, $mes_fim);
		return $query->count();
	}

}
