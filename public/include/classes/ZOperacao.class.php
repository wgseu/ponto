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
 * Código Fiscal de Operações e Prestações (CFOP)
 */
class ZOperacao
{
    private $id;
    private $codigo;
    private $descricao;
    private $detalhes;

    public function __construct($operacao = [])
    {
        $this->fromArray($operacao);
    }

    /**
     * Identificador da operação
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
     * Código CFOP sem pontuação
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    /**
     * Descrição da operação
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }

    /**
     * Detalhes da operação (Opcional)
     */
    public function getDetalhes()
    {
        return $this->detalhes;
    }

    public function setDetalhes($detalhes)
    {
        $this->detalhes = $detalhes;
    }

    public function toArray()
    {
        $operacao = [];
        $operacao['id'] = $this->getID();
        $operacao['codigo'] = $this->getCodigo();
        $operacao['descricao'] = $this->getDescricao();
        $operacao['detalhes'] = $this->getDetalhes();
        return $operacao;
    }

    public function fromArray($operacao = [])
    {
        if (!is_array($operacao)) {
            return $this;
        }
        $this->setID(isset($operacao['id'])?$operacao['id']:null);
        $this->setCodigo(isset($operacao['codigo'])?$operacao['codigo']:null);
        $this->setDescricao(isset($operacao['descricao'])?$operacao['descricao']:null);
        $this->setDetalhes(isset($operacao['detalhes'])?$operacao['detalhes']:null);
    }

    public static function getPeloID($id)
    {
        $query = \DB::$pdo->from('Operacoes')
                         ->where(['id' => $id]);
        return new Operacao($query->fetch());
    }

    public static function getPeloCodigo($codigo)
    {
        $query = \DB::$pdo->from('Operacoes')
                         ->where(['codigo' => $codigo]);
        return new Operacao($query->fetch());
    }

    private static function validarCampos(&$operacao)
    {
        $erros = [];
        if (!is_numeric($operacao['codigo'])) {
            $erros['codigo'] = 'O código não foi informado';
        }
        $operacao['descricao'] = strip_tags(trim($operacao['descricao']));
        if (strlen($operacao['descricao']) == 0) {
            $erros['descricao'] = 'A descrição não pode ser vazia';
        }
        $operacao['detalhes'] = strip_tags(trim($operacao['detalhes']));
        if (strlen($operacao['detalhes']) == 0) {
            $operacao['detalhes'] = null;
        }
        if (!empty($erros)) {
            throw new ValidationException($erros);
        }
    }

    private static function handleException(&$e)
    {
        if (stripos($e->getMessage(), 'PRIMARY') !== false) {
            throw new ValidationException(['id' => 'O id informado já está cadastrado']);
        }
        if (stripos($e->getMessage(), 'Codigo_UNIQUE') !== false) {
            throw new ValidationException(['codigo' => 'O código informado já está cadastrado']);
        }
    }

    public static function cadastrar($operacao)
    {
        $_operacao = $operacao->toArray();
        self::validarCampos($_operacao);
        try {
            $_operacao['id'] = \DB::$pdo->insertInto('Operacoes')->values($_operacao)->execute();
        } catch (Exception $e) {
            self::handleException($e);
            throw $e;
        }
        return self::findByID($_operacao['id']);
    }

    public static function atualizar($operacao)
    {
        $_operacao = $operacao->toArray();
        if (!$_operacao['id']) {
            throw new ValidationException(['id' => 'O id da operacao não foi informado']);
        }
        self::validarCampos($_operacao);
        $campos = [
            'codigo',
            'descricao',
            'detalhes',
        ];
        try {
            $query = \DB::$pdo->update('Operacoes');
            $query = $query->set(array_intersect_key($_operacao, array_flip($campos)));
            $query = $query->where('id', $_operacao['id']);
            $query->execute();
        } catch (Exception $e) {
            self::handleException($e);
            throw $e;
        }
        return self::findByID($_operacao['id']);
    }

    public static function excluir($id)
    {
        if (!$id) {
            throw new \Exception('Não foi possível excluir a operacao, o id da operacao não foi informado');
        }
        $query = \DB::$pdo->deleteFrom('Operacoes')
                         ->where(['id' => $id]);
        return $query->execute();
    }

    private static function initSearch()
    {
        return   \DB::$pdo->from('Operacoes')
                         ->orderBy('id ASC');
    }

    public static function getTodas($inicio = null, $quantidade = null)
    {
        $query = self::initSearch();
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_operacaos = $query->fetchAll();
        $operacaos = [];
        foreach ($_operacaos as $operacao) {
            $operacaos[] = new Operacao($operacao);
        }
        return $operacaos;
    }

    public static function getCount()
    {
        $query = self::initSearch();
        return $query->count();
    }
}
