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

use MZ\Database\SyncModel;
use MZ\Database\DB;
use MZ\Util\Filter;
use MZ\Util\Validator;

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
     * @return mixed ID of Impressora
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param  mixed $id new value for ID
     * @return Impressora Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Setor de impressão
     * @return mixed Setor de impressão of Impressora
     */
    public function getSetorID()
    {
        return $this->setor_id;
    }

    /**
     * Set SetorID value to new on param
     * @param  mixed $setor_id new value for SetorID
     * @return Impressora Self instance
     */
    public function setSetorID($setor_id)
    {
        $this->setor_id = $setor_id;
        return $this;
    }

    /**
     * Dispositivo que contém a impressora
     * @return mixed Dispositivo of Impressora
     */
    public function getDispositivoID()
    {
        return $this->dispositivo_id;
    }

    /**
     * Set DispositivoID value to new on param
     * @param  mixed $dispositivo_id new value for DispositivoID
     * @return Impressora Self instance
     */
    public function setDispositivoID($dispositivo_id)
    {
        $this->dispositivo_id = $dispositivo_id;
        return $this;
    }

    /**
     * Nome da impressora instalada no windows
     * @return mixed Nome of Impressora
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set Nome value to new on param
     * @param  mixed $nome new value for Nome
     * @return Impressora Self instance
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * Informa qual conjunto de comandos deve ser utilizado
     * @return mixed Driver of Impressora
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * Set Driver value to new on param
     * @param  mixed $driver new value for Driver
     * @return Impressora Self instance
     */
    public function setDriver($driver)
    {
        $this->driver = $driver;
        return $this;
    }

    /**
     * Descrição da impressora
     * @return mixed Descrição of Impressora
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Set Descricao value to new on param
     * @param  mixed $descricao new value for Descricao
     * @return Impressora Self instance
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * Modo de impressão
     * @return mixed Modo of Impressora
     */
    public function getModo()
    {
        return $this->modo;
    }

    /**
     * Set Modo value to new on param
     * @param  mixed $modo new value for Modo
     * @return Impressora Self instance
     */
    public function setModo($modo)
    {
        $this->modo = $modo;
        return $this;
    }

    /**
     * Opções da impressora, Ex.: Cortar papel, Acionar gaveta e outros
     * @return mixed Opções of Impressora
     */
    public function getOpcoes()
    {
        return $this->opcoes;
    }

    /**
     * Set Opcoes value to new on param
     * @param  mixed $opcoes new value for Opcoes
     * @return Impressora Self instance
     */
    public function setOpcoes($opcoes)
    {
        $this->opcoes = $opcoes;
        return $this;
    }

    /**
     * Quantidade de colunas do cupom
     * @return mixed Quantidade de colunas of Impressora
     */
    public function getColunas()
    {
        return $this->colunas;
    }

    /**
     * Set Colunas value to new on param
     * @param  mixed $colunas new value for Colunas
     * @return Impressora Self instance
     */
    public function setColunas($colunas)
    {
        $this->colunas = $colunas;
        return $this;
    }

    /**
     * Quantidade de linhas para avanço do papel
     * @return mixed Avanço de papel of Impressora
     */
    public function getAvanco()
    {
        return $this->avanco;
    }

    /**
     * Set Avanco value to new on param
     * @param  mixed $avanco new value for Avanco
     * @return Impressora Self instance
     */
    public function setAvanco($avanco)
    {
        $this->avanco = $avanco;
        return $this;
    }

    /**
     * Comandos para impressão, quando o driver é customizado
     * @return mixed Comandos of Impressora
     */
    public function getComandos()
    {
        return $this->comandos;
    }

    /**
     * Set Comandos value to new on param
     * @param  mixed $comandos new value for Comandos
     * @return Impressora Self instance
     */
    public function setComandos($comandos)
    {
        $this->comandos = $comandos;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param  boolean $recursive Allow rescursive conversion of fields
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
     * @param  mixed $impressora Associated key -> value to assign into this instance
     * @return Impressora Self instance
     */
    public function fromArray($impressora = [])
    {
        if ($impressora instanceof Impressora) {
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
     * @param Impressora $original Original instance without modifications
     */
    public function filter($original)
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
    }

    /**
     * Clean instance resources like images and docs
     * @param  Impressora $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Impressora in array format
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getSetorID())) {
            $errors['setorid'] = 'O setor de impressão não pode ser vazio';
        }
        if (is_null($this->getNome())) {
            $errors['nome'] = 'O nome não pode ser vazio';
        }
        if (is_null($this->getDescricao())) {
            $errors['descricao'] = 'A descrição não pode ser vazia';
        }
        if (is_null($this->getModo())) {
            $errors['modo'] = 'O modo não pode ser vazio';
        }
        if (!Validator::checkInSet($this->getModo(), self::getModoOptions(), true)) {
            $errors['modo'] = 'O modo é inválido';
        }
        if (is_null($this->getOpcoes())) {
            $errors['opcoes'] = 'A opções não pode ser vazia';
        }
        if (is_null($this->getColunas())) {
            $errors['colunas'] = 'A quantidade de colunas não pode ser vazia';
        }
        if (is_null($this->getAvanco())) {
            $errors['avanco'] = 'O avanço de papel não pode ser vazio';
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
        if (stripos($e->getMessage(), 'UK_Impresoras_Setor_Dispositivo_Modo') !== false) {
            return new \MZ\Exception\ValidationException([
                'setorid' => sprintf(
                    'O setor de impressão "%s" já está cadastrado',
                    $this->getSetorID()
                ),
                'dispositivoid' => sprintf(
                    'O dispositivo "%s" já está cadastrado',
                    $this->getDispositivoID()
                ),
                'modo' => sprintf(
                    'O modo "%s" já está cadastrado',
                    $this->getModo()
                ),
            ]);
        }
        if (stripos($e->getMessage(), 'UK_Impressoras_Dispositivo_Descricao') !== false) {
            return new \MZ\Exception\ValidationException([
                'dispositivoid' => sprintf(
                    'O dispositivo "%s" já está cadastrado',
                    $this->getDispositivoID()
                ),
                'descricao' => sprintf(
                    'A descrição "%s" já está cadastrada',
                    $this->getDescricao()
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Insert a new Impressora into the database and fill instance from database
     * @return Impressora Self instance
     */
    public function insert()
    {
        $this->setID(null);
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = DB::insertInto('Impressoras')->values($values)->execute();
            $this->loadByID($id);
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Impressora with instance values into database for ID
     * @param  array $only Save these fields only, when empty save all fields except id
     * @return Impressora Self instance
     */
    public function update($only = [])
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador da impressora não foi informado');
        }
        $values = DB::filterValues($values, $only, false);
        try {
            DB::update('Impressoras')
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
            throw new \Exception('O identificador da impressora não foi informado');
        }
        $result = DB::deleteFrom('Impressoras')
            ->where('id', $this->getID())
            ->execute();
        return $result;
    }

    /**
     * Load one register for it self with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order associative field name -> [-1, 1]
     * @return Impressora Self instance filled or empty
     */
    public function load($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return $this->fromArray($row);
    }

    /**
     * Load into this object from database using, SetorID, DispositivoID, Modo
     * @param  int $setor_id setor de impressão to find Impressora
     * @param  int $dispositivo_id dispositivo to find Impressora
     * @param  string $modo modo to find Impressora
     * @return Impressora Self filled instance or empty when not found
     */
    public function loadBySetorIDDispositivoIDModo($setor_id, $dispositivo_id, $modo)
    {
        return $this->load([
            'setorid' => intval($setor_id),
            'dispositivoid' => intval($dispositivo_id),
            'modo' => strval($modo),
        ]);
    }

    /**
     * Load into this object from database using, DispositivoID, Descricao
     * @param  int $dispositivo_id dispositivo to find Impressora
     * @param  string $descricao descrição to find Impressora
     * @return Impressora Self filled instance or empty when not found
     */
    public function loadByDispositivoIDDescricao($dispositivo_id, $descricao)
    {
        return $this->load([
            'dispositivoid' => intval($dispositivo_id),
            'descricao' => strval($descricao),
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
     * @param  int $index choose option from index
     * @return mixed A associative key -> translated representative text or text for index
     */
    public static function getModoOptions($index = null)
    {
        $options = [
            self::MODO_TERMINAL => 'Terminal',
            self::MODO_CAIXA => 'Caixa',
            self::MODO_SERVICO => 'Serviço',
            self::MODO_ESTOQUE => 'Estoque',
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
        $impressora = new Impressora();
        $allowed = Filter::concatKeys('i.', $impressora->toArray());
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
            $field = 'i.descricao LIKE ?';
            $condition[$field] = '%'.$search.'%';
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
        $query = DB::from('Impressoras i');
        $condition = self::filterCondition($condition);
        $query = DB::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('i.descricao ASC');
        $query = $query->orderBy('i.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order order rows
     * @return Impressora A filled Impressora or empty instance
     */
    public static function find($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return new Impressora($row);
    }

    /**
     * Find this object on database using, SetorID, DispositivoID, Modo
     * @param  int $setor_id setor de impressão to find Impressora
     * @param  int $dispositivo_id dispositivo to find Impressora
     * @param  string $modo modo to find Impressora
     * @return Impressora A filled instance or empty when not found
     */
    public static function findBySetorIDDispositivoIDModo($setor_id, $dispositivo_id, $modo)
    {
        $result = new self();
        return $result->loadBySetorIDDispositivoIDModo($setor_id, $dispositivo_id, $modo);
    }

    /**
     * Find this object on database using, DispositivoID, Descricao
     * @param  int $dispositivo_id dispositivo to find Impressora
     * @param  string $descricao descrição to find Impressora
     * @return Impressora A filled instance or empty when not found
     */
    public static function findByDispositivoIDDescricao($dispositivo_id, $descricao)
    {
        $result = new self();
        return $result->loadByDispositivoIDDescricao($dispositivo_id, $descricao);
    }

    /**
     * Find all Impressora
     * @param  array  $condition Condition to get all Impressora
     * @param  array  $order     Order Impressora
     * @param  int    $limit     Limit data into row count
     * @param  int    $offset    Start offset to get rows
     * @return array             List of all rows instanced as Impressora
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
            $result[] = new Impressora($row);
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
