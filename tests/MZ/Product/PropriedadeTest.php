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
use MZ\Exception\ValidationException;

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

    public function testAbreviado()
    {
        $propriedade = self::build();
        $propriedade->setAbreviacao(null);
        $propriedade->insert();
        $desc = $propriedade->getAbreviado();
        $this->assertEquals($propriedade->getNome(), $desc);
        //----
        $propriedade1 = self::build();
        $propriedade1->setAbreviacao('P');
        $propriedade1->insert();
        $desc1 = $propriedade1->getAbreviado();
        $this->assertEquals($propriedade1->getAbreviacao(), $desc1);
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

    public function testFinds()
    {
        $propriedade = self::create();

        $grupo = $propriedade->findGrupoID();
        $this->assertEquals($propriedade->getGrupoID(), $grupo->getID());

        $prop = $propriedade->findByGrupoIDNome($grupo->getID(), $propriedade->getNome());
        $this->assertInstanceOf(get_class($propriedade), $prop);
    }

    public function testAddInvalid()
    {
        $propriedade = self::build();
        $propriedade->setGrupoID(null);
        $propriedade->setNome(null);
        try {
            $propriedade->insert();
            $this->fail('Não cadastrar com valores nulos');
        } catch (ValidationException $e) {
            $this->assertEquals(['grupoid', 'nome'], array_keys($e->getErrors()));
        }
    }

    public function testTranslate()
    {
        $propriedade = self::create();
        try {
            $propriedade->insert();
            $this->fail('Não cadastra fk duplicada');
        } catch (ValidationException $e) {
            $this->assertEquals(['grupoid', 'nome'], array_keys($e->getErrors()));
        }
    }

    public function testMakeImg()
    {
        $propriedade = new Propriedade();
        $this->assertEquals('/static/img/propriedade.png', $propriedade->makeImagemURL(true));
        $propriedade->setImagemURL('imagem.png');
        $this->assertEquals('/static/img/propriedade/imagem.png', $propriedade->makeImagemURL());
    }

    public function testClean()
    {
        $old = new Propriedade();
        $old->setImagemURL('propriedadeinexistente.png');
        $propriedade = new Propriedade();
        $propriedade->setImagemURL('propriedadeinexistente1.png');
        $propriedade->clean($old);
        $this->assertEquals($old, $propriedade);
    }
}
