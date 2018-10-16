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
namespace MZ\Sale;

use MZ\Association\Order;

use MZ\Database\DB;
use MZ\Device\Impressora;
use MZ\Device\Dispositivo;
use MZ\Payment\Pagamento;
use MZ\Logger\Log;

use Thermal\Printer;
use Thermal\Connection\Buffer;
use Thermal\Model;
use MZ\Coupon\Order\Receipt;

/**
 * Allow application to serve system resources
 */
class PedidoApiController extends \MZ\Core\ApiController
{
    /**
     * Create order or add items to existing order
     * @Post("/api/pedidos", name="api_pedido_add")
     */
    public function add()
    {
        app()->needLogin();
        $order = new Order();
        $order->setEmployee(app()->auth->provider);
        try {
            $order->loadData($this->getData());
            $order->search();
            $order->process();
        } catch (\Exception $e) {
            return $this->json()->error($e->getMessage());
        }
        return $this->json()->success(['item' => $order->publish()]);
    }

    /**
     * Build receipt job for printer and mark order as closing
     * @Put("/api/pedidos/receipt/{id}", name="api_pedido_receipt", params={ "id": "\d+" })
     */
    public function receipt($id)
    {
        if (!app()->getAuthentication()->isLogin()) {
            return $this->json()->error('Usuário não autenticado!');
        }
        app()->needManager();
        $data = $this->getData();
        try {
            $pedido = Pedido::findByID($id);
            if (!$pedido->exists()) {
                throw new \Exception('O pedido informado não existe');
            }
            $pedido->checkAccess(app()->auth->provider);
            $dispositivo = new Dispositivo();
            $dispositivo->setNome($data['device']);
            $dispositivo->setSerial($data['serial']);
            $dispositivo->loadBySerial();
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
            $receipt->setItems(Item::findAll(
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
            if (!$impressora->hasOption(Impressora::OPCAO_SEM_BEEP)) {
                $printer->buzzer();
            }
            if ($impressora->hasOption(Impressora::OPCAO_CORTAR)) {
                $printer->cutter();
            }
            $buffer = $connection->getBuffer();
            if ($pedido->getEstado() != Pedido::ESTADO_FECHADO) {
                $pedido->setFechadorID(app()->auth->provider->getID());
                $pedido->setDataImpressao(DB::now());
                $pedido->setEstado(Pedido::ESTADO_FECHADO);
                $pedido->update();
            }
            return $this->json()->success([
                'data' => base64_encode($buffer),
                'printer' => $impressora->getNome(),
                'name' => 'Cupom de Consumo do Pedido #' . $pedido->getID()
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->json()->error($e->getMessage());
        }
    }
}
