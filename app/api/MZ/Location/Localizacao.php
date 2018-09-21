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

use MZ\Database\SyncModel;
use MZ\Database\DB;
use MZ\Util\Filter;
use MZ\Util\Validator;

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
     * @return mixed ID of Localizacao
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param  mixed $id new value for ID
     * @return Localizacao Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Cliente a qual esse endereço pertence
     * @return mixed Cliente of Localizacao
     */
    public function getClienteID()
    {
        return $this->cliente_id;
    }

    /**
     * Set ClienteID value to new on param
     * @param  mixed $cliente_id new value for ClienteID
     * @return Localizacao Self instance
     */
    public function setClienteID($cliente_id)
    {
        $this->cliente_id = $cliente_id;
        return $this;
    }

    /**
     * Bairro do endereço
     * @return mixed Bairro of Localizacao
     */
    public function getBairroID()
    {
        return $this->bairro_id;
    }

    /**
     * Set BairroID value to new on param
     * @param  mixed $bairro_id new value for BairroID
     * @return Localizacao Self instance
     */
    public function setBairroID($bairro_id)
    {
        $this->bairro_id = $bairro_id;
        return $this;
    }

    /**
     * Código dos correios para identificar um logradouro
     * @return mixed CEP of Localizacao
     */
    public function getCEP()
    {
        return $this->cep;
    }

    /**
     * Set CEP value to new on param
     * @param  mixed $cep new value for CEP
     * @return Localizacao Self instance
     */
    public function setCEP($cep)
    {
        $this->cep = $cep;
        return $this;
    }

    /**
     * Nome da rua ou avenida
     * @return mixed Logradouro of Localizacao
     */
    public function getLogradouro()
    {
        return $this->logradouro;
    }

    /**
     * Set Logradouro value to new on param
     * @param  mixed $logradouro new value for Logradouro
     * @return Localizacao Self instance
     */
    public function setLogradouro($logradouro)
    {
        $this->logradouro = $logradouro;
        return $this;
    }

    /**
     * Número da casa ou do condomínio
     * @return mixed Número of Localizacao
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set Numero value to new on param
     * @param  mixed $numero new value for Numero
     * @return Localizacao Self instance
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;
        return $this;
    }

    /**
     * Tipo de endereço Casa ou Apartamento
     * @return mixed Tipo of Localizacao
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set Tipo value to new on param
     * @param  mixed $tipo new value for Tipo
     * @return Localizacao Self instance
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
        return $this;
    }

    /**
     * Complemento do endereço, Ex.: Loteamento Sul
     * @return mixed Complemento of Localizacao
     */
    public function getComplemento()
    {
        return $this->complemento;
    }

    /**
     * Set Complemento value to new on param
     * @param  mixed $complemento new value for Complemento
     * @return Localizacao Self instance
     */
    public function setComplemento($complemento)
    {
        $this->complemento = $complemento;
        return $this;
    }

    /**
     * Nome do condomínio
     * @return mixed Condomínio of Localizacao
     */
    public function getCondominio()
    {
        return $this->condominio;
    }

    /**
     * Set Condominio value to new on param
     * @param  mixed $condominio new value for Condominio
     * @return Localizacao Self instance
     */
    public function setCondominio($condominio)
    {
        $this->condominio = $condominio;
        return $this;
    }

    /**
     * Número do bloco quando for apartamento
     * @return mixed Bloco of Localizacao
     */
    public function getBloco()
    {
        return $this->bloco;
    }

    /**
     * Set Bloco value to new on param
     * @param  mixed $bloco new value for Bloco
     * @return Localizacao Self instance
     */
    public function setBloco($bloco)
    {
        $this->bloco = $bloco;
        return $this;
    }

    /**
     * Número do apartamento
     * @return mixed Apartamento of Localizacao
     */
    public function getApartamento()
    {
        return $this->apartamento;
    }

    /**
     * Set Apartamento value to new on param
     * @param  mixed $apartamento new value for Apartamento
     * @return Localizacao Self instance
     */
    public function setApartamento($apartamento)
    {
        $this->apartamento = $apartamento;
        return $this;
    }

    /**
     * Ponto de referência para chegar ao local
     * @return mixed Referência of Localizacao
     */
    public function getReferencia()
    {
        return $this->referencia;
    }

    /**
     * Set Referencia value to new on param
     * @param  mixed $referencia new value for Referencia
     * @return Localizacao Self instance
     */
    public function setReferencia($referencia)
    {
        $this->referencia = $referencia;
        return $this;
    }

    /**
     * Ponto latitudinal para localização em um mapa
     * @return mixed Latitude of Localizacao
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set Latitude value to new on param
     * @param  mixed $latitude new value for Latitude
     * @return Localizacao Self instance
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
        return $this;
    }

    /**
     * Ponto longitudinal para localização em um mapa
     * @return mixed Longitude of Localizacao
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set Longitude value to new on param
     * @param  mixed $longitude new value for Longitude
     * @return Localizacao Self instance
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
        return $this;
    }

    /**
     * Ex.: Minha Casa, Casa da Amiga
     * @return mixed Apelido of Localizacao
     */
    public function getApelido()
    {
        return $this->apelido;
    }

    /**
     * Set Apelido value to new on param
     * @param  mixed $apelido new value for Apelido
     * @return Localizacao Self instance
     */
    public function setApelido($apelido)
    {
        $this->apelido = $apelido;
        return $this;
    }

    /**
     * Permite esconder ou exibir um endereço do cliente
     * @return mixed Mostrar of Localizacao
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
     * @param  mixed $mostrar new value for Mostrar
     * @return Localizacao Self instance
     */
    public function setMostrar($mostrar)
    {
        $this->mostrar = $mostrar;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param  boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $localizacao = parent::toArray($recursive);
        $localizacao['id'] = $this->getID();
        $localizacao['clienteid'] = $this->getClienteID();
        $localizacao['bairroid'] = $this->getBairroID();
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
     * @param  mixed $localizacao Associated key -> value to assign into this instance
     * @return Localizacao Self instance
     */
    public function fromArray($localizacao = [])
    {
        if ($localizacao instanceof Localizacao) {
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
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $localizacao = parent::publish();
        $localizacao['cep'] = \MZ\Util\Mask::cep($localizacao['cep']);
        return $localizacao;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param Localizacao $original Original instance without modifications
     */
    public function filter($original, $localized = false)
    {
        $this->setID($original->getID());
        $this->setClienteID(Filter::number($original->getClienteID()));
        $this->setBairroID(Filter::number($this->getBairroID()));
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
    }

    /**
     * Clean instance resources like images and docs
     * @param  Localizacao $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Localizacao in array format
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getClienteID())) {
            $errors['clienteid'] = 'O cliente não pode ser vazio';
        }
        if (is_null($this->getBairroID())) {
            $errors['bairroid'] = 'O bairro não pode ser vazio';
        }
        if (!Validator::checkCEP($this->getCEP(), true)) {
            $errors['cep'] = sprintf('O %s é inválido', _p('Titulo', 'CEP'));
        }
        if (is_null($this->getLogradouro())) {
            $errors['logradouro'] = 'O logradouro não pode ser vazio';
        }
        if (is_null($this->getNumero())) {
            $errors['numero'] = 'O número não pode ser vazio';
        }
        if (is_null($this->getTipo())) {
            $this->setTipo(self::TIPO_CASA);
        }
        if (!is_null($this->getTipo()) &&
            !array_key_exists($this->getTipo(), self::getTipoOptions())
        ) {
            $errors['tipo'] = 'O tipo é inválido';
        }
        if (is_null($this->getMostrar())) {
            $this->setMostrar('N');
        }
        if (!is_null($this->getMostrar()) &&
            !array_key_exists($this->getMostrar(), DB::getBooleanOptions())
        ) {
            $errors['mostrar'] = 'A exibição do endereço é inválida';
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
        if (stripos($e->getMessage(), 'UK_Localizacoes_ClienteID_Apelido') !== false) {
            return new \MZ\Exception\ValidationException([
                'clienteid' => vsprintf(
                    'O cliente "%s" já está cadastrado',
                    [$this->getClienteID()]
                ),
                'apelido' => vsprintf(
                    'O apelido "%s" já está cadastrado',
                    [$this->getApelido()]
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Insert a new Localização into the database and fill instance from database
     * @return Localizacao Self instance
     */
    public function insert()
    {
        $this->setID(null);
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = DB::insertInto('Localizacoes')->values($values)->execute();
            $this->setID($id);
            $this->loadByID();
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Localização with instance values into database for ID
     * @param  array $only Save these fields only, when empty save all fields except id
     * @return Localizacao Self instance
     */
    public function update($only = [])
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador da localização não foi informado');
        }
        $values = DB::filterValues($values, $only, false);
        try {
            DB::update('Localizacoes')
                ->set($values)
                ->where('id', $this->getID())
                ->execute();
            $this->loadByID();
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
            throw new \Exception('O identificador da localização não foi informado');
        }
        $result = DB::deleteFrom('Localizacoes')
            ->where('id', $this->getID())
            ->execute();
        return $result;
    }

    /**
     * Load one register for it self with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order associative field name -> [-1, 1]
     * @return Localizacao Self instance filled or empty
     */
    public function load($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return $this->fromArray($row);
    }

    /**
     * Load into this object from database using, ClienteID, Apelido
     * @param  int $cliente_id cliente to find Localização
     * @param  string $apelido apelido to find Localização
     * @return Localizacao Self filled instance or empty when not found
     */
    public function loadByClienteIDApelido($cliente_id, $apelido)
    {
        return $this->load([
            'clienteid' => intval($cliente_id),
            'apelido' => strval($apelido),
        ]);
    }

    /**
     * Search from database customer localization from CEP
     * @return Localizacao Self filled instance or empty when not found
     */
    public function loadByCEP()
    {
        return $this->load([
            'cep' => strval($this->getCEP()),
            'clienteid' => $this->getClienteID(),
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
     * @return \ZCliente The object fetched from database
     */
    public function findClienteID()
    {
        return \Cliente::findByID($this->getClienteID());
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
     * Gets textual and translated Tipo for Localizacao
     * @param  int $index choose option from index
     * @return mixed A associative key -> translated representative text or text for index
     */
    public static function getTipoOptions($index = null)
    {
        $options = [
            self::TIPO_CASA => 'Casa',
            self::TIPO_APARTAMENTO => 'Apartamento',
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
        $localizacao = new Localizacao();
        $allowed = Filter::concatKeys('l.', $localizacao->toArray());
        $allowed['b.cidadeid'] = true;
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
     * @param  array $condition condition to filter rows
     * @return array allowed condition
     */
    private static function filterCondition($condition)
    {
        $allowed = self::getAllowedKeys();
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
     * @param  array $condition condition to filter rows
     * @param  array $order order rows
     * @return SelectQuery query object with condition statement
     */
    private static function query($condition = [], $order = [])
    {
        $query = DB::from('Localizacoes l');
        $query = $query->leftJoin('Bairros b ON b.id = l.bairroid');
        $typesearch = array_key_exists('typesearch', $condition);
        $condition = self::filterCondition($condition);
        $query = DB::buildOrderBy($query, self::filterOrder($order));
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
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order order rows
     * @return Localizacao A filled Localização or empty instance
     */
    public static function find($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch();
        if ($row === false) {
            $row = [];
        }
        return new Localizacao($row);
    }

    /**
     * Fetch all rows from database with matched condition critery
     * @param  array $condition condition to filter rows
     * @param  integer $limit number of rows to get, null for all
     * @param  integer $offset start index to get rows, null for begining
     * @return array All rows instanced and filled
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
            $result[] = new Localizacao($row);
        }
        return $result;
    }

    /**
     * Find this object on database using, ClienteID, Apelido
     * @param  int $cliente_id cliente to find Localização
     * @param  string $apelido apelido to find Localização
     * @return Localizacao A filled instance or empty when not found
     */
    public static function findByClienteIDApelido($cliente_id, $apelido)
    {
        $result = new self();
        return $result->loadByClienteIDApelido($cliente_id, $apelido);
    }

    /**
     * Count all rows from database with matched condition critery
     * @param  array $condition condition to filter rows
     * @return integer Quantity of rows
     */
    public static function count($condition = [])
    {
        $typesearch = array_key_exists('typesearch', $condition);
        $query = self::query($condition);
        if ($typesearch) {
            $condition = self::filterCondition($condition);
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
