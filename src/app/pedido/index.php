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
require_once(dirname(dirname(dirname(__FILE__))) . '/app.php');

if(!is_login())
	json('Usuário não autenticado!');
$_pedidos = $_POST['pedidos'];
$sync = $_POST['sync'];
$action = AppSync::ACTION_ADDED;
try {
	DB::BeginTransaction();
	$sessao = ZSessao::getPorAberta();
	if(is_null($sessao->getID()))
		throw new Exception('A sessão ainda não foi aberta');
	$tipo = PedidoTipo::MESA;
	if($_POST['tipo'] == 'comanda')
		$tipo = PedidoTipo::COMANDA;
	/* else if($_POST['tipo'] == 'avulso')
		$tipo = PedidoTipo::AVULSO;
	else if($_POST['tipo'] == 'entrega')
		$tipo = PedidoTipo::ENTREGA; */
	$mesa = ZMesa::getPeloID($_POST['mesa']);
	if(is_null($mesa->getID()) && $tipo == PedidoTipo::MESA)
		throw new Exception('A mesa não foi informada ou não existe');
	$comanda = \MZ\Sale\Comanda::findByID($_POST['comanda']);
	if(is_null($comanda->getID()) && $tipo == PedidoTipo::COMANDA)
		throw new Exception('A comanda não foi informada ou não existe');
	$pedido = ZPedido::getPeloLocal($tipo, $mesa->getID(), $comanda->getID());
	$pedido->setTipo($tipo);
	$pedido->validaAcesso($login_funcionario);
	if(is_null($pedido->getID())) {
		// não existe pedido ainda, cadastra um novo
		$cliente = ZCliente::getPeloFone($_POST['cliente']);
		$pedido->setMesaID($mesa->getID());
		$pedido->setComandaID($comanda->getID());
		$pedido->setSessaoID($sessao->getID());
		$pedido->setFuncionarioID($login_funcionario->getID());
		$pedido->setClienteID($cliente->getID());
		$pedido->setPessoas(1);
		$pedido->setCancelado('N');
		$pedido->setDescricao($_POST['descricao']);
		$pedido->setEstado(PedidoEstado::ATIVO);
		$pedido = ZPedido::cadastrar($pedido);
		$action = AppSync::ACTION_OPEN;
	}
	$added = 0;
	$pacote_pedido = new ZProdutoPedido();
	foreach ($_pedidos as $index => $_produto_pedido) {
		$produto_pedido = new ZProdutoPedido($_produto_pedido);
		$produto = ZProduto::getPeloID($produto_pedido->getProdutoID());
		if(is_null($produto->getID()))
			throw new Exception('O produto informado não existe');
		$produto_pedido->setPedidoID($pedido->getID());
		$produto_pedido->setFuncionarioID($login_funcionario->getID());
		$produto_pedido->setProdutoID($produto->getID());
		if($produto->isCobrarServico())
			$produto_pedido->setPorcentagem($login_funcionario->getPorcentagem());
		if(is_null($produto_pedido->getPorcentagem()))
			$produto_pedido->setPorcentagem(0);
		if(!is_null($produto_pedido->getProdutoPedidoID())) {
			$produto_pedido->setProdutoPedidoID($pacote_pedido->getID());
		} else if($produto->getTipo() != ProdutoTipo::PACOTE) {
			$produto_pedido->setPreco($produto->getPrecoVenda());
			$produto_pedido->setPrecoVenda($produto->getPrecoVenda());
			$pacote_pedido = new ZProdutoPedido();
		}
		$produto_pedido->setPrecoCompra(0);
		if(!is_null($produto->getCustoProducao()))
			$produto_pedido->setPrecoCompra($produto->getCustoProducao());
		$produto_pedido->setEstado(ProdutoPedidoEstado::ADICIONADO);
		$produto_pedido->setCancelado('N');
		$produto_pedido->setVisualizado('N');
		$formacoes = $_produto_pedido['formacoes'];
		if(is_null($formacoes))
			$formacoes = array();
		if($pedido->getTipo() == PedidoTipo::COMANDA && is_boolean_config('Comandas', 'PrePaga')) {
			$subtotal = $produto_pedido->getTotal();
			$pedido_total = ZPedido::getTotalDoLocal(PedidoTipo::COMANDA, null, $pedido->getComandaID());
			$total = $subtotal + $pedido_total;
			$pagamentos = ZPagamento::getTotalPedido($pedido->getID());
			$restante = $pedido_total - $pagamentos;
			if($total > $pagamentos)
				throw new Exception('Saldo insuficiente para a realização do pedido, Necessário: R$ '.moneyit($subtotal).', Saldo atual: R$ '.moneyit(-$restante));
		}
		$produto_pedido = ZProdutoPedido::cadastrar($produto_pedido, $produto, $formacoes);
		if($produto->getTipo() == ProdutoTipo::PACOTE) {
			$pacote_pedido = $produto_pedido;
		}
		$added++;
	}
	if($added > 0 && $action != AppSync::ACTION_OPEN && $pedido->getEstado() != PedidoEstado::ATIVO) {
		$action = AppSync::ACTION_STATE;
		$pedido->setEstado(PedidoEstado::ATIVO);
		$pedido = ZPedido::atualizar($pedido);
	}
	if($sync) {
		$appsync = new AppSync();
		if($action != AppSync::ACTION_ADDED)
			$appsync->updateOrder($pedido->getID(), $pedido->getTipo(), $pedido->getMesaID(), $pedido->getComandaID(), $action);
		if($action == AppSync::ACTION_OPEN) {
			$senha_balcao = is_boolean_config('Imprimir', 'Senha.Paineis');
			$comanda_senha = is_boolean_config('Imprimir', 'Comanda.Senha');
			if(($senha_balcao && $pedido->getTipo() == PedidoTipo::AVULSO) ||
			   ($comanda_senha && $pedido->getTipo() == PedidoTipo::COMANDA)) {
				$appsync->printQueue($pedido->getID());
			}
		}
		if($added > 0) {
			$appsync->updateOrder($pedido->getID(), $pedido->getTipo(), $pedido->getMesaID(), $pedido->getComandaID(), AppSync::ACTION_ADDED);
			$appsync->printServices($pedido->getID());
		}
	}
	DB::Commit();
} catch (Exception $e) {
	DB::RollBack();
	json($e->getMessage());
}
json(array('status' => 'ok', 'action' => $action));
