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
 * Folhas de cheque de um pagamento
 */
class ZFolhaCheque
{
    private $id;
    private $cheque_id;
    private $compensacao;
    private $numero;
    private $valor;
    private $vencimento;
    private $c = [];
    private $serie;
    private $recolhido;
    private $recolhimento;

    public function __construct($folha_cheque = [])
    {
        if (is_array($folha_cheque)) {
            $this->setID(isset($folha_cheque['id'])?$folha_cheque['id']:null);
            $this->setChequeID(isset($folha_cheque['chequeid'])?$folha_cheque['chequeid']:null);
            $this->setCompensacao(isset($folha_cheque['compensacao'])?$folha_cheque['compensacao']:null);
            $this->setNumero(isset($folha_cheque['numero'])?$folha_cheque['numero']:null);
            $this->setValor(isset($folha_cheque['valor'])?$folha_cheque['valor']:null);
            $this->setVencimento(isset($folha_cheque['vencimento'])?$folha_cheque['vencimento']:null);
            for ($i = 1; $i <= 3; $i++) {
                $this->setC($i, isset($folha_cheque['c'.$i])?$folha_cheque['c'.$i]:null);
            }
            $this->setSerie(isset($folha_cheque['serie'])?$folha_cheque['serie']:null);
            $this->setRecolhido(isset($folha_cheque['recolhido'])?$folha_cheque['recolhido']:null);
            $this->setRecolhimento(isset($folha_cheque['recolhimento'])?$folha_cheque['recolhimento']:null);
        }
    }

    /**
     * Identificador da folha de cheque
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
     * Cheque a qual pertence esssa folha
     */
    public function getChequeID()
    {
        return $this->cheque_id;
    }

    public function setChequeID($cheque_id)
    {
        $this->cheque_id = $cheque_id;
    }

    /**
     * Número de compensação do cheque
     */
    public function getCompensacao()
    {
        return $this->compensacao;
    }

    public function setCompensacao($compensacao)
    {
        $this->compensacao = $compensacao;
    }

    /**
     * Número da folha do cheque
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
     * Valor na folha do cheque
     */
    public function getValor()
    {
        return $this->valor;
    }

    public function setValor($valor)
    {
        $this->valor = $valor;
    }

    /**
     * Data de vencimento do cheque
     */
    public function getVencimento()
    {
        return $this->vencimento;
    }

    public function setVencimento($vencimento)
    {
        $this->vencimento = $vencimento;
    }

    /**
     * C1 do cheque
     */
    public function getC($index)
    {
        if ($index < 1 || $index > 3) {
            throw new Exception('Índice '.$index.' inválido, aceito somente de 1 até 3');
        }
        return $this->c[$index];
    }

    public function setC($index, $value)
    {
        if ($index < 1 || $index > 3) {
            throw new Exception('Índice '.$index.' inválido, aceito somente de 1 até 3');
        }
        $this->c[$index] = $value;
    }

    /**
     * Número de série do cheque
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
     * Informa se o cheque foi recolhido no banco
     */
    public function getRecolhido()
    {
        return $this->recolhido;
    }

    /**
     * Informa se o cheque foi recolhido no banco
     */
    public function isRecolhido()
    {
        return $this->recolhido == 'Y';
    }

    public function setRecolhido($recolhido)
    {
        $this->recolhido = $recolhido;
    }

    /**
     * Data de recolhimento do cheque
     */
    public function getRecolhimento()
    {
        return $this->recolhimento;
    }

    public function setRecolhimento($recolhimento)
    {
        $this->recolhimento = $recolhimento;
    }

    public function toArray()
    {
        $folha_cheque = [];
        $folha_cheque['id'] = $this->getID();
        $folha_cheque['chequeid'] = $this->getChequeID();
        $folha_cheque['compensacao'] = $this->getCompensacao();
        $folha_cheque['numero'] = $this->getNumero();
        $folha_cheque['valor'] = $this->getValor();
        $folha_cheque['vencimento'] = $this->getVencimento();
        $folha_cheque['serie'] = $this->getSerie();
        $folha_cheque['recolhido'] = $this->getRecolhido();
        $folha_cheque['recolhimento'] = $this->getRecolhimento();
        for ($i = 1; $i <= 3; $i++) {
            $folha_cheque['c'.$i] = $this->getC($i);
        }
        return $folha_cheque;
    }

    public static function getPeloID($id)
    {
        $query = DB::$pdo->from('Folhas_Cheques')
                         ->where(['id' => $id]);
        return new ZFolhaCheque($query->fetch());
    }

    public static function getPeloChequeIDNumero($cheque_id, $numero)
    {
        $query = DB::$pdo->from('Folhas_Cheques')
                         ->where(['chequeid' => $cheque_id, 'numero' => $numero]);
        return new ZFolhaCheque($query->fetch());
    }

    private static function validarCampos(&$folha_cheque)
    {
        $erros = [];
        if (!is_numeric($folha_cheque['chequeid'])) {
            $erros['chequeid'] = 'O cheque não foi informado';
        }
        $folha_cheque['compensacao'] = strip_tags(trim($folha_cheque['compensacao']));
        if (strlen($folha_cheque['compensacao']) == 0) {
            $erros['compensacao'] = 'A compensação não pode ser vazia';
        }
        $folha_cheque['numero'] = strip_tags(trim($folha_cheque['numero']));
        if (strlen($folha_cheque['numero']) == 0) {
            $erros['numero'] = 'O número não pode ser vazio';
        }
        if (!is_numeric($folha_cheque['valor'])) {
            $erros['valor'] = 'O valor não foi informado';
        }
        $folha_cheque['vencimento'] = date('Y-m-d H:i:s');
        for ($i = 1; $i <= 3; $i++) {
            if (!is_numeric($folha_cheque['c'.$i])) {
                $erros['c'.$i] = 'O c1 não foi informado';
            }
        }
        $folha_cheque['serie'] = strip_tags(trim($folha_cheque['serie']));
        if (strlen($folha_cheque['serie']) == 0) {
            $folha_cheque['serie'] = null;
        }
        $folha_cheque['recolhido'] = trim($folha_cheque['recolhido']);
        if (strlen($folha_cheque['recolhido']) == 0) {
            $folha_cheque['recolhido'] = 'N';
        } elseif (!in_array($folha_cheque['recolhido'], ['Y', 'N'])) {
            $erros['recolhido'] = 'O recolhido informado não é válido';
        }
        $folha_cheque['recolhimento'] = date('Y-m-d H:i:s');
        if (!empty($erros)) {
            throw new ValidationException($erros);
        }
    }

    private static function handleException(&$e)
    {
        if (stripos($e->getMessage(), 'PRIMARY') !== false) {
            throw new ValidationException(['id' => 'O ID informado já está cadastrado']);
        }
        if (stripos($e->getMessage(), 'UK_Folhas_Cheques_ChequeID_Numero') !== false) {
            throw new ValidationException(['numero' => 'O número informado já está cadastrado']);
        }
    }

    public static function cadastrar($folha_cheque)
    {
        $_folha_cheque = $folha_cheque->toArray();
        self::validarCampos($_folha_cheque);
        try {
            $_folha_cheque['id'] = DB::$pdo->insertInto('Folhas_Cheques')->values($_folha_cheque)->execute();
        } catch (Exception $e) {
            self::handleException($e);
            throw $e;
        }
        return self::getPeloID($_folha_cheque['id']);
    }

    public static function atualizar($folha_cheque)
    {
        $_folha_cheque = $folha_cheque->toArray();
        if (!$_folha_cheque['id']) {
            throw new ValidationException(['id' => 'O id do folhacheque não foi informado']);
        }
        self::validarCampos($_folha_cheque);
        $campos = [
            'chequeid',
            'compensacao',
            'numero',
            'valor',
            'vencimento',
            'serie',
            'recolhido',
            'recolhimento',
        ];
        for ($i = 1; $i <= 3; $i++) {
            $campos[] = 'c'.$i;
        }
        try {
            $query = DB::$pdo->update('Folhas_Cheques');
            $query = $query->set(array_intersect_key($_folha_cheque, array_flip($campos)));
            $query = $query->where('id', $_folha_cheque['id']);
            $query->execute();
        } catch (Exception $e) {
            self::handleException($e);
            throw $e;
        }
        return self::getPeloID($_folha_cheque['id']);
    }

    public function recolher()
    {
        if ($this->isRecolhido()) {
            throw new Exception('Essa folha de cheque já foi recolhida');
        }
        $recolhimento = date('Y-m-d H:i:s');
        $query = DB::$pdo->update('Folhas_Cheques')
                         ->set('recolhido', 'Y')
                         ->set('recolhimento', $recolhimento)
                         ->where('id', $this->getID());
        $query->execute();
        $this->setRecolhido('Y');
        $this->setRecolhimento($recolhimento);
    }

    private static function initSearch($banco_id, $cliente_id, $recolhido)
    {
        $query = DB::$pdo->from('Folhas_Cheques f')
                         ->leftJoin('Cheques c ON c.id = f.chequeid')
                         ->orderBy('f.id DESC');
        if (is_numeric($banco_id)) {
            $query = $query->where('c.bancoid', $banco_id);
        }
        if (is_numeric($cliente_id)) {
            $query = $query->where('c.clienteid', $cliente_id);
        }
        $recolhido = trim($recolhido);
        if ($recolhido != '') {
            $query = $query->where('f.recolhido', $recolhido);
        }
        return $query;
    }

    public static function getTodos($banco_id = null, $cliente_id = null, $recolhido = null, $inicio = null, $quantidade = null)
    {
        $query = self::initSearch($banco_id, $cliente_id, $recolhido);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_folha_cheques = $query->fetchAll();
        $folha_cheques = [];
        foreach ($_folha_cheques as $folha_cheque) {
            $folha_cheques[] = new ZFolhaCheque($folha_cheque);
        }
        return $folha_cheques;
    }

    public static function getCount($banco_id = null, $cliente_id = null, $recolhido = null)
    {
        $query = self::initSearch($banco_id, $cliente_id, $recolhido);
        return $query->count();
    }

    private static function initSearchDoChequeID($cheque_id)
    {
        return   DB::$pdo->from('Folhas_Cheques')
                         ->where(['chequeid' => $cheque_id])
                         ->orderBy('id ASC');
    }

    public static function getTodosDoChequeID($cheque_id, $inicio = null, $quantidade = null)
    {
        $query = self::initSearchDoChequeID($cheque_id);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_folha_cheques = $query->fetchAll();
        $folha_cheques = [];
        foreach ($_folha_cheques as $folha_cheque) {
            $folha_cheques[] = new ZFolhaCheque($folha_cheque);
        }
        return $folha_cheques;
    }

    public static function getCountDoChequeID($cheque_id)
    {
        $query = self::initSearchDoChequeID($cheque_id);
        return $query->count();
    }
}
