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
class ZMesa
{
    
    private $id;
    private $nome;
    private $ativa;
    // extra
    private $estado;
    private $juntaid;
    private $juntanome;

    public function __construct($mesa = array())
    {
        if (is_array($mesa)) {
            $this->setID(isset($mesa['id'])?$mesa['id']:null);
            $this->setNome(isset($mesa['nome'])?$mesa['nome']:null);
            $this->setAtiva(isset($mesa['ativa'])?$mesa['ativa']:null);
            // extra
            $this->setEstado(isset($mesa['estado'])?$mesa['estado']:null);
            $this->setJuntaID(isset($mesa['juntaid'])?$mesa['juntaid']:null);
            $this->setJuntaNome(isset($mesa['juntanome'])?$mesa['juntanome']:null);
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

    public function getNome()
    {
        return $this->nome;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    public function getAtiva()
    {
        return $this->ativa;
    }

    public function isAtiva()
    {
        return $this->ativa == 'Y';
    }

    public function setAtiva($ativa)
    {
        $this->ativa = $ativa;
    }

    // extra
    public function getEstado()
    {
        return $this->estado;
    }

    public function setEstado($estado)
    {
        $this->estado = $estado;
    }

    public function getJuntaID()
    {
        return $this->juntaid;
    }

    public function setJuntaID($juntaid)
    {
        $this->juntaid = $juntaid;
    }

    public function getJuntaNome()
    {
        return $this->juntanome;
    }

    public function setJuntaNome($juntanome)
    {
        $this->juntanome = $juntanome;
    }

    public function toArray()
    {
        $mesa = array();
        $mesa['id'] = $this->getID();
        $mesa['nome'] = $this->getNome();
        $mesa['ativa'] = $this->getAtiva();
        // extra
        $mesa['estado'] = $this->getEstado();
        $mesa['juntaid'] = $this->getJuntaID();
        $mesa['juntanome'] = $this->getJuntaNome();
        return $mesa;
    }

    public static function getPeloID($mesa_id)
    {
        $query = DB::$pdo->from('Mesas')
                         ->where(array('id' => $mesa_id));
        return new ZMesa($query->fetch());
    }

    public static function getPeloNome($nome)
    {
        $query = DB::$pdo->from('Mesas')
                         ->where(array('nome' => $nome));
        return new ZMesa($query->fetch());
    }

    public static function getProximoID()
    {
        $query = DB::$pdo->from('Mesas')
                         ->select(null)
                         ->select('MAX(id) as id');
        return $query->fetch('id') + 1;
    }

    private static function validarCampos(&$mesa)
    {
        $erros = array();
        $mesa['nome'] = strip_tags(trim($mesa['nome']));
        if (strlen($mesa['nome']) == 0) {
            $erros['nome'] = 'O nome não pode ser vazio';
        }
        $mesa['ativa'] = trim($mesa['ativa']);
        if (strlen($mesa['ativa']) == 0) {
            $mesa['ativa'] = 'N';
        } elseif (!in_array($mesa['ativa'], array('Y', 'N'))) {
            $erros['ativa'] = 'O estado de ativação da mesa não é válido';
        }
        $old_mesa = self::getPeloID($mesa['id']);
        if (!is_null($old_mesa->getID()) && $old_mesa->isAtiva() && $mesa['ativa'] == 'N') {
            $pedido = ZPedido::getPelaMesaID($old_mesa->getID());
            if (!is_null($pedido->getID())) {
                $erros['ativa'] = 'A mesa não pode ser desativada porque possui um pedido em aberto';
            }
        }
        if (!empty($erros)) {
            throw new ValidationException($erros);
        }
        // extra
        unset($mesa['estado']);
        unset($mesa['juntaid']);
        unset($mesa['juntanome']);
    }

    private static function handleException(&$e)
    {
        if (stripos($e->getMessage(), 'PRIMARY') !== false) {
            throw new ValidationException(array('id' => 'O ID informado já está cadastrado'));
        }
        if (stripos($e->getMessage(), 'Nome_UNIQUE') !== false) {
            throw new ValidationException(array('nome' => 'O nome informado já está cadastrado'));
        }
    }

    public static function cadastrar($mesa)
    {
        $_mesa = $mesa->toArray();
        self::validarCampos($_mesa);
        try {
            $_mesa['id'] = DB::$pdo->insertInto('Mesas')->values($_mesa)->execute();
        } catch (Exception $e) {
            self::handleException($e);
            throw $e;
        }
        return self::getPeloID($_mesa['id']);
    }

    public static function atualizar($mesa)
    {
        $_mesa = $mesa->toArray();
        if (!$_mesa['id']) {
            throw new ValidationException(array('id' => 'O id da mesa não foi informado'));
        }
        self::validarCampos($_mesa);
        $campos = array(
            'nome',
            'ativa',
        );
        try {
            $query = DB::$pdo->update('Mesas');
            $query = $query->set(array_intersect_key($_mesa, array_flip($campos)));
            $query = $query->where('id', $_mesa['id']);
            $query->execute();
        } catch (Exception $e) {
            self::handleException($e);
            throw $e;
        }
        return self::getPeloID($_mesa['id']);
    }

    public static function excluir($id)
    {
        if (!$id) {
            throw new Exception('Não foi possível excluir a mesa, o id da mesa não foi informado');
        }
        $query = DB::$pdo->deleteFrom('Mesas')
                         ->where(array('id' => $id));
        return $query->execute();
    }

    private static function initSearch($funcionario_id, $ativa, $busca)
    {
        $query = DB::$pdo->from('Mesas m')
                         ->select('(CASE WHEN ISNULL(p.id) THEN "livre" WHEN p.estado = "Fechado" THEN "fechado" WHEN p.estado = "Agendado" THEN "reservado" ELSE "ocupado" END) as estado')
                         ->select('pj.mesaid as juntaid')
                         ->select('mj.nome as juntanome')
                         ->leftJoin('Pedidos p ON p.mesaid = m.id AND p.tipo = "Mesa" AND p.cancelado = "N" AND p.estado <> "Finalizado"')
                         ->leftJoin('Produtos_Pedidos pp ON pp.pedidoid = p.id')
                         ->leftJoin('Juncoes j ON j.mesaid = m.id AND j.estado = "Associado"')
                         ->leftJoin('Pedidos pj ON pj.id = j.pedidoid')
                         ->leftJoin('Mesas mj ON mj.id = pj.mesaid')
                         ->groupBy('m.id');
        if (!is_null($funcionario_id)) {
            $query = $query->orderBy('IF(p.funcionarioid = ?, 1, 0) DESC', $funcionario_id);
        }
        $busca = trim($busca);
        if (is_numeric($busca)) {
            $query = $query->where('m.id', $busca);
        } elseif ($busca != '') {
            $query = $query->where('m.nome LIKE ?', '%'.$busca.'%');
        }
        if (in_array($ativa, array('Y', 'N'))) {
            $query = $query->where('m.ativa', $ativa);
        }
        $query = $query->orderBy('m.id ASC');
        return $query;
    }

    public static function getTodas($funcionario_id = null, $ativa = 'Y', $busca = null, $inicio = null, $quantidade = null)
    {
        $query = self::initSearch($funcionario_id, $ativa, $busca);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_mesas = $query->fetchAll();
        $mesas = array();
        foreach ($_mesas as $mesa) {
            $mesas[] = new ZMesa($mesa);
        }
        return $mesas;
    }

    public static function getCount($funcionario_id = null, $ativa = 'Y', $busca = null)
    {
        $query = self::initSearch($funcionario_id, $ativa, $busca);
        $query = $query->select(null)->groupBy(null)->select('COUNT(DISTINCT m.id)');
        return (int) $query->fetchColumn();
    }
}
