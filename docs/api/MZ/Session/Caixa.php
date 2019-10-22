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

namespace MZ\Session;

use MZ\Util\Mask;
use MZ\Util\Filter;
use MZ\Util\Validator;
use MZ\Database\DB;
use MZ\Database\SyncModel;
use MZ\Exception\ValidationException;

/**
 * Caixas de movimentação financeira
 */
class Caixa extends SyncModel
{

    /**
     * Identificador do caixa
     */
    private $id;
    /**
     * Informa a carteira que representa esse caixa
     */
    private $carteira_id;
    /**
     * Descrição do caixa
     */
    private $descricao;
    /**
     * Série do caixa
     */
    private $serie;
    /**
     * Número inicial na geração da nota, será usado quando maior que o último
     * número utilizado
     */
    private $numero_inicial;
    /**
     * Informa se o caixa está ativo
     */
    private $ativo;

    /**
     * Constructor for a new empty instance of Caixa
     * @param array $caixa All field and values to fill the instance
     */
    public function __construct($caixa = [])
    {
        parent::__construct($caixa);
    }

    /**
     * Identificador do caixa
     * @return int id of Caixa
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param int $id Set id for Caixa
     * @return self Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Informa a carteira que representa esse caixa
     * @return int carteira of Caixa
     */
    public function getCarteiraID()
    {
        return $this->carteira_id;
    }

    /**
     * Set CarteiraID value to new on param
     * @param int $carteira_id Set carteira for Caixa
     * @return self Self instance
     */
    public function setCarteiraID($carteira_id)
    {
        $this->carteira_id = $carteira_id;
        return $this;
    }

    /**
     * Descrição do caixa
     * @return string descrição of Caixa
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Set Descricao value to new on param
     * @param string $descricao Set descrição for Caixa
     * @return self Self instance
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * Série do caixa
     * @return int série of Caixa
     */
    public function getSerie()
    {
        return $this->serie;
    }

    /**
     * Set Serie value to new on param
     * @param int $serie Set série for Caixa
     * @return self Self instance
     */
    public function setSerie($serie)
    {
        $this->serie = $serie;
        return $this;
    }

    /**
     * Número inicial na geração da nota, será usado quando maior que o último
     * número utilizado
     * @return int número inicial of Caixa
     */
    public function getNumeroInicial()
    {
        return $this->numero_inicial;
    }

    /**
     * Set NumeroInicial value to new on param
     * @param int $numero_inicial Set número inicial for Caixa
     * @return self Self instance
     */
    public function setNumeroInicial($numero_inicial)
    {
        $this->numero_inicial = $numero_inicial;
        return $this;
    }

    /**
     * Informa se o caixa está ativo
     * @return string ativo of Caixa
     */
    public function getAtivo()
    {
        return $this->ativo;
    }

    /**
     * Informa se o caixa está ativo
     * @return boolean Check if o of Ativo is selected or checked
     */
    public function isAtivo()
    {
        return $this->ativo == 'Y';
    }

    /**
     * Set Ativo value to new on param
     * @param string $ativo Set ativo for Caixa
     * @return self Self instance
     */
    public function setAtivo($ativo)
    {
        $this->ativo = $ativo;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $caixa = parent::toArray($recursive);
        $caixa['id'] = $this->getID();
        $caixa['carteiraid'] = $this->getCarteiraID();
        $caixa['descricao'] = $this->getDescricao();
        $caixa['serie'] = $this->getSerie();
        $caixa['numeroinicial'] = $this->getNumeroInicial();
        $caixa['ativo'] = $this->getAtivo();
        return $caixa;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param mixed $caixa Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($caixa = [])
    {
        if ($caixa instanceof self) {
            $caixa = $caixa->toArray();
        } elseif (!is_array($caixa)) {
            $caixa = [];
        }
        parent::fromArray($caixa);
        if (!isset($caixa['id'])) {
            $this->setID(null);
        } else {
            $this->setID($caixa['id']);
        }
        if (!isset($caixa['carteiraid'])) {
            $this->setCarteiraID(null);
        } else {
            $this->setCarteiraID($caixa['carteiraid']);
        }
        if (!isset($caixa['descricao'])) {
            $this->setDescricao(null);
        } else {
            $this->setDescricao($caixa['descricao']);
        }
        if (!isset($caixa['serie'])) {
            $this->setSerie(null);
        } else {
            $this->setSerie($caixa['serie']);
        }
        if (!isset($caixa['numeroinicial'])) {
            $this->setNumeroInicial(null);
        } else {
            $this->setNumeroInicial($caixa['numeroinicial']);
        }
        if (!isset($caixa['ativo'])) {
            $this->setAtivo('N');
        } else {
            $this->setAtivo($caixa['ativo']);
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
        $caixa = parent::publish($requester);
        return $caixa;
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
        $this->setCarteiraID(Filter::number($this->getCarteiraID()));
        $this->setDescricao(Filter::string($this->getDescricao()));
        $this->setSerie(Filter::number($this->getSerie()));
        $this->setNumeroInicial(Filter::number($this->getNumeroInicial()));
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
     * @return array All field of Caixa in array format
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getCarteiraID())) {
            $errors['carteiraid'] = _t('caixa.carteira_id_cannot_empty');
        }
        if (is_null($this->getDescricao())) {
            $errors['descricao'] = _t('caixa.descricao_cannot_empty');
        }
        $old_caixa = self::findByID($this->getID());
        if (!app()->getSystem()->isFiscalVisible()) {
            $this->setSerie($old_caixa->exists() ? $old_caixa->getSerie() : 1);
        } elseif (!Validator::checkDigits($this->getSerie())) {
            $errors['serie'] = _t('caixa.serie_cannot_empty');
        }
        if (!app()->getSystem()->isFiscalVisible()) {
            $this->setNumeroInicial($old_caixa->exists() ? $old_caixa->getNumeroInicial() : 1);
        } elseif (!Validator::checkDigits($this->getNumeroInicial())) {
            $errors['numeroinicial'] = _t('caixa.numero_inicial_cannot_empty');
        }
        if (!Validator::checkBoolean($this->getAtivo())) {
            $errors['ativo'] = _t('caixa.ativo_invalid');
        } elseif (!$this->isAtivo() && Movimentacao::isCaixaOpen($this->getID())) {
            $errors['ativo'] = 'O caixa está aberto e não pode ser desativado';
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
                    'caixa.descricao_used',
                    $this->getDescricao()
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Reset invoice initial number to 1 because reached the maximum
     * @param  int $serie affect only this serie
     * @return Caixa Self instance
     */
    public static function resetBySerie($serie)
    {
        // TODO: convert to incremental version
        try {
            DB::update('Caixas')
                ->set('numeroinicial', '1')
                ->where('serie', $serie)
                ->where('ativo', 'Y')
                ->execute();
        } catch (\Exception $e) {
            $caixa = new Caixa();
            throw $caixa->translate($e);
        }
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
     * Informa a carteira que representa esse caixa
     * @return \MZ\Wallet\Carteira The object fetched from database
     */
    public function findCarteiraID()
    {
        return \MZ\Wallet\Carteira::findByID($this->getCarteiraID());
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
            $field = 'c.descricao LIKE ?';
            $condition[$field] = '%'.$search.'%';
            $allowed[$field] = true;
            unset($condition['search']);
        }
        return Filter::keys($condition, $allowed, 'c.');
    }

    /**
     * Fetch data from database with a condition
     * @param array $condition condition to filter rows
     * @param array $order order rows
     * @return SelectQuery query object with condition statement
     */
    protected function query($condition = [], $order = [])
    {
        $query = DB::from('Caixas c');
        $condition = $this->filterCondition($condition);
        $query = DB::buildOrderBy($query, $this->filterOrder($order));
        $query = $query->orderBy('c.descricao ASC');
        $query = $query->orderBy('c.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Find this object on database using, Descricao
     * @param string $descricao descrição to find Caixa
     * @return self A filled instance or empty when not found
     */
    public static function findByDescricao($descricao)
    {
        $result = new self();
        $result->setDescricao($descricao);
        return $result->loadByDescricao();
    }

    /**
     * Find this object as active on database using, Serie
     * @param  string $serie série to find Caixa
     * @return Caixa An active filled instance or empty when not found
     */
    public static function findBySerie($serie)
    {
        return self::find(
            ['serie' => intval($serie), 'ativo' => 'Y'],
            ['numeroinicial' => -1]
        );
    }
}
