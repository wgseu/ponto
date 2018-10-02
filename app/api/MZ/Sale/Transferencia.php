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

use MZ\Util\Mask;
use MZ\Util\Filter;
use MZ\Util\Validator;
use MZ\Database\DB;
use MZ\Database\SyncModel;
use MZ\Exception\ValidationException;

/**
 * Informa a transferência de uma mesa / comanda para outra, ou de um
 * produto para outra mesa / comanda
 */
class Transferencia extends SyncModel
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
    private $item_id;
    /**
     * Prestador que transferiu esse pedido/produto
     */
    private $prestador_id;
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
     * @return int id of Transferência
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param int $id Set id for Transferência
     * @return self Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Identificador do pedido de origem
     * @return int pedido de origem of Transferência
     */
    public function getPedidoID()
    {
        return $this->pedido_id;
    }

    /**
     * Set PedidoID value to new on param
     * @param int $pedido_id Set pedido de origem for Transferência
     * @return self Self instance
     */
    public function setPedidoID($pedido_id)
    {
        $this->pedido_id = $pedido_id;
        return $this;
    }

    /**
     * Identificador do pedido de destino
     * @return int pedido de destino of Transferência
     */
    public function getDestinoPedidoID()
    {
        return $this->destino_pedido_id;
    }

    /**
     * Set DestinoPedidoID value to new on param
     * @param int $destino_pedido_id Set pedido de destino for Transferência
     * @return self Self instance
     */
    public function setDestinoPedidoID($destino_pedido_id)
    {
        $this->destino_pedido_id = $destino_pedido_id;
        return $this;
    }

    /**
     * Tipo de transferência, se de mesa/comanda ou de produto
     * @return string tipo of Transferência
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set Tipo value to new on param
     * @param string $tipo Set tipo for Transferência
     * @return self Self instance
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
        return $this;
    }

    /**
     * Módulo de venda, se mesa ou comanda
     * @return string módulo of Transferência
     */
    public function getModulo()
    {
        return $this->modulo;
    }

    /**
     * Set Modulo value to new on param
     * @param string $modulo Set módulo for Transferência
     * @return self Self instance
     */
    public function setModulo($modulo)
    {
        $this->modulo = $modulo;
        return $this;
    }

    /**
     * Identificador da mesa de origem
     * @return int mesa de origem of Transferência
     */
    public function getMesaID()
    {
        return $this->mesa_id;
    }

    /**
     * Set MesaID value to new on param
     * @param int $mesa_id Set mesa de origem for Transferência
     * @return self Self instance
     */
    public function setMesaID($mesa_id)
    {
        $this->mesa_id = $mesa_id;
        return $this;
    }

    /**
     * Mesa de destino da transferência
     * @return int mesa de destino of Transferência
     */
    public function getDestinoMesaID()
    {
        return $this->destino_mesa_id;
    }

    /**
     * Set DestinoMesaID value to new on param
     * @param int $destino_mesa_id Set mesa de destino for Transferência
     * @return self Self instance
     */
    public function setDestinoMesaID($destino_mesa_id)
    {
        $this->destino_mesa_id = $destino_mesa_id;
        return $this;
    }

    /**
     * Comanda de origem da transferência
     * @return int comanda de origem of Transferência
     */
    public function getComandaID()
    {
        return $this->comanda_id;
    }

    /**
     * Set ComandaID value to new on param
     * @param int $comanda_id Set comanda de origem for Transferência
     * @return self Self instance
     */
    public function setComandaID($comanda_id)
    {
        $this->comanda_id = $comanda_id;
        return $this;
    }

    /**
     * Comanda de destino
     * @return int comanda de destino of Transferência
     */
    public function getDestinoComandaID()
    {
        return $this->destino_comanda_id;
    }

    /**
     * Set DestinoComandaID value to new on param
     * @param int $destino_comanda_id Set comanda de destino for Transferência
     * @return self Self instance
     */
    public function setDestinoComandaID($destino_comanda_id)
    {
        $this->destino_comanda_id = $destino_comanda_id;
        return $this;
    }

    /**
     * Item que foi transferido
     * @return int item transferido of Transferência
     */
    public function getItemID()
    {
        return $this->item_id;
    }

    /**
     * Set ItemID value to new on param
     * @param int $item_id Set item transferido for Transferência
     * @return self Self instance
     */
    public function setItemID($item_id)
    {
        $this->item_id = $item_id;
        return $this;
    }

    /**
     * Prestador que transferiu esse pedido/produto
     * @return int prestador of Transferência
     */
    public function getPrestadorID()
    {
        return $this->prestador_id;
    }

    /**
     * Set PrestadorID value to new on param
     * @param int $prestador_id Set prestador for Transferência
     * @return self Self instance
     */
    public function setPrestadorID($prestador_id)
    {
        $this->prestador_id = $prestador_id;
        return $this;
    }

    /**
     * Data e hora da transferência
     * @return string data e hora of Transferência
     */
    public function getDataHora()
    {
        return $this->data_hora;
    }

    /**
     * Set DataHora value to new on param
     * @param string $data_hora Set data e hora for Transferência
     * @return self Self instance
     */
    public function setDataHora($data_hora)
    {
        $this->data_hora = $data_hora;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param boolean $recursive Allow rescursive conversion of fields
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
        $transferencia['itemid'] = $this->getItemID();
        $transferencia['prestadorid'] = $this->getPrestadorID();
        $transferencia['datahora'] = $this->getDataHora();
        return $transferencia;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param mixed $transferencia Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($transferencia = [])
    {
        if ($transferencia instanceof self) {
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
        if (!array_key_exists('itemid', $transferencia)) {
            $this->setItemID(null);
        } else {
            $this->setItemID($transferencia['itemid']);
        }
        if (!isset($transferencia['prestadorid'])) {
            $this->setPrestadorID(null);
        } else {
            $this->setPrestadorID($transferencia['prestadorid']);
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
     * @param self $original Original instance without modifications
     * @param boolean $localized Informs if fields are localized
     * @return self Self instance
     */
    public function filter($original, $localized = false)
    {
        $this->setID($original->getID());
        $this->setPedidoID(Filter::number($this->getPedidoID()));
        $this->setDestinoPedidoID(Filter::number($this->getDestinoPedidoID()));
        $this->setMesaID(Filter::number($this->getMesaID()));
        $this->setDestinoMesaID(Filter::number($this->getDestinoMesaID()));
        $this->setComandaID(Filter::number($this->getComandaID()));
        $this->setDestinoComandaID(Filter::number($this->getDestinoComandaID()));
        $this->setItemID(Filter::number($this->getItemID()));
        $this->setPrestadorID(Filter::number($this->getPrestadorID()));
        $this->setDataHora(Filter::datetime($this->getDataHora()));
        return $this;
    }

    /**
     * Clean instance resources like images and docs
     * @param self $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Transferencia in array format
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getPedidoID())) {
            $errors['pedidoid'] = _t('transferencia.pedido_id_cannot_empty');
        }
        if (is_null($this->getDestinoPedidoID())) {
            $errors['destinopedidoid'] = _t('transferencia.destino_pedido_id_cannot_empty');
        }
        if (!Validator::checkInSet($this->getTipo(), self::getTipoOptions())) {
            $errors['tipo'] = _t('transferencia.tipo_invalid');
        }
        if (!Validator::checkInSet($this->getModulo(), self::getModuloOptions())) {
            $errors['modulo'] = _t('transferencia.modulo_invalid');
        }
        if (is_null($this->getPrestadorID())) {
            $errors['prestadorid'] = _t('transferencia.prestador_id_cannot_empty');
        }
        if (is_null($this->getDataHora())) {
            $errors['datahora'] = _t('transferencia.data_hora_cannot_empty');
        }
        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
        return $this->toArray();
    }

    /**
     * Insert a new Transferência into the database and fill instance from database
     * @return self Self instance
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function insert()
    {
        $this->setID(null);
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = DB::insertInto('Transferencias')->values($values)->execute();
            $this->setID($id);
            $this->loadByID();
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Transferência with instance values into database for ID
     * @param array $only Save these fields only, when empty save all fields except id
     * @return int rows affected
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function update($only = [])
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new ValidationException(
                ['id' => _t('transferencia.id_cannot_empty')]
            );
        }
        $values = DB::filterValues($values, $only, false);
        try {
            $affected = DB::update('Transferencias')
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
                ['id' => _t('transferencia.id_cannot_empty')]
            );
        }
        $result = DB::deleteFrom('Transferencias')
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
     * @return \MZ\Sale\Item The object fetched from database
     */
    public function findItemID()
    {
        if (is_null($this->getItemID())) {
            return new \MZ\Sale\Item();
        }
        return \MZ\Sale\Item::findByID($this->getItemID());
    }

    /**
     * Prestador que transferiu esse pedido/produto
     * @return \MZ\Provider\Prestador The object fetched from database
     */
    public function findPrestadorID()
    {
        return \MZ\Provider\Prestador::findByID($this->getPrestadorID());
    }

    /**
     * Gets textual and translated Tipo for Transferencia
     * @param int $index choose option from index
     * @return string[] A associative key -> translated representative text or text for index
     */
    public static function getTipoOptions($index = null)
    {
        $options = [
            self::TIPO_PEDIDO => _t('transferencia.tipo_pedido'),
            self::TIPO_PRODUTO => _t('transferencia.tipo_produto'),
        ];
        if (!is_null($index)) {
            return $options[$index];
        }
        return $options;
    }

    /**
     * Gets textual and translated Modulo for Transferencia
     * @param int $index choose option from index
     * @return string[] A associative key -> translated representative text or text for index
     */
    public static function getModuloOptions($index = null)
    {
        $options = [
            self::MODULO_MESA => _t('transferencia.modulo_mesa'),
            self::MODULO_COMANDA => _t('transferencia.modulo_comanda'),
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
        $transferencia = new self();
        $allowed = Filter::concatKeys('t.', $transferencia->toArray());
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
        return Filter::orderBy($order, $allowed, 't.');
    }

    /**
     * Filter condition array with allowed fields
     * @param array $condition condition to filter rows
     * @return array allowed condition
     */
    private static function filterCondition($condition)
    {
        $allowed = self::getAllowedKeys();
        return Filter::keys($condition, $allowed, 't.');
    }

    /**
     * Fetch data from database with a condition
     * @param array $condition condition to filter rows
     * @param array $order order rows
     * @return SelectQuery query object with condition statement
     */
    private static function query($condition = [], $order = [])
    {
        $query = DB::from('Transferencias t');
        $condition = self::filterCondition($condition);
        $query = DB::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('t.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param array $condition Condition for searching the row
     * @param array $order order rows
     * @return self A filled Transferência or empty instance
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
     * @return self A filled Transferência or empty instance
     * @throws \Exception when register has not found
     */
    public static function findOrFail($condition, $order = [])
    {
        $result = self::find($condition, $order);
        if (!$result->exists()) {
            throw new \Exception(_t('transferencia.not_found'), 404);
        }
        return $result;
    }

    /**
     * Find all Transferência
     * @param array  $condition Condition to get all Transferência
     * @param array  $order     Order Transferência
     * @param int    $limit     Limit data into row count
     * @param int    $offset    Start offset to get rows
     * @return self[] List of all rows instanced as Transferencia
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
