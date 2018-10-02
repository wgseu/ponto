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
namespace MZ\Product;

use MZ\Util\Mask;
use MZ\Util\Filter;
use MZ\Util\Validator;
use MZ\Database\DB;
use MZ\Database\SyncModel;
use MZ\Exception\ValidationException;

/**
 * Informa as propriedades da composição de um produto composto
 */
class Composicao extends SyncModel
{

    /**
     * Tipo de composição, 'Composicao' sempre retira do estoque, 'Opcional'
     * permite desmarcar na venda, 'Adicional' permite adicionar na venda
     */
    const TIPO_COMPOSICAO = 'Composicao';
    const TIPO_OPCIONAL = 'Opcional';
    const TIPO_ADICIONAL = 'Adicional';

    /**
     * Identificador da composição
     */
    private $id;
    /**
     * Informa a qual produto pertence essa composição, deve sempre ser um
     * produto do tipo Composição
     */
    private $composicao_id;
    /**
     * Produto ou composição que faz parte dessa composição, Obs: Não pode ser
     * um pacote
     */
    private $produto_id;
    /**
     * Tipo de composição, 'Composicao' sempre retira do estoque, 'Opcional'
     * permite desmarcar na venda, 'Adicional' permite adicionar na venda
     */
    private $tipo;
    /**
     * Quantidade que será consumida desse produto para cada composição formada
     */
    private $quantidade;
    /**
     * Desconto que será realizado ao retirar esse produto da composição no
     * momento da venda
     */
    private $valor;
    /**
     * Define a quantidade máxima que essa composição pode ser vendida
     * repetidamente
     */
    private $quantidade_maxima;
    /**
     * Indica se a composição está sendo usada atualmente na composição do
     * produto
     */
    private $ativa;

    /**
     * Constructor for a new empty instance of Composicao
     * @param array $composicao All field and values to fill the instance
     */
    public function __construct($composicao = [])
    {
        parent::__construct($composicao);
    }

    /**
     * Identificador da composição
     * @return int id of Composição
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param int $id Set id for Composição
     * @return self Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Informa a qual produto pertence essa composição, deve sempre ser um
     * produto do tipo Composição
     * @return int composição of Composição
     */
    public function getComposicaoID()
    {
        return $this->composicao_id;
    }

    /**
     * Set ComposicaoID value to new on param
     * @param int $composicao_id Set composição for Composição
     * @return self Self instance
     */
    public function setComposicaoID($composicao_id)
    {
        $this->composicao_id = $composicao_id;
        return $this;
    }

    /**
     * Produto ou composição que faz parte dessa composição, Obs: Não pode ser
     * um pacote
     * @return int produto da composição of Composição
     */
    public function getProdutoID()
    {
        return $this->produto_id;
    }

    /**
     * Set ProdutoID value to new on param
     * @param int $produto_id Set produto da composição for Composição
     * @return self Self instance
     */
    public function setProdutoID($produto_id)
    {
        $this->produto_id = $produto_id;
        return $this;
    }

    /**
     * Tipo de composição, 'Composicao' sempre retira do estoque, 'Opcional'
     * permite desmarcar na venda, 'Adicional' permite adicionar na venda
     * @return string tipo of Composição
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set Tipo value to new on param
     * @param string $tipo Set tipo for Composição
     * @return self Self instance
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
        return $this;
    }

    /**
     * Quantidade que será consumida desse produto para cada composição formada
     * @return float quantidade of Composição
     */
    public function getQuantidade()
    {
        return $this->quantidade;
    }

    /**
     * Set Quantidade value to new on param
     * @param float $quantidade Set quantidade for Composição
     * @return self Self instance
     */
    public function setQuantidade($quantidade)
    {
        $this->quantidade = $quantidade;
        return $this;
    }

    /**
     * Desconto que será realizado ao retirar esse produto da composição no
     * momento da venda
     * @return string valor of Composição
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Set Valor value to new on param
     * @param string $valor Set valor for Composição
     * @return self Self instance
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
        return $this;
    }

    /**
     * Define a quantidade máxima que essa composição pode ser vendida
     * repetidamente
     * @return int quantidade máxima of Composição
     */
    public function getQuantidadeMaxima()
    {
        return $this->quantidade_maxima;
    }

    /**
     * Set QuantidadeMaxima value to new on param
     * @param int $quantidade_maxima Set quantidade máxima for Composição
     * @return self Self instance
     */
    public function setQuantidadeMaxima($quantidade_maxima)
    {
        $this->quantidade_maxima = $quantidade_maxima;
        return $this;
    }

    /**
     * Indica se a composição está sendo usada atualmente na composição do
     * produto
     * @return string ativa of Composição
     */
    public function getAtiva()
    {
        return $this->ativa;
    }

    /**
     * Indica se a composição está sendo usada atualmente na composição do
     * produto
     * @return boolean Check if a of Ativa is selected or checked
     */
    public function isAtiva()
    {
        return $this->ativa == 'Y';
    }

    /**
     * Set Ativa value to new on param
     * @param string $ativa Set ativa for Composição
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
        $composicao = parent::toArray($recursive);
        $composicao['id'] = $this->getID();
        $composicao['composicaoid'] = $this->getComposicaoID();
        $composicao['produtoid'] = $this->getProdutoID();
        $composicao['tipo'] = $this->getTipo();
        $composicao['quantidade'] = $this->getQuantidade();
        $composicao['valor'] = $this->getValor();
        $composicao['quantidademaxima'] = $this->getQuantidadeMaxima();
        $composicao['ativa'] = $this->getAtiva();
        return $composicao;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param mixed $composicao Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($composicao = [])
    {
        if ($composicao instanceof self) {
            $composicao = $composicao->toArray();
        } elseif (!is_array($composicao)) {
            $composicao = [];
        }
        parent::fromArray($composicao);
        if (!isset($composicao['id'])) {
            $this->setID(null);
        } else {
            $this->setID($composicao['id']);
        }
        if (!isset($composicao['composicaoid'])) {
            $this->setComposicaoID(null);
        } else {
            $this->setComposicaoID($composicao['composicaoid']);
        }
        if (!isset($composicao['produtoid'])) {
            $this->setProdutoID(null);
        } else {
            $this->setProdutoID($composicao['produtoid']);
        }
        if (!isset($composicao['tipo'])) {
            $this->setTipo(null);
        } else {
            $this->setTipo($composicao['tipo']);
        }
        if (!isset($composicao['quantidade'])) {
            $this->setQuantidade(null);
        } else {
            $this->setQuantidade($composicao['quantidade']);
        }
        if (!isset($composicao['valor'])) {
            $this->setValor(null);
        } else {
            $this->setValor($composicao['valor']);
        }
        if (!isset($composicao['quantidademaxima'])) {
            $this->setQuantidadeMaxima(1);
        } else {
            $this->setQuantidadeMaxima($composicao['quantidademaxima']);
        }
        if (!isset($composicao['ativa'])) {
            $this->setAtiva('N');
        } else {
            $this->setAtiva($composicao['ativa']);
        }
        return $this;
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $composicao = parent::publish();
        return $composicao;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param self $original Original instance without modifications
     * @param boolean $localized Informs if fields are localized
     * @return self Self instance
     */
    public function filter($original, $localized = false)
    {
        $this->setID($original->getID());
        $this->setComposicaoID(Filter::number($this->getComposicaoID()));
        $this->setProdutoID(Filter::number($this->getProdutoID()));
        $this->setQuantidade(Filter::float($this->getQuantidade(), $localized));
        $this->setValor(Filter::money($this->getValor(), $localized));
        $this->setQuantidadeMaxima(Filter::number($this->getQuantidadeMaxima()));
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
     * @return array All field of Composicao in array format
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getComposicaoID())) {
            $errors['composicaoid'] = _t('composicao.composicao_id_cannot_empty');
        }
        if (is_null($this->getProdutoID())) {
            $errors['produtoid'] = _t('composicao.produto_id_cannot_empty');
        }
        if (!Validator::checkInSet($this->getTipo(), self::getTipoOptions())) {
            $errors['tipo'] = _t('composicao.tipo_invalid');
        }
        if (is_null($this->getQuantidade())) {
            $errors['quantidade'] = _t('composicao.quantidade_cannot_empty');
        }
        if (is_null($this->getValor())) {
            $errors['valor'] = _t('composicao.valor_cannot_empty');
        }
        if (is_null($this->getQuantidadeMaxima())) {
            $errors['quantidademaxima'] = _t('composicao.quantidade_maxima_cannot_empty');
        }
        if (!Validator::checkBoolean($this->getAtiva())) {
            $errors['ativa'] = _t('composicao.ativa_invalid');
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
        if (contains(['ComposicaoID', 'ProdutoID', 'Tipo', 'UNIQUE'], $e->getMessage())) {
            return new ValidationException([
                'composicaoid' => _t(
                    'composicao.composicao_id_used',
                    $this->getComposicaoID()
                ),
                'produtoid' => _t(
                    'composicao.produto_id_used',
                    $this->getProdutoID()
                ),
                'tipo' => _t(
                    'composicao.tipo_used',
                    $this->getTipo()
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Insert a new Composição into the database and fill instance from database
     * @return self Self instance
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function insert()
    {
        $this->setID(null);
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = DB::insertInto('Composicoes')->values($values)->execute();
            $this->setID($id);
            $this->loadByID();
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Composição with instance values into database for ID
     * @param array $only Save these fields only, when empty save all fields except id
     * @return int rows affected
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function update($only = [])
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new ValidationException(
                ['id' => _t('composicao.id_cannot_empty')]
            );
        }
        $values = DB::filterValues($values, $only, false);
        try {
            $affected = DB::update('Composicoes')
                ->set($values)
                ->where(['id' => $this->getID()])
                ->execute();
            $this->loadByID();
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $affected;
    }

    /**
     * Delete this instance from database using ID
     * @return integer Number of rows deleted (Max 1)
     * @throws \MZ\Exception\ValidationException for invalid id
     */
    public function delete()
    {
        if (!$this->exists()) {
            throw new ValidationException(
                ['id' => _t('composicao.id_cannot_empty')]
            );
        }
        $result = DB::deleteFrom('Composicoes')
            ->where('id', $this->getID())
            ->execute();
        return $result;
    }

    /**
     * Load one register for it self with a condition
     * @param array $condition Condition for searching the row
     * @param array $order associative field name -> [-1, 1]
     * @return self Self instance filled or empty
     */
    public function load($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return $this->fromArray($row);
    }

    /**
     * Load into this object from database using, ComposicaoID, ProdutoID, Tipo
     * @return self Self filled instance or empty when not found
     */
    public function loadByComposicaoIDProdutoIDTipo()
    {
        return $this->load([
            'composicaoid' => intval($this->getComposicaoID()),
            'produtoid' => intval($this->getProdutoID()),
            'tipo' => strval($this->getTipo()),
        ]);
    }

    /**
     * Informa a qual produto pertence essa composição, deve sempre ser um
     * produto do tipo Composição
     * @return \MZ\Product\Produto The object fetched from database
     */
    public function findComposicaoID()
    {
        return \MZ\Product\Produto::findByID($this->getComposicaoID());
    }

    /**
     * Produto ou composição que faz parte dessa composição, Obs: Não pode ser
     * um pacote
     * @return \MZ\Product\Produto The object fetched from database
     */
    public function findProdutoID()
    {
        return \MZ\Product\Produto::findByID($this->getProdutoID());
    }

    /**
     * Gets textual and translated Tipo for Composicao
     * @param int $index choose option from index
     * @return string[] A associative key -> translated representative text or text for index
     */
    public static function getTipoOptions($index = null)
    {
        $options = [
            self::TIPO_COMPOSICAO => _t('composicao.tipo_composicao'),
            self::TIPO_OPCIONAL => _t('composicao.tipo_opcional'),
            self::TIPO_ADICIONAL => _t('composicao.tipo_adicional'),
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
        $composicao = new self();
        $allowed = Filter::concatKeys('c.', $composicao->toArray());
        return $allowed;
    }

    /**
     * Filter order array
     * @param mixed $order order string or array to parse and filter allowed
     * @return array allowed associative order
     */
    private static function filterOrder($order)
    {
        $allowed = self::getAllowedKeys();
        return Filter::orderBy($order, $allowed, 'c.');
    }

    /**
     * Filter condition array with allowed fields
     * @param array $condition condition to filter rows
     * @return array allowed condition
     */
    private static function filterCondition($condition)
    {
        $allowed = self::getAllowedKeys();
        return Filter::keys($condition, $allowed, 'c.');
    }

    /**
     * Fetch data from database with a condition
     * @param array $condition condition to filter rows
     * @param array $order order rows
     * @return SelectQuery query object with condition statement
     */
    private static function query($condition = [], $order = [])
    {
        $query = DB::from('Composicoes c')
            ->leftJoin('Produtos p ON p.id = c.produtoid');
        if (isset($condition['search'])) {
            $search = trim($condition['search']);
            $query = DB::buildSearch($search, 'p.descricao', $query);
            unset($condition['search']);
        }
        $condition = self::filterCondition($condition);
        $query = DB::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('c.tipo ASC');
        $query = $query->orderBy('c.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param array $condition Condition for searching the row
     * @param array $order order rows
     * @return self A filled Composição or empty instance
     */
    public static function find($condition, $order = [])
    {
        $result = new self();
        return $result->load($condition, $order);
    }

    /**
     * Search one register with a condition
     * @param array $condition Condition for searching the row
     * @param array $order order rows
     * @return self A filled Composição or empty instance
     * @throws \Exception when register has not found
     */
    public static function findOrFail($condition, $order = [])
    {
        $result = self::find($condition, $order);
        if (!$result->exists()) {
            throw new \Exception(_t('composicao.not_found'), 404);
        }
        return $result;
    }

    /**
     * Find this object on database using, ComposicaoID, ProdutoID, Tipo
     * @param int $composicao_id composição to find Composição
     * @param int $produto_id produto da composição to find Composição
     * @param string $tipo tipo to find Composição
     * @return self A filled instance or empty when not found
     */
    public static function findByComposicaoIDProdutoIDTipo($composicao_id, $produto_id, $tipo)
    {
        $result = new self();
        $result->setComposicaoID($composicao_id);
        $result->setProdutoID($produto_id);
        $result->setTipo($tipo);
        return $result->loadByComposicaoIDProdutoIDTipo();
    }

    /**
     * Find all Composição
     * @param array  $condition Condition to get all Composição
     * @param array  $order     Order Composição
     * @param int    $limit     Limit data into row count
     * @param int    $offset    Start offset to get rows
     * @return self[] List of all rows instanced as Composicao
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
            $result[] = new self($row);
        }
        return $result;
    }

    /**
     * Count all rows from database with matched condition critery
     * @param array $condition condition to filter rows
     * @return integer Quantity of rows
     */
    public static function count($condition = [])
    {
        $query = self::query($condition);
        return $query->count();
    }
}
