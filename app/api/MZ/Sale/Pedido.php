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
use MZ\System\Permissao;
use MZ\Payment\Pagamento;

/**
 * Informações do pedido de venda
 */
class Pedido extends SyncModel
{

    /**
     * Tipo de venda
     */
    const TIPO_MESA = 'Mesa';
    const TIPO_COMANDA = 'Comanda';
    const TIPO_AVULSO = 'Avulso';
    const TIPO_ENTREGA = 'Entrega';

    /**
     * Estado do pedido, Agendado: O pedido deve ser processado na data de
     * agendamento. Ativo: O pedido deve ser processado. Fechado: O cliente
     * pediu a conta e está pronto para pagar. Entrega: O pedido saiu para
     * entrega. Finalizado: O pedido foi pago e concluído
     */
    const ESTADO_FINALIZADO = 'Finalizado';
    const ESTADO_ATIVO = 'Ativo';
    const ESTADO_AGENDADO = 'Agendado';
    const ESTADO_ENTREGA = 'Entrega';
    const ESTADO_FECHADO = 'Fechado';

    /**
     * Código do pedido
     */
    private $id;
    /**
     * Informa o pedido da comanda principal quando as comandas forem agrupadas
     */
    private $pedido_id;
    /**
     * Identificador da mesa, único quando o pedido não está fechado
     */
    private $mesa_id;
    /**
     * Identificador da comanda, único quando o pedido não está fechado
     */
    private $comanda_id;
    /**
     * Identificador da sessão de vendas
     */
    private $sessao_id;
    /**
     * Prestador que criou esse pedido
     */
    private $prestador_id;
    /**
     * Identificador do cliente do pedido
     */
    private $cliente_id;
    /**
     * Endereço de entrega do pedido, se não informado na venda entrega, o
     * pedido será para viagem
     */
    private $localizacao_id;
    /**
     * Informa em qual entrega esse pedido foi despachado
     */
    private $entrega_id;
    /**
     * Tipo de venda
     */
    private $tipo;
    /**
     * Estado do pedido, Agendado: O pedido deve ser processado na data de
     * agendamento. Ativo: O pedido deve ser processado. Fechado: O cliente
     * pediu a conta e está pronto para pagar. Entrega: O pedido saiu para
     * entrega. Finalizado: O pedido foi pago e concluído
     */
    private $estado;
    /**
     * Valor total dos serviços desse pedido
     */
    private $servicos;
    /**
     * Valor total dos produtos do pedido sem a comissão
     */
    private $produtos;
    /**
     * Valor total da comissão desse pedido
     */
    private $comissao;
    /**
     * Subtotal do pedido sem os descontos
     */
    private $subtotal;
    /**
     * Total de descontos realizado nesse pedido
     */
    private $descontos;
    /**
     * Total do pedido já com descontos
     */
    private $total;
    /**
     * Valor já pago do pedido
     */
    private $pago;
    /**
     * Valor lançado para pagar, mas não foi pago ainda
     */
    private $lancado;
    /**
     * Informa quantas pessoas estão na mesa
     */
    private $pessoas;
    /**
     * Detalhes da reserva ou do pedido
     */
    private $descricao;
    /**
     * Informa quem fechou o pedido e imprimiu a conta
     */
    private $fechador_id;
    /**
     * Data de impressão da conta do cliente
     */
    private $data_impressao;
    /**
     * Informa se o pedido foi cancelado
     */
    private $cancelado;
    /**
     * Informa o motivo do cancelamento
     */
    private $motivo;
    /**
     * Data e hora que o entregador concluiu a entrega desse pedido
     */
    private $data_entrega;
    /**
     * Data de agendamento do pedido
     */
    private $data_agendamento;
    /**
     * Data de finalização do pedido
     */
    private $data_conclusao;
    /**
     * Data de criação do pedido
     */
    private $data_criacao;

    /**
     * Constructor for a new empty instance of Pedido
     * @param array $pedido All field and values to fill the instance
     */
    public function __construct($pedido = [])
    {
        parent::__construct($pedido);
    }

    /**
     * Código do pedido
     * @return int código of Pedido
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param int $id Set código for Pedido
     * @return self Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Informa o pedido da comanda principal quando as comandas forem agrupadas
     * @return int comanda principal of Pedido
     */
    public function getPedidoID()
    {
        return $this->pedido_id;
    }

    /**
     * Set PedidoID value to new on param
     * @param int $pedido_id Set comanda principal for Pedido
     * @return self Self instance
     */
    public function setPedidoID($pedido_id)
    {
        $this->pedido_id = $pedido_id;
        return $this;
    }

    /**
     * Identificador da mesa, único quando o pedido não está fechado
     * @return int mesa of Pedido
     */
    public function getMesaID()
    {
        return $this->mesa_id;
    }

    /**
     * Set MesaID value to new on param
     * @param int $mesa_id Set mesa for Pedido
     * @return self Self instance
     */
    public function setMesaID($mesa_id)
    {
        $this->mesa_id = $mesa_id;
        return $this;
    }

    /**
     * Identificador da comanda, único quando o pedido não está fechado
     * @return int comanda of Pedido
     */
    public function getComandaID()
    {
        return $this->comanda_id;
    }

    /**
     * Set ComandaID value to new on param
     * @param int $comanda_id Set comanda for Pedido
     * @return self Self instance
     */
    public function setComandaID($comanda_id)
    {
        $this->comanda_id = $comanda_id;
        return $this;
    }

    /**
     * Identificador da sessão de vendas
     * @return int sessão of Pedido
     */
    public function getSessaoID()
    {
        return $this->sessao_id;
    }

    /**
     * Set SessaoID value to new on param
     * @param int $sessao_id Set sessão for Pedido
     * @return self Self instance
     */
    public function setSessaoID($sessao_id)
    {
        $this->sessao_id = $sessao_id;
        return $this;
    }

    /**
     * Prestador que criou esse pedido
     * @return int prestador of Pedido
     */
    public function getPrestadorID()
    {
        return $this->prestador_id;
    }

    /**
     * Set PrestadorID value to new on param
     * @param int $prestador_id Set prestador for Pedido
     * @return self Self instance
     */
    public function setPrestadorID($prestador_id)
    {
        $this->prestador_id = $prestador_id;
        return $this;
    }

    /**
     * Identificador do cliente do pedido
     * @return int cliente of Pedido
     */
    public function getClienteID()
    {
        return $this->cliente_id;
    }

    /**
     * Set ClienteID value to new on param
     * @param int $cliente_id Set cliente for Pedido
     * @return self Self instance
     */
    public function setClienteID($cliente_id)
    {
        $this->cliente_id = $cliente_id;
        return $this;
    }

    /**
     * Endereço de entrega do pedido, se não informado na venda entrega, o
     * pedido será para viagem
     * @return int localização of Pedido
     */
    public function getLocalizacaoID()
    {
        return $this->localizacao_id;
    }

    /**
     * Set LocalizacaoID value to new on param
     * @param int $localizacao_id Set localização for Pedido
     * @return self Self instance
     */
    public function setLocalizacaoID($localizacao_id)
    {
        $this->localizacao_id = $localizacao_id;
        return $this;
    }

    /**
     * Informa em qual entrega esse pedido foi despachado
     * @return int entrega of Pedido
     */
    public function getEntregaID()
    {
        return $this->entrega_id;
    }

    /**
     * Set EntregaID value to new on param
     * @param int $entrega_id Set entrega for Pedido
     * @return self Self instance
     */
    public function setEntregaID($entrega_id)
    {
        $this->entrega_id = $entrega_id;
        return $this;
    }

    /**
     * Tipo de venda
     * @return string tipo of Pedido
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set Tipo value to new on param
     * @param string $tipo Set tipo for Pedido
     * @return self Self instance
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
        return $this;
    }

    /**
     * Estado do pedido, Agendado: O pedido deve ser processado na data de
     * agendamento. Ativo: O pedido deve ser processado. Fechado: O cliente
     * pediu a conta e está pronto para pagar. Entrega: O pedido saiu para
     * entrega. Finalizado: O pedido foi pago e concluído
     * @return string estado of Pedido
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set Estado value to new on param
     * @param string $estado Set estado for Pedido
     * @return self Self instance
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
        return $this;
    }

    /**
     * Valor total dos serviços desse pedido
     * @return string total dos serviços of Pedido
     */
    public function getServicos()
    {
        return $this->servicos;
    }

    /**
     * Set Servicos value to new on param
     * @param string $servicos Set total dos serviços for Pedido
     * @return self Self instance
     */
    public function setServicos($servicos)
    {
        $this->servicos = $servicos;
        return $this;
    }

    /**
     * Valor total dos produtos do pedido sem a comissão
     * @return string total dos produtos of Pedido
     */
    public function getProdutos()
    {
        return $this->produtos;
    }

    /**
     * Set Produtos value to new on param
     * @param string $produtos Set total dos produtos for Pedido
     * @return self Self instance
     */
    public function setProdutos($produtos)
    {
        $this->produtos = $produtos;
        return $this;
    }

    /**
     * Valor total da comissão desse pedido
     * @return string total da comissão of Pedido
     */
    public function getComissao()
    {
        return $this->comissao;
    }

    /**
     * Set Comissao value to new on param
     * @param string $comissao Set total da comissão for Pedido
     * @return self Self instance
     */
    public function setComissao($comissao)
    {
        $this->comissao = $comissao;
        return $this;
    }

    /**
     * Subtotal do pedido sem os descontos
     * @return string subtotal of Pedido
     */
    public function getSubtotal()
    {
        return $this->subtotal;
    }

    /**
     * Set Subtotal value to new on param
     * @param string $subtotal Set subtotal for Pedido
     * @return self Self instance
     */
    public function setSubtotal($subtotal)
    {
        $this->subtotal = $subtotal;
        return $this;
    }

    /**
     * Total de descontos realizado nesse pedido
     * @return string descontos of Pedido
     */
    public function getDescontos()
    {
        return $this->descontos;
    }

    /**
     * Set Descontos value to new on param
     * @param string $descontos Set descontos for Pedido
     * @return self Self instance
     */
    public function setDescontos($descontos)
    {
        $this->descontos = $descontos;
        return $this;
    }

    /**
     * Total do pedido já com descontos
     * @return string total of Pedido
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set Total value to new on param
     * @param string $total Set total for Pedido
     * @return self Self instance
     */
    public function setTotal($total)
    {
        $this->total = $total;
        return $this;
    }

    /**
     * Valor já pago do pedido
     * @return string total pago of Pedido
     */
    public function getPago()
    {
        return $this->pago;
    }

    /**
     * Set Pago value to new on param
     * @param string $pago Set total pago for Pedido
     * @return self Self instance
     */
    public function setPago($pago)
    {
        $this->pago = $pago;
        return $this;
    }

    /**
     * Valor lançado para pagar, mas não foi pago ainda
     * @return string total lançado of Pedido
     */
    public function getLancado()
    {
        return $this->lancado;
    }

    /**
     * Set Lancado value to new on param
     * @param string $lancado Set total lançado for Pedido
     * @return self Self instance
     */
    public function setLancado($lancado)
    {
        $this->lancado = $lancado;
        return $this;
    }

    /**
     * Informa quantas pessoas estão na mesa
     * @return int pessoas of Pedido
     */
    public function getPessoas()
    {
        return $this->pessoas;
    }

    /**
     * Set Pessoas value to new on param
     * @param int $pessoas Set pessoas for Pedido
     * @return self Self instance
     */
    public function setPessoas($pessoas)
    {
        $this->pessoas = $pessoas;
        return $this;
    }

    /**
     * Detalhes da reserva ou do pedido
     * @return string descrição of Pedido
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Set Descricao value to new on param
     * @param string $descricao Set descrição for Pedido
     * @return self Self instance
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * Informa quem fechou o pedido e imprimiu a conta
     * @return int fechador do pedido of Pedido
     */
    public function getFechadorID()
    {
        return $this->fechador_id;
    }

    /**
     * Set FechadorID value to new on param
     * @param int $fechador_id Set fechador do pedido for Pedido
     * @return self Self instance
     */
    public function setFechadorID($fechador_id)
    {
        $this->fechador_id = $fechador_id;
        return $this;
    }

    /**
     * Data de impressão da conta do cliente
     * @return string data de impressão of Pedido
     */
    public function getDataImpressao()
    {
        return $this->data_impressao;
    }

    /**
     * Set DataImpressao value to new on param
     * @param string $data_impressao Set data de impressão for Pedido
     * @return self Self instance
     */
    public function setDataImpressao($data_impressao)
    {
        $this->data_impressao = $data_impressao;
        return $this;
    }

    /**
     * Informa se o pedido foi cancelado
     * @return string cancelado of Pedido
     */
    public function getCancelado()
    {
        return $this->cancelado;
    }

    /**
     * Informa se o pedido foi cancelado
     * @return boolean Check if o of Cancelado is selected or checked
     */
    public function isCancelado()
    {
        return $this->cancelado == 'Y';
    }

    /**
     * Set Cancelado value to new on param
     * @param string $cancelado Set cancelado for Pedido
     * @return self Self instance
     */
    public function setCancelado($cancelado)
    {
        $this->cancelado = $cancelado;
        return $this;
    }

    /**
     * Informa o motivo do cancelamento
     * @return string motivo of Pedido
     */
    public function getMotivo()
    {
        return $this->motivo;
    }

    /**
     * Set Motivo value to new on param
     * @param string $motivo Set motivo for Pedido
     * @return self Self instance
     */
    public function setMotivo($motivo)
    {
        $this->motivo = $motivo;
        return $this;
    }

    /**
     * Data e hora que o entregador concluiu a entrega desse pedido
     * @return string data de entrega of Pedido
     */
    public function getDataEntrega()
    {
        return $this->data_entrega;
    }

    /**
     * Set DataEntrega value to new on param
     * @param string $data_entrega Set data de entrega for Pedido
     * @return self Self instance
     */
    public function setDataEntrega($data_entrega)
    {
        $this->data_entrega = $data_entrega;
        return $this;
    }

    /**
     * Data de agendamento do pedido
     * @return string data de agendamento of Pedido
     */
    public function getDataAgendamento()
    {
        return $this->data_agendamento;
    }

    /**
     * Set DataAgendamento value to new on param
     * @param string $data_agendamento Set data de agendamento for Pedido
     * @return self Self instance
     */
    public function setDataAgendamento($data_agendamento)
    {
        $this->data_agendamento = $data_agendamento;
        return $this;
    }

    /**
     * Data de finalização do pedido
     * @return string data de conclusão of Pedido
     */
    public function getDataConclusao()
    {
        return $this->data_conclusao;
    }

    /**
     * Set DataConclusao value to new on param
     * @param string $data_conclusao Set data de conclusão for Pedido
     * @return self Self instance
     */
    public function setDataConclusao($data_conclusao)
    {
        $this->data_conclusao = $data_conclusao;
        return $this;
    }

    /**
     * Data de criação do pedido
     * @return string data de criação of Pedido
     */
    public function getDataCriacao()
    {
        return $this->data_criacao;
    }

    /**
     * Set DataCriacao value to new on param
     * @param string $data_criacao Set data de criação for Pedido
     * @return self Self instance
     */
    public function setDataCriacao($data_criacao)
    {
        $this->data_criacao = $data_criacao;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $pedido = parent::toArray($recursive);
        $pedido['id'] = $this->getID();
        $pedido['pedidoid'] = $this->getPedidoID();
        $pedido['mesaid'] = $this->getMesaID();
        $pedido['comandaid'] = $this->getComandaID();
        $pedido['sessaoid'] = $this->getSessaoID();
        $pedido['prestadorid'] = $this->getPrestadorID();
        $pedido['clienteid'] = $this->getClienteID();
        $pedido['localizacaoid'] = $this->getLocalizacaoID();
        $pedido['entregaid'] = $this->getEntregaID();
        $pedido['tipo'] = $this->getTipo();
        $pedido['estado'] = $this->getEstado();
        $pedido['servicos'] = $this->getServicos();
        $pedido['produtos'] = $this->getProdutos();
        $pedido['comissao'] = $this->getComissao();
        $pedido['subtotal'] = $this->getSubtotal();
        $pedido['descontos'] = $this->getDescontos();
        $pedido['total'] = $this->getTotal();
        $pedido['pago'] = $this->getPago();
        $pedido['lancado'] = $this->getLancado();
        $pedido['pessoas'] = $this->getPessoas();
        $pedido['descricao'] = $this->getDescricao();
        $pedido['fechadorid'] = $this->getFechadorID();
        $pedido['dataimpressao'] = $this->getDataImpressao();
        $pedido['cancelado'] = $this->getCancelado();
        $pedido['motivo'] = $this->getMotivo();
        $pedido['dataentrega'] = $this->getDataEntrega();
        $pedido['dataagendamento'] = $this->getDataAgendamento();
        $pedido['dataconclusao'] = $this->getDataConclusao();
        $pedido['datacriacao'] = $this->getDataCriacao();
        return $pedido;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param mixed $pedido Associated key -> value to assign into this instance
     * @return self Self instance
     */
    public function fromArray($pedido = [])
    {
        if ($pedido instanceof self) {
            $pedido = $pedido->toArray();
        } elseif (!is_array($pedido)) {
            $pedido = [];
        }
        parent::fromArray($pedido);
        if (!isset($pedido['id'])) {
            $this->setID(null);
        } else {
            $this->setID($pedido['id']);
        }
        if (!array_key_exists('pedidoid', $pedido)) {
            $this->setPedidoID(null);
        } else {
            $this->setPedidoID($pedido['pedidoid']);
        }
        if (!array_key_exists('mesaid', $pedido)) {
            $this->setMesaID(null);
        } else {
            $this->setMesaID($pedido['mesaid']);
        }
        if (!array_key_exists('comandaid', $pedido)) {
            $this->setComandaID(null);
        } else {
            $this->setComandaID($pedido['comandaid']);
        }
        if (!array_key_exists('sessaoid', $pedido)) {
            $this->setSessaoID(null);
        } else {
            $this->setSessaoID($pedido['sessaoid']);
        }
        if (!array_key_exists('prestadorid', $pedido)) {
            $this->setPrestadorID(null);
        } else {
            $this->setPrestadorID($pedido['prestadorid']);
        }
        if (!array_key_exists('clienteid', $pedido)) {
            $this->setClienteID(null);
        } else {
            $this->setClienteID($pedido['clienteid']);
        }
        if (!array_key_exists('localizacaoid', $pedido)) {
            $this->setLocalizacaoID(null);
        } else {
            $this->setLocalizacaoID($pedido['localizacaoid']);
        }
        if (!array_key_exists('entregaid', $pedido)) {
            $this->setEntregaID(null);
        } else {
            $this->setEntregaID($pedido['entregaid']);
        }
        if (!isset($pedido['tipo'])) {
            $this->setTipo(null);
        } else {
            $this->setTipo($pedido['tipo']);
        }
        if (!isset($pedido['estado'])) {
            $this->setEstado(null);
        } else {
            $this->setEstado($pedido['estado']);
        }
        if (!isset($pedido['servicos'])) {
            $this->setServicos(0);
        } else {
            $this->setServicos($pedido['servicos']);
        }
        if (!isset($pedido['produtos'])) {
            $this->setProdutos(0);
        } else {
            $this->setProdutos($pedido['produtos']);
        }
        if (!isset($pedido['comissao'])) {
            $this->setComissao(0);
        } else {
            $this->setComissao($pedido['comissao']);
        }
        if (!isset($pedido['subtotal'])) {
            $this->setSubtotal(0);
        } else {
            $this->setSubtotal($pedido['subtotal']);
        }
        if (!isset($pedido['descontos'])) {
            $this->setDescontos(0);
        } else {
            $this->setDescontos($pedido['descontos']);
        }
        if (!isset($pedido['total'])) {
            $this->setTotal(0);
        } else {
            $this->setTotal($pedido['total']);
        }
        if (!isset($pedido['pago'])) {
            $this->setPago(0);
        } else {
            $this->setPago($pedido['pago']);
        }
        if (!isset($pedido['lancado'])) {
            $this->setLancado(0);
        } else {
            $this->setLancado($pedido['lancado']);
        }
        if (!isset($pedido['pessoas'])) {
            $this->setPessoas(null);
        } else {
            $this->setPessoas($pedido['pessoas']);
        }
        if (!array_key_exists('descricao', $pedido)) {
            $this->setDescricao(null);
        } else {
            $this->setDescricao($pedido['descricao']);
        }
        if (!array_key_exists('fechadorid', $pedido)) {
            $this->setFechadorID(null);
        } else {
            $this->setFechadorID($pedido['fechadorid']);
        }
        if (!array_key_exists('dataimpressao', $pedido)) {
            $this->setDataImpressao(null);
        } else {
            $this->setDataImpressao($pedido['dataimpressao']);
        }
        if (!isset($pedido['cancelado'])) {
            $this->setCancelado('N');
        } else {
            $this->setCancelado($pedido['cancelado']);
        }
        if (!array_key_exists('motivo', $pedido)) {
            $this->setMotivo(null);
        } else {
            $this->setMotivo($pedido['motivo']);
        }
        if (!array_key_exists('dataentrega', $pedido)) {
            $this->setDataEntrega(null);
        } else {
            $this->setDataEntrega($pedido['dataentrega']);
        }
        if (!array_key_exists('dataagendamento', $pedido)) {
            $this->setDataAgendamento(null);
        } else {
            $this->setDataAgendamento($pedido['dataagendamento']);
        }
        if (!array_key_exists('dataconclusao', $pedido)) {
            $this->setDataConclusao(null);
        } else {
            $this->setDataConclusao($pedido['dataconclusao']);
        }
        if (!isset($pedido['datacriacao'])) {
            $this->setDataCriacao(DB::now());
        } else {
            $this->setDataCriacao($pedido['datacriacao']);
        }
        return $this;
    }

    /**
     * Retorna a descrição do local do pedido
     * @param \MZ\Environment\Mesa $mesa Mesa de onde será obtido o nome
     * @param \MZ\Environment\Comanda $comanda Comanda de onde será obtido o nome
     * @return string Nome do local de destino do pedido
     */
    public function getDestino($mesa = null, $comanda = null)
    {
        if ($this->getTipo() == self::TIPO_MESA) {
            $mesa = !is_null($mesa) ? $mesa : $this->findMesaID();
            return $mesa->getNome();
        } elseif ($this->getTipo() == self::TIPO_COMANDA) {
            $comanda = !is_null($comanda) ? $comanda : $this->findComandaID();
            return $comanda->getNome();
        } elseif ($this->getTipo() == self::TIPO_ENTREGA &&
            is_null($this->getLocalizacaoID())
        ) {
            return 'Viagem';
        } else {
            return self::getTipoOptions($this->getTipo());
        }
    }

    /**
     * Informa se o pedido é para entrega
     */
    public function isDelivery()
    {
        return $this->getTipo() == self::TIPO_ENTREGA && !is_null($this->getLocalizacaoID());
    }

    /**
     * Informa se o pedido precisa de um caixa aberto
     */
    public function needMovimentacao()
    {
        return $this->getTipo() == self::TIPO_AVULSO || $this->getTipo() == self::TIPO_ENTREGA;
    }

    /**
     * Converte o nome do estado para um nome mais genérico
     */
    public function getEstadoSimples()
    {
        if ($this->getEstado() == self::ESTADO_ATIVO) {
            return 'ocupado';
        } elseif ($this->getEstado() == self::ESTADO_AGENDADO) {
            return 'reservado';
        } elseif (is_null($this->getEstado())) {
            return 'livre';
        }
        return strtolower($this->getEstado());
    }

    /**
     * Obtém o saldo de pagamento do pedido
     * @return float saldo restante do pedido
     */
    public function getSaldo()
    {
        return max(0, $this->getPago() - $this->getTotal());
    }

    /**
     * Convert this instance into array associated key -> value with only public fields
     * @param \MZ\Provider\Prestador $requester user that request to view this fields
     * @return array All public field and values into array format
     */
    public function publish($requester)
    {
        $pedido = parent::publish($requester);
        return $pedido;
    }

    /**
     * Filter fields, upload data and keep key data
     * @param self $original Original instance without modifications
     * @param \MZ\Provider\Prestador $updater user that want to update this object
     * @param boolean $localized Informs if fields are localized
     * @return self Self instance
     */
    public function filter($original, $updater, $localized = false)
    {
        $this->setID($original->getID());
        $this->setPedidoID(Filter::number($original->getPedidoID()));
        $this->setMesaID(Filter::number($this->getMesaID()));
        $this->setComandaID(Filter::number($this->getComandaID()));
        $this->setSessaoID(Filter::number($this->getSessaoID()));
        $this->setPrestadorID(Filter::number($this->getPrestadorID()));
        $this->setClienteID(Filter::number($this->getClienteID()));
        $this->setLocalizacaoID(Filter::number($this->getLocalizacaoID()));
        $this->setEntregaID(Filter::number($this->getEntregaID()));
        $this->setServicos(Filter::money($original->getServicos(), $localized));
        $this->setProdutos(Filter::money($original->getProdutos(), $localized));
        $this->setComissao(Filter::money($original->getComissao(), $localized));
        $this->setSubtotal(Filter::money($original->getSubtotal(), $localized));
        $this->setDescontos(Filter::money($original->getDescontos(), $localized));
        $this->setTotal(Filter::money($original->getTotal(), $localized));
        $this->setPago(Filter::money($original->getPago(), $localized));
        $this->setLancado(Filter::money($original->getLancado(), $localized));
        $this->setPessoas(Filter::number($this->getPessoas()));
        $this->setDescricao(Filter::string($this->getDescricao()));
        $this->setFechadorID(Filter::number($this->getFechadorID()));
        $this->setDataImpressao(Filter::datetime($this->getDataImpressao()));
        $this->setMotivo(Filter::string($this->getMotivo()));
        $this->setDataEntrega(Filter::datetime($this->getDataEntrega()));
        $this->setDataAgendamento(Filter::datetime($this->getDataAgendamento()));
        $this->setDataConclusao(Filter::datetime($this->getDataConclusao()));
        $this->setDataCriacao(Filter::datetime($this->getDataCriacao()));
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
     * @return array All field of Pedido in array format
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function validate()
    {
        $errors = [];
        if (is_null($this->getPrestadorID()) && is_null($this->getClienteID())) {
            $errors['clienteid'] = 'O usuário não foi informado';
        }
        if (!Validator::checkInSet($this->getTipo(), self::getTipoOptions())) {
            $errors['tipo'] = _t('pedido.tipo_invalid');
        }
        if (is_null($this->getMesaID()) && $this->getTipo() == self::TIPO_MESA) {
            $errors['mesaid'] = 'A mesa não foi informada';
        } elseif (!is_null($this->getMesaID()) &&
            !in_array($this->getTipo(), [self::TIPO_MESA, self::TIPO_COMANDA])
        ) {
            $errors['mesaid'] = 'Esse tipo de venda não aceita informar mesa';
        }
        if (is_null($this->getComandaID()) && $this->getTipo() == self::TIPO_COMANDA) {
            $errors['comandaid'] = 'A comanda não foi informada';
        } elseif (!is_null($this->getComandaID()) && $this->getTipo() != self::TIPO_COMANDA) {
            $errors['comandaid'] = 'Esse tipo de venda não aceita informar comanda';
        }
        if (!Validator::checkInSet($this->getEstado(), self::getEstadoOptions())) {
            $errors['estado'] = _t('pedido.estado_invalid');
        }
        if (is_null($this->getServicos())) {
            $errors['servicos'] = _t('pedido.servicos_cannot_empty');
        }
        if (is_null($this->getProdutos())) {
            $errors['produtos'] = _t('pedido.produtos_cannot_empty');
        }
        if (is_null($this->getComissao())) {
            $errors['comissao'] = _t('pedido.comissao_cannot_empty');
        }
        if (is_null($this->getSubtotal())) {
            $errors['subtotal'] = _t('pedido.subtotal_cannot_empty');
        }
        if (is_null($this->getDescontos())) {
            $errors['descontos'] = _t('pedido.descontos_cannot_empty');
        }
        if (is_null($this->getTotal())) {
            $errors['total'] = _t('pedido.total_cannot_empty');
        }
        if (is_null($this->getPago())) {
            $errors['pago'] = _t('pedido.pago_cannot_empty');
        }
        if (is_null($this->getLancado())) {
            $errors['lancado'] = _t('pedido.lancado_cannot_empty');
        }
        if (is_null($this->getPessoas())) {
            $errors['pessoas'] = _t('pedido.pessoas_cannot_empty');
        }
        if (!Validator::checkBoolean($this->getCancelado())) {
            $errors['cancelado'] = _t('pedido.cancelado_invalid');
        }
        if (!$this->exists() && trim(app()->getSystem()->getLicenca()) == '') {
            $count = self::count();
            if ($count >= 20) {
                $errors['id'] = 'Quantidade de pedidos excedido, adquira uma licença para continuar';
            }
        }
        $this->setDataCriacao(DB::now());
        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
        $values = $this->toArray();
        if ($this->exists()) {
            unset($values['datacriacao']);
        }
        return $values;
    }

    public function checkAccess($operador)
    {
        if ($this->getTipo() == self::TIPO_MESA) {
            if (!$operador->has([Permissao::NOME_PEDIDOMESA])) {
                throw new \Exception('Você não tem permissão para acessar mesas');
            }
        } elseif ($this->getTipo() == self::TIPO_COMANDA) {
            if (!$operador->has([Permissao::NOME_PEDIDOCOMANDA])) {
                throw new \Exception('Você não tem permissão para acessar comandas');
            }
        } elseif ($this->getTipo() == self::TIPO_ENTREGA) {
            if (!$operador->has([Permissao::NOME_ENTREGAPEDIDOS])) {
                throw new \Exception('Você não tem permissão para criar pedidos para entrega');
            } elseif (!$operador->has([Permissao::NOME_ENTREGAADICIONAR]) && $this->exists()) {
                throw new \Exception('Você não tem permissão para adicionar produtos no pedido para entrega');
            }
        } else {
            // AVULSA
            if (!$operador->has([Permissao::NOME_PAGAMENTO])) {
                throw new \Exception('Você não tem permissão para criar pedidos para balcão');
            }
        }
        return $this;
    }

    /**
     * Check if operator has permission to sell this order
     * @param \MZ\Provider\Prestador $operador operator that will sale this order
     * @return Pedido Self instance
     * @throws \Exception when operator has not permission
     */
    public function validaAcesso($operador)
    {
        $this->checkAccess($operador);
        if (!$this->exists()) {
            return $this;
        }
        if (!in_array($this->getTipo(), [self::TIPO_MESA, self::TIPO_COMANDA])) {
            return $this;
        }
        if ($this->getPrestadorID() == $operador->getID()) {
            return $this;
        }
        if ($this->getTipo() == self::TIPO_MESA && !$operador->has([Permissao::NOME_MESAS])) {
            $cliente = $this->findPrestadorID()->findClienteID();
            $msg = sprintf(
                'Apenas o(a) funcionário(a) "%s" poderá realizar pedidos para essa mesa.',
                $cliente->getAssinatura()
            );
            throw new \Exception($msg);
        }
        if ($this->getTipo() == self::TIPO_COMANDA && !$operador->has([Permissao::NOME_COMANDAS])) {
            $cliente = $this->findPrestadorID()->findClienteID();
            $msg = sprintf(
                'Apenas o(a) funcionário(a) "%s" poderá realizar pedidos para essa comanda.',
                $cliente->getAssinatura()
            );
            throw new \Exception($msg);
        }
        return $this;
    }

    public function checkSaldo($subtotal)
    {
        if ($this->getTipo() == self::TIPO_COMANDA && is_boolean_config('Comandas', 'PrePaga')) {
            $itens_total = $this->findTotal(true);
            $total = $subtotal + $itens_total;
            $pagamentos_total = $this->findPagamentoTotal();
            $restante = $itens_total - $pagamentos_total;
            $msg = 'Saldo insuficiente para a realização do pedido, Necessário: %s, Saldo atual: %s';
            if ($total > $pagamentos_total) {
                throw new \Exception(sprintf(
                    $msg,
                    \MZ\Util\Mask::money($subtotal, true),
                    \MZ\Util\Mask::money(-$restante, true)
                ));
            }
        }
    }

    /**
     * Load order by open table id
     * @param  int $mesa_id id to find open table
     * @return self Self instance filled or empty when not found
     */
    public function loadByMesaID()
    {
        return $this->load([
            'mesaid' => intval($this->getMesaID()),
            'cancelado' => 'N',
            'tipo' => self::TIPO_MESA,
            '!estado' => self::ESTADO_FINALIZADO
        ]);
    }

    /**
     * Load order by open card id
     * @param  int $comanda_id id to find open card
     * @return self Self instance filled or empty when not found
     */
    public function loadByComandaID()
    {
        return $this->load([
            'comandaid' => intval($this->getComandaID()),
            'cancelado' => 'N',
            'tipo' => self::TIPO_COMANDA,
            '!estado' => self::ESTADO_FINALIZADO
        ]);
    }

    /**
     * Load open order by type into this object
     * @return self The object fetched from database or empty when not found
     */
    public function loadByLocal()
    {
        if ($this->getTipo() == self::TIPO_MESA) {
            $this->loadByMesaID();
        } elseif ($this->getTipo() == self::TIPO_COMANDA) {
            $this->loadByComandaID();
        } else {
            $this->fromArray([]);
        }
        return $this;
    }

    /**
     * Load open order by customer and date
     * @return self The object fetched from database or empty when not found
     */
    public function loadAproximado()
    {
        return $this->load([
            'clienteid' => $this->getClienteID(),
            'tipo' => self::TIPO_ENTREGA,
            'apartir_criacao' => DB::now(strtotime('-1 min', strtotime($this->getDataCriacao()))),
            'ate_criacao' => DB::now(strtotime('+1 min', strtotime($this->getDataCriacao())))
        ]);
    }

    public function totalize()
    {
        $produtos = Item::sum(['subtotal', 'comissao'], [
            'pedidoid' => $this->getID(),
            'servicoid' => null,
            'cancelado' => 'N'
        ]);
        $servicos = Item::sum(['subtotal'], [
            'pedidoid' => $this->getID(),
            'produtoid' => null,
            'cancelado' => 'N',
            'apartir_preco' => 0,
        ]);
        $descontos = Item::sum(['subtotal'], [
            'pedidoid' => $this->getID(),
            'produtoid' => null,
            'cancelado' => 'N',
            'ate_preco' => 0,
        ]);
        $pago = Pagamento::sum(['lancado'], [
            'pedidoid' => $this->getID(),
            'estado' => Pagamento::ESTADO_PAGO
        ]);
        $lancado = Pagamento::sum(['lancado'], [
            'pedidoid' => $this->getID(),
            'estado' => [
                Pagamento::ESTADO_ABERTO,
                Pagamento::ESTADO_AGUARDANDO,
                Pagamento::ESTADO_ANALISE
            ]
        ]);
        $this->setServicos(floatval($servicos));
        $this->setProdutos(floatval($produtos['subtotal']));
        $this->setComissao(floatval($produtos['comissao']));
        $this->setSubtotal(Filter::money(
            $this->getServicos() + $this->getProdutos() + $this->getComissao(),
            false
        ));
        $this->setDescontos(floatval($descontos));
        $this->setTotal(Filter::money(
            $this->getSubtotal() + $this->getDescontos(),
            false
        ));
        $this->setPago(floatval($pago));
        $this->setLancado(floatval($lancado));
    }

    /**
     * Informa o pedido da comanda principal quando as comandas forem agrupadas
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
     * Identificador da mesa, único quando o pedido não está fechado
     * @return \MZ\Environment\Mesa The object fetched from database
     */
    public function findMesaID()
    {
        if (is_null($this->getMesaID())) {
            return new \MZ\Environment\Mesa();
        }
        return \MZ\Environment\Mesa::findByID($this->getMesaID());
    }

    /**
     * Identificador da comanda, único quando o pedido não está fechado
     * @return \MZ\Sale\Comanda The object fetched from database
     */
    public function findComandaID()
    {
        if (is_null($this->getComandaID())) {
            return new \MZ\Sale\Comanda();
        }
        return \MZ\Sale\Comanda::findByID($this->getComandaID());
    }

    /**
     * Identificador da sessão de vendas
     * @return \MZ\Session\Sessao The object fetched from database
     */
    public function findSessaoID()
    {
        if (is_null($this->getSessaoID())) {
            return new \MZ\Session\Sessao();
        }
        return \MZ\Session\Sessao::findByID($this->getSessaoID());
    }

    /**
     * Prestador que criou esse pedido
     * @return \MZ\Provider\Prestador The object fetched from database
     */
    public function findPrestadorID()
    {
        if (is_null($this->getPrestadorID())) {
            return new \MZ\Provider\Prestador();
        }
        return \MZ\Provider\Prestador::findByID($this->getPrestadorID());
    }

    /**
     * Identificador do cliente do pedido
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
     * Endereço de entrega do pedido, se não informado na venda entrega, o
     * pedido será para viagem
     * @return \MZ\Location\Localizacao The object fetched from database
     */
    public function findLocalizacaoID()
    {
        if (is_null($this->getLocalizacaoID())) {
            return new \MZ\Location\Localizacao();
        }
        return \MZ\Location\Localizacao::findByID($this->getLocalizacaoID());
    }

    /**
     * Informa em qual entrega esse pedido foi despachado
     * @return \MZ\Location\Viagem The object fetched from database
     */
    public function findEntregaID()
    {
        if (is_null($this->getEntregaID())) {
            return new \MZ\Location\Viagem();
        }
        return \MZ\Location\Viagem::findByID($this->getEntregaID());
    }

    /**
     * Informa quem fechou o pedido e imprimiu a conta
     * @return \MZ\Provider\Prestador The object fetched from database
     */
    public function findFechadorID()
    {
        if (is_null($this->getFechadorID())) {
            return new \MZ\Provider\Prestador();
        }
        return \MZ\Provider\Prestador::findByID($this->getFechadorID());
    }

    /**
     * Retorna o total vendido do pedido com informações detalhadas ou resumida
     * @param  bool $resumido informa se deve retornar apenas o total do pedido
     * @return mixed array com os totais detalhados ou apenas o total se for resumido
     */
    public function findTotal($resumido = false)
    {
        if (!$this->exists() && is_null($this->getMesaID())) {
            $row = [
                'servicos' => 0,
                'produtos' => 0,
                'comissao' => 0,
                'subtotal' => 0,
                'descontos' => 0,
                'total' => 0,
            ];
        } else {
            $query = DB::from('Pedidos p')
                ->select(null)
                ->select('SUM(p.servicos) as servicos')
                ->select('SUM(p.produtos) as produtos')
                ->select('SUM(p.comissao) as comissao')
                ->select('SUM(p.subtotal) as subtotal')
                ->select('SUM(p.descontos) as descontos')
                ->select('SUM(p.total) as total');
            if ($this->exists()) {
                $query = $query->where('p.id', $this->getID());
            } else {
                $query = $query->where('p.mesaid', $this->getMesaID())
                    ->where('p.tipo', self::TIPO_COMANDA);
            }
            $row = $query->fetch();
        }
        if ($resumido) {
            return $row['total'];
        }
        return $row;
    }

    /**
     * Obtem o total pago para esse pedido
     */
    public function findPagamentoTotal()
    {
        if ($this->exists()) {
            return Pagamento::rawFindTotal(['pedidoid' => $this->getID()]);
        } elseif (!is_null($this->getMesaID())) {
            return Pagamento::rawFindTotal(['mesaid' => $this->getMesaID()]);
        }
        return 0;
    }

    /**
     * Gets textual and translated Tipo for Pedido
     * @param int $index choose option from index
     * @return string[] A associative key -> translated representative text or text for index
     */
    public static function getTipoOptions($index = null)
    {
        $options = [
            self::TIPO_MESA => _t('pedido.tipo_mesa'),
            self::TIPO_COMANDA => _t('pedido.tipo_comanda'),
            self::TIPO_AVULSO => _t('pedido.tipo_avulso'),
            self::TIPO_ENTREGA => _t('pedido.tipo_entrega'),
        ];
        if (!is_null($index)) {
            return $options[$index];
        }
        return $options;
    }

    /**
     * Gets textual and translated Estado for Pedido
     * @param int $index choose option from index
     * @return string[] A associative key -> translated representative text or text for index
     */
    public static function getEstadoOptions($index = null)
    {
        $options = [
            self::ESTADO_FINALIZADO => _t('pedido.estado_finalizado'),
            self::ESTADO_ATIVO => _t('pedido.estado_ativo'),
            self::ESTADO_AGENDADO => _t('pedido.estado_agendado'),
            self::ESTADO_ENTREGA => _t('pedido.estado_entrega'),
            self::ESTADO_FECHADO => _t('pedido.estado_fechado'),
        ];
        if (!is_null($index)) {
            return $options[$index];
        }
        return $options;
    }

    /**
     * Filter condition array with allowed fields
     * @param array $condition condition to filter rows
     * @return array allowed condition
     */
    protected function filterCondition($condition)
    {
        $allowed = $this->getAllowedKeys();

        if (isset($condition['search'])) {
            $search = trim($condition['search']);
            if (is_numeric($search)) {
                $condition['id'] = intval($search);
            } elseif (substr($search, 0, 1) == '#') {
                $sessaoid = intval(substr($search, 1));
                $condition['sessaoid'] = intval($sessaoid);
            } else {
                $field = 'p.descricao LIKE ?';
                $condition[$field] = '%'.$search.'%';
                $allowed[$field] = true;
            }
        }
        if (isset($condition['!estado'])) {
            $field = 'p.estado <> ?';
            $condition[$field] = $condition['!estado'];
            $allowed[$field] = true;
        }
        if (isset($condition['apartir_criacao'])) {
            $field = 'p.datacriacao >= ?';
            $condition[$field] = Filter::datetime($condition['apartir_criacao'], '00:00:00');
            $allowed[$field] = true;
        }
        if (isset($condition['ate_criacao'])) {
            $field = 'p.datacriacao <= ?';
            $condition[$field] = Filter::datetime($condition['ate_criacao'], '23:59:59');
            $allowed[$field] = true;
        }
        return Filter::keys($condition, $allowed, 'p.');
    }

    /**
     * Fetch data from database with a condition
     * @param array $condition condition to filter rows
     * @param array $order order rows
     * @return SelectQuery query object with condition statement
     */
    protected function query($condition = [], $order = [])
    {
        $query = DB::from('Pedidos p');
        $condition = $this->filterCondition($condition);
        $query = DB::buildOrderBy($query, $this->filterOrder($order));
        $query = $query->orderBy('p.id DESC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Find this object on database using, ID
     * @param  int $id código to find Pedido
     * @return Pedido A filled instance or empty when not found
     */
    public static function findByMesaID($mesa_id)
    {
        $result = new self();
        $result->setMesaID($mesa_id);
        return $result->loadByMesaID();
    }

    /**
     * Find this object on database using, ID
     * @param  int $id código to find Pedido
     * @return Pedido A filled instance or empty when not found
     */
    public static function findByComandaID($comanda_id)
    {
        $result = new self();
        $result->setComandaID($comanda_id);
        return $result->loadByComandaID();
    }

    public static function getTicketMedio($sessao_id, $data_inicio = null, $data_fim = null)
    {
        $query = DB::from('Pedidos p')
            ->select(null)
            ->select('SUM(TIME_TO_SEC(TIMEDIFF(COALESCE(p.dataconclusao, NOW()), p.datacriacao))) as segundos')
            ->select('COUNT(p.id) as quantidade')
            ->where(['p.cancelado' => 'N']);
        if (!is_null($sessao_id)) {
            $query = $query->where(['p.sessaoid' => $sessao_id]);
        }
        if (!is_null($data_inicio) && is_null($sessao_id)) {
            $query = $query->where('p.datacriacao >= ?', date('Y-m-d', $data_inicio));
        }
        if (!is_null($data_fim) && is_null($sessao_id)) {
            $query = $query->where('p.datacriacao <= ?', date('Y-m-d H:i:s', $data_fim));
        }
        $row = $query->fetch();
        $result = [
            'permanencia' => intval($row['segundos']),
            'pedidos' => intval($row['quantidade'])
        ];
        $total = self::fetchTotal($sessao_id, $data_inicio, $data_fim);
        if ($result['pedidos'] > 0) {
            $result['permanencia'] = (int)($result['permanencia'] / $result['pedidos']);
            $result['total'] = $total['subtotal'] / $result['pedidos'];
        } else {
            $result['permanencia'] = 0;
            $result['total'] = 0;
        }
        return $result;
    }

    public static function getTotalPessoas($sessao_id, $data_inicio = null, $data_fim = null)
    {
        $query = DB::from('Pedidos p')
            ->select(null)
            ->select('SUM(p.pessoas) as total')
            ->select('SUM(IF(p.estado = ?, 0, p.pessoas)) as atual', Pedido::ESTADO_FINALIZADO)
            ->where(['p.cancelado' => 'N'])
            ->where('p.tipo <> ?', self::TIPO_ENTREGA);
        if (!is_null($sessao_id)) {
            $query = $query->where(['p.sessaoid' => $sessao_id]);
        }
        if (!is_null($data_inicio) && is_null($sessao_id)) {
            $query = $query->where('p.datacriacao >= ?', date('Y-m-d', $data_inicio));
        }
        if (!is_null($data_fim) && is_null($sessao_id)) {
            $query = $query->where('p.datacriacao <= ?', date('Y-m-d H:i:s', $data_fim));
        }
        $row = $query->fetch();
        return ['total' => $row['total'] + 0, 'atual' => $row['atual'] + 0];
    }

    public static function fetchTotal($sessao_id, $data_inicio = null, $data_fim = null)
    {
        $query = DB::from('Pedidos p')
            ->select(null)
            ->select('SUM(p.subtotal) as subtotal')
            ->select('SUM(p.total) as total')
            ->select('SUM(IF(p.tipo = "Mesa", p.total, 0)) as mesa')
            ->select('SUM(IF(p.tipo = "Comanda", p.total, 0)) as comanda')
            ->select('SUM(IF(p.tipo = "Avulso", p.total, 0)) as avulso')
            ->select('SUM(IF(p.tipo = "Entrega", p.total, 0)) as entrega')
            ->where(['p.cancelado' => 'N']);
        if (!is_null($sessao_id)) {
            $query = $query->where(['p.sessaoid' => $sessao_id]);
        }
        if (!is_null($data_inicio) && is_null($sessao_id)) {
            $query = $query->where('p.datacriacao >= ?', date('Y-m-d', $data_inicio));
        }
        if (!is_null($data_fim) && is_null($sessao_id)) {
            $query = $query->where('p.datacriacao <= ?', date('Y-m-d H:i:s', $data_fim));
        }
        $row = $query->fetch();
        return [
            'total' => $row['total'] + 0,
            'subtotal' => $row['subtotal'] + 0,
            'tipo' => [
                'mesa' => $row['mesa'] + 0,
                'comanda' => $row['comanda'] + 0,
                'avulso' => $row['avulso'] + 0,
                'entrega' => $row['entrega'] + 0
            ]
        ];
    }
}
