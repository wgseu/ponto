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
namespace MZ\Stock;

use MZ\Util\Mask;
use MZ\Util\Filter;
use MZ\Util\Validator;
use MZ\Database\DB;
use MZ\Database\SyncModel;
use MZ\Exception\ValidationException;

/**
 * Compras realizadas em uma lista num determinado fornecedor
 */
class Compra extends SyncModel
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
     * @return int id of Compra
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param int $id Set id for Compra
     * @return self Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Informa o número fiscal da compra
     * @return string número da compra of Compra
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set Numero value to new on param
     * @param string $numero Set número da compra for Compra
     * @return self Self instance
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;
        return $this;
    }

    /**
     * Informa o funcionário que comprou os produtos da lista
     * @return int comprador of Compra
     */
    public function getCompradorID()
    {
        return $this->comprador_id;
    }

    /**
     * Set CompradorID value to new on param
     * @param int $comprador_id Set comprador for Compra
     * @return self Self instance
     */
    public function setCompradorID($comprador_id)
    {
        $this->comprador_id = $comprador_id;
        return $this;
    }

    /**
     * Fornecedor em que os produtos foram compras
     * @return int fornecedor of Compra
     */
    public function getFornecedorID()
    {
        return $this->fornecedor_id;
    }

    /**
     * Set FornecedorID value to new on param
     * @param int $fornecedor_id Set fornecedor for Compra
     * @return self Self instance
     */
    public function setFornecedorID($fornecedor_id)
    {
        $this->fornecedor_id = $fornecedor_id;
        return $this;
    }

    /**
     * Informa o nome do documento no servidor do sistema
     * @return string documento of Compra
     */
    public function getDocumentoURL()
    {
        return $this->documento_url;
    }

    /**
     * Set DocumentoURL value to new on param
     * @param string $documento_url Set documento for Compra
     * @return self Self instance
     */
    public function setDocumentoURL($documento_url)
    {
        $this->documento_url = $documento_url;
        return $this;
    }

    /**
     * Informa da data de finalização da compra
     * @return string data da compra of Compra
     */
    public function getDataCompra()
    {
        return $this->data_compra;
    }

    /**
     * Set DataCompra value to new on param
     * @param string $data_compra Set data da compra for Compra
     * @return self Self instance
     */
    public function setDataCompra($data_compra)
    {
        $this->data_compra = $data_compra;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param boolean $recursive Allow rescursive conversion of fields
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
     * @param mixed $compra Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($compra = [])
    {
        if ($compra instanceof self) {
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
     * Get relative documento path or default documento
     * @param boolean $default If true return default image, otherwise check field
     * @param string  $default_name Default image name
     * @return string relative web path for compra documento
     */
    public function makeDocumentoURL($default = false, $default_name = 'compra.png')
    {
        $documento_url = $this->getDocumentoURL();
        if ($default) {
            $documento_url = null;
        }
        return get_image_url($documento_url, 'compra', $default_name);
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $compra = parent::publish();
        $compra['documentourl'] = $this->makeDocumentoURL(false, null);
        return $compra;
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
        $this->setNumero(Filter::string($this->getNumero()));
        $this->setCompradorID(Filter::number($this->getCompradorID()));
        $this->setFornecedorID(Filter::number($this->getFornecedorID()));
        $documento_url = upload_document('raw_documentourl', 'compra');
        if (is_null($documento_url) && trim($this->getDocumentoURL()) != '') {
            $this->setDocumentoURL($original->getDocumentoURL());
        } else {
            $this->setDocumentoURL($documento_url);
        }
        $this->setDataCompra(Filter::datetime($this->getDataCompra()));
        return $this;
    }

    /**
     * Clean instance resources like images and docs
     * @param self $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
        if (!is_null($this->getDocumentoURL()) && $dependency->getDocumentoURL() != $this->getDocumentoURL()) {
            @unlink(get_document_path($this->getDocumentoURL(), 'compra'));
        }
        $this->setDocumentoURL($dependency->getDocumentoURL());
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Compra in array format
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getCompradorID())) {
            $errors['compradorid'] = _t('compra.comprador_id_cannot_empty');
        }
        if (is_null($this->getFornecedorID())) {
            $errors['fornecedorid'] = _t('compra.fornecedor_id_cannot_empty');
        }
        if (is_null($this->getDataCompra())) {
            $errors['datacompra'] = _t('compra.data_compra_cannot_empty');
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
        if (contains(['Numero', 'UNIQUE'], $e->getMessage())) {
            return new ValidationException([
                'numero' => _t(
                    'compra.numero_used',
                    $this->getNumero()
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Insert a new Compra into the database and fill instance from database
     * @return self Self instance
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function insert()
    {
        $this->setID(null);
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = DB::insertInto('Compras')->values($values)->execute();
            $this->setID($id);
            $this->loadByID();
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Compra with instance values into database for ID
     * @param array $only Save these fields only, when empty save all fields except id
     * @return int rows affected
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function update($only = [])
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new ValidationException(
                ['id' => _t('compra.id_cannot_empty')]
            );
        }
        $values = DB::filterValues($values, $only, false);
        try {
            $affected = DB::update('Compras')
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
                ['id' => _t('compra.id_cannot_empty')]
            );
        }
        $result = DB::deleteFrom('Compras')
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
     * Load into this object from database using, Numero
     * @return self Self filled instance or empty when not found
     */
    public function loadByNumero()
    {
        return $this->load([
            'numero' => strval($this->getNumero()),
        ]);
    }

    /**
     * Informa o funcionário que comprou os produtos da lista
     * @return \MZ\Provider\Prestador The object fetched from database
     */
    public function findCompradorID()
    {
        return \MZ\Provider\Prestador::findByID($this->getCompradorID());
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
        $compra = new self();
        $allowed = Filter::concatKeys('c.', $compra->toArray());
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
        return Filter::orderBy($order, $allowed, 'c.');
    }

    /**
     * Filter condition array with allowed fields
     * @param array $condition condition to filter rows
     * @return array allowed condition
     */
    private static function filterCondition($condition)
    {
        $allowed = self::getAllowedKeys();
        return Filter::keys($condition, $allowed, 'c.');
    }

    /**
     * Fetch data from database with a condition
     * @param array $condition condition to filter rows
     * @param array $order order rows
     * @return SelectQuery query object with condition statement
     */
    private static function query($condition = [], $order = [])
    {
        $query = DB::from('Compras c');
        $condition = self::filterCondition($condition);
        $query = DB::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('c.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param array $condition Condition for searching the row
     * @param array $order order rows
     * @return self A filled Compra or empty instance
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
     * @return self A filled Compra or empty instance
     * @throws \Exception when register has not found
     */
    public static function findOrFail($condition, $order = [])
    {
        $result = self::find($condition, $order);
        if (!$result->exists()) {
            throw new \Exception(_t('compra.not_found'), 404);
        }
        return $result;
    }

    /**
     * Find this object on database using, Numero
     * @param string $numero número da compra to find Compra
     * @return self A filled instance or empty when not found
     */
    public static function findByNumero($numero)
    {
        $result = new self();
        $result->setNumero($numero);
        return $result->loadByNumero();
    }

    /**
     * Find all Compra
     * @param array  $condition Condition to get all Compra
     * @param array  $order     Order Compra
     * @param int    $limit     Limit data into row count
     * @param int    $offset    Start offset to get rows
     * @return self[] List of all rows instanced as Compra
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
