<?php
/**
 * Copyright 2014 da MZ Software - MZ Desenvolvimento de Sistemas LTDA
 *
 * Este arquivo é parte do programa GrandChef - Sistema para Gerenciamento de Churrascarias, Bares e Restaurantes.
 * O GrandChef é um software proprietário; você não pode redistribuí-lo e/ou modificá-lo.
 * DISPOSIÇÕES GERAIS
 * O cliente não deverá remover qualquer identificação do produto, avisos de direitos autorais,
 * ou outros avisos ou restrições de propriedade do GrandChef.
 *
 * O cliente não deverá causar ou permitir a engenharia reversa, desmontagem,
 * ou descompilação do GrandChef.
 *
 * PROPRIEDADE DOS DIREITOS AUTORAIS DO PROGRAMA
 *
 * GrandChef é a especialidade do desenvolvedor e seus
 * licenciadores e é protegido por direitos autorais, segredos comerciais e outros direitos
 * de leis de propriedade.
 *
 * O Cliente adquire apenas o direito de usar o software e não adquire qualquer outros
 * direitos, expressos ou implícitos no GrandChef diferentes dos especificados nesta Licença.
 *
 * @author Equipe GrandChef <desenvolvimento@mzsw.com.br>
 */
namespace MZ\Account;

use MZ\Database\SyncModel;
use MZ\Database\DB;
use MZ\Util\Filter;
use MZ\Util\Validator;

/**
 * Contas a pagar e ou receber
 */
class Conta extends SyncModel
{
    const MOVIMENTACAO_ID = 1;

    /**
     * Código da conta
     */
    private $id;
    /**
     * Classificação da conta
     */
    private $classificacao_id;
    /**
     * Funcionário que lançou a conta
     */
    private $funcionario_id;
    /**
     * Subclassificação da conta
     */
    private $sub_classificacao_id;
    /**
     * Cliente a qual a conta pertence
     */
    private $cliente_id;
    /**
     * Pedido da qual essa conta foi gerada
     */
    private $pedido_id;
    /**
     * Descrição da conta
     */
    private $descricao;
    /**
     * Valor da conta
     */
    private $valor;
    /**
     * Acréscimo de valores ao total
     */
    private $acrescimo;
    /**
     * Multa em valor em caso atraso
     */
    private $multa;
    /**
     * Juros em caso de atraso, valor de 0 a 1, 1 = 100%
     */
    private $juros;
    /**
     * Calcula o acréscimo automaticamente no pagamento quando a conta está
     * atrasada
     */
    private $auto_acrescimo;
    /**
     * Data de vencimento da conta
     */
    private $vencimento;
    /**
     * Data de emissão da conta
     */
    private $data_emissao;
    /**
     * Número do documento que gerou a conta
     */
    private $numero_doc;
    /**
     * Caminho do anexo da conta
     */
    private $anexo_caminho;
    /**
     * Informa se a conta foi cancelada
     */
    private $cancelada;
    /**
     * Data de pagamento que será atribuida ao pagar a conta
     */
    private $data_pagamento;
    /**
     * Data de cadastro da conta
     */
    private $data_cadastro;

    /**
     * Constructor for a new empty instance of Conta
     * @param array $conta All field and values to fill the instance
     */
    public function __construct($conta = [])
    {
        parent::__construct($conta);
    }

    /**
     * Código da conta
     * @return mixed ID of Conta
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param  mixed $id new value for ID
     * @return Conta Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Classificação da conta
     * @return mixed Classificação of Conta
     */
    public function getClassificacaoID()
    {
        return $this->classificacao_id;
    }

    /**
     * Set ClassificacaoID value to new on param
     * @param  mixed $classificacao_id new value for ClassificacaoID
     * @return Conta Self instance
     */
    public function setClassificacaoID($classificacao_id)
    {
        $this->classificacao_id = $classificacao_id;
        return $this;
    }

    /**
     * Funcionário que lançou a conta
     * @return mixed Funcionário of Conta
     */
    public function getFuncionarioID()
    {
        return $this->funcionario_id;
    }

    /**
     * Set FuncionarioID value to new on param
     * @param  mixed $funcionario_id new value for FuncionarioID
     * @return Conta Self instance
     */
    public function setFuncionarioID($funcionario_id)
    {
        $this->funcionario_id = $funcionario_id;
        return $this;
    }

    /**
     * Subclassificação da conta
     * @return mixed Subclassificação of Conta
     */
    public function getSubClassificacaoID()
    {
        return $this->sub_classificacao_id;
    }

    /**
     * Set SubClassificacaoID value to new on param
     * @param  mixed $sub_classificacao_id new value for SubClassificacaoID
     * @return Conta Self instance
     */
    public function setSubClassificacaoID($sub_classificacao_id)
    {
        $this->sub_classificacao_id = $sub_classificacao_id;
        return $this;
    }

    /**
     * Cliente a qual a conta pertence
     * @return mixed Cliente of Conta
     */
    public function getClienteID()
    {
        return $this->cliente_id;
    }

    /**
     * Set ClienteID value to new on param
     * @param  mixed $cliente_id new value for ClienteID
     * @return Conta Self instance
     */
    public function setClienteID($cliente_id)
    {
        $this->cliente_id = $cliente_id;
        return $this;
    }

    /**
     * Pedido da qual essa conta foi gerada
     * @return mixed Pedido of Conta
     */
    public function getPedidoID()
    {
        return $this->pedido_id;
    }

    /**
     * Set PedidoID value to new on param
     * @param  mixed $pedido_id new value for PedidoID
     * @return Conta Self instance
     */
    public function setPedidoID($pedido_id)
    {
        $this->pedido_id = $pedido_id;
        return $this;
    }

    /**
     * Descrição da conta
     * @return mixed Descrição of Conta
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Set Descricao value to new on param
     * @param  mixed $descricao new value for Descricao
     * @return Conta Self instance
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * Valor da conta
     * @return mixed Valor of Conta
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Set Valor value to new on param
     * @param  mixed $valor new value for Valor
     * @return Conta Self instance
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
        return $this;
    }

    /**
     * Acréscimo de valores ao total
     * @return mixed Acréscimo of Conta
     */
    public function getAcrescimo()
    {
        return $this->acrescimo;
    }

    /**
     * Set Acrescimo value to new on param
     * @param  mixed $acrescimo new value for Acrescimo
     * @return Conta Self instance
     */
    public function setAcrescimo($acrescimo)
    {
        $this->acrescimo = $acrescimo;
        return $this;
    }

    /**
     * Multa em valor em caso atraso
     * @return mixed Multa of Conta
     */
    public function getMulta()
    {
        return $this->multa;
    }

    /**
     * Set Multa value to new on param
     * @param  mixed $multa new value for Multa
     * @return Conta Self instance
     */
    public function setMulta($multa)
    {
        $this->multa = $multa;
        return $this;
    }

    /**
     * Juros em caso de atraso, valor de 0 a 1, 1 = 100%
     * @return mixed Juros of Conta
     */
    public function getJuros()
    {
        return $this->juros;
    }

    /**
     * Set Juros value to new on param
     * @param  mixed $juros new value for Juros
     * @return Conta Self instance
     */
    public function setJuros($juros)
    {
        $this->juros = $juros;
        return $this;
    }

    /**
     * Calcula o acréscimo automaticamente no pagamento quando a conta está
     * atrasada
     * @return mixed Acréscimo automático of Conta
     */
    public function getAutoAcrescimo()
    {
        return $this->auto_acrescimo;
    }

    /**
     * Calcula o acréscimo automaticamente no pagamento quando a conta está
     * atrasada
     * @return boolean Check if o of AutoAcrescimo is selected or checked
     */
    public function isAutoAcrescimo()
    {
        return $this->auto_acrescimo == 'Y';
    }

    /**
     * Set AutoAcrescimo value to new on param
     * @param  mixed $auto_acrescimo new value for AutoAcrescimo
     * @return Conta Self instance
     */
    public function setAutoAcrescimo($auto_acrescimo)
    {
        $this->auto_acrescimo = $auto_acrescimo;
        return $this;
    }

    /**
     * Data de vencimento da conta
     * @return mixed Data de vencimento of Conta
     */
    public function getVencimento()
    {
        return $this->vencimento;
    }

    /**
     * Set Vencimento value to new on param
     * @param  mixed $vencimento new value for Vencimento
     * @return Conta Self instance
     */
    public function setVencimento($vencimento)
    {
        $this->vencimento = $vencimento;
        return $this;
    }

    /**
     * Data de emissão da conta
     * @return mixed Data de emissão of Conta
     */
    public function getDataEmissao()
    {
        return $this->data_emissao;
    }

    /**
     * Set DataEmissao value to new on param
     * @param  mixed $data_emissao new value for DataEmissao
     * @return Conta Self instance
     */
    public function setDataEmissao($data_emissao)
    {
        $this->data_emissao = $data_emissao;
        return $this;
    }

    /**
     * Número do documento que gerou a conta
     * @return mixed Número do documento of Conta
     */
    public function getNumeroDoc()
    {
        return $this->numero_doc;
    }

    /**
     * Set NumeroDoc value to new on param
     * @param  mixed $numero_doc new value for NumeroDoc
     * @return Conta Self instance
     */
    public function setNumeroDoc($numero_doc)
    {
        $this->numero_doc = $numero_doc;
        return $this;
    }

    /**
     * Caminho do anexo da conta
     * @return mixed Anexo of Conta
     */
    public function getAnexoCaminho()
    {
        return $this->anexo_caminho;
    }

    /**
     * Set AnexoCaminho value to new on param
     * @param  mixed $anexo_caminho new value for AnexoCaminho
     * @return Conta Self instance
     */
    public function setAnexoCaminho($anexo_caminho)
    {
        $this->anexo_caminho = $anexo_caminho;
        return $this;
    }

    /**
     * Informa se a conta foi cancelada
     * @return mixed Cancelada of Conta
     */
    public function getCancelada()
    {
        return $this->cancelada;
    }

    /**
     * Informa se a conta foi cancelada
     * @return boolean Check if a of Cancelada is selected or checked
     */
    public function isCancelada()
    {
        return $this->cancelada == 'Y';
    }

    /**
     * Set Cancelada value to new on param
     * @param  mixed $cancelada new value for Cancelada
     * @return Conta Self instance
     */
    public function setCancelada($cancelada)
    {
        $this->cancelada = $cancelada;
        return $this;
    }

    /**
     * Data de pagamento que será atribuida ao pagar a conta
     * @return mixed Data de pagamento of Conta
     */
    public function getDataPagamento()
    {
        return $this->data_pagamento;
    }

    /**
     * Set DataPagamento value to new on param
     * @param  mixed $data_pagamento new value for DataPagamento
     * @return Conta Self instance
     */
    public function setDataPagamento($data_pagamento)
    {
        $this->data_pagamento = $data_pagamento;
        return $this;
    }

    /**
     * Data de cadastro da conta
     * @return mixed Data de cadastro of Conta
     */
    public function getDataCadastro()
    {
        return $this->data_cadastro;
    }

    /**
     * Set DataCadastro value to new on param
     * @param  mixed $data_cadastro new value for DataCadastro
     * @return Conta Self instance
     */
    public function setDataCadastro($data_cadastro)
    {
        $this->data_cadastro = $data_cadastro;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param  boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $conta = parent::toArray($recursive);
        $conta['id'] = $this->getID();
        $conta['classificacaoid'] = $this->getClassificacaoID();
        $conta['funcionarioid'] = $this->getFuncionarioID();
        $conta['subclassificacaoid'] = $this->getSubClassificacaoID();
        $conta['clienteid'] = $this->getClienteID();
        $conta['pedidoid'] = $this->getPedidoID();
        $conta['descricao'] = $this->getDescricao();
        $conta['valor'] = $this->getValor();
        $conta['acrescimo'] = $this->getAcrescimo();
        $conta['multa'] = $this->getMulta();
        $conta['juros'] = $this->getJuros();
        $conta['autoacrescimo'] = $this->getAutoAcrescimo();
        $conta['vencimento'] = $this->getVencimento();
        $conta['dataemissao'] = $this->getDataEmissao();
        $conta['numerodoc'] = $this->getNumeroDoc();
        $conta['anexocaminho'] = $this->getAnexoCaminho();
        $conta['cancelada'] = $this->getCancelada();
        $conta['datapagamento'] = $this->getDataPagamento();
        $conta['datacadastro'] = $this->getDataCadastro();
        return $conta;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param  mixed $conta Associated key -> value to assign into this instance
     * @return Conta Self instance
     */
    public function fromArray($conta = [])
    {
        if ($conta instanceof Conta) {
            $conta = $conta->toArray();
        } elseif (!is_array($conta)) {
            $conta = [];
        }
        parent::fromArray($conta);
        if (!isset($conta['id'])) {
            $this->setID(null);
        } else {
            $this->setID($conta['id']);
        }
        if (!isset($conta['classificacaoid'])) {
            $this->setClassificacaoID(null);
        } else {
            $this->setClassificacaoID($conta['classificacaoid']);
        }
        if (!isset($conta['funcionarioid'])) {
            $this->setFuncionarioID(null);
        } else {
            $this->setFuncionarioID($conta['funcionarioid']);
        }
        if (!array_key_exists('subclassificacaoid', $conta)) {
            $this->setSubClassificacaoID(null);
        } else {
            $this->setSubClassificacaoID($conta['subclassificacaoid']);
        }
        if (!array_key_exists('clienteid', $conta)) {
            $this->setClienteID(null);
        } else {
            $this->setClienteID($conta['clienteid']);
        }
        if (!array_key_exists('pedidoid', $conta)) {
            $this->setPedidoID(null);
        } else {
            $this->setPedidoID($conta['pedidoid']);
        }
        if (!isset($conta['descricao'])) {
            $this->setDescricao(null);
        } else {
            $this->setDescricao($conta['descricao']);
        }
        if (!isset($conta['valor'])) {
            $this->setValor(null);
        } else {
            $this->setValor($conta['valor']);
        }
        if (!isset($conta['acrescimo'])) {
            $this->setAcrescimo(null);
        } else {
            $this->setAcrescimo($conta['acrescimo']);
        }
        if (!isset($conta['multa'])) {
            $this->setMulta(null);
        } else {
            $this->setMulta($conta['multa']);
        }
        if (!isset($conta['juros'])) {
            $this->setJuros(null);
        } else {
            $this->setJuros($conta['juros']);
        }
        if (!isset($conta['autoacrescimo'])) {
            $this->setAutoAcrescimo('N');
        } else {
            $this->setAutoAcrescimo($conta['autoacrescimo']);
        }
        if (!array_key_exists('vencimento', $conta)) {
            $this->setVencimento(null);
        } else {
            $this->setVencimento($conta['vencimento']);
        }
        if (!array_key_exists('dataemissao', $conta)) {
            $this->setDataEmissao(null);
        } else {
            $this->setDataEmissao($conta['dataemissao']);
        }
        if (!array_key_exists('numerodoc', $conta)) {
            $this->setNumeroDoc(null);
        } else {
            $this->setNumeroDoc($conta['numerodoc']);
        }
        if (!array_key_exists('anexocaminho', $conta)) {
            $this->setAnexoCaminho(null);
        } else {
            $this->setAnexoCaminho($conta['anexocaminho']);
        }
        if (!isset($conta['cancelada'])) {
            $this->setCancelada('N');
        } else {
            $this->setCancelada($conta['cancelada']);
        }
        if (!array_key_exists('datapagamento', $conta)) {
            $this->setDataPagamento(null);
        } else {
            $this->setDataPagamento($conta['datapagamento']);
        }
        if (!isset($conta['datacadastro'])) {
            $this->setDataCadastro(DB::now());
        } else {
            $this->setDataCadastro($conta['datacadastro']);
        }
        return $this;
    }

    public function getAcrescimoAtual()
    {
        $datapagto = strtotime($this->getDataPagamento());
        if ($datapagto === false) {
            $datapagto = time();
        }
        $vencimento = strtotime("tomorrow", strtotime($this->getVencimento())) - 1;
        $is_vencida = !is_null($this->getVencimento()) && $vencimento < $datapagto;
        if (!$is_vencida) {
            return $this->getAcrescimo();
        }
        if (is_equal($this->getJuros(), 0, 0.000005)) {
            // ainda não incluiu a multa
            if (abs($this->getAcrescimo()) < abs($this->getMulta())) {
                return $this->getAcrescimo() + $this->getMulta();
            }
            return $this->getAcrescimo();
        }
        if (!$this->exists()) {
            $info = [
                'quantidade' => 0,
                'despesas' => 0,
                'receitas' => 0,
                'pago' => 0,
                'recebido' => 0,
                'datapagto' => null,
            ];
        } else {
            $info = self::getTotalAbertas($this->getID());
        }
        $is_paga = is_equal($this->getValor() + $this->getAcrescimo(), $info['pago']);
        if ($is_paga) {
            return $this->getAcrescimo();
        }
        $restante = $this->getValor() + $this->getAcrescimo() - $info['pago'];
        $datavenc = strtotime($info['datapagto']);
        if ($datavenc === false) {
            $datavenc = strtotime($this->getVencimento());
        }
        $dias = floor(($datapagto - $datavenc) / (60 * 60 * 24));
        $juros = $restante * pow(1 + $this->getJuros(), $dias) - $restante;
        if (abs($this->getAcrescimo()) < abs($this->getMulta())) { // ainda não incluiu a multa
            return $this->getAcrescimo() + $this->getMulta() + $juros;
        }
        return $this->getAcrescimo() + $juros;
    }

    public function getTotal()
    {
        if (!$this->isAutoAcrescimo()) {
            return $this->getValor() + $this->getAcrescimo();
        }
        return $this->getValor() + $this->getAcrescimoAtual();
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $conta = parent::publish();
        return $conta;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param Conta $original Original instance without modifications
     */
    public function filter($original, $despesa = false)
    {
        // não deixa alterar esses dados
        $this->setID($original->getID());
        $this->setFuncionarioID($original->getFuncionarioID());
        $this->setPedidoID($original->getPedidoID());
        $this->setCancelada($original->getCancelada());
        $this->setClassificacaoID(Filter::number($this->getClassificacaoID()));
        $this->setSubClassificacaoID(Filter::number($this->getSubClassificacaoID()));
        $this->setClienteID(Filter::number($this->getClienteID()));
        $this->setDescricao(Filter::string($this->getDescricao()));
        $this->setValor(abs(Filter::money($this->getValor(), $localized)));
        $this->setAcrescimo(abs(Filter::money($this->getAcrescimo(), $localized)));
        $this->setMulta(abs(Filter::money($this->getMulta(), $localized)));
        if ($despesa) {
            $this->setValor(-$this->getValor());
            $this->setAcrescimo(-$this->getAcrescimo());
            $this->setMulta(-$this->getMulta());
        }
        $this->setJuros(Filter::float($this->getJuros(), $localized) / 100.0);
        $this->setVencimento(Filter::date($this->getVencimento()));
        $this->setDataEmissao(Filter::date($this->getDataEmissao()));
        $this->setNumeroDoc(Filter::string($this->getNumeroDoc()));
        $this->setAnexoCaminho(Filter::string($this->getAnexoCaminho()));
        $this->setDataPagamento(Filter::date($this->getDataPagamento()));
        $anexocaminho = upload_document('raw_anexocaminho', 'conta');
        if (is_null($anexocaminho) && trim($this->getAnexoCaminho()) != '') {
            $this->setAnexoCaminho($original->getAnexoCaminho());
        } else {
            $this->setAnexoCaminho($anexocaminho);
        }
    }

    /**
     * Clean instance resources like images and docs
     * @param  Conta $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {

        // exclui o documento antigo
        if (!is_null($this->getAnexoCaminho()) &&
            $dependency->getAnexoCaminho() != $this->getAnexoCaminho() &&
            !is_local_path($this->getAnexoCaminho())
        ) {
            @unlink(app()->getPath('public') . get_document_url($this->getAnexoCaminho(), 'conta'));
        }
        $this->setAnexoCaminho($dependency->getAnexoCaminho());
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Conta in array format
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getClassificacaoID())) {
            $errors['classificacaoid'] = 'A classificação não pode ser vazia';
        }
        if (is_null($this->getFuncionarioID())) {
            $errors['funcionarioid'] = 'O funcionário não pode ser vazio';
        }
        if (is_null($this->getDescricao())) {
            $errors['descricao'] = 'A descrição não pode ser vazia';
        }
        if (is_null($this->getValor())) {
            $errors['valor'] = 'O valor não pode ser vazio';
        } elseif (is_equal($this->getValor(), 0)) {
            $errors['valor'] = 'O valor não pode ser nulo';
        }
        if (is_null($this->getAcrescimo())) {
            $errors['acrescimo'] = 'O acréscimo não pode ser vazio';
        } elseif ($this->getValor() > 0 && $this->getAcrescimo() < 0) {
            $errors['acrescimo'] = 'O acréscimo não pode ser negativo';
        } elseif ($this->getValor() <= 0 && $this->getAcrescimo() > 0) {
            $errors['acrescimo'] = 'O acréscimo não pode ser positivo';
        }
        if (is_null($this->getMulta())) {
            $errors['multa'] = 'A multa não pode ser vazia';
        } elseif ($this->getValor() > 0 && $this->getMulta() < 0) {
            $errors['multa'] = 'A multa não pode ser negativa';
        } elseif ($this->getValor() <= 0 && $this->getMulta() > 0) {
            $errors['multa'] = 'A multa não pode ser positiva';
        }
        if (is_null($this->getJuros())) {
            $errors['juros'] = 'O juros não pode ser vazio';
        } elseif ($this->getJuros() < 0) {
            $errors['juros'] = 'O juros não pode ser negativo';
        }
        if (!Validator::checkBoolean($this->getAutoAcrescimo())) {
            $errors['autoacrescimo'] = 'O acréscimo automático não foi informado ou é inválido';
        }
        if (!Validator::checkBoolean($this->getCancelada())) {
            $errors['cancelada'] = 'A informação de cancelamento não foi informada ou é inválida';
        }
        $receitas = 0;
        if ($this->exists()) {
            $info = self::getTotalAbertas($this->getID());
            if (is_equal($info['receitas'], 0) && is_equal($info['despesas'], 0)) {
                $errors['id'] = 'A conta informada já foi consolidada e não pode ser alterada';
            }
            if ($this->getValor() > 0 && is_greater($info['recebido'], $this->getValor())) {
                $errors['valor'] = 'O total recebido é maior que o valor da conta';
            }
            if ($this->getValor() <= 0 && is_greater(-$info['pago'], -$this->getValor())) {
                $errors['valor'] = 'O total pago é maior que o valor da conta';
            }
            $_conta = self::findByID($this->getID());
            $receitas = $_conta->getTotal();
        }
        if (!is_null($this->getClienteID()) && $this->getValor() > 0) {
            $cliente = $this->findClienteID();
            if ($cliente->getLimiteCompra() > 0) {
                $info_total = self::getTotalAbertas(null, $this->getClienteID());
                $utilizado = ($info_total['receitas'] - $info_total['recebido']) +
                    ($info_total['despesas'] - $info_total['pago']) - $receitas;
                if ($this->getValor() + $utilizado > $cliente->getLimiteCompra()) {
                    $restante = ($this->getValor() + $utilizado) - $cliente->getLimiteCompra();
                    $msg = 'O cliente "%s" não possui limite de crédito '.
                        'suficiente para concluir a operação, necessário %s, limite '.
                        'utilizado %s de %s';
                    $errors['valor'] = sprintf(
                        $msg,
                        $cliente->getNomeCompleto(),
                        \MZ\Util\Mask::money($restante, true),
                        \MZ\Util\Mask::money($utilizado, true),
                        \MZ\Util\Mask::money($cliente->getLimiteCompra(), true)
                    );
                }
            }
        }
        if (is_null($this->getVencimento())) {
            $errors['vencimento'] = 'A data de vencimento não foi informada corretamente';
        }
        $this->setDataCadastro(DB::now());
        if (!empty($errors)) {
            throw new \MZ\Exception\ValidationException($errors);
        }
        return $this->toArray();
    }

    /**
     * Translate SQL exception into application exception
     * @param  \Exception $e exception to translate into a readable error
     * @return \MZ\Exception\ValidationException new exception translated
     */
    protected function translate($e)
    {
        return parent::translate($e);
    }

    /**
     * Insert a new Conta into the database and fill instance from database
     * @return Conta Self instance
     */
    public function insert()
    {
        $this->setID(null);
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = DB::insertInto('Contas')->values($values)->execute();
            $this->setID($id);
            $this->loadByID();
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Conta with instance values into database for ID
     * @param  array $only Save these fields only, when empty save all fields except id
     * @return Conta Self instance
     */
    public function update($only = [])
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador da conta não foi informado');
        }
        if ($this->getID() == self::MOVIMENTACAO_ID) {
            throw new \Exception('A conta informada é utilizada internamente pelo sistema e não pode ser alterada');
        }
        $values = DB::filterValues($values, $only, false);
        unset($values['datacadastro']);
        try {
            DB::update('Contas')
                ->set($values)
                ->where('id', $this->getID())
                ->execute();
            $this->loadByID();
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Delete this instance from database using ID
     * @return integer Number of rows deleted (Max 1)
     */
    public function delete()
    {
        if (!$this->exists()) {
            throw new \Exception('O identificador da conta não foi informado');
        }
        if ($this->getID() == self::MOVIMENTACAO_ID) {
            throw new \Exception('A conta informada é utilizada internamente pelo sistema e não pode ser excluída');
        }
        $result = DB::deleteFrom('Contas')
            ->where('id', $this->getID())
            ->execute();
        return $result;
    }

    /**
     * Load one register for it self with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order associative field name -> [-1, 1]
     * @return Conta Self instance filled or empty
     */
    public function load($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return $this->fromArray($row);
    }

    /**
     * Classificação da conta
     * @return \MZ\Account\Classificacao The object fetched from database
     */
    public function findClassificacaoID()
    {
        return \MZ\Account\Classificacao::findByID($this->getClassificacaoID());
    }

    /**
     * Funcionário que lançou a conta
     * @return \MZ\Provider\Prestador The object fetched from database
     */
    public function findFuncionarioID()
    {
        return \MZ\Provider\Prestador::findByID($this->getFuncionarioID());
    }

    /**
     * Subclassificação da conta
     * @return \MZ\Account\Classificacao The object fetched from database
     */
    public function findSubClassificacaoID()
    {
        if (is_null($this->getSubClassificacaoID())) {
            return new \MZ\Account\Classificacao();
        }
        return \MZ\Account\Classificacao::findByID($this->getSubClassificacaoID());
    }

    /**
     * Cliente a qual a conta pertence
     * @return \MZ\Account\Cliente The object fetched from database
     */
    public function findClienteID()
    {
        if (is_null($this->getClienteID())) {
            return new \MZ\Account\Cliente();
        }
        return \MZ\Account\Cliente::findByID($this->getClienteID());
    }

    /**
     * Pedido da qual essa conta foi gerada
     * @return \MZ\Sale\Pedido The object fetched from database
     */
    public function findPedidoID()
    {
        if (is_null($this->getPedidoID())) {
            return new \MZ\Sale\Pedido();
        }
        return \MZ\Sale\Pedido::findByID($this->getPedidoID());
    }

    /**
     * Get allowed keys array
     * @return array allowed keys array
     */
    private static function getAllowedKeys()
    {
        $conta = new Conta();
        $allowed = Filter::concatKeys('c.', $conta->toArray());
        return $allowed;
    }

    /**
     * Filter order array
     * @param  mixed $order order string or array to parse and filter allowed
     * @return array allowed associative order
     */
    private static function filterOrder($order)
    {
        $allowed = self::getAllowedKeys();
        return Filter::orderBy($order, $allowed, 'c.');
    }

    /**
     * Filter condition array with allowed fields
     * @param  array $condition condition to filter rows
     * @return array allowed condition
     */
    private static function filterCondition($condition)
    {
        $allowed = self::getAllowedKeys();
        if (isset($condition['search'])) {
            $search = trim($condition['search']);
            if (substr($search, 0, 1) == '#') {
                $condition['numerodoc'] = substr($search, 1);
            } elseif ($search != '') {
                $field = 'c.descricao LIKE ?';
                $condition[$field] = '%'.$search.'%';
                $allowed[$field] = true;
            }
            unset($condition['search']);
        }
        if (isset($condition['classificacao'])) {
            $classificacao = intval($condition['classificacao']);
            $field = '(c.classificacaoid = ? OR c.subclassificacaoid = ?)';
            $condition[$field] = [$classificacao, $classificacao];
            $allowed[$field] = true;
            unset($condition['classificacao']);
        }
        return Filter::keys($condition, $allowed, 'c.');
    }

    /**
     * Fetch data from database with a condition
     * @param  array $condition condition to filter rows
     * @param  array $order order rows
     * @return SelectQuery query object with condition statement
     */
    private static function query($condition = [], $order = [])
    {
        $query = DB::from('Contas c');
        $condition = self::filterCondition($condition);
        $query = DB::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('c.id DESC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order order rows
     * @return Conta A filled Conta or empty instance
     */
    public static function find($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return new Conta($row);
    }

    public static function getTotalAbertas(
        $descricao = null,
        $cliente_id = null,
        $tipo = 0,
        $mes_inicio = null,
        $mes_fim = null
    ) {
        $data_inicio = null;
        if (!is_null($mes_inicio) && !is_numeric($mes_inicio)) {
            $data_inicio = strtotime($mes_inicio);
        } elseif (!is_null($mes_inicio)) {
            $data_inicio = strtotime(date('Y-m').' '.$mes_inicio.' month');
        }
        $data_fim = null;
        if (!is_null($mes_fim) && !is_numeric($mes_fim)) {
            $data_fim = strtotime($mes_fim);
        } elseif (!is_null($mes_fim)) {
            $data_fim = strtotime(date('Y-m').' '.$mes_fim.' month');
            $data_fim = strtotime('last day of this month', $data_fim);
        }
        $db = DB::getPdo();
        $sql = '';
        $data = [];
        $descricao = trim($descricao);
        if (is_numeric($descricao)) {
            $sql .= 'AND c.id = :codigo ';
            $data[':codigo'] = intval($descricao);
        } elseif ($descricao != '') {
            $sql .= 'AND c.descricao LIKE :descricao ';
            $data[':descricao'] = '%'.$descricao.'%';
        }
        if (!is_null($cliente_id)) {
            $sql .= 'AND c.clienteid = :cliente ';
            $data[':cliente'] = $cliente_id;
        }
        if (is_numeric($tipo) && $tipo != 0) {
            if ($tipo < 0) {
                $sql .= 'AND c.valor <= 0 ';
            } else {
                $sql .= 'AND c.valor > 0 ';
            }
        }
        if (!is_null($data_inicio)) {
            $sql .= 'AND c.vencimento >= :inicio ';
            $data[':inicio'] = date('Y-m-d', $data_inicio);
        }
        if (!is_null($data_fim)) {
            $sql .= 'AND c.vencimento <= :fim ';
            $data[':fim'] = date('Y-m-d 23:59:59', $data_fim);
        }
        $stmt = $db->prepare('SELECT COUNT(id) as quantidade, SUM(IF(valor <= 0, valor + acrescimo, 0)) as despesas, '.
                             '  SUM(IF(valor > 0, valor + acrescimo, 0)) as receitas, SUM(pago) as pago, '.
                             '  SUM(recebido) as recebido, MAX(datapagto) as datapagto '.
                             'FROM ('.
                                'SELECT c.id, c.valor, c.acrescimo, MAX(pg.datahora) as datapagto, '.
                                '  COALESCE(IF(c.valor <= 0, SUM(pg.total), 0), 0) as pago, '.
                                '  COALESCE(IF(c.valor > 0, SUM(pg.total), 0), 0) as recebido '.
                                'FROM Contas c '.
                                'LEFT JOIN Pagamentos pg ON pg.pagtocontaid = c.id AND pg.cancelado = "N" AND pg.ativo = "Y" '.
                                'WHERE c.id <> 1 AND c.cancelada = "N" '.$sql.
                                'GROUP BY c.id '.
                                'HAVING (ABS(valor + acrescimo) - ABS(pago + recebido)) >= 0.005) a');
        $stmt->execute($data);
        $info = $stmt->fetch();
        return [
            'quantidade' => $info['quantidade'] + 0,
            'despesas' => $info['despesas'] + 0,
            'receitas' => $info['receitas'] + 0,
            'pago' => $info['pago'] + 0,
            'recebido' => $info['recebido'] + 0,
            'datapagto' => $info['datapagto'],
        ];
    }

    /**
     * Find all Conta
     * @param  array  $condition Condition to get all Conta
     * @param  array  $order     Order Conta
     * @param  int    $limit     Limit data into row count
     * @param  int    $offset    Start offset to get rows
     * @return array             List of all rows instanced as Conta
     */
    public static function findAll($condition = [], $order = [], $limit = null, $offset = null)
    {
        $query = self::query($condition, $order);
        if (!is_null($limit)) {
            $query = $query->limit($limit);
        }
        if (!is_null($offset)) {
            $query = $query->offset($offset);
        }
        $rows = $query->fetchAll();
        $result = [];
        foreach ($rows as $row) {
            $result[] = new Conta($row);
        }
        return $result;
    }

    /**
     * Count all rows from database with matched condition critery
     * @param  array $condition condition to filter rows
     * @return integer Quantity of rows
     */
    public static function count($condition = [])
    {
        $query = self::query($condition);
        return $query->count();
    }
}
