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
class ZSessao
{
    private $id;
    private $data_inicio;
    private $data_termino;
    private $aberta;

    public function __construct($sessao = array())
    {
        if (is_array($sessao)) {
            $this->setID(isset($sessao['id'])?$sessao['id']:null);
            $this->setDataInicio(isset($sessao['datainicio'])?$sessao['datainicio']:null);
            $this->setDataTermino(isset($sessao['datatermino'])?$sessao['datatermino']:null);
            $this->setAberta(isset($sessao['aberta'])?$sessao['aberta']:null);
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

    public function getDataInicio()
    {
        return $this->data_inicio;
    }

    public function setDataInicio($data_inicio)
    {
        $this->data_inicio = $data_inicio;
    }

    public function getDataTermino()
    {
        return $this->data_termino;
    }

    public function setDataTermino($data_termino)
    {
        $this->data_termino = $data_termino;
    }

    public function getAberta()
    {
        return $this->aberta;
    }

    public function isAberta()
    {
        return $this->aberta == 'Y';
    }

    public function setAberta($aberta)
    {
        $this->aberta = $aberta;
    }

    public function toArray()
    {
        $sessao = array();
        $sessao['id'] = $this->getID();
        $sessao['datainicio'] = $this->getDataInicio();
        $sessao['datatermino'] = $this->getDataTermino();
        $sessao['aberta'] = $this->getAberta();
        return $sessao;
    }

    public static function getPeloID($id)
    {
        $query = DB::$pdo->from('Sessoes')
                         ->where(array('id' => $id));
        return new ZSessao($query->fetch());
    }

    public static function getPorAberta()
    {
        $query = DB::$pdo->from('Sessoes')
                         ->where(array('aberta' => 'Y'))
                         ->limit(1);
        return new ZSessao($query->fetch());
    }

    public static function getAbertaOuUltima()
    {
        $query = DB::$pdo->from('Sessoes')
                         ->orderBy('IF(aberta = "Y", 1, 0) DESC, id DESC')
                         ->limit(1);
        return new ZSessao($query->fetch());
    }

    private static function validarCampos(&$sessao)
    {
        $erros = array();
        $sessao['datainicio'] = date('Y-m-d H:i:s');
        $sessao['datatermino'] = date('Y-m-d H:i:s');
        $sessao['aberta'] = strval($sessao['aberta']);
        if (!in_array($sessao['aberta'], array('Y', 'N'))) {
            $erros['aberta'] = 'A Aberta informado não é válida';
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

    public static function cadastrar($sessao)
    {
        $_sessao = $sessao->toArray();
        self::validarCampos($_sessao);
        try {
            $_sessao['id'] = DB::$pdo->insertInto('Sessoes')->values($_sessao)->execute();
        } catch (Exception $e) {
            self::handleException($e);
            throw $e;
        }
        return self::getPeloID($_sessao['id']);
    }

    public static function atualizar($sessao)
    {
        $_sessao = $sessao->toArray();
        if (!$_sessao['id']) {
            throw new ValidationException(array('id' => 'O id da sessao não foi informado'));
        }
        self::validarCampos($_sessao);
        $campos = array(
            'datatermino',
            'aberta',
        );
        try {
            $query = DB::$pdo->update('Sessoes');
            $query = $query->set(array_intersect_key($_sessao, array_flip($campos)));
            $query = $query->where('id', $_sessao['id']);
            $query->execute();
        } catch (Exception $e) {
            self::handleException($e);
            throw $e;
        }
        return self::getPeloID($_sessao['id']);
    }

    private static function initSearch()
    {
        return   DB::$pdo->from('Sessoes')
                         ->orderBy('id ASC');
    }

    public static function getTodas($inicio = null, $quantidade = null)
    {
        $query = self::initSearch();
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_sessaos = $query->fetchAll();
        $sessaos = array();
        foreach ($_sessaos as $sessao) {
            $sessaos[] = new ZSessao($sessao);
        }
        return $sessaos;
    }

    public static function getCount()
    {
        $query = self::initSearch();
        return $query->count();
    }
}
