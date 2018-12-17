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

    public function testAdd()
    {
        $movimentacao = self::build();
        $movimentacao->insert();
        $this->assertTrue($movimentacao->exists());
        self::close($movimentacao);
    }

    public function testUpdate()
    {
        $movimentacao = self::create();
        $movimentacao->update();
        $this->assertTrue($movimentacao->exists());
        self::close($movimentacao);
    }

    public function testDelete()
    {
        $movimentacao = self::create();
        self::close($movimentacao);
        $movimentacao->delete();
        $movimentacao->loadByID();
        $this->assertFalse($movimentacao->exists());
    }
}
