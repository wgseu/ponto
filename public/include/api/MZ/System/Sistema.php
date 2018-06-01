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
    const VERSAO = '1935';

    /**
     * Identificador único do sistema, valor 1
     */
    private $id;
    /**
     * País em que o sistema está sendo utilizado
     */
    private $pais_id;
    /**
     * Informa qual a empresa que gerencia o sistema, a empresa deve ser um
     * cliente do tipo pessoa jurídica
     */
    private $empresa_id;
    /**
     * Informa quem realiza o suporte do sistema, deve ser um cliente do tipo
     * empresa que possua um acionista como representante
     */
    private $parceiro_id;
    /**
     * Chave de acesso ao sistema, a chave é atualizada sempre ao utilizar o
     * programa
     */
    private $access_key;
    /**
     * Chave de registro, permite licenças do tipo aluguel
     */
    private $registry_key;
    /**
     * Chave da Licença, permite licença do tipo vitalícia
     */
    private $license_key;
    /**
     * Quantidade de computadores permitido para uso em rede
     */
    private $computadores;
    /**
     * Código único da empresa, permite baixar novas licenças automaticamente
     */
    private $guid;
    /**
     * Opções gerais do sistema como opções de impressão
     */
    private $opcoes;
    /**
     * Informa qual foi a data da última realização de backup do banco de dados
     * do sistema
     */
    private $ultimo_backup;
    /**
     * Informa qual a versão do banco de dados
     */
    private $versao_db;

    /* system fields */

    /**
     * Enterprise that manages this system
     * @var Cliente
     */
    private $company;
    private $localization;
    private $district;
    private $city;
    private $state;
    private $country;
    private $currency;
    private $entries;
    /**
     * System settings
     * @var Settings
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
        $this->company = new \MZ\Account\Cliente();
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
     * @return Sistema Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * País em que o sistema está sendo utilizado
     * @return mixed País of Sistema
     */
    public function getPaisID()
    {
        return $this->pais_id;
    }

    /**
     * Set PaisID value to new on param
     * @param  mixed $pais_id new value for PaisID
     * @return Sistema Self instance
     */
    public function setPaisID($pais_id)
    {
        $this->pais_id = $pais_id;
        return $this;
    }

    /**
     * Informa qual a empresa que gerencia o sistema, a empresa deve ser um
     * cliente do tipo pessoa jurídica
     * @return mixed Empresa of Sistema
     */
    public function getEmpresaID()
    {
        return $this->empresa_id;
    }

    /**
     * Set EmpresaID value to new on param
     * @param  mixed $empresa_id new value for EmpresaID
     * @return Sistema Self instance
     */
    public function setEmpresaID($empresa_id)
    {
        $this->empresa_id = $empresa_id;
        return $this;
    }

    /**
     * Informa quem realiza o suporte do sistema, deve ser um cliente do tipo
     * empresa que possua um acionista como representante
     * @return mixed Parceiro of Sistema
     */
    public function getParceiroID()
    {
        return $this->parceiro_id;
    }

    /**
     * Set ParceiroID value to new on param
     * @param  mixed $parceiro_id new value for ParceiroID
     * @return Sistema Self instance
     */
    public function setParceiroID($parceiro_id)
    {
        $this->parceiro_id = $parceiro_id;
        return $this;
    }

    /**
     * Chave de acesso ao sistema, a chave é atualizada sempre ao utilizar o
     * programa
     * @return mixed Chave de acesso of Sistema
     */
    public function getAccessKey()
    {
        return $this->access_key;
    }

    /**
     * Set AccessKey value to new on param
     * @param  mixed $access_key new value for AccessKey
     * @return Sistema Self instance
     */
    public function setAccessKey($access_key)
    {
        $this->access_key = $access_key;
        return $this;
    }

    /**
     * Chave de registro, permite licenças do tipo aluguel
     * @return mixed Chave de registro of Sistema
     */
    public function getRegistryKey()
    {
        return $this->registry_key;
    }

    /**
     * Set RegistryKey value to new on param
     * @param  mixed $registry_key new value for RegistryKey
     * @return Sistema Self instance
     */
    public function setRegistryKey($registry_key)
    {
        $this->registry_key = $registry_key;
        return $this;
    }

    /**
     * Chave da Licença, permite licença do tipo vitalícia
     * @return mixed Chave de licença of Sistema
     */
    public function getLicenseKey()
    {
        return $this->license_key;
    }

    /**
     * Set LicenseKey value to new on param
     * @param  mixed $license_key new value for LicenseKey
     * @return Sistema Self instance
     */
    public function setLicenseKey($license_key)
    {
        $this->license_key = $license_key;
        return $this;
    }

    /**
     * Quantidade de computadores permitido para uso em rede
     * @return mixed Quantidade de computadores of Sistema
     */
    public function getComputadores()
    {
        return $this->computadores;
    }

    /**
     * Set Computadores value to new on param
     * @param  mixed $computadores new value for Computadores
     * @return Sistema Self instance
     */
    public function setComputadores($computadores)
    {
        $this->computadores = $computadores;
        return $this;
    }

    /**
     * Código único da empresa, permite baixar novas licenças automaticamente
     * @return mixed Identificador da empresa of Sistema
     */
    public function getGUID()
    {
        return $this->guid;
    }

    /**
     * Set GUID value to new on param
     * @param  mixed $guid new value for GUID
     * @return Sistema Self instance
     */
    public function setGUID($guid)
    {
        $this->guid = $guid;
        return $this;
    }

    /**
     * Opções gerais do sistema como opções de impressão
     * @return mixed Opções do sistema of Sistema
     */
    public function getOpcoes()
    {
        return $this->opcoes;
    }

    /**
     * Set Opcoes value to new on param
     * @param  mixed $opcoes new value for Opcoes
     * @return Sistema Self instance
     */
    public function setOpcoes($opcoes)
    {
        $this->opcoes = $opcoes;
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
     * @return Sistema Self instance
     */
    public function setUltimoBackup($ultimo_backup)
    {
        $this->ultimo_backup = $ultimo_backup;
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
     * @return Sistema Self instance
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
        $sistema['paisid'] = $this->getPaisID();
        $sistema['empresaid'] = $this->getEmpresaID();
        $sistema['parceiroid'] = $this->getParceiroID();
        $sistema['accesskey'] = $this->getAccessKey();
        $sistema['registrykey'] = $this->getRegistryKey();
        $sistema['licensekey'] = $this->getLicenseKey();
        $sistema['computadores'] = $this->getComputadores();
        $sistema['guid'] = $this->getGUID();
        $sistema['opcoes'] = $this->getOpcoes();
        $sistema['ultimobackup'] = $this->getUltimoBackup();
        $sistema['versaodb'] = $this->getVersaoDB();
        return $sistema;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param  mixed $sistema Associated key -> value to assign into this instance
     * @return Sistema Self instance
     */
    public function fromArray($sistema = [])
    {
        if ($sistema instanceof Sistema) {
            $sistema = $sistema->toArray();
        } elseif (!is_array($sistema)) {
            $sistema = [];
        }
        parent::fromArray($sistema);
        if (!isset($sistema['id'])) {
            $this->setID('1');
        } else {
            $this->setID($sistema['id']);
        }
        if (!array_key_exists('paisid', $sistema)) {
            $this->setPaisID(null);
        } else {
            $this->setPaisID($sistema['paisid']);
        }
        if (!array_key_exists('empresaid', $sistema)) {
            $this->setEmpresaID(null);
        } else {
            $this->setEmpresaID($sistema['empresaid']);
        }
        if (!array_key_exists('parceiroid', $sistema)) {
            $this->setParceiroID(null);
        } else {
            $this->setParceiroID($sistema['parceiroid']);
        }
        if (!array_key_exists('accesskey', $sistema)) {
            $this->setAccessKey(null);
        } else {
            $this->setAccessKey($sistema['accesskey']);
        }
        if (!array_key_exists('registrykey', $sistema)) {
            $this->setRegistryKey(null);
        } else {
            $this->setRegistryKey($sistema['registrykey']);
        }
        if (!array_key_exists('licensekey', $sistema)) {
            $this->setLicenseKey(null);
        } else {
            $this->setLicenseKey($sistema['licensekey']);
        }
        if (!array_key_exists('computadores', $sistema)) {
            $this->setComputadores(null);
        } else {
            $this->setComputadores($sistema['computadores']);
        }
        if (!array_key_exists('guid', $sistema)) {
            $this->setGUID(null);
        } else {
            $this->setGUID($sistema['guid']);
        }
        if (!array_key_exists('opcoes', $sistema)) {
            $this->setOpcoes(null);
        } else {
            $this->setOpcoes($sistema['opcoes']);
        }
        if (!array_key_exists('ultimobackup', $sistema)) {
            $this->setUltimoBackup(null);
        } else {
            $this->setUltimoBackup($sistema['ultimobackup']);
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
        unset($sistema['accesskey']);
        unset($sistema['registrykey']);
        unset($sistema['licensekey']);
        unset($sistema['guid']);
        unset($sistema['opcoes']);
        return $sistema;
    }

    /**
     * Quantidade de tablets permitido para uso em rede
     */
    public function getTablets()
    {
        return $this->getComputadores() * 2;
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
        $this->setPaisID(Filter::number($this->getPaisID()));
        $this->setEmpresaID(Filter::number($this->getEmpresaID()));
        $this->setParceiroID(Filter::number($this->getParceiroID()));
        $this->setAccessKey(Filter::text($this->getAccessKey()));
        $this->setRegistryKey(Filter::text($this->getRegistryKey()));
        $this->setLicenseKey(Filter::text($this->getLicenseKey()));
        $this->setComputadores(Filter::number($this->getComputadores()));
        $this->setGUID(Filter::string($this->getGUID()));

        $opcoes = to_ini($this->getSettings()->getValues());
        $opcoes = base64_encode($opcoes);
        $this->setOpcoes($opcoes);

        $this->setUltimoBackup(Filter::datetime($this->getUltimoBackup()));
        $this->setVersaoDB(Filter::string($this->getVersaoDB()));
    }

    /**
     * Clean instance resources like images and docs
     * @param  Sistema $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Sistema in array format
     */
    public function validate()
    {
        $errors = [];
        if ($this->getID() != '1') {
            $errors['id'] = 'O id do sistema não foi informado';
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
     * Company that manages this system
     * @return Empresa company of this system
     */
    public function getCompany()
    {
        return $this->company;
    }

    public function getLocalization()
    {
        return $this->localization;
    }

    public function getDistrict()
    {
        return $this->district;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function getState()
    {
        return $this->state;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function getEntries()
    {
        return $this->entries;
    }

    /**
     * Get the system settings
     * @return Settings system settings
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
        $this->getSettings()->load($app_path  . '/public/include/configure');
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
     * @param  boolean $except When true, saves all fields except $only
     * @return Sistema Self instance
     */
    public function update($only = [], $except = false)
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador do sistema não foi informado');
        }
        $values = DB::filterValues($values, $only, $except);
        try {
            DB::update('Sistema')
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
            throw new \Exception('O identificador do sistema não foi informado');
        }
        $result = DB::deleteFrom('Sistema')
            ->where('id', $this->getID())
            ->execute();
        return $result;
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

    public function loadAll()
    {
        $this->loadByID('1');
        $this->company  = $this->findEmpresaID();
        $this->localization = Localizacao::find(['clienteid' => $this->company->getID()]);
        $this->district = $this->localization->findBairroID();
        $this->city = $this->district->findCidadeID();
        $this->state = $this->city->findEstadoID();
        $this->country = $this->findPaisID();
        $this->currency = $this->country->findMoedaID();

        set_timezone_for($this->state->getUF(), $this->country->getSigla());

        $values = parse_ini_string(base64_decode($this->getOpcoes()), true, INI_SCANNER_RAW);
        settype($values, 'array');
        $this->getSettings()->addValues($values);
        $this->entries = parse_ini_string(base64_decode($this->country->getEntradas()), true, INI_SCANNER_RAW);
        settype($this->entries, 'array');
    }

    /**
     * País em que o sistema está sendo utilizado
     * @return \MZ\Location\Pais The object fetched from database
     */
    public function findPaisID()
    {
        if (is_null($this->getPaisID())) {
            return new \MZ\Location\Pais();
        }
        return \MZ\Location\Pais::findByID($this->getPaisID());
    }

    /**
     * Informa qual a empresa que gerencia o sistema, a empresa deve ser um
     * cliente do tipo pessoa jurídica
     * @return \MZ\Account\Cliente The object fetched from database
     */
    public function findEmpresaID()
    {
        if (is_null($this->getEmpresaID())) {
            return new \MZ\Account\Cliente();
        }
        return \MZ\Account\Cliente::findByID($this->getEmpresaID());
    }

    /**
     * Informa quem realiza o suporte do sistema, deve ser um cliente do tipo
     * empresa que possua um acionista como representante
     * @return \MZ\Account\Cliente The object fetched from database
     */
    public function findParceiroID()
    {
        if (is_null($this->getParceiroID())) {
            return new \MZ\Account\Cliente();
        }
        return \MZ\Account\Cliente::findByID($this->getParceiroID());
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
        if (isset($condition['search'])) {
            $search = $condition['search'];
            $field = 's.versaodb LIKE ?';
            $condition[$field] = '%'.$search.'%';
            $allowed[$field] = true;
            unset($condition['search']);
        }
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
        $query = $query->orderBy('s.versaodb ASC');
        $query = $query->orderBy('s.id ASC');
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
