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

/**
 * Contém todos as opções para a formação do produto final
 */
class ZPacote
{
    private $id;
    private $pacote_id;
    private $grupo_id;
    private $produto_id;
    private $propriedade_id;
    private $associacao_id;
    private $quantidade;
    private $valor;
    private $selecionado;
    private $visivel;

    public function __construct($pacote = array())
    {
        if (is_array($pacote)) {
            $this->setID(isset($pacote['id'])?$pacote['id']:null);
            $this->setPacoteID(isset($pacote['pacoteid'])?$pacote['pacoteid']:null);
            $this->setGrupoID(isset($pacote['grupoid'])?$pacote['grupoid']:null);
            $this->setProdutoID(isset($pacote['produtoid'])?$pacote['produtoid']:null);
            $this->setPropriedadeID(isset($pacote['propriedadeid'])?$pacote['propriedadeid']:null);
            $this->setAssociacaoID(isset($pacote['associacaoid'])?$pacote['associacaoid']:null);
            $this->setQuantidade(isset($pacote['quantidade'])?$pacote['quantidade']:null);
            $this->setValor(isset($pacote['valor'])?$pacote['valor']:null);
            $this->setSelecionado(isset($pacote['selecionado'])?$pacote['selecionado']:null);
            $this->setVisivel(isset($pacote['visivel'])?$pacote['visivel']:null);
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
     * Pacote a qual pertence as informações de formação do produto final
     */
    public function getPacoteID()
    {
        return $this->pacote_id;
    }

    /**
     * Pacote a qual pertence as informações de formação do produto final
     */
    public function setPacoteID($pacote_id)
    {
        $this->pacote_id = $pacote_id;
    }

    /**
     * Grupo de formação, Ex.: Tamanho, Sabores e Complementos.
     */
    public function getGrupoID()
    {
        return $this->grupo_id;
    }

    /**
     * Grupo de formação, Ex.: Tamanho, Sabores e Complementos.
     */
    public function setGrupoID($grupo_id)
    {
        $this->grupo_id = $grupo_id;
    }

    /**
     * Produto selecionável do grupo. Não pode conter propriedade.
     */
    public function getProdutoID()
    {
        return $this->produto_id;
    }

    /**
     * Produto selecionável do grupo. Não pode conter propriedade.
     */
    public function setProdutoID($produto_id)
    {
        $this->produto_id = $produto_id;
    }

    /**
     * Propriedade selecionável do grupo. Não pode conter produto.
     */
    public function getPropriedadeID()
    {
        return $this->propriedade_id;
    }

    /**
     * Propriedade selecionável do grupo. Não pode conter produto.
     */
    public function setPropriedadeID($propriedade_id)
    {
        $this->propriedade_id = $propriedade_id;
    }

    /**
     * Informa a propriedade pai de um complemento, permite atribuir preços diferentes dependendo da propriedade, Ex.: Tamanho -> Sabor, onde Tamanho é pai de Sabor
     */
    public function getAssociacaoID()
    {
        return $this->associacao_id;
    }

    /**
     * Informa a propriedade pai de um complemento, permite atribuir preços diferentes dependendo da propriedade, Ex.: Tamanho -> Sabor, onde Tamanho é pai de Sabor
     */
    public function setAssociacaoID($associacao_id)
    {
        $this->associacao_id = $associacao_id;
    }

    /**
     * Quantidade que deve ser retirada do estoque para o produto selecionado, todos as quantidades são multiplicadas para obter a fração total
     */
    public function getQuantidade()
    {
        return $this->quantidade;
    }

    /**
     * Quantidade que deve ser retirada do estoque para o produto selecionado, todos as quantidades são multiplicadas para obter a fração total
     */
    public function setQuantidade($quantidade)
    {
        $this->quantidade = $quantidade;
    }

    /**
     * Valor acrescentado ao produto quando o item é selecionado
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Valor acrescentado ao produto quando o item é selecionado
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
    }

    /**
     * Informa se o complemento está selecionado por padrão, recomendado apenas para produtos
     */
    public function getSelecionado()
    {
        return $this->selecionado;
    }

    /**
     * Informa se o complemento está selecionado por padrão, recomendado apenas para produtos
     */
    public function isSelecionado()
    {
        return $this->selecionado == 'Y';
    }

    /**
     * Informa se o complemento está selecionado por padrão, recomendado apenas para produtos
     */
    public function setSelecionado($selecionado)
    {
        $this->selecionado = $selecionado;
    }

    /**
     * Indica se o pacote estará disponível para venda
     */
    public function getVisivel()
    {
        return $this->visivel;
    }

    /**
     * Indica se o pacote estará disponível para venda
     */
    public function isVisivel()
    {
        return $this->visivel == 'Y';
    }

    /**
     * Indica se o pacote estará disponível para venda
     */
    public function setVisivel($visivel)
    {
        $this->visivel = $visivel;
    }

    public function toArray()
    {
        $pacote = array();
        $pacote['id'] = $this->getID();
        $pacote['pacoteid'] = $this->getPacoteID();
        $pacote['grupoid'] = $this->getGrupoID();
        $pacote['produtoid'] = $this->getProdutoID();
        $pacote['propriedadeid'] = $this->getPropriedadeID();
        $pacote['associacaoid'] = $this->getAssociacaoID();
        $pacote['quantidade'] = $this->getQuantidade();
        $pacote['valor'] = $this->getValor();
        $pacote['selecionado'] = $this->getSelecionado();
        $pacote['visivel'] = $this->getVisivel();
        return $pacote;
    }

    public static function getPeloID($id)
    {
        $query = DB::$pdo->from('Pacotes')
                         ->where(array('id' => $id));
        return new ZPacote($query->fetch());
    }

    private static function validarCampos(&$pacote)
    {
        $erros = array();
        if (!is_numeric($pacote['pacoteid'])) {
            $erros['pacoteid'] = 'O pacote não foi informado';
        }
        if (!is_numeric($pacote['grupoid'])) {
            $erros['grupoid'] = 'O grupo não foi informado';
        }
        $pacote['produtoid'] = trim($pacote['produtoid']);
        if (strlen($pacote['produtoid']) == 0) {
            $pacote['produtoid'] = null;
        } elseif (!is_numeric($pacote['produtoid'])) {
            $erros['produtoid'] = 'O produto não foi informado';
        }
        $pacote['propriedadeid'] = trim($pacote['propriedadeid']);
        if (strlen($pacote['propriedadeid']) == 0) {
            $pacote['propriedadeid'] = null;
        } elseif (!is_numeric($pacote['propriedadeid'])) {
            $erros['propriedadeid'] = 'A propriedade não foi informada';
        }
        $pacote['associacaoid'] = trim($pacote['associacaoid']);
        if (strlen($pacote['associacaoid']) == 0) {
            $pacote['associacaoid'] = null;
        } elseif (!is_numeric($pacote['associacaoid'])) {
            $erros['associacaoid'] = 'A associação não foi informada';
        }
        if (!is_numeric($pacote['quantidade'])) {
            $erros['quantidade'] = 'A quantidade não foi informada';
        }
        if (!is_numeric($pacote['valor'])) {
            $erros['valor'] = 'O valor não foi informado';
        }
        $pacote['selecionado'] = trim($pacote['selecionado']);
        if (strlen($pacote['selecionado']) == 0) {
            $pacote['selecionado'] = 'N';
        } elseif (!in_array($pacote['selecionado'], array('Y', 'N'))) {
            $erros['selecionado'] = 'O selecionado informado não é válido';
        }
        $pacote['visivel'] = trim($pacote['visivel']);
        if (strlen($pacote['visivel']) == 0) {
            $pacote['visivel'] = 'N';
        } elseif (!in_array($pacote['visivel'], array('Y', 'N'))) {
            $erros['visivel'] = 'O visível informado não é válido';
        }
        if (!empty($erros)) {
            throw new ValidationException($erros);
        }
    }

    private static function handleException(&$e)
    {
        if (stripos($e->getMessage(), 'PRIMARY') !== false) {
            throw new ValidationException(array('id' => 'O ID informado já está cadastrado'));
        }
    }

    public static function cadastrar($pacote)
    {
        $_pacote = $pacote->toArray();
        self::validarCampos($_pacote);
        try {
            $_pacote['id'] = DB::$pdo->insertInto('Pacotes')->values($_pacote)->execute();
        } catch (Exception $e) {
            self::handleException($e);
            throw $e;
        }
        return self::getPeloID($_pacote['id']);
    }

    public static function atualizar($pacote)
    {
        $_pacote = $pacote->toArray();
        if (!$_pacote['id']) {
            throw new ValidationException(array('id' => 'O id do pacote não foi informado'));
        }
        self::validarCampos($_pacote);
        $campos = array(
            'pacoteid',
            'grupoid',
            'produtoid',
            'propriedadeid',
            'associacaoid',
            'quantidade',
            'valor',
            'selecionado',
            'visivel',
        );
        try {
            $query = DB::$pdo->update('Pacotes');
            $query = $query->set(array_intersect_key($_pacote, array_flip($campos)));
            $query = $query->where('id', $_pacote['id']);
            $query->execute();
        } catch (Exception $e) {
            self::handleException($e);
            throw $e;
        }
        return self::getPeloID($_pacote['id']);
    }

    public static function excluir($id)
    {
        if (!$id) {
            throw new Exception('Não foi possível excluir o pacote, o id do pacote não foi informado');
        }
        $query = DB::$pdo->deleteFrom('Pacotes')
                         ->where(array('id' => $id));
        return $query->execute();
    }

    public static function existe($produto_id)
    {
        $query = DB::$pdo->from('Pacotes')
                         ->where('produtoid', $produto_id);
        return $query->count() > 0;
    }

    private static function initSearch()
    {
        return   DB::$pdo->from('Pacotes')
                         ->orderBy('id ASC');
    }

    public static function getTodos($inicio = null, $quantidade = null)
    {
        $query = self::initSearch();
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_pacotes = $query->fetchAll();
        $pacotes = array();
        foreach ($_pacotes as $pacote) {
            $pacotes[] = new ZPacote($pacote);
        }
        return $pacotes;
    }

    public static function getCount()
    {
        $query = self::initSearch();
        return $query->count();
    }

    private static function initSearchDoPacoteID($pacote_id)
    {
        return   DB::$pdo->from('Pacotes')
                         ->where(array('pacoteid' => $pacote_id))
                         ->orderBy('id ASC');
    }

    public static function getTodosDoPacoteID($pacote_id, $inicio = null, $quantidade = null)
    {
        $query = self::initSearchDoPacoteID($pacote_id);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_pacotes = $query->fetchAll();
        $pacotes = array();
        foreach ($_pacotes as $pacote) {
            $pacotes[] = new ZPacote($pacote);
        }
        return $pacotes;
    }

    public static function getCountDoPacoteID($pacote_id)
    {
        $query = self::initSearchDoPacoteID($pacote_id);
        return $query->count();
    }

    private static function initSearchDoProdutoID($produto_id)
    {
        return   DB::$pdo->from('Pacotes')
                         ->where(array('produtoid' => $produto_id))
                         ->orderBy('id ASC');
    }

    public static function getTodosDoProdutoID($produto_id, $inicio = null, $quantidade = null)
    {
        $query = self::initSearchDoProdutoID($produto_id);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_pacotes = $query->fetchAll();
        $pacotes = array();
        foreach ($_pacotes as $pacote) {
            $pacotes[] = new ZPacote($pacote);
        }
        return $pacotes;
    }

    public static function getCountDoProdutoID($produto_id)
    {
        $query = self::initSearchDoProdutoID($produto_id);
        return $query->count();
    }

    private static function initSearchDoGrupoID($grupo_id, $pacotes, $busca)
    {
        $query = DB::$pdo->from('Pacotes pc')
                         ->leftJoin('Produtos p ON p.id = pc.produtoid')
                         ->leftJoin('Propriedades pr ON pr.id = pc.propriedadeid')
                         ->where(array('pc.visivel' => 'Y', 'pc.grupoid' => $grupo_id))
                         ->orderBy('pc.id ASC');
        if (!is_null($busca) && strlen($busca) > 0) {
            $keywords = preg_split('/[\s,]+/', $busca);
            $words = '';
            foreach ($keywords as $word) {
                $words .= '%'.$word.'%';
                $query = $query->orderBy('IF(LOCATE(?, '.
                    'CONCAT(" ", COALESCE(COALESCE(p.abreviacao, pr.abreviacao), ""), " ", COALESCE(p.descricao, pr.nome))) = 0, 256, LOCATE(?, '.
                    'CONCAT(" ", COALESCE(COALESCE(p.abreviacao, pr.abreviacao), ""), " ", COALESCE(p.descricao, pr.nome)))) ASC', ' '.$word, ' '.$word);
                $query = $query->orderBy('IF(LOCATE(?, '.
                    'CONCAT(COALESCE(COALESCE(p.abreviacao, pr.abreviacao), ""), " ", COALESCE(p.descricao, pr.nome))) = 0, 256, LOCATE(?, '.
                    'CONCAT(COALESCE(COALESCE(p.abreviacao, pr.abreviacao), ""), " ", COALESCE(p.descricao, pr.nome)))) ASC', $word, $word);
            }
            $query = $query->where('CONCAT(COALESCE(COALESCE(p.abreviacao, pr.abreviacao), ""), " ", COALESCE(p.descricao, pr.nome)) LIKE ?', $words);
        }
        if (!is_null($pacotes) && count($pacotes) > 0) {
            $query = $query->where('pc.associacaoid', $pacotes);
        }
        return $query;
    }

    public static function getTodosDoGrupoID($grupo_id, $pacotes = array(), $busca = null, $inicio = null, $quantidade = null)
    {
        $query = self::initSearchDoGrupoID($grupo_id, $pacotes, $busca);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_pacotes = $query->fetchAll();
        $pacotes = array();
        foreach ($_pacotes as $pacote) {
            $pacotes[] = new ZPacote($pacote);
        }
        return $pacotes;
    }

    public static function getTodosDoGrupoIDEx($grupo_id, $pacotes = array(), $busca = null, $inicio = null, $quantidade = null)
    {
        $query = self::initSearchDoGrupoID($grupo_id, $pacotes, $busca);
        $query = $query->leftJoin('Pacotes pca ON pca.id = pc.associacaoid')
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
                       ->select('IF(ISNULL(p.imagem) AND ISNULL(pr.imagem), NULL, CONCAT(IF(ISNULL(pc.produtoid), pr.id, p.id), ".png")) as imagemurl')
                       ->select('IF(ISNULL(pc.produtoid), pr.dataatualizacao, p.dataatualizacao) as dataatualizacao');
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        return $query->fetchAll();
    }

    public static function getCountDoGrupoID($grupo_id)
    {
        $query = self::initSearchDoGrupoID($grupo_id);
        return $query->count();
    }

    private static function initSearchDaAssociacaoID($associacao_id)
    {
        return   DB::$pdo->from('Pacotes')
                         ->where(array('associacaoid' => $associacao_id))
                         ->orderBy('id ASC');
    }

    public static function getTodosDaAssociacaoID($associacao_id, $inicio = null, $quantidade = null)
    {
        $query = self::initSearchDaAssociacaoID($associacao_id);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_pacotes = $query->fetchAll();
        $pacotes = array();
        foreach ($_pacotes as $pacote) {
            $pacotes[] = new ZPacote($pacote);
        }
        return $pacotes;
    }

    public static function getCountDaAssociacaoID($associacao_id)
    {
        $query = self::initSearchDaAssociacaoID($associacao_id);
        return $query->count();
    }

    private static function initSearchDaPropriedadeID($propriedade_id)
    {
        return   DB::$pdo->from('Pacotes')
                         ->where(array('propriedadeid' => $propriedade_id))
                         ->orderBy('id ASC');
    }

    public static function getTodosDaPropriedadeID($propriedade_id, $inicio = null, $quantidade = null)
    {
        $query = self::initSearchDaPropriedadeID($propriedade_id);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_pacotes = $query->fetchAll();
        $pacotes = array();
        foreach ($_pacotes as $pacote) {
            $pacotes[] = new ZPacote($pacote);
        }
        return $pacotes;
    }

    public static function getCountDaPropriedadeID($propriedade_id)
    {
        $query = self::initSearchDaPropriedadeID($propriedade_id);
        return $query->count();
    }
}
