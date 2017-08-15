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
 * Bairro de uma cidade
 */
class ZBairro
{
    private $id;
    private $cidade_id;
    private $nome;
    private $valor_entrega;
    private $disponivel;

    public function __construct($bairro = array())
    {
        if (is_array($bairro)) {
            $this->setID(isset($bairro['id'])?$bairro['id']:null);
            $this->setCidadeID(isset($bairro['cidadeid'])?$bairro['cidadeid']:null);
            $this->setNome(isset($bairro['nome'])?$bairro['nome']:null);
            $this->setValorEntrega(isset($bairro['valorentrega'])?$bairro['valorentrega']:null);
            $this->setDisponivel(isset($bairro['disponivel'])?$bairro['disponivel']:null);
        }
    }

    /**
     * Identificador do bairro
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
     * Cidade a qual o bairro pertence
     */
    public function getCidadeID()
    {
        return $this->cidade_id;
    }

    public function setCidadeID($cidade_id)
    {
        $this->cidade_id = $cidade_id;
    }

    /**
     * Nome do bairro
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
     * Valor cobrado para entregar um pedido nesse bairro
     */
    public function getValorEntrega()
    {
        return $this->valor_entrega;
    }

    public function setValorEntrega($valor_entrega)
    {
        $this->valor_entrega = $valor_entrega;
    }

    /**
     * Informa se o bairro está disponível para entrega de pedidos
     */
    public function getDisponivel()
    {
        return $this->disponivel;
    }

    /**
     * Informa se o bairro está disponível para entrega de pedidos
     */
    public function isDisponivel()
    {
        return $this->disponivel == 'Y';
    }

    public function setDisponivel($disponivel)
    {
        $this->disponivel = $disponivel;
    }

    public function toArray()
    {
        $bairro = array();
        $bairro['id'] = $this->getID();
        $bairro['cidadeid'] = $this->getCidadeID();
        $bairro['nome'] = $this->getNome();
        $bairro['valorentrega'] = $this->getValorEntrega();
        $bairro['disponivel'] = $this->getDisponivel();
        return $bairro;
    }

    public static function getPeloID($id)
    {
        $query = DB::$pdo->from('Bairros')
                         ->where(array('id' => $id));
        return new ZBairro($query->fetch());
    }

    public static function getPelaCidadeIDNome($cidade_id, $nome)
    {
        $query = DB::$pdo->from('Bairros')
                         ->where(array('cidadeid' => $cidade_id, 'nome' => $nome));
        return new ZBairro($query->fetch());
    }

    public static function procuraOuCadastra($cidade_id, $nome)
    {
        $nome = trim($nome);
        $bairro = ZBairro::getPelaCidadeIDNome($cidade_id, $nome);
        if (!is_null($bairro->getID())) {
            return $bairro;
        }
        $bairro->setCidadeID($cidade_id);
        $bairro->setNome($nome);
        $bairro->setValorEntrega(0.0);
        return ZBairro::cadastrar($bairro);
    }

    private static function validarCampos(&$bairro)
    {
        $erros = array();
        if (!is_numeric($bairro['cidadeid'])) {
            $erros['cidadeid'] = 'A cidade não foi informada';
        }
        $bairro['nome'] = strip_tags(trim($bairro['nome']));
        if (strlen($bairro['nome']) == 0) {
            $erros['nome'] = 'O nome não pode ser vazio';
        }
        if (!is_numeric($bairro['valorentrega'])) {
            $erros['valorentrega'] = 'O valor da entrega não foi informado';
        } elseif ($bairro['valorentrega'] < 0) {
            $erros['valorentrega'] = 'O valor da entrega não pode ser negativo';
        }
        $bairro['disponivel'] = trim($bairro['disponivel']);
        if (strlen($bairro['disponivel']) == 0) {
            $bairro['disponivel'] = 'N';
        } elseif (!in_array($bairro['disponivel'], array('Y', 'N'))) {
            $erros['disponivel'] = 'O disponível informado não é válido';
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
        if (stripos($e->getMessage(), 'CidadeID_Nome_UNIQUE') !== false) {
            throw new ValidationException(array('nome' => 'O nome informado já está cadastrado'));
        }
    }

    public static function cadastrar($bairro)
    {
        $_bairro = $bairro->toArray();
        self::validarCampos($_bairro);
        try {
            $_bairro['id'] = DB::$pdo->insertInto('Bairros')->values($_bairro)->execute();
        } catch (Exception $e) {
            self::handleException($e);
            throw $e;
        }
        return self::getPeloID($_bairro['id']);
    }

    public static function atualizar($bairro)
    {
        $_bairro = $bairro->toArray();
        if (!$_bairro['id']) {
            throw new ValidationException(array('id' => 'O id do bairro não foi informado'));
        }
        self::validarCampos($_bairro);
        $campos = array(
            'cidadeid',
            'nome',
            'valorentrega',
            'disponivel',
        );
        try {
            $query = DB::$pdo->update('Bairros');
            $query = $query->set(array_intersect_key($_bairro, array_flip($campos)));
            $query = $query->where('id', $_bairro['id']);
            $query->execute();
        } catch (Exception $e) {
            self::handleException($e);
            throw $e;
        }
        return self::getPeloID($_bairro['id']);
    }

    public static function excluir($id)
    {
        if (!$id) {
            throw new Exception('Não foi possível excluir o bairro, o id do bairro não foi informado');
        }
        $query = DB::$pdo->deleteFrom('Bairros')
                         ->where(array('id' => $id));
        return $query->execute();
    }

    private static function initSearch($busca, $pais_id, $estado_id, $cidade_id)
    {
        $query = DB::$pdo->from('Bairros b')
                         ->leftJoin('Cidades c ON c.id = b.cidadeid')
                         ->leftJoin('Estados e ON e.id = c.estadoid')
                         ->orderBy('b.nome ASC');
        $busca = trim($busca);
        if ($busca != '') {
            $query = $query->where('b.nome LIKE ?', '%'.$busca.'%');
        }
        if (is_numeric($pais_id)) {
            $query = $query->where('e.paisid', $pais_id);
        }
        if (is_numeric($estado_id)) {
            $query = $query->where('c.estadoid', $estado_id);
        }
        if (is_numeric($cidade_id)) {
            $query = $query->where('b.cidadeid', $cidade_id);
        }
        return $query;
    }

    public static function getTodos($busca = null, $pais_id = null, $estado_id = null, $cidade_id = null, $inicio = null, $quantidade = null)
    {
        $query = self::initSearch($busca, $pais_id, $estado_id, $cidade_id);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_bairros = $query->fetchAll();
        $bairros = array();
        foreach ($_bairros as $bairro) {
            $bairros[] = new ZBairro($bairro);
        }
        return $bairros;
    }

    public static function getCount($busca = null, $pais_id = null, $estado_id = null, $cidade_id = null)
    {
        $query = self::initSearch($busca, $pais_id, $estado_id, $cidade_id);
        return $query->count();
    }

    private static function initSearchDaCidadeID($cidade_id, $busca)
    {
        $query = DB::$pdo->from('Bairros')
                         ->where(array('cidadeid' => $cidade_id))
                         ->orderBy('nome ASC');
        $busca = trim($busca);
        if ($busca != '') {
            $query = $query->where('nome LIKE ?', '%'.$busca.'%');
        }
        return $query;
    }

    public static function getTodosDaCidadeID($cidade_id, $busca = null, $inicio = null, $quantidade = null)
    {
        $query = self::initSearchDaCidadeID($cidade_id, $busca);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_bairros = $query->fetchAll();
        $bairros = array();
        foreach ($_bairros as $bairro) {
            $bairros[] = new ZBairro($bairro);
        }
        return $bairros;
    }

    public static function getCountDaCidadeID($cidade_id, $busca = null)
    {
        $query = self::initSearchDaCidadeID($cidade_id, $busca);
        return $query->count();
    }
}
