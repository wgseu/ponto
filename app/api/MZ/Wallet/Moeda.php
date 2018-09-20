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
namespace MZ\Wallet;

use MZ\Util\Mask;
use MZ\Util\Filter;
use MZ\Util\Validator;
use MZ\Database\DB;
use MZ\Database\SyncModel;
use MZ\Exception\ValidationException;

/**
 * Moedas financeiras de um país
 */
class Moeda extends SyncModel
{

    /**
     * Identificador da moeda
     */
    private $id;
    /**
     * Nome da moeda
     */
    private $nome;
    /**
     * Símbolo da moeda, Ex.: R$, $
     */
    private $simbolo;
    /**
     * Código internacional da moeda, Ex.: USD, BRL
     */
    private $codigo;
    /**
     * Informa o número fracionário para determinar a quantidade de casas
     * decimais, Ex: 100 para 0,00. 10 para 0,0
     */
    private $divisao;
    /**
     * Informa o nome da fração, Ex.: Centavo
     */
    private $fracao;
    /**
     * Formado de exibição do valor, Ex: $ %s, para $ 3,00
     */
    private $formato;
    /**
     * Multiplicador para conversão para a moeda principal
     */
    private $conversao;
    /**
     * Data da última atualização do fator de conversão
     */
    private $data_atualizacao;
    /**
     * Informa se a moeda é recebida pela empresa, a moeda do país mesmo
     * desativada sempre é aceita
     */
    private $ativa;

    /**
     * Constructor for a new empty instance of Moeda
     * @param array $moeda All field and values to fill the instance
     */
    public function __construct($moeda = [])
    {
        parent::__construct($moeda);
    }

    /**
     * Identificador da moeda
     * @return mixed ID of Moeda
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
     * Nome da moeda
     * @return mixed Nome of Moeda
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set Nome value to new on param
     * @param  mixed $nome new value for Nome
     * @return self Self instance
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * Símbolo da moeda, Ex.: R$, $
     * @return mixed Símbolo of Moeda
     */
    public function getSimbolo()
    {
        return $this->simbolo;
    }

    /**
     * Set Simbolo value to new on param
     * @param  mixed $simbolo new value for Simbolo
     * @return self Self instance
     */
    public function setSimbolo($simbolo)
    {
        $this->simbolo = $simbolo;
        return $this;
    }

    /**
     * Código internacional da moeda, Ex.: USD, BRL
     * @return mixed Código of Moeda
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
     * Informa o número fracionário para determinar a quantidade de casas
     * decimais, Ex: 100 para 0,00. 10 para 0,0
     * @return mixed Divisão of Moeda
     */
    public function getDivisao()
    {
        return $this->divisao;
    }

    /**
     * Set Divisao value to new on param
     * @param  mixed $divisao new value for Divisao
     * @return self Self instance
     */
    public function setDivisao($divisao)
    {
        $this->divisao = $divisao;
        return $this;
    }

    /**
     * Informa o nome da fração, Ex.: Centavo
     * @return mixed Nome da fração of Moeda
     */
    public function getFracao()
    {
        return $this->fracao;
    }

    /**
     * Set Fracao value to new on param
     * @param  mixed $fracao new value for Fracao
     * @return self Self instance
     */
    public function setFracao($fracao)
    {
        $this->fracao = $fracao;
        return $this;
    }

    /**
     * Formado de exibição do valor, Ex: $ %s, para $ 3,00
     * @return mixed Formato of Moeda
     */
    public function getFormato()
    {
        return $this->formato;
    }

    /**
     * Set Formato value to new on param
     * @param  mixed $formato new value for Formato
     * @return self Self instance
     */
    public function setFormato($formato)
    {
        $this->formato = $formato;
        return $this;
    }

    /**
     * Multiplicador para conversão para a moeda principal
     * @return mixed Conversão of Moeda
     */
    public function getConversao()
    {
        return $this->conversao;
    }

    /**
     * Set Conversao value to new on param
     * @param  mixed $conversao new value for Conversao
     * @return self Self instance
     */
    public function setConversao($conversao)
    {
        $this->conversao = $conversao;
        return $this;
    }

    /**
     * Data da última atualização do fator de conversão
     * @return mixed Data de atualização of Moeda
     */
    public function getDataAtualizacao()
    {
        return $this->data_atualizacao;
    }

    /**
     * Set DataAtualizacao value to new on param
     * @param  mixed $data_atualizacao new value for DataAtualizacao
     * @return self Self instance
     */
    public function setDataAtualizacao($data_atualizacao)
    {
        $this->data_atualizacao = $data_atualizacao;
        return $this;
    }

    /**
     * Informa se a moeda é recebida pela empresa, a moeda do país mesmo
     * desativada sempre é aceita
     * @return mixed Ativa of Moeda
     */
    public function getAtiva()
    {
        return $this->ativa;
    }

    /**
     * Informa se a moeda é recebida pela empresa, a moeda do país mesmo
     * desativada sempre é aceita
     * @return boolean Check if a of Ativa is selected or checked
     */
    public function isAtiva()
    {
        return $this->ativa == 'Y';
    }

    /**
     * Set Ativa value to new on param
     * @param  mixed $ativa new value for Ativa
     * @return self Self instance
     */
    public function setAtiva($ativa)
    {
        $this->ativa = $ativa;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param  boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $moeda = parent::toArray($recursive);
        $moeda['id'] = $this->getID();
        $moeda['nome'] = $this->getNome();
        $moeda['simbolo'] = $this->getSimbolo();
        $moeda['codigo'] = $this->getCodigo();
        $moeda['divisao'] = $this->getDivisao();
        $moeda['fracao'] = $this->getFracao();
        $moeda['formato'] = $this->getFormato();
        $moeda['conversao'] = $this->getConversao();
        $moeda['dataatualizacao'] = $this->getDataAtualizacao();
        $moeda['ativa'] = $this->getAtiva();
        return $moeda;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param  mixed $moeda Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($moeda = [])
    {
        if ($moeda instanceof self) {
            $moeda = $moeda->toArray();
        } elseif (!is_array($moeda)) {
            $moeda = [];
        }
        parent::fromArray($moeda);
        if (!isset($moeda['id'])) {
            $this->setID(null);
        } else {
            $this->setID($moeda['id']);
        }
        if (!isset($moeda['nome'])) {
            $this->setNome(null);
        } else {
            $this->setNome($moeda['nome']);
        }
        if (!isset($moeda['simbolo'])) {
            $this->setSimbolo(null);
        } else {
            $this->setSimbolo($moeda['simbolo']);
        }
        if (!isset($moeda['codigo'])) {
            $this->setCodigo(null);
        } else {
            $this->setCodigo($moeda['codigo']);
        }
        if (!isset($moeda['divisao'])) {
            $this->setDivisao(null);
        } else {
            $this->setDivisao($moeda['divisao']);
        }
        if (!array_key_exists('fracao', $moeda)) {
            $this->setFracao(null);
        } else {
            $this->setFracao($moeda['fracao']);
        }
        if (!isset($moeda['formato'])) {
            $this->setFormato(null);
        } else {
            $this->setFormato($moeda['formato']);
        }
        if (!array_key_exists('conversao', $moeda)) {
            $this->setConversao(null);
        } else {
            $this->setConversao($moeda['conversao']);
        }
        if (!array_key_exists('dataatualizacao', $moeda)) {
            $this->setDataAtualizacao(null);
        } else {
            $this->setDataAtualizacao($moeda['dataatualizacao']);
        }
        if (!isset($moeda['ativa'])) {
            $this->setAtiva('N');
        } else {
            $this->setAtiva($moeda['ativa']);
        }
        return $this;
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $moeda = parent::publish();
        return $moeda;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param self $original Original instance without modifications
     */
    public function filter($original)
    {
        $this->setID($original->getID());
        $this->setNome(Filter::string($this->getNome()));
        $this->setSimbolo(Filter::string($this->getSimbolo()));
        $this->setCodigo(Filter::string($this->getCodigo()));
        $this->setDivisao(Filter::number($this->getDivisao()));
        $this->setFracao(Filter::string($this->getFracao()));
        $this->setFormato(Filter::string($this->getFormato()));
        $this->setConversao(Filter::float($this->getConversao()));
        $this->setDataAtualizacao(Filter::datetime($this->getDataAtualizacao()));
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
     * @return self[] All field of Moeda in array format
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getNome())) {
            $errors['nome'] = 'O nome não pode ser vazio';
        }
        if (is_null($this->getSimbolo())) {
            $errors['simbolo'] = 'O símbolo não pode ser vazio';
        }
        if (is_null($this->getCodigo())) {
            $errors['codigo'] = 'O código não pode ser vazio';
        }
        if (is_null($this->getDivisao())) {
            $errors['divisao'] = 'A divisão não pode ser vazia';
        }
        if (is_null($this->getFormato())) {
            $errors['formato'] = 'O formato não pode ser vazio';
        }
        if (!Validator::checkBoolean($this->getAtiva())) {
            $errors['ativa'] = 'A ativa é inválida';
        }
        if (!empty($errors)) {
            throw new ValidationException($errors);
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
        if (contains(['ID', 'UNIQUE'], $e->getMessage())) {
            return new ValidationException([
                'id' => sprintf(
                    'O id "%s" já está cadastrado',
                    $this->getID()
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Insert a new Moeda into the database and fill instance from database
     * @return self Self instance
     */
    public function insert()
    {
        $this->setID(null);
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = DB::insertInto('Moedas')->values($values)->execute();
            $this->loadByID($id);
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Moeda with instance values into database for ID
     * @param  array $only Save these fields only, when empty save all fields except id
     * @return int rows affected
     */
    public function update($only = [])
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador da moeda não foi informado');
        }
        $values = DB::filterValues($values, $only, false);
        try {
            $affected = DB::update('Moedas')
                ->set($values)
                ->where(['id' => $this->getID()])
                ->execute();
            $this->loadByID($this->getID());
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
            throw new \Exception('O identificador da moeda não foi informado');
        }
        $result = DB::deleteFrom('Moedas')
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
     * Get allowed keys array
     * @return array allowed keys array
     */
    private static function getAllowedKeys()
    {
        $moeda = new self();
        $allowed = Filter::concatKeys('m.', $moeda->toArray());
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
        if (isset($condition['search'])) {
            $search = $condition['search'];
            $field = '(m.nome LIKE ? OR m.codigo = ?)';
            $condition[$field] = ['%'.$search.'%', $search];
            $allowed[$field] = true;
            unset($condition['search']);
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
        $query = DB::from('Moedas m');
        $condition = self::filterCondition($condition);
        $query = DB::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('m.nome ASC');
        $query = $query->orderBy('m.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order order rows
     * @return self A filled Moeda or empty instance
     */
    public static function find($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return new self($row);
    }

    /**
     * Find all Moeda
     * @param  array  $condition Condition to get all Moeda
     * @param  array  $order     Order Moeda
     * @param  int    $limit     Limit data into row count
     * @param  int    $offset    Start offset to get rows
     * @return self[]             List of all rows instanced as Moeda
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
