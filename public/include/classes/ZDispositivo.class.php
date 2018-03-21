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
class DispositivoTipo
{
    const COMPUTADOR = 'Computador';
    const TABLET = 'Tablet';
}

/**
 * Computadores e tablets com opções de acesso
 */
class ZDispositivo
{
    private $id;
    private $setor_id;
    private $caixa_id;
    private $nome;
    private $tipo;
    private $descricao;
    private $opcoes;
    private $serial;
    private $validacao;

    public function __construct($dispositivo = [])
    {
        if (is_array($dispositivo)) {
            $this->setID(isset($dispositivo['id'])?$dispositivo['id']:null);
            $this->setSetorID(isset($dispositivo['setorid'])?$dispositivo['setorid']:null);
            $this->setCaixaID(isset($dispositivo['caixaid'])?$dispositivo['caixaid']:null);
            $this->setNome(isset($dispositivo['nome'])?$dispositivo['nome']:null);
            $this->setTipo(isset($dispositivo['tipo'])?$dispositivo['tipo']:null);
            $this->setDescricao(isset($dispositivo['descricao'])?$dispositivo['descricao']:null);
            $this->setOpcoes(isset($dispositivo['opcoes'])?$dispositivo['opcoes']:null);
            $this->setSerial(isset($dispositivo['serial'])?$dispositivo['serial']:null);
            $this->setValidacao(isset($dispositivo['validacao'])?$dispositivo['validacao']:null);
        }
    }

    /**
     * Identificador do dispositivo
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Identificador do dispositivo
     */
    public function setID($id)
    {
        $this->id = $id;
    }

    /**
     * Setor em que o dispositivo está instalado/será usado
     */
    public function getSetorID()
    {
        return $this->setor_id;
    }

    /**
     * Setor em que o dispositivo está instalado/será usado
     */
    public function setSetorID($setor_id)
    {
        $this->setor_id = $setor_id;
    }

    /**
     * Finalidade do dispositivo, caixa ou terminal, o caixa é único entre os dispositivos
     */
    public function getCaixaID()
    {
        return $this->caixa_id;
    }

    /**
     * Finalidade do dispositivo, caixa ou terminal, o caixa é único entre os dispositivos
     */
    public function setCaixaID($caixa_id)
    {
        $this->caixa_id = $caixa_id;
    }

    /**
     * Nome do computador ou tablet em rede, único entre os dispositivos
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Nome do computador ou tablet em rede, único entre os dispositivos
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    /**
     * Tipo de dispositivo
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Tipo de dispositivo
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    /**
     * Descrição do dispositivo
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Descrição do dispositivo
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }

    /**
     * Opções do dispositivo, Ex.: Balança, identificador de chamadas e outros
     */
    public function getOpcoes()
    {
        return $this->opcoes;
    }

    /**
     * Opções do dispositivo, Ex.: Balança, identificador de chamadas e outros
     */
    public function setOpcoes($opcoes)
    {
        $this->opcoes = $opcoes;
    }

    /**
     * Serial do tablet para validação, único entre os dispositivos
     */
    public function getSerial()
    {
        return $this->serial;
    }

    /**
     * Serial do tablet para validação, único entre os dispositivos
     */
    public function setSerial($serial)
    {
        $this->serial = $serial;
    }

    /**
     * Validação do tablet
     */
    public function getValidacao()
    {
        return $this->validacao;
    }

    /**
     * Validação do tablet
     */
    public function setValidacao($validacao)
    {
        $this->validacao = $validacao;
    }
    public function toArray()
    {
        $dispositivo = [];
        $dispositivo['id'] = $this->getID();
        $dispositivo['setorid'] = $this->getSetorID();
        $dispositivo['caixaid'] = $this->getCaixaID();
        $dispositivo['nome'] = $this->getNome();
        $dispositivo['tipo'] = $this->getTipo();
        $dispositivo['descricao'] = $this->getDescricao();
        $dispositivo['opcoes'] = $this->getOpcoes();
        $dispositivo['serial'] = $this->getSerial();
        $dispositivo['validacao'] = $this->getValidacao();
        return $dispositivo;
    }

    public static function getPeloID($id)
    {
        $query = DB::$pdo->from('Dispositivos')
                         ->where(['id' => $id]);
        return new ZDispositivo($query->fetch());
    }

    public static function getPeloNome($nome)
    {
        $query = DB::$pdo->from('Dispositivos')
                         ->where(['nome' => $nome]);
        return new ZDispositivo($query->fetch());
    }

    public static function getNaoValidado()
    {
        $query = DB::$pdo->from('Dispositivos')
                         ->where(['validacao' => null,
                                       'tipo' => DispositivoTipo::TABLET])
                         ->limit(1)->offset(0);
        return new ZDispositivo($query->fetch());
    }

    public static function getPelaCaixaID($caixa_id)
    {
        $query = DB::$pdo->from('Dispositivos')
                         ->where(['caixaid' => $caixa_id]);
        return new ZDispositivo($query->fetch());
    }

    public static function getPelaSerial($serial)
    {
        $query = DB::$pdo->from('Dispositivos')
                         ->where(['serial' => $serial]);
        return new ZDispositivo($query->fetch());
    }

    private static function validarCampos(&$dispositivo)
    {
        $erros = [];
        if (!is_numeric($dispositivo['setorid'])) {
            $erros['setorid'] = 'O ID do setor não é um número';
        }
        $dispositivo['caixaid'] = trim($dispositivo['caixaid']);
        if (strlen($dispositivo['caixaid']) == 0) {
            $dispositivo['caixaid'] = null;
        } elseif (!is_numeric($dispositivo['caixaid'])) {
            $erros['caixaid'] = 'O ID do caixa não é um número';
        }
        $dispositivo['nome'] = strip_tags(trim($dispositivo['nome']));
        if (strlen($dispositivo['nome']) == 0) {
            $erros['nome'] = 'O Nome não pode ser vazio';
        }
        $dispositivo['tipo'] = trim($dispositivo['tipo']);
        if (strlen($dispositivo['tipo']) == 0) {
            $dispositivo['tipo'] = null;
        } elseif (!in_array($dispositivo['tipo'], ['Computador', 'Tablet'])) {
            $erros['tipo'] = 'O Tipo informado não é válido';
        }
        $dispositivo['descricao'] = strip_tags(trim($dispositivo['descricao']));
        if (strlen($dispositivo['descricao']) == 0) {
            $dispositivo['descricao'] = null;
        }
        if (!is_numeric($dispositivo['opcoes'])) {
            $erros['opcoes'] = 'As opções do dispositivo não é um número';
        } else {
            $dispositivo['opcoes'] = intval($dispositivo['opcoes']);
        }
        $dispositivo['serial'] = strip_tags(trim($dispositivo['serial']));
        if (strlen($dispositivo['serial']) == 0) {
            $dispositivo['serial'] = null;
        }
        $dispositivo['validacao'] = strip_tags(trim($dispositivo['validacao']));
        if (strlen($dispositivo['validacao']) == 0) {
            $dispositivo['validacao'] = null;
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
            throw new ValidationException(['nome' => 'O Nome informado já está cadastrado']);
        }
        if (stripos($e->getMessage(), 'CaixaID_UNIQUE') !== false) {
            throw new ValidationException(['caixaid' => 'Já existe um dispositivo para este caixa']);
        }
        if (stripos($e->getMessage(), 'Serial_UNIQUE') !== false) {
            throw new ValidationException(['serial' => 'O serial informado já está cadastrado']);
        }
    }

    public static function cadastrar($dispositivo)
    {
        $_dispositivo = $dispositivo->toArray();
        self::validarCampos($_dispositivo);
        try {
            $_dispositivo['id'] = DB::$pdo->insertInto('Dispositivos')->values($_dispositivo)->execute();
        } catch (Exception $e) {
            self::handleException($e);
            throw $e;
        }
        return self::getPeloID($_dispositivo['id']);
    }

    public static function atualizar($dispositivo)
    {
        $_dispositivo = $dispositivo->toArray();
        if (!$_dispositivo['id']) {
            throw new ValidationException(['id' => 'O id do dispositivo não foi informado']);
        }
        self::validarCampos($_dispositivo);
        $campos = [
            'setorid',
            'caixaid',
            'nome',
            'tipo',
            'descricao',
            'opcoes',
            'serial',
            'validacao',
        ];
        try {
            $query = DB::$pdo->update('Dispositivos');
            $query = $query->set(array_intersect_key($_dispositivo, array_flip($campos)));
            $query = $query->where('id', $_dispositivo['id']);
            $query->execute();
        } catch (Exception $e) {
            self::handleException($e);
            throw $e;
        }
        return self::getPeloID($_dispositivo['id']);
    }

    public static function excluir($id)
    {
        if (!$id) {
            throw new Exception('Não foi possível excluir o dispositivo, o id do dispositivo não foi informado');
        }
        $query = DB::$pdo->deleteFrom('Dispositivos')
                         ->where(['id' => $id]);
        return $query->execute();
    }

    private static function initSearch()
    {
        return   DB::$pdo->from('Dispositivos')
                         ->orderBy('id ASC');
    }

    public static function getTodos($inicio = null, $quantidade = null)
    {
        $query = self::initSearch();
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_dispositivos = $query->fetchAll();
        $dispositivos = [];
        foreach ($_dispositivos as $dispositivo) {
            $dispositivos[] = new ZDispositivo($dispositivo);
        }
        return $dispositivos;
    }

    public static function getCount()
    {
        $query = self::initSearch();
        return $query->count();
    }

    private static function initSearchDoTablet()
    {
        return   DB::$pdo->from('Dispositivos')
                         ->where(['tipo' => DispositivoTipo::TABLET])
                         ->orderBy('id ASC');
    }

    public static function getCountDoTablet()
    {
        $query = self::initSearchDoTablet();
        return $query->count();
    }

    private static function initSearchDoSetorID($setor_id)
    {
        return   DB::$pdo->from('Dispositivos')
                         ->where(['setorid' => $setor_id])
                         ->orderBy('id ASC');
    }

    public static function getTodosDoSetorID($setor_id, $inicio = null, $quantidade = null)
    {
        $query = self::initSearchDoSetorID($setor_id);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_dispositivos = $query->fetchAll();
        $dispositivos = [];
        foreach ($_dispositivos as $dispositivo) {
            $dispositivos[] = new ZDispositivo($dispositivo);
        }
        return $dispositivos;
    }

    public static function getCountDoSetorID($setor_id)
    {
        $query = self::initSearchDoSetorID($setor_id);
        return $query->count();
    }

    private static function initSearchDaCaixaID($caixa_id)
    {
        return   DB::$pdo->from('Dispositivos')
                         ->where(['caixaid' => $caixa_id])
                         ->orderBy('id ASC');
    }

    public static function getTodosDaCaixaID($caixa_id, $inicio = null, $quantidade = null)
    {
        $query = self::initSearchDaCaixaID($caixa_id);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_dispositivos = $query->fetchAll();
        $dispositivos = [];
        foreach ($_dispositivos as $dispositivo) {
            $dispositivos[] = new ZDispositivo($dispositivo);
        }
        return $dispositivos;
    }

    public static function getCountDaCaixaID($caixa_id)
    {
        $query = self::initSearchDaCaixaID($caixa_id);
        return $query->count();
    }
}
