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
namespace MZ\Employee;

use MZ\Database\Model;
use MZ\Database\DB;
use MZ\Util\Filter;
use MZ\Util\Validator;
use MZ\Account\Cliente;
use MZ\System\Permissao;

/**
 * Funcionário que trabalha na empresa e possui uma determinada função
 */
class Funcionario extends Model
{

    /**
     * Código do funcionário
     */
    private $id;
    /**
     * Função do funcionário na empresa
     */
    private $funcao_id;
    /**
     * Cliente que representa esse funcionário, único no cadastro de
     * funcionários
     */
    private $cliente_id;
    /**
     * Código de barras utilizado pelo funcionário para autorizar uma operação
     * no sistema
     */
    private $codigo_barras;
    /**
     * Porcentagem cobrada pelo funcionário ao cliente, Ex.: Comissão de 10%
     */
    private $porcentagem;
    /**
     * Código da linguagem utilizada pelo funcionário para visualizar o
     * programa e o site
     */
    private $linguagem_id;
    /**
     * Define a distribuição da porcentagem pela parcela de pontos
     */
    private $pontuacao;
    /**
     * Informa se o funcionário está ativo na empresa
     */
    private $ativo;
    /**
     * Data de saída do funcionário, informado apenas quando ativo for não
     */
    private $data_saida;
    /**
     * Data em que o funcionário foi cadastrado no sistema
     */
    private $data_cadastro;

    /**
     * Constructor for a new empty instance of Funcionario
     * @param array $funcionario All field and values to fill the instance
     */
    public function __construct($funcionario = [])
    {
        parent::__construct($funcionario);
    }

    /**
     * Código do funcionário
     * @return mixed Código of Funcionario
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param  mixed $id new value for ID
     * @return Funcionario Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Função do funcionário na empresa
     * @return mixed Função of Funcionario
     */
    public function getFuncaoID()
    {
        return $this->funcao_id;
    }

    /**
     * Set FuncaoID value to new on param
     * @param  mixed $funcao_id new value for FuncaoID
     * @return Funcionario Self instance
     */
    public function setFuncaoID($funcao_id)
    {
        $this->funcao_id = $funcao_id;
        return $this;
    }

    /**
     * Cliente que representa esse funcionário, único no cadastro de
     * funcionários
     * @return mixed Cliente of Funcionario
     */
    public function getClienteID()
    {
        return $this->cliente_id;
    }

    /**
     * Set ClienteID value to new on param
     * @param  mixed $cliente_id new value for ClienteID
     * @return Funcionario Self instance
     */
    public function setClienteID($cliente_id)
    {
        $this->cliente_id = $cliente_id;
        return $this;
    }

    /**
     * Código de barras utilizado pelo funcionário para autorizar uma operação
     * no sistema
     * @return mixed Código de barras of Funcionario
     */
    public function getCodigoBarras()
    {
        return $this->codigo_barras;
    }

    /**
     * Set CodigoBarras value to new on param
     * @param  mixed $codigo_barras new value for CodigoBarras
     * @return Funcionario Self instance
     */
    public function setCodigoBarras($codigo_barras)
    {
        $this->codigo_barras = $codigo_barras;
        return $this;
    }

    /**
     * Porcentagem cobrada pelo funcionário ao cliente, Ex.: Comissão de 10%
     * @return mixed Comissão of Funcionario
     */
    public function getPorcentagem()
    {
        return $this->porcentagem;
    }

    /**
     * Set Porcentagem value to new on param
     * @param  mixed $porcentagem new value for Porcentagem
     * @return Funcionario Self instance
     */
    public function setPorcentagem($porcentagem)
    {
        $this->porcentagem = $porcentagem;
        return $this;
    }

    /**
     * Código da linguagem utilizada pelo funcionário para visualizar o
     * programa e o site
     * @return mixed Linguagem of Funcionario
     */
    public function getLinguagemID()
    {
        return $this->linguagem_id;
    }

    /**
     * Set LinguagemID value to new on param
     * @param  mixed $linguagem_id new value for LinguagemID
     * @return Funcionario Self instance
     */
    public function setLinguagemID($linguagem_id)
    {
        $this->linguagem_id = $linguagem_id;
        return $this;
    }

    /**
     * Define a distribuição da porcentagem pela parcela de pontos
     * @return mixed Pontuação of Funcionario
     */
    public function getPontuacao()
    {
        return $this->pontuacao;
    }

    /**
     * Set Pontuacao value to new on param
     * @param  mixed $pontuacao new value for Pontuacao
     * @return Funcionario Self instance
     */
    public function setPontuacao($pontuacao)
    {
        $this->pontuacao = $pontuacao;
        return $this;
    }

    /**
     * Informa se o funcionário está ativo na empresa
     * @return mixed Ativo of Funcionario
     */
    public function getAtivo()
    {
        return $this->ativo;
    }

    /**
     * Informa se o funcionário está ativo na empresa
     * @return boolean Check if o of Ativo is selected or checked
     */
    public function isAtivo()
    {
        return $this->ativo == 'Y';
    }

    /**
     * Set Ativo value to new on param
     * @param  mixed $ativo new value for Ativo
     * @return Funcionario Self instance
     */
    public function setAtivo($ativo)
    {
        $this->ativo = $ativo;
        return $this;
    }

    /**
     * Data de saída do funcionário, informado apenas quando ativo for não
     * @return mixed Data de saída of Funcionario
     */
    public function getDataSaida()
    {
        return $this->data_saida;
    }

    /**
     * Set DataSaida value to new on param
     * @param  mixed $data_saida new value for DataSaida
     * @return Funcionario Self instance
     */
    public function setDataSaida($data_saida)
    {
        $this->data_saida = $data_saida;
        return $this;
    }

    /**
     * Data em que o funcionário foi cadastrado no sistema
     * @return mixed Data de cadastro of Funcionario
     */
    public function getDataCadastro()
    {
        return $this->data_cadastro;
    }

    /**
     * Set DataCadastro value to new on param
     * @param  mixed $data_cadastro new value for DataCadastro
     * @return Funcionario Self instance
     */
    public function setDataCadastro($data_cadastro)
    {
        $this->data_cadastro = $data_cadastro;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param  boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $funcionario = parent::toArray($recursive);
        $funcionario['id'] = $this->getID();
        $funcionario['funcaoid'] = $this->getFuncaoID();
        $funcionario['clienteid'] = $this->getClienteID();
        $funcionario['codigobarras'] = $this->getCodigoBarras();
        $funcionario['porcentagem'] = $this->getPorcentagem();
        $funcionario['linguagemid'] = $this->getLinguagemID();
        $funcionario['pontuacao'] = $this->getPontuacao();
        $funcionario['ativo'] = $this->getAtivo();
        $funcionario['datasaida'] = $this->getDataSaida();
        $funcionario['datacadastro'] = $this->getDataCadastro();
        return $funcionario;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param  mixed $funcionario Associated key -> value to assign into this instance
     * @return Funcionario Self instance
     */
    public function fromArray($funcionario = [])
    {
        if ($funcionario instanceof Funcionario) {
            $funcionario = $funcionario->toArray();
        } elseif (!is_array($funcionario)) {
            $funcionario = [];
        }
        parent::fromArray($funcionario);
        if (!isset($funcionario['id'])) {
            $this->setID(null);
        } else {
            $this->setID($funcionario['id']);
        }
        if (!isset($funcionario['funcaoid'])) {
            $this->setFuncaoID(null);
        } else {
            $this->setFuncaoID($funcionario['funcaoid']);
        }
        if (!isset($funcionario['clienteid'])) {
            $this->setClienteID(null);
        } else {
            $this->setClienteID($funcionario['clienteid']);
        }
        if (!array_key_exists('codigobarras', $funcionario)) {
            $this->setCodigoBarras(null);
        } else {
            $this->setCodigoBarras($funcionario['codigobarras']);
        }
        if (!isset($funcionario['porcentagem'])) {
            $this->setPorcentagem(0);
        } else {
            $this->setPorcentagem($funcionario['porcentagem']);
        }
        if (!isset($funcionario['linguagemid'])) {
            $this->setLinguagemID(1046);
        } else {
            $this->setLinguagemID($funcionario['linguagemid']);
        }
        if (!isset($funcionario['pontuacao'])) {
            $this->setPontuacao(0);
        } else {
            $this->setPontuacao($funcionario['pontuacao']);
        }
        if (!isset($funcionario['ativo'])) {
            $this->setAtivo('N');
        } else {
            $this->setAtivo($funcionario['ativo']);
        }
        if (!array_key_exists('datasaida', $funcionario)) {
            $this->setDataSaida(null);
        } else {
            $this->setDataSaida($funcionario['datasaida']);
        }
        if (!isset($funcionario['datacadastro'])) {
            $this->setDataCadastro(DB::now());
        } else {
            $this->setDataCadastro($funcionario['datacadastro']);
        }
        return $this;
    }

    public function getLinguagemName()
    {
        $linguagens = get_languages_info();
        if (isset($linguagens[$this->getLinguagemID()])) {
            return $linguagens[$this->getLinguagemID()];
        }
        return null;
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $funcionario = parent::publish();
        return $funcionario;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param Funcionario $original Original instance without modifications
     */
    public function filter($original)
    {
        $this->setID(Filter::number($this->getID()));
        $this->setFuncaoID(Filter::number($this->getFuncaoID()));
        $this->setClienteID(Filter::number($this->getClienteID()));
        $this->setCodigoBarras(Filter::string($this->getCodigoBarras()));
        $this->setPorcentagem(Filter::float($this->getPorcentagem()));
        $this->setLinguagemID(Filter::number($this->getLinguagemID()));
        $this->setPontuacao(Filter::number($this->getPontuacao()));
        $this->setDataSaida($original->getDataSaida());
        if (is_owner($original) || is_self($original)) {
            $this->setClienteID($original->getClienteID());
            $this->setFuncaoID($original->getFuncaoID());
            $this->setAtivo($original->getAtivo());
        }
        if (!is_owner($original) && is_self($original)) {
            $this->setPorcentagem($original->getPorcentagem());
            $this->setCodigoBarras($original->getCodigoBarras());
            $this->setPontuacao($original->getPontuacao());
        }
    }

    /**
     * Clean instance resources like images and docs
     * @param  Funcionario $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Funcionario in array format
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getFuncaoID())) {
            $errors['funcaoid'] = 'A função não pode ser vazia';
        }
        if (is_null($this->getClienteID())) {
            $errors['clienteid'] = 'O funcionário não foi identificado';
        } else {
            $cliente = $this->findClienteID();
            if (trim($cliente->getLogin()) == '') {
                $errors['clienteid'] = 'O cliente não possui nome de login';
            } elseif ($cliente->getTipo() != Cliente::TIPO_FISICA) {
                $errors['clienteid'] = 'O cliente precisa ser uma pessoa física';
            } elseif (is_null($cliente->getSenha())) {
                $errors['clienteid'] = 'O cliente precisa possuir uma senha';
            }
        }
        if (is_null($this->getPorcentagem())) {
            $errors['porcentagem'] = 'A comissão não pode ser vazia';
        } elseif ($this->getPorcentagem() < 0) {
            $errors['porcentagem'] = 'A comissão não pode ser negativa';
        }
        if (is_null($this->getLinguagemID())) {
            $errors['linguagemid'] = 'A linguagem não pode ser vazia';
        }
        if (is_null($this->getPontuacao())) {
            $errors['pontuacao'] = 'A pontuação não pode ser vazia';
        } elseif ($this->getPontuacao() < 0) {
            $errors['pontuacao'] = 'A pontuação não pode ser negativa';
        }
        if (!Validator::checkBoolean($this->getAtivo())) {
            $errors['ativo'] = 'A informação se está ativo(a) não foi informada ou é inválida';
        }
        $this->setDataCadastro(DB::now());
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
        if (stripos($e->getMessage(), 'PRIMARY') !== false) {
            return new \MZ\Exception\ValidationException([
                'id' => sprintf(
                    'O código "%s" já está cadastrado',
                    $this->getID()
                ),
            ]);
        }
        if (stripos($e->getMessage(), 'UK_ClienteID') !== false) {
            return new \MZ\Exception\ValidationException([
                'clienteid' => sprintf(
                    'O cliente "%s" já está cadastrado',
                    $this->getClienteID()
                ),
            ]);
        }
        if (contains(['CodigoBarras', 'UNIQUE'], $e->getMessage())) {
            return new \MZ\Exception\ValidationException([
                'codigobarras' => sprintf(
                    'O código de barras "%s" já está cadastrado',
                    $this->getCodigoBarras()
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Insert a new Funcionário into the database and fill instance from database
     * @return Funcionario Self instance
     */
    public function insert()
    {
        $values = $this->validate();
        try {
            $id = DB::insertInto('Funcionarios')->values($values)->execute();
            $this->loadByID($id);
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Funcionário with instance values into database for Código
     * @return Funcionario Self instance
     */
    public function update($only = [], $except = false)
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador do funcionário não foi informado');
        }
        $values = DB::filterValues($values, $only, $except);
        unset($values['datacadastro']);
        try {
            DB::update('Funcionarios')
                ->set($values)
                ->where('id', $this->getID())
                ->execute();
            $this->loadByID($this->getID());
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Delete this instance from database using Código
     * @return integer Number of rows deleted (Max 1)
     */
    public function delete()
    {
        if (!$this->exists()) {
            throw new \Exception('O identificador do funcionário não foi informado');
        }
        if ($this->has(Permissao::NOME_CADASTROFUNCIONARIOS) && !is_owner()) {
            throw new \Exception('Você não tem permissão para excluir esse funcionário!');
        }
        if (is_self($this)) {
            throw new \Exception('Você não pode excluir a si mesmo!');
        }
        if (is_owner($this)) {
            throw new \Exception('Esse funcionário não pode ser excluído!');
        }
        $result = DB::deleteFrom('Funcionarios')
            ->where('id', $this->getID())
            ->execute();
        return $result;
    }

    public function isOwner()
    {
        return $this->getID() == 1;
    }

    /**
     * Check if this employee have permission
     * @param array|string $permission permission to check
     * @return boolean true when has all permission passed or false otherwise
     */
    public function has($permission)
    {
        global $app;
        if (!$this->exists()) {
            return false;
        }
        if ($this->isOwner()) {
            return true;
        }
        settype($permission, 'array');
        $permissoes = $app->getAuthentication()->getPermissions();
        if ($this->getID() != logged_employee()->getID()) {
            $permissoes = Acesso::getPermissoes($this->getFuncaoID());
        }
        $allow = true;
        $operator = '&&';
        foreach ($permission as $value) {
            if (is_array($value)) {
                $operator = current($value);
                continue;
            }
            if ($operator == '||') {
                $allow = $allow || in_array($value, $permissoes);
            } else {
                $allow = $allow && in_array($value, $permissoes);
            }
        }
        return ($allow && count($permission) > 0);
    }

    /**
     * Load one register for it self with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order associative field name -> [-1, 1]
     * @return Funcionario Self instance filled or empty
     */
    public function load($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return $this->fromArray($row);
    }

    /**
     * Load into this object from database using, ClienteID
     * @param  int $cliente_id cliente to find Funcionário
     * @return Funcionario Self filled instance or empty when not found
     */
    public function loadByClienteID($cliente_id)
    {
        return $this->load([
            'clienteid' => intval($cliente_id),
        ]);
    }

    /**
     * Load into this object from database using, CodigoBarras
     * @param  string $codigo_barras código de barras to find Funcionário
     * @return Funcionario Self filled instance or empty when not found
     */
    public function loadByCodigoBarras($codigo_barras)
    {
        return $this->load([
            'codigobarras' => strval($codigo_barras),
        ]);
    }

    /**
     * Função do funcionário na empresa
     * @return \MZ\Employee\Funcao The object fetched from database
     */
    public function findFuncaoID()
    {
        return \MZ\Employee\Funcao::findByID($this->getFuncaoID());
    }

    /**
     * Cliente que representa esse funcionário, único no cadastro de
     * funcionários
     * @return \MZ\Account\Cliente The object fetched from database
     */
    public function findClienteID()
    {
        return \MZ\Account\Cliente::findByID($this->getClienteID());
    }

    /**
     * Get allowed keys array
     * @return array allowed keys array
     */
    private static function getAllowedKeys()
    {
        $funcionario = new Funcionario();
        $allowed = Filter::concatKeys('f.', $funcionario->toArray());
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
        return Filter::orderBy($order, $allowed, 'f.');
    }

    /**
     * Filter condition array with allowed fields
     * @param  array $condition condition to filter rows
     * @return array allowed condition
     */
    private static function filterCondition($condition)
    {
        $allowed = self::getAllowedKeys();
        $allowed['c.genero'] = true;
        return Filter::keys($condition, $allowed, ['f.', 'c.']);
    }

    /**
     * Fetch data from database with a condition
     * @param  array $condition condition to filter rows
     * @param  array $order order rows
     * @return SelectQuery query object with condition statement
     */
    private static function query($condition = [], $order = [])
    {
        $query = DB::from('Funcionarios f')
            ->leftJoin('Clientes c ON c.id = f.clienteid');
        if (isset($condition['search'])) {
            $search = trim($condition['search']);
            if (Validator::checkEmail($search)) {
                $query = $query->where('c.email', $search);
            } elseif (Validator::checkCPF($search)) {
                $query = $query->where('c.cpf', Filter::digits($search));
            } elseif (check_fone($search, true)) {
                $fone = Cliente::buildFoneSearch($search);
                $query = $query->where('(c.fone1 LIKE ? OR c.fone2 LIKE ?)', $fone, $fone);
                $query = $query->orderBy('IF(c.fone1 LIKE ?, 0, 1)', $fone);
            } elseif (Validator::checkDigits($search)) {
                $query = $query->where('f.id', intval($search));
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
        $condition = self::filterCondition($condition);
        $query = DB::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('f.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order order rows
     * @return Funcionario A filled Funcionário or empty instance
     */
    public static function find($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return new Funcionario($row);
    }

    /**
     * Find this object on database using, ClienteID
     * @param  int $cliente_id cliente to find Funcionário
     * @return Funcionario A filled instance or empty when not found
     */
    public static function findByClienteID($cliente_id)
    {
        $result = new self();
        return $result->loadByClienteID($cliente_id);
    }

    /**
     * Find this object on database using, CodigoBarras
     * @param  string $codigo_barras código de barras to find Funcionário
     * @return Funcionario A filled instance or empty when not found
     */
    public static function findByCodigoBarras($codigo_barras)
    {
        $result = new self();
        return $result->loadByCodigoBarras($codigo_barras);
    }

    /**
     * Find all Funcionário
     * @param  array  $condition Condition to get all Funcionário
     * @param  array  $order     Order Funcionário
     * @param  int    $limit     Limit data into row count
     * @param  int    $offset    Start offset to get rows
     * @return array             List of all rows instanced as Funcionario
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
            $result[] = new Funcionario($row);
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
