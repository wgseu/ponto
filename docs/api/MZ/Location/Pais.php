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

namespace MZ\Location;

use MZ\Util\Mask;
use MZ\Util\Filter;
use MZ\Util\Validator;
use MZ\Database\DB;
use MZ\Database\SyncModel;
use MZ\Exception\ValidationException;

/**
 * Informações de um páis com sua moeda e língua nativa
 */
class Pais extends SyncModel
{

    /**
     * Identificador do país
     */
    private $id;
    /**
     * Nome do país
     */
    private $nome;
    /**
     * Abreviação do nome do país
     */
    private $sigla;
    /**
     * Código do país com 2 letras
     */
    private $codigo;
    /**
     * Informa a moeda principal do país
     */
    private $moeda_id;
    /**
     * Idioma nativo do país
     */
    private $idioma;
    /**
     * Prefixo de telefone para ligações internacionais
     */
    private $prefixo;
    /**
     * Frases, nomes de campos e máscaras específicas do país
     */
    private $entradas;
    /**
     * Informa se o país tem apenas um estado federativo
     */
    private $unitario;

    /**
     * Constructor for a new empty instance of Pais
     * @param array $pais All field and values to fill the instance
     */
    public function __construct($pais = [])
    {
        parent::__construct($pais);
    }

    /**
     * Identificador do país
     * @return int id of País
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param int $id Set id for País
     * @return self Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Nome do país
     * @return string nome of País
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set Nome value to new on param
     * @param string $nome Set nome for País
     * @return self Self instance
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * Abreviação do nome do país
     * @return string sigla of País
     */
    public function getSigla()
    {
        return $this->sigla;
    }

    /**
     * Set Sigla value to new on param
     * @param string $sigla Set sigla for País
     * @return self Self instance
     */
    public function setSigla($sigla)
    {
        $this->sigla = $sigla;
        return $this;
    }

    /**
     * Código do país com 2 letras
     * @return string código of País
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set Codigo value to new on param
     * @param string $codigo Set código for País
     * @return self Self instance
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
        return $this;
    }

    /**
     * Informa a moeda principal do país
     * @return int moeda of País
     */
    public function getMoedaID()
    {
        return $this->moeda_id;
    }

    /**
     * Set MoedaID value to new on param
     * @param int $moeda_id Set moeda for País
     * @return self Self instance
     */
    public function setMoedaID($moeda_id)
    {
        $this->moeda_id = $moeda_id;
        return $this;
    }

    /**
     * Idioma nativo do país
     * @return string código do idioma of País
     */
    public function getIdioma()
    {
        return $this->idioma;
    }

    /**
     * Set Idioma value to new on param
     * @param string $idioma Set código do idioma for País
     * @return self Self instance
     */
    public function setIdioma($idioma)
    {
        $this->idioma = $idioma;
        return $this;
    }

    /**
     * Prefixo de telefone para ligações internacionais
     * @return string prefixo of País
     */
    public function getPrefixo()
    {
        return $this->prefixo;
    }

    /**
     * Set Prefixo value to new on param
     * @param string $prefixo Set prefixo for País
     * @return self Self instance
     */
    public function setPrefixo($prefixo)
    {
        $this->prefixo = $prefixo;
        return $this;
    }

    /**
     * Frases, nomes de campos e máscaras específicas do país
     * @return string entrada of País
     */
    public function getEntradas()
    {
        return $this->entradas;
    }

    /**
     * Set Entradas value to new on param
     * @param string $entradas Set entrada for País
     * @return self Self instance
     */
    public function setEntradas($entradas)
    {
        $this->entradas = $entradas;
        return $this;
    }

    /**
     * Informa se o país tem apenas um estado federativo
     * @return string unitário of País
     */
    public function getUnitario()
    {
        return $this->unitario;
    }

    /**
     * Informa se o país tem apenas um estado federativo
     * @return boolean Check if o of Unitario is selected or checked
     */
    public function isUnitario()
    {
        return $this->unitario == 'Y';
    }

    /**
     * Set Unitario value to new on param
     * @param string $unitario Set unitário for País
     * @return self Self instance
     */
    public function setUnitario($unitario)
    {
        $this->unitario = $unitario;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $pais = parent::toArray($recursive);
        $pais['id'] = $this->getID();
        $pais['nome'] = $this->getNome();
        $pais['sigla'] = $this->getSigla();
        $pais['codigo'] = $this->getCodigo();
        $pais['moedaid'] = $this->getMoedaID();
        $pais['idioma'] = $this->getIdioma();
        $pais['prefixo'] = $this->getPrefixo();
        $pais['entradas'] = $this->getEntradas();
        $pais['unitario'] = $this->getUnitario();
        return $pais;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param mixed $pais Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($pais = [])
    {
        if ($pais instanceof self) {
            $pais = $pais->toArray();
        } elseif (!is_array($pais)) {
            $pais = [];
        }
        parent::fromArray($pais);
        if (!isset($pais['id'])) {
            $this->setID(null);
        } else {
            $this->setID($pais['id']);
        }
        if (!isset($pais['nome'])) {
            $this->setNome(null);
        } else {
            $this->setNome($pais['nome']);
        }
        if (!isset($pais['sigla'])) {
            $this->setSigla(null);
        } else {
            $this->setSigla($pais['sigla']);
        }
        if (!isset($pais['codigo'])) {
            $this->setCodigo(null);
        } else {
            $this->setCodigo($pais['codigo']);
        }
        if (!isset($pais['moedaid'])) {
            $this->setMoedaID(null);
        } else {
            $this->setMoedaID($pais['moedaid']);
        }
        if (!isset($pais['idioma'])) {
            $this->setIdioma(null);
        } else {
            $this->setIdioma($pais['idioma']);
        }
        if (!array_key_exists('prefixo', $pais)) {
            $this->setPrefixo(null);
        } else {
            $this->setPrefixo($pais['prefixo']);
        }
        if (!array_key_exists('entradas', $pais)) {
            $this->setEntradas(null);
        } else {
            $this->setEntradas($pais['entradas']);
        }
        if (!isset($pais['unitario'])) {
            $this->setUnitario('N');
        } else {
            $this->setUnitario($pais['unitario']);
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
        $pais = parent::publish($requester);
        return $pais;
    }

    /**
     * Get flag index base on country code
     * @return int flag index
     */
    public function getBandeiraIndex()
    {
        switch ($this->getCodigo()) {
            case 'BR':
                return 28;
            case 'US':
                return 220;
            case 'ES':
                return 66;
            case 'MZ':
                return 151;
            default:
                return 0;
        }
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
        $this->setNome(Filter::string($this->getNome()));
        $this->setSigla(Filter::string($this->getSigla()));
        $this->setCodigo(Filter::string($this->getCodigo()));
        $this->setMoedaID(Filter::number($this->getMoedaID()));
        $this->setIdioma(Filter::string($this->getIdioma()));
        $this->setPrefixo(Filter::string($this->getPrefixo()));
        $this->setEntradas(Filter::text($this->getEntradas()));
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
     * @return array All field of Pais in array format
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getNome())) {
            $errors['nome'] = _t('pais.nome_cannot_empty');
        }
        if (is_null($this->getSigla())) {
            $errors['sigla'] = _t('pais.sigla_cannot_empty');
        }
        if (is_null($this->getCodigo())) {
            $errors['codigo'] = _t('pais.codigo_cannot_empty');
        }
        if (is_null($this->getMoedaID())) {
            $errors['moedaid'] = _t('pais.moeda_id_cannot_empty');
        }
        if (is_null($this->getIdioma())) {
            $errors['idioma'] = _t('pais.idioma_cannot_empty');
        }
        if (!Validator::checkBoolean($this->getUnitario())) {
            $errors['unitario'] = _t('pais.unitario_invalid');
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
                    'pais.nome_used',
                    $this->getNome()
                ),
            ]);
        }
        if (contains(['Sigla', 'UNIQUE'], $e->getMessage())) {
            return new ValidationException([
                'sigla' => _t(
                    'pais.sigla_used',
                    $this->getSigla()
                ),
            ]);
        }
        if (contains(['Codigo', 'UNIQUE'], $e->getMessage())) {
            return new ValidationException([
                'codigo' => _t(
                    'pais.codigo_used',
                    $this->getCodigo()
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
     * Load into this object from database using, Sigla
     * @return self Self filled instance or empty when not found
     */
    public function loadBySigla()
    {
        return $this->load([
            'sigla' => strval($this->getSigla()),
        ]);
    }

    /**
     * Load into this object from database using, Codigo
     * @return self Self filled instance or empty when not found
     */
    public function loadByCodigo()
    {
        return $this->load([
            'codigo' => strval($this->getCodigo()),
        ]);
    }

    /**
     * Informa a moeda principal do país
     * @return \MZ\Wallet\Moeda The object fetched from database
     */
    public function findMoedaID()
    {
        return \MZ\Wallet\Moeda::findByID($this->getMoedaID());
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
            $field = '(p.nome LIKE ? OR p.sigla = ? OR p.codigo = ?)';
            $condition[$field] = ['%'.$search.'%', $search, $search];
            $allowed[$field] = true;
            unset($condition['search']);
        }
        return Filter::keys($condition, $allowed, 'p.');
    }

    /**
     * Fetch data from database with a condition
     * @param array $condition condition to filter rows
     * @param array $order order rows
     * @return SelectQuery query object with condition statement
     */
    protected function query($condition = [], $order = [])
    {
        $query = DB::from('Paises p');
        $condition = $this->filterCondition($condition);
        $query = DB::buildOrderBy($query, $this->filterOrder($order));
        $query = $query->orderBy('p.nome ASC');
        $query = $query->orderBy('p.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Find this object on database using, Nome
     * @param string $nome nome to find País
     * @return self A filled instance or empty when not found
     */
    public static function findByNome($nome)
    {
        $result = new self();
        $result->setNome($nome);
        return $result->loadByNome();
    }

    /**
     * Find this object on database using, Sigla
     * @param string $sigla sigla to find País
     * @return self A filled instance or empty when not found
     */
    public static function findBySigla($sigla)
    {
        $result = new self();
        $result->setSigla($sigla);
        return $result->loadBySigla();
    }

    /**
     * Find this object on database using, Codigo
     * @param string $codigo código to find País
     * @return self A filled instance or empty when not found
     */
    public static function findByCodigo($codigo)
    {
        $result = new self();
        $result->setCodigo($codigo);
        return $result->loadByCodigo();
    }
}
