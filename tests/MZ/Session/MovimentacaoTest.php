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

namespace MZ\Session;

use MZ\Session\SessaoTest;
use MZ\Session\CaixaTest;
use MZ\Provider\PrestadorTest;
use MZ\Database\DB;
use MZ\Exception\ValidationException;
use MZ\Sale\PedidoTest;

class MovimentacaoTest extends \MZ\Framework\TestCase
{
    /**
     * Build a valid movimentação
     * @return Movimentacao
     */
    public static function build()
    {
        $last = Movimentacao::find([], ['id' => -1]);
        $id = $last->getID() + 1;
        $sessao = SessaoTest::create();
        $caixa = CaixaTest::create();
        $prestador = PrestadorTest::create();
        $movimentacao = new Movimentacao();
        $movimentacao->setSessaoID($sessao->getID());
        $movimentacao->setCaixaID($caixa->getID());
        $movimentacao->setAberta('Y');
        $movimentacao->setIniciadorID($prestador->getID());
        $movimentacao->setDataAbertura('2016-12-25 12:15:00');
        return $movimentacao;
    }

    /**
     * Create a movimentação on database
     * @return Movimentacao
     */
    public static function create()
    {
        $movimentacao = self::build();
        $movimentacao->insert();
        return $movimentacao;
    }

    /**
     * Encerra a movimentação do caixa
     * @param Movimentacao $movimentacao
     */
    public static function close($movimentacao)
    {
        $movimentacao->setAberta('N');
        $movimentacao->setFechadorID($movimentacao->getIniciadorID());
        $movimentacao->setDataFechamento(DB::now());
        $movimentacao->update();
        SessaoTest::close($movimentacao->findSessaoID());
    }

    public function testFind()
    {
        $movimentacao = self::create();
        $condition = ['caixaid' => $movimentacao->getCaixaID()];
        $found_movimentacao = Movimentacao::find($condition);
        $this->assertEquals($movimentacao, $found_movimentacao);
        list($found_movimentacao) = Movimentacao::findAll($condition, [], 1);
        $this->assertEquals($movimentacao, $found_movimentacao);
        $this->assertEquals(1, Movimentacao::count($condition));
        self::close($movimentacao);
    }

    public function testAddNull()
    {
        $movimentacao = self::build();
        $sessao = $movimentacao->findSessaoID();

        $movimentacao->setSessaoID(null);
        $movimentacao->setCaixaID(null);
        $movimentacao->setAberta('T');
        $movimentacao->setIniciadorID(null);
        $movimentacao->setDataAbertura(null);
        try {
            $movimentacao->insert();
            $this->fail('Valores nulos');
        } catch (ValidationException $e) {
            $this->assertEquals(
                ['sessaoid', 'caixaid', 'aberta', 'iniciadorid', 'fechadorid', 'dataabertura', 'datafechamento'],
                array_keys($e->getErrors())
            );
        }
        SessaoTest::close($sessao);
    }

    public function testAddInvalid()
    {
        $movimentacao = new Movimentacao();

        $sessao = SessaoTest::create();
        SessaoTest::close($sessao);

        $caixa = CaixaTest::create();
        $caixa->setAtivo('N');
        $caixa->update();

        $prestador = PrestadorTest::create();
        $prestador->setDataTermino(DB::now());
        $prestador->setAtivo('N');
        $prestador->update();

        $movimentacao->setSessaoID($sessao->getID());
        $movimentacao->setCaixaID($caixa->getID());
        $movimentacao->setIniciadorID($prestador->getID());
        $movimentacao->setAberta('Y');
        $movimentacao->setDataAbertura('2016-12-25 12:15:00');
        try {
            $movimentacao->insert();
            $this->fail('Não cadastrar');
        } catch (ValidationException $e) {
            $this->assertEquals(['sessaoid', 'caixaid', 'iniciadorid'], array_keys($e->getErrors()));
        }
    }

    public function testReabrirCaixa()
    {
        $movimentacao = self::create();
        self::close($movimentacao);
        try {
            $movimentacao->setAberta('Y');
            //$old->exists() && $old->getSessaoID() != $this->getSessaoID()
            $movimentacao->setSessaoID(4);
            $movimentacao->update();
        } catch (ValidationException $e) {
            $this->assertEquals(['id', 'sessaoid', 'aberta', 'fechadorid', 'datafechamento'], array_keys($e->getErrors()));
        }
    }

    public function testAddMovimentacaoFechada()
    {
        $movimentacao = self::build();
        $movimentacao->setAberta('N');
        $movimentacao->setFechadorID($movimentacao->getIniciadorID());
        $movimentacao->setDataFechamento(DB::now());
        try {
            $movimentacao->insert();
            $this->fail('Não criar movimentação fechada');
        } catch (ValidationException $e) {
            $this->assertEquals(['aberta'], array_keys($e->getErrors()));
        }
    }

    public function testMudarFuncionario()
    {
        $movimentacao = self::create();
        try {
            $movimentacao->setIniciadorID(5);
            $movimentacao->update();
            $this->fail('Não pode alterar o funcionário que abriu o caixa');
        } catch (ValidationException $e) {
            $this->assertEquals(['iniciadorid'], array_keys($e->getErrors()));
        }
        $movimentacaoClose = Movimentacao::find(['id' => $movimentacao->getID()]);
        self::close($movimentacaoClose);
    }

    public function testAlterarDtAbertura()
    {
        $movimentacao = self::create();
        try {
            $movimentacao->setDataAbertura(DB::now());
            $movimentacao->update();
            $this->fail('Não alterar a data de abertura do caixa');
        } catch (ValidationException $e) {
            $this->assertEquals(['dataabertura'], array_keys($e->getErrors()));
        }
        $movimentacaoClose = Movimentacao::find(['id' => $movimentacao->getID()]);
        self::close($movimentacaoClose);
    }

    public function testFindFechador()
    {
        $movimentacao = self::create();
        $fechador = $movimentacao->findFechadorID();
        $this->assertEquals($movimentacao->getFechadorID(), $fechador->getID());
        self::close($movimentacao);
    }

    public function testFindByAberta()
    {
        $movimentacao = self::create();
        $movimentacaoFound = Movimentacao::findByAberta($movimentacao->getIniciadorID());

        $this->assertInstanceOf(get_class($movimentacao), $movimentacaoFound);
        self::close($movimentacao);
    }
}
