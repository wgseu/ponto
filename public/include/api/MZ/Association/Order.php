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
namespace MZ\Association;

use MZ\Util\Document;
use MZ\Database\Model;
use MZ\Database\DB;
use MZ\Util\Filter;
use MZ\Location\Localizacao;
use MZ\Location\Cidade;
use MZ\Location\Bairro;
use MZ\Location\Estado;
use MZ\Location\Pais;
use MZ\Payment\FormaPagto;
use MZ\Payment\Pagamento;
use MZ\Payment\Cartao;
use MZ\Account\Cliente;
use MZ\Sale\Pedido;
use MZ\Sale\ProdutoPedido;
use MZ\Product\Servico;
use MZ\Product\Produto;
use MZ\Sale\Formacao;
use MZ\Session\Sessao;
use MZ\System\Synchronizer;

class Order extends Pedido
{
    /**
     * Integration
     */
    private $integracao;
    /**
     * Card names and codes
     */
    private $card_names;
    /**
     * Customer
     */
    private $customer;
    /**
     * Localization
     */
    private $localization;
    /**
     * District
     */
    private $district;
    /**
     * City
     */
    private $city;
    /**
     * State
     */
    private $state;
    /**
     * Country
     */
    private $country;
    /**
     * Products
     */
    private $products;
    /**
     * Payments
     */
    private $payments;
    /**
     * Payments
     */
    private $employee;
    /**
     * Integration order code
     */
    private $code;

    public function setIntegracao($integracao)
    {
        $this->integracao = $integracao;
        return $this;
    }

    public function setCardNames($card_names)
    {
        $this->card_names = $card_names;
        return $this;
    }

    public function setEmployee($employee)
    {
        $this->employee = $employee;
        return $this;
    }

    public function loadDOM($dom)
    {
        $dados = $this->integracao->read();
        $cartoes = isset($dados['cartoes'])?$dados['cartoes']:[];
        $produtos = isset($dados['produtos'])?$dados['produtos']:[];
        $response = $dom->documentElement;
        if (is_null($response)) {
            throw new \Exception('O arquivo de importação de pedido não é válido', 401);
        }
        $body_nodes = $response->getElementsByTagName('response-body');
        $body_pedido = null;
        $body_list = null;
        foreach ($body_nodes as $body) {
            $attr_value = $body->getAttribute('class');
            if ($attr_value == 'pedido') {
                $body_pedido = $body;
            } elseif ($attr_value == 'list') {
                $body_list = $body;
            }
        }
        if (is_null($body_pedido)) {
            throw new \Exception('O pedido não foi encontrado na integração', 401);
        }
        if (is_null($body_list)) {
            throw new \Exception('A lista de produtos não foi informada', 401);
        }
        $this->code = Document::childValue($body_pedido, 'idPedidoCurto');
        $obs = Document::childValue($body_pedido, 'obsPedido');
        // $obs = str_replace("\r", '', str_replace("\n", ', ', trim($obs)));
        $this->setDescricao($obs);
        $data_criacao = Document::childValue($body_pedido, 'dataPedidoComanda');
        $this->setDataCriacao(DB::now($data_criacao));
        $this->setTipo(self::TIPO_ENTREGA);
        $this->setEstado(self::ESTADO_AGENDADO);
        $this->setPessoas(1);
        $this->setCancelado('N');
        // agenda para o mesmo horário do pedido
        $this->setDataAgendamento($this->getDataCriacao());
        $this->customer = new Cliente();
        $nome = Document::childValue($body_pedido, 'nome');
        $this->customer->setNomeCompleto(Filter::name($nome));
        $email = Document::childValue($body_pedido, 'email');
        $this->customer->setEmail(strtolower($email));
        $telefones_node = Document::findChild($body_pedido, 'telefones', false);
        if (!is_null($telefones_node)) {
            $index = 1;
            $telefone_list = $telefones_node->getElementsByTagName('telefone');
            foreach ($telefone_list as $telefone) {
                $ddd = Document::childValue($telefone, 'ddd');
                $numero = Document::childValue($telefone, 'numero');
                $this->customer->setFone($index, $ddd . $numero);
                $index++;
                if ($index > 2) {
                    break;
                }
            }
        }
        $entregar = Document::childValue($body_pedido, 'togo');
        if ($entregar == 'false') {
            $this->setLocalizacaoID(1);
        } else {
            $this->setLocalizacaoID(null);
        }
        $this->localization = new Localizacao();
        $this->localization->setCEP(Document::childValue($body_pedido, 'cep'));
        $this->localization->setTipo(Localizacao::TIPO_CASA);
        $this->localization->setLogradouro(Document::childValue($body_pedido, 'logradouro'));
        $this->localization->setNumero(Document::childValue($body_pedido, 'logradouroNum'));
        $this->localization->setComplemento(Document::childValue($body_pedido, 'complemento', false));
        $this->localization->setReferencia(Document::childValue($body_pedido, 'referencia', false));
        $this->localization->setMostrar('Y');

        $this->district = new Bairro();
        $this->district->setNome(Document::childValue($body_pedido, 'bairro'));
        $this->district->setValorEntrega(floatval(Document::childValue($body_pedido, 'vlrTaxa')));

        $this->city = new Cidade();
        $this->city->setNome(Document::childValue($body_pedido, 'cidade'));

        $this->state = new Estado();
        $this->state->setUF(Document::childValue($body_pedido, 'estado'));

        $this->country = new Pais();
        $this->country->setCodigo(Document::childValue($body_pedido, 'pais'));
        $this->payments = [];
        $servicos = [];
        $pagamentos_node = Document::findChild($body_pedido, 'pagamentos', false);
        if (!is_null($pagamentos_node)) {
            $pagamento_list = $pagamentos_node->getElementsByTagName('pagamento');
            foreach ($pagamento_list as $pagamento_node) {
                $cod_tipo_cond_pagto = Document::childValue($pagamento_node, 'codTipoCondPagto');
                if ($cod_tipo_cond_pagto == 'D') {
                    $produto_pedido = new ProdutoPedido();
                    $produto_pedido->setServicoID(Servico::DESCONTO_ID);
                    $produto_pedido->setQuantidade(1);
                    $produto_pedido->setPreco(-floatval(Document::childValue($pagamento_node, 'valor')));
                    $produto_pedido->setPrecoVenda(0.00);
                    $produto_pedido->setDetalhes('Desconto no pedido');
                    $servicos[] = $produto_pedido;
                } else {
                    $debito = false;
                    switch ($cod_tipo_cond_pagto) {
                        case '3':
                        case 'D':
                            $tipo_pagto = FormaPagto::TIPO_DINHEIRO;
                            break;
                        case '1': // Crédito
                        case '4': // Débito
                        case '5': // Crédito online
                        case 'A': // Transferência
                        case 'C': // Carteira online
                        case 'O': // Transferência
                            $debito = $cod_tipo_cond_pagto == '4';
                            $tipo_pagto = FormaPagto::TIPO_CARTAO;
                            break;
                        case '2':
                            $tipo_pagto = FormaPagto::TIPO_CHEQUE;
                            break;
                        case 'E':
                            $tipo_pagto = FormaPagto::TIPO_CONTA;
                            break;
                        default:
                            throw new \Exception(
                                sprintf('Forma de pagamento "%s" não reconhecida', $cod_tipo_cond_pagto),
                                500
                            );
                    }
                    $forma_pagto = FormaPagto::find(['tipo' => $tipo_pagto, 'ativa' => 'Y']);
                    if (!$forma_pagto->exists()) {
                        throw new \Exception(
                            sprintf('Não existe forma de pagamento em %s ativa', FormaPagto::getTipoOptions($tipo_pagto)),
                            500
                        );
                    }
                    $pagamento = new Pagamento();
                    $pagamento->setFormaPagtoID($forma_pagto->getID());
                    $pagamento->setTotal(floatval(Document::childValue($pagamento_node, 'valor')));
                    if ($pagamento->getTotal() > 0) {
                        $pagamento->setCarteiraID($forma_pagto->getCarteiraID());
                    } else {
                        $pagamento->setCarteiraID($forma_pagto->getCarteiraPagtoID());
                    }
                    $pagamento->setAtivo('N');
                    if ($forma_pagto->getTipo() == FormaPagto::TIPO_CARTAO) {
                        $cartao = new Cartao();
                        $cod_tipo_pagto = Document::childValue($pagamento_node, 'codFormaPagto');
                        if (!isset($this->card_names[$cod_tipo_pagto])) {
                            throw new \Exception(
                                sprintf('O cartão de código "%s" não é suportado nessa versão', $cod_tipo_pagto),
                                500
                            );
                        }
                        if (!isset($cartoes[$cod_tipo_pagto])) {
                            throw new \Exception(
                                sprintf('O cartão %s não foi associado', $this->card_names[$cod_tipo_pagto]),
                                500
                            );
                        }
                        $pagamento->setCartaoID($cartoes[$cod_tipo_pagto]);
                        $cartao = $pagamento->findCartaoID();
                        if (!$cartao->exists()) {
                            throw new \Exception(
                                sprintf('A associação do cartão %s está inválida', $this->card_names[$cod_tipo_pagto]),
                                500
                            );
                        }
                        $pagamento->setParcelas(1);
                        $pagamento->setValorParcela($pagamento->getTotal());
                        $pagamento->setTaxas(
                            -1 * $pagamento->getParcelas() *
                            $pagamento->getValorParcela() * ($cartao->getTaxa() / 100.0) -
                            $cartao->getTransacao()
                        );
                        if ($pagamento->getTotal() > 0) {
                            $pagamento->setCarteiraID($cartao->getCarteiraID() ?: $forma_pagto->getCarteiraID());
                        } else {
                            $pagamento->setCarteiraID($cartao->getCarteiraPagtoID() ?: $forma_pagto->getCarteiraPagtoID());
                        }
                        if ($debito) {
                            $pagamento->setDetalhes('Cartão de débito');
                        }
                        $pagamento->setDataCompensacao(DB::now('+' . intval($cartao->getDiasRepasse()) . ' day'));
                    } elseif ($forma_pagto->getTipo() != FormaPagto::TIPO_DINHEIRO) {
                        throw new \Exception('Apenas dinheiro e cartão são aceitos', 500);
                    }
                    $this->payments[] = $pagamento;
                }
            }
        }
        $troco = floatval(Document::childValue($body_pedido, 'vlrTroco'));
        if ($troco > 0) {
            $forma_pagto = FormaPagto::find(['tipo' => FormaPagto::TIPO_DINHEIRO, 'ativa' => 'Y']);
            if (!$forma_pagto->exists()) {
                throw new \Exception(
                    sprintf(
                        'Não existe forma de pagamento em %s ativa',
                        FormaPagto::getTipoOptions(FormaPagto::TIPO_DINHEIRO)
                    ),
                    500
                );
            }
            $pagamento = new Pagamento();
            $pagamento->setFormaPagtoID($forma_pagto->getID());
            $pagamento->setCarteiraID($forma_pagto->getCarteiraPagtoID());
            $pagamento->setTotal(-$troco);
            $pagamento->setDetalhes('Troco');
            $pagamento->setAtivo('N');
            $this->payments[] = $pagamento;
        }
        $desconto = floatval(Document::childValue($body_pedido, 'vlrDesconto'));
        if ($desconto > 0) {
            $produto_pedido = new ProdutoPedido();
            $produto_pedido->setServicoID(Servico::DESCONTO_ID);
            $produto_pedido->setQuantidade(1);
            $produto_pedido->setPreco(-$desconto);
            $produto_pedido->setPrecoVenda(0.00);
            $produto_pedido->setDetalhes('Desconto no pedido');
            $servicos[] = $produto_pedido;
        }
        $taxa_entrega = floatval(Document::childValue($body_pedido, 'vlrTaxa'));
        if ($taxa_entrega > 0) {
            $produto_pedido = new ProdutoPedido();
            $produto_pedido->setServicoID(Servico::ENTREGA_ID);
            $produto_pedido->setQuantidade(1);
            $produto_pedido->setPreco($taxa_entrega);
            $produto_pedido->setPrecoVenda(0.00);
            $produto_pedido->setDetalhes('Taxa de entrega');
            $servicos[] = $produto_pedido;
        }
        $i = 0;
        $itens = $body_list->getElementsByTagName('item');
        $this->products = [];
        $parent_products = [];
        foreach ($itens as $item) {
            $codigo = Document::childValue($item, 'codCardapio');
            $codigo_pai = Document::childValue($item, 'codPai', false);
            $descricao = Document::childValue($item, 'descricaoCardapio');
            $codigo_pdv = Document::childValue($item, 'codProdutoPdv', false);
            $produto_pedido = new ProdutoPedido();
            $produto_pedido->setID($i);
            $produto_pedido->setQuantidade(floatval(Document::childValue($item, 'quantidade')));
            $produto_pedido->setPreco(floatval(Document::childValue($item, 'vlrUnitBruto')));
            $produto_pedido->setPrecoVenda(floatval(Document::childValue($item, 'vlrUnitLiq')));
            $produto_pedido->setDetalhes(Document::childValue($item, 'obsItem', false));
            if (is_null($codigo_pai)) {
                $produto_id = isset($produtos[$codigo]['id']) ? $produtos[$codigo]['id'] : null;
                $produto_pedido->setProdutoID($produto_id ?: $codigo_pdv);
                $produto = $produto_pedido->findProdutoID();
                if (!$produto->exists()) {
                    throw new \Exception(
                        sprintf('O produto "%s" não foi associado corretamente', $descricao),
                        404
                    );
                }
                $this->products[$i] = [
                    'produto' => $produto_pedido,
                    'formacoes' => []
                ];
                $parent_products[$codigo] = $i;
                $i++;
            } elseif (array_key_exists($parent_products[$codigo_pai])) {
                $subitem_id = isset($produtos[$codigo_pai]['itens'][$codigo]['id']) ?
                    $produtos[$codigo_pai]['itens'][$codigo]['id'] : null;
                $parent_index = $parent_products[$codigo_pai];
                $produto_pedido_pai = $this->products[$parent_index]['produto'];
                $produto = $produto_pedido_pai->findProdutoID();
                $formacao = new Formacao();
                if ($produto->getTipo() == Produto::TIPO_COMPOSICAO) {
                    $formacao->setTipo(Formacao::TIPO_COMPOSICAO);
                    $formacao->setComposicaoID($subitem_id ?: $codigo_pdv);
                    $composicao = $formacao->findComposicaoID();
                    if (!$composicao->exists()) {
                        throw new \Exception(
                            sprintf(
                                'A composição "%s" não foi associada corretamente no produto "%s"',
                                $descricao,
                                $produto->getDescricao()
                            ),
                            404
                        );
                    }
                    $produto_pedido->setProdutoID($composicao->getProdutoID());
                } else {
                    $formacao->setTipo(Formacao::TIPO_PACOTE);
                    $formacao->setPacoteID($subitem_id ?: $codigo_pdv);
                    $pacote = $formacao->findPacoteID();
                    if (!$pacote->exists()) {
                        throw new \Exception(
                            sprintf(
                                'O item "%s" não foi associado corretamente no produto "%s"',
                                $descricao,
                                $produto->getDescricao()
                            ),
                            404
                        );
                    }
                    $produto_pedido->setProdutoID($pacote->getProdutoID());
                }
                if (!is_null($produto_pedido->getProdutoID())) {
                    $produto_pedido->setProdutoPedidoID($produto_pedido_pai->getID());
                    $this->products[$i] = [
                        'produto' => $produto_pedido,
                        'formacoes' => []
                    ];
                    $parent_index = $i;
                    $i++;
                } else {
                    // pacote propriedade
                    $produto_pedido_pai->setPreco($produto_pedido_pai->getPreco() + $produto_pedido->getPreco());
                    $produto_pedido_pai->setPrecoVenda(
                        $produto_pedido_pai->getPrecoVenda() + $produto_pedido->getPrecoVenda()
                    );
                }
                $this->products[$parent_index]['formacoes'][] = $formacao;
            } else {
                throw new \Exception('O produto principal do pacote não foi encontrado', 404);
            }
        }
        foreach ($servicos as $servico) {
            $this->products[$i] = [
                'produto' => $servico,
                'formacoes' => []
            ];
            $i++;
        }
    }

    public function loadData($data)
    {
        $_pedidos = isset($data['pedidos']) ? $data['pedidos'] : [];
        $this->setTipo(self::TIPO_MESA);
        if (isset($data['tipo'])) {
            if ($data['tipo'] == 'comanda') {
                $this->setTipo(self::TIPO_COMANDA);
            } elseif ($data['tipo'] == 'mesa') {
                $this->setTipo(self::TIPO_MESA);
            /*} elseif ($data['tipo'] == 'avulso') {
                $this->setTipo(self::TIPO_AVULSO);
            } elseif ($data['tipo'] == 'entrega') {
                $this->setTipo(self::TIPO_ENTREGA);*/
            } else {
                throw new \Exception('Tipo de lançamento não suportado nessa versão', 500);
            }
        }
        $this->setMesaID(isset($data['mesa']) && $data['mesa'] ? intval($data['mesa']) : null);
        $this->setComandaID(isset($data['comanda']) ? intval($data['comanda']) : null);
        if (isset($data['cliente']) && check_fone($data['cliente'], true)) {
            $this->customer = Cliente::findByFone($data['cliente']);
            if (!$this->customer->exists()) {
                $this->customer = null;
            }
        } else {
            $this->customer = null;
        }
        $this->setPessoas(1);
        $this->setCancelado('N');
        $this->setDescricao(isset($data['descricao']) ? $data['descricao'] : null);
        $this->setEstado(self::ESTADO_ATIVO);
        $i = 0;
        $parent_index = null;
        $this->products = [];
        $parent_products = [];
        foreach ($_pedidos as $_produto_pedido) {
            $produto_pedido = new ProdutoPedido($_produto_pedido);
            if (!is_null($produto_pedido->getProdutoPedidoID())) {
                if (isset($parent_products[$produto_pedido->getProdutoPedidoID()])) {
                    $parent_index = $parent_products[$produto_pedido->getProdutoPedidoID()];
                } elseif (is_null($parent_index)) {
                    throw new \Exception('A ordem dos pedidos enviados é inválida', 500);
                }
                $produto_pedido->setProdutoPedidoID($parent_index);
            } else {
                if ($produto_pedido->exists()) {
                    $parent_products[$produto_pedido->getID()] = $i;
                    $parent_index = null;
                } else {
                    $parent_index = $i;
                }
            }
            $formacoes = [];
            $_formacoes = isset($_produto_pedido['formacoes']) ? $_produto_pedido['formacoes'] : [];
            foreach ($_formacoes as $_formacao) {
                $formacoes[] = new Formacao($_formacao);
            }
            $produto_pedido->setID($i);
            $this->products[$i] = [
                'produto' => $produto_pedido,
                'formacoes' => $formacoes
            ];
            $i++;
        }
    }

    public function search()
    {
        if (!is_null($this->customer)) {
            if (!$this->customer->exists() && Validator::checkEmail($this->customer->getEmail())) {
                $cliente = Cliente::findByEmail($this->customer->getEmail());
                if ($cliente->exists()) {
                    $this->customer->fromArray($cliente->toArray());
                }
            }
            if (!$this->customer->exists() && Validator::checkPhone($this->customer->getFone(1))) {
                $cliente = Cliente::findByFone($this->customer->getFone(1));
                if ($cliente->exists()) {
                    $this->customer->fromArray($cliente->toArray());
                }
            }
        }
        if ($this->exists()) {
            return true;
        }
        $pedido = new Pedido($this);
        if (in_array($this->getTipo(), [self::TIPO_MESA, self::TIPO_COMANDA])) {
            $pedido->loadByLocal();
        } elseif (!is_null($this->customer) && $this->customer->exists()) {
            // loadAproximado requires costumer id
            $pedido->setClienteID($this->customer->getID());
            $pedido->loadAproximado();
        }
        if ($pedido->exists()) {
            $this->fromArray($pedido->toArray());
        }
    }

    public function process($synchronize = true)
    {
        $action = Synchronizer::ACTION_ADDED;
        try {
            DB::beginTransaction();
            $this->validaAcesso($this->employee);
            if (!$this->exists()) {
                $sessao = Sessao::findByAberta(true);
                $this->setSessaoID($sessao->getID());
            }
            if (!is_null($this->customer) && !$this->customer->exists()) {
                $this->customer->filter(new Cliente());
                $this->customer->insert();
            }
            if (!$this->exists()) {
                if (!is_null($this->customer)) {
                    $this->setClienteID($this->customer->getID());
                }
                // não existe pedido ainda, cadastra um novo
                $this->setFuncionarioID($this->employee->getID());
                $this->filter(new Pedido());
                $this->insert();
                $action = Synchronizer::ACTION_OPEN;
            }
            $added = 0;
            $pacote_pedido = new ProdutoPedido();
            foreach ($this->products as $index => $item_info) {
                $produto_pedido = $item_info['produto'];
                $produto_pedido->setPedidoID($this->getID());
                $produto_pedido->setFuncionarioID($this->employee->getID());
                $produto_pedido->setPrecoCompra(0);
                $produto = $produto_pedido->findProdutoID();
                if ($produto->exists()) {
                    if ($produto->isCobrarServico()) {
                        $produto_pedido->setPorcentagem($this->employee->getPorcentagem());
                    }
                    if (!is_null($produto_pedido->getProdutoPedidoID())) {
                        // TODO atribuir preço e verificar preços das composições
                        $produto_pedido->setProdutoPedidoID($pacote_pedido->getID());
                    } elseif ($produto->getTipo() != Produto::TIPO_PACOTE) {
                        $produto_pedido->setPreco($produto->getPrecoVenda());
                        $produto_pedido->setPrecoVenda($produto->getPrecoVenda());
                        $pacote_pedido = new ProdutoPedido();
                    } else {
                        // TODO atribuir preço padrão e verificar preços das propriedades
                    }
                    if (!is_null($produto->getCustoProducao())) {
                        $produto_pedido->setPrecoCompra($produto->getCustoProducao());
                    }
                }
                if (is_null($produto_pedido->getPorcentagem())) {
                    $produto_pedido->setPorcentagem(0);
                }
                $produto_pedido->setEstado(ProdutoPedido::ESTADO_ADICIONADO);
                $produto_pedido->setCancelado('N');
                $produto_pedido->setVisualizado('N');
                $this->checkSaldo($produto_pedido->getTotal());
                $save_item = new ProdutoPedido($produto_pedido);
                $produto_pedido->filter(new ProdutoPedido()); // limpa o ID

                // corrige a aplicação de filtro acima
                $produto_pedido->setPreco(floatval($save_item->getPreco()));
                $produto_pedido->setQuantidade(floatval($save_item->getQuantidade()));
                $produto_pedido->setPorcentagem(floatval($save_item->getPorcentagem()));
                $produto_pedido->setPrecoVenda(floatval($save_item->getPrecoVenda()));
                $produto_pedido->setPrecoCompra(floatval($save_item->getPrecoCompra()));

                $produto_pedido->register($item_info['formacoes']);
                if ($produto->exists() && $produto->getTipo() == Produto::TIPO_PACOTE) {
                    $pacote_pedido = $produto_pedido;
                }
                $added++;
            }
            if ($added > 0 && $action != Synchronizer::ACTION_OPEN && $this->getEstado() != self::ESTADO_ATIVO) {
                $action = Synchronizer::ACTION_STATE;
                if (in_array($this->getTipo(), [self::TIPO_MESA, self::TIPO_COMANDA])) {
                    $this->setEstado(self::ESTADO_ATIVO);
                    $this->update();
                }
            }
            if ($synchronize) {
                $sync = new Synchronizer();
                if ($action != Synchronizer::ACTION_ADDED) {
                    $sync->updateOrder(
                        $this->getID(),
                        $this->getTipo(),
                        $this->getMesaID(),
                        $this->getComandaID(),
                        $action
                    );
                }
                if ($action == Synchronizer::ACTION_OPEN) {
                    $senha_balcao = is_boolean_config('Imprimir', 'Senha.Paineis');
                    $comanda_senha = is_boolean_config('Imprimir', 'Comanda.Senha');
                    if (($senha_balcao && $this->getTipo() == self::TIPO_AVULSO) ||
                        ($comanda_senha && $this->getTipo() == self::TIPO_COMANDA)
                    ) {
                        $sync->printQueue($this->getID());
                    }
                }
                if ($added > 0) {
                    $sync->updateOrder(
                        $this->getID(),
                        $this->getTipo(),
                        $this->getMesaID(),
                        $this->getComandaID(),
                        Synchronizer::ACTION_ADDED
                    );
                    $sync->printServices($this->getID());
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
        return $action;
    }

    public function store()
    {
        $changes = [];
        //TODO
        $changes[] = ['code' => 2981, 'estado' => Pedido::ESTADO_AGENDADO];
        return $changes;
    }

    public function changes($limit = null)
    {
        //TODO
    }

    public function apply($updates)
    {
        //TODO
        return count($updates) > 0;
    }
}
