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
 * Função ou cargo de um funcionário
 */
class ZFuncao
{
    private $id;
    private $descricao;
    private $salario_base;

    public function __construct($funcao = [])
    {
        if (is_array($funcao)) {
            $this->setID(isset($funcao['id'])?$funcao['id']:null);
            $this->setDescricao(isset($funcao['descricao'])?$funcao['descricao']:null);
            $this->setSalarioBase(isset($funcao['salariobase'])?$funcao['salariobase']:null);
        }
    }

    /**
     * Identificador da função
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
     * Descreve o nome da função
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
     * Salário base ou mínimo que será acrescentado comissões
     */
    public function getSalarioBase()
    {
        return $this->salario_base;
    }

    public function setSalarioBase($salario_base)
    {
        $this->salario_base = $salario_base;
    }

    public function toArray()
    {
        $funcao = [];
        $funcao['id'] = $this->getID();
        $funcao['descricao'] = $this->getDescricao();
        $funcao['salariobase'] = $this->getSalarioBase();
        return $funcao;
    }

    public static function getPeloID($id)
    {
        $query = \DB::$pdo->from('Funcoes')
                         ->where(['id' => $id]);
        return new Funcao($query->fetch());
    }

    public static function getPelaDescricao($descricao)
    {
        $query = \DB::$pdo->from('Funcoes')
                         ->where(['descricao' => $descricao]);
        return new Funcao($query->fetch());
    }

    private static function validarCampos(&$funcao)
    {
        $erros = [];
        $funcao['descricao'] = strip_tags(trim($funcao['descricao']));
        if (strlen($funcao['descricao']) == 0) {
            $erros['descricao'] = 'A descrição não pode ser vazia';
        }
        if (!is_numeric($funcao['salariobase'])) {
            $erros['salariobase'] = 'O salário base não foi informado';
        } elseif ($funcao['salariobase'] < 0) {
            $erros['salariobase'] = 'O salário base não pode ser negativo';
        }
        if (!empty($erros)) {
            throw new ValidationException($erros);
        }
    }

    private static function handleException(&$e)
    {
        if (stripos($e->getMessage(), 'PRIMARY') !== false) {
            throw new ValidationException(['id' => 'O ID informado já está cadastrado']);
        }
        if (stripos($e->getMessage(), 'Descricao_UNIQUE') !== false) {
            throw new ValidationException(['descricao' => 'A descrição informada já está cadastrada']);
        }
    }

    public static function cadastrar($funcao)
    {
        $_funcao = $funcao->toArray();
        self::validarCampos($_funcao);
        try {
            $_funcao['id'] = \DB::$pdo->insertInto('Funcoes')->values($_funcao)->execute();
        } catch (Exception $e) {
            self::handleException($e);
            throw $e;
        }
        return self::findByID($_funcao['id']);
    }

    public static function atualizar($funcao)
    {
        $_funcao = $funcao->toArray();
        if (!$_funcao['id']) {
            throw new ValidationException(['id' => 'O id da funcao não foi informado']);
        }
        self::validarCampos($_funcao);
        $campos = [
            'descricao',
            'salariobase',
        ];
        try {
            $query = \DB::$pdo->update('Funcoes');
            $query = $query->set(array_intersect_key($_funcao, array_flip($campos)));
            $query = $query->where('id', $_funcao['id']);
            $query->execute();
        } catch (Exception $e) {
            self::handleException($e);
            throw $e;
        }
        return self::findByID($_funcao['id']);
    }

    public static function excluir($id)
    {
        if (!$id) {
            throw new \Exception('Não foi possível excluir a funcao, o id da funcao não foi informado');
        }
        $query = \DB::$pdo->deleteFrom('Funcoes')
                         ->where(['id' => $id]);
        return $query->execute();
    }

    private static function initSearch($busca)
    {
        $query = \DB::$pdo->from('Funcoes')
                         ->orderBy('id ASC');
        $busca = trim($busca);
        if ($busca != '') {
            $query = $query->where('descricao LIKE ?', '%'.$busca.'%');
        }
        return $query;
    }

    public static function getTodas($busca = null, $inicio = null, $quantidade = null)
    {
        $query = self::initSearch($busca);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_funcaos = $query->fetchAll();
        $funcaos = [];
        foreach ($_funcaos as $funcao) {
            $funcaos[] = new Funcao($funcao);
        }
        return $funcaos;
    }

    public static function getCount($busca = null)
    {
        $query = self::initSearch($busca);
        return $query->count();
    }
}
