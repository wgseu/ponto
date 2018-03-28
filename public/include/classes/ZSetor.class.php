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
 * Setor de impressão e de estoque
 */
class ZSetor
{
    private $id;
    private $nome;
    private $descricao;

    public function __construct($setor = [])
    {
        if (is_array($setor)) {
            $this->setID(isset($setor['id'])?$setor['id']:null);
            $this->setNome(isset($setor['nome'])?$setor['nome']:null);
            $this->setDescricao(isset($setor['descricao'])?$setor['descricao']:null);
        }
    }

    /**
     * Identificador do setor
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Identificador do setor
     */
    public function setID($id)
    {
        $this->id = $id;
    }

    /**
     * Nome do setor, único em todo o sistema
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Nome do setor, único em todo o sistema
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    /**
     * Descreve a utilização do setor
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Descreve a utilização do setor
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }

    public function toArray()
    {
        $setor = [];
        $setor['id'] = $this->getID();
        $setor['nome'] = $this->getNome();
        $setor['descricao'] = $this->getDescricao();
        return $setor;
    }

    public static function getPeloID($id)
    {
        $query = \DB::$pdo->from('Setores')
                         ->where(['id' => $id]);
        return new Setor($query->fetch());
    }

    public static function getPrimeiro()
    {
        $query = \DB::$pdo->from('Setores')
                         ->limit(1)->offset(0);
        return new Setor($query->fetch());
    }

    public static function getPeloNome($nome)
    {
        $query = \DB::$pdo->from('Setores')
                         ->where(['nome' => $nome])
                         ->limit(1)->offset(0);
        return new Setor($query->fetch());
    }

    private static function validarCampos(&$setor)
    {
        $erros = [];
        $setor['nome'] = strip_tags(trim($setor['nome']));
        if (strlen($setor['nome']) == 0) {
            $erros['nome'] = 'O nome não pode ser vazio';
        }
        $setor['descricao'] = strip_tags(trim($setor['descricao']));
        if (strlen($setor['descricao']) == 0) {
            $setor['descricao'] = null;
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
        if (stripos($e->getMessage(), 'Nome_UNIQUE') !== false) {
            throw new ValidationException(['nome' => 'O nome informado já está cadastrado']);
        }
    }

    public static function cadastrar($setor)
    {
        $_setor = $setor->toArray();
        self::validarCampos($_setor);
        try {
            $_setor['id'] = \DB::$pdo->insertInto('Setores')->values($_setor)->execute();
        } catch (Exception $e) {
            self::handleException($e);
            throw $e;
        }
        return self::findByID($_setor['id']);
    }

    public static function atualizar($setor)
    {
        $_setor = $setor->toArray();
        if (!$_setor['id']) {
            throw new ValidationException(['id' => 'O id do setor não foi informado']);
        }
        self::validarCampos($_setor);
        $campos = [
            'nome',
            'descricao',
        ];
        try {
            $query = \DB::$pdo->update('Setores');
            $query = $query->set(array_intersect_key($_setor, array_flip($campos)));
            $query = $query->where('id', $_setor['id']);
            $query->execute();
        } catch (Exception $e) {
            self::handleException($e);
            throw $e;
        }
        return self::findByID($_setor['id']);
    }

    public static function excluir($id)
    {
        if (!$id) {
            throw new \Exception('Não foi possível excluir o setor, o id do setor não foi informado');
        }
        $query = \DB::$pdo->deleteFrom('Setores')
                         ->where(['id' => $id]);
        return $query->execute();
    }

    private static function initSearch($busca)
    {
        $query = \DB::$pdo->from('Setores')
                         ->orderBy('nome ASC');
        $busca = trim($busca);
        if ($busca != '') {
            $query = $query->where('CONCAT(nome, " ", descricao) LIKE ?', '%'.$busca.'%');
        }
        return $query;
    }

    public static function getTodos($busca = null, $inicio = null, $quantidade = null)
    {
        $query = self::initSearch($busca);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_setors = $query->fetchAll();
        $setors = [];
        foreach ($_setors as $setor) {
            $setors[] = new Setor($setor);
        }
        return $setors;
    }

    public static function getCount($busca = null)
    {
        $query = self::initSearch($busca);
        return $query->count();
    }
}
