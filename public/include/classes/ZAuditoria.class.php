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
class AuditoriaTipo
{
    const FINANCEIRO = 'Financeiro';
    const ADMINISTRATIVO = 'Administrativo';
}
class AuditoriaPrioridade
{
    const BAIXA = 'Baixa';
    const MEDIA = 'Media';
    const ALTA = 'Alta';
}

/**
 * Registra todas as atividades importantes do sistema
 */
class ZAuditoria
{
    private $id;
    private $funcionario_id;
    private $autorizador_id;
    private $tipo;
    private $prioridade;
    private $descricao;
    private $data_hora;

    public function __construct($auditoria = [])
    {
        if (is_array($auditoria)) {
            $this->setID(isset($auditoria['id'])?$auditoria['id']:null);
            $this->setFuncionarioID(isset($auditoria['funcionarioid'])?$auditoria['funcionarioid']:null);
            $this->setAutorizadorID(isset($auditoria['autorizadorid'])?$auditoria['autorizadorid']:null);
            $this->setTipo(isset($auditoria['tipo'])?$auditoria['tipo']:null);
            $this->setPrioridade(isset($auditoria['prioridade'])?$auditoria['prioridade']:null);
            $this->setDescricao(isset($auditoria['descricao'])?$auditoria['descricao']:null);
            $this->setDataHora(isset($auditoria['datahora'])?$auditoria['datahora']:null);
        }
    }

    /**
     * Identificador da auditoria
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
     * Funcionário que exerceu a atividade
     */
    public function getFuncionarioID()
    {
        return $this->funcionario_id;
    }

    public function setFuncionarioID($funcionario_id)
    {
        $this->funcionario_id = $funcionario_id;
    }

    /**
     * Funcionário que autorizou o acesso ao recurso descrito
     */
    public function getAutorizadorID()
    {
        return $this->autorizador_id;
    }

    public function setAutorizadorID($autorizador_id)
    {
        $this->autorizador_id = $autorizador_id;
    }

    /**
     * Tipo de atividade exercida
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    /**
     * Prioridade de acesso do recurso
     */
    public function getPrioridade()
    {
        return $this->prioridade;
    }

    public function setPrioridade($prioridade)
    {
        $this->prioridade = $prioridade;
    }

    /**
     * Descrição da atividade exercida
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
     * Data e hora do ocorrido
     */
    public function getDataHora()
    {
        return $this->data_hora;
    }

    public function setDataHora($data_hora)
    {
        $this->data_hora = $data_hora;
    }

    public function toArray()
    {
        $auditoria = [];
        $auditoria['id'] = $this->getID();
        $auditoria['funcionarioid'] = $this->getFuncionarioID();
        $auditoria['autorizadorid'] = $this->getAutorizadorID();
        $auditoria['tipo'] = $this->getTipo();
        $auditoria['prioridade'] = $this->getPrioridade();
        $auditoria['descricao'] = $this->getDescricao();
        $auditoria['datahora'] = $this->getDataHora();
        return $auditoria;
    }

    public static function getPeloID($id)
    {
        $query = \DB::$pdo->from('Auditoria')
                         ->where(['id' => $id]);
        return new Auditoria($query->fetch());
    }

    private static function validarCampos(&$auditoria)
    {
        $erros = [];
        if (!is_numeric($auditoria['funcionarioid'])) {
            $erros['funcionarioid'] = 'O funcionário não foi informado';
        }
        if (!is_numeric($auditoria['autorizadorid'])) {
            $erros['autorizadorid'] = 'O autorizador não foi informado';
        }
        $auditoria['tipo'] = strval($auditoria['tipo']);
        if (!in_array($auditoria['tipo'], ['Financeiro', 'Administrativo'])) {
            $erros['tipo'] = 'O tipo informado não é válido';
        }
        $auditoria['prioridade'] = strval($auditoria['prioridade']);
        if (!in_array($auditoria['prioridade'], ['Baixa', 'Media', 'Alta'])) {
            $erros['prioridade'] = 'A prioridade informada não é válida';
        }
        $auditoria['descricao'] = strip_tags(trim($auditoria['descricao']));
        if (strlen($auditoria['descricao']) == 0) {
            $erros['descricao'] = 'A descrição não pode ser vazia';
        }
        $auditoria['datahora'] = date('Y-m-d H:i:s');
        if (!empty($erros)) {
            throw new ValidationException($erros);
        }
    }

    private static function handleException(&$e)
    {
        if (stripos($e->getMessage(), 'PRIMARY') !== false) {
            throw new ValidationException(['id' => 'O ID informado já está cadastrado']);
        }
    }

    public static function cadastrar($auditoria)
    {
        $_auditoria = $auditoria->toArray();
        self::validarCampos($_auditoria);
        try {
            $_auditoria['id'] = \DB::$pdo->insertInto('Auditoria')->values($_auditoria)->execute();
        } catch (Exception $e) {
            self::handleException($e);
            throw $e;
        }
        return self::findByID($_auditoria['id']);
    }

    private static function initSearch($busca, $funcionario_id, $tipo, $prioridade)
    {
        $query = \DB::$pdo->from('Auditoria')
                         ->orderBy('id DESC');
        $busca = trim($busca);
        if ($busca != '') {
            $query = $query->where('descricao LIKE ?', '%'.$busca.'%');
        }
        $funcionario_id = trim($funcionario_id);
        if ($funcionario_id != '') {
            $query = $query->where('funcionarioid', $funcionario_id);
        }
        $tipo = trim($tipo);
        if ($tipo != '') {
            $query = $query->where('tipo', $tipo);
        }
        $prioridade = trim($prioridade);
        if ($prioridade != '') {
            $query = $query->where('prioridade', $prioridade);
        }
        return $query;
    }

    public static function getTodas(
        $busca = null,
        $funcionario_id = null,
        $tipo = null,
        $prioridade = null,
        $inicio = null,
        $quantidade = null
    ) {
        $query = self::initSearch($busca, $funcionario_id, $tipo, $prioridade);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_auditorias = $query->fetchAll();
        $auditorias = [];
        foreach ($_auditorias as $auditoria) {
            $auditorias[] = new Auditoria($auditoria);
        }
        return $auditorias;
    }

    public static function getCount($busca = null, $funcionario_id = null, $tipo = null, $prioridade = null)
    {
        $query = self::initSearch($busca, $funcionario_id, $tipo, $prioridade);
        return $query->count();
    }

    private static function initSearchDoFuncionarioID($funcionario_id)
    {
        return   \DB::$pdo->from('Auditoria')
                         ->where(['funcionarioid' => $funcionario_id])
                         ->orderBy('id ASC');
    }

    public static function getTodasDoFuncionarioID($funcionario_id, $inicio = null, $quantidade = null)
    {
        $query = self::initSearchDoFuncionarioID($funcionario_id);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_auditorias = $query->fetchAll();
        $auditorias = [];
        foreach ($_auditorias as $auditoria) {
            $auditorias[] = new Auditoria($auditoria);
        }
        return $auditorias;
    }

    public static function getCountDoFuncionarioID($funcionario_id)
    {
        $query = self::initSearchDoFuncionarioID($funcionario_id);
        return $query->count();
    }

    private static function initSearchDoAutorizadorID($autorizador_id)
    {
        return   \DB::$pdo->from('Auditoria')
                         ->where(['autorizadorid' => $autorizador_id])
                         ->orderBy('id ASC');
    }

    public static function getTodasDoAutorizadorID($autorizador_id, $inicio = null, $quantidade = null)
    {
        $query = self::initSearchDoAutorizadorID($autorizador_id);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_auditorias = $query->fetchAll();
        $auditorias = [];
        foreach ($_auditorias as $auditoria) {
            $auditorias[] = new Auditoria($auditoria);
        }
        return $auditorias;
    }

    public static function getCountDoAutorizadorID($autorizador_id)
    {
        $query = self::initSearchDoAutorizadorID($autorizador_id);
        return $query->count();
    }
}
