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
 * Classificação se contas, permite atribuir um grupo de contas
 */
class ZClassificacao
{
    private $id;
    private $classificacao_id;
    private $descricao;

    public function __construct($classificacao = array())
    {
        if (is_array($classificacao)) {
            $this->setID(isset($classificacao['id'])?$classificacao['id']:null);
            $this->setClassificacaoID(isset($classificacao['classificacaoid'])?$classificacao['classificacaoid']:null);
            $this->setDescricao(isset($classificacao['descricao'])?$classificacao['descricao']:null);
        }
    }

    /**
     * Identificador da classificação
     */
    public function getID()
    {
        return $this->id;
    }

    public function setID($id)
    {
        $this->id = $id;
    }

    /**
     * Classificação superior, quando informado, esta classificação será uma
     * subclassificação
     */
    public function getClassificacaoID()
    {
        return $this->classificacao_id;
    }

    public function setClassificacaoID($classificacao_id)
    {
        $this->classificacao_id = $classificacao_id;
    }

    /**
     * Descrição da classificação
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }

    public function toArray()
    {
        $classificacao = array();
        $classificacao['id'] = $this->getID();
        $classificacao['classificacaoid'] = $this->getClassificacaoID();
        $classificacao['descricao'] = $this->getDescricao();
        return $classificacao;
    }

    public static function getPeloID($id)
    {
        $query = DB::$pdo->from('Classificacoes')
                         ->where(array('id' => $id));
        return new ZClassificacao($query->fetch());
    }

    public static function getPelaDescricao($descricao)
    {
        $query = DB::$pdo->from('Classificacoes')
                         ->where(array('descricao' => $descricao));
        return new ZClassificacao($query->fetch());
    }

    private static function validarCampos(&$classificacao)
    {
        $erros = array();
        $classificacao['classificacaoid'] = trim($classificacao['classificacaoid']);
        if (strlen($classificacao['classificacaoid']) == 0) {
            $classificacao['classificacaoid'] = null;
        } elseif (!is_numeric($classificacao['classificacaoid'])) {
            $erros['classificacaoid'] = 'A classificação superior não foi informada';
        }
        $classificacao['descricao'] = strip_tags(trim($classificacao['descricao']));
        if (strlen($classificacao['descricao']) == 0) {
            $erros['descricao'] = 'A descrição não pode ser vazia';
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
        if (stripos($e->getMessage(), 'Descricao_UNIQUE') !== false) {
            throw new ValidationException(array('descricao' => 'A descrição informada já está cadastrada'));
        }
    }

    public static function cadastrar($classificacao)
    {
        $_classificacao = $classificacao->toArray();
        self::validarCampos($_classificacao);
        try {
            $_classificacao['id'] = DB::$pdo->insertInto('Classificacoes')->values($_classificacao)->execute();
        } catch (Exception $e) {
            self::handleException($e);
            throw $e;
        }
        return self::getPeloID($_classificacao['id']);
    }

    public static function atualizar($classificacao)
    {
        $_classificacao = $classificacao->toArray();
        if (!$_classificacao['id']) {
            throw new ValidationException(array('id' => 'O id da classificacao não foi informado'));
        }
        self::validarCampos($_classificacao);
        $campos = array(
            'classificacaoid',
            'descricao',
        );
        try {
            $query = DB::$pdo->update('Classificacoes');
            $query = $query->set(array_intersect_key($_classificacao, array_flip($campos)));
            $query = $query->where('id', $_classificacao['id']);
            $query->execute();
        } catch (Exception $e) {
            self::handleException($e);
            throw $e;
        }
        return self::getPeloID($_classificacao['id']);
    }

    public static function excluir($id)
    {
        if (!$id) {
            throw new Exception('Não foi possível excluir a classificacao, o id da classificacao não foi informado');
        }
        $query = DB::$pdo->deleteFrom('Classificacoes')
                         ->where(array('id' => $id));
        return $query->execute();
    }

    private static function initSearch($superiores, $classificacao_id, $busca)
    {
        $query = DB::$pdo->from('Classificacoes')
                         ->orderBy('descricao ASC');
        if ($superiores) {
            $query = $query->where('classificacaoid', null);
        } elseif (is_numeric($classificacao_id)) {
            $query = $query->where('classificacaoid', $classificacao_id);
        }
        $busca = trim($busca);
        if ($busca != '') {
            $query = $query->where('descricao LIKE ?', '%'.$busca.'%');
        }
        return $query;
    }

    public static function getTodas($superiores = false, $classificacao_id = null, $busca = null, $inicio = null, $quantidade = null)
    {
        $query = self::initSearch($superiores, $classificacao_id, $busca);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_classificacaos = $query->fetchAll();
        $classificacaos = array();
        foreach ($_classificacaos as $classificacao) {
            $classificacaos[] = new ZClassificacao($classificacao);
        }
        return $classificacaos;
    }

    public static function getCount($superiores = false, $classificacao_id = null, $busca = null)
    {
        $query = self::initSearch($superiores, $classificacao_id, $busca);
        return $query->count();
    }

    private static function initSearchDaClassificacaoID($classificacao_id)
    {
        return   DB::$pdo->from('Classificacoes')
                         ->where(array('classificacaoid' => $classificacao_id))
                         ->orderBy('id ASC');
    }

    public static function getTodasDaClassificacaoID($classificacao_id, $inicio = null, $quantidade = null)
    {
        $query = self::initSearchDaClassificacaoID($classificacao_id);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_classificacaos = $query->fetchAll();
        $classificacaos = array();
        foreach ($_classificacaos as $classificacao) {
            $classificacaos[] = new ZClassificacao($classificacao);
        }
        return $classificacaos;
    }

    public static function getCountDaClassificacaoID($classificacao_id)
    {
        $query = self::initSearchDaClassificacaoID($classificacao_id);
        return $query->count();
    }
}
