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
 * Módulos do sistema que podem ser desativados/ativados
 */
class Modulo extends SyncModel
{

    /**
     * Identificador do módulo
     */
    private $id;
    /**
     * Nome do módulo, unico em todo o sistema
     */
    private $nome;
    /**
     * Descrição do módulo, informa detalhes sobre a funcionalidade do módulo
     * no sistema
     */
    private $descricao;
    /**
     * Informa se o módulo do sistema está habilitado
     */
    private $habilitado;

    /**
     * Constructor for a new empty instance of Modulo
     * @param array $modulo All field and values to fill the instance
     */
    public function __construct($modulo = [])
    {
        parent::__construct($modulo);
    }

    /**
     * Identificador do módulo
     * @return int id of Módulo
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param int $id Set id for Módulo
     * @return self Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Nome do módulo, unico em todo o sistema
     * @return string nome of Módulo
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set Nome value to new on param
     * @param string $nome Set nome for Módulo
     * @return self Self instance
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * Descrição do módulo, informa detalhes sobre a funcionalidade do módulo
     * no sistema
     * @return string descrição of Módulo
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Set Descricao value to new on param
     * @param string $descricao Set descrição for Módulo
     * @return self Self instance
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * Informa se o módulo do sistema está habilitado
     * @return string habilitado of Módulo
     */
    public function getHabilitado()
    {
        return $this->habilitado;
    }

    /**
     * Informa se o módulo do sistema está habilitado
     * @return boolean Check if o of Habilitado is selected or checked
     */
    public function isHabilitado()
    {
        return $this->habilitado == 'Y';
    }

    /**
     * Set Habilitado value to new on param
     * @param string $habilitado Set habilitado for Módulo
     * @return self Self instance
     */
    public function setHabilitado($habilitado)
    {
        $this->habilitado = $habilitado;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $modulo = parent::toArray($recursive);
        $modulo['id'] = $this->getID();
        $modulo['nome'] = $this->getNome();
        $modulo['descricao'] = $this->getDescricao();
        $modulo['habilitado'] = $this->getHabilitado();
        return $modulo;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param mixed $modulo Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($modulo = [])
    {
        if ($modulo instanceof self) {
            $modulo = $modulo->toArray();
        } elseif (!is_array($modulo)) {
            $modulo = [];
        }
        parent::fromArray($modulo);
        if (!isset($modulo['id'])) {
            $this->setID(null);
        } else {
            $this->setID($modulo['id']);
        }
        if (!isset($modulo['nome'])) {
            $this->setNome(null);
        } else {
            $this->setNome($modulo['nome']);
        }
        if (!isset($modulo['descricao'])) {
            $this->setDescricao(null);
        } else {
            $this->setDescricao($modulo['descricao']);
        }
        if (!isset($modulo['habilitado'])) {
            $this->setHabilitado('N');
        } else {
            $this->setHabilitado($modulo['habilitado']);
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
        $modulo = parent::publish($requester);
        return $modulo;
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
        $this->setNome(Filter::string($original->getNome()));
        $this->setDescricao(Filter::string($original->getDescricao()));
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
     * @return array All field of Modulo in array format
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getNome())) {
            $errors['nome'] = _t('modulo.nome_cannot_empty');
        }
        if (is_null($this->getDescricao())) {
            $errors['descricao'] = _t('modulo.descricao_cannot_empty');
        }
        if (!Validator::checkBoolean($this->getHabilitado())) {
            $errors['habilitado'] = _t('modulo.habilitado_invalid');
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
        if (contains(['Nome', 'UNIQUE'], $e->getMessage())) {
            return new ValidationException([
                'nome' => _t(
                    'modulo.nome_used',
                    $this->getNome()
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Load into this object from database using, Nome
     * @return self Self filled instance or empty when not found
     */
    public function loadByNome()
    {
        return $this->load([
            'nome' => strval($this->getNome()),
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
            $field = 'm.nome LIKE ?';
            $condition[$field] = '%'.$search.'%';
            $allowed[$field] = true;
            unset($condition['search']);
        }
        return Filter::keys($condition, $allowed, 'm.');
    }

    /**
     * Fetch data from database with a condition
     * @param array $condition condition to filter rows
     * @param array $order order rows
     * @return SelectQuery query object with condition statement
     */
    protected function query($condition = [], $order = [])
    {
        $query = DB::from('Modulos m');
        $condition = $this->filterCondition($condition);
        $query = DB::buildOrderBy($query, $this->filterOrder($order));
        $query = $query->orderBy('m.nome ASC');
        $query = $query->orderBy('m.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Find this object on database using, Nome
     * @param string $nome nome to find Módulo
     * @return self A filled instance or empty when not found
     */
    public static function findByNome($nome)
    {
        $result = new self();
        $result->setNome($nome);
        return $result->loadByNome();
    }
}
