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

use MZ\Util\Filter;
use MZ\Util\Validator;
use MZ\System\Permissao;
use MZ\Payment\Pagamento;

/**
 * Informações do pedido de venda
 */
class Pedido extends \MZ\Database\Helper
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
     * Movimentação do caixa quando o pedido é pago total ou parcial, somente
     * um caixa pode receber os pagamentos de um pedido
     */
    private $movimentacao_id;
    /**
     * Identificador da sessão de vendas
     */
    private $sessao_id;
    /**
     * Funcionário que criou esse pedido
     */
    private $funcionario_id;
    /**
     * Entregador que fez a entrega do pedido
     */
    private $entregador_id;
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
     * Data de criação do pedido
     */
    private $data_criacao;
    /**
     * Data de agendamento do pedido
     */
    private $data_agendamento;
    /**
     * Data e hora que o entregador saiu para entregar o pedido
     */
    private $data_entrega;
    /**
     * Data de finalização do pedido
     */
    private $data_conclusao;

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
     * @return mixed Código of Pedido
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set ID value to new on param
     * @param  mixed $id new value for ID
     * @return Pedido Self instance
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Identificador da mesa, único quando o pedido não está fechado
     * @return mixed Mesa of Pedido
     */
    public function getMesaID()
    {
        return $this->mesa_id;
    }

    /**
     * Set MesaID value to new on param
     * @param  mixed $mesa_id new value for MesaID
     * @return Pedido Self instance
     */
    public function setMesaID($mesa_id)
    {
        $this->mesa_id = $mesa_id;
        return $this;
    }

    /**
     * Identificador da comanda, único quando o pedido não está fechado
     * @return mixed Comanda of Pedido
     */
    public function getComandaID()
    {
        return $this->comanda_id;
    }

    /**
     * Set ComandaID value to new on param
     * @param  mixed $comanda_id new value for ComandaID
     * @return Pedido Self instance
     */
    public function setComandaID($comanda_id)
    {
        $this->comanda_id = $comanda_id;
        return $this;
    }

    /**
     * Movimentação do caixa quando o pedido é pago total ou parcial, somente
     * um caixa pode receber os pagamentos de um pedido
     * @return mixed Movimentação of Pedido
     */
    public function getMovimentacaoID()
    {
        return $this->movimentacao_id;
    }

    /**
     * Set MovimentacaoID value to new on param
     * @param  mixed $movimentacao_id new value for MovimentacaoID
     * @return Pedido Self instance
     */
    public function setMovimentacaoID($movimentacao_id)
    {
        $this->movimentacao_id = $movimentacao_id;
        return $this;
    }

    /**
     * Identificador da sessão de vendas
     * @return mixed Sessão of Pedido
     */
    public function getSessaoID()
    {
        return $this->sessao_id;
    }

    /**
     * Set SessaoID value to new on param
     * @param  mixed $sessao_id new value for SessaoID
     * @return Pedido Self instance
     */
    public function setSessaoID($sessao_id)
    {
        $this->sessao_id = $sessao_id;
        return $this;
    }

    /**
     * Funcionário que criou esse pedido
     * @return mixed Funcionário of Pedido
     */
    public function getFuncionarioID()
    {
        return $this->funcionario_id;
    }

    /**
     * Set FuncionarioID value to new on param
     * @param  mixed $funcionario_id new value for FuncionarioID
     * @return Pedido Self instance
     */
    public function setFuncionarioID($funcionario_id)
    {
        $this->funcionario_id = $funcionario_id;
        return $this;
    }

    /**
     * Entregador que fez a entrega do pedido
     * @return mixed Entregador of Pedido
     */
    public function getEntregadorID()
    {
        return $this->entregador_id;
    }

    /**
     * Set EntregadorID value to new on param
     * @param  mixed $entregador_id new value for EntregadorID
     * @return Pedido Self instance
     */
    public function setEntregadorID($entregador_id)
    {
        $this->entregador_id = $entregador_id;
        return $this;
    }

    /**
     * Identificador do cliente do pedido
     * @return mixed Cliente of Pedido
     */
    public function getClienteID()
    {
        return $this->cliente_id;
    }

    /**
     * Set ClienteID value to new on param
     * @param  mixed $cliente_id new value for ClienteID
     * @return Pedido Self instance
     */
    public function setClienteID($cliente_id)
    {
        $this->cliente_id = $cliente_id;
        return $this;
    }

    /**
     * Endereço de entrega do pedido, se não informado na venda entrega, o
     * pedido será para viagem
     * @return mixed Localização of Pedido
     */
    public function getLocalizacaoID()
    {
        return $this->localizacao_id;
    }

    /**
     * Set LocalizacaoID value to new on param
     * @param  mixed $localizacao_id new value for LocalizacaoID
     * @return Pedido Self instance
     */
    public function setLocalizacaoID($localizacao_id)
    {
        $this->localizacao_id = $localizacao_id;
        return $this;
    }

    /**
     * Tipo de venda
     * @return mixed Tipo of Pedido
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set Tipo value to new on param
     * @param  mixed $tipo new value for Tipo
     * @return Pedido Self instance
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
     * @return mixed Estado of Pedido
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set Estado value to new on param
     * @param  mixed $estado new value for Estado
     * @return Pedido Self instance
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
        return $this;
    }

    /**
     * Informa quantas pessoas estão na mesa
     * @return mixed Pessoas of Pedido
     */
    public function getPessoas()
    {
        return $this->pessoas;
    }

    /**
     * Set Pessoas value to new on param
     * @param  mixed $pessoas new value for Pessoas
     * @return Pedido Self instance
     */
    public function setPessoas($pessoas)
    {
        $this->pessoas = $pessoas;
        return $this;
    }

    /**
     * Detalhes da reserva ou do pedido
     * @return mixed Descrição of Pedido
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Set Descricao value to new on param
     * @param  mixed $descricao new value for Descricao
     * @return Pedido Self instance
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * Informa quem fechou o pedido e imprimiu a conta
     * @return mixed Fechador do pedido of Pedido
     */
    public function getFechadorID()
    {
        return $this->fechador_id;
    }

    /**
     * Set FechadorID value to new on param
     * @param  mixed $fechador_id new value for FechadorID
     * @return Pedido Self instance
     */
    public function setFechadorID($fechador_id)
    {
        $this->fechador_id = $fechador_id;
        return $this;
    }

    /**
     * Data de impressão da conta do cliente
     * @return mixed Data de impressão of Pedido
     */
    public function getDataImpressao()
    {
        return $this->data_impressao;
    }

    /**
     * Set DataImpressao value to new on param
     * @param  mixed $data_impressao new value for DataImpressao
     * @return Pedido Self instance
     */
    public function setDataImpressao($data_impressao)
    {
        $this->data_impressao = $data_impressao;
        return $this;
    }

    /**
     * Informa se o pedido foi cancelado
     * @return mixed Cancelado of Pedido
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
     * @param  mixed $cancelado new value for Cancelado
     * @return Pedido Self instance
     */
    public function setCancelado($cancelado)
    {
        $this->cancelado = $cancelado;
        return $this;
    }

    /**
     * Informa o motivo do cancelamento
     * @return mixed Motivo of Pedido
     */
    public function getMotivo()
    {
        return $this->motivo;
    }

    /**
     * Set Motivo value to new on param
     * @param  mixed $motivo new value for Motivo
     * @return Pedido Self instance
     */
    public function setMotivo($motivo)
    {
        $this->motivo = $motivo;
        return $this;
    }

    /**
     * Data de criação do pedido
     * @return mixed Data de criação of Pedido
     */
    public function getDataCriacao()
    {
        return $this->data_criacao;
    }

    /**
     * Set DataCriacao value to new on param
     * @param  mixed $data_criacao new value for DataCriacao
     * @return Pedido Self instance
     */
    public function setDataCriacao($data_criacao)
    {
        $this->data_criacao = $data_criacao;
        return $this;
    }

    /**
     * Data de agendamento do pedido
     * @return mixed Data de agendamento of Pedido
     */
    public function getDataAgendamento()
    {
        return $this->data_agendamento;
    }

    /**
     * Set DataAgendamento value to new on param
     * @param  mixed $data_agendamento new value for DataAgendamento
     * @return Pedido Self instance
     */
    public function setDataAgendamento($data_agendamento)
    {
        $this->data_agendamento = $data_agendamento;
        return $this;
    }

    /**
     * Data e hora que o entregador saiu para entregar o pedido
     * @return mixed Data de entrega of Pedido
     */
    public function getDataEntrega()
    {
        return $this->data_entrega;
    }

    /**
     * Set DataEntrega value to new on param
     * @param  mixed $data_entrega new value for DataEntrega
     * @return Pedido Self instance
     */
    public function setDataEntrega($data_entrega)
    {
        $this->data_entrega = $data_entrega;
        return $this;
    }

    /**
     * Data de finalização do pedido
     * @return mixed Data de conclusão of Pedido
     */
    public function getDataConclusao()
    {
        return $this->data_conclusao;
    }

    /**
     * Set DataConclusao value to new on param
     * @param  mixed $data_conclusao new value for DataConclusao
     * @return Pedido Self instance
     */
    public function setDataConclusao($data_conclusao)
    {
        $this->data_conclusao = $data_conclusao;
        return $this;
    }

    /**
     * Convert this instance to array associated key -> value
     * @param  boolean $recursive Allow rescursive conversion of fields
     * @return array All field and values into array format
     */
    public function toArray($recursive = false)
    {
        $pedido = parent::toArray($recursive);
        $pedido['id'] = $this->getID();
        $pedido['mesaid'] = $this->getMesaID();
        $pedido['comandaid'] = $this->getComandaID();
        $pedido['movimentacaoid'] = $this->getMovimentacaoID();
        $pedido['sessaoid'] = $this->getSessaoID();
        $pedido['funcionarioid'] = $this->getFuncionarioID();
        $pedido['entregadorid'] = $this->getEntregadorID();
        $pedido['clienteid'] = $this->getClienteID();
        $pedido['localizacaoid'] = $this->getLocalizacaoID();
        $pedido['tipo'] = $this->getTipo();
        $pedido['estado'] = $this->getEstado();
        $pedido['pessoas'] = $this->getPessoas();
        $pedido['descricao'] = $this->getDescricao();
        $pedido['fechadorid'] = $this->getFechadorID();
        $pedido['dataimpressao'] = $this->getDataImpressao();
        $pedido['cancelado'] = $this->getCancelado();
        $pedido['motivo'] = $this->getMotivo();
        $pedido['datacriacao'] = $this->getDataCriacao();
        $pedido['dataagendamento'] = $this->getDataAgendamento();
        $pedido['dataentrega'] = $this->getDataEntrega();
        $pedido['dataconclusao'] = $this->getDataConclusao();
        return $pedido;
    }

    /**
     * Fill this instance with from array values, you can pass instance to
     * @param  mixed $pedido Associated key -> value to assign into this instance
     * @return Pedido Self instance
     */
    public function fromArray($pedido = [])
    {
        if ($pedido instanceof Pedido) {
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
        if (!array_key_exists('movimentacaoid', $pedido)) {
            $this->setMovimentacaoID(null);
        } else {
            $this->setMovimentacaoID($pedido['movimentacaoid']);
        }
        if (!isset($pedido['sessaoid'])) {
            $this->setSessaoID(null);
        } else {
            $this->setSessaoID($pedido['sessaoid']);
        }
        if (!isset($pedido['funcionarioid'])) {
            $this->setFuncionarioID(null);
        } else {
            $this->setFuncionarioID($pedido['funcionarioid']);
        }
        if (!array_key_exists('entregadorid', $pedido)) {
            $this->setEntregadorID(null);
        } else {
            $this->setEntregadorID($pedido['entregadorid']);
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
            $this->setCancelado(null);
        } else {
            $this->setCancelado($pedido['cancelado']);
        }
        if (!array_key_exists('motivo', $pedido)) {
            $this->setMotivo(null);
        } else {
            $this->setMotivo($pedido['motivo']);
        }
        if (!isset($pedido['datacriacao'])) {
            $this->setDataCriacao(self::now());
        } else {
            $this->setDataCriacao($pedido['datacriacao']);
        }
        if (!array_key_exists('dataagendamento', $pedido)) {
            $this->setDataAgendamento(null);
        } else {
            $this->setDataAgendamento($pedido['dataagendamento']);
        }
        if (!array_key_exists('dataentrega', $pedido)) {
            $this->setDataEntrega(null);
        } else {
            $this->setDataEntrega($pedido['dataentrega']);
        }
        if (!array_key_exists('dataconclusao', $pedido)) {
            $this->setDataConclusao(null);
        } else {
            $this->setDataConclusao($pedido['dataconclusao']);
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
     * @param Pedido $original Original instance without modifications
     */
    public function filter($original)
    {
        $this->setID($original->getID());
        $this->setMesaID(Filter::number($this->getMesaID()));
        $this->setComandaID(Filter::number($this->getComandaID()));
        $this->setMovimentacaoID(Filter::number($this->getMovimentacaoID()));
        $this->setSessaoID(Filter::number($this->getSessaoID()));
        $this->setFuncionarioID(Filter::number($this->getFuncionarioID()));
        $this->setEntregadorID(Filter::number($this->getEntregadorID()));
        $this->setClienteID(Filter::number($this->getClienteID()));
        $this->setLocalizacaoID(Filter::number($this->getLocalizacaoID()));
        $this->setPessoas(Filter::number($this->getPessoas()));
        $this->setDescricao(Filter::string($this->getDescricao()));
        $this->setFechadorID(Filter::number($this->getFechadorID()));
        $this->setDataImpressao(Filter::datetime($this->getDataImpressao()));
        $this->setMotivo(Filter::string($this->getMotivo()));
        $this->setDataCriacao(self::now());
        $this->setDataAgendamento(Filter::datetime($this->getDataAgendamento()));
        $this->setDataEntrega(Filter::datetime($this->getDataEntrega()));
        $this->setDataConclusao(Filter::datetime($this->getDataConclusao()));
    }

    /**
     * Clean instance resources like images and docs
     * @param  Pedido $dependency Don't clean when dependency use same resources
     */
    public function clean($dependency)
    {
    }

    /**
     * Validate fields updating them and throw exception when invalid data has found
     * @return array All field of Pedido in array format
     */
    public function validate()
    {
        global $app;

        $errors = [];
        if (is_null($this->getSessaoID())) {
            $errors['sessaoid'] = 'A sessão não pode ser vazia';
        }
        if (is_null($this->getFuncionarioID())) {
            $errors['funcionarioid'] = 'O funcionário não pode ser vazio';
        }
        if (!Validator::checkInSet($this->getTipo(), self::getTipoOptions())) {
            $errors['tipo'] = 'O tipo não foi informado ou é inválido';
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
            $errors['estado'] = 'O estado não foi informado ou é inválido';
        }
        if (is_null($this->getPessoas())) {
            $errors['pessoas'] = 'A quantidade de pessoas não foi informada';
        }
        if (!Validator::checkBoolean($this->getCancelado())) {
            $errors['cancelado'] = 'A informação de cancelamento não foi informado';
        }
        if (!$this->exists() && trim($app->getSystem()->getLicenseKey()) == '') {
            $count = self::count();
            if ($count >= 20) {
                $errors['id'] = 'Quantidade de pedidos excedido, adquira uma licença para continuar';
            }
        }
        $this->setDataCriacao(self::now());
        if (!empty($errors)) {
            throw new \MZ\Exception\ValidationException($errors);
        }
        return $this->toArray();
    }

    public function checkAccess($operador)
    {
        if ($this->getTipo() == self::TIPO_MESA) {
            if (!$operador->has(Permissao::NOME_PEDIDOMESA)) {
                throw new \Exception('Você não tem permissão para acessar mesas');
            }
        } elseif ($this->getTipo() == self::TIPO_COMANDA) {
            if (!$operador->has(Permissao::NOME_PEDIDOCOMANDA)) {
                throw new \Exception('Você não tem permissão para acessar comandas');
            }
        } elseif ($this->getTipo() == self::TIPO_ENTREGA) {
            if (!$operador->has(Permissao::NOME_ENTREGAPEDIDOS)) {
                throw new \Exception('Você não tem permissão para criar pedidos para entrega');
            } elseif (!$operador->has(Permissao::NOME_ENTREGAADICIONAR) && $this->exists()) {
                throw new \Exception('Você não tem permissão para adicionar produtos no pedido para entrega');
            }
        } else {
            // AVULSA
            if (!$operador->has(Permissao::NOME_PAGAMENTO)) {
                throw new \Exception('Você não tem permissão para criar pedidos para balcão');
            }
        }
        return $this;
    }

    public function validaAcesso($operador)
    {
        $this->checkAccess($operador);
        if (!$this->exists()) {
            return;
        }
        if (!in_array($this->getTipo(), [self::TIPO_MESA, self::TIPO_COMANDA])) {
            return;
        }
        if ($this->getFuncionarioID() == $operador->getID()) {
            return;
        }
        if ($this->getTipo() == self::TIPO_MESA && !$operador->has(Permissao::NOME_MESAS)) {
            $cliente = $this->findFuncionarioID()->findClienteID();
            $msg = sprintf(
                'Apenas o(a) funcionário(a) "%s" poderá realizar pedidos para essa mesa.',
                $cliente->getAssinatura()
            );
            throw new \Exception($msg);
        }
        if ($this->getTipo() == self::TIPO_COMANDA && !$operador->has(Permissao::NOME_COMANDAS)) {
            $cliente = $this->findFuncionarioID()->findClienteID();
            $msg = sprintf(
                'Apenas o(a) funcionário(a) "%s" poderá realizar pedidos para essa comanda.',
                $cliente->getAssinatura()
            );
            throw new \Exception($msg);
        }
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
     * Translate SQL exception into application exception
     * @param  \Exception $e exception to translate into a readable error
     * @return \MZ\Exception\ValidationException new exception translated
     */
    protected function translate($e)
    {
        if (stripos($e->getMessage(), 'PRIMARY') !== false) {
            return new \MZ\Exception\ValidationException([
                'id' => sprintf(
                    'O código "%s" já está cadastrado',
                    $this->getID()
                ),
            ]);
        }
        return parent::translate($e);
    }

    /**
     * Insert a new Pedido into the database and fill instance from database
     * @return Pedido Self instance
     */
    public function insert()
    {
        $this->setID(null);
        $values = $this->validate();
        unset($values['id']);
        try {
            $id = self::getDB()->insertInto('Pedidos')->values($values)->execute();
            $this->loadByID($id);
        } catch (\Exception $e) {
            throw $this->translate($e);
        }
        return $this;
    }

    /**
     * Update Pedido with instance values into database for Código
     * @return Pedido Self instance
     */
    public function update($only = [], $except = false)
    {
        $values = $this->validate();
        if (!$this->exists()) {
            throw new \Exception('O identificador do pedido não foi informado');
        }
        $values = self::filterValues($values, $only, $except);
        unset($values['datacriacao']);
        try {
            self::getDB()
                ->update('Pedidos')
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
     * Delete this instance from database using Código
     * @return integer Number of rows deleted (Max 1)
     */
    public function delete()
    {
        if (!$this->exists()) {
            throw new \Exception('O identificador do pedido não foi informado');
        }
        $result = self::getDB()
            ->deleteFrom('Pedidos')
            ->where('id', $this->getID())
            ->execute();
        return $result;
    }

    /**
     * Load one register for it self with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order associative field name -> [-1, 1]
     * @return Pedido Self instance filled or empty
     */
    public function load($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return $this->fromArray($row);
    }

    /**
     * Load open order by type into this object
     * @return Pedido The object fetched from database or empty when not found
     */
    public function loadByLocal()
    {
        $pedido = new Pedido();
        if ($this->getTipo() == self::TIPO_MESA) {
            $pedido = self::findByMesaID($this->getMesaID());
        } elseif ($this->getTipo() == self::TIPO_COMANDA) {
            $pedido = self::findByComandaID($this->getComandaID());
        }
        $this->fromArray($pedido->toArray());
        return $this;
    }

    /**
     * Load open order by customer and date
     * @return Pedido The object fetched from database or empty when not found
     */
    public function loadAproximado()
    {
        // TODO implementar
        return $this;
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
     * Movimentação do caixa quando o pedido é pago total ou parcial, somente
     * um caixa pode receber os pagamentos de um pedido
     * @return \MZ\Session\Movimentacao The object fetched from database
     */
    public function findMovimentacaoID()
    {
        if (is_null($this->getMovimentacaoID())) {
            return new \MZ\Session\Movimentacao();
        }
        return \MZ\Session\Movimentacao::findByID($this->getMovimentacaoID());
    }

    /**
     * Identificador da sessão de vendas
     * @return \MZ\Session\Sessao The object fetched from database
     */
    public function findSessaoID()
    {
        return \MZ\Session\Sessao::findByID($this->getSessaoID());
    }

    /**
     * Funcionário que criou esse pedido
     * @return \MZ\Employee\Funcionario The object fetched from database
     */
    public function findFuncionarioID()
    {
        return \MZ\Employee\Funcionario::findByID($this->getFuncionarioID());
    }

    /**
     * Entregador que fez a entrega do pedido
     * @return \MZ\Employee\Funcionario The object fetched from database
     */
    public function findEntregadorID()
    {
        if (is_null($this->getEntregadorID())) {
            return new \MZ\Employee\Funcionario();
        }
        return \MZ\Employee\Funcionario::findByID($this->getEntregadorID());
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
     * Informa quem fechou o pedido e imprimiu a conta
     * @return \MZ\Employee\Funcionario The object fetched from database
     */
    public function findFechadorID()
    {
        if (is_null($this->getFechadorID())) {
            return new \MZ\Employee\Funcionario();
        }
        return \MZ\Employee\Funcionario::findByID($this->getFechadorID());
    }

    /**
     * Retorna o total vendido do pedido com informações detalhadas ou resumida
     * @param  bool $resumido informa se deve retornar apenas o total do pedido
     * @return mixed array com os totais detalhados ou apenas o total se for resumido
     */
    public function findTotal($resumido = false)
    {
        $query = self::getDB()->from('Pedidos p')
            ->select(null)
            ->select('SUM(r.precocompra * r.quantidade) as custo')
            ->select('SUM(IF(NOT ISNULL(r.produtoid), r.preco * r.quantidade, 0)) as produtos')
            ->select('SUM(IF(NOT ISNULL(r.produtoid), r.preco * r.quantidade * r.porcentagem / 100, 0)) as comissao')
            ->select('SUM(IF(NOT ISNULL(r.servicoid) AND r.preco >= 0, r.preco * r.quantidade, 0)) as servicos')
            ->select('SUM(IF(NOT ISNULL(r.servicoid) AND r.preco < 0, r.preco * r.quantidade, 0)) as descontos')
            ->leftJoin('Produtos_Pedidos r ON r.pedidoid = p.id AND r.cancelado = ?', $this->getCancelado())
            ->where('p.id', $this->getID());
        $row = $query->fetch();
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
        return Pagamento::rawFindTotal(['pedidoid' => $this->getID()]);
    }

    /**
     * Gets textual and translated Tipo for Pedido
     * @param  int $index choose option from index
     * @return mixed A associative key -> translated representative text or text for index
     */
    public static function getTipoOptions($index = null)
    {
        $options = [
            self::TIPO_MESA => 'Mesa',
            self::TIPO_COMANDA => 'Comanda',
            self::TIPO_AVULSO => 'Balcão',
            self::TIPO_ENTREGA => 'Entrega',
        ];
        if (!is_null($index)) {
            return $options[$index];
        }
        return $options;
    }

    /**
     * Gets textual and translated Estado for Pedido
     * @param  int $index choose option from index
     * @return mixed A associative key -> translated representative text or text for index
     */
    public static function getEstadoOptions($index = null)
    {
        $options = [
            self::ESTADO_FINALIZADO => 'Finalizado',
            self::ESTADO_ATIVO => 'Ativo',
            self::ESTADO_AGENDADO => 'Agendado',
            self::ESTADO_ENTREGA => 'Entrega',
            self::ESTADO_FECHADO => 'Fechado',
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
        $pedido = new Pedido();
        $allowed = Filter::concatKeys('p.', $pedido->toArray());
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
     * @param  array $condition condition to filter rows
     * @param  array $order order rows
     * @return SelectQuery query object with condition statement
     */
    private static function query($condition = [], $order = [])
    {
        $query = self::getDB()->from('Pedidos p');
        $condition = self::filterCondition($condition);
        $query = self::buildOrderBy($query, self::filterOrder($order));
        $query = $query->orderBy('p.id DESC');
        return self::buildCondition($query, $condition);
    }

    /**
     * Search one register with a condition
     * @param  array $condition Condition for searching the row
     * @param  array $order order rows
     * @return Pedido A filled Pedido or empty instance
     */
    public static function find($condition, $order = [])
    {
        $query = self::query($condition, $order)->limit(1);
        $row = $query->fetch() ?: [];
        return new Pedido($row);
    }

    /**
     * Find this object on database using, ID
     * @param  int $id código to find Pedido
     * @return Pedido A filled instance or empty when not found
     */
    public static function findByID($id)
    {
        return self::find([
            'id' => intval($id),
        ]);
    }

    /**
     * Find this object on database using, ID
     * @param  int $id código to find Pedido
     * @return Pedido A filled instance or empty when not found
     */
    public static function findByMesaID($mesa_id)
    {
        return self::find([
            'mesaid' => intval($mesa_id),
            'cancelado' => 'N',
            'tipo' => self::TIPO_MESA,
            '!estado', self::ESTADO_FINALIZADO
        ]);
    }

    /**
     * Find this object on database using, ID
     * @param  int $id código to find Pedido
     * @return Pedido A filled instance or empty when not found
     */
    public static function findByComandaID($comanda_id)
    {
        return self::find([
            'comandaid' => intval($comanda_id),
            'cancelado' => 'N',
            'tipo' => self::TIPO_COMANDA,
            '!estado', self::ESTADO_FINALIZADO
        ]);
    }

    public static function getTicketMedio($sessao_id, $data_inicio = null, $data_fim = null)
    {
        $query = self::getDB()->from('Pedidos p')
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
        $query = self::getDB()->from('Pedidos p')
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
        $query = self::getDB()->from('Pedidos p')
            ->select(null)
            ->select('ROUND(SUM(r.preco * r.quantidade), 4) as subtotal')
            ->select('ROUND(SUM(r.preco * r.quantidade * (r.porcentagem / 100 + 1)), 4) as total')
            ->select('ROUND(SUM(IF(p.tipo = "Mesa", r.preco * r.quantidade * (r.porcentagem / 100 + 1), 0)), 4) as mesa')
            ->select('ROUND(SUM(IF(p.tipo = "Comanda", r.preco * r.quantidade * (r.porcentagem / 100 + 1), 0)), 4) as comanda')
            ->select('ROUND(SUM(IF(p.tipo = "Avulso", r.preco * r.quantidade * (r.porcentagem / 100 + 1), 0)), 4) as avulso')
            ->select('ROUND(SUM(IF(p.tipo = "Entrega", r.preco * r.quantidade * (r.porcentagem / 100 + 1), 0)), 4) as entrega')
            ->leftJoin('Produtos_Pedidos r ON r.pedidoid = p.id AND r.cancelado = ?', 'N')
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
     * @param  array  $condition Condition to get all Pedido
     * @param  array  $order     Order Pedido
     * @param  int    $limit     Limit data into row count
     * @param  int    $offset    Start offset to get rows
     * @return array             List of all rows instanced as Pedido
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
            $result[] = new Pedido($row);
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
