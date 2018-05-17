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
namespace MZ\Session;

use MZ\Database\Model;
use MZ\Database\DB;
use MZ\Util\Filter;
use MZ\Util\Validator;

/**
 * Resumo de fechamento de caixa, informa o valor contado no fechamento do
 * caixa para cada forma de pagamento
 */
class Resumo extends Model
{

    /**
     * Tipo de pagamento do resumo
     */
    const TIPO_DINHEIRO = 'Dinheiro';
    const TIPO_CARTAO = 'Cartao';
    const TIPO_CHEQUE = 'Cheque';
    const TIPO_CONTA = 'Conta';
    const TIPO_CREDITO = 'Credito';
    const TIPO_TRANSFERENCIA = 'Transferencia';

    /**
     * Identificador do resumo
     */
    private $id;
    /**
     * Movimentação do caixa referente ao resumo
     */
    private $movimentacao_id;
    /**
     * Tipo de pagamento do resumo
     */
    private $tipo;
    /**
     * Cartão da forma de pagamento
     */
    private $cartao_id;
    /**
     * Valor que foi contado ao fechar o caixa
     */
    private $valor;

    /**
     * Constructor for a new empty instance of Resumo
     * @param array $resumo All field and values to fill the instance
     */
    public function __construct($resumo = [])
    {
        parent::__construct($resumo);
    }

    /**
     * Identificador do resumo
     * @return mixed ID of Resumo
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param  mixed $id new value for ID
     * @return Resumo Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Movimentação do caixa referente ao resumo
     * @return mixed Movimentação of Resumo
     */
    public function getMovimentacaoID()
    {
        return $this->movimentacao_id;
    }

    /**
     * Set MovimentacaoID value to new on param
     * @param  mixed $movimentacao_id new value for MovimentacaoID
     * @return Resumo Self instance
     */
    public function setMovimentacaoID($movimentacao_id)
    {
        $this->movimentacao_id = $movimentacao_id;
        return $this;
    }

    /**
     * Tipo de pagamento do resumo
     * @return mixed Tipo of Resumo
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set Tipo value to new on param
     * @param  mixed $tipo new value for Tipo
     * @return Resumo Self instance
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
        return $this;
    }

    /**
     * Cartão da forma de pagamento
     * @return mixed Cartão of Resumo
     */
    public function getCartaoID()
    {
        return $this->cartao_id;
    }

    /**
     * Set CartaoID value to new on param
     * @param  mixed $cartao_id new value for CartaoID
     * @return Resumo Self instance
     */
    public function setCartaoID($cartao_id)
    {
        $this->cartao_id = $cartao_id;
        return $this;
    }

    /**
     * Valor que foi contado ao fechar o caixa
     * @return mixed Valor of Resumo
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Set Valor value to new on param
     * @param  mixed $valor new value for Valor
     * @return Resumo Self instance
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param  boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $resumo = parent::toArray($recursive);
        $resumo['id'] = $this->getID();
        $resumo['movimentacaoid'] = $this->getMovimentacaoID();
        $resumo['tipo'] = $this->getTipo();
        $resumo['cartaoid'] = $this->getCartaoID();
        $resumo['valor'] = $this->getValor();
        return $resumo;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param  mixed $resumo Associated key -> value to assign into this instance
     * @return Resumo Self instance
     */
    public function fromArray($resumo = [])
    {
        if ($resumo instanceof Resumo) {
            $resumo = $resumo->toArray();
        } elseif (!is_array($resumo)) {
            $resumo = [];
        }
        parent::fromArray($resumo);
        if (!isset($resumo['id'])) {
            $this->setID(null);
        } else {
            $this->setID($resumo['id']);
        }
        if (!isset($resumo['movimentacaoid'])) {
            $this->setMovimentacaoID(null);
        } else {
            $this->setMovimentacaoID($resumo['movimentacaoid']);
        }
        if (!isset($resumo['tipo'])) {
            $this->setTipo(null);
        } else {
            $this->setTipo($resumo['tipo']);
        }
        if (!array_key_exists('cartaoid', $resumo)) {
            $this->setCartaoID(null);
        } else {
            $this->setCartaoID($resumo['cartaoid']);
        }
        if (!isset($resumo['valor'])) {
            $this->setValor(null);
        } else {
            $this->setValor($resumo['valor']);
        }
        return $this;
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $resumo = parent::publish();
        return $resumo;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param Resumo $original Original instance without modifications
     */
    public function filter($original)
    {
        $this->setID($original->getID());
        $this->setMovimentacaoID(Filter::number($this->getMovimentacaoID()));
        $this->setCartaoID(Filter::number($this->getCartaoID()));
        $this->setValor(Filter::money($this->getValor()));
    }

    /**
     * Clean instance resources like images and docs
     * @param  Resumo $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Resumo in array format
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getMovimentacaoID())) {
            $errors['movimentacaoid'] = 'A movimentação não pode ser vazia';
        }
        if (is_null($this->getTipo())) {
            $errors['tipo'] = 'O tipo não pode ser vazio';
        }
        if (!Validator::checkInSet($this->getTipo(), self::getTipoOptions(), true)) {
            $errors['tipo'] = 'O tipo é inválido';
        }
        if (is_null($this->getValor())) {
            $errors['valor'] = 'O valor não pode ser vazio';
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
        if (stripos($e->getMessage(), 'UK_Resumos_MovimentacaoID_Tipo_CartaoID') !== false) {
            return new \MZ\Exception\ValidationException([
                'movimentacaoid' => sprintf(
                    'A movimentação "%s" já está cadastrada',
                    $this->getMovimentacaoID()
                ),
                'tipo' => sprintf(
                    'O tipo "%s" já está cadastrado',
                    $this->getTipo()
                ),
                'cartaoid' => sprintf(
                    'O cartão "%s" já está cadastrado',
                    $this->getCartaoID()
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Insert a new Resumo into the database and fill instance from database
     * @return Resumo Self instance
     */
    public function insert()
    {
        $this->setID(null);
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = DB::insertInto('Resumos')->values($values)->execute();
            $this->loadByID($id);
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Resumo with instance values into database for ID
     * @param  array $only Save these fields only, when empty save all fields except id
     * @param  boolean $except When true, saves all fields except $only
     * @return Resumo Self instance
     */
    public function update($only = [], $except = false)
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador do resumo não foi informado');
        }
        $values = DB::filterValues($values, $only, $except);
        try {
            DB::update('Resumos')
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
            throw new \Exception('O identificador do resumo não foi informado');
        }
        $result = DB::deleteFrom('Resumos')
            ->where('id', $this->getID())
            ->execute();
        return $result;
    }

    /**
     * Load one register for it self with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order associative field name -> [-1, 1]
     * @return Resumo Self instance filled or empty
     */
    public function load($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return $this->fromArray($row);
    }

    /**
     * Load into this object from database using, MovimentacaoID, Tipo, CartaoID
     * @param  int $movimentacao_id movimentação to find Resumo
     * @param  string $tipo tipo to find Resumo
     * @param  int $cartao_id cartão to find Resumo
     * @return Resumo Self filled instance or empty when not found
     */
    public function loadByMovimentacaoIDTipoCartaoID($movimentacao_id, $tipo, $cartao_id)
    {
        return $this->load([
            'movimentacaoid' => intval($movimentacao_id),
            'tipo' => strval($tipo),
            'cartaoid' => intval($cartao_id),
        ]);
    }

    /**
     * Movimentação do caixa referente ao resumo
     * @return \MZ\Session\Movimentacao The object fetched from database
     */
    public function findMovimentacaoID()
    {
        return \MZ\Session\Movimentacao::findByID($this->getMovimentacaoID());
    }

    /**
     * Cartão da forma de pagamento
     * @return \MZ\Payment\Cartao The object fetched from database
     */
    public function findCartaoID()
    {
        if (is_null($this->getCartaoID())) {
            return new \MZ\Payment\Cartao();
        }
        return \MZ\Payment\Cartao::findByID($this->getCartaoID());
    }

    /**
     * Gets textual and translated Tipo for Resumo
     * @param  int $index choose option from index
     * @return mixed A associative key -> translated representative text or text for index
     */
    public static function getTipoOptions($index = null)
    {
        $options = [
            self::TIPO_DINHEIRO => 'Dinheiro',
            self::TIPO_CARTAO => 'Cartão',
            self::TIPO_CHEQUE => 'Cheque',
            self::TIPO_CONTA => 'Conta',
            self::TIPO_CREDITO => 'Crédito',
            self::TIPO_TRANSFERENCIA => 'Transferência',
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
        $resumo = new Resumo();
        $allowed = Filter::concatKeys('r.', $resumo->toArray());
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
        return Filter::orderBy($order, $allowed, 'r.');
    }

    /**
     * Filter condition array with allowed fields
     * @param  array $condition condition to filter rows
     * @return array allowed condition
     */
    private static function filterCondition($condition)
    {
        $allowed = self::getAllowedKeys();
        return Filter::keys($condition, $allowed, 'r.');
    }

    /**
     * Fetch data from database with a condition
     * @param  array $condition condition to filter rows
     * @param  array $order order rows
     * @return SelectQuery query object with condition statement
     */
    private static function query($condition = [], $order = [])
    {
        $query = DB::from('Resumos r');
        $condition = self::filterCondition($condition);
        $query = DB::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('r.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order order rows
     * @return Resumo A filled Resumo or empty instance
     */
    public static function find($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return new Resumo($row);
    }

    /**
     * Find this object on database using, ID
     * @param  int $id id to find Resumo
     * @return Resumo A filled instance or empty when not found
     */
    public static function findByID($id)
    {
        $result = new self();
        return $result->loadByID($id);
    }

    /**
     * Find this object on database using, MovimentacaoID, Tipo, CartaoID
     * @param  int $movimentacao_id movimentação to find Resumo
     * @param  string $tipo tipo to find Resumo
     * @param  int $cartao_id cartão to find Resumo
     * @return Resumo A filled instance or empty when not found
     */
    public static function findByMovimentacaoIDTipoCartaoID($movimentacao_id, $tipo, $cartao_id)
    {
        $result = new self();
        return $result->loadByMovimentacaoIDTipoCartaoID($movimentacao_id, $tipo, $cartao_id);
    }

    /**
     * Find all Resumo
     * @param  array  $condition Condition to get all Resumo
     * @param  array  $order     Order Resumo
     * @param  int    $limit     Limit data into row count
     * @param  int    $offset    Start offset to get rows
     * @return array             List of all rows instanced as Resumo
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
            $result[] = new Resumo($row);
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
