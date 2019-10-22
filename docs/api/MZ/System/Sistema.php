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

use MZ\Util\Mask;
use MZ\Util\Filter;
use MZ\Util\Validator;
use MZ\Database\DB;
use MZ\Database\Model;
use MZ\Exception\ValidationException;
use MZ\Location\Localizacao;

/**
 * Classe que informa detalhes da empresa, parceiro e opções do sistema
 * como a versão do banco de dados e a licença de uso
 */
class Sistema extends Model
{
    const VERSAO = '2000';

    protected $table = 'Sistema';

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
    public $business;
    
    /**
     * Enterprise that manages this system
     * @var \MZ\Account\Cliente
     */
    public $company;

    /**
     * Company address
     * @var \MZ\Location\Localizacao
     */
    public $localization;

    /**
     * Company district location
     * @var \MZ\Location\Bairro
     */
    public $district;

    /**
     * Company city location
     * @var \MZ\Location\Cidade
     */
    public $city;

    /**
     * Company state location
     * @var \MZ\Location\Estado
     */
    public $state;

    /**
     * Company country location
     * @var \MZ\Location\Pais
     */
    public $country;

    /**
     * Country main currency
     * @var \MZ\Wallet\Moeda
     */
    public $currency;

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
     * @return string id of Sistema
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param string $id Set id for Sistema
     * @return self Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Servidor do sistema
     * @return int servidor of Sistema
     */
    public function getServidorID()
    {
        return $this->servidor_id;
    }

    /**
     * Set ServidorID value to new on param
     * @param int $servidor_id Set servidor for Sistema
     * @return self Self instance
     */
    public function setServidorID($servidor_id)
    {
        $this->servidor_id = $servidor_id;
        return $this;
    }

    /**
     * Chave da Licença, permite licença do tipo vitalícia
     * @return string chave de licença of Sistema
     */
    public function getLicenca()
    {
        return $this->licenca;
    }

    /**
     * Set Licenca value to new on param
     * @param string $licenca Set chave de licença for Sistema
     * @return self Self instance
     */
    public function setLicenca($licenca)
    {
        $this->licenca = $licenca;
        return $this;
    }

    /**
     * Quantidade de tablets e computadores permitido para uso
     * @return int quantidade de dispositivos of Sistema
     */
    public function getDispositivos()
    {
        return $this->dispositivos;
    }

    /**
     * Set Dispositivos value to new on param
     * @param int $dispositivos Set quantidade de dispositivos for Sistema
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
     * @return string identificador da empresa of Sistema
     */
    public function getGUID()
    {
        return $this->guid;
    }

    /**
     * Set GUID value to new on param
     * @param string $guid Set identificador da empresa for Sistema
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
     * @return string data do último backup of Sistema
     */
    public function getUltimoBackup()
    {
        return $this->ultimo_backup;
    }

    /**
     * Set UltimoBackup value to new on param
     * @param string $ultimo_backup Set data do último backup for Sistema
     * @return self Self instance
     */
    public function setUltimoBackup($ultimo_backup)
    {
        $this->ultimo_backup = $ultimo_backup;
        return $this;
    }

    /**
     * Informa qual o fuso horário
     * @return string fusohorario of Sistema
     */
    public function getFusoHorario()
    {
        return $this->fuso_horario;
    }

    /**
     * Set FusoHorario value to new on param
     * @param string $fuso_horario Set fusohorario for Sistema
     * @return self Self instance
     */
    public function setFusoHorario($fuso_horario)
    {
        $this->fuso_horario = $fuso_horario;
        return $this;
    }

    /**
     * Informa qual a versão do banco de dados
     * @return string versão do banco de dados of Sistema
     */
    public function getVersaoDB()
    {
        return $this->versao_db;
    }

    /**
     * Set VersaoDB value to new on param
     * @param string $versao_db Set versão do banco de dados for Sistema
     * @return self Self instance
     */
    public function setVersaoDB($versao_db)
    {
        $this->versao_db = $versao_db;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param boolean $recursive Allow rescursive conversion of fields
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
     * @param mixed $sistema Associated key -> value to assign into this instance
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
     * @param \MZ\Provider\Prestador $requester user that request to view this fields
     * @return array All public field and values into array format
     */
    public function publish($requester)
    {
        $sistema = parent::publish($requester);
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

    private function detectTimezone($uf, $pais)
    {
        $timezones = [
            'BR' => [
                'AC' => 'America/Rio_branco',   'AL' => 'America/Maceio',
                'AP' => 'America/Belem',        'AM' => 'America/Manaus',
                'BA' => 'America/Bahia',        'CE' => 'America/Fortaleza',
                'DF' => 'America/Sao_Paulo',    'ES' => 'America/Sao_Paulo',
                'GO' => 'America/Sao_Paulo',    'MA' => 'America/Fortaleza',
                'MT' => 'America/Cuiaba',       'MS' => 'America/Campo_Grande',
                'MG' => 'America/Sao_Paulo',    'PR' => 'America/Sao_Paulo',
                'PB' => 'America/Fortaleza',    'PA' => 'America/Belem',
                'PE' => 'America/Recife',       'PI' => 'America/Fortaleza',
                'RJ' => 'America/Sao_Paulo',    'RN' => 'America/Fortaleza',
                'RS' => 'America/Sao_Paulo',    'RO' => 'America/Porto_Velho',
                'RR' => 'America/Boa_Vista',    'SC' => 'America/Sao_Paulo',
                'SE' => 'America/Maceio',       'SP' => 'America/Sao_Paulo',
                'TO' => 'America/Araguaia'
            ]
        ];
        $timezone = date_default_timezone_get();
        switch ($pais) {
            case 'BR':
            case 'BRA':
            case 'Brasil':
                if (isset($timezones['BR'][$uf])) {
                    $timezone = $timezones['BR'][$uf];
                }
                break;
        }
        return $timezone;
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
        $this->setServidorID(Filter::number($this->getServidorID()));
        $this->setLicenca(Filter::text($this->getLicenca()));
        $this->setDispositivos(Filter::number($this->getDispositivos()));
        $this->setGUID(Filter::string($this->getGUID()));
        $this->setUltimoBackup(Filter::datetime($this->getUltimoBackup()));
        $this->setFusoHorario(Filter::string($this->getFusoHorario()));
        $this->setVersaoDB(Filter::string($this->getVersaoDB()));
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
     * @return array All field of Sistema in array format
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function validate()
    {
        $errors = [];
        if ($this->getID() != '1') {
            $errors['id'] = 'O id do sistema não foi informado';
        }
        if (is_null($this->getServidorID())) {
            $errors['servidorid'] = _t('sistema.servidor_id_cannot_empty');
        }
        if (is_null($this->getVersaoDB())) {
            $errors['versaodb'] = _t('sistema.versao_db_cannot_empty');
        }
        if (!empty($errors)) {
            throw new ValidationException($errors);
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

    public function initialize($app_path)
    {
        $this->business = new Empresa();
        $this->getSettings()->load($app_path  . '/config');
    }

    /**
     * Insert a new Sistema into the database and fill instance from database
     * @return self Self instance
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function insert()
    {
        throw new \Exception(
            'Nenhuma informação sobre o sistema, contacte seu fornecedor para resolve o problema',
            500
        );
    }

    /**
     * Delete this instance from database using ID
     * @throws \Exception for invalid id
     */
    public function delete()
    {
        throw new \Exception(
            'Não é possível excluir informações do sistema',
            500
        );
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

        $timezone = $this->getFusoHorario() ?: $this->detectTimezone(
            $this->getState()->getUF(),
            $this->getCountry()->getSigla() ?: 'Brasil'
        );
        date_default_timezone_set($timezone);
        $this->entries = parse_ini_string(base64_decode($this->getCountry()->getEntradas()), true, INI_SCANNER_RAW);
        settype($this->entries, 'array');
        return $this;
    }

    /**
     * Servidor do sistema
     * @return \MZ\System\Servidor The object fetched from database
     */
    public function findServidorID()
    {
        return \MZ\System\Servidor::findByID($this->getServidorID());
    }

    /**
     * Filter condition array with allowed fields
     * @param array $condition condition to filter rows
     * @return array allowed condition
     */
    protected function filterCondition($condition)
    {
        $allowed = $this->getAllowedKeys();
        return Filter::keys($condition, $allowed, 's.');
    }

    /**
     * Fetch data from database with a condition
     * @param array $condition condition to filter rows
     * @param array $order order rows
     * @return SelectQuery query object with condition statement
     */
    protected function query($condition = [], $order = [])
    {
        $query = DB::from('Sistema s');
        $condition = $this->filterCondition($condition);
        $query = DB::buildOrderBy($query, $this->filterOrder($order));
        return DB::buildCondition($query, $condition);
    }
}
