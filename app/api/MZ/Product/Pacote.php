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

use MZ\Database\SyncModel;
use MZ\Database\DB;
use MZ\Util\Date;
use MZ\Util\Filter;
use MZ\Util\Validator;

/**
 * Contém todos as opções para a formação do produto final
 */
class Pacote extends SyncModel
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
            $this->setVisivel('N');
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
    public function filter($original, $localized = false)
    {
        $this->setID($original->getID());
        $this->setPacoteID(Filter::number($this->getPacoteID()));
        $this->setGrupoID(Filter::number($this->getGrupoID()));
        $this->setProdutoID(Filter::number($this->getProdutoID()));
        $this->setPropriedadeID(Filter::number($this->getPropriedadeID()));
        $this->setAssociacaoID(Filter::number($this->getAssociacaoID()));
        $this->setQuantidadeMinima(Filter::number($this->getQuantidadeMinima()));
        $this->setQuantidadeMaxima(Filter::number($this->getQuantidadeMaxima()));
        $this->setValor(Filter::money($this->getValor(), $localized));
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
        if (!Validator::checkBoolean($this->getSelecionado())) {
            $errors['selecionado'] = 'A seleção não foi informada ou é inválida';
        }
        if (!Validator::checkBoolean($this->getVisivel())) {
            $errors['visivel'] = 'A visíbilidade não foi informada ou é inválida';
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
        return parent::translate($e);
    }

    /**
     * Insert a new Pacote into the database and fill instance from database
     * @return Pacote Self instance
     */
    public function insert()
    {
        $this->setID(null);
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = DB::insertInto('Pacotes')->values($values)->execute();
            $this->setID($id);
            $this->loadByID();
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Pacote with instance values into database for ID
     * @param  array $only Save these fields only, when empty save all fields except id
     * @return Pacote Self instance
     */
    public function update($only = [])
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador do pacote não foi informado');
        }
        $values = DB::filterValues($values, $only, false);
        try {
            DB::update('Pacotes')
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
            throw new \Exception('O identificador do pacote não foi informado');
        }
        $result = DB::deleteFrom('Pacotes')
            ->where('id', $this->getID())
            ->execute();
        return $result;
    }

    /**
     * Load one register for it self with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order associative field name -> [-1, 1]
     * @return Pacote Self instance filled or empty
     */
    public function load($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return $this->fromArray($row);
    }

    /**
     * Pacote a qual pertence as informações de formação do produto final
     * @return \MZ\Product\Produto The object fetched from database
     */
    public function findPacoteID()
    {
        return \MZ\Product\Produto::findByID($this->getPacoteID());
    }

    /**
     * Grupo de formação, Ex.: Tamanho, Sabores e Complementos.
     * @return \MZ\Product\Grupo The object fetched from database
     */
    public function findGrupoID()
    {
        return \MZ\Product\Grupo::findByID($this->getGrupoID());
    }

    /**
     * Produto selecionável do grupo. Não pode conter propriedade.
     * @return \MZ\Product\Produto The object fetched from database
     */
    public function findProdutoID()
    {
        if (is_null($this->getProdutoID())) {
            return new \MZ\Product\Produto();
        }
        return \MZ\Product\Produto::findByID($this->getProdutoID());
    }

    /**
     * Propriedade selecionável do grupo. Não pode conter produto.
     * @return \MZ\Product\Propriedade The object fetched from database
     */
    public function findPropriedadeID()
    {
        if (is_null($this->getPropriedadeID())) {
            return new \MZ\Product\Propriedade();
        }
        return \MZ\Product\Propriedade::findByID($this->getPropriedadeID());
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

    /**
     * Get allowed keys array
     * @return array allowed keys array
     */
    private static function getAllowedKeys()
    {
        $pacote = new Pacote();
        $allowed = Filter::concatKeys('p.', $pacote->toArray());
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
        return Filter::orderBy($order, $allowed, 'p.');
    }

    /**
     * Filter condition array with allowed fields
     * @param  array $condition condition to filter rows
     * @return array allowed condition
     */
    private static function filterCondition($condition)
    {
        $allowed = self::getAllowedKeys();
        return Filter::keys($condition, $allowed, 'p.');
    }

    /**
     * Fetch data from database with a condition
     * @param  array $condition condition to filter rows
     * @param  array $order order rows
     * @return SelectQuery query object with condition statement
     */
    private static function query($condition = [], $order = [])
    {
        $query = DB::from('Pacotes p');
        $condition = self::filterCondition($condition);
        $query = DB::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('p.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Fetch data from database with a condition
     * @param  array $condition condition to filter rows
     * @return SelectQuery query object with condition statement
     */
    private static function queryEx($condition = [], $order = [])
    {
        $week_offset = Date::weekOffset();
        $query = DB::from('Pacotes p')
            ->leftJoin('Produtos d ON d.id = p.produtoid')
            ->leftJoin('Propriedades r ON r.id = p.propriedadeid')
            ->leftJoin('Pacotes a ON a.id = p.associacaoid')
            ->leftJoin('Unidades u ON u.id = d.unidadeid')
            ->leftJoin(
                'Promocoes m ON m.produtoid = d.id AND ' .
                '? BETWEEN m.inicio AND m.fim',
                $week_offset
            )
            ->select('a.grupoid as associacaogrupoid')
            ->select('COALESCE(d.precovenda + COALESCE(m.valor, 0), 0) as precobase')
            ->select('COALESCE(d.descricao, r.nome) as descricao')
            ->select('COALESCE(d.abreviacao, r.abreviacao) as abreviacao')
            ->select('d.detalhes')
            ->select('d.tipo as produtotipo')
            ->select('d.conteudo as produtoconteudo')
            ->select('u.sigla as unidadesigla')
            ->select(
                '(CASE WHEN d.imagem IS NULL AND r.imagem IS NULL THEN NULL ELSE '.
                DB::concat(['COALESCE(r.id, d.id)', '".png"']).
                ' END) as imagemurl'
            )
            ->select('COALESCE(r.dataatualizacao, d.dataatualizacao) as dataatualizacao');
        if (isset($condition['search'])) {
            $busca = $condition['search'];
            $query = DB::buildSearch(
                $busca,
                DB::concat([
                    'COALESCE(d.abreviacao, r.abreviacao, "")',
                    '" "',
                    'COALESCE(d.descricao, r.nome)'
                ]),
                $query
            );
        }
        $condition = self::filterCondition($condition);
        $query = DB::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('p.id ASC');
        return DB::buildCondition($query, $condition);
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
        $row = $query->fetch() ?: [];
        return new Pacote($row);
    }

    /**
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order order rows
     * @return array Array with fields or empty when not found
     */
    public static function rawFind($condition, $order = [])
    {
        $query = self::queryEx($condition, $order)->limit(1);
        return $query->fetch() ?: [];
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
     * @return array All rows data
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
