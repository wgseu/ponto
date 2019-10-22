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
 * Origem da mercadoria
 */
class Origem extends SyncModel
{

    /**
     * Identificador da origem
     */
    private $id;
    /**
     * Código da origem da mercadoria
     */
    private $codigo;
    /**
     * Descrição da origem da mercadoria
     */
    private $descricao;

    /**
     * Constructor for a new empty instance of Origem
     * @param array $origem All field and values to fill the instance
     */
    public function __construct($origem = [])
    {
        parent::__construct($origem);
    }

    /**
     * Identificador da origem
     * @return int id of Origem
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param int $id Set id for Origem
     * @return self Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Código da origem da mercadoria
     * @return int código of Origem
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set Codigo value to new on param
     * @param int $codigo Set código for Origem
     * @return self Self instance
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
        return $this;
    }

    /**
     * Descrição da origem da mercadoria
     * @return string descrição of Origem
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Set Descricao value to new on param
     * @param string $descricao Set descrição for Origem
     * @return self Self instance
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $origem = parent::toArray($recursive);
        $origem['id'] = $this->getID();
        $origem['codigo'] = $this->getCodigo();
        $origem['descricao'] = $this->getDescricao();
        return $origem;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param mixed $origem Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($origem = [])
    {
        if ($origem instanceof self) {
            $origem = $origem->toArray();
        } elseif (!is_array($origem)) {
            $origem = [];
        }
        parent::fromArray($origem);
        if (!isset($origem['id'])) {
            $this->setID(null);
        } else {
            $this->setID($origem['id']);
        }
        if (!isset($origem['codigo'])) {
            $this->setCodigo(null);
        } else {
            $this->setCodigo($origem['codigo']);
        }
        if (!isset($origem['descricao'])) {
            $this->setDescricao(null);
        } else {
            $this->setDescricao($origem['descricao']);
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
        $origem = parent::publish($requester);
        return $origem;
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
        $this->setCodigo(Filter::number($this->getCodigo()));
        $this->setDescricao(Filter::string($this->getDescricao()));
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
     * @return array All field of Origem in array format
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getCodigo())) {
            $errors['codigo'] = _t('origem.codigo_cannot_empty');
        }
        if (is_null($this->getDescricao())) {
            $errors['descricao'] = _t('origem.descricao_cannot_empty');
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
        if (contains(['Codigo', 'UNIQUE'], $e->getMessage())) {
            return new ValidationException([
                'codigo' => _t(
                    'origem.codigo_used',
                    $this->getCodigo()
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Load into this object from database using, Codigo
     * @return self Self filled instance or empty when not found
     */
    public function loadByCodigo()
    {
        return $this->load([
            'codigo' => intval($this->getCodigo()),
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
            $field = 'o.descricao LIKE ?';
            $condition[$field] = '%'.$search.'%';
            $allowed[$field] = true;
            unset($condition['search']);
        }
        return Filter::keys($condition, $allowed, 'o.');
    }

    /**
     * Fetch data from database with a condition
     * @param array $condition condition to filter rows
     * @param array $order order rows
     * @return SelectQuery query object with condition statement
     */
    protected function query($condition = [], $order = [])
    {
        $query = DB::from('Origens o');
        $condition = $this->filterCondition($condition);
        $query = DB::buildOrderBy($query, $this->filterOrder($order));
        $query = $query->orderBy('o.descricao ASC');
        $query = $query->orderBy('o.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Find this object on database using, Codigo
     * @param int $codigo código to find Origem
     * @return self A filled instance or empty when not found
     */
    public static function findByCodigo($codigo)
    {
        $result = new self();
        $result->setCodigo($codigo);
        return $result->loadByCodigo();
    }
}
