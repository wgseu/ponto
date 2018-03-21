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
namespace MZ\Product;

use MZ\Util\Filter;
use MZ\Util\Validator;

/**
 * Contém todos as opções para a formação do produto final
 */
class Pacote extends \MZ\Database\Helper
{

    /**
     * Identificador do pacote
     */
    private $id;
    /**
     * Pacote a qual pertence as informações de formação do produto final
     */
    private $pacote_id;
    /**
     * Grupo de formação, Ex.: Tamanho, Sabores e Complementos.
     */
    private $grupo_id;
    /**
     * Produto selecionável do grupo. Não pode conter propriedade.
     */
    private $produto_id;
    /**
     * Propriedade selecionável do grupo. Não pode conter produto.
     */
    private $propriedade_id;
    /**
     * Informa a propriedade pai de um complemento, permite atribuir preços
     * diferentes dependendo da propriedade, Ex.: Tamanho -> Sabor, onde
     * Tamanho é pai de Sabor
     */
    private $associacao_id;
    /**
     * Permite definir uma quantidade mínima obrigatória para a venda desse
     * item
     */
    private $quantidade_minima;
    /**
     * Define a quantidade máxima que pode ser vendido esse item repetidamente
     */
    private $quantidade_maxima;
    /**
     * Valor acrescentado ao produto quando o item é selecionado
     */
    private $valor;
    /**
     * Informa se o complemento está selecionado por padrão, recomendado apenas
     * para produtos
     */
    private $selecionado;
    /**
     * Indica se o pacote estará disponível para venda
     */
    private $visivel;

    /**
     * Constructor for a new empty instance of Pacote
     * @param array $pacote All field and values to fill the instance
     */
    public function __construct($pacote = [])
    {
        parent::__construct($pacote);
    }

    /**
     * Identificador do pacote
     * @return mixed ID of Pacote
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param  mixed $id new value for ID
     * @return Pacote Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Pacote a qual pertence as informações de formação do produto final
     * @return mixed Pacote of Pacote
     */
    public function getPacoteID()
    {
        return $this->pacote_id;
    }

    /**
     * Set PacoteID value to new on param
     * @param  mixed $pacote_id new value for PacoteID
     * @return Pacote Self instance
     */
    public function setPacoteID($pacote_id)
    {
        $this->pacote_id = $pacote_id;
        return $this;
    }

    /**
     * Grupo de formação, Ex.: Tamanho, Sabores e Complementos.
     * @return mixed Grupo of Pacote
     */
    public function getGrupoID()
    {
        return $this->grupo_id;
    }

    /**
     * Set GrupoID value to new on param
     * @param  mixed $grupo_id new value for GrupoID
     * @return Pacote Self instance
     */
    public function setGrupoID($grupo_id)
    {
        $this->grupo_id = $grupo_id;
        return $this;
    }

    /**
     * Produto selecionável do grupo. Não pode conter propriedade.
     * @return mixed Produto of Pacote
     */
    public function getProdutoID()
    {
        return $this->produto_id;
    }

    /**
     * Set ProdutoID value to new on param
     * @param  mixed $produto_id new value for ProdutoID
     * @return Pacote Self instance
     */
    public function setProdutoID($produto_id)
    {
        $this->produto_id = $produto_id;
        return $this;
    }

    /**
     * Propriedade selecionável do grupo. Não pode conter produto.
     * @return mixed Propriedade of Pacote
     */
    public function getPropriedadeID()
    {
        return $this->propriedade_id;
    }

    /**
     * Set PropriedadeID value to new on param
     * @param  mixed $propriedade_id new value for PropriedadeID
     * @return Pacote Self instance
     */
    public function setPropriedadeID($propriedade_id)
    {
        $this->propriedade_id = $propriedade_id;
        return $this;
    }

    /**
     * Informa a propriedade pai de um complemento, permite atribuir preços
     * diferentes dependendo da propriedade, Ex.: Tamanho -> Sabor, onde
     * Tamanho é pai de Sabor
     * @return mixed Associação of Pacote
     */
    public function getAssociacaoID()
    {
        return $this->associacao_id;
    }

    /**
     * Set AssociacaoID value to new on param
     * @param  mixed $associacao_id new value for AssociacaoID
     * @return Pacote Self instance
     */
    public function setAssociacaoID($associacao_id)
    {
        $this->associacao_id = $associacao_id;
        return $this;
    }

    /**
     * Permite definir uma quantidade mínima obrigatória para a venda desse
     * item
     * @return mixed Quantidade mínima of Pacote
     */
    public function getQuantidadeMinima()
    {
        return $this->quantidade_minima;
    }

    /**
     * Set QuantidadeMinima value to new on param
     * @param  mixed $quantidade_minima new value for QuantidadeMinima
     * @return Pacote Self instance
     */
    public function setQuantidadeMinima($quantidade_minima)
    {
        $this->quantidade_minima = $quantidade_minima;
        return $this;
    }

    /**
     * Define a quantidade máxima que pode ser vendido esse item repetidamente
     * @return mixed Quantidade máxima of Pacote
     */
    public function getQuantidadeMaxima()
    {
        return $this->quantidade_maxima;
    }

    /**
     * Set QuantidadeMaxima value to new on param
     * @param  mixed $quantidade_maxima new value for QuantidadeMaxima
     * @return Pacote Self instance
     */
    public function setQuantidadeMaxima($quantidade_maxima)
    {
        $this->quantidade_maxima = $quantidade_maxima;
        return $this;
    }

    /**
     * Valor acrescentado ao produto quando o item é selecionado
     * @return mixed Valor of Pacote
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Set Valor value to new on param
     * @param  mixed $valor new value for Valor
     * @return Pacote Self instance
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
        return $this;
    }

    /**
     * Informa se o complemento está selecionado por padrão, recomendado apenas
     * para produtos
     * @return mixed Selecionado of Pacote
     */
    public function getSelecionado()
    {
        return $this->selecionado;
    }

    /**
     * Informa se o complemento está selecionado por padrão, recomendado apenas
     * para produtos
     * @return boolean Check if o of Selecionado is selected or checked
     */
    public function isSelecionado()
    {
        return $this->selecionado == 'Y';
    }

    /**
     * Set Selecionado value to new on param
     * @param  mixed $selecionado new value for Selecionado
     * @return Pacote Self instance
     */
    public function setSelecionado($selecionado)
    {
        $this->selecionado = $selecionado;
        return $this;
    }

    /**
     * Indica se o pacote estará disponível para venda
     * @return mixed Visível of Pacote
     */
    public function getVisivel()
    {
        return $this->visivel;
    }

    /**
     * Indica se o pacote estará disponível para venda
     * @return boolean Check if o of Visivel is selected or checked
     */
    public function isVisivel()
    {
        return $this->visivel == 'Y';
    }

    /**
     * Set Visivel value to new on param
     * @param  mixed $visivel new value for Visivel
     * @return Pacote Self instance
     */
    public function setVisivel($visivel)
    {
        $this->visivel = $visivel;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param  boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $pacote = parent::toArray($recursive);
        $pacote['id'] = $this->getID();
        $pacote['pacoteid'] = $this->getPacoteID();
        $pacote['grupoid'] = $this->getGrupoID();
        $pacote['produtoid'] = $this->getProdutoID();
        $pacote['propriedadeid'] = $this->getPropriedadeID();
        $pacote['associacaoid'] = $this->getAssociacaoID();
        $pacote['quantidademinima'] = $this->getQuantidadeMinima();
        $pacote['quantidademaxima'] = $this->getQuantidadeMaxima();
        $pacote['valor'] = $this->getValor();
        $pacote['selecionado'] = $this->getSelecionado();
        $pacote['visivel'] = $this->getVisivel();
        return $pacote;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param  mixed $pacote Associated key -> value to assign into this instance
     * @return Pacote Self instance
     */
    public function fromArray($pacote = [])
    {
        if ($pacote instanceof Pacote) {
            $pacote = $pacote->toArray();
        } elseif (!is_array($pacote)) {
            $pacote = [];
        }
        parent::fromArray($pacote);
        if (!isset($pacote['id'])) {
            $this->setID(null);
        } else {
            $this->setID($pacote['id']);
        }
        if (!isset($pacote['pacoteid'])) {
            $this->setPacoteID(null);
        } else {
            $this->setPacoteID($pacote['pacoteid']);
        }
        if (!isset($pacote['grupoid'])) {
            $this->setGrupoID(null);
        } else {
            $this->setGrupoID($pacote['grupoid']);
        }
        if (!array_key_exists('produtoid', $pacote)) {
            $this->setProdutoID(null);
        } else {
            $this->setProdutoID($pacote['produtoid']);
        }
        if (!array_key_exists('propriedadeid', $pacote)) {
            $this->setPropriedadeID(null);
        } else {
            $this->setPropriedadeID($pacote['propriedadeid']);
        }
        if (!array_key_exists('associacaoid', $pacote)) {
            $this->setAssociacaoID(null);
        } else {
            $this->setAssociacaoID($pacote['associacaoid']);
        }
        if (!isset($pacote['quantidademinima'])) {
            $this->setQuantidadeMinima(0);
        } else {
            $this->setQuantidadeMinima($pacote['quantidademinima']);
        }
        if (!isset($pacote['quantidademaxima'])) {
            $this->setQuantidadeMaxima(1);
        } else {
            $this->setQuantidadeMaxima($pacote['quantidademaxima']);
        }
        if (!isset($pacote['valor'])) {
            $this->setValor(null);
        } else {
            $this->setValor($pacote['valor']);
        }
        if (!isset($pacote['selecionado'])) {
            $this->setSelecionado('N');
        } else {
            $this->setSelecionado($pacote['selecionado']);
        }
        if (!isset($pacote['visivel'])) {
            $this->setVisivel('Y');
        } else {
            $this->setVisivel($pacote['visivel']);
        }
        return $this;
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $pacote = parent::publish();
        return $pacote;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param Pacote $original Original instance without modifications
     */
    public function filter($original)
    {
        $this->setID($original->getID());
        $this->setPacoteID(Filter::number($this->getPacoteID()));
        $this->setGrupoID(Filter::number($this->getGrupoID()));
        $this->setProdutoID(Filter::number($this->getProdutoID()));
        $this->setPropriedadeID(Filter::number($this->getPropriedadeID()));
        $this->setAssociacaoID(Filter::number($this->getAssociacaoID()));
        $this->setQuantidadeMinima(Filter::number($this->getQuantidadeMinima()));
        $this->setQuantidadeMaxima(Filter::number($this->getQuantidadeMaxima()));
        $this->setValor(Filter::money($this->getValor()));
    }

    /**
     * Clean instance resources like images and docs
     * @param  Pacote $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Pacote in array format
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getPacoteID())) {
            $errors['pacoteid'] = 'O Pacote não pode ser vazio';
        }
        if (is_null($this->getGrupoID())) {
            $errors['grupoid'] = 'O Grupo não pode ser vazio';
        }
        if (is_null($this->getQuantidadeMinima())) {
            $this->setQuantidadeMinima(0);
        }
        if (is_null($this->getQuantidadeMaxima())) {
            $this->setQuantidadeMaxima(1);
        }
        if (is_null($this->getValor())) {
            $errors['valor'] = 'O Valor não pode ser vazio';
        }
        if (is_null($this->getSelecionado())) {
            $this->setSelecionado('N');
        }
        if (is_null($this->getVisivel())) {
            $this->setVisivel('Y');
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
                'id' => vsprintf(
                    'O ID "%s" já está cadastrado',
                    [$this->getID()]
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Find this object on database using, ID
     * @param  int $id id to find Pacote
     * @return Pacote A filled instance or empty when not found
     */
    public static function findByID($id)
    {
        return self::find([
            'id' => intval($id),
        ]);
    }

    /**
     * Filter order array
     * @param  mixed $order order string or array to parse and filter allowed
     * @return array allowed associative order
     */
    private static function filterOrder($order)
    {
        $pacote = new Pacote();
        $allowed = $pacote->toArray();
        return Filter::orderBy($order, $allowed);
    }

    /**
     * Fetch data from database with a condition
     * @param  array $condition condition to filter rows
     * @return SelectQuery query object with condition statement
     */
    private static function filterOrderEx($order)
    {
        $pacote = new Pacote();
        $allowed = Filter::concatKeys('pc.', $pacote->toArray());
        return Filter::orderBy($order, $allowed);
    }

    /**
     * Filter condition array with allowed fields
     * @param  array $condition condition to filter rows
     * @return array allowed condition
     */
    private static function filterCondition($condition)
    {
        $pacote = new Pacote();
        $allowed = $pacote->toArray();
        return Filter::keys($condition, $allowed);
    }

    /**
     * Filter condition array with allowed fields
     * @param  array $condition condition to filter rows
     * @return array allowed condition
     */
    private static function filterConditionEx($condition)
    {
        $pacote = new Pacote();
        $allowed = Filter::concatKeys('pc.', $pacote->toArray());
        return Filter::keys($condition, $allowed);
    }

    /**
     * Fetch data from database with a condition
     * @param  array $condition condition to filter rows
     * @param  array $order order rows
     * @return SelectQuery query object with condition statement
     */
    private static function query($condition = [], $order = [])
    {
        $query = self::getDB()->from('Pacotes');
        $condition = self::filterCondition($condition);
        $query = self::buildOrderBy($query, self::filterOrder($order));
        return self::buildCondition($query, $condition);
    }

    /**
     * Fetch data from database with a condition
     * @param  array $condition condition to filter rows
     * @return SelectQuery query object with condition statement
     */
    private static function queryEx($condition = [], $order = [])
    {
        $query = self::getDB()->from('Pacotes pc')
            ->leftJoin('Produtos p ON p.id = pc.produtoid')
            ->leftJoin('Propriedades pr ON pr.id = pc.propriedadeid')
            ->leftJoin('Pacotes pca ON pca.id = pc.associacaoid')
            ->leftJoin('Unidades u ON u.id = p.unidadeid')
            ->leftJoin('Promocoes prm ON prm.produtoid = p.id AND NOW() BETWEEN DATE_ADD(CURDATE(), INTERVAL prm.inicio - DAYOFWEEK(CURDATE()) * 1440 MINUTE) AND DATE_ADD(CURDATE(), INTERVAL prm.fim - DAYOFWEEK(CURDATE()) * 1440 MINUTE)')
            ->select('pca.grupoid as associacaogrupoid')
            ->select('COALESCE(p.precovenda + COALESCE(prm.valor, 0), 0) as precobase')
            ->select('COALESCE(p.descricao, pr.nome) as descricao')
            ->select('COALESCE(p.abreviacao, pr.abreviacao) as abreviacao')
            ->select('p.detalhes')
            ->select('p.tipo as produtotipo')
            ->select('p.conteudo as produtoconteudo')
            ->select('u.sigla as unidadesigla')
            ->select('IF(ISNULL(p.imagem) AND ISNULL(pr.imagem), NULL, CONCAT(COALESCE(pr.id, p.id), ".png")) as imagemurl')
            ->select('COALESCE(pr.dataatualizacao, p.dataatualizacao) as dataatualizacao');
        if (isset($condition['query'])) {
            $busca = $condition['query'];
            $query = self::buildSearch(
                $busca,
                'CONCAT(COALESCE(p.abreviacao, pr.abreviacao, ""), " ", COALESCE(p.descricao, pr.nome))',
                $query
            );
            unset($condition['query']);
        }
        $condition = self::filterConditionEx($condition);
        if (
            isset($condition['pc.associacaoid']) &&
            is_array($condition['pc.associacaoid']) &&
            count($condition['pc.associacaoid']) == 0
        ) {
            unset($condition['pc.associacaoid']);
        }
        $query = self::buildOrderBy($query, self::filterOrderEx($order));
        return self::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order order rows
     * @return Pacote A filled Pacote or empty instance
     */
    public static function find($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch();
        if ($row === false) {
            $row = [];
        }
        return new Pacote($row);
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
            $result[] = new Pacote($row);
        }
        return $result;
    }

    /**
     * Fetch all rows from database with matched condition critery
     * @param  array $condition condition to filter rows
     * @param  integer $limit number of rows to get, null for all
     * @param  integer $offset start index to get rows, null for begining
     * @return array All rows instanced and filled
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
     * Insert a new Pacote into the database and fill instance from database
     * @return Pacote Self instance
     */
    public function insert()
    {
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = self::getDB()->insertInto('Pacotes')->values($values)->execute();
            $pacote = self::findByID($id);
            $this->fromArray($pacote->toArray());
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Pacote with instance values into database for ID
     * @return Pacote Self instance
     */
    public function update()
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador do pacote não foi informado');
        }
        unset($values['id']);
        try {
            self::getDB()
                ->update('Pacotes')
                ->set($values)
                ->where('id', $this->getID())
                ->execute();
            $pacote = self::findByID($this->getID());
            $this->fromArray($pacote->toArray());
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Save the Pacote into the database
     * @return Pacote Self instance
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
            throw new \Exception('O identificador do pacote não foi informado');
        }
        $result = self::getDB()
            ->deleteFrom('Pacotes')
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
     * Informa a propriedade pai de um complemento, permite atribuir preços
     * diferentes dependendo da propriedade, Ex.: Tamanho -> Sabor, onde
     * Tamanho é pai de Sabor
     * @return \MZ\Product\Pacote The object fetched from database
     */
    public function findAssociacaoID()
    {
        if (is_null($this->getAssociacaoID())) {
            return new \MZ\Product\Pacote();
        }
        return \MZ\Product\Pacote::findByID($this->getAssociacaoID());
    }
}
