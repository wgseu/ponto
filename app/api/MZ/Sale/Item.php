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
namespace MZ\Sale;

use MZ\Util\Mask;
use MZ\Util\Filter;
use MZ\Util\Validator;
use MZ\Database\DB;
use MZ\Database\SyncModel;
use MZ\Exception\ValidationException;
use MZ\Stock\Estoque;
use MZ\Product\Composicao;

/**
 * Produtos, taxas e serviços do pedido, a alteração do estado permite o
 * controle de produção
 */
class Item extends SyncModel
{

    /**
     * Estado de preparo e envio do produto
     */
    const ESTADO_ADICIONADO = 'Adicionado';
    const ESTADO_ENVIADO = 'Enviado';
    const ESTADO_PROCESSADO = 'Processado';
    const ESTADO_PRONTO = 'Pronto';
    const ESTADO_DISPONIVEL = 'Disponivel';
    const ESTADO_ENTREGUE = 'Entregue';

    /**
     * Identificador do item do pedido
     */
    private $id;
    /**
     * Pedido a qual pertence esse item
     */
    private $pedido_id;
    /**
     * Prestador que lançou esse item no pedido
     */
    private $prestador_id;
    /**
     * Produto vendido
     */
    private $produto_id;
    /**
     * Serviço cobrado ou taxa
     */
    private $servico_id;
    /**
     * Pacote em que esse item faz parte
     */
    private $item_id;
    /**
     * Informa se esse item foi pago e qual foi o lançamento
     */
    private $pagamento_id;
    /**
     * Sobrescreve a descrição do produto na exibição
     */
    private $descricao;
    /**
     * Preço do produto já com desconto
     */
    private $preco;
    /**
     * Quantidade de itens vendidos
     */
    private $quantidade;
    /**
     * Subtotal do item sem comissão
     */
    private $subtotal;
    /**
     * Valor total de comissão cobrada nesse item da venda
     */
    private $comissao;
    /**
     * Total a pagar do item com a comissão
     */
    private $total;
    /**
     * Preço de normal do produto no momento da venda
     */
    private $preco_venda;
    /**
     * Preço de compra do produto calculado automaticamente na hora da venda
     */
    private $preco_compra;
    /**
     * Observações do item pedido, Ex.: bem gelado, mal passado
     */
    private $detalhes;
    /**
     * Estado de preparo e envio do produto
     */
    private $estado;
    /**
     * Informa se o item foi cancelado
     */
    private $cancelado;
    /**
     * Informa o motivo do item ser cancelado
     */
    private $motivo;
    /**
     * Informa se o item foi cancelado por conta de desperdício
     */
    private $desperdicado;
    /**
     * Informa se os produtos foram retirados do estoque para produção
     */
    private $reservado;
    /**
     * Data de visualização do item
     */
    private $data_visualizacao;
    /**
     * Data de atualização do estado do item
     */
    private $data_atualizacao;
    /**
     * Data e hora da realização do pedido do item
     */
    private $data_lancamento;

    /**
     * Constructor for a new empty instance of Item
     * @param array $item All field and values to fill the instance
     */
    public function __construct($item = [])
    {
        parent::__construct($item);
    }

    /**
     * Identificador do item do pedido
     * @return int id of Item do pedido
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param int $id Set id for Item do pedido
     * @return self Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Pedido a qual pertence esse item
     * @return int pedido of Item do pedido
     */
    public function getPedidoID()
    {
        return $this->pedido_id;
    }

    /**
     * Set PedidoID value to new on param
     * @param int $pedido_id Set pedido for Item do pedido
     * @return self Self instance
     */
    public function setPedidoID($pedido_id)
    {
        $this->pedido_id = $pedido_id;
        return $this;
    }

    /**
     * Prestador que lançou esse item no pedido
     * @return int prestador of Item do pedido
     */
    public function getPrestadorID()
    {
        return $this->prestador_id;
    }

    /**
     * Set PrestadorID value to new on param
     * @param int $prestador_id Set prestador for Item do pedido
     * @return self Self instance
     */
    public function setPrestadorID($prestador_id)
    {
        $this->prestador_id = $prestador_id;
        return $this;
    }

    /**
     * Produto vendido
     * @return int produto of Item do pedido
     */
    public function getProdutoID()
    {
        return $this->produto_id;
    }

    /**
     * Set ProdutoID value to new on param
     * @param int $produto_id Set produto for Item do pedido
     * @return self Self instance
     */
    public function setProdutoID($produto_id)
    {
        $this->produto_id = $produto_id;
        return $this;
    }

    /**
     * Serviço cobrado ou taxa
     * @return int serviço of Item do pedido
     */
    public function getServicoID()
    {
        return $this->servico_id;
    }

    /**
     * Set ServicoID value to new on param
     * @param int $servico_id Set serviço for Item do pedido
     * @return self Self instance
     */
    public function setServicoID($servico_id)
    {
        $this->servico_id = $servico_id;
        return $this;
    }

    /**
     * Pacote em que esse item faz parte
     * @return int pacote of Item do pedido
     */
    public function getItemID()
    {
        return $this->item_id;
    }

    /**
     * Set ItemID value to new on param
     * @param int $item_id Set pacote for Item do pedido
     * @return self Self instance
     */
    public function setItemID($item_id)
    {
        $this->item_id = $item_id;
        return $this;
    }

    /**
     * Informa se esse item foi pago e qual foi o lançamento
     * @return int pagamento of Item do pedido
     */
    public function getPagamentoID()
    {
        return $this->pagamento_id;
    }

    /**
     * Set PagamentoID value to new on param
     * @param int $pagamento_id Set pagamento for Item do pedido
     * @return self Self instance
     */
    public function setPagamentoID($pagamento_id)
    {
        $this->pagamento_id = $pagamento_id;
        return $this;
    }

    /**
     * Sobrescreve a descrição do produto na exibição
     * @return string descrição of Item do pedido
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Set Descricao value to new on param
     * @param string $descricao Set descrição for Item do pedido
     * @return self Self instance
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * Preço do produto já com desconto
     * @return string preço of Item do pedido
     */
    public function getPreco()
    {
        return $this->preco;
    }

    /**
     * Set Preco value to new on param
     * @param string $preco Set preço for Item do pedido
     * @return self Self instance
     */
    public function setPreco($preco)
    {
        $this->preco = $preco;
        return $this;
    }

    /**
     * Quantidade de itens vendidos
     * @return float quantidade of Item do pedido
     */
    public function getQuantidade()
    {
        return $this->quantidade;
    }

    /**
     * Set Quantidade value to new on param
     * @param float $quantidade Set quantidade for Item do pedido
     * @return self Self instance
     */
    public function setQuantidade($quantidade)
    {
        $this->quantidade = $quantidade;
        return $this;
    }

    /**
     * Subtotal do item sem comissão
     * @return string subtotal of Item do pedido
     */
    public function getSubtotal()
    {
        return $this->subtotal;
    }

    /**
     * Set Subtotal value to new on param
     * @param string $subtotal Set subtotal for Item do pedido
     * @return self Self instance
     */
    public function setSubtotal($subtotal)
    {
        $this->subtotal = $subtotal;
        return $this;
    }

    /**
     * Valor total de comissão cobrada nesse item da venda
     * @return string porcentagem of Item do pedido
     */
    public function getComissao()
    {
        return $this->comissao;
    }

    /**
     * Set Comissao value to new on param
     * @param string $comissao Set porcentagem for Item do pedido
     * @return self Self instance
     */
    public function setComissao($comissao)
    {
        $this->comissao = $comissao;
        return $this;
    }

    /**
     * Total a pagar do item com a comissão
     * @return string total of Item do pedido
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set Total value to new on param
     * @param string $total Set total for Item do pedido
     * @return self Self instance
     */
    public function setTotal($total)
    {
        $this->total = $total;
        return $this;
    }

    /**
     * Preço de normal do produto no momento da venda
     * @return string preço de venda of Item do pedido
     */
    public function getPrecoVenda()
    {
        return $this->preco_venda;
    }

    /**
     * Set PrecoVenda value to new on param
     * @param string $preco_venda Set preço de venda for Item do pedido
     * @return self Self instance
     */
    public function setPrecoVenda($preco_venda)
    {
        $this->preco_venda = $preco_venda;
        return $this;
    }

    /**
     * Preço de compra do produto calculado automaticamente na hora da venda
     * @return string preço de compra of Item do pedido
     */
    public function getPrecoCompra()
    {
        return $this->preco_compra;
    }

    /**
     * Set PrecoCompra value to new on param
     * @param string $preco_compra Set preço de compra for Item do pedido
     * @return self Self instance
     */
    public function setPrecoCompra($preco_compra)
    {
        $this->preco_compra = $preco_compra;
        return $this;
    }

    /**
     * Observações do item pedido, Ex.: bem gelado, mal passado
     * @return string observações of Item do pedido
     */
    public function getDetalhes()
    {
        return $this->detalhes;
    }

    /**
     * Set Detalhes value to new on param
     * @param string $detalhes Set observações for Item do pedido
     * @return self Self instance
     */
    public function setDetalhes($detalhes)
    {
        $this->detalhes = $detalhes;
        return $this;
    }

    /**
     * Estado de preparo e envio do produto
     * @return string estado of Item do pedido
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set Estado value to new on param
     * @param string $estado Set estado for Item do pedido
     * @return self Self instance
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
        return $this;
    }

    /**
     * Informa se o item foi cancelado
     * @return string cancelado of Item do pedido
     */
    public function getCancelado()
    {
        return $this->cancelado;
    }

    /**
     * Informa se o item foi cancelado
     * @return boolean Check if o of Cancelado is selected or checked
     */
    public function isCancelado()
    {
        return $this->cancelado == 'Y';
    }

    /**
     * Set Cancelado value to new on param
     * @param string $cancelado Set cancelado for Item do pedido
     * @return self Self instance
     */
    public function setCancelado($cancelado)
    {
        $this->cancelado = $cancelado;
        return $this;
    }

    /**
     * Informa o motivo do item ser cancelado
     * @return string motivo of Item do pedido
     */
    public function getMotivo()
    {
        return $this->motivo;
    }

    /**
     * Set Motivo value to new on param
     * @param string $motivo Set motivo for Item do pedido
     * @return self Self instance
     */
    public function setMotivo($motivo)
    {
        $this->motivo = $motivo;
        return $this;
    }

    /**
     * Informa se o item foi cancelado por conta de desperdício
     * @return string desperdiçado of Item do pedido
     */
    public function getDesperdicado()
    {
        return $this->desperdicado;
    }

    /**
     * Informa se o item foi cancelado por conta de desperdício
     * @return boolean Check if o of Desperdicado is selected or checked
     */
    public function isDesperdicado()
    {
        return $this->desperdicado == 'Y';
    }

    /**
     * Set Desperdicado value to new on param
     * @param string $desperdicado Set desperdiçado for Item do pedido
     * @return self Self instance
     */
    public function setDesperdicado($desperdicado)
    {
        $this->desperdicado = $desperdicado;
        return $this;
    }

    /**
     * Informa se os produtos foram retirados do estoque para produção
     * @return string reservado of Item do pedido
     */
    public function getReservado()
    {
        return $this->reservado;
    }

    /**
     * Informa se os produtos foram retirados do estoque para produção
     * @return boolean Check if o of Reservado is selected or checked
     */
    public function isReservado()
    {
        return $this->reservado == 'Y';
    }

    /**
     * Set Reservado value to new on param
     * @param string $reservado Set reservado for Item do pedido
     * @return self Self instance
     */
    public function setReservado($reservado)
    {
        $this->reservado = $reservado;
        return $this;
    }

    /**
     * Data de visualização do item
     * @return string data de visualização of Item do pedido
     */
    public function getDataVisualizacao()
    {
        return $this->data_visualizacao;
    }

    /**
     * Set DataVisualizacao value to new on param
     * @param string $data_visualizacao Set data de visualização for Item do pedido
     * @return self Self instance
     */
    public function setDataVisualizacao($data_visualizacao)
    {
        $this->data_visualizacao = $data_visualizacao;
        return $this;
    }

    /**
     * Data de atualização do estado do item
     * @return string data de atualização of Item do pedido
     */
    public function getDataAtualizacao()
    {
        return $this->data_atualizacao;
    }

    /**
     * Set DataAtualizacao value to new on param
     * @param string $data_atualizacao Set data de atualização for Item do pedido
     * @return self Self instance
     */
    public function setDataAtualizacao($data_atualizacao)
    {
        $this->data_atualizacao = $data_atualizacao;
        return $this;
    }

    /**
     * Data e hora da realização do pedido do item
     * @return string data de lançamento of Item do pedido
     */
    public function getDataLancamento()
    {
        return $this->data_lancamento;
    }

    /**
     * Set DataLancamento value to new on param
     * @param string $data_lancamento Set data de lançamento for Item do pedido
     * @return self Self instance
     */
    public function setDataLancamento($data_lancamento)
    {
        $this->data_lancamento = $data_lancamento;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $item = parent::toArray($recursive);
        $item['id'] = $this->getID();
        $item['pedidoid'] = $this->getPedidoID();
        $item['prestadorid'] = $this->getPrestadorID();
        $item['produtoid'] = $this->getProdutoID();
        $item['servicoid'] = $this->getServicoID();
        $item['itemid'] = $this->getItemID();
        $item['pagamentoid'] = $this->getPagamentoID();
        $item['descricao'] = $this->getDescricao();
        $item['preco'] = $this->getPreco();
        $item['quantidade'] = $this->getQuantidade();
        $item['subtotal'] = $this->getSubtotal();
        $item['comissao'] = $this->getComissao();
        $item['total'] = $this->getTotal();
        $item['precovenda'] = $this->getPrecoVenda();
        $item['precocompra'] = $this->getPrecoCompra();
        $item['detalhes'] = $this->getDetalhes();
        $item['estado'] = $this->getEstado();
        $item['cancelado'] = $this->getCancelado();
        $item['motivo'] = $this->getMotivo();
        $item['desperdicado'] = $this->getDesperdicado();
        $item['reservado'] = $this->getReservado();
        $item['datavisualizacao'] = $this->getDataVisualizacao();
        $item['dataatualizacao'] = $this->getDataAtualizacao();
        $item['datalancamento'] = $this->getDataLancamento();
        return $item;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param mixed $item Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($item = [])
    {
        if ($item instanceof self) {
            $item = $item->toArray();
        } elseif (!is_array($item)) {
            $item = [];
        }
        parent::fromArray($item);
        if (!isset($item['id'])) {
            $this->setID(null);
        } else {
            $this->setID($item['id']);
        }
        if (!isset($item['pedidoid'])) {
            $this->setPedidoID(null);
        } else {
            $this->setPedidoID($item['pedidoid']);
        }
        if (!isset($item['prestadorid'])) {
            $this->setPrestadorID(null);
        } else {
            $this->setPrestadorID($item['prestadorid']);
        }
        if (!array_key_exists('produtoid', $item)) {
            $this->setProdutoID(null);
        } else {
            $this->setProdutoID($item['produtoid']);
        }
        if (!array_key_exists('servicoid', $item)) {
            $this->setServicoID(null);
        } else {
            $this->setServicoID($item['servicoid']);
        }
        if (!array_key_exists('itemid', $item)) {
            $this->setItemID(null);
        } else {
            $this->setItemID($item['itemid']);
        }
        if (!array_key_exists('pagamentoid', $item)) {
            $this->setPagamentoID(null);
        } else {
            $this->setPagamentoID($item['pagamentoid']);
        }
        if (!array_key_exists('descricao', $item)) {
            $this->setDescricao(null);
        } else {
            $this->setDescricao($item['descricao']);
        }
        if (!isset($item['preco'])) {
            $this->setPreco(null);
        } else {
            $this->setPreco($item['preco']);
        }
        if (!isset($item['quantidade'])) {
            $this->setQuantidade(null);
        } else {
            $this->setQuantidade($item['quantidade']);
        }
        if (!isset($item['subtotal'])) {
            $this->setSubtotal(null);
        } else {
            $this->setSubtotal($item['subtotal']);
        }
        if (!isset($item['comissao'])) {
            $this->setComissao(0);
        } else {
            $this->setComissao($item['comissao']);
        }
        if (!isset($item['total'])) {
            $this->setTotal(null);
        } else {
            $this->setTotal($item['total']);
        }
        if (!isset($item['precovenda'])) {
            $this->setPrecoVenda(null);
        } else {
            $this->setPrecoVenda($item['precovenda']);
        }
        if (!isset($item['precocompra'])) {
            $this->setPrecoCompra(0);
        } else {
            $this->setPrecoCompra($item['precocompra']);
        }
        if (!array_key_exists('detalhes', $item)) {
            $this->setDetalhes(null);
        } else {
            $this->setDetalhes($item['detalhes']);
        }
        if (!isset($item['estado'])) {
            $this->setEstado(null);
        } else {
            $this->setEstado($item['estado']);
        }
        if (!isset($item['cancelado'])) {
            $this->setCancelado('N');
        } else {
            $this->setCancelado($item['cancelado']);
        }
        if (!array_key_exists('motivo', $item)) {
            $this->setMotivo(null);
        } else {
            $this->setMotivo($item['motivo']);
        }
        if (!isset($item['desperdicado'])) {
            $this->setDesperdicado('N');
        } else {
            $this->setDesperdicado($item['desperdicado']);
        }
        if (!isset($item['reservado'])) {
            $this->setReservado('N');
        } else {
            $this->setReservado($item['reservado']);
        }
        if (!array_key_exists('datavisualizacao', $item)) {
            $this->setDataVisualizacao(null);
        } else {
            $this->setDataVisualizacao($item['datavisualizacao']);
        }
        if (!array_key_exists('dataatualizacao', $item)) {
            $this->setDataAtualizacao(DB::now());
        } else {
            $this->setDataAtualizacao($item['dataatualizacao']);
        }
        if (!isset($item['datalancamento'])) {
            $this->setDataLancamento(DB::now());
        } else {
            $this->setDataLancamento($item['datalancamento']);
        }
        return $this;
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $item = parent::publish();
        return $item;
    }

    public function getSubvenda()
    {
        return $this->getPrecoVenda() * $this->getQuantidade();
    }

    /**
     * Obtém o desconto de uma unidade
     * @return float valor de desconto unitário
     */
    public function getDesconto()
    {
        return $this->getPrecoVenda() - $this->getPreco();
    }

    /**
     * Obtém o desconto desse lançamento, inclui todas as quantidades
     * @return float desconto geral do lançamento
     */
    public function getDescontos()
    {
        return $this->getSubvenda() - $this->getSubtotal();
    }

    public function getCusto()
    {
        return $this->getQuantidade() * $this->getPrecoCompra();
    }

    public function getLucro()
    {
        return $this->getSubtotal() - $this->getCusto();
    }

    public function isServico()
    {
        return !is_null($this->getServicoID()) && is_greater($this->getPreco(), 0.00);
    }

    /**
     * @param \MZ\Product\Produto $produto
     * @param \MZ\Product\Unidade $unidade
     */
    public function getQuantidadeFormatada($produto = null, $unidade = null)
    {
        if (!is_null($this->getServicoID())) {
            return quantify($this->getQuantidade());
        }
        $produto = !is_null($produto) ? $produto : $this->findProdutoID();
        $unidade = !is_null($unidade) ? $unidade : $produto->findUnidadeID();
        return quantify($this->getQuantidade(), $unidade->getSigla(), $produto->getConteudo());
    }

    /**
     * Retorna a descrição dinâmica do item, utilizada em pacotes com propriedades
     * @param \MZ\Product\Produto $produto Produto pré-carregado do item
     * @param \MZ\Product\Servico $servico Serviço pré-carregado do item
     */
    public function getDescricaoAtual($produto = null, $servico = null)
    {
        if (!is_null($this->getProdutoID())) {
            if (!is_null($this->getDescricao())) {
                return $this->getDescricao();
            }
            $produto = !is_null($produto) ? $produto : $this->findProdutoID();
            return $produto->getDescricao();
        }
        $servico = !is_null($servico) ? $servico : $this->findServicoID();
        return $servico->getDescricao();
    }

    /**
     * Adiciona observação na frente dos detalhes
     * @param Item $obs Texto da observação
     * @return Item Self instance
     */
    public function addObservacao($obs)
    {
        if (trim($this->getDetalhes()) == '') {
            $this->setDetalhes($obs);
        } else {
            $this->setDetalhes($obs . ', ' . $this->getDetalhes());
        }
        return $this;
    }

    /**
     * Update total and subtotal based on quantity, price and commission
     * @return Item Self instance
     */
    public function totalize()
    {
        $this->setSubtotal(Filter::money($this->getPreco() * $this->getQuantidade(), false));
        $this->setTotal(Filter::money($this->getSubtotal() + $this->getComissao(), false));
        return $this;
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
        $this->setPedidoID(Filter::number($this->getPedidoID()));
        $this->setPrestadorID(Filter::number($this->getPrestadorID()));
        $this->setProdutoID(Filter::number($this->getProdutoID()));
        $this->setServicoID(Filter::number($this->getServicoID()));
        $this->setItemID(Filter::number($this->getItemID()));
        $this->setPagamentoID(Filter::number($this->getPagamentoID()));
        $this->setDescricao(Filter::string($this->getDescricao()));
        $this->setPreco(Filter::money($this->getPreco(), $localized));
        $this->setQuantidade(Filter::float($this->getQuantidade(), $localized));
        $this->setComissao(Filter::money($this->getComissao(), $localized));
        $this->setPrecoVenda(Filter::money($this->getPrecoVenda(), $localized));
        $this->setPrecoCompra(Filter::money($this->getPrecoCompra(), $localized));
        $this->setDetalhes(Filter::string($this->getDetalhes()));
        $this->setMotivo(Filter::string($this->getMotivo()));
        $this->setDataVisualizacao(Filter::datetime($this->getDataVisualizacao()));
        return $this;
    }

    /**
     * Clean instance resources like images and docs
     * @param self $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Item in array format
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getPedidoID())) {
            $errors['pedidoid'] = _t('item.pedido_id_cannot_empty');
        }
        if (is_null($this->getPrestadorID())) {
            $errors['prestadorid'] = _t('item.prestador_id_cannot_empty');
        }
        if (is_null($this->getPreco())) {
            $errors['preco'] = _t('item.preco_cannot_empty');
        }
        if (is_null($this->getQuantidade())) {
            $errors['quantidade'] = _t('item.quantidade_cannot_empty');
        } elseif ($this->getQuantidade() > 10000) {
            $errors['quantidade'] = 'Quantidade muito elevada, faça multiplos lançamentos menores';
        }
        if (is_null($this->getSubtotal())) {
            $errors['subtotal'] = _t('item.subtotal_cannot_empty');
        }
        if (is_null($this->getComissao())) {
            $errors['comissao'] = _t('item.comissao_cannot_empty');
        }
        if (is_null($this->getTotal())) {
            $errors['total'] = _t('item.total_cannot_empty');
        }
        if (is_null($this->getPrecoVenda())) {
            $errors['precovenda'] = _t('item.preco_venda_cannot_empty');
        }
        if (is_null($this->getPrecoCompra())) {
            $errors['precocompra'] = _t('item.preco_compra_cannot_empty');
        }
        if (!Validator::checkInSet($this->getEstado(), self::getEstadoOptions())) {
            $errors['estado'] = _t('item.estado_invalid');
        }
        if (!Validator::checkBoolean($this->getCancelado())) {
            $errors['cancelado'] = _t('item.cancelado_invalid');
        }
        if (!Validator::checkBoolean($this->getDesperdicado())) {
            $errors['desperdicado'] = _t('item.desperdicado_invalid');
        }
        if (!Validator::checkBoolean($this->getReservado())) {
            $errors['reservado'] = _t('item.reservado_invalid');
        }
        $this->setDataAtualizacao(DB::now());
        $this->setDataLancamento(DB::now());
        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
        return $this->toArray();
    }

    /**
     * Insert a new Item do pedido into the database and fill instance from database
     * @return self Self instance
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function insert()
    {
        $this->setID(null);
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = DB::insertInto('Itens')->values($values)->execute();
            $this->setID($id);
            $this->loadByID();
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Item do pedido with instance values into database for ID
     * @param array $only Save these fields only, when empty save all fields except id
     * @return int rows affected
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function update($only = [])
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new ValidationException(
                ['id' => _t('item.id_cannot_empty')]
            );
        }
        $values = DB::filterValues($values, $only, false);
        unset($values['datalancamento']);
        try {
            $affected = DB::update('Itens')
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
                ['id' => _t('item.id_cannot_empty')]
            );
        }
        $result = DB::deleteFrom('Itens')
            ->where('id', $this->getID())
            ->execute();
        return $result;
    }

    private function checkFormation($formacoes)
    {
        if (is_null($this->getItemID())) {
            // aplica o desconto dos opcionais e acrescenta o valor dos adicionais
            // apenas nas composições fora de pacotes
            $produto = $this->findProdutoID();
            $preco = $produto->getPrecoVenda();
            foreach ($formacoes as $formacao) {
                if ($formacao->getTipo() != Formacao::TIPO_COMPOSICAO) {
                    continue;
                }
                $composicao = $formacao->findComposicaoID();
                if (!$composicao->exists()) {
                    throw new ValidationException(['formacao' => 'A composição formada não existe']);
                }
                $operacao = -1;
                if ($composicao->getTipo() == Composicao::TIPO_ADICIONAL) {
                    $operacao = 1;
                }
                $preco += $operacao * $composicao->getValor();
            }
            if (!is_equal($this->getPreco(), $preco)) {
                throw new \Exception(
                    sprintf(
                        'O preço do item "%s" deveria ser %s em vez de %s',
                        $produto->getDescricao(),
                        Mask::money($preco, true),
                        Mask::money($this->getPreco(), true)
                    ),
                    401
                );
            }
        }
    }

    public function register($formacoes)
    {
        try {
            $this->checkFormation($formacoes);
            $this->insert();
            if (is_null($this->getProdutoID())) {
                return $this;
            }
            $composicoes = [];
            foreach ($formacoes as $formacao) {
                $formacao->setItemID($this->getID());
                $formacao->filter(new Formacao());
                $formacao->insert();
                if ($formacao->getTipo() == Formacao::TIPO_COMPOSICAO) {
                    $composicoes[$formacao->getComposicaoID()] = $formacao->getID();
                }
            }
            $estoque = new Estoque();
            $estoque->setTransacaoID($this->getID());
            $estoque->setProdutoID($this->getProdutoID());
            $estoque->setPrestadorID($this->getPrestadorID());
            $estoque->setQuantidade($this->getQuantidade());
            $estoque->retirar($composicoes);
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
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
     * Pedido a qual pertence esse item
     * @return \MZ\Sale\Pedido The object fetched from database
     */
    public function findPedidoID()
    {
        return \MZ\Sale\Pedido::findByID($this->getPedidoID());
    }

    /**
     * Prestador que lançou esse item no pedido
     * @return \MZ\Provider\Prestador The object fetched from database
     */
    public function findPrestadorID()
    {
        return \MZ\Provider\Prestador::findByID($this->getPrestadorID());
    }

    /**
     * Produto vendido
     * @return \MZ\Product\Produto The object fetched from database
     */
    public function findProdutoID()
    {
        if (is_null($this->getProdutoID())) {
            return new \MZ\Product\Produto();
        }
        return \MZ\Product\Produto::findByID($this->getProdutoID());
    }

    /**
     * Serviço cobrado ou taxa
     * @return \MZ\Product\Servico The object fetched from database
     */
    public function findServicoID()
    {
        if (is_null($this->getServicoID())) {
            return new \MZ\Product\Servico();
        }
        return \MZ\Product\Servico::findByID($this->getServicoID());
    }

    /**
     * Pacote em que esse item faz parte
     * @return \MZ\Sale\Item The object fetched from database
     */
    public function findItemID()
    {
        if (is_null($this->getItemID())) {
            return new \MZ\Sale\Item();
        }
        return \MZ\Sale\Item::findByID($this->getItemID());
    }

    /**
     * Informa se esse item foi pago e qual foi o lançamento
     * @return \MZ\Payment\Pagamento The object fetched from database
     */
    public function findPagamentoID()
    {
        if (is_null($this->getPagamentoID())) {
            return new \MZ\Payment\Pagamento();
        }
        return \MZ\Payment\Pagamento::findByID($this->getPagamentoID());
    }

    /**
     * Gets textual and translated Estado for Item
     * @param int $index choose option from index
     * @return string[] A associative key -> translated representative text or text for index
     */
    public static function getEstadoOptions($index = null)
    {
        $options = [
            self::ESTADO_ADICIONADO => _t('item.estado_adicionado'),
            self::ESTADO_ENVIADO => _t('item.estado_enviado'),
            self::ESTADO_PROCESSADO => _t('item.estado_processado'),
            self::ESTADO_PRONTO => _t('item.estado_pronto'),
            self::ESTADO_DISPONIVEL => _t('item.estado_disponivel'),
            self::ESTADO_ENTREGUE => _t('item.estado_entregue'),
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
        $item = new self();
        $allowed = Filter::concatKeys('i.', $item->toArray());
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
        return Filter::orderBy($order, $allowed, 'i.');
    }

    /**
     * Filter condition array with allowed fields
     * @param array $condition condition to filter rows
     * @return array allowed condition
     */
    protected static function filterCondition($condition)
    {
        $allowed = self::getAllowedKeys();
        if (array_key_exists('!produtoid', $condition)) {
            $field = 'NOT i.produtoid';
            $condition[$field] = $condition['!produtoid'];
            $allowed[$field] = true;
        }
        if (array_key_exists('!servicoid', $condition)) {
            $field = 'NOT i.servicoid';
            $condition[$field] = $condition['!servicoid'];
            $allowed[$field] = true;
        }
        if (isset($condition['apartir_preco'])) {
            $field = 'i.preco >= ?';
            $condition[$field] = $condition['apartir_preco'];
            $allowed[$field] = true;
        }
        if (isset($condition['ate_preco'])) {
            $field = 'i.preco < ?';
            $condition[$field] = $condition['ate_preco'];
            $allowed[$field] = true;
        }
        if (isset($condition['apartir_datalancamento'])) {
            $field = 'i.datalancamento >= ?';
            $condition[$field] = Filter::datetime($condition['apartir_datalancamento'], '00:00:00');
            $allowed[$field] = true;
        }
        if (isset($condition['ate_datalancamento'])) {
            $field = 'i.datalancamento <= ?';
            $condition[$field] = Filter::datetime($condition['ate_datalancamento'], '23:59:59');
            $allowed[$field] = true;
        }
        if (isset($condition['search'])) {
            $search = trim($condition['search']);
            if (Validator::checkDigits($search)) {
                $condition['pedidoid'] = Filter::number($search);
            } else {
                $field = 'i.detalhes LIKE ?';
                $condition[$field] = '%'.$search.'%';
                $allowed[$field] = true;
            }
        }
        if (isset($condition['modulo'])) {
            $field = 'e.tipo';
            $condition[$field] = $condition['modulo'];
        }
        if (isset($condition['servico'])) {
            $field = 's.tipo';
            $condition[$field] = $condition['servico'];
        }
        if (isset($condition['produto'])) {
            $field = 'd.tipo';
            $condition[$field] = $condition['produto'];
        }
        $prefix = ['i.', 'e.', 'd.', 's.'];
        $allowed['d.tipo'] = true;
        $allowed['s.tipo'] = true;
        $allowed['e.tipo'] = true;
        $allowed['e.sessaoid'] = true;
        $allowed['e.movimentacaoid'] = true;
        return Filter::keys($condition, $allowed, $prefix);
    }

    /**
     * Fetch data from database with a condition
     * @param array $condition condition to filter rows
     * @param array $order order rows
     * @param  array $select select fields, empty to all fields
     * @param  array $group group rows
     * @return SelectQuery query object with condition statement
     */
    protected static function query($condition = [], $order = [], $select = [], $group = [])
    {
        $query = DB::from('Itens i')
            ->leftJoin('Pedidos e ON e.id = i.pedidoid')
            ->leftJoin('Produtos d ON d.id = i.produtoid')
            ->leftJoin('Servicos s ON s.id = i.servicoid');

        $detalhado = isset($condition['detalhado']);
        $categorizado = isset($condition['categorizado']);
        if ($detalhado) {
            $query = $query->select(null)
                ->select('IF(COUNT(i.id) = 1, i.id, 0) as id')
                ->select('i.pedidoid')
                ->select('i.prestadorid')
                ->select('i.produtoid')
                ->select('i.servicoid')
                ->select('i.itemid')
                ->select('i.pagamentoid')
                ->select('i.descricao')
                ->select('i.preco')
                ->select('SUM(i.quantidade) as quantidade')
                ->select('i.subtotal')
                ->select('i.comissao')
                ->select('i.total')
                ->select('i.precovenda')
                ->select('i.precocompra')
                ->select('i.detalhes')
                ->select('i.estado')
                ->select('i.cancelado')
                ->select('i.motivo')
                ->select('i.desperdicado')
                ->select('i.reservado')
                ->select('i.datavisualizacao')
                ->select('i.dataatualizacao')
                ->select('i.datalancamento')

                ->select('l.login as prestadorlogin')
                ->select('COALESCE(s.descricao, i.descricao, d.descricao) as produtodescricao')
                ->select('COALESCE(s.nome, d.abreviacao) as produtoabreviacao')
                ->select('d.dataatualizacao as produtodataatualizacao')
                ->select('e.tipo as pedidotipo')
                ->select('d.tipo as produtotipo')
                ->select('d.conteudo as produtoconteudo')
                ->select('u.sigla as unidadesigla')
                ->select('e.mesaid')
                ->select('e.comandaid')
                ->select('d.imagemurl')
                ->select('m.nome as mesanome')
                ->select('c.nome as comandanome')

                ->leftJoin('Unidades u ON u.id = d.unidadeid')
                ->leftJoin('Prestadores r ON r.id = i.prestadorid')
                ->leftJoin('Clientes l ON l.id = r.clienteid')
                ->leftJoin('Mesas m ON m.id = e.mesaid')
                ->leftJoin('Comandas c ON c.id = e.comandaid');
        }
        if ($categorizado) {
            $query = $query->select(null)
                ->select('SUM(i.subtotal) as total')
                ->select('COALESCE(t.descricao, ?) as descricao', 'Taxas e Serviços')
                ->leftJoin('Categorias t ON t.id = d.categoriaid')
                ->groupBy('d.categoriaid')
                ->orderBy('total DESC');
        }
        $condition = self::filterCondition($condition);
        $query = DB::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('i.datalancamento DESC');
        $query = $query->orderBy('i.id DESC');
        foreach ($select as $value) {
            $query = $query->select($value);
        }
        foreach ($group as $value) {
            $query = $query->groupBy($value);
        }
        return DB::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param array $condition Condition for searching the row
     * @param array $order order rows
     * @return self A filled Item do pedido or empty instance
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
     * @return self A filled Item do pedido or empty instance
     * @throws \Exception when register has not found
     */
    public static function findOrFail($condition, $order = [])
    {
        $result = self::find($condition, $order);
        if (!$result->exists()) {
            throw new \Exception(_t('item.not_found'), 404);
        }
        return $result;
    }

    /**
     * Find all Item do pedido
     * @param array  $condition Condition to get all Item do pedido
     * @param array  $order     Order Item do pedido
     * @param int    $limit     Limit data into row count
     * @param int    $offset    Start offset to get rows
     * @param array  $select select fields, empty to all fields
     * @param array  $group group rows
     * @return self[] List of all rows instanced as Item
     */
    public static function findAll(
        $condition = [],
        $order = [],
        $limit = null,
        $offset = null,
        $select = [],
        $group = []
    ) {
        $query = self::query($condition, $order, $select, $group);
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
     * Find all Item do pedido
     * @param  array  $condition Condition to get all Item do pedido
     * @param  array  $order     Order Item do pedido
     * @param  int    $limit     Limit data into row count
     * @param  int    $offset    Start offset to get rows
     * @param  array  $select select fields, empty to all fields
     * @param  array  $group group rows
     * @return array  List of all rows
     */
    public static function rawFindAll(
        $condition = [],
        $order = [],
        $limit = null,
        $offset = null,
        $select = [],
        $group = []
    ) {
        $query = self::query($condition, $order, $select, $group);
        if (!is_null($limit)) {
            $query = $query->limit($limit);
        }
        if (!is_null($offset)) {
            $query = $query->offset($offset);
        }
        return $query->fetchAll();
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
