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
        $pedido['mesaid'] = $this->getMesaID();
        $pedido['comandaid'] = $this->getComandaID();
        $pedido['sessaoid'] = $this->getSessaoID();
        $pedido['prestadorid'] = $this->getPrestadorID();
        $pedido['clienteid'] = $this->getClienteID();
        $pedido['localizacaoid'] = $this->getLocalizacaoID();
        $pedido['entregaid'] = $this->getEntregaID();
        $pedido['tipo'] = $this->getTipo();
        $pedido['estado'] = $this->getEstado();
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
     * Convert this instance into array associated key -> value with only public fields
     * @return array All public field and values into array format
     */
    public function publish()
    {
        $pedido = parent::publish();
        return $pedido;
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
        $this->setMesaID(Filter::number($this->getMesaID()));
        $this->setComandaID(Filter::number($this->getComandaID()));
        $this->setSessaoID(Filter::number($this->getSessaoID()));
        $this->setPrestadorID(Filter::number($this->getPrestadorID()));
        $this->setClienteID(Filter::number($this->getClienteID()));
        $this->setLocalizacaoID(Filter::number($this->getLocalizacaoID()));
        $this->setEntregaID(Filter::number($this->getEntregaID()));
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
        if (is_null($this->getFuncionarioID()) && is_null($this->getClienteID())) {
            $errors['funcionarioid'] = 'O usuário não foi informado';
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
        if (is_null($this->getPessoas())) {
            $errors['pessoas'] = _t('pedido.pessoas_cannot_empty');
        }
        if (!Validator::checkBoolean($this->getCancelado())) {
            $errors['cancelado'] = _t('pedido.cancelado_invalid');
        }
        if (!$this->exists() && trim(app()->getSystem()->getLicenseKey()) == '') {
            $count = self::count();
            if ($count >= 20) {
                $errors['id'] = 'Quantidade de pedidos excedido, adquira uma licença para continuar';
            }
        }
        $this->setDataCriacao(DB::now());
        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
        return $this->toArray();
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
     * Insert a new Pedido into the database and fill instance from database
     * @return self Self instance
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function insert()
    {
        $this->setID(null);
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = DB::insertInto('Pedidos')->values($values)->execute();
            $this->setID($id);
            $this->loadByID();
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Pedido with instance values into database for Código
     * @param array $only Save these fields only, when empty save all fields except id
     * @return int rows affected
     * @throws \MZ\Exception\ValidationException for invalid input data
     */
    public function update($only = [])
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new ValidationException(
                ['id' => _t('pedido.id_cannot_empty')]
            );
        }
        $values = DB::filterValues($values, $only, false);
        unset($values['datacriacao']);
        try {
            $affected = DB::update('Pedidos')
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
     * Delete this instance from database using Código
     * @return integer Number of rows deleted (Max 1)
     * @throws \MZ\Exception\ValidationException for invalid id
     */
    public function delete()
    {
        if (!$this->exists()) {
            throw new ValidationException(
                ['id' => _t('pedido.id_cannot_empty')]
            );
        }
        $result = DB::deleteFrom('Pedidos')
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
                'produtos' => 0,
                'comissao' => 0,
                'servicos' => 0,
                'descontos' => 0
            ];
        } else {
            $query = DB::from('Pedidos p')
                ->select(null)
                ->select('SUM(IF(NOT ISNULL(i.produtoid), i.subtotal, 0)) as produtos')
                ->select('SUM(IF(NOT ISNULL(i.produtoid), i.comissao, 0)) as comissao')
                ->select('SUM(IF(NOT ISNULL(i.servicoid) AND i.preco >= 0, i.subtotal, 0)) as servicos')
                ->select('SUM(IF(NOT ISNULL(i.servicoid) AND i.preco < 0, i.subtotal, 0)) as descontos')
                ->leftJoin('Itens i ON i.pedidoid = p.id AND i.cancelado = ?', $this->getCancelado());
            if ($this->exists()) {
                $query = $query->where('p.id', $this->getID());
            } else {
                $query = $query->where('p.mesaid', $this->getMesaID())
                    ->where('p.tipo', self::TIPO_COMANDA);
            }
            $row = $query->fetch();
        }
        $row['subtotal'] = $row['servicos'] + $row['produtos'] + $row['comissao'];
        $row['total'] = $row['descontos'] + $row['subtotal'];
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
     * Get allowed keys array
     * @return array allowed keys array
     */
    private static function getAllowedKeys()
    {
        $pedido = new self();
        $allowed = Filter::concatKeys('p.', $pedido->toArray());
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
        return Filter::orderBy($order, $allowed, 'p.');
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
    private static function query($condition = [], $order = [])
    {
        $query = DB::from('Pedidos p');
        $condition = self::filterCondition($condition);
        $query = DB::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('p.id DESC');
        return DB::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param array $condition Condition for searching the row
     * @param array $order order rows
     * @return self A filled Pedido or empty instance
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
     * @return self A filled Pedido or empty instance
     * @throws \Exception when register has not found
     */
    public static function findOrFail($condition, $order = [])
    {
        $result = self::find($condition, $order);
        if (!$result->exists()) {
            throw new \Exception(_t('pedido.not_found'), 404);
        }
        return $result;
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
            ->select('SUM(i.subtotal) as subtotal')
            ->select('SUM(i.total) as total')
            ->select('SUM(IF(p.tipo = "Mesa", i.total, 0)) as mesa')
            ->select('SUM(IF(p.tipo = "Comanda", i.total, 0)) as comanda')
            ->select('SUM(IF(p.tipo = "Avulso", i.total, 0)) as avulso')
            ->select('SUM(IF(p.tipo = "Entrega", i.total, 0)) as entrega')
            ->leftJoin('Itens i ON i.pedidoid = p.id AND i.cancelado = ?', 'N')
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
            'subtotal' => $row['total'] + 0,
            'tipo' => [
                'mesa' => $row['mesa'] + 0,
                'comanda' => $row['comanda'] + 0,
                'avulso' => $row['avulso'] + 0,
                'entrega' => $row['entrega'] + 0
            ]
        ];
    }

    /**
     * Find all Pedido
     * @param array  $condition Condition to get all Pedido
     * @param array  $order     Order Pedido
     * @param int    $limit     Limit data into row count
     * @param int    $offset    Start offset to get rows
     * @return self[] List of all rows instanced as Pedido
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
