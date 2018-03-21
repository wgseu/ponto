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
namespace MZ\Sale;

use MZ\Util\Filter;
use MZ\Util\Validator;

/**
 * Informa se há descontos nos produtos em determinados dias da semana, o
 * preço pode subir ou descer
 */
class Promocao extends \MZ\Database\Helper
{

    /**
     * Identificador da promoção
     */
    private $id;
    /**
     * Informa qual o produto que possui desconto ou acréscimo
     */
    private $produto_id;
    /**
     * Dia inicial em que o produto começa a sofrer alteração de preço
     */
    private $inicio;
    /**
     * Dia final em que o produto deixará de estar na promoção
     */
    private $fim;
    /**
     * Acréscimo ou desconto aplicado ao produto produto
     */
    private $valor;
    /**
     * Informa se deve proibir a venda desse produto no período informado
     */
    private $proibir;

    /**
     * Constructor for a new empty instance of Promocao
     * @param array $promocao All field and values to fill the instance
     */
    public function __construct($promocao = [])
    {
        parent::__construct($promocao);
    }

    /**
     * Identificador da promoção
     * @return mixed ID of Promocao
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param  mixed $id new value for ID
     * @return Promocao Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Informa qual o produto que possui desconto ou acréscimo
     * @return mixed Produto of Promocao
     */
    public function getProdutoID()
    {
        return $this->produto_id;
    }

    /**
     * Set ProdutoID value to new on param
     * @param  mixed $produto_id new value for ProdutoID
     * @return Promocao Self instance
     */
    public function setProdutoID($produto_id)
    {
        $this->produto_id = $produto_id;
        return $this;
    }

    /**
     * Dia inicial em que o produto começa a sofrer alteração de preço
     * @return mixed Dia inicial of Promocao
     */
    public function getInicio()
    {
        return $this->inicio;
    }

    /**
     * Set Inicio value to new on param
     * @param  mixed $inicio new value for Inicio
     * @return Promocao Self instance
     */
    public function setInicio($inicio)
    {
        $this->inicio = $inicio;
        return $this;
    }

    /**
     * Dia final em que o produto deixará de estar na promoção
     * @return mixed Dia final of Promocao
     */
    public function getFim()
    {
        return $this->fim;
    }

    /**
     * Set Fim value to new on param
     * @param  mixed $fim new value for Fim
     * @return Promocao Self instance
     */
    public function setFim($fim)
    {
        $this->fim = $fim;
        return $this;
    }

    /**
     * Acréscimo ou desconto aplicado ao produto produto
     * @return mixed Valor of Promocao
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Set Valor value to new on param
     * @param  mixed $valor new value for Valor
     * @return Promocao Self instance
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
        return $this;
    }

    /**
     * Informa se deve proibir a venda desse produto no período informado
     * @return mixed Proibir a venda of Promocao
     */
    public function getProibir()
    {
        return $this->proibir;
    }

    /**
     * Informa se deve proibir a venda desse produto no período informado
     * @return boolean Check if a of Proibir is selected or checked
     */
    public function isProibir()
    {
        return $this->proibir == 'Y';
    }

    /**
     * Set Proibir value to new on param
     * @param  mixed $proibir new value for Proibir
     * @return Promocao Self instance
     */
    public function setProibir($proibir)
    {
        $this->proibir = $proibir;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param  boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $promocao = parent::toArray($recursive);
        $promocao['id'] = $this->getID();
        $promocao['produtoid'] = $this->getProdutoID();
        $promocao['inicio'] = $this->getInicio();
        $promocao['fim'] = $this->getFim();
        $promocao['valor'] = $this->getValor();
        $promocao['proibir'] = $this->getProibir();
        return $promocao;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param  mixed $promocao Associated key -> value to assign into this instance
     * @return Promocao Self instance
     */
    public function fromArray($promocao = [])
    {
        if ($promocao instanceof Promocao) {
            $promocao = $promocao->toArray();
        } elseif (!is_array($promocao)) {
            $promocao = [];
        }
        parent::fromArray($promocao);
        if (!isset($promocao['id'])) {
            $this->setID(null);
        } else {
            $this->setID($promocao['id']);
        }
        if (!isset($promocao['produtoid'])) {
            $this->setProdutoID(null);
        } else {
            $this->setProdutoID($promocao['produtoid']);
        }
        if (!isset($promocao['inicio'])) {
            $this->setInicio(null);
        } else {
            $this->setInicio($promocao['inicio']);
        }
        if (!isset($promocao['fim'])) {
            $this->setFim(null);
        } else {
            $this->setFim($promocao['fim']);
        }
        if (!isset($promocao['valor'])) {
            $this->setValor(null);
        } else {
            $this->setValor($promocao['valor']);
        }
        if (!isset($promocao['proibir'])) {
            $this->setProibir(null);
        } else {
            $this->setProibir($promocao['proibir']);
        }
        return $this;
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $promocao = parent::publish();
        return $promocao;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param Promocao $original Original instance without modifications
     */
    public function filter($original)
    {
        $this->setID($original->getID());
        $this->setProdutoID(Filter::number($this->getProdutoID()));
        $this->setInicio(Filter::number($this->getInicio()));
        $this->setFim(Filter::number($this->getFim()));
        $this->setValor(Filter::money($this->getValor()));
    }

    /**
     * Clean instance resources like images and docs
     * @param  Promocao $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Promocao in array format
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getProdutoID())) {
            $errors['produtoid'] = 'O produto não pode ser vazio';
        }
        if (is_null($this->getInicio())) {
            $errors['inicio'] = 'O dia inicial não pode ser vazio';
        }
        if (is_null($this->getFim())) {
            $errors['fim'] = 'O dia final não pode ser vazio';
        }
        if (is_null($this->getValor())) {
            $errors['valor'] = 'O valor não pode ser vazio';
        }
        if (is_null($this->getProibir())) {
            $errors['proibir'] = 'A proibir a venda não pode ser vazia';
        }
        if (!is_null($this->getProibir()) &&
            !array_key_exists($this->getProibir(), self::getBooleanOptions())
        ) {
            $errors['proibir'] = 'A proibir a venda é inválida';
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
                'id' => sprintf(
                    'O id "%s" já está cadastrado',
                    $this->getID()
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Find this object on database using, ID
     * @param  int $id id to find Promoção
     * @return Promocao A filled instance or empty when not found
     */
    public static function findByID($id)
    {
        return self::find([
            'id' => intval($id),
        ]);
    }

    /**
     * Get allowed keys array
     * @return array allowed keys array
     */
    private static function getAllowedKeys()
    {
        $promocao = new Promocao();
        $allowed = Filter::concatKeys('p.', $promocao->toArray());
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
        return Filter::orderBy($order, $allowed, 'p.');
    }

    /**
     * Filter condition array with allowed fields
     * @param  array $condition condition to filter rows
     * @return array allowed condition
     */
    private static function filterCondition($condition)
    {
        $allowed = self::getAllowedKeys();
        return Filter::keys($condition, $allowed, 'p.');
    }

    /**
     * Fetch data from database with a condition
     * @param  array $condition condition to filter rows
     * @param  array $order order rows
     * @return SelectQuery query object with condition statement
     */
    private static function query($condition = [], $order = [])
    {
        $query = self::getDB()->from('Promocoes p');
        $condition = self::filterCondition($condition);
        $query = self::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('p.id ASC');
        return self::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order order rows
     * @return Promocao A filled Promoção or empty instance
     */
    public static function find($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch();
        if ($row === false) {
            $row = [];
        }
        return new Promocao($row);
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
            $result[] = new Promocao($row);
        }
        return $result;
    }

    /**
     * Insert a new Promoção into the database and fill instance from database
     * @return Promocao Self instance
     */
    public function insert()
    {
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = self::getDB()->insertInto('Promocoes')->values($values)->execute();
            $promocao = self::findByID($id);
            $this->fromArray($promocao->toArray());
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Promoção with instance values into database for ID
     * @return Promocao Self instance
     */
    public function update()
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador da promoção não foi informado');
        }
        unset($values['id']);
        try {
            self::getDB()
                ->update('Promocoes')
                ->set($values)
                ->where('id', $this->getID())
                ->execute();
            $promocao = self::findByID($this->getID());
            $this->fromArray($promocao->toArray());
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Save the Promoção into the database
     * @return Promocao Self instance
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
            throw new \Exception('O identificador da promoção não foi informado');
        }
        $result = self::getDB()
            ->deleteFrom('Promocoes')
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
     * Informa qual o produto que possui desconto ou acréscimo
     * @return \MZ\Product\Produto The object fetched from database
     */
    public function findProdutoID()
    {
        return \MZ\Product\Produto::findByID($this->getProdutoID());
    }
}
