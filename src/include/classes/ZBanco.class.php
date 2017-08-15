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
 * Bancos disponíveis no país
 */
class ZBanco
{
    private $id;
    private $numero;
    private $razao_social;
    private $agencia_mascara;
    private $conta_mascara;

    public function __construct($banco = array())
    {
        if (is_array($banco)) {
            $this->setID(isset($banco['id'])?$banco['id']:null);
            $this->setNumero(isset($banco['numero'])?$banco['numero']:null);
            $this->setRazaoSocial(isset($banco['razaosocial'])?$banco['razaosocial']:null);
            $this->setAgenciaMascara(isset($banco['agenciamascara'])?$banco['agenciamascara']:null);
            $this->setContaMascara(isset($banco['contamascara'])?$banco['contamascara']:null);
        }
    }

    /**
     * Identificador do banco
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
     * Número do banco
     */
    public function getNumero()
    {
        return $this->numero;
    }

    public function setNumero($numero)
    {
        $this->numero = $numero;
    }

    /**
     * Razão social do banco
     */
    public function getRazaoSocial()
    {
        return $this->razao_social;
    }

    public function setRazaoSocial($razao_social)
    {
        $this->razao_social = $razao_social;
    }

    /**
     * Mascara para formatação do número da agência
     */
    public function getAgenciaMascara()
    {
        return $this->agencia_mascara;
    }

    public function setAgenciaMascara($agencia_mascara)
    {
        $this->agencia_mascara = $agencia_mascara;
    }

    /**
     * Máscara para formatação do número da conta
     */
    public function getContaMascara()
    {
        return $this->conta_mascara;
    }

    public function setContaMascara($conta_mascara)
    {
        $this->conta_mascara = $conta_mascara;
    }

    public function toArray()
    {
        $banco = array();
        $banco['id'] = $this->getID();
        $banco['numero'] = $this->getNumero();
        $banco['razaosocial'] = $this->getRazaoSocial();
        $banco['agenciamascara'] = $this->getAgenciaMascara();
        $banco['contamascara'] = $this->getContaMascara();
        return $banco;
    }

    public static function getPeloID($id)
    {
        $query = DB::$pdo->from('Bancos')
                         ->where(array('id' => $id));
        return new ZBanco($query->fetch());
    }

    public static function getPelaRazaoSocial($razao_social)
    {
        $query = DB::$pdo->from('Bancos')
                         ->where(array('razaosocial' => $razao_social));
        return new ZBanco($query->fetch());
    }

    public static function getPeloNumero($numero)
    {
        $query = DB::$pdo->from('Bancos')
                         ->where(array('numero' => $numero));
        return new ZBanco($query->fetch());
    }

    private static function validarCampos(&$banco)
    {
        $erros = array();
        $banco['numero'] = strip_tags(trim($banco['numero']));
        if (strlen($banco['numero']) == 0) {
            $erros['numero'] = 'O número não pode ser vazio';
        }
        $banco['razaosocial'] = strip_tags(trim($banco['razaosocial']));
        if (strlen($banco['razaosocial']) == 0) {
            $erros['razaosocial'] = 'A razão social não pode ser vazia';
        }
        $banco['agenciamascara'] = strip_tags(trim($banco['agenciamascara']));
        if (strlen($banco['agenciamascara']) == 0) {
            $banco['agenciamascara'] = null;
        }
        $banco['contamascara'] = strip_tags(trim($banco['contamascara']));
        if (strlen($banco['contamascara']) == 0) {
            $banco['contamascara'] = null;
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
        if (stripos($e->getMessage(), 'RazaoSocial_UNIQUE') !== false) {
            throw new ValidationException(array('razaosocial' => 'A razão social informada já está cadastrada'));
        }
        if (stripos($e->getMessage(), 'Numero_UNIQUE') !== false) {
            throw new ValidationException(array('numero' => 'O número informado já está cadastrado'));
        }
    }

    public static function cadastrar($banco)
    {
        $_banco = $banco->toArray();
        self::validarCampos($_banco);
        try {
            $_banco['id'] = DB::$pdo->insertInto('Bancos')->values($_banco)->execute();
        } catch (Exception $e) {
            self::handleException($e);
            throw $e;
        }
        return self::getPeloID($_banco['id']);
    }

    public static function atualizar($banco)
    {
        $_banco = $banco->toArray();
        if (!$_banco['id']) {
            throw new ValidationException(array('id' => 'O id do banco não foi informado'));
        }
        self::validarCampos($_banco);
        $campos = array(
            'numero',
            'razaosocial',
            'agenciamascara',
            'contamascara',
        );
        try {
            $query = DB::$pdo->update('Bancos');
            $query = $query->set(array_intersect_key($_banco, array_flip($campos)));
            $query = $query->where('id', $_banco['id']);
            $query->execute();
        } catch (Exception $e) {
            self::handleException($e);
            throw $e;
        }
        return self::getPeloID($_banco['id']);
    }

    public static function excluir($id)
    {
        if (!$id) {
            throw new Exception('Não foi possível excluir o banco, o id do banco não foi informado');
        }
        $query = DB::$pdo->deleteFrom('Bancos')
                         ->where(array('id' => $id));
        return $query->execute();
    }

    private static function initSearch($busca)
    {
        $query = DB::$pdo->from('Bancos')
                         ->orderBy('id ASC');
        $busca = trim($busca);
        if (is_numeric($busca)) {
            $query = $query->where('numero', $busca);
        } elseif ($busca != '') {
            $query = $query->where('razaosocial LIKE ?', '%'.$busca.'%');
        }
        return $query;
    }

    public static function getTodos($busca = null, $inicio = null, $quantidade = null)
    {
        $query = self::initSearch($busca);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_bancos = $query->fetchAll();
        $bancos = array();
        foreach ($_bancos as $banco) {
            $bancos[] = new ZBanco($banco);
        }
        return $bancos;
    }

    public static function getCount($busca = null)
    {
        $query = self::initSearch($busca);
        return $query->count();
    }
}
