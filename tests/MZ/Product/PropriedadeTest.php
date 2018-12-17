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
namespace MZ\Product;

use MZ\Product\GrupoTest;

class PropriedadeTest extends \MZ\Framework\TestCase
{
    /**
     * Build a valid propriedade
     * @param string $nome Propriedade nome
     * @return Propriedade
     */
    public static function build($nome = null)
    {
        $last = Propriedade::find([], ['id' => -1]);
        $id = $last->getID() + 1;
        $grupo = GrupoTest::create();
        $propriedade = new Propriedade();
        $propriedade->setGrupoID($grupo->getID());
        $propriedade->setNome($nome ?: "Propriedade {$id}");
        return $propriedade;
    }

    /**
     * Create a propriedade on database
     * @param string $nome Propriedade nome
     * @return Propriedade
     */
    public static function create($nome = null)
    {
        $propriedade = self::build($nome);
        $propriedade->insert();
        return $propriedade;
    }

    public function testFind()
    {
        $propriedade = self::create();
        $condition = ['nome' => $propriedade->getNome()];
        $found_propriedade = Propriedade::find($condition);
        $this->assertEquals($propriedade, $found_propriedade);
        list($found_propriedade) = Propriedade::findAll($condition, [], 1);
        $this->assertEquals($propriedade, $found_propriedade);
        $this->assertEquals(1, Propriedade::count($condition));
    }

    public function testAdd()
    {
        $propriedade = self::build();
        $propriedade->insert();
        $this->assertTrue($propriedade->exists());
    }

    public function testUpdate()
    {
        $propriedade = self::create();
        $propriedade->update();
        $this->assertTrue($propriedade->exists());
    }

    public function testDelete()
    {
        $propriedade = self::create();
        $propriedade->delete();
        $propriedade->loadByID();
        $this->assertFalse($propriedade->exists());
    }
}
