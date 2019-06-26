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
use MZ\Wallet\CarteiraTest;
use MZ\Exception\ValidationException;

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
        $carteiraTeste = CarteiraTest::create();

        $cartao = new Cartao();
        $cartao->setCarteiraID($carteiraTeste->getID());
        $cartao->setFormaPagtoID($forma_pagto->getID());
        $cartao->setBandeira($bandeira ?: "Bandeira {$id}");
        $cartao->setTaxa(3.50);
        $cartao->setDiasRepasse(30);
        $cartao->setTaxaAntecipacao(6.39);
        $cartao->setAtivo('Y');
        return $cartao;
    }

    public function testTranslateFK()
    {
        $cartao = self::build();
        $cartao->insert();

        try {
            $cartao->insert();
            $this->fail('Não cadastrar com fk duplicada');
        } catch (ValidationException $e) {
            $this->assertEquals(['formapagtoid', 'bandeira'], array_keys($e->getErrors()));
        }
    }

    public function testInvalidPaymentMethod()
    {
        $cartao = self::build();
        $cartao->setFormaPagtoID(-1);
        $this->expectException('\Exception');
        $cartao->insert();
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

    public function testFindCarteiraId()
    {
        $cartao = self::create();
        $carteira = $cartao->findCarteiraID();
        $this->assertEquals($cartao->getCarteiraID(), $carteira->getID());
    }

    public function testFindByFormaPagtoIDBandeira()
    {
        $cartao = self::create();
        $forma_pagto = $cartao->findFormaPagtoID();
        $formaPagtoBandeira = $cartao->findByFormaPagtoIDBandeira($forma_pagto->getID(), $cartao->getBandeira());
        $this->assertInstanceOf(get_class($cartao), $formaPagtoBandeira);
    }

    public function testAdd()
    {
        $cartao = self::build();
        $cartao->insert();
        $this->assertTrue($cartao->exists());
    }

    public function testAddInvalid()
    {
        $cartao = new Cartao();
        $cartao->setFormaPagtoID(null);
        $cartao->setBandeira(null);
        $cartao->setTaxa(null);
        $cartao->setDiasRepasse(null);
        $cartao->setTaxaAntecipacao(null);
        $cartao->setAtivo(null);
        try {
            $cartao->insert();
            $this->fail('Pegando fogo no banco');
        } catch (ValidationException $e) {
            $this->assertEquals(['formapagtoid', 'bandeira', 'taxa', 'diasrepasse', 'taxaantecipacao', 'ativo'], array_keys($e->getErrors()));
        }
        //else if do validate
        $cartao->setTaxa(-1);
        $cartao->setDiasRepasse(-1);
        $cartao->setTaxaAntecipacao(-1);
        try {
            $cartao->insert();
            $this->fail('Não pode cadastrar');
        } catch (ValidationException $ex) {
            $this->assertEquals(['formapagtoid', 'bandeira', 'taxa', 'diasrepasse', 'taxaantecipacao', 'ativo'], array_keys($ex->getErrors()));
        }
    }

    public function testMakeImagemURL()
    {
        $cartao = new Cartao();
        $teste = $cartao->makeImagemURL(true);
        $this->assertEquals('/static/img/cartao.png', $cartao->makeImagemURL(true));
        $cartao->setImagemURL('imagem.png');
        $this->assertEquals('/static/img/cartao/imagem.png', $cartao->makeImagemURL());
    }

    public function testClean()
    {
        $old_cartao = new Cartao();
        $old_cartao->setImagemURL('cartoesnaoexistente2.png');
        $cartao = new Cartao();
        $cartao->setImagemURL('cartaonaoexistente.png');
        $cartao->clean($old_cartao);
        $this->assertEquals($old_cartao, $cartao);
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
