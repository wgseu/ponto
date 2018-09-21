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

use MZ\Database\SyncModel;
use MZ\Database\DB;
use MZ\Util\Filter;
use MZ\Util\Validator;

/**
 * Formas de pagamento disponíveis para pedido e contas
 */
class FormaPagto extends SyncModel
{

    /**
     * Tipo de pagamento
     */
    const TIPO_DINHEIRO = 'Dinheiro';
    const TIPO_CARTAO = 'Cartao';
    const TIPO_CHEQUE = 'Cheque';
    const TIPO_CONTA = 'Conta';
    const TIPO_CREDITO = 'Credito';
    const TIPO_TRANSFERENCIA = 'Transferencia';

    /**
     * Identificador da forma de pagamento
     */
    private $id;
    /**
     * Tipo de pagamento
     */
    private $tipo;
    /**
     * Carteira que será usada para entrada de valores no caixa
     */
    private $carteira_id;
    /**
     * Carteira de saída de valores do caixa
     */
    private $carteira_pagto_id;
    /**
     * Descrição da forma de pagamento
     */
    private $descricao;
    /**
     * Informa se a forma de pagamento permite parcelamento
     */
    private $parcelado;
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
     * @return mixed ID of FormaPagto
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param  mixed $id new value for ID
     * @return FormaPagto Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Tipo de pagamento
     * @return mixed Tipo of FormaPagto
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set Tipo value to new on param
     * @param  mixed $tipo new value for Tipo
     * @return FormaPagto Self instance
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
        return $this;
    }

    /**
     * Carteira que será usada para entrada de valores no caixa
     * @return mixed Carteira de entrada of FormaPagto
     */
    public function getCarteiraID()
    {
        return $this->carteira_id;
    }

    /**
     * Set CarteiraID value to new on param
     * @param  mixed $carteira_id new value for CarteiraID
     * @return FormaPagto Self instance
     */
    public function setCarteiraID($carteira_id)
    {
        $this->carteira_id = $carteira_id;
        return $this;
    }

    /**
     * Carteira de saída de valores do caixa
     * @return mixed Carteira de saída of FormaPagto
     */
    public function getCarteiraPagtoID()
    {
        return $this->carteira_pagto_id;
    }

    /**
     * Set CarteiraPagtoID value to new on param
     * @param  mixed $carteira_pagto_id new value for CarteiraPagtoID
     * @return FormaPagto Self instance
     */
    public function setCarteiraPagtoID($carteira_pagto_id)
    {
        $this->carteira_pagto_id = $carteira_pagto_id;
        return $this;
    }

    /**
     * Descrição da forma de pagamento
     * @return mixed Descrição of FormaPagto
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Set Descricao value to new on param
     * @param  mixed $descricao new value for Descricao
     * @return FormaPagto Self instance
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * Informa se a forma de pagamento permite parcelamento
     * @return mixed Parcelado of FormaPagto
     */
    public function getParcelado()
    {
        return $this->parcelado;
    }

    /**
     * Informa se a forma de pagamento permite parcelamento
     * @return boolean Check if o of Parcelado is selected or checked
     */
    public function isParcelado()
    {
        return $this->parcelado == 'Y';
    }

    /**
     * Set Parcelado value to new on param
     * @param  mixed $parcelado new value for Parcelado
     * @return FormaPagto Self instance
     */
    public function setParcelado($parcelado)
    {
        $this->parcelado = $parcelado;
        return $this;
    }

    /**
     * Quantidade mínima de parcelas
     * @return mixed Minimo de parcelas of FormaPagto
     */
    public function getMinParcelas()
    {
        return $this->min_parcelas;
    }

    /**
     * Set MinParcelas value to new on param
     * @param  mixed $min_parcelas new value for MinParcelas
     * @return FormaPagto Self instance
     */
    public function setMinParcelas($min_parcelas)
    {
        $this->min_parcelas = $min_parcelas;
        return $this;
    }

    /**
     * Quantidade máxima de parcelas
     * @return mixed Máximo de parcelas of FormaPagto
     */
    public function getMaxParcelas()
    {
        return $this->max_parcelas;
    }

    /**
     * Set MaxParcelas value to new on param
     * @param  mixed $max_parcelas new value for MaxParcelas
     * @return FormaPagto Self instance
     */
    public function setMaxParcelas($max_parcelas)
    {
        $this->max_parcelas = $max_parcelas;
        return $this;
    }

    /**
     * Quantidade de parcelas em que não será cobrado juros
     * @return mixed Parcelas sem juros of FormaPagto
     */
    public function getParcelasSemJuros()
    {
        return $this->parcelas_sem_juros;
    }

    /**
     * Set ParcelasSemJuros value to new on param
     * @param  mixed $parcelas_sem_juros new value for ParcelasSemJuros
     * @return FormaPagto Self instance
     */
    public function setParcelasSemJuros($parcelas_sem_juros)
    {
        $this->parcelas_sem_juros = $parcelas_sem_juros;
        return $this;
    }

    /**
     * Juros cobrado ao cliente no parcelamento
     * @return mixed Juros of FormaPagto
     */
    public function getJuros()
    {
        return $this->juros;
    }

    /**
     * Set Juros value to new on param
     * @param  mixed $juros new value for Juros
     * @return FormaPagto Self instance
     */
    public function setJuros($juros)
    {
        $this->juros = $juros;
        return $this;
    }

    /**
     * Informa se a forma de pagamento está ativa
     * @return mixed Ativa of FormaPagto
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
     * @param  mixed $ativa new value for Ativa
     * @return FormaPagto Self instance
     */
    public function setAtiva($ativa)
    {
        $this->ativa = $ativa;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param  boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $forma_pagto = parent::toArray($recursive);
        $forma_pagto['id'] = $this->getID();
        $forma_pagto['tipo'] = $this->getTipo();
        $forma_pagto['carteiraid'] = $this->getCarteiraID();
        $forma_pagto['carteirapagtoid'] = $this->getCarteiraPagtoID();
        $forma_pagto['descricao'] = $this->getDescricao();
        $forma_pagto['parcelado'] = $this->getParcelado();
        $forma_pagto['minparcelas'] = $this->getMinParcelas();
        $forma_pagto['maxparcelas'] = $this->getMaxParcelas();
        $forma_pagto['parcelassemjuros'] = $this->getParcelasSemJuros();
        $forma_pagto['juros'] = $this->getJuros();
        $forma_pagto['ativa'] = $this->getAtiva();
        return $forma_pagto;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param  mixed $forma_pagto Associated key -> value to assign into this instance
     * @return FormaPagto Self instance
     */
    public function fromArray($forma_pagto = [])
    {
        if ($forma_pagto instanceof FormaPagto) {
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
        if (!isset($forma_pagto['carteiraid'])) {
            $this->setCarteiraID(null);
        } else {
            $this->setCarteiraID($forma_pagto['carteiraid']);
        }
        if (!isset($forma_pagto['carteirapagtoid'])) {
            $this->setCarteiraPagtoID(null);
        } else {
            $this->setCarteiraPagtoID($forma_pagto['carteirapagtoid']);
        }
        if (!isset($forma_pagto['descricao'])) {
            $this->setDescricao(null);
        } else {
            $this->setDescricao($forma_pagto['descricao']);
        }
        if (!isset($forma_pagto['parcelado'])) {
            $this->setParcelado(null);
        } else {
            $this->setParcelado($forma_pagto['parcelado']);
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
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $forma_pagto = parent::publish();
        return $forma_pagto;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param FormaPagto $original Original instance without modifications
     */
    public function filter($original, $localized = false)
    {
        $this->setID($original->getID());
        $this->setCarteiraID(Filter::number($this->getCarteiraID()));
        $this->setCarteiraPagtoID(Filter::number($this->getCarteiraPagtoID()));
        $this->setDescricao(Filter::string($this->getDescricao()));
        $this->setMinParcelas(Filter::number($this->getMinParcelas()));
        $this->setMaxParcelas(Filter::number($this->getMaxParcelas()));
        $this->setParcelasSemJuros(Filter::number($this->getParcelasSemJuros()));
        $this->setJuros(Filter::float($this->getJuros(), $localized));
    }

    /**
     * Clean instance resources like images and docs
     * @param  FormaPagto $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of FormaPagto in array format
     */
    public function validate()
    {
        $errors = [];
        if (!Validator::checkInSet($this->getTipo(), self::getTipoOptions())) {
            $errors['tipo'] = 'O tipo não foi informado ou é inválido';
        }
        if (is_null($this->getCarteiraID())) {
            $errors['carteiraid'] = 'A carteira de entrada não pode ser vazia';
        }
        if (is_null($this->getCarteiraPagtoID())) {
            $errors['carteirapagtoid'] = 'A carteira de saída não pode ser vazia';
        }
        if (is_null($this->getDescricao())) {
            $errors['descricao'] = 'A descrição não pode ser vazia';
        }
        if (in_array($this->getTipo(), [self::TIPO_CARTAO, self::TIPO_CHEQUE])) {
            $this->setParcelado('Y');
        } else {
            $this->setParcelado('N');
        }
        if (!Validator::checkBoolean($this->getParcelado())) {
            $errors['parcelado'] = 'O parcelamento não foi informado ou é inválido';
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
            $errors['ativa'] = 'A ativação não foi informada ou é inválida';
        }
        if (!empty($errors)) {
            throw new \MZ\Exception\ValidationException($errors);
        }
        return $this->toArray();
    }

    /**
     * Translate SQL exception into application exception
     * @param  \Exception $e exception to translate into a readable error
     * @return \MZ\Exception\ValidationException new exception translated
     */
    protected function translate($e)
    {
        if (contains(['Descricao', 'UNIQUE'], $e->getMessage())) {
            return new \MZ\Exception\ValidationException([
                'descricao' => sprintf(
                    'A descrição "%s" já está cadastrada',
                    $this->getDescricao()
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Insert a new Forma de pagamento into the database and fill instance from database
     * @return FormaPagto Self instance
     */
    public function insert()
    {
        $this->setID(null);
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = DB::insertInto('Formas_Pagto')->values($values)->execute();
            $this->setID($id);
            $this->loadByID();
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Forma de pagamento with instance values into database for ID
     * @return FormaPagto Self instance
     */
    public function update($only = [])
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador da forma de pagamento não foi informado');
        }
        $values = DB::filterValues($values, $only, false);
        try {
            DB::update('Formas_Pagto')
                ->set($values)
                ->where('id', $this->getID())
                ->execute();
            $this->loadByID();
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Delete this instance from database using ID
     * @return integer Number of rows deleted (Max 1)
     */
    public function delete()
    {
        if (!$this->exists()) {
            throw new \Exception('O identificador da forma de pagamento não foi informado');
        }
        $result = DB::deleteFrom('Formas_Pagto')
            ->where('id', $this->getID())
            ->execute();
        return $result;
    }

    /**
     * Load one register for it self with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order associative field name -> [-1, 1]
     * @return FormaPagto Self instance filled or empty
     */
    public function load($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return $this->fromArray($row);
    }

    /**
     * Load into this object from database using, Descricao
     * @param  string $descricao descrição to find Forma de pagamento
     * @return FormaPagto Self filled instance or empty when not found
     */
    public function loadByDescricao($descricao)
    {
        return $this->load([
            'descricao' => strval($descricao),
        ]);
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
     * Carteira de saída de valores do caixa
     * @return \MZ\Wallet\Carteira The object fetched from database
     */
    public function findCarteiraPagtoID()
    {
        return \MZ\Wallet\Carteira::findByID($this->getCarteiraPagtoID());
    }

    /**
     * Gets textual and translated Tipo for FormaPagto
     * @param  int $index choose option from index
     * @return mixed A associative key -> translated representative text or text for index
     */
    public static function getTipoOptions($index = null)
    {
        $options = [
            self::TIPO_DINHEIRO => 'Dinheiro',
            self::TIPO_CARTAO => 'Cartão',
            self::TIPO_CHEQUE => 'Cheque',
            self::TIPO_CONTA => 'Conta',
            self::TIPO_CREDITO => 'Crédito',
            self::TIPO_TRANSFERENCIA => 'Transferência',
        ];
        if (!is_null($index)) {
            return $options[$index];
        }
        return $options;
    }

    /**
     * Get allowed keys array
     * @return array allowed keys array
     */
    private static function getAllowedKeys()
    {
        $forma_pagto = new FormaPagto();
        $allowed = Filter::concatKeys('f.', $forma_pagto->toArray());
        return $allowed;
    }

    /**
     * Filter order array
     * @param  mixed $order order string or array to parse and filter allowed
     * @return array allowed associative order
     */
    private static function filterOrder($order)
    {
        $allowed = self::getAllowedKeys();
        return Filter::orderBy($order, $allowed, 'f.');
    }

    /**
     * Filter condition array with allowed fields
     * @param  array $condition condition to filter rows
     * @return array allowed condition
     */
    private static function filterCondition($condition)
    {
        $allowed = self::getAllowedKeys();
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
     * @param  array $condition condition to filter rows
     * @param  array $order order rows
     * @return SelectQuery query object with condition statement
     */
    private static function query($condition = [], $order = [])
    {
        $query = DB::from('Formas_Pagto f');
        $condition = self::filterCondition($condition);
        $query = DB::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('f.ativa ASC');
        $query = $query->orderBy('f.descricao ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order order rows
     * @return FormaPagto A filled Forma de pagamento or empty instance
     */
    public static function find($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return new FormaPagto($row);
    }

    /**
     * Find this object on database using, Descricao
     * @param  string $descricao descrição to find Forma de pagamento
     * @return FormaPagto A filled instance or empty when not found
     */
    public static function findByDescricao($descricao)
    {
        $result = new self();
        return $result->loadByDescricao($descricao);
    }

    /**
     * Find all Forma de pagamento
     * @param  array  $condition Condition to get all Forma de pagamento
     * @param  array  $order     Order Forma de pagamento
     * @param  int    $limit     Limit data into row count
     * @param  int    $offset    Start offset to get rows
     * @return array             List of all rows instanced as FormaPagto
     */
    public static function findAll($condition = [], $order = [], $limit = null, $offset = null)
    {
        $query = self::query($condition, $order);
        if (!is_null($limit)) {
            $query = $query->limit($limit);
        }
        if (!is_null($offset)) {
            $query = $query->offset($offset);
        }
        $rows = $query->fetchAll();
        $result = [];
        foreach ($rows as $row) {
            $result[] = new FormaPagto($row);
        }
        return $result;
    }

    /**
     * Count all rows from database with matched condition critery
     * @param  array $condition condition to filter rows
     * @return integer Quantity of rows
     */
    public static function count($condition = [])
    {
        $query = self::query($condition);
        return $query->count();
    }
}
