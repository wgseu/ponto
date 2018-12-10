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
use MZ\Database\SyncModel;
use MZ\Exception\ValidationException;

/**
 * Permite acesso à uma determinada funcionalidade da lista de permissões
 */
class Acesso extends SyncModel
{

    /**
     * Identificador do acesso
     */
    private $id;
    /**
     * Função a que a permissão se aplica
     */
    private $funcao_id;
    /**
     * Permissão liberada para a função
     */
    private $permissao_id;

    /**
     * Constructor for a new empty instance of Acesso
     * @param array $acesso All field and values to fill the instance
     */
    public function __construct($acesso = [])
    {
        parent::__construct($acesso);
    }

    /**
     * Identificador do acesso
     * @return int id of Acesso
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param int $id Set id for Acesso
     * @return self Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Função a que a permissão se aplica
     * @return int função of Acesso
     */
    public function getFuncaoID()
    {
        return $this->funcao_id;
    }

    /**
     * Set FuncaoID value to new on param
     * @param int $funcao_id Set função for Acesso
     * @return self Self instance
     */
    public function setFuncaoID($funcao_id)
    {
        $this->funcao_id = $funcao_id;
        return $this;
    }

    /**
     * Permissão liberada para a função
     * @return int permissão of Acesso
     */
    public function getPermissaoID()
    {
        return $this->permissao_id;
    }

    /**
     * Set PermissaoID value to new on param
     * @param int $permissao_id Set permissão for Acesso
     * @return self Self instance
     */
    public function setPermissaoID($permissao_id)
    {
        $this->permissao_id = $permissao_id;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $acesso = parent::toArray($recursive);
        $acesso['id'] = $this->getID();
        $acesso['funcaoid'] = $this->getFuncaoID();
        $acesso['permissaoid'] = $this->getPermissaoID();
        return $acesso;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param mixed $acesso Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($acesso = [])
    {
        if ($acesso instanceof self) {
            $acesso = $acesso->toArray();
        } elseif (!is_array($acesso)) {
            $acesso = [];
        }
        parent::fromArray($acesso);
        if (!isset($acesso['id'])) {
            $this->setID(null);
        } else {
            $this->setID($acesso['id']);
        }
        if (!isset($acesso['funcaoid'])) {
            $this->setFuncaoID(null);
        } else {
            $this->setFuncaoID($acesso['funcaoid']);
        }
        if (!isset($acesso['permissaoid'])) {
            $this->setPermissaoID(null);
        } else {
            $this->setPermissaoID($acesso['permissaoid']);
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
        $acesso = parent::publish($requester);
        return $acesso;
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
        $this->setFuncaoID(Filter::number($this->getFuncaoID()));
        $this->setPermissaoID(Filter::number($this->getPermissaoID()));
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
     * @return array All field of Acesso in array format
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getFuncaoID())) {
            $errors['funcaoid'] = _t('acesso.funcao_id_cannot_empty');
        }
        if (is_null($this->getPermissaoID())) {
            $errors['permissaoid'] = _t('acesso.permissao_id_cannot_empty');
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
        if (contains(['FuncaoID', 'PermissaoID', 'UNIQUE'], $e->getMessage())) {
            return new ValidationException([
                'funcaoid' => _t(
                    'acesso.funcao_id_used',
                    $this->getFuncaoID()
                ),
                'permissaoid' => _t(
                    'acesso.permissao_id_used',
                    $this->getPermissaoID()
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Load into this object from database using, FuncaoID, PermissaoID
     * @return self Self filled instance or empty when not found
     */
    public function loadByFuncaoIDPermissaoID()
    {
        return $this->load([
            'funcaoid' => intval($this->getFuncaoID()),
            'permissaoid' => intval($this->getPermissaoID()),
        ]);
    }

    /**
     * Função a que a permissão se aplica
     * @return \MZ\Provider\Funcao The object fetched from database
     */
    public function findFuncaoID()
    {
        return \MZ\Provider\Funcao::findByID($this->getFuncaoID());
    }

    /**
     * Permissão liberada para a função
     * @return \MZ\System\Permissao The object fetched from database
     */
    public function findPermissaoID()
    {
        return \MZ\System\Permissao::findByID($this->getPermissaoID());
    }

    /**
     * Filter condition array with allowed fields
     * @param array $condition condition to filter rows
     * @return array allowed condition
     */
    protected function filterCondition($condition)
    {
        $allowed = $this->getAllowedKeys();
        return Filter::keys($condition, $allowed, 'a.');
    }

    /**
     * Fetch data from database with a condition
     * @param array $condition condition to filter rows
     * @param array $order order rows
     * @return SelectQuery query object with condition statement
     */
    protected function query($condition = [], $order = [])
    {
        $query = DB::from('Acessos a');
        $condition = $this->filterCondition($condition);
        $query = DB::buildOrderBy($query, $this->filterOrder($order));
        $query = $query->orderBy('a.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Find this object on database using, FuncaoID, PermissaoID
     * @param int $funcao_id função to find Acesso
     * @param int $permissao_id permissão to find Acesso
     * @return self A filled instance or empty when not found
     */
    public static function findByFuncaoIDPermissaoID($funcao_id, $permissao_id)
    {
        $result = new self();
        $result->setFuncaoID($funcao_id);
        $result->setPermissaoID($permissao_id);
        return $result->loadByFuncaoIDPermissaoID();
    }

    public static function getPermissoes($funcao_id)
    {
        $instance = new self();
        $rows = $instance->query(['funcaoid' => $funcao_id])
            ->select(null)
            ->select('p.nome')
            ->leftJoin('Permissoes p ON p.id = a.permissaoid')
            ->fetchAll();
        $permissoes = [];
        foreach ($rows as $row) {
            $permissoes[] = $row['nome'];
        }
        return $permissoes;
    }
}
