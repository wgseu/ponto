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
namespace MZ\Invoice;

use MZ\Database\Model;
use MZ\Database\DB;
use MZ\Util\Filter;
use MZ\Util\Validator;

/**
 * Eventos de envio das notas
 */
class Evento extends Model
{

    /**
     * Estado do evento
     */
    const ESTADO_ABERTO = 'Aberto';
    const ESTADO_ASSINADO = 'Assinado';
    const ESTADO_VALIDADO = 'Validado';
    const ESTADO_PENDENTE = 'Pendente';
    const ESTADO_PROCESSAMENTO = 'Processamento';
    const ESTADO_DENEGADO = 'Denegado';
    const ESTADO_CANCELADO = 'Cancelado';
    const ESTADO_REJEITADO = 'Rejeitado';
    const ESTADO_CONTINGENCIA = 'Contingencia';
    const ESTADO_INUTILIZADO = 'Inutilizado';
    const ESTADO_AUTORIZADO = 'Autorizado';

    /**
     * Identificador do evento
     */
    private $id;
    /**
     * Nota a qual o evento foi criado
     */
    private $nota_id;
    /**
     * Estado do evento
     */
    private $estado;
    /**
     * Mensagem do evento, descreve que aconteceu
     */
    private $mensagem;
    /**
     * Código de status do evento, geralmente código de erro de uma exceção
     */
    private $codigo;
    /**
     * Data de criação do evento
     */
    private $data_criacao;

    /**
     * Constructor for a new empty instance of Evento
     * @param array $evento All field and values to fill the instance
     */
    public function __construct($evento = [])
    {
        parent::__construct($evento);
    }

    /**
     * Identificador do evento
     * @return mixed ID of Evento
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param  mixed $id new value for ID
     * @return Evento Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Nota a qual o evento foi criado
     * @return mixed Nota of Evento
     */
    public function getNotaID()
    {
        return $this->nota_id;
    }

    /**
     * Set NotaID value to new on param
     * @param  mixed $nota_id new value for NotaID
     * @return Evento Self instance
     */
    public function setNotaID($nota_id)
    {
        $this->nota_id = $nota_id;
        return $this;
    }

    /**
     * Estado do evento
     * @return mixed Estado of Evento
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set Estado value to new on param
     * @param  mixed $estado new value for Estado
     * @return Evento Self instance
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
        return $this;
    }

    /**
     * Mensagem do evento, descreve que aconteceu
     * @return mixed Mensagem of Evento
     */
    public function getMensagem()
    {
        return $this->mensagem;
    }

    /**
     * Set Mensagem value to new on param
     * @param  mixed $mensagem new value for Mensagem
     * @return Evento Self instance
     */
    public function setMensagem($mensagem)
    {
        $this->mensagem = $mensagem;
        return $this;
    }

    /**
     * Código de status do evento, geralmente código de erro de uma exceção
     * @return mixed Código of Evento
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set Codigo value to new on param
     * @param  mixed $codigo new value for Codigo
     * @return Evento Self instance
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
        return $this;
    }

    /**
     * Data de criação do evento
     * @return mixed Data de criação of Evento
     */
    public function getDataCriacao()
    {
        return $this->data_criacao;
    }

    /**
     * Set DataCriacao value to new on param
     * @param  mixed $data_criacao new value for DataCriacao
     * @return Evento Self instance
     */
    public function setDataCriacao($data_criacao)
    {
        $this->data_criacao = $data_criacao;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param  boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $evento = parent::toArray($recursive);
        $evento['id'] = $this->getID();
        $evento['notaid'] = $this->getNotaID();
        $evento['estado'] = $this->getEstado();
        $evento['mensagem'] = $this->getMensagem();
        $evento['codigo'] = $this->getCodigo();
        $evento['datacriacao'] = $this->getDataCriacao();
        return $evento;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param  mixed $evento Associated key -> value to assign into this instance
     * @return Evento Self instance
     */
    public function fromArray($evento = [])
    {
        if ($evento instanceof Evento) {
            $evento = $evento->toArray();
        } elseif (!is_array($evento)) {
            $evento = [];
        }
        parent::fromArray($evento);
        if (!isset($evento['id'])) {
            $this->setID(null);
        } else {
            $this->setID($evento['id']);
        }
        if (!isset($evento['notaid'])) {
            $this->setNotaID(null);
        } else {
            $this->setNotaID($evento['notaid']);
        }
        if (!isset($evento['estado'])) {
            $this->setEstado(null);
        } else {
            $this->setEstado($evento['estado']);
        }
        if (!isset($evento['mensagem'])) {
            $this->setMensagem(null);
        } else {
            $this->setMensagem($evento['mensagem']);
        }
        if (!isset($evento['codigo'])) {
            $this->setCodigo(null);
        } else {
            $this->setCodigo($evento['codigo']);
        }
        if (!isset($evento['datacriacao'])) {
            $this->setDataCriacao(DB::now());
        } else {
            $this->setDataCriacao($evento['datacriacao']);
        }
        return $this;
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $evento = parent::publish();
        return $evento;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param Evento $original Original instance without modifications
     */
    public function filter($original)
    {
        $this->setID($original->getID());
        $this->setNotaID(Filter::number($this->getNotaID()));
        $this->setMensagem(Filter::text($this->getMensagem()));
        $this->setCodigo(Filter::string($this->getCodigo()));
        $this->setDataCriacao(Filter::datetime($this->getDataCriacao()));
    }

    /**
     * Clean instance resources like images and docs
     * @param  Evento $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Evento in array format
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getNotaID())) {
            $errors['notaid'] = 'A nota não pode ser vazia';
        }
        if (is_null($this->getEstado())) {
            $errors['estado'] = 'O estado não pode ser vazio';
        }
        if (!Validator::checkInSet($this->getEstado(), self::getEstadoOptions(), true)) {
            $errors['estado'] = 'O estado é inválido';
        }
        if (is_null($this->getMensagem())) {
            $errors['mensagem'] = 'A mensagem não pode ser vazia';
        }
        if (is_null($this->getCodigo())) {
            $errors['codigo'] = 'O código não pode ser vazio';
        }
        if (is_null($this->getDataCriacao())) {
            $errors['datacriacao'] = 'A data de criação não pode ser vazia';
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
     * Insert a new Evento into the database and fill instance from database
     * @return Evento Self instance
     */
    public function insert()
    {
        $this->setID(null);
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = DB::insertInto('Eventos')->values($values)->execute();
            $this->loadByID($id);
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Evento with instance values into database for ID
     * @param  array $only Save these fields only, when empty save all fields except id
     * @param  boolean $except When true, saves all fields except $only
     * @return Evento Self instance
     */
    public function update($only = [], $except = false)
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador do evento não foi informado');
        }
        $values = DB::filterValues($values, $only, $except);
        try {
            DB::update('Eventos')
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
     * Delete this instance from database using ID
     * @return integer Number of rows deleted (Max 1)
     */
    public function delete()
    {
        if (!$this->exists()) {
            throw new \Exception('O identificador do evento não foi informado');
        }
        $result = DB::deleteFrom('Eventos')
            ->where('id', $this->getID())
            ->execute();
        return $result;
    }

    /**
     * Load one register for it self with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order associative field name -> [-1, 1]
     * @return Evento Self instance filled or empty
     */
    public function load($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return $this->fromArray($row);
    }

    /**
     * Nota a qual o evento foi criado
     * @return \MZ\Invoice\Nota The object fetched from database
     */
    public function findNotaID()
    {
        return \MZ\Invoice\Nota::findByID($this->getNotaID());
    }

    /**
     * Chamado quando ocorre uma falha na execução de uma tarefa
     */
    public static function log($nota_id, $estado, $mensagem, $codigo)
    {
        $_evento = new Evento();
        $_evento->setNotaID($nota_id);
        $_evento->setEstado($estado);
        $_evento->setMensagem($mensagem);
        $_evento->setCodigo($codigo);
        return $_evento->insert();
    }

    /**
     * Gets textual and translated Estado for Evento
     * @param  int $index choose option from index
     * @return mixed A associative key -> translated representative text or text for index
     */
    public static function getEstadoOptions($index = null)
    {
        $options = [
            self::ESTADO_ABERTO => 'Aberto',
            self::ESTADO_ASSINADO => 'Assinado',
            self::ESTADO_VALIDADO => 'Pendente',
            self::ESTADO_PENDENTE => 'Em processamento',
            self::ESTADO_PROCESSAMENTO => 'Denegado',
            self::ESTADO_DENEGADO => 'Cancelado',
            self::ESTADO_CANCELADO => 'Rejeitado',
            self::ESTADO_REJEITADO => 'Contingência',
            self::ESTADO_CONTINGENCIA => 'Inutilizado',
            self::ESTADO_INUTILIZADO => 'Autorizado',
            self::ESTADO_AUTORIZADO => 'Autorizado',
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
        $evento = new Evento();
        $allowed = Filter::concatKeys('e.', $evento->toArray());
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
        return Filter::orderBy($order, $allowed, 'e.');
    }

    /**
     * Filter condition array with allowed fields
     * @param  array $condition condition to filter rows
     * @return array allowed condition
     */
    private static function filterCondition($condition)
    {
        $allowed = self::getAllowedKeys();
        return Filter::keys($condition, $allowed, 'e.');
    }

    /**
     * Fetch data from database with a condition
     * @param  array $condition condition to filter rows
     * @param  array $order order rows
     * @return SelectQuery query object with condition statement
     */
    private static function query($condition = [], $order = [])
    {
        $query = DB::from('Eventos e');
        $condition = self::filterCondition($condition);
        $query = DB::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('e.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order order rows
     * @return Evento A filled Evento or empty instance
     */
    public static function find($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return new Evento($row);
    }

    /**
     * Find this object on database using, ID
     * @param  int $id id to find Evento
     * @return Evento A filled instance or empty when not found
     */
    public static function findByID($id)
    {
        $result = new self();
        return $result->loadByID($id);
    }

    /**
     * Find all Evento
     * @param  array  $condition Condition to get all Evento
     * @param  array  $order     Order Evento
     * @param  int    $limit     Limit data into row count
     * @param  int    $offset    Start offset to get rows
     * @return array             List of all rows instanced as Evento
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
            $result[] = new Evento($row);
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