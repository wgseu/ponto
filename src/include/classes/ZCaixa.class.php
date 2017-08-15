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
 * Caixas de movimentação financeira
 */
class ZCaixa
{
    private $id;
    private $descricao;
    private $serie;
    private $numero_inicial;
    private $ativo;

    public function __construct($caixa = array())
    {
        $this->fromArray($caixa);
    }

    /**
     * Identificador do caixa
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
     * Descrição do caixa
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
     * Série do caixa
     */
    public function getSerie()
    {
        return $this->serie;
    }

    public function setSerie($serie)
    {
        $this->serie = $serie;
    }

    /**
     * Número inicial na geração da nota, será usado quando maior que o último número
     * utilizado
     */
    public function getNumeroInicial()
    {
        return $this->numero_inicial;
    }

    public function setNumeroInicial($numero_inicial)
    {
        $this->numero_inicial = $numero_inicial;
    }

    /**
     * Informa se o caixa está ativo
     */
    public function getAtivo()
    {
        return $this->ativo;
    }

    /**
     * Informa se o caixa está ativo
     */
    public function isAtivo()
    {
        return $this->ativo == 'Y';
    }

    public function setAtivo($ativo)
    {
        $this->ativo = $ativo;
    }

    public function toArray()
    {
        $caixa = array();
        $caixa['id'] = $this->getID();
        $caixa['descricao'] = $this->getDescricao();
        $caixa['serie'] = $this->getSerie();
        $caixa['numeroinicial'] = $this->getNumeroInicial();
        $caixa['ativo'] = $this->getAtivo();
        return $caixa;
    }

    public function fromArray($caixa = array())
    {
        if (!is_array($caixa)) {
            return $this;
        }
        $this->setID(isset($caixa['id'])?$caixa['id']:null);
        $this->setDescricao(isset($caixa['descricao'])?$caixa['descricao']:null);
        $this->setSerie(isset($caixa['serie'])?$caixa['serie']:null);
        $this->setNumeroInicial(isset($caixa['numeroinicial'])?$caixa['numeroinicial']:null);
        $this->setAtivo(isset($caixa['ativo'])?$caixa['ativo']:null);
    }

    public static function getPeloID($id)
    {
        $query = DB::$pdo->from('Caixas')
                         ->where(array('id' => $id));
        return new ZCaixa($query->fetch());
    }

    public static function getPelaDescricao($descricao)
    {
        $query = DB::$pdo->from('Caixas')
                         ->where(array('descricao' => $descricao));
        return new ZCaixa($query->fetch());
    }

    public static function getPelaSerie($serie)
    {
        $query = DB::$pdo->from('Caixas')
                         ->where(array('serie' => $serie))
                         ->orderBy('numeroinicial DESC')
                         ->limit(1);
        return new ZCaixa($query->fetch());
    }

    private static function validarCampos(&$caixa)
    {
        $erros = array();
        $caixa['descricao'] = strip_tags(trim($caixa['descricao']));
        if (strlen($caixa['descricao']) == 0) {
            $erros['descricao'] = 'A descrição não pode ser vazia';
        }
        if (!is_numeric($caixa['serie'])) {
            $erros['serie'] = 'A série não foi informada';
        } else {
            $caixa['serie'] = intval($caixa['serie']);
        }
        if (!is_numeric($caixa['numeroinicial'])) {
            $erros['numeroinicial'] = 'O número inicial não foi informado';
        } else {
            $caixa['numeroinicial'] = intval($caixa['numeroinicial']);
        }
        $caixa['ativo'] = trim($caixa['ativo']);
        if (strlen($caixa['ativo']) == 0) {
            $caixa['ativo'] = 'N';
        } elseif (!in_array($caixa['ativo'], array('Y', 'N'))) {
            $erros['ativo'] = 'O ativo informado não é válido';
        }
        if ($caixa['ativo'] == 'N' && ZMovimentacao::existe($caixa['id'])) {
            $erros['ativo'] = 'O caixa está aberto e não pode ser desativado';
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

    public static function cadastrar($caixa)
    {
        $_caixa = $caixa->toArray();
        self::validarCampos($_caixa);
        try {
            $_caixa['id'] = DB::$pdo->insertInto('Caixas')->values($_caixa)->execute();
        } catch (Exception $e) {
            self::handleException($e);
            throw $e;
        }
        return self::getPeloID($_caixa['id']);
    }

    public static function atualizar($caixa)
    {
        $_caixa = $caixa->toArray();
        if (!$_caixa['id']) {
            throw new ValidationException(array('id' => 'O id da caixa não foi informado'));
        }
        self::validarCampos($_caixa);
        $campos = array(
            'descricao',
            'serie',
            'numeroinicial',
            'ativo',
        );
        try {
            $query = DB::$pdo->update('Caixas');
            $query = $query->set(array_intersect_key($_caixa, array_flip($campos)));
            $query = $query->where('id', $_caixa['id']);
            $query->execute();
        } catch (Exception $e) {
            self::handleException($e);
            throw $e;
        }
        return self::getPeloID($_caixa['id']);
    }

    public static function resetaInicios($serie)
    {
        try {
            $query = DB::$pdo->update('Caixas');
            $query = $query->set('numeroinicial', '1');
            $query = $query->where('serie', $serie);
            $query->execute();
        } catch (Exception $e) {
            self::handleException($e);
            throw $e;
        }
    }

    public static function excluir($id)
    {
        if (!$id) {
            throw new Exception('Não foi possível excluir a caixa, o id da caixa não foi informado');
        }
        $query = DB::$pdo->deleteFrom('Caixas')
                         ->where(array('id' => $id));
        return $query->execute();
    }

    private static function initSearch($busca, $ativo)
    {
        $query = DB::$pdo->from('Caixas')
                         ->orderBy('id ASC');
        $busca = trim($busca);
        if ($busca != '') {
            $query = $query->where('descricao LIKE ?', '%'.$busca.'%');
        }
        $ativo = trim($ativo);
        if ($ativo != '') {
            $query = $query->where('ativo', $ativo);
        }
        return $query;
    }

    public static function getTodas($busca = null, $ativo = null, $inicio = null, $quantidade = null)
    {
        $query = self::initSearch($busca, $ativo);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_caixas = $query->fetchAll();
        $caixas = array();
        foreach ($_caixas as $caixa) {
            $caixas[] = new ZCaixa($caixa);
        }
        return $caixas;
    }

    public static function getCount($busca = null, $ativo = null)
    {
        $query = self::initSearch($busca, $ativo);
        return $query->count();
    }
}
