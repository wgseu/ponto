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
 * Cheques lançados como pagamentos
 */
class ZCheque
{
    private $id;
    private $banco_id;
    private $agencia;
    private $conta;
    private $cliente_id;
    private $parcelas;
    private $total;
    private $cancelado;
    private $data_cadastro;

    public function __construct($cheque = [])
    {
        if (is_array($cheque)) {
            $this->setID(isset($cheque['id'])?$cheque['id']:null);
            $this->setBancoID(isset($cheque['bancoid'])?$cheque['bancoid']:null);
            $this->setAgencia(isset($cheque['agencia'])?$cheque['agencia']:null);
            $this->setConta(isset($cheque['conta'])?$cheque['conta']:null);
            $this->setClienteID(isset($cheque['clienteid'])?$cheque['clienteid']:null);
            $this->setParcelas(isset($cheque['parcelas'])?$cheque['parcelas']:null);
            $this->setTotal(isset($cheque['total'])?$cheque['total']:null);
            $this->setCancelado(isset($cheque['cancelado'])?$cheque['cancelado']:null);
            $this->setDataCadastro(isset($cheque['datacadastro'])?$cheque['datacadastro']:null);
        }
    }

    /**
     * Identificador do cheque
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
     * Banco do cheque
     */
    public function getBancoID()
    {
        return $this->banco_id;
    }

    public function setBancoID($banco_id)
    {
        $this->banco_id = $banco_id;
    }

    /**
     * Número da agência
     */
    public function getAgencia()
    {
        return $this->agencia;
    }

    public function setAgencia($agencia)
    {
        $this->agencia = $agencia;
    }

    /**
     * Número da conta do banco descrito no cheque
     */
    public function getConta()
    {
        return $this->conta;
    }

    public function setConta($conta)
    {
        $this->conta = $conta;
    }

    /**
     * Cliente que emitiu o cheque
     */
    public function getClienteID()
    {
        return $this->cliente_id;
    }

    public function setClienteID($cliente_id)
    {
        $this->cliente_id = $cliente_id;
    }

    /**
     * Quantidade de parcelas/folhas de cheque
     */
    public function getParcelas()
    {
        return $this->parcelas;
    }

    public function setParcelas($parcelas)
    {
        $this->parcelas = $parcelas;
    }

    /**
     * Total pago em cheque
     */
    public function getTotal()
    {
        return $this->total;
    }

    public function setTotal($total)
    {
        $this->total = $total;
    }

    /**
     * Informa se o cheque e todas as suas folhas estão cancelados
     */
    public function getCancelado()
    {
        return $this->cancelado;
    }

    /**
     * Informa se o cheque e todas as suas folhas estão cancelados
     */
    public function isCancelado()
    {
        return $this->cancelado == 'Y';
    }

    public function setCancelado($cancelado)
    {
        $this->cancelado = $cancelado;
    }

    /**
     * Data de cadastro do cheque
     */
    public function getDataCadastro()
    {
        return $this->data_cadastro;
    }

    public function setDataCadastro($data_cadastro)
    {
        $this->data_cadastro = $data_cadastro;
    }

    public function toArray()
    {
        $cheque = [];
        $cheque['id'] = $this->getID();
        $cheque['bancoid'] = $this->getBancoID();
        $cheque['agencia'] = $this->getAgencia();
        $cheque['conta'] = $this->getConta();
        $cheque['clienteid'] = $this->getClienteID();
        $cheque['parcelas'] = $this->getParcelas();
        $cheque['total'] = $this->getTotal();
        $cheque['cancelado'] = $this->getCancelado();
        $cheque['datacadastro'] = $this->getDataCadastro();
        return $cheque;
    }

    public static function getPeloID($id)
    {
        $query = \DB::$pdo->from('Cheques')
                         ->where(['id' => $id]);
        return new Cheque($query->fetch());
    }

    private static function validarCampos(&$cheque)
    {
        $erros = [];
        if (!is_numeric($cheque['bancoid'])) {
            $erros['bancoid'] = 'O banco não foi informado';
        }
        $cheque['agencia'] = strip_tags(trim($cheque['agencia']));
        if (strlen($cheque['agencia']) == 0) {
            $erros['agencia'] = 'A agência não pode ser vazia';
        }
        $cheque['conta'] = strip_tags(trim($cheque['conta']));
        if (strlen($cheque['conta']) == 0) {
            $erros['conta'] = 'A conta não pode ser vazia';
        }
        if (!is_numeric($cheque['clienteid'])) {
            $erros['clienteid'] = 'O cliente não foi informado';
        }
        if (!is_numeric($cheque['parcelas'])) {
            $erros['parcelas'] = 'A parcelas não foi informada';
        }
        if (!is_numeric($cheque['total'])) {
            $erros['total'] = 'O total não foi informado';
        }
        $cheque['cancelado'] = trim($cheque['cancelado']);
        if (strlen($cheque['cancelado']) == 0) {
            $cheque['cancelado'] = 'N';
        } elseif (!in_array($cheque['cancelado'], ['Y', 'N'])) {
            $erros['cancelado'] = 'O cancelado informado não é válido';
        }
        $cheque['datacadastro'] = date('Y-m-d H:i:s');
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

    public static function cadastrar($cheque)
    {
        $_cheque = $cheque->toArray();
        self::validarCampos($_cheque);
        try {
            $_cheque['id'] = \DB::$pdo->insertInto('Cheques')->values($_cheque)->execute();
        } catch (Exception $e) {
            self::handleException($e);
            throw $e;
        }
        return self::findByID($_cheque['id']);
    }

    public static function atualizar($cheque)
    {
        $_cheque = $cheque->toArray();
        if (!$_cheque['id']) {
            throw new ValidationException(['id' => 'O id do cheque não foi informado']);
        }
        self::validarCampos($_cheque);
        $campos = [
            'bancoid',
            'agencia',
            'conta',
            'clienteid',
            'parcelas',
            'total',
            'cancelado',
        ];
        try {
            $query = \DB::$pdo->update('Cheques');
            $query = $query->set(array_intersect_key($_cheque, array_flip($campos)));
            $query = $query->where('id', $_cheque['id']);
            $query->execute();
        } catch (Exception $e) {
            self::handleException($e);
            throw $e;
        }
        return self::findByID($_cheque['id']);
    }

    private static function initSearch()
    {
        return   \DB::$pdo->from('Cheques')
                         ->orderBy('id ASC');
    }

    public static function getTodos($inicio = null, $quantidade = null)
    {
        $query = self::initSearch();
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_cheques = $query->fetchAll();
        $cheques = [];
        foreach ($_cheques as $cheque) {
            $cheques[] = new Cheque($cheque);
        }
        return $cheques;
    }

    public static function getCount()
    {
        $query = self::initSearch();
        return $query->count();
    }

    private static function initSearchDoBancoID($banco_id)
    {
        return   \DB::$pdo->from('Cheques')
                         ->where(['bancoid' => $banco_id])
                         ->orderBy('id ASC');
    }

    public static function getTodosDoBancoID($banco_id, $inicio = null, $quantidade = null)
    {
        $query = self::initSearchDoBancoID($banco_id);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_cheques = $query->fetchAll();
        $cheques = [];
        foreach ($_cheques as $cheque) {
            $cheques[] = new Cheque($cheque);
        }
        return $cheques;
    }

    public static function getCountDoBancoID($banco_id)
    {
        $query = self::initSearchDoBancoID($banco_id);
        return $query->count();
    }

    private static function initSearchDoClienteID($cliente_id)
    {
        return   \DB::$pdo->from('Cheques')
                         ->where(['clienteid' => $cliente_id])
                         ->orderBy('id ASC');
    }

    public static function getTodosDoClienteID($cliente_id, $inicio = null, $quantidade = null)
    {
        $query = self::initSearchDoClienteID($cliente_id);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_cheques = $query->fetchAll();
        $cheques = [];
        foreach ($_cheques as $cheque) {
            $cheques[] = new Cheque($cheque);
        }
        return $cheques;
    }

    public static function getCountDoClienteID($cliente_id)
    {
        $query = self::initSearchDoClienteID($cliente_id);
        return $query->count();
    }
}
