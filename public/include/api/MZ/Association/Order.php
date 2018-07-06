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
use MZ\Util\Generator;
use MZ\Util\Filter;
use MZ\Util\Gender;
use MZ\Util\Validator;
use MZ\Database\Model;
use MZ\Database\DB;
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
use MZ\Sale\Formacao;
use MZ\Sale\Montagem;
use MZ\Product\Servico;
use MZ\Product\Produto;
use MZ\Product\Composicao;
use MZ\Session\Sessao;
use MZ\Session\Movimentacao;
use MZ\System\Synchronizer;
use MZ\Exception\RedirectException;

class Order extends Pedido
{
    const ESTADO_CANCELADO = 'Cancelado';

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
        global $app;

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
        $this->setDataCriacao(Filter::datetime($data_criacao));
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
        $this->customer->setGenero(Gender::detect($this->customer->getNome()));
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
                            throw new RedirectException(
                                sprintf('Forma de pagamento "%s" não reconhecida', $cod_tipo_cond_pagto),
                                500,
                                $app->makeURL('/gerenciar/cartao/' . $this->integracao->getAcessoURL())
                            );
                    }
                    $forma_pagto = FormaPagto::find(['tipo' => $tipo_pagto, 'ativa' => 'Y']);
                    if (!$forma_pagto->exists()) {
                        throw new RedirectException(
                            sprintf(
                                'Não existe forma de pagamento em %s ativa',
                                FormaPagto::getTipoOptions($tipo_pagto)
                            ),
                            500,
                            'grandchef://cartao'
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
                            throw new RedirectException(
                                sprintf('O cartão de código "%s" não é suportado nessa versão', $cod_tipo_pagto),
                                500,
                                $app->makeURL('/gerenciar/cartao/' . $this->integracao->getAcessoURL())
                            );
                        }
                        if (!isset($cartoes[$cod_tipo_pagto])) {
                            throw new RedirectException(
                                sprintf('O cartão "%s" não foi associado', $this->card_names[$cod_tipo_pagto]['name']),
                                500,
                                $app->makeURL('/gerenciar/cartao/' . $this->integracao->getAcessoURL())
                            );
                        }
                        $pagamento->setCartaoID($cartoes[$cod_tipo_pagto]);
                        $cartao = $pagamento->findCartaoID();
                        if (!$cartao->exists()) {
                            throw new RedirectException(
                                sprintf(
                                    'A associação do cartão "%s" está inválida',
                                    $this->card_names[$cod_tipo_pagto]['name']
                                ),
                                500,
                                $app->makeURL('/gerenciar/cartao/' . $this->integracao->getAcessoURL())
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
                            $pagamento->setCarteiraID($cartao->getCarteiraID() ?:
                                $forma_pagto->getCarteiraID());
                        } else {
                            $pagamento->setCarteiraID($cartao->getCarteiraPagtoID() ?:
                                $forma_pagto->getCarteiraPagtoID());
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
                throw new RedirectException(
                    sprintf(
                        'Não existe forma de pagamento em %s ativa',
                        FormaPagto::getTipoOptions(FormaPagto::TIPO_DINHEIRO)
                    ),
                    500,
                    'grandchef://forma_pagto'
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
        $pacotes = [];
        $parent_products = [];
        foreach ($itens as $item) {
            $codigo = Document::childValue($item, 'codCardapio');
            $codigo_pai = Document::childValue($item, 'codPai', false);
            $descricao = Document::childValue($item, 'descricaoCardapio');
            $codigo_pdv = Document::childValue($item, 'codProdutoPdv', false);
            $produto_pedido = new ProdutoPedido();
            $produto_pedido->setID($i);
            $produto_pedido->setQuantidade(floatval(Document::childValue($item, 'quantidade')));
            $produto_pedido->setPreco(floatval(Document::childValue($item, 'vlrUnitLiq')));
            $produto_pedido->setPrecoVenda(floatval(Document::childValue($item, 'vlrUnitBruto')));
            $produto_pedido->setDetalhes(Document::childValue($item, 'obsItem', false));
            if (is_null($codigo_pai)) {
                $produto_id = isset($produtos[$codigo]['id']) ? $produtos[$codigo]['id'] : null;
                $produto_pedido->setProdutoID($produto_id ?: $codigo_pdv);
                $produto = $produto_pedido->findProdutoID();
                if (!$produto->exists()) {
                    throw new RedirectException(
                        sprintf('O produto "%s" não foi associado corretamente', $descricao),
                        404,
                        $app->makeURL('/gerenciar/produto/' . $this->integracao->getAcessoURL())
                    );
                }
                if ($produto->getTipo() == Produto::TIPO_PACOTE) {
                    $pacotes[$i] = [];
                }
                $this->products[$i] = [
                    'item' => $produto_pedido,
                    'formacoes' => []
                ];
                $parent_products[$codigo] = $i;
                $i++;
            } elseif (array_key_exists($codigo_pai, $parent_products)) {
                $subitem_id = isset($produtos[$codigo_pai]['itens'][$codigo]['id']) ?
                    $produtos[$codigo_pai]['itens'][$codigo]['id'] : null;
                $parent_index = $parent_products[$codigo_pai];
                $produto_pedido_pai = $this->products[$parent_index]['item'];
                $produto_pai = $produto_pedido_pai->findProdutoID();
                $formacao = new Formacao();
                if ($produto_pai->getTipo() == Produto::TIPO_COMPOSICAO) {
                    $formacao->setTipo(Formacao::TIPO_COMPOSICAO);
                    $formacao->setComposicaoID($subitem_id ?: $codigo_pdv);
                    $composicao = $formacao->findComposicaoID();
                    if (!$composicao->exists()) {
                        throw new RedirectException(
                            sprintf(
                                'A composição "%s" não foi associada corretamente no produto "%s"',
                                $descricao,
                                $produto_pai->getDescricao()
                            ),
                            404,
                            $app->makeURL('/gerenciar/produto/' . $this->integracao->getAcessoURL())
                        );
                    }
                    $produto = $composicao->findProdutoID();
                    if ($composicao->getTipo() == Composicao::TIPO_ADICIONAL) {
                        $produto_pedido_pai->addObservacao('Com ' . $produto->getAbreviado());
                    } else {
                        $produto_pedido_pai->addObservacao('Sem ' . $produto->getAbreviado());
                    }
                } elseif ($produto_pai->getTipo() == Produto::TIPO_PACOTE) {
                    $formacao->setTipo(Formacao::TIPO_PACOTE);
                    $formacao->setPacoteID($subitem_id ?: $codigo_pdv);
                    $pacote = $formacao->findPacoteID();
                    if (!$pacote->exists()) {
                        throw new RedirectException(
                            sprintf(
                                'O item "%s" não foi associado corretamente no produto "%s"',
                                $descricao,
                                $produto_pai->getDescricao()
                            ),
                            404,
                            $app->makeURL('/gerenciar/produto/' . $this->integracao->getAcessoURL())
                        );
                    }
                    $produto_pedido->setProdutoID($pacote->getProdutoID());
                } else {
                    throw new RedirectException(
                        sprintf(
                            'O produto "%s" não é um pacote ou composição, não é possível adicionar "%s" nele',
                            $produto_pai->getDescricao(),
                            $descricao
                        ),
                        404,
                        $app->makeURL('/gerenciar/produto/' . $this->integracao->getAcessoURL())
                    );
                }
                if (!is_null($produto_pedido->getProdutoID())) {
                    // aqui o produto pai é um pacote e o item é um produto
                    $pacotes[$parent_index][] = $i;
                    $produto_pedido->setProdutoPedidoID($produto_pedido_pai->getID());
                    $this->products[$i] = [
                        'item' => $produto_pedido,
                        'formacoes' => []
                    ];
                    // permite adicionar a formação no próprio item mais abaixo
                    $parent_index = $i;
                    $i++;
                } else {
                    // Propriedade ou adicional, não adiciona na lista de itens
                    // a formação será adicionada no produto pai
                    $produto_pedido_pai->setPreco($produto_pedido_pai->getPreco() + $produto_pedido->getPreco());
                    $produto_pedido_pai->setPrecoVenda(
                        $produto_pedido_pai->getPrecoVenda() + $produto_pedido->getPrecoVenda()
                    );
                }
                $this->products[$parent_index]['formacoes'][] = $formacao;
            } else {
                throw new RedirectException(
                    'O produto principal do pacote não foi encontrado',
                    404,
                    $app->makeURL('/gerenciar/produto/' . $this->integracao->getAcessoURL())
                );
            }
        }
        // percorre todos os pacotes e corrige preços e quantidades
        foreach ($pacotes as $parent_index => $itens) {
            $pacote = $this->products[$parent_index];
            $montagem = new Montagem($pacote['item']);
            $montagem->initialize();
            $montagem->addItem($pacote['item'], $pacote['formacoes']);
            foreach ($itens as $index) {
                $item = $this->products[$index];
                $montagem->addItem($item['item'], $item['formacoes']);
            }
            $montagem->filter();
        }
        foreach ($servicos as $servico) {
            $this->products[$i] = [
                'item' => $servico,
                'formacoes' => []
            ];
            $i++;
        }
    }

    public function loadData($data)
    {
        $this->payments = [];
        $this->localization = null;
        $this->district = null;
        $this->city = null;
        $this->state = null;
        $this->country = null;
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
                'item' => $produto_pedido,
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

    private function registerAddress()
    {
        $this->country->loadByCodigo();
        $this->state->setPaisID($this->country->getID());
        $this->state->loadByPaisIDUF();
        if (!$this->state->exists()) {
            throw new \Exception(sprintf('O estado de UF "%s" não existe', $this->state->getUF()), 401);
        }
        $this->city->setEstadoID($this->state->getID());
        $find_city = new Cidade($this->city);
        $find_city->loadByEstadoIDNome();
        if (!$find_city->exists()) {
            $this->city->filter(new Cidade());
            $this->city->insert();
        } else {
            $this->city->fromArray($find_city->toArray());
        }
        $this->district->setCidadeID($this->city->getID());
        $find_district = new Bairro($this->district);
        $find_district->loadByCidadeIDNome();
        if (!$find_district->exists()) {
            foreach ($this->products as $item_info) {
                $produto_pedido = $item_info['item'];
                if ($produto_pedido->getServicoID() == Servico::ENTREGA_ID) {
                    $this->district->setValorEntrega($produto_pedido->getSubtotal());
                    break;
                }
            }
            $this->district->setDisponivel('Y');
            $this->district->filter(new Bairro());
            $this->district->insert();
        } else {
            $this->district->fromArray($find_district->toArray());
        }
        $this->localization->setClienteID($this->getClienteID());
        $find_localization = new Localizacao($this->localization);
        $find_localization->loadByCEP();
        if (!$find_localization->isSame($this->localization)) {
            $find_localization->fromArray($this->localization->toArray());
            $find_localization->loadByClienteID();
        }
        if ($find_localization->isSame($this->localization)) {
            $this->localization->fromArray($find_localization->toArray());
            return $this;
        }
        $this->localization->setBairroID($this->district->getID());
        $this->localization->insert();
    }

    private function insertProducts()
    {
        $added = 0;
        $pacotes = [];
        $comissao_balcao = is_boolean_config('Vendas', 'Balcao.Comissao');
        $pacote_pedido = new ProdutoPedido();
        foreach ($this->products as $index => $item_info) {
            $produto_pedido = $item_info['item'];
            $produto_pedido->setPedidoID($this->getID());
            $produto_pedido->setFuncionarioID($this->employee->getID());
            $produto_pedido->setPrecoCompra(0);
            $produto = $produto_pedido->findProdutoID();
            if ($produto->exists()) {
                // se chegou aqui é porque o item é um produto e não serviço
                if ($produto->isCobrarServico() &&
                    (
                        in_array($this->getTipo(), [self::TIPO_MESA, self::TIPO_COMANDA]) ||
                        ($comissao_balcao && $this->getTipo() == self::TIPO_AVULSO)
                    )
                ) {
                    $produto_pedido->setPorcentagem($this->employee->getPorcentagem());
                }
                if (!is_null($produto_pedido->getProdutoPedidoID())) {
                    $produto_pedido->setProdutoPedidoID($pacote_pedido->getID());
                    $pacotes[$pacote_pedido->getID()]['itens'][] = $item_info;
                } elseif ($produto->getTipo() != Produto::TIPO_PACOTE) {
                    $produto_pedido->setPreco($produto->getPrecoVenda());
                    $produto_pedido->setPrecoVenda($produto->getPrecoVenda());
                    $pacote_pedido = new ProdutoPedido();
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
                $pacote = $item_info;
                $pacote['itens'] = [];
                $pacotes[$pacote_pedido->getID()] = $pacote;
            }
            $added++;
        }
        // percorre todos os pacotes e valida a formação, lançando exceção quando conter erros
        foreach ($pacotes as $pacote) {
            $montagem = new Montagem($pacote['item']);
            $montagem->initialize();
            $montagem->addItem($pacote['item'], $pacote['formacoes']);
            $itens = $pacote['itens'];
            foreach ($itens as $item) {
                $montagem->addItem($item['item'], $item['formacoes']);
            }
            $montagem->validate();
        }
        return $added;
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
                if ($this->needMovimentacao()) {
                    // venda balcão e delivery precisa informar o caixa
                    $movimentacao = Movimentacao::findByAberta($this->employee->getID());
                    $this->setMovimentacaoID($movimentacao->getID());
                }
            }
            if (!is_null($this->customer) && !$this->customer->exists()) {
                // todo cliente precisa de uma senha, gera uma aleatória
                $this->customer->setSenha(Generator::token().'a123Z');
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
            $viagem = !$this->getLocalizacaoID();
            if (!is_null($this->localization) && !is_null($this->getClienteID())) {
                $this->registerAddress();
                $this->setLocalizacaoID($viagem ? null : $this->localization->getID());
            }
            $added = $this->insertProducts();
            if (!$viagem) {
                foreach ($this->payments as $index => $pagamento) {
                    $pagamento->setPedidoID($this->getID());
                    $pagamento->setFuncionarioID($this->employee->getID());
                    $pagamento->setMovimentacaoID($this->getMovimentacaoID());
                    $pagamento->insert();
                }
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
                    if (!$this->needMovimentacao()) {
                        $sync->printServices($this->getID());
                    }
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
        return $action;
    }

    /**
     * Store integrated order for post sync status
     * @return array changes to submit to the web API
     */
    public function store()
    {
        $change = ['id' => $this->getID(), 'code' => $this->code, 'estado' => $this->getEstado()];
        $dados = $this->integracao->read() ?: [];
        $pedidos = isset($dados['pedidos']) ? $dados['pedidos']: [];
        $pedidos[$this->getID()] = $change;
        $dados['pedidos'] = $pedidos;
        $this->integracao->write($dados);
        return [$change];
    }

    /**
     * Retrive orders changes to submit to web API
     * @param int $limit limit to get first changes
     * @return array changes to submit to the web API
     */
    public function changes($limit = null)
    {
        $dados = $this->integracao->read() ?: [];
        $pedidos = isset($dados['pedidos']) ? $dados['pedidos']: [];
        $changes = [];
        foreach ($pedidos as $pedido_id => $change) {
            $pedido = self::findByID($pedido_id);
            if (!$pedido->exists()) {
                continue;
            }
            if ($pedido->isCancelado()) {
                $change['estado'] = self::ESTADO_CANCELADO;
                $changes[] = $change;
            } elseif ($pedido->getEstado() != $change['estado']) {
                $change['estado'] = $pedido->getEstado();
                $changes[] = $change;
            }
            if (count($changes) >= $limit) {
                break;
            }
        }
        return $changes;
    }

    /**
     * Apply submited changes to web API to local storage
     * @param array $updates list of changes to apply
     * @return boolean true when any changes was applied
     */
    public function apply($updates)
    {
        $changes = 0;
        $dados = $this->integracao->read() ?: [];
        $pedidos = isset($dados['pedidos']) ? $dados['pedidos']: [];
        foreach ($updates as $update) {
            if (!isset($pedidos[$update['id']])) {
                continue;
            }
            $change = $pedidos[$update['id']];
            if ($update['estado'] == self::ESTADO_FINALIZADO ||
                $update['estado'] == self::ESTADO_CANCELADO
            ) {
                unset($pedidos[$update['id']]);
                $changes++;
                continue;
            }
            $change['estado'] = $update['estado'];
            $pedidos[$update['id']] = $change;
            $changes++;
        }
        if ($changes > 0) {
            $dados['pedidos'] = $pedidos;
            $this->integracao->write($dados);
        }
        return $changes;
    }
}
