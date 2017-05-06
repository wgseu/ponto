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
try {
	DB::BeginTransaction();
	$sessao = ZSessao::getPorAberta();
	if(is_null($sessao->getID()))
		throw new Exception('A sessão ainda não foi aberta');
	$tipo = PedidoTipo::MESA;
	if($_GET['tipo'] == 'comanda')
		$tipo = PedidoTipo::COMANDA;
	/* else if($_GET['tipo'] == 'avulso')
		$tipo = PedidoTipo::AVULSO;
	else if($_GET['tipo'] == 'entrega')
		$tipo = PedidoTipo::ENTREGA; */
	if($tipo == PedidoTipo::MESA && !have_permission(PermissaoNome::PEDIDOMESA))
		throw new Exception('Você não tem permissão para acessar mesas');
	else if($tipo == PedidoTipo::COMANDA && !have_permission(PermissaoNome::PEDIDOCOMANDA))
		throw new Exception('Você não tem permissão para acessar comandas');
	$mesa = ZMesa::getPeloID($_GET['mesa']);
	if(is_null($mesa->getID()) && $tipo == PedidoTipo::MESA)
		throw new Exception('A mesa não foi informada ou não existe');
	$comanda = ZComanda::getPeloID($_GET['comanda']);
	if(is_null($comanda->getID()) && $tipo == PedidoTipo::COMANDA)
		throw new Exception('A comanda não foi informada ou não existe');
	$pedido = ZPedido::getPeloLocal($tipo, $mesa->getID(), $comanda->getID());
	if(is_null($pedido->getID())) 
		throw new Exception('A mesa ou comanda informada não está aberta');
	if($pedido->getEstado() != PedidoEstado::FECHADO) {
		$pedido->setFechadorID($login_funcionario->getID());
		$pedido->setDataImpressao(date('Y-m-d H:i:s'));
		$pedido->setEstado(PedidoEstado::FECHADO);
		$pedido = ZPedido::atualizar($pedido);
	}
	$appsync = new AppSync();
	$appsync->printOrder($pedido->getID(), $login_funcionario->getID());
	$appsync->updateOrder($pedido->getID(), $pedido->getTipo(), $pedido->getMesaID(), $pedido->getComandaID(), AppSync::ACTION_STATE);
	DB::Commit();
} catch (Exception $e) {
	DB::RollBack();
	Log::error($e->getMessage());
	json($e->getMessage());
}
json(array('status' => 'ok'));
