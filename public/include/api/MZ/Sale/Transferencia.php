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
 * Informa a transferência de uma mesa / comanda para outra, ou de um
 * produto para outra mesa / comanda
 */
class Transferencia extends \MZ\Database\Helper
{

    /**
     * Tipo de transferência, se de mesa/comanda ou de produto
     */
    const TIPO_PEDIDO = 'Pedido';
    const TIPO_PRODUTO = 'Produto';

    /**
     * Módulo de venda, se mesa ou comanda
     */
    const MODULO_MESA = 'Mesa';
    const MODULO_COMANDA = 'Comanda';

    /**
     * Identificador da transferência
     */
    private $id;
    /**
     * Identificador do pedido de origem
     */
    private $pedido_id;
    /**
     * Identificador do pedido de destino
     */
    private $destino_pedido_id;
    /**
     * Tipo de transferência, se de mesa/comanda ou de produto
     */
    private $tipo;
    /**
     * Módulo de venda, se mesa ou comanda
     */
    private $modulo;
    /**
     * Identificador da mesa de origem
     */
    private $mesa_id;
    /**
     * Mesa de destino da transferência
     */
    private $destino_mesa_id;
    /**
     * Comanda de origem da transferência
     */
    private $comanda_id;
    /**
     * Comanda de destino
     */
    private $destino_comanda_id;
    /**
     * Item que foi transferido
     */
    private $produto_pedido_id;
    /**
     * Funcionário que transferiu esse pedido/produto
     */
    private $funcionario_id;
    /**
     * Data e hora da transferência
     */
    private $data_hora;

    /**
     * Constructor for a new empty instance of Transferencia
     * @param array $transferencia All field and values to fill the instance
     */
    public function __construct($transferencia = [])
    {
        parent::__construct($transferencia);
    }

    /**
     * Identificador da transferência
     * @return mixed ID of Transferencia
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param  mixed $id new value for ID
     * @return Transferencia Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Identificador do pedido de origem
     * @return mixed Pedido de origem of Transferencia
     */
    public function getPedidoID()
    {
        return $this->pedido_id;
    }

    /**
     * Set PedidoID value to new on param
     * @param  mixed $pedido_id new value for PedidoID
     * @return Transferencia Self instance
     */
    public function setPedidoID($pedido_id)
    {
        $this->pedido_id = $pedido_id;
        return $this;
    }

    /**
     * Identificador do pedido de destino
     * @return mixed Pedido de destino of Transferencia
     */
    public function getDestinoPedidoID()
    {
        return $this->destino_pedido_id;
    }

    /**
     * Set DestinoPedidoID value to new on param
     * @param  mixed $destino_pedido_id new value for DestinoPedidoID
     * @return Transferencia Self instance
     */
    public function setDestinoPedidoID($destino_pedido_id)
    {
        $this->destino_pedido_id = $destino_pedido_id;
        return $this;
    }

    /**
     * Tipo de transferência, se de mesa/comanda ou de produto
     * @return mixed Tipo of Transferencia
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set Tipo value to new on param
     * @param  mixed $tipo new value for Tipo
     * @return Transferencia Self instance
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
        return $this;
    }

    /**
     * Módulo de venda, se mesa ou comanda
     * @return mixed Módulo of Transferencia
     */
    public function getModulo()
    {
        return $this->modulo;
    }

    /**
     * Set Modulo value to new on param
     * @param  mixed $modulo new value for Modulo
     * @return Transferencia Self instance
     */
    public function setModulo($modulo)
    {
        $this->modulo = $modulo;
        return $this;
    }

    /**
     * Identificador da mesa de origem
     * @return mixed Mesa de origem of Transferencia
     */
    public function getMesaID()
    {
        return $this->mesa_id;
    }

    /**
     * Set MesaID value to new on param
     * @param  mixed $mesa_id new value for MesaID
     * @return Transferencia Self instance
     */
    public function setMesaID($mesa_id)
    {
        $this->mesa_id = $mesa_id;
        return $this;
    }

    /**
     * Mesa de destino da transferência
     * @return mixed Mesa de destino of Transferencia
     */
    public function getDestinoMesaID()
    {
        return $this->destino_mesa_id;
    }

    /**
     * Set DestinoMesaID value to new on param
     * @param  mixed $destino_mesa_id new value for DestinoMesaID
     * @return Transferencia Self instance
     */
    public function setDestinoMesaID($destino_mesa_id)
    {
        $this->destino_mesa_id = $destino_mesa_id;
        return $this;
    }

    /**
     * Comanda de origem da transferência
     * @return mixed Comanda de origem of Transferencia
     */
    public function getComandaID()
    {
        return $this->comanda_id;
    }

    /**
     * Set ComandaID value to new on param
     * @param  mixed $comanda_id new value for ComandaID
     * @return Transferencia Self instance
     */
    public function setComandaID($comanda_id)
    {
        $this->comanda_id = $comanda_id;
        return $this;
    }

    /**
     * Comanda de destino
     * @return mixed Comanda de destino of Transferencia
     */
    public function getDestinoComandaID()
    {
        return $this->destino_comanda_id;
    }

    /**
     * Set DestinoComandaID value to new on param
     * @param  mixed $destino_comanda_id new value for DestinoComandaID
     * @return Transferencia Self instance
     */
    public function setDestinoComandaID($destino_comanda_id)
    {
        $this->destino_comanda_id = $destino_comanda_id;
        return $this;
    }

    /**
     * Item que foi transferido
     * @return mixed Item transferido of Transferencia
     */
    public function getProdutoPedidoID()
    {
        return $this->produto_pedido_id;
    }

    /**
     * Set ProdutoPedidoID value to new on param
     * @param  mixed $produto_pedido_id new value for ProdutoPedidoID
     * @return Transferencia Self instance
     */
    public function setProdutoPedidoID($produto_pedido_id)
    {
        $this->produto_pedido_id = $produto_pedido_id;
        return $this;
    }

    /**
     * Funcionário que transferiu esse pedido/produto
     * @return mixed Funcionário of Transferencia
     */
    public function getFuncionarioID()
    {
        return $this->funcionario_id;
    }

    /**
     * Set FuncionarioID value to new on param
     * @param  mixed $funcionario_id new value for FuncionarioID
     * @return Transferencia Self instance
     */
    public function setFuncionarioID($funcionario_id)
    {
        $this->funcionario_id = $funcionario_id;
        return $this;
    }

    /**
     * Data e hora da transferência
     * @return mixed Data e hora of Transferencia
     */
    public function getDataHora()
    {
        return $this->data_hora;
    }

    /**
     * Set DataHora value to new on param
     * @param  mixed $data_hora new value for DataHora
     * @return Transferencia Self instance
     */
    public function setDataHora($data_hora)
    {
        $this->data_hora = $data_hora;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param  boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $transferencia = parent::toArray($recursive);
        $transferencia['id'] = $this->getID();
        $transferencia['pedidoid'] = $this->getPedidoID();
        $transferencia['destinopedidoid'] = $this->getDestinoPedidoID();
        $transferencia['tipo'] = $this->getTipo();
        $transferencia['modulo'] = $this->getModulo();
        $transferencia['mesaid'] = $this->getMesaID();
        $transferencia['destinomesaid'] = $this->getDestinoMesaID();
        $transferencia['comandaid'] = $this->getComandaID();
        $transferencia['destinocomandaid'] = $this->getDestinoComandaID();
        $transferencia['produtopedidoid'] = $this->getProdutoPedidoID();
        $transferencia['funcionarioid'] = $this->getFuncionarioID();
        $transferencia['datahora'] = $this->getDataHora();
        return $transferencia;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param  mixed $transferencia Associated key -> value to assign into this instance
     * @return Transferencia Self instance
     */
    public function fromArray($transferencia = [])
    {
        if ($transferencia instanceof Transferencia) {
            $transferencia = $transferencia->toArray();
        } elseif (!is_array($transferencia)) {
            $transferencia = [];
        }
        parent::fromArray($transferencia);
        if (!isset($transferencia['id'])) {
            $this->setID(null);
        } else {
            $this->setID($transferencia['id']);
        }
        if (!isset($transferencia['pedidoid'])) {
            $this->setPedidoID(null);
        } else {
            $this->setPedidoID($transferencia['pedidoid']);
        }
        if (!isset($transferencia['destinopedidoid'])) {
            $this->setDestinoPedidoID(null);
        } else {
            $this->setDestinoPedidoID($transferencia['destinopedidoid']);
        }
        if (!isset($transferencia['tipo'])) {
            $this->setTipo(null);
        } else {
            $this->setTipo($transferencia['tipo']);
        }
        if (!isset($transferencia['modulo'])) {
            $this->setModulo(null);
        } else {
            $this->setModulo($transferencia['modulo']);
        }
        if (!array_key_exists('mesaid', $transferencia)) {
            $this->setMesaID(null);
        } else {
            $this->setMesaID($transferencia['mesaid']);
        }
        if (!array_key_exists('destinomesaid', $transferencia)) {
            $this->setDestinoMesaID(null);
        } else {
            $this->setDestinoMesaID($transferencia['destinomesaid']);
        }
        if (!array_key_exists('comandaid', $transferencia)) {
            $this->setComandaID(null);
        } else {
            $this->setComandaID($transferencia['comandaid']);
        }
        if (!array_key_exists('destinocomandaid', $transferencia)) {
            $this->setDestinoComandaID(null);
        } else {
            $this->setDestinoComandaID($transferencia['destinocomandaid']);
        }
        if (!array_key_exists('produtopedidoid', $transferencia)) {
            $this->setProdutoPedidoID(null);
        } else {
            $this->setProdutoPedidoID($transferencia['produtopedidoid']);
        }
        if (!isset($transferencia['funcionarioid'])) {
            $this->setFuncionarioID(null);
        } else {
            $this->setFuncionarioID($transferencia['funcionarioid']);
        }
        if (!isset($transferencia['datahora'])) {
            $this->setDataHora(null);
        } else {
            $this->setDataHora($transferencia['datahora']);
        }
        return $this;
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $transferencia = parent::publish();
        return $transferencia;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param Transferencia $original Original instance without modifications
     */
    public function filter($original)
    {
        $this->setID($original->getID());
        $this->setPedidoID(Filter::number($this->getPedidoID()));
        $this->setDestinoPedidoID(Filter::number($this->getDestinoPedidoID()));
        $this->setMesaID(Filter::number($this->getMesaID()));
        $this->setDestinoMesaID(Filter::number($this->getDestinoMesaID()));
        $this->setComandaID(Filter::number($this->getComandaID()));
        $this->setDestinoComandaID(Filter::number($this->getDestinoComandaID()));
        $this->setProdutoPedidoID(Filter::number($this->getProdutoPedidoID()));
        $this->setFuncionarioID(Filter::number($this->getFuncionarioID()));
        $this->setDataHora(Filter::datetime($this->getDataHora()));
    }

    /**
     * Clean instance resources like images and docs
     * @param  Transferencia $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Transferencia in array format
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getPedidoID())) {
            $errors['pedidoid'] = 'O pedido de origem não pode ser vazio';
        }
        if (is_null($this->getDestinoPedidoID())) {
            $errors['destinopedidoid'] = 'O pedido de destino não pode ser vazio';
        }
        if (is_null($this->getTipo())) {
            $errors['tipo'] = 'O tipo não pode ser vazio';
        }
        if (!Validator::checkInSet($this->getTipo(), self::getTipoOptions(), true)) {
            $errors['tipo'] = 'O tipo é inválido';
        }
        if (is_null($this->getModulo())) {
            $errors['modulo'] = 'O módulo não pode ser vazio';
        }
        if (!Validator::checkInSet($this->getModulo(), self::getModuloOptions(), true)) {
            $errors['modulo'] = 'O módulo é inválido';
        }
        if (is_null($this->getFuncionarioID())) {
            $errors['funcionarioid'] = 'O funcionário não pode ser vazio';
        }
        if (is_null($this->getDataHora())) {
            $errors['datahora'] = 'A data e hora não pode ser vazia';
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
        return parent::translate($e);
    }

    /**
     * Insert a new Transferência into the database and fill instance from database
     * @return Transferencia Self instance
     */
    public function insert()
    {
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = self::getDB()->insertInto('Transferencias')->values($values)->execute();
            $this->loadByID($id);
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Transferência with instance values into database for ID
     * @param  array $only Save these fields only, when empty save all fields except id
     * @param  boolean $except When true, saves all fields except $only
     * @return Transferencia Self instance
     */
    public function update($only = [], $except = false)
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador da transferência não foi informado');
        }
        $values = self::filterValues($values, $only, $except);
        try {
            self::getDB()
                ->update('Transferencias')
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
            throw new \Exception('O identificador da transferência não foi informado');
        }
        $result = self::getDB()
            ->deleteFrom('Transferencias')
            ->where('id', $this->getID())
            ->execute();
        return $result;
    }

    /**
     * Load one register for it self with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order associative field name -> [-1, 1]
     * @return Transferencia Self instance filled or empty
     */
    public function load($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return $this->fromArray($row);
    }

    /**
     * Identificador do pedido de origem
     * @return \MZ\Sale\Pedido The object fetched from database
     */
    public function findPedidoID()
    {
        return \MZ\Sale\Pedido::findByID($this->getPedidoID());
    }

    /**
     * Identificador do pedido de destino
     * @return \MZ\Sale\Pedido The object fetched from database
     */
    public function findDestinoPedidoID()
    {
        return \MZ\Sale\Pedido::findByID($this->getDestinoPedidoID());
    }

    /**
     * Identificador da mesa de origem
     * @return \MZ\Environment\Mesa The object fetched from database
     */
    public function findMesaID()
    {
        if (is_null($this->getMesaID())) {
            return new \MZ\Environment\Mesa();
        }
        return \MZ\Environment\Mesa::findByID($this->getMesaID());
    }

    /**
     * Mesa de destino da transferência
     * @return \MZ\Environment\Mesa The object fetched from database
     */
    public function findDestinoMesaID()
    {
        if (is_null($this->getDestinoMesaID())) {
            return new \MZ\Environment\Mesa();
        }
        return \MZ\Environment\Mesa::findByID($this->getDestinoMesaID());
    }

    /**
     * Comanda de origem da transferência
     * @return \MZ\Sale\Comanda The object fetched from database
     */
    public function findComandaID()
    {
        if (is_null($this->getComandaID())) {
            return new \MZ\Sale\Comanda();
        }
        return \MZ\Sale\Comanda::findByID($this->getComandaID());
    }

    /**
     * Comanda de destino
     * @return \MZ\Sale\Comanda The object fetched from database
     */
    public function findDestinoComandaID()
    {
        if (is_null($this->getDestinoComandaID())) {
            return new \MZ\Sale\Comanda();
        }
        return \MZ\Sale\Comanda::findByID($this->getDestinoComandaID());
    }

    /**
     * Item que foi transferido
     * @return \MZ\Sale\ProdutoPedido The object fetched from database
     */
    public function findProdutoPedidoID()
    {
        if (is_null($this->getProdutoPedidoID())) {
            return new \MZ\Sale\ProdutoPedido();
        }
        return \MZ\Sale\ProdutoPedido::findByID($this->getProdutoPedidoID());
    }

    /**
     * Funcionário que transferiu esse pedido/produto
     * @return \MZ\Employee\Funcionario The object fetched from database
     */
    public function findFuncionarioID()
    {
        return \MZ\Employee\Funcionario::findByID($this->getFuncionarioID());
    }

    /**
     * Gets textual and translated Tipo for Transferencia
     * @param  int $index choose option from index
     * @return mixed A associative key -> translated representative text or text for index
     */
    public static function getTipoOptions($index = null)
    {
        $options = [
            self::TIPO_PEDIDO => 'Pedido',
            self::TIPO_PRODUTO => 'Produto',
        ];
        if (!is_null($index)) {
            return $options[$index];
        }
        return $options;
    }

    /**
     * Gets textual and translated Modulo for Transferencia
     * @param  int $index choose option from index
     * @return mixed A associative key -> translated representative text or text for index
     */
    public static function getModuloOptions($index = null)
    {
        $options = [
            self::MODULO_MESA => 'Mesa',
            self::MODULO_COMANDA => 'Comanda',
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
        $transferencia = new Transferencia();
        $allowed = Filter::concatKeys('t.', $transferencia->toArray());
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
        return Filter::orderBy($order, $allowed, 't.');
    }

    /**
     * Filter condition array with allowed fields
     * @param  array $condition condition to filter rows
     * @return array allowed condition
     */
    private static function filterCondition($condition)
    {
        $allowed = self::getAllowedKeys();
        return Filter::keys($condition, $allowed, 't.');
    }

    /**
     * Fetch data from database with a condition
     * @param  array $condition condition to filter rows
     * @param  array $order order rows
     * @return SelectQuery query object with condition statement
     */
    private static function query($condition = [], $order = [])
    {
        $query = self::getDB()->from('Transferencias t');
        $condition = self::filterCondition($condition);
        $query = self::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('t.id ASC');
        return self::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order order rows
     * @return Transferencia A filled Transferência or empty instance
     */
    public static function find($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return new Transferencia($row);
    }

    /**
     * Find this object on database using, ID
     * @param  int $id id to find Transferência
     * @return Transferencia A filled instance or empty when not found
     */
    public static function findByID($id)
    {
        return self::find([
            'id' => intval($id),
        ]);
    }

    /**
     * Find all Transferência
     * @param  array  $condition Condition to get all Transferência
     * @param  array  $order     Order Transferência
     * @param  int    $limit     Limit data into row count
     * @param  int    $offset    Start offset to get rows
     * @return array             List of all rows instanced as Transferencia
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
            $result[] = new Transferencia($row);
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
