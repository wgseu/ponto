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
 * Informação tributária dos produtos
 */
class Tributacao extends SyncModel
{

    /**
     * Identificador da tributação
     */
    private $id;
    /**
     * Código NCM (Nomenclatura Comum do Mercosul) do produto
     */
    private $ncm;
    /**
     * Código CEST do produto (Opcional)
     */
    private $cest;
    /**
     * Origem do produto
     */
    private $origem_id;
    /**
     * CFOP do produto
     */
    private $operacao_id;
    /**
     * Imposto do produto
     */
    private $imposto_id;

    /**
     * Constructor for a new empty instance of Tributacao
     * @param array $tributacao All field and values to fill the instance
     */
    public function __construct($tributacao = [])
    {
        parent::__construct($tributacao);
    }

    /**
     * Identificador da tributação
     * @return int id of Tributação
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param int $id Set id for Tributação
     * @return self Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Código NCM (Nomenclatura Comum do Mercosul) do produto
     * @return string ncm of Tributação
     */
    public function getNCM()
    {
        return $this->ncm;
    }

    /**
     * Set NCM value to new on param
     * @param string $ncm Set ncm for Tributação
     * @return self Self instance
     */
    public function setNCM($ncm)
    {
        $this->ncm = $ncm;
        return $this;
    }

    /**
     * Código CEST do produto (Opcional)
     * @return string cest of Tributação
     */
    public function getCEST()
    {
        return $this->cest;
    }

    /**
     * Set CEST value to new on param
     * @param string $cest Set cest for Tributação
     * @return self Self instance
     */
    public function setCEST($cest)
    {
        $this->cest = $cest;
        return $this;
    }

    /**
     * Origem do produto
     * @return int origem of Tributação
     */
    public function getOrigemID()
    {
        return $this->origem_id;
    }

    /**
     * Set OrigemID value to new on param
     * @param int $origem_id Set origem for Tributação
     * @return self Self instance
     */
    public function setOrigemID($origem_id)
    {
        $this->origem_id = $origem_id;
        return $this;
    }

    /**
     * CFOP do produto
     * @return int cfop of Tributação
     */
    public function getOperacaoID()
    {
        return $this->operacao_id;
    }

    /**
     * Set OperacaoID value to new on param
     * @param int $operacao_id Set cfop for Tributação
     * @return self Self instance
     */
    public function setOperacaoID($operacao_id)
    {
        $this->operacao_id = $operacao_id;
        return $this;
    }

    /**
     * Imposto do produto
     * @return int imposto of Tributação
     */
    public function getImpostoID()
    {
        return $this->imposto_id;
    }

    /**
     * Set ImpostoID value to new on param
     * @param int $imposto_id Set imposto for Tributação
     * @return self Self instance
     */
    public function setImpostoID($imposto_id)
    {
        $this->imposto_id = $imposto_id;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $tributacao = parent::toArray($recursive);
        $tributacao['id'] = $this->getID();
        $tributacao['ncm'] = $this->getNCM();
        $tributacao['cest'] = $this->getCEST();
        $tributacao['origemid'] = $this->getOrigemID();
        $tributacao['operacaoid'] = $this->getOperacaoID();
        $tributacao['impostoid'] = $this->getImpostoID();
        return $tributacao;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param mixed $tributacao Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($tributacao = [])
    {
        if ($tributacao instanceof self) {
            $tributacao = $tributacao->toArray();
        } elseif (!is_array($tributacao)) {
            $tributacao = [];
        }
        parent::fromArray($tributacao);
        if (!isset($tributacao['id'])) {
            $this->setID(null);
        } else {
            $this->setID($tributacao['id']);
        }
        if (!isset($tributacao['ncm'])) {
            $this->setNCM(null);
        } else {
            $this->setNCM($tributacao['ncm']);
        }
        if (!array_key_exists('cest', $tributacao)) {
            $this->setCEST(null);
        } else {
            $this->setCEST($tributacao['cest']);
        }
        if (!isset($tributacao['origemid'])) {
            $this->setOrigemID(null);
        } else {
            $this->setOrigemID($tributacao['origemid']);
        }
        if (!isset($tributacao['operacaoid'])) {
            $this->setOperacaoID(null);
        } else {
            $this->setOperacaoID($tributacao['operacaoid']);
        }
        if (!isset($tributacao['impostoid'])) {
            $this->setImpostoID(null);
        } else {
            $this->setImpostoID($tributacao['impostoid']);
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
        $tributacao = parent::publish($requester);
        return $tributacao;
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
        $this->setNCM(Filter::string($this->getNCM()));
        $this->setCEST(Filter::string($this->getCEST()));
        $this->setOrigemID(Filter::number($this->getOrigemID()));
        $this->setOperacaoID(Filter::number($this->getOperacaoID()));
        $this->setImpostoID(Filter::number($this->getImpostoID()));
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
     * @return array All field of Tributacao in array format
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getNCM())) {
            $errors['ncm'] = _t('tributacao.ncm_cannot_empty');
        }
        if (is_null($this->getOrigemID())) {
            $errors['origemid'] = _t('tributacao.origem_id_cannot_empty');
        }
        if (is_null($this->getOperacaoID())) {
            $errors['operacaoid'] = _t('tributacao.operacao_id_cannot_empty');
        }
        if (is_null($this->getImpostoID())) {
            $errors['impostoid'] = _t('tributacao.imposto_id_cannot_empty');
        }
        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
        return $this->toArray();
    }

    /**
     * Origem do produto
     * @return \MZ\Invoice\Origem The object fetched from database
     */
    public function findOrigemID()
    {
        return \MZ\Invoice\Origem::findByID($this->getOrigemID());
    }

    /**
     * CFOP do produto
     * @return \MZ\Invoice\Operacao The object fetched from database
     */
    public function findOperacaoID()
    {
        return \MZ\Invoice\Operacao::findByID($this->getOperacaoID());
    }

    /**
     * Imposto do produto
     * @return \MZ\Invoice\Imposto The object fetched from database
     */
    public function findImpostoID()
    {
        return \MZ\Invoice\Imposto::findByID($this->getImpostoID());
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
            $field = 't.ncm LIKE ?';
            $condition[$field] = '%'.$search.'%';
            $allowed[$field] = true;
            unset($condition['search']);
        }
        return Filter::keys($condition, $allowed, 't.');
    }

    /**
     * Fetch data from database with a condition
     * @param array $condition condition to filter rows
     * @param array $order order rows
     * @return SelectQuery query object with condition statement
     */
    protected function query($condition = [], $order = [])
    {
        $query = DB::from('Tributacoes t');
        $condition = $this->filterCondition($condition);
        $query = DB::buildOrderBy($query, $this->filterOrder($order));
        $query = $query->orderBy('t.ncm ASC');
        $query = $query->orderBy('t.id ASC');
        return DB::buildCondition($query, $condition);
    }
}
