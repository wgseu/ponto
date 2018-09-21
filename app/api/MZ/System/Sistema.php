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
namespace MZ\System;

use MZ\Database\Model;
use MZ\Database\DB;
use MZ\Util\Filter;
use MZ\Util\Validator;
use MZ\Location\Localizacao;

/**
 * Classe que informa detalhes da empresa, parceiro e opções do sistema
 * como a versão do banco de dados e a licença de uso
 */
class Sistema extends Model
{
    const VERSAO = '2000';

    /**
     * Identificador único do sistema, valor 1
     */
    private $id;
    /**
     * Servidor do sistema
     */
    private $servidor_id;
    /**
     * Chave da Licença, permite licença do tipo vitalícia
     */
    private $licenca;
    /**
     * Quantidade de tablets e computadores permitido para uso
     */
    private $dispositivos;
    /**
     * Código único da empresa, permite baixar novas licenças automaticamente e
     * autorizar sincronização do servidor
     */
    private $guid;
    /**
     * Informa qual foi a data da última realização de backup do banco de dados
     * do sistema
     */
    private $ultimo_backup;
    /**
     * Informa qual o fuso horário
     */
    private $fuso_horario;
    /**
     * Informa qual a versão do banco de dados
     */
    private $versao_db;

    /* system fields */

    /**
     * Business information
     * @var Empresa
     */
    private $business;
    
    /**
     * Enterprise that manages this system
     * @var \MZ\Account\Cliente
     */
    private $company;

    /**
     * Company address
     * @var \MZ\Location\Localizacao
     */
    private $localization;

    /**
     * Company district location
     * @var \MZ\Location\Bairro
     */
    private $district;

    /**
     * Company city location
     * @var \MZ\Location\Cidade
     */
    private $city;

    /**
     * Company state location
     * @var \MZ\Location\Estado
     */
    private $state;

    /**
     * Company country location
     * @var \MZ\Location\Pais
     */
    private $country;

    /**
     * Country main currency
     * @var \MZ\Wallet\Moeda
     */
    private $currency;

    /**
     * Country region options
     * @var mixed[]
     */
    private $entries;
    /**
     * System settings
     * @var \MZ\Core\Settings
     */
    private $settings;
    /**
     * Website URL
     * @var string
     */
    private $url;

    /* end system fields */

    /**
     * Constructor for a new empty instance of Sistema
     * @param array $sistema All field and values to fill the instance
     */
    public function __construct($sistema = [])
    {
        parent::__construct($sistema);
        $this->settings = new \MZ\Core\Settings(
            isset($sistema['settings']) ? $sistema['settings'] : []
        );
    }

    /**
     * Identificador único do sistema, valor 1
     * @return mixed ID of Sistema
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param  mixed $id new value for ID
     * @return self Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Servidor do sistema
     * @return mixed Servidor of Sistema
     */
    public function getServidorID()
    {
        return $this->servidor_id;
    }

    /**
     * Set ServidorID value to new on param
     * @param  mixed $servidor_id new value for ServidorID
     * @return self Self instance
     */
    public function setServidorID($servidor_id)
    {
        $this->servidor_id = $servidor_id;
        return $this;
    }

    /**
     * Chave da Licença, permite licença do tipo vitalícia
     * @return mixed Chave de licença of Sistema
     */
    public function getLicenca()
    {
        return $this->licenca;
    }

    /**
     * Set Licenca value to new on param
     * @param  mixed $licenca new value for Licenca
     * @return self Self instance
     */
    public function setLicenca($licenca)
    {
        $this->licenca = $licenca;
        return $this;
    }

    /**
     * Quantidade de tablets e computadores permitido para uso
     * @return mixed Quantidade de dispositivos of Sistema
     */
    public function getDispositivos()
    {
        return $this->dispositivos;
    }

    /**
     * Set Dispositivos value to new on param
     * @param  mixed $dispositivos new value for Dispositivos
     * @return self Self instance
     */
    public function setDispositivos($dispositivos)
    {
        $this->dispositivos = $dispositivos;
        return $this;
    }

    /**
     * Código único da empresa, permite baixar novas licenças automaticamente e
     * autorizar sincronização do servidor
     * @return mixed Identificador da empresa of Sistema
     */
    public function getGUID()
    {
        return $this->guid;
    }

    /**
     * Set GUID value to new on param
     * @param  mixed $guid new value for GUID
     * @return self Self instance
     */
    public function setGUID($guid)
    {
        $this->guid = $guid;
        return $this;
    }

    /**
     * Informa qual foi a data da última realização de backup do banco de dados
     * do sistema
     * @return mixed Data do último backup of Sistema
     */
    public function getUltimoBackup()
    {
        return $this->ultimo_backup;
    }

    /**
     * Set UltimoBackup value to new on param
     * @param  mixed $ultimo_backup new value for UltimoBackup
     * @return self Self instance
     */
    public function setUltimoBackup($ultimo_backup)
    {
        $this->ultimo_backup = $ultimo_backup;
        return $this;
    }

    /**
     * Informa qual o fuso horário
     * @return mixed FusoHorario of Sistema
     */
    public function getFusoHorario()
    {
        return $this->fuso_horario;
    }

    /**
     * Set FusoHorario value to new on param
     * @param  mixed $fuso_horario new value for FusoHorario
     * @return self Self instance
     */
    public function setFusoHorario($fuso_horario)
    {
        $this->fuso_horario = $fuso_horario;
        return $this;
    }

    /**
     * Informa qual a versão do banco de dados
     * @return mixed Versão do banco de dados of Sistema
     */
    public function getVersaoDB()
    {
        return $this->versao_db;
    }

    /**
     * Set VersaoDB value to new on param
     * @param  mixed $versao_db new value for VersaoDB
     * @return self Self instance
     */
    public function setVersaoDB($versao_db)
    {
        $this->versao_db = $versao_db;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param  boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $sistema = parent::toArray($recursive);
        $sistema['id'] = $this->getID();
        $sistema['servidorid'] = $this->getServidorID();
        $sistema['licenca'] = $this->getLicenca();
        $sistema['dispositivos'] = $this->getDispositivos();
        $sistema['guid'] = $this->getGUID();
        $sistema['ultimobackup'] = $this->getUltimoBackup();
        $sistema['fusohorario'] = $this->getFusoHorario();
        $sistema['versaodb'] = $this->getVersaoDB();
        return $sistema;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param  mixed $sistema Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($sistema = [])
    {
        if ($sistema instanceof self) {
            $sistema = $sistema->toArray();
        } elseif (!is_array($sistema)) {
            $sistema = [];
        }
        parent::fromArray($sistema);
        if (!isset($sistema['id'])) {
            $this->setID(null);
        } else {
            $this->setID($sistema['id']);
        }
        if (!isset($sistema['servidorid'])) {
            $this->setServidorID(null);
        } else {
            $this->setServidorID($sistema['servidorid']);
        }
        if (!array_key_exists('licenca', $sistema)) {
            $this->setLicenca(null);
        } else {
            $this->setLicenca($sistema['licenca']);
        }
        if (!array_key_exists('dispositivos', $sistema)) {
            $this->setDispositivos(null);
        } else {
            $this->setDispositivos($sistema['dispositivos']);
        }
        if (!array_key_exists('guid', $sistema)) {
            $this->setGUID(null);
        } else {
            $this->setGUID($sistema['guid']);
        }
        if (!array_key_exists('ultimobackup', $sistema)) {
            $this->setUltimoBackup(null);
        } else {
            $this->setUltimoBackup($sistema['ultimobackup']);
        }
        if (!array_key_exists('fusohorario', $sistema)) {
            $this->setFusoHorario(null);
        } else {
            $this->setFusoHorario($sistema['fusohorario']);
        }
        if (!isset($sistema['versaodb'])) {
            $this->setVersaoDB(null);
        } else {
            $this->setVersaoDB($sistema['versaodb']);
        }
        return $this;
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $sistema = parent::publish();
        unset($sistema['licenca']);
        unset($sistema['guid']);
        return $sistema;
    }

    /**
     * Informa se o sistema está operando no modo fiscal
     * @return boolean true para modo fiscal, false caso contrário
     */
    public function isFiscal()
    {
        return !is_null(get_string_config('Licenca', 'Modulo.Fiscal', null));
    }

    /**
     * Informa se o sistema dve exibir configurações fiscais
     * @return boolean true para exibir, false caso contrário
     */
    public function isFiscalVisible()
    {
        return $this->isFiscal() || is_boolean_config('Sistema', 'Fiscal.Mostrar', false);
    }

    /**
     * Filter fields, upload data and keep key data
     * @param Sistema $original Original instance without modifications
     */
    public function filter($original)
    {
        $this->setID($original->getID());
        $this->setServidorID(Filter::number($this->getServidorID()));
        $this->setLicenca(Filter::text($this->getLicenca()));
        $this->setDispositivos(Filter::number($this->getDispositivos()));
        $this->setGUID(Filter::string($this->getGUID()));
        $this->setUltimoBackup(Filter::datetime($this->getUltimoBackup()));
        $this->setFusoHorario(Filter::string($this->getFusoHorario()));
        $this->setVersaoDB(Filter::string($this->getVersaoDB()));
    }

    /**
     * Clean instance resources like images and docs
     * @param Sistema $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return mixed[] All field of Sistema in array format
     */
    public function validate()
    {
        $errors = [];
        if ($this->getID() != '1') {
            $errors['id'] = 'O id do sistema não foi informado';
        }
        if (is_null($this->getServidorID())) {
            $errors['servidorid'] = 'O servidor não pode ser vazio';
        }
        if (is_null($this->getVersaoDB())) {
            $errors['versaodb'] = 'A versão do banco de dados não pode ser vazia';
        }
        if (!empty($errors)) {
            throw new \MZ\Exception\ValidationException($errors);
        }
        return $this->toArray();
    }

    /**
     * Business of company
     * @return Empresa
     */
    public function getBusiness()
    {
        return $this->business;
    }

    /**
     * Company that manages this system
     * @return \MZ\Account\Cliente
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Company address
     * @return \MZ\Location\Localizacao
     */
    public function getLocalization()
    {
        return $this->localization;
    }

    /**
     * Company district location
     * @return \MZ\Location\Bairro
     */
    public function getDistrict()
    {
        return $this->district;
    }

    /**
     * Company city location
     * @return \MZ\Location\Cidade
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Company state location
     * @return \MZ\Location\Estado
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Company country location
     * @return \MZ\Location\Pais
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Country main currency
     * @return \MZ\Wallet\Moeda
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Country region options
     * @var mixed[]
     */
    public function getEntries()
    {
        return $this->entries;
    }

    /**
     * Get the system settings
     * @return \MZ\Core\Settings system settings
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * Get the current URL for this application system
     * @return string URL with protocol for this system
     */
    public function getURL()
    {
        return $this->url;
    }

    public function initialize($app_path)
    {
        $this->business = new Empresa();
        $this->getSettings()->load($app_path  . '/config');
        $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : null;
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
            $protocol = 'https';
        } else {
            $protocol = 'http';
        }
        $this->url = "{$protocol}://{$host}";
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
     * Insert a new Sistema into the database and fill instance from database
     * @return Sistema Self instance
     */
    public function insert()
    {
        throw new \Exception(
            'Nenhuma informação sobre o sistema, contacte seu fornecedor para resolve o problema',
            500
        );
    }

    /**
     * Update Sistema with instance values into database for ID
     * @param  array $only Save these fields only, when empty save all fields except id
     * @return Sistema Self instance
     */
    public function update($only = [])
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador do sistema não foi informado');
        }
        $values = DB::filterValues($values, $only, false);
        try {
            DB::update('Sistema')
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
        throw new \Exception(
            'Não é possível excluir informações do sistema',
            500
        );
    }

    /**
     * Load one register for it self with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order associative field name -> [-1, 1]
     * @return Sistema Self instance filled or empty
     */
    public function load($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return $this->fromArray($row);
    }

    /**
     * load all system data from database and local settings
     * @return self Self instance loaded
     */
    public function loadAll()
    {
        $this->setID('1');
        $this->loadByID();
        $this->getBusiness()->loadAll();
        $this->company  = $this->getBusiness()->findEmpresaID();
        $this->localization = Localizacao::find(['clienteid' => $this->getCompany()->getID()]);
        $this->district = $this->getLocalization()->findBairroID();
        $this->city = $this->getDistrict()->findCidadeID();
        $this->state = $this->getCity()->findEstadoID();
        $this->country = $this->getBusiness()->findPaisID();
        $this->currency = $this->getCountry()->findMoedaID();

        set_timezone_for($this->getState()->getUF(), $this->getCountry()->getSigla());
        $this->entries = parse_ini_string(base64_decode($this->getCountry()->getEntradas()), true, INI_SCANNER_RAW);
        settype($this->entries, 'array');
        return $this;
    }

    /**
     * Servidor do sistema
     * @return Servidor The object fetched from database
     */
    public function findServidorID()
    {
        return Servidor::findByID($this->getServidorID());
    }

    /**
     * Get allowed keys array
     * @return array allowed keys array
     */
    private static function getAllowedKeys()
    {
        $sistema = new Sistema();
        $allowed = Filter::concatKeys('s.', $sistema->toArray());
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
        return Filter::orderBy($order, $allowed, 's.');
    }

    /**
     * Filter condition array with allowed fields
     * @param  array $condition condition to filter rows
     * @return array allowed condition
     */
    private static function filterCondition($condition)
    {
        $allowed = self::getAllowedKeys();
        return Filter::keys($condition, $allowed, 's.');
    }

    /**
     * Fetch data from database with a condition
     * @param  array $condition condition to filter rows
     * @param  array $order order rows
     * @return SelectQuery query object with condition statement
     */
    private static function query($condition = [], $order = [])
    {
        $query = DB::from('Sistema s');
        $condition = self::filterCondition($condition);
        $query = DB::buildOrderBy($query, self::filterOrder($order));
        return DB::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order order rows
     * @return Sistema A filled Sistema or empty instance
     */
    public static function find($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return new Sistema($row);
    }

    /**
     * Find all Sistema
     * @param  array  $condition Condition to get all Sistema
     * @param  array  $order     Order Sistema
     * @param  int    $limit     Limit data into row count
     * @param  int    $offset    Start offset to get rows
     * @return array             List of all rows instanced as Sistema
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
            $result[] = new Sistema($row);
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
