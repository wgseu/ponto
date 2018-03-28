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
 * @author  Francimar Alves <mazinsw@gmail.com>
 */
namespace MZ\Location;

use MZ\Util\Filter;
use MZ\Util\Validator;

/**
 * Bairro de uma cidade
 */
class Bairro extends \MZ\Database\Helper
{

    /**
     * Identificador do bairro
     */
    private $id;
    /**
     * Cidade a qual o bairro pertence
     */
    private $cidade_id;
    /**
     * Nome do bairro
     */
    private $nome;
    /**
     * Valor cobrado para entregar um pedido nesse bairro
     */
    private $valor_entrega;
    /**
     * Informa se o bairro está disponível para entrega de pedidos
     */
    private $disponivel;

    /**
     * Constructor for a new empty instance of Bairro
     * @param array $bairro All field and values to fill the instance
     */
    public function __construct($bairro = [])
    {
        parent::__construct($bairro);
    }

    /**
     * Identificador do bairro
     * @return mixed ID of Bairro
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param  mixed $id new value for ID
     * @return Bairro Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Cidade a qual o bairro pertence
     * @return mixed Cidade of Bairro
     */
    public function getCidadeID()
    {
        return $this->cidade_id;
    }

    /**
     * Set CidadeID value to new on param
     * @param  mixed $cidade_id new value for CidadeID
     * @return Bairro Self instance
     */
    public function setCidadeID($cidade_id)
    {
        $this->cidade_id = $cidade_id;
        return $this;
    }

    /**
     * Nome do bairro
     * @return mixed Nome of Bairro
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set Nome value to new on param
     * @param  mixed $nome new value for Nome
     * @return Bairro Self instance
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * Valor cobrado para entregar um pedido nesse bairro
     * @return mixed Valor da entrega of Bairro
     */
    public function getValorEntrega()
    {
        return $this->valor_entrega;
    }

    /**
     * Set ValorEntrega value to new on param
     * @param  mixed $valor_entrega new value for ValorEntrega
     * @return Bairro Self instance
     */
    public function setValorEntrega($valor_entrega)
    {
        $this->valor_entrega = $valor_entrega;
        return $this;
    }

    /**
     * Informa se o bairro está disponível para entrega de pedidos
     * @return mixed Disponível of Bairro
     */
    public function getDisponivel()
    {
        return $this->disponivel;
    }

    /**
     * Informa se o bairro está disponível para entrega de pedidos
     * @return boolean Check if o of Disponivel is selected or checked
     */
    public function isDisponivel()
    {
        return $this->disponivel == 'Y';
    }

    /**
     * Set Disponivel value to new on param
     * @param  mixed $disponivel new value for Disponivel
     * @return Bairro Self instance
     */
    public function setDisponivel($disponivel)
    {
        $this->disponivel = $disponivel;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param  boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $bairro = parent::toArray($recursive);
        $bairro['id'] = $this->getID();
        $bairro['cidadeid'] = $this->getCidadeID();
        $bairro['nome'] = $this->getNome();
        $bairro['valorentrega'] = $this->getValorEntrega();
        $bairro['disponivel'] = $this->getDisponivel();
        return $bairro;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param  mixed $bairro Associated key -> value to assign into this instance
     * @return Bairro Self instance
     */
    public function fromArray($bairro = [])
    {
        if ($bairro instanceof Bairro) {
            $bairro = $bairro->toArray();
        } elseif (!is_array($bairro)) {
            $bairro = [];
        }
        parent::fromArray($bairro);
        if (!isset($bairro['id'])) {
            $this->setID(null);
        } else {
            $this->setID($bairro['id']);
        }
        if (!isset($bairro['cidadeid'])) {
            $this->setCidadeID(null);
        } else {
            $this->setCidadeID($bairro['cidadeid']);
        }
        if (!isset($bairro['nome'])) {
            $this->setNome(null);
        } else {
            $this->setNome($bairro['nome']);
        }
        if (!isset($bairro['valorentrega'])) {
            $this->setValorEntrega(null);
        } else {
            $this->setValorEntrega($bairro['valorentrega']);
        }
        if (!isset($bairro['disponivel'])) {
            $this->setDisponivel('Y');
        } else {
            $this->setDisponivel($bairro['disponivel']);
        }
        return $this;
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $bairro = parent::publish();
        return $bairro;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param Bairro $original Original instance without modifications
     */
    public function filter($original)
    {
        $this->setID($original->getID());
        $this->setCidadeID(Filter::number($this->getCidadeID()));
        $this->setNome(Filter::string($this->getNome()));
        $this->setValorEntrega(Filter::money($this->getValorEntrega()));
    }

    /**
     * Clean instance resources like images and docs
     * @param  Bairro $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Bairro in array format
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getCidadeID())) {
            $errors['cidadeid'] = 'A cidade não pode ser vazia';
        }
        if (is_null($this->getNome())) {
            $errors['nome'] = 'O nome não pode ser vazio';
        }
        if (is_null($this->getValorEntrega())) {
            $errors['valorentrega'] = 'O Valor da entrega não pode ser vazio';
        } elseif ($this->getValorEntrega() < 0) {
            $errors['valorentrega'] = 'O valor da entrega não pode ser negativo';
        }
        if (is_null($this->getDisponivel())) {
            $this->setDisponivel('N');
        }
        if (!array_key_exists($this->getDisponivel(), self::getBooleanOptions())) {
            $errors['disponivel'] = 'A disponibilidade de entrega é inválida';
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
        if (stripos($e->getMessage(), 'PRIMARY') !== false) {
            return new \MZ\Exception\ValidationException([
                'id' => vsprintf(
                    'O ID "%s" já está cadastrado',
                    [$this->getID()]
                ),
            ]);
        }
        if (stripos($e->getMessage(), 'CidadeID_Nome_UNIQUE') !== false) {
            return new \MZ\Exception\ValidationException([
                'cidadeid' => vsprintf(
                    'A cidade "%s" já está cadastrada',
                    [$this->getCidadeID()]
                ),
                'nome' => vsprintf(
                    'O nome "%s" já está cadastrado',
                    [$this->getNome()]
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Find this object on database using, ID
     * @param  int $id id to find Bairro
     * @return Bairro A filled instance or empty when not found
     */
    public static function findByID($id)
    {
        return self::find([
            'id' => intval($id),
        ]);
    }

    /**
     * Find this object on database using, CidadeID, Nome
     * @param  int $cidade_id cidade to find Bairro
     * @param  string $nome nome to find Bairro
     * @return Bairro A filled instance or empty when not found
     */
    public static function findByCidadeIDNome($cidade_id, $nome)
    {
        return self::find([
            'cidadeid' => intval($cidade_id),
            'nome' => strval($nome),
        ]);
    }

    /**
     * Find district with that name or register a new one
     * @param  int $cidade_id cidade to find Bairro
     * @param  string $nome nome to find Bairro
     * @return Bairro A filled instance
     */
    public static function findOrInsert($cidade_id, $nome)
    {
        $bairro = self::findByCidadeIDNome(
            $cidade_id,
            Filter::string($nome)
        );
        if ($bairro->exists()) {
            return $bairro;
        }
        global $login_funcionario;
        if (!$login_funcionario->has(\Permissao::NOME_CADASTROBAIRROS)) {
            throw new \Exception('O bairro não está cadastrada e você não tem permissão para cadastrar um');
        }
        $bairro->setCidadeID($cidade_id);
        $bairro->setNome($nome);
        $bairro->setValorEntrega(0.0);
        $bairro->filter(new Bairro());
        return $bairro->insert();
    }

    /**
     * Get allowed keys array
     * @return array allowed keys array
     */
    private static function getAllowedKeys()
    {
        $bairro = new Bairro();
        $allowed = Filter::concatKeys('b.', $bairro->toArray());
        $allowed['e.paisid'] = true;
        $allowed['c.estadoid'] = true;
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
        return Filter::orderBy($order, $allowed, ['b.', 'c.', 'e.']);
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
            $field = 'b.nome LIKE ?';
            $condition[$field] = '%'.$search.'%';
            $allowed[$field] = true;
            unset($condition['search']);
        }
        return Filter::keys($condition, $allowed, ['b.', 'c.', 'e.']);
    }

    /**
     * Fetch data from database with a condition
     * @param  array $condition condition to filter rows
     * @param  array $order order rows
     * @return SelectQuery query object with condition statement
     */
    private static function query($condition = [], $order = [])
    {
        $query = self::getDB()->from('Bairros b')
            ->leftJoin('Cidades c ON c.id = b.cidadeid')
            ->leftJoin('Estados e ON e.id = c.estadoid');
        $condition = self::filterCondition($condition);
        $query = self::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('b.nome ASC');
        $query = $query->orderBy('b.id ASC');
        return self::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order order rows
     * @return Bairro A filled Bairro or empty instance
     */
    public static function find($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch();
        if ($row === false) {
            $row = [];
        }
        return new Bairro($row);
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
            $result[] = new Bairro($row);
        }
        return $result;
    }

    /**
     * Insert a new Bairro into the database and fill instance from database
     * @return Bairro Self instance
     */
    public function insert()
    {
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = self::getDB()->insertInto('Bairros')->values($values)->execute();
            $bairro = self::findByID($id);
            $this->fromArray($bairro->toArray());
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Bairro with instance values into database for ID
     * @return Bairro Self instance
     */
    public function update()
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador do bairro não foi informado');
        }
        unset($values['id']);
        try {
            self::getDB()
                ->update('Bairros')
                ->set($values)
                ->where('id', $this->getID())
                ->execute();
            $bairro = self::findByID($this->getID());
            $this->fromArray($bairro->toArray());
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Save the Bairro into the database
     * @return Bairro Self instance
     */
    public function save()
    {
        if ($this->exists()) {
            return $this->update();
        }
        return $this->insert();
    }

    /**
     * Delete this instance from database using ID
     * @return integer Number of rows deleted (Max 1)
     */
    public function delete()
    {
        if (!$this->exists()) {
            throw new \Exception('O identificador do bairro não foi informado');
        }
        $result = self::getDB()
            ->deleteFrom('Bairros')
            ->where('id', $this->getID())
            ->execute();
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

    /**
     * Cidade a qual o bairro pertence
     * @return \MZ\Location\Cidade The object fetched from database
     */
    public function findCidadeID()
    {
        return \MZ\Location\Cidade::findByID($this->getCidadeID());
    }
}
