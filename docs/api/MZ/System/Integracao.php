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

namespace MZ\System;

use MZ\Util\Mask;
use MZ\Util\Filter;
use MZ\Util\Validator;
use MZ\Database\DB;
use MZ\Database\SyncModel;
use MZ\Exception\ValidationException;
use MZ\Integrator\IFood;
use MZ\Integrator\Kromax;

/**
 * Informa quais integrações estão disponíveis
 */
class Integracao extends SyncModel
{

    /**
     * Identificador da integração
     */
    private $id;
    /**
     * Nome do módulo de integração
     */
    private $nome;
    /**
     * Nome da URL de acesso
     */
    private $acesso_url;
    /**
     * Descrição do módulo integrador
     */
    private $descricao;
    /**
     * Nome do ícone do módulo integrador
     */
    private $icone_url;
    /**
     * Informa de o módulo de integração está habilitado
     */
    private $ativo;
    /**
     * Token de acesso à API de sincronização
     */
    private $token;
    /**
     * Chave secreta para acesso à API
     */
    private $secret;
    /**
     * Data de atualização dos dados do módulo de integração
     */
    private $data_atualizacao;

    /**
     * Constructor for a new empty instance of Integracao
     * @param array $integracao All field and values to fill the instance
     */
    public function __construct($integracao = [])
    {
        parent::__construct($integracao);
    }

    /**
     * Identificador da integração
     * @return int id of Integração
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param int $id Set id for Integração
     * @return self Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Nome do módulo de integração
     * @return string nome of Integração
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set Nome value to new on param
     * @param string $nome Set nome for Integração
     * @return self Self instance
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * Nome da URL de acesso
     * @return string url of Integração
     */
    public function getAcessoURL()
    {
        return $this->acesso_url;
    }

    /**
     * Set AcessoURL value to new on param
     * @param string $acesso_url Set url for Integração
     * @return self Self instance
     */
    public function setAcessoURL($acesso_url)
    {
        $this->acesso_url = $acesso_url;
        return $this;
    }

    /**
     * Descrição do módulo integrador
     * @return string descrição of Integração
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Set Descricao value to new on param
     * @param string $descricao Set descrição for Integração
     * @return self Self instance
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * Nome do ícone do módulo integrador
     * @return string ícone of Integração
     */
    public function getIconeURL()
    {
        return $this->icone_url;
    }

    /**
     * Set IconeURL value to new on param
     * @param string $icone_url Set ícone for Integração
     * @return self Self instance
     */
    public function setIconeURL($icone_url)
    {
        $this->icone_url = $icone_url;
        return $this;
    }

    /**
     * Informa de o módulo de integração está habilitado
     * @return string habilitado of Integração
     */
    public function getAtivo()
    {
        return $this->ativo;
    }

    /**
     * Informa de o módulo de integração está habilitado
     * @return boolean Check if o of Ativo is selected or checked
     */
    public function isAtivo()
    {
        return $this->ativo == 'Y';
    }

    /**
     * Set Ativo value to new on param
     * @param string $ativo Set habilitado for Integração
     * @return self Self instance
     */
    public function setAtivo($ativo)
    {
        $this->ativo = $ativo;
        return $this;
    }

    /**
     * Token de acesso à API de sincronização
     * @return string token of Integração
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set Token value to new on param
     * @param string $token Set token for Integração
     * @return self Self instance
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * Chave secreta para acesso à API
     * @return string chave secreta of Integração
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * Set Secret value to new on param
     * @param string $secret Set chave secreta for Integração
     * @return self Self instance
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;
        return $this;
    }

    /**
     * Data de atualização dos dados do módulo de integração
     * @return string data de atualização of Integração
     */
    public function getDataAtualizacao()
    {
        return $this->data_atualizacao;
    }

    /**
     * Set DataAtualizacao value to new on param
     * @param string $data_atualizacao Set data de atualização for Integração
     * @return self Self instance
     */
    public function setDataAtualizacao($data_atualizacao)
    {
        $this->data_atualizacao = $data_atualizacao;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $integracao = parent::toArray($recursive);
        $integracao['id'] = $this->getID();
        $integracao['nome'] = $this->getNome();
        $integracao['acessourl'] = $this->getAcessoURL();
        $integracao['descricao'] = $this->getDescricao();
        $integracao['iconeurl'] = $this->getIconeURL();
        $integracao['ativo'] = $this->getAtivo();
        $integracao['token'] = $this->getToken();
        $integracao['secret'] = $this->getSecret();
        $integracao['dataatualizacao'] = $this->getDataAtualizacao();
        return $integracao;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param mixed $integracao Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($integracao = [])
    {
        if ($integracao instanceof self) {
            $integracao = $integracao->toArray();
        } elseif (!is_array($integracao)) {
            $integracao = [];
        }
        parent::fromArray($integracao);
        if (!isset($integracao['id'])) {
            $this->setID(null);
        } else {
            $this->setID($integracao['id']);
        }
        if (!isset($integracao['nome'])) {
            $this->setNome(null);
        } else {
            $this->setNome($integracao['nome']);
        }
        if (!isset($integracao['acessourl'])) {
            $this->setAcessoURL(null);
        } else {
            $this->setAcessoURL($integracao['acessourl']);
        }
        if (!array_key_exists('descricao', $integracao)) {
            $this->setDescricao(null);
        } else {
            $this->setDescricao($integracao['descricao']);
        }
        if (!array_key_exists('iconeurl', $integracao)) {
            $this->setIconeURL(null);
        } else {
            $this->setIconeURL($integracao['iconeurl']);
        }
        if (!isset($integracao['ativo'])) {
            $this->setAtivo('N');
        } else {
            $this->setAtivo($integracao['ativo']);
        }
        if (!array_key_exists('token', $integracao)) {
            $this->setToken(null);
        } else {
            $this->setToken($integracao['token']);
        }
        if (!array_key_exists('secret', $integracao)) {
            $this->setSecret(null);
        } else {
            $this->setSecret($integracao['secret']);
        }
        if (!isset($integracao['dataatualizacao'])) {
            $this->setDataAtualizacao(DB::now());
        } else {
            $this->setDataAtualizacao($integracao['dataatualizacao']);
        }
        return $this;
    }

    /**
     * Get relative ícone path or default ícone
     * @param boolean $default If true return default image, otherwise check field
     * @param string  $default_name Default image name
     * @return string relative web path for integração ícone
     */
    public function makeIconeURL($default = false, $default_name = 'integracao.png')
    {
        $icone_url = $this->getIconeURL();
        if ($default) {
            $icone_url = null;
        }
        return get_image_url($icone_url, 'integracao', $default_name);
    }

    /**
     * Get relative data filename path or default data filename
     * @param boolean $default If true return default data filename, otherwise check field
     * @return string relative web path for integração data filename
     */
    public function makeDataURL($default = false)
    {
        $data_url = $this->getAcessoURL() . '.json';
        if ($default) {
            $data_url = null;
        }
        return get_document_url($data_url, 'integracao', $this->getAcessoURL() . '.json');
    }

    public function getTask()
    {
        switch ($this->getAcessoURL()) {
            case IFood::NAME:
                $ifood = new IFood();
                $ifood->setData($this);
                return $ifood;
            case Kromax::NAME:
                $kromax = new Kromax();
                $kromax->setData($this);
                return $kromax;
            default:
                throw new \Exception(
                    sprintf('Integração com "%s" não implementada', $this->getNome()),
                    404
                );
        }
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @param \MZ\Provider\Prestador $requester user that request to view this fields
     * @return array All public field and values into array format
     */
    public function publish($requester)
    {
        $integracao = parent::publish($requester);
        $integracao['iconeurl'] = $this->makeIconeURL(false, null);
        return $integracao;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param self $original Original instance without modifications
     * @param \MZ\Provider\Prestador $updater user that want to update this object
     * @param boolean $localized Informs if fields are localized
     * @return self Self instance
     */
    public function filter($original, $updater, $localized = false)
    {
        $this->setID($original->getID());
        $this->setNome(Filter::string($original->getNome()));
        $this->setAcessoURL(Filter::string($original->getAcessoURL()));
        $this->setDescricao(Filter::string($original->getDescricao()));
        $this->setIconeURL($original->getIconeURL());
        $this->setToken(Filter::string($this->getToken()));
        $this->setSecret(Filter::string($this->getSecret()));
        $this->setDataAtualizacao(DB::now());
        return $this;
    }

    /**
     * Clean instance resources like images and docs
     * @param self $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
        if (!is_null($this->getIconeURL()) && $dependency->getIconeURL() != $this->getIconeURL()) {
            @unlink(get_image_path($this->getIconeURL(), 'integracao'));
        }
        $this->setIconeURL($dependency->getIconeURL());
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Integracao in array format
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getNome())) {
            $errors['nome'] = _t('integracao.nome_cannot_empty');
        }
        if (is_null($this->getAcessoURL())) {
            $errors['acessourl'] = _t('integracao.acesso_url_cannot_empty');
        }
        if (!Validator::checkBoolean($this->getAtivo())) {
            $errors['ativo'] = _t('integracao.ativo_invalid');
        }
        $this->setDataAtualizacao(DB::now());
        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
        return $this->toArray();
    }

    /**
     * Translate SQL exception into application exception
     * @param \Exception $e exception to translate into a readable error
     * @return \MZ\Exception\ValidationException new exception translated
     */
    protected function translate($e)
    {
        if (contains(['Nome', 'UNIQUE'], $e->getMessage())) {
            return new ValidationException([
                'nome' => _t(
                    'integracao.nome_used',
                    $this->getNome()
                ),
            ]);
        }
        if (contains(['AcessoURL', 'UNIQUE'], $e->getMessage())) {
            return new ValidationException([
                'acessourl' => _t(
                    'integracao.acesso_url_used',
                    $this->getAcessoURL()
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Decodifica os dados e retorna o array
     * @return array array contendo as integrações
     */
    public function read()
    {

        $filename = app()->getPath('public') . $this->makeDataURL();
        if (!file_exists($filename)) {
            return [];
        }
        return json_decode(file_get_contents($filename), true);
    }

    /**
     * Codifica os dados e salvar no arquivo
     * @return Integracao Self instance
     */
    public function write($data)
    {

        $filename = app()->getPath('public') . $this->makeDataURL();
        xmkdir(dirname($filename), 0711);
        if (file_put_contents($filename, json_encode($data)) === false) {
            throw new \Exception(
                sprintf(
                    'Falha ao escrever o arquivo "%s" com os dados da integração',
                    $filename
                ),
                500
            );
        }
        xchmod($filename, 0644);
    }

    /**
     * Load into this object from database using, Nome
     * @return self Self filled instance or empty when not found
     */
    public function loadByNome()
    {
        return $this->load([
            'nome' => strval($this->getNome()),
        ]);
    }

    /**
     * Load into this object from database using, AcessoURL
     * @return self Self filled instance or empty when not found
     */
    public function loadByAcessoURL()
    {
        return $this->load([
            'acessourl' => strval($this->getAcessoURL()),
        ]);
    }

    /**
     * Filter condition array with allowed fields
     * @param array $condition condition to filter rows
     * @return array allowed condition
     */
    protected function filterCondition($condition)
    {
        $allowed = $this->getAllowedKeys();
        if (isset($condition['search'])) {
            $search = $condition['search'];
            $field = '(i.nome LIKE ? OR i.descricao LIKE ?)';
            $condition[$field] = ['%'.$search.'%', '%'.$search.'%'];
            $allowed[$field] = true;
            unset($condition['search']);
        }
        return Filter::keys($condition, $allowed, 'i.');
    }

    /**
     * Fetch data from database with a condition
     * @param array $condition condition to filter rows
     * @param array $order order rows
     * @return SelectQuery query object with condition statement
     */
    protected function query($condition = [], $order = [])
    {
        $query = DB::from('Integracoes i');
        $condition = $this->filterCondition($condition);
        $query = DB::buildOrderBy($query, $this->filterOrder($order));
        $query = $query->orderBy('i.nome ASC');
        $query = $query->orderBy('i.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Find this object on database using, Nome
     * @param string $nome nome to find Integração
     * @return self A filled instance or empty when not found
     */
    public static function findByNome($nome)
    {
        $result = new self();
        $result->setNome($nome);
        return $result->loadByNome();
    }

    /**
     * Find this object on database using, AcessoURL
     * @param string $acesso_url url to find Integração
     * @return self A filled instance or empty when not found
     */
    public static function findByAcessoURL($acesso_url)
    {
        $result = new self();
        $result->setAcessoURL($acesso_url);
        return $result->loadByAcessoURL();
    }
}
