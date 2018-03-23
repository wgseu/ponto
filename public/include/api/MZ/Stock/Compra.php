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
namespace MZ\Stock;

use MZ\Util\Filter;
use MZ\Util\Validator;

/**
 * Compras realizadas em uma lista num determinado fornecedor
 */
class Compra extends \MZ\Database\Helper
{

    /**
     * Identificador da compra
     */
    private $id;
    /**
     * Informa o número fiscal da compra
     */
    private $numero;
    /**
     * Informa o funcionário que comprou os produtos da lista
     */
    private $comprador_id;
    /**
     * Fornecedor em que os produtos foram compras
     */
    private $fornecedor_id;
    /**
     * Informa o nome do documento no servidor do sistema
     */
    private $documento_url;
    /**
     * Informa da data de finalização da compra
     */
    private $data_compra;

    /**
     * Constructor for a new empty instance of Compra
     * @param array $compra All field and values to fill the instance
     */
    public function __construct($compra = [])
    {
        parent::__construct($compra);
    }

    /**
     * Identificador da compra
     * @return mixed ID of Compra
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param  mixed $id new value for ID
     * @return Compra Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Informa o número fiscal da compra
     * @return mixed Número da compra of Compra
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set Numero value to new on param
     * @param  mixed $numero new value for Numero
     * @return Compra Self instance
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;
        return $this;
    }

    /**
     * Informa o funcionário que comprou os produtos da lista
     * @return mixed Comprador of Compra
     */
    public function getCompradorID()
    {
        return $this->comprador_id;
    }

    /**
     * Set CompradorID value to new on param
     * @param  mixed $comprador_id new value for CompradorID
     * @return Compra Self instance
     */
    public function setCompradorID($comprador_id)
    {
        $this->comprador_id = $comprador_id;
        return $this;
    }

    /**
     * Fornecedor em que os produtos foram compras
     * @return mixed Fornecedor of Compra
     */
    public function getFornecedorID()
    {
        return $this->fornecedor_id;
    }

    /**
     * Set FornecedorID value to new on param
     * @param  mixed $fornecedor_id new value for FornecedorID
     * @return Compra Self instance
     */
    public function setFornecedorID($fornecedor_id)
    {
        $this->fornecedor_id = $fornecedor_id;
        return $this;
    }

    /**
     * Informa o nome do documento no servidor do sistema
     * @return mixed Documento of Compra
     */
    public function getDocumentoURL()
    {
        return $this->documento_url;
    }

    /**
     * Set DocumentoURL value to new on param
     * @param  mixed $documento_url new value for DocumentoURL
     * @return Compra Self instance
     */
    public function setDocumentoURL($documento_url)
    {
        $this->documento_url = $documento_url;
        return $this;
    }

    /**
     * Informa da data de finalização da compra
     * @return mixed Data da compra of Compra
     */
    public function getDataCompra()
    {
        return $this->data_compra;
    }

    /**
     * Set DataCompra value to new on param
     * @param  mixed $data_compra new value for DataCompra
     * @return Compra Self instance
     */
    public function setDataCompra($data_compra)
    {
        $this->data_compra = $data_compra;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param  boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $compra = parent::toArray($recursive);
        $compra['id'] = $this->getID();
        $compra['numero'] = $this->getNumero();
        $compra['compradorid'] = $this->getCompradorID();
        $compra['fornecedorid'] = $this->getFornecedorID();
        $compra['documentourl'] = $this->getDocumentoURL();
        $compra['datacompra'] = $this->getDataCompra();
        return $compra;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param  mixed $compra Associated key -> value to assign into this instance
     * @return Compra Self instance
     */
    public function fromArray($compra = [])
    {
        if ($compra instanceof Compra) {
            $compra = $compra->toArray();
        } elseif (!is_array($compra)) {
            $compra = [];
        }
        parent::fromArray($compra);
        if (!isset($compra['id'])) {
            $this->setID(null);
        } else {
            $this->setID($compra['id']);
        }
        if (!array_key_exists('numero', $compra)) {
            $this->setNumero(null);
        } else {
            $this->setNumero($compra['numero']);
        }
        if (!isset($compra['compradorid'])) {
            $this->setCompradorID(null);
        } else {
            $this->setCompradorID($compra['compradorid']);
        }
        if (!isset($compra['fornecedorid'])) {
            $this->setFornecedorID(null);
        } else {
            $this->setFornecedorID($compra['fornecedorid']);
        }
        if (!array_key_exists('documentourl', $compra)) {
            $this->setDocumentoURL(null);
        } else {
            $this->setDocumentoURL($compra['documentourl']);
        }
        if (!isset($compra['datacompra'])) {
            $this->setDataCompra(null);
        } else {
            $this->setDataCompra($compra['datacompra']);
        }
        return $this;
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $compra = parent::publish();
        return $compra;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param Compra $original Original instance without modifications
     */
    public function filter($original)
    {
        $this->setID($original->getID());
        $this->setNumero(Filter::string($this->getNumero()));
        $this->setCompradorID(Filter::number($this->getCompradorID()));
        $this->setFornecedorID(Filter::number($this->getFornecedorID()));
        $this->setDocumentoURL(Filter::string($this->getDocumentoURL()));
        $this->setDataCompra(Filter::datetime($this->getDataCompra()));
    }

    /**
     * Clean instance resources like images and docs
     * @param  Compra $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Compra in array format
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getCompradorID())) {
            $errors['compradorid'] = 'O comprador não pode ser vazio';
        }
        if (is_null($this->getFornecedorID())) {
            $errors['fornecedorid'] = 'O fornecedor não pode ser vazio';
        }
        if (is_null($this->getDataCompra())) {
            $errors['datacompra'] = 'A data da compra não pode ser vazia';
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
        if (stripos($e->getMessage(), 'Numero_UNIQUE') !== false) {
            return new \MZ\Exception\ValidationException([
                'numero' => sprintf(
                    'O número da compra "%s" já está cadastrado',
                    $this->getNumero()
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Insert a new Compra into the database and fill instance from database
     * @return Compra Self instance
     */
    public function insert()
    {
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = self::getDB()->insertInto('Compras')->values($values)->execute();
            $compra = self::findByID($id);
            $this->fromArray($compra->toArray());
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Compra with instance values into database for ID
     * @return Compra Self instance
     */
    public function update()
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador da compra não foi informado');
        }
        unset($values['id']);
        try {
            self::getDB()
                ->update('Compras')
                ->set($values)
                ->where('id', $this->getID())
                ->execute();
            $compra = self::findByID($this->getID());
            $this->fromArray($compra->toArray());
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
            throw new \Exception('O identificador da compra não foi informado');
        }
        $result = self::getDB()
            ->deleteFrom('Compras')
            ->where('id', $this->getID())
            ->execute();
        return $result;
    }

    /**
     * Load one register for it self with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order associative field name -> [-1, 1]
     * @return Compra Self instance filled or empty
     */
    public function load($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return $this->fromArray($row);
    }

    /**
     * Load into this object from database using, ID
     * @param  int $id id to find Compra
     * @return Compra Self filled instance or empty when not found
     */
    public function loadByID($id)
    {
        return $this->load([
            'id' => intval($id),
        ]);
    }

    /**
     * Load into this object from database using, Numero
     * @param  string $numero número da compra to find Compra
     * @return Compra Self filled instance or empty when not found
     */
    public function loadByNumero($numero)
    {
        return $this->load([
            'numero' => strval($numero),
        ]);
    }

    /**
     * Informa o funcionário que comprou os produtos da lista
     * @return \MZ\Employee\Funcionario The object fetched from database
     */
    public function findCompradorID()
    {
        return \MZ\Employee\Funcionario::findByID($this->getCompradorID());
    }

    /**
     * Fornecedor em que os produtos foram compras
     * @return \MZ\Stock\Fornecedor The object fetched from database
     */
    public function findFornecedorID()
    {
        return \MZ\Stock\Fornecedor::findByID($this->getFornecedorID());
    }

    /**
     * Get allowed keys array
     * @return array allowed keys array
     */
    private static function getAllowedKeys()
    {
        $compra = new Compra();
        $allowed = Filter::concatKeys('c.', $compra->toArray());
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
        return Filter::orderBy($order, $allowed, 'c.');
    }

    /**
     * Filter condition array with allowed fields
     * @param  array $condition condition to filter rows
     * @return array allowed condition
     */
    private static function filterCondition($condition)
    {
        $allowed = self::getAllowedKeys();
        return Filter::keys($condition, $allowed, 'c.');
    }

    /**
     * Fetch data from database with a condition
     * @param  array $condition condition to filter rows
     * @param  array $order order rows
     * @return SelectQuery query object with condition statement
     */
    private static function query($condition = [], $order = [])
    {
        $query = self::getDB()->from('Compras c');
        $condition = self::filterCondition($condition);
        $query = self::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('c.id ASC');
        return self::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order order rows
     * @return Compra A filled Compra or empty instance
     */
    public static function find($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return new Compra($row);
    }

    /**
     * Find this object on database using, ID
     * @param  int $id id to find Compra
     * @return Compra A filled instance or empty when not found
     */
    public static function findByID($id)
    {
        return self::find([
            'id' => intval($id),
        ]);
    }

    /**
     * Find this object on database using, Numero
     * @param  string $numero número da compra to find Compra
     * @return Compra A filled instance or empty when not found
     */
    public static function findByNumero($numero)
    {
        return self::find([
            'numero' => strval($numero),
        ]);
    }

    /**
     * Find all Compra
     * @param  array  $condition Condition to get all Compra
     * @param  array  $order     Order Compra
     * @param  int    $limit     Limit data into row count
     * @param  int    $offset    Start offset to get rows
     * @return array             List of all rows instanced as Compra
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
            $result[] = new Compra($row);
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
