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

namespace MZ\Provider;

use MZ\Util\Mask;
use MZ\Util\Filter;
use MZ\Util\Validator;
use MZ\Database\DB;
use MZ\Database\SyncModel;
use MZ\Exception\ValidationException;

/**
 * Função ou atribuição de tarefas à um prestador
 */
class Funcao extends SyncModel
{

    /**
     * Identificador da função
     */
    private $id;
    /**
     * Descreve o nome da função
     */
    private $descricao;
    /**
     * Remuneracao pelas atividades exercidas, não está incluso comissões
     */
    private $remuneracao;

    /**
     * Constructor for a new empty instance of Funcao
     * @param array $funcao All field and values to fill the instance
     */
    public function __construct($funcao = [])
    {
        parent::__construct($funcao);
    }

    /**
     * Identificador da função
     * @return int id of Função
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param int $id Set id for Função
     * @return self Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Descreve o nome da função
     * @return string descrição of Função
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Set Descricao value to new on param
     * @param string $descricao Set descrição for Função
     * @return self Self instance
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * Remuneracao pelas atividades exercidas, não está incluso comissões
     * @return string remuneração of Função
     */
    public function getRemuneracao()
    {
        return $this->remuneracao;
    }

    /**
     * Set Remuneracao value to new on param
     * @param string $remuneracao Set remuneração for Função
     * @return self Self instance
     */
    public function setRemuneracao($remuneracao)
    {
        $this->remuneracao = $remuneracao;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $funcao = parent::toArray($recursive);
        $funcao['id'] = $this->getID();
        $funcao['descricao'] = $this->getDescricao();
        $funcao['remuneracao'] = $this->getRemuneracao();
        return $funcao;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param mixed $funcao Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($funcao = [])
    {
        if ($funcao instanceof self) {
            $funcao = $funcao->toArray();
        } elseif (!is_array($funcao)) {
            $funcao = [];
        }
        parent::fromArray($funcao);
        if (!isset($funcao['id'])) {
            $this->setID(null);
        } else {
            $this->setID($funcao['id']);
        }
        if (!isset($funcao['descricao'])) {
            $this->setDescricao(null);
        } else {
            $this->setDescricao($funcao['descricao']);
        }
        if (!isset($funcao['remuneracao'])) {
            $this->setRemuneracao(null);
        } else {
            $this->setRemuneracao($funcao['remuneracao']);
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
        $funcao = parent::publish($requester);
        return $funcao;
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
        $this->setDescricao(Filter::string($this->getDescricao()));
        $this->setRemuneracao(Filter::money($this->getRemuneracao(), $localized));
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
     * @return array All field of Funcao in array format
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getDescricao())) {
            $errors['descricao'] = _t('funcao.descricao_cannot_empty');
        }
        if (is_null($this->getRemuneracao())) {
            $errors['remuneracao'] = _t('funcao.remuneracao_cannot_empty');
        } elseif ($this->getRemuneracao() < 0) {
            $errors['remuneracao'] = _t('funcao.remuneracao_cannot_negative');
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
        if (contains(['Descricao', 'UNIQUE'], $e->getMessage())) {
            return new ValidationException([
                'descricao' => _t(
                    'funcao.descricao_used',
                    $this->getDescricao()
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Load into this object from database using, Descricao
     * @return self Self filled instance or empty when not found
     */
    public function loadByDescricao()
    {
        return $this->load([
            'descricao' => strval($this->getDescricao()),
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
            $field = 'f.descricao LIKE ?';
            $condition[$field] = '%'.$search.'%';
            $allowed[$field] = true;
            unset($condition['search']);
        }
        return Filter::keys($condition, $allowed, 'f.');
    }

    /**
     * Fetch data from database with a condition
     * @param array $condition condition to filter rows
     * @param array $order order rows
     * @return SelectQuery query object with condition statement
     */
    protected function query($condition = [], $order = [])
    {
        $query = DB::from('Funcoes f');
        $condition = $this->filterCondition($condition);
        $query = DB::buildOrderBy($query, $this->filterOrder($order));
        $query = $query->orderBy('f.descricao ASC');
        $query = $query->orderBy('f.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Find this object on database using, Descricao
     * @param string $descricao descrição to find Função
     * @return self A filled instance or empty when not found
     */
    public static function findByDescricao($descricao)
    {
        $result = new self();
        $result->setDescricao($descricao);
        return $result->loadByDescricao();
    }
}
