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

use MZ\Util\Filter;
use MZ\Util\Validator;

/**
 * Computadores e tablets com opções de acesso
 */
class Dispositivo extends \MZ\Database\Helper
{

    /**
     * Tipo de dispositivo
     */
    const TIPO_COMPUTADOR = 'Computador';
    const TIPO_TABLET = 'Tablet';

    /**
     * Identificador do dispositivo
     */
    private $id;
    /**
     * Setor em que o dispositivo está instalado/será usado
     */
    private $setor_id;
    /**
     * Finalidade do dispositivo, caixa ou terminal, o caixa é único entre os
     * dispositivos
     */
    private $caixa_id;
    /**
     * Nome do computador ou tablet em rede, único entre os dispositivos
     */
    private $nome;
    /**
     * Tipo de dispositivo
     */
    private $tipo;
    /**
     * Descrição do dispositivo
     */
    private $descricao;
    /**
     * Opções do dispositivo, Ex.: Balança, identificador de chamadas e outros
     */
    private $opcoes;
    /**
     * Serial do tablet para validação, único entre os dispositivos
     */
    private $serial;
    /**
     * Validação do tablet
     */
    private $validacao;

    /**
     * Constructor for a new empty instance of Dispositivo
     * @param array $dispositivo All field and values to fill the instance
     */
    public function __construct($dispositivo = [])
    {
        parent::__construct($dispositivo);
    }

    /**
     * Identificador do dispositivo
     * @return mixed ID of Dispositivo
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param  mixed $id new value for ID
     * @return Dispositivo Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Setor em que o dispositivo está instalado/será usado
     * @return mixed Setor of Dispositivo
     */
    public function getSetorID()
    {
        return $this->setor_id;
    }

    /**
     * Set SetorID value to new on param
     * @param  mixed $setor_id new value for SetorID
     * @return Dispositivo Self instance
     */
    public function setSetorID($setor_id)
    {
        $this->setor_id = $setor_id;
        return $this;
    }

    /**
     * Finalidade do dispositivo, caixa ou terminal, o caixa é único entre os
     * dispositivos
     * @return mixed Caixa of Dispositivo
     */
    public function getCaixaID()
    {
        return $this->caixa_id;
    }

    /**
     * Set CaixaID value to new on param
     * @param  mixed $caixa_id new value for CaixaID
     * @return Dispositivo Self instance
     */
    public function setCaixaID($caixa_id)
    {
        $this->caixa_id = $caixa_id;
        return $this;
    }

    /**
     * Nome do computador ou tablet em rede, único entre os dispositivos
     * @return mixed Nome of Dispositivo
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set Nome value to new on param
     * @param  mixed $nome new value for Nome
     * @return Dispositivo Self instance
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * Tipo de dispositivo
     * @return mixed Tipo of Dispositivo
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set Tipo value to new on param
     * @param  mixed $tipo new value for Tipo
     * @return Dispositivo Self instance
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
        return $this;
    }

    /**
     * Descrição do dispositivo
     * @return mixed Descrição of Dispositivo
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Set Descricao value to new on param
     * @param  mixed $descricao new value for Descricao
     * @return Dispositivo Self instance
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * Opções do dispositivo, Ex.: Balança, identificador de chamadas e outros
     * @return mixed Opções of Dispositivo
     */
    public function getOpcoes()
    {
        return $this->opcoes;
    }

    /**
     * Set Opcoes value to new on param
     * @param  mixed $opcoes new value for Opcoes
     * @return Dispositivo Self instance
     */
    public function setOpcoes($opcoes)
    {
        $this->opcoes = $opcoes;
        return $this;
    }

    /**
     * Serial do tablet para validação, único entre os dispositivos
     * @return mixed Serial of Dispositivo
     */
    public function getSerial()
    {
        return $this->serial;
    }

    /**
     * Set Serial value to new on param
     * @param  mixed $serial new value for Serial
     * @return Dispositivo Self instance
     */
    public function setSerial($serial)
    {
        $this->serial = $serial;
        return $this;
    }

    /**
     * Validação do tablet
     * @return mixed Validação of Dispositivo
     */
    public function getValidacao()
    {
        return $this->validacao;
    }

    /**
     * Set Validacao value to new on param
     * @param  mixed $validacao new value for Validacao
     * @return Dispositivo Self instance
     */
    public function setValidacao($validacao)
    {
        $this->validacao = $validacao;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param  boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $dispositivo = parent::toArray($recursive);
        $dispositivo['id'] = $this->getID();
        $dispositivo['setorid'] = $this->getSetorID();
        $dispositivo['caixaid'] = $this->getCaixaID();
        $dispositivo['nome'] = $this->getNome();
        $dispositivo['tipo'] = $this->getTipo();
        $dispositivo['descricao'] = $this->getDescricao();
        $dispositivo['opcoes'] = $this->getOpcoes();
        $dispositivo['serial'] = $this->getSerial();
        $dispositivo['validacao'] = $this->getValidacao();
        return $dispositivo;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param  mixed $dispositivo Associated key -> value to assign into this instance
     * @return Dispositivo Self instance
     */
    public function fromArray($dispositivo = [])
    {
        if ($dispositivo instanceof Dispositivo) {
            $dispositivo = $dispositivo->toArray();
        } elseif (!is_array($dispositivo)) {
            $dispositivo = [];
        }
        parent::fromArray($dispositivo);
        if (!isset($dispositivo['id'])) {
            $this->setID(null);
        } else {
            $this->setID($dispositivo['id']);
        }
        if (!isset($dispositivo['setorid'])) {
            $this->setSetorID(null);
        } else {
            $this->setSetorID($dispositivo['setorid']);
        }
        if (!array_key_exists('caixaid', $dispositivo)) {
            $this->setCaixaID(null);
        } else {
            $this->setCaixaID($dispositivo['caixaid']);
        }
        if (!isset($dispositivo['nome'])) {
            $this->setNome(null);
        } else {
            $this->setNome($dispositivo['nome']);
        }
        if (!isset($dispositivo['tipo'])) {
            $this->setTipo(null);
        } else {
            $this->setTipo($dispositivo['tipo']);
        }
        if (!array_key_exists('descricao', $dispositivo)) {
            $this->setDescricao(null);
        } else {
            $this->setDescricao($dispositivo['descricao']);
        }
        if (!isset($dispositivo['opcoes'])) {
            $this->setOpcoes(0);
        } else {
            $this->setOpcoes($dispositivo['opcoes']);
        }
        if (!array_key_exists('serial', $dispositivo)) {
            $this->setSerial(null);
        } else {
            $this->setSerial($dispositivo['serial']);
        }
        if (!array_key_exists('validacao', $dispositivo)) {
            $this->setValidacao(null);
        } else {
            $this->setValidacao($dispositivo['validacao']);
        }
        return $this;
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $dispositivo = parent::publish();
        return $dispositivo;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param Dispositivo $original Original instance without modifications
     */
    public function filter($original)
    {
        $this->setID($original->getID());
        $this->setSetorID(Filter::number($this->getSetorID()));
        $this->setCaixaID(Filter::number($this->getCaixaID()));
        $this->setNome(Filter::string($this->getNome()));
        $this->setDescricao(Filter::string($this->getDescricao()));
        $this->setOpcoes(Filter::number($this->getOpcoes()));
        $this->setSerial(Filter::string($this->getSerial()));
        $this->setValidacao(Filter::string($this->getValidacao()));
    }

    /**
     * Clean instance resources like images and docs
     * @param  Dispositivo $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Dispositivo in array format
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getSetorID())) {
            $errors['setorid'] = 'O setor não pode ser vazio';
        }
        if (is_null($this->getNome())) {
            $errors['nome'] = 'O nome não pode ser vazio';
        }
        if (!Validator::checkInSet($this->getTipo(), self::getTipoOptions())) {
            $errors['tipo'] = 'O tipo não foi informado ou é inválido';
        }
        if (is_null($this->getOpcoes())) {
            $errors['opcoes'] = 'A opções não pode ser vazia';
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
        if (contains(['Nome', 'UNIQUE'], $e->getMessage())) {
            return new \MZ\Exception\ValidationException([
                'nome' => sprintf(
                    'O nome "%s" já está cadastrado',
                    $this->getNome()
                ),
            ]);
        }
        if (contains(['CaixaID', 'UNIQUE'], $e->getMessage())) {
            return new \MZ\Exception\ValidationException([
                'caixaid' => sprintf(
                    'O caixa "%s" já está cadastrado',
                    $this->getCaixaID()
                ),
            ]);
        }
        if (contains(['Serial', 'UNIQUE'], $e->getMessage())) {
            return new \MZ\Exception\ValidationException([
                'serial' => sprintf(
                    'O serial "%s" já está cadastrado',
                    $this->getSerial()
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Insert a new Dispositivo into the database and fill instance from database
     * @return Dispositivo Self instance
     */
    public function insert()
    {
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = self::getDB()->insertInto('Dispositivos')->values($values)->execute();
            $dispositivo = self::findByID($id);
            $this->fromArray($dispositivo->toArray());
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Dispositivo with instance values into database for ID
     * @param  array $only Save these fields only, when empty save all fields except id
     * @param  boolean $except When true, saves all fields except $only
     * @return Dispositivo Self instance
     */
    public function update($only = [], $except = false)
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador do dispositivo não foi informado');
        }
        $values = self::filterValues($values, $only, $except);
        try {
            self::getDB()
                ->update('Dispositivos')
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
            throw new \Exception('O identificador do dispositivo não foi informado');
        }
        $result = self::getDB()
            ->deleteFrom('Dispositivos')
            ->where('id', $this->getID())
            ->execute();
        return $result;
    }

    /**
     * Load one register for it self with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order associative field name -> [-1, 1]
     * @return Dispositivo Self instance filled or empty
     */
    public function load($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return $this->fromArray($row);
    }

    /**
     * Load into this object from database using, Nome
     * @param  string $nome nome to find Dispositivo
     * @return Dispositivo Self filled instance or empty when not found
     */
    public function loadByNome($nome)
    {
        return $this->load([
            'nome' => strval($nome),
        ]);
    }

    /**
     * Load into this object from database using, CaixaID
     * @param  int $caixa_id caixa to find Dispositivo
     * @return Dispositivo Self filled instance or empty when not found
     */
    public function loadByCaixaID($caixa_id)
    {
        return $this->load([
            'caixaid' => intval($caixa_id),
        ]);
    }

    /**
     * Load into this object from database using, Serial
     * @param  string $serial serial to find Dispositivo
     * @return Dispositivo Self filled instance or empty when not found
     */
    public function loadBySerial($serial)
    {
        return $this->load([
            'serial' => strval($serial),
        ]);
    }

    /**
     * Setor em que o dispositivo está instalado/será usado
     * @return \MZ\Environment\Setor The object fetched from database
     */
    public function findSetorID()
    {
        return \MZ\Environment\Setor::findByID($this->getSetorID());
    }

    /**
     * Finalidade do dispositivo, caixa ou terminal, o caixa é único entre os
     * dispositivos
     * @return \MZ\Session\Caixa The object fetched from database
     */
    public function findCaixaID()
    {
        if (is_null($this->getCaixaID())) {
            return new \MZ\Session\Caixa();
        }
        return \MZ\Session\Caixa::findByID($this->getCaixaID());
    }

    /**
     * Gets textual and translated Tipo for Dispositivo
     * @param  int $index choose option from index
     * @return mixed A associative key -> translated representative text or text for index
     */
    public static function getTipoOptions($index = null)
    {
        $options = [
            self::TIPO_COMPUTADOR => 'Computador',
            self::TIPO_TABLET => 'Tablet',
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
        $dispositivo = new Dispositivo();
        $allowed = Filter::concatKeys('d.', $dispositivo->toArray());
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
        return Filter::orderBy($order, $allowed, 'd.');
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
            $field = 'd.nome LIKE ?';
            $condition[$field] = '%'.$search.'%';
            $allowed[$field] = true;
            unset($condition['search']);
        }
        return Filter::keys($condition, $allowed, 'd.');
    }

    /**
     * Fetch data from database with a condition
     * @param  array $condition condition to filter rows
     * @param  array $order order rows
     * @return SelectQuery query object with condition statement
     */
    private static function query($condition = [], $order = [])
    {
        $query = self::getDB()->from('Dispositivos d');
        $condition = self::filterCondition($condition);
        $query = self::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('d.nome ASC');
        $query = $query->orderBy('d.id ASC');
        return self::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order order rows
     * @return Dispositivo A filled Dispositivo or empty instance
     */
    public static function find($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return new Dispositivo($row);
    }

    /**
     * Find this object on database using, ID
     * @param  int $id id to find Dispositivo
     * @return Dispositivo A filled instance or empty when not found
     */
    public static function findByID($id)
    {
        return self::find([
            'id' => intval($id),
        ]);
    }

    /**
     * Find this object on database using, Nome
     * @param  string $nome nome to find Dispositivo
     * @return Dispositivo A filled instance or empty when not found
     */
    public static function findByNome($nome)
    {
        return self::find([
            'nome' => strval($nome),
        ]);
    }

    /**
     * Find this object on database using, CaixaID
     * @param  int $caixa_id caixa to find Dispositivo
     * @return Dispositivo A filled instance or empty when not found
     */
    public static function findByCaixaID($caixa_id)
    {
        return self::find([
            'caixaid' => intval($caixa_id),
        ]);
    }

    /**
     * Find this object on database using, Serial
     * @param  string $serial serial to find Dispositivo
     * @return Dispositivo A filled instance or empty when not found
     */
    public static function findBySerial($serial)
    {
        return self::find([
            'serial' => strval($serial),
        ]);
    }

    /**
     * Find not validated tablet
     * @return Dispositivo A filled instance or empty when not found
     */
    public static function findNotValidated()
    {
        return self::find([
            'validacao' => null,
            'tipo' => self::TIPO_TABLET
        ]);
    }

    /**
     * Find all Dispositivo
     * @param  array  $condition Condition to get all Dispositivo
     * @param  array  $order     Order Dispositivo
     * @param  int    $limit     Limit data into row count
     * @param  int    $offset    Start offset to get rows
     * @return array             List of all rows instanced as Dispositivo
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
            $result[] = new Dispositivo($row);
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
