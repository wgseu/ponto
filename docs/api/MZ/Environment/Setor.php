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

namespace MZ\Environment;

use MZ\Util\Mask;
use MZ\Util\Filter;
use MZ\Util\Validator;
use MZ\Database\DB;
use MZ\Database\SyncModel;
use MZ\Exception\ValidationException;

/**
 * Setor de impressão e de estoque
 */
class Setor extends SyncModel
{

    /**
     * Identificador do setor
     */
    private $id;
    /**
     * Informa o setor que abrange esse subsetor
     */
    private $setor_id;
    /**
     * Nome do setor, único em todo o sistema
     */
    private $nome;
    /**
     * Descreve a utilização do setor
     */
    private $descricao;

    /**
     * Constructor for a new empty instance of Setor
     * @param array $setor All field and values to fill the instance
     */
    public function __construct($setor = [])
    {
        parent::__construct($setor);
    }

    /**
     * Identificador do setor
     * @return int id of Setor
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param int $id Set id for Setor
     * @return self Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Informa o setor que abrange esse subsetor
     * @return int setor superior of Setor
     */
    public function getSetorID()
    {
        return $this->setor_id;
    }

    /**
     * Set SetorID value to new on param
     * @param int $setor_id Set setor superior for Setor
     * @return self Self instance
     */
    public function setSetorID($setor_id)
    {
        $this->setor_id = $setor_id;
        return $this;
    }

    /**
     * Nome do setor, único em todo o sistema
     * @return string nome of Setor
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set Nome value to new on param
     * @param string $nome Set nome for Setor
     * @return self Self instance
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * Descreve a utilização do setor
     * @return string descrição of Setor
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Set Descricao value to new on param
     * @param string $descricao Set descrição for Setor
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
        $setor = parent::toArray($recursive);
        $setor['id'] = $this->getID();
        $setor['setorid'] = $this->getSetorID();
        $setor['nome'] = $this->getNome();
        $setor['descricao'] = $this->getDescricao();
        return $setor;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param mixed $setor Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($setor = [])
    {
        if ($setor instanceof self) {
            $setor = $setor->toArray();
        } elseif (!is_array($setor)) {
            $setor = [];
        }
        parent::fromArray($setor);
        if (!isset($setor['id'])) {
            $this->setID(null);
        } else {
            $this->setID($setor['id']);
        }
        if (!array_key_exists('setorid', $setor)) {
            $this->setSetorID(null);
        } else {
            $this->setSetorID($setor['setorid']);
        }
        if (!isset($setor['nome'])) {
            $this->setNome(null);
        } else {
            $this->setNome($setor['nome']);
        }
        if (!array_key_exists('descricao', $setor)) {
            $this->setDescricao(null);
        } else {
            $this->setDescricao($setor['descricao']);
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
        $setor = parent::publish($requester);
        return $setor;
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
        $this->setNome(Filter::string($this->getNome()));
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
     * @return array All field of Setor in array format
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getNome())) {
            $errors['nome'] = _t('setor.nome_cannot_empty');
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
                    'setor.nome_used',
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
     * Informa o setor que abrange esse subsetor
     * @return \MZ\Environment\Setor The object fetched from database
     */
    public function findSetorID()
    {
        return \MZ\Environment\Setor::findByID($this->getSetorID());
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
            $field = 's.nome LIKE ?';
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
        $query = DB::from('Setores s');
        $condition = $this->filterCondition($condition);
        $query = DB::buildOrderBy($query, $this->filterOrder($order));
        $query = $query->orderBy('s.nome ASC');
        $query = $query->orderBy('s.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Find this object on database using, Nome
     * @param string $nome nome to find Setor
     * @return self A filled instance or empty when not found
     */
    public static function findByNome($nome)
    {
        $result = new self();
        $result->setNome($nome);
        return $result->loadByNome();
    }

    /**
     * Find default sector
     * @return Setor A filled instance or empty when not found
     */
    public static function findDefault()
    {
        // <TODO:>Mudar busca por setor Vendas para o setor do dispositivo</TODO:>
        $setor = self::findByNome('Vendas');
        if ($setor->exists()) {
            return $setor;
        }
        return self::find([]);
    }
}
