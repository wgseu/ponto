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

namespace MZ\Payment;

use MZ\Account\ClienteTest;
use MZ\Wallet\BancoTest;
use MZ\Exception\ValidationException;

class ChequeTest extends \MZ\Framework\TestCase
{
    /**
     * Build a valid cheque
     * @return Cheque
     */
    public static function build()
    {
        $last = Cheque::find([], ['id' => -1]);
        $id = $last->getID() + 1;
        $cliente = ClienteTest::create();
        $banco = BancoTest::create();
        $cheque = new Cheque();
        $cheque->setClienteID($cliente->getID());
        $cheque->setBancoID($banco->getID());
        $cheque->setAgencia('Agência do cheque');
        $cheque->setConta('Conta do cheque');
        $cheque->setNumero($id);
        $cheque->setValor(12.3);
        $cheque->setVencimento('2016-12-25 12:15:00');
        return $cheque;
    }

    /**
     * Create a cheque on database
     * @return Cheque
     */
    public static function create()
    {
        $cheque = self::build();
        $cheque->insert();
        return $cheque;
    }

    public function testFind()
    {
        $cheque = self::create();
        $condition = ['bancoid' => $cheque->getBancoID()];
        $found_cheque = Cheque::find($condition);
        $this->assertEquals($cheque, $found_cheque);
        list($found_cheque) = Cheque::findAll($condition, [], 1);
        $this->assertEquals($cheque, $found_cheque);
        $this->assertEquals(1, Cheque::count($condition));
    }

    public function testAdd()
    {
        $cheque = self::build();
        $cheque->insert();
        $this->assertTrue($cheque->exists());
    }

    public function testAddInvalid()
    {
        $cheque = self::build();
        $cheque->setClienteID(null);
        $cheque->setBancoID(null);
        $cheque->setAgencia(null);
        $cheque->setConta(null);
        $cheque->setNumero(null);
        $cheque->setValor(null);
        $cheque->setVencimento(null);
        $cheque->setCancelado('T');
        $cheque->setRecolhido('T');
        try {
            $cheque->insert();
            $this->fail('Valores invalidos');
        } catch (ValidationException $e) {
            $this->assertEquals(['clienteid', 'bancoid', 'agencia', 'conta',
            'numero', 'valor', 'vencimento', 'cancelado', 'recolhido'], array_keys($e->getErrors()));
        }
        $cheque = self::build();
        $cheque->setCancelado('Y');
        try {
            $cheque->insert();
            $this->fail('Não pode cadastrar cheque cancelado');
        } catch (ValidationException $e) {
            $this->assertEquals(['cancelado'], array_keys($e->getErrors()));
        }
    }

    public function testUpdateInvalid()
    {
        $cheque = self::create();
        $cheque->recolher();
        $cheque->insert();
        try {
            $cheque->setRecolhido('Y');
            $cheque->update();
            $this->fail('Cheque já recolhido');
        } catch (ValidationException $e) {
            $this->assertEquals(['recolhido'], array_keys($e->getErrors()));
        }
    }

    public function testFinds()
    {
        $cheque = self::create();
        $cliente = $cheque->findClienteID();
        $this->assertEquals($cheque->getClienteID(), $cliente->getID());

        $banco = $cheque->findBancoID();
        $this->assertEquals($cheque->getBancoID(), $banco->getID());
    }
}
