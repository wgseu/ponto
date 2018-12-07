<?php
/**
 * Copyright 2014 da MZ Software - MZ Desenvolvimento de Sistemas LTDA
 *
 * Este arquivo é parte do programa GrandChef - Sistema para Gerenciamento de Churrascarias, Bares e Restaurantes.
 * O GrandChef é um software proprietário; você não pode redistribuí-lo e/ou modificá-lo.
 * DISPOSIÇÕES GERAIS
 * O cliente não deverá remover qualquer identificação do produto, avisos de direitos autorais,
 * ou outros avisos ou restrições de propriedade do GrandChef.
 *
 * O cliente não deverá causar ou permitir a engenharia reversa, desmontagem,
 * ou descompilação do GrandChef.
 *
 * PROPRIEDADE DOS DIREITOS AUTORAIS DO PROGRAMA
 *
 * GrandChef é a especialidade do desenvolvedor e seus
 * licenciadores e é protegido por direitos autorais, segredos comerciais e outros direitos
 * de leis de propriedade.
 *
 * O Cliente adquire apenas o direito de usar o software e não adquire qualquer outros
 * direitos, expressos ou implícitos no GrandChef diferentes dos especificados nesta Licença.
 *
 * @author Equipe GrandChef <desenvolvimento@mzsw.com.br>
 */
namespace MZ\Account;

use MZ\Util\Mask;
use MZ\Util\Filter;
use MZ\Util\Validator;
use MZ\Database\DB;
use MZ\Database\SyncModel;
use MZ\Provider\Prestador;
use MZ\Sale\Pedido;
use MZ\Exception\ValidationException;

/**
 * Informações de cliente físico ou jurídico. Clientes, empresas,
 * funcionários, fornecedores e parceiros são cadastrados aqui
 */
class Cliente extends SyncModel
{
    const CHAVE_SECRETA = '%#@87GhÃ¨¬';

    /**
     * Informa o tipo de pessoa, que pode ser física ou jurídica
     */
    const TIPO_FISICA = 'Fisica';
    const TIPO_JURIDICA = 'Juridica';

    /**
     * Informa o gênero do cliente do tipo pessoa física
     */
    const GENERO_MASCULINO = 'Masculino';
    const GENERO_FEMININO = 'Feminino';

    /**
     * Informa o estado da conta do cliente
     */
    const STATUS_ATIVO = 'Ativo';
    const STATUS_INATIVO = 'Inativo';
    const STATUS_BLOQUEADO = 'Bloqueado';

    /**
     * Identificador do cliente
     */
    private $id;
    /**
     * Informa o tipo de pessoa, que pode ser física ou jurídica
     */
    private $tipo;
    /**
     * Informa se esse cliente faz parte da empresa informada
     */
    private $empresa_id;
    /**
     * Nome de usuário utilizado para entrar no sistema, aplicativo ou site
     */
    private $login;
    /**
     * Senha embaralhada do cliente
     */
    private $senha;
    /**
     * Primeiro nome da pessoa física ou nome fantasia da empresa
     */
    private $nome;
    /**
     * Restante do nome da pessoa física ou Razão social da empresa
     */
    private $sobrenome;
    /**
     * Informa o gênero do cliente do tipo pessoa física
     */
    private $genero;
    /**
     * Cadastro de Pessoa Física(CPF) ou Cadastro Nacional de Pessoa
     * Jurídica(CNPJ)
     */
    private $cpf;
    /**
     * Registro Geral(RG) ou Inscrição Estadual (IE)
     */
    private $rg;
    /**
     * Inscrição municipal da empresa
     */
    private $im;
    /**
     * E-mail do cliente ou da empresa
     */
    private $email;
    /**
     * Data de aniversário sem o ano ou data de fundação
     */
    private $data_aniversario;
    /**
     * Slogan ou detalhes do cliente
     */
    private $slogan;
    /**
     * Informa o estado da conta do cliente
     */
    private $status;
    /**
     * Código secreto para recuperar a conta do cliente
     */
    private $secreto;
    /**
     * Se informado, significa que a senha é segura
     */
    private $salt;
    /**
     * Limite de compra utilizando a forma de pagamento Conta
     */
    private $limite_compra;
    /**
     * URL para acessar a página do Instagram do cliente
     */
    private $instagram_url;
    /**
     * URL para acessar a página do Facebook do cliente
     */
    private $facebook_url;
    /**
     * URL para acessar a página do Twitter do cliente
     */
    private $twitter_url;
    /**
     * URL para acessar a página do LinkedIn do cliente
     */
    private $linked_in_url;
    /**
     * Foto do cliente ou logo da empresa
     */
    private $imagem_url;
    /**
     * Código da linguagem utilizada pelo cliente para visualizar o aplicativo
     * e o site, Ex: pt-BR
     */
    private $linguagem;
    /**
     * Data de atualização das informações do cliente
     */
    private $data_atualizacao;
    /**
     * Data de cadastro do cliente
     */
    private $data_cadastro;

    /**
     * Telefone principal do cliente
     * @var Telefone
     */
    private $telefone;

    /**
     * Constructor for a new empty instance of Cliente
     * @param array $cliente All field and values to fill the instance
     */
    public function __construct($cliente = [])
    {
        parent::__construct($cliente);
    }

    /**
     * Identificador do cliente
     * @return int id of Cliente
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param int $id Set id for Cliente
     * @return self Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Informa o tipo de pessoa, que pode ser física ou jurídica
     * @return string tipo of Cliente
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set Tipo value to new on param
     * @param string $tipo Set tipo for Cliente
     * @return self Self instance
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
        return $this;
    }

    /**
     * Informa se esse cliente faz parte da empresa informada
     * @return int empresa of Cliente
     */
    public function getEmpresaID()
    {
        return $this->empresa_id;
    }

    /**
     * Set EmpresaID value to new on param
     * @param int $empresa_id Set empresa for Cliente
     * @return self Self instance
     */
    public function setEmpresaID($empresa_id)
    {
        $this->empresa_id = $empresa_id;
        return $this;
    }

    /**
     * Nome de usuário utilizado para entrar no sistema, aplicativo ou site
     * @return string login of Cliente
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set Login value to new on param
     * @param string $login Set login for Cliente
     * @return self Self instance
     */
    public function setLogin($login)
    {
        $this->login = $login;
        return $this;
    }

    /**
     * Senha embaralhada do cliente
     * @return string senha of Cliente
     */
    public function getSenha()
    {
        return $this->senha;
    }

    /**
     * Set Senha value to new on param
     * @param string $senha Set senha for Cliente
     * @return self Self instance
     */
    public function setSenha($senha)
    {
        $this->senha = $senha;
        return $this;
    }

    /**
     * Primeiro nome da pessoa física ou nome fantasia da empresa
     * @return string nome of Cliente
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set Nome value to new on param
     * @param string $nome Set nome for Cliente
     * @return self Self instance
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * Restante do nome da pessoa física ou Razão social da empresa
     * @return string sobrenome of Cliente
     */
    public function getSobrenome()
    {
        return $this->sobrenome;
    }

    /**
     * Set Sobrenome value to new on param
     * @param string $sobrenome Set sobrenome for Cliente
     * @return self Self instance
     */
    public function setSobrenome($sobrenome)
    {
        $this->sobrenome = $sobrenome;
        return $this;
    }

    /**
     * Informa o gênero do cliente do tipo pessoa física
     * @return string gênero of Cliente
     */
    public function getGenero()
    {
        return $this->genero;
    }

    /**
     * Set Genero value to new on param
     * @param string $genero Set gênero for Cliente
     * @return self Self instance
     */
    public function setGenero($genero)
    {
        $this->genero = $genero;
        return $this;
    }

    /**
     * Cadastro de Pessoa Física(CPF) ou Cadastro Nacional de Pessoa
     * Jurídica(CNPJ)
     * @return string cpf of Cliente
     */
    public function getCPF()
    {
        return $this->cpf;
    }

    /**
     * Set CPF value to new on param
     * @param string $cpf Set cpf for Cliente
     * @return self Self instance
     */
    public function setCPF($cpf)
    {
        $this->cpf = $cpf;
        return $this;
    }

    /**
     * Registro Geral(RG) ou Inscrição Estadual (IE)
     * @return string registro geral of Cliente
     */
    public function getRG()
    {
        return $this->rg;
    }

    /**
     * Set RG value to new on param
     * @param string $rg Set registro geral for Cliente
     * @return self Self instance
     */
    public function setRG($rg)
    {
        $this->rg = $rg;
        return $this;
    }

    /**
     * Inscrição municipal da empresa
     * @return string inscrição municipal of Cliente
     */
    public function getIM()
    {
        return $this->im;
    }

    /**
     * Set IM value to new on param
     * @param string $im Set inscrição municipal for Cliente
     * @return self Self instance
     */
    public function setIM($im)
    {
        $this->im = $im;
        return $this;
    }

    /**
     * E-mail do cliente ou da empresa
     * @return string e-mail of Cliente
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set Email value to new on param
     * @param string $email Set e-mail for Cliente
     * @return self Self instance
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Data de aniversário sem o ano ou data de fundação
     * @return string data de aniversário of Cliente
     */
    public function getDataAniversario()
    {
        return $this->data_aniversario;
    }

    /**
     * Set DataAniversario value to new on param
     * @param string $data_aniversario Set data de aniversário for Cliente
     * @return self Self instance
     */
    public function setDataAniversario($data_aniversario)
    {
        $this->data_aniversario = $data_aniversario;
        return $this;
    }

    /**
     * Slogan ou detalhes do cliente
     * @return string observação of Cliente
     */
    public function getSlogan()
    {
        return $this->slogan;
    }

    /**
     * Set Slogan value to new on param
     * @param string $slogan Set observação for Cliente
     * @return self Self instance
     */
    public function setSlogan($slogan)
    {
        $this->slogan = $slogan;
        return $this;
    }

    /**
     * Informa o estado da conta do cliente
     * @return string status of Cliente
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set Status value to new on param
     * @param string $status Set status for Cliente
     * @return self Self instance
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Código secreto para recuperar a conta do cliente
     * @return string código de recuperação of Cliente
     */
    public function getSecreto()
    {
        return $this->secreto;
    }

    /**
     * Set Secreto value to new on param
     * @param string $secreto Set código de recuperação for Cliente
     * @return self Self instance
     */
    public function setSecreto($secreto)
    {
        $this->secreto = $secreto;
        return $this;
    }

    /**
     * Se informado, significa que a senha é segura
     * @return string código de segurança of Cliente
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set Salt value to new on param
     * @param string $salt Set código de segurança for Cliente
     * @return self Self instance
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
        return $this;
    }

    /**
     * Limite de compra utilizando a forma de pagamento Conta
     * @return string limite de compra of Cliente
     */
    public function getLimiteCompra()
    {
        return $this->limite_compra;
    }

    /**
     * Set LimiteCompra value to new on param
     * @param string $limite_compra Set limite de compra for Cliente
     * @return self Self instance
     */
    public function setLimiteCompra($limite_compra)
    {
        $this->limite_compra = $limite_compra;
        return $this;
    }

    /**
     * URL para acessar a página do Instagram do cliente
     * @return string instagram of Cliente
     */
    public function getInstagramURL()
    {
        return $this->instagram_url;
    }

    /**
     * Set InstagramURL value to new on param
     * @param string $instagram_url Set instagram for Cliente
     * @return self Self instance
     */
    public function setInstagramURL($instagram_url)
    {
        $this->instagram_url = $instagram_url;
        return $this;
    }

    /**
     * URL para acessar a página do Facebook do cliente
     * @return string facebook of Cliente
     */
    public function getFacebookURL()
    {
        return $this->facebook_url;
    }

    /**
     * Set FacebookURL value to new on param
     * @param string $facebook_url Set facebook for Cliente
     * @return self Self instance
     */
    public function setFacebookURL($facebook_url)
    {
        $this->facebook_url = $facebook_url;
        return $this;
    }

    /**
     * URL para acessar a página do Twitter do cliente
     * @return string twitter of Cliente
     */
    public function getTwitterURL()
    {
        return $this->twitter_url;
    }

    /**
     * Set TwitterURL value to new on param
     * @param string $twitter_url Set twitter for Cliente
     * @return self Self instance
     */
    public function setTwitterURL($twitter_url)
    {
        $this->twitter_url = $twitter_url;
        return $this;
    }

    /**
     * URL para acessar a página do LinkedIn do cliente
     * @return string linkedin of Cliente
     */
    public function getLinkedInURL()
    {
        return $this->linked_in_url;
    }

    /**
     * Set LinkedInURL value to new on param
     * @param string $linked_in_url Set linkedin for Cliente
     * @return self Self instance
     */
    public function setLinkedInURL($linked_in_url)
    {
        $this->linked_in_url = $linked_in_url;
        return $this;
    }

    /**
     * Foto do cliente ou logo da empresa
     * @return string foto of Cliente
     */
    public function getImagemURL()
    {
        return $this->imagem_url;
    }

    /**
     * Set ImagemURL value to new on param
     * @param string $imagem_url Set foto for Cliente
     * @return self Self instance
     */
    public function setImagemURL($imagem_url)
    {
        $this->imagem_url = $imagem_url;
        return $this;
    }

    /**
     * Código da linguagem utilizada pelo cliente para visualizar o aplicativo
     * e o site, Ex: pt-BR
     * @return string linguagem of Cliente
     */
    public function getLinguagem()
    {
        return $this->linguagem;
    }

    /**
     * Set Linguagem value to new on param
     * @param string $linguagem Set linguagem for Cliente
     * @return self Self instance
     */
    public function setLinguagem($linguagem)
    {
        $this->linguagem = $linguagem;
        return $this;
    }

    /**
     * Data de atualização das informações do cliente
     * @return string data de atualização of Cliente
     */
    public function getDataAtualizacao()
    {
        return $this->data_atualizacao;
    }

    /**
     * Set DataAtualizacao value to new on param
     * @param string $data_atualizacao Set data de atualização for Cliente
     * @return self Self instance
     */
    public function setDataAtualizacao($data_atualizacao)
    {
        $this->data_atualizacao = $data_atualizacao;
        return $this;
    }

    /**
     * Data de cadastro do cliente
     * @return string data de cadastro of Cliente
     */
    public function getDataCadastro()
    {
        return $this->data_cadastro;
    }

    /**
     * Set DataCadastro value to new on param
     * @param string $data_cadastro Set data de cadastro for Cliente
     * @return self Self instance
     */
    public function setDataCadastro($data_cadastro)
    {
        $this->data_cadastro = $data_cadastro;
        return $this;
    }

    /** Campos Extra **/

    /**
     * Data de cadastro do cliente
     * @return Telefone Data de cadastro of Cliente
     */
    public function getTelefone()
    {
        return $this->telefone;
    }

    /**
     * Informa o telefone principal do cliente
     * @param Telefone|string $telefone Telefone principal do cliente
     * @return self A própria instância do cliente
     */
    public function setTelefone($telefone)
    {
        if (!($telefone instanceof Telefone)) {
            $telefone = new Telefone(is_array($telefone) ? $telefone : ['numero' => $telefone]);
        }
        $this->telefone = $telefone;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $cliente = parent::toArray($recursive);
        $cliente['id'] = $this->getID();
        $cliente['tipo'] = $this->getTipo();
        $cliente['empresaid'] = $this->getEmpresaID();
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
        $cliente['status'] = $this->getStatus();
        $cliente['secreto'] = $this->getSecreto();
        $cliente['salt'] = $this->getSalt();
        $cliente['limitecompra'] = $this->getLimiteCompra();
        $cliente['instagramurl'] = $this->getInstagramURL();
        $cliente['facebookurl'] = $this->getFacebookURL();
        $cliente['twitterurl'] = $this->getTwitterURL();
        $cliente['linkedinurl'] = $this->getLinkedInURL();
        $cliente['imagemurl'] = $this->getImagemURL();
        $cliente['linguagem'] = $this->getLinguagem();
        $cliente['dataatualizacao'] = $this->getDataAtualizacao();
        $cliente['datacadastro'] = $this->getDataCadastro();
        return $cliente;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param mixed $cliente Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($cliente = [])
    {
        if ($cliente instanceof self) {
            $telefone = $cliente->getTelefone();
            $cliente = $cliente->toArray();
            $cliente['fone1'] = $telefone;
        } elseif (!is_array($cliente)) {
            $cliente = [];
        }
        parent::fromArray($cliente);
        if (!isset($cliente['id'])) {
            $this->setID(null);
        } else {
            $this->setID($cliente['id']);
        }
        if (!isset($cliente['tipo'])) {
            $this->setTipo(self::TIPO_FISICA);
        } else {
            $this->setTipo($cliente['tipo']);
        }
        if (!array_key_exists('empresaid', $cliente)) {
            $this->setEmpresaID(null);
        } else {
            $this->setEmpresaID($cliente['empresaid']);
        }
        if (!array_key_exists('login', $cliente)) {
            $this->setLogin(null);
        } else {
            $this->setLogin($cliente['login']);
        }
        if (!array_key_exists('senha', $cliente)) {
            $this->setSenha('');
        } else {
            $this->setSenha($cliente['senha']);
        }
        if (!isset($cliente['nome'])) {
            $this->setNome(null);
        } else {
            $this->setNome($cliente['nome']);
        }
        if (!array_key_exists('sobrenome', $cliente)) {
            $this->setSobrenome(null);
        } else {
            $this->setSobrenome($cliente['sobrenome']);
        }
        if (!array_key_exists('genero', $cliente)) {
            $this->setGenero(null);
        } else {
            $this->setGenero($cliente['genero']);
        }
        if (!array_key_exists('cpf', $cliente)) {
            $this->setCPF(null);
        } else {
            $this->setCPF($cliente['cpf']);
        }
        if (!array_key_exists('rg', $cliente)) {
            $this->setRG(null);
        } else {
            $this->setRG($cliente['rg']);
        }
        if (!array_key_exists('im', $cliente)) {
            $this->setIM(null);
        } else {
            $this->setIM($cliente['im']);
        }
        if (!array_key_exists('email', $cliente)) {
            $this->setEmail(null);
        } else {
            $this->setEmail($cliente['email']);
        }
        if (!array_key_exists('dataaniversario', $cliente)) {
            $this->setDataAniversario(null);
        } else {
            $this->setDataAniversario($cliente['dataaniversario']);
        }
        if (!array_key_exists('fone1', $cliente)) {
            $this->setTelefone(null);
        } else {
            $this->setTelefone($cliente['fone1']);
        }
        if (!array_key_exists('slogan', $cliente)) {
            $this->setSlogan(null);
        } else {
            $this->setSlogan($cliente['slogan']);
        }
        if (!isset($cliente['status'])) {
            $this->setStatus(self::STATUS_INATIVO);
        } else {
            $this->setStatus($cliente['status']);
        }
        if (!array_key_exists('secreto', $cliente)) {
            $this->setSecreto(null);
        } else {
            $this->setSecreto($cliente['secreto']);
        }
        if (!array_key_exists('salt', $cliente)) {
            $this->setSalt(null);
        } else {
            $this->setSalt($cliente['salt']);
        }
        if (!array_key_exists('limitecompra', $cliente)) {
            $this->setLimiteCompra(null);
        } else {
            $this->setLimiteCompra($cliente['limitecompra']);
        }
        if (!array_key_exists('instagramurl', $cliente)) {
            $this->setInstagramURL(null);
        } else {
            $this->setInstagramURL($cliente['instagramurl']);
        }
        if (!array_key_exists('facebookurl', $cliente)) {
            $this->setFacebookURL(null);
        } else {
            $this->setFacebookURL($cliente['facebookurl']);
        }
        if (!array_key_exists('twitterurl', $cliente)) {
            $this->setTwitterURL(null);
        } else {
            $this->setTwitterURL($cliente['twitterurl']);
        }
        if (!array_key_exists('linkedinurl', $cliente)) {
            $this->setLinkedInURL(null);
        } else {
            $this->setLinkedInURL($cliente['linkedinurl']);
        }
        if (!array_key_exists('imagemurl', $cliente)) {
            $this->setImagemURL(null);
        } else {
            $this->setImagemURL($cliente['imagemurl']);
        }
        if (!array_key_exists('linguagem', $cliente)) {
            $this->setLinguagem(null);
        } else {
            $this->setLinguagem($cliente['linguagem']);
        }
        if (!isset($cliente['dataatualizacao'])) {
            $this->setDataAtualizacao(DB::now());
        } else {
            $this->setDataAtualizacao($cliente['dataatualizacao']);
        }
        if (!isset($cliente['datacadastro'])) {
            $this->setDataCadastro(DB::now());
        } else {
            $this->setDataCadastro($cliente['datacadastro']);
        }
        return $this;
    }

    /**
     * Get relative foto path or default foto
     * @param boolean $default If true return default image, otherwise check field
     * @param string  $default_name Default image name
     * @return string relative web path for cliente foto
     */
    public function makeImagemURL($default = false, $default_name = 'cliente.png')
    {
        $imagem_url = $this->getImagemURL();
        if ($default) {
            $imagem_url = null;
        }
        return get_image_url($imagem_url, 'cliente', $default_name);
    }

    /**
     * Obtém o nome completo da pessoa física ou o nome fantasia da empresa
     */
    public function getNomeCompleto()
    {
        if ($this->getTipo() == self::TIPO_JURIDICA) {
            return $this->getNome();
        }
        return trim($this->getNome() . ' ' . $this->getSobrenome());
    }

    /**
     * Obtém o nome completo da pessoa física ou o nome fantasia da empresa
     */
    public function setNomeCompleto($nome)
    {
        $pos = strpos($nome, ' ');
        if ($pos === false) {
            $this->setSobrenome(null);
            return $this->setNome($nome);
        }
        $this->setNome(substr($nome, 0, $pos));
        return $this->setSobrenome(substr($nome, $pos + 1, strlen($nome) - $pos - 1));
    }

    /**
     * Retorna a assinatura do cliente
     */
    public function getAssinatura()
    {
        $nomes = preg_split('/[\s,]+/', $this->getSobrenome());
        $sobrenome = $this->getSobrenome();
        foreach ($nomes as $nome) {
            if (in_array($nome, ['da', 'de', 'do', 'das', 'dos'])) {
                continue;
            }
            $sobrenome = $nome;
            break;
        }
        return trim($this->getNome().' '.$sobrenome);
    }

    private function gerarSenha()
    {
        $this->setSenha(sha1(utf8_decode(self::CHAVE_SECRETA . $this->getSenha())));
        return $this;
    }

    public function getGeneroName()
    {
        if ($this->getTipo() == self::TIPO_JURIDICA) {
            return 'Empresa';
        }
        return self::getGeneroOptions()[$this->getGenero()];
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $cliente = parent::publish();
        unset($cliente['senha']);
        if ($this->getTipo() == self::TIPO_FISICA) {
            $cliente['cpf'] = Mask::cpf($cliente['cpf']);
        } else {
            $cliente['cpf'] = Mask::cnpj($cliente['cpf']);
        }
        if (!is_null($this->getTelefone())) {
            $cliente['fone1'] = Mask::phone($this->getTelefone()->getNumero());
        } else {
            $cliente['fone1'] = null;
        }
        unset($cliente['secreto']);
        unset($cliente['salt']);
        $cliente['imagemurl'] = $this->makeImagemURL(false, null);
        return $cliente;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param self $original Original instance without modifications
     * @param boolean $localized Informs if fields are localized
     * @return self Self instance
     */
    public function filter($original, $localized = false)
    {
        $this->setID($original->getID());
        $this->setSecreto($original->getSecreto());
        $this->setSalt($original->getSalt());
        $this->setLimiteCompra(Filter::float($original->getLimiteCompra(), $localized));
        $this->setEmpresaID(Filter::number($original->getEmpresaID()));
        $this->setLogin(Filter::string($this->getLogin()));
        $this->setSenha(Filter::text($this->getSenha()));
        $this->setNome(Filter::string($this->getNome()));
        $this->setSobrenome(Filter::string($this->getSobrenome()));
        if ($this->getTipo() == self::TIPO_JURIDICA) {
            $this->setCPF(Filter::unmask($this->getCPF(), _p('Mascara', 'CNPJ')));
            $this->setGenero(self::GENERO_FEMININO);
        } else {
            $this->setCPF(Filter::unmask($this->getCPF(), _p('Mascara', 'CPF')));
        }
        $this->setRG(Filter::digits($this->getRG()));
        $this->setIM(Filter::digits($this->getIM()));
        $this->setEmail(Filter::string($this->getEmail()));
        $this->setDataAniversario(Filter::date($this->getDataAniversario()));
        $this->setSlogan(Filter::string($this->getSlogan()));
        $this->setInstagramURL(Filter::string($this->getInstagramURL()));
        $this->setFacebookURL(Filter::string($this->getFacebookURL()));
        $this->setTwitterURL(Filter::string($this->getTwitterURL()));
        $this->setLinkedInURL(Filter::string($this->getLinkedInURL()));
        $this->setLinguagem(Filter::string($this->getLinguagem()));

        $width = 256;
        if ($this->getTipo() == Cliente::TIPO_JURIDICA) {
            $width = 640;
        }
        $imagem_url = upload_image('raw_imagemurl', 'cliente', null, $width, 256, true);
        if (is_null($imagem_url) && trim($this->getImagemURL()) != '') {
            $this->setImagemURL($original->getImagemURL());
        } else {
            $this->setImagemURL($imagem_url);
        }
        return $this;
    }

    /**
     * Clean instance resources like images and docs
     * @param self $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
        if (!is_null($this->getImagemURL()) && $dependency->getImagemURL() != $this->getImagemURL()) {
            @unlink(get_image_path($this->getImagemURL(), 'cliente'));
        }
        $this->setImagemURL($dependency->getImagemURL());
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Cliente in array format
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function validate()
    {
        $errors = [];
        $prestador = Prestador::findByClienteID($this->getID());
        if (!Validator::checkInSet($this->getTipo(), self::getTipoOptions())) {
            $errors['tipo'] = _t('cliente.tipo_invalid');
        }
        if ($prestador->exists() && $this->getTipo() != self::TIPO_FISICA) {
            $errors['tipo'] = _t('cliente.employee_humman');
        }
        if (!Validator::checkUsername($this->getLogin(), true)) {
            $errors['login'] = _t('login_invalid');
        }
        if ($prestador->exists() && is_null($this->getLogin())) {
            $errors['login'] = _t('employee_need_login');
        }
        if (!Validator::checkPassword($this->getSenha(), $this->exists())) {
            $errors['senha'] = _t('senha_insecure');
        }
        if (!is_null($this->getSenha())) {
            $this->gerarSenha();
        }
        if (is_null($this->getNome())) {
            $errors['nome'] = _t('cliente.nome_cannot_empty');
        }
        if (strlen($this->getNome()) < 2) {
            $errors['nome'] = _t('cliente.nome_invalid');
        }
        if (!Validator::checkInSet($this->getGenero(), self::getGeneroOptions())) {
            $errors['genero'] = _t('cliente.genero_invalid');
        }
        if ($this->getTipo() == self::TIPO_FISICA) {
            if (!Validator::checkCPF($this->getCPF(), true)) {
                $errors['cpf'] = _t('cpf_invalid', _p('Titulo', 'CPF'));
            }
        } else {
            if (!Validator::checkCNPJ($this->getCPF(), true)) {
                $errors['cpf'] = _t('cpf_invalid', _p('Titulo', 'CNPJ'));
            }
        }
        if (!Validator::checkEmail($this->getEmail(), true)) {
            $errors['email'] = _t('email_invalid');
        }
        if (is_null($this->getCPF()) &&
            is_null($this->getTelefone()->getNumero()) &&
            is_null($this->getLogin()) &&
            is_null($this->getEmail())
        ) {
            $cpf_title = _p('Titulo', 'CPF');
            if ($this->getTipo() == self::TIPO_JURIDICA) {
                $cpf_title = _p('Titulo', 'CNPJ');
            }
            $errors['email'] = _t('cliente.none_key', $cpf_title);
        }
        if (!Validator::checkInSet($this->getStatus(), self::getStatusOptions())) {
            $errors['status'] = _t('cliente.status_invalid');
        }
        if (!is_null($this->getLimiteCompra()) && $this->getLimiteCompra() < 0) {
            $errors['limitecompra'] = _t('cliente.limitecompra_negative');
        }
        $this->setDataCadastro(DB::now());
        $this->setDataAtualizacao(DB::now());
        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
        $values = $this->toArray();
        if (is_null($this->getSenha())) {
            unset($values['senha']);
        }
        return $values;
    }

    /**
     * Check if password match
     * @param  string $password password to compare
     * @throws \MZ\Exception\ValidationException When password does't match
     */
    public function passwordMatch($password)
    {
        if ($password != $this->getSenha()) {
            throw new \MZ\Exception\ValidationException([
                'senha' => 'As senhas não são iguais',
                'confirmarsenha' => 'As senhas não são iguais'
            ]);
        }
    }

    /**
     * Translate SQL exception into application exception
     * @param \Exception $e exception to translate into a readable error
     * @return \MZ\Exception\ValidationException new exception translated
     */
    protected function translate($e)
    {
        if (contains(['Email', 'UNIQUE'], $e->getMessage())) {
            return new ValidationException([
                'email' => _t(
                    'cliente.email_used',
                    $this->getEmail()
                ),
            ]);
        }
        if (contains(['CPF', 'UNIQUE'], $e->getMessage())) {
            $cpf_title = _p('Titulo', 'CPF');
            if ($this->getTipo() == self::TIPO_JURIDICA) {
                $cpf_title = _p('Titulo', 'CNPJ');
            }
            return new ValidationException([
                'cpf' => _t(
                    'cliente.cpf_used',
                    $cpf_title
                ),
            ]);
        }
        if (contains(['Login', 'UNIQUE'], $e->getMessage())) {
            return new ValidationException([
                'login' => _t(
                    'cliente.login_used',
                    $this->getLogin()
                ),
            ]);
        }
        if (contains(['Secreto', 'UNIQUE'], $e->getMessage())) {
            return new ValidationException([
                'secreto' => _t(
                    'cliente.recover_code',
                    $this->getSecreto()
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Insert a new Cliente into the database and fill instance from database
     * @return self Self instance
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function insert()
    {
        $this->setID(null);
        $values = $this->validate();
        unset($values['id']);
        try {
            DB::beginTransaction();
            $id = DB::insertInto('Clientes')->values($values)->execute();
            if (!is_null($this->getTelefone()->getNumero())) {
                $this->getTelefone()->setPrincipal('Y');
                $this->getTelefone()->setClienteID($id);
                $this->getTelefone()->insert();
            }
            $this->setID($id);
            $this->loadByID();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Cliente with instance values into database for ID
     * @param array $only Save these fields only, when empty save all fields except id
     * @return int rows affected
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function update($only = [])
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new ValidationException(
                ['id' => _t('cliente.id_cannot_empty')]
            );
        }
        $values = DB::filterValues($values, $only, false);
        unset($values['datacadastro']);
        try {
            DB::beginTransaction();
            $affected = DB::update('Clientes')
                ->set($values)
                ->where(['id' => $this->getID()])
                ->execute();
            if (!is_null($this->getTelefone()->getNumero())) {
                $this->getTelefone()->setPrincipal('Y');
                $this->getTelefone()->setClienteID($this->getID());
                $this->getTelefone()->save();
            }
            $this->loadByID();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $this->translate($e);
        }
        return $affected;
    }

    /**
     * Delete this instance from database using ID
     * @return integer Number of rows deleted (Max 1)
     * @throws \MZ\Exception\ValidationException for invalid id
     */
    public function delete()
    {
        if (!$this->exists()) {
            throw new ValidationException(
                ['id' => _t('cliente.id_cannot_empty')]
            );
        }
        $result = DB::deleteFrom('Clientes')
            ->where('id', $this->getID())
            ->execute();
        return $result;
    }

    /**
     * Load one register for it self with a condition
     * @param array $condition Condition for searching the row
     * @param array $order associative field name -> [-1, 1]
     * @return self Self instance filled or empty
     */
    public function load($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        $this->fromArray($row);
        $this->loadTelefone();
        return $this;
    }

    /**
     * Load into this object from database using, Fone
     * @param  string $fone telefone to find Cliente
     * @return Cliente Self filled instance or empty when not found
     */
    public function loadByFone()
    {
        return $this->load([
            'fone' => strval($this->getTelefone()->getNumero()),
        ]);
    }

    /**
     * Load into this object from database using, Email
     * @return self Self filled instance or empty when not found
     */
    public function loadByEmail()
    {
        return $this->load([
            'email' => strval($this->getEmail()),
        ]);
    }

    /**
     * Load into this object from database using, CPF
     * @return self Self filled instance or empty when not found
     */
    public function loadByCPF()
    {
        return $this->load([
            'cpf' => strval($this->getCPF()),
        ]);
    }

    /**
     * Load into this object from database using, Login
     * @return self Self filled instance or empty when not found
     */
    public function loadByLogin()
    {
        return $this->load([
            'login' => strval($this->getLogin()),
        ]);
    }

    /**
     * Load into this object from database using, Secreto
     * @return self Self filled instance or empty when not found
     */
    public function loadBySecreto()
    {
        return $this->load([
            'secreto' => strval($this->getSecreto()),
        ]);
    }

    /**
     * Load main phone from database
     * @return self Self instance
     */
    public function loadTelefone()
    {
        return $this->setTelefone(Telefone::find(['clienteid' => $this->getID(), 'principal' => 'Y']));
    }

    /**
     * Informa se esse cliente faz parte da empresa informada
     * @return self The object fetched from database
     */
    public function findEmpresaID()
    {
        if (is_null($this->getEmpresaID())) {
            return new self();
        }
        return self::findByID($this->getEmpresaID());
    }

    /**
     * Gets textual and translated Tipo for Cliente
     * @param int $index choose option from index
     * @return string[]|string A associative key -> translated representative text or text for index
     */
    public static function getTipoOptions($index = null)
    {
        $options = [
            self::TIPO_FISICA => _t('cliente.tipo_fisica'),
            self::TIPO_JURIDICA => _t('cliente.tipo_juridica'),
        ];
        if (!is_null($index)) {
            return $options[$index];
        }
        return $options;
    }

    /**
     * Gets textual and translated Genero for Cliente
     * @param int $index choose option from index
     * @return string[]|string A associative key -> translated representative text or text for index
     */
    public static function getGeneroOptions($index = null)
    {
        $options = [
            self::GENERO_MASCULINO => _t('cliente.genero_masculino'),
            self::GENERO_FEMININO => _t('cliente.genero_feminino'),
        ];
        if (!is_null($index)) {
            return $options[$index];
        }
        return $options;
    }

    /**
     * Gets textual and translated Status for Cliente
     * @param int $index choose option from index
     * @return string[]|string A associative key -> translated representative text or text for index
     */
    public static function getStatusOptions($index = null)
    {
        $options = [
            self::STATUS_ATIVO => _t('cliente.status_ativo'),
            self::STATUS_INATIVO => _t('cliente.status_inativo'),
            self::STATUS_BLOQUEADO => _t('cliente.status_bloqueado'),
        ];
        if (!is_null($index)) {
            return $options[$index];
        }
        return $options;
    }

    /**
     * Get allowed keys array
     * @return array allowed keys array
     */
    private static function getAllowedKeys()
    {
        $cliente = new self();
        $allowed = Filter::concatKeys('c.', $cliente->toArray());
        return $allowed;
    }

    /**
     * Build search for phone number
     * @param string $fone masked phone number
     * @return string string like for search
     */
    public static function buildFoneSearch($fone)
    {
        $fone = Filter::digits($fone);
        $ddd = substr($fone, 0, 2).'%';
        if (strlen($fone) == 10) {
            $fone = $ddd . substr($fone, 2, 8);
        } elseif (strlen($fone) <= 9) {
            $fone = '%' . $fone;
        } else {
            $fone = $ddd . substr($fone, 3);
        }
        return $fone;
    }

    /**
     * Filter order array
     * @param mixed $order order string or array to parse and filter allowed
     * @return array allowed associative order
     */
    private static function filterOrder($order)
    {
        $allowed = self::getAllowedKeys();
        return Filter::orderBy($order, $allowed, 'c.');
    }

    /**
     * Filter condition array with allowed fields
     * @param array $condition condition to filter rows
     * @return array allowed condition
     */
    protected static function filterCondition($condition)
    {
        $allowed = self::getAllowedKeys();
        if (isset($condition['fone'])) {
            $fone = $condition['fone'];
            $fone = self::buildFoneSearch($fone);
            $field = '(t.numero LIKE ?)';
            $condition[$field] = $fone;
            $allowed[$field] = true;
            unset($condition['fone']);
        }
        if (isset($condition['apartir_cadastro'])) {
            $field = 'c.datacadastro >= ?';
            $condition[$field] = Filter::datetime($condition['apartir_cadastro'], '00:00:00');
            $allowed[$field] = true;
            unset($condition['apartir_cadastro']);
        }
        if (isset($condition['ate_cadastro'])) {
            $field = 'c.datacadastro <= ?';
            $condition[$field] = Filter::datetime($condition['ate_cadastro'], '23:59:59');
            $allowed[$field] = true;
            unset($condition['ate_cadastro']);
        }
        if (isset($condition['aniversariante'])) {
            $field = DB::strftime('2000-%m-%d', 'c.dataaniversario');
            $condition[$field] = DB::date(date('2000-m-d'));
            $allowed[$field] = true;
            unset($condition['aniversariante']);
        }
        return Filter::keys($condition, $allowed, 'c.');
    }

    /**
     * Fetch data from database with a condition
     * @param array $condition condition to filter rows
     * @param array $order order rows
     * @return SelectQuery query object with condition statement
     */
    protected static function query($condition = [], $order = [])
    {
        $order = Filter::order($order);
        if (isset($condition['comprador'])) {
            $query = DB::from('Pedidos p')
                ->select(null)
                ->select('COUNT(DISTINCT p.id) as pedidos')
                ->select('SUM(p.total) as total')
                ->leftJoin('Clientes c ON c.id = p.clienteid')
                ->leftJoin('Telefones t ON t.clienteid = c.id AND t.principal = ?', 'Y')
                ->where('p.cancelado', 'N')
                ->where('p.estado', Pedido::ESTADO_FINALIZADO)
                ->where('NOT p.clienteid IS NULL')
                ->orderBy('total DESC')
                ->groupBy('p.clienteid');
            if (isset($condition['apartir_compra'])) {
                $query = $query->where(
                    'p.datacriacao >= ?',
                    Filter::datetime($condition['apartir_compra'], '00:00:00')
                );
            }
            if (isset($condition['ate_compra'])) {
                $query = $query->where('p.datacriacao <= ?', Filter::datetime($condition['ate_compra'], '23:59:59'));
            }
        } else {
            $query = DB::from('Clientes c')
                ->leftJoin('Telefones t ON t.clienteid = c.id AND t.principal = ?', 'Y');
        }
        $query = $query->select('c.*');
        if (isset($condition['search'])) {
            $search = trim($condition['search']);
            if (Validator::checkEmail($search)) {
                $query = $query->where('c.email', $search);
            } elseif (Validator::checkCPF($search) || Validator::checkCNPJ($search)) {
                $query = $query->where('c.cpf', Filter::digits($search));
            } elseif (check_fone($search, true)) {
                $condition['fone'] = $search;
            } else {
                $query = DB::buildSearch(
                    $search,
                    DB::concat([
                        'c.nome',
                        '" "',
                        'COALESCE(c.sobrenome, "")'
                    ]),
                    $query
                );
            }
            unset($condition['search']);
        }
        if (isset($condition['fone'])) {
            $fone = $condition['fone'];
            $fone = self::buildFoneSearch($fone);
            $query = $query->orderBy('(t.numero LIKE ?) DESC', $fone);
        }
        $condition = self::filterCondition($condition);
        $query = DB::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy(DB::concat(['c.nome', '" "', 'COALESCE(c.sobrenome, "")']).' ASC');
        $query = $query->orderBy('c.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param array $condition Condition for searching the row
     * @param array $order order rows
     * @return self A filled Cliente or empty instance
     */
    public static function find($condition, $order = [])
    {
        $result = new self();
        return $result->load($condition, $order);
    }

    /**
     * Search one register with a condition
     * @param array $condition Condition for searching the row
     * @param array $order order rows
     * @return self A filled Cliente or empty instance
     * @throws \Exception when register has not found
     */
    public static function findOrFail($condition, $order = [])
    {
        $result = self::find($condition, $order);
        if (!$result->exists()) {
            throw new \Exception(_t('cliente.not_found'), 404);
        }
        return $result;
    }

    /**
     * Find this object on database using, Fone
     * @param string $fone telefone to find Cliente
     * @return self A filled instance or empty when not found
     */
    public static function findByFone($fone)
    {
        $result = new self();
        $result->getTelefone()->setNumero($fone);
        return $result->loadByFone();
    }

    /**
     * Find this object on database using, Email
     * @param string $email e-mail to find Cliente
     * @return self A filled instance or empty when not found
     */
    public static function findByEmail($email)
    {
        $result = new self();
        $result->setEmail($email);
        return $result->loadByEmail();
    }

    /**
     * Find this object on database using, CPF
     * @param string $cpf cpf to find Cliente
     * @return self A filled instance or empty when not found
     */
    public static function findByCPF($cpf)
    {
        $result = new self();
        $result->setCPF($cpf);
        return $result->loadByCPF();
    }

    /**
     * Find this object on database using, Login
     * @param string $login login to find Cliente
     * @return self A filled instance or empty when not found
     */
    public static function findByLogin($login)
    {
        $result = new self();
        $result->setLogin($login);
        return $result->loadByLogin();
    }

    /**
     * Find this object on database using, Secreto
     * @param string $secreto código de recuperação to find Cliente
     * @return self A filled instance or empty when not found
     */
    public static function findBySecreto($secreto)
    {
        $result = new self();
        $result->setSecreto($secreto);
        return $result->loadBySecreto();
    }

    /**
     * Find this object on database using login and password
     * @param  string $login email, CPF, CNPJ, phone or username to find Cliente
     * @param  string $senha password to check
     * @return Cliente A filled instance or empty when not found
     */
    public static function findByLoginSenha($login, $senha)
    {
        if (strpos($login, '@') !== false) {
            $field = 'email';
        } elseif (Validator::checkCPF($login) || Validator::checkCNPJ($login)) {
            $field = 'cpf';
            $login = Filter::digits($login);
        } elseif (Validator::checkPhone($login)) {
            $field = 'fone';
            $login = Filter::digits($login);
        } else {
            $field = 'login';
        }
        $cliente = self::find([
            $field => strval($login),
        ]);
        $hash = $cliente->getSenha();
        $cliente->setSenha($senha);
        $cliente->gerarSenha();
        if ($hash == $cliente->getSenha() && strval($login) != '') {
            return $cliente;
        }
        return new Cliente();
    }

    /**
     * Find all Cliente
     * @param array  $condition Condition to get all Cliente
     * @param array  $order     Order Cliente
     * @param int    $limit     Limit data into row count
     * @param int    $offset    Start offset to get rows
     * @return self[] List of all rows instanced as Cliente
     */
    public static function findAll($condition = [], $order = [], $limit = null, $offset = null)
    {
        $query = self::query($condition, $order);
        if (!is_null($limit)) {
            $query = $query->limit($limit);
        }
        if (!is_null($offset)) {
            $query = $query->offset($offset);
        }
        $rows = $query->fetchAll();
        $result = [];
        foreach ($rows as $row) {
            $result[] = new self($row);
        }
        return $result;
    }

    /**
     * Find all Cliente
     * @param  array  $condition Condition to get all Cliente
     * @param  array  $order     Order Cliente
     * @param  int    $limit     Limit data into row count
     * @param  int    $offset    Start offset to get rows
     * @return array  List of all rows
     */
    public static function rawFindAll($condition = [], $order = [], $limit = null, $offset = null)
    {
        $query = self::query($condition, $order);
        if (!is_null($limit)) {
            $query = $query->limit($limit);
        }
        if (!is_null($offset)) {
            $query = $query->offset($offset);
        }
        return $query->fetchAll();
    }

    /**
     * Count all rows from database with matched condition critery
     * @param array $condition condition to filter rows
     * @return integer Quantity of rows
     */
    public static function count($condition = [])
    {
        $query = self::query($condition);
        return $query->count();
    }
}
