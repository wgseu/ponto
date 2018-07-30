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

use MZ\Session\Sessao;
use MZ\Sale\Pedido;
use MZ\Sale\ProdutoPedido;
use MZ\Database\DB;
use MZ\Device\Impressora;
use MZ\Device\Dispositivo;
use MZ\Payment\Pagamento;

use Thermal\Printer;
use Thermal\Connection\Buffer;
use Thermal\Model;
use MZ\Coupon\Order\Receipt;

if (!is_login()) {
    json('Usuário não autenticado!');
}
need_manager(true);
try {
    $pedido = new Pedido($_GET);
    $pedido->loadByID($pedido->getID());
    if (!$pedido->exists()) {
        throw new \Exception('O pedido informado não existe');
    }
    $pedido->checkAccess(logged_employee());
    if ($pedido->getEstado() != Pedido::ESTADO_FECHADO) {
        $pedido->setFechadorID(logged_employee()->getID());
        $pedido->setDataImpressao(DB::now());
        $pedido->setEstado(Pedido::ESTADO_FECHADO);
        $pedido->update();
    }
    $dispositivo = new Dispositivo();
    $dispositivo->setNome(isset($_GET['device']) ? $_GET['device'] : null);
    $dispositivo->setSerial(isset($_GET['serial']) ? $_GET['serial'] : null);
    $dispositivo->loadBySerial($dispositivo->getSerial());
    if (!$dispositivo->exists()) {
        throw new \Exception('O dispositivo informado não existe ou não foi validado');
    }
    $impressora = Impressora::find([], [
        'modo' => [-1 => Impressora::MODO_TERMINAL],
        'dispositivoid' => [-1 => $dispositivo->getID()],
        'setorid' => [-1 => $dispositivo->getSetorID()]
    ]);
    if (!$impressora->exists()) {
        throw new \Exception('Nenhuma impressora cadastrada, cadastre uma impressora!');
    }
    $model = new Model($impressora->getModelo());
    $connection = new Buffer();
    $printer = new Printer($model, $connection);
    $printer->setColumns($impressora->getColunas());
    $receipt = new Receipt($printer);
    $receipt->setOrder($pedido);
    $receipt->setItems(ProdutoPedido::findAll(
        [
            'pedidoid' => $pedido->getID(),
            'cancelado' => 'N'
        ],
        ['id' => 1]
    ));
    $receipt->setPayments(Pagamento::findAll(
        [
            'pedidoid' => $pedido->getID(),
            'cancelado' => 'N'
        ],
        ['id' => 1]
    ));
    $receipt->printCoupon();
    if ($impressora->getAvanco() > 0) {
        $printer->feed($impressora->getAvanco());
    }
    $printer->buzzer();
    $printer->cutter();
    $data = $connection->getBuffer();
    json([
        'status' => 'ok',
        'data' => base64_encode($data),
        'printer' => $impressora->getNome(),
        'name' => 'Cupom de Consumo do Pedido #' . $pedido->getID()
    ]);
} catch (\Exception $e) {
    \Log::error($e->getMessage());
    json($e->getMessage());
}
