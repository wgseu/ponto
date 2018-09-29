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

use MZ\Util\Mask;
use MZ\Util\Filter;
use MZ\Util\Validator;
use MZ\Database\DB;
use MZ\Database\SyncModel;
use MZ\Exception\ValidationException;

/**
 * Contas a pagar e ou receber
 */
class Conta extends SyncModel
{

    /**
     * Tipo de conta se receita ou despesa
     */
    const TIPO_RECEITA = 'Receita';
    const TIPO_DESPESA = 'Despesa';

    /**
     * Fonte dos valores, comissão e remuneração se pagar antes do vencimento,
     * o valor será proporcional
     */
    const FONTE_FIXA = 'Fixa';
    const FONTE_VARIAVEL = 'Variavel';
    const FONTE_COMISSAO = 'Comissao';
    const FONTE_REMUNERACAO = 'Remuneracao';

    /**
     * Modo de cobrança se diário ou mensal, a quantidade é definida em
     * frequencia
     */
    const MODO_DIARIO = 'Diario';
    const MODO_MENSAL = 'Mensal';

    /**
     * Fórmula de juros que será cobrado em caso de atraso
     */
    const FORMULA_SIMPLES = 'Simples';
    const FORMULA_COMPOSTO = 'Composto';

    /**
     * Informa o estado da conta, desativa quando agrupa
     */
    const ESTADO_ANALISE = 'Analise';
    const ESTADO_ATIVA = 'Ativa';
    const ESTADO_PAGA = 'Paga';
    const ESTADO_CANCELADA = 'Cancelada';
    const ESTADO_DESATIVADA = 'Desativada';

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
     * Informa a conta principal
     */
    private $conta_id;
    /**
     * Informa se esta conta foi agrupada e não precisa ser mais paga
     * individualmente, uma conta agrupada é tratada internamente como
     * desativada
     */
    private $agrupamento_id;
    /**
     * Informa a carteira que essa conta será paga automaticamente ou para
     * informar as contas a pagar dessa carteira
     */
    private $carteira_id;
    /**
     * Cliente a qual a conta pertence
     */
    private $cliente_id;
    /**
     * Pedido da qual essa conta foi gerada
     */
    private $pedido_id;
    /**
     * Tipo de conta se receita ou despesa
     */
    private $tipo;
    /**
     * Descrição da conta
     */
    private $descricao;
    /**
     * Valor da conta
     */
    private $valor;
    /**
     * Fonte dos valores, comissão e remuneração se pagar antes do vencimento,
     * o valor será proporcional
     */
    private $fonte;
    /**
     * Informa qual o número da parcela para esta conta
     */
    private $numero_parcela;
    /**
     * Quantidade de parcelas que essa conta terá, zero para conta recorrente e
     * será alterado para 1 quando criar a próxima conta
     */
    private $parcelas;
    /**
     * Frequência da recorrência em dias ou mês, depende do modo de cobrança
     */
    private $frequencia;
    /**
     * Modo de cobrança se diário ou mensal, a quantidade é definida em
     * frequencia
     */
    private $modo;
    /**
     * Informa se o pagamento será automático após o vencimento, só ocorrerá se
     * tiver saldo na carteira, usado para débito automático
     */
    private $automatico;
    /**
     * Acréscimo de valores ao total
     */
    private $acrescimo;
    /**
     * Valor da multa em caso de atraso
     */
    private $multa;
    /**
     * Juros diário em caso de atraso, valor de 0 a 1, 1 = 100%
     */
    private $juros;
    /**
     * Fórmula de juros que será cobrado em caso de atraso
     */
    private $formula;
    /**
     * Data de vencimento da conta
     */
    private $vencimento;
    /**
     * Número do documento que gerou a conta
     */
    private $numero_doc;
    /**
     * Caminho do anexo da conta
     */
    private $anexo_url;
    /**
     * Informa o estado da conta, desativa quando agrupa
     */
    private $estado;
    /**
     * Data do último cálculo de acréscimo por atraso de pagamento
     */
    private $data_calculo;
    /**
     * Data de emissão da conta
     */
    private $data_emissao;

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
     * @return int id of Conta
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param int $id Set id for Conta
     * @return self Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Classificação da conta
     * @return int classificação of Conta
     */
    public function getClassificacaoID()
    {
        return $this->classificacao_id;
    }

    /**
     * Set ClassificacaoID value to new on param
     * @param int $classificacao_id Set classificação for Conta
     * @return self Self instance
     */
    public function setClassificacaoID($classificacao_id)
    {
        $this->classificacao_id = $classificacao_id;
        return $this;
    }

    /**
     * Funcionário que lançou a conta
     * @return int funcionário of Conta
     */
    public function getFuncionarioID()
    {
        return $this->funcionario_id;
    }

    /**
     * Set FuncionarioID value to new on param
     * @param int $funcionario_id Set funcionário for Conta
     * @return self Self instance
     */
    public function setFuncionarioID($funcionario_id)
    {
        $this->funcionario_id = $funcionario_id;
        return $this;
    }

    /**
     * Informa a conta principal
     * @return int conta principal of Conta
     */
    public function getContaID()
    {
        return $this->conta_id;
    }

    /**
     * Set ContaID value to new on param
     * @param int $conta_id Set conta principal for Conta
     * @return self Self instance
     */
    public function setContaID($conta_id)
    {
        $this->conta_id = $conta_id;
        return $this;
    }

    /**
     * Informa se esta conta foi agrupada e não precisa ser mais paga
     * individualmente, uma conta agrupada é tratada internamente como
     * desativada
     * @return int agrupamento of Conta
     */
    public function getAgrupamentoID()
    {
        return $this->agrupamento_id;
    }

    /**
     * Set AgrupamentoID value to new on param
     * @param int $agrupamento_id Set agrupamento for Conta
     * @return self Self instance
     */
    public function setAgrupamentoID($agrupamento_id)
    {
        $this->agrupamento_id = $agrupamento_id;
        return $this;
    }

    /**
     * Informa a carteira que essa conta será paga automaticamente ou para
     * informar as contas a pagar dessa carteira
     * @return int carteira of Conta
     */
    public function getCarteiraID()
    {
        return $this->carteira_id;
    }

    /**
     * Set CarteiraID value to new on param
     * @param int $carteira_id Set carteira for Conta
     * @return self Self instance
     */
    public function setCarteiraID($carteira_id)
    {
        $this->carteira_id = $carteira_id;
        return $this;
    }

    /**
     * Cliente a qual a conta pertence
     * @return int cliente of Conta
     */
    public function getClienteID()
    {
        return $this->cliente_id;
    }

    /**
     * Set ClienteID value to new on param
     * @param int $cliente_id Set cliente for Conta
     * @return self Self instance
     */
    public function setClienteID($cliente_id)
    {
        $this->cliente_id = $cliente_id;
        return $this;
    }

    /**
     * Pedido da qual essa conta foi gerada
     * @return int pedido of Conta
     */
    public function getPedidoID()
    {
        return $this->pedido_id;
    }

    /**
     * Set PedidoID value to new on param
     * @param int $pedido_id Set pedido for Conta
     * @return self Self instance
     */
    public function setPedidoID($pedido_id)
    {
        $this->pedido_id = $pedido_id;
        return $this;
    }

    /**
     * Tipo de conta se receita ou despesa
     * @return string tipo of Conta
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set Tipo value to new on param
     * @param string $tipo Set tipo for Conta
     * @return self Self instance
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
        return $this;
    }

    /**
     * Descrição da conta
     * @return string descrição of Conta
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Set Descricao value to new on param
     * @param string $descricao Set descrição for Conta
     * @return self Self instance
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * Valor da conta
     * @return string valor of Conta
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Set Valor value to new on param
     * @param string $valor Set valor for Conta
     * @return self Self instance
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
        return $this;
    }

    /**
     * Fonte dos valores, comissão e remuneração se pagar antes do vencimento,
     * o valor será proporcional
     * @return string fonte dos valores of Conta
     */
    public function getFonte()
    {
        return $this->fonte;
    }

    /**
     * Set Fonte value to new on param
     * @param string $fonte Set fonte dos valores for Conta
     * @return self Self instance
     */
    public function setFonte($fonte)
    {
        $this->fonte = $fonte;
        return $this;
    }

    /**
     * Informa qual o número da parcela para esta conta
     * @return int número da parcela of Conta
     */
    public function getNumeroParcela()
    {
        return $this->numero_parcela;
    }

    /**
     * Set NumeroParcela value to new on param
     * @param int $numero_parcela Set número da parcela for Conta
     * @return self Self instance
     */
    public function setNumeroParcela($numero_parcela)
    {
        $this->numero_parcela = $numero_parcela;
        return $this;
    }

    /**
     * Quantidade de parcelas que essa conta terá, zero para conta recorrente e
     * será alterado para 1 quando criar a próxima conta
     * @return int parcelas of Conta
     */
    public function getParcelas()
    {
        return $this->parcelas;
    }

    /**
     * Set Parcelas value to new on param
     * @param int $parcelas Set parcelas for Conta
     * @return self Self instance
     */
    public function setParcelas($parcelas)
    {
        $this->parcelas = $parcelas;
        return $this;
    }

    /**
     * Frequência da recorrência em dias ou mês, depende do modo de cobrança
     * @return int frequencia of Conta
     */
    public function getFrequencia()
    {
        return $this->frequencia;
    }

    /**
     * Set Frequencia value to new on param
     * @param int $frequencia Set frequencia for Conta
     * @return self Self instance
     */
    public function setFrequencia($frequencia)
    {
        $this->frequencia = $frequencia;
        return $this;
    }

    /**
     * Modo de cobrança se diário ou mensal, a quantidade é definida em
     * frequencia
     * @return string modo of Conta
     */
    public function getModo()
    {
        return $this->modo;
    }

    /**
     * Set Modo value to new on param
     * @param string $modo Set modo for Conta
     * @return self Self instance
     */
    public function setModo($modo)
    {
        $this->modo = $modo;
        return $this;
    }

    /**
     * Informa se o pagamento será automático após o vencimento, só ocorrerá se
     * tiver saldo na carteira, usado para débito automático
     * @return string automático of Conta
     */
    public function getAutomatico()
    {
        return $this->automatico;
    }

    /**
     * Informa se o pagamento será automático após o vencimento, só ocorrerá se
     * tiver saldo na carteira, usado para débito automático
     * @return boolean Check if o of Automatico is selected or checked
     */
    public function isAutomatico()
    {
        return $this->automatico == 'Y';
    }

    /**
     * Set Automatico value to new on param
     * @param string $automatico Set automático for Conta
     * @return self Self instance
     */
    public function setAutomatico($automatico)
    {
        $this->automatico = $automatico;
        return $this;
    }

    /**
     * Acréscimo de valores ao total
     * @return string acréscimo of Conta
     */
    public function getAcrescimo()
    {
        return $this->acrescimo;
    }

    /**
     * Set Acrescimo value to new on param
     * @param string $acrescimo Set acréscimo for Conta
     * @return self Self instance
     */
    public function setAcrescimo($acrescimo)
    {
        $this->acrescimo = $acrescimo;
        return $this;
    }

    /**
     * Valor da multa em caso de atraso
     * @return string multa por atraso of Conta
     */
    public function getMulta()
    {
        return $this->multa;
    }

    /**
     * Set Multa value to new on param
     * @param string $multa Set multa por atraso for Conta
     * @return self Self instance
     */
    public function setMulta($multa)
    {
        $this->multa = $multa;
        return $this;
    }

    /**
     * Juros diário em caso de atraso, valor de 0 a 1, 1 = 100%
     * @return float juros of Conta
     */
    public function getJuros()
    {
        return $this->juros;
    }

    /**
     * Set Juros value to new on param
     * @param float $juros Set juros for Conta
     * @return self Self instance
     */
    public function setJuros($juros)
    {
        $this->juros = $juros;
        return $this;
    }

    /**
     * Fórmula de juros que será cobrado em caso de atraso
     * @return string tipo de juros of Conta
     */
    public function getFormula()
    {
        return $this->formula;
    }

    /**
     * Set Formula value to new on param
     * @param string $formula Set tipo de juros for Conta
     * @return self Self instance
     */
    public function setFormula($formula)
    {
        $this->formula = $formula;
        return $this;
    }

    /**
     * Data de vencimento da conta
     * @return string data de vencimento of Conta
     */
    public function getVencimento()
    {
        return $this->vencimento;
    }

    /**
     * Set Vencimento value to new on param
     * @param string $vencimento Set data de vencimento for Conta
     * @return self Self instance
     */
    public function setVencimento($vencimento)
    {
        $this->vencimento = $vencimento;
        return $this;
    }

    /**
     * Número do documento que gerou a conta
     * @return string número do documento of Conta
     */
    public function getNumeroDoc()
    {
        return $this->numero_doc;
    }

    /**
     * Set NumeroDoc value to new on param
     * @param string $numero_doc Set número do documento for Conta
     * @return self Self instance
     */
    public function setNumeroDoc($numero_doc)
    {
        $this->numero_doc = $numero_doc;
        return $this;
    }

    /**
     * Caminho do anexo da conta
     * @return string anexo of Conta
     */
    public function getAnexoURL()
    {
        return $this->anexo_url;
    }

    /**
     * Set AnexoURL value to new on param
     * @param string $anexo_url Set anexo for Conta
     * @return self Self instance
     */
    public function setAnexoURL($anexo_url)
    {
        $this->anexo_url = $anexo_url;
        return $this;
    }

    /**
     * Informa o estado da conta, desativa quando agrupa
     * @return string estado of Conta
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set Estado value to new on param
     * @param string $estado Set estado for Conta
     * @return self Self instance
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
        return $this;
    }

    /**
     * Data do último cálculo de acréscimo por atraso de pagamento
     * @return string data de cálculo of Conta
     */
    public function getDataCalculo()
    {
        return $this->data_calculo;
    }

    /**
     * Set DataCalculo value to new on param
     * @param string $data_calculo Set data de cálculo for Conta
     * @return self Self instance
     */
    public function setDataCalculo($data_calculo)
    {
        $this->data_calculo = $data_calculo;
        return $this;
    }

    /**
     * Data de emissão da conta
     * @return string data de emissão of Conta
     */
    public function getDataEmissao()
    {
        return $this->data_emissao;
    }

    /**
     * Set DataEmissao value to new on param
     * @param string $data_emissao Set data de emissão for Conta
     * @return self Self instance
     */
    public function setDataEmissao($data_emissao)
    {
        $this->data_emissao = $data_emissao;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $conta = parent::toArray($recursive);
        $conta['id'] = $this->getID();
        $conta['classificacaoid'] = $this->getClassificacaoID();
        $conta['funcionarioid'] = $this->getFuncionarioID();
        $conta['contaid'] = $this->getContaID();
        $conta['agrupamentoid'] = $this->getAgrupamentoID();
        $conta['carteiraid'] = $this->getCarteiraID();
        $conta['clienteid'] = $this->getClienteID();
        $conta['pedidoid'] = $this->getPedidoID();
        $conta['tipo'] = $this->getTipo();
        $conta['descricao'] = $this->getDescricao();
        $conta['valor'] = $this->getValor();
        $conta['fonte'] = $this->getFonte();
        $conta['numeroparcela'] = $this->getNumeroParcela();
        $conta['parcelas'] = $this->getParcelas();
        $conta['frequencia'] = $this->getFrequencia();
        $conta['modo'] = $this->getModo();
        $conta['automatico'] = $this->getAutomatico();
        $conta['acrescimo'] = $this->getAcrescimo();
        $conta['multa'] = $this->getMulta();
        $conta['juros'] = $this->getJuros();
        $conta['formula'] = $this->getFormula();
        $conta['vencimento'] = $this->getVencimento();
        $conta['numerodoc'] = $this->getNumeroDoc();
        $conta['anexourl'] = $this->getAnexoURL();
        $conta['estado'] = $this->getEstado();
        $conta['datacalculo'] = $this->getDataCalculo();
        $conta['dataemissao'] = $this->getDataEmissao();
        return $conta;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param mixed $conta Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($conta = [])
    {
        if ($conta instanceof self) {
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
        if (!array_key_exists('contaid', $conta)) {
            $this->setContaID(null);
        } else {
            $this->setContaID($conta['contaid']);
        }
        if (!array_key_exists('agrupamentoid', $conta)) {
            $this->setAgrupamentoID(null);
        } else {
            $this->setAgrupamentoID($conta['agrupamentoid']);
        }
        if (!array_key_exists('carteiraid', $conta)) {
            $this->setCarteiraID(null);
        } else {
            $this->setCarteiraID($conta['carteiraid']);
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
        if (!isset($conta['tipo'])) {
            $this->setTipo(null);
        } else {
            $this->setTipo($conta['tipo']);
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
        if (!isset($conta['fonte'])) {
            $this->setFonte(self::FONTE_FIXA);
        } else {
            $this->setFonte($conta['fonte']);
        }
        if (!isset($conta['numeroparcela'])) {
            $this->setNumeroParcela(1);
        } else {
            $this->setNumeroParcela($conta['numeroparcela']);
        }
        if (!isset($conta['parcelas'])) {
            $this->setParcelas(1);
        } else {
            $this->setParcelas($conta['parcelas']);
        }
        if (!isset($conta['frequencia'])) {
            $this->setFrequencia(1);
        } else {
            $this->setFrequencia($conta['frequencia']);
        }
        if (!isset($conta['modo'])) {
            $this->setModo(self::MODO_MENSAL);
        } else {
            $this->setModo($conta['modo']);
        }
        if (!isset($conta['automatico'])) {
            $this->setAutomatico('N');
        } else {
            $this->setAutomatico($conta['automatico']);
        }
        if (!isset($conta['acrescimo'])) {
            $this->setAcrescimo(0);
        } else {
            $this->setAcrescimo($conta['acrescimo']);
        }
        if (!isset($conta['multa'])) {
            $this->setMulta(0);
        } else {
            $this->setMulta($conta['multa']);
        }
        if (!isset($conta['juros'])) {
            $this->setJuros(0);
        } else {
            $this->setJuros($conta['juros']);
        }
        if (!isset($conta['formula'])) {
            $this->setFormula(self::FORMULA_COMPOSTO);
        } else {
            $this->setFormula($conta['formula']);
        }
        if (!isset($conta['vencimento'])) {
            $this->setVencimento(null);
        } else {
            $this->setVencimento($conta['vencimento']);
        }
        if (!array_key_exists('numerodoc', $conta)) {
            $this->setNumeroDoc(null);
        } else {
            $this->setNumeroDoc($conta['numerodoc']);
        }
        if (!array_key_exists('anexourl', $conta)) {
            $this->setAnexoURL(null);
        } else {
            $this->setAnexoURL($conta['anexourl']);
        }
        if (!isset($conta['estado'])) {
            $this->setEstado(self::ESTADO_ATIVA);
        } else {
            $this->setEstado($conta['estado']);
        }
        if (!array_key_exists('datacalculo', $conta)) {
            $this->setDataCalculo(DB::now());
        } else {
            $this->setDataCalculo($conta['datacalculo']);
        }
        if (!isset($conta['dataemissao'])) {
            $this->setDataEmissao(DB::now());
        } else {
            $this->setDataEmissao($conta['dataemissao']);
        }
        return $this;
    }

    /**
     * Get relative anexo path or default anexo
     * @param boolean $default If true return default image, otherwise check field
     * @param string  $default_name Default image name
     * @return string relative web path for conta anexo
     */
    public function makeAnexoURL($default = false, $default_name = 'conta.png')
    {
        $anexo_url = $this->getAnexoURL();
        if ($default) {
            $anexo_url = null;
        }
        return get_document_url($anexo_url, 'conta', $default_name);
    }

    public function getAcrescimoAtual()
    {
        $datapagto = time();
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
        return $this->getValor() + $this->getAcrescimo();
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $conta = parent::publish();
        $conta['anexourl'] = $this->makeAnexoURL(false, null);
        return $conta;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param self $original Original instance without modifications
     * @param boolean $localized Informs if fields are localized
     * @return self Self instance
     */
    public function filter($original, $localized = false)
    {
        $this->setID($original->getID());
        $this->setFuncionarioID(Filter::number($original->getFuncionarioID()));
        $this->setPedidoID(Filter::number($original->getPedidoID()));
        $this->setEstado($original->getEstado());
        $this->setClassificacaoID(Filter::number($this->getClassificacaoID()));
        $this->setContaID(Filter::number($this->getContaID()));
        $this->setAgrupamentoID(Filter::number($this->getAgrupamentoID()));
        $this->setCarteiraID(Filter::number($this->getCarteiraID()));
        $this->setClienteID(Filter::number($this->getClienteID()));
        $this->setDescricao(Filter::string($this->getDescricao()));
        $this->setValor(Filter::money($this->getValor(), $localized));
        $this->setAcrescimo(Filter::money($this->getAcrescimo(), $localized));
        $this->setMulta(Filter::money($this->getMulta(), $localized));
        if ($this->getValor() > 0 && $this->getTipo() == self::TIPO_DESPESA) {
            $this->setValor(-$this->getValor());
        }
        if ($this->getAcrescimo() > 0 && $this->getTipo() == self::TIPO_DESPESA) {
            $this->setAcrescimo(-$this->getAcrescimo());
        }
        if ($this->getMulta() > 0 && $this->getTipo() == self::TIPO_DESPESA) {
            $this->setMulta(-$this->getMulta());
        }

        $this->setNumeroParcela(Filter::number($this->getNumeroParcela()));
        $this->setParcelas(Filter::number($this->getParcelas()));
        $this->setFrequencia(Filter::number($this->getFrequencia()));
        $this->setJuros(Filter::float($this->getJuros(), $localized) / 100);
        $this->setVencimento(Filter::datetime($this->getVencimento()));
        $this->setNumeroDoc(Filter::string($this->getNumeroDoc()));
        $anexo_url = upload_document('raw_anexourl', 'conta');
        if (is_null($anexo_url) && trim($this->getAnexoURL()) != '') {
            $this->setAnexoURL($original->getAnexoURL());
        } else {
            $this->setAnexoURL($anexo_url);
        }
        $this->setDataCalculo(Filter::datetime($this->getDataCalculo()));
        $this->setDataEmissao(Filter::datetime($this->getDataEmissao()));
        return $this;
    }

    /**
     * Clean instance resources like images and docs
     * @param self $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
        if (!is_null($this->getAnexoURL()) && $dependency->getAnexoURL() != $this->getAnexoURL()) {
            @unlink(get_document_path($this->getAnexoURL(), 'conta'));
        }
        $this->setAnexoURL($dependency->getAnexoURL());
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Conta in array format
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getClassificacaoID())) {
            $errors['classificacaoid'] = _t('conta.classificacao_id_cannot_empty');
        }
        if (is_null($this->getFuncionarioID())) {
            $errors['funcionarioid'] = _t('conta.funcionario_id_cannot_empty');
        }
        if (!Validator::checkInSet($this->getTipo(), self::getTipoOptions())) {
            $errors['tipo'] = _t('conta.tipo_invalid');
        }
        if (is_null($this->getDescricao())) {
            $errors['descricao'] = _t('conta.descricao_cannot_empty');
        }
        if (is_null($this->getValor())) {
            $errors['valor'] = _t('conta.valor_cannot_empty');
        } elseif (is_equal($this->getValor(), 0)) {
            $errors['valor'] = _t('conta.valor_cannot_zero');
        } elseif ($this->getValor() < 0 && $this->getTipo() == self::TIPO_RECEITA) {
            $errors['valor'] = _t('conta.receita_negativa');
        } elseif ($this->getValor() > 0 && $this->getTipo() == self::TIPO_DESPESA) {
            $errors['valor'] = _t('conta.despesa_positiva');
        }
        if (!Validator::checkInSet($this->getFonte(), self::getFonteOptions())) {
            $errors['fonte'] = _t('conta.fonte_invalid');
        }
        if (is_null($this->getNumeroParcela())) {
            $errors['numeroparcela'] = _t('conta.numero_parcela_cannot_empty');
        }
        if (is_null($this->getParcelas())) {
            $errors['parcelas'] = _t('conta.parcelas_cannot_empty');
        }
        if (is_null($this->getFrequencia())) {
            $errors['frequencia'] = _t('conta.frequencia_cannot_empty');
        }
        if (!Validator::checkInSet($this->getModo(), self::getModoOptions())) {
            $errors['modo'] = _t('conta.modo_invalid');
        }
        if (!Validator::checkBoolean($this->getAutomatico())) {
            $errors['automatico'] = _t('conta.automatico_invalid');
        }
        if (is_null($this->getAcrescimo())) {
            $errors['acrescimo'] = _t('conta.acrescimo_cannot_empty');
        } elseif ($this->getTipo() == self::TIPO_RECEITA && $this->getAcrescimo() < 0) {
            $errors['acrescimo'] = _t('conta.acrescimo_cannot_negative');
        } elseif ($this->getTipo() == self::TIPO_DESPESA && $this->getAcrescimo() > 0) {
            $errors['acrescimo'] = _t('conta.acrescimo_cannot_positive');
        }
        if (is_null($this->getMulta())) {
            $errors['multa'] = _t('conta.multa_cannot_empty');
        } elseif ($this->getTipo() == self::TIPO_RECEITA && $this->getMulta() < 0) {
            $errors['multa'] = _t('conta.multa_cannot_negative');
        } elseif ($this->getTipo() == self::TIPO_DESPESA && $this->getMulta() > 0) {
            $errors['multa'] = _t('conta.multa_cannot_positive');
        }
        if (is_null($this->getJuros())) {
            $errors['juros'] = _t('conta.juros_cannot_empty');
        } elseif ($this->getJuros() < 0) {
            $errors['juros'] = _t('conta.juros_cannot_negative');
        }
        if (!Validator::checkInSet($this->getFormula(), self::getFormulaOptions())) {
            $errors['formula'] = _t('conta.formula_invalid');
        }
        if (is_null($this->getVencimento())) {
            $errors['vencimento'] = _t('conta.vencimento_cannot_empty');
        }
        if (!Validator::checkInSet($this->getEstado(), self::getEstadoOptions())) {
            $errors['estado'] = _t('conta.estado_invalid');
        }
        $receitas = 0;
        if ($this->exists()) {
            $info = self::getTotalAbertas($this->getID());
            if (is_equal($info['receitas'], 0) && is_equal($info['despesas'], 0)) {
                $errors['id'] = 'A conta informada já foi consolidada e não pode ser alterada';
            }
            if ($this->getTipo() == self::TIPO_RECEITA && is_greater($info['recebido'], $this->getValor())) {
                $errors['valor'] = 'O total recebido é maior que o valor da conta';
            }
            if ($this->getTipo() == self::TIPO_DESPESA && is_greater(-$info['pago'], -$this->getValor())) {
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
        if (is_null($this->getDataEmissao())) {
            $errors['dataemissao'] = _t('conta.data_emissao_cannot_empty');
        }
        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
        return $this->toArray();
    }

    /**
     * Insert a new Conta into the database and fill instance from database
     * @return self Self instance
     * @throws \MZ\Exception\ValidationException for invalid input data
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
     * @param array $only Save these fields only, when empty save all fields except id
     * @return int rows affected
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function update($only = [])
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new ValidationException(
                ['id' => _t('conta.id_cannot_empty')]
            );
        }
        $values = DB::filterValues($values, $only, false);
        try {
            $affected = DB::update('Contas')
                ->set($values)
                ->where(['id' => $this->getID()])
                ->execute();
            $this->loadByID();
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $affected;
    }

    /**
     * Delete this instance from database using ID
     * @return integer Number of rows deleted (Max 1)
     * @throws \MZ\Exception\ValidationException for invalid id
     */
    public function delete()
    {
        if (!$this->exists()) {
            throw new ValidationException(
                ['id' => _t('conta.id_cannot_empty')]
            );
        }
        $result = DB::deleteFrom('Contas')
            ->where('id', $this->getID())
            ->execute();
        return $result;
    }

    /**
     * Load one register for it self with a condition
     * @param array $condition Condition for searching the row
     * @param array $order associative field name -> [-1, 1]
     * @return self Self instance filled or empty
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
     * Informa a conta principal
     * @return \MZ\Account\Conta The object fetched from database
     */
    public function findContaID()
    {
        if (is_null($this->getContaID())) {
            return new \MZ\Account\Conta();
        }
        return \MZ\Account\Conta::findByID($this->getContaID());
    }

    /**
     * Informa se esta conta foi agrupada e não precisa ser mais paga
     * individualmente, uma conta agrupada é tratada internamente como
     * desativada
     * @return \MZ\Account\Conta The object fetched from database
     */
    public function findAgrupamentoID()
    {
        if (is_null($this->getAgrupamentoID())) {
            return new \MZ\Account\Conta();
        }
        return \MZ\Account\Conta::findByID($this->getAgrupamentoID());
    }

    /**
     * Informa a carteira que essa conta será paga automaticamente ou para
     * informar as contas a pagar dessa carteira
     * @return \MZ\Wallet\Carteira The object fetched from database
     */
    public function findCarteiraID()
    {
        if (is_null($this->getCarteiraID())) {
            return new \MZ\Wallet\Carteira();
        }
        return \MZ\Wallet\Carteira::findByID($this->getCarteiraID());
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
     * Gets textual and translated Tipo for Conta
     * @param int $index choose option from index
     * @return string[]|string A associative key -> translated representative text or text for index
     */
    public static function getTipoOptions($index = null)
    {
        $options = [
            self::TIPO_RECEITA => _t('conta.tipo_receita'),
            self::TIPO_DESPESA => _t('conta.tipo_despesa'),
        ];
        if (!is_null($index)) {
            return $options[$index];
        }
        return $options;
    }

    /**
     * Gets textual and translated Fonte for Conta
     * @param int $index choose option from index
     * @return string[]|string A associative key -> translated representative text or text for index
     */
    public static function getFonteOptions($index = null)
    {
        $options = [
            self::FONTE_FIXA => _t('conta.fonte_fixa'),
            self::FONTE_VARIAVEL => _t('conta.fonte_variavel'),
            self::FONTE_COMISSAO => _t('conta.fonte_comissao'),
            self::FONTE_REMUNERACAO => _t('conta.fonte_remuneracao'),
        ];
        if (!is_null($index)) {
            return $options[$index];
        }
        return $options;
    }

    /**
     * Gets textual and translated Modo for Conta
     * @param int $index choose option from index
     * @return string[]|string A associative key -> translated representative text or text for index
     */
    public static function getModoOptions($index = null)
    {
        $options = [
            self::MODO_DIARIO => _t('conta.modo_diario'),
            self::MODO_MENSAL => _t('conta.modo_mensal'),
        ];
        if (!is_null($index)) {
            return $options[$index];
        }
        return $options;
    }

    /**
     * Gets textual and translated Formula for Conta
     * @param int $index choose option from index
     * @return string[]|string A associative key -> translated representative text or text for index
     */
    public static function getFormulaOptions($index = null)
    {
        $options = [
            self::FORMULA_SIMPLES => _t('conta.formula_simples'),
            self::FORMULA_COMPOSTO => _t('conta.formula_composto'),
        ];
        if (!is_null($index)) {
            return $options[$index];
        }
        return $options;
    }

    /**
     * Gets textual and translated Estado for Conta
     * @param int $index choose option from index
     * @return string[]|string A associative key -> translated representative text or text for index
     */
    public static function getEstadoOptions($index = null)
    {
        $options = [
            self::ESTADO_ANALISE => _t('conta.estado_analise'),
            self::ESTADO_ATIVA => _t('conta.estado_ativa'),
            self::ESTADO_PAGA => _t('conta.estado_paga'),
            self::ESTADO_CANCELADA => _t('conta.estado_cancelada'),
            self::ESTADO_DESATIVADA => _t('conta.estado_desativada'),
        ];
        if (!is_null($index)) {
            return $options[$index];
        }
        return $options;
    }

    /**
     * Get allowed keys array
     * @return array allowed keys array
     */
    private static function getAllowedKeys()
    {
        $conta = new self();
        $allowed = Filter::concatKeys('c.', $conta->toArray());
        return $allowed;
    }

    /**
     * Filter order array
     * @param mixed $order order string or array to parse and filter allowed
     * @return array allowed associative order
     */
    private static function filterOrder($order)
    {
        $allowed = self::getAllowedKeys();
        return Filter::orderBy($order, $allowed, 'c.');
    }

    /**
     * Filter condition array with allowed fields
     * @param array $condition condition to filter rows
     * @return array allowed condition
     */
    private static function filterCondition($condition)
    {
        $allowed = self::getAllowedKeys();
        if (isset($condition['search'])) {
            $search = trim($condition['search']);
            if (is_numeric($search)) {
                $condition['numerodoc'] = $search;
            } elseif ($search != '') {
                $field = 'c.descricao LIKE ?';
                $condition[$field] = '%'.$search.'%';
                $allowed[$field] = true;
            }
            unset($condition['search']);
        }
        if (isset($condition['classificacao'])) {
            $classificacao = intval($condition['classificacao']);
            $field = '(c.classificacaoid = ? OR s.classificacaoid = ?)';
            $condition[$field] = [$classificacao, $classificacao];
            $allowed[$field] = true;
            unset($condition['classificacao']);
        }
        return Filter::keys($condition, $allowed, 'c.');
    }

    /**
     * Fetch data from database with a condition
     * @param array $condition condition to filter rows
     * @param array $order order rows
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
     * @param array $condition Condition for searching the row
     * @param array $order order rows
     * @return self A filled Conta or empty instance
     */
    public static function find($condition, $order = [])
    {
        $result = new self();
        return $result->load($condition, $order);
    }

    /**
     * Search one register with a condition
     * @param array $condition Condition for searching the row
     * @param array $order order rows
     * @return self A filled Conta or empty instance
     * @throws \Exception when register has not found
     */
    public static function findOrFail($condition, $order = [])
    {
        $result = self::find($condition, $order);
        if (!$result->exists()) {
            throw new \Exception(_t('conta.not_found'), 404);
        }
        return $result;
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
            $sql .= 'AND c.numerodoc = :codigo ';
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
     * @param array  $condition Condition to get all Conta
     * @param array  $order     Order Conta
     * @param int    $limit     Limit data into row count
     * @param int    $offset    Start offset to get rows
     * @return self[] List of all rows instanced as Conta
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
            $result[] = new self($row);
        }
        return $result;
    }

    /**
     * Count all rows from database with matched condition critery
     * @param array $condition condition to filter rows
     * @return integer Quantity of rows
     */
    public static function count($condition = [])
    {
        $query = self::query($condition);
        return $query->count();
    }
}
