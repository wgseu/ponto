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

/**
 * Lista de servidores que fazem sincronizações
 */
class Servidor extends Model
{

    /**
     * Identificador do servidor no banco de dados
     */
    private $id;
    /**
     * Identificador único do servidor, usando para identificação na
     * sincronização
     */
    private $guid;
    /**
     * Informa até onde foi sincronzado os dados desse servidor, sempre nulo no
     * proprio servidor
     */
    private $sincronizado_ate;
    /**
     * Data da última sincronização com esse servidor
     */
    private $ultima_sincronizacao;

    /**
     * Constructor for a new empty instance of Servidor
     * @param array $servidor All field and values to fill the instance
     */
    public function __construct($servidor = [])
    {
        parent::__construct($servidor);
    }

    /**
     * Identificador do servidor no banco de dados
     * @return int id of Servidor
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param int $id Set id for Servidor
     * @return self Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Identificador único do servidor, usando para identificação na
     * sincronização
     * @return string identificador único of Servidor
     */
    public function getGUID()
    {
        return $this->guid;
    }

    /**
     * Set GUID value to new on param
     * @param string $guid Set identificador único for Servidor
     * @return self Self instance
     */
    public function setGUID($guid)
    {
        $this->guid = $guid;
        return $this;
    }

    /**
     * Informa até onde foi sincronzado os dados desse servidor, sempre nulo no
     * proprio servidor
     * @return int sincronizado até of Servidor
     */
    public function getSincronizadoAte()
    {
        return $this->sincronizado_ate;
    }

    /**
     * Set SincronizadoAte value to new on param
     * @param int $sincronizado_ate Set sincronizado até for Servidor
     * @return self Self instance
     */
    public function setSincronizadoAte($sincronizado_ate)
    {
        $this->sincronizado_ate = $sincronizado_ate;
        return $this;
    }

    /**
     * Data da última sincronização com esse servidor
     * @return string data da última sincronização of Servidor
     */
    public function getUltimaSincronizacao()
    {
        return $this->ultima_sincronizacao;
    }

    /**
     * Set UltimaSincronizacao value to new on param
     * @param string $ultima_sincronizacao Set data da última sincronização for Servidor
     * @return self Self instance
     */
    public function setUltimaSincronizacao($ultima_sincronizacao)
    {
        $this->ultima_sincronizacao = $ultima_sincronizacao;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $servidor = parent::toArray($recursive);
        $servidor['id'] = $this->getID();
        $servidor['guid'] = $this->getGUID();
        $servidor['sincronizadoate'] = $this->getSincronizadoAte();
        $servidor['ultimasincronizacao'] = $this->getUltimaSincronizacao();
        return $servidor;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param mixed $servidor Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($servidor = [])
    {
        if ($servidor instanceof self) {
            $servidor = $servidor->toArray();
        } elseif (!is_array($servidor)) {
            $servidor = [];
        }
        parent::fromArray($servidor);
        if (!isset($servidor['id'])) {
            $this->setID(null);
        } else {
            $this->setID($servidor['id']);
        }
        if (!isset($servidor['guid'])) {
            $this->setGUID(null);
        } else {
            $this->setGUID($servidor['guid']);
        }
        if (!array_key_exists('sincronizadoate', $servidor)) {
            $this->setSincronizadoAte(null);
        } else {
            $this->setSincronizadoAte($servidor['sincronizadoate']);
        }
        if (!array_key_exists('ultimasincronizacao', $servidor)) {
            $this->setUltimaSincronizacao(null);
        } else {
            $this->setUltimaSincronizacao($servidor['ultimasincronizacao']);
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
        $servidor = parent::publish($requester);
        return $servidor;
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
        $this->setGUID(Filter::string($this->getGUID()));
        $this->setSincronizadoAte(Filter::number($this->getSincronizadoAte()));
        $this->setUltimaSincronizacao(Filter::datetime($this->getUltimaSincronizacao()));
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
     * @return array All field of Servidor in array format
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getGUID())) {
            $errors['guid'] = _t('servidor.guid_cannot_empty');
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
        if (contains(['GUID', 'UNIQUE'], $e->getMessage())) {
            return new ValidationException([
                'guid' => _t(
                    'servidor.guid_used',
                    $this->getGUID()
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Load into this object from database using, GUID
     * @return self Self filled instance or empty when not found
     */
    public function loadByGUID()
    {
        return $this->load([
            'guid' => strval($this->getGUID()),
        ]);
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
            $field = 's.guid LIKE ?';
            $condition[$field] = '%'.$search.'%';
            $allowed[$field] = true;
            unset($condition['search']);
        }
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
        $query = DB::from('Servidores s');
        $condition = $this->filterCondition($condition);
        $query = DB::buildOrderBy($query, $this->filterOrder($order));
        $query = $query->orderBy('s.guid ASC');
        $query = $query->orderBy('s.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Find this object on database using, GUID
     * @param string $guid identificador único to find Servidor
     * @return self A filled instance or empty when not found
     */
    public static function findByGUID($guid)
    {
        $result = new self();
        $result->setGUID($guid);
        return $result->loadByGUID();
    }
}
