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
namespace MZ\Session;

use MZ\Database\SyncModel;
use MZ\Database\DB;
use MZ\Util\Filter;
use MZ\Util\Validator;

/**
 * Movimentação do caixa, permite abrir diversos caixas na conta de
 * operadores
 */
class Movimentacao extends SyncModel
{

    /**
     * Código da movimentação do caixa
     */
    private $id;
    /**
     * Sessão do dia, permite abrir vários caixas no mesmo dia com o mesmo
     * código da sessão
     */
    private $sessao_id;
    /**
     * Caixa a qual pertence essa movimentação
     */
    private $caixa_id;
    /**
     * Informa se o caixa está aberto
     */
    private $aberta;
    /**
     * Funcionário que abriu o caixa
     */
    private $funcionario_abertura_id;
    /**
     * Data de abertura do caixa
     */
    private $data_abertura;
    /**
     * Funcionário que fechou o caixa
     */
    private $funcionario_fechamento_id;
    /**
     * Data de fechamento do caixa
     */
    private $data_fechamento;

    /**
     * Constructor for a new empty instance of Movimentacao
     * @param array $movimentacao All field and values to fill the instance
     */
    public function __construct($movimentacao = [])
    {
        parent::__construct($movimentacao);
    }

    /**
     * Código da movimentação do caixa
     * @return mixed ID of Movimentacao
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param  mixed $id new value for ID
     * @return Movimentacao Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Sessão do dia, permite abrir vários caixas no mesmo dia com o mesmo
     * código da sessão
     * @return mixed Sessão of Movimentacao
     */
    public function getSessaoID()
    {
        return $this->sessao_id;
    }

    /**
     * Set SessaoID value to new on param
     * @param  mixed $sessao_id new value for SessaoID
     * @return Movimentacao Self instance
     */
    public function setSessaoID($sessao_id)
    {
        $this->sessao_id = $sessao_id;
        return $this;
    }

    /**
     * Caixa a qual pertence essa movimentação
     * @return mixed Caixa of Movimentacao
     */
    public function getCaixaID()
    {
        return $this->caixa_id;
    }

    /**
     * Set CaixaID value to new on param
     * @param  mixed $caixa_id new value for CaixaID
     * @return Movimentacao Self instance
     */
    public function setCaixaID($caixa_id)
    {
        $this->caixa_id = $caixa_id;
        return $this;
    }

    /**
     * Informa se o caixa está aberto
     * @return mixed Aberta of Movimentacao
     */
    public function getAberta()
    {
        return $this->aberta;
    }

    /**
     * Informa se o caixa está aberto
     * @return boolean Check if a of Aberta is selected or checked
     */
    public function isAberta()
    {
        return $this->aberta == 'Y';
    }

    /**
     * Set Aberta value to new on param
     * @param  mixed $aberta new value for Aberta
     * @return Movimentacao Self instance
     */
    public function setAberta($aberta)
    {
        $this->aberta = $aberta;
        return $this;
    }

    /**
     * Funcionário que abriu o caixa
     * @return mixed Funcionário inicializador of Movimentacao
     */
    public function getFuncionarioAberturaID()
    {
        return $this->funcionario_abertura_id;
    }

    /**
     * Set FuncionarioAberturaID value to new on param
     * @param  mixed $funcionario_abertura_id new value for FuncionarioAberturaID
     * @return Movimentacao Self instance
     */
    public function setFuncionarioAberturaID($funcionario_abertura_id)
    {
        $this->funcionario_abertura_id = $funcionario_abertura_id;
        return $this;
    }

    /**
     * Data de abertura do caixa
     * @return mixed Data de abertura of Movimentacao
     */
    public function getDataAbertura()
    {
        return $this->data_abertura;
    }

    /**
     * Set DataAbertura value to new on param
     * @param  mixed $data_abertura new value for DataAbertura
     * @return Movimentacao Self instance
     */
    public function setDataAbertura($data_abertura)
    {
        $this->data_abertura = $data_abertura;
        return $this;
    }

    /**
     * Funcionário que fechou o caixa
     * @return mixed Funcionário fechador of Movimentacao
     */
    public function getFuncionarioFechamentoID()
    {
        return $this->funcionario_fechamento_id;
    }

    /**
     * Set FuncionarioFechamentoID value to new on param
     * @param  mixed $funcionario_fechamento_id new value for FuncionarioFechamentoID
     * @return Movimentacao Self instance
     */
    public function setFuncionarioFechamentoID($funcionario_fechamento_id)
    {
        $this->funcionario_fechamento_id = $funcionario_fechamento_id;
        return $this;
    }

    /**
     * Data de fechamento do caixa
     * @return mixed Data de fechamento of Movimentacao
     */
    public function getDataFechamento()
    {
        return $this->data_fechamento;
    }

    /**
     * Set DataFechamento value to new on param
     * @param  mixed $data_fechamento new value for DataFechamento
     * @return Movimentacao Self instance
     */
    public function setDataFechamento($data_fechamento)
    {
        $this->data_fechamento = $data_fechamento;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param  boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $movimentacao = parent::toArray($recursive);
        $movimentacao['id'] = $this->getID();
        $movimentacao['sessaoid'] = $this->getSessaoID();
        $movimentacao['caixaid'] = $this->getCaixaID();
        $movimentacao['aberta'] = $this->getAberta();
        $movimentacao['funcionarioaberturaid'] = $this->getFuncionarioAberturaID();
        $movimentacao['dataabertura'] = $this->getDataAbertura();
        $movimentacao['funcionariofechamentoid'] = $this->getFuncionarioFechamentoID();
        $movimentacao['datafechamento'] = $this->getDataFechamento();
        return $movimentacao;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param  mixed $movimentacao Associated key -> value to assign into this instance
     * @return Movimentacao Self instance
     */
    public function fromArray($movimentacao = [])
    {
        if ($movimentacao instanceof Movimentacao) {
            $movimentacao = $movimentacao->toArray();
        } elseif (!is_array($movimentacao)) {
            $movimentacao = [];
        }
        parent::fromArray($movimentacao);
        if (!isset($movimentacao['id'])) {
            $this->setID(null);
        } else {
            $this->setID($movimentacao['id']);
        }
        if (!isset($movimentacao['sessaoid'])) {
            $this->setSessaoID(null);
        } else {
            $this->setSessaoID($movimentacao['sessaoid']);
        }
        if (!isset($movimentacao['caixaid'])) {
            $this->setCaixaID(null);
        } else {
            $this->setCaixaID($movimentacao['caixaid']);
        }
        if (!isset($movimentacao['aberta'])) {
            $this->setAberta('N');
        } else {
            $this->setAberta($movimentacao['aberta']);
        }
        if (!isset($movimentacao['funcionarioaberturaid'])) {
            $this->setFuncionarioAberturaID(null);
        } else {
            $this->setFuncionarioAberturaID($movimentacao['funcionarioaberturaid']);
        }
        if (!isset($movimentacao['dataabertura'])) {
            $this->setDataAbertura(DB::now());
        } else {
            $this->setDataAbertura($movimentacao['dataabertura']);
        }
        if (!array_key_exists('funcionariofechamentoid', $movimentacao)) {
            $this->setFuncionarioFechamentoID(null);
        } else {
            $this->setFuncionarioFechamentoID($movimentacao['funcionariofechamentoid']);
        }
        if (!array_key_exists('datafechamento', $movimentacao)) {
            $this->setDataFechamento(null);
        } else {
            $this->setDataFechamento($movimentacao['datafechamento']);
        }
        return $this;
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $movimentacao = parent::publish();
        return $movimentacao;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param Movimentacao $original Original instance without modifications
     */
    public function filter($original, $localized = false)
    {
        $this->setID($original->getID());
        $this->setSessaoID(Filter::number($this->getSessaoID()));
        $this->setCaixaID(Filter::number($this->getCaixaID()));
        $this->setFuncionarioAberturaID(Filter::number($this->getFuncionarioAberturaID()));
        $this->setDataAbertura(Filter::datetime($this->getDataAbertura()));
        $this->setFuncionarioFechamentoID(Filter::number($this->getFuncionarioFechamentoID()));
        $this->setDataFechamento(Filter::datetime($this->getDataFechamento()));
    }

    /**
     * Clean instance resources like images and docs
     * @param  Movimentacao $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Movimentacao in array format
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getSessaoID())) {
            $errors['sessaoid'] = 'A sessão não foi informada';
        }
        $caixa = $this->findCaixaID();
        if (is_null($this->getCaixaID())) {
            $errors['caixaid'] = 'O caixa não foi informado';
        } elseif (!$caixa->isAtivo()) {
            $errors['caixaid'] = sprintf('O caixa "%s" não está ativo', $caixa->getDescricao());
        }
        if (!Validator::checkBoolean($this->getAberta())) {
            $errors['aberta'] = 'A abertura não foi informada ou é inválida';
        } elseif (!$this->exists() && !$this->isAberta()) {
            $errors['aberta'] = 'O caixa não pode ser aberto com status fechado';
        }
        if (is_null($this->getFuncionarioAberturaID())) {
            $errors['funcionarioaberturaid'] = 'O(A) funcionário(a) inicializador(a) não pode ser vazio(a)';
        }
        if (is_null($this->getDataAbertura())) {
            $errors['dataabertura'] = 'A data de abertura não pode ser vazia';
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
        return parent::translate($e);
    }

    /**
     * Insert a new Movimentação into the database and fill instance from database
     * @return Movimentacao Self instance
     */
    public function insert()
    {
        $this->setID(null);
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = DB::insertInto('Movimentacoes')->values($values)->execute();
            $this->setID($id);
            $this->loadByID();
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Movimentação with instance values into database for ID
     * @param  array $only Save these fields only, when empty save all fields except id
     * @return Movimentacao Self instance
     */
    public function update($only = [])
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador da movimentação não foi informado');
        }
        $values = DB::filterValues($values, $only, false);
        try {
            DB::update('Movimentacoes')
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
            throw new \Exception('O identificador da movimentação não foi informado');
        }
        $result = DB::deleteFrom('Movimentacoes')
            ->where('id', $this->getID())
            ->execute();
        return $result;
    }

    /**
     * Load one register for it self with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order associative field name -> [-1, 1]
     * @return Movimentacao Self instance filled or empty
     */
    public function load($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return $this->fromArray($row);
    }

    /**
     * Find open cash register preferred to employee informed
     * @param  int $funcionario_id preferred to employee
     * @return Movimentacao A filled instance or empty when not found
     */
    public function loadByAberta($funcionario_id = null)
    {
        return $this->load(
            ['aberta' => 'Y'],
            [
                'inicializador' => [-1 => $funcionario_id],
                'dataabertura' => -1
            ]
        );
    }

    /**
     * Sessão do dia, permite abrir vários caixas no mesmo dia com o mesmo
     * código da sessão
     * @return \MZ\Session\Sessao The object fetched from database
     */
    public function findSessaoID()
    {
        return \MZ\Session\Sessao::findByID($this->getSessaoID());
    }

    /**
     * Caixa a qual pertence essa movimentação
     * @return \MZ\Session\Caixa The object fetched from database
     */
    public function findCaixaID()
    {
        return \MZ\Session\Caixa::findByID($this->getCaixaID());
    }

    /**
     * Funcionário que abriu o caixa
     * @return \MZ\Provider\Prestador The object fetched from database
     */
    public function findFuncionarioAberturaID()
    {
        return \MZ\Provider\Prestador::findByID($this->getFuncionarioAberturaID());
    }

    /**
     * Funcionário que fechou o caixa
     * @return \MZ\Provider\Prestador The object fetched from database
     */
    public function findFuncionarioFechamentoID()
    {
        if (is_null($this->getFuncionarioFechamentoID())) {
            return new \MZ\Provider\Prestador();
        }
        return \MZ\Provider\Prestador::findByID($this->getFuncionarioFechamentoID());
    }

    /**
     * Get allowed keys array
     * @return array allowed keys array
     */
    private static function getAllowedKeys()
    {
        $movimentacao = new Movimentacao();
        $allowed = Filter::concatKeys('m.', $movimentacao->toArray());
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
        $order = Filter::order($order);
        if (isset($order['inicializador'])) {
            $field = '(m.funcionarioaberturaid = ?)';
            $order = replace_key($order, 'inicializador', $field);
            $allowed[$field] = true;
        }
        return Filter::orderBy($order, $allowed, 'm.');
    }

    /**
     * Filter condition array with allowed fields
     * @param  array $condition condition to filter rows
     * @return array allowed condition
     */
    private static function filterCondition($condition)
    {
        $allowed = self::getAllowedKeys();
        if (isset($condition['apartir_abertura'])) {
            $field = 'm.dataabertura >= ?';
            $condition[$field] = Filter::datetime($condition['apartir_abertura'], '00:00:00');
            $allowed[$field] = true;
            unset($condition['apartir_abertura']);
        }
        if (isset($condition['ate_abertura'])) {
            $field = 'm.dataabertura <= ?';
            $condition[$field] = Filter::datetime($condition['ate_abertura'], '23:59:59');
            $allowed[$field] = true;
            unset($condition['ate_abertura']);
        }
        return Filter::keys($condition, $allowed, 'm.');
    }

    /**
     * Fetch data from database with a condition
     * @param  array $condition condition to filter rows
     * @param  array $order order rows
     * @return SelectQuery query object with condition statement
     */
    private static function query($condition = [], $order = [])
    {
        $query = DB::from('Movimentacoes m');
        $condition = self::filterCondition($condition);
        $query = DB::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('m.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order order rows
     * @return Movimentacao A filled Movimentação or empty instance
     */
    public static function find($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return new Movimentacao($row);
    }

    /**
     * Find open cash register preferred to employee informed
     * @param  int $funcionario_id preferred to employee
     * @return Movimentacao A filled instance or empty when not found
     */
    public static function findByAberta($funcionario_id = null)
    {
        $result = new self();
        return $result->loadByAberta($funcionario_id);
    }

    /**
     * Check if cash register is open
     * @param  int $caixa_id cash register id to find open cash register
     * @return bool true when cash register is open, false otherwise
     */
    public static function isCaixaOpen($caixa_id)
    {
        $abertos = self::count([
            'caixaid' => $caixa_id,
            'aberta' => 'Y'
        ]);
        return $abertos > 0;
    }

    /**
     * Find all Movimentação
     * @param  array  $condition Condition to get all Movimentação
     * @param  array  $order     Order Movimentação
     * @param  int    $limit     Limit data into row count
     * @param  int    $offset    Start offset to get rows
     * @return array             List of all rows instanced as Movimentacao
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
            $result[] = new Movimentacao($row);
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
