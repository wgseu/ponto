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

    public function testAdd()
    {
        $credito = self::build();
        $credito->insert();
        $this->assertTrue($credito->exists());
    }

    public function testUpdate()
    {
        $credito = self::create();
        $credito->update();
        $this->assertTrue($credito->exists());
    }

    public function testDelete()
    {
        $credito = self::create();
        $credito->delete();
        $credito->loadByID();
        $this->assertFalse($credito->exists());
    }
}
