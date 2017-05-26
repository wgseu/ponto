<?php
/*
	Copyright 2014 da MZ Software - MZ Desenvolvimento de Sistemas LTDA
	Este arquivo é parte do programa GrandChef - Sistema para Gerenciamento de Churrascarias, Bares e Restaurantes.
	O GrandChef é um software proprietário; você não pode redistribuí-lo e/ou modificá-lo.
	DISPOSIÇÕES GERAIS
	O cliente não deverá remover qualquer identificação do produto, avisos de direitos autorais,
	ou outros avisos ou restrições de propriedade do GrandChef.

	O cliente não deverá causar ou permitir a engenharia reversa, desmontagem,
	ou descompilação do GrandChef.

	PROPRIEDADE DOS DIREITOS AUTORAIS DO PROGRAMA

	GrandChef é a especialidade do desenvolvedor e seus
	licenciadores e é protegido por direitos autorais, segredos comerciais e outros direitos
	de leis de propriedade.

	O Cliente adquire apenas o direito de usar o software e não adquire qualquer outros
	direitos, expressos ou implícitos no GrandChef diferentes dos especificados nesta Licença.
*/
class NFeDB extends \NFe\Database\Estatico {

	public static function getCaminhoXmlAtual($_nota) {
		$ambiente = NFeUtil::toAmbiente($_nota->getAmbiente());
		$config = \NFe\Core\SEFAZ::getInstance()->getConfiguracao();
		switch ($_nota->getEstado()) {
			case NotaEstado::ASSINADO:
				$path = $config->getPastaXmlAssinado($ambiente);
				break;
			case NotaEstado::PENDENTE:
				$path = $config->getPastaXmlPendente($ambiente);
				break;
			case NotaEstado::PROCESSAMENTO:
				$path = $config->getPastaXmlProcessamento($ambiente);
				break;
			case NotaEstado::DENEGADO:
				$path = $config->getPastaXmlDenegado($ambiente);
				break;
			case NotaEstado::REJEITADO:
				$path = $config->getPastaXmlRejeitado($ambiente);
				break;
			case NotaEstado::CANCELADO:
				$path = $config->getPastaXmlCancelado($ambiente);
				break;
			case NotaEstado::AUTORIZADO:
				$path = $config->getPastaXmlAutorizado($ambiente);
				break;
			default:
				throw new Exception('Não existe XML salvo para o estado "'.
					$_nota->getEstado().'" da nota "'.$_nota->getChave().'"', 404);		
		}
		return $path . '/' . $_nota->getChave() . '.xml';
	}

	private function criarNFCe($_nota)
	{
		/* Informações do pedido */
		$_pedido = ZPedido::getPeloID($_nota->getPedidoID());
		/* Pagamentos */
		$_pagamentos = ZPagamento::getTodosDoPedidoID($_pedido->getID());
		/* Itens do pedido */
		$_itens = ZProdutoPedido::getTodosDoPedidoID($_pedido->getID());
		/* Informações de entrega */
		$_localizacao_entrega = ZLocalizacao::getPeloID($_pedido->getLocalizacaoID());
		$_bairro_entrega = ZBairro::getPeloID($_localizacao_entrega->getBairroID());
		$_cidade_entrega = ZCidade::getPeloID($_bairro_entrega->getCidadeID());
		$_estado_entrega = ZEstado::getPeloID($_cidade_entrega->getEstadoID());
		/* Informações do cliente */
		$_cliente = ZCliente::getPeloID($_pedido->getClienteID());

		$config = \NFe\Core\SEFAZ::getInstance()->getConfiguracao();
		/** Preenchimento da nota **/
		$nota = new \NFe\Core\NFCe();
		$nota->setEmitente($config->getEmitente());
		$nota->setCodigo($_pedido->getID());
		$nota->setSerie($_nota->getSerie());
		$nota->setNumero($_nota->getNumeroInicial());
		if($_nota->isContingencia())
			$nota->setDataEmissao($_nota->setDataLancamento());
		else
			$nota->setDataEmissao(time());
		if($_pedido->isDelivery())
			$nota->setPresenca(\NFe\Core\Nota::PRESENCA_ENTREGA);
		else
			$nota->setPresenca(\NFe\Core\Nota::PRESENCA_PRESENCIAL);
		$_atendente_funcionario = ZFuncionario::getPeloID($_pedido->getFuncionarioID());
		$_atendente = ZCliente::getPeloID($_atendente_funcionario->getClienteID());
		$nota->addObservacao('Operador', $_atendente->getAssinatura());
		switch ($_pedido->getTipo()) {
			case PedidoTipo::MESA:
				$_mesa = ZMesa::getPeloID($_pedido->getMesaID());
				$nota->addObservacao('Local', $_mesa->getNome());
				break;
			case PedidoTipo::COMANDA:
				$_comanda = \MZ\Sale\Comanda::findByID($_pedido->getComandaID());
				$nota->addObservacao('Local', $_comanda->getNome());
				break;
			case PedidoTipo::AVULSO:
				$nota->addObservacao('Local', 'Venda Balcão');
				break;
			case PedidoTipo::ENTREGA:
				if($_pedido->isDelivery()) {
					$_entregador_funcionario = ZFuncionario::getPeloID($_pedido->getEntregadorID());
					$_entregador = ZCliente::getPeloID($_entregador_funcionario->getClienteID());
					$nota->addObservacao('Local', 'Pedido para Entrega');
					$nota->addObservacao('Entregador', $_entregador->getAssinatura());
				} else {
					$nota->addObservacao('Local', 'Pedido para Viagem');
				}
				break;
		}
		$nota->setAmbiente(NFeUtil::toAmbiente($_nota->getAmbiente()));
		$_nota->setDataEmissao($nota->getDataEmissao());
		$_nota = ZNota::atualizar($_nota);
		/* Destinatário */
		$destinatario = null;
		if(!is_null($_cliente->getID()) && (!is_null($_cliente->getCPF()) || $_pedido->isDelivery())) {
			$destinatario = new \NFe\Entity\Destinatario();
			if($_cliente->getTipo() == ClienteTipo::FISICA) {
				$destinatario->setNome($_cliente->getNomeCompleto());
				$destinatario->setCPF($_cliente->getCPF());
			} else {
				$destinatario->setRazaoSocial($_cliente->getSobrenome());
				$destinatario->setCNPJ($_cliente->getCPF());
			}
			$destinatario->setEmail($_cliente->getEmail());
			$destinatario->setTelefone($_cliente->getFone(1));
			$endereco = null;
			if(!is_null($_localizacao_entrega->getID())) {
				$endereco = new \NFe\Entity\Endereco();
				$endereco->setCEP($_localizacao_entrega->getCEP());
				$endereco->getMunicipio()
						 ->setNome($_cidade_entrega->getNome())
						 ->getEstado()
						 ->setNome($_estado_entrega->getNome())
						 ->setUF($_estado_entrega->getUF());
				$endereco->setBairro($_bairro_entrega->getNome());
				$endereco->setLogradouro($_localizacao_entrega->getLogradouro());
				$endereco->setNumero($_localizacao_entrega->getNumero());
				$endereco->setComplemento($_localizacao_entrega->getComplemento());
			}
			$destinatario->setEndereco($endereco);
		}
		$nota->setDestinatario($destinatario);
		/* Transporte */
		if($_pedido->isDelivery()) {
			$transportador = new \NFe\Entity\Transporte\Transportador();
			$transportador->setRazaoSocial($nota->getEmitente()->getRazaoSocial());
			$transportador->setCNPJ($nota->getEmitente()->getCNPJ());
			$transportador->setIE($nota->getEmitente()->getIE());
			$transportador->setEndereco($nota->getEmitente()->getEndereco());
			$nota->getTransporte()
				 ->setFrete(\NFe\Entity\Transporte::FRETE_EMITENTE)
				 ->setRetencao(null)
				 ->setVeiculo(null)
				 ->setReboque(null)
				 ->setTransportador($transportador);
		} else {
			$nota->getTransporte()
				 ->setFrete(\NFe\Entity\Transporte::FRETE_NENHUM);
		}
		/* Produtos */
		$total_produtos = 0.00;
		$desconto = 0.00;
		$servicos = 0.00;
		$frete = 0.00;
		foreach ($_itens as $_item) {
			// descontos
			if (is_less($_item->getPreco(), 0.00)) {
				$desconto += -$_item->getSubtotal();
				continue;
			}
			// serviços e taxas
			if($_item->isServico()) {
				if ($_item->getServicoID() == ZServico::ENTREGA_ID) {
					$frete += $_item->getSubtotal();
				} else {
					$servicos += $_item->getSubtotal();
				}
				continue;
			}
			$_produto = ZProduto::getPeloID($_item->getProdutoID());
			$_tributacao = ZTributacao::getPeloID($_produto->getTributacaoID());
			$_unidade = ZUnidade::getPeloID($_produto->getUnidadeID());
			$_origem = ZOrigem::getPeloID($_tributacao->getOrigemID());
			$_operacao = ZOperacao::getPeloID($_tributacao->getOperacaoID());
			$_imposto = ZImposto::getPeloID($_tributacao->getImpostoID());
			$produto = new \NFe\Entity\Produto();
			$produto->setPedido($_pedido->getID());
			$produto->setCodigo($_produto->getID());
			$produto->setCodigoBarras($_produto->getCodigoBarras());
			$produto->setDescricao($_produto->getDescricao());
			$produto->setUnidade($_unidade->processaSigla($_item->getQuantidade(), $_produto->getConteudo()));
			$produto->setPreco($_item->getSubvenda());
			$produto->setDespesas($_item->getComissao());
			// pode acontecer de alterar o preço para mais em vez de dar desconto
			$descontos = $_item->getDescontos();
			if(is_less($descontos, 0.00))
				$produto->setDespesas($produto->getDespesas() - $descontos);
			else
				$produto->setDesconto($descontos);
			$produto->setQuantidade($_unidade->processaQuantidade($_item->getQuantidade(), $_produto->getConteudo()));
			$produto->setNCM($_tributacao->getNCM());
			$produto->setCEST($_tributacao->getCEST());
			$produto->setCFOP($_operacao->getCodigo());
			/* Impostos */
			$imposto = NFeUtil::toImposto($_imposto);
			if($imposto instanceof \NFe\Entity\Imposto\ICMS\Base) {
				$imposto->setOrigem($_origem->getCodigo());
			}
			$produto->addImposto($imposto);
			$nota->addProduto($produto);
			$total_produtos += $produto->getBase();
		}
		$soma_desconto = 0.00;
		$soma_servicos = 0.00;
		$soma_frete = 0.00;
		$count = count($nota->getProdutos());
		$i = 0;
		$produtos = $nota->getProdutos();
		foreach ($produtos as $produto) {
			$i++;
			if ($i == $count) {
				$_desconto = $desconto - $soma_desconto;
				$_servicos = $servicos - $soma_servicos;
				$_frete = $frete - $soma_frete;
			} else {
				$_base = $produto->getBase();
				$_desconto = $desconto * $_base / $total_produtos;
				$_servicos = $servicos * $_base / $total_produtos;
				$_frete = $frete * $_base / $total_produtos;
			}
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
		}
		$total_pago = 0.00;
		$troco = 0.00;
		$dinheiro = array(); // guarda os pagamentos em dinheiro
		$outros = array(); // guarda os outros pagamentos
		$pagamentos = array();
		foreach ($_pagamentos as $key => $_pagamento) {
			$_forma = ZFormaPagto::getPeloID($_pagamento->getFormaPagtoID());
			if(is_less($_pagamento->getTotal(), 0.00)) {
				$troco += $_pagamento->getTotal();
				continue;
			}
			$total_pago += $_pagamento->getTotal();
			$pagamento = new \NFe\Entity\Pagamento();
			$pagamento->setForma(NFeUtil::toFormaPagamento($_forma->getTipo()));
			$pagamento->setValor($_pagamento->getTotal());
			// $pagamento->setCredenciadora('60889128000422');
			if($_forma->getTipo() == FormaPagtoTipo::CARTAO) {
				$_cartao = ZCartao::getPeloID($_pagamento->getCartaoID());
				$pagamento->setBandeira(NFeUtil::toBandeira($_cartao->getDescricao()));
			}
			// $pagamento->setAutorizacao('110011');
			$new_key = 'pg_' . $key;
			$pagamentos[$new_key] = $pagamento;
			if ($_forma->getTipo() == FormaPagtoTipo::DINHEIRO) {
				$dinheiro[$new_key] = $pagamento;
			} else {
				$outros[$new_key] = $pagamento;
			}
		}
		$troco_total = $troco;
		$arrays = array($dinheiro, $outros);
		foreach ($arrays as $array) {
			foreach ($array as $key => $pagamento) {
				if (!is_less($troco, 0.00)) {
					break;
				}
				if(is_greater($pagamento->getValor(), -$troco)) {
					$pagamento->setValor($pagamento->getValor() + $troco);
					$troco = 0.00;
				} else {
					$troco += $pagamento->getValor();
					unset($pagamentos[$key]);
				}
			}
		}
		$nota->setPagamentos($pagamentos);
		$nota->addObservacao('Troco', \NFe\Common\Util::toCurrency(-$troco_total));
		return $nota;
	}

	/**
	 * Obtém as notas pendentes de envio, em contingência e corrigidas após
	 * rejeitadas
	 */
	public function getNotasAbertas($inicio = null, $quantidade = null) {
		$notas = array();
		$config = \NFe\Core\SEFAZ::getInstance()->getConfiguracao();
		$_notas = ZNota::getAbertas($inicio, $quantidade);
		foreach ($_notas as $_nota) {
			try {
				/** Notas em contingência **/
				// Só envia o mesmo XML se não tiver ocorrido rejeição
				if($_nota->isContingencia() && $_nota->getEstado() == NotaEstado::ASSINADO) {
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
			} catch (Exception $e) {
				$_nota->setCorrigido('N');
				$_nota = ZNota::atualizar($_nota);
				$_evento = ZEvento::log(
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
	public function getNotasPendentes($inicio = null, $quantidade = null) {
		$tarefas = array();
		$config = \NFe\Core\SEFAZ::getInstance()->getConfiguracao();
		if ($config->isOffline()) {
			return $tarefas;
		}
		$_notas = ZNota::getPendentes($inicio, $quantidade);
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
			} catch (Exception $e) {
				$_nota->setCorrigido('N');
				$_nota = ZNota::atualizar($_nota);
				$_evento = ZEvento::log(
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
	public function getNotasTarefas($inicio = null, $quantidade = null) {
		$tarefas = array();
		$config = \NFe\Core\SEFAZ::getInstance()->getConfiguracao();
		if ($config->isOffline()) {
			return $tarefas;
		}
		$emitente = $config->getEmitente();
		$estado = $emitente->getEndereco()->getMunicipio()->getEstado();
		$_notas = ZNota::getTarefas($inicio, $quantidade);
		foreach ($_notas as $_nota) {
			try {
				$nota = new \NFe\Core\NFCe();
				$tarefa = new \NFe\Task\Tarefa();
				$tarefa->setID($_nota->getID());
				switch ($_nota->getAcao()) {
					case NotaAcao::AUTORIZAR:
						$xmlfile = self::getCaminhoXmlAtual($_nota);
						$dom = $nota->load($xmlfile);
						// Notas em contingência podem precisar de consultas quando não se sabe o status
						$tarefa->setAcao(\NFe\Task\Tarefa::ACAO_CONSULTAR);
						$tarefa->setNota($nota);
						$tarefa->setDocumento($dom);
						$tarefas[] = $tarefa;
						break;
					case NotaAcao::CANCELAR:
						$xmlfile = self::getCaminhoXmlAtual($_nota);
						$dom = $nota->load($xmlfile);

						// cancelamento sem protocolo significa:
						// consulta para posterior cancelamento ou inutilização 
						if(is_null($_nota->getProtocolo()) || $_nota->getEstado() == NotaEstado::REJEITADO) {
							$tarefa->setAcao(\NFe\Task\Tarefa::ACAO_CONSULTAR);
						} else {
							$tarefa->setAcao(\NFe\Task\Tarefa::ACAO_CANCELAR);
							$nota->setJustificativa($_nota->getMotivo());
						}
						$tarefa->setNota($nota);
						$tarefa->setDocumento($dom);
						$tarefas[] = $tarefa;
						break;
					case NotaAcao::INUTILIZAR:
						$ambiente = NFeUtil::toAmbiente($_nota->getAmbiente());
						$inutilizacao = new \NFe\Task\Inutilizacao();
						$inutilizacao->setUF($estado->getUF());
						$inutilizacao->setCNPJ($emitente->getCNPJ());
						$inutilizacao->setAmbiente($ambiente);
						$inutilizacao->setAno(date('Y', strtotime($_nota->getDataLancamento())));
						$inutilizacao->setModelo($nota->getModelo()); // NFCe 65
						$inutilizacao->setSerie($_nota->getSerie());
						$inutilizacao->setInicio($_nota->getNumeroInicial());
						$inutilizacao->setFinal($_nota->getNumeroFinal());
						$inutilizacao->setJustificativa($_nota->getMotivo());

						$tarefa->setAcao(\NFe\Task\Tarefa::ACAO_INUTILIZAR);
						$tarefa->setAgente($inutilizacao);
						$tarefas[] = $tarefa;
						break;
				}
			} catch (Exception $e) {
				$_nota->setCorrigido('N');
				$_nota = ZNota::atualizar($_nota);
				$_evento = ZEvento::log(
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
