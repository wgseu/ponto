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
class PedidoTipo
{
    const MESA = 'Mesa';
    const COMANDA = 'Comanda';
    const AVULSO = 'Avulso';
    const ENTREGA = 'Entrega';
}
class PedidoEstado
{
    const FINALIZADO = 'Finalizado';
    const ATIVO = 'Ativo';
    const AGENDADO = 'Agendado';
    const ENTREGA = 'Entrega';
    const FECHADO = 'Fechado';
}

class ZPedido
{
    private $id;
    private $mesa_id;
    private $comanda_id;
    private $movimentacao_id;
    private $sessao_id;
    private $funcionario_id;
    private $entregador_id;
    private $cliente_id;
    private $localizacao_id;
    private $tipo;
    private $estado;
    private $pessoas;
    private $descricao;
    private $fechador_id;
    private $data_impressao;
    private $cancelado;
    private $data_criacao;
    private $data_agendamento;
    private $data_entrega;
    private $data_conclusao;

    public function __construct($pedido = [])
    {
        if (is_array($pedido)) {
            $this->setID(isset($pedido['id'])?$pedido['id']:null);
            $this->setMesaID(isset($pedido['mesaid'])?$pedido['mesaid']:null);
            $this->setComandaID(isset($pedido['comandaid'])?$pedido['comandaid']:null);
            $this->setMovimentacaoID(isset($pedido['movimentacaoid'])?$pedido['movimentacaoid']:null);
            $this->setSessaoID(isset($pedido['sessaoid'])?$pedido['sessaoid']:null);
            $this->setFuncionarioID(isset($pedido['funcionarioid'])?$pedido['funcionarioid']:null);
            $this->setEntregadorID(isset($pedido['entregadorid'])?$pedido['entregadorid']:null);
            $this->setClienteID(isset($pedido['clienteid'])?$pedido['clienteid']:null);
            $this->setLocalizacaoID(isset($pedido['localizacaoid'])?$pedido['localizacaoid']:null);
            $this->setTipo(isset($pedido['tipo'])?$pedido['tipo']:null);
            $this->setEstado(isset($pedido['estado'])?$pedido['estado']:null);
            $this->setPessoas(isset($pedido['pessoas'])?$pedido['pessoas']:null);
            $this->setDescricao(isset($pedido['descricao'])?$pedido['descricao']:null);
            $this->setFechadorID(isset($pedido['fechadorid'])?$pedido['fechadorid']:null);
            $this->setDataImpressao(isset($pedido['dataimpressao'])?$pedido['dataimpressao']:null);
            $this->setCancelado(isset($pedido['cancelado'])?$pedido['cancelado']:null);
            $this->setDataCriacao(isset($pedido['datacriacao'])?$pedido['datacriacao']:null);
            $this->setDataAgendamento(isset($pedido['dataagendamento'])?$pedido['dataagendamento']:null);
            $this->setDataEntrega(isset($pedido['dataentrega'])?$pedido['dataentrega']:null);
            $this->setDataConclusao(isset($pedido['dataconclusao'])?$pedido['dataconclusao']:null);
        }
    }

    public function getID()
    {
        return $this->id;
    }

    public function setID($id)
    {
        $this->id = $id;
    }

    public function getMesaID()
    {
        return $this->mesa_id;
    }

    public function setMesaID($mesa_id)
    {
        $this->mesa_id = $mesa_id;
    }

    public function getComandaID()
    {
        return $this->comanda_id;
    }

    public function setComandaID($comanda_id)
    {
        $this->comanda_id = $comanda_id;
    }

    public function getMovimentacaoID()
    {
        return $this->movimentacao_id;
    }

    public function setMovimentacaoID($movimentacao_id)
    {
        $this->movimentacao_id = $movimentacao_id;
    }

    public function getSessaoID()
    {
        return $this->sessao_id;
    }

    public function setSessaoID($sessao_id)
    {
        $this->sessao_id = $sessao_id;
    }

    public function getFuncionarioID()
    {
        return $this->funcionario_id;
    }

    public function setFuncionarioID($funcionario_id)
    {
        $this->funcionario_id = $funcionario_id;
    }

    public function getEntregadorID()
    {
        return $this->entregador_id;
    }

    public function setEntregadorID($entregador_id)
    {
        $this->entregador_id = $entregador_id;
    }

    public function getClienteID()
    {
        return $this->cliente_id;
    }

    public function setClienteID($cliente_id)
    {
        $this->cliente_id = $cliente_id;
    }

    /**
     * Endereço de entrega do pedido
     */
    public function getLocalizacaoID()
    {
        return $this->localizacao_id;
    }

    /**
     * Endereço de entrega do pedido
     */
    public function setLocalizacaoID($localizacao_id)
    {
        $this->localizacao_id = $localizacao_id;
    }

    public function getTipo()
    {
        return $this->tipo;
    }

    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    public function getEstado()
    {
        return $this->estado;
    }

    public function setEstado($estado)
    {
        $this->estado = $estado;
    }

    /**
     * Informa quantas pessoas estão na mesa
     */
    public function getPessoas()
    {
        return $this->pessoas;
    }

    /**
     * Informa quantas pessoas estão na mesa
     */
    public function setPessoas($pessoas)
    {
        $this->pessoas = $pessoas;
    }

    /**
     * Detalhes da reserva
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Detalhes da reserva
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }

    public function getFechadorID()
    {
        return $this->fechador_id;
    }

    public function setFechadorID($fechador_id)
    {
        $this->fechador_id = $fechador_id;
    }

    public function getDataImpressao()
    {
        return $this->data_impressao;
    }

    public function setDataImpressao($data_impressao)
    {
        $this->data_impressao = $data_impressao;
    }

    public function getCancelado()
    {
        return $this->cancelado;
    }

    public function isCancelado()
    {
        return $this->cancelado == 'Y';
    }

    public function setCancelado($cancelado)
    {
        $this->cancelado = $cancelado;
    }

    public function getDataCriacao()
    {
        return $this->data_criacao;
    }

    public function setDataCriacao($data_criacao)
    {
        $this->data_criacao = $data_criacao;
    }

    public function getDataAgendamento()
    {
        return $this->data_agendamento;
    }

    public function setDataAgendamento($data_agendamento)
    {
        $this->data_agendamento = $data_agendamento;
    }

    public function getDataEntrega()
    {
        return $this->data_entrega;
    }

    public function setDataEntrega($data_entrega)
    {
        $this->data_entrega = $data_entrega;
    }

    public function getDataConclusao()
    {
        return $this->data_conclusao;
    }

    public function setDataConclusao($data_conclusao)
    {
        $this->data_conclusao = $data_conclusao;
    }

    /**
     * Informa se o pedido é para entrega
     */
    public function isDelivery()
    {
        return $this->getTipo() == self::TIPO_ENTREGA && !is_null($this->getLocalizacaoID());
    }

    public function toArray()
    {
        $pedido = [];
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
        $pedido['datacriacao'] = $this->getDataCriacao();
        $pedido['dataagendamento'] = $this->getDataAgendamento();
        $pedido['dataentrega'] = $this->getDataEntrega();
        $pedido['dataconclusao'] = $this->getDataConclusao();
        return $pedido;
    }

    public static function getPeloID($id)
    {
        $query = \DB::$pdo->from('Pedidos')
                         ->where(['id' => $id]);
        return new Pedido($query->fetch());
    }

    public static function getPelaMesaID($mesa_id)
    {
        $query = \DB::$pdo->from('Pedidos')
                         ->where(['mesaid' => $mesa_id, 'cancelado' => 'N', 'tipo' => self::TIPO_MESA])
                         ->where('estado <> ?', Pedido::ESTADO_FINALIZADO);
        return new Pedido($query->fetch());
    }

    public static function getPelaComandaID($comanda_id)
    {
        $query = \DB::$pdo->from('Pedidos')
                         ->where(['comandaid' => $comanda_id, 'cancelado' => 'N', 'tipo' => self::TIPO_COMANDA])
                         ->where('estado <> ?', Pedido::ESTADO_FINALIZADO);
        return new Pedido($query->fetch());
    }

    public static function getPeloLocal($tipo, $mesa_id, $comanda_id)
    {
        if ($tipo == self::TIPO_MESA) {
            return self::getPelaMesaID($mesa_id);
        }
        if ($tipo == self::TIPO_COMANDA) {
            return self::getPelaComandaID($comanda_id);
        }
        return new Pedido();
    }

    public static function getTicketMedio($sessao_id, $data_inicio = null, $data_fim = null)
    {
        $query = \DB::$pdo->from('Pedidos p')
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
        $total = self::getTotal($sessao_id, $data_inicio, $data_fim);
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
        $query = \DB::$pdo->from('Pedidos p')
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

    public static function getTotal($sessao_id, $data_inicio = null, $data_fim = null)
    {
        $query = \DB::$pdo->from('Pedidos p')
                         ->select(null)
                         ->select('ROUND(SUM(pdp.preco * pdp.quantidade), 4) as subtotal')
                         ->select('ROUND(SUM(pdp.preco * pdp.quantidade * (pdp.porcentagem / 100 + 1)), 4) as total')
                         ->select('ROUND(SUM(IF(p.tipo = "Mesa", pdp.preco * pdp.quantidade * (pdp.porcentagem / 100 + 1), 0)), 4) as mesa')
                         ->select('ROUND(SUM(IF(p.tipo = "Comanda", pdp.preco * pdp.quantidade * (pdp.porcentagem / 100 + 1), 0)), 4) as comanda')
                         ->select('ROUND(SUM(IF(p.tipo = "Avulso", pdp.preco * pdp.quantidade * (pdp.porcentagem / 100 + 1), 0)), 4) as avulso')
                         ->select('ROUND(SUM(IF(p.tipo = "Entrega", pdp.preco * pdp.quantidade * (pdp.porcentagem / 100 + 1), 0)), 4) as entrega')
                         ->leftJoin('Produtos_Pedidos pdp ON pdp.pedidoid = p.id AND pdp.cancelado = "N"')
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
        return ['total' => $row['total'] + 0,
                     'subtotal' => $row['total'] + 0,
                     'tipo' => [
                        'mesa' => $row['mesa'] + 0,
                        'comanda' => $row['comanda'] + 0,
                        'avulso' => $row['avulso'] + 0,
                        'entrega' => $row['entrega'] + 0]
                    ];
    }

    public static function getTotalDetalhado($id, $cancelado = null)
    {
        $query = \DB::$pdo->from('Pedidos p')
                         ->select(null)
                         ->select('SUM(pdp.precocompra * pdp.quantidade) as custo')
                         ->select('SUM(IF(NOT ISNULL(pdp.produtoid), pdp.preco * pdp.quantidade, 0)) as produtos')
                         ->select('SUM(IF(NOT ISNULL(pdp.produtoid), pdp.preco * pdp.quantidade * pdp.porcentagem / 100, 0)) as comissao')
                         ->select('SUM(IF(NOT ISNULL(pdp.servicoid) AND pdp.preco >= 0, pdp.preco * pdp.quantidade, 0)) as servicos')
                         ->select('SUM(IF(NOT ISNULL(pdp.servicoid) AND pdp.preco < 0, pdp.preco * pdp.quantidade, 0)) as descontos')
                         ->leftJoin('Produtos_Pedidos pdp ON pdp.pedidoid = p.id AND (? = "" OR pdp.cancelado = ?)', strval($cancelado), strval($cancelado))
                         ->where('p.id', $id);
        $row = $query->fetch();
        $row['subtotal'] = $row['servicos'] + $row['produtos'] + $row['comissao'];
        $row['total'] = $row['descontos'] + $row['subtotal'];
        return $row;
    }

    public static function getTotalDoLocal($tipo, $mesa_id, $comanda_id)
    {
        if ($tipo == self::TIPO_COMANDA) {
            $pedido = self::getPelaComandaID($comanda_id);
        } else {
            $pedido = self::getPelaMesaID($mesa_id);
        }
        $info = self::getTotalDetalhado($pedido->getID(), 'N');
        return $info['total'];
    }

    private static function validarCampos(&$pedido)
    {
        $erros = [];
        $pedido['mesaid'] = trim($pedido['mesaid']);
        if (strlen($pedido['mesaid']) == 0) {
            $pedido['mesaid'] = null;
        } elseif (!is_numeric($pedido['mesaid'])) {
            $erros['mesaid'] = 'O número da mesa não é válido';
        }
        $pedido['comandaid'] = trim($pedido['comandaid']);
        if (strlen($pedido['comandaid']) == 0) {
            $pedido['comandaid'] = null;
        } elseif (!is_numeric($pedido['comandaid'])) {
            $erros['comandaid'] = 'O número da comanda não é válido';
        }
        $pedido['movimentacaoid'] = trim($pedido['movimentacaoid']);
        if (strlen($pedido['movimentacaoid']) == 0) {
            $pedido['movimentacaoid'] = null;
        } elseif (!is_numeric($pedido['movimentacaoid'])) {
            $erros['movimentacaoid'] = 'O número da movimentacao não é válido';
        }
        if (!is_numeric($pedido['sessaoid'])) {
            $erros['sessaoid'] = 'O número da sessao não é válido';
        }
        if (!is_numeric($pedido['funcionarioid'])) {
            $erros['funcionarioid'] = 'O funcionario informado não é válido';
        }
        $pedido['entregadorid'] = trim($pedido['entregadorid']);
        if (strlen($pedido['entregadorid']) == 0) {
            $pedido['entregadorid'] = null;
        } elseif (!is_numeric($pedido['entregadorid'])) {
            $erros['entregadorid'] = 'O EntregadorID não é um número';
        }
        $pedido['clienteid'] = trim($pedido['clienteid']);
        if (strlen($pedido['clienteid']) == 0) {
            $pedido['clienteid'] = null;
        } elseif (!is_numeric($pedido['clienteid'])) {
            $erros['clienteid'] = 'O ClienteID não é um número';
        }
        $pedido['localizacaoid'] = trim($pedido['localizacaoid']);
        if (strlen($pedido['localizacaoid']) == 0) {
            $pedido['localizacaoid'] = null;
        } elseif (!is_numeric($pedido['localizacaoid'])) {
            $erros['localizacaoid'] = 'A LocalizacaoID não é um número';
        }
        $pedido['tipo'] = trim($pedido['tipo']);
        if (strlen($pedido['tipo']) == 0) {
            $pedido['tipo'] = null;
        } elseif (!in_array($pedido['tipo'], ['Mesa', 'Comanda', 'Avulso', 'Entrega'])) {
            $erros['tipo'] = 'O Tipo informado não é válido';
        }
        $pedido['estado'] = trim($pedido['estado']);
        if (strlen($pedido['estado']) == 0) {
            $pedido['estado'] = null;
        } elseif (!in_array($pedido['estado'], ['Finalizado', 'Ativo', 'Agendado', 'Entrega', 'Fechado'])) {
            $erros['estado'] = 'O Estado informado não é válido';
        }
        if (!is_numeric($pedido['pessoas'])) {
            $erros['pessoas'] = 'A Pessoas não é um número';
        } else {
            $pedido['pessoas'] = intval($pedido['pessoas']);
        }
        $pedido['descricao'] = strip_tags(trim($pedido['descricao']));
        if (strlen($pedido['descricao']) == 0) {
            $pedido['descricao'] = null;
        }
        $pedido['fechadorid'] = trim($pedido['fechadorid']);
        if (strlen($pedido['fechadorid']) == 0) {
            $pedido['fechadorid'] = null;
        } elseif (!is_numeric($pedido['fechadorid'])) {
            $erros['fechadorid'] = 'O FechadorID não foi informado';
        }
        $pedido['dataimpressao'] = strval($pedido['dataimpressao']);
        if (strlen($pedido['dataimpressao']) == 0) {
            $pedido['dataimpressao'] = null;
        } else {
            $time = strtotime($pedido['dataimpressao']);
            if ($time === false) {
                $erros['dataimpressao'] = 'A data de impressão é inválida';
            } else {
                $pedido['dataimpressao'] = date('Y-m-d H:i:s', $time);
            }
        }
        $pedido['cancelado'] = trim($pedido['cancelado']);
        if (strlen($pedido['cancelado']) == 0) {
            $pedido['cancelado'] = 'N';
        } elseif (!in_array($pedido['cancelado'], ['Y', 'N'])) {
            $erros['cancelado'] = 'O Cancelado informado não é válido';
        }
        $pedido['datacriacao'] = date('Y-m-d H:i:s');
        $pedido['dataagendamento'] = null;
        $pedido['dataentrega'] = null;
        $pedido['dataconclusao'] = null;
        if (!empty($erros)) {
            throw new \MZ\Exception\ValidationException($erros);
        }
    }

    private static function handleException(&$e)
    {
        if (stripos($e->getMessage(), 'PRIMARY') !== false) {
            throw new \MZ\Exception\ValidationException(['id' => 'O ID informado já está cadastrado']);
        }
    }

    public static function cadastrar($pedido)
    {
        global $app;

        if (trim($app->getSystem()->getLicenseKey()) == '') {
            $count = self::getCount();
            if ($count >= 20) {
                throw new \Exception('Quantidade de pedidos excedido, adquira uma licença para continuar', 401);
            }
        }
        $_pedido = $pedido->toArray();
        self::validarCampos($_pedido);
        try {
            $_pedido['id'] = \DB::$pdo->insertInto('Pedidos')->values($_pedido)->execute();
        } catch (\Exception $e) {
            self::handleException($e);
            throw $e;
        }
        return self::findByID($_pedido['id']);
    }

    public static function atualizar($pedido)
    {
        $_pedido = $pedido->toArray();
        if (!$_pedido['id']) {
            throw new \MZ\Exception\ValidationException(['id' => 'O id do pedido não foi informado']);
        }
        self::validarCampos($_pedido);
        $campos = [
            // 'mesaid',
            // 'comandaid',
            // 'movimentacaoid',
            // 'sessaoid',
            // 'funcionarioid',
            // 'entregadorid',
            // 'clienteid',
            // 'localizacaoid',
            // 'tipo',
            'estado',
            // 'pessoas',
            // 'descricao',
            'fechadorid',
            'dataimpressao',
            // 'cancelado',
            // 'dataagendamento',
            // 'dataentrega',
            // 'dataconclusao',
        ];
        try {
            $query = \DB::$pdo->update('Pedidos');
            $query = $query->set(array_intersect_key($_pedido, array_flip($campos)));
            $query = $query->where('id', $_pedido['id']);
            $query->execute();
        } catch (\Exception $e) {
            self::handleException($e);
            throw $e;
        }
        return self::findByID($_pedido['id']);
    }

    public function validaAcesso($funcionario = null)
    {
        if (is_null($funcionario)) {
            $funcionario = logged_employee();
        }
        if ($this->getTipo() == self::TIPO_MESA && !$funcionario->has(Permissao::NOME_PEDIDOMESA)) {
            throw new \Exception('Você não tem permissão para acessar mesas');
        } elseif ($this->getTipo() == self::TIPO_COMANDA && !$funcionario->has(Permissao::NOME_PEDIDOCOMANDA)) {
            throw new \Exception('Você não tem permissão para acessar comandas');
        }
        if (!$this->exists()) {
            return;
        }
        if (!in_array($this->getTipo(), [self::TIPO_MESA, self::TIPO_COMANDA])) {
            return;
        }
        if ($this->getFuncionarioID() == $funcionario->getID()) {
            return;
        }
        if ($this->getTipo() == self::TIPO_MESA && !$funcionario->has(Permissao::NOME_MESAS)) {
            $funcionario_pedido = $this->findFuncionarioID();
            $cliente_funcionario_pedido = $funcionario_pedido->findClienteID();
            $msg = sprintf(
                'Apenas o(a) funcionário(a) "%s" poderá realizar pedidos para essa mesa.',
                $cliente_funcionario_pedido->getAssinatura()
            );
            throw new \Exception($msg);
        }
        if ($this->getTipo() == self::TIPO_COMANDA && !$funcionario->has(Permissao::NOME_COMANDAS)) {
            $funcionario_pedido = $this->findFuncionarioID();
            $cliente_funcionario_pedido = $funcionario_pedido->findClienteID();
            $msg = sprintf(
                'Apenas o(a) funcionário(a) "%s" poderá realizar pedidos para essa comanda.',
                $cliente_funcionario_pedido->getAssinatura()
            );
            throw new \Exception($msg);
        }
    }

    private static function initSearch(
        $busca,
        $cliente_id,
        $funcionario_id,
        $tipo,
        $estado,
        $cancelado,
        $data_inicio,
        $data_fim,
        $sessao_id,
        $caixa_id,
        $movimentacao_id
    ) {
        $query = \DB::$pdo->from('Pedidos p')
                         ->orderBy('p.id DESC');
        $sessaoid = null;
        $busca = trim($busca);
        if (is_numeric($busca)) {
            $query = $query->where('p.id', intval($busca));
        } elseif (substr($busca, 0, 1) == '#') {
            $sessaoid = intval(substr($busca, 1));
            $query = $query->where('p.sessaoid', $sessaoid);
        } elseif ($busca != '') {
            $query = $query->where('p.descricao LIKE ?', '%'.$busca.'%');
        }
        if (is_numeric($cliente_id)) {
            $query = $query->where('p.clienteid', intval($cliente_id));
        }
        if (is_numeric($funcionario_id)) {
            $query = $query->where('p.funcionarioid', intval($funcionario_id));
        }
        $tipo = trim($tipo);
        if ($tipo != '') {
            $query = $query->where('p.tipo', $tipo);
        }
        $estado = trim($estado);
        if ($estado != '') {
            $query = $query->where('p.estado', $estado);
        }
        $cancelado = trim($cancelado);
        if ($cancelado != '') {
            $query = $query->where('p.cancelado', $cancelado);
        }
        if (!is_null($data_inicio) && is_null($sessaoid)) {
            $query = $query->where('p.datacriacao >= ?', date('Y-m-d', $data_inicio));
        }
        if (!is_null($data_fim) && is_null($sessaoid)) {
            $query = $query->where('p.datacriacao <= ?', date('Y-m-d 23:59:59', $data_fim));
        }
        if (is_numeric($sessao_id)) {
            $query = $query->where('p.sessaoid', $sessao_id);
        }
        if (is_numeric($movimentacao_id)) {
            $query = $query->where('p.movimentacaoid', $movimentacao_id);
        }
        return $query;
    }

    public static function getTodos(
        $busca = null,
        $cliente_id = null,
        $funcionario_id = null,
        $tipo = null,
        $estado = null,
        $cancelado = null,
        $data_inicio = null,
        $data_fim = null,
        $sessao_id = null,
        $caixa_id = null,
        $movimentacao_id = null,
        $inicio = null,
        $quantidade = null
    ) {
        $query = self::initSearch(
            $busca,
            $cliente_id,
            $funcionario_id,
            $tipo,
            $estado,
            $cancelado,
            $data_inicio,
            $data_fim,
            $sessao_id,
            $caixa_id,
            $movimentacao_id
        );
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_pedidos = $query->fetchAll();
        $pedidos = [];
        foreach ($_pedidos as $pedido) {
            $pedidos[] = new Pedido($pedido);
        }
        return $pedidos;
    }

    public static function getCount(
        $busca = null,
        $cliente_id = null,
        $funcionario_id = null,
        $tipo = null,
        $estado = null,
        $cancelado = null,
        $data_inicio = null,
        $data_fim = null,
        $sessao_id = null,
        $caixa_id = null,
        $movimentacao_id = null
    ) {
        $query = self::initSearch(
            $busca,
            $cliente_id,
            $funcionario_id,
            $tipo,
            $estado,
            $cancelado,
            $data_inicio,
            $data_fim,
            $sessao_id,
            $caixa_id,
            $movimentacao_id
        );
        return $query->count();
    }

    private static function initSearchDaMesaID($mesa_id)
    {
        return   \DB::$pdo->from('Pedidos')
                         ->where(['mesaid' => $mesa_id])
                         ->orderBy('id ASC');
    }

    public static function getTodosDaMesaID($mesa_id, $inicio = null, $quantidade = null)
    {
        $query = self::initSearchDaMesaID($mesa_id);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_pedidos = $query->fetchAll();
        $pedidos = [];
        foreach ($_pedidos as $pedido) {
            $pedidos[] = new Pedido($pedido);
        }
        return $pedidos;
    }

    public static function getCountDaMesaID($mesa_id)
    {
        $query = self::initSearchDaMesaID($mesa_id);
        return $query->count();
    }

    private static function initSearchDaSessaoID($sessao_id)
    {
        return   \DB::$pdo->from('Pedidos')
                         ->where(['sessaoid' => $sessao_id])
                         ->orderBy('id ASC');
    }

    public static function getTodosDaSessaoID($sessao_id, $inicio = null, $quantidade = null)
    {
        $query = self::initSearchDaSessaoID($sessao_id);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_pedidos = $query->fetchAll();
        $pedidos = [];
        foreach ($_pedidos as $pedido) {
            $pedidos[] = new Pedido($pedido);
        }
        return $pedidos;
    }

    public static function getCountDaSessaoID($sessao_id)
    {
        $query = self::initSearchDaSessaoID($sessao_id);
        return $query->count();
    }

    private static function initSearchDoFuncionarioID($funcionario_id)
    {
        return   \DB::$pdo->from('Pedidos')
                         ->where(['funcionarioid' => $funcionario_id])
                         ->orderBy('id ASC');
    }

    public static function getTodosDoFuncionarioID($funcionario_id, $inicio = null, $quantidade = null)
    {
        $query = self::initSearchDoFuncionarioID($funcionario_id);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_pedidos = $query->fetchAll();
        $pedidos = [];
        foreach ($_pedidos as $pedido) {
            $pedidos[] = new Pedido($pedido);
        }
        return $pedidos;
    }

    public static function getCountDoFuncionarioID($funcionario_id)
    {
        $query = self::initSearchDoFuncionarioID($funcionario_id);
        return $query->count();
    }

    private static function initSearchDoClienteID($cliente_id)
    {
        return   \DB::$pdo->from('Pedidos')
                         ->where(['clienteid' => $cliente_id])
                         ->orderBy('id ASC');
    }

    public static function getTodosDoClienteID($cliente_id, $inicio = null, $quantidade = null)
    {
        $query = self::initSearchDoClienteID($cliente_id);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_pedidos = $query->fetchAll();
        $pedidos = [];
        foreach ($_pedidos as $pedido) {
            $pedidos[] = new Pedido($pedido);
        }
        return $pedidos;
    }

    public static function getCountDoClienteID($cliente_id)
    {
        $query = self::initSearchDoClienteID($cliente_id);
        return $query->count();
    }

    private static function initSearchDoEntregadorID($entregador_id)
    {
        return   \DB::$pdo->from('Pedidos')
                         ->where(['entregadorid' => $entregador_id])
                         ->orderBy('id ASC');
    }

    public static function getTodosDoEntregadorID($entregador_id, $inicio = null, $quantidade = null)
    {
        $query = self::initSearchDoEntregadorID($entregador_id);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_pedidos = $query->fetchAll();
        $pedidos = [];
        foreach ($_pedidos as $pedido) {
            $pedidos[] = new Pedido($pedido);
        }
        return $pedidos;
    }

    public static function getCountDoEntregadorID($entregador_id)
    {
        $query = self::initSearchDoEntregadorID($entregador_id);
        return $query->count();
    }

    private static function initSearchDaLocalizacaoID($localizacao_id)
    {
        return   \DB::$pdo->from('Pedidos')
                         ->where(['localizacaoid' => $localizacao_id])
                         ->orderBy('id ASC');
    }

    public static function getTodosDaLocalizacaoID($localizacao_id, $inicio = null, $quantidade = null)
    {
        $query = self::initSearchDaLocalizacaoID($localizacao_id);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_pedidos = $query->fetchAll();
        $pedidos = [];
        foreach ($_pedidos as $pedido) {
            $pedidos[] = new Pedido($pedido);
        }
        return $pedidos;
    }

    public static function getCountDaLocalizacaoID($localizacao_id)
    {
        $query = self::initSearchDaLocalizacaoID($localizacao_id);
        return $query->count();
    }

    private static function initSearchDaComandaID($comanda_id)
    {
        return   \DB::$pdo->from('Pedidos')
                         ->where(['comandaid' => $comanda_id])
                         ->orderBy('id ASC');
    }

    public static function getTodosDaComandaID($comanda_id, $inicio = null, $quantidade = null)
    {
        $query = self::initSearchDaComandaID($comanda_id);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_pedidos = $query->fetchAll();
        $pedidos = [];
        foreach ($_pedidos as $pedido) {
            $pedidos[] = new Pedido($pedido);
        }
        return $pedidos;
    }

    public static function getCountDaComandaID($comanda_id)
    {
        $query = self::initSearchDaComandaID($comanda_id);
        return $query->count();
    }

    private static function initSearchDaMovimentacaoID($movimentacao_id)
    {
        return   \DB::$pdo->from('Pedidos')
                         ->where(['movimentacaoid' => $movimentacao_id])
                         ->orderBy('id ASC');
    }

    public static function getTodosDaMovimentacaoID($movimentacao_id, $inicio = null, $quantidade = null)
    {
        $query = self::initSearchDaMovimentacaoID($movimentacao_id);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_pedidos = $query->fetchAll();
        $pedidos = [];
        foreach ($_pedidos as $pedido) {
            $pedidos[] = new Pedido($pedido);
        }
        return $pedidos;
    }

    public static function getCountDaMovimentacaoID($movimentacao_id)
    {
        $query = self::initSearchDaMovimentacaoID($movimentacao_id);
        return $query->count();
    }

    private static function initSearchDoFechadorID($fechador_id)
    {
        return   \DB::$pdo->from('Pedidos')
                         ->where(['fechadorid' => $fechador_id])
                         ->orderBy('id ASC');
    }

    public static function getTodosDoFechadorID($fechador_id, $inicio = null, $quantidade = null)
    {
        $query = self::initSearchDoFechadorID($fechador_id);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        $_pedidos = $query->fetchAll();
        $pedidos = [];
        foreach ($_pedidos as $pedido) {
            $pedidos[] = new Pedido($pedido);
        }
        return $pedidos;
    }

    public static function getCountDoFechadorID($fechador_id)
    {
        $query = self::initSearchDoFechadorID($fechador_id);
        return $query->count();
    }

    private static function initSearchComandas($funcionario_id, $inativas, $busca)
    {
        $query = \DB::$pdo->from('Pedidos p')
                         ->select(null)
                         ->select('cm.*')
                         ->select('p.estado')
                         ->select('c.nome as cliente')
                         ->select('p.descricao as observacao')
                         ->rightJoin('Comandas cm ON p.comandaid = cm.id AND p.tipo = "Comanda" AND p.cancelado = "N" AND p.estado <> "Finalizado"')
                         ->leftJoin('Clientes c ON c.id = p.clienteid')
                         ->groupBy('cm.id');
        if (!is_null($funcionario_id)) {
            $query = $query->orderBy('IF(p.funcionarioid = ?, 1, 0) DESC', $funcionario_id);
        }
        $busca = trim($busca);
        if (is_numeric($busca)) {
            $query = $query->where('cm.id', $busca);
        } elseif ($busca != '') {
            $query = $query->where('cm.nome LIKE ?', '%'.$busca.'%');
        }
        if (!$inativas) {
            $query = $query->where('cm.ativa', 'Y');
        }
        $query = $query->orderBy('cm.id ASC');
        return $query;
    }

    public static function getTodasComandas($funcionario_id = null, $inativas = false, $busca = null, $inicio = null, $quantidade = null)
    {
        $query = self::initSearchComandas($funcionario_id, $inativas, $busca);
        if (!is_null($inicio) && !is_null($quantidade)) {
            $query = $query->limit($quantidade)->offset($inicio);
        }
        return $query->fetchAll();
    }

    public static function getCountComandas($funcionario_id, $inativas = false, $busca = null)
    {
        $query = self::initSearchComandas($funcionario_id, $inativas, $busca);
        $query = $query->select(null)->groupBy(null)->select('COUNT(DISTINCT cm.id)');
        return (int) $query->fetchColumn();
    }
}
