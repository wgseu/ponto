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

use MZ\Session\MovimentacaoTest;
use MZ\Exception\ValidationException;

class ResumoTest extends \MZ\Framework\TestCase
{
    /**
     * Build a valid resumo
     * @return Resumo
     */
    public static function build()
    {
        $last = Resumo::find([], ['id' => -1]);
        $id = $last->getID() + 1;
        $movimentacao = MovimentacaoTest::create();
        $resumo = new Resumo();
        $resumo->setMovimentacaoID($movimentacao->getID());
        $resumo->setTipo(Resumo::TIPO_DINHEIRO);
        $resumo->setValor(12.3);
        return $resumo;
    }

    /**
     * Create a resumo on database
     * @return Resumo
     */
    public static function create()
    {
        $resumo = self::build();
        $resumo->insert();
        return $resumo;
    }

    public function testFind()
    {
        $resumo = self::create();
        $condition = ['movimentacaoid' => $resumo->getMovimentacaoID()];
        $found_resumo = Resumo::find($condition);
        $this->assertEquals($resumo, $found_resumo);
        list($found_resumo) = Resumo::findAll($condition, [], 1);
        $this->assertEquals($resumo, $found_resumo);
        $this->assertEquals(1, Resumo::count($condition));
        $movimentacao = $resumo->findMovimentacaoID();
        MovimentacaoTest::close($movimentacao);
    }

    public function testAdd()
    {
        $resumo = self::build();
        $resumo->insert();
        $this->assertTrue($resumo->exists());
        $movimentacao = $resumo->findMovimentacaoID();
        MovimentacaoTest::close($movimentacao);
    }

    public function testAddInvalid()
    {
        $resumo = self::build();
        $movimentacao = $resumo->findMovimentacaoID();
        $resumo->setMovimentacaoID(null);
        $resumo->setTipo('Tipo inválido');
        $resumo->setValor(null);
        try {
            $resumo->insert();
            $this->fail('Não cadastrar com valores inválidos');
        } catch (ValidationException $e) {
            $this->assertEquals(['movimentacaoid', 'tipo', 'valor'], array_keys($e->getErrors()));
        }
        MovimentacaoTest::close($movimentacao);
    }

    public function testTranslate()
    {
        $resumo = self::build();
        $resumo->setCartaoID(2);
        $resumo->insert();
        try {
            $resumo->insert();
            $this->fail('Fk duplicada');
        } catch (ValidationException $e) {
            $this->assertEquals(['movimentacaoid', 'tipo', 'cartaoid'], array_keys($e->getErrors()));
        }
        $movimentacao = $resumo->findMovimentacaoID();
        MovimentacaoTest::close($movimentacao);
    }

    public function testFinds()
    {
        $resumo = self::build();
        $resumo->setCartaoID(2);
        $resumo->insert();

        $movimentacao = $resumo->findMovimentacaoID();
        $this->assertEquals($resumo->getMovimentacaoID(), $movimentacao->getID());

        $cartao = $resumo->findCartaoID();
        $this->assertEquals($resumo->getCartaoID(), $cartao->getID());

        $resumoFound = Resumo::findByMovimentacaoIDTipoCartaoID($movimentacao->getID(), $resumo->getTipo(), $cartao->getID());
        $this->assertInstanceOf(get_class($resumo), $resumoFound);
        $movimentacao = $resumo->findMovimentacaoID();
        MovimentacaoTest::close($movimentacao);
    }

    public function testGetTipo()
    {
        $resumo = new Resumo(['tipo' => Resumo::TIPO_VALE]);
        $options = Resumo::getTipoOptions();
        $this->assertEquals(Resumo::getTipoOptions($resumo->getTipo()), $options[$resumo->getTipo()]);
    }

    public function testUpdate()
    {
        $resumo = self::create();
        $resumo->update();
        $this->assertTrue($resumo->exists());
        $movimentacao = $resumo->findMovimentacaoID();
        MovimentacaoTest::close($movimentacao);
    }

    public function testDelete()
    {
        $resumo = self::create();
        $movimentacao = $resumo->findMovimentacaoID();
        $resumo->delete();
        $resumo->loadByID();
        $this->assertFalse($resumo->exists());
        MovimentacaoTest::close($movimentacao);
    }
}
