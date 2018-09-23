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

use MZ\Database\SyncModel;
use MZ\Database\DB;
use MZ\Util\Filter;
use MZ\Util\Validator;
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
     * @return mixed ID of Integracao
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param  mixed $id new value for ID
     * @return Integracao Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Nome do módulo de integração
     * @return mixed Nome of Integracao
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set Nome value to new on param
     * @param  mixed $nome new value for Nome
     * @return Integracao Self instance
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * Nome da URL de acesso
     * @return mixed URL of Integracao
     */
    public function getAcessoURL()
    {
        return $this->acesso_url;
    }

    /**
     * Set AcessoURL value to new on param
     * @param  mixed $acesso_url new value for AcessoURL
     * @return Integracao Self instance
     */
    public function setAcessoURL($acesso_url)
    {
        $this->acesso_url = $acesso_url;
        return $this;
    }

    /**
     * Descrição do módulo integrador
     * @return mixed Descrição of Integracao
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Set Descricao value to new on param
     * @param  mixed $descricao new value for Descricao
     * @return Integracao Self instance
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * Nome do ícone do módulo integrador
     * @return mixed Ícone of Integracao
     */
    public function getIconeURL()
    {
        return $this->icone_url;
    }

    /**
     * Set IconeURL value to new on param
     * @param  mixed $icone_url new value for IconeURL
     * @return Integracao Self instance
     */
    public function setIconeURL($icone_url)
    {
        $this->icone_url = $icone_url;
        return $this;
    }

    /**
     * Informa de o módulo de integração está habilitado
     * @return mixed Habilitado of Integracao
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
     * @param  mixed $ativo new value for Ativo
     * @return Integracao Self instance
     */
    public function setAtivo($ativo)
    {
        $this->ativo = $ativo;
        return $this;
    }

    /**
     * Token de acesso à API de sincronização
     * @return mixed Token of Integracao
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set Token value to new on param
     * @param  mixed $token new value for Token
     * @return Integracao Self instance
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * Chave secreta para acesso à API
     * @return mixed Chave secreta of Integracao
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * Set Secret value to new on param
     * @param  mixed $secret new value for Secret
     * @return Integracao Self instance
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;
        return $this;
    }

    /**
     * Data de atualização dos dados do módulo de integração
     * @return mixed Data de atualização of Integracao
     */
    public function getDataAtualizacao()
    {
        return $this->data_atualizacao;
    }

    /**
     * Set DataAtualizacao value to new on param
     * @param  mixed $data_atualizacao new value for DataAtualizacao
     * @return Integracao Self instance
     */
    public function setDataAtualizacao($data_atualizacao)
    {
        $this->data_atualizacao = $data_atualizacao;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param  boolean $recursive Allow rescursive conversion of fields
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
     * @param  mixed $integracao Associated key -> value to assign into this instance
     * @return Integracao Self instance
     */
    public function fromArray($integracao = [])
    {
        if ($integracao instanceof Integracao) {
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
     * @return string relative web path for integração ícone
     */
    public function makeIconeURL($default = false)
    {
        $icone_url = $this->getIconeURL();
        if ($default) {
            $icone_url = null;
        }
        return get_image_url($icone_url, 'integracao', 'integracao.png');
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
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $integracao = parent::publish();
        $integracao['iconeurl'] = $this->makeIconeURL();
        return $integracao;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param Integracao $original Original instance without modifications
     */
    public function filter($original, $localized = false)
    {
        $this->setID($original->getID());
        $this->setNome($original->getNome());
        $this->setAcessoURL($original->getAcessoURL());
        $this->setDescricao($original->getDescricao());
        $this->setIconeURL($original->getIconeURL());
        $this->setToken(Filter::string($this->getToken()));
        $this->setSecret(Filter::string($this->getSecret()));
        $this->setDataAtualizacao(DB::now());
    }

    /**
     * Clean instance resources like images and docs
     * @param  Integracao $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
        if (!is_null($this->getIconeURL()) && $dependency->getIconeURL() != $this->getIconeURL()) {
            unlink(get_image_path($this->getIconeURL(), 'integracao'));
        }
        $this->setIconeURL($dependency->getIconeURL());
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Integracao in array format
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getNome())) {
            $errors['nome'] = 'O Nome não pode ser vazio';
        }
        if (is_null($this->getAcessoURL())) {
            $errors['acessourl'] = 'A URL não pode ser vazia';
        }
        if (is_null($this->getAtivo())) {
            $errors['ativo'] = 'O Habilitado não pode ser vazio';
        }
        if (is_null($this->getDataAtualizacao())) {
            $errors['dataatualizacao'] = 'A Data de atualização não pode ser vazia';
        }
        if (!empty($errors)) {
            throw new \MZ\Exception\ValidationException($errors);
        }
        return $this->toArray();
    }

    /**
     * Translate SQL exception into application exception
     * @param  \Exception $e exception to translate into a readable error
     * @return \MZ\Exception\ValidationException new exception translated
     */
    protected function translate($e)
    {
        if (contains(['Nome', 'UNIQUE'], $e->getMessage())) {
            return new \MZ\Exception\ValidationException([
                'nome' => vsprintf(
                    'O Nome "%s" já está cadastrado',
                    [$this->getNome()]
                ),
            ]);
        }
        if (contains(['AcessoURL', 'UNIQUE'], $e->getMessage())) {
            return new \MZ\Exception\ValidationException([
                'acessourl' => vsprintf(
                    'A URL "%s" já está cadastrada',
                    [$this->getAcessoURL()]
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Insert a new Integração into the database and fill instance from database
     * @return Integracao Self instance
     */
    public function insert()
    {
        $this->setID(null);
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = DB::insertInto('Integracoes')->values($values)->execute();
            $this->setID($id);
            $this->loadByID();
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Integração with instance values into database for ID
     * @param  array $only Save these fields only, when empty save all fields except id
     * @return Integracao Self instance
     */
    public function update($only = [])
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador da integração não foi informado');
        }
        $values = DB::filterValues($values, $only, false);
        try {
            DB::update('Integracoes')
                ->set($values)
                ->where('id', $this->getID())
                ->execute();
            $this->loadByID();
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Delete this instance from database using ID
     * @return integer Number of rows deleted (Max 1)
     */
    public function delete()
    {
        if (!$this->exists()) {
            throw new \Exception('O identificador da integração não foi informado');
        }
        $result = DB::deleteFrom('Integracoes')
            ->where('id', $this->getID())
            ->execute();
        return $result;
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
     * Load one register for it self with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order associative field name -> [-1, 1]
     * @return Integracao Self instance filled or empty
     */
    public function load($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return $this->fromArray($row);
    }

    /**
     * Load into this object from database using, Nome
     * @param  string $nome nome to find Integração
     * @return Integracao Self filled instance or empty when not found
     */
    public function loadByNome($nome)
    {
        return $this->load([
            'nome' => strval($nome),
        ]);
    }

    /**
     * Load into this object from database using, AcessoURL
     * @param  string $acesso_url url to find Integração
     * @return Integracao Self filled instance or empty when not found
     */
    public function loadByAcessoURL($acesso_url)
    {
        return $this->load([
            'acessourl' => strval($acesso_url),
        ]);
    }

    /**
     * Get allowed keys array
     * @return array allowed keys array
     */
    private static function getAllowedKeys()
    {
        $integracao = new Integracao();
        $allowed = Filter::concatKeys('i.', $integracao->toArray());
        return $allowed;
    }

    /**
     * Filter order array
     * @param  mixed $order order string or array to parse and filter allowed
     * @return array allowed associative order
     */
    private static function filterOrder($order)
    {
        $allowed = self::getAllowedKeys();
        return Filter::orderBy($order, $allowed, 'i.');
    }

    /**
     * Filter condition array with allowed fields
     * @param  array $condition condition to filter rows
     * @return array allowed condition
     */
    private static function filterCondition($condition)
    {
        $allowed = self::getAllowedKeys();
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
     * @param  array $condition condition to filter rows
     * @param  array $order order rows
     * @return SelectQuery query object with condition statement
     */
    private static function query($condition = [], $order = [])
    {
        $query = DB::from('Integracoes i');
        $condition = self::filterCondition($condition);
        $query = DB::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('i.nome ASC');
        $query = $query->orderBy('i.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order order rows
     * @return Integracao A filled Integração or empty instance
     */
    public static function find($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return new Integracao($row);
    }

    /**
     * Find this object on database using, Nome
     * @param  string $nome nome to find Integração
     * @return Integracao A filled instance or empty when not found
     */
    public static function findByNome($nome)
    {
        $result = new self();
        return $result->loadByNome($nome);
    }

    /**
     * Find this object on database using, AcessoURL
     * @param  string $acesso_url url to find Integração
     * @return Integracao A filled instance or empty when not found
     */
    public static function findByAcessoURL($acesso_url)
    {
        $result = new self();
        return $result->loadByAcessoURL($acesso_url);
    }

    /**
     * Fetch all rows from database with matched condition critery
     * @param  array $condition condition to filter rows
     * @param  integer $limit number of rows to get, null for all
     * @param  integer $offset start index to get rows, null for begining
     * @return array All rows instanced and filled
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
            $result[] = new Integracao($row);
        }
        return $result;
    }

    /**
     * Count all rows from database with matched condition critery
     * @param  array $condition condition to filter rows
     * @return integer Quantity of rows
     */
    public static function count($condition = [])
    {
        $query = self::query($condition);
        return $query->count();
    }
}
