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

use MZ\Account\Cliente;
use MZ\System\Permissao;
use MZ\System\Acesso;
use MZ\Util\Mask;
use MZ\Util\Filter;
use MZ\Util\Validator;
use MZ\Database\DB;
use MZ\Database\SyncModel;
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
     * @return int id of Prestador
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param int $id Set id for Prestador
     * @return self Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Código do prestador
     * @return int código of Prestador
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set Codigo value to new on param
     * @param int $codigo Set código for Prestador
     * @return self Self instance
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
        return $this;
    }

    /**
     * Função do prestada na empresa
     * @return int função of Prestador
     */
    public function getFuncaoID()
    {
        return $this->funcao_id;
    }

    /**
     * Set FuncaoID value to new on param
     * @param int $funcao_id Set função for Prestador
     * @return self Self instance
     */
    public function setFuncaoID($funcao_id)
    {
        $this->funcao_id = $funcao_id;
        return $this;
    }

    /**
     * Cliente que representa esse prestador, único no cadastro de prestadores
     * @return int cliente of Prestador
     */
    public function getClienteID()
    {
        return $this->cliente_id;
    }

    /**
     * Set ClienteID value to new on param
     * @param int $cliente_id Set cliente for Prestador
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
     * @return int prestador of Prestador
     */
    public function getPrestadorID()
    {
        return $this->prestador_id;
    }

    /**
     * Set PrestadorID value to new on param
     * @param int $prestador_id Set prestador for Prestador
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
     * @return string vínculo of Prestador
     */
    public function getVinculo()
    {
        return $this->vinculo;
    }

    /**
     * Set Vinculo value to new on param
     * @param string $vinculo Set vínculo for Prestador
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
     * @return string código de barras of Prestador
     */
    public function getCodigoBarras()
    {
        return $this->codigo_barras;
    }

    /**
     * Set CodigoBarras value to new on param
     * @param string $codigo_barras Set código de barras for Prestador
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
     * @return float comissão of Prestador
     */
    public function getPorcentagem()
    {
        return $this->porcentagem;
    }

    /**
     * Set Porcentagem value to new on param
     * @param float $porcentagem Set comissão for Prestador
     * @return self Self instance
     */
    public function setPorcentagem($porcentagem)
    {
        $this->porcentagem = $porcentagem;
        return $this;
    }

    /**
     * Define a distribuição da porcentagem pela parcela de pontos
     * @return int pontuação of Prestador
     */
    public function getPontuacao()
    {
        return $this->pontuacao;
    }

    /**
     * Set Pontuacao value to new on param
     * @param int $pontuacao Set pontuação for Prestador
     * @return self Self instance
     */
    public function setPontuacao($pontuacao)
    {
        $this->pontuacao = $pontuacao;
        return $this;
    }

    /**
     * Informa se o prestador está ativo na empresa
     * @return string ativo of Prestador
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
     * @param string $ativo Set ativo for Prestador
     * @return self Self instance
     */
    public function setAtivo($ativo)
    {
        $this->ativo = $ativo;
        return $this;
    }

    /**
     * Remuneracao pelas atividades exercidas, não está incluso comissões
     * @return string remuneração of Prestador
     */
    public function getRemuneracao()
    {
        return $this->remuneracao;
    }

    /**
     * Set Remuneracao value to new on param
     * @param string $remuneracao Set remuneração for Prestador
     * @return self Self instance
     */
    public function setRemuneracao($remuneracao)
    {
        $this->remuneracao = $remuneracao;
        return $this;
    }

    /**
     * Data de término de contrato, informado apenas quando ativo for não
     * @return string data de término de contrato of Prestador
     */
    public function getDataTermino()
    {
        return $this->data_termino;
    }

    /**
     * Set DataTermino value to new on param
     * @param string $data_termino Set data de término de contrato for Prestador
     * @return self Self instance
     */
    public function setDataTermino($data_termino)
    {
        $this->data_termino = $data_termino;
        return $this;
    }

    /**
     * Data em que o prestador de serviços foi cadastrado no sistema
     * @return string data de cadastro of Prestador
     */
    public function getDataCadastro()
    {
        return $this->data_cadastro;
    }

    /**
     * Set DataCadastro value to new on param
     * @param string $data_cadastro Set data de cadastro for Prestador
     * @return self Self instance
     */
    public function setDataCadastro($data_cadastro)
    {
        $this->data_cadastro = $data_cadastro;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param boolean $recursive Allow rescursive conversion of fields
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
     * @param mixed $prestador Associated key -> value to assign into this instance
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
     * @param self $original Original instance without modifications
     * @param boolean $localized Informs if fields are localized
     * @return self Self instance
     */
    public function filter($original, $localized = false)
    {
        $this->setID($original->getID());
        $this->setCodigo(Filter::number($this->getCodigo()));
        $this->setFuncaoID(Filter::number($this->getFuncaoID()));
        $this->setClienteID(Filter::number($this->getClienteID()));
        $this->setPrestadorID(Filter::number($this->getPrestadorID()));
        $this->setCodigoBarras(Filter::string($this->getCodigoBarras()));
        $this->setPorcentagem(Filter::float($this->getPorcentagem(), $localized));
        $this->setPontuacao(Filter::number($this->getPontuacao()));
        $this->setRemuneracao(Filter::money($this->getRemuneracao(), $localized));
        $this->setDataTermino(Filter::datetime($this->getDataTermino()));
        if ($original->isOwner() || app()->auth->isSelf($original)) {
            $this->setClienteID($original->getClienteID());
            $this->setFuncaoID($original->getFuncaoID());
            $this->setAtivo($original->getAtivo());
        }
        if (!$original->isOwner() && app()->auth->isSelf($original)) {
            $this->setPorcentagem($original->getPorcentagem());
            $this->setCodigoBarras($original->getCodigoBarras());
            $this->setPontuacao($original->getPontuacao());
        }
        return $this;
    }

    /**
     * Clean instance resources like images and docs
     * @param self $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Prestador in array format
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getCodigo())) {
            $errors['codigo'] = _t('prestador.codigo_cannot_empty');
        }
        if (is_null($this->getFuncaoID())) {
            $errors['funcaoid'] = _t('prestador.funcao_id_cannot_empty');
        }
        if (is_null($this->getClienteID())) {
            $errors['clienteid'] = _t('prestador.cliente_id_cannot_empty');
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
            $errors['vinculo'] = _t('prestador.vinculo_invalid');
        }
        if (is_null($this->getPorcentagem())) {
            $errors['porcentagem'] = _t('prestador.porcentagem_cannot_empty');
        } elseif ($this->getPorcentagem() < 0) {
            $errors['porcentagem'] = 'A comissão não pode ser negativa';
        }
        if (is_null($this->getPontuacao())) {
            $errors['pontuacao'] = _t('prestador.pontuacao_cannot_empty');
        } elseif ($this->getPontuacao() < 0) {
            $errors['pontuacao'] = 'A pontuação não pode ser negativa';
        }
        if (!Validator::checkBoolean($this->getAtivo())) {
            $errors['ativo'] = _t('prestador.ativo_invalid');
        }
        if (is_null($this->getRemuneracao())) {
            $errors['remuneracao'] = _t('prestador.remuneracao_cannot_empty');
        } elseif ($this->getRemuneracao() < 0) {
            $errors['remuneracao'] = 'A remuneração não pode ser negativa';
        }
        $this->setDataCadastro(DB::now());
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
        if (contains(['ClienteID', 'UNIQUE'], $e->getMessage())) {
            return new ValidationException([
                'clienteid' => _t(
                    'prestador.cliente_id_used',
                    $this->getClienteID()
                ),
            ]);
        }
        if (contains(['CodigoBarras', 'UNIQUE'], $e->getMessage())) {
            return new ValidationException([
                'codigobarras' => _t(
                    'prestador.codigo_barras_used',
                    $this->getCodigoBarras()
                ),
            ]);
        }
        if (contains(['Codigo', 'UNIQUE'], $e->getMessage())) {
            return new ValidationException([
                'codigo' => _t(
                    'prestador.codigo_used',
                    $this->getCodigo()
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Insert a new Prestador into the database and fill instance from database
     * @return self Self instance
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function insert()
    {
        $this->setID(null);
        $values = $this->validate();
        unset($values['id']);
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
     * @param array $only Save these fields only, when empty save all fields except id
     * @return int rows affected
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function update($only = [])
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new ValidationException(
                ['id' => _t('prestador.id_cannot_empty')]
            );
        }
        $values = DB::filterValues($values, $only, false);
        unset($values['datacadastro']);
        try {
            $affected = DB::update('Prestadores')
                ->set($values)
                ->where(['id' => $this->getID()])
                ->execute();
            $this->loadByID();
        } catch (\Exception $e) {
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
                ['id' => _t('prestador.id_cannot_empty')]
            );
        }
        if ($this->has([Permissao::NOME_CADASTROFUNCIONARIOS]) && !app()->auth->isOwner()) {
            throw new \Exception('Você não tem permissão para excluir esse funcionário!');
        }
        if (app()->auth->isSelf($this)) {
            throw new \Exception('Você não pode excluir a si mesmo!');
        }
        if ($this->isOwner()) {
            throw new \Exception('Esse funcionário não pode ser excluído!');
        }
        $result = DB::deleteFrom('Prestadores')
            ->where('id', $this->getID())
            ->execute();
        return $result;
    }

    /**
     * Check if this provider is the company owner
     * @return boolean true if provider is the company owner
     */
    public function isOwner()
    {
        $cliente = app()->auth->user->getID() == $this->getClienteID() ? app()->auth->user : $this->findClienteID();
        return $cliente->getEmpresaID() == app()->system->company->getID() || app()->system->company->getID() == null;
    }

    /**
     * Check if this employee have permission
     * @param array $permission permission to check
     * @param array $permissoes provider permissions
     * @return boolean true when has all permission passed or false otherwise
     */
    public function has($permission, $permissoes = null)
    {
        if (!$this->exists()) {
            return false;
        }
        $permissoes = $permissoes ?: Acesso::getPermissoes($this->getFuncaoID());
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
     * @param array $condition Condition for searching the row
     * @param array $order associative field name -> [-1, 1]
     * @return self Self instance filled or empty
     */
    public function load($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return $this->fromArray($row);
    }

    /**
     * Load into this object from database using, ClienteID
     * @return self Self filled instance or empty when not found
     */
    public function loadByClienteID()
    {
        return $this->load([
            'clienteid' => intval($this->getClienteID()),
        ]);
    }

    /**
     * Load into this object from database using, CodigoBarras
     * @return self Self filled instance or empty when not found
     */
    public function loadByCodigoBarras()
    {
        return $this->load([
            'codigobarras' => strval($this->getCodigoBarras()),
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
     * Função do prestada na empresa
     * @return \MZ\Provider\Funcao The object fetched from database
     */
    public function findFuncaoID()
    {
        return \MZ\Provider\Funcao::findByID($this->getFuncaoID());
    }

    /**
     * Cliente que representa esse prestador, único no cadastro de prestadores
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
     * @param int $index choose option from index
     * @return string[] A associative key -> translated representative text or text for index
     */
    public static function getVinculoOptions($index = null)
    {
        $options = [
            self::VINCULO_FUNCIONARIO => _t('prestador.vinculo_funcionario'),
            self::VINCULO_PRESTADOR => _t('prestador.vinculo_prestador'),
            self::VINCULO_AUTONOMO => _t('prestador.vinculo_autonomo'),
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
        $prestador = new self();
        $allowed = Filter::concatKeys('p.', $prestador->toArray());
        return $allowed;
    }

    /**
     * Filter order array
     * @param mixed $order order string or array to parse and filter allowed
     * @return array allowed associative order
     */
    private static function filterOrder($order)
    {
        $allowed = self::getAllowedKeys();
        return Filter::orderBy($order, $allowed, 'p.');
    }

    /**
     * Filter condition array with allowed fields
     * @param array $condition condition to filter rows
     * @return array allowed condition
     */
    protected static function filterCondition($condition)
    {
        $allowed = self::getAllowedKeys();
        $allowed['c.genero'] = true;
        return Filter::keys($condition, $allowed, ['p.', 'c.']);
    }

    /**
     * Fetch data from database with a condition
     * @param array $condition condition to filter rows
     * @param array $order order rows
     * @return SelectQuery query object with condition statement
     */
    protected static function query($condition = [], $order = [])
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
     * @param array $condition Condition for searching the row
     * @param array $order order rows
     * @return self A filled Prestador or empty instance
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
     * @return self A filled Prestador or empty instance
     * @throws \Exception when register has not found
     */
    public static function findOrFail($condition, $order = [])
    {
        $result = self::find($condition, $order);
        if (!$result->exists()) {
            throw new \Exception(_t('prestador.not_found'), 404);
        }
        return $result;
    }

    /**
     * Find this object on database using, ClienteID
     * @param int $cliente_id cliente to find Prestador
     * @return self A filled instance or empty when not found
     */
    public static function findByClienteID($cliente_id)
    {
        $result = new self();
        $result->setClienteID($cliente_id);
        return $result->loadByClienteID();
    }

    /**
     * Find this object on database using, CodigoBarras
     * @param string $codigo_barras código de barras to find Prestador
     * @return self A filled instance or empty when not found
     */
    public static function findByCodigoBarras($codigo_barras)
    {
        $result = new self();
        $result->setCodigoBarras($codigo_barras);
        return $result->loadByCodigoBarras();
    }

    /**
     * Find this object on database using, Codigo
     * @param int $codigo código to find Prestador
     * @return self A filled instance or empty when not found
     */
    public static function findByCodigo($codigo)
    {
        $result = new self();
        $result->setCodigo($codigo);
        return $result->loadByCodigo();
    }

    /**
     * Find all Prestador
     * @param array  $condition Condition to get all Prestador
     * @param array  $order     Order Prestador
     * @param int    $limit     Limit data into row count
     * @param int    $offset    Start offset to get rows
     * @return self[] List of all rows instanced as Prestador
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
