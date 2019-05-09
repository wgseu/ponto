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
namespace MZ\Promotion;

use MZ\Product\ProdutoTest;
use MZ\Product\CategoriaTest;
use MZ\Util\Date;
use MZ\Exception\ValidationException;

class PromocaoTest extends \MZ\Framework\TestCase
{
    /**
     * Build a valid promoção
     * @return Promocao
     */
    public static function build()
    {
        $last = Promocao::find([], ['id' => -1]);
        $id = $last->getID() + 1;
        $produto = ProdutoTest::create();
        $promocao = new Promocao();
        $promocao->setProdutoID($produto->getID());
        $promocao->setInicio(Date::MINUTES_PER_DAY + $id * 20);
        $promocao->setFim($promocao->getInicio() + 10);
        $promocao->setValor(12.3);
        $promocao->setPontos(123);
        $promocao->setParcial('Y');
        $promocao->setProibir('N');
        $promocao->setEvento('N');
        $promocao->setAgendamento('N');
        return $promocao;
    }

    /**
     * Create a promoção on database
     * @return Promocao
     */
    public static function create()
    {
        $promocao = self::build();
        $promocao->insert();
        return $promocao;
    }

    public function testFind()
    {
        $promocao = self::create();
        $condition = ['produtoid' => $promocao->getProdutoID()];
        $found_promocao = Promocao::find($condition);
        $this->assertEquals($promocao, $found_promocao);
        list($found_promocao) = Promocao::findAll($condition, [], 1);
        $this->assertEquals($promocao, $found_promocao);
        $this->assertEquals(1, Promocao::count($condition));
    }

    public function testFinds()
    {
        $promocao = self::create();

        $categoria = $promocao->findCategoriaID();
        $this->assertEquals($promocao->getCategoriaID(), $categoria->getID());

        $produto = $promocao->findProdutoID();
        $this->assertEquals($promocao->getProdutoID(), $produto->getID());

        $servico = $promocao->findServicoID();
        $this->assertEquals($promocao->getServicoID(), $servico->getID());

        $bairro = $promocao->findBairroID();
        $this->assertEquals($promocao->getBairroID(), $bairro->getID());

        $zona = $promocao->findZonaID();
        $this->assertEquals($promocao->getZonaID(), $zona->getID());

        $integracao = $promocao->findIntegracaoID();
        $this->assertEquals($promocao->getIntegracaoID(), $integracao->getID());
    }

    public function testAdd()
    {
        $promocao = self::build();
        $promocao->insert();
        $this->assertTrue($promocao->exists());
    }

    public function testAddInvalid()
    {
        $promocao = self::build();
        $promocao->setInicio(null);
        $promocao->setFim(null);
        $promocao->setValor(null);
        $promocao->setPontos(null);
        $promocao->setParcial(null);
        $promocao->setProibir(null);
        $promocao->setEvento(null);
        $promocao->setAgendamento(null);
        try {
            $promocao->insert();
            $this->fail('Não cadastrar com valores nulos');
        } catch (ValidationException $e) {
            $this->assertEquals(['inicio', 'fim', 'valor', 'pontos', 'parcial', 'proibir', 'evento', 'agendamento'], array_keys($e->getErrors()));
        }
        //----------------
        $promocao = self::build();
        $promocao->setServicoID(null);
        $promocao->setBairroID(1);
        try {
            $promocao->insert();
            $this->fail('Não cadastrar com serviço nulo');
        } catch (ValidationException $e) {
            $this->assertEquals(['servicoid'], array_keys($e->getErrors()));
        }
        //----------------
        $promocao = self::build();
        $promocao->setServicoID(1);
        try {
            $promocao->insert();
            $this->fail('O serviço da promoção não pode ser desconto');
        } catch (ValidationException $e) {
            $this->assertEquals(['servicoid'], array_keys($e->getErrors()));
        }
        //----------------
        $promocao = self::build();
        $promocao->setZonaID(1);
        $promocao->setBairroID(null);
        try {
            $promocao->insert();
            $this->fail('O bairro da zona deve ser informado');
        } catch (ValidationException $e) {
            $this->assertEquals(['bairroid'], array_keys($e->getErrors()));
        }
        //---------------------
        $promocao = self::build();
        $promocao->setCategoriaID(null);
        $promocao->setProdutoID(null);
        $promocao->setServicoID(null);
        try {
            $promocao->insert();
            $this->fail('Nenhuma promo selecionada');
        } catch (ValidationException $e) {
            $this->assertEquals(['produtoid'], array_keys($e->getErrors()));
        }
    }

    public function testUpdate()
    {
        $promocao = self::create();
        $promocao->update();
        $this->assertTrue($promocao->exists());
    }

    public function testMakeBanner()
    {
        $promocao = new Promocao();
        $this->assertEquals('/static/img/promocao.png', $promocao->makeBannerURL(true));
        $promocao->setBannerURL('imagem.png');
        $this->assertEquals('/static/img/promocao/imagem.png', $promocao->makeBannerURL());
    }

    public function testClean()
    {
        $old = new Promocao();
        $old->setBannerURL('promocaofake.png');
        $promocao = new Promocao();
        $promocao->setBannerURL('promocaofake1.png');
        $promocao->clean($old);
        $this->assertEquals($old, $promocao);
    }

    public function testLoadByProdutoID()
    {
        $promocao = self::create();
        $promoProduto = $promocao->loadByProdutoID();
        $this->assertInstanceOf(get_class($promocao), $promoProduto);
        //--------------------
        $promocao = self::build();
        $promocao->setProdutoID(null);
        $promoProduto = $promocao->loadByProdutoID();
    }

    public function testDelete()
    {
        $promocao = self::create();
        $promocao->delete();
        $promocao->loadByID();
        $this->assertFalse($promocao->exists());
    }

    public function testMultipleSelections()
    {
        $promocao = self::build();
        $promocao->setCategoriaID(CategoriaTest::create([])->getID());
        $this->expectException('\MZ\Exception\ValidationException');
        $promocao->insert();
    }

    public function testInvalidInterval()
    {
        $promocao = self::build();
        $inicio = $promocao->getInicio();
        $promocao->setInicio($promocao->getFim());
        $promocao->setFim($inicio);
        $this->expectException('\MZ\Exception\ValidationException');
        $promocao->insert();
    }

    public function testInvalidStart()
    {
        $promocao = self::build();
        $promocao->setInicio(1439);
        $this->expectException('\MZ\Exception\ValidationException');
        $promocao->insert();
    }

    public function testInvalidEnd()
    {
        $promocao = self::build();
        $promocao->setFim(Date::MINUTES_PER_DAY * 8);
        $this->expectException('\MZ\Exception\ValidationException');
        $promocao->insert();
    }

    public function testExistingInto()
    {
        $promocao = self::create();
        $promocao->setInicio($promocao->getInicio() - 1);
        $promocao->setFim($promocao->getFim() + 1);
        $this->expectException('\MZ\Exception\ValidationException');
        $promocao->insert();
    }

    public function testExistingBefore()
    {
        $promocao = self::create();
        $promocao->setInicio($promocao->getInicio() - 1);
        $promocao->setFim($promocao->getFim() - 1);
        $this->expectException('\MZ\Exception\ValidationException');
        $promocao->insert();
    }

    public function testExistingAfter()
    {
        $promocao = self::create();
        $promocao->setInicio($promocao->getInicio() + 1);
        $promocao->setFim($promocao->getFim() + 1);
        $this->expectException('\MZ\Exception\ValidationException');
        $promocao->insert();
    }

    public function testExistingExternal()
    {
        $promocao = self::create();
        $promocao->setInicio($promocao->getInicio() + 1);
        $promocao->setFim($promocao->getFim() - 1);
        $this->expectException('\MZ\Exception\ValidationException');
        $promocao->insert();
    }
}
