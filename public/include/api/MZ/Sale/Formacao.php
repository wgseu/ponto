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
namespace MZ\Sale;

use MZ\Util\Filter;
use MZ\Util\Validator;

/**
 * Informa qual foi a formação que gerou esse produto, assim como quais
 * item foram retirados/adicionados da composição
 */
class Formacao extends \MZ\Database\Helper
{

    /**
     * Informa qual tipo de formação foi escolhida, Pacote: O produto ou
     * propriedade faz parte de um pacote, Composição: O produto é uma
     * composição e esse item foi retirado ou adicionado na venda
     */
    const TIPO_PACOTE = 'Pacote';
    const TIPO_COMPOSICAO = 'Composicao';

    /**
     * Identificador da formação
     */
    private $id;
    /**
     * Informa qual foi o produto vendido para essa formação
     */
    private $produto_pedido_id;
    /**
     * Informa qual tipo de formação foi escolhida, Pacote: O produto ou
     * propriedade faz parte de um pacote, Composição: O produto é uma
     * composição e esse item foi retirado ou adicionado na venda
     */
    private $tipo;
    /**
     * Informa qual pacote foi selecionado no momento da venda
     */
    private $pacote_id;
    /**
     * Informa qual composição foi retirada ou adicionada no momento da venda
     */
    private $composicao_id;

    /**
     * Constructor for a new empty instance of Formacao
     * @param array $formacao All field and values to fill the instance
     */
    public function __construct($formacao = [])
    {
        parent::__construct($formacao);
    }

    /**
     * Identificador da formação
     * @return mixed ID of Formacao
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param  mixed $id new value for ID
     * @return Formacao Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Informa qual foi o produto vendido para essa formação
     * @return mixed Item do pedido of Formacao
     */
    public function getProdutoPedidoID()
    {
        return $this->produto_pedido_id;
    }

    /**
     * Set ProdutoPedidoID value to new on param
     * @param  mixed $produto_pedido_id new value for ProdutoPedidoID
     * @return Formacao Self instance
     */
    public function setProdutoPedidoID($produto_pedido_id)
    {
        $this->produto_pedido_id = $produto_pedido_id;
        return $this;
    }

    /**
     * Informa qual tipo de formação foi escolhida, Pacote: O produto ou
     * propriedade faz parte de um pacote, Composição: O produto é uma
     * composição e esse item foi retirado ou adicionado na venda
     * @return mixed Tipo of Formacao
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set Tipo value to new on param
     * @param  mixed $tipo new value for Tipo
     * @return Formacao Self instance
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
        return $this;
    }

    /**
     * Informa qual pacote foi selecionado no momento da venda
     * @return mixed Pacote of Formacao
     */
    public function getPacoteID()
    {
        return $this->pacote_id;
    }

    /**
     * Set PacoteID value to new on param
     * @param  mixed $pacote_id new value for PacoteID
     * @return Formacao Self instance
     */
    public function setPacoteID($pacote_id)
    {
        $this->pacote_id = $pacote_id;
        return $this;
    }

    /**
     * Informa qual composição foi retirada ou adicionada no momento da venda
     * @return mixed Composição of Formacao
     */
    public function getComposicaoID()
    {
        return $this->composicao_id;
    }

    /**
     * Set ComposicaoID value to new on param
     * @param  mixed $composicao_id new value for ComposicaoID
     * @return Formacao Self instance
     */
    public function setComposicaoID($composicao_id)
    {
        $this->composicao_id = $composicao_id;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param  boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $formacao = parent::toArray($recursive);
        $formacao['id'] = $this->getID();
        $formacao['produtopedidoid'] = $this->getProdutoPedidoID();
        $formacao['tipo'] = $this->getTipo();
        $formacao['pacoteid'] = $this->getPacoteID();
        $formacao['composicaoid'] = $this->getComposicaoID();
        return $formacao;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param  mixed $formacao Associated key -> value to assign into this instance
     * @return Formacao Self instance
     */
    public function fromArray($formacao = [])
    {
        if ($formacao instanceof Formacao) {
            $formacao = $formacao->toArray();
        } elseif (!is_array($formacao)) {
            $formacao = [];
        }
        parent::fromArray($formacao);
        if (!isset($formacao['id'])) {
            $this->setID(null);
        } else {
            $this->setID($formacao['id']);
        }
        if (!isset($formacao['produtopedidoid'])) {
            $this->setProdutoPedidoID(null);
        } else {
            $this->setProdutoPedidoID($formacao['produtopedidoid']);
        }
        if (!isset($formacao['tipo'])) {
            $this->setTipo(null);
        } else {
            $this->setTipo($formacao['tipo']);
        }
        if (!array_key_exists('pacoteid', $formacao)) {
            $this->setPacoteID(null);
        } else {
            $this->setPacoteID($formacao['pacoteid']);
        }
        if (!array_key_exists('composicaoid', $formacao)) {
            $this->setComposicaoID(null);
        } else {
            $this->setComposicaoID($formacao['composicaoid']);
        }
        return $this;
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $formacao = parent::publish();
        return $formacao;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param Formacao $original Original instance without modifications
     */
    public function filter($original)
    {
        $this->setID($original->getID());
        $this->setProdutoPedidoID(Filter::number($this->getProdutoPedidoID()));
        $this->setPacoteID(Filter::number($this->getPacoteID()));
        $this->setComposicaoID(Filter::number($this->getComposicaoID()));
    }

    /**
     * Clean instance resources like images and docs
     * @param  Formacao $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Formacao in array format
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getProdutoPedidoID())) {
            $errors['produtopedidoid'] = 'O item do pedido não pode ser vazio';
        }
        if (is_null($this->getTipo())) {
            $errors['tipo'] = 'O tipo não pode ser vazio';
        } elseif (!Validator::checkInSet($this->getTipo(), self::getTipoOptions())) {
            $errors['tipo'] = 'O tipo é inválido';
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
        if (stripos($e->getMessage(), 'UK_Formacoes_ProdutoPedidoID_PacoteID') !== false) {
            return new \MZ\Exception\ValidationException([
                'produtopedidoid' => sprintf(
                    'O item do pedido "%s" já está cadastrado',
                    $this->getProdutoPedidoID()
                ),
                'pacoteid' => sprintf(
                    'O pacote "%s" já está cadastrado',
                    $this->getPacoteID()
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Insert a new Formação into the database and fill instance from database
     * @return Formacao Self instance
     */
    public function insert()
    {
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = self::getDB()->insertInto('Formacoes')->values($values)->execute();
            $this->loadByID($id);
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Formação with instance values into database for ID
     * @param  array $only Save these fields only, when empty save all fields except id
     * @param  boolean $except When true, saves all fields except $only
     * @return Formacao Self instance
     */
    public function update($only = [], $except = false)
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador da formação não foi informado');
        }
        $values = self::filterValues($values, $only, $except);
        try {
            self::getDB()
                ->update('Formacoes')
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
            throw new \Exception('O identificador da formação não foi informado');
        }
        $result = self::getDB()
            ->deleteFrom('Formacoes')
            ->where('id', $this->getID())
            ->execute();
        return $result;
    }

    /**
     * Load one register for it self with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order associative field name -> [-1, 1]
     * @return Formacao Self instance filled or empty
     */
    public function load($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return $this->fromArray($row);
    }

    /**
     * Load into this object from database using, ProdutoPedidoID, PacoteID
     * @param  int $produto_pedido_id item do pedido to find Formação
     * @param  int $pacote_id pacote to find Formação
     * @return Formacao Self filled instance or empty when not found
     */
    public function loadByProdutoPedidoIDPacoteID($produto_pedido_id, $pacote_id)
    {
        return $this->load([
            'produtopedidoid' => intval($produto_pedido_id),
            'pacoteid' => intval($pacote_id),
        ]);
    }

    /**
     * Informa qual foi o produto vendido para essa formação
     * @return \MZ\Sale\ProdutoPedido The object fetched from database
     */
    public function findProdutoPedidoID()
    {
        return \MZ\Sale\ProdutoPedido::findByID($this->getProdutoPedidoID());
    }

    /**
     * Informa qual pacote foi selecionado no momento da venda
     * @return \MZ\Product\Pacote The object fetched from database
     */
    public function findPacoteID()
    {
        if (is_null($this->getPacoteID())) {
            return new \MZ\Product\Pacote();
        }
        return \MZ\Product\Pacote::findByID($this->getPacoteID());
    }

    /**
     * Informa qual composição foi retirada ou adicionada no momento da venda
     * @return \MZ\Product\Composicao The object fetched from database
     */
    public function findComposicaoID()
    {
        if (is_null($this->getComposicaoID())) {
            return new \MZ\Product\Composicao();
        }
        return \MZ\Product\Composicao::findByID($this->getComposicaoID());
    }

    /**
     * Gets textual and translated Tipo for Formacao
     * @param  int $index choose option from index
     * @return mixed A associative key -> translated representative text or text for index
     */
    public static function getTipoOptions($index = null)
    {
        $options = [
            self::TIPO_PACOTE => 'Pacote',
            self::TIPO_COMPOSICAO => 'Composicao',
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
        $formacao = new Formacao();
        $allowed = Filter::concatKeys('f.', $formacao->toArray());
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
        return Filter::orderBy($order, $allowed, 'f.');
    }

    /**
     * Filter condition array with allowed fields
     * @param  array $condition condition to filter rows
     * @return array allowed condition
     */
    private static function filterCondition($condition)
    {
        $allowed = self::getAllowedKeys();
        return Filter::keys($condition, $allowed, 'f.');
    }

    /**
     * Fetch data from database with a condition
     * @param  array $condition condition to filter rows
     * @param  array $order order rows
     * @return SelectQuery query object with condition statement
     */
    private static function query($condition = [], $order = [])
    {
        $query = self::getDB()->from('Formacoes f');
        $condition = self::filterCondition($condition);
        $query = self::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('f.id ASC');
        return self::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order order rows
     * @return Formacao A filled Formação or empty instance
     */
    public static function find($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return new Formacao($row);
    }

    /**
     * Find this object on database using, ID
     * @param  int $id id to find Formação
     * @return Formacao A filled instance or empty when not found
     */
    public static function findByID($id)
    {
        return self::find([
            'id' => intval($id),
        ]);
    }

    /**
     * Find this object on database using, ProdutoPedidoID, PacoteID
     * @param  int $produto_pedido_id item do pedido to find Formação
     * @param  int $pacote_id pacote to find Formação
     * @return Formacao A filled instance or empty when not found
     */
    public static function findByProdutoPedidoIDPacoteID($produto_pedido_id, $pacote_id)
    {
        return self::find([
            'produtopedidoid' => intval($produto_pedido_id),
            'pacoteid' => intval($pacote_id),
        ]);
    }

    /**
     * Find all Formação
     * @param  array  $condition Condition to get all Formação
     * @param  array  $order     Order Formação
     * @param  int    $limit     Limit data into row count
     * @param  int    $offset    Start offset to get rows
     * @return array             List of all rows instanced as Formacao
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
            $result[] = new Formacao($row);
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
