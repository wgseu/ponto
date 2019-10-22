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
 * Registro de viagem de uma entrega ou compra de insumos
 */
class Viagem extends SyncModel
{

    /**
     * Identificador da viagem
     */
    private $id;
    /**
     * Responsável pela entrega ou compra
     */
    private $responsavel_id;
    /**
     * Ponto latitudinal para localização do responsável em tempo real
     */
    private $latitude;
    /**
     * Ponto longitudinal para localização do responsável em tempo real
     */
    private $longitude;
    /**
     * Quilometragem no veículo antes de iniciar a viagem
     */
    private $quilometragem;
    /**
     * Distância percorrida até chegar de volta ao ponto de partida
     */
    private $distancia;
    /**
     * Data de atualização da localização do responsável
     */
    private $data_atualizacao;
    /**
     * Data de chegada no estabelecimento
     */
    private $data_chegada;
    /**
     * Data e hora que o responsável saiu para entregar o pedido ou fazer as
     * compras
     */
    private $data_saida;

    /**
     * Constructor for a new empty instance of Viagem
     * @param array $viagem All field and values to fill the instance
     */
    public function __construct($viagem = [])
    {
        parent::__construct($viagem);
    }

    /**
     * Identificador da viagem
     * @return int id of Viagem
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Identificador da viagem
     * @param int $id Set id for Viagem
     * @return self Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Responsável pela entrega ou compra
     * @return int responsável of Viagem
     */
    public function getResponsavelID()
    {
        return $this->responsavel_id;
    }

    /**
     * Responsável pela entrega ou compra
     * @param int $responsavel_id Set responsável for Viagem
     * @return self Self instance
     */
    public function setResponsavelID($responsavel_id)
    {
        $this->responsavel_id = $responsavel_id;
        return $this;
    }

    /**
     * Ponto latitudinal para localização do responsável em tempo real
     * @return float latitude of Viagem
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Ponto latitudinal para localização do responsável em tempo real
     * @param float $latitude Set latitude for Viagem
     * @return self Self instance
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
        return $this;
    }

    /**
     * Ponto longitudinal para localização do responsável em tempo real
     * @return float longitude of Viagem
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Ponto longitudinal para localização do responsável em tempo real
     * @param float $longitude Set longitude for Viagem
     * @return self Self instance
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
        return $this;
    }

    /**
     * Quilometragem no veículo antes de iniciar a viagem
     * @return float quilometragem of Viagem
     */
    public function getQuilometragem()
    {
        return $this->quilometragem;
    }

    /**
     * Quilometragem no veículo antes de iniciar a viagem
     * @param float $quilometragem Set quilometragem for Viagem
     * @return self Self instance
     */
    public function setQuilometragem($quilometragem)
    {
        $this->quilometragem = $quilometragem;
        return $this;
    }

    /**
     * Distância percorrida até chegar de volta ao ponto de partida
     * @return float distância of Viagem
     */
    public function getDistancia()
    {
        return $this->distancia;
    }

    /**
     * Distância percorrida até chegar de volta ao ponto de partida
     * @param float $distancia Set distância for Viagem
     * @return self Self instance
     */
    public function setDistancia($distancia)
    {
        $this->distancia = $distancia;
        return $this;
    }

    /**
     * Data de atualização da localização do responsável
     * @return string data de atualização of Viagem
     */
    public function getDataAtualizacao()
    {
        return $this->data_atualizacao;
    }

    /**
     * Data de atualização da localização do responsável
     * @param string $data_atualizacao Set data de atualização for Viagem
     * @return self Self instance
     */
    public function setDataAtualizacao($data_atualizacao)
    {
        $this->data_atualizacao = $data_atualizacao;
        return $this;
    }

    /**
     * Data de chegada no estabelecimento
     * @return string data de chegada of Viagem
     */
    public function getDataChegada()
    {
        return $this->data_chegada;
    }

    /**
     * Data de chegada no estabelecimento
     * @param string $data_chegada Set data de chegada for Viagem
     * @return self Self instance
     */
    public function setDataChegada($data_chegada)
    {
        $this->data_chegada = $data_chegada;
        return $this;
    }

    /**
     * Data e hora que o responsável saiu para entregar o pedido ou fazer as
     * compras
     * @return string data de saida of Viagem
     */
    public function getDataSaida()
    {
        return $this->data_saida;
    }

    /**
     * Data e hora que o responsável saiu para entregar o pedido ou fazer as
     * compras
     * @param string $data_saida Set data de saida for Viagem
     * @return self Self instance
     */
    public function setDataSaida($data_saida)
    {
        $this->data_saida = $data_saida;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $viagem = parent::toArray($recursive);
        $viagem['id'] = $this->getID();
        $viagem['responsavelid'] = $this->getResponsavelID();
        $viagem['latitude'] = $this->getLatitude();
        $viagem['longitude'] = $this->getLongitude();
        $viagem['quilometragem'] = $this->getQuilometragem();
        $viagem['distancia'] = $this->getDistancia();
        $viagem['dataatualizacao'] = $this->getDataAtualizacao();
        $viagem['datachegada'] = $this->getDataChegada();
        $viagem['datasaida'] = $this->getDataSaida();
        return $viagem;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param mixed $viagem Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($viagem = [])
    {
        if ($viagem instanceof self) {
            $viagem = $viagem->toArray();
        } elseif (!is_array($viagem)) {
            $viagem = [];
        }
        parent::fromArray($viagem);
        if (!isset($viagem['id'])) {
            $this->setID(null);
        } else {
            $this->setID($viagem['id']);
        }
        if (!isset($viagem['responsavelid'])) {
            $this->setResponsavelID(null);
        } else {
            $this->setResponsavelID($viagem['responsavelid']);
        }
        if (!array_key_exists('latitude', $viagem)) {
            $this->setLatitude(null);
        } else {
            $this->setLatitude($viagem['latitude']);
        }
        if (!array_key_exists('longitude', $viagem)) {
            $this->setLongitude(null);
        } else {
            $this->setLongitude($viagem['longitude']);
        }
        if (!array_key_exists('quilometragem', $viagem)) {
            $this->setQuilometragem(null);
        } else {
            $this->setQuilometragem($viagem['quilometragem']);
        }
        if (!array_key_exists('distancia', $viagem)) {
            $this->setDistancia(null);
        } else {
            $this->setDistancia($viagem['distancia']);
        }
        if (!array_key_exists('dataatualizacao', $viagem)) {
            $this->setDataAtualizacao(null);
        } else {
            $this->setDataAtualizacao($viagem['dataatualizacao']);
        }
        if (!array_key_exists('datachegada', $viagem)) {
            $this->setDataChegada(null);
        } else {
            $this->setDataChegada($viagem['datachegada']);
        }
        if (!isset($viagem['datasaida'])) {
            $this->setDataSaida(null);
        } else {
            $this->setDataSaida($viagem['datasaida']);
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
        $viagem = parent::publish($requester);
        return $viagem;
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
        $this->setResponsavelID(Filter::number($this->getResponsavelID()));
        $this->setLatitude(Filter::float($this->getLatitude(), $localized));
        $this->setLongitude(Filter::float($this->getLongitude(), $localized));
        $this->setQuilometragem(Filter::float($this->getQuilometragem(), $localized));
        $this->setDistancia(Filter::float($this->getDistancia(), $localized));
        $this->setDataAtualizacao(Filter::datetime($this->getDataAtualizacao()));
        $this->setDataChegada(Filter::datetime($this->getDataChegada()));
        $this->setDataSaida(Filter::datetime($this->getDataSaida()));
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
     * @return array All field of Viagem in array format
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getResponsavelID())) {
            $errors['responsavelid'] = _t('viagem.responsavel_id_cannot_empty');
        }
        $this->setDataAtualizacao(DB::now());
        if (is_null($this->getDataSaida())) {
            $errors['datasaida'] = _t('viagem.data_saida_cannot_empty');
        }
        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
        return $this->toArray();
    }

    /**
     * Responsável pela entrega ou compra
     * @return \MZ\Provider\Prestador The object fetched from database
     */
    public function findResponsavelID()
    {
        return \MZ\Provider\Prestador::findByID($this->getResponsavelID());
    }

    /**
     * Filter condition array with allowed fields
     * @param array $condition condition to filter rows
     * @return array allowed condition
     */
    protected function filterCondition($condition)
    {
        $allowed = $this->getAllowedKeys();
        return Filter::keys($condition, $allowed, 'v.');
    }

    /**
     * Fetch data from database with a condition
     * @param array $condition condition to filter rows
     * @param array $order order rows
     * @return SelectQuery query object with condition statement
     */
    protected function query($condition = [], $order = [])
    {
        $query = DB::from('Viagens v');
        $condition = $this->filterCondition($condition);
        $query = DB::buildOrderBy($query, $this->filterOrder($order));
        $query = $query->orderBy('v.id ASC');
        return DB::buildCondition($query, $condition);
    }

}
