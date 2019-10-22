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
 * Junções de mesas, informa quais mesas estão juntas ao pedido
 */
class Juncao extends SyncModel
{

    /**
     * Estado a junção da mesa. Associado: a mesa está junta ao pedido,
     * Liberado: A mesa está livre, Cancelado: A mesa está liberada
     */
    const ESTADO_ASSOCIADO = 'Associado';
    const ESTADO_LIBERADO = 'Liberado';
    const ESTADO_CANCELADO = 'Cancelado';

    /**
     * Identificador da junção
     */
    private $id;
    /**
     * Mesa que está junta ao pedido
     */
    private $mesa_id;
    /**
     * Pedido a qual a mesa está junta, o pedido deve ser de uma mesa
     */
    private $pedido_id;
    /**
     * Estado a junção da mesa. Associado: a mesa está junta ao pedido,
     * Liberado: A mesa está livre, Cancelado: A mesa está liberada
     */
    private $estado;
    /**
     * Data e hora da junção das mesas
     */
    private $data_movimento;

    /**
     * Constructor for a new empty instance of Juncao
     * @param array $juncao All field and values to fill the instance
     */
    public function __construct($juncao = [])
    {
        parent::__construct($juncao);
    }

    /**
     * Identificador da junção
     * @return int id of Junção
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param int $id Set id for Junção
     * @return self Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Mesa que está junta ao pedido
     * @return int mesa of Junção
     */
    public function getMesaID()
    {
        return $this->mesa_id;
    }

    /**
     * Set MesaID value to new on param
     * @param int $mesa_id Set mesa for Junção
     * @return self Self instance
     */
    public function setMesaID($mesa_id)
    {
        $this->mesa_id = $mesa_id;
        return $this;
    }

    /**
     * Pedido a qual a mesa está junta, o pedido deve ser de uma mesa
     * @return int pedido of Junção
     */
    public function getPedidoID()
    {
        return $this->pedido_id;
    }

    /**
     * Set PedidoID value to new on param
     * @param int $pedido_id Set pedido for Junção
     * @return self Self instance
     */
    public function setPedidoID($pedido_id)
    {
        $this->pedido_id = $pedido_id;
        return $this;
    }

    /**
     * Estado a junção da mesa. Associado: a mesa está junta ao pedido,
     * Liberado: A mesa está livre, Cancelado: A mesa está liberada
     * @return string estado of Junção
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set Estado value to new on param
     * @param string $estado Set estado for Junção
     * @return self Self instance
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
        return $this;
    }

    /**
     * Data e hora da junção das mesas
     * @return string data do movimento of Junção
     */
    public function getDataMovimento()
    {
        return $this->data_movimento;
    }

    /**
     * Set DataMovimento value to new on param
     * @param string $data_movimento Set data do movimento for Junção
     * @return self Self instance
     */
    public function setDataMovimento($data_movimento)
    {
        $this->data_movimento = $data_movimento;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $juncao = parent::toArray($recursive);
        $juncao['id'] = $this->getID();
        $juncao['mesaid'] = $this->getMesaID();
        $juncao['pedidoid'] = $this->getPedidoID();
        $juncao['estado'] = $this->getEstado();
        $juncao['datamovimento'] = $this->getDataMovimento();
        return $juncao;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param mixed $juncao Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($juncao = [])
    {
        if ($juncao instanceof self) {
            $juncao = $juncao->toArray();
        } elseif (!is_array($juncao)) {
            $juncao = [];
        }
        parent::fromArray($juncao);
        if (!isset($juncao['id'])) {
            $this->setID(null);
        } else {
            $this->setID($juncao['id']);
        }
        if (!isset($juncao['mesaid'])) {
            $this->setMesaID(null);
        } else {
            $this->setMesaID($juncao['mesaid']);
        }
        if (!isset($juncao['pedidoid'])) {
            $this->setPedidoID(null);
        } else {
            $this->setPedidoID($juncao['pedidoid']);
        }
        if (!isset($juncao['estado'])) {
            $this->setEstado(null);
        } else {
            $this->setEstado($juncao['estado']);
        }
        if (!isset($juncao['datamovimento'])) {
            $this->setDataMovimento(null);
        } else {
            $this->setDataMovimento($juncao['datamovimento']);
        }
        return $this;
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @param \MZ\Provider\Prestador $requester user that request to view this fields
     * @return array All public field and values into array format
     */
    public function publish($requester)
    {
        $juncao = parent::publish($requester);
        return $juncao;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param self $original Original instance without modifications
     * @param \MZ\Provider\Prestador $updater user that want to update this object
     * @param boolean $localized Informs if fields are localized
     * @return self Self instance
     */
    public function filter($original, $updater, $localized = false)
    {
        $this->setID($original->getID());
        $this->setMesaID(Filter::number($this->getMesaID()));
        $this->setPedidoID(Filter::number($this->getPedidoID()));
        $this->setDataMovimento(Filter::datetime($this->getDataMovimento()));
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
     * @return array All field of Juncao in array format
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function validate()
    {
        $errors = [];
        $juncao = self::find([
            '!id' => $this->getID(),
            'mesaid' => $this->getMesaID(),
            'estado' => self::ESTADO_ASSOCIADO
        ]);
        $pedido = $this->findPedidoID();
        if (is_null($this->getMesaID())) {
            $errors['mesaid'] = _t('juncao.mesa_id_cannot_empty');
        } elseif ($juncao->exists()) {
            $errors['mesaid'] = _t('juncao.mesa_id_exists');
        } elseif ($this->getMesaID() == $pedido->getMesaID()) {
            $errors['mesaid'] = _t('juncao.mesa_id_same');
        }
        if (is_null($this->getPedidoID())) {
            $errors['pedidoid'] = _t('juncao.pedido_id_cannot_empty');
        } elseif (!$pedido->isAberto()) {
            $errors['pedidoid'] = _t('juncao.pedido_id_closed');
        } elseif ($pedido->getTipo() != Pedido::TIPO_MESA) {
            $errors['pedidoid'] = _t('juncao.pedido_id_incompatible');
        }
        if (!Validator::checkInSet($this->getEstado(), self::getEstadoOptions())) {
            $errors['estado'] = _t('juncao.estado_invalid');
        } elseif (!$this->exists() && $this->getEstado() != self::ESTADO_ASSOCIADO) {
            $errors['estado'] = _t('juncao.estado_new_closed');
        }
        $this->setDataMovimento(DB::now());
        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
        return $this->toArray();
    }

    /**
     * Mesa que está junta ao pedido
     * @return \MZ\Environment\Mesa The object fetched from database
     */
    public function findMesaID()
    {
        return \MZ\Environment\Mesa::findByID($this->getMesaID());
    }

    /**
     * Pedido a qual a mesa está junta, o pedido deve ser de uma mesa
     * @return \MZ\Sale\Pedido The object fetched from database
     */
    public function findPedidoID()
    {
        return \MZ\Sale\Pedido::findByID($this->getPedidoID());
    }

    /**
     * Gets textual and translated Estado for Juncao
     * @param int $index choose option from index
     * @return string[] A associative key -> translated representative text or text for index
     */
    public static function getEstadoOptions($index = null)
    {
        $options = [
            self::ESTADO_ASSOCIADO => _t('juncao.estado_associado'),
            self::ESTADO_LIBERADO => _t('juncao.estado_liberado'),
            self::ESTADO_CANCELADO => _t('juncao.estado_cancelado'),
        ];
        if (!is_null($index)) {
            return $options[$index];
        }
        return $options;
    }

    /**
     * Filter condition array with allowed fields
     * @param array $condition condition to filter rows
     * @return array allowed condition
     */
    protected function filterCondition($condition)
    {
        $allowed = $this->getAllowedKeys();
        if (isset($condition['!id'])) {
            $field = 'NOT j.id';
            $condition[$field] = $condition['!id'];
            $allowed[$field] = true;
        }
        return Filter::keys($condition, $allowed, 'j.');
    }

    /**
     * Fetch data from database with a condition
     * @param array $condition condition to filter rows
     * @param array $order order rows
     * @return SelectQuery query object with condition statement
     */
    protected function query($condition = [], $order = [])
    {
        $query = DB::from('Juncoes j');
        $condition = $this->filterCondition($condition);
        $query = DB::buildOrderBy($query, $this->filterOrder($order));
        $query = $query->orderBy('j.id ASC');
        return DB::buildCondition($query, $condition);
    }
}
