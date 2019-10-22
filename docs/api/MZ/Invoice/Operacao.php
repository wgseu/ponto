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
 * Código Fiscal de Operações e Prestações (CFOP)
 */
class Operacao extends SyncModel
{

    /**
     * Identificador da operação
     */
    private $id;
    /**
     * Código CFOP sem pontuação
     */
    private $codigo;
    /**
     * Descrição da operação
     */
    private $descricao;
    /**
     * Detalhes da operação (Opcional)
     */
    private $detalhes;

    /**
     * Constructor for a new empty instance of Operacao
     * @param array $operacao All field and values to fill the instance
     */
    public function __construct($operacao = [])
    {
        parent::__construct($operacao);
    }

    /**
     * Identificador da operação
     * @return int id of Operação
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param int $id Set id for Operação
     * @return self Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Código CFOP sem pontuação
     * @return int código of Operação
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set Codigo value to new on param
     * @param int $codigo Set código for Operação
     * @return self Self instance
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
        return $this;
    }

    /**
     * Descrição da operação
     * @return string descrição of Operação
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Set Descricao value to new on param
     * @param string $descricao Set descrição for Operação
     * @return self Self instance
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * Detalhes da operação (Opcional)
     * @return string detalhes of Operação
     */
    public function getDetalhes()
    {
        return $this->detalhes;
    }

    /**
     * Set Detalhes value to new on param
     * @param string $detalhes Set detalhes for Operação
     * @return self Self instance
     */
    public function setDetalhes($detalhes)
    {
        $this->detalhes = $detalhes;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $operacao = parent::toArray($recursive);
        $operacao['id'] = $this->getID();
        $operacao['codigo'] = $this->getCodigo();
        $operacao['descricao'] = $this->getDescricao();
        $operacao['detalhes'] = $this->getDetalhes();
        return $operacao;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param mixed $operacao Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($operacao = [])
    {
        if ($operacao instanceof self) {
            $operacao = $operacao->toArray();
        } elseif (!is_array($operacao)) {
            $operacao = [];
        }
        parent::fromArray($operacao);
        if (!isset($operacao['id'])) {
            $this->setID(null);
        } else {
            $this->setID($operacao['id']);
        }
        if (!isset($operacao['codigo'])) {
            $this->setCodigo(null);
        } else {
            $this->setCodigo($operacao['codigo']);
        }
        if (!isset($operacao['descricao'])) {
            $this->setDescricao(null);
        } else {
            $this->setDescricao($operacao['descricao']);
        }
        if (!array_key_exists('detalhes', $operacao)) {
            $this->setDetalhes(null);
        } else {
            $this->setDetalhes($operacao['detalhes']);
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
        $operacao = parent::publish($requester);
        return $operacao;
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
        $this->setDetalhes(Filter::string($this->getDetalhes()));
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
     * @return array All field of Operacao in array format
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getCodigo())) {
            $errors['codigo'] = _t('operacao.codigo_cannot_empty');
        }
        if (is_null($this->getDescricao())) {
            $errors['descricao'] = _t('operacao.descricao_cannot_empty');
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
                    'operacao.codigo_used',
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
        $query = DB::from('Operacoes o');
        $condition = $this->filterCondition($condition);
        $query = DB::buildOrderBy($query, $this->filterOrder($order));
        $query = $query->orderBy('o.descricao ASC');
        $query = $query->orderBy('o.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Find this object on database using, Codigo
     * @param int $codigo código to find Operação
     * @return self A filled instance or empty when not found
     */
    public static function findByCodigo($codigo)
    {
        $result = new self();
        $result->setCodigo($codigo);
        return $result->loadByCodigo();
    }
}
