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
 * Movimentação do caixa, permite abrir diversos caixas na conta de operadores
 */
class ZMovimentacao
{
    private $id;
    private $sessao_id;
    private $caixa_id;
    private $aberta;
    private $funcionario_abertura_id;
    private $data_abertura;
    private $funcionario_fechamento_id;
    private $data_fechamento;

    public function __construct($movimentacao = array())
    {
        if (is_array($movimentacao)) {
            $this->setID(isset($movimentacao['id'])?$movimentacao['id']:null);
            $this->setSessaoID(isset($movimentacao['sessaoid'])?$movimentacao['sessaoid']:null);
            $this->setCaixaID(isset($movimentacao['caixaid'])?$movimentacao['caixaid']:null);
            $this->setAberta(isset($movimentacao['aberta'])?$movimentacao['aberta']:null);
            $this->setFuncionarioAberturaID(isset($movimentacao['funcionarioaberturaid'])?$movimentacao['funcionarioaberturaid']:null);
            $this->setDataAbertura(isset($movimentacao['dataabertura'])?$movimentacao['dataabertura']:null);
            $this->setFuncionarioFechamentoID(isset($movimentacao['funcionariofechamentoid'])?$movimentacao['funcionariofechamentoid']:null);
            $this->setDataFechamento(isset($movimentacao['datafechamento'])?$movimentacao['datafechamento']:null);
        }
    }

    /**
     * Código da movimentação do caixa
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
     * Sessão do dia, permite abrir vários caixas no mesmo dia com o mesmo código da
     * sessão
     */
    public function getSessaoID()
    {
        return $this->sessao_id;
    }

    public function setSessaoID($sessao_id)
    {
        $this->sessao_id = $sessao_id;
    }

    /**
     * Caixa a qual pertence essa movimentação
     */
    public function getCaixaID()
    {
        return $this->caixa_id;
    }

    public function setCaixaID($caixa_id)
    {
        $this->caixa_id = $caixa_id;
    }

    /**
     * Informa se o caixa está aberto
     */
    public function getAberta()
    {
        return $this->aberta;
    }

    /**
     * Informa se o caixa está aberto
     */
    public function isAberta()
    {
        return $this->aberta == 'Y';
    }

    public function setAberta($aberta)
    {
        $this->aberta = $aberta;
    }

    /**
     * Funcionário que abriu o caixa
     */
    public function getFuncionarioAberturaID()
    {
        return $this->funcionario_abertura_id;
    }

    public function setFuncionarioAberturaID($funcionario_abertura_id)
    {
        $this->funcionario_abertura_id = $funcionario_abertura_id;
    }

    /**
     * Data de abertura do caixa
     */
    public function getDataAbertura()
    {
        return $this->data_abertura;
    }

    public function setDataAbertura($data_abertura)
    {
        $this->data_abertura = $data_abertura;
    }

    /**
     * Funcionário que fechou o caixa
     */
    public function getFuncionarioFechamentoID()
    {
        return $this->funcionario_fechamento_id;
    }

    public function setFuncionarioFechamentoID($funcionario_fechamento_id)
    {
        $this->funcionario_fechamento_id = $funcionario_fechamento_id;
    }

    /**
     * Data de fechamento do caixa
     */
    public function getDataFechamento()
    {
        return $this->data_fechamento;
    }

    public function setDataFechamento($data_fechamento)
    {
        $this->data_fechamento = $data_fechamento;
    }

    public function toArray()
    {
        $movimentacao = array();
        $movimentacao['id'] = $this->getID();
        $movimentacao['sessaoid'] = $this->getSessaoID();
        $movimentacao['caixaid'] = $this->getCaixaID();
        $movimentacao['aberta'] = $this->getAberta();
        $movimentacao['funcionarioaberturaid'] = $this->getFuncionarioAberturaID();
        $movimentacao['dataabertura'] = $this->getDataAbertura();
        $movimentacao['funcionariofechamentoid'] = $this->getFuncionarioFechamentoID();
        $movimentacao['datafechamento'] = $this->getDataFechamento();
        return $movimentacao;
    }

    public static function getPeloID($id)
    {
        $query = DB::$pdo->from('Movimentacoes')
                         ->where(array('id' => $id));
        return new ZMovimentacao($query->fetch());
    }

    private static function validarCampos(&$movimentacao)
    {
        $erros = array();
        if (!is_numeric($movimentacao['sessaoid'])) {
            $erros['sessaoid'] = 'A sessão não foi informada';
        }
        if (!is_numeric($movimentacao['caixaid'])) {
            $erros['caixaid'] = 'O caixa não foi informado';
        }
        $movimentacao['aberta'] = trim($movimentacao['aberta']);
        if (strlen($movimentacao['aberta']) == 0) {
            $movimentacao['aberta'] = 'N';
        } elseif (!in_array($movimentacao['aberta'], array('Y', 'N'))) {
            $erros['aberta'] = 'A aberta informada não é válida';
        }
        if (!is_numeric($movimentacao['funcionarioaberturaid'])) {
            $erros['funcionarioaberturaid'] = 'A funcionário inicializador não foi informada';
        }
        $movimentacao['dataabertura'] = date('Y-m-d H:i:s');
        $movimentacao['funcionariofechamentoid'] = trim($movimentacao['funcionariofechamentoid']);
        if (strlen($movimentacao['funcionariofechamentoid']) == 0) {
            $movimentacao['funcionariofechamentoid'] = null;
        } elseif (!is_numeric($movimentacao['funcionariofechamentoid'])) {
            $erros['funcionariofechamentoid'] = 'O funcionário fechador não foi informado';
        }
        $movimentacao['datafechamento'] = date('Y-m-d H:i:s');
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

    public static function cadastrar($movimentacao)
    {
        $_movimentacao = $movimentacao->toArray();
        self::validarCampos($_movimentacao);
        try {
            $_movimentacao['id'] = DB::$pdo->insertInto('Movimentacoes')->values($_movimentacao)->execute();
        } catch (Exception $e) {
            self::handleException($e);
            throw $e;
        }
        return self::getPeloID($_movimentacao['id']);
    }

    public static function atualizar($movimentacao)
    {
        $_movimentacao = $movimentacao->toArray();
        if (!$_movimentacao['id']) {
            throw new ValidationException(array('id' => 'O id da movimentacao não foi informado'));
        }
        self::validarCampos($_movimentacao);
        $campos = array(
            'sessaoid',
            'caixaid',
            'aberta',
            'funcionarioaberturaid',
            'funcionariofechamentoid',
            'datafechamento',
        );
        try {
            $query = DB::$pdo->update('Movimentacoes');
            $query = $query->set(array_intersect_key($_movimentacao, array_flip($campos)));
            $query = $query->where('id', $_movimentacao['id']);
            $query->execute();
        } catch (Exception $e) {
            self::handleException($e);
            throw $e;
        }
        return self::getPeloID($_movimentacao['id']);
    }

    public static function existe($caixa_id)
    {
        $query = DB::$pdo->from('Movimentacoes')
                         ->where('caixaid', $caixa_id);
        return $query->count() > 0;
    }

    private static function initSearch($caixa_id, $aberta, $inicializador_id)
    {
        $query = DB::$pdo->from('Movimentacoes')
                         ->orderBy('id DESC');
        if (is_numeric($caixa_id)) {
            $query = $query->where('caixaid', intval($caixa_id));
        }
        $aberta = trim($aberta);
        if ($aberta != '') {
            $query = $query->where('aberta', $aberta);
        }
        if (is_numeric($inicializador_id)) {
            $query = $query->where('funcionarioaberturaid', intval($inicializador_id));
        }
        return $query;
    }

    public static function getTodas(
        $caixa_id = null,
        $aberta = null,
        $inicializador_id = null,
        $inicio = null,
        $quantidade = null
    ) {
        $query = self::initSearch($caixa_id, $aberta, $inicializador_id);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_movimentacaos = $query->fetchAll();
        $movimentacaos = array();
        foreach ($_movimentacaos as $movimentacao) {
            $movimentacaos[] = new ZMovimentacao($movimentacao);
        }
        return $movimentacaos;
    }

    public static function getCount($caixa_id = null, $aberta = null, $inicializador_id = null)
    {
        $query = self::initSearch($caixa_id, $aberta, $inicializador_id);
        return $query->count();
    }

    private static function initSearchDaSessaoID($sessao_id)
    {
        return   DB::$pdo->from('Movimentacoes')
                         ->where(array('sessaoid' => $sessao_id))
                         ->orderBy('id ASC');
    }

    public static function getTodasDaSessaoID($sessao_id, $inicio = null, $quantidade = null)
    {
        $query = self::initSearchDaSessaoID($sessao_id);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_movimentacaos = $query->fetchAll();
        $movimentacaos = array();
        foreach ($_movimentacaos as $movimentacao) {
            $movimentacaos[] = new ZMovimentacao($movimentacao);
        }
        return $movimentacaos;
    }

    public static function getCountDaSessaoID($sessao_id)
    {
        $query = self::initSearchDaSessaoID($sessao_id);
        return $query->count();
    }

    private static function initSearchDaCaixaID($caixa_id)
    {
        return   DB::$pdo->from('Movimentacoes')
                         ->where(array('caixaid' => $caixa_id))
                         ->orderBy('id ASC');
    }

    public static function getTodasDaCaixaID($caixa_id, $inicio = null, $quantidade = null)
    {
        $query = self::initSearchDaCaixaID($caixa_id);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_movimentacaos = $query->fetchAll();
        $movimentacaos = array();
        foreach ($_movimentacaos as $movimentacao) {
            $movimentacaos[] = new ZMovimentacao($movimentacao);
        }
        return $movimentacaos;
    }

    public static function getCountDaCaixaID($caixa_id)
    {
        $query = self::initSearchDaCaixaID($caixa_id);
        return $query->count();
    }

    private static function initSearchDaFuncionarioAberturaID($funcionario_abertura_id)
    {
        return   DB::$pdo->from('Movimentacoes')
                         ->where(array('funcionarioaberturaid' => $funcionario_abertura_id))
                         ->orderBy('id ASC');
    }

    public static function getTodasDaFuncionarioAberturaID($funcionario_abertura_id, $inicio = null, $quantidade = null)
    {
        $query = self::initSearchDaFuncionarioAberturaID($funcionario_abertura_id);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_movimentacaos = $query->fetchAll();
        $movimentacaos = array();
        foreach ($_movimentacaos as $movimentacao) {
            $movimentacaos[] = new ZMovimentacao($movimentacao);
        }
        return $movimentacaos;
    }

    public static function getCountDaFuncionarioAberturaID($funcionario_abertura_id)
    {
        $query = self::initSearchDaFuncionarioAberturaID($funcionario_abertura_id);
        return $query->count();
    }

    private static function initSearchDoFuncionarioFechamentoID($funcionario_fechamento_id)
    {
        return   DB::$pdo->from('Movimentacoes')
                         ->where(array('funcionariofechamentoid' => $funcionario_fechamento_id))
                         ->orderBy('id ASC');
    }

    public static function getTodasDoFuncionarioFechamentoID($funcionario_fechamento_id, $inicio = null, $quantidade = null)
    {
        $query = self::initSearchDoFuncionarioFechamentoID($funcionario_fechamento_id);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_movimentacaos = $query->fetchAll();
        $movimentacaos = array();
        foreach ($_movimentacaos as $movimentacao) {
            $movimentacaos[] = new ZMovimentacao($movimentacao);
        }
        return $movimentacaos;
    }

    public static function getCountDoFuncionarioFechamentoID($funcionario_fechamento_id)
    {
        $query = self::initSearchDoFuncionarioFechamentoID($funcionario_fechamento_id);
        return $query->count();
    }
}
