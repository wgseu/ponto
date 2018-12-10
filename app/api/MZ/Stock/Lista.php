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
 * Lista de compras de produtos
 */
class Lista extends SyncModel
{

    /**
     * Estado da lista de compra. Análise: Ainda estão sendo adicionado
     * produtos na lista, Fechada: Está pronto para compra, Comprada: Todos os
     * itens foram comprados
     */
    const ESTADO_ANALISE = 'Analise';
    const ESTADO_FECHADA = 'Fechada';
    const ESTADO_COMPRADA = 'Comprada';

    /**
     * Identificador da lista de compras
     */
    private $id;
    /**
     * Nome da lista, pode ser uma data
     */
    private $descricao;
    /**
     * Estado da lista de compra. Análise: Ainda estão sendo adicionado
     * produtos na lista, Fechada: Está pronto para compra, Comprada: Todos os
     * itens foram comprados
     */
    private $estado;
    /**
     * Informa o funcionário encarregado de fazer as compras
     */
    private $encarregado_id;
    /**
     * Informações da viagem para realizar as compras
     */
    private $viagem_id;
    /**
     * Data e hora para o encarregado ir fazer as compras
     */
    private $data_viagem;
    /**
     * Data de cadastro da lista
     */
    private $data_cadastro;

    /**
     * Constructor for a new empty instance of Lista
     * @param array $lista All field and values to fill the instance
     */
    public function __construct($lista = [])
    {
        parent::__construct($lista);
    }

    /**
     * Identificador da lista de compras
     * @return int id of Lista de compra
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param int $id Set id for Lista de compra
     * @return self Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Nome da lista, pode ser uma data
     * @return string descrição of Lista de compra
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Set Descricao value to new on param
     * @param string $descricao Set descrição for Lista de compra
     * @return self Self instance
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * Estado da lista de compra. Análise: Ainda estão sendo adicionado
     * produtos na lista, Fechada: Está pronto para compra, Comprada: Todos os
     * itens foram comprados
     * @return string estado of Lista de compra
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set Estado value to new on param
     * @param string $estado Set estado for Lista de compra
     * @return self Self instance
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
        return $this;
    }

    /**
     * Informa o funcionário encarregado de fazer as compras
     * @return int encarregado of Lista de compra
     */
    public function getEncarregadoID()
    {
        return $this->encarregado_id;
    }

    /**
     * Set EncarregadoID value to new on param
     * @param int $encarregado_id Set encarregado for Lista de compra
     * @return self Self instance
     */
    public function setEncarregadoID($encarregado_id)
    {
        $this->encarregado_id = $encarregado_id;
        return $this;
    }

    /**
     * Informações da viagem para realizar as compras
     * @return int viagem of Lista de compra
     */
    public function getViagemID()
    {
        return $this->viagem_id;
    }

    /**
     * Set ViagemID value to new on param
     * @param int $viagem_id Set viagem for Lista de compra
     * @return self Self instance
     */
    public function setViagemID($viagem_id)
    {
        $this->viagem_id = $viagem_id;
        return $this;
    }

    /**
     * Data e hora para o encarregado ir fazer as compras
     * @return string data de viagem of Lista de compra
     */
    public function getDataViagem()
    {
        return $this->data_viagem;
    }

    /**
     * Set DataViagem value to new on param
     * @param string $data_viagem Set data de viagem for Lista de compra
     * @return self Self instance
     */
    public function setDataViagem($data_viagem)
    {
        $this->data_viagem = $data_viagem;
        return $this;
    }

    /**
     * Data de cadastro da lista
     * @return string data de cadastro of Lista de compra
     */
    public function getDataCadastro()
    {
        return $this->data_cadastro;
    }

    /**
     * Set DataCadastro value to new on param
     * @param string $data_cadastro Set data de cadastro for Lista de compra
     * @return self Self instance
     */
    public function setDataCadastro($data_cadastro)
    {
        $this->data_cadastro = $data_cadastro;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $lista = parent::toArray($recursive);
        $lista['id'] = $this->getID();
        $lista['descricao'] = $this->getDescricao();
        $lista['estado'] = $this->getEstado();
        $lista['encarregadoid'] = $this->getEncarregadoID();
        $lista['viagemid'] = $this->getViagemID();
        $lista['dataviagem'] = $this->getDataViagem();
        $lista['datacadastro'] = $this->getDataCadastro();
        return $lista;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param mixed $lista Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($lista = [])
    {
        if ($lista instanceof self) {
            $lista = $lista->toArray();
        } elseif (!is_array($lista)) {
            $lista = [];
        }
        parent::fromArray($lista);
        if (!isset($lista['id'])) {
            $this->setID(null);
        } else {
            $this->setID($lista['id']);
        }
        if (!isset($lista['descricao'])) {
            $this->setDescricao(null);
        } else {
            $this->setDescricao($lista['descricao']);
        }
        if (!isset($lista['estado'])) {
            $this->setEstado(null);
        } else {
            $this->setEstado($lista['estado']);
        }
        if (!isset($lista['encarregadoid'])) {
            $this->setEncarregadoID(null);
        } else {
            $this->setEncarregadoID($lista['encarregadoid']);
        }
        if (!array_key_exists('viagemid', $lista)) {
            $this->setViagemID(null);
        } else {
            $this->setViagemID($lista['viagemid']);
        }
        if (!isset($lista['dataviagem'])) {
            $this->setDataViagem(null);
        } else {
            $this->setDataViagem($lista['dataviagem']);
        }
        if (!isset($lista['datacadastro'])) {
            $this->setDataCadastro(null);
        } else {
            $this->setDataCadastro($lista['datacadastro']);
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
        $lista = parent::publish($requester);
        return $lista;
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
        $this->setDescricao(Filter::string($this->getDescricao()));
        $this->setEncarregadoID(Filter::number($this->getEncarregadoID()));
        $this->setViagemID(Filter::number($this->getViagemID()));
        $this->setDataViagem(Filter::datetime($this->getDataViagem()));
        $this->setDataCadastro(Filter::datetime($this->getDataCadastro()));
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
     * @return array All field of Lista in array format
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getDescricao())) {
            $errors['descricao'] = _t('lista.descricao_cannot_empty');
        }
        if (!Validator::checkInSet($this->getEstado(), self::getEstadoOptions())) {
            $errors['estado'] = _t('lista.estado_invalid');
        }
        if (is_null($this->getEncarregadoID())) {
            $errors['encarregadoid'] = _t('lista.encarregado_id_cannot_empty');
        }
        if (is_null($this->getDataViagem())) {
            $errors['dataviagem'] = _t('lista.data_viagem_cannot_empty');
        }
        $this->setDataCadastro(DB::now());
        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
        return $this->toArray();
    }

    /**
     * Update Lista de compra with instance values into database for ID
     * @param array $only Save these fields only, when empty save all fields except id
     * @return int rows affected
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function update($only = [])
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new ValidationException(
                ['id' => _t('lista.id_cannot_empty')]
            );
        }
        $values = DB::filterValues($values, $only, false);
        unset($values['datacadastro']);
        try {
            $affected = DB::update('Listas')
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
     * Informa o funcionário encarregado de fazer as compras
     * @return \MZ\Provider\Prestador The object fetched from database
     */
    public function findEncarregadoID()
    {
        return \MZ\Provider\Prestador::findByID($this->getEncarregadoID());
    }

    /**
     * Informações da viagem para realizar as compras
     * @return \MZ\Location\Viagem The object fetched from database
     */
    public function findViagemID()
    {
        if (is_null($this->getViagemID())) {
            return new \MZ\Location\Viagem();
        }
        return \MZ\Location\Viagem::findByID($this->getViagemID());
    }

    /**
     * Gets textual and translated Estado for Lista
     * @param int $index choose option from index
     * @return string[] A associative key -> translated representative text or text for index
     */
    public static function getEstadoOptions($index = null)
    {
        $options = [
            self::ESTADO_ANALISE => _t('lista.estado_analise'),
            self::ESTADO_FECHADA => _t('lista.estado_fechada'),
            self::ESTADO_COMPRADA => _t('lista.estado_comprada'),
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
        if (isset($condition['search'])) {
            $search = $condition['search'];
            $field = 'l.descricao LIKE ?';
            $condition[$field] = '%'.$search.'%';
            $allowed[$field] = true;
            unset($condition['search']);
        }
        return Filter::keys($condition, $allowed, 'l.');
    }

    /**
     * Fetch data from database with a condition
     * @param array $condition condition to filter rows
     * @param array $order order rows
     * @return SelectQuery query object with condition statement
     */
    protected function query($condition = [], $order = [])
    {
        $query = DB::from('Listas l');
        $condition = $this->filterCondition($condition);
        $query = DB::buildOrderBy($query, $this->filterOrder($order));
        $query = $query->orderBy('l.descricao ASC');
        $query = $query->orderBy('l.id ASC');
        return DB::buildCondition($query, $condition);
    }
}
