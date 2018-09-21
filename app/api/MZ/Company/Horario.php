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
namespace MZ\Company;

use MZ\Util\Mask;
use MZ\Util\Filter;
use MZ\Util\Validator;
use MZ\Database\DB;
use MZ\Database\SyncModel;
use MZ\Exception\ValidationException;

/**
 * Informa o horário de funcionamento do estabelecimento
 */
class Horario extends SyncModel
{

    /**
     * Modo de trabalho disponível nesse horário, Funcionamento: horário em que
     * o estabelecimento estará aberto, Operação: quando aceitar novos pedidos
     * locais, Entrega: quando aceita ainda pedidos para entrega
     */
    const MODO_FUNCIONAMENTO = 'Funcionamento';
    const MODO_OPERACAO = 'Operacao';
    const MODO_ENTREGA = 'Entrega';

    /**
     * Identificador do horário
     */
    private $id;
    /**
     * Modo de trabalho disponível nesse horário, Funcionamento: horário em que
     * o estabelecimento estará aberto, Operação: quando aceitar novos pedidos
     * locais, Entrega: quando aceita ainda pedidos para entrega
     */
    private $modo;
    /**
     * Permite informar o horário de acesso ao sistema para realizar essa
     * função
     */
    private $funcao_id;
    /**
     * Permite informar o horário de prestação de serviço para esse prestador
     */
    private $prestador_id;
    /**
     * Permite informar o horário de atendimento para cada integração
     */
    private $integracao_id;
    /**
     * Início do horário de funcionamento em minutos contando a partir de
     * domingo até sábado
     */
    private $inicio;
    /**
     * Horário final de funcionamento do estabelecimento contando em minutos a
     * partir de domingo
     */
    private $fim;
    /**
     * Mensagem que será mostrada quando o estabelecimento estiver fechado por
     * algum motivo
     */
    private $mensagem;
    /**
     * Informa se o estabelecimento estará fechado nesse horário programado, o
     * início e fim será tempo no formato unix, quando verdadeiro tem
     * prioridade sobre todos os horários
     */
    private $fechado;

    /**
     * Constructor for a new empty instance of Horario
     * @param array $horario All field and values to fill the instance
     */
    public function __construct($horario = [])
    {
        parent::__construct($horario);
    }

    /**
     * Identificador do horário
     * @return mixed ID of Horario
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
     * Modo de trabalho disponível nesse horário, Funcionamento: horário em que
     * o estabelecimento estará aberto, Operação: quando aceitar novos pedidos
     * locais, Entrega: quando aceita ainda pedidos para entrega
     * @return mixed Modo of Horario
     */
    public function getModo()
    {
        return $this->modo;
    }

    /**
     * Set Modo value to new on param
     * @param  mixed $modo new value for Modo
     * @return self Self instance
     */
    public function setModo($modo)
    {
        $this->modo = $modo;
        return $this;
    }

    /**
     * Permite informar o horário de acesso ao sistema para realizar essa
     * função
     * @return mixed Função of Horario
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
     * Permite informar o horário de prestação de serviço para esse prestador
     * @return mixed Prestador of Horario
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
     * Permite informar o horário de atendimento para cada integração
     * @return mixed Integração of Horario
     */
    public function getIntegracaoID()
    {
        return $this->integracao_id;
    }

    /**
     * Set IntegracaoID value to new on param
     * @param  mixed $integracao_id new value for IntegracaoID
     * @return self Self instance
     */
    public function setIntegracaoID($integracao_id)
    {
        $this->integracao_id = $integracao_id;
        return $this;
    }

    /**
     * Início do horário de funcionamento em minutos contando a partir de
     * domingo até sábado
     * @return mixed Início of Horario
     */
    public function getInicio()
    {
        return $this->inicio;
    }

    /**
     * Set Inicio value to new on param
     * @param  mixed $inicio new value for Inicio
     * @return self Self instance
     */
    public function setInicio($inicio)
    {
        $this->inicio = $inicio;
        return $this;
    }

    /**
     * Horário final de funcionamento do estabelecimento contando em minutos a
     * partir de domingo
     * @return mixed Fim of Horario
     */
    public function getFim()
    {
        return $this->fim;
    }

    /**
     * Set Fim value to new on param
     * @param  mixed $fim new value for Fim
     * @return self Self instance
     */
    public function setFim($fim)
    {
        $this->fim = $fim;
        return $this;
    }

    /**
     * Mensagem que será mostrada quando o estabelecimento estiver fechado por
     * algum motivo
     * @return mixed Mensagem of Horario
     */
    public function getMensagem()
    {
        return $this->mensagem;
    }

    /**
     * Set Mensagem value to new on param
     * @param  mixed $mensagem new value for Mensagem
     * @return self Self instance
     */
    public function setMensagem($mensagem)
    {
        $this->mensagem = $mensagem;
        return $this;
    }

    /**
     * Informa se o estabelecimento estará fechado nesse horário programado, o
     * início e fim será tempo no formato unix, quando verdadeiro tem
     * prioridade sobre todos os horários
     * @return mixed Fechado of Horario
     */
    public function getFechado()
    {
        return $this->fechado;
    }

    /**
     * Informa se o estabelecimento estará fechado nesse horário programado, o
     * início e fim será tempo no formato unix, quando verdadeiro tem
     * prioridade sobre todos os horários
     * @return boolean Check if o of Fechado is selected or checked
     */
    public function isFechado()
    {
        return $this->fechado == 'Y';
    }

    /**
     * Set Fechado value to new on param
     * @param  mixed $fechado new value for Fechado
     * @return self Self instance
     */
    public function setFechado($fechado)
    {
        $this->fechado = $fechado;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param  boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $horario = parent::toArray($recursive);
        $horario['id'] = $this->getID();
        $horario['modo'] = $this->getModo();
        $horario['funcaoid'] = $this->getFuncaoID();
        $horario['prestadorid'] = $this->getPrestadorID();
        $horario['integracaoid'] = $this->getIntegracaoID();
        $horario['inicio'] = $this->getInicio();
        $horario['fim'] = $this->getFim();
        $horario['mensagem'] = $this->getMensagem();
        $horario['fechado'] = $this->getFechado();
        return $horario;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param  mixed $horario Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($horario = [])
    {
        if ($horario instanceof self) {
            $horario = $horario->toArray();
        } elseif (!is_array($horario)) {
            $horario = [];
        }
        parent::fromArray($horario);
        if (!isset($horario['id'])) {
            $this->setID(null);
        } else {
            $this->setID($horario['id']);
        }
        if (!isset($horario['modo'])) {
            $this->setModo('Funcionamento');
        } else {
            $this->setModo($horario['modo']);
        }
        if (!array_key_exists('funcaoid', $horario)) {
            $this->setFuncaoID(null);
        } else {
            $this->setFuncaoID($horario['funcaoid']);
        }
        if (!array_key_exists('prestadorid', $horario)) {
            $this->setPrestadorID(null);
        } else {
            $this->setPrestadorID($horario['prestadorid']);
        }
        if (!array_key_exists('integracaoid', $horario)) {
            $this->setIntegracaoID(null);
        } else {
            $this->setIntegracaoID($horario['integracaoid']);
        }
        if (!isset($horario['inicio'])) {
            $this->setInicio(null);
        } else {
            $this->setInicio($horario['inicio']);
        }
        if (!isset($horario['fim'])) {
            $this->setFim(null);
        } else {
            $this->setFim($horario['fim']);
        }
        if (!array_key_exists('mensagem', $horario)) {
            $this->setMensagem(null);
        } else {
            $this->setMensagem($horario['mensagem']);
        }
        if (!isset($horario['fechado'])) {
            $this->setFechado('N');
        } else {
            $this->setFechado($horario['fechado']);
        }
        return $this;
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $horario = parent::publish();
        return $horario;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param self $original Original instance without modifications
     */
    public function filter($original)
    {
        $this->setID($original->getID());
        $this->setFuncaoID(Filter::number($this->getFuncaoID()));
        $this->setPrestadorID(Filter::number($this->getPrestadorID()));
        $this->setIntegracaoID(Filter::number($this->getIntegracaoID()));
        $this->setInicio(Filter::number($this->getInicio()));
        $this->setFim(Filter::number($this->getFim()));
        $this->setMensagem(Filter::string($this->getMensagem()));
    }

    /**
     * Clean instance resources like images and docs
     * @param  self $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return mixed[] All field of Horario in array format
     */
    public function validate()
    {
        $errors = [];
        if (!Validator::checkInSet($this->getModo(), self::getModoOptions())) {
            $errors['modo'] = 'O modo é inválido';
        }
        if (is_null($this->getInicio())) {
            $errors['inicio'] = 'O início não pode ser vazio';
        }
        if (is_null($this->getFim())) {
            $errors['fim'] = 'O fim não pode ser vazio';
        }
        if (!Validator::checkBoolean($this->getFechado())) {
            $errors['fechado'] = 'O fechado é inválido';
        }
        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
        return $this->toArray();
    }

    /**
     * Insert a new Horário into the database and fill instance from database
     * @return self Self instance
     */
    public function insert()
    {
        $this->setID(null);
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = DB::insertInto('Horarios')->values($values)->execute();
            $this->setID($id);
            $this->loadByID();
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Horário with instance values into database for ID
     * @param  array $only Save these fields only, when empty save all fields except id
     * @return int rows affected
     */
    public function update($only = [])
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador do horário não foi informado');
        }
        $values = DB::filterValues($values, $only, false);
        try {
            $affected = DB::update('Horarios')
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
     */
    public function delete()
    {
        if (!$this->exists()) {
            throw new \Exception('O identificador do horário não foi informado');
        }
        $result = DB::deleteFrom('Horarios')
            ->where('id', $this->getID())
            ->execute();
        return $result;
    }

    /**
     * Load one register for it self with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order associative field name -> [-1, 1]
     * @return self Self instance filled or empty
     */
    public function load($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return $this->fromArray($row);
    }

    /**
     * Permite informar o horário de acesso ao sistema para realizar essa
     * função
     * @return \MZ\Provider\Funcao The object fetched from database
     */
    public function findFuncaoID()
    {
        if (is_null($this->getFuncaoID())) {
            return new \MZ\Provider\Funcao();
        }
        return \MZ\Provider\Funcao::findByID($this->getFuncaoID());
    }

    /**
     * Permite informar o horário de prestação de serviço para esse prestador
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
     * Permite informar o horário de atendimento para cada integração
     * @return \MZ\System\Integracao The object fetched from database
     */
    public function findIntegracaoID()
    {
        if (is_null($this->getIntegracaoID())) {
            return new \MZ\System\Integracao();
        }
        return \MZ\System\Integracao::findByID($this->getIntegracaoID());
    }

    /**
     * Gets textual and translated Modo for Horario
     * @param  int $index choose option from index
     * @return mixed A associative key -> translated representative text or text for index
     */
    public static function getModoOptions($index = null)
    {
        $options = [
            self::MODO_FUNCIONAMENTO => 'Funcionamento',
            self::MODO_OPERACAO => 'Operacao',
            self::MODO_ENTREGA => 'Entrega',
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
        $horario = new self();
        $allowed = Filter::concatKeys('h.', $horario->toArray());
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
        return Filter::orderBy($order, $allowed, 'h.');
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
            $field = 'h.mensagem LIKE ?';
            $condition[$field] = '%'.$search.'%';
            $allowed[$field] = true;
            unset($condition['search']);
        }
        return Filter::keys($condition, $allowed, 'h.');
    }

    /**
     * Fetch data from database with a condition
     * @param  array $condition condition to filter rows
     * @param  array $order order rows
     * @return SelectQuery query object with condition statement
     */
    private static function query($condition = [], $order = [])
    {
        $query = DB::from('Horarios h');
        $condition = self::filterCondition($condition);
        $query = DB::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('h.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order order rows
     * @return self A filled Horário or empty instance
     */
    public static function find($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return new self($row);
    }

    /**
     * Find all Horário
     * @param  array  $condition Condition to get all Horário
     * @param  array  $order     Order Horário
     * @param  int    $limit     Limit data into row count
     * @param  int    $offset    Start offset to get rows
     * @return self[]             List of all rows instanced as Horario
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
     * @param  array $condition condition to filter rows
     * @return integer Quantity of rows
     */
    public static function count($condition = [])
    {
        $query = self::query($condition);
        return $query->count();
    }
}
