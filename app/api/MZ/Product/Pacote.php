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
use MZ\Util\Date;
use MZ\Util\Filter;
use MZ\Util\Validator;
use MZ\Database\DB;
use MZ\Database\SyncModel;
use MZ\Exception\ValidationException;

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
     * Data em que o pacote foi arquivado e não será mais usado
     */
    private $data_arquivado;

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
     * @return int id of Pacote
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param int $id Set id for Pacote
     * @return self Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Pacote a qual pertence as informações de formação do produto final
     * @return int pacote of Pacote
     */
    public function getPacoteID()
    {
        return $this->pacote_id;
    }

    /**
     * Set PacoteID value to new on param
     * @param int $pacote_id Set pacote for Pacote
     * @return self Self instance
     */
    public function setPacoteID($pacote_id)
    {
        $this->pacote_id = $pacote_id;
        return $this;
    }

    /**
     * Grupo de formação, Ex.: Tamanho, Sabores e Complementos.
     * @return int grupo of Pacote
     */
    public function getGrupoID()
    {
        return $this->grupo_id;
    }

    /**
     * Set GrupoID value to new on param
     * @param int $grupo_id Set grupo for Pacote
     * @return self Self instance
     */
    public function setGrupoID($grupo_id)
    {
        $this->grupo_id = $grupo_id;
        return $this;
    }

    /**
     * Produto selecionável do grupo. Não pode conter propriedade.
     * @return int produto of Pacote
     */
    public function getProdutoID()
    {
        return $this->produto_id;
    }

    /**
     * Set ProdutoID value to new on param
     * @param int $produto_id Set produto for Pacote
     * @return self Self instance
     */
    public function setProdutoID($produto_id)
    {
        $this->produto_id = $produto_id;
        return $this;
    }

    /**
     * Propriedade selecionável do grupo. Não pode conter produto.
     * @return int propriedade of Pacote
     */
    public function getPropriedadeID()
    {
        return $this->propriedade_id;
    }

    /**
     * Set PropriedadeID value to new on param
     * @param int $propriedade_id Set propriedade for Pacote
     * @return self Self instance
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
     * @return int associação of Pacote
     */
    public function getAssociacaoID()
    {
        return $this->associacao_id;
    }

    /**
     * Set AssociacaoID value to new on param
     * @param int $associacao_id Set associação for Pacote
     * @return self Self instance
     */
    public function setAssociacaoID($associacao_id)
    {
        $this->associacao_id = $associacao_id;
        return $this;
    }

    /**
     * Permite definir uma quantidade mínima obrigatória para a venda desse
     * item
     * @return int quantidade mínima of Pacote
     */
    public function getQuantidadeMinima()
    {
        return $this->quantidade_minima;
    }

    /**
     * Set QuantidadeMinima value to new on param
     * @param int $quantidade_minima Set quantidade mínima for Pacote
     * @return self Self instance
     */
    public function setQuantidadeMinima($quantidade_minima)
    {
        $this->quantidade_minima = $quantidade_minima;
        return $this;
    }

    /**
     * Define a quantidade máxima que pode ser vendido esse item repetidamente
     * @return int quantidade máxima of Pacote
     */
    public function getQuantidadeMaxima()
    {
        return $this->quantidade_maxima;
    }

    /**
     * Set QuantidadeMaxima value to new on param
     * @param int $quantidade_maxima Set quantidade máxima for Pacote
     * @return self Self instance
     */
    public function setQuantidadeMaxima($quantidade_maxima)
    {
        $this->quantidade_maxima = $quantidade_maxima;
        return $this;
    }

    /**
     * Valor acrescentado ao produto quando o item é selecionado
     * @return string valor of Pacote
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Set Valor value to new on param
     * @param string $valor Set valor for Pacote
     * @return self Self instance
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
        return $this;
    }

    /**
     * Informa se o complemento está selecionado por padrão, recomendado apenas
     * para produtos
     * @return string selecionado of Pacote
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
     * @param string $selecionado Set selecionado for Pacote
     * @return self Self instance
     */
    public function setSelecionado($selecionado)
    {
        $this->selecionado = $selecionado;
        return $this;
    }

    /**
     * Indica se o pacote estará disponível para venda
     * @return string visível of Pacote
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
     * @param string $visivel Set visível for Pacote
     * @return self Self instance
     */
    public function setVisivel($visivel)
    {
        $this->visivel = $visivel;
        return $this;
    }

    /**
     * Data em que o pacote foi arquivado e não será mais usado
     * @return string data de arquivação of Pacote
     */
    public function getDataArquivado()
    {
        return $this->data_arquivado;
    }

    /**
     * Set DataArquivado value to new on param
     * @param string $data_arquivado Set data de arquivação for Pacote
     * @return self Self instance
     */
    public function setDataArquivado($data_arquivado)
    {
        $this->data_arquivado = $data_arquivado;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param boolean $recursive Allow rescursive conversion of fields
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
        $pacote['dataarquivado'] = $this->getDataArquivado();
        return $pacote;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param mixed $pacote Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($pacote = [])
    {
        if ($pacote instanceof self) {
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
        if (!array_key_exists('dataarquivado', $pacote)) {
            $this->setDataArquivado(null);
        } else {
            $this->setDataArquivado($pacote['dataarquivado']);
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
        $pacote = parent::publish($requester);
        return $pacote;
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
        $this->setPacoteID(Filter::number($this->getPacoteID()));
        $this->setGrupoID(Filter::number($this->getGrupoID()));
        $this->setProdutoID(Filter::number($this->getProdutoID()));
        $this->setPropriedadeID(Filter::number($this->getPropriedadeID()));
        $this->setAssociacaoID(Filter::number($this->getAssociacaoID()));
        $this->setQuantidadeMinima(Filter::number($this->getQuantidadeMinima()));
        $this->setQuantidadeMaxima(Filter::number($this->getQuantidadeMaxima()));
        $this->setValor(Filter::money($this->getValor(), $localized));
        $this->setDataArquivado(null);
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
     * @return array All field of Pacote in array format
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getPacoteID())) {
            $errors['pacoteid'] = _t('pacote.pacote_id_cannot_empty');
        }
        if (is_null($this->getGrupoID())) {
            $errors['grupoid'] = _t('pacote.grupo_id_cannot_empty');
        }
        if (is_null($this->getQuantidadeMinima())) {
            $errors['quantidademinima'] = _t('pacote.quantidade_minima_cannot_empty');
        }
        if (is_null($this->getQuantidadeMaxima())) {
            $errors['quantidademaxima'] = _t('pacote.quantidade_maxima_cannot_empty');
        }
        if (is_null($this->getValor())) {
            $errors['valor'] = _t('pacote.valor_cannot_empty');
        }
        if (!Validator::checkBoolean($this->getSelecionado())) {
            $errors['selecionado'] = _t('pacote.selecionado_invalid');
        }
        if (!Validator::checkBoolean($this->getVisivel())) {
            $errors['visivel'] = _t('pacote.visivel_invalid');
        }
        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
        return $this->toArray();
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
     * Filter condition array with allowed fields
     * @param array $condition condition to filter rows
     * @return array allowed condition
     */
    protected function filterCondition($condition)
    {
        $allowed = $this->getAllowedKeys();
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
        $query = DB::from('Pacotes p');
        $condition = $this->filterCondition($condition);
        $query = DB::buildOrderBy($query, $this->filterOrder($order));
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
            ->select('d.imagemurl')
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
        $instance = new self();
        $condition = $instance->filterCondition($condition);
        $query = DB::buildOrderBy($query, $instance->filterOrder($order));
        $query = $query->orderBy('p.id ASC');
        return DB::buildCondition($query, $condition);
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
}
