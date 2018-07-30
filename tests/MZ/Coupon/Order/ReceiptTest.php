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
namespace MZ\Coupon\Order;

use Thermal\Printer;
use Thermal\Connection\Buffer;
use Thermal\Model;
use MZ\Sale\Pedido;

class ReceiptTest extends \PHPUnit_Framework_TestCase
{
    public function testPrint()
    {
        $model = new Model('MP-4200 TH');
        $connection = new Buffer();
        $printer = new Printer($model, $connection);
        $receipt = new Receipt($printer);
        $receipt->setItems([]);
        $receipt->setOrder(new Pedido());
        $receipt->setPayments([]);
        $receipt->setDateTime('2018-07-25 21:11:00');
        $receipt->printCoupon();
        $printer->feed(6);
        $printer->buzzer();
        $printer->cutter();
        $printer->drawer(Printer::DRAWER_1);
        $this->assertEquals(
            getExpectedBuffer('order_receipt_print', $connection->getBuffer()),
            $connection->getBuffer()
        );
    }
}
