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

namespace MZ\Account;

use MZ\Account\ClienteTest;
use MZ\Exception\ValidationException;

class CreditoTest extends \MZ\Framework\TestCase
{
    /**
     * Build a valid crédito
     * @param string $detalhes Crédito detalhes
     * @return Credito
     */
    public static function build($detalhes = null)
    {
        $last = Credito::find([], ['id' => -1]);
        $id = $last->getID() + 1;
        $cliente = ClienteTest::create();
        $credito = new Credito();
        $credito->setClienteID($cliente->getID());
        $credito->setValor(12.3);
        $credito->setDetalhes($detalhes ?: "Crédito {$id}");
        return $credito;
    }

    /**
     * Create a crédito on database
     * @param string $detalhes Crédito detalhes
     * @return Credito
     */
    public static function create($detalhes = null)
    {
        $credito = self::build($detalhes);
        $credito->insert();
        return $credito;
    }

    public function testFind()
    {
        $credito = self::create();
        $condition = ['detalhes' => $credito->getDetalhes()];
        $found_credito = Credito::find($condition);
        $this->assertEquals($credito, $found_credito);
        list($found_credito) = Credito::findAll($condition, [], 1);
        $this->assertEquals($credito, $found_credito);
        $this->assertEquals(1, Credito::count($condition));
    }

    public function testFindCliente()
    {
        $credito = self::create();

        $cliente = $credito->findClienteID();
        $this->assertEquals($credito->getClienteID(), $cliente->getID());
    }

    public function testAdd()
    {
        $credito = self::build();
        $credito->insert();
        $this->assertTrue($credito->exists());
    }

    public function testAddInvalid()
    {
        $credito = new Credito();
        $credito->setValor(null);
        $credito->setDetalhes(null);
        $credito->setCancelado(null);
        try {
            $credito->insert();
            $this->fail('Não cadastrar com valores null');
        } catch (ValidationException $e) {
            $this->assertEquals(['clienteid', 'valor', 'detalhes', 'cancelado'], array_keys($e->getErrors()));
        }
        //---------------------------
        $credito = self::build();
        $credito->setCancelado('N');
        $credito->setValor(-100);
        try {
            $credito->insert();
            $this->fail('Não cadastrar');
        } catch (ValidationException $e) {
            $this->assertEquals(['valor'], array_keys($e->getErrors()));
        }
        //---------------------------
        $credito = self::build();
        $credito->setCancelado('Y');
        $credito->setValor(100);
        try {
            $credito->insert();
            $this->fail('Não cadastrar');
        } catch (ValidationException $e) {
            $this->assertEquals(['valor', 'cancelado'], array_keys($e->getErrors()));
        }
        //---------------------------
        $credito = self::build();
        $credito->setCancelado('Y');
        try {
            $credito->insert();
            $this->fail('Não cadastrar');
        } catch (ValidationException $e) {
            $this->assertEquals(['valor', 'cancelado'], array_keys($e->getErrors()));
        }
    }

    public function testUpdate()
    {
        $credito = self::create();
        $credito->update();
        $this->assertTrue($credito->exists());
    }

    public function testUpdateInvalid()
    {
        $old = self::create();
        $credito = self::build();
        $credito->setID($old->getID());
        $credito->setValor(55);
        try {
            $credito->update();
            $this->fail('Erro');
        } catch (ValidationException $e) {
            $this->assertEquals(['valor'], array_keys($e->getErrors()));
        }
        //---------------------------
        // $old = self::build();
        // $old->setValor(10);
        // $old->insert();
        // $credito = self::build();
        // $credito->setID(14);
        // try {
        //     $credito->update();
        //     $this->fail('Não atualizar');
        // } catch (ValidationException $e) {
        //     $this->assertEquals(['clienteid'], array_keys($e->getErrors()));
        // }
        //---------------
        // $old = self::build();
        // $old->isCancelado('Y');
        // $old->insert();
        // try {
        //     $old->setDetalhes('Teste credito já cancelado');
        //     $old->update();
        //     $this->fail('Não atualizar');
        // } catch (ValidationException $e) {
        //     $this->assertEquals(['clienteid'], array_keys($e->getErrors()));
        // }
    }

    public function testCancel()
    {
        $credito = self::create();
        $credito->cancel();
        $this->assertTrue($credito->isCancelado());
        $this->assertEquals('Y', $credito->getCancelado());
    }

    public function testDelete()
    {
        $credito = self::create();
        $credito->delete();
        $credito->loadByID();
        $this->assertFalse($credito->exists());
    }
}
