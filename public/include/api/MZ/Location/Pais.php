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
namespace MZ\Location;

use MZ\Util\Filter;
use MZ\Util\Validator;

/**
 * Informações de um páis com sua moeda e língua nativa
 */
class Pais extends \MZ\Database\Helper
{

    /**
     * Identificador do país
     */
    private $id;
    /**
     * Nome do país
     */
    private $nome;
    /**
     * Abreviação do nome do país
     */
    private $sigla;
    /**
     * Código do país com 2 letras
     */
    private $codigo;
    /**
     * Informa a moeda principal do país
     */
    private $moeda_id;
    /**
     * Index da imagem da bandeira do país
     */
    private $bandeira_index;
    /**
     * Linguagem nativa do país
     */
    private $linguagem_id;
    /**
     * Frases, nomes de campos e máscaras específicas do país
     */
    private $entradas;
    /**
     * Informa se o país tem apenas um estado federativo
     */
    private $unitario;

    /**
     * Constructor for a new empty instance of Pais
     * @param array $pais All field and values to fill the instance
     */
    public function __construct($pais = [])
    {
        parent::__construct($pais);
    }

    /**
     * Identificador do país
     * @return mixed ID of Pais
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param  mixed $id new value for ID
     * @return Pais Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Nome do país
     * @return mixed Nome of Pais
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set Nome value to new on param
     * @param  mixed $nome new value for Nome
     * @return Pais Self instance
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * Abreviação do nome do país
     * @return mixed Sigla of Pais
     */
    public function getSigla()
    {
        return $this->sigla;
    }

    /**
     * Set Sigla value to new on param
     * @param  mixed $sigla new value for Sigla
     * @return Pais Self instance
     */
    public function setSigla($sigla)
    {
        $this->sigla = $sigla;
        return $this;
    }

    /**
     * Código do país com 2 letras
     * @return mixed Código of Pais
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set Codigo value to new on param
     * @param  mixed $codigo new value for Codigo
     * @return Pais Self instance
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
        return $this;
    }

    /**
     * Informa a moeda principal do país
     * @return mixed Moeda of Pais
     */
    public function getMoedaID()
    {
        return $this->moeda_id;
    }

    /**
     * Set MoedaID value to new on param
     * @param  mixed $moeda_id new value for MoedaID
     * @return Pais Self instance
     */
    public function setMoedaID($moeda_id)
    {
        $this->moeda_id = $moeda_id;
        return $this;
    }

    /**
     * Index da imagem da bandeira do país
     * @return mixed Bandeira of Pais
     */
    public function getBandeiraIndex()
    {
        return $this->bandeira_index;
    }

    /**
     * Set BandeiraIndex value to new on param
     * @param  mixed $bandeira_index new value for BandeiraIndex
     * @return Pais Self instance
     */
    public function setBandeiraIndex($bandeira_index)
    {
        $this->bandeira_index = $bandeira_index;
        return $this;
    }

    /**
     * Linguagem nativa do país
     * @return mixed Linguagem ID of Pais
     */
    public function getLinguagemID()
    {
        return $this->linguagem_id;
    }

    /**
     * Set LinguagemID value to new on param
     * @param  mixed $linguagem_id new value for LinguagemID
     * @return Pais Self instance
     */
    public function setLinguagemID($linguagem_id)
    {
        $this->linguagem_id = $linguagem_id;
        return $this;
    }

    /**
     * Frases, nomes de campos e máscaras específicas do país
     * @return mixed Entrada of Pais
     */
    public function getEntradas()
    {
        return $this->entradas;
    }

    /**
     * Set Entradas value to new on param
     * @param  mixed $entradas new value for Entradas
     * @return Pais Self instance
     */
    public function setEntradas($entradas)
    {
        $this->entradas = $entradas;
        return $this;
    }

    /**
     * Informa se o país tem apenas um estado federativo
     * @return mixed Unitário of Pais
     */
    public function getUnitario()
    {
        return $this->unitario;
    }

    /**
     * Informa se o país tem apenas um estado federativo
     * @return boolean Check if o of Unitario is selected or checked
     */
    public function isUnitario()
    {
        return $this->unitario == 'Y';
    }

    /**
     * Set Unitario value to new on param
     * @param  mixed $unitario new value for Unitario
     * @return Pais Self instance
     */
    public function setUnitario($unitario)
    {
        $this->unitario = $unitario;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param  boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $pais = parent::toArray($recursive);
        $pais['id'] = $this->getID();
        $pais['nome'] = $this->getNome();
        $pais['sigla'] = $this->getSigla();
        $pais['codigo'] = $this->getCodigo();
        $pais['moedaid'] = $this->getMoedaID();
        $pais['bandeiraindex'] = $this->getBandeiraIndex();
        $pais['linguagemid'] = $this->getLinguagemID();
        $pais['entradas'] = $this->getEntradas();
        $pais['unitario'] = $this->getUnitario();
        return $pais;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param  mixed $pais Associated key -> value to assign into this instance
     * @return Pais Self instance
     */
    public function fromArray($pais = [])
    {
        if ($pais instanceof Pais) {
            $pais = $pais->toArray();
        } elseif (!is_array($pais)) {
            $pais = [];
        }
        parent::fromArray($pais);
        if (!isset($pais['id'])) {
            $this->setID(null);
        } else {
            $this->setID($pais['id']);
        }
        if (!isset($pais['nome'])) {
            $this->setNome(null);
        } else {
            $this->setNome($pais['nome']);
        }
        if (!isset($pais['sigla'])) {
            $this->setSigla(null);
        } else {
            $this->setSigla($pais['sigla']);
        }
        if (!isset($pais['codigo'])) {
            $this->setCodigo(null);
        } else {
            $this->setCodigo($pais['codigo']);
        }
        if (!isset($pais['moedaid'])) {
            $this->setMoedaID(null);
        } else {
            $this->setMoedaID($pais['moedaid']);
        }
        if (!isset($pais['bandeiraindex'])) {
            $this->setBandeiraIndex(null);
        } else {
            $this->setBandeiraIndex($pais['bandeiraindex']);
        }
        if (!isset($pais['linguagemid'])) {
            $this->setLinguagemID(null);
        } else {
            $this->setLinguagemID($pais['linguagemid']);
        }
        if (!array_key_exists('entradas', $pais)) {
            $this->setEntradas(null);
        } else {
            $this->setEntradas($pais['entradas']);
        }
        if (!isset($pais['unitario'])) {
            $this->setUnitario('N');
        } else {
            $this->setUnitario($pais['unitario']);
        }
        return $this;
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $pais = parent::publish();
        return $pais;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param Pais $original Original instance without modifications
     */
    public function filter($original)
    {
        $this->setID($original->getID());
        $this->setNome(Filter::string($this->getNome()));
        $this->setSigla(Filter::string($this->getSigla()));
        $this->setCodigo(Filter::string($this->getCodigo()));
        $this->setMoedaID(Filter::number($this->getMoedaID()));
        $this->setBandeiraIndex(Filter::number($this->getBandeiraIndex()));
        $this->setLinguagemID(Filter::number($this->getLinguagemID()));
        $this->setEntradas(Filter::text($this->getEntradas()));
    }

    /**
     * Clean instance resources like images and docs
     * @param  Pais $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Pais in array format
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getNome())) {
            $errors['nome'] = 'O nome não pode ser vazio';
        }
        if (is_null($this->getSigla())) {
            $errors['sigla'] = 'A sigla não pode ser vazia';
        }
        if (is_null($this->getCodigo())) {
            $errors['codigo'] = 'O código não pode ser vazio';
        }
        if (is_null($this->getMoedaID())) {
            $errors['moedaid'] = 'A moeda não pode ser vazia';
        }
        if (is_null($this->getBandeiraIndex())) {
            $errors['bandeiraindex'] = 'A bandeira não pode ser vazia';
        }
        if ($this->getBandeiraIndex() < 0 || $this->getBandeiraIndex() > 237) {
            $errors['bandeiraindex'] = 'A bandeira informada é inválida';
        }
        if (is_null($this->getLinguagemID())) {
            $errors['linguagemid'] = 'O id da linguagem não pode ser vazio';
        }
        if (is_null($this->getUnitario())) {
            $this->setUnitario('N');
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
                'nome' => vsprintf(
                    'O Nome "%s" já está cadastrado',
                    [$this->getNome()]
                ),
            ]);
        }
        if (contains(['Sigla', 'UNIQUE'], $e->getMessage())) {
            return new \MZ\Exception\ValidationException([
                'sigla' => vsprintf(
                    'A Sigla "%s" já está cadastrada',
                    [$this->getSigla()]
                ),
            ]);
        }
        if (contains(['Codigo', 'UNIQUE'], $e->getMessage())) {
            return new \MZ\Exception\ValidationException([
                'codigo' => vsprintf(
                    'O Código "%s" já está cadastrado',
                    [$this->getCodigo()]
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Find this object on database using, ID
     * @param  int $id id to find País
     * @return Pais A filled instance or empty when not found
     */
    public static function findByID($id)
    {
        return self::find([
            'id' => intval($id),
        ]);
    }

    /**
     * Find this object on database using, Nome
     * @param  string $nome nome to find País
     * @return Pais A filled instance or empty when not found
     */
    public static function findByNome($nome)
    {
        return self::find([
            'nome' => strval($nome),
        ]);
    }

    /**
     * Find this object on database using, Sigla
     * @param  string $sigla sigla to find País
     * @return Pais A filled instance or empty when not found
     */
    public static function findBySigla($sigla)
    {
        return self::find([
            'sigla' => strval($sigla),
        ]);
    }

    /**
     * Find this object on database using, Codigo
     * @param  string $codigo código to find País
     * @return Pais A filled instance or empty when not found
     */
    public static function findByCodigo($codigo)
    {
        return self::find([
            'codigo' => strval($codigo),
        ]);
    }

    /**
     * Filter order array
     * @param  mixed $order order string or array to parse and filter allowed
     * @return array allowed associative order
     */
    private static function filterOrder($order)
    {
        $pais = new Pais();
        $allowed = $pais->toArray();
        return Filter::orderBy($order, $allowed);
    }

    /**
     * Filter condition array with allowed fields
     * @param  array $condition condition to filter rows
     * @return array allowed condition
     */
    private static function filterCondition($condition)
    {
        $pais = new Pais();
        $allowed = $pais->toArray();
        if (isset($condition['search'])) {
            $search = $condition['search'];
            $field = '(nome LIKE ? OR sigla = ? OR codigo = ?)';
            $condition[$field] = ['%'.$search.'%', $search, $search];
            $allowed[$field] = true;
            unset($condition['search']);
        }
        return Filter::keys($condition, $allowed);
    }

    /**
     * Fetch data from database with a condition
     * @param  array $condition condition to filter rows
     * @param  array $order order rows
     * @return SelectQuery query object with condition statement
     */
    private static function query($condition = [], $order = [])
    {
        $query = self::getDB()->from('Paises');
        $condition = self::filterCondition($condition);
        $query = self::buildOrderBy($query, self::filterOrder($order));
        return self::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order order rows
     * @return Pais A filled País or empty instance
     */
    public static function find($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch();
        if ($row === false) {
            $row = [];
        }
        return new Pais($row);
    }

    /**
     * Fetch all rows from database with matched condition critery
     * @param  array $condition condition to filter rows
     * @param  integer $limit number of rows to get, null for all
     * @param  integer $offset start index to get rows, null for begining
     * @return array All rows instanced and filled
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
            $result[] = new Pais($row);
        }
        return $result;
    }

    /**
     * Insert a new País into the database and fill instance from database
     * @return Pais Self instance
     */
    public function insert()
    {
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = self::getDB()->insertInto('Paises')->values($values)->execute();
            $pais = self::findByID($id);
            $this->fromArray($pais->toArray());
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update País with instance values into database for ID
     * @param  array $only Save these fields only, when empty save all fields except id
     * @param  boolean $except When true, saves all fields except $only
     * @return Pais Self instance
     */
    public function update($only = [], $except = false)
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador do país não foi informado');
        }
        $values = self::filterValues($values, $only, $except);
        try {
            self::getDB()
                ->update('Paises')
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
            throw new \Exception('O identificador do país não foi informado');
        }
        $result = self::getDB()
            ->deleteFrom('Paises')
            ->where('id', $this->getID())
            ->execute();
        return $result;
    }

    /**
     * Load one register for it self with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order associative field name -> [-1, 1]
     * @return Pais Self instance filled or empty
     */
    public function load($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return $this->fromArray($row);
    }

    /**
     * Load into this object from database using, Nome
     * @param  string $nome nome to find País
     * @return Pais Self filled instance or empty when not found
     */
    public function loadByNome($nome)
    {
        return $this->load([
            'nome' => strval($nome),
        ]);
    }

    /**
     * Load into this object from database using, Sigla
     * @param  string $sigla sigla to find País
     * @return Pais Self filled instance or empty when not found
     */
    public function loadBySigla($sigla)
    {
        return $this->load([
            'sigla' => strval($sigla),
        ]);
    }

    /**
     * Load into this object from database using, Codigo
     * @param  string $codigo código to find País
     * @return Pais Self filled instance or empty when not found
     */
    public function loadByCodigo($codigo)
    {
        return $this->load([
            'codigo' => strval($codigo),
        ]);
    }

    /**
     * Flag image index list
     */
    public static function getImageIndexOptions()
    {
        $images = [];
        for ($i = 0; $i < 238; $i++) {
            $images[] = ['index' => $i];
        }
        return $images;
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

    /**
     * Informa a moeda principal do país
     * @return \MZ\Wallet\Moeda The object fetched from database
     */
    public function findMoedaID()
    {
        return \MZ\Wallet\Moeda::findByID($this->getMoedaID());
    }
}
