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
namespace MZ\Provider;

use MZ\Database\SyncModel;
use MZ\Database\DB;
use MZ\Util\Filter;
use MZ\Util\Validator;
use MZ\Account\Cliente;
use MZ\System\Permissao;
use MZ\Exception\ValidationException;

/**
 * Prestador de serviço que realiza alguma tarefa na empresa
 */
class Prestador extends SyncModel
{

    /**
     * Vínculo empregatício com a empresa, funcionário e autônomo são pessoas
     * físicas, prestador é pessoa jurídica
     */
    const VINCULO_FUNCIONARIO = 'Funcionario';
    const VINCULO_PRESTADOR = 'Prestador';
    const VINCULO_AUTONOMO = 'Autonomo';

    /**
     * Identificador do prestador
     */
    private $id;
    /**
     * Código do prestador
     */
    private $codigo;
    /**
     * Função do prestada na empresa
     */
    private $funcao_id;
    /**
     * Cliente que representa esse prestador, único no cadastro de prestadores
     */
    private $cliente_id;
    /**
     * Informa a empresa que gerencia os colaboradores, nulo para a empresa do
     * próprio estabelecimento
     */
    private $prestador_id;
    /**
     * Vínculo empregatício com a empresa, funcionário e autônomo são pessoas
     * físicas, prestador é pessoa jurídica
     */
    private $vinculo;
    /**
     * Código de barras utilizado pelo prestador para autorizar uma operação no
     * sistema
     */
    private $codigo_barras;
    /**
     * Porcentagem cobrada pelo funcionário ou autônomo ao cliente, Ex.:
     * Comissão de 10%
     */
    private $porcentagem;
    /**
     * Define a distribuição da porcentagem pela parcela de pontos
     */
    private $pontuacao;
    /**
     * Informa se o prestador está ativo na empresa
     */
    private $ativo;
    /**
     * Remuneracao pelas atividades exercidas, não está incluso comissões
     */
    private $remuneracao;
    /**
     * Data de término de contrato, informado apenas quando ativo for não
     */
    private $data_termino;
    /**
     * Data em que o prestador de serviços foi cadastrado no sistema
     */
    private $data_cadastro;

    /**
     * Constructor for a new empty instance of Prestador
     * @param array $prestador All field and values to fill the instance
     */
    public function __construct($prestador = [])
    {
        parent::__construct($prestador);
    }

    /**
     * Identificador do prestador
     * @return mixed ID of Prestador
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param  mixed $id new value for ID
     * @return self Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Código do prestador
     * @return mixed Código of Prestador
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set Codigo value to new on param
     * @param  mixed $codigo new value for Codigo
     * @return self Self instance
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
        return $this;
    }

    /**
     * Função do prestada na empresa
     * @return mixed Função of Prestador
     */
    public function getFuncaoID()
    {
        return $this->funcao_id;
    }

    /**
     * Set FuncaoID value to new on param
     * @param  mixed $funcao_id new value for FuncaoID
     * @return self Self instance
     */
    public function setFuncaoID($funcao_id)
    {
        $this->funcao_id = $funcao_id;
        return $this;
    }

    /**
     * Cliente que representa esse prestador, único no cadastro de prestadores
     * @return mixed Cliente of Prestador
     */
    public function getClienteID()
    {
        return $this->cliente_id;
    }

    /**
     * Set ClienteID value to new on param
     * @param  mixed $cliente_id new value for ClienteID
     * @return self Self instance
     */
    public function setClienteID($cliente_id)
    {
        $this->cliente_id = $cliente_id;
        return $this;
    }

    /**
     * Informa a empresa que gerencia os colaboradores, nulo para a empresa do
     * próprio estabelecimento
     * @return mixed Prestador of Prestador
     */
    public function getPrestadorID()
    {
        return $this->prestador_id;
    }

    /**
     * Set PrestadorID value to new on param
     * @param  mixed $prestador_id new value for PrestadorID
     * @return self Self instance
     */
    public function setPrestadorID($prestador_id)
    {
        $this->prestador_id = $prestador_id;
        return $this;
    }

    /**
     * Vínculo empregatício com a empresa, funcionário e autônomo são pessoas
     * físicas, prestador é pessoa jurídica
     * @return mixed Vínculo of Prestador
     */
    public function getVinculo()
    {
        return $this->vinculo;
    }

    /**
     * Set Vinculo value to new on param
     * @param  mixed $vinculo new value for Vinculo
     * @return self Self instance
     */
    public function setVinculo($vinculo)
    {
        $this->vinculo = $vinculo;
        return $this;
    }

    /**
     * Código de barras utilizado pelo prestador para autorizar uma operação no
     * sistema
     * @return mixed Código de barras of Prestador
     */
    public function getCodigoBarras()
    {
        return $this->codigo_barras;
    }

    /**
     * Set CodigoBarras value to new on param
     * @param  mixed $codigo_barras new value for CodigoBarras
     * @return self Self instance
     */
    public function setCodigoBarras($codigo_barras)
    {
        $this->codigo_barras = $codigo_barras;
        return $this;
    }

    /**
     * Porcentagem cobrada pelo funcionário ou autônomo ao cliente, Ex.:
     * Comissão de 10%
     * @return mixed Comissão of Prestador
     */
    public function getPorcentagem()
    {
        return $this->porcentagem;
    }

    /**
     * Set Porcentagem value to new on param
     * @param  mixed $porcentagem new value for Porcentagem
     * @return self Self instance
     */
    public function setPorcentagem($porcentagem)
    {
        $this->porcentagem = $porcentagem;
        return $this;
    }

    /**
     * Define a distribuição da porcentagem pela parcela de pontos
     * @return mixed Pontuação of Prestador
     */
    public function getPontuacao()
    {
        return $this->pontuacao;
    }

    /**
     * Set Pontuacao value to new on param
     * @param  mixed $pontuacao new value for Pontuacao
     * @return self Self instance
     */
    public function setPontuacao($pontuacao)
    {
        $this->pontuacao = $pontuacao;
        return $this;
    }

    /**
     * Informa se o prestador está ativo na empresa
     * @return mixed Ativo of Prestador
     */
    public function getAtivo()
    {
        return $this->ativo;
    }

    /**
     * Informa se o prestador está ativo na empresa
     * @return boolean Check if o of Ativo is selected or checked
     */
    public function isAtivo()
    {
        return $this->ativo == 'Y';
    }

    /**
     * Set Ativo value to new on param
     * @param  mixed $ativo new value for Ativo
     * @return self Self instance
     */
    public function setAtivo($ativo)
    {
        $this->ativo = $ativo;
        return $this;
    }

    /**
     * Remuneracao pelas atividades exercidas, não está incluso comissões
     * @return mixed Remuneração of Prestador
     */
    public function getRemuneracao()
    {
        return $this->remuneracao;
    }

    /**
     * Set Remuneracao value to new on param
     * @param  mixed $remuneracao new value for Remuneracao
     * @return self Self instance
     */
    public function setRemuneracao($remuneracao)
    {
        $this->remuneracao = $remuneracao;
        return $this;
    }

    /**
     * Data de término de contrato, informado apenas quando ativo for não
     * @return mixed Data de término de contrato of Prestador
     */
    public function getDataTermino()
    {
        return $this->data_termino;
    }

    /**
     * Set DataTermino value to new on param
     * @param  mixed $data_termino new value for DataTermino
     * @return self Self instance
     */
    public function setDataTermino($data_termino)
    {
        $this->data_termino = $data_termino;
        return $this;
    }

    /**
     * Data em que o prestador de serviços foi cadastrado no sistema
     * @return mixed Data de cadastro of Prestador
     */
    public function getDataCadastro()
    {
        return $this->data_cadastro;
    }

    /**
     * Set DataCadastro value to new on param
     * @param  mixed $data_cadastro new value for DataCadastro
     * @return self Self instance
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
        $prestador = parent::toArray($recursive);
        $prestador['id'] = $this->getID();
        $prestador['codigo'] = $this->getCodigo();
        $prestador['funcaoid'] = $this->getFuncaoID();
        $prestador['clienteid'] = $this->getClienteID();
        $prestador['prestadorid'] = $this->getPrestadorID();
        $prestador['vinculo'] = $this->getVinculo();
        $prestador['codigobarras'] = $this->getCodigoBarras();
        $prestador['porcentagem'] = $this->getPorcentagem();
        $prestador['pontuacao'] = $this->getPontuacao();
        $prestador['ativo'] = $this->getAtivo();
        $prestador['remuneracao'] = $this->getRemuneracao();
        $prestador['datatermino'] = $this->getDataTermino();
        $prestador['datacadastro'] = $this->getDataCadastro();
        return $prestador;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param  mixed $prestador Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($prestador = [])
    {
        if ($prestador instanceof self) {
            $prestador = $prestador->toArray();
        } elseif (!is_array($prestador)) {
            $prestador = [];
        }
        parent::fromArray($prestador);
        if (!isset($prestador['id'])) {
            $this->setID(null);
        } else {
            $this->setID($prestador['id']);
        }
        if (!isset($prestador['codigo'])) {
            $this->setCodigo(null);
        } else {
            $this->setCodigo($prestador['codigo']);
        }
        if (!isset($prestador['funcaoid'])) {
            $this->setFuncaoID(null);
        } else {
            $this->setFuncaoID($prestador['funcaoid']);
        }
        if (!isset($prestador['clienteid'])) {
            $this->setClienteID(null);
        } else {
            $this->setClienteID($prestador['clienteid']);
        }
        if (!array_key_exists('prestadorid', $prestador)) {
            $this->setPrestadorID(null);
        } else {
            $this->setPrestadorID($prestador['prestadorid']);
        }
        if (!isset($prestador['vinculo'])) {
            $this->setVinculo(null);
        } else {
            $this->setVinculo($prestador['vinculo']);
        }
        if (!array_key_exists('codigobarras', $prestador)) {
            $this->setCodigoBarras(null);
        } else {
            $this->setCodigoBarras($prestador['codigobarras']);
        }
        if (!isset($prestador['porcentagem'])) {
            $this->setPorcentagem(0);
        } else {
            $this->setPorcentagem($prestador['porcentagem']);
        }
        if (!isset($prestador['pontuacao'])) {
            $this->setPontuacao(0);
        } else {
            $this->setPontuacao($prestador['pontuacao']);
        }
        if (!isset($prestador['ativo'])) {
            $this->setAtivo('N');
        } else {
            $this->setAtivo($prestador['ativo']);
        }
        if (!isset($prestador['remuneracao'])) {
            $this->setRemuneracao(0);
        } else {
            $this->setRemuneracao($prestador['remuneracao']);
        }
        if (!array_key_exists('datatermino', $prestador)) {
            $this->setDataTermino(null);
        } else {
            $this->setDataTermino($prestador['datatermino']);
        }
        if (!isset($prestador['datacadastro'])) {
            $this->setDataCadastro(DB::now());
        } else {
            $this->setDataCadastro($prestador['datacadastro']);
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
        $prestador = parent::publish();
        return $prestador;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param Prestador $original Original instance without modifications
     */
    public function filter($original)
    {
        $this->setID($original->getID());
        $this->setCodigo(Filter::number($this->getCodigo()));
        $this->setFuncaoID(Filter::number($this->getFuncaoID()));
        $this->setClienteID(Filter::number($this->getClienteID()));
        $this->setPrestadorID(Filter::number($this->getPrestadorID()));
        $this->setCodigoBarras(Filter::string($this->getCodigoBarras()));
        $this->setPorcentagem(Filter::float($this->getPorcentagem()));
        $this->setPontuacao(Filter::number($this->getPontuacao()));
        $this->setRemuneracao(Filter::money($this->getRemuneracao()));
        $this->setDataTermino(Filter::datetime($this->getDataTermino()));
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
     * @param  Prestador $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Prestador in array format
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getCodigo())) {
            $errors['codigo'] = 'O código não pode ser vazio';
        }
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
        if (!Validator::checkInSet($this->getVinculo(), self::getVinculoOptions())) {
            $errors['vinculo'] = 'O vínculo é inválido';
        }
        if (is_null($this->getPorcentagem())) {
            $errors['porcentagem'] = 'A comissão não pode ser vazia';
        } elseif ($this->getPorcentagem() < 0) {
            $errors['porcentagem'] = 'A comissão não pode ser negativa';
        }
        if (is_null($this->getPontuacao())) {
            $errors['pontuacao'] = 'A pontuação não pode ser vazia';
        } elseif ($this->getPontuacao() < 0) {
            $errors['pontuacao'] = 'A pontuação não pode ser negativa';
        }
        if (!Validator::checkBoolean($this->getAtivo())) {
            $errors['ativo'] = 'A informação se está ativo(a) não foi informada ou é inválida';
        }
        if (is_null($this->getRemuneracao())) {
            $errors['remuneracao'] = 'A remuneração não pode ser vazia';
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
        if (contains(['ClienteID', 'UNIQUE'], $e->getMessage())) {
            return new ValidationException([
                'clienteid' => sprintf(
                    'O cliente "%s" já está cadastrado',
                    $this->getClienteID()
                ),
            ]);
        }
        if (contains(['CodigoBarras', 'UNIQUE'], $e->getMessage())) {
            return new ValidationException([
                'codigobarras' => sprintf(
                    'O código de barras "%s" já está cadastrado',
                    $this->getCodigoBarras()
                ),
            ]);
        }
        if (contains(['Codigo', 'UNIQUE'], $e->getMessage())) {
            return new ValidationException([
                'codigo' => sprintf(
                    'O código "%s" já está cadastrado',
                    $this->getCodigo()
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Insert a new Funcionário into the database and fill instance from database
     * @return Prestador Self instance
     */
    public function insert()
    {
        $values = $this->validate();
        try {
            $id = DB::insertInto('Prestadores')->values($values)->execute();
            $this->setID($id);
            $this->loadByID();
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Prestador with instance values into database for ID
     * @param  array $only Save these fields only, when empty save all fields except id
     * @return int rows affected
     */
    public function update($only = [])
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador do prestador não foi informado');
        }
        $values = DB::filterValues($values, $only, false);
        unset($values['datacadastro']);
        try {
            $affected = DB::update('Prestadores')
                ->set($values)
                ->where('id', $this->getID())
                ->execute();
            $this->loadByID();
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $affected;
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
        $result = DB::deleteFrom('Prestadores')
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
        if (!$this->exists()) {
            return false;
        }
        if ($this->isOwner()) {
            return true;
        }
        settype($permission, 'array');
        $permissoes = $this->getApplication()->getAuthentication()->getPermissions();
        if ($this->getID() != logged_provider()->getID()) {
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
     * @return Prestador Self instance filled or empty
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
     * @return Prestador Self filled instance or empty when not found
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
     * @return Prestador Self filled instance or empty when not found
     */
    public function loadByCodigoBarras($codigo_barras)
    {
        return $this->load([
            'codigobarras' => strval($codigo_barras),
        ]);
    }

    /**
     * Load into this object from database using, Codigo
     * @return self Self filled instance or empty when not found
     */
    public function loadByCodigo()
    {
        return $this->load([
            'codigo' => intval($this->getCodigo()),
        ]);
    }

    /**
     * Load next available code from database into this object codigo field
     * @return self Self id filled instance with next code
     */
    public function loadNextCodigo()
    {
        $last = self::find([], ['codigo' => -1]);
        return $this->setCodigo($last->getCodigo() + 1);
    }

    /**
     * Função do funcionário na empresa
     * @return \MZ\Provider\Funcao The object fetched from database
     */
    public function findFuncaoID()
    {
        return \MZ\Provider\Funcao::findByID($this->getFuncaoID());
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
     * Informa a empresa que gerencia os colaboradores, nulo para a empresa do
     * próprio estabelecimento
     * @return \MZ\Provider\Prestador The object fetched from database
     */
    public function findPrestadorID()
    {
        if (is_null($this->getPrestadorID())) {
            return new \MZ\Provider\Prestador();
        }
        return \MZ\Provider\Prestador::findByID($this->getPrestadorID());
    }

    /**
     * Gets textual and translated Vinculo for Prestador
     * @param  int $index choose option from index
     * @return mixed A associative key -> translated representative text or text for index
     */
    public static function getVinculoOptions($index = null)
    {
        $options = [
            self::VINCULO_FUNCIONARIO => 'Funcionário',
            self::VINCULO_PRESTADOR => 'Prestador',
            self::VINCULO_AUTONOMO => 'Autônomo',
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
        $prestador = new Prestador();
        $allowed = Filter::concatKeys('p.', $prestador->toArray());
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
        return Filter::orderBy($order, $allowed, 'p.');
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
        return Filter::keys($condition, $allowed, ['p.', 'c.']);
    }

    /**
     * Fetch data from database with a condition
     * @param  array $condition condition to filter rows
     * @param  array $order order rows
     * @return SelectQuery query object with condition statement
     */
    private static function query($condition = [], $order = [])
    {
        $query = DB::from('Prestadores p')
            ->leftJoin('Clientes c ON c.id = p.clienteid')
            ->leftJoin('Telefones t ON t.clienteid = c.id AND t.principal = ?', 'Y');
        if (isset($condition['search'])) {
            $search = trim($condition['search']);
            if (Validator::checkEmail($search)) {
                $query = $query->where('c.email', $search);
            } elseif (Validator::checkCPF($search)) {
                $query = $query->where('c.cpf', Filter::digits($search));
            } elseif (check_fone($search, true)) {
                $fone = Cliente::buildFoneSearch($search);
                $query = $query->where('(t.numero LIKE ?)', $fone, $fone);
                $query = $query->orderBy('IF(t.numero LIKE ?, 0, 1)', $fone);
            } elseif (Validator::checkDigits($search)) {
                $query = $query->where('p.id', intval($search));
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
        $query = $query->orderBy('p.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order order rows
     * @return Prestador A filled Funcionário or empty instance
     */
    public static function find($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return new Prestador($row);
    }

    /**
     * Find this object on database using, ClienteID
     * @param  int $cliente_id cliente to find Funcionário
     * @return Prestador A filled instance or empty when not found
     */
    public static function findByClienteID($cliente_id)
    {
        $result = new self();
        return $result->loadByClienteID($cliente_id);
    }

    /**
     * Find this object on database using, CodigoBarras
     * @param  string $codigo_barras código de barras to find Funcionário
     * @return Prestador A filled instance or empty when not found
     */
    public static function findByCodigoBarras($codigo_barras)
    {
        $result = new self();
        return $result->loadByCodigoBarras($codigo_barras);
    }

    /**
     * Find this object on database using, Codigo
     * @param  int $codigo código to find Prestador
     * @return self A filled instance or empty when not found
     */
    public static function findByCodigo($codigo)
    {
        $result = new self();
        $result->setCodigo($codigo);
        return $result->loadByCodigo();
    }

    /**
     * Find all Funcionário
     * @param  array  $condition Condition to get all Funcionário
     * @param  array  $order     Order Funcionário
     * @param  int    $limit     Limit data into row count
     * @param  int    $offset    Start offset to get rows
     * @return self[]             List of all rows instanced as Prestador
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
            $result[] = new Prestador($row);
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
