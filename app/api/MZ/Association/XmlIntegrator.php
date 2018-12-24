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
use MZ\Database\SyncModel;
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
use MZ\Sale\Item;
use MZ\Sale\Formacao;
use MZ\Sale\Montagem;
use MZ\Product\Servico;
use MZ\Product\Produto;
use MZ\Product\Composicao;
use MZ\Session\Sessao;
use MZ\Session\Movimentacao;
use MZ\Exception\RedirectException;

class XmlIntegrator extends Integrator
{
    /**
     * Card names and codes
     */
    public $card_names;

    /**
     * @var \DOMDocument
     */
    public $dom;

    private function loadOrder($node)
    {
        $pedido = [];
        $obs = Document::childValue($node, 'obsPedido');
        // $obs = str_replace("\r", '', str_replace("\n", ', ', trim($obs)));
        $pedido['descricao'] = $obs;
        $data_criacao = Document::childValue($node, 'dataPedidoComanda');
        $pedido['datacriacao'] = Filter::datetime($data_criacao);
        $pedido['tipo'] = Pedido::TIPO_ENTREGA;
        $pedido['estado'] = Pedido::ESTADO_AGENDADO;
        $data_entrega = Document::childValue($node, 'dataEntrega');
        $pedido['dataagendamento'] = Filter::datetime($data_entrega);
        $entregar = Document::childValue($node, 'togo');
        if ($entregar == 'false') {
            $pedido['localizacaoid'] = 1;
        }
        return $pedido;
    }

    private function loadCustomer($node)
    {
        $cliente = [];
        $nome = Document::childValue($node, 'nome');
        $cliente_obj = new Cliente();
        $cliente_obj->setNomeCompleto(Filter::name($nome));
        $cliente['nome'] = $cliente_obj->getNome();
        $cliente['sobrenome'] = $cliente_obj->getSobrenome();
        $email = Document::childValue($node, 'email');
        $cliente['email'] = strtolower($email);
        $cliente['genero'] = Gender::detect($cliente_obj->getNome());
        $telefones_node = Document::findChild($node, 'telefones', false);
        if (!is_null($telefones_node)) {
            $telefone_list = $telefones_node->getElementsByTagName('telefone');
            foreach ($telefone_list as $telefone) {
                $ddd = Document::childValue($telefone, 'ddd');
                $numero = Document::childValue($telefone, 'numero');
                $cliente['fone1'] = $ddd . $numero;
                break;
            }
        }
        return $cliente;
    }

    private function loadAddress($node)
    {
        $localizacao = [];
        $localizacao['cep'] = Document::childValue($node, 'cep');
        $localizacao['tipo'] = Localizacao::TIPO_CASA;
        $localizacao['logradouro'] = Document::childValue($node, 'logradouro');
        $localizacao['numero'] = Document::childValue($node, 'logradouroNum');
        $localizacao['complemento'] = Document::childValue($node, 'complemento', false);
        $localizacao['referencia'] = Document::childValue($node, 'referencia', false);
        $localizacao['mostrar'] = 'Y';

        $bairro = [];
        $bairro['nome'] = Document::childValue($node, 'bairro');
        $bairro['valorentrega'] = floatval(Document::childValue($node, 'vlrTaxa'));

        $cidade = [];
        $cidade['nome'] = Document::childValue($node, 'cidade');

        $estado = [];
        $estado['uf'] = Document::childValue($node, 'estado');

        $pais = [];
        $pais['codigo'] = Document::childValue($node, 'pais');

        $estado['pais'] = $pais;
        $cidade['estado'] = $estado;
        $bairro['cidade'] = $cidade;
        $localizacao['bairro'] = $bairro;
        return $localizacao;
    }

    public function loadPayments($node)
    {
        $pagamentos = [];
        $servicos = [];
        $pagamentos_node = Document::findChild($node, 'pagamentos', false);
        if (!is_null($pagamentos_node)) {
            $pagamento_list = $pagamentos_node->getElementsByTagName('pagamento');
            foreach ($pagamento_list as $pagamento_node) {
                $cod_tipo_cond_pagto = Document::childValue($pagamento_node, 'codTipoCondPagto');
                if ($cod_tipo_cond_pagto == 'D') {
                    $item = [];
                    $item['servicoid'] = Servico::DESCONTO_ID;
                    $item['quantidade'] = 1;
                    $item['preco'] = -floatval(Document::childValue($pagamento_node, 'valor'));
                    $item['precovenda'] = 0.00;
                    $item['detalhes'] = 'Desconto no pedido';
                    $servicos[] = $item;
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
                                app()->makeURL('/gerenciar/cartao/' . $this->integracao->getAcessoURL())
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
                    $pagamento = [];
                    $pagamento['formapagtoid'] = $forma_pagto->getID();
                    $pagamento['total'] = floatval(Document::childValue($pagamento_node, 'valor'));
                    if ($pagamento['total'] > 0) {
                        $pagamento['carteiraid'] = $forma_pagto->getCarteiraID();
                    } else {
                        $pagamento['carteiraid'] = $forma_pagto->getCarteiraPagtoID();
                    }
                    $pagamento['ativo'] = 'N';
                    if ($forma_pagto->getTipo() == FormaPagto::TIPO_CARTAO) {
                        $cartao = new Cartao();
                        $cod_tipo_pagto = Document::childValue($pagamento_node, 'codFormaPagto');
                        if (!isset($this->card_names[$cod_tipo_pagto])) {
                            throw new RedirectException(
                                sprintf('O cartão de código "%s" não é suportado nessa versão', $cod_tipo_pagto),
                                500,
                                app()->makeURL('/gerenciar/cartao/' . $this->integracao->getAcessoURL())
                            );
                        }
                        if (!isset($cartoes[$cod_tipo_pagto])) {
                            throw new RedirectException(
                                sprintf('O cartão "%s" não foi associado', $this->card_names[$cod_tipo_pagto]['name']),
                                500,
                                app()->makeURL('/gerenciar/cartao/' . $this->integracao->getAcessoURL())
                            );
                        }
                        $pagamento['cartaoid'] = $cartoes[$cod_tipo_pagto];
                        $pagamento_obj = new Pagamento($pagamento);
                        $cartao = $pagamento_obj->findCartaoID();
                        if (!$cartao->exists()) {
                            throw new RedirectException(
                                sprintf(
                                    'A associação do cartão "%s" está inválida',
                                    $this->card_names[$cod_tipo_pagto]['name']
                                ),
                                500,
                                app()->makeURL('/gerenciar/cartao/' . $this->integracao->getAcessoURL())
                            );
                        }
                        $pagamento['parcelas'] = 1;
                        $pagamento['valorparcela'] = $pagamento['total'];
                        $pagamento['taxas'] = (
                            -1 * $pagamento['parcelas'] *
                            $pagamento['valorparcela'] * ($cartao->getTaxa() / 100.0) -
                            $cartao->getTransacao()
                        );
                        if ($pagamento['total'] > 0) {
                            $pagamento['carteiraid'] = $cartao->getCarteiraID() ?:
                                $forma_pagto->getCarteiraID();
                        } else {
                            $pagamento['carteiraid'] = $cartao->getCarteiraPagtoID() ?:
                                $forma_pagto->getCarteiraPagtoID();
                        }
                        if ($debito) {
                            $pagamento['detalhes'] = 'Cartão de débito';
                        }
                        $pagamento['datacompensacao'] = DB::now('+' . intval($cartao->getDiasRepasse()) . ' day');
                    } elseif ($forma_pagto->getTipo() != FormaPagto::TIPO_DINHEIRO) {
                        throw new \Exception('Apenas dinheiro e cartão são aceitos', 500);
                    }
                    $pagamentos[] = $pagamento;
                }
            }
        }
        $troco = floatval(Document::childValue($node, 'vlrTroco'));
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
            $pagamento = [];
            $pagamento['formapagtoid'] = $forma_pagto->getID();
            $pagamento['carteiraid'] = $forma_pagto->getCarteiraPagtoID();
            $pagamento['total'] = -$troco;
            $pagamento['detalhes'] = 'Troco';
            $pagamento['ativo'] = 'N';
            $pagamentos[] = $pagamento;
        }
        return ['pagamentos' => $pagamentos, 'servicos' => $servicos];
    }

    public function loadProducts($node)
    {
        $last_id = 0;
        $itens = [];
        $pacotes = [];
        $parent_itens = [];
        $itens_node = $node->getElementsByTagName('item');
        foreach ($itens_node as $item_node) {
            $codigo = Document::childValue($item_node, 'codCardapio');
            $codigo_pai = Document::childValue($item_node, 'codPai', false);
            $descricao = Document::childValue($item_node, 'descricaoCardapio');
            $codigo_pdv = Document::childValue($item_node, 'codProdutoPdv', false);
            $item = [];
            $item['id'] = $last_id;
            $item['quantidade'] = floatval(Document::childValue($item_node, 'quantidade'));
            $item['preco'] = floatval(Document::childValue($item_node, 'vlrUnitLiq'));
            $item['precovenda'] = floatval(Document::childValue($item_node, 'vlrUnitBruto'));
            $item['detalhes'] = Document::childValue($item_node, 'obsItem', false);
            if (is_null($codigo_pai)) {
                $produto_id = isset($produtos[$codigo]['id']) ? $produtos[$codigo]['id'] : null;
                $item['produtoid'] = $produto_id ?: $codigo_pdv;
                $item_obj = new Item($item);
                $produto = $item_obj->findProdutoID();
                if (!$produto->exists()) {
                    throw new RedirectException(
                        sprintf('O produto "%s" não foi associado corretamente', $descricao),
                        404,
                        app()->makeURL('/gerenciar/produto/' . $this->integracao->getAcessoURL())
                    );
                }
                if ($produto->getTipo() == Produto::TIPO_PACOTE) {
                    $pacotes[$last_id] = [];
                }
                $item['formacoes'] = [];
                $itens[$last_id] = $item;
                $parent_itens[$codigo] = $last_id;
                $last_id++;
            } elseif (array_key_exists($codigo_pai, $parent_itens)) {
                $subitem_id = isset($produtos[$codigo_pai]['itens'][$codigo]['id']) ?
                    $produtos[$codigo_pai]['itens'][$codigo]['id'] : null;
                $parent_id = $parent_itens[$codigo_pai];
                $item_pai = $itens[$parent_id];
                $item_pai_obj = new Item($item_pai);
                $produto_pai = $item_pai_obj->findProdutoID();
                $formacao = [];
                if ($produto_pai->getTipo() == Produto::TIPO_COMPOSICAO) {
                    $formacao['tipo'] = Formacao::TIPO_COMPOSICAO;
                    $formacao['composicaoid'] = $subitem_id ?: $codigo_pdv;
                    $formacao_obj = new Formacao($formacao);
                    $composicao = $formacao_obj->findComposicaoID();
                    if (!$composicao->exists()) {
                        throw new RedirectException(
                            sprintf(
                                'A composição "%s" não foi associada corretamente no produto "%s"',
                                $descricao,
                                $produto_pai->getDescricao()
                            ),
                            404,
                            app()->makeURL('/gerenciar/produto/' . $this->integracao->getAcessoURL())
                        );
                    }
                    $produto = $composicao->findProdutoID();
                    if ($composicao->getTipo() == Composicao::TIPO_ADICIONAL) {
                        $item_pai->addObservacao('Com ' . $produto->getAbreviado());
                    } else {
                        $item_pai->addObservacao('Sem ' . $produto->getAbreviado());
                    }
                } elseif ($produto_pai->getTipo() == Produto::TIPO_PACOTE) {
                    $formacao['tipo'] = Formacao::TIPO_PACOTE;
                    $formacao['pacoteid'] = $subitem_id ?: $codigo_pdv;
                    $formacao_obj = new Formacao($formacao);
                    $pacote = $formacao_obj->findPacoteID();
                    if (!$pacote->exists()) {
                        throw new RedirectException(
                            sprintf(
                                'O item "%s" não foi associado corretamente no produto "%s"',
                                $descricao,
                                $produto_pai->getDescricao()
                            ),
                            404,
                            app()->makeURL('/gerenciar/produto/' . $this->integracao->getAcessoURL())
                        );
                    }
                    $item['produtoid'] = $pacote->getProdutoID();
                } else {
                    throw new RedirectException(
                        sprintf(
                            'O produto "%s" não é um pacote ou composição, não é possível adicionar "%s" nele',
                            $produto_pai->getDescricao(),
                            $descricao
                        ),
                        404,
                        app()->makeURL('/gerenciar/produto/' . $this->integracao->getAcessoURL())
                    );
                }
                if (!is_null($item->getProdutoID())) {
                    // aqui o produto pai é um pacote e o item é um produto
                    $pacotes[$parent_id][] = $last_id;
                    $item['itemid'] = $item_pai->getID();
                    $item['formacoes'] = [];
                    $itens[$last_id] = $item;
                    // permite adicionar a formação no próprio item mais abaixo
                    $parent_id = $last_id;
                    $last_id++;
                } else {
                    // Propriedade ou adicional, não adiciona na lista de itens
                    // a formação será adicionada no produto pai
                    $item_pai['preco'] = $item_pai->getPreco() + $item->getPreco();
                    $item_pai['precovenda'] = (
                        $item_pai->getPrecoVenda() + $item->getPrecoVenda()
                    );
                }
                $itens[$parent_id]['formacoes'][] = $formacao;
            } else {
                throw new RedirectException(
                    'O produto principal do pacote não foi encontrado',
                    404,
                    app()->makeURL('/gerenciar/produto/' . $this->integracao->getAcessoURL())
                );
            }
        }
        // percorre todos os pacotes e corrige preços e quantidades
        foreach ($pacotes as $parent_id => $subitens) {
            $data = [];
            // item principal da montagem
            $item = $itens[$parent_id];
            $item_obj = new Item($item);
            $montagem = new Montagem($item_obj);
            $montagem->initialize();
            $formacoes = [];
            foreach ($item['formacoes'] as $formacao) {
                $formacoes[] = new Formacao($formacao);
            }
            $montagem->addItem($item_obj, $formacoes);
            $data[$parent_id] = ['item' => $item_obj, 'formacoes' => $formacoes];
            // adiciona os subitens do pacote para checkagem
            foreach ($subitens as $subitem_id) {
                $item = $itens[$subitem_id];
                $item_obj = new Item($item);
                $formacoes = [];
                foreach ($item['formacoes'] as $formacao) {
                    $formacoes[] = new Formacao($formacao);
                }
                $montagem->addItem($item_obj, $formacoes);
                $data[$subitem_id] = ['item' => $item_obj, 'formacoes' => $formacoes];
            }
            // ajusta as quantidade e preços
            $montagem->filter();
            // pega de volta as informações ajustadas
            foreach ($data as $update_id => $info) {
                $item = $itens[$update_id];
                $formacoes = [];
                foreach ($info['formacoes'] as $index => $formacao_obj) {
                    $formacoes[] = array_intersect_key($formacao_obj->toArray(), $item['formacoes'][$index]);
                }
                $item_obj = $info['item'];
                $new_item = array_merge($item_obj->toArray(), ['formacoes' => $formacoes]);
                $item = array_intersect_key($new_item, $item);
                $itens[$update_id] = $item;
            }
        }
        return ['itens' => $itens, 'last_id' => $last_id];
    }

    public function load()
    {
        $dom = $this->dom;
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
        $pedido = $this->loadOrder($body_pedido);
        $cliente = $this->loadCustomer($body_pedido);
        $localizacao = $this->loadAddress($body_pedido);
        $data = $this->loadPayments($body_pedido);
        $pagamentos = $data['pagamentos'];
        $servicos = $data['servicos'];
        $data = $this->loadProducts($body_list);
        $itens = $data['itens'];
        $last_id = $data['last_id'];
        $desconto = floatval(Document::childValue($body_pedido, 'vlrDesconto'));
        if ($desconto > 0) {
            $item = [];
            $item['servicoid'] = Servico::DESCONTO_ID;
            $item['quantidade'] = 1;
            $item['preco'] = -$desconto;
            $item['precovenda'] = 0.00;
            $item['detalhes'] = 'Desconto no pedido';
            $servicos[] = $item;
        }
        $taxa_entrega = floatval(Document::childValue($body_pedido, 'vlrTaxa'));
        if ($taxa_entrega > 0) {
            $item = [];
            $item['servicoid'] = Servico::ENTREGA_ID;
            $item['quantidade'] = 1;
            $item['preco'] = $taxa_entrega;
            $item['precovenda'] = 0.00;
            $item['detalhes'] = 'Taxa de entrega';
            $servicos[] = $item;
        }
        foreach ($servicos as $servico) {
            $servico['formacoes'] = [];
            $itens[$last_id] = $servico;
            $last_id++;
        }
        $cliente['localizacao'] = $localizacao;
        $pedido['cliente'] = $cliente;
        $pedido['itens'] = $itens;
        $pedido['pagamentos'] = $pagamentos;
        return $pedido;
    }
}
