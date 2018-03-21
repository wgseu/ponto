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
class FormaPagtoTipo
{
    const DINHEIRO = 'Dinheiro';
    const CARTAO = 'Cartao';
    const CHEQUE = 'Cheque';
    const CONTA = 'Conta';
    const CREDITO = 'Credito';
    const TRANSFERENCIA = 'Transferencia';
}

/**
 * Formas de pagamento disponíveis para pedido e contas
 */
class ZFormaPagto
{
    private $id;
    private $tipo;
    private $carteira_id;
    private $carteira_pagto_id;
    private $descricao;
    private $parcelado;
    private $min_parcelas;
    private $max_parcelas;
    private $parcelas_sem_juros;
    private $juros;
    private $ativa;

    public function __construct($forma_pagto = [])
    {
        if (is_array($forma_pagto)) {
            $this->setID(isset($forma_pagto['id'])?$forma_pagto['id']:null);
            $this->setTipo(isset($forma_pagto['tipo'])?$forma_pagto['tipo']:null);
            $this->setCarteiraID(isset($forma_pagto['carteiraid'])?$forma_pagto['carteiraid']:null);
            $this->setCarteiraPagtoID(isset($forma_pagto['carteirapagtoid'])?$forma_pagto['carteirapagtoid']:null);
            $this->setDescricao(isset($forma_pagto['descricao'])?$forma_pagto['descricao']:null);
            $this->setParcelado(isset($forma_pagto['parcelado'])?$forma_pagto['parcelado']:null);
            $this->setMinParcelas(isset($forma_pagto['minparcelas'])?$forma_pagto['minparcelas']:null);
            $this->setMaxParcelas(isset($forma_pagto['maxparcelas'])?$forma_pagto['maxparcelas']:null);
            $this->setParcelasSemJuros(isset($forma_pagto['parcelassemjuros'])?$forma_pagto['parcelassemjuros']:null);
            $this->setJuros(isset($forma_pagto['juros'])?$forma_pagto['juros']:null);
            $this->setAtiva(isset($forma_pagto['ativa'])?$forma_pagto['ativa']:null);
        }
    }

    /**
     * Identificador da forma de pagamento
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
     * Tipo de pagamento
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
     * Carteira que será usada para entrada de valores no caixa
     */
    public function getCarteiraID()
    {
        return $this->carteira_id;
    }

    public function setCarteiraID($carteira_id)
    {
        $this->carteira_id = $carteira_id;
    }

    /**
     * Carteira de saída de valores do caixa
     */
    public function getCarteiraPagtoID()
    {
        return $this->carteira_pagto_id;
    }

    public function setCarteiraPagtoID($carteira_pagto_id)
    {
        $this->carteira_pagto_id = $carteira_pagto_id;
    }

    /**
     * Descrição da forma de pagamento
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
     * Informa se a forma de pagamento permite parcelamento
     */
    public function getParcelado()
    {
        return $this->parcelado;
    }

    /**
     * Informa se a forma de pagamento permite parcelamento
     */
    public function isParcelado()
    {
        return $this->parcelado == 'Y';
    }

    public function setParcelado($parcelado)
    {
        $this->parcelado = $parcelado;
    }

    /**
     * Quantidade mínima de parcelas
     */
    public function getMinParcelas()
    {
        return $this->min_parcelas;
    }

    public function setMinParcelas($min_parcelas)
    {
        $this->min_parcelas = $min_parcelas;
    }

    /**
     * Quantidade máxima de parcelas
     */
    public function getMaxParcelas()
    {
        return $this->max_parcelas;
    }

    public function setMaxParcelas($max_parcelas)
    {
        $this->max_parcelas = $max_parcelas;
    }

    /**
     * Quantidade de parcelas em que não será cobrado juros
     */
    public function getParcelasSemJuros()
    {
        return $this->parcelas_sem_juros;
    }

    public function setParcelasSemJuros($parcelas_sem_juros)
    {
        $this->parcelas_sem_juros = $parcelas_sem_juros;
    }

    /**
     * Juros cobrado ao cliente no parcelamento
     */
    public function getJuros()
    {
        return $this->juros;
    }

    public function setJuros($juros)
    {
        $this->juros = $juros;
    }

    /**
     * Informa se a forma de pagamento está ativa
     */
    public function getAtiva()
    {
        return $this->ativa;
    }

    /**
     * Informa se a forma de pagamento está ativa
     */
    public function isAtiva()
    {
        return $this->ativa == 'Y';
    }

    public function setAtiva($ativa)
    {
        $this->ativa = $ativa;
    }

    public function toArray()
    {
        $forma_pagto = [];
        $forma_pagto['id'] = $this->getID();
        $forma_pagto['tipo'] = $this->getTipo();
        $forma_pagto['carteiraid'] = $this->getCarteiraID();
        $forma_pagto['carteirapagtoid'] = $this->getCarteiraPagtoID();
        $forma_pagto['descricao'] = $this->getDescricao();
        $forma_pagto['parcelado'] = $this->getParcelado();
        $forma_pagto['minparcelas'] = $this->getMinParcelas();
        $forma_pagto['maxparcelas'] = $this->getMaxParcelas();
        $forma_pagto['parcelassemjuros'] = $this->getParcelasSemJuros();
        $forma_pagto['juros'] = $this->getJuros();
        $forma_pagto['ativa'] = $this->getAtiva();
        return $forma_pagto;
    }

    public static function getPeloID($id)
    {
        $query = DB::$pdo->from('Formas_Pagto')
                         ->where(['id' => $id]);
        return new ZFormaPagto($query->fetch());
    }

    public static function getPelaDescricao($descricao)
    {
        $query = DB::$pdo->from('Formas_Pagto')
                         ->where(['descricao' => $descricao]);
        return new ZFormaPagto($query->fetch());
    }

    private static function validarCampos(&$forma_pagto)
    {
        $erros = [];
        $forma_pagto['tipo'] = strval($forma_pagto['tipo']);
        if (!in_array($forma_pagto['tipo'], ['Dinheiro', 'Cartao', 'Cheque', 'Conta', 'Credito', 'Transferencia'])) {
            $erros['tipo'] = 'O tipo informado não é válido';
        }
        if (!is_numeric($forma_pagto['carteiraid'])) {
            $erros['carteiraid'] = 'A carteira de entrada não foi informada';
        }
        if (!is_numeric($forma_pagto['carteirapagtoid'])) {
            $erros['carteirapagtoid'] = 'A carteira de saída não foi informada';
        }
        $forma_pagto['descricao'] = strip_tags(trim($forma_pagto['descricao']));
        if (strlen($forma_pagto['descricao']) == 0) {
            $erros['descricao'] = 'A descrição não pode ser vazia';
        }
        if (in_array($forma_pagto['tipo'], ['Cartao', 'Cheque'])) {
            $forma_pagto['parcelado'] = 'Y';
        } else {
            $forma_pagto['parcelado'] = 'N';
        }
        $forma_pagto['minparcelas'] = trim($forma_pagto['minparcelas']);
        if (strlen($forma_pagto['minparcelas']) == 0) {
            $forma_pagto['minparcelas'] = null;
        } elseif (!is_numeric($forma_pagto['minparcelas'])) {
            $erros['minparcelas'] = 'O mínimo de parcelas não foi informado';
        } elseif ($forma_pagto['minparcelas'] < 0) {
            $erros['minparcelas'] = 'O mínimo de parcelas não pode ser negativo';
        }
        $forma_pagto['maxparcelas'] = trim($forma_pagto['maxparcelas']);
        if (strlen($forma_pagto['maxparcelas']) == 0) {
            $forma_pagto['maxparcelas'] = null;
        } elseif (!is_numeric($forma_pagto['maxparcelas'])) {
            $erros['maxparcelas'] = 'O máximo de parcelas não foi informado';
        } elseif ($forma_pagto['maxparcelas'] < 0) {
            $erros['maxparcelas'] = 'O máximo de parcelas não pode ser negativo';
        } elseif ($forma_pagto['maxparcelas'] < $forma_pagto['minparcelas']) {
            $erros['maxparcelas'] = 'O máximo de parcelas não pode ser menor que o mínimo de parcelas';
        }
        $forma_pagto['parcelassemjuros'] = trim($forma_pagto['parcelassemjuros']);
        if (strlen($forma_pagto['parcelassemjuros']) == 0) {
            $forma_pagto['parcelassemjuros'] = null;
        } elseif (!is_numeric($forma_pagto['parcelassemjuros'])) {
            $erros['parcelassemjuros'] = 'As parcelas sem juros não foi informada';
        } elseif ($forma_pagto['parcelassemjuros'] < 0) {
            $erros['parcelassemjuros'] = 'As parcelas sem juros não podem ser negativas';
        } elseif ($forma_pagto['parcelassemjuros'] < $forma_pagto['minparcelas']) {
            $erros['parcelassemjuros'] = 'As parcelas sem juros não pode ser menor que o mínimo de parcelas';
        }
        $forma_pagto['juros'] = trim($forma_pagto['juros']);
        if (strlen($forma_pagto['juros']) == 0) {
            $forma_pagto['juros'] = null;
        } elseif (!is_numeric($forma_pagto['juros'])) {
            $erros['juros'] = 'O juros não foi informado';
        } elseif ($forma_pagto['juros'] < 0) {
            $erros['juros'] = 'O juros não pode ser negativo';
        }
        $forma_pagto['ativa'] = trim($forma_pagto['ativa']);
        if (strlen($forma_pagto['ativa']) == 0) {
            $forma_pagto['ativa'] = 'N';
        } elseif (!in_array($forma_pagto['ativa'], ['Y', 'N'])) {
            $erros['ativa'] = 'A ativa informada não é válida';
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
        if (stripos($e->getMessage(), 'Descricao_UNIQUE') !== false) {
            throw new ValidationException(['descricao' => 'A descrição informada já está cadastrada']);
        }
    }

    public static function cadastrar($forma_pagto)
    {
        $_forma_pagto = $forma_pagto->toArray();
        self::validarCampos($_forma_pagto);
        try {
            $_forma_pagto['id'] = DB::$pdo->insertInto('Formas_Pagto')->values($_forma_pagto)->execute();
        } catch (Exception $e) {
            self::handleException($e);
            throw $e;
        }
        return self::getPeloID($_forma_pagto['id']);
    }

    public static function atualizar($forma_pagto)
    {
        $_forma_pagto = $forma_pagto->toArray();
        if (!$_forma_pagto['id']) {
            throw new ValidationException(['id' => 'O id do formapagto não foi informado']);
        }
        self::validarCampos($_forma_pagto);
        $campos = [
            'tipo',
            'carteiraid',
            'carteirapagtoid',
            'descricao',
            'parcelado',
            'minparcelas',
            'maxparcelas',
            'parcelassemjuros',
            'juros',
            'ativa',
        ];
        try {
            $query = DB::$pdo->update('Formas_Pagto');
            $query = $query->set(array_intersect_key($_forma_pagto, array_flip($campos)));
            $query = $query->where('id', $_forma_pagto['id']);
            $query->execute();
        } catch (Exception $e) {
            self::handleException($e);
            throw $e;
        }
        return self::getPeloID($_forma_pagto['id']);
    }

    public static function excluir($id)
    {
        if (!$id) {
            throw new Exception('Não foi possível excluir o formapagto, o id do formapagto não foi informado');
        }
        $query = DB::$pdo->deleteFrom('Formas_Pagto')
                         ->where(['id' => $id]);
        return $query->execute();
    }

    private static function initSearch($busca, $tipo, $ativa)
    {
        $query = DB::$pdo->from('Formas_Pagto')
                         ->orderBy('ativa ASC, descricao ASC');
        $busca = trim($busca);
        if ($busca != '') {
            $query = $query->where('descricao LIKE ?', '%'.$busca.'%');
        }
        $tipo = trim($tipo);
        if ($tipo != '') {
            $query = $query->where('tipo', $tipo);
        }
        $ativa = trim($ativa);
        if ($ativa != '') {
            $query = $query->where('ativa', $ativa);
        }
        return $query;
    }

    public static function getTodos($busca = null, $tipo = null, $ativa = null, $inicio = null, $quantidade = null)
    {
        $query = self::initSearch($busca, $tipo, $ativa);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_forma_pagtos = $query->fetchAll();
        $forma_pagtos = [];
        foreach ($_forma_pagtos as $forma_pagto) {
            $forma_pagtos[] = new ZFormaPagto($forma_pagto);
        }
        return $forma_pagtos;
    }

    public static function getCount($busca = null, $tipo = null, $ativa = null)
    {
        $query = self::initSearch($busca, $tipo, $ativa);
        return $query->count();
    }

    private static function initSearchDaCarteiraID($carteira_id)
    {
        return   DB::$pdo->from('Formas_Pagto')
                         ->where(['carteiraid' => $carteira_id])
                         ->orderBy('id ASC');
    }

    public static function getTodosDaCarteiraID($carteira_id, $inicio = null, $quantidade = null)
    {
        $query = self::initSearchDaCarteiraID($carteira_id);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_forma_pagtos = $query->fetchAll();
        $forma_pagtos = [];
        foreach ($_forma_pagtos as $forma_pagto) {
            $forma_pagtos[] = new ZFormaPagto($forma_pagto);
        }
        return $forma_pagtos;
    }

    public static function getCountDaCarteiraID($carteira_id)
    {
        $query = self::initSearchDaCarteiraID($carteira_id);
        return $query->count();
    }

    private static function initSearchDoCarteiraPagtoID($carteira_pagto_id)
    {
        return   DB::$pdo->from('Formas_Pagto')
                         ->where(['carteirapagtoid' => $carteira_pagto_id])
                         ->orderBy('id ASC');
    }

    public static function getTodosDoCarteiraPagtoID($carteira_pagto_id, $inicio = null, $quantidade = null)
    {
        $query = self::initSearchDoCarteiraPagtoID($carteira_pagto_id);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_forma_pagtos = $query->fetchAll();
        $forma_pagtos = [];
        foreach ($_forma_pagtos as $forma_pagto) {
            $forma_pagtos[] = new ZFormaPagto($forma_pagto);
        }
        return $forma_pagtos;
    }

    public static function getCountDoCarteiraPagtoID($carteira_pagto_id)
    {
        $query = self::initSearchDoCarteiraPagtoID($carteira_pagto_id);
        return $query->count();
    }
}
