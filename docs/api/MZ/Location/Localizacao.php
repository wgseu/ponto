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
 * Endereço detalhado de um cliente
 */
class Localizacao extends SyncModel
{

    /**
     * Tipo de endereço Casa ou Apartamento
     */
    const TIPO_CASA = 'Casa';
    const TIPO_APARTAMENTO = 'Apartamento';
    const TIPO_CONDOMINIO = 'Condominio';

    /**
     * Identificador do endereço
     */
    private $id;
    /**
     * Cliente a qual esse endereço pertence
     */
    private $cliente_id;
    /**
     * Bairro do endereço
     */
    private $bairro_id;
    /**
     * Informa a zona do bairro
     */
    private $zona_id;
    /**
     * Código dos correios para identificar um logradouro
     */
    private $cep;
    /**
     * Nome da rua ou avenida
     */
    private $logradouro;
    /**
     * Número da casa ou do condomínio
     */
    private $numero;
    /**
     * Tipo de endereço Casa ou Apartamento
     */
    private $tipo;
    /**
     * Complemento do endereço, Ex.: Loteamento Sul
     */
    private $complemento;
    /**
     * Nome do condomínio
     */
    private $condominio;
    /**
     * Número do bloco quando for apartamento
     */
    private $bloco;
    /**
     * Número do apartamento
     */
    private $apartamento;
    /**
     * Ponto de referência para chegar ao local
     */
    private $referencia;
    /**
     * Ponto latitudinal para localização em um mapa
     */
    private $latitude;
    /**
     * Ponto longitudinal para localização em um mapa
     */
    private $longitude;
    /**
     * Ex.: Minha Casa, Casa da Amiga
     */
    private $apelido;
    /**
     * Permite esconder ou exibir um endereço do cliente
     */
    private $mostrar;

    /**
     * Constructor for a new empty instance of Localizacao
     * @param array $localizacao All field and values to fill the instance
     */
    public function __construct($localizacao = [])
    {
        parent::__construct($localizacao);
    }

    /**
     * Identificador do endereço
     * @return int id of Localização
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param int $id Set id for Localização
     * @return self Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Cliente a qual esse endereço pertence
     * @return int cliente of Localização
     */
    public function getClienteID()
    {
        return $this->cliente_id;
    }

    /**
     * Set ClienteID value to new on param
     * @param int $cliente_id Set cliente for Localização
     * @return self Self instance
     */
    public function setClienteID($cliente_id)
    {
        $this->cliente_id = $cliente_id;
        return $this;
    }

    /**
     * Bairro do endereço
     * @return int bairro of Localização
     */
    public function getBairroID()
    {
        return $this->bairro_id;
    }

    /**
     * Set BairroID value to new on param
     * @param int $bairro_id Set bairro for Localização
     * @return self Self instance
     */
    public function setBairroID($bairro_id)
    {
        $this->bairro_id = $bairro_id;
        return $this;
    }

    /**
     * Informa a zona do bairro
     * @return int zonaid of Localização
     */
    public function getZonaID()
    {
        return $this->zona_id;
    }

    /**
     * Set ZonaID value to new on param
     * @param int $zona_id Set zonaid for Localização
     * @return self Self instance
     */
    public function setZonaID($zona_id)
    {
        $this->zona_id = $zona_id;
        return $this;
    }

    /**
     * Código dos correios para identificar um logradouro
     * @return string cep of Localização
     */
    public function getCEP()
    {
        return $this->cep;
    }

    /**
     * Set CEP value to new on param
     * @param string $cep Set cep for Localização
     * @return self Self instance
     */
    public function setCEP($cep)
    {
        $this->cep = $cep;
        return $this;
    }

    /**
     * Nome da rua ou avenida
     * @return string logradouro of Localização
     */
    public function getLogradouro()
    {
        return $this->logradouro;
    }

    /**
     * Set Logradouro value to new on param
     * @param string $logradouro Set logradouro for Localização
     * @return self Self instance
     */
    public function setLogradouro($logradouro)
    {
        $this->logradouro = $logradouro;
        return $this;
    }

    /**
     * Número da casa ou do condomínio
     * @return string número of Localização
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set Numero value to new on param
     * @param string $numero Set número for Localização
     * @return self Self instance
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;
        return $this;
    }

    /**
     * Tipo de endereço Casa ou Apartamento
     * @return string tipo of Localização
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set Tipo value to new on param
     * @param string $tipo Set tipo for Localização
     * @return self Self instance
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
        return $this;
    }

    /**
     * Complemento do endereço, Ex.: Loteamento Sul
     * @return string complemento of Localização
     */
    public function getComplemento()
    {
        return $this->complemento;
    }

    /**
     * Set Complemento value to new on param
     * @param string $complemento Set complemento for Localização
     * @return self Self instance
     */
    public function setComplemento($complemento)
    {
        $this->complemento = $complemento;
        return $this;
    }

    /**
     * Nome do condomínio
     * @return string condomínio of Localização
     */
    public function getCondominio()
    {
        return $this->condominio;
    }

    /**
     * Set Condominio value to new on param
     * @param string $condominio Set condomínio for Localização
     * @return self Self instance
     */
    public function setCondominio($condominio)
    {
        $this->condominio = $condominio;
        return $this;
    }

    /**
     * Número do bloco quando for apartamento
     * @return string bloco of Localização
     */
    public function getBloco()
    {
        return $this->bloco;
    }

    /**
     * Set Bloco value to new on param
     * @param string $bloco Set bloco for Localização
     * @return self Self instance
     */
    public function setBloco($bloco)
    {
        $this->bloco = $bloco;
        return $this;
    }

    /**
     * Número do apartamento
     * @return string apartamento of Localização
     */
    public function getApartamento()
    {
        return $this->apartamento;
    }

    /**
     * Set Apartamento value to new on param
     * @param string $apartamento Set apartamento for Localização
     * @return self Self instance
     */
    public function setApartamento($apartamento)
    {
        $this->apartamento = $apartamento;
        return $this;
    }

    /**
     * Ponto de referência para chegar ao local
     * @return string referência of Localização
     */
    public function getReferencia()
    {
        return $this->referencia;
    }

    /**
     * Set Referencia value to new on param
     * @param string $referencia Set referência for Localização
     * @return self Self instance
     */
    public function setReferencia($referencia)
    {
        $this->referencia = $referencia;
        return $this;
    }

    /**
     * Ponto latitudinal para localização em um mapa
     * @return float latitude of Localização
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set Latitude value to new on param
     * @param float $latitude Set latitude for Localização
     * @return self Self instance
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
        return $this;
    }

    /**
     * Ponto longitudinal para localização em um mapa
     * @return float longitude of Localização
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set Longitude value to new on param
     * @param float $longitude Set longitude for Localização
     * @return self Self instance
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
        return $this;
    }

    /**
     * Ex.: Minha Casa, Casa da Amiga
     * @return string apelido of Localização
     */
    public function getApelido()
    {
        return $this->apelido;
    }

    /**
     * Set Apelido value to new on param
     * @param string $apelido Set apelido for Localização
     * @return self Self instance
     */
    public function setApelido($apelido)
    {
        $this->apelido = $apelido;
        return $this;
    }

    /**
     * Permite esconder ou exibir um endereço do cliente
     * @return string mostrar of Localização
     */
    public function getMostrar()
    {
        return $this->mostrar;
    }

    /**
     * Permite esconder ou exibir um endereço do cliente
     * @return boolean Check if o of Mostrar is selected or checked
     */
    public function isMostrar()
    {
        return $this->mostrar == 'Y';
    }

    /**
     * Set Mostrar value to new on param
     * @param string $mostrar Set mostrar for Localização
     * @return self Self instance
     */
    public function setMostrar($mostrar)
    {
        $this->mostrar = $mostrar;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $localizacao = parent::toArray($recursive);
        $localizacao['id'] = $this->getID();
        $localizacao['clienteid'] = $this->getClienteID();
        $localizacao['bairroid'] = $this->getBairroID();
        $localizacao['zonaid'] = $this->getZonaID();
        $localizacao['cep'] = $this->getCEP();
        $localizacao['logradouro'] = $this->getLogradouro();
        $localizacao['numero'] = $this->getNumero();
        $localizacao['tipo'] = $this->getTipo();
        $localizacao['complemento'] = $this->getComplemento();
        $localizacao['condominio'] = $this->getCondominio();
        $localizacao['bloco'] = $this->getBloco();
        $localizacao['apartamento'] = $this->getApartamento();
        $localizacao['referencia'] = $this->getReferencia();
        $localizacao['latitude'] = $this->getLatitude();
        $localizacao['longitude'] = $this->getLongitude();
        $localizacao['apelido'] = $this->getApelido();
        $localizacao['mostrar'] = $this->getMostrar();
        return $localizacao;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param mixed $localizacao Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($localizacao = [])
    {
        if ($localizacao instanceof self) {
            $localizacao = $localizacao->toArray();
        } elseif (!is_array($localizacao)) {
            $localizacao = [];
        }
        parent::fromArray($localizacao);
        if (!isset($localizacao['id'])) {
            $this->setID(null);
        } else {
            $this->setID($localizacao['id']);
        }
        if (!isset($localizacao['clienteid'])) {
            $this->setClienteID(null);
        } else {
            $this->setClienteID($localizacao['clienteid']);
        }
        if (!isset($localizacao['bairroid'])) {
            $this->setBairroID(null);
        } else {
            $this->setBairroID($localizacao['bairroid']);
        }
        if (!array_key_exists('zonaid', $localizacao)) {
            $this->setZonaID(null);
        } else {
            $this->setZonaID($localizacao['zonaid']);
        }
        if (!array_key_exists('cep', $localizacao)) {
            $this->setCEP(null);
        } else {
            $this->setCEP($localizacao['cep']);
        }
        if (!isset($localizacao['logradouro'])) {
            $this->setLogradouro(null);
        } else {
            $this->setLogradouro($localizacao['logradouro']);
        }
        if (!isset($localizacao['numero'])) {
            $this->setNumero(null);
        } else {
            $this->setNumero($localizacao['numero']);
        }
        if (!isset($localizacao['tipo'])) {
            $this->setTipo(self::TIPO_CASA);
        } else {
            $this->setTipo($localizacao['tipo']);
        }
        if (!array_key_exists('complemento', $localizacao)) {
            $this->setComplemento(null);
        } else {
            $this->setComplemento($localizacao['complemento']);
        }
        if (!array_key_exists('condominio', $localizacao)) {
            $this->setCondominio(null);
        } else {
            $this->setCondominio($localizacao['condominio']);
        }
        if (!array_key_exists('bloco', $localizacao)) {
            $this->setBloco(null);
        } else {
            $this->setBloco($localizacao['bloco']);
        }
        if (!array_key_exists('apartamento', $localizacao)) {
            $this->setApartamento(null);
        } else {
            $this->setApartamento($localizacao['apartamento']);
        }
        if (!array_key_exists('referencia', $localizacao)) {
            $this->setReferencia(null);
        } else {
            $this->setReferencia($localizacao['referencia']);
        }
        if (!array_key_exists('latitude', $localizacao)) {
            $this->setLatitude(null);
        } else {
            $this->setLatitude($localizacao['latitude']);
        }
        if (!array_key_exists('longitude', $localizacao)) {
            $this->setLongitude(null);
        } else {
            $this->setLongitude($localizacao['longitude']);
        }
        if (!array_key_exists('apelido', $localizacao)) {
            $this->setApelido(null);
        } else {
            $this->setApelido($localizacao['apelido']);
        }
        if (!isset($localizacao['mostrar'])) {
            $this->setMostrar('N');
        } else {
            $this->setMostrar($localizacao['mostrar']);
        }
        return $this;
    }

    /**
     * Check if this address are the same as informed
     * @param Localizacao $localizacao address to check
     * @return boolean true if address are the same
     */
    public function isSame($localizacao)
    {
        if ($this->getBairroID() == $localizacao->getBairroID()) {
            return false;
        }
        if (mb_strtolower($this->getLogradouro()) != mb_strtolower($localizacao->getLogradouro())) {
            return false;
        }
        if (mb_strtolower($this->getNumero()) != mb_strtolower($localizacao->getNumero())) {
            return false;
        }
        if ($this->getTipo() != $localizacao->getTipo() || $this->getTipo() == self::TIPO_CASA) {
            return true;
        }
        if (mb_strtolower($this->getCondominio()) != mb_strtolower($localizacao->getCondominio())) {
            return false;
        }
        if (mb_strtolower($this->getBloco()) != mb_strtolower($localizacao->getBloco())) {
            return false;
        }
        if (mb_strtolower($this->getApartamento()) != mb_strtolower($localizacao->getApartamento())) {
            return false;
        }
        return true;
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @param \MZ\Provider\Prestador $requester user that request to view this fields
     * @return array All public field and values into array format
     */
    public function publish($requester)
    {
        $localizacao = parent::publish($requester);
        $localizacao['cep'] = Mask::cep($localizacao['cep']);
        return $localizacao;
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
        $this->setClienteID(Filter::number($original->getClienteID()));
        $this->setBairroID(Filter::number($this->getBairroID()));
        $this->setZonaID(Filter::number($this->getZonaID()));
        $this->setCEP(Filter::unmask($this->getCEP(), _p('Mascara', 'CEP')));
        $this->setLogradouro(Filter::string($this->getLogradouro()));
        $this->setNumero(Filter::string($this->getNumero()));
        $this->setComplemento(Filter::string($this->getComplemento()));
        $this->setCondominio(Filter::string($this->getCondominio()));
        $this->setBloco(Filter::string($this->getBloco()));
        $this->setApartamento(Filter::string($this->getApartamento()));
        $this->setReferencia(Filter::string($this->getReferencia()));
        $this->setLatitude(Filter::float($this->getLatitude(), $localized));
        $this->setLongitude(Filter::float($this->getLongitude(), $localized));
        $this->setApelido(Filter::string($this->getApelido()));
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
     * @return array All field of Localizacao in array format
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getClienteID())) {
            $errors['clienteid'] = _t('localizacao.cliente_id_cannot_empty');
        }
        if (is_null($this->getBairroID())) {
            $errors['bairroid'] = _t('localizacao.bairro_id_cannot_empty');
        }
        if (!Validator::checkCEP($this->getCEP(), true)) {
            $errors['cep'] = _t('cep_invalid', _p('Titulo', 'CEP'));
        }
        if (is_null($this->getLogradouro())) {
            $errors['logradouro'] = _t('localizacao.logradouro_cannot_empty');
        }
        if (is_null($this->getNumero())) {
            $errors['numero'] = _t('localizacao.numero_cannot_empty');
        }
        if (!Validator::checkInSet($this->getTipo(), self::getTipoOptions())) {
            $errors['tipo'] = _t('localizacao.tipo_invalid');
        }
        if (!Validator::checkBoolean($this->getMostrar())) {
            $errors['mostrar'] = _t('localizacao.mostrar_invalid');
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
        if (contains(['ClienteID', 'Apelido', 'UNIQUE'], $e->getMessage())) {
            return new ValidationException([
                'clienteid' => _t(
                    'localizacao.cliente_id_used',
                    $this->getClienteID()
                ),
                'apelido' => _t(
                    'localizacao.apelido_used',
                    $this->getApelido()
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Load into this object from database using, ClienteID, Apelido
     * @return self Self filled instance or empty when not found
     */
    public function loadByClienteIDApelido()
    {
        return $this->load([
            'clienteid' => intval($this->getClienteID()),
            'apelido' => strval($this->getApelido()),
        ]);
    }

    /**
     * Load first address from customer with preferred number
     * @return Localizacao Self filled instance or empty when not found
     */
    public function loadByClienteID()
    {
        return $this->load(
            ['clienteid' => $this->getClienteID()],
            [
                'mostrar' => 1,
                'numero' => [-1 => $this->getNumero()]
            ]
        );
    }

    /**
     * Cliente a qual esse endereço pertence
     * @return \MZ\Account\Cliente The object fetched from database
     */
    public function findClienteID()
    {
        return \MZ\Account\Cliente::findByID($this->getClienteID());
    }

    /**
     * Bairro do endereço
     * @return \MZ\Location\Bairro The object fetched from database
     */
    public function findBairroID()
    {
        return \MZ\Location\Bairro::findByID($this->getBairroID());
    }

    /**
     * Informa a zona do bairro
     * @return \MZ\Location\Zona The object fetched from database
     */
    public function findZonaID()
    {
        return \MZ\Location\Zona::findByID($this->getZonaID());
    }

    /**
     * Gets textual and translated Tipo for Localizacao
     * @param int $index choose option from index
     * @return string[] A associative key -> translated representative text or text for index
     */
    public static function getTipoOptions($index = null)
    {
        $options = [
            self::TIPO_CASA => _t('localizacao.tipo_casa'),
            self::TIPO_APARTAMENTO => _t('localizacao.tipo_apartamento'),
            self::TIPO_CONDOMINIO => _t('localizacao.tipo_condominio'),
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
    protected function getAllowedKeys()
    {
        $localizacao = new self();
        $allowed = Filter::concatKeys('l.', $localizacao->toArray());
        $allowed['b.cidadeid'] = true;
        return $allowed;
    }

    /**
     * Filter order array
     * @param mixed $order order string or array to parse and filter allowed
     * @return array allowed associative order
     */
    protected function filterOrder($order)
    {
        $allowed = $this->getAllowedKeys();
        $order = Filter::order($order);
        if (isset($order['numero']) && is_array($order['numero'])) {
            $field = '(l.numero = ?)';
            $order = replace_key($order, 'numero', $field);
            $allowed[$field] = true;
        }
        return Filter::orderBy($order, $allowed, ['l.', 'b.']);
    }

    /**
     * Filter condition array with allowed fields
     * @param array $condition condition to filter rows
     * @return array allowed condition
     */
    protected function filterCondition($condition)
    {
        $allowed = $this->getAllowedKeys();
        if (isset($condition['typesearch'])) {
            $typesearch = $condition['typesearch'];
            $condition = Filter::keys($condition, $allowed, ['l.', 'b.']);
            if (isset($condition['l.tipo']) && $condition['l.tipo'] == self::TIPO_APARTAMENTO) {
                $field = 'l.condominio LIKE ?';
            } else {
                $field = 'l.logradouro LIKE ?';
            }
            $condition[$field] = '%'.$typesearch.'%';
        } else {
            $condition = Filter::keys($condition, $allowed, ['l.', 'b.']);
        }
        return $condition;
    }

    /**
     * Fetch data from database with a condition
     * @param array $condition condition to filter rows
     * @param array $order order rows
     * @return SelectQuery query object with condition statement
     */
    protected function query($condition = [], $order = [])
    {
        $query = DB::from('Localizacoes l');
        $query = $query->leftJoin('Bairros b ON b.id = l.bairroid');
        $typesearch = array_key_exists('typesearch', $condition);
        $condition = $this->filterCondition($condition);
        $query = DB::buildOrderBy($query, $this->filterOrder($order));
        if ($typesearch) {
            if (isset($condition['l.tipo']) && $condition['l.tipo'] == self::TIPO_APARTAMENTO) {
                $query = $query->orderBy('l.condominio ASC');
                $query = $query->groupBy('l.condominio');
            } else {
                $query = $query->orderBy('l.logradouro ASC');
                $query = $query->groupBy('l.logradouro');
            }
        }
        $query = $query->orderBy('l.mostrar ASC');
        $query = $query->orderBy('l.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Find this object on database using, ClienteID, Apelido
     * @param int $cliente_id cliente to find Localização
     * @param string $apelido apelido to find Localização
     * @return self A filled instance or empty when not found
     */
    public static function findByClienteIDApelido($cliente_id, $apelido)
    {
        $result = new self();
        $result->setClienteID($cliente_id);
        $result->setApelido($apelido);
        return $result->loadByClienteIDApelido();
    }

    /**
     * Count all rows from database with matched condition critery
     * @param array $condition condition to filter rows
     * @return integer Quantity of rows
     */
    public static function count($condition = [])
    {
        $typesearch = array_key_exists('typesearch', $condition);
        $instance = new self();
        $query = $instance->query($condition);
        if ($typesearch) {
            $condition = $instance->filterCondition($condition);
            if (isset($condition['l.tipo']) && $condition['l.tipo'] == self::TIPO_APARTAMENTO) {
                $query = $query->select(null)->groupBy(null)->select('COUNT(DISTINCT l.condominio)');
            } else {
                $query = $query->select(null)->groupBy(null)->select('COUNT(DISTINCT l.logradouro)');
            }
            return (int) $query->fetchColumn();
        }
        return $query->count();
    }
}
