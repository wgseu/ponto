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

use MZ\Product\Servico;
use MZ\Account\Cliente;
use MZ\Payment\FormaPagto;
use MZ\Payment\Pagamento;
use MZ\Sale\Item;
use MZ\Sale\Pedido;
use MZ\Invoice\Evento;
use MZ\Invoice\Nota;
use MZ\Database\DB;

class NFeDB extends \NFe\Database\Estatico
{
    const CANCEL_SUFFIX = '-procEventoNFe';

    public static function getCaminhoXmlAtual($_nota)
    {
        $ambiente = \NFeUtil::toAmbiente($_nota->getAmbiente());
        $config = \NFe\Core\SEFAZ::getInstance()->getConfiguracao();
        switch ($_nota->getEstado()) {
            case Nota::ESTADO_ASSINADO:
                $path = $config->getPastaXmlAssinado($ambiente);
                break;
            case Nota::ESTADO_PENDENTE:
                $path = $config->getPastaXmlPendente($ambiente);
                break;
            case Nota::ESTADO_PROCESSAMENTO:
                $path = $config->getPastaXmlProcessamento($ambiente);
                break;
            case Nota::ESTADO_DENEGADO:
                $path = $config->getPastaXmlDenegado($ambiente);
                break;
            case Nota::ESTADO_REJEITADO:
                $path = $config->getPastaXmlRejeitado($ambiente);
                break;
            case Nota::ESTADO_CANCELADO:
                $path = $config->getPastaXmlCancelado($ambiente);
                $arquivos = [
                    'nota' => $path . '/' . $_nota->getChave() . '.xml',
                    'evento' => $path . '/' . $_nota->getChave() . self::CANCEL_SUFFIX . '.xml'
                ];
                return $arquivos;
            case Nota::ESTADO_AUTORIZADO:
                $path = $config->getPastaXmlAutorizado($ambiente);
                break;
            case Nota::ESTADO_INUTILIZADO:
                $path = $config->getPastaXmlInutilizado($ambiente);
                break;
            default:
                throw new \Exception(
                    sprintf(
                        'Não existe XML salvo para o estado "%s" da nota "%s"',
                        $_nota->getEstado(),
                        $_nota->getChave()
                    ),
                    404
                );
        }
        return $path . '/' . $_nota->getChave() . '.xml';
    }

    private function criarNFCe($_nota)
    {
        /* Informações do pedido */
        $_pedido = $_nota->findPedidoID();
        /* Pagamentos */
        $_pagamentos = Pagamento::findAll(
            [
                'pedidoid' => $_pedido->getID(),
                'cancelado' => 'N'
            ],
            ['id' => 1]
        );
        /* Itens do pedido */
        $_itens = Item::findAll(
            [
                'pedidoid' => $_pedido->getID(),
                'cancelado' => 'N'
            ],
            ['id' => 1]
        );
        /* Informações de entrega */
        $_localizacao_entrega = $_pedido->findLocalizacaoID();
        $_bairro_entrega = $_localizacao_entrega->findBairroID();
        $_cidade_entrega = $_bairro_entrega->findCidadeID();
        $_estado_entrega = $_cidade_entrega->findEstadoID();
        /* Informações do cliente */
        $_cliente = $_pedido->findClienteID();

        $config = \NFe\Core\SEFAZ::getInstance()->getConfiguracao();
        /** Preenchimento da nota **/
        $nota = new \NFe\Core\NFCe();
        $nota->setEmitente($config->getEmitente());
        $nota->setCodigo($_pedido->getID());
        $nota->setSerie($_nota->getSerie());
        $nota->setNumero($_nota->getNumeroInicial());
        if ($_nota->isContingencia()) {
            $nota->setEmissao(\NFe\Core\Nota::EMISSAO_CONTINGENCIA);
            $nota->setDataEmissao($_nota->getDataLancamento());
            $nota->setDataContingencia($_nota->getDataLancamento());
            $nota->setJustificativa(\NFeUtil::fixEncoding($_nota->getMotivo()));
        } else {
            $nota->setEmissao(\NFe\Core\Nota::EMISSAO_NORMAL);
            $nota->setDataEmissao(DB::now());
        }
        if ($_pedido->isDelivery()) {
            $nota->setPresenca(\NFe\Core\Nota::PRESENCA_ENTREGA);
        } else {
            $nota->setPresenca(\NFe\Core\Nota::PRESENCA_PRESENCIAL);
        }
        $_atendente_funcionario = $_pedido->findFuncionarioID();
        $_atendente = $_atendente_funcionario->findClienteID();
        $nota->addObservacao('Operador', \NFeUtil::fixEncoding($_atendente->getAssinatura()));
        switch ($_pedido->getTipo()) {
            case Pedido::TIPO_MESA:
                $_mesa = $_pedido->findMesaID();
                $nota->addObservacao('Local', \NFeUtil::fixEncoding($_mesa->getNome()));
                break;
            case Pedido::TIPO_COMANDA:
                $_comanda = $_pedido->findComandaID();
                $nota->addObservacao('Local', \NFeUtil::fixEncoding($_comanda->getNome()));
                break;
            case Pedido::TIPO_AVULSO:
                $nota->addObservacao('Local', \NFeUtil::fixEncoding('Venda Balcão'));
                break;
            case Pedido::TIPO_ENTREGA:
                if ($_pedido->isDelivery()) {
                    $_entregador_funcionario = $_pedido->findEntregadorID();
                    $_entregador = $_entregador_funcionario->findClienteID();
                    $nota->addObservacao('Local', \NFeUtil::fixEncoding('Pedido para Entrega'));
                    $nota->addObservacao('Entregador', \NFeUtil::fixEncoding($_entregador->getAssinatura()));
                } else {
                    $nota->addObservacao('Local', \NFeUtil::fixEncoding('Pedido para Viagem'));
                }
                break;
        }
        $nota->setAmbiente(\NFeUtil::toAmbiente($_nota->getAmbiente()));
        $_nota->setDataEmissao(DB::now($nota->getDataEmissao()));
        $_nota->update();
        /* Destinatário */
        $destinatario = null;
        if ($_cliente->exists() && (!is_null($_cliente->getCPF()) || $_pedido->isDelivery())) {
            $destinatario = new \NFe\Entity\Destinatario();
            if ($_cliente->getTipo() == Cliente::TIPO_FISICA) {
                $destinatario->setNome(\NFeUtil::fixEncoding($_cliente->getNomeCompleto()));
                $destinatario->setCPF($_cliente->getCPF());
            } else {
                $destinatario->setRazaoSocial(\NFeUtil::fixEncoding($_cliente->getSobrenome()));
                $destinatario->setCNPJ($_cliente->getCPF());
            }
            $destinatario->setEmail($_cliente->getEmail());
            $destinatario->setTelefone($_cliente->getTelefone()->getNumero());
            $endereco = null;
            if ($_localizacao_entrega->exists()) {
                $endereco = new \NFe\Entity\Endereco();
                $endereco->setCEP($_localizacao_entrega->getCEP());
                $endereco->getMunicipio()
                         ->setNome(\NFeUtil::fixEncoding($_cidade_entrega->getNome()))
                         ->getEstado()
                         ->setNome(\NFeUtil::fixEncoding($_estado_entrega->getNome()))
                         ->setUF($_estado_entrega->getUF());
                $endereco->setBairro(\NFeUtil::fixEncoding($_bairro_entrega->getNome()));
                $endereco->setLogradouro(\NFeUtil::fixEncoding($_localizacao_entrega->getLogradouro()));
                $endereco->setNumero($_localizacao_entrega->getNumero());
                $endereco->setComplemento(\NFeUtil::fixEncoding($_localizacao_entrega->getComplemento()));
            }
            $destinatario->setEndereco($endereco);
        }
        $nota->setDestinatario($destinatario);
        /* Transporte */
        if ($_pedido->isDelivery()) {
            $transportador = new \NFe\Entity\Transporte\Transportador();
            $transportador->setRazaoSocial(\NFeUtil::fixEncoding($nota->getEmitente()->getRazaoSocial()));
            $transportador->setCNPJ($nota->getEmitente()->getCNPJ());
            $transportador->setIE($nota->getEmitente()->getIE());
            $transportador->setEndereco($nota->getEmitente()->getEndereco());
            $nota->getTransporte()
                 ->setFrete(\NFe\Entity\Transporte::FRETE_REMETENTE)
                 ->setRetencao(null)
                 ->setVeiculo(null)
                 ->setReboque(null)
                 ->setTransportador($transportador);
        } else {
            $nota->getTransporte()
                 ->setFrete(\NFe\Entity\Transporte::FRETE_NENHUM);
        }
        /* Produtos */
        $total_produtos = 0;
        $desconto = 0;
        $servicos = 0;
        $frete = 0;
        foreach ($_itens as $_item) {
            // descontos
            if (is_less($_item->getPreco(), 0)) {
                $desconto += -$_item->getSubtotal();
                continue;
            }
            // serviços e taxas
            if ($_item->isServico()) {
                if ($_item->getServicoID() == Servico::ENTREGA_ID) {
                    $frete += $_item->getSubtotal();
                } else {
                    $servicos += $_item->getSubtotal();
                }
                continue;
            }
            $_produto = $_item->findProdutoID();
            $_tributacao = $_produto->findTributacaoID();
            $_unidade = $_produto->findUnidadeID();
            $_origem = $_tributacao->findOrigemID();
            $_operacao = $_tributacao->findOperacaoID();
            $_imposto = $_tributacao->findImpostoID();
            $produto = new \NFe\Entity\Produto();
            $produto->setPedido($_pedido->getID());
            $produto->setCodigo($_produto->getID());
            $produto->setCodigoBarras(\NFeUtil::fixBarCode($_produto->getCodigoBarras()));
            $produto->setCodigoTributario($produto->getCodigoBarras());
            $produto->setDescricao(\NFeUtil::fixEncoding($_produto->getDescricao()));
            $produto->setUnidade($_unidade->processaSigla($_item->getQuantidade(), $_produto->getConteudo()));
            $produto->setPreco($_item->getSubvenda());
            $produto->setDespesas($_item->getComissao());
            // pode acontecer de alterar o preço para mais em vez de dar desconto
            $descontos = $_item->getDescontos();
            if (is_less($descontos, 0)) {
                $produto->setDespesas($produto->getDespesas() - $descontos);
            } else {
                $produto->setDesconto($descontos);
            }
            $produto->setQuantidade($_unidade->processaQuantidade($_item->getQuantidade(), $_produto->getConteudo()));
            $produto->setNCM($_tributacao->getNCM());
            $produto->setCEST($_tributacao->getCEST());
            $produto->setCFOP($_operacao->getCodigo());
            /* Impostos */
            $imposto = \NFeUtil::toImposto($_imposto);
            if ($imposto instanceof \NFe\Entity\Imposto\ICMS\Base) {
                $imposto->setOrigem($_origem->getCodigo());
            }
            $produto->addImposto($imposto);
            $nota->addProduto($produto);
            $total_produtos += $produto->getBase();
        }
        $soma_desconto = 0;
        $soma_servicos = 0;
        $soma_frete = 0;
        $count = count($nota->getProdutos());
        $i = 0;
        $produtos = $nota->getProdutos();
        $produto_maximo = null;
        // distribui o desconto de forma proporcional para todos os produtos
        foreach ($produtos as $produto) {
            // limita o desconto
            $_base = $produto->getBase();
            $_desconto = $desconto * $_base / $total_produtos;
            $_servicos = $servicos * $_base / $total_produtos;
            $_frete = $frete * $_base / $total_produtos;
            do {
                $i++;
                $old_desconto = $produto->getDesconto();
                $old_servicos = $produto->getDespesas();
                $old_frete = $produto->getFrete();
                $produto->setDesconto($_desconto);
                $produto->setDespesas($_servicos);
                $produto->setFrete($_frete);
                $soma_desconto += floatval($produto->getDesconto(true));
                $soma_servicos += floatval($produto->getDespesas(true));
                $soma_frete += floatval($produto->getFrete(true));
                $produto->setDesconto($_desconto + $old_desconto);
                $produto->setDespesas($_servicos + $old_servicos);
                $produto->setFrete($_frete + $old_frete);
                /* aplica o desconto restante em um produto com maior saldo */
                if (is_null($produto_maximo) || $produto->getBase() > $produto_maximo->getBase()) {
                    $produto_maximo = $produto;
                }
                if ($i == $count) {
                    $_desconto = $desconto - $soma_desconto;
                    $_servicos = $servicos - $soma_servicos;
                    $_frete = $frete - $soma_frete;
                    $produto = $produto_maximo;
                }
                /* fim do desconto restante */
            } while ($i == $count);
        }
        $totais = $nota->getTotais();
        $saldo = $totais['nota'];
        $troco = 0;
        $pagamentos = [];
        foreach ($_pagamentos as $key => $_pagamento) {
            $_forma = $_pagamento->findFormaPagtoID();
            if (is_less($_pagamento->getTotal(), 0)) {
                $troco += $_pagamento->getTotal();
                continue;
            }
            $pagamento = new \NFe\Entity\Pagamento();
            $pagamento->setForma(\NFeUtil::toFormaPagamento($_forma->getTipo()));
            $pagamento->setValor($_pagamento->getTotal());
            // $pagamento->setCredenciadora('60889128000422');
            if ($_forma->getTipo() == FormaPagto::TIPO_CARTAO) {
                $_cartao = $_pagamento->findCartaoID();
                $pagamento->setBandeira(\NFeUtil::toBandeira($_cartao->getDescricao()));
            }
            // $pagamento->setAutorizacao('110011');
            $saldo -= floatval($pagamento->getValor(true));
            $pagamentos[] = $pagamento;
        }
        if (is_less($troco, 0)) {
            $pagamento = new \NFe\Entity\Pagamento();
            $pagamento->setValor($troco);
            $saldo -= floatval($pagamento->getValor(true));
            $pagamentos[] = $pagamento;
        }
        if (count($pagamentos) == 0) {
            $pagamento = new \NFe\Entity\Pagamento();
            $pagamento->setForma(\NFeUtil::toFormaPagamento(FormaPagto::TIPO_DINHEIRO));
            $pagamento->setValor(0);
            $pagamentos[] = $pagamento;
        }
        $nota->setPagamentos($pagamentos);
        // corrige centavos a mais no pagamento
        if (!is_equal($saldo, 0) && !is_null($produto_maximo)) {
            if (is_less($saldo, 0)) {
                $produto_maximo->setDespesas($produto_maximo->getDespesas() - $saldo);
            } else {
                $produto_maximo->setDesconto($produto_maximo->getDesconto() + $saldo);
            }
        }
        return $nota;
    }

    /**
     * Obtém as notas pendentes de envio, em contingência e corrigidas após
     * rejeitadas
     */
    public function getNotasAbertas($inicio = null, $quantidade = null)
    {
        $notas = [];
        $config = \NFe\Core\SEFAZ::getInstance()->getConfiguracao();
        $_notas = Nota::findAllOpen($quantidade, $inicio);
        foreach ($_notas as $_nota) {
            try {
                /** Notas em contingência **/
                // Só envia o mesmo XML se não tiver ocorrido rejeição
                if ($_nota->isContingencia() && $_nota->getEstado() == Nota::ESTADO_ASSINADO) {
                    // não tenta enviar notas em contingência quando estiver offline
                    if ($config->isOffline()) {
                        continue;
                    }
                    // a nota entrou em contingência, mas nunca foi enviada
                    $xmlfile = self::getCaminhoXmlAtual($_nota);
                    $nota = new \NFe\Core\NFCe();
                    $nota->load($xmlfile);
                    $notas[] = $nota;
                    continue;
                }
                /** Novos envios e correções **/
                $nota = $this->criarNFCe($_nota);
                $notas[] = $nota;
            } catch (\Exception $e) {
                $_nota->setCorrigido('N');
                $_nota->update();
                $_evento = Evento::log(
                    $_nota->getID(),
                    $_nota->getEstado(),
                    $e->getMessage(),
                    $e->getCode()
                );
            }
        }
        return $notas;
    }

    /**
     * Obtém as notas em processamento para consulta e possível protocolação
     */
    public function getNotasPendentes($inicio = null, $quantidade = null)
    {
        $tarefas = [];
        $config = \NFe\Core\SEFAZ::getInstance()->getConfiguracao();
        if ($config->isOffline()) {
            return $tarefas;
        }
        $_notas = Nota::findAllPending($quantidade, $inicio);
        foreach ($_notas as $_nota) {
            try {
                $xmlfile = self::getCaminhoXmlAtual($_nota);
                $nota = new \NFe\Core\NFCe();
                $dom = $nota->load($xmlfile);

                $recibo = new \NFe\Task\Recibo();
                $recibo->setNumero($_nota->getRecibo());
                $recibo->setAmbiente($nota->getAmbiente());
                $recibo->setModelo($nota->getModelo());
                
                $tarefa = new \NFe\Task\Tarefa();
                $tarefa->setID($_nota->getID());
                $tarefa->setAcao(\NFe\Task\Tarefa::ACAO_CONSULTAR);
                $tarefa->setNota($nota);
                $tarefa->setAgente($recibo);
                $tarefa->setDocumento($dom);
                
                $tarefas[] = $tarefa;
            } catch (\Exception $e) {
                $_nota->setCorrigido('N');
                $_nota->update();
                $_evento = Evento::log(
                    $_nota->getID(),
                    $_nota->getEstado(),
                    $e->getMessage(),
                    $e->getCode()
                );
            }
        }
        return $tarefas;
    }

    /**
     * Obtém as tarefas de inutilização, cancelamento e consulta de notas
     * pendentes que entraram em contingência
     */
    public function getNotasTarefas($inicio = null, $quantidade = null)
    {
        $tarefas = [];
        $config = \NFe\Core\SEFAZ::getInstance()->getConfiguracao();
        if ($config->isOffline()) {
            return $tarefas;
        }
        $emitente = $config->getEmitente();
        $estado = $emitente->getEndereco()->getMunicipio()->getEstado();
        $_notas = Nota::findAllTasks($quantidade, $inicio);
        foreach ($_notas as $_nota) {
            try {
                $nota = new \NFe\Core\NFCe();
                $tarefa = new \NFe\Task\Tarefa();
                $tarefa->setID($_nota->getID());
                switch ($_nota->getAcao()) {
                    case Nota::ACAO_AUTORIZAR:
                        $xmlfile = self::getCaminhoXmlAtual($_nota);
                        $dom = $nota->load($xmlfile);
                        // Notas em contingência podem precisar de consultas quando não se sabe o status
                        $tarefa->setAcao(\NFe\Task\Tarefa::ACAO_CONSULTAR);
                        $tarefa->setNota($nota);
                        $tarefa->setDocumento($dom);
                        $tarefas[] = $tarefa;
                        break;
                    case Nota::ACAO_CANCELAR:
                        $xmlfile = self::getCaminhoXmlAtual($_nota);
                        $dom = $nota->load($xmlfile);

                        // cancelamento sem protocolo significa:
                        // consulta para posterior cancelamento ou inutilização
                        if (is_null($_nota->getProtocolo()) || $_nota->getEstado() == Nota::ESTADO_REJEITADO) {
                            $tarefa->setAcao(\NFe\Task\Tarefa::ACAO_CONSULTAR);
                        } else {
                            $tarefa->setAcao(\NFe\Task\Tarefa::ACAO_CANCELAR);
                            $nota->setJustificativa(\NFeUtil::fixEncoding($_nota->getMotivo()));
                        }
                        $tarefa->setNota($nota);
                        $tarefa->setDocumento($dom);
                        $tarefas[] = $tarefa;
                        break;
                    case Nota::ACAO_INUTILIZAR:
                        $ambiente = \NFeUtil::toAmbiente($_nota->getAmbiente());
                        $inutilizacao = new \NFe\Task\Inutilizacao();
                        $inutilizacao->setUF($estado->getUF());
                        $inutilizacao->setCNPJ($emitente->getCNPJ());
                        $inutilizacao->setAmbiente($ambiente);
                        $inutilizacao->setAno(date('Y', strtotime($_nota->getDataLancamento())));
                        $inutilizacao->setModelo($nota->getModelo()); // NFCe 65
                        $inutilizacao->setSerie($_nota->getSerie());
                        $inutilizacao->setInicio($_nota->getNumeroInicial());
                        $inutilizacao->setFinal($_nota->getNumeroFinal());
                        $inutilizacao->setJustificativa(\NFeUtil::fixEncoding($_nota->getMotivo()));

                        $tarefa->setAcao(\NFe\Task\Tarefa::ACAO_INUTILIZAR);
                        $tarefa->setAgente($inutilizacao);
                        $tarefas[] = $tarefa;
                        break;
                }
            } catch (\Exception $e) {
                $_nota->setCorrigido('N');
                $_nota->update();
                $_evento = Evento::log(
                    $_nota->getID(),
                    $_nota->getEstado(),
                    $e->getMessage(),
                    $e->getCode()
                );
            }
        }
        return $tarefas;
    }
}
