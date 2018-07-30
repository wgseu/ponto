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

use MZ\Database\Model;
use MZ\Database\DB;
use MZ\Util\Filter;
use MZ\Util\Validator;
use MZ\Stock\Estoque;
use MZ\Product\Composicao;
use MZ\Exception\ValidationException;

/**
 * Produtos, taxas e serviços do pedido, a alteração do estado permite o
 * controle de produção
 */
class ProdutoPedido extends Model
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
     * Funcionário que lançou esse item no pedido
     */
    private $funcionario_id;
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
    private $produto_pedido_id;
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
     * Porcentagem cobrada sobre essa venda, escala de 0 a 100
     */
    private $porcentagem;
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
     * Informa se o item foi visualizado por alguém
     */
    private $visualizado;
    /**
     * Data de visualização do item
     */
    private $data_visualizacao;
    /**
     * Data de atualização do estado do item
     */
    private $data_atualizacao;
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
     * Data e hora da realização do pedido do item
     */
    private $data_hora;

    /**
     * Constructor for a new empty instance of ProdutoPedido
     * @param array $produto_pedido All field and values to fill the instance
     */
    public function __construct($produto_pedido = [])
    {
        parent::__construct($produto_pedido);
    }

    /**
     * Identificador do item do pedido
     * @return mixed ID of ProdutoPedido
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param  mixed $id new value for ID
     * @return ProdutoPedido Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Pedido a qual pertence esse item
     * @return mixed Pedido of ProdutoPedido
     */
    public function getPedidoID()
    {
        return $this->pedido_id;
    }

    /**
     * Set PedidoID value to new on param
     * @param  mixed $pedido_id new value for PedidoID
     * @return ProdutoPedido Self instance
     */
    public function setPedidoID($pedido_id)
    {
        $this->pedido_id = $pedido_id;
        return $this;
    }

    /**
     * Funcionário que lançou esse item no pedido
     * @return mixed Funcionário of ProdutoPedido
     */
    public function getFuncionarioID()
    {
        return $this->funcionario_id;
    }

    /**
     * Set FuncionarioID value to new on param
     * @param  mixed $funcionario_id new value for FuncionarioID
     * @return ProdutoPedido Self instance
     */
    public function setFuncionarioID($funcionario_id)
    {
        $this->funcionario_id = $funcionario_id;
        return $this;
    }

    /**
     * Produto vendido
     * @return mixed Produto of ProdutoPedido
     */
    public function getProdutoID()
    {
        return $this->produto_id;
    }

    /**
     * Set ProdutoID value to new on param
     * @param  mixed $produto_id new value for ProdutoID
     * @return ProdutoPedido Self instance
     */
    public function setProdutoID($produto_id)
    {
        $this->produto_id = $produto_id;
        return $this;
    }

    /**
     * Serviço cobrado ou taxa
     * @return mixed Serviço of ProdutoPedido
     */
    public function getServicoID()
    {
        return $this->servico_id;
    }

    /**
     * Set ServicoID value to new on param
     * @param  mixed $servico_id new value for ServicoID
     * @return ProdutoPedido Self instance
     */
    public function setServicoID($servico_id)
    {
        $this->servico_id = $servico_id;
        return $this;
    }

    /**
     * Pacote em que esse item faz parte
     * @return mixed Pacote of ProdutoPedido
     */
    public function getProdutoPedidoID()
    {
        return $this->produto_pedido_id;
    }

    /**
     * Set ProdutoPedidoID value to new on param
     * @param  mixed $produto_pedido_id new value for ProdutoPedidoID
     * @return ProdutoPedido Self instance
     */
    public function setProdutoPedidoID($produto_pedido_id)
    {
        $this->produto_pedido_id = $produto_pedido_id;
        return $this;
    }

    /**
     * Sobrescreve a descrição do produto na exibição
     * @return mixed Descrição of ProdutoPedido
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Set Descricao value to new on param
     * @param  mixed $descricao new value for Descricao
     * @return ProdutoPedido Self instance
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * Preço do produto já com desconto
     * @return mixed Preço of ProdutoPedido
     */
    public function getPreco()
    {
        return $this->preco;
    }

    /**
     * Set Preco value to new on param
     * @param  mixed $preco new value for Preco
     * @return ProdutoPedido Self instance
     */
    public function setPreco($preco)
    {
        $this->preco = $preco;
        return $this;
    }

    /**
     * Quantidade de itens vendidos
     * @return mixed Quantidade of ProdutoPedido
     */
    public function getQuantidade()
    {
        return $this->quantidade;
    }

    /**
     * Set Quantidade value to new on param
     * @param  mixed $quantidade new value for Quantidade
     * @return ProdutoPedido Self instance
     */
    public function setQuantidade($quantidade)
    {
        $this->quantidade = $quantidade;
        return $this;
    }

    /**
     * Porcentagem cobrada sobre essa venda, escala de 0 a 100
     * @return mixed Porcentagem of ProdutoPedido
     */
    public function getPorcentagem()
    {
        return $this->porcentagem;
    }

    /**
     * Set Porcentagem value to new on param
     * @param  mixed $porcentagem new value for Porcentagem
     * @return ProdutoPedido Self instance
     */
    public function setPorcentagem($porcentagem)
    {
        $this->porcentagem = $porcentagem;
        return $this;
    }

    /**
     * Preço de normal do produto no momento da venda
     * @return mixed Preço de venda of ProdutoPedido
     */
    public function getPrecoVenda()
    {
        return $this->preco_venda;
    }

    /**
     * Set PrecoVenda value to new on param
     * @param  mixed $preco_venda new value for PrecoVenda
     * @return ProdutoPedido Self instance
     */
    public function setPrecoVenda($preco_venda)
    {
        $this->preco_venda = $preco_venda;
        return $this;
    }

    /**
     * Preço de compra do produto calculado automaticamente na hora da venda
     * @return mixed Preço de compra of ProdutoPedido
     */
    public function getPrecoCompra()
    {
        return $this->preco_compra;
    }

    /**
     * Set PrecoCompra value to new on param
     * @param  mixed $preco_compra new value for PrecoCompra
     * @return ProdutoPedido Self instance
     */
    public function setPrecoCompra($preco_compra)
    {
        $this->preco_compra = $preco_compra;
        return $this;
    }

    /**
     * Observações do item pedido, Ex.: bem gelado, mal passado
     * @return mixed Observações of ProdutoPedido
     */
    public function getDetalhes()
    {
        return $this->detalhes;
    }

    /**
     * Set Detalhes value to new on param
     * @param  mixed $detalhes new value for Detalhes
     * @return ProdutoPedido Self instance
     */
    public function setDetalhes($detalhes)
    {
        $this->detalhes = $detalhes;
        return $this;
    }

    /**
     * Estado de preparo e envio do produto
     * @return mixed Estado of ProdutoPedido
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set Estado value to new on param
     * @param  mixed $estado new value for Estado
     * @return ProdutoPedido Self instance
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
        return $this;
    }

    /**
     * Informa se o item foi visualizado por alguém
     * @return mixed Visualizado of ProdutoPedido
     */
    public function getVisualizado()
    {
        return $this->visualizado;
    }

    /**
     * Informa se o item foi visualizado por alguém
     * @return boolean Check if o of Visualizado is selected or checked
     */
    public function isVisualizado()
    {
        return $this->visualizado == 'Y';
    }

    /**
     * Set Visualizado value to new on param
     * @param  mixed $visualizado new value for Visualizado
     * @return ProdutoPedido Self instance
     */
    public function setVisualizado($visualizado)
    {
        $this->visualizado = $visualizado;
        return $this;
    }

    /**
     * Data de visualização do item
     * @return mixed Data de visualização of ProdutoPedido
     */
    public function getDataVisualizacao()
    {
        return $this->data_visualizacao;
    }

    /**
     * Set DataVisualizacao value to new on param
     * @param  mixed $data_visualizacao new value for DataVisualizacao
     * @return ProdutoPedido Self instance
     */
    public function setDataVisualizacao($data_visualizacao)
    {
        $this->data_visualizacao = $data_visualizacao;
        return $this;
    }

    /**
     * Data de atualização do estado do item
     * @return mixed Data de atualização of ProdutoPedido
     */
    public function getDataAtualizacao()
    {
        return $this->data_atualizacao;
    }

    /**
     * Set DataAtualizacao value to new on param
     * @param  mixed $data_atualizacao new value for DataAtualizacao
     * @return ProdutoPedido Self instance
     */
    public function setDataAtualizacao($data_atualizacao)
    {
        $this->data_atualizacao = $data_atualizacao;
        return $this;
    }

    /**
     * Informa se o item foi cancelado
     * @return mixed Cancelado of ProdutoPedido
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
     * @param  mixed $cancelado new value for Cancelado
     * @return ProdutoPedido Self instance
     */
    public function setCancelado($cancelado)
    {
        $this->cancelado = $cancelado;
        return $this;
    }

    /**
     * Informa o motivo do item ser cancelado
     * @return mixed Motivo of ProdutoPedido
     */
    public function getMotivo()
    {
        return $this->motivo;
    }

    /**
     * Set Motivo value to new on param
     * @param  mixed $motivo new value for Motivo
     * @return ProdutoPedido Self instance
     */
    public function setMotivo($motivo)
    {
        $this->motivo = $motivo;
        return $this;
    }

    /**
     * Informa se o item foi cancelado por conta de desperdício
     * @return mixed Desperdiçado of ProdutoPedido
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
     * @param  mixed $desperdicado new value for Desperdicado
     * @return ProdutoPedido Self instance
     */
    public function setDesperdicado($desperdicado)
    {
        $this->desperdicado = $desperdicado;
        return $this;
    }

    /**
     * Data e hora da realização do pedido do item
     * @return mixed Data e hora of ProdutoPedido
     */
    public function getDataHora()
    {
        return $this->data_hora;
    }

    /**
     * Set DataHora value to new on param
     * @param  mixed $data_hora new value for DataHora
     * @return ProdutoPedido Self instance
     */
    public function setDataHora($data_hora)
    {
        $this->data_hora = $data_hora;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param  boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $produto_pedido = parent::toArray($recursive);
        $produto_pedido['id'] = $this->getID();
        $produto_pedido['pedidoid'] = $this->getPedidoID();
        $produto_pedido['funcionarioid'] = $this->getFuncionarioID();
        $produto_pedido['produtoid'] = $this->getProdutoID();
        $produto_pedido['servicoid'] = $this->getServicoID();
        $produto_pedido['produtopedidoid'] = $this->getProdutoPedidoID();
        $produto_pedido['descricao'] = $this->getDescricao();
        $produto_pedido['preco'] = $this->getPreco();
        $produto_pedido['quantidade'] = $this->getQuantidade();
        $produto_pedido['porcentagem'] = $this->getPorcentagem();
        $produto_pedido['precovenda'] = $this->getPrecoVenda();
        $produto_pedido['precocompra'] = $this->getPrecoCompra();
        $produto_pedido['detalhes'] = $this->getDetalhes();
        $produto_pedido['estado'] = $this->getEstado();
        $produto_pedido['visualizado'] = $this->getVisualizado();
        $produto_pedido['datavisualizacao'] = $this->getDataVisualizacao();
        $produto_pedido['dataatualizacao'] = $this->getDataAtualizacao();
        $produto_pedido['cancelado'] = $this->getCancelado();
        $produto_pedido['motivo'] = $this->getMotivo();
        $produto_pedido['desperdicado'] = $this->getDesperdicado();
        $produto_pedido['datahora'] = $this->getDataHora();
        return $produto_pedido;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param  mixed $produto_pedido Associated key -> value to assign into this instance
     * @return ProdutoPedido Self instance
     */
    public function fromArray($produto_pedido = [])
    {
        if ($produto_pedido instanceof ProdutoPedido) {
            $produto_pedido = $produto_pedido->toArray();
        } elseif (!is_array($produto_pedido)) {
            $produto_pedido = [];
        }
        parent::fromArray($produto_pedido);
        if (!isset($produto_pedido['id'])) {
            $this->setID(null);
        } else {
            $this->setID($produto_pedido['id']);
        }
        if (!isset($produto_pedido['pedidoid'])) {
            $this->setPedidoID(null);
        } else {
            $this->setPedidoID($produto_pedido['pedidoid']);
        }
        if (!isset($produto_pedido['funcionarioid'])) {
            $this->setFuncionarioID(null);
        } else {
            $this->setFuncionarioID($produto_pedido['funcionarioid']);
        }
        if (!array_key_exists('produtoid', $produto_pedido)) {
            $this->setProdutoID(null);
        } else {
            $this->setProdutoID($produto_pedido['produtoid']);
        }
        if (!array_key_exists('servicoid', $produto_pedido)) {
            $this->setServicoID(null);
        } else {
            $this->setServicoID($produto_pedido['servicoid']);
        }
        if (!array_key_exists('produtopedidoid', $produto_pedido)) {
            $this->setProdutoPedidoID(null);
        } else {
            $this->setProdutoPedidoID($produto_pedido['produtopedidoid']);
        }
        if (!array_key_exists('descricao', $produto_pedido)) {
            $this->setDescricao(null);
        } else {
            $this->setDescricao($produto_pedido['descricao']);
        }
        if (!isset($produto_pedido['preco'])) {
            $this->setPreco(null);
        } else {
            $this->setPreco($produto_pedido['preco']);
        }
        if (!isset($produto_pedido['quantidade'])) {
            $this->setQuantidade(null);
        } else {
            $this->setQuantidade($produto_pedido['quantidade']);
        }
        if (!isset($produto_pedido['porcentagem'])) {
            $this->setPorcentagem(0);
        } else {
            $this->setPorcentagem($produto_pedido['porcentagem']);
        }
        if (!isset($produto_pedido['precovenda'])) {
            $this->setPrecoVenda(null);
        } else {
            $this->setPrecoVenda($produto_pedido['precovenda']);
        }
        if (!isset($produto_pedido['precocompra'])) {
            $this->setPrecoCompra(0);
        } else {
            $this->setPrecoCompra($produto_pedido['precocompra']);
        }
        if (!array_key_exists('detalhes', $produto_pedido)) {
            $this->setDetalhes(null);
        } else {
            $this->setDetalhes($produto_pedido['detalhes']);
        }
        if (!isset($produto_pedido['estado'])) {
            $this->setEstado(self::ESTADO_ADICIONADO);
        } else {
            $this->setEstado($produto_pedido['estado']);
        }
        if (!isset($produto_pedido['visualizado'])) {
            $this->setVisualizado('N');
        } else {
            $this->setVisualizado($produto_pedido['visualizado']);
        }
        if (!array_key_exists('datavisualizacao', $produto_pedido)) {
            $this->setDataVisualizacao(null);
        } else {
            $this->setDataVisualizacao($produto_pedido['datavisualizacao']);
        }
        if (!array_key_exists('dataatualizacao', $produto_pedido)) {
            $this->setDataAtualizacao(DB::now());
        } else {
            $this->setDataAtualizacao($produto_pedido['dataatualizacao']);
        }
        if (!isset($produto_pedido['cancelado'])) {
            $this->setCancelado('N');
        } else {
            $this->setCancelado($produto_pedido['cancelado']);
        }
        if (!array_key_exists('motivo', $produto_pedido)) {
            $this->setMotivo(null);
        } else {
            $this->setMotivo($produto_pedido['motivo']);
        }
        if (!isset($produto_pedido['desperdicado'])) {
            $this->setDesperdicado('N');
        } else {
            $this->setDesperdicado($produto_pedido['desperdicado']);
        }
        if (!isset($produto_pedido['datahora'])) {
            $this->setDataHora(DB::now());
        } else {
            $this->setDataHora($produto_pedido['datahora']);
        }
        return $this;
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $produto_pedido = parent::publish();
        return $produto_pedido;
    }

    public function getSubvenda()
    {
        return $this->getPrecoVenda() * $this->getQuantidade();
    }

    public function getSubtotal()
    {
        return $this->getPreco() * $this->getQuantidade();
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

    public function getComissao()
    {
        return $this->getSubtotal() * $this->getPorcentagem() / 100.0;
    }

    public function getTotal()
    {
        return $this->getSubtotal() + $this->getComissao();
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
     * @param ProdutoPedido $obs Texto da observação
     * @return ProdutoPedido Self instance
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
     * Filter fields, upload data and keep key data
     * @param ProdutoPedido $original Original instance without modifications
     */
    public function filter($original)
    {
        $this->setID($original->getID());
        $this->setPedidoID(Filter::number($this->getPedidoID()));
        $this->setFuncionarioID(Filter::number($this->getFuncionarioID()));
        $this->setProdutoID(Filter::number($this->getProdutoID()));
        $this->setServicoID(Filter::number($this->getServicoID()));
        $this->setProdutoPedidoID(Filter::number($this->getProdutoPedidoID()));
        $this->setDescricao(Filter::string($this->getDescricao()));
        $this->setPreco(Filter::money($this->getPreco()));
        $this->setQuantidade(Filter::float($this->getQuantidade()));
        $this->setPorcentagem(Filter::float($this->getPorcentagem()));
        $this->setPrecoVenda(Filter::money($this->getPrecoVenda()));
        $this->setPrecoCompra(Filter::money($this->getPrecoCompra()));
        $this->setDetalhes(Filter::string($this->getDetalhes()));
        $this->setDataVisualizacao(Filter::datetime($this->getDataVisualizacao()));
        $this->setDataAtualizacao(DB::now());
        $this->setMotivo(Filter::string($this->getMotivo()));
        $this->setDataHora(DB::now());
    }

    /**
     * Clean instance resources like images and docs
     * @param  ProdutoPedido $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of ProdutoPedido in array format
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getPedidoID())) {
            $errors['pedidoid'] = 'O pedido não pode ser vazio';
        }
        if (is_null($this->getFuncionarioID())) {
            $errors['funcionarioid'] = 'O funcionário não pode ser vazio';
        }
        if (is_null($this->getPreco())) {
            $errors['preco'] = 'O preço não pode ser vazio';
        }
        if (is_null($this->getQuantidade())) {
            $errors['quantidade'] = 'A quantidade não pode ser vazia';
        } elseif ($this->getQuantidade() > 10000) {
            $errors['quantidade'] = 'Quantidade muito elevada, faça multiplos lançamentos menores';
        }
        if (is_null($this->getPorcentagem())) {
            $errors['porcentagem'] = 'A porcentagem não pode ser vazia';
        }
        if (is_null($this->getPrecoVenda())) {
            $errors['precovenda'] = 'O preço de venda não pode ser vazio';
        }
        if (is_null($this->getPrecoCompra())) {
            $errors['precocompra'] = 'O preço de compra não pode ser vazio';
        }
        if (!Validator::checkInSet($this->getEstado(), self::getEstadoOptions())) {
            $errors['estado'] = 'O estado do item não foi informado ou é inválido';
        }
        if (!Validator::checkBoolean($this->getVisualizado())) {
            $errors['visualizado'] = 'A informação de visualização não foi informada ou é inválida';
        }
        if (!Validator::checkBoolean($this->getCancelado())) {
            $errors['cancelado'] = 'O cancelamento não foi informado ou é inválido';
        }
        if (!Validator::checkBoolean($this->getDesperdicado(), true)) {
            $errors['desperdicado'] = 'O desperdício não foi informado ou é inválido';
        }
        $this->setDataAtualizacao(DB::now());
        $this->setDataHora(DB::now());
        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
        return $this->toArray();
    }

    /**
     * Translate SQL exception into application exception
     * @param  \Exception $e exception to translate into a readable error
     * @return ValidationException new exception translated
     */
    protected function translate($e)
    {
        return parent::translate($e);
    }

    /**
     * Insert a new Item do pedido into the database and fill instance from database
     * @return ProdutoPedido Self instance
     */
    public function insert()
    {
        $this->setID(null);
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = DB::insertInto('Produtos_Pedidos')->values($values)->execute();
            $this->loadByID($id);
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Item do pedido with instance values into database for ID
     * @return ProdutoPedido Self instance
     */
    public function update($only = [], $except = false)
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador do item do pedido não foi informado');
        }
        $values = DB::filterValues($values, $only, $except);
        unset($values['datahora']);
        try {
            DB::update('Produtos_Pedidos')
                ->set($values)
                ->where('id', $this->getID())
                ->execute();
            $this->loadByID($this->getID());
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
            throw new \Exception('O identificador do item do pedido não foi informado');
        }
        $result = DB::deleteFrom('Produtos_Pedidos')
            ->where('id', $this->getID())
            ->execute();
        return $result;
    }

    public function register($formacoes)
    {
        try {
            if (is_null($this->getProdutoPedidoID())) {
                // aplica o desconto dos opcionais e acrescenta o valor dos adicionais
                // apenas nas composições fora de pacotes
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
                    $this->setPrecoVenda($this->getPrecoVenda() + $operacao * $composicao->getValor());
                    $this->setPreco($this->getPreco() + $operacao * $composicao->getValor());
                }
            }
            $this->insert();
            if (is_null($this->getProdutoID())) {
                return $this;
            }
            $composicoes = [];
            foreach ($formacoes as $formacao) {
                $formacao->setProdutoPedidoID($this->getID());
                $formacao->filter(new Formacao());
                $formacao->insert();
                if ($formacao->getTipo() == Formacao::TIPO_COMPOSICAO) {
                    $composicoes[$formacao->getComposicaoID()] = $formacao->getID();
                }
            }
            $estoque = new Estoque();
            $estoque->setTransacaoID($this->getID());
            $estoque->setProdutoID($this->getProdutoID());
            $estoque->setFuncionarioID($this->getFuncionarioID());
            $estoque->setQuantidade($this->getQuantidade());
            $estoque->retirar($composicoes);
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Load one register for it self with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order associative field name -> [-1, 1]
     * @return ProdutoPedido Self instance filled or empty
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
     * Funcionário que lançou esse item no pedido
     * @return \MZ\Employee\Funcionario The object fetched from database
     */
    public function findFuncionarioID()
    {
        return \MZ\Employee\Funcionario::findByID($this->getFuncionarioID());
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
     * @return \MZ\Sale\ProdutoPedido The object fetched from database
     */
    public function findProdutoPedidoID()
    {
        if (is_null($this->getProdutoPedidoID())) {
            return new \MZ\Sale\ProdutoPedido();
        }
        return \MZ\Sale\ProdutoPedido::findByID($this->getProdutoPedidoID());
    }

    /**
     * Gets textual and translated Estado for ProdutoPedido
     * @param  int $index choose option from index
     * @return mixed A associative key -> translated representative text or text for index
     */
    public static function getEstadoOptions($index = null)
    {
        $options = [
            self::ESTADO_ADICIONADO => 'Adicionado',
            self::ESTADO_ENVIADO => 'Enviado',
            self::ESTADO_PROCESSADO => 'Processado',
            self::ESTADO_PRONTO => 'Pronto',
            self::ESTADO_DISPONIVEL => 'Disponível',
            self::ESTADO_ENTREGUE => 'Entregue',
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
        $produto_pedido = new ProdutoPedido();
        $allowed = Filter::concatKeys('p.', $produto_pedido->toArray());
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
        return Filter::orderBy($order, $allowed, 'p.');
    }

    /**
     * Filter condition array with allowed fields
     * @param  array $condition condition to filter rows
     * @return array allowed condition
     */
    private static function filterCondition($condition)
    {
        $allowed = self::getAllowedKeys();
        if (array_key_exists('!produtoid', $condition)) {
            $field = 'NOT p.produtoid';
            $condition[$field] = $condition['!produtoid'];
            $allowed[$field] = true;
        }
        if (array_key_exists('!servicoid', $condition)) {
            $field = 'NOT p.servicoid';
            $condition[$field] = $condition['!servicoid'];
            $allowed[$field] = true;
        }
        if (isset($condition['apartir_preco'])) {
            $field = 'p.preco >= ?';
            $condition[$field] = $condition['apartir_preco'];
            $allowed[$field] = true;
        }
        if (isset($condition['ate_preco'])) {
            $field = 'p.preco < ?';
            $condition[$field] = $condition['ate_preco'];
            $allowed[$field] = true;
        }
        if (isset($condition['apartir_datahora'])) {
            $field = 'p.datahora >= ?';
            $condition[$field] = Filter::datetime($condition['apartir_datahora'], '00:00:00');
            $allowed[$field] = true;
        }
        if (isset($condition['ate_datahora'])) {
            $field = 'p.datahora <= ?';
            $condition[$field] = Filter::datetime($condition['ate_datahora'], '23:59:59');
            $allowed[$field] = true;
        }
        if (isset($condition['search'])) {
            $search = trim($condition['search']);
            if (Validator::checkDigits($search)) {
                $condition['pedidoid'] = Filter::number($search);
            } else {
                $field = 'p.detalhes LIKE ?';
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
        if (isset($condition['cancelamento'])) {
            $field = 'e.cancelado';
            $condition[$field] = $condition['cancelamento'];
        }
        if (array_key_exists('!status', $condition)) {
            $field = 'NOT e.estado';
            $condition[$field] = $condition['!status'];
            $allowed[$field] = true;
        }
        $prefix = ['p.', 'e.', 'd.', 's.'];
        $allowed['d.tipo'] = true;
        $allowed['s.tipo'] = true;
        $allowed['e.tipo'] = true;
        $allowed['e.sessaoid'] = true;
        $allowed['e.cancelado'] = true;
        $allowed['e.movimentacaoid'] = true;
        return Filter::keys($condition, $allowed, $prefix);
    }

    /**
     * Fetch data from database with a condition
     * @param  array $condition condition to filter rows
     * @param  array $order order rows
     * @param  array $select select fields, empty to all fields
     * @param  array $group group rows
     * @return SelectQuery query object with condition statement
     */
    private static function query($condition = [], $order = [], $select = [], $group = [])
    {
        $query = DB::from('Produtos_Pedidos p')
            ->leftJoin('Pedidos e ON e.id = p.pedidoid')
            ->leftJoin('Produtos d ON d.id = p.produtoid')
            ->leftJoin('Servicos s ON s.id = p.servicoid');

        $detalhado = isset($condition['detalhado']);
        $categorizado = isset($condition['categorizado']);
        if ($detalhado) {
            $query = $query->select(null)
                ->select('IF(COUNT(p.id) = 1, p.id, 0) as id')
                ->select('p.pedidoid')
                ->select('p.funcionarioid')
                ->select('p.produtoid')
                ->select('p.servicoid')
                ->select('p.produtopedidoid')
                ->select('p.descricao')
                ->select('p.preco')
                ->select('SUM(p.quantidade) as quantidade')
                ->select('p.porcentagem')
                ->select('p.precovenda')
                ->select('p.precocompra')
                ->select('p.detalhes')
                ->select('p.estado')
                ->select('p.visualizado')
                ->select('p.datavisualizacao')
                ->select('p.dataatualizacao')
                ->select('p.cancelado')
                ->select('p.motivo')
                ->select('p.desperdicado')
                ->select('p.datahora')

                ->select('l.login as funcionariologin')
                ->select('COALESCE(s.descricao, COALESCE(p.descricao, d.descricao)) as produtodescricao')
                ->select('d.dataatualizacao as produtodataatualizacao')
                ->select('e.tipo as pedidotipo')
                ->select('d.tipo as produtotipo')
                ->select('d.conteudo as produtoconteudo')
                ->select('u.sigla as unidadesigla')
                ->select('e.mesaid')
                ->select('e.comandaid')
                ->select(
                    '(CASE WHEN d.imagem IS NULL THEN NULL ELSE '.
                    DB::concat(['d.id', '".png"']).
                    ' END) as imagemurl'
                )
                ->select('m.nome as mesanome')
                ->select('c.nome as comandanome')

                ->leftJoin('Unidades u ON u.id = d.unidadeid')
                ->leftJoin('Funcionarios f ON f.id = p.funcionarioid')
                ->leftJoin('Clientes l ON l.id = f.clienteid')
                ->leftJoin('Mesas m ON m.id = e.mesaid')
                ->leftJoin('Comandas c ON c.id = e.comandaid');
        }
        if ($categorizado) {
            $query = $query->select(null)
                ->select('SUM(p.preco * p.quantidade) as total')
                ->select('COALESCE(t.descricao, ?) as descricao', 'Taxas e Serviços')
                ->leftJoin('Categorias t ON t.id = d.categoriaid')
                ->groupBy('d.categoriaid')
                ->orderBy('total DESC');
        }
        $condition = self::filterCondition($condition);
        $query = DB::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('p.datahora DESC');
        $query = $query->orderBy('p.id DESC');
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
     * @param  array $condition Condition for searching the row
     * @param  array $order associative field name -> [-1, 1]
     * @param  array $select select fields, empty to all fields
     * @param  array $group group rows
     * @return ProdutoPedido A filled Item do pedido or empty instance
     */
    public static function find($condition, $order = [], $select = [], $group = [])
    {
        $query = self::query($condition, $order, $select, $group)->limit(1);
        $row = $query->fetch() ?: [];
        return new ProdutoPedido($row);
    }

    /**
     * Find all Item do pedido
     * @param  array  $condition Condition to get all Item do pedido
     * @param  array  $order     Order Item do pedido
     * @param  int    $limit     Limit data into row count
     * @param  int    $offset    Start offset to get rows
     * @param  array  $select select fields, empty to all fields
     * @param  array  $group group rows
     * @return array  List of all rows instanced as ProdutoPedido
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
            $result[] = new ProdutoPedido($row);
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
     * @param  array $condition condition to filter rows
     * @return integer Quantity of rows
     */
    public static function count($condition = [])
    {
        $query = self::query($condition);
        return $query->count();
    }
}
