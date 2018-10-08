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
namespace MZ\Device;

use MZ\Util\Mask;
use MZ\Util\Filter;
use MZ\Util\Validator;
use MZ\Database\DB;
use MZ\Database\SyncModel;
use MZ\Exception\ValidationException;

/**
 * Impressora para impressão de serviços e contas
 */
class Impressora extends SyncModel
{

    /**
     * Modo de impressão
     */
    const MODO_TERMINAL = 'Terminal';
    const MODO_CAIXA = 'Caixa';
    const MODO_SERVICO = 'Servico';
    const MODO_ESTOQUE = 'Estoque';

    /**
     * Identificador da impressora
     */
    private $id;
    /**
     * Setor de impressão
     */
    private $setor_id;
    /**
     * Dispositivo que contém a impressora
     */
    private $dispositivo_id;
    /**
     * Nome da impressora instalada no windows
     */
    private $nome;
    /**
     * Informa qual conjunto de comandos deve ser utilizado
     */
    private $driver;
    /**
     * Descrição da impressora
     */
    private $descricao;
    /**
     * Modo de impressão
     */
    private $modo;
    /**
     * Opções da impressora, Ex.: Cortar papel, Acionar gaveta e outros
     */
    private $opcoes;
    /**
     * Quantidade de colunas do cupom
     */
    private $colunas;
    /**
     * Quantidade de linhas para avanço do papel
     */
    private $avanco;
    /**
     * Comandos para impressão, quando o driver é customizado
     */
    private $comandos;

    /**
     * Constructor for a new empty instance of Impressora
     * @param array $impressora All field and values to fill the instance
     */
    public function __construct($impressora = [])
    {
        parent::__construct($impressora);
    }

    /**
     * Identificador da impressora
     * @return int id of Impressora
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param int $id Set id for Impressora
     * @return self Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Setor de impressão
     * @return int setor de impressão of Impressora
     */
    public function getSetorID()
    {
        return $this->setor_id;
    }

    /**
     * Set SetorID value to new on param
     * @param int $setor_id Set setor de impressão for Impressora
     * @return self Self instance
     */
    public function setSetorID($setor_id)
    {
        $this->setor_id = $setor_id;
        return $this;
    }

    /**
     * Dispositivo que contém a impressora
     * @return int dispositivo of Impressora
     */
    public function getDispositivoID()
    {
        return $this->dispositivo_id;
    }

    /**
     * Set DispositivoID value to new on param
     * @param int $dispositivo_id Set dispositivo for Impressora
     * @return self Self instance
     */
    public function setDispositivoID($dispositivo_id)
    {
        $this->dispositivo_id = $dispositivo_id;
        return $this;
    }

    /**
     * Nome da impressora instalada no windows
     * @return string nome of Impressora
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set Nome value to new on param
     * @param string $nome Set nome for Impressora
     * @return self Self instance
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * Informa qual conjunto de comandos deve ser utilizado
     * @return string driver of Impressora
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * Set Driver value to new on param
     * @param string $driver Set driver for Impressora
     * @return self Self instance
     */
    public function setDriver($driver)
    {
        $this->driver = $driver;
        return $this;
    }

    /**
     * Descrição da impressora
     * @return string descrição of Impressora
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Set Descricao value to new on param
     * @param string $descricao Set descrição for Impressora
     * @return self Self instance
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * Modo de impressão
     * @return string modo of Impressora
     */
    public function getModo()
    {
        return $this->modo;
    }

    /**
     * Set Modo value to new on param
     * @param string $modo Set modo for Impressora
     * @return self Self instance
     */
    public function setModo($modo)
    {
        $this->modo = $modo;
        return $this;
    }

    /**
     * Opções da impressora, Ex.: Cortar papel, Acionar gaveta e outros
     * @return int opções of Impressora
     */
    public function getOpcoes()
    {
        return $this->opcoes;
    }

    /**
     * Set Opcoes value to new on param
     * @param int $opcoes Set opções for Impressora
     * @return self Self instance
     */
    public function setOpcoes($opcoes)
    {
        $this->opcoes = $opcoes;
        return $this;
    }

    /**
     * Quantidade de colunas do cupom
     * @return int quantidade de colunas of Impressora
     */
    public function getColunas()
    {
        return $this->colunas;
    }

    /**
     * Set Colunas value to new on param
     * @param int $colunas Set quantidade de colunas for Impressora
     * @return self Self instance
     */
    public function setColunas($colunas)
    {
        $this->colunas = $colunas;
        return $this;
    }

    /**
     * Quantidade de linhas para avanço do papel
     * @return int avanço de papel of Impressora
     */
    public function getAvanco()
    {
        return $this->avanco;
    }

    /**
     * Set Avanco value to new on param
     * @param int $avanco Set avanço de papel for Impressora
     * @return self Self instance
     */
    public function setAvanco($avanco)
    {
        $this->avanco = $avanco;
        return $this;
    }

    /**
     * Comandos para impressão, quando o driver é customizado
     * @return string comandos of Impressora
     */
    public function getComandos()
    {
        return $this->comandos;
    }

    /**
     * Set Comandos value to new on param
     * @param string $comandos Set comandos for Impressora
     * @return self Self instance
     */
    public function setComandos($comandos)
    {
        $this->comandos = $comandos;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $impressora = parent::toArray($recursive);
        $impressora['id'] = $this->getID();
        $impressora['setorid'] = $this->getSetorID();
        $impressora['dispositivoid'] = $this->getDispositivoID();
        $impressora['nome'] = $this->getNome();
        $impressora['driver'] = $this->getDriver();
        $impressora['descricao'] = $this->getDescricao();
        $impressora['modo'] = $this->getModo();
        $impressora['opcoes'] = $this->getOpcoes();
        $impressora['colunas'] = $this->getColunas();
        $impressora['avanco'] = $this->getAvanco();
        $impressora['comandos'] = $this->getComandos();
        return $impressora;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param mixed $impressora Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($impressora = [])
    {
        if ($impressora instanceof self) {
            $impressora = $impressora->toArray();
        } elseif (!is_array($impressora)) {
            $impressora = [];
        }
        parent::fromArray($impressora);
        if (!isset($impressora['id'])) {
            $this->setID(null);
        } else {
            $this->setID($impressora['id']);
        }
        if (!isset($impressora['setorid'])) {
            $this->setSetorID(null);
        } else {
            $this->setSetorID($impressora['setorid']);
        }
        if (!array_key_exists('dispositivoid', $impressora)) {
            $this->setDispositivoID(null);
        } else {
            $this->setDispositivoID($impressora['dispositivoid']);
        }
        if (!isset($impressora['nome'])) {
            $this->setNome(null);
        } else {
            $this->setNome($impressora['nome']);
        }
        if (!array_key_exists('driver', $impressora)) {
            $this->setDriver(null);
        } else {
            $this->setDriver($impressora['driver']);
        }
        if (!isset($impressora['descricao'])) {
            $this->setDescricao(null);
        } else {
            $this->setDescricao($impressora['descricao']);
        }
        if (!isset($impressora['modo'])) {
            $this->setModo(null);
        } else {
            $this->setModo($impressora['modo']);
        }
        if (!isset($impressora['opcoes'])) {
            $this->setOpcoes(null);
        } else {
            $this->setOpcoes($impressora['opcoes']);
        }
        if (!isset($impressora['colunas'])) {
            $this->setColunas(null);
        } else {
            $this->setColunas($impressora['colunas']);
        }
        if (!isset($impressora['avanco'])) {
            $this->setAvanco(null);
        } else {
            $this->setAvanco($impressora['avanco']);
        }
        if (!array_key_exists('comandos', $impressora)) {
            $this->setComandos(null);
        } else {
            $this->setComandos($impressora['comandos']);
        }
        return $this;
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $impressora = parent::publish();
        return $impressora;
    }

    public function getModelo()
    {
        switch ($this->getDriver()) {
            case 'Thermal':
                return 'CMP-20';
            case 'Elgin':
            case 'Sweda':
            case 'Dataregis':
                return 'VOX';
            case 'Bematech':
                return 'MP-4200 TH';
            case 'Daruma':
                return 'DR700';
            case 'Diebold':
                return 'IM453';
            case 'PertoPrinter':
                return 'PertoPrinter';
            default:
                // Epson based
                return 'TM-T20';
        }
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
        $this->setSetorID(Filter::number($this->getSetorID()));
        $this->setDispositivoID(Filter::number($this->getDispositivoID()));
        $this->setNome(Filter::string($this->getNome()));
        $this->setDriver(Filter::string($this->getDriver()));
        $this->setDescricao(Filter::string($this->getDescricao()));
        $this->setOpcoes(Filter::number($this->getOpcoes()));
        $this->setColunas(Filter::number($this->getColunas()));
        $this->setAvanco(Filter::number($this->getAvanco()));
        $this->setComandos(Filter::text($this->getComandos()));
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
     * @return array All field of Impressora in array format
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getSetorID())) {
            $errors['setorid'] = _t('impressora.setor_id_cannot_empty');
        }
        if (is_null($this->getNome())) {
            $errors['nome'] = _t('impressora.nome_cannot_empty');
        }
        if (is_null($this->getDescricao())) {
            $errors['descricao'] = _t('impressora.descricao_cannot_empty');
        }
        if (!Validator::checkInSet($this->getModo(), self::getModoOptions())) {
            $errors['modo'] = _t('impressora.modo_invalid');
        }
        if (is_null($this->getOpcoes())) {
            $errors['opcoes'] = _t('impressora.opcoes_cannot_empty');
        }
        if (is_null($this->getColunas())) {
            $errors['colunas'] = _t('impressora.colunas_cannot_empty');
        }
        if (is_null($this->getAvanco())) {
            $errors['avanco'] = _t('impressora.avanco_cannot_empty');
        }
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
        if (contains(['SetorID', 'DispositivoID', 'Modo', 'UNIQUE'], $e->getMessage())) {
            return new ValidationException([
                'setorid' => _t(
                    'impressora.setor_id_used',
                    $this->getSetorID()
                ),
                'dispositivoid' => _t(
                    'impressora.dispositivo_id_used',
                    $this->getDispositivoID()
                ),
                'modo' => _t(
                    'impressora.modo_used',
                    $this->getModo()
                ),
            ]);
        }
        if (contains(['DispositivoID', 'Descricao', 'UNIQUE'], $e->getMessage())) {
            return new ValidationException([
                'dispositivoid' => _t(
                    'impressora.dispositivo_id_used',
                    $this->getDispositivoID()
                ),
                'descricao' => _t(
                    'impressora.descricao_used',
                    $this->getDescricao()
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Insert a new Impressora into the database and fill instance from database
     * @return self Self instance
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function insert()
    {
        $this->setID(null);
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = DB::insertInto('Impressoras')->values($values)->execute();
            $this->setID($id);
            $this->loadByID();
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Impressora with instance values into database for ID
     * @param array $only Save these fields only, when empty save all fields except id
     * @return int rows affected
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function update($only = [])
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new ValidationException(
                ['id' => _t('impressora.id_cannot_empty')]
            );
        }
        $values = DB::filterValues($values, $only, false);
        try {
            $affected = DB::update('Impressoras')
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
                ['id' => _t('impressora.id_cannot_empty')]
            );
        }
        $result = DB::deleteFrom('Impressoras')
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
     * Load into this object from database using, SetorID, DispositivoID, Modo
     * @return self Self filled instance or empty when not found
     */
    public function loadBySetorIDDispositivoIDModo()
    {
        return $this->load([
            'setorid' => intval($this->getSetorID()),
            'dispositivoid' => intval($this->getDispositivoID()),
            'modo' => strval($this->getModo()),
        ]);
    }

    /**
     * Load into this object from database using, DispositivoID, Descricao
     * @return self Self filled instance or empty when not found
     */
    public function loadByDispositivoIDDescricao()
    {
        return $this->load([
            'dispositivoid' => intval($this->getDispositivoID()),
            'descricao' => strval($this->getDescricao()),
        ]);
    }

    /**
     * Setor de impressão
     * @return \MZ\Environment\Setor The object fetched from database
     */
    public function findSetorID()
    {
        return \MZ\Environment\Setor::findByID($this->getSetorID());
    }

    /**
     * Dispositivo que contém a impressora
     * @return \MZ\Device\Dispositivo The object fetched from database
     */
    public function findDispositivoID()
    {
        if (is_null($this->getDispositivoID())) {
            return new \MZ\Device\Dispositivo();
        }
        return \MZ\Device\Dispositivo::findByID($this->getDispositivoID());
    }

    /**
     * Gets textual and translated Modo for Impressora
     * @param int $index choose option from index
     * @return string[] A associative key -> translated representative text or text for index
     */
    public static function getModoOptions($index = null)
    {
        $options = [
            self::MODO_TERMINAL => _t('impressora.modo_terminal'),
            self::MODO_CAIXA => _t('impressora.modo_caixa'),
            self::MODO_SERVICO => _t('impressora.modo_servico'),
            self::MODO_ESTOQUE => _t('impressora.modo_estoque'),
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
        $impressora = new self();
        $allowed = Filter::concatKeys('i.', $impressora->toArray());
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
        return Filter::orderBy($order, $allowed, 'i.');
    }

    /**
     * Filter condition array with allowed fields
     * @param array $condition condition to filter rows
     * @return array allowed condition
     */
    protected static function filterCondition($condition)
    {
        $allowed = self::getAllowedKeys();
        if (isset($condition['search'])) {
            $search = $condition['search'];
            $field = 'i.descricao LIKE ?';
            $condition[$field] = '%'.$search.'%';
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
    protected static function query($condition = [], $order = [])
    {
        $query = DB::from('Impressoras i');
        $condition = self::filterCondition($condition);
        $query = DB::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('i.descricao ASC');
        $query = $query->orderBy('i.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param array $condition Condition for searching the row
     * @param array $order order rows
     * @return self A filled Impressora or empty instance
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
     * @return self A filled Impressora or empty instance
     * @throws \Exception when register has not found
     */
    public static function findOrFail($condition, $order = [])
    {
        $result = self::find($condition, $order);
        if (!$result->exists()) {
            throw new \Exception(_t('impressora.not_found'), 404);
        }
        return $result;
    }

    /**
     * Find this object on database using, SetorID, DispositivoID, Modo
     * @param int $setor_id setor de impressão to find Impressora
     * @param int $dispositivo_id dispositivo to find Impressora
     * @param string $modo modo to find Impressora
     * @return self A filled instance or empty when not found
     */
    public static function findBySetorIDDispositivoIDModo($setor_id, $dispositivo_id, $modo)
    {
        $result = new self();
        $result->setSetorID($setor_id);
        $result->setDispositivoID($dispositivo_id);
        $result->setModo($modo);
        return $result->loadBySetorIDDispositivoIDModo();
    }

    /**
     * Find this object on database using, DispositivoID, Descricao
     * @param int $dispositivo_id dispositivo to find Impressora
     * @param string $descricao descrição to find Impressora
     * @return self A filled instance or empty when not found
     */
    public static function findByDispositivoIDDescricao($dispositivo_id, $descricao)
    {
        $result = new self();
        $result->setDispositivoID($dispositivo_id);
        $result->setDescricao($descricao);
        return $result->loadByDispositivoIDDescricao();
    }

    /**
     * Find all Impressora
     * @param array  $condition Condition to get all Impressora
     * @param array  $order     Order Impressora
     * @param int    $limit     Limit data into row count
     * @param int    $offset    Start offset to get rows
     * @return self[] List of all rows instanced as Impressora
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
