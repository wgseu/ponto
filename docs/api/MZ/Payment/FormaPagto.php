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
namespace MZ\Payment;

use MZ\Util\Mask;
use MZ\Util\Filter;
use MZ\Util\Validator;
use MZ\Database\DB;
use MZ\Database\SyncModel;
use MZ\Exception\ValidationException;

/**
 * Formas de pagamento disponíveis para pedido e contas
 */
class FormaPagto extends SyncModel
{
    protected $table = 'Formas_Pagto';

    /**
     * Tipo de pagamento
     */
    const TIPO_DINHEIRO = 'Dinheiro';
    const TIPO_CREDITO = 'Credito';
    const TIPO_DEBITO = 'Debito';
    const TIPO_VALE = 'Vale';
    const TIPO_CHEQUE = 'Cheque';
    const TIPO_CREDIARIO = 'Crediario';
    const TIPO_SALDO = 'Saldo';

    /**
     * Identificador da forma de pagamento
     */
    private $id;
    /**
     * Tipo de pagamento
     */
    private $tipo;
    /**
     * Informa se essa forma de pagamento estará disponível apenas nessa
     * integração
     */
    private $integracao_id;
    /**
     * Carteira que será usada para entrada de valores no caixa
     */
    private $carteira_id;
    /**
     * Descrição da forma de pagamento
     */
    private $descricao;
    /**
     * Quantidade mínima de parcelas
     */
    private $min_parcelas;
    /**
     * Quantidade máxima de parcelas
     */
    private $max_parcelas;
    /**
     * Quantidade de parcelas em que não será cobrado juros
     */
    private $parcelas_sem_juros;
    /**
     * Juros cobrado ao cliente no parcelamento
     */
    private $juros;
    /**
     * Informa se a forma de pagamento está ativa
     */
    private $ativa;

    /**
     * Constructor for a new empty instance of FormaPagto
     * @param array $forma_pagto All field and values to fill the instance
     */
    public function __construct($forma_pagto = [])
    {
        parent::__construct($forma_pagto);
    }

    /**
     * Identificador da forma de pagamento
     * @return int id of Forma de pagamento
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param int $id Set id for Forma de pagamento
     * @return self Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Tipo de pagamento
     * @return string tipo of Forma de pagamento
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set Tipo value to new on param
     * @param string $tipo Set tipo for Forma de pagamento
     * @return self Self instance
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
        return $this;
    }

    /**
     * Informa se essa forma de pagamento estará disponível apenas nessa
     * integração
     * @return int integração of Forma de pagamento
     */
    public function getIntegracaoID()
    {
        return $this->integracao_id;
    }

    /**
     * Set IntegracaoID value to new on param
     * @param int $integracao_id Set integração for Forma de pagamento
     * @return self Self instance
     */
    public function setIntegracaoID($integracao_id)
    {
        $this->integracao_id = $integracao_id;
        return $this;
    }

    /**
     * Carteira que será usada para entrada de valores no caixa
     * @return int carteira de entrada of Forma de pagamento
     */
    public function getCarteiraID()
    {
        return $this->carteira_id;
    }

    /**
     * Set CarteiraID value to new on param
     * @param int $carteira_id Set carteira de entrada for Forma de pagamento
     * @return self Self instance
     */
    public function setCarteiraID($carteira_id)
    {
        $this->carteira_id = $carteira_id;
        return $this;
    }

    /**
     * Descrição da forma de pagamento
     * @return string descrição of Forma de pagamento
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Set Descricao value to new on param
     * @param string $descricao Set descrição for Forma de pagamento
     * @return self Self instance
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * Quantidade mínima de parcelas
     * @return int minimo de parcelas of Forma de pagamento
     */
    public function getMinParcelas()
    {
        return $this->min_parcelas;
    }

    /**
     * Set MinParcelas value to new on param
     * @param int $min_parcelas Set minimo de parcelas for Forma de pagamento
     * @return self Self instance
     */
    public function setMinParcelas($min_parcelas)
    {
        $this->min_parcelas = $min_parcelas;
        return $this;
    }

    /**
     * Quantidade máxima de parcelas
     * @return int máximo de parcelas of Forma de pagamento
     */
    public function getMaxParcelas()
    {
        return $this->max_parcelas;
    }

    /**
     * Set MaxParcelas value to new on param
     * @param int $max_parcelas Set máximo de parcelas for Forma de pagamento
     * @return self Self instance
     */
    public function setMaxParcelas($max_parcelas)
    {
        $this->max_parcelas = $max_parcelas;
        return $this;
    }

    /**
     * Quantidade de parcelas em que não será cobrado juros
     * @return int parcelas sem juros of Forma de pagamento
     */
    public function getParcelasSemJuros()
    {
        return $this->parcelas_sem_juros;
    }

    /**
     * Set ParcelasSemJuros value to new on param
     * @param int $parcelas_sem_juros Set parcelas sem juros for Forma de pagamento
     * @return self Self instance
     */
    public function setParcelasSemJuros($parcelas_sem_juros)
    {
        $this->parcelas_sem_juros = $parcelas_sem_juros;
        return $this;
    }

    /**
     * Juros cobrado ao cliente no parcelamento
     * @return float juros of Forma de pagamento
     */
    public function getJuros()
    {
        return $this->juros;
    }

    /**
     * Set Juros value to new on param
     * @param float $juros Set juros for Forma de pagamento
     * @return self Self instance
     */
    public function setJuros($juros)
    {
        $this->juros = $juros;
        return $this;
    }

    /**
     * Informa se a forma de pagamento está ativa
     * @return string ativa of Forma de pagamento
     */
    public function getAtiva()
    {
        return $this->ativa;
    }

    /**
     * Informa se a forma de pagamento está ativa
     * @return boolean Check if a of Ativa is selected or checked
     */
    public function isAtiva()
    {
        return $this->ativa == 'Y';
    }

    /**
     * Set Ativa value to new on param
     * @param string $ativa Set ativa for Forma de pagamento
     * @return self Self instance
     */
    public function setAtiva($ativa)
    {
        $this->ativa = $ativa;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $forma_pagto = parent::toArray($recursive);
        $forma_pagto['id'] = $this->getID();
        $forma_pagto['tipo'] = $this->getTipo();
        $forma_pagto['integracaoid'] = $this->getIntegracaoID();
        $forma_pagto['carteiraid'] = $this->getCarteiraID();
        $forma_pagto['descricao'] = $this->getDescricao();
        $forma_pagto['minparcelas'] = $this->getMinParcelas();
        $forma_pagto['maxparcelas'] = $this->getMaxParcelas();
        $forma_pagto['parcelassemjuros'] = $this->getParcelasSemJuros();
        $forma_pagto['juros'] = $this->getJuros();
        $forma_pagto['ativa'] = $this->getAtiva();
        return $forma_pagto;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param mixed $forma_pagto Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($forma_pagto = [])
    {
        if ($forma_pagto instanceof self) {
            $forma_pagto = $forma_pagto->toArray();
        } elseif (!is_array($forma_pagto)) {
            $forma_pagto = [];
        }
        parent::fromArray($forma_pagto);
        if (!isset($forma_pagto['id'])) {
            $this->setID(null);
        } else {
            $this->setID($forma_pagto['id']);
        }
        if (!isset($forma_pagto['tipo'])) {
            $this->setTipo(null);
        } else {
            $this->setTipo($forma_pagto['tipo']);
        }
        if (!array_key_exists('integracaoid', $forma_pagto)) {
            $this->setIntegracaoID(null);
        } else {
            $this->setIntegracaoID($forma_pagto['integracaoid']);
        }
        if (!isset($forma_pagto['carteiraid'])) {
            $this->setCarteiraID(null);
        } else {
            $this->setCarteiraID($forma_pagto['carteiraid']);
        }
        if (!isset($forma_pagto['descricao'])) {
            $this->setDescricao(null);
        } else {
            $this->setDescricao($forma_pagto['descricao']);
        }
        if (!array_key_exists('minparcelas', $forma_pagto)) {
            $this->setMinParcelas(null);
        } else {
            $this->setMinParcelas($forma_pagto['minparcelas']);
        }
        if (!array_key_exists('maxparcelas', $forma_pagto)) {
            $this->setMaxParcelas(null);
        } else {
            $this->setMaxParcelas($forma_pagto['maxparcelas']);
        }
        if (!array_key_exists('parcelassemjuros', $forma_pagto)) {
            $this->setParcelasSemJuros(null);
        } else {
            $this->setParcelasSemJuros($forma_pagto['parcelassemjuros']);
        }
        if (!array_key_exists('juros', $forma_pagto)) {
            $this->setJuros(null);
        } else {
            $this->setJuros($forma_pagto['juros']);
        }
        if (!isset($forma_pagto['ativa'])) {
            $this->setAtiva('N');
        } else {
            $this->setAtiva($forma_pagto['ativa']);
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
        $forma_pagto = parent::publish($requester);
        return $forma_pagto;
    }

    /**
     * Informa se a forma de pagamento permite parcelamento
     * @return boolean true se permite parcelamento
     */
    public function isParcelado()
    {
        return $this->getTipo() == self::TIPO_CREDITO;
    }

    /**
     * Informa se a forma de pagamento usa cartão
     * @return boolean true se usa cartão
     */
    public function usaCartao()
    {
        return $this->getTipo() == self::TIPO_CREDITO ||
            $this->getTipo() == self::TIPO_DEBITO ||
            $this->getTipo() == self::TIPO_VALE;
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
        $this->setIntegracaoID(Filter::number($this->getIntegracaoID()));
        $this->setCarteiraID(Filter::number($this->getCarteiraID()));
        $this->setDescricao(Filter::string($this->getDescricao()));
        $this->setMinParcelas(Filter::number($this->getMinParcelas()));
        $this->setMaxParcelas(Filter::number($this->getMaxParcelas()));
        $this->setParcelasSemJuros(Filter::number($this->getParcelasSemJuros()));
        $this->setJuros(Filter::float($this->getJuros(), $localized));
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
     * @return array All field of FormaPagto in array format
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function validate()
    {
        $errors = [];
        $count = $this->exists() ? Pagamento::count(['formapagtoid' => $this->getID()]) : 0;
        $old = self::findByID($this->getID());
        if (!Validator::checkInSet($this->getTipo(), self::getTipoOptions())) {
            $errors['tipo'] = _t('forma_pagto.tipo_invalid');
        } elseif ($old->exists() && $count > 0 && $old->getTipo() != $this->getTipo()) {
            $errors['tipo'] = _t('forma_pagto.tipo_cannot_change');
        }
        if (is_null($this->getCarteiraID())) {
            $errors['carteiraid'] = _t('forma_pagto.carteira_id_cannot_empty');
        }
        if (is_null($this->getDescricao())) {
            $errors['descricao'] = _t('forma_pagto.descricao_cannot_empty');
        }
        if (!is_null($this->getMinParcelas()) && $this->getMinParcelas() < 0) {
            $errors['minparcelas'] = 'O mínimo de parcelas não pode ser negativo';
        }
        if (!is_null($this->getMaxParcelas()) && $this->getMaxParcelas() < 0) {
            $errors['maxparcelas'] = 'O máximo de parcelas não pode ser negativo';
        }
        if (!is_null($this->getMinParcelas()) &&
            !is_null($this->getMaxParcelas()) &&
            $this->getMinParcelas() > $this->getMaxParcelas()
        ) {
            $errors['maxparcelas'] = 'O máximo de parcelas não pode ser menor que o mínimo de parcelas';
        }
        if (!is_null($this->getParcelasSemJuros()) && $this->getParcelasSemJuros() < 0) {
            $errors['parcelassemjuros'] = 'As parcelas sem juros não podem ser negativas';
        }
        if (!is_null($this->getParcelasSemJuros()) &&
            !is_null($this->getMinParcelas()) &&
            $this->getParcelasSemJuros() < $this->getMinParcelas()
        ) {
            $errors['parcelassemjuros'] = 'As parcelas sem juros não podem ser menores que o mínimo de parcelas';
        }
        if (!is_null($this->getJuros()) && $this->getJuros() < 0) {
            $errors['juros'] = 'O juros não pode ser negativo';
        }
        if (!Validator::checkBoolean($this->getAtiva())) {
            $errors['ativa'] = _t('forma_pagto.ativa_invalid');
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
                    'forma_pagto.descricao_used',
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
     * Informa se essa forma de pagamento estará disponível apenas nessa
     * integração
     * @return \MZ\System\Integracao The object fetched from database
     */
    public function findIntegracaoID()
    {
        return \MZ\System\Integracao::findByID($this->getIntegracaoID());
    }

    /**
     * Carteira que será usada para entrada de valores no caixa
     * @return \MZ\Wallet\Carteira The object fetched from database
     */
    public function findCarteiraID()
    {
        return \MZ\Wallet\Carteira::findByID($this->getCarteiraID());
    }

    /**
     * Gets textual and translated Tipo for FormaPagto
     * @param int $index choose option from index
     * @return string[] A associative key -> translated representative text or text for index
     */
    public static function getTipoOptions($index = null)
    {
        $options = [
            self::TIPO_DINHEIRO => _t('forma_pagto.tipo_dinheiro'),
            self::TIPO_CREDITO => _t('forma_pagto.tipo_credito'),
            self::TIPO_DEBITO => _t('forma_pagto.tipo_debito'),
            self::TIPO_VALE => _t('forma_pagto.tipo_vale'),
            self::TIPO_CHEQUE => _t('forma_pagto.tipo_cheque'),
            self::TIPO_CREDIARIO => _t('forma_pagto.tipo_crediario'),
            self::TIPO_SALDO => _t('forma_pagto.tipo_saldo'),
        ];
        if (!is_null($index)) {
            return $options[$index];
        }
        return $options;
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
        $query = DB::from('Formas_Pagto f');
        $condition = $this->filterCondition($condition);
        $query = DB::buildOrderBy($query, $this->filterOrder($order));
        $query = $query->orderBy('f.ativa ASC');
        $query = $query->orderBy('f.descricao ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Find this object on database using, Descricao
     * @param string $descricao descrição to find Forma de pagamento
     * @return self A filled instance or empty when not found
     */
    public static function findByDescricao($descricao)
    {
        $result = new self();
        $result->setDescricao($descricao);
        return $result->loadByDescricao();
    }
}
