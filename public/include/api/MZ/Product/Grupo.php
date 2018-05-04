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
 * Grupos de pacotes, permite criar grupos como Tamanho, Sabores para
 * formações de produtos
 */
class Grupo extends \MZ\Database\Helper
{

    /**
     * Informa se a formação final será apenas uma unidade ou vários itens
     */
    const TIPO_INTEIRO = 'Inteiro';
    const TIPO_FRACIONADO = 'Fracionado';

    /**
     * Informa qual será a fórmula de cálculo do preço, Mínimo: obtém o menor
     * preço, Média:  define o preço do produto como a média dos itens
     * selecionados, Máximo: Obtém o preço do item mais caro do grupo, Soma:
     * Soma todos os preços dos produtos selecionados
     */
    const FUNCAO_MINIMO = 'Minimo';
    const FUNCAO_MEDIA = 'Media';
    const FUNCAO_MAXIMO = 'Maximo';
    const FUNCAO_SOMA = 'Soma';

    /**
     * Identificador do grupo
     */
    private $id;
    /**
     * Informa o pacote base da formação
     */
    private $produto_id;
    /**
     * Descrição do grupo da formação, Exemplo: Tamanho, Sabores
     */
    private $descricao;
    /**
     * Informa se é possível selecionar mais de um produto ou opção do produto
     */
    private $multiplo;
    /**
     * Informa se a formação final será apenas uma unidade ou vários itens
     */
    private $tipo;
    /**
     * Permite definir uma quantidade mínima obrigatória para continuar com a
     * venda
     */
    private $quantidade_minima;
    /**
     * Define a quantidade máxima de itens que podem ser escolhidos
     */
    private $quantidade_maxima;
    /**
     * Informa qual será a fórmula de cálculo do preço, Mínimo: obtém o menor
     * preço, Média:  define o preço do produto como a média dos itens
     * selecionados, Máximo: Obtém o preço do item mais caro do grupo, Soma:
     * Soma todos os preços dos produtos selecionados
     */
    private $funcao;

    /**
     * Constructor for a new empty instance of Grupo
     * @param array $grupo All field and values to fill the instance
     */
    public function __construct($grupo = [])
    {
        parent::__construct($grupo);
    }

    /**
     * Identificador do grupo
     * @return mixed ID of Grupo
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param  mixed $id new value for ID
     * @return Grupo Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Informa o pacote base da formação
     * @return mixed Pacote of Grupo
     */
    public function getProdutoID()
    {
        return $this->produto_id;
    }

    /**
     * Set ProdutoID value to new on param
     * @param  mixed $produto_id new value for ProdutoID
     * @return Grupo Self instance
     */
    public function setProdutoID($produto_id)
    {
        $this->produto_id = $produto_id;
        return $this;
    }

    /**
     * Descrição do grupo da formação, Exemplo: Tamanho, Sabores
     * @return mixed Descrição of Grupo
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Set Descricao value to new on param
     * @param  mixed $descricao new value for Descricao
     * @return Grupo Self instance
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * Informa se é possível selecionar mais de um produto ou opção do produto
     * @return mixed Múltiplo of Grupo
     */
    public function getMultiplo()
    {
        return $this->multiplo;
    }

    /**
     * Informa se é possível selecionar mais de um produto ou opção do produto
     * @return boolean Check if o of Multiplo is selected or checked
     */
    public function isMultiplo()
    {
        return $this->multiplo == 'Y';
    }

    /**
     * Set Multiplo value to new on param
     * @param  mixed $multiplo new value for Multiplo
     * @return Grupo Self instance
     */
    public function setMultiplo($multiplo)
    {
        $this->multiplo = $multiplo;
        return $this;
    }

    /**
     * Informa se a formação final será apenas uma unidade ou vários itens
     * @return mixed Tipo of Grupo
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set Tipo value to new on param
     * @param  mixed $tipo new value for Tipo
     * @return Grupo Self instance
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
        return $this;
    }

    /**
     * Permite definir uma quantidade mínima obrigatória para continuar com a
     * venda
     * @return mixed Quantidade mínima of Grupo
     */
    public function getQuantidadeMinima()
    {
        return $this->quantidade_minima;
    }

    /**
     * Set QuantidadeMinima value to new on param
     * @param  mixed $quantidade_minima new value for QuantidadeMinima
     * @return Grupo Self instance
     */
    public function setQuantidadeMinima($quantidade_minima)
    {
        $this->quantidade_minima = $quantidade_minima;
        return $this;
    }

    /**
     * Define a quantidade máxima de itens que podem ser escolhidos
     * @return mixed Quantidade máxima of Grupo
     */
    public function getQuantidadeMaxima()
    {
        return $this->quantidade_maxima;
    }

    /**
     * Set QuantidadeMaxima value to new on param
     * @param  mixed $quantidade_maxima new value for QuantidadeMaxima
     * @return Grupo Self instance
     */
    public function setQuantidadeMaxima($quantidade_maxima)
    {
        $this->quantidade_maxima = $quantidade_maxima;
        return $this;
    }

    /**
     * Informa qual será a fórmula de cálculo do preço, Mínimo: obtém o menor
     * preço, Média:  define o preço do produto como a média dos itens
     * selecionados, Máximo: Obtém o preço do item mais caro do grupo, Soma:
     * Soma todos os preços dos produtos selecionados
     * @return mixed Função de preço of Grupo
     */
    public function getFuncao()
    {
        return $this->funcao;
    }

    /**
     * Set Funcao value to new on param
     * @param  mixed $funcao new value for Funcao
     * @return Grupo Self instance
     */
    public function setFuncao($funcao)
    {
        $this->funcao = $funcao;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param  boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $grupo = parent::toArray($recursive);
        $grupo['id'] = $this->getID();
        $grupo['produtoid'] = $this->getProdutoID();
        $grupo['descricao'] = $this->getDescricao();
        $grupo['multiplo'] = $this->getMultiplo();
        $grupo['tipo'] = $this->getTipo();
        $grupo['quantidademinima'] = $this->getQuantidadeMinima();
        $grupo['quantidademaxima'] = $this->getQuantidadeMaxima();
        $grupo['funcao'] = $this->getFuncao();
        return $grupo;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param  mixed $grupo Associated key -> value to assign into this instance
     * @return Grupo Self instance
     */
    public function fromArray($grupo = [])
    {
        if ($grupo instanceof Grupo) {
            $grupo = $grupo->toArray();
        } elseif (!is_array($grupo)) {
            $grupo = [];
        }
        parent::fromArray($grupo);
        if (!isset($grupo['id'])) {
            $this->setID(null);
        } else {
            $this->setID($grupo['id']);
        }
        if (!isset($grupo['produtoid'])) {
            $this->setProdutoID(null);
        } else {
            $this->setProdutoID($grupo['produtoid']);
        }
        if (!isset($grupo['descricao'])) {
            $this->setDescricao(null);
        } else {
            $this->setDescricao($grupo['descricao']);
        }
        if (!isset($grupo['multiplo'])) {
            $this->setMultiplo('N');
        } else {
            $this->setMultiplo($grupo['multiplo']);
        }
        if (!isset($grupo['tipo'])) {
            $this->setTipo(null);
        } else {
            $this->setTipo($grupo['tipo']);
        }
        if (!isset($grupo['quantidademinima'])) {
            $this->setQuantidadeMinima(null);
        } else {
            $this->setQuantidadeMinima($grupo['quantidademinima']);
        }
        if (!isset($grupo['quantidademaxima'])) {
            $this->setQuantidadeMaxima(null);
        } else {
            $this->setQuantidadeMaxima($grupo['quantidademaxima']);
        }
        if (!isset($grupo['funcao'])) {
            $this->setFuncao(null);
        } else {
            $this->setFuncao($grupo['funcao']);
        }
        return $this;
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $grupo = parent::publish();
        return $grupo;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param Grupo $original Original instance without modifications
     */
    public function filter($original)
    {
        $this->setID($original->getID());
        $this->setProdutoID(Filter::number($this->getProdutoID()));
        $this->setDescricao(Filter::string($this->getDescricao()));
        $this->setQuantidadeMinima(Filter::number($this->getQuantidadeMinima()));
        $this->setQuantidadeMaxima(Filter::number($this->getQuantidadeMaxima()));
    }

    /**
     * Clean instance resources like images and docs
     * @param  Grupo $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Grupo in array format
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getProdutoID())) {
            $errors['produtoid'] = 'O pacote não pode ser vazio';
        }
        if (is_null($this->getDescricao())) {
            $errors['descricao'] = 'A descrição não pode ser vazia';
        }
        if (!Validator::checkBoolean($this->getMultiplo())) {
            $errors['multiplo'] = 'A informação de múltiplo não foi informada ou é inválida';
        }
        if (!Validator::checkInSet($this->getTipo(), self::getTipoOptions())) {
            $errors['tipo'] = 'O tipo não foi informado ou é inválido';
        }
        if (is_null($this->getQuantidadeMinima())) {
            $errors['quantidademinima'] = 'A quantidade mínima não pode ser vazia';
        }
        if (is_null($this->getQuantidadeMaxima())) {
            $errors['quantidademaxima'] = 'A quantidade máxima não pode ser vazia';
        }
        if (!Validator::checkInSet($this->getFuncao(), self::getFuncaoOptions())) {
            $errors['funcao'] = 'A função de preço não foi informada ou é inválida';
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
        if (stripos($e->getMessage(), 'UK_Grupos_Produto_Descricao') !== false) {
            return new \MZ\Exception\ValidationException([
                'produtoid' => sprintf(
                    'O pacote "%s" já está cadastrado',
                    $this->getProdutoID()
                ),
                'descricao' => sprintf(
                    'A descrição "%s" já está cadastrada',
                    $this->getDescricao()
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Insert a new Grupo into the database and fill instance from database
     * @return Grupo Self instance
     */
    public function insert()
    {
        $this->setID(null);
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = self::getDB()->insertInto('Grupos')->values($values)->execute();
            $this->loadByID($id);
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Grupo with instance values into database for ID
     * @param  array $only Save these fields only, when empty save all fields except id
     * @param  boolean $except When true, saves all fields except $only
     * @return Grupo Self instance
     */
    public function update($only = [], $except = false)
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador do grupo não foi informado');
        }
        $values = self::filterValues($values, $only, $except);
        try {
            self::getDB()
                ->update('Grupos')
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
            throw new \Exception('O identificador do grupo não foi informado');
        }
        $result = self::getDB()
            ->deleteFrom('Grupos')
            ->where('id', $this->getID())
            ->execute();
        return $result;
    }

    /**
     * Load one register for it self with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order associative field name -> [-1, 1]
     * @return Grupo Self instance filled or empty
     */
    public function load($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return $this->fromArray($row);
    }

    /**
     * Load into this object from database using, ProdutoID, Descricao
     * @param  int $produto_id pacote to find Grupo
     * @param  string $descricao descrição to find Grupo
     * @return Grupo Self filled instance or empty when not found
     */
    public function loadByProdutoIDDescricao($produto_id, $descricao)
    {
        return $this->load([
            'produtoid' => intval($produto_id),
            'descricao' => strval($descricao),
        ]);
    }

    /**
     * Informa o pacote base da formação
     * @return \MZ\Product\Produto The object fetched from database
     */
    public function findProdutoID()
    {
        return \MZ\Product\Produto::findByID($this->getProdutoID());
    }

    /**
     * Gets textual and translated Tipo for Grupo
     * @param  int $index choose option from index
     * @return mixed A associative key -> translated representative text or text for index
     */
    public static function getTipoOptions($index = null)
    {
        $options = [
            self::TIPO_INTEIRO => 'Inteiro',
            self::TIPO_FRACIONADO => 'Fracionado',
        ];
        if (!is_null($index)) {
            return $options[$index];
        }
        return $options;
    }

    /**
     * Gets textual and translated Funcao for Grupo
     * @param  int $index choose option from index
     * @return mixed A associative key -> translated representative text or text for index
     */
    public static function getFuncaoOptions($index = null)
    {
        $options = [
            self::FUNCAO_MINIMO => 'Mínimo',
            self::FUNCAO_MEDIA => 'Média',
            self::FUNCAO_MAXIMO => 'Máximo',
            self::FUNCAO_SOMA => 'Soma',
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
        $grupo = new Grupo();
        $allowed = Filter::concatKeys('g.', $grupo->toArray());
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
        return Filter::orderBy($order, $allowed, 'g.');
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
            $field = 'g.descricao LIKE ?';
            $condition[$field] = '%'.$search.'%';
            $allowed[$field] = true;
            unset($condition['search']);
        }
        return Filter::keys($condition, $allowed, 'g.');
    }

    /**
     * Fetch data from database with a condition
     * @param  array $condition condition to filter rows
     * @param  array $order order rows
     * @return SelectQuery query object with condition statement
     */
    private static function query($condition = [], $order = [])
    {
        $query = self::getDB()->from('Grupos g');
        $condition = self::filterCondition($condition);
        $query = self::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('g.id ASC');
        return self::buildCondition($query, $condition);
    }

    /**
     * Fetch data from database with a condition
     * @param  array $condition condition to filter rows
     * @param  array $order order rows
     * @return SelectQuery query object with condition statement
     */
    private static function queryEx($condition = [], $order = [])
    {
        $query = self::getDB()->from('Grupos g')
            ->select('a.grupoid as grupoassociadoid')
            ->innerJoin('Pacotes c ON c.grupoid = g.id')
            ->leftJoin('Pacotes p ON p.grupoid = c.grupoid AND p.id > c.id')
            ->leftJoin('Pacotes a ON a.id = c.associacaoid')
            ->where(['p.id' => null]);
        $condition = self::filterCondition($condition);
        $query = self::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('g.id ASC');
        return self::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order order rows
     * @return Grupo A filled Grupo or empty instance
     */
    public static function find($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return new Grupo($row);
    }

    /**
     * Find this object on database using, ID
     * @param  int $id id to find Grupo
     * @return Grupo A filled instance or empty when not found
     */
    public static function findByID($id)
    {
        return self::find([
            'id' => intval($id),
        ]);
    }

    /**
     * Find this object on database using, ProdutoID, Descricao
     * @param  int $produto_id pacote to find Grupo
     * @param  string $descricao descrição to find Grupo
     * @return Grupo A filled instance or empty when not found
     */
    public static function findByProdutoIDDescricao($produto_id, $descricao)
    {
        return self::find([
            'produtoid' => intval($produto_id),
            'descricao' => strval($descricao),
        ]);
    }

    /**
     * Find all Grupo
     * @param  array  $condition Condition to get all Grupo
     * @param  array  $order     Order Grupo
     * @param  int    $limit     Limit data into row count
     * @param  int    $offset    Start offset to get rows
     * @return array             List of all rows instanced as Grupo
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
            $result[] = new Grupo($row);
        }
        return $result;
    }

    /**
     * Find all Grupo
     * @param  array  $condition Condition to get all Grupo
     * @param  array  $order     Order Grupo
     * @param  int    $limit     Limit data into row count
     * @param  int    $offset    Start offset to get rows
     * @return array             List of all rows instanced as Grupo
     */
    public static function rawFindAll($condition = [], $order = [], $limit = null, $offset = null)
    {
        $query = self::queryEx($condition, $order);
        if (!is_null($limit)) {
            $query = $query->limit($limit);
        }
        if (!is_null($offset)) {
            $query = $query->offset($offset);
        }
        return $query->fetchAll();
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
