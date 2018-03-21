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
 * @author  Francimar Alves <mazinsw@gmail.com>
 */
namespace MZ\Payment;

use MZ\Util\Filter;
use MZ\Util\Validator;

/**
 * Cartões utilizados na forma de pagamento em cartão
 */
class Cartao extends \MZ\Database\Helper
{

    /**
     * Identificador do cartão
     */
    private $id;
    /**
     * Carteira de entrada de valores no caixa
     */
    private $carteira_id;
    /**
     * Carteira de saída de pagamentos no caixa
     */
    private $carteira_pagto_id;
    /**
     * Descrição do cartão
     */
    private $descricao;
    /**
     * Índice da imagem do cartão
     */
    private $image_index;
    /**
     * Valor da mensalidade cobrada pela operadora do cartão
     */
    private $mensalidade;
    /**
     * Valor cobrado pela operadora para cada transação com o cartão
     */
    private $transacao;
    /**
     * Taxa em porcentagem cobrado sobre o total do pagamento, valores de 0 a
     * 100
     */
    private $taxa;
    /**
     * Quantidade de dias para repasse do valor
     */
    private $dias_repasse;
    /**
     * Informa se o cartão está ativo
     */
    private $ativo;

    /**
     * Constructor for a new empty instance of Cartao
     * @param array $cartao All field and values to fill the instance
     */
    public function __construct($cartao = [])
    {
        parent::__construct($cartao);
    }

    /**
     * Identificador do cartão
     * @return mixed ID of Cartao
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param  mixed $id new value for ID
     * @return Cartao Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Carteira de entrada de valores no caixa
     * @return mixed Carteira de entrada of Cartao
     */
    public function getCarteiraID()
    {
        return $this->carteira_id;
    }

    /**
     * Set CarteiraID value to new on param
     * @param  mixed $carteira_id new value for CarteiraID
     * @return Cartao Self instance
     */
    public function setCarteiraID($carteira_id)
    {
        $this->carteira_id = $carteira_id;
        return $this;
    }

    /**
     * Carteira de saída de pagamentos no caixa
     * @return mixed Carteira de saída of Cartao
     */
    public function getCarteiraPagtoID()
    {
        return $this->carteira_pagto_id;
    }

    /**
     * Set CarteiraPagtoID value to new on param
     * @param  mixed $carteira_pagto_id new value for CarteiraPagtoID
     * @return Cartao Self instance
     */
    public function setCarteiraPagtoID($carteira_pagto_id)
    {
        $this->carteira_pagto_id = $carteira_pagto_id;
        return $this;
    }

    /**
     * Descrição do cartão
     * @return mixed Descrição of Cartao
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Set Descricao value to new on param
     * @param  mixed $descricao new value for Descricao
     * @return Cartao Self instance
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * Índice da imagem do cartão
     * @return mixed Índice da imagem of Cartao
     */
    public function getImageIndex()
    {
        return $this->image_index;
    }

    /**
     * Set ImageIndex value to new on param
     * @param  mixed $image_index new value for ImageIndex
     * @return Cartao Self instance
     */
    public function setImageIndex($image_index)
    {
        $this->image_index = $image_index;
        return $this;
    }

    /**
     * Valor da mensalidade cobrada pela operadora do cartão
     * @return mixed Mensalidade of Cartao
     */
    public function getMensalidade()
    {
        return $this->mensalidade;
    }

    /**
     * Set Mensalidade value to new on param
     * @param  mixed $mensalidade new value for Mensalidade
     * @return Cartao Self instance
     */
    public function setMensalidade($mensalidade)
    {
        $this->mensalidade = $mensalidade;
        return $this;
    }

    /**
     * Valor cobrado pela operadora para cada transação com o cartão
     * @return mixed Transação of Cartao
     */
    public function getTransacao()
    {
        return $this->transacao;
    }

    /**
     * Set Transacao value to new on param
     * @param  mixed $transacao new value for Transacao
     * @return Cartao Self instance
     */
    public function setTransacao($transacao)
    {
        $this->transacao = $transacao;
        return $this;
    }

    /**
     * Taxa em porcentagem cobrado sobre o total do pagamento, valores de 0 a
     * 100
     * @return mixed Taxa of Cartao
     */
    public function getTaxa()
    {
        return $this->taxa;
    }

    /**
     * Set Taxa value to new on param
     * @param  mixed $taxa new value for Taxa
     * @return Cartao Self instance
     */
    public function setTaxa($taxa)
    {
        $this->taxa = $taxa;
        return $this;
    }

    /**
     * Quantidade de dias para repasse do valor
     * @return mixed Dias para repasse of Cartao
     */
    public function getDiasRepasse()
    {
        return $this->dias_repasse;
    }

    /**
     * Set DiasRepasse value to new on param
     * @param  mixed $dias_repasse new value for DiasRepasse
     * @return Cartao Self instance
     */
    public function setDiasRepasse($dias_repasse)
    {
        $this->dias_repasse = $dias_repasse;
        return $this;
    }

    /**
     * Informa se o cartão está ativo
     * @return mixed Ativo of Cartao
     */
    public function getAtivo()
    {
        return $this->ativo;
    }

    /**
     * Informa se o cartão está ativo
     * @return boolean Check if o of Ativo is selected or checked
     */
    public function isAtivo()
    {
        return $this->ativo == 'Y';
    }

    /**
     * Set Ativo value to new on param
     * @param  mixed $ativo new value for Ativo
     * @return Cartao Self instance
     */
    public function setAtivo($ativo)
    {
        $this->ativo = $ativo;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param  boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $cartao = parent::toArray($recursive);
        $cartao['id'] = $this->getID();
        $cartao['carteiraid'] = $this->getCarteiraID();
        $cartao['carteirapagtoid'] = $this->getCarteiraPagtoID();
        $cartao['descricao'] = $this->getDescricao();
        $cartao['imageindex'] = $this->getImageIndex();
        $cartao['mensalidade'] = $this->getMensalidade();
        $cartao['transacao'] = $this->getTransacao();
        $cartao['taxa'] = $this->getTaxa();
        $cartao['diasrepasse'] = $this->getDiasRepasse();
        $cartao['ativo'] = $this->getAtivo();
        return $cartao;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param  mixed $cartao Associated key -> value to assign into this instance
     * @return Cartao Self instance
     */
    public function fromArray($cartao = [])
    {
        if ($cartao instanceof Cartao) {
            $cartao = $cartao->toArray();
        } elseif (!is_array($cartao)) {
            $cartao = [];
        }
        parent::fromArray($cartao);
        if (!isset($cartao['id'])) {
            $this->setID(null);
        } else {
            $this->setID($cartao['id']);
        }
        if (!array_key_exists('carteiraid', $cartao)) {
            $this->setCarteiraID(null);
        } else {
            $this->setCarteiraID($cartao['carteiraid']);
        }
        if (!array_key_exists('carteirapagtoid', $cartao)) {
            $this->setCarteiraPagtoID(null);
        } else {
            $this->setCarteiraPagtoID($cartao['carteirapagtoid']);
        }
        if (!isset($cartao['descricao'])) {
            $this->setDescricao(null);
        } else {
            $this->setDescricao($cartao['descricao']);
        }
        if (!array_key_exists('imageindex', $cartao)) {
            $this->setImageIndex(null);
        } else {
            $this->setImageIndex($cartao['imageindex']);
        }
        if (!isset($cartao['mensalidade'])) {
            $this->setMensalidade(null);
        } else {
            $this->setMensalidade($cartao['mensalidade']);
        }
        if (!isset($cartao['transacao'])) {
            $this->setTransacao(null);
        } else {
            $this->setTransacao($cartao['transacao']);
        }
        if (!isset($cartao['taxa'])) {
            $this->setTaxa(null);
        } else {
            $this->setTaxa($cartao['taxa']);
        }
        if (!isset($cartao['diasrepasse'])) {
            $this->setDiasRepasse(null);
        } else {
            $this->setDiasRepasse($cartao['diasrepasse']);
        }
        if (!isset($cartao['ativo'])) {
            $this->setAtivo(null);
        } else {
            $this->setAtivo($cartao['ativo']);
        }
        return $this;
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $cartao = parent::publish();
        return $cartao;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param Cartao $original Original instance without modifications
     */
    public function filter($original)
    {
        $this->setID($original->getID());
        $this->setCarteiraID(Filter::number($this->getCarteiraID()));
        $this->setCarteiraPagtoID(Filter::number($this->getCarteiraPagtoID()));
        $this->setDescricao(Filter::string($this->getDescricao()));
        $this->setImageIndex(Filter::number($this->getImageIndex()));
        $this->setMensalidade(Filter::money($this->getMensalidade()));
        $this->setTransacao(Filter::money($this->getTransacao()));
        $this->setTaxa(Filter::float($this->getTaxa()));
        $this->setDiasRepasse(Filter::number($this->getDiasRepasse()));
    }

    /**
     * Clean instance resources like images and docs
     * @param  Cartao $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Cartao in array format
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getDescricao())) {
            $errors['descricao'] = 'A descrição não pode ser vazia';
        }
        if (is_null($this->getMensalidade())) {
            $errors['mensalidade'] = 'A mensalidade não pode ser vazia';
        }
        if (is_null($this->getTransacao())) {
            $errors['transacao'] = 'A transação não pode ser vazia';
        }
        if (is_null($this->getTaxa())) {
            $errors['taxa'] = 'A taxa não pode ser vazia';
        }
        if (is_null($this->getDiasRepasse())) {
            $errors['diasrepasse'] = 'O dias para repasse não pode ser vazio';
        }
        if (is_null($this->getAtivo())) {
            $errors['ativo'] = 'O ativo não pode ser vazio';
        }
        if (!is_null($this->getAtivo()) &&
            !array_key_exists($this->getAtivo(), self::getBooleanOptions())
        ) {
            $errors['ativo'] = 'O ativo é inválido';
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
        if (stripos($e->getMessage(), 'PRIMARY') !== false) {
            return new \MZ\Exception\ValidationException([
                'id' => sprintf(
                    'O id "%s" já está cadastrado',
                    $this->getID()
                ),
            ]);
        }
        if (stripos($e->getMessage(), 'Descricao_UNIQUE') !== false) {
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
     * Find this object on database using, ID
     * @param  int $id id to find Cartão
     * @return Cartao A filled instance or empty when not found
     */
    public static function findByID($id)
    {
        return self::find([
            'id' => intval($id),
        ]);
    }

    /**
     * Find this object on database using, Descricao
     * @param  string $descricao descrição to find Cartão
     * @return Cartao A filled instance or empty when not found
     */
    public static function findByDescricao($descricao)
    {
        return self::find([
            'descricao' => strval($descricao),
        ]);
    }

    /**
     * Get allowed keys array
     * @return array allowed keys array
     */
    private static function getAllowedKeys()
    {
        $cartao = new Cartao();
        $allowed = Filter::concatKeys('c.', $cartao->toArray());
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
        return Filter::orderBy($order, $allowed, 'c.');
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
            $field = 'c.descricao LIKE ?';
            $condition[$field] = '%'.$search.'%';
            $allowed[$field] = true;
            unset($condition['search']);
        }
        return Filter::keys($condition, $allowed, 'c.');
    }

    /**
     * Fetch data from database with a condition
     * @param  array $condition condition to filter rows
     * @param  array $order order rows
     * @return SelectQuery query object with condition statement
     */
    private static function query($condition = [], $order = [])
    {
        $query = self::getDB()->from('Cartoes c');
        $condition = self::filterCondition($condition);
        $query = self::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('c.descricao ASC');
        $query = $query->orderBy('c.id ASC');
        return self::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order order rows
     * @return Cartao A filled Cartão or empty instance
     */
    public static function find($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch();
        if ($row === false) {
            $row = [];
        }
        return new Cartao($row);
    }

    /**
     * Fetch all rows from database with matched condition critery
     * @param  array $condition condition to filter rows
     * @param  integer $limit number of rows to get, null for all
     * @param  integer $offset start index to get rows, null for begining
     * @return array All rows instanced and filled
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
            $result[] = new Cartao($row);
        }
        return $result;
    }

    /**
     * Insert a new Cartão into the database and fill instance from database
     * @return Cartao Self instance
     */
    public function insert()
    {
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = self::getDB()->insertInto('Cartoes')->values($values)->execute();
            $cartao = self::findByID($id);
            $this->fromArray($cartao->toArray());
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Cartão with instance values into database for ID
     * @return Cartao Self instance
     */
    public function update()
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador do cartão não foi informado');
        }
        unset($values['id']);
        try {
            self::getDB()
                ->update('Cartoes')
                ->set($values)
                ->where('id', $this->getID())
                ->execute();
            $cartao = self::findByID($this->getID());
            $this->fromArray($cartao->toArray());
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Save the Cartão into the database
     * @return Cartao Self instance
     */
    public function save()
    {
        if ($this->exists()) {
            return $this->update();
        }
        return $this->insert();
    }

    /**
     * Delete this instance from database using ID
     * @return integer Number of rows deleted (Max 1)
     */
    public function delete()
    {
        if (!$this->exists()) {
            throw new \Exception('O identificador do cartão não foi informado');
        }
        $result = self::getDB()
            ->deleteFrom('Cartoes')
            ->where('id', $this->getID())
            ->execute();
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

    /**
     * Carteira de entrada de valores no caixa
     * @return \MZ\Wallet\Carteira The object fetched from database
     */
    public function findCarteiraID()
    {
        if (is_null($this->getCarteiraID())) {
            return new \MZ\Wallet\Carteira();
        }
        return \MZ\Wallet\Carteira::findByID($this->getCarteiraID());
    }

    /**
     * Carteira de saída de pagamentos no caixa
     * @return \MZ\Wallet\Carteira The object fetched from database
     */
    public function findCarteiraPagtoID()
    {
        if (is_null($this->getCarteiraPagtoID())) {
            return new \MZ\Wallet\Carteira();
        }
        return \MZ\Wallet\Carteira::findByID($this->getCarteiraPagtoID());
    }
}
