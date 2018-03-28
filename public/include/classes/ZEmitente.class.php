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
class EmitenteAmbiente
{
    const HOMOLOGACAO = 'Homologacao';
    const PRODUCAO = 'Producao';
}

/**
 * Dados do emitente das notas fiscais
 */
class ZEmitente
{
    private $id;
    private $contador_id;
    private $regime_id;
    private $ambiente;
    private $csc;
    private $token;
    private $ibpt;
    private $chave_privada;
    private $chave_publica;
    private $data_expiracao;

    public function __construct($emitente = [])
    {
        $this->fromArray($emitente);
    }

    /**
     * Identificador do emitente, sempre 1
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
     * Contador responsável pela contabilidade da empresa
     */
    public function getContadorID()
    {
        return $this->contador_id;
    }

    public function setContadorID($contador_id)
    {
        $this->contador_id = $contador_id;
    }

    /**
     * Regime tributário da empresa
     */
    public function getRegimeID()
    {
        return $this->regime_id;
    }

    public function setRegimeID($regime_id)
    {
        $this->regime_id = $regime_id;
    }

    /**
     * Ambiente de emissão das notas
     */
    public function getAmbiente()
    {
        return $this->ambiente;
    }

    public function setAmbiente($ambiente)
    {
        $this->ambiente = $ambiente;
    }

    /**
     * Código de segurança do contribuinte
     */
    public function getCSC()
    {
        return $this->csc;
    }

    public function setCSC($csc)
    {
        $this->csc = $csc;
    }

    /**
     * Token do código de segurança do contribuinte
     */
    public function getToken()
    {
        return $this->token;
    }

    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * Token da API do IBPT
     */
    public function getIBPT()
    {
        return $this->ibpt;
    }

    public function setIBPT($ibpt)
    {
        $this->ibpt = $ibpt;
    }

    /**
     * Nome do arquivo da chave privada
     */
    public function getChavePrivada()
    {
        return $this->chave_privada;
    }

    public function setChavePrivada($chave_privada)
    {
        $this->chave_privada = $chave_privada;
    }

    /**
     * Nome do arquivo da chave pública
     */
    public function getChavePublica()
    {
        return $this->chave_publica;
    }

    public function setChavePublica($chave_publica)
    {
        $this->chave_publica = $chave_publica;
    }

    /**
     * Data de expiração do certificado
     */
    public function getDataExpiracao()
    {
        return $this->data_expiracao;
    }

    public function setDataExpiracao($data_expiracao)
    {
        $this->data_expiracao = $data_expiracao;
    }

    public function toArray()
    {
        $emitente = [];
        $emitente['id'] = $this->getID();
        $emitente['contadorid'] = $this->getContadorID();
        $emitente['regimeid'] = $this->getRegimeID();
        $emitente['ambiente'] = $this->getAmbiente();
        $emitente['csc'] = $this->getCSC();
        $emitente['token'] = $this->getToken();
        $emitente['ibpt'] = $this->getIBPT();
        $emitente['chaveprivada'] = $this->getChavePrivada();
        $emitente['chavepublica'] = $this->getChavePublica();
        $emitente['dataexpiracao'] = $this->getDataExpiracao();
        return $emitente;
    }

    public function fromArray($emitente = [])
    {
        if (!is_array($emitente)) {
            return $this;
        }
        $this->setID(isset($emitente['id'])?$emitente['id']:null);
        $this->setContadorID(isset($emitente['contadorid'])?$emitente['contadorid']:null);
        $this->setRegimeID(isset($emitente['regimeid'])?$emitente['regimeid']:null);
        $this->setAmbiente(isset($emitente['ambiente'])?$emitente['ambiente']:null);
        $this->setCSC(isset($emitente['csc'])?$emitente['csc']:null);
        $this->setToken(isset($emitente['token'])?$emitente['token']:null);
        $this->setIBPT(isset($emitente['ibpt'])?$emitente['ibpt']:null);
        $this->setChavePrivada(isset($emitente['chaveprivada'])?$emitente['chaveprivada']:null);
        $this->setChavePublica(isset($emitente['chavepublica'])?$emitente['chavepublica']:null);
        $this->setDataExpiracao(isset($emitente['dataexpiracao'])?$emitente['dataexpiracao']:null);
    }

    public static function getPeloID($id)
    {
        $query = \DB::$pdo->from('Emitentes')
                         ->where(['id' => $id]);
        return new Emitente($query->fetch());
    }

    private static function validarCampos(&$emitente)
    {
        $erros = [];
        $emitente['id'] = trim($emitente['id']);
        if (strlen($emitente['id']) == 0) {
            $emitente['id'] = null;
        } elseif (!in_array($emitente['id'], ['1'])) {
            $erros['id'] = 'O id informado não é válido';
        }
        $emitente['contadorid'] = trim($emitente['contadorid']);
        if (strlen($emitente['contadorid']) == 0) {
            $emitente['contadorid'] = null;
        } elseif (!is_numeric($emitente['contadorid'])) {
            $erros['contadorid'] = 'O contador não foi informado';
        }
        if (!is_numeric($emitente['regimeid'])) {
            $erros['regimeid'] = 'O regime tributário não foi informado';
        }
        $emitente['ambiente'] = strval($emitente['ambiente']);
        if (!in_array($emitente['ambiente'], ['Homologacao', 'Producao'])) {
            $erros['ambiente'] = 'O ambiente informado não é válido';
        }
        $emitente['csc'] = strip_tags(trim($emitente['csc']));
        if (strlen($emitente['csc']) == 0) {
            $erros['csc'] = 'O csc não pode ser vazio';
        }
        $emitente['token'] = strip_tags(trim($emitente['token']));
        if (strlen($emitente['token']) == 0) {
            $erros['token'] = 'O token não pode ser vazio';
        }
        $emitente['ibpt'] = strip_tags(trim($emitente['ibpt']));
        if (strlen($emitente['ibpt']) == 0) {
            $emitente['ibpt'] = null;
        }
        $emitente['chaveprivada'] = strip_tags(trim($emitente['chaveprivada']));
        if (strlen($emitente['chaveprivada']) == 0) {
            $erros['chaveprivada'] = 'A chave privada não pode ser vazia';
        }
        $emitente['chavepublica'] = strip_tags(trim($emitente['chavepublica']));
        if (strlen($emitente['chavepublica']) == 0) {
            $erros['chavepublica'] = 'A chave pública não pode ser vazia';
        }
        $emitente['dataexpiracao'] = date('Y-m-d H:i:s');
        if (!empty($erros)) {
            throw new ValidationException($erros);
        }
    }

    private static function handleException(&$e)
    {
        if (stripos($e->getMessage(), 'PRIMARY') !== false) {
            throw new ValidationException(['id' => 'O id informado já está cadastrado']);
        }
    }

    public static function cadastrar($emitente)
    {
        $_emitente = $emitente->toArray();
        self::validarCampos($_emitente);
        try {
            $_emitente['id'] = \DB::$pdo->insertInto('Emitentes')->values($_emitente)->execute();
        } catch (Exception $e) {
            self::handleException($e);
            throw $e;
        }
        return self::findByID($_emitente['id']);
    }

    public static function atualizar($emitente)
    {
        $_emitente = $emitente->toArray();
        if (!$_emitente['id']) {
            throw new ValidationException(['id' => 'O id do emitente não foi informado']);
        }
        self::validarCampos($_emitente);
        $campos = [
            'contadorid',
            'regimeid',
            'ambiente',
            'csc',
            'token',
            'ibpt',
            'chaveprivada',
            'chavepublica',
            'dataexpiracao',
        ];
        try {
            $query = \DB::$pdo->update('Emitentes');
            $query = $query->set(array_intersect_key($_emitente, array_flip($campos)));
            $query = $query->where('id', $_emitente['id']);
            $query->execute();
        } catch (Exception $e) {
            self::handleException($e);
            throw $e;
        }
        return self::findByID($_emitente['id']);
    }

    public static function excluir($id)
    {
        if (!$id) {
            throw new \Exception('Não foi possível excluir o emitente, o id do emitente não foi informado');
        }
        $query = \DB::$pdo->deleteFrom('Emitentes')
                         ->where(['id' => $id]);
        return $query->execute();
    }

    private static function initSearchDoContadorID($contador_id)
    {
        return   \DB::$pdo->from('Emitentes')
                         ->where(['contadorid' => $contador_id])
                         ->orderBy('id ASC');
    }

    public static function getTodosDoContadorID($contador_id, $inicio = null, $quantidade = null)
    {
        $query = self::initSearchDoContadorID($contador_id);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_emitentes = $query->fetchAll();
        $emitentes = [];
        foreach ($_emitentes as $emitente) {
            $emitentes[] = new Emitente($emitente);
        }
        return $emitentes;
    }

    public static function getCountDoContadorID($contador_id)
    {
        $query = self::initSearchDoContadorID($contador_id);
        return $query->count();
    }

    private static function initSearchDoRegimeID($regime_id)
    {
        return   \DB::$pdo->from('Emitentes')
                         ->where(['regimeid' => $regime_id])
                         ->orderBy('id ASC');
    }

    public static function getTodosDoRegimeID($regime_id, $inicio = null, $quantidade = null)
    {
        $query = self::initSearchDoRegimeID($regime_id);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_emitentes = $query->fetchAll();
        $emitentes = [];
        foreach ($_emitentes as $emitente) {
            $emitentes[] = new Emitente($emitente);
        }
        return $emitentes;
    }

    public static function getCountDoRegimeID($regime_id)
    {
        $query = self::initSearchDoRegimeID($regime_id);
        return $query->count();
    }
}
