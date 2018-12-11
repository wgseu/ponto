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
namespace MZ\Location;

use MZ\Util\Mask;
use MZ\Util\Filter;
use MZ\Util\Validator;
use MZ\Database\DB;
use MZ\Database\SyncModel;
use MZ\Exception\ValidationException;

/**
 * Zonas de um bairro
 */
class Zona extends SyncModel
{

    private $id;
    private $bairro_id;
    private $nome;
    private $adicional_entrega;
    /**
     * Informa se a zona está disponível para entrega de pedidos
     */
    private $disponivel;
    private $area;
    /**
     * Tempo médio de entrega para essa zona, sobrescreve o tempo de entrega
     * para o bairro
     */
    private $tempo_entrega;

    /**
     * Constructor for a new empty instance of Zona
     * @param array $zona All field and values to fill the instance
     */
    public function __construct($zona = [])
    {
        parent::__construct($zona);
    }

    public function getID()
    {
        return $this->id;
    }

    /**
     * @param int $id Set id for Zona
     * @return self Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getBairroID()
    {
        return $this->bairro_id;
    }

    /**
     * @param int $bairro_id Set bairroid for Zona
     * @return self Self instance
     */
    public function setBairroID($bairro_id)
    {
        $this->bairro_id = $bairro_id;
        return $this;
    }

    public function getNome()
    {
        return $this->nome;
    }

    /**
     * @param string $nome Set nome for Zona
     * @return self Self instance
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    public function getAdicionalEntrega()
    {
        return $this->adicional_entrega;
    }

    /**
     * @param string $adicional_entrega Set adicionalentrega for Zona
     * @return self Self instance
     */
    public function setAdicionalEntrega($adicional_entrega)
    {
        $this->adicional_entrega = $adicional_entrega;
        return $this;
    }

    /**
     * Informa se a zona está disponível para entrega de pedidos
     * @return string disponível of Zona
     */
    public function getDisponivel()
    {
        return $this->disponivel;
    }

    /**
     * Informa se a zona está disponível para entrega de pedidos
     * @return boolean Check if o of Disponivel is selected or checked
     */
    public function isDisponivel()
    {
        return $this->disponivel == 'Y';
    }

    /**
     * Informa se a zona está disponível para entrega de pedidos
     * @param string $disponivel Set disponível for Zona
     * @return self Self instance
     */
    public function setDisponivel($disponivel)
    {
        $this->disponivel = $disponivel;
        return $this;
    }

    public function getArea()
    {
        return $this->area;
    }

    /**
     * @param string $area Set area for Zona
     * @return self Self instance
     */
    public function setArea($area)
    {
        $this->area = $area;
        return $this;
    }

    /**
     * Tempo médio de entrega para essa zona, sobrescreve o tempo de entrega
     * para o bairro
     * @return int tempo de entrega of Zona
     */
    public function getTempoEntrega()
    {
        return $this->tempo_entrega;
    }

    /**
     * Tempo médio de entrega para essa zona, sobrescreve o tempo de entrega
     * para o bairro
     * @param int $tempo_entrega Set tempo de entrega for Zona
     * @return self Self instance
     */
    public function setTempoEntrega($tempo_entrega)
    {
        $this->tempo_entrega = $tempo_entrega;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $zona = parent::toArray($recursive);
        $zona['id'] = $this->getID();
        $zona['bairroid'] = $this->getBairroID();
        $zona['nome'] = $this->getNome();
        $zona['adicionalentrega'] = $this->getAdicionalEntrega();
        $zona['disponivel'] = $this->getDisponivel();
        $zona['area'] = $this->getArea();
        $zona['tempoentrega'] = $this->getTempoEntrega();
        return $zona;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param mixed $zona Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($zona = [])
    {
        if ($zona instanceof self) {
            $zona = $zona->toArray();
        } elseif (!is_array($zona)) {
            $zona = [];
        }
        parent::fromArray($zona);
        if (!isset($zona['id'])) {
            $this->setID(null);
        } else {
            $this->setID($zona['id']);
        }
        if (!isset($zona['bairroid'])) {
            $this->setBairroID(null);
        } else {
            $this->setBairroID($zona['bairroid']);
        }
        if (!isset($zona['nome'])) {
            $this->setNome(null);
        } else {
            $this->setNome($zona['nome']);
        }
        if (!isset($zona['adicionalentrega'])) {
            $this->setAdicionalEntrega(null);
        } else {
            $this->setAdicionalEntrega($zona['adicionalentrega']);
        }
        if (!isset($zona['disponivel'])) {
            $this->setDisponivel('N');
        } else {
            $this->setDisponivel($zona['disponivel']);
        }
        if (!array_key_exists('area', $zona)) {
            $this->setArea(null);
        } else {
            $this->setArea($zona['area']);
        }
        if (!array_key_exists('tempoentrega', $zona)) {
            $this->setTempoEntrega(null);
        } else {
            $this->setTempoEntrega($zona['tempoentrega']);
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
        $zona = parent::publish($requester);
        return $zona;
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
        $this->setBairroID(Filter::number($this->getBairroID()));
        $this->setNome(Filter::string($this->getNome()));
        $this->setAdicionalEntrega(Filter::money($this->getAdicionalEntrega(), $localized));
        $this->setArea(Filter::text($this->getArea()));
        $this->setTempoEntrega(Filter::number($this->getTempoEntrega()));
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
     * @return array All field of Zona in array format
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getBairroID())) {
            $errors['bairroid'] = _t('zona.bairro_id_cannot_empty');
        }
        if (is_null($this->getNome())) {
            $errors['nome'] = _t('zona.nome_cannot_empty');
        }
        if (is_null($this->getAdicionalEntrega())) {
            $errors['adicionalentrega'] = _t('zona.adicional_entrega_cannot_empty');
        }
        if (!Validator::checkBoolean($this->getDisponivel())) {
            $errors['disponivel'] = _t('zona.disponivel_invalid');
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
        if (contains(['ID', 'UNIQUE'], $e->getMessage())) {
            return new ValidationException([
                'id' => _t(
                    'zona.id_used',
                    $this->getID()
                ),
            ]);
        }
        if (contains(['BairroID', 'Nome', 'UNIQUE'], $e->getMessage())) {
            return new ValidationException([
                'bairroid' => _t(
                    'zona.bairro_id_used',
                    $this->getBairroID()
                ),
                'nome' => _t(
                    'zona.nome_used',
                    $this->getNome()
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Load into this object from database using, ID
     * @return self Self filled instance or empty when not found
     */
    public function loadByID()
    {
        return $this->load([
            'id' => intval($this->getID()),
        ]);
    }

    /**
     * Load into this object from database using, BairroID, Nome
     * @return self Self filled instance or empty when not found
     */
    public function loadByBairroIDNome()
    {
        return $this->load([
            'bairroid' => intval($this->getBairroID()),
            'nome' => strval($this->getNome()),
        ]);
    }

    public function findBairroID()
    {
        return \MZ\Location\Bairro::findByID($this->getBairroID());
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
            $field = 'z.nome LIKE ?';
            $condition[$field] = '%'.$search.'%';
            $allowed[$field] = true;
            unset($condition['search']);
        }
        return Filter::keys($condition, $allowed, 'z.');
    }

    /**
     * Fetch data from database with a condition
     * @param array $condition condition to filter rows
     * @param array $order order rows
     * @return SelectQuery query object with condition statement
     */
    protected function query($condition = [], $order = [])
    {
        $query = DB::from('Zonas z');
        $condition = $this->filterCondition($condition);
        $query = DB::buildOrderBy($query, $this->filterOrder($order));
        $query = $query->orderBy('z.nome ASC');
        $query = $query->orderBy('z.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Find this object on database using, ID
     * @param int $id id to find Zona
     * @return self A filled instance or empty when not found
     */
    public static function findByID($id)
    {
        $result = new self();
        $result->setID($id);
        return $result->loadByID();
    }

    /**
     * Find this object on database using, BairroID, Nome
     * @param int $bairro_id bairroid to find Zona
     * @param string $nome nome to find Zona
     * @return self A filled instance or empty when not found
     */
    public static function findByBairroIDNome($bairro_id, $nome)
    {
        $result = new self();
        $result->setBairroID($bairro_id);
        $result->setNome($nome);
        return $result->loadByBairroIDNome();
    }
}
