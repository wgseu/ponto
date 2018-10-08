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

use MZ\Util\Mask;
use MZ\Util\Filter;
use MZ\Util\Validator;
use MZ\Database\DB;
use MZ\Database\SyncModel;
use MZ\Exception\ValidationException;

/**
 * Eventos de envio das notas
 */
class Evento extends SyncModel
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
     * @return int id of Evento
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param int $id Set id for Evento
     * @return self Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Nota a qual o evento foi criado
     * @return int nota of Evento
     */
    public function getNotaID()
    {
        return $this->nota_id;
    }

    /**
     * Set NotaID value to new on param
     * @param int $nota_id Set nota for Evento
     * @return self Self instance
     */
    public function setNotaID($nota_id)
    {
        $this->nota_id = $nota_id;
        return $this;
    }

    /**
     * Estado do evento
     * @return string estado of Evento
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set Estado value to new on param
     * @param string $estado Set estado for Evento
     * @return self Self instance
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
        return $this;
    }

    /**
     * Mensagem do evento, descreve que aconteceu
     * @return string mensagem of Evento
     */
    public function getMensagem()
    {
        return $this->mensagem;
    }

    /**
     * Set Mensagem value to new on param
     * @param string $mensagem Set mensagem for Evento
     * @return self Self instance
     */
    public function setMensagem($mensagem)
    {
        $this->mensagem = $mensagem;
        return $this;
    }

    /**
     * Código de status do evento, geralmente código de erro de uma exceção
     * @return string código of Evento
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set Codigo value to new on param
     * @param string $codigo Set código for Evento
     * @return self Self instance
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
        return $this;
    }

    /**
     * Data de criação do evento
     * @return string data de criação of Evento
     */
    public function getDataCriacao()
    {
        return $this->data_criacao;
    }

    /**
     * Set DataCriacao value to new on param
     * @param string $data_criacao Set data de criação for Evento
     * @return self Self instance
     */
    public function setDataCriacao($data_criacao)
    {
        $this->data_criacao = $data_criacao;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param boolean $recursive Allow rescursive conversion of fields
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
     * @param mixed $evento Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($evento = [])
    {
        if ($evento instanceof self) {
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
     * @param self $original Original instance without modifications
     * @param boolean $localized Informs if fields are localized
     * @return self Self instance
     */
    public function filter($original, $localized = false)
    {
        $this->setID($original->getID());
        $this->setNotaID(Filter::number($this->getNotaID()));
        $this->setMensagem(Filter::text($this->getMensagem()));
        $this->setCodigo(Filter::string($this->getCodigo()));
        $this->setDataCriacao(Filter::datetime($this->getDataCriacao()));
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
     * @return array All field of Evento in array format
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getNotaID())) {
            $errors['notaid'] = _t('evento.nota_id_cannot_empty');
        }
        if (!Validator::checkInSet($this->getEstado(), self::getEstadoOptions())) {
            $errors['estado'] = _t('evento.estado_invalid');
        }
        if (is_null($this->getMensagem())) {
            $errors['mensagem'] = _t('evento.mensagem_cannot_empty');
        }
        if (is_null($this->getCodigo())) {
            $errors['codigo'] = _t('evento.codigo_cannot_empty');
        }
        $this->setDataCriacao(DB::now());
        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
        return $this->toArray();
    }

    /**
     * Insert a new Evento into the database and fill instance from database
     * @return self Self instance
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function insert()
    {
        $this->setID(null);
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = DB::insertInto('Eventos')->values($values)->execute();
            $this->setID($id);
            $this->loadByID();
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Evento with instance values into database for ID
     * @param array $only Save these fields only, when empty save all fields except id
     * @return int rows affected
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function update($only = [])
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new ValidationException(
                ['id' => _t('evento.id_cannot_empty')]
            );
        }
        $values = DB::filterValues($values, $only, false);
        unset($values['datacriacao']);
        try {
            $affected = DB::update('Eventos')
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
                ['id' => _t('evento.id_cannot_empty')]
            );
        }
        $result = DB::deleteFrom('Eventos')
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
     * @param int $index choose option from index
     * @return string[] A associative key -> translated representative text or text for index
     */
    public static function getEstadoOptions($index = null)
    {
        $options = [
            self::ESTADO_ABERTO => _t('evento.estado_aberto'),
            self::ESTADO_ASSINADO => _t('evento.estado_assinado'),
            self::ESTADO_VALIDADO => _t('evento.estado_validado'),
            self::ESTADO_PENDENTE => _t('evento.estado_pendente'),
            self::ESTADO_PROCESSAMENTO => _t('evento.estado_processamento'),
            self::ESTADO_DENEGADO => _t('evento.estado_denegado'),
            self::ESTADO_CANCELADO => _t('evento.estado_cancelado'),
            self::ESTADO_REJEITADO => _t('evento.estado_rejeitado'),
            self::ESTADO_CONTINGENCIA => _t('evento.estado_contingencia'),
            self::ESTADO_INUTILIZADO => _t('evento.estado_inutilizado'),
            self::ESTADO_AUTORIZADO => _t('evento.estado_autorizado'),
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
        $evento = new self();
        $allowed = Filter::concatKeys('e.', $evento->toArray());
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
        return Filter::orderBy($order, $allowed, 'e.');
    }

    /**
     * Filter condition array with allowed fields
     * @param array $condition condition to filter rows
     * @return array allowed condition
     */
    protected static function filterCondition($condition)
    {
        $allowed = self::getAllowedKeys();
        return Filter::keys($condition, $allowed, 'e.');
    }

    /**
     * Fetch data from database with a condition
     * @param array $condition condition to filter rows
     * @param array $order order rows
     * @return SelectQuery query object with condition statement
     */
    protected static function query($condition = [], $order = [])
    {
        $query = DB::from('Eventos e');
        $condition = self::filterCondition($condition);
        $query = DB::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('e.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param array $condition Condition for searching the row
     * @param array $order order rows
     * @return self A filled Evento or empty instance
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
     * @return self A filled Evento or empty instance
     * @throws \Exception when register has not found
     */
    public static function findOrFail($condition, $order = [])
    {
        $result = self::find($condition, $order);
        if (!$result->exists()) {
            throw new \Exception(_t('evento.not_found'), 404);
        }
        return $result;
    }

    /**
     * Find all Evento
     * @param array  $condition Condition to get all Evento
     * @param array  $order     Order Evento
     * @param int    $limit     Limit data into row count
     * @param int    $offset    Start offset to get rows
     * @return self[] List of all rows instanced as Evento
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
