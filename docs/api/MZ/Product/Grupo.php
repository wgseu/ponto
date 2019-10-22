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
 * Grupos de pacotes, permite criar grupos como Tamanho, Sabores para
 * formações de produtos
 */
class Grupo extends SyncModel
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
     * Nome resumido do grupo da formação, Exemplo: Tamanho, Sabores
     */
    private $nome;
    /**
     * Descrição do grupo da formação, Exemplo: Escolha o tamanho, Escolha os
     * sabores
     */
    private $descricao;
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
     * Informa a ordem de exibição dos grupos
     */
    private $ordem;
    /**
     * Data em que o grupo foi arquivado e não será mais usado
     */
    private $data_arquivado;

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
     * @return int id of Grupo
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param int $id Set id for Grupo
     * @return self Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Informa o pacote base da formação
     * @return int pacote of Grupo
     */
    public function getProdutoID()
    {
        return $this->produto_id;
    }

    /**
     * Set ProdutoID value to new on param
     * @param int $produto_id Set pacote for Grupo
     * @return self Self instance
     */
    public function setProdutoID($produto_id)
    {
        $this->produto_id = $produto_id;
        return $this;
    }

    /**
     * Nome resumido do grupo da formação, Exemplo: Tamanho, Sabores
     * @return string nome of Grupo
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set Nome value to new on param
     * @param string $nome Set nome for Grupo
     * @return self Self instance
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * Descrição do grupo da formação, Exemplo: Escolha o tamanho, Escolha os
     * sabores
     * @return string descrição of Grupo
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Set Descricao value to new on param
     * @param string $descricao Set descrição for Grupo
     * @return self Self instance
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * Informa se a formação final será apenas uma unidade ou vários itens
     * @return string tipo of Grupo
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set Tipo value to new on param
     * @param string $tipo Set tipo for Grupo
     * @return self Self instance
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
        return $this;
    }

    /**
     * Permite definir uma quantidade mínima obrigatória para continuar com a
     * venda
     * @return int quantidade mínima of Grupo
     */
    public function getQuantidadeMinima()
    {
        return $this->quantidade_minima;
    }

    /**
     * Set QuantidadeMinima value to new on param
     * @param int $quantidade_minima Set quantidade mínima for Grupo
     * @return self Self instance
     */
    public function setQuantidadeMinima($quantidade_minima)
    {
        $this->quantidade_minima = $quantidade_minima;
        return $this;
    }

    /**
     * Define a quantidade máxima de itens que podem ser escolhidos
     * @return int quantidade máxima of Grupo
     */
    public function getQuantidadeMaxima()
    {
        return $this->quantidade_maxima;
    }

    /**
     * Set QuantidadeMaxima value to new on param
     * @param int $quantidade_maxima Set quantidade máxima for Grupo
     * @return self Self instance
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
     * @return string função de preço of Grupo
     */
    public function getFuncao()
    {
        return $this->funcao;
    }

    /**
     * Set Funcao value to new on param
     * @param string $funcao Set função de preço for Grupo
     * @return self Self instance
     */
    public function setFuncao($funcao)
    {
        $this->funcao = $funcao;
        return $this;
    }

    /**
     * Informa a ordem de exibição dos grupos
     * @return int ordem of Grupo
     */
    public function getOrdem()
    {
        return $this->ordem;
    }

    /**
     * Set Ordem value to new on param
     * @param int $ordem Set ordem for Grupo
     * @return self Self instance
     */
    public function setOrdem($ordem)
    {
        $this->ordem = $ordem;
        return $this;
    }

    /**
     * Data em que o grupo foi arquivado e não será mais usado
     * @return string data de arquivação of Grupo
     */
    public function getDataArquivado()
    {
        return $this->data_arquivado;
    }

    /**
     * Set DataArquivado value to new on param
     * @param string $data_arquivado Set data de arquivação for Grupo
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
        $grupo = parent::toArray($recursive);
        $grupo['id'] = $this->getID();
        $grupo['produtoid'] = $this->getProdutoID();
        $grupo['nome'] = $this->getNome();
        $grupo['descricao'] = $this->getDescricao();
        $grupo['tipo'] = $this->getTipo();
        $grupo['quantidademinima'] = $this->getQuantidadeMinima();
        $grupo['quantidademaxima'] = $this->getQuantidadeMaxima();
        $grupo['funcao'] = $this->getFuncao();
        $grupo['ordem'] = $this->getOrdem();
        $grupo['dataarquivado'] = $this->getDataArquivado();
        return $grupo;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param mixed $grupo Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($grupo = [])
    {
        if ($grupo instanceof self) {
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
        if (!isset($grupo['nome'])) {
            $this->setNome(null);
        } else {
            $this->setNome($grupo['nome']);
        }
        if (!isset($grupo['descricao'])) {
            $this->setDescricao(null);
        } else {
            $this->setDescricao($grupo['descricao']);
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
        if (!isset($grupo['ordem'])) {
            $this->setOrdem(null);
        } else {
            $this->setOrdem($grupo['ordem']);
        }
        if (!array_key_exists('dataarquivado', $grupo)) {
            $this->setDataArquivado(null);
        } else {
            $this->setDataArquivado($grupo['dataarquivado']);
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
        $grupo = parent::publish($requester);
        return $grupo;
    }

    /**
     * Informa se é possível selecionar mais de um produto ou opção do produto
     * @return boolean Check if o of Multiplo is selected or checked
     */
    public function isMultiplo()
    {
        return $this->getQuantidadeMaxima() == 0 || $this->getQuantidadeMaxima() > 1;
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
        $this->setProdutoID(Filter::number($this->getProdutoID()));
        $this->setNome(Filter::string($this->getNome()));
        $this->setDescricao(Filter::string($this->getDescricao()));
        $this->setQuantidadeMinima(Filter::number($this->getQuantidadeMinima()));
        $this->setQuantidadeMaxima(Filter::number($this->getQuantidadeMaxima()));
        $this->setOrdem(Filter::number($this->getOrdem()));
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
     * @return array All field of Grupo in array format
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function validate()
    {
        $errors = [];
        $produto = $this->findProdutoID();
        if (is_null($this->getProdutoID())) {
            $errors['produtoid'] = _t('grupo.produto_id_cannot_empty');
        } elseif ($produto->getTipo() != Produto::TIPO_PACOTE) {
            $errors['produtoid'] = _t('grupo.produto_id_not_package');
        }
        if (is_null($this->getNome())) {
            $errors['nome'] = _t('grupo.nome_cannot_empty');
        }
        if (is_null($this->getDescricao())) {
            $errors['descricao'] = _t('grupo.descricao_cannot_empty');
        }
        if (!Validator::checkInSet($this->getTipo(), self::getTipoOptions())) {
            $errors['tipo'] = _t('grupo.tipo_invalid');
        }
        if (is_null($this->getQuantidadeMinima())) {
            $errors['quantidademinima'] = _t('grupo.quantidade_minima_cannot_empty');
        }
        if (is_null($this->getQuantidadeMaxima())) {
            $errors['quantidademaxima'] = _t('grupo.quantidade_maxima_cannot_empty');
        }
        if (!Validator::checkInSet($this->getFuncao(), self::getFuncaoOptions())) {
            $errors['funcao'] = _t('grupo.funcao_invalid');
        }
        if (is_null($this->getOrdem())) {
            $errors['ordem'] = _t('grupo.ordem_cannot_empty');
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
        if (contains(['ProdutoID', 'Descricao', 'UNIQUE'], $e->getMessage())) {
            return new ValidationException([
                'produtoid' => _t(
                    'grupo.produto_id_used',
                    $this->getProdutoID()
                ),
                'descricao' => _t(
                    'grupo.descricao_used',
                    $this->getDescricao()
                ),
            ]);
        }
        if (contains(['ProdutoID', 'Nome', 'UNIQUE'], $e->getMessage())) {
            return new ValidationException([
                'produtoid' => _t(
                    'grupo.produto_id_used',
                    $this->getProdutoID()
                ),
                'nome' => _t(
                    'grupo.nome_used',
                    $this->getNome()
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Load into this object from database using, ProdutoID, Descricao
     * @return self Self filled instance or empty when not found
     */
    public function loadByProdutoIDDescricao()
    {
        return $this->load([
            'produtoid' => intval($this->getProdutoID()),
            'descricao' => strval($this->getDescricao()),
        ]);
    }

    /**
     * Load into this object from database using, ProdutoID, Nome
     * @return self Self filled instance or empty when not found
     */
    public function loadByProdutoIDNome()
    {
        return $this->load([
            'produtoid' => intval($this->getProdutoID()),
            'nome' => strval($this->getNome()),
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
     * @param int $index choose option from index
     * @return string[] A associative key -> translated representative text or text for index
     */
    public static function getTipoOptions($index = null)
    {
        $options = [
            self::TIPO_INTEIRO => _t('grupo.tipo_inteiro'),
            self::TIPO_FRACIONADO => _t('grupo.tipo_fracionado'),
        ];
        if (!is_null($index)) {
            return $options[$index];
        }
        return $options;
    }

    /**
     * Gets textual and translated Funcao for Grupo
     * @param int $index choose option from index
     * @return string[] A associative key -> translated representative text or text for index
     */
    public static function getFuncaoOptions($index = null)
    {
        $options = [
            self::FUNCAO_MINIMO => _t('grupo.funcao_minimo'),
            self::FUNCAO_MEDIA => _t('grupo.funcao_media'),
            self::FUNCAO_MAXIMO => _t('grupo.funcao_maximo'),
            self::FUNCAO_SOMA => _t('grupo.funcao_soma'),
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
            $field = 'g.descricao LIKE ?';
            $condition[$field] = '%'.$search.'%';
            $allowed[$field] = true;
            unset($condition['search']);
        }
        return Filter::keys($condition, $allowed, 'g.');
    }

    /**
     * Fetch data from database with a condition
     * @param array $condition condition to filter rows
     * @param array $order order rows
     * @return SelectQuery query object with condition statement
     */
    protected function query($condition = [], $order = [])
    {
        $query = DB::from('Grupos g');
        $condition = $this->filterCondition($condition);
        $query = DB::buildOrderBy($query, $this->filterOrder($order));
        $query = $query->orderBy('g.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Fetch data from database with a condition
     * @param  array $condition condition to filter rows
     * @param  array $order order rows
     * @return SelectQuery query object with condition statement
     */
    private static function queryEx($condition = [], $order = [])
    {
        $instance = new self();
        $query = DB::from('Grupos g')
            ->select('a.grupoid as grupoassociadoid')
            ->innerJoin('Pacotes c ON c.grupoid = g.id')
            ->leftJoin('Pacotes p ON p.grupoid = c.grupoid AND p.id > c.id')
            ->leftJoin('Pacotes a ON a.id = c.associacaoid')
            ->where(['p.id' => null]);
        $condition = $instance->filterCondition($condition);
        $query = DB::buildOrderBy($query, $instance->filterOrder($order));
        $query = $query->orderBy('g.id ASC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Find this object on database using, ProdutoID, Descricao
     * @param int $produto_id pacote to find Grupo
     * @param string $descricao descrição to find Grupo
     * @return self A filled instance or empty when not found
     */
    public static function findByProdutoIDDescricao($produto_id, $descricao)
    {
        $result = new self();
        $result->setProdutoID($produto_id);
        $result->setDescricao($descricao);
        return $result->loadByProdutoIDDescricao();
    }

    /**
     * Find this object on database using, ProdutoID, Nome
     * @param int $produto_id pacote to find Grupo
     * @param string $nome nome to find Grupo
     * @return self A filled instance or empty when not found
     */
    public static function findByProdutoIDNome($produto_id, $nome)
    {
        $result = new self();
        $result->setProdutoID($produto_id);
        $result->setNome($nome);
        return $result->loadByProdutoIDNome();
    }

    /**
     * Find all Grupo
     * @param  array  $condition Condition to get all Grupo
     * @param  array  $order     Order Grupo
     * @param  int    $limit     Limit data into row count
     * @param  int    $offset    Start offset to get rows
     * @return array             List of all rows instanced as Grupo
     */
    public static function rawFindAllEx($condition = [], $order = [], $limit = null, $offset = null)
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
