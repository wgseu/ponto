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

namespace MZ\Stock;

use MZ\Util\Mask;
use MZ\Util\Filter;
use MZ\Util\Validator;
use MZ\Database\DB;
use MZ\Database\SyncModel;
use MZ\Exception\ValidationException;
use MZ\Account\Cliente;

/**
 * Fornecedores de produtos
 */
class Fornecedor extends SyncModel
{

    /**
     * Identificador do fornecedor
     */
    private $id;
    /**
     * Empresa do fornecedor
     */
    private $empresa_id;
    /**
     * Prazo em dias para pagamento do fornecedor
     */
    private $prazo_pagamento;
    /**
     * Data de cadastro do fornecedor
     */
    private $data_cadastro;

    /**
     * Constructor for a new empty instance of Fornecedor
     * @param array $fornecedor All field and values to fill the instance
     */
    public function __construct($fornecedor = [])
    {
        parent::__construct($fornecedor);
    }

    /**
     * Identificador do fornecedor
     * @return int id of Fornecedor
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param int $id Set id for Fornecedor
     * @return self Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Empresa do fornecedor
     * @return int empresa of Fornecedor
     */
    public function getEmpresaID()
    {
        return $this->empresa_id;
    }

    /**
     * Set EmpresaID value to new on param
     * @param int $empresa_id Set empresa for Fornecedor
     * @return self Self instance
     */
    public function setEmpresaID($empresa_id)
    {
        $this->empresa_id = $empresa_id;
        return $this;
    }

    /**
     * Prazo em dias para pagamento do fornecedor
     * @return int prazo de pagamento of Fornecedor
     */
    public function getPrazoPagamento()
    {
        return $this->prazo_pagamento;
    }

    /**
     * Set PrazoPagamento value to new on param
     * @param int $prazo_pagamento Set prazo de pagamento for Fornecedor
     * @return self Self instance
     */
    public function setPrazoPagamento($prazo_pagamento)
    {
        $this->prazo_pagamento = $prazo_pagamento;
        return $this;
    }

    /**
     * Data de cadastro do fornecedor
     * @return string data de cadastro of Fornecedor
     */
    public function getDataCadastro()
    {
        return $this->data_cadastro;
    }

    /**
     * Set DataCadastro value to new on param
     * @param string $data_cadastro Set data de cadastro for Fornecedor
     * @return self Self instance
     */
    public function setDataCadastro($data_cadastro)
    {
        $this->data_cadastro = $data_cadastro;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $fornecedor = parent::toArray($recursive);
        $fornecedor['id'] = $this->getID();
        $fornecedor['empresaid'] = $this->getEmpresaID();
        $fornecedor['prazopagamento'] = $this->getPrazoPagamento();
        $fornecedor['datacadastro'] = $this->getDataCadastro();
        return $fornecedor;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param mixed $fornecedor Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($fornecedor = [])
    {
        if ($fornecedor instanceof self) {
            $fornecedor = $fornecedor->toArray();
        } elseif (!is_array($fornecedor)) {
            $fornecedor = [];
        }
        parent::fromArray($fornecedor);
        if (!isset($fornecedor['id'])) {
            $this->setID(null);
        } else {
            $this->setID($fornecedor['id']);
        }
        if (!isset($fornecedor['empresaid'])) {
            $this->setEmpresaID(null);
        } else {
            $this->setEmpresaID($fornecedor['empresaid']);
        }
        if (!isset($fornecedor['prazopagamento'])) {
            $this->setPrazoPagamento(null);
        } else {
            $this->setPrazoPagamento($fornecedor['prazopagamento']);
        }
        if (!isset($fornecedor['datacadastro'])) {
            $this->setDataCadastro(DB::now());
        } else {
            $this->setDataCadastro($fornecedor['datacadastro']);
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
        $fornecedor = parent::publish($requester);
        return $fornecedor;
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
        $this->setEmpresaID(Filter::number($this->getEmpresaID()));
        $this->setPrazoPagamento(Filter::number($this->getPrazoPagamento()));
        $this->setDataCadastro(Filter::datetime($this->getDataCadastro()));
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
     * @return array All field of Fornecedor in array format
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getEmpresaID())) {
            $errors['empresaid'] = _t('fornecedor.empresa_id_cannot_empty');
        }
        if (is_null($this->getPrazoPagamento())) {
            $errors['prazopagamento'] = _t('fornecedor.prazo_pagamento_cannot_empty');
        }
        $cliente = $this->findEmpresaID();
        if ($cliente->getTipo() != Cliente::TIPO_JURIDICA) {
            $errors['empresaid'] = 'A empresa deve ser do tipo jurídica';
        }
        $this->setDataCadastro(DB::now());
        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
        $values = $this->toArray();
        if ($this->exists()) {
            unset($values['datacadastro']);
        }
        return $values;
    }

    /**
     * Translate SQL exception into application exception
     * @param \Exception $e exception to translate into a readable error
     * @return \MZ\Exception\ValidationException new exception translated
     */
    protected function translate($e)
    {
        if (contains(['EmpresaID', 'UNIQUE'], $e->getMessage())) {
            return new ValidationException([
                'empresaid' => _t(
                    'fornecedor.empresa_id_used',
                    $this->getEmpresaID()
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Load into this object from database using, EmpresaID
     * @return self Self filled instance or empty when not found
     */
    public function loadByEmpresaID()
    {
        return $this->load([
            'empresaid' => intval($this->getEmpresaID()),
        ]);
    }

    /**
     * Empresa do fornecedor
     * @return \MZ\Account\Cliente The object fetched from database
     */
    public function findEmpresaID()
    {
        return \MZ\Account\Cliente::findByID($this->getEmpresaID());
    }

    /**
     * Filter condition array with allowed fields
     * @param array $condition condition to filter rows
     * @return array allowed condition
     */
    protected function filterCondition($condition)
    {
        $allowed = $this->getAllowedKeys();
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
        $query = DB::from('Fornecedores f')
            ->leftJoin('Clientes c ON c.id = f.empresaid')
            ->leftJoin('Telefones t ON t.clienteid = c.id AND t.principal = ?', 'Y');
        if (isset($condition['search'])) {
            $search = trim($condition['search']);
            if (Validator::checkEmail($search)) {
                $query = $query->where('c.email', $search);
            } elseif (Validator::checkCNPJ($search)) {
                $query = $query->where('c.cpf', Filter::digits($search));
            } elseif (Validator::checkPhone($search)) {
                $fone = Cliente::buildFoneSearch($search);
                $query = $query->where('t.numero', $search);
                $query = $query->orderBy('t.numero ASC');
            } else {
                $query = DB::buildSearch(
                    $search,
                    DB::concat(['c.nome', '" "', 'COALESCE(c.sobrenome, "")']),
                    $query
                );
            }
            unset($condition['search']);
        }
        $condition = $this->filterCondition($condition);
        $query = DB::buildOrderBy($query, $this->filterOrder($order));
        $query = $query->orderBy('c.nome ASC');
        $query = $query->orderBy('f.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Find this object on database using, EmpresaID
     * @param int $empresa_id empresa to find Fornecedor
     * @return self A filled instance or empty when not found
     */
    public static function findByEmpresaID($empresa_id)
    {
        $result = new self();
        $result->setEmpresaID($empresa_id);
        return $result->loadByEmpresaID();
    }
}
