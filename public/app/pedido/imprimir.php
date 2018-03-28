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
require_once(dirname(dirname(__DIR__)) . '/app.php');

if (!is_login()) {
    json('Usuário não autenticado!');
}
try {
    \DB::BeginTransaction();
    $sessao = Sessao::getPorAberta();
    if (is_null($sessao->getID())) {
        throw new \Exception('A sessão ainda não foi aberta');
    }
    $tipo = Pedido::TIPO_MESA;
    if ($_GET['tipo'] == 'comanda') {
        $tipo = Pedido::TIPO_COMANDA;
    }
    /* else if($_GET['tipo'] == 'avulso')
		$tipo = Pedido::TIPO_AVULSO;
	else if($_GET['tipo'] == 'entrega')
		$tipo = Pedido::TIPO_ENTREGA; */
    if ($tipo == Pedido::TIPO_MESA && !$login_funcionario->has(Permissao::NOME_PEDIDOMESA)) {
        throw new \Exception('Você não tem permissão para acessar mesas');
    } elseif ($tipo == Pedido::TIPO_COMANDA && !$login_funcionario->has(Permissao::NOME_PEDIDOCOMANDA)) {
        throw new \Exception('Você não tem permissão para acessar comandas');
    }
    $mesa = Mesa::findByID($_GET['mesa']);
    if (is_null($mesa->getID()) && $tipo == Pedido::TIPO_MESA) {
        throw new \Exception('A mesa não foi informada ou não existe');
    }
    $comanda = \MZ\Sale\Comanda::findByID($_GET['comanda']);
    if (is_null($comanda->getID()) && $tipo == Pedido::TIPO_COMANDA) {
        throw new \Exception('A comanda não foi informada ou não existe');
    }
    $pedido = Pedido::findByLocal($tipo, $mesa->getID(), $comanda->getID());
    if (is_null($pedido->getID())) {
        throw new \Exception('A mesa ou comanda informada não está aberta');
    }
    if ($pedido->getEstado() != Pedido::ESTADO_FECHADO) {
        $pedido->setFechadorID($login_funcionario->getID());
        $pedido->setDataImpressao(date('Y-m-d H:i:s'));
        $pedido->setEstado(Pedido::ESTADO_FECHADO);
        $pedido->update();
    }
    $appsync = new \MZ\System\Synchronizer();
    $appsync->printOrder($pedido->getID(), $login_funcionario->getID());
    $appsync->updateOrder($pedido->getID(), $pedido->getTipo(), $pedido->getMesaID(), $pedido->getComandaID(), \MZ\System\Synchronizer::ACTION_STATE);
    \DB::Commit();
} catch (Exception $e) {
    \DB::RollBack();
    Log::error($e->getMessage());
    json($e->getMessage());
}
json(['status' => 'ok']);
