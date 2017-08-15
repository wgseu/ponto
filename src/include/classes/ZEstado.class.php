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
 * Estado federativo de um país
 */
class ZEstado
{
    private $id;
    private $pais_id;
    private $nome;
    private $uf;

    public function __construct($estado = array())
    {
        if (is_array($estado)) {
            $this->setID(isset($estado['id'])?$estado['id']:null);
            $this->setPaisID(isset($estado['paisid'])?$estado['paisid']:null);
            $this->setNome(isset($estado['nome'])?$estado['nome']:null);
            $this->setUF(isset($estado['uf'])?$estado['uf']:null);
        }
    }

    /**
     * Identificador do estado
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
     * País a qual esse estado pertence
     */
    public function getPaisID()
    {
        return $this->pais_id;
    }

    public function setPaisID($pais_id)
    {
        $this->pais_id = $pais_id;
    }

    /**
     * Nome do estado
     */
    public function getNome()
    {
        return $this->nome;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    /**
     * Sigla do estado
     */
    public function getUF()
    {
        return $this->uf;
    }

    public function setUF($uf)
    {
        $this->uf = $uf;
    }

    public function toArray()
    {
        $estado = array();
        $estado['id'] = $this->getID();
        $estado['paisid'] = $this->getPaisID();
        $estado['nome'] = $this->getNome();
        $estado['uf'] = $this->getUF();
        return $estado;
    }

    public static function getPeloID($id)
    {
        $query = DB::$pdo->from('Estados')
                         ->where(array('id' => $id));
        return new ZEstado($query->fetch());
    }

    public static function getPelaPaisIDNome($pais_id, $nome)
    {
        $query = DB::$pdo->from('Estados')
                         ->where(array('paisid' => $pais_id, 'nome' => $nome));
        return new ZEstado($query->fetch());
    }

    public static function getPelaPaisIDUF($pais_id, $uf)
    {
        $query = DB::$pdo->from('Estados')
                         ->where(array('paisid' => $pais_id, 'uf' => $uf));
        return new ZEstado($query->fetch());
    }

    private static function validarCampos(&$estado)
    {
        $erros = array();
        if (!is_numeric($estado['paisid'])) {
            $erros['paisid'] = 'O país não foi informado';
        }
        $estado['nome'] = strip_tags(trim($estado['nome']));
        if (strlen($estado['nome']) == 0) {
            $erros['nome'] = 'O nome não pode ser vazio';
        }
        $estado['uf'] = strip_tags(trim($estado['uf']));
        if (strlen($estado['uf']) == 0) {
            $erros['uf'] = 'A UF não pode ser vazia';
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
        if (stripos($e->getMessage(), 'PaisID_Nome_UNIQUE') !== false) {
            throw new ValidationException(array('nome' => 'O nome informado já está cadastrado'));
        }
        if (stripos($e->getMessage(), 'PaisID_UF_UNIQUE') !== false) {
            throw new ValidationException(array('uf' => 'A UF informada já está cadastrada'));
        }
    }

    public static function cadastrar($estado)
    {
        $_estado = $estado->toArray();
        self::validarCampos($_estado);
        try {
            $_estado['id'] = DB::$pdo->insertInto('Estados')->values($_estado)->execute();
        } catch (Exception $e) {
            self::handleException($e);
            throw $e;
        }
        return self::getPeloID($_estado['id']);
    }

    public static function atualizar($estado)
    {
        $_estado = $estado->toArray();
        if (!$_estado['id']) {
            throw new ValidationException(array('id' => 'O id do estado não foi informado'));
        }
        self::validarCampos($_estado);
        $campos = array(
            'paisid',
            'nome',
            'uf',
        );
        try {
            $query = DB::$pdo->update('Estados');
            $query = $query->set(array_intersect_key($_estado, array_flip($campos)));
            $query = $query->where('id', $_estado['id']);
            $query->execute();
        } catch (Exception $e) {
            self::handleException($e);
            throw $e;
        }
        return self::getPeloID($_estado['id']);
    }

    public static function excluir($id)
    {
        if (!$id) {
            throw new Exception('Não foi possível excluir o estado, o id do estado não foi informado');
        }
        $query = DB::$pdo->deleteFrom('Estados')
                         ->where(array('id' => $id));
        return $query->execute();
    }

    private static function initSearch($busca, $pais_id)
    {
        $query = DB::$pdo->from('Estados')
                         ->orderBy('nome ASC');
        $busca = trim($busca);
        if ($busca != '') {
            $query = $query->where('CONCAT(nome, " ", uf) LIKE ?', '%'.$busca.'%');
        }
        if (is_numeric($pais_id)) {
            $query = $query->where('paisid', $pais_id);
        }
        return $query;
    }

    public static function getTodos($busca = null, $pais_id = null, $inicio = null, $quantidade = null)
    {
        $query = self::initSearch($busca, $pais_id);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_estados = $query->fetchAll();
        $estados = array();
        foreach ($_estados as $estado) {
            $estados[] = new ZEstado($estado);
        }
        return $estados;
    }

    public static function getCount($busca = null, $pais_id = null)
    {
        $query = self::initSearch($busca, $pais_id);
        return $query->count();
    }

    private static function initSearchDaPaisID($pais_id)
    {
        return   DB::$pdo->from('Estados')
                         ->where(array('paisid' => $pais_id))
                         ->orderBy('id ASC');
    }

    public static function getTodosDaPaisID($pais_id, $inicio = null, $quantidade = null)
    {
        $query = self::initSearchDaPaisID($pais_id);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_estados = $query->fetchAll();
        $estados = array();
        foreach ($_estados as $estado) {
            $estados[] = new ZEstado($estado);
        }
        return $estados;
    }

    public static function getCountDaPaisID($pais_id)
    {
        $query = self::initSearchDaPaisID($pais_id);
        return $query->count();
    }
}
