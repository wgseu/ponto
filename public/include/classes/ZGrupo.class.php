<?php
/*
	Copyright 2014 da MZ Software - MZ Desenvolvimento de Sistemas LTDA
	Este arquivo é parte do programa GrandChef - Sistema para Gerenciamento de Churrascarias, Bares e Restaurantes.
	O GrandChef é um software proprietário; você não pode redistribuí-lo e/ou modificá-lo.
	DISPOSIÇÕES GERAIS
	O cliente não deverá remover qualquer identificação do produto, avisos de direitos autorais,
	ou outros avisos ou restrições de propriedade do GrandChef.

	O cliente não deverá causar ou permitir a engenharia reversa, desmontagem,
	ou descompilação do GrandChef.

	PROPRIEDADE DOS DIREITOS AUTORAIS DO PROGRAMA

	GrandChef é a especialidade do desenvolvedor e seus
	licenciadores e é protegido por direitos autorais, segredos comerciais e outros direitos
	de leis de propriedade.

	O Cliente adquire apenas o direito de usar o software e não adquire qualquer outros
	direitos, expressos ou implícitos no GrandChef diferentes dos especificados nesta Licença.
*/
class GrupoTipo
{
    const INTEIRO = 'Inteiro';
    const FRACIONADO = 'Fracionado';
}
class GrupoFuncao
{
    const MINIMO = 'Minimo';
    const MEDIA = 'Media';
    const MAXIMO = 'Maximo';
    const SOMA = 'Soma';
}

/**
 * Grupos de pacotes, permite criar grupos como Tamanho, Sabores para formações de produtos
 */
class ZGrupo
{
    private $id;
    private $produto_id;
    private $descricao;
    private $multiplo;
    private $tipo;
    private $quantidade_minima;
    private $quantidade_maxima;
    private $funcao;
    // extra
    private $grupo_associado_id;

    public function __construct($grupo = [])
    {
        if (is_array($grupo)) {
            $this->setID(isset($grupo['id'])?$grupo['id']:null);
            $this->setProdutoID(isset($grupo['produtoid'])?$grupo['produtoid']:null);
            $this->setDescricao(isset($grupo['descricao'])?$grupo['descricao']:null);
            $this->setMultiplo(isset($grupo['multiplo'])?$grupo['multiplo']:null);
            $this->setTipo(isset($grupo['tipo'])?$grupo['tipo']:null);
            $this->setQuantidadeMinima(isset($grupo['quantidademinima'])?$grupo['quantidademinima']:null);
            $this->setQuantidadeMaxima(isset($grupo['quantidademaxima'])?$grupo['quantidademaxima']:null);
            $this->setFuncao(isset($grupo['funcao'])?$grupo['funcao']:null);
            // extra
            $this->setGrupoAssociadoID(isset($grupo['grupoassociadoid'])?$grupo['grupoassociadoid']:null);
        }
    }

    public function getID()
    {
        return $this->id;
    }

    public function setID($id)
    {
        $this->id = $id;
    }

    /**
     * Informa o produto base da formação
     */
    public function getProdutoID()
    {
        return $this->produto_id;
    }

    /**
     * Informa o produto base da formação
     */
    public function setProdutoID($produto_id)
    {
        $this->produto_id = $produto_id;
    }

    /**
     * Descrição do grupo da formação, Exemplo: Tamanho, Sabores
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Descrição do grupo da formação, Exemplo: Tamanho, Sabores
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }

    /**
     * Informa se é possível selecionar mais de um produto ou opção do produto
     */
    public function getMultiplo()
    {
        return $this->multiplo;
    }

    /**
     * Informa se é possível selecionar mais de um produto ou opção do produto
     */
    public function isMultiplo()
    {
        return $this->multiplo == 'Y';
    }

    /**
     * Informa se é possível selecionar mais de um produto ou opção do produto
     */
    public function setMultiplo($multiplo)
    {
        $this->multiplo = $multiplo;
    }

    /**
     * Informa se a formação final será apenas uma unidade ou vários itens
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Informa se a formação final será apenas uma unidade ou vários itens
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    /**
     * Permite definir uma quantidade mínima obrigatória para continuar com a venda
     */
    public function getQuantidadeMinima()
    {
        return $this->quantidade_minima;
    }

    /**
     * Permite definir uma quantidade mínima obrigatória para continuar com a venda
     */
    public function setQuantidadeMinima($quantidade_minima)
    {
        $this->quantidade_minima = $quantidade_minima;
    }

    /**
     * Define a quantidade máxima de itens que podem ser escolhidos
     */
    public function getQuantidadeMaxima()
    {
        return $this->quantidade_maxima;
    }

    /**
     * Define a quantidade máxima de itens que podem ser escolhidos
     */
    public function setQuantidadeMaxima($quantidade_maxima)
    {
        $this->quantidade_maxima = $quantidade_maxima;
    }

    /**
     * Informa qual será a fórmula de cálculo do preço, Mínimo: obtém o menor preço, Média:  define o preço do produto como a média dos itens selecionados, Máximo: Obtém o preço do item mais caro do grupo, Soma: Soma todos os preços dos produtos selecionados
     */
    public function getFuncao()
    {
        return $this->funcao;
    }

    /**
     * Informa qual será a fórmula de cálculo do preço, Mínimo: obtém o menor preço, Média:  define o preço do produto como a média dos itens selecionados, Máximo: Obtém o preço do item mais caro do grupo, Soma: Soma todos os preços dos produtos selecionados
     */
    public function setFuncao($funcao)
    {
        $this->funcao = $funcao;
    }
    // extra
    public function getGrupoAssociadoID()
    {
        return $this->grupo_associado_id;
    }

    public function setGrupoAssociadoID($grupo_associado_id)
    {
        $this->grupo_associado_id = $grupo_associado_id;
    }

    public function toArray()
    {
        $grupo = [];
        $grupo['id'] = $this->getID();
        $grupo['produtoid'] = $this->getProdutoID();
        $grupo['descricao'] = $this->getDescricao();
        $grupo['multiplo'] = $this->getMultiplo();
        $grupo['tipo'] = $this->getTipo();
        $grupo['quantidademinima'] = $this->getQuantidadeMinima();
        $grupo['quantidademaxima'] = $this->getQuantidadeMaxima();
        $grupo['funcao'] = $this->getFuncao();
        // extra
        $grupo['grupoassociadoid'] = $this->getGrupoAssociadoID();
        return $grupo;
    }

    public static function getPeloID($id)
    {
        $query = \DB::$pdo->from('Grupos')
                         ->where(['id' => $id]);
        return new Grupo($query->fetch());
    }

    public static function getPeloProdutoIDDescricao($produto_id, $descricao)
    {
        $query = \DB::$pdo->from('Grupos')
                         ->where(['produtoid' => $produto_id, 'descricao' => $descricao]);
        return new Grupo($query->fetch());
    }

    private static function validarCampos(&$grupo)
    {
        $erros = [];
        if (!is_numeric($grupo['produtoid'])) {
            $erros['produtoid'] = 'O produto não foi informado';
        }
        $grupo['descricao'] = strip_tags(trim($grupo['descricao']));
        if (strlen($grupo['descricao']) == 0) {
            $erros['descricao'] = 'A descrição não pode ser vazia';
        }
        $grupo['multiplo'] = trim($grupo['multiplo']);
        if (strlen($grupo['multiplo']) == 0) {
            $grupo['multiplo'] = 'N';
        } elseif (!in_array($grupo['multiplo'], ['Y', 'N'])) {
            $erros['multiplo'] = 'O múltiplo informado não é válido';
        }
        $grupo['tipo'] = trim($grupo['tipo']);
        if (strlen($grupo['tipo']) == 0) {
            $grupo['tipo'] = null;
        } elseif (!in_array($grupo['tipo'], ['Inteiro', 'Fracionado'])) {
            $erros['tipo'] = 'O tipo informado não é válido';
        }
        if (!is_numeric($grupo['quantidademinima'])) {
            $erros['quantidademinima'] = 'A quantidade mínima não foi informada';
        } else {
            $grupo['quantidademinima'] = intval($grupo['quantidademinima']);
        }
        if (!is_numeric($grupo['quantidademaxima'])) {
            $erros['quantidademaxima'] = 'A quantidade máxima não foi informada';
        } else {
            $grupo['quantidademaxima'] = intval($grupo['quantidademaxima']);
        }
        $grupo['funcao'] = trim($grupo['funcao']);
        if (strlen($grupo['funcao']) == 0) {
            $grupo['funcao'] = null;
        } elseif (!in_array($grupo['funcao'], ['Minimo', 'Media', 'Maximo', 'Soma'])) {
            $erros['funcao'] = 'A função informada não é válida';
        }
        // extra
        unset($grupo['grupoassociadoid']);
        if (!empty($erros)) {
            throw new ValidationException($erros);
        }
    }

    private static function handleException(&$e)
    {
        if (stripos($e->getMessage(), 'PRIMARY') !== false) {
            throw new ValidationException(['id' => 'O ID informado já está cadastrado']);
        }
        if (stripos($e->getMessage(), 'UK_Grupos_Produto_Descricao') !== false) {
            throw new ValidationException(['descricao' => 'A descrição informada já está cadastrada']);
        }
    }

    public static function cadastrar($grupo)
    {
        $_grupo = $grupo->toArray();
        self::validarCampos($_grupo);
        try {
            $_grupo['id'] = \DB::$pdo->insertInto('Grupos')->values($_grupo)->execute();
        } catch (Exception $e) {
            self::handleException($e);
            throw $e;
        }
        return self::findByID($_grupo['id']);
    }

    public static function atualizar($grupo)
    {
        $_grupo = $grupo->toArray();
        if (!$_grupo['id']) {
            throw new ValidationException(['id' => 'O id do grupo não foi informado']);
        }
        self::validarCampos($_grupo);
        $campos = [
            'produtoid',
            'descricao',
            'multiplo',
            'tipo',
            'quantidademinima',
            'quantidademaxima',
            'funcao',
        ];
        try {
            $query = \DB::$pdo->update('Grupos');
            $query = $query->set(array_intersect_key($_grupo, array_flip($campos)));
            $query = $query->where('id', $_grupo['id']);
            $query->execute();
        } catch (Exception $e) {
            self::handleException($e);
            throw $e;
        }
        return self::findByID($_grupo['id']);
    }

    public static function excluir($id)
    {
        if (!$id) {
            throw new \Exception('Não foi possível excluir o grupo, o id do grupo não foi informado');
        }
        $query = \DB::$pdo->deleteFrom('Grupos')
                         ->where(['id' => $id]);
        return $query->execute();
    }

    private static function initSearch()
    {
        return   \DB::$pdo->from('Grupos')
                         ->orderBy('id ASC');
    }

    public static function getTodos($inicio = null, $quantidade = null)
    {
        $query = self::initSearch();
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_grupos = $query->fetchAll();
        $grupos = [];
        foreach ($_grupos as $grupo) {
            $grupos[] = new Grupo($grupo);
        }
        return $grupos;
    }

    public static function getCount()
    {
        $query = self::initSearch();
        return $query->count();
    }

    private static function initSearchDoProdutoID($produto_id)
    {
        return   \DB::$pdo->from('Grupos g')
                         ->select('pca.grupoid as grupoassociadoid')
                         ->innerJoin('Pacotes pc ON pc.grupoid = g.id')
                         ->leftJoin('Pacotes pcf ON pcf.grupoid = pc.grupoid AND pcf.id > pc.id')
                         ->leftJoin('Pacotes pca ON pca.id = pc.associacaoid')
                         ->where(['g.produtoid' => $produto_id, 'pcf.id' => null])
                         ->orderBy('g.id ASC');
    }

    public static function getTodosDoProdutoID($produto_id, $inicio = null, $quantidade = null)
    {
        $query = self::initSearchDoProdutoID($produto_id);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_grupos = $query->fetchAll();
        $grupos = [];
        foreach ($_grupos as $grupo) {
            $grupos[] = new Grupo($grupo);
        }
        return $grupos;
    }

    public static function getCountDoProdutoID($produto_id)
    {
        $query = self::initSearchDoProdutoID($produto_id);
        return $query->count();
    }
}
