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

namespace MZ\Device;

use MZ\Util\Mask;
use MZ\Util\Filter;
use MZ\Util\Validator;
use MZ\Logger\Log;
use MZ\Database\DB;
use MZ\Database\SyncModel;
use MZ\Exception\ValidationException;

/**
 * Computadores e tablets com opções de acesso
 */
class Dispositivo extends SyncModel
{
    const CONCAT_TOKEN = 'a*9Jh654';

    /**
     * Tipo de dispositivo
     */
    const TIPO_COMPUTADOR = 'Computador';
    const TIPO_TABLET = 'Tablet';
    const TIPO_NAVEGADOR = 'Navegador';

    /**
     * Identificador do dispositivo
     * @var int
     */
    private $id;
    /**
     * Setor em que o dispositivo está instalado/será usado
     * @var int
     */
    private $setor_id;
    /**
     * Finalidade do dispositivo, caixa ou terminal, o caixa é único entre os
     * dispositivos
     * @var int
     */
    private $caixa_id;
    /**
     * Nome do computador ou tablet em rede, único entre os dispositivos
     * @var string
     */
    private $nome;
    /**
     * Tipo de dispositivo
     * @var string
     */
    private $tipo;
    /**
     * Descrição do dispositivo
     * @var string
     */
    private $descricao;
    /**
     * Opções do dispositivo, Ex.: Balança, identificador de chamadas e outros
     * @var string
     */
    private $opcoes;
    /**
     * Serial do tablet para validação, único entre os dispositivos
     * @var string
     */
    private $serial;
    /**
     * Validação do dispositivo
     * @var string
     */
    private $validacao;

    /**
     * Constructor for a new empty instance of Dispositivo
     * @param array $dispositivo All field and values to fill the instance
     */
    public function __construct($dispositivo = [])
    {
        parent::__construct($dispositivo);
    }

    /**
     * Identificador do dispositivo
     * @return int id of Dispositivo
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Identificador do dispositivo
     * @param int $id Set id for Dispositivo
     * @return self Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Setor em que o dispositivo está instalado/será usado
     * @return int setor of Dispositivo
     */
    public function getSetorID()
    {
        return $this->setor_id;
    }

    /**
     * Setor em que o dispositivo está instalado/será usado
     * @param int $setor_id Set setor for Dispositivo
     * @return self Self instance
     */
    public function setSetorID($setor_id)
    {
        $this->setor_id = $setor_id;
        return $this;
    }

    /**
     * Finalidade do dispositivo, caixa ou terminal, o caixa é único entre os
     * dispositivos
     * @return int caixa of Dispositivo
     */
    public function getCaixaID()
    {
        return $this->caixa_id;
    }

    /**
     * Finalidade do dispositivo, caixa ou terminal, o caixa é único entre os
     * dispositivos
     * @param int $caixa_id Set caixa for Dispositivo
     * @return self Self instance
     */
    public function setCaixaID($caixa_id)
    {
        $this->caixa_id = $caixa_id;
        return $this;
    }

    /**
     * Nome do computador ou tablet em rede, único entre os dispositivos
     * @return string nome of Dispositivo
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Nome do computador ou tablet em rede, único entre os dispositivos
     * @param string $nome Set nome for Dispositivo
     * @return self Self instance
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * Tipo de dispositivo
     * @return string tipo of Dispositivo
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Tipo de dispositivo
     * @param string $tipo Set tipo for Dispositivo
     * @return self Self instance
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
        return $this;
    }

    /**
     * Descrição do dispositivo
     * @return string descrição of Dispositivo
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Descrição do dispositivo
     * @param string $descricao Set descrição for Dispositivo
     * @return self Self instance
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * Opções do dispositivo, Ex.: Balança, identificador de chamadas e outros
     * @return string opções of Dispositivo
     */
    public function getOpcoes()
    {
        return $this->opcoes;
    }

    /**
     * Opções do dispositivo, Ex.: Balança, identificador de chamadas e outros
     * @param string $opcoes Set opções for Dispositivo
     * @return self Self instance
     */
    public function setOpcoes($opcoes)
    {
        $this->opcoes = $opcoes;
        return $this;
    }

    /**
     * Serial do tablet para validação, único entre os dispositivos
     * @return string serial of Dispositivo
     */
    public function getSerial()
    {
        return $this->serial;
    }

    /**
     * Serial do tablet para validação, único entre os dispositivos
     * @param string $serial Set serial for Dispositivo
     * @return self Self instance
     */
    public function setSerial($serial)
    {
        $this->serial = $serial;
        return $this;
    }

    /**
     * Validação do dispositivo
     * @return string validação of Dispositivo
     */
    public function getValidacao()
    {
        return $this->validacao;
    }

    /**
     * Validação do dispositivo
     * @param string $validacao Set validação for Dispositivo
     * @return self Self instance
     */
    public function setValidacao($validacao)
    {
        $this->validacao = $validacao;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $dispositivo = parent::toArray($recursive);
        $dispositivo['id'] = $this->getID();
        $dispositivo['setorid'] = $this->getSetorID();
        $dispositivo['caixaid'] = $this->getCaixaID();
        $dispositivo['nome'] = $this->getNome();
        $dispositivo['tipo'] = $this->getTipo();
        $dispositivo['descricao'] = $this->getDescricao();
        $dispositivo['opcoes'] = $this->getOpcoes();
        $dispositivo['serial'] = $this->getSerial();
        $dispositivo['validacao'] = $this->getValidacao();
        return $dispositivo;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param mixed $dispositivo Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($dispositivo = [])
    {
        if ($dispositivo instanceof self) {
            $dispositivo = $dispositivo->toArray();
        } elseif (!is_array($dispositivo)) {
            $dispositivo = [];
        }
        parent::fromArray($dispositivo);
        if (!isset($dispositivo['id'])) {
            $this->setID(null);
        } else {
            $this->setID($dispositivo['id']);
        }
        if (!isset($dispositivo['setorid'])) {
            $this->setSetorID(null);
        } else {
            $this->setSetorID($dispositivo['setorid']);
        }
        if (!array_key_exists('caixaid', $dispositivo)) {
            $this->setCaixaID(null);
        } else {
            $this->setCaixaID($dispositivo['caixaid']);
        }
        if (!isset($dispositivo['nome'])) {
            $this->setNome(null);
        } else {
            $this->setNome($dispositivo['nome']);
        }
        if (!isset($dispositivo['tipo'])) {
            $this->setTipo(null);
        } else {
            $this->setTipo($dispositivo['tipo']);
        }
        if (!array_key_exists('descricao', $dispositivo)) {
            $this->setDescricao(null);
        } else {
            $this->setDescricao($dispositivo['descricao']);
        }
        if (!array_key_exists('opcoes', $dispositivo)) {
            $this->setOpcoes(null);
        } else {
            $this->setOpcoes($dispositivo['opcoes']);
        }
        if (!isset($dispositivo['serial'])) {
            $this->setSerial(null);
        } else {
            $this->setSerial($dispositivo['serial']);
        }
        if (!array_key_exists('validacao', $dispositivo)) {
            $this->setValidacao(null);
        } else {
            $this->setValidacao($dispositivo['validacao']);
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
        $dispositivo = parent::publish($requester);
        return $dispositivo;
    }

    /**
     * Check if this device is validated
     * @return boolean true if validated, false otherwise
     */
    public function checkValidacao()
    {
        return $this->getValidacao() == $this->makeValidacao();
    }

    public function makeValidacao()
    {
        return \sha1(self::CONCAT_TOKEN . $this->getSerial());
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
        $this->setSetorID(Filter::number($this->getSetorID()));
        $this->setCaixaID(Filter::number($this->getCaixaID()));
        $this->setNome(Filter::string($this->getNome()));
        $this->setTipo(Filter::string($this->getTipo()));
        $this->setDescricao(Filter::string($this->getDescricao()));
        $this->setOpcoes(Filter::text($this->getOpcoes()));
        $this->setSerial(Filter::string($this->getSerial()));
        $this->setValidacao(Filter::string($this->getValidacao()));
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
     * @return array All field of Dispositivo in array format
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function validate()
    {
        $errors = [];
        if (!app()->getSystem()->exists()) {
            $errors['sistemaid'] = 'Não há dados na tabela do sistema';
        }
        if (is_null($this->getSetorID())) {
            $errors['setorid'] = _t('dispositivo.setor_id_cannot_empty');
        }
        if (is_null($this->getNome())) {
            $errors['nome'] = _t('dispositivo.nome_cannot_empty');
        }
        if (!Validator::checkInSet($this->getTipo(), self::getTipoOptions())) {
            $errors['tipo'] = _t('dispositivo.tipo_invalid');
        }
        if (is_null($this->getSerial())) {
            $errors['serial'] = _t('dispositivo.serial_cannot_empty');
        }
        $device_count = self::count();
        if ($device_count > app()->getSystem()->getDispositivos()) {
            $errors['limite'] = 'Limite de dispositivos excedido, remova os dispositivos excedentes para continuar';
        }
        if (!$this->exists() &&
            $device_count >= app()->getSystem()->getDispositivos()
        ) {
            $errors['limite'] = 'Limite de dispositivos esgotado, verifique sua licença';
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
        if (contains(['CaixaID', 'UNIQUE'], $e->getMessage())) {
            return new ValidationException([
                'caixaid' => _t(
                    'dispositivo.caixa_id_used',
                    $this->getCaixaID()
                ),
            ]);
        }
        if (contains(['Serial', 'UNIQUE'], $e->getMessage())) {
            return new ValidationException([
                'serial' => _t(
                    'dispositivo.serial_used',
                    $this->getSerial()
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * @param \MZ\Provider\Prestador $prestador
     */
    public function register($prestador)
    {
        $setor = \MZ\Environment\Setor::findDefault();
        $this->setSetorID($setor->getID());
        $this->setTipo(self::TIPO_TABLET);
        $this->setDescricao('Tablet ' . $this->getNome());
        $dispositivo = self::findBySerial($this->getSerial());
        // permite a atualização das informações para o novo dispositivo
        $this->setID($dispositivo->getID());
        try {
            $this->validate();
        } catch (ValidationException $e) {
            $errors = $e->getErrors();
            if ($prestador->isOwner() && count($errors) == 1 && isset($errors['limite'])) {
                // tenta reusar dispositivos que não foram validados
                $dispositivo = self::find(['validacao' => null]);
                if (!$dispositivo->exists()) {
                    // tenta reusar dispositivos cadastrados a muito tempo
                    $dispositivo = self::find([], ['id' => 1]);
                }
                $this->setID($dispositivo->getID());
            } else {
                throw $e;
            }
        }
        if ($this->exists() &&
            $this->getSerial() == $dispositivo->getSerial() &&
            $this->getNome() == $dispositivo->getNome() &&
            $dispositivo->getTipo() == self::TIPO_TABLET
        ) {
            $this->fromArray($dispositivo->toArray());
            if ($prestador->isOwner() && !$this->checkValidacao()) {
                $this->authorize();
            }
            return $this;
        }
        $exists = $this->exists();
        if ($prestador->isOwner() && !$this->checkValidacao()) {
            $this->setValidacao($this->makeValidacao());
        }
        $this->save();
        return $this;
    }

    /**
     * Authorize this device to access this app
     * @return self self instance
     */
    public function authorize()
    {
        $this->setValidacao($this->makeValidacao());
        return $this->update();
    }

    /**
     * Load into this object from database using, CaixaID
     * @return self Self filled instance or empty when not found
     */
    public function loadByCaixaID()
    {
        return $this->load([
            'caixaid' => intval($this->getCaixaID()),
        ]);
    }

    /**
     * Load into this object from database using, Serial
     * @return self Self filled instance or empty when not found
     */
    public function loadBySerial()
    {
        return $this->load([
            'serial' => strval($this->getSerial()),
        ]);
    }

    /**
     * Setor em que o dispositivo está instalado/será usado
     * @return \MZ\Environment\Setor The object fetched from database
     */
    public function findSetorID()
    {
        return \MZ\Environment\Setor::findByID($this->getSetorID());
    }

    /**
     * Finalidade do dispositivo, caixa ou terminal, o caixa é único entre os
     * dispositivos
     * @return \MZ\Session\Caixa The object fetched from database
     */
    public function findCaixaID()
    {
        return \MZ\Session\Caixa::findByID($this->getCaixaID());
    }

    /**
     * Gets textual and translated Tipo for Dispositivo
     * @param int $index choose option from index
     * @return string[] A associative key -> translated representative text or text for index
     */
    public static function getTipoOptions($index = null)
    {
        $options = [
            self::TIPO_COMPUTADOR => _t('dispositivo.tipo_computador'),
            self::TIPO_TABLET => _t('dispositivo.tipo_tablet'),
            self::TIPO_NAVEGADOR => _t('dispositivo.tipo_navegador'),
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
            $field = 'd.nome LIKE ?';
            $condition[$field] = '%'.$search.'%';
            $allowed[$field] = true;
            unset($condition['search']);
        }
        return Filter::keys($condition, $allowed, 'd.');
    }

    /**
     * Fetch data from database with a condition
     * @param array $condition condition to filter rows
     * @param array $order order rows
     * @return SelectQuery query object with condition statement
     */
    protected function query($condition = [], $order = [])
    {
        $query = DB::from('Dispositivos d');
        $condition = $this->filterCondition($condition);
        $query = DB::buildOrderBy($query, $this->filterOrder($order));
        $query = $query->orderBy('d.nome ASC');
        $query = $query->orderBy('d.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Find this object on database using, CaixaID
     * @param int $caixa_id caixa to find Dispositivo
     * @return self A filled instance or empty when not found
     */
    public static function findByCaixaID($caixa_id)
    {
        $result = new self();
        $result->setCaixaID($caixa_id);
        return $result->loadByCaixaID();
    }

    /**
     * Find this object on database using, Serial
     * @param string $serial serial to find Dispositivo
     * @return self A filled instance or empty when not found
     */
    public static function findBySerial($serial)
    {
        $result = new self();
        $result->setSerial($serial);
        return $result->loadBySerial();
    }
}
