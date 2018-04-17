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

use MZ\Util\Filter;
use MZ\Util\Validator;

/**
 * Informa as propriedades da composição de um produto composto
 */
class Composicao extends \MZ\Database\Helper
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
     * @return mixed ID of Composicao
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param  mixed $id new value for ID
     * @return Composicao Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Informa a qual produto pertence essa composição, deve sempre ser um
     * produto do tipo Composição
     * @return mixed Composição of Composicao
     */
    public function getComposicaoID()
    {
        return $this->composicao_id;
    }

    /**
     * Set ComposicaoID value to new on param
     * @param  mixed $composicao_id new value for ComposicaoID
     * @return Composicao Self instance
     */
    public function setComposicaoID($composicao_id)
    {
        $this->composicao_id = $composicao_id;
        return $this;
    }

    /**
     * Produto ou composição que faz parte dessa composição, Obs: Não pode ser
     * um pacote
     * @return mixed Produto da composição of Composicao
     */
    public function getProdutoID()
    {
        return $this->produto_id;
    }

    /**
     * Set ProdutoID value to new on param
     * @param  mixed $produto_id new value for ProdutoID
     * @return Composicao Self instance
     */
    public function setProdutoID($produto_id)
    {
        $this->produto_id = $produto_id;
        return $this;
    }

    /**
     * Tipo de composição, 'Composicao' sempre retira do estoque, 'Opcional'
     * permite desmarcar na venda, 'Adicional' permite adicionar na venda
     * @return mixed Tipo of Composicao
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set Tipo value to new on param
     * @param  mixed $tipo new value for Tipo
     * @return Composicao Self instance
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
        return $this;
    }

    /**
     * Quantidade que será consumida desse produto para cada composição formada
     * @return mixed Quantidade of Composicao
     */
    public function getQuantidade()
    {
        return $this->quantidade;
    }

    /**
     * Set Quantidade value to new on param
     * @param  mixed $quantidade new value for Quantidade
     * @return Composicao Self instance
     */
    public function setQuantidade($quantidade)
    {
        $this->quantidade = $quantidade;
        return $this;
    }

    /**
     * Desconto que será realizado ao retirar esse produto da composição no
     * momento da venda
     * @return mixed Valor of Composicao
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Set Valor value to new on param
     * @param  mixed $valor new value for Valor
     * @return Composicao Self instance
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
        return $this;
    }

    /**
     * Indica se a composição está sendo usada atualmente na composição do
     * produto
     * @return mixed Ativa of Composicao
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
     * @param  mixed $ativa new value for Ativa
     * @return Composicao Self instance
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
        $composicao = parent::toArray($recursive);
        $composicao['id'] = $this->getID();
        $composicao['composicaoid'] = $this->getComposicaoID();
        $composicao['produtoid'] = $this->getProdutoID();
        $composicao['tipo'] = $this->getTipo();
        $composicao['quantidade'] = $this->getQuantidade();
        $composicao['valor'] = $this->getValor();
        $composicao['ativa'] = $this->getAtiva();
        return $composicao;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param  mixed $composicao Associated key -> value to assign into this instance
     * @return Composicao Self instance
     */
    public function fromArray($composicao = [])
    {
        if ($composicao instanceof Composicao) {
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
        if (!isset($composicao['ativa'])) {
            $this->setAtiva(null);
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
     * @param Composicao $original Original instance without modifications
     */
    public function filter($original)
    {
        $this->setID($original->getID());
        $this->setComposicaoID(Filter::number($this->getComposicaoID()));
        $this->setProdutoID(Filter::number($this->getProdutoID()));
        $this->setQuantidade(Filter::float($this->getQuantidade()));
        $this->setValor(Filter::money($this->getValor()));
    }

    /**
     * Clean instance resources like images and docs
     * @param  Composicao $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Composicao in array format
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getComposicaoID())) {
            $errors['composicaoid'] = 'A composição não pode ser vazia';
        }
        if (is_null($this->getProdutoID())) {
            $errors['produtoid'] = 'O produto da composição não pode ser vazio';
        }
        if (is_null($this->getTipo())) {
            $errors['tipo'] = 'O tipo não pode ser vazio';
        }
        if (!Validator::checkInSet($this->getTipo(), self::getTipoOptions(), true)) {
            $errors['tipo'] = 'O tipo é inválido';
        }
        if (is_null($this->getQuantidade())) {
            $errors['quantidade'] = 'A quantidade não pode ser vazia';
        }
        if (is_null($this->getValor())) {
            $errors['valor'] = 'O valor não pode ser vazio';
        }
        if (is_null($this->getAtiva())) {
            $errors['ativa'] = 'A ativa não pode ser vazia';
        }
        if (!Validator::checkBoolean($this->getAtiva(), true)) {
            $errors['ativa'] = 'A ativa é inválida';
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
        if (stripos($e->getMessage(), 'UK_Composicoes_ComposicaoID_ProdutoID_Tipo') !== false) {
            return new \MZ\Exception\ValidationException([
                'composicaoid' => sprintf(
                    'A composição "%s" já está cadastrada',
                    $this->getComposicaoID()
                ),
                'produtoid' => sprintf(
                    'O produto da composição "%s" já está cadastrado',
                    $this->getProdutoID()
                ),
                'tipo' => sprintf(
                    'O tipo "%s" já está cadastrado',
                    $this->getTipo()
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Insert a new Composição into the database and fill instance from database
     * @return Composicao Self instance
     */
    public function insert()
    {
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = self::getDB()->insertInto('Composicoes')->values($values)->execute();
            $this->loadByID($id);
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Composição with instance values into database for ID
     * @param  array $only Save these fields only, when empty save all fields except id
     * @param  boolean $except When true, saves all fields except $only
     * @return Composicao Self instance
     */
    public function update($only = [], $except = false)
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador da composição não foi informado');
        }
        $values = self::filterValues($values, $only, $except);
        try {
            self::getDB()
                ->update('Composicoes')
                ->set($values)
                ->where('id', $this->getID())
                ->execute();
            $this->loadByID($this->getID());
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
            throw new \Exception('O identificador da composição não foi informado');
        }
        $result = self::getDB()
            ->deleteFrom('Composicoes')
            ->where('id', $this->getID())
            ->execute();
        return $result;
    }

    /**
     * Load one register for it self with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order associative field name -> [-1, 1]
     * @return Composicao Self instance filled or empty
     */
    public function load($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return $this->fromArray($row);
    }

    /**
     * Load into this object from database using, ComposicaoID, ProdutoID, Tipo
     * @param  int $composicao_id composição to find Composição
     * @param  int $produto_id produto da composição to find Composição
     * @param  string $tipo tipo to find Composição
     * @return Composicao Self filled instance or empty when not found
     */
    public function loadByComposicaoIDProdutoIDTipo($composicao_id, $produto_id, $tipo)
    {
        return $this->load([
            'composicaoid' => intval($composicao_id),
            'produtoid' => intval($produto_id),
            'tipo' => strval($tipo),
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
     * @param  int $index choose option from index
     * @return mixed A associative key -> translated representative text or text for index
     */
    public static function getTipoOptions($index = null)
    {
        $options = [
            self::TIPO_COMPOSICAO => 'Composição',
            self::TIPO_OPCIONAL => 'Opcional',
            self::TIPO_ADICIONAL => 'Adicional',
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
        $composicao = new Composicao();
        $allowed = Filter::concatKeys('c.', $composicao->toArray());
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
        $query = self::getDB()->from('Composicoes c');
        $condition = self::filterCondition($condition);
        $query = self::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('c.tipo ASC');
        $query = $query->orderBy('c.id ASC');
        return self::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order order rows
     * @return Composicao A filled Composição or empty instance
     */
    public static function find($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return new Composicao($row);
    }

    /**
     * Find this object on database using, ID
     * @param  int $id id to find Composição
     * @return Composicao A filled instance or empty when not found
     */
    public static function findByID($id)
    {
        return self::find([
            'id' => intval($id),
        ]);
    }

    /**
     * Find this object on database using, ComposicaoID, ProdutoID, Tipo
     * @param  int $composicao_id composição to find Composição
     * @param  int $produto_id produto da composição to find Composição
     * @param  string $tipo tipo to find Composição
     * @return Composicao A filled instance or empty when not found
     */
    public static function findByComposicaoIDProdutoIDTipo($composicao_id, $produto_id, $tipo)
    {
        return self::find([
            'composicaoid' => intval($composicao_id),
            'produtoid' => intval($produto_id),
            'tipo' => strval($tipo),
        ]);
    }

    /**
     * Find all Composição
     * @param  array  $condition Condition to get all Composição
     * @param  array  $order     Order Composição
     * @param  int    $limit     Limit data into row count
     * @param  int    $offset    Start offset to get rows
     * @return array             List of all rows instanced as Composicao
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
            $result[] = new Composicao($row);
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
