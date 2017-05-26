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
class NFeAPI extends \NFe\Common\Ajuste {

	private $sefaz;
	private $external_emitente;
	private $external_regime;

	const CANCEL_SUFFIX = '-procEventoNFe';

	public function getExternalEmitente()
	{
		return $this->external_emitente;
	}

	public function getExternalRegime()
	{
		return $this->external_regime;
	}

	public function init() {

		global $__empresa__;
		global $__localizacao__;
		global $__bairro__;
		global $__cidade__;
		global $__estado__;

		$this->external_emitente = ZEmitente::getPeloID('1');
		if(is_null($this->external_emitente->getID()))
			throw new \Exception('As configurações fiscais do emitente não foram ajustadas', 500);
		\NFe\Log\Logger::getInstance()->setDirectory(dirname(__DIR__).'/logs');
		$this->external_regime = ZRegime::getPeloID($this->external_emitente->getRegimeID());
		$this->sefaz = \NFe\Core\SEFAZ::getInstance();
		$this->sefaz->setConfiguracao($this);
		$this->setBanco(new NFeDB());
		$this->getBanco()->getIBPT()->setOffline($this->isOffline());
		$chave_publica = WWW_ROOT . get_document_url($this->external_emitente->getChavePublica(), 'cert');
		$this->setArquivoChavePublica($chave_publica);
		$chave_privada = WWW_ROOT . get_document_url($this->external_emitente->getChavePrivada(), 'cert');
		$this->setArquivoChavePrivada($chave_privada);
		$xml_base = WWW_ROOT . get_document_url('', 'xml');
		$this->setPastaXmlBase($xml_base);
		$this->setToken($this->external_emitente->getToken());
		$this->setCSC($this->external_emitente->getCSC());
		$this->setTokenIBPT($this->external_emitente->getIBPT());
		$this->setSincrono('Y');

		/* Emitente */
		$emitente = new \NFe\Entity\Emitente();
		$emitente->setRazaoSocial($__empresa__->getSobrenome());
		$emitente->setFantasia($__empresa__->getNome());
		$emitente->setCNPJ($__empresa__->getCPF());
		$emitente->setTelefone($__empresa__->getFone(1));
		$emitente->setIE($__empresa__->getRG());
		$emitente->setIM($__empresa__->getIM());
		$emitente->setRegime($this->external_regime->getCodigo());

		$endereco = new \NFe\Entity\Endereco();
		$endereco->setCEP($__localizacao__->getCEP());
		$endereco->getMunicipio()
				 ->setNome($__cidade__->getNome())
				 ->getEstado()
				 ->setNome($__estado__->getNome())
				 ->setUF($__estado__->getUF());
		$endereco->setBairro($__bairro__->getNome());
		$endereco->setLogradouro($__localizacao__->getLogradouro());
		$endereco->setNumero($__localizacao__->getNumero());

		$emitente->setEndereco($endereco);
		$this->setEmitente($emitente);

		return $this;
	}

	private function deleteXmlAnteriores($nota) {
		$filename = $this->getPastaXmlRejeitado($nota->getAmbiente()) . '/' . $nota->getID() . '.xml';
		if(file_exists($filename))
			unlink($filename);

		$filename = $this->getPastaXmlProcessamento($nota->getAmbiente()) . '/' . $nota->getID() . '.xml';
		if(file_exists($filename))
			unlink($filename);

		$filename = $this->getPastaXmlPendente($nota->getAmbiente()) . '/' . $nota->getID() . '.xml';
		if(file_exists($filename))
			unlink($filename);

		$filename = $this->getPastaXmlAssinado($nota->getAmbiente()) . '/' . $nota->getID() . '.xml';
		if(file_exists($filename))
			unlink($filename);
	}

	/**
	 * Processa as notas e tarefas
	 */
	public function processa() {
		return $this->sefaz->processa();
	}

	/**
	 * Chamado quando o XML da nota foi gerado
	 */
	public function onNotaGerada($nota, $xml) {
		$_nota = ZNota::getPelaChave($nota->getID());
		// o código é truncado quando em contingência e pode devolver uma nota diferente
		if(is_null($_nota->getID()))
			$_nota = ZNota::getPeloPedidoID($nota->getCodigo());
		$_evento = ZEvento::log(
			$_nota->getID(),
			EventoEstado::ABERTO,
			'XML da nota gerado com sucesso',
			0
		);
		// TODO: atualizar tributos na _nota
		$_nota->setRecibo(null);
		$_nota->setQRCode(null);
		$_nota->setConsultaURL(null);
		$_nota->setDataEmissao($nota->getDataEmissao());
		$_nota->setChave($nota->getID());
		$_nota->setEstado(NotaEstado::ABERTO);
		$_nota = ZNota::atualizar($_nota);
	}

	/**
	 * Chamado após o XML da nota ser assinado
	 */
	public function onNotaAssinada($nota, $xml) {
		$_nota = ZNota::getPelaChave($nota->getID());
		$_evento = ZEvento::log(
			$_nota->getID(),
			EventoEstado::ASSINADO,
			'XML da nota assinado com sucesso',
			0
		);
	}

	/**
	 * Chamado após o XML da nota ser validado com sucesso
	 */
	public function onNotaValidada($nota, $xml) {
		$this->deleteXmlAnteriores($nota);
		$path = $this->getPastaXmlAssinado($nota->getAmbiente());
		$filename = $path . '/' . $nota->getID() . '.xml';
		xmkdir($path, 0711);
		file_put_contents($filename, $xml->saveXML());

		$_nota = ZNota::getPelaChave($nota->getID());
		$_nota->setEstado(NotaEstado::ASSINADO);
		$_nota->setQRCode($nota->getQRCodeURL());
		$_nota->setConsultaURL($nota->getConsultaURL());
		$_nota->setTributos($nota->getTotal()->getTributos());
		$_nota->setDetalhes($nota->getTotal()->getComplemento());
		$_nota = ZNota::atualizar($_nota);
		$_evento = ZEvento::log(
			$_nota->getID(),
			EventoEstado::VALIDADO,
			'XML da nota validado com sucesso',
			0
		);
	}

	/**
	 * Chamado antes de enviar a nota para a SEFAZ
	 */
	public function onNotaEnviando($nota, $xml) {
	}

	/**
	 * Chamado quando a forma de emissão da nota fiscal muda para contingência,
	 * aqui deve ser decidido se o número da nota deverá ser pulado e se esse
	 * número deve ser cancelado ou inutilizado
	 */
	public function onNotaContingencia($nota, $offline, $exception) {
		$_nota = ZNota::getPelaChave($nota->getID());
		$this->deleteXmlAnteriores($nota);
		$_nota->setMotivo($nota->getJustificativa());
		$_nota->setContingencia('Y');
		$_nota->setDataLancamento($nota->getDataContingencia());
		$_nota = ZNota::atualizar($_nota);
		$_evento = ZEvento::log(
			$_nota->getID(),
			EventoEstado::CONTINGENCIA,
			$exception->getMessage(),
			$exception->getCode()
		);
	}

	/**
	 * Chamado quando a nota foi enviada e aceita pela SEFAZ
	 */
	public function onNotaAutorizada($nota, $xml, $retorno) {
		$_nota = ZNota::getPelaChave($nota->getID());
		$this->deleteXmlAnteriores($nota);
		$path = $this->getPastaXmlAutorizado($nota->getAmbiente());
		$filename = $path . '/' . $nota->getID() . '.xml';
		xmkdir($path, 0711);
		file_put_contents($filename, $xml->saveXML());

		$_evento = ZEvento::log(
			$_nota->getID(),
			EventoEstado::AUTORIZADO,
			$retorno->getMotivo(),
			$retorno->getStatus()
		);
		// condição para quando tenta cancelar uma nota autorizada, sem saber se ela está autorizada
		// dessa forma a ação de cancelar continua, mas agora com um protocolo para que seja possível
		if($_nota->getAcao() != NotaAcao::CANCELAR)
			$_nota->setConcluido('Y');
		$_nota->setDataAutorizacao($nota->getProtocolo()->getDataRecebimento());
		$_nota->setProtocolo($nota->getProtocolo()->getNumero());
		$_nota->setEstado(NotaEstado::AUTORIZADO);
		$_nota = ZNota::atualizar($_nota);
	}

	/**
	 * Chamado quando a emissão da nota foi concluída com sucesso independente
	 * da forma de emissão
	 */
	public function onNotaCompleto($nota, $xml) {
	}

	/**
	 * Chamado quando uma nota é rejeitada pela SEFAZ, a nota deve ser
	 * corrigida para depois ser enviada novamente
	 */
	public function onNotaRejeitada($nota, $xml, $retorno) {
		$_nota = ZNota::getPelaChave($nota->getID());
		// quando uma consulta de nota em contingencia informa que a nota não existe
		// volta a enviar a nota novamente
		if ($_nota->isContingencia() && $retorno->isInexistente()) {
			$this->onNotaValidada($nota, $xml);
			return;
		}
		$this->deleteXmlAnteriores($nota);
		$path = $this->getPastaXmlRejeitado($nota->getAmbiente());
		$filename = $path . '/' . $nota->getID() . '.xml';
		xmkdir($path, 0711);
		file_put_contents($filename, $xml->saveXML());

		// não salva o evento, pois será salvo no onNotaErro
		if($_nota->getAcao() == NotaAcao::CANCELAR && is_null($_nota->getProtocolo())) {
			$data_emissao = strtotime($_nota->getDataEmissao());
			// só inutiliza depois de 10 min após a tentativa de autorizar a nota
			// if ($data_emissao + 600 < time()) {
				// se a intenção era cancelar uma nota sem protocolo, então muda para inutilização
				// pois a rejeição foi resultante de uma consulta do status da nota pela chave de acesso
				$_nota->setAcao(NotaAcao::INUTILIZAR);
			// }
			// mantém o XML rejeitado, pois pode acontecer da nota estar em processamento e não ser reconhecida ainda
		} else {
			// evita de ficar enviando uma mesma nota rejeitada a todo momento
			$_nota->setCorrigido('N');
		}
		$_nota->setEstado(NotaEstado::REJEITADO);
		$_nota = ZNota::atualizar($_nota);
	}

	/**
	 * Chamado quando a nota é denegada e não pode ser utilizada (outra nota
	 * deve ser gerada)
	 */
	public function onNotaDenegada($nota, $xml, $retorno) {
		$_nota = ZNota::getPelaChave($nota->getID());
		$this->deleteXmlAnteriores($nota);
		$path = $this->getPastaXmlDenegado($nota->getAmbiente());
		$filename = $path . '/' . $nota->getID() . '.xml';
		xmkdir($path, 0711);
		file_put_contents($filename, $xml->saveXML());

		$_evento = ZEvento::log(
			$_nota->getID(),
			EventoEstado::DENEGADO,
			$retorno->getMotivo(),
			$retorno->getStatus()
		);
		// não precisa analisar a denegação se a intenção era cancelar
		if($_nota->getAcao() != NotaAcao::CANCELAR)
			$_nota->setCorrigido('N');
		$_nota->setConcluido('Y');
		$_nota->setEstado(NotaEstado::DENEGADO);
		$_nota = ZNota::atualizar($_nota);
	}

	/**
	 * Chamado após tentar enviar uma nota e não ter certeza se ela foi
	 * recebida ou não (problemas técnicos), deverá ser feito uma consulta pela
	 * chave para obter o estado da nota
	 */
	public function onNotaPendente($nota, $xml, $exception) {
		$_nota = ZNota::getPelaChave($nota->getID());
		$this->deleteXmlAnteriores($nota);
		$path = $this->getPastaXmlPendente($nota->getAmbiente());
		$filename = $path . '/' . $nota->getID() . '.xml';
		xmkdir($path, 0711);
		file_put_contents($filename, $xml->saveXML());

		$_evento = ZEvento::log(
			$_nota->getID(),
			EventoEstado::PENDENTE,
			$exception->getMessage(),
			$exception->getCode()
		);
		// Pode acontecer de uma nota já em contingência ficar pendente, ou seja
		// não sabermos se ela foi autorizada no envio, assim consultaremos para saber o status
		// Para só depois tentar autorizar novamente
		if (!$_nota->isContingencia()) {
			// cancela a nota, mas como não tem protocolo será forçado uma consulta
			// se a consulta não obtiver sucesso com a chave, o número será inutilizado
			// caso a nota tenha sido autorizada, ela será autorizada normalmente
			// mas depois será cancelada pois a acão da nota continua sendo CANCELAR
			$_nota->setMotivo('Falha no retorno do status, problema de rede');
			$_nota->setAcao(NotaAcao::CANCELAR);
		}
		$_nota->setEstado(NotaEstado::PENDENTE);
		$_nota = ZNota::atualizar($_nota);
		// Não cria outra nota pois já está em contingência 
		if ($_nota->isContingencia()) {
			return;
		}
		// cria outra nota, pois a nota atual pode ter sido recebida e autorizada
		$_nota = ZNota::criarProxima($_nota);
		// atualiza a chave da nova nota, evita de obter a _nota que será cancelada
		// pois a contingência é otimizada para ser executada logo após a falha
		$nota->setNumero($_nota->getNumeroInicial());
		$nota->setID($nota->gerarID());
		$_nota->setChave($nota->getID());
		$_nota = ZNota::atualizar($_nota);
	}

	/**
	 * Chamado quando uma nota é enviada, mas não retornou o protocolo que será
	 * consultado mais tarde
	 */
	public function onNotaProcessando($nota, $xml, $retorno) {
		$_nota = ZNota::getPelaChave($nota->getID());
		$this->deleteXmlAnteriores($nota);
		$path = $this->getPastaXmlProcessamento($nota->getAmbiente());
		$filename = $path . '/' . $nota->getID() . '.xml';
		xmkdir($path, 0711);
		file_put_contents($filename, $xml->saveXML());

		$_evento = ZEvento::log(
			$_nota->getID(),
			EventoEstado::PROCESSAMENTO,
			$retorno->getMotivo(),
			$retorno->getStatus()
		);
		$_nota->setRecibo($retorno->getNumero());
		$_nota->setEstado(NotaEstado::PROCESSAMENTO);
		$_nota = ZNota::atualizar($_nota);
	}

	/**
	 * Chamado quando uma nota autorizada é cancelada na SEFAZ
	 */
	public function onNotaCancelada($nota, $xml, $retorno) {
		$_nota = ZNota::getPelaChave($nota->getID());
		$this->deleteXmlAnteriores($nota);
		$path = $this->getPastaXmlCancelado($nota->getAmbiente());
		$filename = $path . '/' . $nota->getID() . '.xml';
		xmkdir($path, 0711);
		file_put_contents($filename, $xml->saveXML());

		$_evento = ZEvento::log(
			$_nota->getID(),
			EventoEstado::CANCELADO,
			$retorno->getMotivo(),
			$retorno->getStatus()
		);
		// sobrescreve o protocolo de autorização da nota pelo protocolo de cancelamento
		$_nota->setProtocolo($retorno->getNumero());
		$_nota->setEstado(NotaEstado::CANCELADO);
		$_nota->setConcluido('Y');
		$_nota = ZNota::atualizar($_nota);
	}

	/**
	 * Chamado quando ocorre um erro nas etapas de geração e envio da nota (Não
	 * é chamado quando entra em contigência)
	 */
	public function onNotaErro($nota, $exception) {
		$_nota = ZNota::getPelaChave($nota->getID());
		if(is_null($_nota->getID()))
			$_nota = ZNota::getPeloPedidoID($nota->getCodigo());
		$_evento = ZEvento::log(
			$_nota->getID(),
			$_nota->getEstado(),
			$exception->getMessage(),
			$exception->getCode()
		);
		if ($exception instanceof \NFe\Exception\NetworkException) {
			// não bloqueia a tarefa se for problema de rede
			return;
		}
		// Evita de ficar toda hora enviando a mesma nota com erro
		$_nota->setCorrigido('N');
		$_nota = ZNota::atualizar($_nota);
	}

	/**
	 * Chamado quando um ou mais números de notas forem inutilizados
	 */
	public function onInutilizado($inutilizacao, $xml) {
		$path = $this->getPastaXmlInutilizado($inutilizacao->getAmbiente());
		$filename = $path . '/' . $inutilizacao->getID() . '.xml';
		xmkdir($path, 0711);
		file_put_contents($filename, $xml->saveXML());
	}
	
	/**
	 * Chamado quando uma ação é executada
	 */
	public function onTarefaExecutada($tarefa, $retorno) {
		$_nota = ZNota::getPeloID($tarefa->getID());
		switch ($tarefa->getAcao()) {
			case \NFe\Task\Tarefa::ACAO_INUTILIZAR:
				// implementado aqui pois o evento de inutilização não devolve o ID da _nota
				// e não possui a nota para consulta pela chave
				$inutilizacao = $tarefa->getAgente();
				$_evento = ZEvento::log(
					$_nota->getID(),
					EventoEstado::INUTILIZADO,
					$inutilizacao->getMotivo(),
					$inutilizacao->getStatus()
				);
				$_nota->setChave($inutilizacao->getID());
				$_nota->setProtocolo($inutilizacao->getNumero());
				$_nota->setDataAutorizacao($inutilizacao->getDataRecebimento());
				$_nota->setConcluido('Y');
				$_nota->setEstado(NotaEstado::INUTILIZADO);
				$_nota = ZNota::atualizar($_nota);
				break;
			case \NFe\Task\Tarefa::ACAO_CONSULTAR:
				// não precisa implementar, pois a consulta já processa a nota internamente
				// se a intenção da consulta for para cancelar ou inutilizar
				// os eventos já estão preparados para manter a ação correta para posterior processamento 
				// Pode acontecer de uma nota cancelada ser consultada
				if (!$retorno->isCancelado())
					break;
			case \NFe\Task\Tarefa::ACAO_CANCELAR:
				// salva um XML diferenciado e não embutido no XML da nota
				$nota = $tarefa->getNota();
				$path = $this->getPastaXmlCancelado($nota->getAmbiente());
				$filename = $path . '/' . $nota->getID() . self::CANCEL_SUFFIX . '.xml';
				xmkdir($path, 0711);
				$xml = $tarefa->getDocumento();
				file_put_contents($filename, $xml->saveXML());
				break;
		}
	}

	/**
	 * Chamado quando ocorre uma falha na execução de uma tarefa
	 */
	public function onTarefaErro($tarefa, $exception) {
		$_nota = ZNota::getPeloID($tarefa->getID());
		$_evento = ZEvento::log(
			$_nota->getID(),
			$_nota->getEstado(),
			$exception->getMessage(),
			$exception->getCode()
		);
		if ($exception instanceof \NFe\Exception\NetworkException) {
			// não bloqueia a tarefa se for problema de rede
			return;
		}
		if ($_nota->isContingencia()) {
			// não bloqueia a tarefa de consulta de notas pendentes em contingência 
			return;
		}
		// Evita de ficar toda hora enviando a mesma nota com erro
		$_nota->setCorrigido('N');
		$_nota = ZNota::atualizar($_nota);
	}

}
