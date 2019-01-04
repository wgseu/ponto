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
     * @var int
     */
    private $id;
    /**
     * Código do prestador, podendo ser de barras
     * @var string
     */
    private $codigo;
    /**
     * Função do prestada na empresa
     * @var int
     */
    private $funcao_id;
    /**
     * Cliente que representa esse prestador, único no cadastro de prestadores
     * @var int
     */
    private $cliente_id;
    /**
     * Informa a empresa que gerencia os colaboradores, nulo para a empresa do
     * próprio estabelecimento
     * @var int
     */
    private $prestador_id;
    /**
     * Vínculo empregatício com a empresa, funcionário e autônomo são pessoas
     * físicas, prestador é pessoa jurídica
     * @var string
     */
    private $vinculo;
    /**
     * Porcentagem cobrada pelo funcionário ou autônomo ao cliente, Ex.:
     * Comissão de 10%
     * @var float
     */
    private $porcentagem;
    /**
     * Define a distribuição da porcentagem pela parcela de pontos
     * @var int
     */
    private $pontuacao;
    /**
     * Informa se o prestador está ativo na empresa
     * @var string
     */
    private $ativo;
    /**
     * Remuneracao pelas atividades exercidas, não está incluso comissões
     * @var float
     */
    private $remuneracao;
    /**
     * Data de término de contrato, informado apenas quando ativo for não
     * @var string
     */
    private $data_termino;
    /**
     * Data em que o prestador de serviços foi cadastrado no sistema
     * @var string
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
     * Identificador do prestador
     * @param int $id Set id for Prestador
     * @return self Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Código do prestador, podendo ser de barras
     * @return string código of Prestador
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Código do prestador, podendo ser de barras
     * @param string $codigo Set código for Prestador
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
     * Função do prestada na empresa
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
     * Cliente que representa esse prestador, único no cadastro de prestadores
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
     * Informa a empresa que gerencia os colaboradores, nulo para a empresa do
     * próprio estabelecimento
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
     * Vínculo empregatício com a empresa, funcionário e autônomo são pessoas
     * físicas, prestador é pessoa jurídica
     * @param string $vinculo Set vínculo for Prestador
     * @return self Self instance
     */
    public function setVinculo($vinculo)
    {
        $this->vinculo = $vinculo;
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
     * Porcentagem cobrada pelo funcionário ou autônomo ao cliente, Ex.:
     * Comissão de 10%
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
     * Define a distribuição da porcentagem pela parcela de pontos
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
     * Informa se o prestador está ativo na empresa
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
     * @return float remuneração of Prestador
     */
    public function getRemuneracao()
    {
        return $this->remuneracao;
    }

    /**
     * Remuneracao pelas atividades exercidas, não está incluso comissões
     * @param float $remuneracao Set remuneração for Prestador
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
     * Data de término de contrato, informado apenas quando ativo for não
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
     * Data em que o prestador de serviços foi cadastrado no sistema
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
     * @param \MZ\Provider\Prestador $requester user that request to view this fields
     * @return array All public field and values into array format
     */
    public function publish($requester)
    {
        $prestador = parent::publish($requester);
        return $prestador;
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
        $this->setCodigo(Filter::digits($this->getCodigo()));
        $this->setFuncaoID(Filter::number($this->getFuncaoID()));
        $this->setClienteID(Filter::number($this->getClienteID()));
        $this->setPrestadorID(Filter::number($this->getPrestadorID()));
        $this->setVinculo(Filter::string($this->getVinculo()));
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
        } elseif (!$this->exists() && !$this->isAtivo()) {
            $errors['ativo'] = _t('prestador.new_inactive');
        }
        if (is_null($this->getRemuneracao())) {
            $errors['remuneracao'] = _t('prestador.remuneracao_cannot_empty');
        } elseif ($this->getRemuneracao() < 0) {
            $errors['remuneracao'] = 'A remuneração não pode ser negativa';
        }
        if ($this->isAtivo() && !is_null($this->getDataTermino())) {
            $errors['datatermino'] = _t('prestador.data_termino_mustbe_null');
        } elseif (!$this->isAtivo() && is_null($this->getDataTermino())) {
            $errors['datatermino'] = _t('prestador.data_termino_cannot_empty');
        }
        $this->setDataCadastro(DB::now());
        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
        $values = $this->toArray();
        if ($this->exists()) {
            unset($values['datacadastro']);
        }
        return $values;
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
     * Delete this instance from database using ID
     * @return integer Number of rows deleted (Max 1)
     * @throws \MZ\Exception\ValidationException for invalid id
     */
    public function delete()
    {
        if ($this->has([Permissao::NOME_CADASTROFUNCIONARIOS]) && !app()->auth->isOwner()) {
            throw new \Exception('Você não tem permissão para excluir esse funcionário!');
        }
        if (app()->auth->isSelf($this)) {
            throw new \Exception('Você não pode excluir a si mesmo!');
        }
        if ($this->isOwner()) {
            throw new \Exception('Esse funcionário não pode ser excluído!');
        }
        return parent::delete();
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
        if ($this->isOwner()) {
            return true;
        }
        $permissoes = $permissoes ?: Acesso::getPermissoes($this->getFuncaoID());
        $allow = false;
        $tested = 0;
        $operator = '&&';
        foreach ($permission as $value) {
            if (is_array($value)) {
                $flag = $this->has($value, $permissoes);
            } elseif ($value == '&&' || $value == '||') {
                $operator = $value;
                continue;
            } else {
                $flag = in_array($value, $permissoes);
            }
            $tested++;
            if ($operator == '||') {
                $allow = ($allow && $tested  > 1) || $flag;
            } else {
                $allow = ($allow || $tested == 1) && $flag;
                if (!$allow) {
                    break;
                }
            }
        }
        return $allow;
    }

    /**
     * Load into this object from database using, ClienteID
     * @return self Self filled instance or empty when not found
     */
    public function loadByClienteID()
    {
        return $this->load([
            'clienteid' => strval($this->getClienteID()),
        ]);
    }

    /**
     * Load into this object from database using, Codigo
     * @return self Self filled instance or empty when not found
     */
    public function loadByCodigo()
    {
        return $this->load([
            'codigo' => strval($this->getCodigo()),
        ]);
    }

    /**
     * Load next available code from database into this object codigo field
     * @return self Self codigo filled instance with next code
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
    protected function getAllowedKeys()
    {
        $allowed = parent::getAllowedKeys();
        $allowed['CAST(p.codigo AS DECIMAL)'] = true;
        return $allowed;
    }

    /**
     * Filter order array
     * @param mixed $order order string or array to parse and filter allowed
     * @return array allowed associative order
     */
    protected function filterOrder($order)
    {
        $order = Filter::order($order);
        if (isset($order['codigo'])) {
            $field = 'CAST(p.codigo AS DECIMAL)';
            $order = replace_key($order, 'codigo', $field);
        }
        return parent::filterOrder($order);
    }

    /**
     * Filter condition array with allowed fields
     * @param array $condition condition to filter rows
     * @return array allowed condition
     */
    protected function filterCondition($condition)
    {
        $allowed = $this->getAllowedKeys();
        $allowed['c.genero'] = true;
        return Filter::keys($condition, $allowed, ['p.', 'c.']);
    }

    /**
     * Fetch data from database with a condition
     * @param array $condition condition to filter rows
     * @param array $order order rows
     * @return SelectQuery query object with condition statement
     */
    protected function query($condition = [], $order = [])
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
        $condition = $this->filterCondition($condition);
        $query = DB::buildOrderBy($query, $this->filterOrder($order));
        $query = $query->orderBy('p.id ASC');
        return DB::buildCondition($query, $condition);
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
     * Find this object on database using, Codigo
     * @param string $codigo código to find Prestador
     * @return self A filled instance or empty when not found
     */
    public static function findByCodigo($codigo)
    {
        $result = new self();
        $result->setCodigo($codigo);
        return $result->loadByCodigo();
    }
}
