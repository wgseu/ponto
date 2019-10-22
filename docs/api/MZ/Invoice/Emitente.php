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

namespace MZ\Invoice;

use MZ\Util\Mask;
use MZ\Util\Filter;
use MZ\Util\Validator;
use MZ\Database\DB;
use MZ\Database\SyncModel;
use MZ\Exception\ValidationException;

/**
 * Dados do emitente das notas fiscais
 */
class Emitente extends SyncModel
{

    /**
     * Ambiente de emissão das notas
     */
    const AMBIENTE_HOMOLOGACAO = 'Homologacao';
    const AMBIENTE_PRODUCAO = 'Producao';

    /**
     * Identificador do emitente, sempre 1
     */
    private $id;
    /**
     * Contador responsável pela contabilidade da empresa
     */
    private $contador_id;
    /**
     * Regime tributário da empresa
     */
    private $regime_id;
    /**
     * Ambiente de emissão das notas
     */
    private $ambiente;
    /**
     * Código de segurança do contribuinte
     */
    private $csc;
    /**
     * Token do código de segurança do contribuinte
     */
    private $token;
    /**
     * Token da API do IBPT
     */
    private $ibpt;
    /**
     * Nome do arquivo da chave privada
     */
    private $chave_privada;
    /**
     * Nome do arquivo da chave pública
     */
    private $chave_publica;
    /**
     * Data de expiração do certificado
     */
    private $data_expiracao;

    /**
     * Constructor for a new empty instance of Emitente
     * @param array $emitente All field and values to fill the instance
     */
    public function __construct($emitente = [])
    {
        parent::__construct($emitente);
    }

    /**
     * Identificador do emitente, sempre 1
     * @return string id of Emitente
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param string $id Set id for Emitente
     * @return self Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Contador responsável pela contabilidade da empresa
     * @return int contador of Emitente
     */
    public function getContadorID()
    {
        return $this->contador_id;
    }

    /**
     * Set ContadorID value to new on param
     * @param int $contador_id Set contador for Emitente
     * @return self Self instance
     */
    public function setContadorID($contador_id)
    {
        $this->contador_id = $contador_id;
        return $this;
    }

    /**
     * Regime tributário da empresa
     * @return int regime tributário of Emitente
     */
    public function getRegimeID()
    {
        return $this->regime_id;
    }

    /**
     * Set RegimeID value to new on param
     * @param int $regime_id Set regime tributário for Emitente
     * @return self Self instance
     */
    public function setRegimeID($regime_id)
    {
        $this->regime_id = $regime_id;
        return $this;
    }

    /**
     * Ambiente de emissão das notas
     * @return string ambiente of Emitente
     */
    public function getAmbiente()
    {
        return $this->ambiente;
    }

    /**
     * Set Ambiente value to new on param
     * @param string $ambiente Set ambiente for Emitente
     * @return self Self instance
     */
    public function setAmbiente($ambiente)
    {
        $this->ambiente = $ambiente;
        return $this;
    }

    /**
     * Código de segurança do contribuinte
     * @return string csc of Emitente
     */
    public function getCSC()
    {
        return $this->csc;
    }

    /**
     * Set CSC value to new on param
     * @param string $csc Set csc for Emitente
     * @return self Self instance
     */
    public function setCSC($csc)
    {
        $this->csc = $csc;
        return $this;
    }

    /**
     * Token do código de segurança do contribuinte
     * @return string token of Emitente
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set Token value to new on param
     * @param string $token Set token for Emitente
     * @return self Self instance
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * Token da API do IBPT
     * @return string token ibpt of Emitente
     */
    public function getIBPT()
    {
        return $this->ibpt;
    }

    /**
     * Set IBPT value to new on param
     * @param string $ibpt Set token ibpt for Emitente
     * @return self Self instance
     */
    public function setIBPT($ibpt)
    {
        $this->ibpt = $ibpt;
        return $this;
    }

    /**
     * Nome do arquivo da chave privada
     * @return string chave privada of Emitente
     */
    public function getChavePrivada()
    {
        return $this->chave_privada;
    }

    /**
     * Set ChavePrivada value to new on param
     * @param string $chave_privada Set chave privada for Emitente
     * @return self Self instance
     */
    public function setChavePrivada($chave_privada)
    {
        $this->chave_privada = $chave_privada;
        return $this;
    }

    /**
     * Nome do arquivo da chave pública
     * @return string chave pública of Emitente
     */
    public function getChavePublica()
    {
        return $this->chave_publica;
    }

    /**
     * Set ChavePublica value to new on param
     * @param string $chave_publica Set chave pública for Emitente
     * @return self Self instance
     */
    public function setChavePublica($chave_publica)
    {
        $this->chave_publica = $chave_publica;
        return $this;
    }

    /**
     * Data de expiração do certificado
     * @return string data de expiração of Emitente
     */
    public function getDataExpiracao()
    {
        return $this->data_expiracao;
    }

    /**
     * Set DataExpiracao value to new on param
     * @param string $data_expiracao Set data de expiração for Emitente
     * @return self Self instance
     */
    public function setDataExpiracao($data_expiracao)
    {
        $this->data_expiracao = $data_expiracao;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $emitente = parent::toArray($recursive);
        $emitente['id'] = $this->getID();
        $emitente['contadorid'] = $this->getContadorID();
        $emitente['regimeid'] = $this->getRegimeID();
        $emitente['ambiente'] = $this->getAmbiente();
        $emitente['csc'] = $this->getCSC();
        $emitente['token'] = $this->getToken();
        $emitente['ibpt'] = $this->getIBPT();
        $emitente['chaveprivada'] = $this->getChavePrivada();
        $emitente['chavepublica'] = $this->getChavePublica();
        $emitente['dataexpiracao'] = $this->getDataExpiracao();
        return $emitente;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param mixed $emitente Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($emitente = [])
    {
        if ($emitente instanceof self) {
            $emitente = $emitente->toArray();
        } elseif (!is_array($emitente)) {
            $emitente = [];
        }
        parent::fromArray($emitente);
        if (!isset($emitente['id'])) {
            $this->setID(null);
        } else {
            $this->setID($emitente['id']);
        }
        if (!array_key_exists('contadorid', $emitente)) {
            $this->setContadorID(null);
        } else {
            $this->setContadorID($emitente['contadorid']);
        }
        if (!isset($emitente['regimeid'])) {
            $this->setRegimeID(null);
        } else {
            $this->setRegimeID($emitente['regimeid']);
        }
        if (!isset($emitente['ambiente'])) {
            $this->setAmbiente(null);
        } else {
            $this->setAmbiente($emitente['ambiente']);
        }
        if (!isset($emitente['csc'])) {
            $this->setCSC(null);
        } else {
            $this->setCSC($emitente['csc']);
        }
        if (!isset($emitente['token'])) {
            $this->setToken(null);
        } else {
            $this->setToken($emitente['token']);
        }
        if (!array_key_exists('ibpt', $emitente)) {
            $this->setIBPT(null);
        } else {
            $this->setIBPT($emitente['ibpt']);
        }
        if (!isset($emitente['chaveprivada'])) {
            $this->setChavePrivada(null);
        } else {
            $this->setChavePrivada($emitente['chaveprivada']);
        }
        if (!isset($emitente['chavepublica'])) {
            $this->setChavePublica(null);
        } else {
            $this->setChavePublica($emitente['chavepublica']);
        }
        if (!isset($emitente['dataexpiracao'])) {
            $this->setDataExpiracao(null);
        } else {
            $this->setDataExpiracao($emitente['dataexpiracao']);
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
        $emitente = parent::publish($requester);
        return $emitente;
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
        $this->setContadorID(Filter::number($this->getContadorID()));
        $this->setRegimeID(Filter::number($this->getRegimeID()));
        $this->setCSC(Filter::string($this->getCSC()));
        $this->setToken(Filter::string($this->getToken()));
        $this->setIBPT(Filter::string($this->getIBPT()));
        $this->setChavePrivada(Filter::string($this->getChavePrivada()));
        $this->setChavePublica(Filter::string($this->getChavePublica()));
        $this->setDataExpiracao(Filter::datetime($this->getDataExpiracao()));
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
     * @return array All field of Emitente in array format
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getRegimeID())) {
            $errors['regimeid'] = _t('emitente.regime_id_cannot_empty');
        }
        if (!Validator::checkInSet($this->getAmbiente(), self::getAmbienteOptions())) {
            $errors['ambiente'] = _t('emitente.ambiente_invalid');
        }
        if (is_null($this->getCSC())) {
            $errors['csc'] = _t('emitente.csc_cannot_empty');
        }
        if (is_null($this->getToken())) {
            $errors['token'] = _t('emitente.token_cannot_empty');
        }
        if (is_null($this->getChavePrivada())) {
            $errors['chaveprivada'] = _t('emitente.chave_privada_cannot_empty');
        }
        if (is_null($this->getChavePublica())) {
            $errors['chavepublica'] = _t('emitente.chave_publica_cannot_empty');
        }
        if (is_null($this->getDataExpiracao())) {
            $errors['dataexpiracao'] = _t('emitente.data_expiracao_cannot_empty');
        }
        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
        return $this->toArray();
    }

    /**
     * Contador responsável pela contabilidade da empresa
     * @return \MZ\Account\Cliente The object fetched from database
     */
    public function findContadorID()
    {
        return \MZ\Account\Cliente::findByID($this->getContadorID());
    }

    /**
     * Regime tributário da empresa
     * @return \MZ\Invoice\Regime The object fetched from database
     */
    public function findRegimeID()
    {
        return \MZ\Invoice\Regime::findByID($this->getRegimeID());
    }

    /**
     * Gets textual and translated Ambiente for Emitente
     * @param int $index choose option from index
     * @return string[] A associative key -> translated representative text or text for index
     */
    public static function getAmbienteOptions($index = null)
    {
        $options = [
            self::AMBIENTE_HOMOLOGACAO => _t('emitente.ambiente_homologacao'),
            self::AMBIENTE_PRODUCAO => _t('emitente.ambiente_producao'),
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
        return Filter::keys($condition, $allowed, 'e.');
    }

    /**
     * Fetch data from database with a condition
     * @param array $condition condition to filter rows
     * @param array $order order rows
     * @return SelectQuery query object with condition statement
     */
    protected function query($condition = [], $order = [])
    {
        $query = DB::from('Emitentes e');
        $condition = $this->filterCondition($condition);
        $query = DB::buildOrderBy($query, $this->filterOrder($order));
        $query = $query->orderBy('e.id ASC');
        return DB::buildCondition($query, $condition);
    }
}
