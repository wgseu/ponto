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

use MZ\Wallet\CarteiraTest;
use MZ\Wallet\MoedaTest;

class PagamentoTest extends \MZ\Framework\TestCase
{
    /**
     * Build a valid pagamento
     * @param string $detalhes detalhes do pagamento
     * @return Pagamento
     */
    public static function build($detalhes = null)
    {
        $last = Pagamento::find([], ['id' => -1]);
        $id = $last->getID() + 1;
        $carteira = CarteiraTest::create();
        $moeda = MoedaTest::create();
        $pagamento = new Pagamento();
        $pagamento->setCarteiraID($carteira->getID());
        $pagamento->setMoedaID($moeda->getID());
        $pagamento->setValor(12.3);
        $pagamento->setNumeroParcela(1);
        $pagamento->setParcelas(3);
        $pagamento->setLancado(12.3);
        $pagamento->setDetalhes($detalhes);
        $pagamento->setDataCompensacao('2016-12-25 12:15:00');
        return $pagamento;
    }

    /**
     * Create a pagamento on database
     * @param string $detalhes detalhes do pagamento
     * @return Pagamento
     */
    public static function create($detalhes = null)
    {
        $pagamento = self::build($detalhes);
        $pagamento->insert();
        return $pagamento;
    }

    public function testFind()
    {
        $pagamento = self::create('Pagamento de teste');
        $condition = ['search' => $pagamento->getDetalhes()];
        $found_pagamento = Pagamento::find($condition);
        $this->assertEquals($pagamento, $found_pagamento);
        list($found_pagamento) = Pagamento::findAll($condition, [], 1);
        $this->assertEquals($pagamento, $found_pagamento);
        $this->assertEquals(1, Pagamento::count($condition));
    }

    public function testAdd()
    {
        $pagamento = self::build();
        $pagamento->insert();
        $this->assertTrue($pagamento->exists());
    }

    public function testUpdate()
    {
        $pagamento = self::create();
        $pagamento->update();
        $this->assertTrue($pagamento->exists());
    }

    public function testDelete()
    {
        $pagamento = self::create();
        $pagamento->delete();
        $pagamento->loadByID();
        $this->assertFalse($pagamento->exists());
    }
}
