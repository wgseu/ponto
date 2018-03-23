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
 * @author  Francimar Alves <mazinsw@gmail.com>
 */
namespace MZ\Invoice;

use MZ\Util\Filter;
use MZ\Util\Validator;

/**
 * Dados do emitente das notas fiscais
 */
class Emitente extends \MZ\Database\Helper
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
     * @return mixed ID of Emitente
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param  mixed $id new value for ID
     * @return Emitente Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Contador responsável pela contabilidade da empresa
     * @return mixed Contador of Emitente
     */
    public function getContadorID()
    {
        return $this->contador_id;
    }

    /**
     * Set ContadorID value to new on param
     * @param  mixed $contador_id new value for ContadorID
     * @return Emitente Self instance
     */
    public function setContadorID($contador_id)
    {
        $this->contador_id = $contador_id;
        return $this;
    }

    /**
     * Regime tributário da empresa
     * @return mixed Regime tributário of Emitente
     */
    public function getRegimeID()
    {
        return $this->regime_id;
    }

    /**
     * Set RegimeID value to new on param
     * @param  mixed $regime_id new value for RegimeID
     * @return Emitente Self instance
     */
    public function setRegimeID($regime_id)
    {
        $this->regime_id = $regime_id;
        return $this;
    }

    /**
     * Ambiente de emissão das notas
     * @return mixed Ambiente of Emitente
     */
    public function getAmbiente()
    {
        return $this->ambiente;
    }

    /**
     * Set Ambiente value to new on param
     * @param  mixed $ambiente new value for Ambiente
     * @return Emitente Self instance
     */
    public function setAmbiente($ambiente)
    {
        $this->ambiente = $ambiente;
        return $this;
    }

    /**
     * Código de segurança do contribuinte
     * @return mixed CSC of Emitente
     */
    public function getCSC()
    {
        return $this->csc;
    }

    /**
     * Set CSC value to new on param
     * @param  mixed $csc new value for CSC
     * @return Emitente Self instance
     */
    public function setCSC($csc)
    {
        $this->csc = $csc;
        return $this;
    }

    /**
     * Token do código de segurança do contribuinte
     * @return mixed Token of Emitente
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set Token value to new on param
     * @param  mixed $token new value for Token
     * @return Emitente Self instance
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * Token da API do IBPT
     * @return mixed Token IBPT of Emitente
     */
    public function getIBPT()
    {
        return $this->ibpt;
    }

    /**
     * Set IBPT value to new on param
     * @param  mixed $ibpt new value for IBPT
     * @return Emitente Self instance
     */
    public function setIBPT($ibpt)
    {
        $this->ibpt = $ibpt;
        return $this;
    }

    /**
     * Nome do arquivo da chave privada
     * @return mixed Chave privada of Emitente
     */
    public function getChavePrivada()
    {
        return $this->chave_privada;
    }

    /**
     * Set ChavePrivada value to new on param
     * @param  mixed $chave_privada new value for ChavePrivada
     * @return Emitente Self instance
     */
    public function setChavePrivada($chave_privada)
    {
        $this->chave_privada = $chave_privada;
        return $this;
    }

    /**
     * Nome do arquivo da chave pública
     * @return mixed Chave pública of Emitente
     */
    public function getChavePublica()
    {
        return $this->chave_publica;
    }

    /**
     * Set ChavePublica value to new on param
     * @param  mixed $chave_publica new value for ChavePublica
     * @return Emitente Self instance
     */
    public function setChavePublica($chave_publica)
    {
        $this->chave_publica = $chave_publica;
        return $this;
    }

    /**
     * Data de expiração do certificado
     * @return mixed Data de expiração of Emitente
     */
    public function getDataExpiracao()
    {
        return $this->data_expiracao;
    }

    /**
     * Set DataExpiracao value to new on param
     * @param  mixed $data_expiracao new value for DataExpiracao
     * @return Emitente Self instance
     */
    public function setDataExpiracao($data_expiracao)
    {
        $this->data_expiracao = $data_expiracao;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param  boolean $recursive Allow rescursive conversion of fields
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
     * @param  mixed $emitente Associated key -> value to assign into this instance
     * @return Emitente Self instance
     */
    public function fromArray($emitente = [])
    {
        if ($emitente instanceof Emitente) {
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
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $emitente = parent::publish();
        return $emitente;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param Emitente $original Original instance without modifications
     */
    public function filter($original)
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
    }

    /**
     * Clean instance resources like images and docs
     * @param  Emitente $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Emitente in array format
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getRegimeID())) {
            $errors['regimeid'] = 'O regime tributário não pode ser vazio';
        }
        if (is_null($this->getAmbiente())) {
            $errors['ambiente'] = 'O ambiente não pode ser vazio';
        }
        if (!Validator::checkInSet($this->getAmbiente(), self::getAmbienteOptions(), true)) {
            $errors['ambiente'] = 'O ambiente é inválido';
        }
        if (is_null($this->getCSC())) {
            $errors['csc'] = 'O csc não pode ser vazio';
        }
        if (is_null($this->getToken())) {
            $errors['token'] = 'O token não pode ser vazio';
        }
        if (is_null($this->getChavePrivada())) {
            $errors['chaveprivada'] = 'A chave privada não pode ser vazia';
        }
        if (is_null($this->getChavePublica())) {
            $errors['chavepublica'] = 'A chave pública não pode ser vazia';
        }
        if (is_null($this->getDataExpiracao())) {
            $errors['dataexpiracao'] = 'A data de expiração não pode ser vazia';
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
        if (stripos($e->getMessage(), 'PRIMARY') !== false) {
            return new \MZ\Exception\ValidationException([
                'id' => sprintf(
                    'O id "%s" já está cadastrado',
                    $this->getID()
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Insert a new Emitente into the database and fill instance from database
     * @return Emitente Self instance
     */
    public function insert()
    {
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = self::getDB()->insertInto('Emitentes')->values($values)->execute();
            $emitente = self::findByID($id);
            $this->fromArray($emitente->toArray());
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Emitente with instance values into database for ID
     * @return Emitente Self instance
     */
    public function update()
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador do emitente não foi informado');
        }
        unset($values['id']);
        try {
            self::getDB()
                ->update('Emitentes')
                ->set($values)
                ->where('id', $this->getID())
                ->execute();
            $emitente = self::findByID($this->getID());
            $this->fromArray($emitente->toArray());
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
            throw new \Exception('O identificador do emitente não foi informado');
        }
        $result = self::getDB()
            ->deleteFrom('Emitentes')
            ->where('id', $this->getID())
            ->execute();
        return $result;
    }

    /**
     * Load one register for it self with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order associative field name -> [-1, 1]
     * @return Emitente Self instance filled or empty
     */
    public function load($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return $this->fromArray($row);
    }

    /**
     * Load into this object from database using, ID
     * @param  string $id id to find Emitente
     * @return Emitente Self filled instance or empty when not found
     */
    public function loadByID($id)
    {
        return $this->load([
            'id' => strval($id),
        ]);
    }

    /**
     * Contador responsável pela contabilidade da empresa
     * @return \MZ\Account\Cliente The object fetched from database
     */
    public function findContadorID()
    {
        if (is_null($this->getContadorID())) {
            return new \MZ\Account\Cliente();
        }
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
     * @param  int $index choose option from index
     * @return mixed A associative key -> translated representative text or text for index
     */
    public static function getAmbienteOptions($index = null)
    {
        $options = [
            self::AMBIENTE_HOMOLOGACAO => 'Homologação',
            self::AMBIENTE_PRODUCAO => 'Produção',
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
        $emitente = new Emitente();
        $allowed = Filter::concatKeys('e.', $emitente->toArray());
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
        return Filter::orderBy($order, $allowed, 'e.');
    }

    /**
     * Filter condition array with allowed fields
     * @param  array $condition condition to filter rows
     * @return array allowed condition
     */
    private static function filterCondition($condition)
    {
        $allowed = self::getAllowedKeys();
        return Filter::keys($condition, $allowed, 'e.');
    }

    /**
     * Fetch data from database with a condition
     * @param  array $condition condition to filter rows
     * @param  array $order order rows
     * @return SelectQuery query object with condition statement
     */
    private static function query($condition = [], $order = [])
    {
        $query = self::getDB()->from('Emitentes e');
        $condition = self::filterCondition($condition);
        $query = self::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('e.id ASC');
        return self::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order order rows
     * @return Emitente A filled Emitente or empty instance
     */
    public static function find($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return new Emitente($row);
    }

    /**
     * Find this object on database using, ID
     * @param  string $id id to find Emitente
     * @return Emitente A filled instance or empty when not found
     */
    public static function findByID($id)
    {
        return self::find([
            'id' => strval($id),
        ]);
    }

    /**
     * Find all Emitente
     * @param  array  $condition Condition to get all Emitente
     * @param  array  $order     Order Emitente
     * @param  int    $limit     Limit data into row count
     * @param  int    $offset    Start offset to get rows
     * @return array             List of all rows instanced as Emitente
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
            $result[] = new Emitente($row);
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
