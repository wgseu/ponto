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

use MZ\System\Permissao;
use MZ\Session\Sessao;
use MZ\Sale\Pedido;
use MZ\Environment\Mesa;
use MZ\Sale\Comanda;
use MZ\System\Synchronizer;

if (!is_login()) {
    json('Usuário não autenticado!');
}
try {
    \DB::BeginTransaction();
    $sessao = Sessao::getPorAberta();
    if (!$sessao->exists()) {
        throw new \Exception('A sessão ainda não foi aberta');
    }
    $tipo = isset($_GET['tipo']) ? $_GET['tipo'] : null;
    if ($tipo == 'comanda') {
        $tipo = Pedido::TIPO_COMANDA;
  /*} else if($tipo == 'avulso') {
        $tipo = Pedido::TIPO_AVULSO;
    } else if($tipo == 'entrega') {
        $tipo = Pedido::TIPO_ENTREGA; */
    } else {
        $tipo = Pedido::TIPO_MESA;
    }
    if ($tipo == Pedido::TIPO_MESA && !logged_employee()->has(Permissao::NOME_PEDIDOMESA)) {
        throw new \Exception('Você não tem permissão para acessar mesas');
    } elseif ($tipo == Pedido::TIPO_COMANDA && !logged_employee()->has(Permissao::NOME_PEDIDOCOMANDA)) {
        throw new \Exception('Você não tem permissão para acessar comandas');
    }
    $mesa = Mesa::findByID(isset($_GET['mesa']) ? $_GET['mesa'] : null);
    if (!$mesa->exists() && $tipo == Pedido::TIPO_MESA) {
        throw new \Exception('A mesa não foi informada ou não existe');
    }
    $comanda = Comanda::findByID(isset($_GET['comanda']) ? $_GET['comanda'] : null);
    if (!$comanda->exists() && $tipo == Pedido::TIPO_COMANDA) {
        throw new \Exception('A comanda não foi informada ou não existe');
    }
    $pedido = Pedido::findByLocal($tipo, $mesa->getID(), $comanda->getID());
    if (!$pedido->exists()) {
        throw new \Exception('A mesa ou comanda informada não está aberta');
    }
    if ($pedido->getEstado() != Pedido::ESTADO_FECHADO) {
        $pedido->setFechadorID(logged_employee()->getID());
        $pedido->setDataImpressao(date('Y-m-d H:i:s'));
        $pedido->setEstado(Pedido::ESTADO_FECHADO);
        $pedido->update();
    }
    $appsync = new Synchronizer();
    $appsync->printOrder($pedido->getID(), logged_employee()->getID());
    $appsync->updateOrder(
        $pedido->getID(),
        $pedido->getTipo(),
        $pedido->getMesaID(),
        $pedido->getComandaID(),
        Synchronizer::ACTION_STATE
    );
    \DB::Commit();
    json(['status' => 'ok']);
} catch (\Exception $e) {
    \DB::RollBack();
    \Log::error($e->getMessage());
    json($e->getMessage());
}
