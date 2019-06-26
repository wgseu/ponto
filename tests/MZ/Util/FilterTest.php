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
namespace MZ\Util;

class FilterTest extends \MZ\Framework\TestCase
{
    public function testConcatKeys()
    {
        $this->assertEquals(
            ['pre.b' => null, 'pre.a' => 3, 'pre.c' => 0],
            Filter::concatKeys('pre.', ['b' => null, 'a' => 3, 'c' => 0])
        );
        $this->assertEquals(
            ['b.pos' => null, 'a.pos' => 3, 'c.pos' => 0],
            Filter::concatKeys('', ['b' => null, 'a' => 3, 'c' => 0], '.pos')
        );
        $this->assertEquals(
            ['pre.b.pos' => null, 'pre.a.pos' => 3, 'pre.c.pos' => 0],
            Filter::concatKeys('pre.', ['b' => null, 'a' => 3, 'c' => 0], '.pos')
        );
    }

    public function testKeys()
    {
        $this->assertEquals(
            ['b' => null, 'c' => 0],
            Filter::keys(
                ['b' => null, 'a' => 3, 'c' => 0],
                ['c' => true, 'b' => true]
            )
        );
    }

    public function testOrder()
    {
        $this->assertEquals(
            ['b' => -1, 'c' => 1, 'a' => 0],
            Filter::order('b:desc,c:asc,a')
        );
        $this->assertEquals(
            ['b' => -1, 'c' => 1],
            Filter::order(['b' => -1, 'c' => 1])
        );
        $this->assertEquals(
            [],
            Filter::order('')
        );
    }

    public function testFloat()
    {
        $this->assertEquals(null, Filter::float(''));
    }

    public function testName()
    {
        $this->assertEquals(null, Filter::name(null));
        $this->assertEquals('Testinho', Filter::name('tEstInho'));
    }

    public function testTime()
    {
        $this->assertEquals('2010-04-12', Filter::time('12-04-2010'));
        $this->assertEquals(null, Filter::time(null));
    }

    public function testValues()
    {
        $this->assertEquals([null], Filter::values(['']));
        $this->assertEquals(['teste' => 'testinho'], Filter::values(['teste' => 'testinho']));
    }

}
