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

use MZ\Payment\FormaPagtoTest;

class CartaoTest extends \MZ\Framework\TestCase
{
    /**
     * Build a valid cartão
     * @param string $bandeira Cartão bandeira
     * @return Cartao
     */
    public static function build($bandeira = null)
    {
        $last = Cartao::find([], ['id' => -1]);
        $id = $last->getID() + 1;
        $forma_pagto = FormaPagtoTest::create();
        $cartao = new Cartao();
        $cartao->setFormaPagtoID($forma_pagto->getID());
        $cartao->setBandeira($bandeira ?: "Bandeira {$id}");
        $cartao->setTaxa(3.50);
        $cartao->setDiasRepasse(30);
        $cartao->setTaxaAntecipacao(6.39);
        $cartao->setAtivo('Y');
        return $cartao;
    }

    /**
     * Create a cartão on database
     * @param string $bandeira Cartão bandeira
     * @return Cartao
     */
    public static function create($bandeira = null)
    {
        $cartao = self::build($bandeira);
        $cartao->insert();
        return $cartao;
    }

    public function testFind()
    {
        $cartao = self::create();
        $condition = ['bandeira' => $cartao->getBandeira()];
        $found_cartao = Cartao::find($condition);
        $this->assertEquals($cartao, $found_cartao);
        list($found_cartao) = Cartao::findAll($condition, [], 1);
        $this->assertEquals($cartao, $found_cartao);
        $this->assertEquals(1, Cartao::count($condition));
    }

    public function testAdd()
    {
        $cartao = self::build();
        $cartao->insert();
        $this->assertTrue($cartao->exists());
    }

    public function testUpdate()
    {
        $cartao = self::create();
        $cartao->update();
        $this->assertTrue($cartao->exists());
    }

    public function testDelete()
    {
        $cartao = self::create();
        $cartao->delete();
        $cartao->loadByID();
        $this->assertFalse($cartao->exists());
    }
}
