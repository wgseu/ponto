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
require_once(dirname(dirname(__DIR__)) . '/app.php');

if (!is_login()) {
    json('Usuário não autenticado!');
}
$_pedidos = $_POST['pedidos'];
$sync = $_POST['sync'];
$action = \MZ\System\Synchronizer::ACTION_ADDED;
try {
    \DB::BeginTransaction();
    $sessao = Sessao::getPorAberta();
    if (!$sessao->exists()) {
        throw new \Exception('A sessão ainda não foi aberta');
    }
    $tipo = Pedido::TIPO_MESA;
    if ($_POST['tipo'] == 'comanda') {
        $tipo = Pedido::TIPO_COMANDA;
    }
    /* else if($_POST['tipo'] == 'avulso')
		$tipo = Pedido::TIPO_AVULSO;
	else if($_POST['tipo'] == 'entrega')
		$tipo = Pedido::TIPO_ENTREGA; */
    $mesa = Mesa::findByID($_POST['mesa']);
    if (!$mesa->exists() && $tipo == Pedido::TIPO_MESA) {
        throw new \Exception('A mesa não foi informada ou não existe');
    }
    $comanda = \MZ\Sale\Comanda::findByID($_POST['comanda']);
    if (!$comanda->exists() && $tipo == Pedido::TIPO_COMANDA) {
        throw new \Exception('A comanda não foi informada ou não existe');
    }
    $pedido = Pedido::findByLocal($tipo, $mesa->getID(), $comanda->getID());
    $pedido->setTipo($tipo);
    $pedido->validaAcesso(logged_employee());
    if (!$pedido->exists()) {
        // não existe pedido ainda, cadastra um novo
        $cliente = Cliente::findByFone($_POST['cliente']);
        $pedido->setMesaID($mesa->getID());
        $pedido->setComandaID($comanda->getID());
        $pedido->setSessaoID($sessao->getID());
        $pedido->setFuncionarioID(logged_employee()->getID());
        $pedido->setClienteID($cliente->getID());
        $pedido->setPessoas(1);
        $pedido->setCancelado('N');
        $pedido->setDescricao($_POST['descricao']);
        $pedido->setEstado(Pedido::ESTADO_ATIVO);
        $pedido->insert();
        $action = \MZ\System\Synchronizer::ACTION_OPEN;
    }
    $added = 0;
    $pacote_pedido = new ProdutoPedido();
    foreach ($_pedidos as $index => $_produto_pedido) {
        $produto_pedido = new ProdutoPedido($_produto_pedido);
        $produto = $produto_pedido->findProdutoID();
        if (!$produto->exists()) {
            throw new \Exception('O produto informado não existe');
        }
        $produto_pedido->setPedidoID($pedido->getID());
        $produto_pedido->setFuncionarioID(logged_employee()->getID());
        $produto_pedido->setProdutoID($produto->getID());
        if ($produto->isCobrarServico()) {
            $produto_pedido->setPorcentagem(logged_employee()->getPorcentagem());
        }
        if (is_null($produto_pedido->getPorcentagem())) {
            $produto_pedido->setPorcentagem(0);
        }
        if (!is_null($produto_pedido->getProdutoPedidoID())) {
            // TODO atribuir preço e verificar preços das composições
            $produto_pedido->setProdutoPedidoID($pacote_pedido->getID());
        } elseif ($produto->getTipo() != Produto::TIPO_PACOTE) {
            // TODO atribuir preço padrão e verificar preços das propriedades
            $produto_pedido->setPreco($produto->getPrecoVenda());
            $produto_pedido->setPrecoVenda($produto->getPrecoVenda());
            $pacote_pedido = new ProdutoPedido();
        }
        $produto_pedido->setPrecoCompra(0);
        if (!is_null($produto->getCustoProducao())) {
            $produto_pedido->setPrecoCompra($produto->getCustoProducao());
        }
        $produto_pedido->setEstado(ProdutoPedido::ESTADO_ADICIONADO);
        $produto_pedido->setCancelado('N');
        $produto_pedido->setVisualizado('N');
        $formacoes = $_produto_pedido['formacoes'];
        if (is_null($formacoes)) {
            $formacoes = [];
        }
        if ($pedido->getTipo() == Pedido::TIPO_COMANDA && is_boolean_config('Comandas', 'PrePaga')) {
            $subtotal = $produto_pedido->getTotal();
            $pedido_total = Pedido::getTotalDoLocal(Pedido::TIPO_COMANDA, null, $pedido->getComandaID());
            $total = $subtotal + $pedido_total;
            $pagamentos = Pagamento::rawFindTotal(['pedidoid' => $pedido->getID()]);
            $restante = $pedido_total - $pagamentos;
            $msg = 'Saldo insuficiente para a realização do pedido, Necessário: %s, Saldo atual: %s';
            if ($total > $pagamentos) {
                throw new \Exception(vsprintf($msg, [\MZ\Util\Mask::money($subtotal, true), \MZ\Util\Mask::money(-$restante, true)]));
            }
        }
        $produto_pedido = ProdutoPedido::cadastrar($produto_pedido, $produto, $formacoes);
        if ($produto->getTipo() == Produto::TIPO_PACOTE) {
            $pacote_pedido = $produto_pedido;
        }
        $added++;
    }
    if ($added > 0 && $action != \MZ\System\Synchronizer::ACTION_OPEN && $pedido->getEstado() != Pedido::ESTADO_ATIVO) {
        $action = \MZ\System\Synchronizer::ACTION_STATE;
        $pedido->setEstado(Pedido::ESTADO_ATIVO);
        $pedido->update();
    }
    if ($sync) {
        $appsync = new \MZ\System\Synchronizer();
        if ($action != \MZ\System\Synchronizer::ACTION_ADDED) {
            $appsync->updateOrder($pedido->getID(), $pedido->getTipo(), $pedido->getMesaID(), $pedido->getComandaID(), $action);
        }
        if ($action == \MZ\System\Synchronizer::ACTION_OPEN) {
            $senha_balcao = is_boolean_config('Imprimir', 'Senha.Paineis');
            $comanda_senha = is_boolean_config('Imprimir', 'Comanda.Senha');
            if (($senha_balcao && $pedido->getTipo() == Pedido::TIPO_AVULSO) ||
               ($comanda_senha && $pedido->getTipo() == Pedido::TIPO_COMANDA)) {
                $appsync->printQueue($pedido->getID());
            }
        }
        if ($added > 0) {
            $appsync->updateOrder($pedido->getID(), $pedido->getTipo(), $pedido->getMesaID(), $pedido->getComandaID(), \MZ\System\Synchronizer::ACTION_ADDED);
            $appsync->printServices($pedido->getID());
        }
    }
    \DB::Commit();
} catch (\Exception $e) {
    \DB::RollBack();
    json($e->getMessage());
}
json(['status' => 'ok', 'action' => $action]);
